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
 * @package   core
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: oxutils.php 23456 2009-10-21 14:49:35Z sarunas $
 */

/**
 * URL utility class
 */
class oxUtilsUrl extends oxSuperCfg
{
    /**
     * oxUtilsUrl class instance.
     *
     * @var oxUtilsUrl
     */
    private static $_instance = null;

    /**
     * Additional url parameters which should be appended to seo/std urls
     *
     * @var array
     */
    protected $_aAddUrlParams = null;

    /**
     * resturns a single instance of this class
     *
     * @return oxUtilsUrl
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            self::$_instance = modInstances::getMod( __CLASS__ );
        }

        if ( !(self::$_instance instanceof oxUtilsUrl ) ) {
            self::$_instance = oxNew( 'oxUtilsUrl' );
            if ( defined( 'OXID_PHP_UNIT' ) ) {
                modInstances::addMod( __CLASS__, self::$_instance);
            }
        }
        return self::$_instance;
    }

    /**
     * Returns core parameters which must be added to each url
     *
     * @return array
     */
    public function getBaseAddUrlParams()
    {
        $aAddUrlParams = array();

        return $aAddUrlParams;
    }

    /**
     * Returns parameters which should be appended to seo or std url
     *
     * @return array
     */
    public function getAddUrlParams()
    {
        if ( $this->_aAddUrlParams === null ) {
            $this->_aAddUrlParams = $this->getBaseAddUrlParams();

            // appending currency
            if ( ( $iCur = $this->getConfig()->getShopCurrency() ) ) {
                $this->_aAddUrlParams['cur'] = $iCur;
            }
        }
        return $this->_aAddUrlParams;
    }

    /**
     * prepareUrlForNoSession adds extra url params making it usable without session
     * also removes sid=xxxx&
     *
     * @param string $sUrl given url
     *
     * @access public
     * @return string
     */
    public function prepareUrlForNoSession( $sUrl )
    {
        if ( oxUtils::getInstance()->seoIsActive() ) {
            return $sUrl;
        }

        $oStr = getStr();
        $sUrl = $oStr->preg_replace('/(\?|&(amp;)?)(force_)?(admin_)?sid=[a-z0-9\._]+&?(amp;)?/i', '\1', $sUrl);

        if ($qpos = $oStr->strpos($sUrl, '?')) {
            if ($qpos == $oStr->strlen($sUrl)-1) {
                $sSep = '';
            } else {
                $sSep = '&amp;';
            }
        } else {
            $sSep = '?';
        }

        if ( !$oStr->preg_match('/[&?](amp;)?lang=[0-9]+/i', $sUrl)) {
            $sUrl .= "{$sSep}lang=".oxLang::getInstance()->getBaseLanguage();
            $sSep = '&amp;';
        }

        $oConfig = $this->getConfig();
        if ( !$oStr->preg_match('/[&?](amp;)?cur=[0-9]+/i', $sUrl)) {
            $iCur = (int) $oConfig->getShopCurrency();
            if ( $iCur ) {
                $sUrl .= "{$sSep}cur=".$iCur;
                $sSep = '&amp;';
            }
        }

        return $sUrl;
    }

    /**
     * Prepares canonical url
     *
     * @param string $sUrl given url
     *
     * @access public
     * @return string
     */
    public function prepareCanonicalUrl( $sUrl )
    {
        $oConfig = $this->getConfig();
        $oStr = getStr();

        // cleaning up session id..
        $sUrl = $oStr->preg_replace('/(\?|&(amp;)?)(force_)?(admin_)?sid=[a-z0-9\._]+&?(amp;)?/i', '\1', $sUrl);
        $sUrl = $oStr->preg_replace( '/(&amp;|\?)$/', '', $sUrl );
        $sSep = ( $oStr->strpos( $sUrl, '?' ) === false ) ? '?' : '&amp;';


        if ( !oxUtils::getInstance()->seoIsActive() ) {
            // non seo url has no language identifier..
            $iLang = oxLang::getInstance()->getBaseLanguage();
            if ( !$oStr->preg_match( '/[&?](amp;)?lang=[0-9]+/i', $sUrl ) && $iLang != $oConfig->getConfigParam( 'sDefaultLang' ) ) {
                $sUrl .= "{$sSep}lang=".$iLang;
            }
        }

        return $sUrl;
    }

    /**
     * Appends url with given parameters
     *
     * @param atring $sUrl       url to append
     * @param array  $aAddParams parameters to append
     *
     * @return string
     */
    public function appendUrl( $sUrl, $aAddParams )
    {
        $oStr = getStr();
        $sSep = '&amp;';
        if ( $oStr->strpos( $sUrl, '?' ) === false ) {
            $sSep = '?';
        }

        if ( count( $aAddParams ) ) {
            foreach ( $aAddParams as $sName => $sValue ) {
                if ( isset( $sValue ) && !$oStr->preg_match("/\?(.*&(amp;)?)?".preg_quote( $sName )."=/", $sUrl ) ) {
                    $sUrl .= $sSep . $sName . "=" . $sValue;
                    $sSep = '&amp;';
                }
            }
        }
        return $sUrl ? $sUrl.$sSep : '';
    }

    /**
     * Removes any or specified dynamic parameter from given url
     *
     * @param string $sUrl    url to clean
     * @param array  $aParams parameters to remove [optional]
     *
     * @return string
     */
    public function cleanUrl( $sUrl, $aParams = null )
    {
        $oStr = getStr();
        if ( is_array( $aParams ) ) {
            foreach ( $aParams as $sParam ) {
                $sUrl = $oStr->preg_replace( '/(\?|&(amp;)?)'.preg_quote( $sParam ).'=[a-z0-9\.]+&?(amp;)?/i', '\1', $sUrl );
            }
        } else {
            $sUrl = $oStr->preg_replace( '/(\?|&(amp;)?).+/i', '\1', $sUrl );
        }

        return trim( $sUrl, "?" );
    }

    /**
     * Performs base url processing - adds required parameters to given url
     *
     * @param string $sUrl       url to process
     * @param bool   $blFinalUrl should url be finalized or should it end with ? or &amp; (default true)
     * @param array  $aParams    additional parameters (default null)
     * @param int    $iLang      url target language (default null)
     *
     * @return string
     */
    public function processUrl( $sUrl, $blFinalUrl = true, $aParams = null, $iLang = null )
    {
        $aAddParams = $this->getAddUrlParams();
        if ( is_array($aParams) && count( $aParams ) ) {
            $aAddParams = array_merge( $aAddParams, $aParams );
        }

        $ret = oxSession::getInstance()->processUrl(
                    oxLang::getInstance()->processUrl(
                        $this->appendUrl(
                                $sUrl,
                                $aAddParams
                        ),
                        $iLang
                    )
                );

        if ($blFinalUrl) {
            $ret = getStr()->preg_replace('/(\?|&(amp;)?)$/', '', $ret);
        }
        return $ret;
    }

    /**
     * Seo url processor: adds various needed parameters, like currency, shop id
     *
     * @param string $sUrl url to process
     *
     * @return string
     */
    public function processSeoUrl( $sUrl )
    {
        $ret = $this->getSession()->processUrl( $this->appendUrl( $sUrl, $this->getAddUrlParams() ) );
        $ret = getStr()->preg_replace('/(\?|&(amp;)?)$/', '', $ret);
        return $ret;
    }

    /**
     * append parameter separator - '?' if it is not in the url or &amp; otherwise
     *
     * @param string $sUrl url
     *
     * @return string
     */
    public function appendParamSeparator($sUrl)
    {
        $oStr = getStr();
        if ( $oStr->preg_match('/(\?|&(amp;)?)$/i', $sUrl ) ) {
            // it is already ok
            return $sUrl;
        }
        if ( $oStr->strpos($sUrl, '?') === false ) {
            return $sUrl.'?';
        }
        return $sUrl.'&amp;';
    }
}
