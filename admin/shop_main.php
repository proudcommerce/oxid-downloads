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
 * @link      http://www.oxid-esales.com
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: shop_main.php 25466 2010-02-01 14:12:07Z alfonsas $
 */


/**
 * Admin article main shop manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Main Menu -> Core Settings -> Main.
 * @package admin
 */
class Shop_Main extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxCategoryList and
     * oxshop objects, passes it's data to Smarty engine and returns name of
     * template file "shop_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $soxId = oxConfig::getParameter( "oxid");


        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oShop = oxNew( "oxshop" );
            $isubjlang = oxConfig::getParameter("subjlang");
            if ( !isset($isubjlang))
                $isubjlang = $this->_iEditLang;

            if ($isubjlang && $isubjlang > 0) {
                $this->_aViewData["subjlang"] =  $isubjlang;
            }

            $oShop->loadInLang( $isubjlang, $soxId );

            $this->_aViewData["edit"] =  $oShop;
            //oxSession::setVar( "actshop", $soxId);//echo "<h2>$soxId</h2>";
            oxSession::setVar( "shp", $soxId);
        }


        $this->_aViewData['IsOXDemoShop'] = $myConfig->isDemoShop();
        if ( !isset( $this->_aViewData['updatenav'] ) ) {
            $this->_aViewData['updatenav']    = oxConfig::getParameter( 'updatenav' );
        }

        return "shop_main.tpl";
    }

    /**
     * Saves changed main shop configuration parameters.
     *
     * @return null
     */
    public function save()
    {
        $myConfig  = $this->getConfig();


        $soxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");


        //  #918 S
        // checkbox handling
        if ( isset( $aParams['oxshops__oxactive']) && $aParams['oxshops__oxactive'] == true)
            $aParams['oxshops__oxactive'] = 1;
        else
            $aParams['oxshops__oxactive'] = 0;

        if ( isset( $aParams['oxshops__oxproductive']) && $aParams['oxshops__oxproductive'] == true)
            $aParams['oxshops__oxproductive'] = 1;
        else
            $aParams['oxshops__oxproductive'] = 0;

        $oShop = oxNew( "oxshop" );


        $isubjlang = oxConfig::getParameter("subjlang");
        if ( $isubjlang && $isubjlang > 0 ) {
            $iLang = $isubjlang;
        } else {
            $iLang = 0;
        }

        if ( $soxId != "-1") {
            $oShop->loadInLang( $iLang, $soxId );
        } else {
                $aParams['oxshops__oxid'] = null;
        }

        if ($aParams['oxshops__oxsmtp']) {
            $aParams['oxshops__oxsmtp'] = trim($aParams['oxshops__oxsmtp']);
        }

        $oShop->setLanguage(0);
        //$aParams = $oShop->ConvertNameArray2Idx( $aParams);
        $oShop->assign( $aParams);
        $oShop->setLanguage($iLang);

        $sNewSMPTPass = oxConfig::getParameter( "oxsmtppwd");

        if ($sNewSMPTPass) {
            $oShop->oxshops__oxsmtppwd->setValue($sNewSMPTPass);
        }
        //unsetting password
        if ($sNewSMPTPass == '-') {
            $oShop->oxshops__oxsmtppwd->setValue("");
        }


        $oShop->save();


        $this->_aViewData["updatelist"] =  "1";

        oxSession::setVar( "actshop", $soxId);
    }


    /**
     * Returns shop id and shop name array
     *
     * @return array
     */
    protected function _getShopIds()
    {
        //loading shop ids
        $aShopIds = array();
        $sRs = oxDb::getDb()->execute( "select oxid, oxname from oxshops" );
        if ($sRs != false && $sRs->recordCount() > 0) {
            while ( !$sRs->EOF ) {
                $aShopIds[$sRs->fields[0]] = new oxStdClass();
                $aShopIds[$sRs->fields[0]]->oxid   = $sRs->fields[0];
                $aShopIds[$sRs->fields[0]]->oxname = $sRs->fields[1];
                $sRs->moveNext();
            }
        }

        return $aShopIds;
    }

}
