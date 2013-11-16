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
 * @version   SVN: $Id: order_address.php 33186 2011-02-10 15:53:43Z arvydas.vapsva $
 */

/**
 * Admin order address manager.
 * Collects order addressing information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders -> Address.
 * @package admin
 */
class Order_Address extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxorder object
     * and passes it's data to Smarty engine. Returns name of template
     * file "order_address.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oOrder = oxNew( "oxorder" );
            $oOrder->load( $soxId);

            $this->_aViewData["edit"] =  $oOrder;
        }

        $oCountryList = oxNew( "oxCountryList" );
        $oCountryList->loadActiveCountries( oxLang::getInstance()->getObjectTplLanguage() );

        $this->_aViewData["countrylist"] = $oCountryList;

        return "order_address.tpl";
    }

    /**
     * Saves ordering address information.
     *
     * @return string
     */
    public function save()
    {

        $soxId = $this->getEditObjectId();
        $aParams    = oxConfig::getParameter( "editval");

            //TODO check if shop id is realy necessary at this place.
            $sShopID = oxSession::getVar( "actshop");
            $aParams['oxorder__oxshopid'] = $sShopID;

        $oOrder = oxNew( "oxorder" );
        if ( $soxId != "-1")
            $oOrder->load( $soxId);
        else
            $aParams['oxorder__oxid'] = null;

        $oOrder->assign( $aParams);
        $oOrder->save();

        // set oxid if inserted
        $this->setEditObjectId( $oOrder->getId() );
    }
}
