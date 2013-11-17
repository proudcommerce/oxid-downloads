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
 * @package admin
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: dyn_ipayment.php 17479 2009-03-20 12:32:53Z arvydas $
 */
/**
 * Includes configuration class.
 */
require_once "shop_config.php";

/**
 * Admin dyn ipayment manager.
 * @package admin
 * @subpackage dyn
 */
class dyn_ipayment extends Shop_Config
{
    /**
     * Creates shop object, passes shop data to Smarty engine and returns name of
     * template file "dyn_ipayment.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['oxid'] = $this->getConfig()->getShopId();

        return 'dyn_ipayment.tpl';

        /**
        */
    }

    /**
     * Assigns payment method to IPayment
     *
     * @return null
     */
    public function addPayment()
    {
        $myConfig = $this->getConfig();
        $aAddPayment = oxConfig::getParameter("allpayments");

        if ( isset( $aAddPayment) && is_array($aAddPayment)) {
            foreach ($aAddPayment as $sAdd) {
                $oNewGroup = oxNew( "oxbase" );
                $oNewGroup->init( "oxobject2ipayment" );
                $oNewGroup->oxobject2ipayment__oxpaymentid = new oxField($sAdd);
                $oNewGroup->oxobject2ipayment__oxshopid    = new oxField($myConfig->getShopId());
                $oNewGroup->oxobject2ipayment__oxtype      = new oxField("cc");
                $oNewGroup->save();
            }
        }
    }

    /**
     * Removes payment method from IPayment
     *
     * @return null
     */
    public function removePayment()
    {
        $myConfig = $this->getConfig();
        $aRemovePayment = oxConfig::getParameter( "addpayments");

        if ( isset( $aRemovePayment) && is_array($aRemovePayment) && count($aRemovePayment)) {
            $sQ  = "delete from oxobject2ipayment where oxobject2ipayment.oxshopid='".$myConfig->getShopId()."' ";
            $sQ .= "and oxobject2ipayment.oxid in (";
            $blSep = false;
            foreach ($aRemovePayment as $sRem) {
                $sQ .= ( ( $blSep ) ? ", ":"" ) . " '$sRem'";
                $blSep = true;
            }
            $sQ .= ")";
            oxDb::getDb()->Execute( $sQ);
        }
    }

    /**
     * Saves assigned method custom IPayment parameters
     *
     * @return null
     */
    public function savePayment()
    {
        $myConfig = $this->getConfig();
        $sActPayment = oxConfig::getParameter("oxpaymentid");
        $aParams     = oxConfig::getParameter("editval");

        $oActPayment = oxNew( "oxbase" );
        $oActPayment->init( "oxobject2ipayment" );

        $sQ  = "select * from oxobject2ipayment where oxobject2ipayment.oxshopid='".$myConfig->getShopId()."'
                and oxobject2ipayment.oxid = '$sActPayment' ";

        if ( $oActPayment->assignRecord( $sQ ) && is_array( $aParams ) ) {
            foreach ( $aParams as $sField => $sValue ) {
                if ( isset( $oActPayment->$sField ) ) {
                    $oActPayment->$sField = new oxField($sValue);
                }
            }
            $oActPayment->save();
        }
    }

    /**
     * Enables filtering for log viewer
     *
     * @return null
     */
    public function setFilter()
    {
        $this->blfiltering = true;
    }

    /**
     *
     */
    public function getViewId()
    {
        return 'dyn_interface';
    }
}
