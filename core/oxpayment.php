<?php
/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.oxid-esales.com
 * @package core
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxpayment.php 20457 2009-06-25 13:21:33Z vilma $
 */

/**
 * Payment manager.
 * Performs mayment methods, such as assigning to someone, returning value etc.
 * @package core
 */
class oxPayment extends oxI18n
{
    /**
     * User groups object (default null).
     *
     * @var object
     */
    protected $_oGroups = null;

    /**
     * Countries assigned to current payment. Value from outside accessible
     * by calling oxpayment::getCountries
     *
     * @var array
     */
    protected $_aCountries = null;

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxpayment';

    /**
     * current dyn values
     *
     * @var array
     */
    protected $_aDynValues = null;

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxpayments' );
    }

    /**
     * Payment groups getter. Returns groups list
     *
     * @return oxlist
     */
    public function getGroups()
    {
        if ( $this->_oGroups == null && $sOxid = $this->getId() ) {

            // usergroups
            $this->_oGroups = oxNew( 'oxlist', 'oxgroups' );

            // performance
            $sSelect  = 'select oxgroups.* from oxgroups, oxobject2group ';
            $sSelect .= "where oxobject2group.oxobjectid = '$sOxid' ";
            $sSelect .= 'and oxobject2group.oxgroupsid=oxgroups.oxid ';
            $this->_oGroups->selectString( $sSelect );
        }

        return $this->_oGroups;
    }

    /**
     * sets the dyn values
     *
     * @param array $aDynValues the array of dy values
     *
     * @return null
     */
    public function setDynValues( $aDynValues )
    {
        $this->_aDynValues = $aDynValues;
    }

    /**
     * sets a single dyn value
     *
     * @param mixed $oKey the key
     * @param mixed $oVal the value
     *
     * @return null
     */
    public function setDynValue( $oKey, $oVal )
    {
        $this->_aDynValues[$oKey] = $oVal;
    }

    /**
     * Returns an array of dyn payment values
     *
     * @return array
     */
    public function getDynValues()
    {
        if ( !$this->_aDynValues ) {
            $sRawDynValue = null;
            if ( is_object($this->oxpayments__oxvaldesc ) ) {
                $sRawDynValue = $this->oxpayments__oxvaldesc->getRawValue();
            }

            $this->_aDynValues = oxUtils::getInstance()->assignValuesFromText( $sRawDynValue );
        }
        return $this->_aDynValues;
    }

    /**
     * Returns additional taxes to base article price.
     *
     * @param double $dBaseprice Base article price
     *
     * @return double
     */
    public function getPaymentValue( $dBaseprice )
    {
        $dRet = 0;

        if ( $this->oxpayments__oxaddsumtype->value == "%") {
            $dRet = $dBaseprice * $this->oxpayments__oxaddsum->value/100;
        } else {
            $oCur = $this->getConfig()->getActShopCurrencyObject();
            $dRet = $this->oxpayments__oxaddsum->value * $oCur->rate;
        }

        if ( ($dRet * -1 ) > $dBaseprice ) {
            $dRet = $dBaseprice;
        }

        return $dRet;
    }

    /**
     * Returns price object for current payment applied on basket
     *
     * @param oxuserbasket $oBasket session basket
     *
     * @return oxprice
     */
    public function getPaymentPrice( $oBasket )
    {
        $dBasketPrice = $oBasket->getDiscountProductsPrice()->getBruttoSum();
        $dPrice = $this->getPaymentValue( $dBasketPrice );

        // calculating total price
        $oPrice = oxNew( 'oxPrice' );
        $oPrice->setBruttoPriceMode();
        $oPrice->setPrice( $dPrice );

        if ( $this->getConfig()->getConfigParam( 'blCalcVATForPayCharge' ) && $dPrice > 0 ) {
            $oPrice->setVat( $oBasket->getMostUsedVatPercent() );
        }

        return $oPrice;
    }

    /**
     * Returns array of country Ids which are assigned to current payment
     *
     * @return array
     */
    public function getCountries()
    {
        if ( $this->_aCountries === null ) {

            $this->_aCountries = array();
            $sSelect = 'select oxobjectid from oxobject2payment where oxpaymentid="'.$this->getId().'" and oxtype = "oxcountry" ';
            $rs = oxDb::getDb()->Execute( $sSelect );
            if ( $rs && $rs->recordCount()) {
                while ( !$rs->EOF ) {
                    $this->_aCountries[] = $rs->fields[0];
                    $rs->moveNext();
                }
            }
        }
        return $this->_aCountries;
    }

    /**
     * Delete this object from the database, returns true on success.
     *
     * @param string $sOXID Object ID(default null)
     *
     * @return bool
     */
    public function delete( $sOXID = null )
    {
        if ( parent::delete( $sOXID ) ) {

            $sOXID = $sOXID?$sOXID:$this->getId();

            // deleting payment related data
            $rs = oxDb::getDb()->execute( "delete from oxobject2payment where oxpaymentid = '$sOXID' " );
            return $rs->EOF;
        }

        return false;
    }

    /**
     * Function checks if loaded payment is valid to current basket
     *
     * @param array  $aDynvalue    dynamical value (in this case oxidcreditcard and oxiddebitnote are checked only)
     * @param string $sShopId      id of current shop
     * @param oxuser $oUser        the current user
     * @param double $dBasketPrice the current basket price (oBasket->dprice)
     * @param string $sShipSetId   the current ship set
     *
     * @return bool true if payment is valid
     */
    public function isValidPayment( $aDynvalue, $sShopId, $oUser, $dBasketPrice, $sShipSetId )
    {
        if ( $this->oxpayments__oxid->value == 'oxempty' ) {
            return true;
        }

        $oValidator = oxNew( 'oxinputvalidator' );
        if ( !$oValidator->validatePaymentInputData( $this->oxpayments__oxid->value, $aDynvalue ) ) {
            return false;
        }

        $oCur = $this->getConfig()->getActShopCurrencyObject();
        $dBasketPrice = $dBasketPrice / $oCur->rate;

        if ( $sShipSetId ) {
            $aPaymentList = oxPaymentList::getInstance()->getPaymentList( $sShipSetId, $dBasketPrice, $oUser );

            if ( !array_key_exists( $this->getId(), $aPaymentList ) ) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }
}
