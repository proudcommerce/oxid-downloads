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
 * $Id: article_seo.php 22624 2009-09-24 14:45:24Z rimvydas.paskevicius $
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
        $this->_aViewData["sCatType"]        = $this->_sActCatType;
        $this->_aViewData["sCatLang"]        = $this->_iActCatLang;

        return parent::render();
    }

    /**
     * Returns SQL to fetch seo data
     *
     * @return string
     */
    protected function _getSeoDataSql( $oObject, $iShopId, $iLang )
    {
        $oDb = oxDb::getDb();
        if ( $this->_sActCatType == 'oxtag' ) {
            $sObjectId = oxSeoEncoderArticle::getInstance()->getDynamicObjectId( $iShopId, $oObject->getStdTagLink( $this->getTag() ) );
            $sQ = "select * from oxseo where oxobjectid = ".$oDb->quote( $sObjectId ) . " and
                   oxshopid = '{$iShopId}' and oxlang = {$this->_iActCatLang} ";
        } else {
            $sParam = ( $sCat = $this->getSelectedCategoryId() ) ? " and oxparams = '$sCat' " : '';
            $sQ = "select * from oxseo where oxobjectid = ".$oDb->quote( $oObject->getId() ) . " and
                   oxshopid = '{$iShopId}' and oxlang = {$iLang} {$sParam} ";
        }
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

            // adding price categories
            $sCatTable = "oxcategories";
            $oDb = oxDb::getDb( true );
            $sQ = "select oxid from $sCatTable where ( oxpricefrom != 0 || oxpriceto != 0 ) and ( oxpricefrom <= ".$oDb->quote( $oArticle->oxarticles__oxprice->value ) ." || oxpriceto >= ".$oDb->quote( $oArticle->oxarticles__oxprice->value ) ." ) ";
            $rs = $oDb->execute( $sQ );
            if ( $rs != false && $rs->recordCount() > 0 ) {
                while ( !$rs->EOF ) {
                    $oCat = oxNew('oxcategory');
                    $oCat->setLanguage($iLang);
                    if ( $oCat->load( $rs->fields['oxid'] ) ) {
                        $this->_oArtCategories[] = $oCat;
                    }
                    $rs->moveNext();
                }
            }

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
     * Returns tag list
     *
     * @return oxlist
     */
    protected function _getTagList( $oArticle )
    {
        $oTagCloud = oxNew("oxTagCloud");
        $aLangs = $oArticle->getAvailableInLangs();

        $aLangTags = array();
        foreach ( $aLangs as $iLang => $sLangTitle ) {
            if ( count( $aTags = $oTagCloud->getTags( $oArticle->getId(), false, $iLang ) ) ) {
                $aLangTags[$iLang] = $aTags;
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
                    $this->_sActCatId   = $oList->current()->oxcategories__oxrootid->value;
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
     * @param oxarticle $oArticle active article object
     * @return string
     */
    protected function _getSeoUrl( $oArticle )
    {
        // setting cat type and id ..
        $this->getSelectedCategoryId();

        // choosing type
        switch ( $this->_sActCatType ) {
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
        }

        oxSeoEncoderArticle::getInstance()->getArticleUrl( $oArticle, $this->getEditLang(), $sType );
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
        if ( $oCat->load( $this->_sActCatId ) ) {
            return $oCat;
        }
    }

    /**
     * Returns editable tag id if available
     *
     * @return string | null
     */
    public function getTag()
    {
        $sTag = $this->getSelectedCategoryId();
        if ( $this->_sActCatType == 'oxtag' ) {
            return $sTag;
        }
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
        $oDb = oxDb::getDb();

         // tag type urls are loaded differently from others..
        if ( $sTag = $this->getTag() ) {

            $sStdUrl = "index.php?cl=details&amp;anid=".$oObject->getId()."&amp;listtype=tag&amp;searchtag=".rawurlencode( $sTag );
            $sObjectId = md5( strtolower( $oObject->getShopId() . $sStdUrl ) );
            $sQ = "select oxseourl from oxseo where oxobjectid = ".$oDb->quote( $sObjectId )."
                   and oxshopid = '{$iShopId}' and oxlang = {$this->_iActCatLang}";
        } else {
            $sQ = "select oxseourl from oxseo where oxobjectid = ".$oDb->quote( $oObject->getId() )."
                   and oxshopid = '{$iShopId}' and oxlang = {$this->_iEditLang}
                   and oxparams = '{$this->_sActCatId}' ";
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
            $iLang = (int) $this->_iActCatLang;
        }
        return $iLang;
    }

    /**
     * Returns seo entry ident
     *
     * @return
     */
    protected function getSeoEntryId()
    {
        if ( $sTag = $this->getTag() ) {
            $oObject = $this->_getObject( oxConfig::getParameter( 'oxid' ) );
            $sStdUrl = "index.php?cl=details&amp;anid=".$oObject->getId()."&amp;listtype=tag&amp;searchtag=".rawurlencode( $sTag );
            return md5( strtolower( $oObject->getShopId() . $sStdUrl ) );
        } else {
            return parent::getSeoEntryId();
        }
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
                $sStdLink .= '&amp;cnid=v_'.$this->getSelectedCategoryId();
                break;
            case 'oxmanufacturer':
                $sStdLink .= '&amp;mnid='.$this->getSelectedCategoryId();
                break;
            case 'oxtag':
                $sStdLink = "index.php?cl=details&amp;anid=".$oArticle->getId()."&amp;listtype=tag&amp;searchtag=".rawurlencode( $this->getTag() );
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
        if ($this->getTag()) {
            return '';
        } else {
            return trim( substr( $sParam, strpos( $sParam, '#') ), '#' );
        }
    }
}