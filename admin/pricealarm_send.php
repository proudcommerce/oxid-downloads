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
 * $Id: pricealarm_send.php 21005 2009-07-20 05:16:20Z alfonsas $
 */

/**
 * pricealarm sending manager.
 * Performs sending of pricealarm to selected iAllCnt groups.
 * @package admin
 */
class PriceAlarm_Send extends oxAdminList
{
    /**
     * Default tab number
     *
     * @var int
     */
    protected $_iDefEdit = 1;
    /**
     * Executes parent method parent::render(), creates oxpricealarm object,
     * sends pricealarm to iAllCnts of chosen groups and returns name of template
     * file "pricealarm_send.tpl"/"pricealarm_done.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig  = $this->getConfig();
        $oDB = oxDb::getDb();

        parent::render();

        ini_set("session.gc_maxlifetime", 36000);

        $iStart     = oxConfig::getParameter( "iStart");
        $iAllCnt    = oxConfig::getParameter( "iAllCnt");
            // #1140 R
            $sSelect = "select oxpricealarm.oxid, oxpricealarm.oxemail, oxpricealarm.oxartid, oxpricealarm.oxprice from oxpricealarm, oxarticles where oxarticles.oxid = oxpricealarm.oxartid and oxpricealarm.oxsended = '0000-00-00 00:00:00'";
            if (isset($iStart)) {
                $rs = $oDB->SelectLimit( $sSelect, $myConfig->getConfigParam( 'iCntofMails' ), $iStart);
            } else {
                $rs = $oDB->Execute( $sSelect);
            }

            $iAllCnt_counting=0;

            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $oArticle = oxNew("oxarticle" );
                    $oArticle->load($rs->fields['oxid']);
                    if ($oArticle->getPrice()->getBruttoPrice() <= $rs->fields['oxprice']) {
                        $this->sendeMail( $rs->fields['oxemail'], $rs->fields['oxartid'], $rs->fields['oxid'], $rs->fields['oxprice']);
                        $iAllCnt_counting++;
                    }
                    $rs->moveNext();
                }
            }
            if ( !isset( $iStart)) {
                // first call
                $iStart     = 0;
                $iAllCnt    = $iAllCnt_counting;
            }


        // adavance mail pointer and set parameter
        $iStart += $myConfig->getConfigParam( 'iCntofMails' );

        $this->_aViewData["iStart"]  =  $iStart;
        $this->_aViewData["iAllCnt"] =  $iAllCnt;
        $this->_aViewData["actlang"] = oxLang::getInstance()->getBaseLanguage();

        // end ?
        if ( $iStart < $iAllCnt)
            $sPage = "pricealarm_send.tpl";
        else
            $sPage = "pricealarm_done.tpl";

        return $sPage;
    }

    /**
     * Overrides parent method to pass referred id
     *
     * @param string $sId class name
     */
    protected function _setupNavigation( $sId )
    {
        parent::_setupNavigation( 'pricealarm_list' );
    }

    /**
     * creates and sends email with pricealarm information
     *
     * @param string $sEMail        email address
     * @param string $sProductID    product id
     * @param string $sPricealarmID price alarm id
     * @param string $sBidPrice     bidded price
     *
     * @return null
     */
    public function sendeMail( $sEMail, $sProductID, $sPricealarmID, $sBidPrice)
    {
        $myConfig = $this->getConfig();
        $oPricealarm = oxNew( "oxpricealarm" );
        $oPricealarm->load( $sPricealarmID);

        // Send Email
        $oShop = oxNew( "oxshop" );
        //$oShop->load( $myConfig->getShopId());
        $oShop->load( $oPricealarm->oxpricealarm__oxshopid->value);
        $oShop = $this->addGlobalParams( $oShop);

        $oArticle = oxNew( "oxarticle" );
        $oArticle->load( $sProductID);

        if ( $oArticle->oxarticles__oxparentid->value && !$oArticle->oxarticles__oxtitle->value) {
            $oParent = oxNew( "oxarticle" );
            $oParent->load($oArticle->oxarticles__oxparentid->value);
            $oArticle->oxarticles__oxtitle->setValue($oParent->oxarticles__oxtitle->value." ".$oArticle->oxarticles__oxvarselect->value);
        }

        $oDefCurr = $myConfig->getActShopCurrencyObject();

        $oAlarm = oxNew( "oxpricealarm" );
        $oAlarm->load( $sPricealarmID);

        $oThisCurr = $myConfig->getCurrencyObject( $oAlarm->oxpricealarm__oxcurrency->value);

        if ( !$oThisCurr ) {
            $oThisCurr = $oDefCurr;
            $oAlarm->oxpricealarm__oxcurrency->setValue($oDefCurr->name);
        }

        // #889C - Netto prices in Admin
        // (we have to call $oArticle->getPrice() to get price with VAT)
        $oLang = oxLang::getInstance();
        $oArticle->oxarticles__oxprice->setValue($oArticle->getPrice()->getBruttoPrice() * $oThisCurr->rate);
        $oArticle->fprice = $oLang->formatCurrency( $oArticle->oxarticles__oxprice->value, $oThisCurr);
        $oAlarm->fpricealarmprice = $oLang->formatCurrency( $oAlarm->oxpricealarm__oxprice->value, $oThisCurr);

        $oxEMail = oxNew( "oxemail" );
        $oxEMail->From     = $oShop->oxshops__oxorderemail->value;
        $oxEMail->FromName = $oShop->oxshops__oxname->getRawValue();
        $oxEMail->Host     = $oShop->oxshops__oxsmtp->value;
        $oxEMail->SetSMTP( $oShop);
        $oxEMail->WordWrap = 100;

        // create messages
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "product", $oArticle );
        $smarty->assign( "bidprice", $oLang->formatCurrency($sBidPrice, $oThisCurr) );
        $smarty->assign( "currency", $oThisCurr );
        $smarty->assign( "shopImageDir", $myConfig->getImageUrl( false , false ) );

        $iLang = $oAlarm->oxpricealarm__oxlang->value;

        if (!$iLang) {
            $iLang = 0;
        }

        $old_iLang = $oLang->getTplLanguage();
        $oLang->setTplLanguage( $iLang );

        $oxEMail->Body      = $smarty->fetch( "email_pricealarm_customer.tpl");
        $oxEMail->Subject   = $oShop->oxshops__oxname->getRawValue();
        $oxEMail->AddAddress( $sEMail, $sEMail );
        $oxEMail->AddReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue());
        $blSuccess = $oxEMail->send();

        $oLang->setTplLanguage( $old_iLang );

        if ( $blSuccess) {
            $oAlarm->oxpricealarm__oxsended->setValue( date( "Y-m-d H:i:s" ) );
            $oAlarm->save();
        }

    }
}
