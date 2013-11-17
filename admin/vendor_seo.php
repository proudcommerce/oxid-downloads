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
 * @version   SVN: $Id: vendor_seo.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * Vendor seo config class
 */
class Vendor_Seo extends Object_Seo
{
    /**
     * Executes parent method parent::render(),
     * and returns name of template file
     * "object_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $this->_aViewData['blShowSuffixEdit'] = true;
        $this->_aViewData['blShowSuffix'] = $this->_getObject( oxConfig::getParameter( 'oxid' ) )->oxvendor__oxshowsuffix->value;

        return parent::render();
    }

    /**
     * Returns SQL to fetch seo data
     *
     * @param oxbase $oObject object to load seo info
     * @param int    $iShopId active shop id
     * @param int    $iLang   active language id
     *
     * @return string
     */
    protected function _getSeoDataSql( $oObject, $iShopId, $iLang )
    {
        return parent::_getSeoDataSql( $oObject, $iShopId, $iLang )." and oxparams = '' ";
    }

    /**
     * Returns objects seo url
     *
     * @param oxvendor $oVendor active vendor object
     *
     * @return string
     */
    protected function _getSeoUrl( $oVendor )
    {
        oxSeoEncoderVendor::getInstance()->getVendorUrl( $oVendor );
        return parent::_getSeoUrl( $oVendor );
    }

    /**
     * Returns seo object
     *
     * @param string $sOxid object id
     *
     * @return mixed
     */
    protected function _getObject( $sOxid )
    {
        // load object
        $oVendor = oxNew( 'oxvendor' );
        if ( $oVendor->loadInLang( $this->_iEditLang, $sOxid ) ) {
            return $oVendor;
        }
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxvendor';
    }

    /**
     * Updating showsuffix field
     *
     * @return null
     */
    public function save()
    {
        if ( $sOxid = oxConfig::getParameter( 'oxid' ) ) {
            $oVendor = oxNew( 'oxbase' );
            $oVendor->init( 'oxvendor' );
            if ( $oVendor->load( $sOxid ) ) {
                $oVendor->oxvendor__oxshowsuffix = new oxField( (int) oxConfig::getParameter( 'blShowSuffix' ) );
                $oVendor->save();
            }
        }

        return parent::save();
    }
}
