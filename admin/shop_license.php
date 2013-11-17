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
 * $Id: shop_license.php 22487 2009-09-22 07:03:10Z arvydas $
 */

/**
 * Admin shop license setting manager.
 * Collects shop license settings, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> License.
 * @package admin
 */
class Shop_License extends Shop_Config
{

    /**
     * Current class template.
     * @var string
     */
    protected $_sThisTemplate = "shop_license.tpl";



    /**
     * Executes parent method parent::render(), creates oxshop object, passes it's
     * data to Smarty engine and returns name of template file "shop_license.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig   = $this->getConfig();
        if ($myConfig->isDemoShop()) {
            throw new oxSystemComponentException("license");
        }

        parent::render();

        $soxId = oxConfig::getParameter( "oxid");

        // dodger: only first shop can store serials

        /*if( $soxId != "-1" && $soxId != "1")
            $soxId = "1";*/

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
            $oShop = oxNew( "oxshop" );
            $oShop->load( $soxId);
            $this->_aViewData["edit"] =  $oShop;
        }

        $this->_aViewData["version"]         = $myConfig->getVersion();




        return $this->_sThisTemplate;
    }

}
