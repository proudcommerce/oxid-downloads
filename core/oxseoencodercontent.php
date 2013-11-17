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
 * $Id: oxseoencodercontent.php 22590 2009-09-24 06:24:00Z alfonsas $
 */

/**
 * Seo encoder base
 *
 * @package core
 */
class oxSeoEncoderContent extends oxSeoEncoder
{
    /**
     * Singleton instance.
     */
    protected static $_instance = null;

    /**
     * Singleton method
     *
     * @return oxseoencoder
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = oxNew("oxSeoEncoderContent");
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
     * Returns SEO uri for content object. Includes parent category path info if
     * content is assigned to it
     *
     * @param oxcontent $oCont content category object
     * @param int       $iLang language
     *
     * @return string
     */
    protected function _getContentUri( $oCont, $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = $oCont->getLanguage();
        }
        //load details link from DB
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxcontent', $oCont->getId(), $iLang ) ) ) {

            if ( $iLang != $oCont->getLanguage() ) {
                $oCont->loadInLang( $iLang, $oCont->getId() );
            }

            $sSeoUrl = '';
            if ( $oCont->oxcontents__oxcatid->value ) {
                $oCat = oxNew( 'oxcategory' );
                if ( $oCat->loadInLang( $iLang, $oCont->oxcontents__oxcatid->value ) ) {
                    if ( $oCat->oxcategories__oxparentid->value && $oCat->oxcategories__oxparentid->value != 'oxrootid' ) {
                        $oParentCat = oxNew( 'oxcategory' );
                        if ( $oParentCat->loadInLang( $iLang, $oCat->oxcategories__oxparentid->value ) ) {
                            $sSeoUrl .= oxSeoEncoderCategory::getInstance()->getCategoryUri( $oParentCat );
                        }
                    }
                }
            }

            $sSeoUrl .= $this->_prepareTitle( $oCont->oxcontents__oxtitle->value . '/' );
            $sSeoUrl  = $this->_processSeoUrl( $sSeoUrl, $oCont->getId(), $iLang );

            $this->_saveToDb( 'oxcontent', $oCont->getId(), $oCont->getStdLink(), $sSeoUrl, $iLang );
        }
        return $sSeoUrl;
    }

    /**
     * encodeContentUrl encodes content link
     *
     * @param oxContent $oCont category object
     * @param int       $iLang language
     *
     * @access public
     *
     * @return void
     */
    public function getContentUrl( $oCont, $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = $oCont->getLanguage();
        }
        return $this->_getFullUrl( $this->_getContentUri( $oCont, $iLang ), $iLang );
    }

    /**
     * deletes content seo entries
     *
     * @param string $sId content ids
     *
     * @return null
     */
    public function onDeleteContent($sId)
    {
        $sIdQuoted = oxDb::getDb()->quote($sId);
        oxDb::getDb()->execute("delete from oxseo where oxobjectid = $sIdQuoted and oxtype = 'oxcontent'");
    }
}
