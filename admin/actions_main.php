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
 * $Id: actions_main.php 17188 2009-03-13 12:19:11Z arvydas $
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
            $oAction->load( $soxId);
            $this->_aViewData["edit"] =  $oAction;
        }
        $aColumns = array();
        if ( oxConfig::getParameter("aoc") ) {

            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/actions_main.tpl";
        }
        return "actions_main.tpl";
    }

    /**
     * Saves article actionss.
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig  = $this->getConfig();


        $soxId   = oxConfig::getParameter( "oxid");
        $aParams = oxConfig::getParameter( "editval");

        $oAction = oxNew( "oxactions" );
        if ( $soxId != "-1" ) {
            $oAction->load( $soxId);
        } else {
            return;
        }

        if ( !$aParams['oxactions__oxactive'] ) {
            $aParams['oxactions__oxactive'] = 0;
        }

        $oAction->assign( $aParams);
        $oAction = oxUtilsFile::getInstance()->processFiles( $oAction );
        $oAction->save();
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oAction->oxactions__oxid->value);
    }
}
