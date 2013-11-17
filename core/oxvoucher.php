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
 * $Id: oxvoucher.php 16303 2009-02-05 10:23:41Z rimvydas.paskevicius $
 */

/**
 * Voucher manager.
 * Performs deletion, generating, assigning to group and other voucher
 * managing functions.
 * @package core
 */
class oxVoucher extends oxBase
{

    protected $_oSerie = null;

    /**
     * Vouchers doesnt need shop id check as this couses problems with
     * inherider vouchers. Voucher validity check is made by oxvoucher::getVoucherByNr()
     * @var bool
     */
    protected $_blDisableShopCheck = true;

    /**
     * @var string Name of current class
     */
    protected $_sClassName = 'oxvoucher';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxvouchers' );
    }

    /**
     * Gets voucher from db by given number.
     *
     * @param string $sVoucherNr         Voucher number
     * @param array  $aVouchers          Array of available vouchers (default array())
     * @param bool   $blCheckavalability check if voucher is still reserver od not
     *
     * @throws oxVoucherException exception
     *
     * @return mixed
     */
    public function getVoucherByNr( $sVoucherNr, $aVouchers = array(), $blCheckavalability = false )
    {
        $oRet = null;
        if ( !is_null( $sVoucherNr ) ) {

            $sViewName = $this->getViewName();
            $sSeriesViewName = getViewName( 'oxvoucherseries' );

            $sQ  = "select {$sViewName}.* from {$sViewName}, {$sSeriesViewName} where
                        {$sSeriesViewName}.oxid = {$sViewName}.oxvoucherserieid and
                        {$sViewName}.oxvouchernr = '{$sVoucherNr}' and ";

            if ( is_array( $aVouchers ) ) {
                foreach ( $aVouchers as $sVoucherId => $sSkipVoucherNr ) {
                    $sQ .= "{$sViewName}.oxid != '$sVoucherId' and ";
                }
            }
            $sQ .= "( {$sViewName}.oxorderid is NULL || {$sViewName}.oxorderid = '' ) ";

            //voucher timeout for 3 hours
            if ( $blCheckavalability ) {
                $iTime = time() - 3600 * 3;
                $sQ .= " and {$sViewName}.oxreserved < '{$iTime}' ";
            }

            $sQ .= " limit 1";

            if ( ! ( $oRet = $this->assignRecord( $sQ ) ) ) {
                $oEx = oxNew( 'oxVoucherException' );
                $oEx->setMessage( 'EXCEPTION_VOUCHER_NOVOUCHER' );
                $oEx->setVoucherNr( $sVoucherNr );
                throw $oEx;
            }
        }

        return $oRet;
    }

    /**
     * marks voucher as used
     *
     * @param string $sOrderId order id
     * @param string $sUserId  user id
     *
     * @return null
     */
    public function markAsUsed( $sOrderId, $sUserId )
    {
        //saving oxreserved field
        if ( $this->oxvouchers__oxid->value ) {
            $this->oxvouchers__oxorderid->setValue($sOrderId);
            $this->oxvouchers__oxuserid->setValue($sUserId);
            $this->oxvouchers__oxdateused->setValue(date( "Y-m-d", oxUtilsDate::getInstance()->getTime() ));
            $this->save();
        }
    }

    /**
     * mark voucher as reserved
     *
     * @return null
     */
    public function markAsReserved()
    {
        //saving oxreserved field
        $sVoucherID = $this->oxvouchers__oxid->value;

        if ( $sVoucherID ) {
            $sQ = 'update oxvouchers set oxreserved = '.time().' where oxid = "'.$sVoucherID.'"';
            oxDb::getDb()->Execute( $sQ );
        }
    }

    /**
     * unmark as reserved
     *
     * @return null
     */
    public function unMarkAsReserved()
    {
        //saving oxreserved field
        $sVoucherID = $this->oxvouchers__oxid->value;

        if ( $sVoucherID ) {
            $sQ = 'update oxvouchers set oxreserved = 0 where oxid = "'.$sVoucherID.'"';
            oxDb::getDb()->Execute($sQ);
        }
    }

    /**
     * Returns the discount value used.
     *
     * @param double $dPrice price to calculate discount on it
     *
     * @throws oxVoucherException exception
     *
     * @return double
     */
    public function getDiscountValue( $dPrice )
    {
        $oSerie = $this->getSerie();
        if ( $oSerie->oxvoucherseries__oxdiscounttype->value == 'absolute' ) {
            $oCur = $this->getConfig()->getActShopCurrencyObject();
            $dDiscount = $oSerie->oxvoucherseries__oxdiscount->value * $oCur->rate;
        } else {
            $dDiscount = $oSerie->oxvoucherseries__oxdiscount->value / 100 * $dPrice;
        }

        if ( $dDiscount > $dPrice ) {
            $oEx = oxNew( 'oxVoucherException' );
            $oEx->setMessage('EXCEPTION_VOUCHER_TOTALBELOWZERO');
            $oEx->setVoucherNr($this->oxVouchers__voucherNr->value);
            throw $oEx;
        }

        return $dDiscount;
    }

    // Checking General Availability
    /**
     * Checks availability without user logged in. Returns array with errors.
     *
     * @param array  $aVouchers array of vouchers
     * @param double $dPrice    current sum (price)
     *
     * @throws oxVoucherException exception
     *
     * @return array
     */
    public function checkVoucherAvailability( $aVouchers, $dPrice )
    {
        $this->_isAvailableWithSameSeries( $aVouchers );
        $this->_isAvailableWithOtherSeries( $aVouchers );
        $this->_isValidDate();
        $this->_isAvailablePrice( $dPrice );
        $this->_isNotReserved();

        // returning true - no exception was thrown
        return true;
    }

    /**
     * Performs basket level voucher availability check (no need to check if voucher
     * is reserved or so).
     *
     * @param array  $aVouchers array of vouchers
     * @param double $dPrice    current sum (price)
     *
     * @throws oxVoucherException exception
     *
     * @return array
     */
    public function checkBasketVoucherAvailability( $aVouchers, $dPrice )
    {
        $this->_isAvailableWithSameSeries( $aVouchers );
        $this->_isAvailableWithOtherSeries( $aVouchers );
        $this->_isValidDate();
        $this->_isAvailablePrice( $dPrice );

        // returning true - no exception was thrown
        return true;
    }

    /**
     * Checks availability about price. Returns error array.
     *
     * @param double $dPrice base article price
     *
     * @throws oxVoucherException exception
     *
     * @return array
     */
    protected function _isAvailablePrice( $dPrice )
    {
        if ( $this->getDiscountValue( $dPrice ) < 0 ) {
            $oEx = oxNew( 'oxVoucherException' );
            $oEx->setMessage('EXCEPTION_VOUCHER_TOTALBELOWZERO');
            $oEx->setVoucherNr($this->oxVouchers__voucherNr->value);
            throw $oEx;
        }
        $oSerie = $this->getSerie();
        if ( $oSerie->oxvoucherseries__oxminimumvalue->value && $dPrice < $oSerie->oxvoucherseries__oxminimumvalue->value ) {
            $oEx = oxNew( 'oxVoucherException' );
            $oEx->setMessage('EXCEPTION_VOUCHER_INCORRECTPRICE');
            $oEx->setVoucherNr($this->oxVouchers__voucherNr->value);
            throw $oEx;
        }

        return true;
    }

    /**
     * Checks if cumulation with vouchers of the same series possible. Returns
     * true on success.
     *
     * @param array $aVouchers array of vouchers
     *
     * @throws oxVoucherException exception
     *
     * @return bool
     *
     */
    protected function _isAvailableWithSameSeries( $aVouchers )
    {
        if ( is_array( $aVouchers ) ) {
            $sId = $this->getId();
            if (isset($aVouchers[$sId])) {
                unset($aVouchers[$sId]);
            }
            $oSerie = $this->getSerie();
            if (!$oSerie->oxvoucherseries__oxallowsameseries->value) {
                foreach ( $aVouchers as $voucherId => $voucherNr ) {
                    $oVoucher = oxNew( 'oxvoucher' );
                    $oVoucher->load($voucherId);
                    if ( $this->oxvouchers__oxvoucherserieid->value == $oVoucher->oxvouchers__oxvoucherserieid->value ) {
                            $oEx = oxNew( 'oxVoucherException' );
                            $oEx->setMessage('EXCEPTION_VOUCHER_NOTALLOWEDSAMESERIES');
                            throw $oEx;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Checks if cumulation with vouchers from the other series possible.
     * Returns true on success.
     *
     * @param array $aVouchers array of vouchers
     *
     * @throws oxVoucherException exception
     *
     * @return bool
     */
    protected function _isAvailableWithOtherSeries( $aVouchers )
    {
        if ( is_array( $aVouchers ) && count($aVouchers) ) {
            $oSerie = $this->getSerie();
            $sIds = '\''.implode('\',\'', array_keys($aVouchers)).'\'';
            $blAvailable = true;
            if (!$oSerie->oxvoucherseries__oxallowotherseries->value) {
                // just search for vouchers with different series
                $sSql  = "select 1 from oxvouchers where oxvouchers.oxid in ($sIds) and ";
                $sSql .= "oxvouchers.oxvoucherserieid != '{$this->oxvouchers__oxvoucherserieid->value}'";
                $blAvailable &= !oxDb::getDb()->getOne($sSql);
            } else {
                // search for vouchers with different series and those vouchers do not allow other series
                $sSql  = "select 1 from oxvouchers left join oxvoucherseries on oxvouchers.oxvoucherserieid=oxvoucherseries.oxid ";
                $sSql .= "where oxvouchers.oxid in ($sIds) and oxvouchers.oxvoucherserieid != '{$this->oxvouchers__oxvoucherserieid->value}' ";
                $sSql .= "and not oxvoucherseries.oxallowotherseries";
                $blAvailable &= !oxDb::getDb()->getOne($sSql);
            }
            if ( !$blAvailable ) {
                    $oEx = oxNew( 'oxVoucherException' );
                    $oEx->setMessage('EXCEPTION_VOUCHER_NOTALLOWEDOTHERSERIES');
                    $oEx->setVoucherNr($this->oxVouchers__voucherNr->value);
                    throw $oEx;
            }
        }

        return true;
    }

    /**
     * Checks if voucher is in valid time period. Returns true on success.
     *
     * @throws oxVoucherException exception
     *
     * @return bool
     */
    protected function _isValidDate()
    {
        $oSerie = $this->getSerie();
        // if time is not set - keep it as active by default
        $sDefTimeStamp = oxUtilsDate::getInstance()->formatDBDate( '-' );
        if ( $oSerie->oxvoucherseries__oxbegindate->value == $sDefTimeStamp &&
             $oSerie->oxvoucherseries__oxenddate->value == $sDefTimeStamp ) {
            return true;
        }

        if ( ( strtotime( $oSerie->oxvoucherseries__oxbegindate->value ) < time() &&
              strtotime( $oSerie->oxvoucherseries__oxenddate->value ) > time() ) ||
            !$oSerie->oxvoucherseries__oxenddate->value ||
            $oSerie->oxvoucherseries__oxenddate->value == $sDefTimeStamp ) {
            return true;
        }

        $oEx = oxNew( 'oxVoucherException' );
        $oEx->setMessage('EXCEPTION_VOUCHER_ISNOTVALIDDATE');
        throw $oEx;
    }

    /**
     * Checks if voucher is not yet reserved before.
     *
     * @throws oxVoucherException exception
     *
     * @return bool
     */
    protected function _isNotReserved()
    {

        if ( $this->oxvouchers__oxreserved->value < time() - 3600 * 3 ) {
            return true;
        }

        $oEx = oxNew( 'oxVoucherException' );
        $oEx->setMessage('EXCEPTION_VOUCHER_ISRESERVED');
        throw $oEx;
    }

    // Checking User Availability
    /**
     * Checks availability for the given user. Returns array with errors.
     *
     * @param object $oUser user object
     *
     * @throws oxVoucherException exception
     *
     * @return array
     */
    public function checkUserAvailability( $oUser )
    {

        $this->_isAvailableInOtherOrder( $oUser );
        $this->_isValidUserGroup( $oUser );

        // returning true if no exception was thrown
        return true;
    }

    /**
     * Checks if user already used vouchers from this series and can he use it again.
     *
     * @param object $oUser user object
     *
     * @throws oxVoucherException exception
     *
     * @return boolean
     */
    protected function _isAvailableInOtherOrder( $oUser )
    {
        $oSerie = $this->getSerie();
        if ( !$oSerie->oxvoucherseries__oxallowuseanother->value ) {

            $sSelect  = 'select count(*) from '.$this->getViewName().' where oxuserid = "'.$oUser->oxuser__oxid->value.'" and ';
            $sSelect .= 'oxvoucherserieid = "'.$this->oxvouchers__oxvoucherserieid->value.'" and ';
            $sSelect .= 'oxorderid is not NULL and oxorderid != "" ';

            if ( oxDb::getDb()->getOne( $sSelect )) {
                $oEx = oxNew( 'oxVoucherException' );
                $oEx->setMessage('EXCEPTION_VOUCHER_NOTAVAILABLEINOTHERORDER');
                $oEx->setVoucherNr($this->oxVouchers__voucherNr->value);
                throw $oEx;
            }
        }

        return true;
    }

    /**
     * Checks if user belongs to the same group as the voucher. Returns true on sucess.
     *
     * @param object $oUser user object
     *
     * @throws oxVoucherException exception
     *
     * @return bool
     */
    protected function _isValidUserGroup( $oUser )
    {
        $oVoucherSerie = $this->getSerie();
        $oUserGroups = $oVoucherSerie->setUserGroups();

        // dodger Task #1555 R Voucher does not work for not logged user?
        if ( !$oUserGroups->count() ) {
            return true;
        }

        if ( $oUser ) {
            foreach ( $oUserGroups as $oGroup ) {
                if ( $oUser->inGroup( $oGroup->getId() ) ) {
                    return true;
                }
            }
        }

        $oEx = oxNew( 'oxVoucherException' );
        $oEx->setMessage( 'EXCEPTION_VOUCHER_NOTVALIDUSERGROUP' );
        $oEx->setVoucherNr( $this->oxvouchers__vouchernr->value );
        throw $oEx;
    }

    /**
     * Returns compact voucher object which is used in oxbasket
     *
     * @return oxstdclass
     */
    public function getSimpleVoucher()
    {
        $oVoucher = new OxstdClass();
        $oVoucher->sVoucherId = $this->getId();
        $oVoucher->sVoucherNr = $this->oxvouchers__oxvouchernr->value;
        // R. setted in oxbasket : $oVoucher->fVoucherdiscount = oxLang::getInstance()->formatCurrency( $this->oxvouchers__oxdiscount->value );

        return $oVoucher;
    }

    /**
     * create oxVoucherSerie object of this voucher
     *
     * @return oxVoucherSerie
     */
    public function getSerie()
    {
        if ($this->_oSerie !== null) {
            return $this->_oSerie;
        }
        $oSerie = oxNew('oxvoucherserie');
        if (!$oSerie->load($this->oxvouchers__oxvoucherserieid->value)) {
            throw new oxObjectException();
        }
        $this->_oSerie = $oSerie;
        return $oSerie;
    }
}
