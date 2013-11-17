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
 * $Id: oxsysrequirements.php 16545 2009-02-13 14:24:57Z vilma $
 */

/**
 * System requirements class.
 * @package core
 */
class oxSysRequirements
{
    /**
     * System required modules
     *
     * @var array
     */
    protected $_aRequiredModules = null;

    /**
     * System requirements status
     *
     * @var bool
     */
    protected $_blSysReqStatus = null;

    /**
     * Columns that should not be check for collation
     *
     * @var array
     */
    protected $_aException = array( 'OXDELIVERY'   => 'OXDELTYPE',
                                    'OXSELECTLIST' => 'OXIDENT');

    /**
     * Columns to check for collation
     *
     * @var array
     */
    protected $_aColumns = array( 'OXID',
                                  'OXOBJECTID',
                                  'OXARTICLENID',
                                  'OXACTIONID',
                                  'OXARTID',
                                  'OXUSERID',
                                  'OXADDRESSUSERID',
                                  'OXCOUNTRYID',
                                  'OXSESSID',
                                  'OXITMID',
                                  'OXPARENTID',
                                  'OXAMITEMID',
                                  'OXAMTASKID',
                                  'OXVENDORID',
                                  'OXMANUFACTURERID',
                                  'OXROOTID',
                                  'OXATTRID',
                                  'OXCATID',
                                  'OXDELID',
                                  'OXDELSETID',
                                  'OXITMARTID',
                                  'OXFIELDID',
                                  'OXROLEID',
                                  'OXCNID',
                                  'OXANID',
                                  'OXARTICLENID',
                                  'OXCATNID',
                                  'OXDELIVERYID',
                                  'OXDISCOUNTID',
                                  'OXGROUPSID',
                                  'OXLISTID',
                                  'OXPAYMENTID',
                                  'OXDELTYPE',
                                  'OXROLEID',
                                  'OXSELNID',
                                  'OXBILLCOUNTRYID',
                                  'OXDELCOUNTRYID',
                                  'OXPAYMENTID',
                                  'OXCARDID',
                                  'OXPAYID',
                                  'OXIDENT',
                                  'OXDEFCAT',
                                  'OXBASKETID',
                                  'OXPAYMENTSID',
                                  'OXORDERID',
                                  'OXVOUCHERSERIEID');

   /**
     * Class constructor. The constructor is defined in order to be possible to call parent::__construct() in modules.
     *
     * @return null;
     */
	public function __construct()
	{
	}

    /**
     * Sets system required modules
     *
     * @return array
     */
    public function getRequiredModules()
    {
        if ( $this->_aRequiredModules == null ) {
            $aRequiredPHPExtensions = array(
                                          'php_version',
                                          'lib_xml2',
                                          'php_xml',
                                          'j_son',
                                          'i_conv',
                                          'tokenizer',
                                          'mysql_connect',
                                          'gd_info',
                                          'mb_string',
                                      );

                $aRequiredPHPExtensions[] = 'bc_math';

            $aRequiredPHPConfigs = array(
                                       'allow_url_fopen',
                                       'php4_compat',
                                       'request_uri',
                                       'ini_set',
                                       'register_globals',
                                       'memory_limit',
                                       'unicode_support'
                                   );

            $aRequiredServerConfigs = array(
                                          'mod_rewrite'
                                      );


            $this->_aRequiredModules = array_fill_keys( $aRequiredPHPExtensions, 'php_extennsions' ) +
                                       array_fill_keys( $aRequiredPHPConfigs, 'php_config' ) +
                                       array_fill_keys( $aRequiredServerConfigs, 'server_config' );
        }
        return $this->_aRequiredModules;
    }

    /**
     * Checks if mbstring extension is loaded
     *
     * @return integer
     */
    public function checkMbString()
    {
        return extension_loaded( 'mbstring' ) ? 2 : 1;
    }

    /**
     * Checks if mod_rewrite extension is loaded
     *
     * @return integer
     */
    public function checkModRewrite()
    {
        $iModStat = null;
        $sHost   = $_SERVER['HTTP_HOST'];
        $sScript = $_SERVER['SCRIPT_NAME'];
        if ( $sScript && $rFp = @fsockopen( $sHost, 80, $iErrNo, $sErrStr, 10 ) ) {
            $sScript = str_replace( basename($sScript), '../admin/test.php', $sScript );

            $sReq  = "POST $sScript HTTP/1.1\r\n";
            $sReq .= "Host: $sHost\r\n";
            $sReq .= "User-Agent: oxid setup\r\n";
            $sReq .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $sReq .= "Content-Length: 0\r\n"; // empty post
            $sReq .= "Connection: close\r\n\r\n";

            $sOut = '';
            fwrite( $rFp, $sReq );
            while ( !feof( $rFp ) ) {
                $sOut .= fgets( $rFp, 100 );
            }
            fclose( $rFp );

            $iModStat = ( strpos( $sOut, 'mod_rewrite_on' ) !== false ) ? 2 : 0;
        } else {
            if ( function_exists( 'apache_get_modules' ) ) {
                // it does not assure that mod_rewrite is enabled on current host, so setting 1
                $iModStat = in_array( 'mod_rewrite', apache_get_modules() ) ? 1 : 0;
            } else {
                $iModStat = -1;
            }
        }
        return $iModStat;
    }

    /**
     * Checks if activated allow_url_fopen or fsockopen on port 80 possible
     *
     * @return integer
     */
    public function checkAllowUrlFopen()
    {
        $iModStat = @ini_get('allow_url_fopen');
        $iModStat = ( $iModStat && strcasecmp( '1', $iModStat ) ) ? 2 : 1;
        if ( $iModStat == 1 ) {
            $iErrNo  = 0;
            $sErrStr = '';
            if ( $oRes = @fsockopen( 'www.example.com', 80, $iErrNo, $sErrStr, 10 ) ) {
                $iModStat = 2;
                fclose( $oRes );
            }
        }
        $iModStat = ( !$iModStat ) ? 1 : $iModStat;
        return $iModStat;
    }

    /**
     * PHP4 compatibility mode must be set off:
     * zend.ze1_compatibility_mode = Off
     *
     * @return integer
     */
    public function checkPhp4Compat()
    {
        $sZendStatus = ( strtolower( (string) @ini_get( 'zend.ze1_compatibility_mode' ) ) );
        return in_array( $sZendStatus, array( 'on', '1' ) ) ? 0 : 2;
    }

    /**
     * Checks PHP version. PHP 5.2.0 or higher.
     * Due to performance matters, PHP 5.2.6 recommended.
     *
     * @return integer
     */
    public function checkPhpVersion()
    {
        $iModStat = ( version_compare( PHP_VERSION, '5.1', '>' ) ) ? 1 : 0;
        $iModStat = ( $iModStat == 0 ) ? $iModStat : ( version_compare( PHP_VERSION, '5.2', '>=' ) ? 2 : 1 );
        return $iModStat;
    }

    /**
     * Checks if apache server variables REQUEST_URI or SCRIPT_URI are set
     *
     * @return integer
     */
    public function checkRequestUri()
    {
        return ( isset( $_SERVER['REQUEST_URI'] ) || isset( $_SERVER['SCRIPT_URI'] ) ) ? 2 : 0;
    }

    /**
     * Checks if libxml2 is activated
     *
     * @return integer
     */
    public function checkLibXml2()
    {
        return class_exists( 'DOMDocument' ) ? 2 : 0;
    }

    /**
     * Checks if php-xml is activated ???
     *
     * @return integer
     */
    public function checkPhpXml()
    {
        return class_exists( 'DOMDocument' ) ? 2 : 0;
    }

    /**
     * Checks if JSON extension is loaded
     *
     * @return integer
     */
    public function checkJSon()
    {
        return extension_loaded( 'json' ) ? 2 : 0;
    }

    /**
     * Checks if iconv extension is loaded
     *
     * @return integer
     */
    public function checkIConv()
    {
        return extension_loaded( 'iconv' ) ? 2 : 0;
    }

    /**
     * Checks if tokenizer extension is loaded
     *
     * @return integer
     */
    public function checkTokenizer()
    {
        return extension_loaded( 'tokenizer' ) ? 2 : 0;
    }

    /**
     * Checks if bcmath extension is loaded
     *
     * @return integer
     */
    public function checkBcMath()
    {
        return extension_loaded( 'bcmath' ) ? 2 : 1;
    }

    /**
     * Checks if mysql5 extension is loaded.
     *
     * @return integer
     */
    public function checkMysqlConnect()
    {
        // MySQL module for MySQL5
        $iModStat = extension_loaded( 'mysql' ) ? 2 : 0;
        // client version must be >=5
        if ( $iModStat ) {
            $sClientVersion = mysql_get_client_info();
            if (version_compare( $sClientVersion, '5', '<' )) {
                $iModStat = 1;
                if (version_compare( $sClientVersion, '4', '<' )) {
                    $iModStat = 0;
                }
            } elseif (version_compare($sClientVersion, '5.0.36', '>=') && version_compare($sClientVersion, '5.0.38', '<')) {
                // mantis#0001003: Problems with MySQL version 5.0.37
                $iModStat = 0;
            }
        }
        return $iModStat;
    }

    /**
     * Checks if GDlib extension is loaded
     *
     * @return integer
     */
    public function checkGdInfo()
    {
        $iModStat = extension_loaded( 'gd' ) ? 1 : 0;
        $iModStat = function_exists( 'imagecreatetruecolor' ) ? 2 : $iModStat;
        $iModStat = function_exists( 'imagecreatefromjpeg' ) ? $iModStat : 0;
        return $iModStat;
    }

    /**
     * Checks if ini set is allowed
     *
     * @return integer
     */
    public function checkIniSet()
    {
        return ( @ini_set('session.name', 'sid' ) !== false ) ? 2 : 0;
    }

    /**
     * Checks if register_globals are off/on. Should be off.
     *
     * @return integer
     */
    public function checkRegisterGlobals()
    {
        $sGlobStatus = ( strtolower( (string) @ini_get( 'register_globals' ) ) );
        return in_array( $sGlobStatus, array( 'on', '1' ) ) ? 0 : 2;
    }

    /**
     * Checks memory limit.
     *
     * @return integer
     */
    public function checkMemoryLimit()
    {
        if ( $sMemLimit = @ini_get('memory_limit') ) {
                // CE - PE at least to 14 MB. We recomend a memory_limit of 30MB.
                $sDefLimit = '14M';
                $sRecLimit = '30M';


            $iMemLimit = $this->_getBytes( $sMemLimit );
            $iModStat = ( $iMemLimit >= $this->_getBytes( $sDefLimit ) ) ? 1 : 0;
            $iModStat = $iModStat ? ( ( $iMemLimit >= $this->_getBytes( $sRecLimit ) ) ? 2 : $iModStat ) : $iModStat;

        } else {
            $iModStat = -1;
        }
        return $iModStat;
    }

    /**
     * Checks if Zend Optimizer extension is loaded
     *
     * @return integer
     */
    public function checkZendOptimizer()
    {
        $iModStat = extension_loaded( 'Zend Optimizer' ) ? 2 : 0;
        $sHost   = $_SERVER['HTTP_HOST'];
        $sScript = $_SERVER['SCRIPT_NAME'];
        if ( $iModStat > 0 && $sScript && $rFp = @fsockopen( $sHost, 80, $iErrNo, $sErrStr, 10 ) ) {
            $sScript = str_replace( basename($sScript), '../admin/index.php', $sScript );

            $sReq  = "POST $sScript HTTP/1.1\r\n";
            $sReq .= "Host: $sHost\r\n";
            $sReq .= "User-Agent: oxid setup\r\n";
            $sReq .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $sReq .= "Content-Length: 0\r\n"; // empty post
            $sReq .= "Connection: close\r\n\r\n";

            $sOut = '';
            fwrite( $rFp, $sReq );
            while ( !feof( $rFp ) ) {
                $sOut .= fgets( $rFp, 100 );
            }

            $iModStat = ( strpos( $sOut, 'Zend Optimizer not installed' ) !== false ) ? 0 : 2;
            fclose( $rFp );
        }
        if ( $iModStat > 0 && $sScript && $rFp = @fsockopen( $sHost, 80, $iErrNo, $sErrStr, 10 ) ) {
            $sScript = str_replace( basename($sScript), '../index.php', $sScript );

            $sReq  = "POST $sScript HTTP/1.1\r\n";
            $sReq .= "Host: $sHost\r\n";
            $sReq .= "User-Agent: oxid setup\r\n";
            $sReq .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $sReq .= "Content-Length: 0\r\n"; // empty post
            $sReq .= "Connection: close\r\n\r\n";

            $sOut = '';
            fwrite( $rFp, $sReq );
            while ( !feof( $rFp ) ) {
                $sOut .= fgets( $rFp, 100 );
            }
            $iModStat = ( strpos( $sOut, 'Zend Optimizer not installed' ) !== false ) ? 0 : 2;
            fclose( $rFp );
        }
        return $iModStat;
    }

    /**
     * Checks if ZEND Platform Version 3.5 is installed
     *
     * @return integer
     */
    public function checkZendPlatform()
    {
        return function_exists( 'output_cache_get' ) ? 2 : 1;
    }

    /**
     * Additional sql: do not check collation for oxsysrequirements::$_aException columns
     *
     * @return string
     */
    protected function _getAdditionalCheck()
    {
        $sSelect = '';
        foreach ( $this->_aException as $sTable => $sColumn ) {
            $sSelect .= 'and ( t.TABLE_NAME != "'.$sTable.'" and c.COLUMN_NAME != "'.$sColumn.'" ) ';
        }
        return $sSelect;
    }

    /**
     * Checks tables and columns (oxsysrequirements::$_aColumns) collation
     *
     * @return array
     */
    public function checkCollation()
    {
        $myConfig = oxConfig::getInstance();

        $aCollations = array();
        $sCollation = '';

        $sSelect = 'select t.TABLE_NAME, c.COLUMN_NAME, c.COLLATION_NAME from INFORMATION_SCHEMA.tables t ' .
                   'LEFT JOIN INFORMATION_SCHEMA.columns c ON t.TABLE_NAME = c.TABLE_NAME  ' .
                   'where t.TABLE_SCHEMA = "'.$myConfig->getConfigParam( 'dbName' ).'" ' .
                   'and c.TABLE_SCHEMA = "'.$myConfig->getConfigParam( 'dbName' ).'" ' .
                   'and c.COLUMN_NAME in ("'.implode('", "', $this->_aColumns).'") ' . $this->_getAdditionalCheck() .
                   ' ORDER BY (t.TABLE_NAME = "oxarticles") DESC';
        $aRez = oxDb::getDb()->getAll($sSelect);
        foreach ( $aRez as $aRetTable ) {
            if ( !$sCollation ) {
                $sCollation = $aRetTable[2];
            } else {
                if ( $aRetTable[2] && $sCollation != $aRetTable[2]) {
                    $aCollations[$aRetTable[0]][$aRetTable[1]] = $aRetTable[2];
                }
            }
        }

        if ( $this->_blSysReqStatus === null ) {
            $this->_blSysReqStatus = true;
        }
        if ( count($aCollations) > 0 ) {
            $this->_blSysReqStatus = false;
        }
        return $aCollations;
    }

    /**
     * Checks if database cluster is installed
     *
     * @return integer
     */
    public function checkDatabaseCluster()
    {
        return 2;
    }

    /**
     * Checks if PCRE unicode support is turned off/on. Should be on.
     *
     * @return integer
     */
    public function checkUnicodeSupport()
    {
        return (@preg_match('/\pL/u', 'a') == 1) ? 2 : 1;
    }

    /**
     * Checks system requirements status
     *
     * @return bool
     */
    public function getSysReqStatus()
    {
        if ( $this->_blSysReqStatus == null ) {
            $this->_blSysReqStatus = true;
            $this->getSystemInfo();
            $this->checkCollation();
        }
        return $this->_blSysReqStatus;
    }

    /**
     * Runs through modules array and checks if current system fits requirements.
     * Returns array with module info:
     *   array( $sGroup, $sModuleName, $sModuleState ):
     *     $sGroup       - group of module
     *     $sModuleName  - name of checked module
     *     $sModuleState - module state:
     *       -1 - unable to datect, should not block
     *        0 - missing, blocks setup
     *        1 - fits min requirements
     *        2 - exists required or better
     *
     * @return array $aSysInfo
     */
    public function getSystemInfo()
    {
        $aSysInfo = array();
        $aRequiredModules = $this->getRequiredModules();
        $this->_blSysReqStatus = true;
        foreach ( $aRequiredModules as $sModule => $sGroup ) {
            if ( isset($aSysInfo[$sGroup]) && !$aSysInfo[$sGroup] ) {
                $aSysInfo[$sGroup] = array();
            }
            $iModuleState = $this->getModuleInfo( $sModule );
            $aSysInfo[$sGroup][$sModule] = $iModuleState;
            $this->_blSysReqStatus = $this->_blSysReqStatus && ( bool ) abs( $iModuleState );
        }
        return $aSysInfo;
    }

    /**
     * Returns passed module state
     *
     * @param string $sModule module name to check
     *
     * @return integer $iModStat
     */
    public function getModuleInfo( $sModule = null )
    {
        if ( $sModule ) {
            $iModStat = null;
            $sCheckFunction = "check".str_replace(" ","",ucwords(str_replace("_"," ",$sModule)));
            $iModStat = $this->$sCheckFunction();

            return $iModStat;
        }
    }

    /**
     * Parses and calculates given string form byte syze value
     *
     * @param string $sBytes string form byte value (64M, 32K etc)
     *
     * @return int
     */
    protected function _getBytes( $sBytes )
    {
        $sBytes = trim( $sBytes );
        $sLast = strtolower($sBytes[strlen($sBytes)-1]);
        switch( $sLast ) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $sBytes *= 1024;
            case 'm':
                $sBytes *= 1024;
            case 'k':
                $sBytes *= 1024;
                break;
        }

        return $sBytes;
    }

}