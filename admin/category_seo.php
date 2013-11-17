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
 * $Id: category_seo.php 17479 2009-03-20 12:32:53Z arvydas $
 */

/**
 * Category seo config class
 */
class Category_Seo extends Object_Seo
{
    /**
     * Loads article parameters and passes them to Smarty engine, returns
     * name of template file "article_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $oCategory = $this->_getObject( oxConfig::getParameter( 'oxid' ) );


        $this->_aViewData["edit"] = $oCategory;
        $this->_aViewData['blShowSuffixEdit'] = true;
        $this->_aViewData['blShowSuffix'] = $oCategory ? $oCategory->oxcategories__oxshowsuffix->value : false;

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
     * @param oxcategory $oCategory active category object
     *
     * @return string
     */
    protected function _getSeoUrl( $oCategory )
    {
        oxSeoEncoderCategory::getInstance()->getCategoryUrl( $oCategory );

        return parent::_getSeoUrl( $oCategory );
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxcategory';
    }

    /**
     * Updating showsuffix field
     *
     * @return null
     */
    public function save()
    {
        if ( $sOxid = oxConfig::getParameter( 'oxid' ) ) {
            $oCategory = oxNew( 'oxbase' );
            $oCategory->init( 'oxcategories' );
            if ( $oCategory->load( $sOxid ) ) {
                $oCategory->oxcategories__oxshowsuffix = new oxField( (int) oxConfig::getParameter( 'blShowSuffix' ) );
                $oCategory->save();

                // marking page links as expired
                oxSeoEncoderCategory::getInstance()->markAsExpired( $sOxid, $this->getconfig()->getShopId(), 1, $this->_iEditLang, "oxparams != '' " );
            }
        }

        return parent::save();
    }
}