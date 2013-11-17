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
 * $Id: oxlang.php 18973 2009-05-12 13:15:11Z vilma $
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
        $myConfig = $this->getConfig();
        //$this->_iBaseLanguageId = null;

        if ( $this->_iBaseLanguageId !== null ) {
            return $this->_iBaseLanguageId;
        }

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

        if ( is_null( $this->_iBaseLanguageId ) ) {
            $this->_iBaseLanguageId = $myConfig->getConfigParam( 'sDefaultLang' );
        }

        $this->_iBaseLanguageId = (int) $this->_iBaseLanguageId;

        // validating language
        $this->_iBaseLanguageId = $this->validateLanguage( $this->_iBaseLanguageId );

        return $this->_iBaseLanguageId;
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
        if ( $this->_iTplLanguageId !== null ) {
            return $this->_iTplLanguageId;
        }

        if ( !$this->isAdmin() ) {
            $this->_iTplLanguageId = $this->getBaseLanguage();
        } else {
            //admin area

            if ( is_null( $this->_iTplLanguageId ) ) {
                //$this->_iTplLanguageId = oxConfig::getParameter( 'tpllanguage' );
                $this->_iTplLanguageId = oxSession::getVar( 'tpllanguage' );
            }

            if ( is_null( $this->_iTplLanguageId ) ) {
                $this->_iTplLanguageId = $this->getBaseLanguage();
            }
        }

        // validating language
        $this->_iTplLanguageId = $this->validateLanguage( $this->_iTplLanguageId );

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
                        continue;
                    }
                }

                if ( $val) {
                    $oLang = new oxStdClass();
                    if ( isset($aLangParams[$key]['baseId']) ) {
                        $oLang->id  = $aLangParams[$key]['baseId'];
                    } else {
                        $oLang->id  = $i;
                    }
                    $oLang->oxid    = $key;
                    $oLang->abbr    = $key;
                    $oLang->name    = $val;

                    if ( is_array($aLangParams) ) {
                        $oLang->active  = $aLangParams[$key]['active'];
                        $oLang->sort   = $aLangParams[$key]['sort'];
                    }

                    if ( isset( $iLanguage ) && $i == $iLanguage ) {
                        $oLang->selected = 1;
                    } else {
                        $oLang->selected = 0;
                    }
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
     * Returns selected language abbervation
     *
     * @param int $iLanguage language number
     *
     * @access public
     * @return string
     */
    public function getLanguageAbbr( $iLanguage = null)
    {
        $myConfig = $this->getConfig();

        if ( !isset($iLanguage) ) {
            $iLanguage = $this->_iBaseLanguageId;
        }

        $aLangAbbr = $this->getLanguageIds();

        if ( isset($iLanguage,$aLangAbbr[$iLanguage]) ) {
            return $aLangAbbr[$iLanguage];
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
        return array_values( $this->getConfig()->getConfigParam( 'aLanguages' ));
    }

    /**
     * Returns available language IDs (abbervations)
     *
     * @return array
     */
    public function getLanguageIds()
    {
        $aLangParams = $this->getConfig()->getConfigParam( 'aLanguageParams' );

        //if exists language parameters array, extract lang id's from there
        if ( is_array($aLangParams) ) {

            $aIds = array();

            foreach ( $aLangParams as $sAbbr => $aValue ) {
                $iBaseId = (int) $aValue['baseId'];
                $aIds[$iBaseId] = $sAbbr;
            }
            return $aIds;
        }

        return array_keys( $this->getConfig()->getConfigParam( 'aLanguages' ));
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
     * Returns formatted currency string, according to formatting standards.
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
        return number_format( $dValue, $oActCur->decimal, $oActCur->dec, $oActCur->thousand );
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
        return number_format( $dValue, $iDecPos, $oActCur->dec, $oActCur->thousand );
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

        // checking if this language is valid
        $aLanguages = $this->getLanguageArray();

        if ( !isset( $aLanguages[$iLang] ) && is_array( $aLanguages ) ) {
            $oLang = current( $aLanguages );
            $iLang = $oLang->id;
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
     * Set templates language id
     *
     * @param int $iLang Language id
     *
     * @return null
     */
    public function setTplLanguage( $iLang = null )
    {
        if ( is_null($iLang) ) {
            $iLang = $this->getTplLanguage();
        } else {
            $this->_iTplLanguageId = (int) $iLang;
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            modSession::getInstance();
        }

        oxSession::setVar( 'tpllanguage', $iLang );
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

        $sCacheName = "langcache_".( (int) $blAdmin )."_{$iLang}_".$myConfig->getShopId();
        $aLangCache = $myUtils->getLangCache( $sCacheName );
        if ( !$aLangCache ) {
            $sDir = dirname( $myConfig->getLanguagePath( 'lang.php', $blAdmin, $iLang ) );

            //get all lang files
            //#M681: content of cust_lang.php should be prefered to lang.php
            $aLangFiles = glob( $sDir."/*_lang.php" );
            array_unshift($aLangFiles, $sDir."/lang.php");

            $aLangCache[$iLang] = array();
            if (!$sDir) {
            	return array();
            }
            foreach ( $aLangFiles as $sLangFile ) {
                require $sLangFile;

                // inclyding only (!) thoose, which has charset defined
                if ( isset( $aLang['charset'] ) ) {

                    // recoding only in utf
                    if ( $myConfig->isUtf() ) {
                        $aLang = $this->_recodeLangArray( $aLang, $aLang['charset'] );

                        // overriding charset
                        $aLang['charset'] = 'UTF-8';
                    }

                    $aLangCache[$iLang] = array_merge( $aLangCache[$iLang], $aLang );
                }
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
        startProfile("<b>_getLangTranslationArray</b>");

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

        stopProfile("<b>_getLangTranslationArray</b>");

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
            $iLang = $this->getBaseLanguage();
            if ( !isset( $iLang ) ) {
                $iLang = 0;
            }
        }

        $sFileName = $this->getConfig()->getLanguagePath('lang.txt', $blIsAdmin, $iLang);

        if ( is_file ( $sFileName ) ) {

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
                return $aLang[$sStringToTranslate];
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

}
