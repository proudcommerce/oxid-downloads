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
 * $Id: order_overview.php 18765 2009-05-04 13:11:33Z vilma $
 */

    // DTAUS
    require_once "dtaus/class.DTAUS.php";

/**
 * Admin order overview manager.
 * Collects order overview information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders -> Overview.
 * @package admin
 */
class Order_Overview extends oxAdminDetails
{
    /**
     * executes parent mathod parent::render(), creates oxorder, passes
     * it's data to Smarty engine and returns name of template file
     * "order_overview.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $oOrder = oxNew( "oxorder" );

        $soxId = oxConfig::getParameter( "oxid");
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oOrder->load( $soxId);

            $this->_aViewData["edit"]          = $oOrder;
            $this->_aViewData["orderArticles"] = $oOrder->getOrderArticles();
            $this->_aViewData["giftCard"]      = $oOrder->getGiftCard();
            $this->_aViewData["paymentType"]   = $oOrder->getPaymentType();
            $this->_aViewData["deliveryType"]  = $oOrder->getDelSet();
        }

        // orders today
        $oLang = oxLang::getInstance();
        $oCur  = $myConfig->getActShopCurrencyObject();
        $dSum  = $oOrder->getOrderSum(true);
        $this->_aViewData["ordersum"] = $oLang->formatCurrency($dSum, $oCur);
        $this->_aViewData["ordercnt"] = $oOrder->getOrderCnt(true);

        // ALL orders
        $dSum = $oOrder->getOrderSum();
        $this->_aViewData["ordertotalsum"] = $oLang->formatCurrency( $dSum, $oCur);
        $this->_aViewData["ordertotalcnt"] = $oOrder->getOrderCnt();
        $this->_aViewData["afolder"] = $myConfig->getConfigParam( 'aOrderfolder' );
        $this->_aViewData["sfolder"] = $myConfig->getConfigParam( 'aOrderfolder' );
            $this->_aViewData["alangs"] = $oLang->getLanguageNames();


        $this->_aViewData["currency"] = $oCur;

        return "order_overview.tpl";
    }

    /**
     * Performs Lexware export to user (outputs file to save).
     *
     * @return null
     */
    public function exportlex()
    {
        $sOrderNr   = oxConfig::getParameter( "ordernr");
        $sToOrderNr = oxConfig::getParameter( "toordernr");

        $oImex = oxNew( "oximex" );
        $sLexware = $oImex->exportLexwareOrders( $sOrderNr, $sToOrderNr);
        if ( isset( $sLexware) && $sLexware) {
            header("Pragma: public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
            header("Content-type: application/x-download");
            header('Content-Length: '.getStr()->strlen($sLexware));
            header("Content-Disposition: attachment; filename=intern.xml");
            echo( $sLexware);
            exit();
        }
    }
    /**
     * Performs PDF export to user (outputs file to save).
     *
     * @return null
     */
    public function createPDF()
    {
        $soxId = oxConfig::getParameter( "oxid");
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oOrder = oxNew( "oxorder" );
            $oOrder->load( $soxId);
            $sFilename = $oOrder->oxorder__oxordernr->value."_".$oOrder->oxorder__oxbilllname->value.".pdf";

            ob_start();
            $oOrder->genPDF( $sFilename, oxConfig::getParameter( "pdflanguage"));
            $sPDF = ob_get_contents();
            ob_end_clean();

            header("Pragma: public");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=".$sFilename);
            echo( $sPDF);
            exit();
        }

    }

    /**
     * Performs DTAUS export to user (outputs file to save).
     *
     * @return null
     */
    public function exportDTAUS()
    {
        $iFromOrderNr = oxConfig::getParameter( "ordernr");

        $oOrderList = oxNew( "oxlist" );
        $oOrderList->init( "oxorder");
        $sSelect =  "select * from oxorder where oxpaymenttype = 'oxiddebitnote'";

        if ( isset( $iFromOrderNr) && $iFromOrderNr)
            $sSelect .= " and oxordernr >= $iFromOrderNr";

        $oOrderList->selectString( $sSelect);

        if ( !count( $oOrderList))
            return;

        $oPayment = oxNew( "oxuserpayment" );
        $oShop = $this->getConfig()->getActiveShop();
        $dtaus = new DTAUS("L", $oShop->oxshops__oxcompany->value, str_replace( " ", "", $oShop->oxshops__oxbankcode->value), str_replace( " ", "", $oShop->oxshops__oxbanknumber->value));

        $myUtils = oxUtils::getInstance();
        $myLang  = oxLang::getInstance();
        foreach ( $oOrderList as $oOrder) {
            $oPayment->load( $oOrder->oxorder__oxpaymentid->value);
            $aDynValues = $myUtils->assignValuesFromText( $oPayment->oxuserpayments__oxvalue->value );
            // #630
            //$dtaus->addTransaktion( $aDynValues[3]->value, str_replace( array(" ", "-"), "", $aDynValues[1]->value), str_replace( array(" ", "-"), "", $aDynValues[2]->value), str_replace( ",", ".",$oOrder->ftotalorder), $oShop->oxshops__oxname->getRawValue(), oxLang::getInstance()->translateString("order")." ".$oOrder->oxorder__oxordernr->value,"");
            $dtaus->addTransaktion( $aDynValues[3]->value, str_replace( " ", "", $aDynValues[1]->value), str_replace( " ", "", $aDynValues[2]->value), str_replace( ",", ".", $oOrder->ftotalorder), $oShop->oxshops__oxname->getRawValue(), $myLang->translateString("order")." ".$oOrder->oxorder__oxordernr->value, "");

        }

        header("Content-Disposition: attachment; filename=\"dtaus0.txt\"");
        header("Content-type: text/plain");
        header("Cache-control: public");

        echo(  $dtaus->create());
        exit();
    }

    /**
     * Sends order.
     *
     * @return null
     */
    public function sendorder()
    {
        $soxId  = oxConfig::getParameter( "oxid");
        $oOrder = oxNew( "oxorder" );
        $oOrder->load( $soxId);

        // #632A
        $timeout = oxUtilsDate::getInstance()->getTime(); //time();
        $now = date("Y-m-d H:i:s", $timeout);
        $oOrder->oxorder__oxsenddate->setValue($now);
        $oOrder->save();

        // #1071C
        $oOrderArticles = $oOrder->getOrderArticles();
        foreach ( $oOrderArticles as $oxid=>$oArticle) {
            // remove canceled articles from list
            if ( $oArticle->oxorderarticles__oxstorno->value == 1 )
                $oOrderArticles->offsetUnset($oxid);
        }

        $blMail  = oxConfig::getParameter( "sendmail");
        if ( isset( $blMail) && $blMail) {
            // send eMail
            $oxEMail = oxNew( "oxemail" );
            $oxEMail->sendSendedNowMail( $oOrder );
        }
    }

    /**
     * Resets order shipping date.
     *
     * @return null
     */
    public function resetorder()
    {
        $soxId  = oxConfig::getParameter( "oxid");
        $oOrder = oxNew( "oxorder" );
        $oOrder->load( $soxId);

        $oOrder->oxorder__oxsenddate->setValue("0000-00-00 00:00:00");
        $oOrder->save();
    }

    /**
     * Returns pdf export state - can export or not
     *
     * @return bool
     */
    public function canExport()
    {
        //V #529: check if PDF invoice modul is active
        if ( oxUtilsObject::getInstance()->isModuleActive( 'oxorder', 'myorder' ) ) {
            $oDb = oxDb::getDb();
            $sOrderId = oxConfig::getParameter( "oxid" );
            $sTable = getViewName( "oxorderarticles" );
            $sQ = "select count(oxid) from {$sTable} where oxorderid = ".$oDb->quote( $sOrderId )." and oxstorno = 0";
            return (bool) $oDb->getOne( $sQ );
        }
        return false;
    }
}
