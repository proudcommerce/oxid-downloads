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
 * $Id: oxseoencodermanufacturer.php 23319 2009-10-16 14:03:21Z arvydas $
 */

/**
 * Seo encoder base
 *
 * @package core
 */
class oxSeoEncoderManufacturer extends oxSeoEncoder
{
    /**
     * Singleton instance.
     *
     * @var oxmanufacturer
     */
    protected static $_instance = null;

    /**
     * Root manufacturer uri cache
     *
     * @var array
     */
    protected $_aRootManufacturerUri = null;

    /**
     * Singleton method
     *
     * @return oxseoencoder
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = oxNew("oxSeoEncoderManufacturer");
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
     * Returns part of SEO url excluding path
     *
     * @param oxmanufacturer $oManufacturer manufacturer object
     * @param int            $iLang         language
     *
     * @return string
     */
    public function getManufacturerUri( $oManufacturer, $iLang = null )
    {
        if (!isset($iLang)) {
            $iLang = $oManufacturer->getLanguage();
        }
        // load from db
        if ( !( $sSeoUrl = $this->_loadFromDb( 'oxmanufacturer', $oManufacturer->getId(), $iLang ) ) ) {

            if ( $iLang != $oManufacturer->getLanguage() ) {
                $sId = $oManufacturer->getId();
                $oManufacturer = oxNew('oxmanufacturer');
                $oManufacturer->loadInLang( $iLang, $sId );
            }

            $sSeoUrl = '';
            if ( $oManufacturer->getId() != 'root' ) {
                if ( !isset( $this->_aRootManufacturerUri[$iLang] ) ) {
                    $oRootManufacturer = oxNew('oxmanufacturer');
                    $oRootManufacturer->loadInLang( $iLang, 'root' );
                    $this->_aRootManufacturerUri[$iLang] = $this->getManufacturerUri( $oRootManufacturer, $iLang );
                }
                $sSeoUrl .= $this->_aRootManufacturerUri[$iLang];
            }

            $sSeoUrl .= $this->_prepareTitle( $oManufacturer->oxmanufacturers__oxtitle->value ) .'/';
            $sSeoUrl  = $this->_processSeoUrl( $sSeoUrl, $oManufacturer->getId(), $iLang );

            // save to db
            $this->_saveToDb( 'oxmanufacturer', $oManufacturer->getId(), $oManufacturer->getStdLink(), $sSeoUrl, $iLang );
        }
        return $sSeoUrl;
    }

    /**
     * Returns Manufacturer SEO url for specified page
     *
     * @param oxManufacturer $oManufacturer manufacturer object
     * @param int            $iPage         page tu prepare number
     * @param int            $iLang         language
     * @param bool           $blFixed       fixed url marker (default is false)
     *
     * @return string
     */
    public function getManufacturerPageUrl( $oManufacturer, $iPage, $iLang = null, $blFixed = false )
    {
        if (!isset($iLang)) {
            $iLang = $oManufacturer->getLanguage();
        }
        $sStdUrl = $oManufacturer->getStdLink() . '&amp;pgNr=' . $iPage;
        $sParams = sprintf( "%0" . ceil( $this->_iCntPages / 10 + 1 ) . "d", $iPage + 1 );

        $sStdUrl = $this->_trimUrl( $sStdUrl, $iLang );
        $sSeoUrl = $this->getManufacturerUri( $oManufacturer, $iLang ) . $sParams . "/";

        return $this->_getFullUrl( $this->_getPageUri( $oManufacturer, 'oxmanufacturers', $sStdUrl, $sSeoUrl, $sParams, $iLang, $blFixed ), $iLang );
    }

    /**
     * Encodes manufacturer category URLs into SEO format
     *
     * @param oxmanufacturer $oManufacturer Manufacturer object
     * @param int            $iLang         language
     *
     * @return null
     */
    public function getManufacturerUrl( $oManufacturer, $iLang = null )
    {
        if (!isset($iLang)) {
            $iLang = $oManufacturer->getLanguage();
        }
        return $this->_getFullUrl( $this->getManufacturerUri( $oManufacturer, $iLang ), $iLang );
    }

    /**
     * Deletes manufacturer seo entry
     *
     * @param oxmanufacturer $oManufacturer Manufacturer object
     *
     * @return null
     */
    public function onDeleteManufacturer($oManufacturer)
    {
        $sIdQuoted = oxDb::getDb()->quote($oManufacturer->getId());
        oxDb::getDb()->execute("delete from oxseo where oxobjectid = $sIdQuoted and oxtype = 'oxmanufacturers'");
    }
}
