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
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: actions_main.php 28344 2010-06-15 11:32:21Z sarunas $
 */

/**
 * Admin article main actions manager.
 * There is possibility to change actions description, assign articles to
 * this actions, etc.
 * Admin Menu: Manage Products -> actions -> Main.
 * @package admin
 */
class Actions_Main extends oxAdminDetails
{
    /**
     * Loads article actionss info, passes it to Smarty engine and
     * returns name of template file "actions_main.tpl".
     *
     * @return string
     */
    public function render()
    {
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

        // copy this tree for our article choose
        $sChosenArtCat = oxConfig::getParameter( "artcat");
        if ( $soxId != "-1" && isset( $soxId)) {
            // generating category tree for select list
            $sChosenArtCat = $this->_getCategoryTree( "artcattree", $sChosenArtCat, $soxId);

            // load object
            $oAction = oxNew( "oxactions" );
            $oAction->loadInLang( $this->_iEditLang, $soxId);

            $oOtherLang = $oAction->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oAction->loadInLang( key($oOtherLang), $soxId );
            }

            $this->_aViewData["edit"] =  $oAction;

            // remove already created languages
            $aLang = array_diff ( oxLang::getInstance()->getLanguageNames(), $oOtherLang );

            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }
        $aColumns = array();
        if ( oxConfig::getParameter("aoc") ) {

            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/actions_main.tpl";
        }
        
        
        if ( ( $oPromotion = $this->getViewDataElement( "edit" ) ) ) {
            if ( $oPromotion->oxactions__oxtype->value == 2 ) {
                $this->_aViewData["editor"] = $this->_generateTextEditor( "100%", 300, $oPromotion, "oxactions__oxlongdesc", "details.tpl.css" );

                if ( $iAoc = oxConfig::getParameter( "oxscpromotionsaoc" ) ) {
                    $sPopup = false;
                    switch( $iAoc ) {
                        /*case 1:
                            $sPopup = 'oxscpromotions_products';
                            break;*/
                        case 2:
                            $sPopup = 'actions_groups';
                            break;
                    }

                    if ( $sPopup ) {
                        $aColumns = array();
                        include_once "inc/{$sPopup}.inc.php";
                        $this->_aViewData['oxajax'] = $aColumns;
                        return "popups/{$sPopup}.tpl";
                    }
                }
            }
        }
                
        return "actions_main.tpl";
    }


    /**
     * Saves Promotions
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig  = $this->getConfig();


        $soxId   = oxConfig::getParameter( "oxid");
        $aParams = oxConfig::getParameter( "editval");

        $oPromotion = oxNew( "oxactions" );
        if ( $soxId != "-1" ) {
            $oPromotion->load( $soxId );
        } else {
            $aParams['oxactions__oxid']   = null;
        }

        if ( !$aParams['oxactions__oxactive'] ) {
            $aParams['oxactions__oxactive'] = 0;
        }

        $oPromotion->setLanguage( 0 );
        $oPromotion->assign( $aParams );
        $oPromotion->setLanguage( $this->_iEditLang );
        $oPromotion = oxUtilsFile::getInstance()->processFiles( $oPromotion );
        $oPromotion->save();

        // set oxid if inserted
        if ( $soxId == "-1") {
            oxSession::setVar( "saved_oxid", $oPromotion->getId() );
        }

        $this->_aViewData["updatelist"] = "1";
    }

    /**
     * Saves changed selected action parameters in different language.
     *
     * @return null
     */
    public function saveinnlang()
    {
        $this->save();
    }
}
