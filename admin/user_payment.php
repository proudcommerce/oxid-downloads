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
 * $Id: user_payment.php 17191 2009-03-13 12:21:00Z arvydas $
 */

/**
 * Admin user payment settings manager.
 * Collects user payment settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> Payment.
 * @package admin
 */
class User_Payment extends oxAdminDetails
{
    /**
     * (default false).
     * @var bool
     */
    protected $_blDelete = false;

    /**
     * Executes parent method parent::render(), creates oxlist and oxuser objects,
     * passes data to Smarty engine and returns name of template file "user_payment.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // all paymenttypes
        $oPaymentTypes = oxNew( "oxlist" );
        $oPaymentTypes->init( "oxpayment");
        $oPaymentTypes->getList();

        $soxId = oxConfig::getParameter( "oxid");
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oUser = oxNew( "oxuser" );
            $oUser->load( $soxId);

            // load payment
            $soxPaymentId = oxConfig::getParameter( "oxpaymentid");
            if ( (!$soxPaymentId || $this->_blDelete) && isset( $oUser->oPayments[0]))
                $soxPaymentId = $oUser->oPayments[0]->oxuserpayments__oxid->value;
            if ( $soxPaymentId != "-1" && isset( $soxPaymentId)) {
                $oUserPayment = oxNew( "oxuserpayment" );
                $oUserPayment->load( $soxPaymentId);
                $sTemplate = $oUserPayment->oxuserpayments__oxvalue->value;

                // generate selected paymenttype
                foreach ( $oPaymentTypes as $oPayment ) {
                    if ( $oPayment->oxpayments__oxid->value == $oUserPayment->oxuserpayments__oxpaymentsid->value) {
                        $oPayment->selected = 1;
                        // if there are no values assigned we set default from paymenttype
                        if ( !$sTemplate )
                            $sTemplate = $oPayment->oxpayments__oxvaldesc->value;
                        break;
                    }
                }
                $oUserPayment->setDynValues( oxUtils::getInstance()->assignValuesFromText( $sTemplate ) );
                $this->_aViewData["edit"] =  $oUserPayment;

            }
            if ( !$soxPaymentId)
                $soxPaymentId = "-1";
            $this->_aViewData["oxpaymentid"]    = $soxPaymentId;

            $this->_aViewData["paymenttypes"]    = $oPaymentTypes;

            // generate selected
            $oUserPayments = $oUser->getUserPayments();
            foreach ( $oUserPayments as $oPayment ) {
                if ( $oPayment->oxuserpayments__oxid->value == $soxPaymentId ) {
                    $oPayment->selected = 1;
                    break;
                }
            }

            $this->_aViewData["edituser"] =  $oUser;
        }

        if (!$this->_allowAdminEdit($soxId))
            $this->_aViewData['readonly'] = true;


        return "user_payment.tpl";
    }

    /**
     * Saves user payment settings.
     *
     * @return mixed
     */
    public function save()
    {

        $soxId      = oxConfig::getParameter( "oxid");
        if (!$this->_allowAdminEdit($soxId))
            return;

        $aParams    = oxConfig::getParameter( "editval");
        $aDynvalues = oxConfig::getParameter( "dynvalue");

        if ( isset($aDynvalues)) {
            // store the dynvalues
            $aParams['oxuserpayments__oxvalue'] = oxUtils::getInstance()->assignValuesToText( $aDynvalues);
        }

        $oAdress = oxNew( "oxuserpayment" );

        if ( $aParams['oxuserpayments__oxid'] == "-1")
            $aParams['oxuserpayments__oxid'] = null;
        //$aParams = $oAdress->ConvertNameArray2Idx( $aParams);
        $oAdress->assign( $aParams);
        $oAdress->save();
    }

    /**
     * Deletes selected user payment information.
     *
     * @return null
     */
    public function delPayment()
    {
        $aParams = oxConfig::getParameter( "editval" );
        $soxId   = oxConfig::getParameter( "oxid" );
        if (!$this->_allowAdminEdit($soxId))
            return;

        $oAdress = oxNew( "oxuserpayment" );

        if ( $aParams['oxuserpayments__oxid'] != "-1") {
            $oAdress->load( $aParams['oxuserpayments__oxid']);
            $oAdress->delete();
            $this->_blDelete = true;
        }
    }
}
