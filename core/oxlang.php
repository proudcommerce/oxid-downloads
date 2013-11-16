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
 * @version   SVN: $Id: oxlang.php 33237 2011-02-14 13:58:44Z linas.kukulskis $
 */

/**
 * Language related utility class
 */
class oxLang extends oxSuperCfg
{
    /**
     * oxUtilsCount instance.
     *
     * @var oxlang
     */
    private static $_instance = null;

    /**
     * Language parameter name
     *
     * @var string
     */
    protected $_sName = 'lang';

    /**
     * Current shop base language Id
     *
     * @var int
     */
    protected $_iBaseLanguageId = null;

    /**
     * Templates language Id
     *
     * @var int
     */
    protected $_iTplLanguageId = null;

    /**
     * Editing object language Id
     *
     * @var int
     */
    protected $_iEditLanguageId = null;

    /**
     * Language translations array
     *
     * @var array
     */
    protected $_aLangCache = array();

    /**
     * Admin language translations array
     *
     * @var array
     */
    protected $_aAdminLangCache = array();

    /**
     * Array containing possible admin template translations
     *
     * @var array
     */
    protected $_aAdminTplLanguageArray = null;

    /**
     * Language abbreviation array
     *
     * @var array
     */
    protected $_aLangAbbr = null;

    /**
     * resturns a single instance of this class
     *
     * @return oxLang
     */
    public static function getInstance()
    {
        if ( defined('OXID_PHP_UNIT')) {
            if ( ($oClassMod = modInstances::getMod(__CLASS__))  && is_object($oClassMod) ) {
                return $oClassMod;
            } else {
                $inst = oxNew( 'oxLang' );
                 modInstances::addMod( __CLASS__, $inst );
                 return $inst;
            }
        }

        if ( !self::$_instance instanceof oxLang ) {

            self::$_instance = oxNew( 'oxLang');
        }
        return self::$_instance;
    }

    /**
     * resetBaseLanguage resets base language id cache
     *
     * @access public
     * @return void
     */
    public function resetBaseLanguage()
    {
        $this->_iBaseLanguageId = null;
    }

    /**
     * Returns active shop language id
     *
     * @return string
     */
    public function getBaseLanguage()
    {
        if ( $this->_iBaseLanguageId === null ) {
            $myConfig = $this->getConfig();
            $blAdmin = $this->isAdmin();

            // languages and search engines
            if ( $blAdmin && ( ( $iSeLang = oxConfig::getParameter( 'changelang' ) ) !== null ) ) {
                $this->_iBaseLanguageId = $iSeLang;
            }

            if ( is_null( $this->_iBaseLanguageId ) ) {
                $this->_iBaseLanguageId = oxConfig::getParameter( 'lang' );
            }

            //or determining by domain
            $aLanguageUrls = $myConfig->getConfigParam( 'aLanguageURLs' );

            if ( !$blAdmin && is_array( $aLanguageUrls ) ) {
                foreach ( $aLanguageUrls as $iId => $sUrl ) {
                    if ( $myConfig->isCurrentUrl( $sUrl ) ) {
                        $this->_iBaseLanguageId = $iId;
                        break;
                    }
                }
            }

            if ( is_null( $this->_iBaseLanguageId ) ) {
                $this->_iBaseLanguageId = oxConfig::getParameter( 'language' );
            }

            // if language still not setted and not search engine browsing,
            // getting language from browser
            if ( is_null( $this->_iBaseLanguageId ) && !$blAdmin && !oxUtils::getInstance()->isSearchEngine() ) {

                // getting from cookie
                $this->_iBaseLanguageId = oxUtilsServer::getInstance()->getOxCookie( 'language' );

                // getting from browser
                if ( is_null( $this->_iBaseLanguageId ) ) {
                    $this->_iBaseLanguageId = $this->detectLanguageByBrowser();
                }
            }

            if ( is_null( $this->_iBaseLanguageId ) ) {
                $this->_iBaseLanguageId = $myConfig->getConfigParam( 'sDefaultLang' );
            }

            $this->_iBaseLanguageId = (int) $this->_iBaseLanguageId;

            // validating language
            $this->_iBaseLanguageId = $this->validateLanguage( $this->_iBaseLanguageId );

            // setting language to cookie
            oxUtilsServer::getInstance()->setOxCookie( 'language', $this->_iBaseLanguageId );
        }
        return $this->_iBaseLanguageId;
    }

    /**
     * Returns language id used to load objects according to current template language
     *
     * @return int
     */
    public function getObjectTplLanguage()
    {
        if ( $this->_iObjectTplLanguageId === null ) {
            $this->_iObjectTplLanguageId = $this->getTplLanguage();
            $aLanguages = $this->getAdminTplLanguageArray();
            if ( !isset( $aLanguages[$this->_iObjectTplLanguageId] ) ||
                 $aLanguages[$this->_iObjectTplLanguageId]->active == 0 ) {
                $this->_iObjectTplLanguageId = key( $aLanguages );
            }
        }
        return $this->_iObjectTplLanguageId;
    }

    /**
     * Returns active shop templates language id
     * If it is not an admin area, template language id is same
     * as base shop language id
     *
     * @return string
     */
    public function getTplLanguage()
    {
        if ( $this->_iTplLanguageId === null ) {
            $iSessLang = oxSession::getVar( 'tpllanguage' );
            $this->_iTplLanguageId = $this->isAdmin() ? $this->setTplLanguage( $iSessLang ) : $this->getBaseLanguage();
        }
        return $this->_iTplLanguageId;
    }

    /**
     * Returns editing object working language id
     *
     * @return string
     */
    public function getEditLanguage()
    {
        if ( $this->_iEditLanguageId !== null ) {
            return $this->_iEditLanguageId;
        }

        if ( !$this->isAdmin() ) {
            $this->_iEditLanguageId = $this->getBaseLanguage();
        } else {

            $this->_iEditLanguageId = oxConfig::getParameter( 'editlanguage' );

            // check if we really need to set the new language
            if ( "saveinnlang" == $this->getConfig()->getActiveView()->getFncName() ) {
                $iNewLanguage = oxConfig::getParameter( "new_lang");
            }

            if ( isset( $iNewLanguage ) ) {
                $this->_iEditLanguageId = $iNewLanguage;
                oxSession::deleteVar( "new_lang" );
            }

            if ( is_null( $this->_iEditLanguageId ) ) {

                $this->_iEditLanguageId = $this->getBaseLanguage();
            }
        }

        // validating language
        $this->_iEditLanguageId = $this->validateLanguage( $this->_iEditLanguageId );

        return $this->_iEditLanguageId;
    }

    /**
     * Returns array of available languages.
     *
     * @param integer $iLanguage    Number if current language (default null)
     * @param bool    $blOnlyActive load only current language or all
     * @param bool    $blSort       enable sorting or not
     *
     * @return array
     */
    public function getLanguageArray( $iLanguage = null, $blOnlyActive = false, $blSort = false )
    {
        $myConfig = $this->getConfig();

        if ( is_null($iLanguage) ) {
            $iLanguage = $this->_iBaseLanguageId;
        }

        $aLanguages = array();
        $aConfLanguages = $myConfig->getConfigParam( 'aLanguages' );
        $aLangParams    = $myConfig->getConfigParam( 'aLanguageParams' );

        if ( is_array( $aConfLanguages ) ) {
            $i = 0;
            reset( $aConfLanguages );
            while ( list( $key, $val ) = each( $aConfLanguages ) ) {

                if ( $blOnlyActive && is_array($aLangParams) ) {
                    //skipping non active languages
                    if ( !$aLangParams[$key]['active'] ) {
                        $i++;
                        continue;
                    }
                }

                if ( $val ) {
                    $oLang = new oxStdClass();
                    $oLang->id   = isset($aLangParams[$key]['baseId']) ? $aLangParams[$key]['baseId'] : $i;
                    $oLang->oxid = $key;
                    $oLang->abbr = $key;
                    $oLang->name = $val;

                    if ( is_array( $aLangParams ) ) {
                        $oLang->active = $aLangParams[$key]['active'];
                        $oLang->sort   = $aLangParams[$key]['sort'];
                    }

                    $oLang->selected = ( isset( $iLanguage ) && $oLang->id == $iLanguage ) ? 1 : 0;
                    $aLanguages[$oLang->id] = $oLang;
                }
                ++$i;
            }
        }

        if ( $blSort && is_array($aLangParams) ) {
            uasort( $aLanguages, array($this, '_sortLanguagesCallback') );
        }

        return $aLanguages;
    }

    /**
     * Returns languages array containing possible admin template translations
     *
     * @return array
     */
    public function getAdminTplLanguageArray()
    {
        if ( $this->_aAdminTplLanguageArray === null ) {

            // #656 add admin languages
            $aLangData = array();
            $aLangIds  = $this->getLanguageIds();

            $sSourceDir = $this->getConfig()->getStdLanguagePath( "", true, false );
            foreach ( glob( $sSourceDir."*", GLOB_ONLYDIR ) as $sDir ) {
                $sFilePath = "{$sDir}/lang.php";
                if ( file_exists( $sFilePath ) && is_readable( $sFilePath ) ) {
                    $sLangName = "";
                    $sAbbr = strtolower( basename( $sDir ) );
                    if ( !in_array( $sAbbr, $aLangIds ) ) {
                        include $sFilePath;
                        $aLangData[$sAbbr] = new oxStdClass();
                        $aLangData[$sAbbr]->name = $sLangName;
                        $aLangData[$sAbbr]->abbr = $sAbbr;
                    }
                }
            }

            $this->_aAdminTplLanguageArray = $this->getLanguageArray();
            if ( count( $aLangData ) ) {

                // sorting languages for selection list view
                ksort( $aLangData );
                $iSort = max( array_keys( $this->_aAdminTplLanguageArray ) );

                // appending other languages
                foreach ( $aLangData as $oLang ) {
                    $oLang->id = $oLang->sort = ++$iSort;
                    $oLang->selected = 0;
                    $oLang->active   = 0;
                    $this->_aAdminTplLanguageArray[$iSort] = $oLang;
                }
            }
        }

        // moving pointer to beginning
        reset( $this->_aAdminTplLanguageArray );
        return $this->_aAdminTplLanguageArray;
    }

    /**
     * Returns selected language abbervation
     *
     * @param int $iLanguage language id [optional]
     *
     * @return string
     */
    public function getLanguageAbbr( $iLanguage = null )
    {
        if ( $this->_aLangAbbr === null ) {
            $this->_aLangAbbr = array();
            if ( $this->isAdmin() ) {
                foreach ( $this->getAdminTplLanguageArray() as $oLang ) {
                    $this->_aLangAbbr[$oLang->id] = $oLang->abbr;
                }
            } else {
                $this->_aLangAbbr = $this->getLanguageIds();
            }
        }

        $iLanguage = isset( $iLanguage ) ? (int) $iLanguage : $this->getBaseLanguage();
        if ( isset( $this->_aLangAbbr[$iLanguage] ) ) {
            $iLanguage = $this->_aLangAbbr[$iLanguage];
        }

        return $iLanguage;
    }

    /**
     * getLanguageNames returns array of language names e.g. array('Deutch', 'English')
     *
     * @access public
     * @return array
     */
    public function getLanguageNames()
    {
        $aConfLanguages = $this->getConfig()->getConfigParam( 'aLanguages' );
        $aLangIds = $this->getLanguageIds();
        $aLanguages = array();
        foreach ( $aLangIds as $iId => $sValue ) {
            $aLanguages[$iId] = $aConfLanguages[$sValue];
        }
        return $aLanguages;
    }

    /**
     * Returns available language IDs (abbervations)
     *
     * @return array
     */
    public function getLanguageIds()
    {
        $myConfig = $this->getConfig();
        $aIds = array();

        //if exists language parameters array, extract lang id's from there
        $aLangParams = $myConfig->getConfigParam( 'aLanguageParams' );
        if ( is_array( $aLangParams ) ) {
            foreach ( $aLangParams as $sAbbr => $aValue ) {
                $iBaseId = (int) $aValue['baseId'];
                $aIds[$iBaseId] = $sAbbr;
            }
        } else {
            $aIds = array_keys( $myConfig->getConfigParam( 'aLanguages' ) );
        }

        return $aIds;
    }

    /**
     * Searches for translation string in file and on success returns translation,
     * otherwise returns initial string.
     *
     * @param string $sStringToTranslate Initial string
     * @param int    $iLang              optional language number
     * @param bool   $blAdminMode        on special case you can force mode, to load language constant from admin/shops language file
     *
     * @throws oxLanguageException in debug mode
     *
     * @return string
     */
    public function translateString( $sStringToTranslate, $iLang = null, $blAdminMode = null )
    {
        $aLangCache = $this->_getLangTranslationArray( $iLang, $blAdminMode );
        $sText = isset( $aLangCache[$sStringToTranslate] ) ? $aLangCache[$sStringToTranslate] : $sStringToTranslate;

            $blIsAdmin = isset( $blAdminMode ) ? $blAdminMode : $this->isAdmin();
            if ( !$blIsAdmin && $sText === $sStringToTranslate ) {
                $sText = $this->_readTranslateStrFromTextFile( $sStringToTranslate, $iLang, $blIsAdmin );
            }

        return $sText;
    }

    /**
     * Returns formatted number, according to active currency formatting standards.
     *
     * @param double $dValue  Plain price
     * @param object $oActCur Object of active currency
     *
     * @return string
     */
    public function formatCurrency( $dValue, $oActCur = null )
    {
        if ( !$oActCur ) {
            $oActCur = $this->getConfig()->getActShopCurrencyObject();
        }
        return number_format( (double)$dValue, $oActCur->decimal, $oActCur->dec, $oActCur->thousand );
    }

    /**
     * Returns formatted vat value, according to formatting standards.
     *
     * @param double $dValue  Plain price
     * @param object $oActCur Object of active currency
     *
     * @return string
     */
    public function formatVat( $dValue, $oActCur = null )
    {
        $iDecPos = 0;
        $sValue  = ( string ) $dValue;
        $oStr = getStr();
        if ( ( $iDotPos = $oStr->strpos( $sValue, '.' ) ) !== false ) {
            $iDecPos = $oStr->strlen( $oStr->substr( $sValue, $iDotPos + 1 ) );
        }

        $oActCur = $oActCur ? $oActCur : $this->getConfig()->getActShopCurrencyObject();
        $iDecPos = ( $iDecPos < $oActCur->decimal ) ? $iDecPos : $oActCur->decimal;
        return number_format( (double)$dValue, $iDecPos, $oActCur->dec, $oActCur->thousand );
    }

    /**
     * According to user configuration forms and return language prefix.
     *
     * @param integer $iLanguage User selected language (default null)
     *
     * @return string
     */
    public function getLanguageTag( $iLanguage = null)
    {
        if ( !isset( $iLanguage ) ) {
            $iLanguage = $this->getBaseLanguage();
        }

        $iLanguage = (int) $iLanguage;

        return ( ( $iLanguage )?"_$iLanguage":"" );
    }

    /**
     * Validate language id. If not valid id, returns default value
     *
     * @param int $iLang Language id
     *
     * @return int
     */
    public function validateLanguage( $iLang = null )
    {
        $iLang = (int) $iLang;

        $blAdmin = $this->isAdmin();

        // checking if this language is valid
        $aLanguages = $this->getLanguageArray(null, !$blAdmin);

        if ( !isset( $aLanguages[$iLang] ) && is_array( $aLanguages ) ) {
            $oLang = current( $aLanguages );
            if (isset($oLang->id)) {
                $iLang = $oLang->id;
            }
        }

        return $iLang;
    }

    /**
     * Set base shop language
     *
     * @param int $iLang Language id
     *
     * @return null
     */
    public function setBaseLanguage( $iLang = null )
    {
        if ( is_null($iLang) ) {
            $iLang = $this->getBaseLanguage();
        } else {
            $this->_iBaseLanguageId = (int) $iLang;
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            modSession::getInstance();
        }

        oxSession::setVar( 'language', $iLang );
    }

    /**
     * Validates and sets templates language id
     *
     * @param int $iLang Language id
     *
     * @return null
     */
    public function setTplLanguage( $iLang = null )
    {
        $this->_iTplLanguageId = isset( $iLang ) ? (int) $iLang : $this->getBaseLanguage();
        if ( $this->isAdmin() ) {
            $aLanguages = $this->getAdminTplLanguageArray();
            if ( !isset( $aLanguages[$this->_iTplLanguageId] ) ) {
                $this->_iTplLanguageId = key( $aLanguages );
            }
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            modSession::getInstance();
        }

        oxSession::setVar( 'tpllanguage', $this->_iTplLanguageId );
        return $this->_iTplLanguageId;
    }

    /**
     * Goes through language array and recodes its values. Returns recoded data
     *
     * @param array  $aLangArray language data
     * @param string $sCharset   charset which was used while making file
     *
     * @return array
     */
    protected function _recodeLangArray( $aLangArray, $sCharset )
    {
        foreach ( $aLangArray as $sKey => $sValue ) {
            $aLangArray[$sKey] = iconv( $sCharset, 'UTF-8', $sValue );
        }

        return $aLangArray;
    }

    /**
     * Returns array with pathes where language files are stored
     *
     * @param bool $blAdmin admin mode
     * @param int  $iLang   active language
     *
     * @return array
     */
    protected function _getLangFilesPathArray( $blAdmin, $iLang )
    {
        $myConfig = $this->getConfig();
        $aLangFiles = array();

        //get all lang files
        $sStdPath = $myConfig->getStdLanguagePath( "", $blAdmin, $iLang );
        if ( $sStdPath ) {
            $aLangFiles[] = $sStdPath . "lang.php";
            $aLangFiles = array_merge( $aLangFiles, glob( $sStdPath."*_lang.php" ) );
        }

        $sCustPath = $myConfig->getLanguagePath( "", $blAdmin, $iLang );
        if ( $sCustPath && $sCustPath != $sStdPath ) {
            if ( is_readable( $sCustPath . "lang.php" ) ) {
                $aLangFiles[] = $sCustPath . "lang.php";
            }
            $aLangFiles = array_merge( $aLangFiles, glob( $sCustPath."*_lang.php" ) );
        }

        return count( $aLangFiles ) ? $aLangFiles : false;
    }

    /**
     * Returns language cache file name
     *
     * @param bool $blAdmin admin or not
     * @param int  $iLang   current language id
     *
     * @return string
     */
    protected function _getLangFileCacheName( $blAdmin, $iLang )
    {
        $myConfig = $this->getConfig();
        return "langcache_" . ( (int) $blAdmin ) . "_{$iLang}_" . $myConfig->getShopId() . "_" . $myConfig->getConfigParam( 'sTheme' );
    }

    /**
     * Returns language cache array
     *
     * @param bool $blAdmin admin or not [optional]
     * @param int  $iLang   current language id [optional]
     *
     * @return array
     */
    protected function _getLanguageFileData( $blAdmin = false, $iLang = 0 )
    {
        $myConfig = $this->getConfig();
        $myUtils  = oxUtils::getInstance();

        $sCacheName = $this->_getLangFileCacheName( $blAdmin, $iLang );
        $aLangCache = $myUtils->getLangCache( $sCacheName );
        if ( !$aLangCache && ( $aLangFiles = $this->_getLangFilesPathArray( $blAdmin, $iLang ) ) ) {
            $aLangCache[$iLang] = array();
            $sBaseCharset = false;
            foreach ( $aLangFiles as $sLangFile ) {

                if ( file_exists( $sLangFile ) && is_readable( $sLangFile ) ) {
                    include $sLangFile;

                    // including only (!) thoose, which has charset defined
                    if ( isset( $aLang['charset'] ) ) {

                        // recoding only in utf
                        if ( $myConfig->isUtf() ) {
                            $aLang = $this->_recodeLangArray( $aLang, $aLang['charset'] );

                            // overriding charset
                            $aLang['charset'] = 'UTF-8';
                        }

                        if ( !$sBaseCharset ) {
                            $sBaseCharset = $aLang['charset'];
                        }

                        $aLangCache[$iLang] = array_merge( $aLangCache[$iLang], $aLang );
                    }
                }
            }

            // setting base charset
            if ( $sBaseCharset ) {
                $aLangCache[$iLang]['charset'] = $sBaseCharset;
            }

            //save to cache
            $myUtils->setLangCache( $sCacheName, $aLangCache );
        }

        return $aLangCache;
    }

    /**
     * Returns current language cache language id
     *
     * @param bool $blAdmin admin mode
     * @param int  $iLang   language id [optional]
     *
     * @return int
     */
    protected function _getCacheLanguageId( $blAdmin, $iLang = null )
    {
        $iLang = ( $iLang === null && $blAdmin ) ? $this->getTplLanguage() : $iLang;
        if ( !isset( $iLang ) ) {
            $iLang = $this->getBaseLanguage();
            if ( !isset( $iLang ) ) {
                $iLang = 0;
            }
        }

        return (int) $iLang;
    }

    /**
     * get language array from lang translation file
     *
     * @param int  $iLang   optional language
     * @param bool $blAdmin admin mode switch
     *
     * @return array
     */
    protected function _getLangTranslationArray( $iLang = null, $blAdmin = null )
    {
        startProfile("_getLangTranslationArray");

        $blAdmin = isset( $blAdmin ) ? $blAdmin : $this->isAdmin();
        $iLang = $this->_getCacheLanguageId( $blAdmin, $iLang );
        $aLangCache = $blAdmin ? $this->_aAdminLangCache : $this->_aLangCache;
        if ( !isset( $aLangCache[$iLang] ) ) {
            // loading lang file data
            $aLangCache = $this->_getLanguageFileData( $blAdmin, $iLang );
            if ( $blAdmin ) {
                $this->_aAdminLangCache = $aLangCache;
            } else {
                $this->_aLangCache = $aLangCache;
            }
        }

        stopProfile("_getLangTranslationArray");

        // if language array exists ..
        return ( isset( $aLangCache[$iLang] ) ? $aLangCache[$iLang] : array() );
    }

    /**
     * translates a given string
     *
     * @param string $sStringToTranslate string that should be translated
     * @param int    $iLang              language id (optional)
     * @param bool   $blIsAdmin          admin mode switch (default null)
     *
     * @return string translation
     */
    protected function _readTranslateStrFromTextFile( $sStringToTranslate, $iLang = null, $blIsAdmin = null )
    {
        $blIsAdmin = isset( $blIsAdmin ) ? $blIsAdmin : $this->isAdmin();
        $iLang  = ( $iLang === null && $blIsAdmin)?$this->getTplLanguage():$iLang;
        if ( !isset( $iLang ) ) {
            $iLang = (int) $this->getBaseLanguage();
        }

        $sFileName = $this->getConfig()->getLanguagePath('lang.txt', $blIsAdmin, $iLang);
        if ( is_file ( $sFileName ) && is_readable( $sFileName ) ) {

            static $aUserLangCache = array();

            if ( !isset( $aUserLangCache[$sFileName] ) ) {
                $handle = @fopen( $sFileName, "r" );
                if ( $handle === false ) {
                    return $sStringToTranslate;
                }

                $contents = fread( $handle, filesize ( $sFileName ) );
                fclose( $handle );
                $fileArray = explode( "\n", $contents );
                $aUserLangCache[$sFileName] = array();
                $aLang = &$aUserLangCache[$sFileName];
                $oStr = getStr();

                while ( list( $nr,$line ) = each( $fileArray ) ) {
                    $line = ltrim( $line );
                    if ( $line[0]!="#" && $oStr->strpos( $line, "=" ) > 0 ) {
                        $index = trim( $oStr->substr( $line, 0, $oStr->strpos($line, "=" ) ) );
                        $value = trim( $oStr->substr( $line, $oStr->strpos( $line, "=" ) + 1, $oStr->strlen( $line ) ) );
                        $aLang[trim($index)] = trim($value);
                    }
                }
            }

            if ( !isset( $aLang ) && isset( $aUserLangCache[$sFileName] ) ) {
                $aLang = &$aUserLangCache[$sFileName];
            }

            if ( isset( $aLang[$sStringToTranslate] ) ) {
                $sStringToTranslate = $aLang[$sStringToTranslate];
            }
        }

        return $sStringToTranslate;
    }

    /**
     * Language sorting callback function
     *
     * @param object $a1 first value to check
     * @param object $a2 second value to check
     *
     * @return bool
     */
    protected function _sortLanguagesCallback( $a1, $a2 )
    {
        return ($a1->sort > $a2->sort);
    }

    /**
     * Returns language id param name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_sName;
    }

    /**
     * Returns form hidden language parameter
     *
     * @return string
     */
    public function getFormLang()
    {
        $sLang = null;
        if ( !$this->isAdmin()) {
            $sLang = "<input type=\"hidden\" name=\"".$this->getName()."\" value=\"". $this->getBaseLanguage() . "\">";
        }
        return $sLang;
    }

    /**
     * Returns url language parameter
     *
     * @param int $iLang lanugage id [optional]
     *
     * @return string
     */
    public function getUrlLang( $iLang = null )
    {
        $sLang = null;
        if ( !$this->isAdmin()) {
            $iLang = isset( $iLang ) ? $iLang : $this->getBaseLanguage();
            $sLang = $this->getName()."=". $iLang;
        }
        return $sLang;
    }

    /**
     * Is needed appends url with language parameter
     * Direct usage of this method to retrieve end url result is discouraged - instead
     * see oxUtilsUrl::processUrl
     *
     * @param string $sUrl  url to process
     * @param int    $iLang language id [optional]
     *
     * @see oxUtilsUrl::processUrl
     *
     * @return string
     */
    public function processUrl( $sUrl, $iLang = null )
    {
        $iLang = isset( $iLang ) ? $iLang : $this->getBaseLanguage();
        $oStr = getStr();

        if ( !$this->isAdmin() ) {
            $sParam = $this->getUrlLang( $iLang );
            if ( !$oStr->preg_match('/(\?|&(amp;)?)lang=[0-9]+/', $sUrl)  && ($iLang != oxConfig::getInstance()->getConfigParam( 'sDefaultLang' ))) {
                if ( $sUrl ) {
                    if ($oStr->strpos( $sUrl, '?') === false) {
                        $sUrl .= "?";
                    } elseif ( !$oStr->preg_match('/(\?|&(amp;)?)$/', $sUrl ) ) {
                        $sUrl .= "&amp;";
                    }
                }
                $sUrl .= $sParam."&amp;";
            } else {
                $sUrl = getStr()->preg_replace('/(\?|&(amp;)?)lang=[0-9]+/', '\1'.$sParam, $sUrl);
            }
        }

        return $sUrl;
    }

    /**
     * Detect language by user browser settings. Returns language ID if
     * detected, otherwise returns null.
     *
     * @return int
     */
    public function detectLanguageByBrowser()
    {
        $sBrowserLang = strtolower( substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ) );

        if ( !$sBrowserLang ) {
            return;
        }

        $aLangs = $this->getLanguageArray(null, true );

        foreach ( $aLangs as $oLang ) {
            if ( $oLang->abbr == $sBrowserLang ) {
                return (int) $oLang->id;
            }
        }
    }
}
