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
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxopenid.php 29103 2010-07-27 12:44:06Z vilma $
 */

$iOldErrorReproting = error_reporting();
error_reporting($iOldErrorReproting & ~E_STRICT);

$sPathExtra = getShopBasePath()."core/openid";
$sPath = ini_get('include_path');
$sPath = $sPath . PATH_SEPARATOR . $sPathExtra;
ini_set('include_path', $sPath);

if ( !defined('Auth_OpenID_RAND_SOURCE') ) {
    if ( $sRandSource = oxConfig::getInstance()->getConfigParam( 'sAuthOpenIdRandSource' ) ) {
        define( 'Auth_OpenID_RAND_SOURCE', $sRandSource );
    } elseif ( PHP_OS == 'WINNT' || ( @fopen( '/dev/urandom', 'r' ) === false ) ) {
        /**
         * in case shop runs on windows or other system, which does not have '/dev/urandom'
         */
        define( 'Auth_OpenID_RAND_SOURCE', null );
    }
}

require_once "openid/Auth/OpenID/Consumer.php";
require_once "openid/Auth/OpenID/FileStore.php";
require_once "openid/Auth/OpenID/SReg.php";

error_reporting($iOldErrorReproting);
/**
 * OpenID authentication manager.
 * Process full authentication of openid.
 *
 * @package core
 */
class oxOpenId extends oxBase
{
    /**
     * OpenID authentication process.
     *
     * @param string $sOpenId    openid url
     * @param string $sReturnUrl url to return
     *
     * @return null
     */
    public function authenticateOid( $sOpenId, $sReturnUrl )
    {
        $myConfig = $this->getConfig();
        // create OpenID consumer
        $oConsumer = $this->_getConsumer();

        // begin sign-in process
        // creates an authentication request to the OpenID provider
        $oAuth = $oConsumer->begin($sOpenId);
        if ( !$oAuth ) {
            //not valid OpenId
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage( 'EXCEPTION_USER_NOVALIDOPENID' );
            throw $oEx;
        }

        // create request for registration data
        $oAuth->addExtension( Auth_OpenID_SRegRequest::build( array( 'email', 'fullname', 'gender', 'country' ), array( 'postcode' ) ) );

        // redirect to OpenID provider for authentication
        $sUrl = $oAuth->redirectURL( $myConfig->getSslShopUrl(), $sReturnUrl);
        oxUtils::getInstance()->redirect( $sUrl, false );

    }

    /**
     * Complete the authentication using the server's response
     *
     * @param string $sReturnUrl url to return
     *
     * @return array $aData registration data
     */
    public function getOidResponse( $sReturnUrl )
    {
        // create OpenID consumer
        $oConsumer = $this->_getConsumer();
        $oResponse = $oConsumer->complete( $sReturnUrl );

        // authentication results
        if ( $oResponse->status == Auth_OpenID_SUCCESS ) {
            // get registration information
            $oSRreg = $this->_getResponse();
            $oRet   = $oSRreg->fromSuccessResponse( $oResponse );
            $aData  = $oRet->contents();
        } elseif ( $oResponse->status == Auth_OpenID_CANCEL ) {
            //Verification Cancelled by user
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage( 'EXCEPTION_USER_OPENIDCANCELED' );
            throw $oEx;
        } else {
            //OpenID authentication failed
            $oEx = oxNew( 'oxUserException' );
            $oLang = oxLang::getInstance();
            $oEx->setMessage( sprintf($oLang->translateString( 'EXCEPTION_USER_OPENIDVALIDFAILED', $oLang->getBaseLanguage() ), $oResponse->message) );
            throw $oEx;
        }
        return $aData;
    }

    /**
     * Returns Openid consumer object
     *
     * @return object
     */
    protected function _getConsumer()
    {
        $oConsumer = new Auth_OpenID_Consumer( $this->_getStore(), oxOpenIdSession::getInstance(), 'oxOpenIdGenericConsumer' );

        return $oConsumer;
    }

    /**
     * Returns response object.
     *
     * @return object $oSRreg
     */
    protected function _getResponse()
    {
        $oSRreg = new Auth_OpenID_SRegResponse();

        return $oSRreg;
    }

    /**
     * Where will store OpenID information.
     * You should change this class if you want to store info in database.
     *
     * @return object $oStore
     */
    protected function _getStore()
    {
        // create file storage area for OpenID data
        // $oStore = new Auth_OpenID_FileStore( oxConfig::getInstance()->getConfigParam( 'sCompileDir' ) );
        // create db storage area for OpenID data
        $oStore = new oxOpenIdDb();
        $oStore->createTables();
        return $oStore;
    }
}
