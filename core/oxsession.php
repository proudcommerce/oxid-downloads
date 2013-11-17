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
 * $Id: oxsession.php 22352 2009-09-16 11:34:18Z sarunas $
 */

DEFINE('_DB_SESSION_HANDLER', getShopBasePath() . 'core/adodblite/session/adodb-session.php');
// Including database session managing class if needed.
if (oxConfig::getInstance()->getConfigParam( 'blAdodbSessionHandler' ) ) {
    $oDB = oxDb::getDb();
    require_once _DB_SESSION_HANDLER;
}

/**
 * Session manager.
 * Performs session managing function, such as variables deletion,
 * initialisation and other session functions.
 * @package core
 */
class oxSession extends oxSuperCfg
{
    /**
     * Session parameter name
     *
     * @var string
     */
    protected $_sName = 'sid';

    /**
     * Session parameter name
     *
     * @var string
     */
    protected $_sForcedPrefix = 'force_';

    /**
     * Unique session ID.
     * @var string
     */
    protected  $_sId     = null;

    /**
     * A flag indicating that sesion was just created, useful for tracking cookie support
     *
     * @var bool
     */
    protected static $_blIsNewSession = false;

    /**
     * Singleton instance keeper.
     */
    protected static $_instance = null;

    /**
     * Active session user object
     * @var object
     */
    protected static  $_oUser = null;

    /**
     * Indicates if setting of session id is executed in this script. After page transition
     * This needed to be checked as new session is not written in db until it is closed
     *
     * @var unknown_type
     */
    protected $_blNewSession = false;

    /**
     * Error message, used for debug purposes only
     *
     * @var string
     */
    protected $_sErrorMsg = null;

    /**
     * Basket session object
     *
     * @var object
     */
    protected $_oBasket = null;

    /**
     * Array of Classes => methods, which requires forced cookies support
     *
     * @var unknown_type
     */
    protected $_aRequireCookiesInFncs = array( 'register' => null,
                                               'account'  => null,
                                               'tobasket',
                                               'login_noredirect',
                                               'tocomparelist'
                                               );

    /**
     * Marker if processed urls must contain SID parameter
     *
     * @var bool
     */
    protected $_blSidNeeded = null;

    /**
     * Session params to be kept even after session timeout
     *
     * @var array
     */
    protected $_aPersistentParams = array("actshop", "lang", "currency", "language", "tpllanguage");

    /**
     * get oxSession object instance (create if needed)
     *
     * @return oxSession
     */
    public static function getInstance()
    {
        if ( defined('OXID_PHP_UNIT')) {
            if ( isset( modSession::$unitMOD) && is_object( modSession::$unitMOD)) {
                return modSession::$unitMOD;
            }
        }
        if (!isset(self::$_instance)) {
            self::$_instance  = oxNew( 'oxsession' );
        }
        return self::$_instance;
    }

    /**
     * Returns session ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->_sId;
    }

    /**
     * Sets session id
     *
     * @param string $sVal id value
     *
     * @return null
     */
    public function setId($sVal)
    {
        $this->_sId = $sVal;
    }

    /**
     * Sets session param name
     *
     * @param string $sVal name value
     *
     * @return null
     */
    public function setName($sVal)
    {
        $this->_sName = $sVal;
    }

    /**
     * Returns forced session id param name
     *
     * @return string
     */
    public function getForcedName()
    {
        return $this->_sForcedPrefix . $this->getName();
    }

    /**
     * Returns session param name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_sName;
    }

    /**
     * Starts shop session, generates unique session ID, extracts user IP.
     *
     * @return null
     */
    public function start()
    {
        $sid = null;

        if ( $this->isAdmin() ) {
            $this->setName("admin_sid");
        } else {
            $this->setName("sid");
        }

        $sForceSidParam = oxConfig::getParameter($this->getForcedName());
        $sSidParam = oxConfig::getParameter($this->getName());

        $blUseCookies = $this->getConfig()->getConfigParam( 'blSessionUseCookies') || $this->isAdmin();

        //forcing sid for SSL<->nonSSL transitions
        if ($sForceSidParam) {
            $sid = $sForceSidParam;
        } elseif ($blUseCookies && $this->_getCookieSid()) {
            $sid = $this->_getCookieSid();
        } elseif ($sSidParam) {
            $sid = $sSidParam;
        }

        //starting session if only we can
        if ($this->_allowSessionStart()) {

            //creating new sid
            if ( !$sid) {
                $this->initNewSession();
                self::$_blIsNewSession = true;
            } else {
                $this->_setSessionId($sid);
            }

            $this->_sessionStart();

            //special handling for new ZP cluster session, as in that case session_start() regenerates id
            if ( $this->_sId != session_id() ) {
                $this->_setSessionId( session_id() );
            }
        }

        //checking for swapped client in case cookies are not available
        if (!$this->_getCookieSid() && !oxUtils::getInstance()->isSearchEngine() && $this->_isSwappedClient() ) {
            $this->initNewSession();
        }
    }

    /**
     * Initialize session data (calls php::session_start())
     *
     * @return null
     */
    protected function _sessionStart()
    {
        return @session_start();
    }

    /**
     * Assigns new session ID, clean existing data except persistent.
     *
     * @return null
     */
    public function initNewSession()
    {
        //saving persistent params if old session exists
        $aPersistent = array();
        foreach ($this->_aPersistentParams as $sParam) {
            if ( self::getVar($sParam)) {
                $aPersistent[$sParam] = self::getVar($sParam);
            }
        }

        $sid = md5(oxUtilsObject::getInstance()->generateUID());

        $this->_setSessionId($sid);
        session_unset();

        //restoring persistent params to session
        foreach ($aPersistent as $key => $sParam) {
            self::setVar($key, $aPersistent[$key]);
        }
    }

    /**
     * Ends the current session and store session data.
     *
     * @return null
     */
    public function freeze()
    {
        // storing basket ..
        self::setVar( $this->_getBasketName(), serialize( $this->getBasket() ) );

        session_write_close();
    }

    /**
     * Destroys all data registered to a session.
     *
     * @return null
     */
    public function destroy()
    {
        //session_unset();
        unset($_SESSION);
        session_destroy();
    }

    /**
     * Checks if variable is set in session. Returns true on success.
     *
     * @param string $name Name to check
     *
     * @return bool
     */
    public static function hasVar( $name )
    {
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            if ( isset( modSession::$unitMOD ) && is_object( modSession::$unitMOD ) ) {
                try{
                    $sVal = modSession::getInstance()->getVar( $name );
                    return isset( $sVal );
                } catch( Exception $e ) {
                    // if exception is thrown, use default
                }
            }
        }

        return isset($_SESSION[$name]);
    }

    /**
     * Sets parameter and its value to global session mixedvar array.
     *
     * @param string $name  Name of parameter to store
     * @param mixed  $value Value of parameter
     *
     * @return null
     */
    public static function setVar( $name, $value)
    {
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            if ( isset( modSession::$unitMOD ) && is_object( modSession::$unitMOD ) ) {
                try{
                    return modSession::getInstance()->setVar(  $name, $value );
                } catch( Exception $e ) {
                    // if exception is thrown, use default
                }
            }
        }

        $_SESSION[$name] = $value;
        //logger( "set sessionvar : $name -> $value");
    }

    /**
     * IF available returns value of parameter, stored in session array.
     *
     * @param string $name Name of parameter
     *
     * @return mixed
     */
    public static function getVar( $name )
    {
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            if ( isset( modSession::$unitMOD ) && is_object( modSession::$unitMOD ) ) {
                try{
                    return modSession::getInstance()->getVar( $name );
                } catch( Exception $e ) {
                    // if exception is thrown, use default
                }
            }
        }

        if ( isset( $_SESSION[$name] )) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    /**
     * Destroys a single element (passed to method) of an session array.
     *
     * @param string $name Name of parameter to destroy
     *
     * @return null
     */
    public static function deleteVar( $name )
    {
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            if ( isset( modSession::$unitMOD ) && is_object( modSession::$unitMOD ) ) {
                try{
                    return modSession::getInstance()->setVar( $name, null );
                } catch( Exception $e ) {
                    // if exception is thrown, use default
                }
            }
        }

        $_SESSION[$name] = null;
        //logger( "delete sessionvar : $name");
        unset($_SESSION[$name]);
    }

    /**
     * Append URL with session information parameter.
     *
     * @param string $url Current url
     *
     * @return string
     */
    public function url($url)
    {
        $myConfig = $this->getConfig();
        $blForceSID = false;
        if (strpos(" ".$url, "https:") === 1 && !$myConfig->isSsl()) {
            $blForceSID = true;
        }
        if (strpos(" ".$url, "http:") === 1 && $myConfig->isSsl()) {
            $blForceSID = true;
        }

        $blUseCookies = $myConfig->getConfigParam( 'blSessionUseCookies' ) || $this->isAdmin();
        $oStr = getStr();
        $sSeparator = $oStr->strstr($url, "?") !== false ?  "&amp;" : "?";

        if ($blUseCookies && $this->_getCookieSid()) {
            //cookies are supported so we do nothing
            $url .= $sSeparator;

            //or this is SSL link in non SSL environment (or vice versa)
            //and we force sid here
            if ($blForceSID) {
                $url .= $this->getForcedName().'=' . $this->getId() . '&amp;';
            }
        } elseif (oxUtils::getInstance()->isSearchEngine()) {
            $url .= $sSeparator;

            //adding lang parameter for search engines
            $sLangParam = oxConfig::getParameter( "lang" );
            $sConfLang = $myConfig->getConfigParam( 'sDefaultLang' );
            if ( (int) $sLangParam != (int) $sConfLang ) {
                $url   .= "lang=" . $sLangParam . "&amp;";
            }
        } elseif ( ($sIcludeParams = $this->sid()) ) {
            //cookies are not supported or this is first time visit
            $url .= $sSeparator . $sIcludeParams . '&amp;';
        } else {
            $url .= $sSeparator;
        }

        return $url;
    }

    /**
     * Returns string prefix to URL with session ID parameter. In some cases
     * (if client is robot, such as Google) adds parameter shp, to identify,
     * witch shop is currently running.
     *
     * @param bool $blForceSid forces sid getter, ignores cookie check (optional)
     *
     * @return string
     */
    public function sid( $blForceSid = false )
    {
        $myConfig     = $this->getConfig();
        $blUseCookies = $myConfig->getConfigParam( 'blSessionUseCookies' ) || $this->isAdmin();
        $sRet         = '';

        //no cookie?
        if ( $this->getId() && ($blForceSid || !$blUseCookies || !$this->_getCookieSid())) {
            $sRet = ( $blForceSid ? $this->getForcedName() : $this->getName() )."=".$this->getId();
        }

        if (oxUtils::getInstance()->isSearchEngine() && is_array($myConfig->getConfigParam( 'aCacheViews' ) ) && !$this->isAdmin() ) {

            $sRet = '';

            $sShopId = $myConfig->getShopId();
            if ( $sShopId != 1) {
                $sRet = "shp=" . $sShopId;
            }
        }

        return $sRet;
    }

    /**
     * Forms input ("hidden" type) to pass session ID after submitting forms.
     *
     * @return string
     */
    public function hiddenSid()
    {
        if ( $this->isAdmin()) {
            return '';
        }

        return "<input type=\"hidden\" name=\"".$this->getForcedName()."\" value=\"". $this->getId() . "\">";
    }

    /**
     * Returns basket session object.
     *
     * @return oxbasket
     */
    public function getBasket()
    {
        if ( $this->_oBasket === null ) {
            $sBasket = self::getVar( $this->_getBasketName() );
            if ( $sBasket && $oBasket = unserialize( $sBasket ) ) {
                $this->setBasket( $oBasket );
            } else {
                $this->setBasket( oxNew( 'oxbasket' ) );
            }
        }

        return $this->_oBasket;
    }

    /**
     * Sets basket session object.
     *
     * @param object $oBasket basket object
     *
     * @return null
     */
    public function setBasket( $oBasket )
    {
        // sets basket session object
        $this->_oBasket = $oBasket;
    }

    /**
     * Deletes basket session object.
     *
     * @return null
     */
    public function delBasket()
    {
        $this->setBasket( null );
        self::deleteVar( $this->_getBasketName());
    }

    /**
     * Indicates if setting of session id is executed in this script.
     *
     * @return bool
     */
    public function isNewSession()
    {
        return self::$_blIsNewSession;
    }

    /**
     * Returns true if its not search engine and config option blForceSessionStart = 1/true
     *
     * @return bool
     */
    protected function _forceSessionStart()
    {
        return ( !oxUtils::getInstance()->isSearchEngine() ) && ( ( bool ) $this->getConfig()->getConfigParam( 'blForceSessionStart' ) ) ;
    }

    /**
     * Checks if we can start new session. Returns bool success status
     *
     * @return bool
     */
    protected function _allowSessionStart()
    {
        $blAllowSessionStart = true;

        // special handling only in non-admin mode
        if ( !$this->isAdmin() ) {
            if ( oxUtils::getInstance()->isSearchEngine() || oxConfig::getParameter( 'skipSession' ) ) {
                $blAllowSessionStart = false;
            } elseif ( !$this->_forceSessionStart() && !oxUtilsServer::getInstance()->getOxCookie( 'sid_key' ) ) {

                // session is not needed to start when it is not necessary:
                // - no sid in request and also user executes no session connected action
                // - no cookie set and user executes no session connected action
                if ( !oxUtilsServer::getInstance()->getOxCookie( $this->getName() ) &&
                     !( oxConfig::getParameter( $this->getName() ) || oxConfig::getParameter( $this->getForcedName() ) ) &&
                     !$this->_isSessionRequiredAction() ) {
                    $blAllowSessionStart = false;
                }
            }
        }

        return $blAllowSessionStart;
    }

    /**
     * Saves various visitor parameters and compares with current data.
     * Returns true if any change is detected.
     * Using this method we can detect different visitor with same session id.
     *
     * @return bool
     */
    protected function _isSwappedClient()
    {
        $myConfig = $this->getConfig();
        $myUtils  = oxUtils::getInstance();

        $blSwapped = false;

        //checking search engine
        if ( $myUtils->isSearchEngine() ) {
            return false;
        }

        /*
        //T2007-05-14
        //checking 'skipSession' paramter to prevent new session generation for popup
        elseif("x" == $this->getId() && !oxConfig::getParameter('skipSession'))
        {
            $this->_sErrorMsg = "Refered from search engine, creating new SID...<br>";

            $blSwapped = true;
        }*/

        $sAgent = oxUtilsServer::getInstance()->getServerVar( 'HTTP_USER_AGENT' );
        $sExistingAgent = self::getVar( 'sessionagent' );
        if ( $this->_checkUserAgent( $sAgent, $sExistingAgent ) ) {
            $blSwapped = true;
        }

        /*
        if ( $this->_checkByTimeOut() )
            $blSwapped = true;
        */

        if ( $myConfig->getConfigParam( 'blAdodbSessionHandler' ) ) {
            if ( $this->_checkSid() ) {
                $blSwapped = true;
            }
        }

        $blDisableCookieCheck = $myConfig->getConfigParam( 'blDisableCookieCheck' );
        $blUseCookies         = $myConfig->getConfigParam( 'blSessionUseCookies' ) || $this->isAdmin();
        if ( !$blDisableCookieCheck && $blUseCookies ) {
            $sCookieSid = oxUtilsServer::getInstance()->getOxCookie( 'sid_key' );
            $aSessCookieSetOnce = self::getVar("sessioncookieisset");
            if ( $this->_checkCookies( $sCookieSid, $aSessCookieSetOnce ) ) {
                $blSwapped = true;
            }
        }

        return $blSwapped;
    }

    /**
     * Checking user agent
     *
     * @param string $sAgent         current user agent
     * @param string $sExistingAgent existing user agent
     *
     * @return bool
     */
    protected function _checkUserAgent( $sAgent, $sExistingAgent)
    {
        $blIgnoreBrowserChange = oxConfig::getParameter("remoteaccess") == "true" && !$this->isAdmin();
        if ($sAgent && $sExistingAgent && $sAgent != $sExistingAgent && (!$blIgnoreBrowserChange)) {
            $this->_sErrorMsg = "Different browser ($sExistingAgent, $sAgent), creating new SID...<br>";
            return true;
        } elseif (!isset($sExistingAgent)) {
            self::setVar("sessionagent", $sAgent);
        }
        return false;
    }

    /**
     * Checking by timeout ( 60 minutes inactive, then kick him )
     * Global session Timeout in oxconfig::StartupDatabase is set to 1 hour
     *
     * @return bool
     */
    /*
    protected function _checkByTimeOut()
    {
        $myConfig = $this->getConfig();
        $iTimeStamp = oxUtilsDate::getInstance()->getTime();

        // #660
        $iSessionTimeout = null;
        if( $this->isAdmin() )
            $iSessionTimeout = $myConfig->getConfigParam( 'iSessionTimeoutAdmin' );
        if ( !$this->isAdmin() || !$iSessionTimeout )
            $iSessionTimeout = $myConfig->getConfigParam( 'iSessionTimeout' );
        if (!$iSessionTimeout)
            $iSessionTimeout = 60;

        $iTimeout = 60 * $iSessionTimeout;
        $iExistingTimeStamp = self::getVar( "sessiontimestamp");
        if ( $iExistingTimeStamp && ( $iExistingTimeStamp + $iTimeout < $iTimeStamp ) ) {
            $this->_sErrorMsg = "Shop timeout($iTimeStamp - $iExistingTimeStamp = ".($iTimeStamp - $iExistingTimeStamp)." ),
                                                                                                creating new SID...<br>";
            return true;
        }
        self::setVar("sessiontimestamp", $iTimeStamp);
        return false;
    }*/

    /**
     * Checking if this sid is old
     *
     * @return bool
     */
    protected function _checkSid()
    {
        //matze changed sesskey to SessionID because structure of oxsession changed!!
        $sSID = oxDb::getDb()->GetOne("select SessionID from oxsessions where SessionID = '".$this->getId()."'");

        //2007-05-14
        //we check _blNewSession as well as this may be actually new session not written to db yet
        if ( !$this->_blNewSession && (!isset( $sSID) || !$sSID)) {
            // this means, that this session has expired in the past and someone uses this sid to reactivate it
            $this->_sErrorMsg = "Session has expired in the past and someone uses this sid to reactivate it, creating new SID...<br>";
            return true;
        }
        return false;
    }

    /**
     * Check for existing cookie.
     * Cookie info is dropped from time to time.
     *
     * @param string $sCookieSid         coockie sid
     * @param array  $aSessCookieSetOnce if session cookie is set
     *
     * @return bool
     */
    protected function _checkCookies( $sCookieSid, $aSessCookieSetOnce )
    {
        $myConfig   = $this->getConfig();
        $blSwapped  = false;

        if ( isset( $aSessCookieSetOnce[$myConfig->getCurrentShopURL()] ) ) {
            $blSessCookieSetOnce = $aSessCookieSetOnce[$myConfig->getCurrentShopURL()];
        } else {
            $blSessCookieSetOnce = false;
        }

        //if cookie was there once but now is gone it means we have to reset
        if ( $blSessCookieSetOnce && !$sCookieSid ) {
            if ( $myConfig->getConfigParam( 'iDebug' ) ) {
                $this->_sErrorMsg  = "Cookie not found, creating new SID...<br>";
                $this->_sErrorMsg .= "Cookie: $sCookieSid<br>";
                $this->_sErrorMsg .= "Session: $blSessCookieSetOnce<br>";
                $this->_sErrorMsg .= "URL: ".$myConfig->getCurrentShopURL()."<br>";
            }
            $blSwapped = true;
        }

        //if we detect the cookie then set session var for possible later use
        if ( $sCookieSid == "oxid" && !$blSessCookieSetOnce ) {
            $aSessCookieSetOnce[$myConfig->getCurrentShopURL()] = "ox_true";
            self::setVar( "sessioncookieisset", $aSessCookieSetOnce );
        }

        //if we have no cookie then try to set it
        if ( !$sCookieSid ) {
            oxUtilsServer::getInstance()->setOxCookie( 'sid_key', 'oxid' );
        }
        return $blSwapped;
    }

    /**
     * Sests session id to $sSessId
     *
     * @param string $sSessId sesion ID
     *
     * @return null
     */
    protected function _setSessionId($sSessId)
    {
        //marking this session as new one, as it might be not writen to db yet
        if ($sSessId && session_id() != $sSessId) {
            $this->_blNewSession = true;
        }

        session_id($sSessId);

        $this->setId($sSessId);

        $blUseCookies = $this->getConfig()->getConfigParam( 'blSessionUseCookies' ) || $this->isAdmin();

        if (!$this->_allowSessionStart()) {
            if ($blUseCookies) {
                oxUtilsServer::getInstance()->setOxCookie($this->getName(), null);
            }
            return;
        }

        if ($blUseCookies) {
            //setting session cookie
            oxUtilsServer::getInstance()->setOxCookie($this->getName(), $sSessId);
        }

        if ( $this->_sErrorMsg) {
            //display debug error msg
            echo $this->_sErrorMsg;
            $this->_sErrorMsg = null;
        }
    }

    /**
     * Returns name of shopping basket.
     *
     * @return string
     */
    protected function _getBasketName()
    {
        $myConfig = $this->getConfig();
        if ( $myConfig->getConfigParam( 'blMallSharedBasket' ) == 0) {
            return $myConfig->getShopId()."_basket";
        } else {
            return "basket";
        }
    }

    /**
     * Returns cookie sid value
     *
     * @return string
     */
    protected function _getCookieSid()
    {
        return oxUtilsServer::getInstance()->getOxCookie($this->getName());
    }

    /**
     * Tests if current action requires session
     *
     * @return bool
     */
    protected function _isSessionRequiredAction()
    {
        $sFunction = oxConfig::getParameter( 'fnc' );
        $sClass = oxConfig::getParameter( 'cl' );

        return ( $sFunction && in_array( strtolower( $sFunction ), $this->_aRequireCookiesInFncs ) ) ||
               ( $sClass && array_key_exists( strtolower( $sClass ), $this->_aRequireCookiesInFncs ) );
    }

    /**
     * Checks if cookies are not available. Returns TRUE of sid needed
     *
     * @param string $sUrl if passed domain does not match current - returns true (optional)
     *
     * @return bool
     */
    public function isSidNeeded( $sUrl = null )
    {
        if ( $sUrl && !$this->getConfig()->isCurrentUrl( $sUrl ) ) {
            return true;
        } elseif ( $this->_blSidNeeded === null ) {
            // setting initial state
            $this->_blSidNeeded = false;

            // no SIDs for seach engines
            if ( !oxUtils::getInstance()->isSearchEngine() ) {
                // cookie found - SID is not needed
                if ( oxUtilsServer::getInstance()->getOxCookie( $this->getName() ) ) {
                    $this->_blSidNeeded = false;
                } elseif ( $this->_forceSessionStart() ) {
                    $this->_blSidNeeded = true;
                } else {
                    // no cookie, so must check session
                    if ( $blSidNeeded = self::getVar( 'blSidNeeded' ) ) {
                        $this->_blSidNeeded = true;
                    } elseif ( $this->_isSessionRequiredAction() ) {
                        $this->_blSidNeeded = true;

                        // storing to session, performance..
                        self::setVar( 'blSidNeeded', $this->_blSidNeeded  );
                    }
                }
            }
        }

        return $this->_blSidNeeded;
    }

    /**
     * Appends url with session ID, but only if oxSession::_isSidNeeded() returns true
     *
     * @param string $sUrl url to append with sid
     *
     * @return string
     */
    public function processUrl( $sUrl )
    {
        if ( $this->isSidNeeded( $sUrl ) ) {
            $oStr = getStr();
            // only if sid is not yet set and we have something to append
            $sSid = $this->sid( true );
            if ( $sSid && $oStr->strstr( $sUrl, 'sid=' ) === false ) {
                $sUrl .= ( $oStr->strstr( $sUrl, '?' ) !== false ?  '&amp;' : '?' ) . $sSid . '&amp;';
            }
        }

        return $sUrl;
    }
}
