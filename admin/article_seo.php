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
 * $Id: article_seo.php 17706 2009-03-31 13:59:00Z vilma $
 */

/**
 * Article seo config class
 */
class Article_Seo extends Object_Seo
{
    /**
     * Chosen category id
     *
     * @var string
     */
    protected $_sActCatId = null;

    /**
     * Chosen category type
     *
     * @var string
     */
    protected $_sActCatType = null;

    /**
     * Article deepest categoy nodes list
     *
     * @var oxlist
     */
    protected $_oArtCategories = null;

    /**
     * Article deepest vendor list
     *
     * @var oxlist
     */
    protected $_oArtVendors = null;

    /**
     * Article deepest manufacturer list
     *
     * @var oxlist
     */
    protected $_oArtManufacturers = null;

    /**
     * Active article object
     *
     * @var oxarticle
     */
    protected $_oArticle = null;

    /**
     * Loads article parameters and passes them to Smarty engine, returns
     * name of template file "article_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $oArticle = $this->_getObject( oxConfig::getParameter( 'oxid' ) );

        $this->_aViewData["edit"] = $oArticle;
        $this->_aViewData["blShowCatSelect"] = true;
        $this->_aViewData["oCategories"] = $this->_getCategoryList( $oArticle );
        $this->_aViewData["oVendors"]    = $this->_getVendorList( $oArticle );
        $this->_aViewData["oManufacturers"] = $this->_getManufacturerList( $oArticle );
        $this->_aViewData["sCatId"]      = $this->getActCategory();
        $this->_aViewData["sCatType"]    = $this->_sActCatType;

        return parent::render();
    }

    /**
     * Returns SQL to fetch seo data
     *
     * @return string
     */
    protected function _getSeoDataSql( $oObject, $iShopId, $iLang )
    {
        //$sParam = ( $sCat = $this->getActCategory() ) ? " and oxparams = '$sCat' and oxtype = '{$this->_sActCatType}' " : '';
        $sParam = ( $sCat = $this->getActCategory() ) ? " and oxparams = '$sCat' " : '';
        $sQ = "select * from oxseo where oxobjectid = '".$oObject->getId()."' and
               oxshopid = '{$iShopId}' and oxlang = {$iLang} {$sParam} ";
        return $sQ;
    }

    /**
     * Returns list with deepest article categories
     *
     * @return oxlist
     */
    protected function _getCategoryList( $oArticle )
    {
        if ( $this->_oArtCategories === null ) {
            $this->_oArtCategories = ( $oArticle ) ? oxSeoEncoderArticle::getInstance()->getSeoCategories( $oArticle ) : false;
        }

        return $this->_oArtCategories;
    }

    /**
     * Returns list with deepest article categories
     *
     * @return oxlist
     */
    protected function _getVendorList( $oArticle )
    {
        if ( $this->_oArtVendors === null ) {
            $this->_oArtVendors = false;

            if ( $oArticle->oxarticles__oxvendorid->value ) {
                $oVendor = oxNew( 'oxvendor' );
                if ( $oVendor->loadInLang( $this->_iEditLang, $oArticle->oxarticles__oxvendorid->value ) ) {
                    $this->_oArtVendors = oxNew( 'oxList', 'oxvendor' );
                    $this->_oArtVendors[] = $oVendor;
                }
            }
        }
        return $this->_oArtVendors;
    }

    /**
     * Returns list with deepest article categories
     *
     * @return oxlist
     */
    protected function _getManufacturerList( $oArticle )
    {
        if ( $this->_oArtManufacturers === null ) {
            $this->_oArtManufacturers = false;

            if ( $oArticle->oxarticles__oxmanufacturerid->value ) {
                $oManufacturer = oxNew( 'oxmanufacturer' );
                if ( $oManufacturer->loadInLang( $this->_iEditLang, $oArticle->oxarticles__oxmanufacturerid->value ) ) {
                    $this->_oArtManufacturers = oxNew( 'oxList', 'oxmanufacturer' );
                    $this->_oArtManufacturers[] = $oManufacturer;
                }
            }
        }
        return $this->_oArtManufacturers;
    }

    /**
     * Returns currently chosen or first from article category deepest list category parent id
     *
     * @return string
     */
    public function getActCategory()
    {
        if ( $this->_sActCatId === null) {
            $this->_sActCatId   = false;
            $this->_sActCatType = false;

            $aSeoData = oxConfig::getParameter( 'aSeoData' );
            if ( $aSeoData && isset( $aSeoData['oxparams'] ) ) {
                if ( $sData = $aSeoData['oxparams'] ) {
                    $this->_sActCatId   = substr( $sData, strpos( $sData, '#' ) + 1 );
                    $this->_sActCatType = substr( $sData, 0, strpos( $sData, '#' ) );
                }
            } else {
                $oArticle = $this->_getObject( oxConfig::getParameter( 'oxid' ) );
                if ( $this->_getCategoryList( $oArticle )->count() ) {
                    $this->_sActCatType = 'oxcategories';
                    $this->_sActCatId   = $this->_getCategoryList( $oArticle )->current()->oxcategories__oxrootid->value;
                } elseif ( $this->_getVendorList( $oArticle ) && $this->_getVendorList( $oArticle )->count() ) {
                    $this->_sActCatType = 'oxvendor';
                    $this->_sActCatId   = $this->_getVendorList( $oArticle )->current()->getId();
                } elseif ( $this->_getManufacturerList( $oArticle ) && $this->_getManufacturerList( $oArticle )->count() ) {
                    $this->_sActCatType = 'oxmanufacturer';
                    $this->_sActCatId   = $this->_getManufacturerList( $oArticle )->current()->getId();
                }
            }
        }

        return $this->_sActCatId;
    }

    /**
     * Returns objects seo url
     * @param oxarticle $oArticle active article object
     * @return string
     */
    protected function _getSeoUrl( $oArticle )
    {
        // setting cat type and id ..
        $this->getActCategory();

        // choosing type
        switch ( $this->_sActCatType ) {
            case 'oxvendor':
                $sType = 1;
                break;
            case 'oxmanufacturer':
                $sType = 2;
                break;
            default:
                $sType = 0;
        }

        oxSeoEncoderArticle::getInstance()->getArticleUrl( $oArticle, null, $sType );
        return parent::_getSeoUrl( $oArticle );
    }

    /**
     * Returns query for selecting seo url
     *
     * @param object $oObject object to build query
     *
     * @return string
     */
     protected function _getSeoUrlQuery( $oObject, $iShopId )
     {
        return "select oxseourl from oxseo where oxobjectid = '".$oObject->getId()."'
                and oxshopid = '{$iShopId}' and oxlang = {$this->_iEditLang}
                and oxparams = '{$this->_sActCatId}' ";
     }

    /**
     * Returns url type
     * @return string
     */
    protected function _getType()
    {
        return 'oxarticle';
    }

    /**
     * Returns objects std url
     * @return string
     */
    protected function _getStdUrl( $sOxid )
    {
        $oArticle = oxNew( 'oxarticle' );
        $oArticle->loadInLang( $this->_iEditLang, $sOxid );
        $sStdLink = $oArticle->getLink();

        // adding vendor or manufacturer id
        switch ( $this->_sActCatType ) {
        	case 'oxvendor':
                $sStdLink .= '&amp;cnid=v_'.$this->getActCategory();
                break;
            case 'oxmanufacturer':
                $sStdLink .= '&amp;mnid='.$this->getActCategory();
                break;
        }

        return $sStdLink;
    }

    /**
     * Processes parameter before writing to db
     *
     * @param string $sParam parameter to process
     *
     * @return string
     */
    public function processParam( $sParam )
    {
        return trim( substr( $sParam, strpos( $sParam, '#') ), '#' );
    }
}