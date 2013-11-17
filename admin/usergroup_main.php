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
 * $Id: usergroup_main.php 17191 2009-03-13 12:21:00Z arvydas $
 */

/**
 * Admin article main usergroup manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: User Administration -> User Groups -> Main.
 * @package admin
 */
class UserGroup_Main extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxgroups object,
     * passes data to Smarty engine and returns name of template file
     * "usergroup_main.tpl".
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

        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oGroup = oxNew( "oxgroups" );
            $oGroup->load( $soxId);
            $this->_aViewData["edit"] =  $oGroup;
        }
        if ( oxConfig::getParameter("aoc") ) {

            $aColumns = array();
            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/usergroup_main.tpl";
        }
        return "usergroup_main.tpl";
    }

    /**
     * Saves changed usergroup parameters.
     *
     * @return mixed
     */
    public function save()
    {

        $soxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");
        // checkbox handling
        if ( !isset( $aParams['oxgroups__oxactive']))
            $aParams['oxgroups__oxactive'] = 0;

        $oGroup = oxNew( "oxgroups" );
        if ( $soxId != "-1")
            $oGroup->load( $soxId);
        else
            $aParams['oxgroups__oxid'] = null;
        //$aParams = $oGroup->ConvertNameArray2Idx( $aParams);
        $oGroup->assign( $aParams);
        $oGroup->save();
        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oGroup->oxgroups__oxid->value);
    }
}
