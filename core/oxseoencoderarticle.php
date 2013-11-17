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
 * @package core
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxseoencoderarticle.php 23319 2009-10-16 14:03:21Z arvydas $
 */

/**
 * Seo encoder for articles
 *
 * @package core
 */
class oxSeoEncoderArticle extends oxSeoEncoder
{
    /**
     * Singleton instance.
     *
     * @var oxSeoEncoderArticle
     */
    protected static $_instance = null;

    /**
     * Product parent title cache
     *
     * @var array
     */
    protected static $_aTitleCache = array();

    /**
     * Singleton method
     *
     * @return oxseoencoder
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = oxNew("oxSeoEncoderArticle");
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            // resetting cache
            self::$_instance->_aSeoCache = array();
        }

        return self::$_instance;
    }

    /**
     * Returns target "extension" (.html)
     *
     * @return string
     */
    protected function _getUrlExtension()
    {
        return '.html';
    }

    /**
     * Checks if current article is in same language as preferred (language id passed by param).
     * In case languages are not the same - reloads article object in different language
     *
     * @param oxarticle $oArticle article to check language
     * @param int       $iLang    user defined language id
     *
     * @return oxarticle
     */
    protected function _getProductForLang( $oArticle, $iLang )
    {
        if ( isset( $iLang ) && $iLang != $oArticle->getLanguage() ) {
            $sId = $oArticle->getId();
            $oArticle = oxNew( 'oxarticle' );
            $oArticle->loadInLang( $iLang, $sId );
        }

        return $oArticle;
    }

    /**
     * Returns SEO uri for passed article and active tag
     *
     * @param object $oArticle article object
     * @param object $iLang    language id [optional]
     *
     * @return string
     */
    protected function _getArticleTagUri( $oArticle, $iLang = null )
    {
        $oView = $this->getConfig()->getActiveView();

        $sTag = null;
        if ( $oView instanceof oxView ) {
            $sTag = $oView->getTag();
        }

        $iShopId = $this->getConfig()->getShopId();
        $sStdUrl = $oArticle->getStdTagLink( $sTag );
        $sSeoUrl = $this->_loadFromDb( 'dynamic', $this->getDynamicObjectId( $iShopId, $sStdUrl ), $iLang );
        if ( !$sSeoUrl ) {

            // generating new if not found
            $sSeoUrl  = oxSeoEncoderTag::getInstance()->getTagUri( $sTag, $iLang );
            $sSeoUrl .= $this->_prepareArticleTitle( $oArticle );
            $sSeoUrl  = $this->_processSeoUrl( $sSeoUrl, $this->_getStaticObjectId( $iShopId, $sStdUrl ), $iLang );

            $sSeoUrl = $this->_getDynamicUri( $sStdUrl, $sSeoUrl, $iLang );
        }

        return $sSeoUrl;
    }

    /**
     * create article uri for given category and save it
     *
     * @param oxArticle  $oArticle  article object
     * @param oxCategory $oCategory category object
     * @param int        $iLang     language to generate uri for
     *
     * @return string
     */
    protected function _createArticleCategoryUri( $oArticle, $oCategory, $iLang = null)
    {
        startProfile(__FUNCTION__);
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }

        $oArticle = $this->_getProductForLang( $oArticle, $iLang );

        // create title part for uri
        $sTitle = $this->_prepareArticleTitle( $oArticle );

        // writing category path
        $sSeoUrl = $this->_processSeoUrl(
                            oxSeoEncoderCategory::getInstance()->getCategoryUri( $oCategory, $iLang ).$sTitle,
                            $oArticle->getId(),
                            $iLang
                        );
        $sCatId = $oCategory->getId();
        $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink($iLang, array('cnid'=>$sCatId)), $sSeoUrl, $iLang, null, 0, false, false, $sCatId);

        stopProfile(__FUNCTION__);

        return $sSeoUrl;
    }

    /**
     * Returns SEO uri for passed article
     *
     * @param oxarticle $oArticle article object
     * @param int       $iLang    language
     *
     * @return string
     */
    protected function _getArticleUri( $oArticle, $iLang = null)
    {
        startProfile(__FUNCTION__);
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }

        $sActCatId = '';
        $oView = $this->getConfig()->getActiveView();
        $oActCat = null;

        if ( $oView instanceof oxview ) {
            $oActCat = $oView->getActCategory();
        }

        if ( $oActCat ) {
            $sActCatId = $oActCat->getId();
        }

        //load details link from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxarticle', $oArticle->getId(), $iLang, null, $sActCatId, true ) ) ) {
            $blInCat  = false;
            if ( $sActCatId ) {
                if ( $oActCat->isPriceCategory() ) {
                    $blInCat = $oArticle->inPriceCategory( $sActCatId );
                } else {
                    $blInCat = $oArticle->inCategory( $sActCatId );
                }
            }
            if ( $blInCat ) {
                $sSeoUrl = $this->_createArticleCategoryUri( $oArticle, $oActCat, $iLang );
            } else {
                $sSeoUrl = $this->_getArticleMainUri( $oArticle, $iLang );
            }
        }

        stopProfile(__FUNCTION__);

        return $sSeoUrl;
    }

    /**
     * Returns SEO uri for passed article
     *
     * @param oxarticle $oArticle article object
     * @param int       $iLang    language
     *
     * @return string
     */
    protected function _getArticleMainUri( $oArticle, $iLang = null)
    {
        startProfile(__FUNCTION__);
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }

        // if variant parent id must be used
        $sArtId = $oArticle->getId();
        if ( isset( $oArticle->oxarticles__oxparentid->value ) && $oArticle->oxarticles__oxparentid->value ) {
            $sArtId = $oArticle->oxarticles__oxparentid->value;
        }

        if ( !( $sMainCatId = oxDb::getDb()->getOne( "select oxcatnid from ".getViewName( "oxobject2category" )." where oxobjectid = '{$sArtId}' order by oxtime" ) ) ) {
            $sMainCatId = '';
        }

        //load default article url from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxarticle', $oArticle->getId(), $iLang, null, $sMainCatId, true ) ) ) {
            if ( $sMainCatId ) {
                $oMainCat = oxNew( "oxcategory" );
                $oMainCat->load( $sMainCatId );
                // save for main category
                $sSeoUrl = $this->_createArticleCategoryUri( $oArticle, $oMainCat, $iLang );
            } else {
                // get default article url
                $oArticle = $this->_getProductForLang( $oArticle, $iLang );
                $sSeoUrl = $this->_processSeoUrl( $this->_prepareArticleTitle( $oArticle ), $oArticle->getId(), $iLang );

                // save default article url
                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink($iLang, array( 'cnid' => '' ) ), $sSeoUrl, $iLang, null, 0, false, false, '' );
            }
        }

        stopProfile(__FUNCTION__);
        return $sSeoUrl;
    }

    /**
     * Returns seo title for current article (if oxtitle field is empty, oxartnum is used).
     * Additionally - if oxvarselect is set - title is appended with its value
     *
     * @param oxarticle $oArticle article object
     *
     * @return string
     */
    protected function _prepareArticleTitle( $oArticle )
    {
        $sTitle = '';

        // create title part for uri
        if ( !( $sTitle = $oArticle->oxarticles__oxtitle->value ) ) {
            // taking parent article title
            if ( ( $sParentId = $oArticle->oxarticles__oxparentid->value ) ) {

                // looking in cache ..
                if ( !isset( self::$_aTitleCache[$sParentId] ) ) {
                    $oDb = oxDb::getDb();
                    $sQ = "select oxtitle from oxarticles where oxid = ".$oDb->quote( $sParentId );
                    self::$_aTitleCache[$sParentId] = $oDb->getOne( $sQ );
                }
                $sTitle = self::$_aTitleCache[$sParentId];
            }
        }

        // variant has varselect value
        if ( $oArticle->oxarticles__oxvarselect->value ) {
            $sTitle .= ( $sTitle ? ' ' : '' ).$oArticle->oxarticles__oxvarselect->value . ' ';
        }

        // in case nothing was found - looking for number
        if ( !$sTitle ) {
            $sTitle .= $oArticle->oxarticles__oxartnum->value;
        }

        return $this->_prepareTitle( $sTitle ) . '.html';
    }

    /**
     * Returns vendor seo uri for current article
     *
     * @param oxarticle $oArticle article object
     * @param int       $iLang    language id (optional)
     *
     * @return string
     */
    protected function _getArticleVendorUri( $oArticle, $iLang = null )
    {
        startProfile(__FUNCTION__);
        if ( !isset( $iLang ) ) {
            $iLang = $oArticle->getLanguage();
        }

        $sActVendorId = $oArticle->oxarticles__oxvendorid->value;
        $oVendor = oxNew( 'oxvendor' );
        if ( !$sActVendorId || !$oVendor->load( $sActVendorId ) ) {
            $oVendor = null;
        }

        //load details link from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxarticle', $oArticle->getId(), $iLang, null, $sActVendorId, true ) ) ) {

            $oArticle = $this->_getProductForLang( $oArticle, $iLang );

            // create title part for uri
            $sTitle = $this->_prepareArticleTitle( $oArticle );

            // create uri for all categories
            if ( !$sActVendorId || !$oVendor ) {
                $sSeoUrl = $this->_processSeoUrl( $sTitle, $oArticle->getId(), $iLang );
                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink($iLang, array('cnid'=>'')), $sSeoUrl, $iLang );
            } else {
                $sSeoUrl = oxSeoEncoderVendor::getInstance()->getVendorUri( $oVendor, $iLang );
                $sSeoUrl = $this->_processSeoUrl( $sSeoUrl . $sTitle, $oArticle->getId(), $iLang );

                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink($iLang, array('cnid'=>'')), $sSeoUrl, $iLang, null, 0, false, false, $sActVendorId );
            }
        }

        stopProfile(__FUNCTION__);

        return $sSeoUrl;
    }

    /**
     * Returns manufacturer seo uri for current article
     *
     * @param oxarticle $oArticle article object
     * @param int       $iLang    language id (optional)
     *
     * @return string
     */
    protected function _getArticleManufacturerUri( $oArticle, $iLang = null )
    {
        startProfile(__FUNCTION__);
        if ( !isset( $iLang ) ) {
            $iLang = $oArticle->getLanguage();
        }

        $sActManufacturerId = $oArticle->oxarticles__oxmanufacturerid->value;
        $oManufacturer = oxNew( 'oxmanufacturer' );
        if ( !$sActManufacturerId || !$oManufacturer->load( $sActManufacturerId ) ) {
            $oManufacturer = null;
        }

        //load details link from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxarticle', $oArticle->getId(), $iLang, null, $sActManufacturerId, true ) ) ) {

            $oArticle = $this->_getProductForLang( $oArticle, $iLang );

            // create title part for uri
            $sTitle = $this->_prepareArticleTitle( $oArticle );

            // create uri for all categories
            if ( !$sActManufacturerId || !$oManufacturer ) {
                $sSeoUrl = $this->_processSeoUrl( $sTitle, $oArticle->getId(), $iLang );
                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink($iLang, array('cnid'=>'')), $sSeoUrl, $iLang );
            } else {
                $sSeoUrl = oxSeoEncoderManufacturer::getInstance()->getManufacturerUri( $oManufacturer, $iLang );
                $sSeoUrl = $this->_processSeoUrl( $sSeoUrl . $sTitle, $oArticle->getId(), $iLang );

                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink($iLang, array('cnid'=>'')), $sSeoUrl, $iLang, null, 0, false, false, $sActManufacturerId );
            }
        }

        stopProfile(__FUNCTION__);

        return $sSeoUrl;
    }

    /**
     * return article main url, with path of its default category
     *
     * @param <type> $oArticle
     * @param <type> $iLang
     * @return <type>
     */
    public function getArticleMainUrl( $oArticle, $iLang = null )
    {
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }
        return $this->_getFullUrl( $this->_getArticleMainUri( $oArticle, $iLang ), $iLang );
    }

    /**
     * Encodes article URLs into SEO format
     *
     * @param oxArticle $oArticle Article object
     * @param int       $iLang    language
     * @param int       $iType    type
     *
     * @return string
     */
    public function getArticleUrl( $oArticle, $iLang = null, $iType = 0 )
    {
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }

        $sUri = '';
        switch ( $iType ) {
            case 1 :
                $sUri = $this->_getArticleVendorUri( $oArticle, $iLang );
                break;
            case 2 :
                $sUri = $this->_getArticleManufacturerUri( $oArticle, $iLang );
                break;
            case 4 :
                $sUri = $this->_getArticleTagUri( $oArticle, $iLang );
                break;
            case 3 : // goes price category urls to default (category urls)
            default:
                $sUri = $this->_getArticleUri( $oArticle, $iLang );
                break;
        }

        return $this->_getFullUrl( $sUri, $iLang );
    }

    /**
     * deletes article seo entries
     *
     * @param oxarticle $oArticle article to remove
     *
     * @return null
     */
    public function onDeleteArticle($oArticle)
    {
        $sIdQuoted = oxDb::getDb()->quote($oArticle->getId());
        oxDb::getDb()->execute("delete from oxseo where oxobjectid = $sIdQuoted and oxtype = 'oxarticle'");
    }
}
