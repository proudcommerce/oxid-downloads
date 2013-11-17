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
 * $Id: oxseoencodercontent.php 17768 2009-04-02 10:52:12Z sarunas $
 */

/**
 * Seo encoder base
 *
 * @package core
 */
class oxSeoEncoderTag extends oxSeoEncoder
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
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            static $inst = array();
            self::$_instance = $inst[oxClassCacheKey()];
        }

        if ( !self::$_instance instanceof oxSeoEncoderTag ) {
            self::$_instance = oxNew( 'oxSeoEncoderTag' );
            if ( defined( 'OXID_PHP_UNIT' ) ) {
                $inst[oxClassCacheKey()] = self::$_instance;
            }
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            // resetting cache
            self::$_instance->_aSeoCache = array();
        }

        return self::$_instance;
    }

    /**
     * Returns SEO uri for tag.
     *
     * @param string $sTag  tag
     * @param int    $iLang language
     *
     * @return string
     */
    public function getTagUri( $sTag, $iLang = null )
    {
        return $this->_getDynamicUri( $this->getStdTagUri( $sTag ), "tag/{$sTag}/", $iLang );
    }

    /**
     * Returns standard tag url
     *
     * @param string $sTag           tag
     * @param bool   $blIncludeIndex if you need only parameters set this param to false (optional)
     *
     * @return string
     */
    public function getStdTagUri( $sTag, $blIncludeIndex = true )
    {
        // while tags are just strings, standard ulrs formatted stays here
        $sUri = "cl=tag&amp;searchtag=" . rawurlencode( $sTag );
        if ( $blIncludeIndex ) {
            $sUri = "index.php?" . $sUri;
        }
        return $sUri;
    }

    /**
     * Returns full url for passed tag
     *
     * @param string $sTag  tag
     * @param int    $iLang language
     *
     * @return string
     */
    public function getTagUrl( $sTag, $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = oxLang::getInstance()->getBaseLanguage();
        }
        return $this->_getFullUrl( $this->getTagUri( $sTag, $iLang ), $iLang );
    }

    /**
     * Returns tag SEO url for specified page
     *
     * @param string $sTag manufacturer object
     * @param int          $iPage       page tu prepare number
     * @param int          $iLang       language
     * @param bool         $blFixed     fixed url marker (default is false)
     *
     * @return string
     */
    public function getTagPageUrl( $sTag, $iPage, $iLang = null, $blFixed = false )
    {
        if (!isset($iLang)) {
            $iLang = oxLang::getInstance()->getBaseLanguage();
        }
        $sStdUrl = $this->getStdTagUri( $sTag ) . '&amp;pgNr=' . $iPage;
        $sParams = sprintf( "%0" . ceil( $this->_iCntPages / 10 + 1 ) . "d", $iPage + 1 );

        $sStdUrl = $this->_trimUrl( $sStdUrl, $iLang );
        $sSeoUrl = $this->getTagUri( $sTag, $iLang ) . $sParams . "/";

        return $this->_getFullUrl( $this->_getDynamicUri( $sStdUrl, $sSeoUrl, $iLang ), $iLang );
    }
}