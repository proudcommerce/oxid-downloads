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
 * $Id: oxutilsserver.php 18948 2009-05-12 07:36:12Z arvydas $
 */

/**
 * Server data manipulation class
 */
class oxUtilsServer extends oxSuperCfg
{
    /**
     * oxUtils class instance.
     *
     * @var oxutils* instance
     */
    private static $_instance = null;

    /**
     * user cookies
     *
     * @var array
     */
    protected $_aUserCookie = array();

    /**
     * Returns server utils instance
     *
     * @return oxUtilsServer
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            static $inst = array();
            self::$_instance = $inst[oxClassCacheKey()];
        }

        if ( !self::$_instance instanceof oxUtilsServer ) {
            self::$_instance = oxNew( 'oxUtilsServer');
            if ( defined( 'OXID_PHP_UNIT' ) ) {
                $inst[oxClassCacheKey()] = self::$_instance;
            }
        }
        return self::$_instance;
    }

    /**
     * sets cookie
     *
     * @param string $sName   cookie name
     * @param string $sValue  value
     * @param int    $iExpire expire time
     * @param string $sPath   The path on the server in which the cookie will be available on
     * @param string $sDomain The domain that the cookie is available.
     *
     * @return bool
     */
    public function setOxCookie( $sName, $sValue = "", $iExpire = 0, $sPath = '/', $sDomain = null )
    {
        //TODO: since setcookie takes more than just 4 params..
        // would be nice to have it sending through https only, if in https mode
        // or allowing only http access to cookie [no JS access - reduces XSS attack possibility]
        // ref: http://lt.php.net/manual/en/function.setcookie.php

        if ( defined('OXID_PHP_UNIT')) {
            // do NOT set cookies in php unit.
            return;
        }

        return setcookie( $sName, $sValue, $iExpire, $this->_getCookiePath( $sPath ), $this->_getCookieDomain( $sDomain ) );
    }

    /**
     * Returns cookie path. If user did not set path, or set it to null, according to php
     * documentation empty string will be returned, marking to skip argument
     *
     * @param string $sPath user defined cookie path
     *
     * @return string
     */
    protected function _getCookiePath( $sPath )
    {
        // from php doc: .. You may also replace an argument with an empty string ("") in order to skip that argument..
        return $sPath ? $sPath : "";
    }

    /**
     * Returns domain that cookie available. If user did not set domain, or set it to null, according to php
     * documentation empty string will be returned, marking to skip argument. Additionally domain can be defined
     * in config.inc.php file as "sCookieDomain" param. Please check cookie documentation for more details about
     * current parameter
     *
     * @param string $sDomain the domain that the cookie is available.
     *
     * @return string
     */
    protected function _getCookieDomain( $sDomain )
    {
        $sDomain = $sDomain ? $sDomain : "";

        // on special cases, like separate domain for SSL, cookies must be defined on domain specific path
        // please have a look at
        if ( !$sDomain ) {
            $myConfig = $this->getConfig();
            $sCookieDomain = $myConfig->getConfigParam( 'sCookieDomain' );
            $sDomain = $sCookieDomain ? $sCookieDomain : "";
        }
        return $sDomain;
    }

    /**
     * Returns cookie $sName value.
     * If optional parameter $sName is not set then getCookie() returns whole cookie array
     *
     * @param string $sName cookie param name
     *
     * @return mixed
     */
    public function getOxCookie( $sName = null )
    {
        $sValue = null;
        if ( $sName && isset( $_COOKIE[$sName] ) ) {
            $sValue = oxConfig::checkSpecialChars($_COOKIE[$sName]);
        } elseif ( $sName && !isset( $_COOKIE[$sName] ) ) {
            $sValue = null;
        } elseif ( !$sName && isset( $_COOKIE ) ) {
            $sValue = $_COOKIE;
        }
        return $sValue;
    }

    /**
     * Returns remote IP address
     *
     * @return string
     */
    public function getRemoteAddress()
    {
        if ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
            $sIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
            $sIP = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $sIP = $_SERVER["REMOTE_ADDR"];
        }
        return $sIP;
    }

    /**
     * returns a server constant
     *
     * @param string $sServVar optional - which server var should be returned, if null returns whole $_SERVER
     *
     * @return mixed
     */
    public function getServerVar( $sServVar = null )
    {
        $sValue = null;
        if ( isset( $_SERVER ) ) {
            if ( $sServVar && isset( $_SERVER[$sServVar] ) ) {
                $sValue = $_SERVER[$sServVar];
            } elseif ( !$sServVar ) {
                $sValue = $_SERVER;
            }
        }
        return $sValue;
    }

    /**
     * Sets user info into cookie
     *
     * @param string  $sUser     user ID
     * @param string  $sPassword password
     * @param string  $sShopId   shop ID (default null)
     * @param integer $iTimeout  timeout value (default 31536000)
     *
     * @return null
     */
    public function setUserCookie( $sUser, $sPassword,  $sShopId = null, $iTimeout = 31536000 )
    {
        $sShopId = ( !$sShopId ) ? $this->getConfig()->getShopId() : $sShopId;
        $this->_aUserCookie[$sShopId] = $sUser . '@@@' . crypt( $sPassword, 'ox' );
        $this->setOxCookie( 'oxid_' . $sShopId, $this->_aUserCookie[$sShopId], oxUtilsDate::getInstance()->getTime() + $iTimeout, '/' );
    }

    /**
     * Deletes user cookie data
     *
     * @param string $sShopId shop ID (default null)
     *
     * @return null
     */
    public function deleteUserCookie( $sShopId = null )
    {
        $sShopId = ( !$sShopId ) ? $this->getConfig()->getShopId() : $sShopId;
        $this->_aUserCookie[$sShopId] = '';
        $this->setOxCookie( 'oxid_'.$sShopId, '', oxUtilsDate::getInstance()->getTime() - 3600, '/' );
    }

    /**
     * Returns cookie stored used login data
     *
     * @param string $sShopId shop ID (default null)
     *
     * @return string
     */
    public function getUserCookie( $sShopId = null )
    {
        $sShopId = ( !$sShopId ) ? parent::getConfig()->getShopID() : $sShopId;
        if ( $this->_aUserCookie[$sShopId] !== null ) {
            if ( !$this->_aUserCookie[$sShopId] ) {
                // cookie has been deleted
                return null;
            }
            return $this->_aUserCookie[$sShopId];
        }

        return $this->_aUserCookie[$sShopId] = $this->getOxCookie( 'oxid_'.$sShopId );
    }
}
