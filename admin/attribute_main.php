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
 * @version   SVN: $Id: attribute_main.php 26706 2010-03-20 13:37:49Z arvydas $
 */

/**
 * Admin article main attributes manager.
 * There is possibility to change attribute description, assign articles to
 * this attribute, etc.
 * Admin Menu: Manage Products -> Attributes -> Main.
 * @package admin
 */
class Attribute_Main extends oxAdminDetails
{
    /**
     * Loads article Attributes info, passes it to Smarty engine and
     * returns name of template file "attribute_main.tpl".
     *
     * @return string
     */
    public function render()
    {   $myConfig = $this->getConfig();

        parent::render();

        $soxId = oxConfig::getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");
        if ( ($soxId == "-1" || !isset( $soxId)) && isset( $sSavedID) ) {
            $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        $sArticleTable = getViewName('oxarticles');

        // copy this tree for our article choose
        $sChosenArtCat = oxConfig::getParameter( "artcat");
        if ( $soxId != "-1" && isset( $soxId)) {
            // generating category tree for select list
            $sChosenArtCat = $this->_getCategoryTree( "artcattree", $sChosenArtCat, $soxId);

            // load object
            $oAttr = oxNew( "oxattribute" );
            $oAttr->loadInLang( $this->_iEditLang, $soxId );


            $oOtherLang = $oAttr->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oAttr->loadInLang( key($oOtherLang), $soxId );
            }

            $this->_aViewData["edit"] =  $oAttr;

            // remove already created languages
            $aLang = array_diff ( oxLang::getInstance()->getLanguageNames(), $oOtherLang);
            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] =  clone $oLang;
            }
        }
        if ( oxConfig::getParameter("aoc") ) {

            $aColumns = array();
            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/attribute_main.tpl";
        }
        return "attribute_main.tpl";
    }

    /**
     * Saves article attributes.
     *
     * @return mixed
     */
    public function save()
    {

        $soxId   = oxConfig::getParameter( "oxid");
        $aParams = oxConfig::getParameter( "editval");

            // shopid
            $aParams['oxattribute__oxshopid'] = oxSession::getVar( "actshop" );
        $oAttr = oxNew( "oxattribute" );

        if ( $soxId != "-1")
            $oAttr->loadInLang( $this->_iEditLang, $soxId );
        else
            $aParams['oxattribute__oxid'] = null;
        //$aParams = $oAttr->ConvertNameArray2Idx( $aParams);


        $oAttr->setLanguage(0);
        $oAttr->assign( $aParams);
        $oAttr->setLanguage($this->_iEditLang);
        $oAttr = oxUtilsFile::getInstance()->processFiles( $oAttr );
        $oAttr->save();
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oAttr->oxattribute__oxid->value);
    }

    /**
     * Saves attribute data to different language (eg. english).
     *
     * @return null
     */
    public function saveinnlang()
    {

        $soxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");

            // shopid
            $aParams['oxattribute__oxshopid'] = oxSession::getVar( "actshop");
        $oAttr = oxNew( "oxattribute" );

        if ( $soxId != "-1") {
            $oAttr->loadInLang( $this->_iEditLang, $soxId );
        } else {
            $aParams['oxattribute__oxid'] = null;
        }


        $oAttr->setLanguage(0);
        $oAttr->assign( $aParams);

        // apply new language
        $sNewLanguage = oxConfig::getParameter( "new_lang");
        $oAttr->setLanguage( $sNewLanguage);
        $oAttr->save();
        $this->_aViewData["updatelist"] = "1";

        // set for reload
        oxSession::setVar( "new_lang", $sNewLanguage);

        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oAttr->oxattribute__oxid->value);
    }
}
