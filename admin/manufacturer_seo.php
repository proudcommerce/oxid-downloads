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
 * $Id: manufacturer_seo.php 14637 2008-12-11 12:17:09Z arvydas $
 */

/**
 * Manufacturer seo config class
 */
class Manufacturer_Seo extends Object_Seo
{
    public function render()
    {
        $this->_aViewData['blShowSuffixEdit'] = true;
        $this->_aViewData['blShowSuffix'] = $this->_getObject( oxConfig::getParameter( 'oxid' ) )->oxmanufacturers__oxshowsuffix->value;

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

     * @param oxmanufacturer $oManufacturer active manufacturer object

     * @return string
     */
    protected function _getSeoUrl( $oManufacturer )
    {
        oxSeoEncoderManufacturer::getInstance()->getManufacturerUrl( $oManufacturer );
        return parent::_getSeoUrl( $oManufacturer );
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxmanufacturer';
    }

    /**
     * Updating showsuffix field
     *
     * return null
     */
    public function save()
    {
        if ( $sOxid = oxConfig::getParameter( 'oxid' ) ) {
            $oManufacturer = oxNew( 'oxbase' );
            $oManufacturer->init( 'oxmanufacturers' );
            if ( $oManufacturer->load( $sOxid ) ) {
                $oManufacturer->oxmanufacturers__oxshowsuffix = new oxField( (int) oxConfig::getParameter( 'blShowSuffix' ) );
                $oManufacturer->save();
            }
        }

        return parent::save();
    }
}