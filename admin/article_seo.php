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
 * @version   SVN: $Id: article_seo.php 31235 2010-11-25 13:53:04Z alfonsas $
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
     * Chosen category type
     *
     * @var string
     */
    protected $_iActCatLang = null;

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
     *
     * @return
     */
    protected $_sNoCategoryId = '__nonecatid';

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
        $this->_aViewData["oCategories"]     = $this->_getCategoryList( $oArticle );
        $this->_aViewData["oVendors"]        = $this->_getVendorList( $oArticle );
        $this->_aViewData["oManufacturers"]  = $this->_getManufacturerList( $oArticle );
        $this->_aViewData["oTags"]           = $this->_getTagList( $oArticle );
        $this->_aViewData["sCatId"]          = $this->getSelectedCategoryId();
        $this->_aViewData["sCatType"]        = $this->getActCatType();
        $this->_aViewData["sCatLang"]        = $this->getActCategoryLang();

        return parent::render();
    }

    /**
     * Returns SQL to fetch seo data
     *
     * @param object $oObject Object
     * @param int    $iShopId Shop ID
     * @param int    $iLang   Language ID
     *
     * @return string
     */
    protected function _getSeoDataSql( $oObject, $iShopId, $iLang )
    {
        $oDb = oxDb::getDb();
        if ( $this->getActCatType() == 'oxtag' ) {
            $sObjectId = $this->_getEncoder()->getDynamicObjectId( $iShopId, $oObject->getStdTagLink( $this->getTag() ) );
            $sQ = "select * from oxseo
                   left join oxobject2seodata on
                       oxobject2seodata.oxobjectid = ".$oDb->quote( $oObject->getId() ) . " and
                       oxobject2seodata.oxshopid = oxseo.oxshopid and
                       oxobject2seodata.oxlang = oxseo.oxlang
                   where
                       oxseo.oxobjectid = ".$oDb->quote( $sObjectId ) ."
                       and oxseo.oxshopid = '{$iShopId}' and oxseo.oxlang = ".$this->getActCategoryLang();
        } else {
            $sParam = ( $sCat = $this->getSelectedCategoryId() ) ? " and oxseo.oxparams = '$sCat' " : '';
            $sQ = "select * from oxseo
                   left join oxobject2seodata on
                       oxobject2seodata.oxobjectid = oxseo.oxobjectid and
                       oxobject2seodata.oxshopid = oxseo.oxshopid and
                       oxobject2seodata.oxlang = oxseo.oxlang
                    where oxseo.oxobjectid = ".$oDb->quote( $oObject->getId() ) . "
                    and oxseo.oxshopid = '{$iShopId}' and oxseo.oxlang = {$iLang} {$sParam} ";
        }
        return $sQ;
    }

    /**
     * Returns list with deepest article categories
     *
     * @param object $oArticle Article object
     *
     * @return oxlist
     */
    protected function _getCategoryList( $oArticle )
    {
        if ( $this->_oArtCategories === null && $oArticle ) {
            // adding categories
            $sO2CView = getViewName( 'oxobject2category');
            $oDb = oxDb::getDb( true );
            $sQ = "select oxobject2category.oxcatnid as oxid from $sO2CView as oxobject2category where oxobject2category.oxobjectid="
                  . $oDb->quote( $oArticle->getId() ) . " union ".$oArticle->getSqlForPriceCategories('oxid');

            $iLang = $this->getEditLang();
            $this->_oArtCategories = oxNew( "oxList" );
            $rs = $oDb->execute( $sQ );
            if ( $rs != false && $rs->recordCount() > 0 ) {
                while ( !$rs->EOF ) {
                    $oCat = oxNew('oxcategory');
                    $oCat->setLanguage( $iLang );
                    if ( $oCat->load( current( $rs->fields ) ) ) {
                        $this->_oArtCategories->offsetSet( $oCat->getId(), $oCat );
                    }
                    $rs->moveNext();
                }
            }

            $this->_setMainCategory( $oArticle, $this->_oArtCategories);
        }

        return $this->_oArtCategories;
    }

    /**
     * Marks category from list as main
     *
     * @param oxarticle $oProduct active product object
     * @param oxlist    $oCatList category list
     *
     * @return null
     */
    protected function _setMainCategory( $oProduct, $oCatList )
    {
        // loading main category
        if ( !( $oMainCat = $oProduct->getCategory() ) ) {
            $sTitle = oxLang::getInstance()->translateString( '(no category)', $this->_iEditLang );
            $oMainCat = oxNew( "oxCategory" );
            $oMainCat->setId( $this->_sNoCategoryId );
            $oMainCat->oxcategories__oxtitle = new oxField( $sTitle, oxField::T_RAW );
        }

        $sSuffix = oxLang::getInstance()->translateString( '(main category)', $this->_iEditLang );
        $oMainCat->oxcategories__oxtitle = new oxField( $oMainCat->oxcategories__oxtitle->getRawValue()." ".$sSuffix, oxField::T_RAW );

        // overriding
        $oCatList->offsetSet( $oMainCat->getId(), $oMainCat );
    }

    /**
     * Returns list with deepest article categories
     *
     * @param object $oArticle Article object
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
     * @param object $oArticle Article object
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
     * Returns tag list
     *
     * @param object $oArticle Article object
     *
     * @return oxlist
     */
    protected function _getTagList( $oArticle )
    {
        $oTagCloud = oxNew("oxTagCloud");
        $aLangs = $oArticle ? $oArticle->getAvailableInLangs() : array();

        $aLangTags = array();
        foreach ( $aLangs as $iLang => $sLangTitle ) {
            if ( count( $aTags = $oTagCloud->getTags( $oArticle->getId(), false, $iLang ) ) ) {
                $aLangTags[$iLang] = array();
                foreach ($aTags as $sTitle => $sValue) {
                    $aLangTags[$iLang][$oTagCloud->getTagTitle($sTitle)] = $sValue;
                }
            }
        }

        return $aLangTags;
    }

    /**
     * Returns currently chosen or first from article category deepest list category parent id
     *
     * @return string
     */
    public function getSelectedCategoryId()
    {
        if ( $this->_sActCatId === null) {
            $this->_sActCatId   = false;
            $this->_sActCatType = false;

            $aSeoData = oxConfig::getParameter( 'aSeoData' );
            if ( $aSeoData && isset( $aSeoData['oxparams'] ) ) {
                if ( $sData = $aSeoData['oxparams'] ) {
                    $this->_sActCatId = substr( $sData, strpos( $sData, '#' ) + 1 );
                    if ( strpos( $this->_sActCatId, '#' ) !== false ) {
                        $this->_sActCatId = substr( $this->_sActCatId, 0, strpos( $this->_sActCatId, '#' ) );
                    }
                    $this->_iActCatLang = substr( $sData, strrpos( $sData, '#' ) + 1 );
                    $this->_sActCatType = substr( $sData, 0, strpos( $sData, '#' ) );
                }
            } else {
                $oArticle = $this->_getObject( oxConfig::getParameter( 'oxid' ) );
                if ( ( $oList = $this->_getCategoryList( $oArticle ) ) && $oList->count() ) {
                    $this->_sActCatType = 'oxcategories';
                    $this->_sActCatId   = $oList->current()->getId();
                } elseif ( ( $oList = $this->_getVendorList( $oArticle ) ) && $oList->count() ) {
                    $this->_sActCatType = 'oxvendor';
                    $this->_sActCatId   = $oList->current()->getId();
                } elseif ( ( $oList = $this->_getManufacturerList( $oArticle ) ) && $oList->count() ) {
                    $this->_sActCatType = 'oxmanufacturer';
                    $this->_sActCatId   = $oList->current()->getId();
                } elseif ( ( $aTagList = $this->_getTagList( $oArticle ) ) && count( $aTagList ) ) {
                    $this->_sActCatType = 'oxtag';
                    $this->_sActCatId   = key( $aTagList );
                }
            }
        }

        return $this->_sActCatId;
    }

    /**
     * Returns objects seo url
     *
     * @param oxarticle $oArticle active article object
     *
     * @return string
     */
    protected function _getSeoUrl( $oArticle )
    {
        // setting cat type and id ..
        $this->getSelectedCategoryId();

        // choosing type
        switch ( $this->getActCatType() ) {
            case 'oxvendor':
                $sType = OXARTICLE_LINKTYPE_VENDOR;
                break;
            case 'oxmanufacturer':
                $sType = OXARTICLE_LINKTYPE_MANUFACTURER;
                break;
            case 'oxtag':
                $sType = OXARTICLE_LINKTYPE_TAG;
                break;
            default:
                $sType = OXARTICLE_LINKTYPE_CATEGORY;
                $oCat = oxNew( 'oxcategory' );
                $oCat->load( $this->_sActCatId );
                if ( $oCat->isPriceCategory() ) {
                    $sType = OXARTICLE_LINKTYPE_PRICECATEGORY;
                }
                break;
        }

        $this->_getEncoder()->getArticleUrl( $oArticle, $this->getEditLang(), $sType );
        return parent::_getSeoUrl( $oArticle );
    }

    /**
     * In case price category is opened - returns its object
     *
     * @return oxcategory | null
     */
    public function getActCategory()
    {
        $oCat = oxNew( 'oxcategory' );
        return ( $oCat->load( $this->_sActCatId ) ) ? $oCat : null;
    }

    /**
     * Returns editable tag id if available
     *
     * @return string | null
     */
    public function getTag()
    {
        $sTag = $this->getSelectedCategoryId();
        return ( $this->getActCatType() == 'oxtag' ) ? $sTag : null;
    }

    /**
     * Returns active vendor object if available
     *
     * @return oxvendor | null
     */
    public function getActVendor()
    {
        $oVendor = oxNew( 'oxvendor' );
        return ( $this->getActCatType() == 'oxvendor' && $oVendor->load( $this->_sActCatId ) ) ? $oVendor : null;
    }

    /**
     * Returns active manufacturer object if available
     *
     * @return oxmanufacturer | null
     */
    public function getActManufacturer()
    {
        $oManufacturer = oxNew( 'oxmanufacturer' );
        return ( $this->getActCatType() == 'oxmanufacturer' && $oManufacturer->load( $this->_sActCatId ) ) ? $oManufacturer : null;
    }

    /**
     * Returns list type for current seo url
     *
     * @return string
     */
    public function getListType()
    {
        $sListType = '';
        switch ( $this->getActCatType() ) {
            case 'oxvendor':
                $sListType = 'vendor';
                break;
            case 'oxmanufacturer':
                $sListType = 'manufacturer';
                break;
            case 'oxtag':
                $sListType = 'tag';
                break;
            default:
                break;
        }
        return $sListType;
    }

    /**
     * Returns query for selecting seo url
     *
     * @param object $oObject object to build query
     * @param int    $iShopId Shop id
     *
     * @return string
     */
    protected function _getSeoUrlQuery( $oObject, $iShopId )
    {
        $oDb = oxDb::getDb();

         // tag type urls are loaded differently from others..
        if ( ( $sTag = $this->getTag() ) ) {

            $sStdUrl = "index.php?cl=details&amp;anid=".$oObject->getId()."&amp;listtype=tag&amp;searchtag=".rawurlencode( $sTag );
            $sObjectId = md5( strtolower( $oObject->getShopId() . $sStdUrl ) );
            $sQ = "select oxseourl from oxseo where oxobjectid = ".$oDb->quote( $sObjectId ).
                  " and oxshopid = '{$iShopId}' and oxlang = ".$this->getActCategoryLang();
        } else {
            $sCatId = ( $this->_sActCatId == $this->_sNoCategoryId ) ? '' : $this->_sActCatId;
            $sQ = "select oxseourl from oxseo where oxobjectid = ".$oDb->quote( $oObject->getId() ).
                  " and oxshopid = '{$iShopId}' and oxlang = {$this->_iEditLang}".
                  " and oxparams = '{$sCatId}' ";
        }

        return $sQ;
    }

    /**
     * Returns edit language id. In case current url is tag url - returns tag language
     *
     * @return int
     */
    public function getEditLang()
    {
        $iLang = $this->_iEditLang;
        if ( $this->getTag() ) {
            $iLang = $this->getActCategoryLang();
        }
        return $iLang;
    }

    /**
     * Returns active edit language id
     *
     * @return int
     */
    public function getActCategoryLang()
    {
        return (int) $this->_iActCatLang;
    }

    /**
     * Returns seo entry ident
     *
     * @return string
     */
    protected function _getSeoEntryId()
    {
        $sId = '';
        if ( $sTag = $this->getTag() ) {
            $oObject = $this->_getObject( oxConfig::getParameter( 'oxid' ) );
            $sId = md5( strtolower( $oObject->getShopId() . $this->_getStdUrl( $oObject->getId() ) ) );
        } else {
            $sId = parent::_getSeoEntryId();
        }
        return $sId;
    }

    /**
     * Returns alternative seo entry id
     *
     * @return null
     */
    protected function _getAltSeoEntryId()
    {
        return oxConfig::getParameter( 'oxid' );
    }

    /**
     * Returns seo entry ident
     *
     * @deprecated should be used object_seo::_getSeoEntryId()
     *
     * @return string
     */
    protected function getSeoEntryId()
    {
        return $this->_getSeoEntryId();
    }

    /**
     * Returns seo entry type
     *
     * @return string
     */
    protected function _getSeoEntryType()
    {
        if ( $this->getTag() ) {
            return 'dynamic';
        } else {
            return $this->_getType();
        }
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
        return 'oxarticle';
    }

    /**
     * Returns objects std url
     *
     * @param string $sOxid object id
     *
     * @return string
     */
    protected function _getStdUrl( $sOxid )
    {
        $oArticle = oxNew( 'oxarticle' );
        $oArticle->loadInLang( $this->_iEditLang, $sOxid );
        $sStdLink = $oArticle->getBaseStdLink( $this->_iEditLang, true, false );
        if ( $sListType = $this->getListType() ) {
            $sStdLink .= "&amp;listtype={$sListType}";
        }

        $sCatId = $this->getSelectedCategoryId();
        $sCatId = ( $sCatId == $this->_sNoCategoryId ) ? false : $sCatId;

        // adding vendor or manufacturer id
        switch ( $this->getActCatType() ) {
            case 'oxvendor':
                $sStdLink .= "&amp;cnid=v_{$sCatId}";
                break;
            case 'oxmanufacturer':
                $sStdLink .= "&amp;mnid={$sCatId}";
                break;
            case 'oxtag':
                $sStdLink .= "&amp;searchtag=".rawurlencode( $this->getTag() );
                break;
            default:
                $sStdLink .= "&amp;cnid={$sCatId}";
                break;
        }

        return $sStdLink;
    }

    /**
     * Returns active category type
     *
     * @return string
     */
    public function getActCatType()
    {
        return $this->_sActCatType;
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
        if ($this->getTag()) {
            return '';
        } else {
            return trim( substr( $sParam, strpos( $sParam, '#') ), '#' );
        }
    }

    /**
     * Returns id used to identify situation when product has no category assigned
     *
     * @return string
     */
    public function getNoCatId()
    {
        return $this->_sNoCategoryId;
    }

    /**
     * Returns current object type seo encoder object
     *
     * @return oxSeoEncoderCategory
     */
    protected function _getEncoder()
    {
        return oxSeoEncoderArticle::getInstance();
    }
}
