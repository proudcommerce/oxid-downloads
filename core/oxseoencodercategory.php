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
 * $Id: oxseoencodercategory.php 20953 2009-07-15 13:36:51Z arvydas $
 */

/**
 * Seo encoder base
 *
 * @package core
 */
class oxSeoEncoderCategory extends oxSeoEncoder
{
    /**
     * Singleton instance.
     */
    protected static $_instance = null;

    /**
     * _aCatCache cache for categories
     *
     * @var array
     * @access protected
     */
    protected $_aCatCache = array();

    /**
     * Singleton method
     *
     * @return oxseoencoder
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = oxNew("oxSeoEncoderCategory");
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            // resetting cache
            self::$_instance->_aSeoCache = array();
        }

        return self::$_instance;
    }

    /**
     * Returns target "extension" (/)
     *
     * @return string
     */
    protected function _getUrlExtension()
    {
        return '/';
    }

    /**
     * _categoryUrlLoader loads category from db
     * returns false if cat needs to be encoded (load failed)
     *
     * @param oxCategory $oCat  category object
     * @param int        $iLang active language id
     *
     * @access protected
     *
     * @return boolean
     */
    protected function _categoryUrlLoader( $oCat, $iLang )
    {
        $sSeoUrl = false;

        $sCacheId = $this->_getCategoryCacheId( $oCat, $iLang );
        if ( isset( $this->_aCatCache[$sCacheId] ) ) {
            $sSeoUrl = $this->_aCatCache[ $sCacheId ];
        } elseif ( ( $sSeoUrl = $this->_loadFromDb( 'oxcategory', $oCat->getId(), $iLang ) ) ) {
            // caching
            $this->_aCatCache[ $sCacheId ] = $sSeoUrl;
        }

        return $sSeoUrl;
    }

    /**
     * _getCatecgoryCacheId return string for isntance cache id
     *
     * @param oxCategory $oCat  category object
     * @param int        $iLang active language
     *
     * @access private
     *
     * @return string
     */
    private function _getCategoryCacheId( $oCat, $iLang )
    {
        return $oCat->getId() . '_' . ( (int) $iLang );
    }

    /**
     * Returns SEO uri for passed category
     *
     * @param oxcategory $oCat  category object
     * @param int        $iLang language
     *
     * @return string
     */
    public function getCategoryUri( $oCat, $iLang = null )
    {
        startProfile(__FUNCTION__);
        $sCatId = $oCat->getId();

        // skipping external category URLs
        if ( $oCat->oxcategories__oxextlink->value ) {
            $sSeoUrl = null;
        } else {
            // not found in cache, process it from the top
            if (!isset($iLang)) {
                $iLang = $oCat->getLanguage();
            }

            $aCacheMap = array();
            $aStdLinks = array();

            while ( $oCat && !($sSeoUrl = $this->_categoryUrlLoader( $oCat, $iLang ) )) {

                if ($iLang != $oCat->getLanguage()) {
                    $sId = $oCat->getId();
                    $oCat = oxNew('oxcategory');
                    $oCat->loadInLang($iLang, $sId);
                }

                // prepare oCat title part
                $sTitle = $this->_prepareTitle( $oCat->oxcategories__oxtitle->value );

                foreach ( array_keys( $aCacheMap ) as $id ) {
                    $aCacheMap[$id] = $sTitle . '/' . $aCacheMap[$id];
                }

                $aCacheMap[$oCat->getId()] = $sTitle;
                $aStdLinks[$oCat->getId()] = $oCat->getStdLink();

                // load parent
                $oCat = $oCat->getParentCategory();
            }

            foreach ( $aCacheMap as $sId => $sUri ) {
                $this->_aCatCache[$sId.'_'.$iLang] = $this->_processSeoUrl( $sSeoUrl.$sUri.'/', $sId, $iLang );
                $this->_saveToDb( 'oxcategory', $sId, $aStdLinks[$sId], $this->_aCatCache[$sId.'_'.$iLang], $iLang );
            }

            $sSeoUrl = $this->_aCatCache[$sCatId.'_'.$iLang];
        }

        stopProfile(__FUNCTION__);

        return $sSeoUrl;
    }


    /**
     * Returns category SEO url for specified page
     *
     * @param oxcategory $oCategory category object
     * @param int        $iPage     page tu prepare number
     * @param int        $iLang     language
     * @param bool       $blFixed   fixed url marker (default is false)
     *
     * @return string
     */
    public function getCategoryPageUrl( $oCategory, $iPage, $iLang = null, $blFixed = false )
    {
        if (!isset($iLang)) {
            $iLang = $oCategory->getLanguage();
        }
        $sStdUrl = $oCategory->getStdLink() . '&amp;pgNr=' . $iPage;
        $sParams = sprintf( "%0" . ceil( $this->_iCntPages / 10 + 1 ) . "d", $iPage + 1 );

        $sStdUrl = $this->_trimUrl( $sStdUrl, $iLang );
        $sSeoUrl = $this->getCategoryUri( $oCategory, $iLang ) . $sParams . "/";

        return $this->_getFullUrl( $this->_getPageUri( $oCategory, 'oxcategory', $sStdUrl, $sSeoUrl, $sParams, $iLang, $blFixed ), $iLang );
    }

    /**
     * Category URL encoder. If category has external URLs, skip encoding
     * for this category. If SEO id is not set, generates and saves SEO id
     * for category (oxSeoEncoder::_getSeoId()).
     * If category has subcategories, it iterates through them.
     *
     * @param oxCategory $oCategory Category object
     * @param int        $iLang     Language
     *
     * @return string
     */
    public function getCategoryUrl( $oCategory, $iLang = null )
    {
        if (!isset($iLang)) {
            $iLang = $oCategory->getLanguage();
        }
        // category may have specified url
        if ( ( $sSeoUrl = $this->getCategoryUri( $oCategory, $iLang ) ) ) {
            return $this->_getFullUrl( $sSeoUrl, $iLang );
        }
        return '';
    }

    /**
     * Marks related to category objects as expired
     *
     * @param oxCategory $oCategory Category object
     *
     * @return null
     */
    public function markRelatedAsExpired( $oCategory )
    {
        $oDb = oxDb::getDb();
        // select it from table instead of using object carrying value
        // this is because this method is usually called inside update,
        // where object may already be carrying changed id
        $aCatInfo = $oDb->getAll("select oxrootid, oxleft, oxright from oxcategories where oxid = '".$oCategory->getId()."' limit 1");
        $sCatRootId = $aCatInfo[0][0];
        // update article for root of this cat
        $sQ = "update oxseo as seo1, (select oxobjectid from oxseo where oxtype = 'oxarticle' and oxparams = '{$sCatRootId}') as seo2 set seo1.oxexpired = '1' where seo1.oxtype = 'oxarticle' and seo1.oxobjectid = seo2.oxobjectid";
        $oDb->execute( $sQ );

        // update sub cats
        $sQ = "update oxseo as seo1, (select oxid from oxcategories where oxrootid='$sCatRootId' and oxleft > {$aCatInfo[0][1]} and oxright < {$aCatInfo[0][2]}) as seo2 set seo1.oxexpired = '1' where seo1.oxtype = 'oxcategory' and seo1.oxobjectid = seo2.oxid";
        $oDb->execute( $sQ );
    }


    /**
     * deletes Category seo entries
     *
     * @param oxCategory $oCategory Category object
     *
     * @return null
     */
    public function onDeleteCategory($oCategory)
    {
        $sId = oxDb::getDb()->quote($oCategory->getId());
        oxDb::getDb()->execute("update oxseo, (select oxseourl from oxseo where oxobjectid = $sId and oxtype = 'oxcategory') as test set oxseo.oxexpired=1 where oxseo.oxseourl like concat(test.oxseourl, '%') and (oxtype = 'oxcategory' or oxtype = 'oxarticle')");
        oxDb::getDb()->execute("delete from oxseo where oxobjectid = $sId and oxtype = 'oxcategory'");
    }

}
