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
 * $Id: oxseoencoderarticle.php 22590 2009-09-24 06:24:00Z alfonsas $
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
     * Returns SEO uri for passed article and price category
     *
     * @param oxarticle $oArticle article object
     * @param int       $iLang    language id [optional]
     *
     * @return string
     */
    protected function _getArticlePriceCategoryUri( $oArticle, $iLang = null)
    {
        startProfile(__FUNCTION__);
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }

        $sActCatId = '';
        $oView = $this->getConfig()->getActiveView();
        $oCategory = null;

        if ( $oView instanceof oxView ) {
            $oCategory = $oView->getActCategory();
        }

        if ( $oCategory ) {
            // in case of price category using its id
            $sActCatId = $oCategory->getId();
        }

        //load details link from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxarticle', $oArticle->getId(), $iLang, null, $sActCatId, true ) ) ) {

            $oArticle = $this->_getProductForLang( $oArticle, $iLang );

            // writing category path
            $sSeoUrl  = oxSeoEncoderCategory::getInstance()->getCategoryUri( $oCategory );
            $sSeoUrl .= $this->_prepareArticleTitle( $oArticle );
            $sSeoUrl  = $this->_processSeoUrl( $sSeoUrl, $oArticle->getId(), $iLang );

            $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sSeoUrl, $iLang, null, 0, false, false, $sActCatId );
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
            $sActCatId = $oActCat->oxcategories__oxrootid->value;
        }

        //load details link from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxarticle', $oArticle->getId(), $iLang, null, $sActCatId, $sActCatId ? true : false ) ) ) {

            $oArticle = $this->_getProductForLang( $oArticle, $iLang );

            // create title part for uri
            $sTitle = $this->_prepareArticleTitle( $oArticle );

            // create uri for all categories
            $oCategorys = $this->getSeoCategories( $oArticle, $iLang );
            if (!$oCategorys->count()) {
                $sSeoUrl = $this->_processSeoUrl( $sTitle, $oArticle->getId(), $iLang );
                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sSeoUrl, $iLang );
            } else {
                $sTmpSeoUrl = '';
                $oEncoder = oxSeoEncoderCategory::getInstance();
                foreach ($oCategorys as $oCategory) {
                    // writing category path
                    $sTmpSeoUrl = $oEncoder->getCategoryUri( $oCategory );
                    $sTmpSeoUrl .= $sTitle;
                    $sTmpSeoUrl  = $this->_processSeoUrl( $sTmpSeoUrl, $oArticle->getId(), $iLang );

                    $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sTmpSeoUrl, $iLang, null, 0, false, false, $oCategory->oxcategories__oxrootid->value);
                    if ($oCategory->oxcategories__oxrootid->value == $sActCatId) {
                        $sSeoUrl = $sTmpSeoUrl;
                    }
                }
                if (!$sSeoUrl) {
                    // seo url not found, use any
                    $sSeoUrl = $sTmpSeoUrl;
                }
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

        return $this->_prepareTitle( $sTitle . '.html' );
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
                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sSeoUrl, $iLang );
            } else {
                $sSeoUrl = oxSeoEncoderVendor::getInstance()->getVendorUri( $oVendor, $iLang );
                $sSeoUrl = $this->_processSeoUrl( $sSeoUrl . $sTitle, $oArticle->getId(), $iLang );

                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sSeoUrl, $iLang, null, 0, false, false, $sActVendorId );
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
                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sSeoUrl, $iLang );
            } else {
                $sSeoUrl = oxSeoEncoderManufacturer::getInstance()->getManufacturerUri( $oManufacturer, $iLang );
                $sSeoUrl = $this->_processSeoUrl( $sSeoUrl . $sTitle, $oArticle->getId(), $iLang );

                $this->_saveToDb( 'oxarticle', $oArticle->getId(), $oArticle->getStdLink(), $sSeoUrl, $iLang, null, 0, false, false, $sActManufacturerId );
            }
        }

        stopProfile(__FUNCTION__);

        return $sSeoUrl;
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
            case 3 :
                $sUri = $this->_getArticlePriceCategoryUri( $oArticle, $iLang );
                break;
            case 4 :
                $sUri = $this->_getArticleTagUri( $oArticle, $iLang );
                break;
            default:
                $sUri = $this->_getArticleUri( $oArticle, $iLang );
                break;
        }

        return $this->_getFullUrl( $sUri, $iLang );

    }

    /**
     * Returns array of suitable categories for given article
     *
     * @param oxArticle $oArticle article to search
     * @param int       $iLang    language
     *
     * @return oxList
     */
    public function getSeoCategories( $oArticle, $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = $oArticle->getLanguage();
        }
        $sArtId = $oArticle->getId();
        if ( isset( $oArticle->oxarticles__oxparentid->value ) && $oArticle->oxarticles__oxparentid->value ) {
            $sArtId = $oArticle->oxarticles__oxparentid->value;
        }

        $oDb = oxDb::getDb( false );
        $sArtIdQuoted = $oDb->quote( $sArtId );

        // checking cache
        $sCatTable = getViewName('oxcategories');

        $sQ = "select distinct catroots.oxrootid
                from oxobject2category as o2c
                left join {$sCatTable} as catroots
                    on o2c.oxcatnid=catroots.oxid
                where o2c.oxobjectid = $sArtIdQuoted
                order by o2c.oxtime";

        $aRoots = $oDb->getAll($sQ);

        $oList = oxNew('oxList', 'oxcategory');
        foreach ($aRoots as $aRootId) {
            $sQ = "select node.* _depth from
                    ( select oxcatnid from oxobject2category
                            where oxobjectid = $sArtIdQuoted order by oxtime
                        ) as sub
                        left join {$sCatTable} as node
                            on sub.oxcatnid=node.oxid
                        join {$sCatTable} as parent
                            on node.oxrootid = parent.oxrootid
                    where node.oxrootid = ".$oDb->quote( $aRootId[0] )."
                        and node.oxleft between parent.oxleft and parent.oxright
                group by node.oxid order by (count( parent.oxid ) ) desc limit 1";

            $oCat = oxNew('oxcategory');
            $oCat->setLanguage($iLang);
            if ($oCat->assignRecord($sQ)) {
                $oList[] = $oCat;
            }
        }
        return $oList;
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
