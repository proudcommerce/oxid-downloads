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
 * $Id: user_address.php 20428 2009-06-23 14:46:14Z vilma $
 */

/**
 * Admin user address setting manager.
 * Collects user address settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> Addresses.
 * @package admin
 */
class User_Address extends oxAdminDetails
{
    /**
     * @var bool
     */
    protected $_blDelete = false;

    /**
     * Executes parent method parent::render(), creates oxuser and oxbase objects,
     * passes data to Smarty engine and returns name of template file
     * "user_address.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = oxConfig::getParameter( "oxid");
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oUser = oxNew( "oxuser" );
            $oUser->load( $soxId);

            // load adress
            $soxAddressId = isset($this->sSavedOxid)?$this->sSavedOxid:oxConfig::getParameter( "oxaddressid");
            if ( $soxAddressId != "-1" && isset( $soxAddressId ) ) {
                $oAdress = oxNew( "oxbase" );
                $oAdress->init( "oxaddress" );
                $oAdress->load( $soxAddressId );
                $this->_aViewData["edit"] = $oAdress;
            }

            $this->_aViewData["oxaddressid"] = $soxAddressId;

            // generate selected
            $oAddressList = $oUser->getUserAddresses();
            foreach ( $oAddressList as $oAddress ) {
                if ( $oAddress->oxaddress__oxid->value == $soxAddressId ) {
                    $oAddress->selected = 1;
                    break;
                }
            }

            $this->_aViewData["edituser"] = $oUser;
        }

        $oCountryList = oxNew( "oxCountryList" );
        $oCountryList->loadActiveCountries( oxLang::getInstance()->getTplLanguage() );

        $this->_aViewData["countrylist"] = $oCountryList;

        if (!$this->_allowAdminEdit($soxId))
            $this->_aViewData['readonly'] = true;

        return "user_address.tpl";
    }

    /**
     * Saves user addressing information.
     *
     * @return mixed
     */
    public function save()
    {

        if ( !$this->_allowAdminEdit( oxConfig::getParameter( "oxid" ) ) )
            return false;

        $aParams = oxConfig::getParameter( "editval");

        $oAdress = oxNew( "oxbase" );
        $oAdress->init( "oxaddress" );

        if ( $aParams['oxaddress__oxid'] == "-1")
            $aParams['oxaddress__oxid'] = null;

        //$aParams = $oAdress->ConvertNameArray2Idx( $aParams);
        $oAdress->assign( $aParams);
        $oAdress->save();

        $this->sSavedOxid = $oAdress->getId();
    }

    /**
     * Deletes user addressing information.
     *
     * @return null
     */
    public function delAddress()
    {
        $aParams = oxConfig::getParameter( "editval" );
        if ( !$this->_allowAdminEdit( oxConfig::getParameter( "oxid" ) ) )
            return false;

        $oAdress = oxNew( "oxbase" );
        $oAdress->init( "oxaddress" );

        if ( $aParams['oxaddress__oxid'] != "-1") {
            $oAdress->load( $aParams['oxaddress__oxid']);
            $oAdress->delete();
            $this->_blDelete = true;
        }
    }
}
