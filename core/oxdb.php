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
 * @version   SVN: $Id: oxdb.php 39237 2011-10-12 14:49:10Z arvydas.vapsva $
 */


// Including main ADODB include
require_once getShopBasePath() . 'core/adodblite/adodb.inc.php';

/**
 * Database connection class
 */
class oxDb extends oxSuperCfg
{
    /**
     * oxDb instance.
     *
     * @var oxdb
     */
    protected static $_instance = null;

    /**
     * Database object
     *
     * @var oxdb
     */
    protected static  $_oDB = null;

    /**
     * Database tables descriptions cache array
     *
     * @var array
     */
    protected static $_aTblDescCache = array();

    /**
     * Returns Singelton instance
     *
     * @return oxdb
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            self::$_instance = modInstances::getMod( __CLASS__ );
        }


        if ( !self::$_instance instanceof oxDb ) {

            //do not use simple oxNew here as it goes to eternal cycle
            self::$_instance = oxNew( 'oxdb' );

            if ( defined( 'OXID_PHP_UNIT' ) ) {
                modInstances::addMod( __CLASS__, self::$_instance);
            }
        }
        return self::$_instance;
    }

    /**
     * Returns database object
     *
     * @param boolean $blAssoc default false
     *
     * @throws oxConnectionException error while initiating connection to DB
     *
     * @return ADOConnection
     */
    public static function getDb( $blAssoc = false )
    {
        global $ADODB_FETCH_MODE;

        if ( $blAssoc ) {
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        } else {
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        }

        if ( defined( 'OXID_PHP_UNIT' ) ) {
            if ( isset( modDB::$unitMOD ) && is_object( modDB::$unitMOD ) ) {
                return modDB::$unitMOD;
            }
        }

        if ( self::$_oDB !== null ) {
            return self::$_oDB;
        }

        global  $ADODB_CACHE_DIR;
        global  $ADODB_DRIVER,
                $ADODB_SESSION_TBL,
                $ADODB_SESSION_CONNECT,
                $ADODB_SESSION_DRIVER,
                $ADODB_SESSION_USER,
                $ADODB_SESSION_PWD,
                $ADODB_SESSION_DB,
                $ADODB_SESS_LIFE,
                $ADODB_SESS_DEBUG;

        //adding exception handler for SQL errors
        $myConfig = self::getInstance()->getConfig();
        $iDebug = $myConfig->getConfigParam( 'iDebug' );
        if ( $iDebug ) {
            include_once getShopBasePath() . 'core/adodblite/adodb-exceptions.inc.php';
        }

        // session related parameters. don't change.

        //Tomas
        //the default setting is 3000 * 60, but actually changing this will give no effect as now redefinition of this constant
        //appears after OXID custom settings are loaded and $ADODB_SESS_LIFE depends on user settings.
        //You can find the redefinition of ADODB_SESS_LIFE @ oxconfig.php:: line ~ 390.
        $ADODB_SESS_LIFE       = 3000 * 60;
        $ADODB_SESSION_TBL     = "oxsessions";
        $ADODB_SESSION_DRIVER  = $myConfig->getConfigParam( 'dbType' );
        $ADODB_SESSION_USER    = $myConfig->getConfigParam( 'dbUser' );
        $ADODB_SESSION_PWD     = $myConfig->getConfigParam( 'dbPwd' );
        $ADODB_SESSION_DB      = $myConfig->getConfigParam( 'dbName' );
        $ADODB_SESSION_CONNECT = $myConfig->getConfigParam( 'dbHost' );
        $ADODB_SESS_DEBUG      = false;
        $ADODB_CACHE_DIR       = $myConfig->getConfigParam( 'sCompileDir' );

        $sModules = '';
        if (  $iDebug == 2 || $iDebug == 3 || $iDebug == 4 || $iDebug == 7  ) {
            $sModules = 'perfmon';
        }

        // log admin changes ?
        if ( $myConfig->isAdmin() && $myConfig->getConfigParam( 'blLogChangesInAdmin' ) ) {
            $sModules = ( $sModules ? ':' : '' ) . 'oxadminlog';
        }

        self::$_oDB = ADONewConnection( $myConfig->getConfigParam( 'dbType' ), $sModules );

        $sVerPrefix = '';
            $sVerPrefix = '_ce';

        if ( !self::$_oDB->connect( $myConfig->getConfigParam( 'dbHost' ), $myConfig->getConfigParam( 'dbUser' ), $myConfig->getConfigParam( 'dbPwd' ), $myConfig->getConfigParam( 'dbName' ) ) ) {
            $sConfig = join( '', file( getShopBasePath().'config.inc.php' ) );
            if ( strpos( $sConfig, '<dbHost'.$sVerPrefix.'>' ) !== false &&
                 strpos( $sConfig, '<dbName'.$sVerPrefix.'>' ) !== false ) {
                header( 'location:setup/index.php' ); // pop to setup as there is something wrong
                oxUtils::getInstance()->showMessageAndExit( "" );
            } else {

                // notifying shop owner about connection problems
                $sFailedShop = isset( $_REQUEST['shp'] )?addslashes( $_REQUEST['shp'] ):'Base shop';

                $sDate = date( 'l dS of F Y h:i:s A');
                $sScript  = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
                $sReferer = $_SERVER['HTTP_REFERER'];

                //sending a message to admin
                $sWarningSubject = 'Offline warning!';
                $sWarningBody = "
                Database error in OXID eShop:
                Date: $sDate
                Shop: $sFailedShop

                mysql error: ".self::$_oDB->errorMsg()."
                mysql error no: ".self::$_oDB->errorNo()."

                Script: $sScript
                Referer: $sReferer";

                if ( ( $sAdminEmail = $myConfig->getConfigParam( 'sAdminEmail' ) ) ) {
                    include 'core/phpmailer/class.phpmailer.php';

                    $oMailer = new phpmailer();
                    $oMailer->isMail();
                    $oMailer->From = $sAdminEmail;
                    $oMailer->AddAddress( $sAdminEmail );
                    $oMailer->Subject = $sWarningSubject;
                    $oMailer->Body = $sWarningBody;
                    $oMailer->send();
                }

                //only exception to default construction method
                $oEx = new oxConnectionException();
                $oEx->setMessage( 'EXCEPTION_CONNECTION_NODB' );
                $oEx->setConnectionError( $myConfig->getConfigParam( 'dbUser' ).'s'.getShopBasePath().self::$_oDB->errorMsg() );
                throw $oEx;
            }
        }

        if (  $iDebug == 2 || $iDebug == 3 || $iDebug == 4  || $iDebug == 7 ) {
            try {
                self::$_oDB->execute('truncate table adodb_logsql;');
            } catch (ADODB_Exception $e) {
                // nothing
            }
            self::$_oDB->logSQL( true );
        }

        self::$_oDB->cacheSecs = 60 * 10; // 10 minute caching
        self::$_oDB->execute( 'SET @@session.sql_mode = ""' );

        if ( $myConfig->isUtf() ) {
            self::$_oDB->execute( 'SET NAMES "utf8"' );
            self::$_oDB->execute( 'SET CHARACTER SET utf8' );
            self::$_oDB->execute( 'SET CHARACTER_SET_CONNECTION = utf8' );
            self::$_oDB->execute( 'SET CHARACTER_SET_DATABASE = utf8' );
            self::$_oDB->execute( 'SET character_set_results = utf8' );
            self::$_oDB->execute( 'SET character_set_server = utf8' );
        } elseif ( $myConfig->getConfigParam('sDefaultDatabaseConnection') != '' ) {
            self::$_oDB->execute( 'SET NAMES ' . $myConfig->getConfigParam('sDefaultDatabaseConnection') );
        }

        return self::$_oDB;
    }

    /**
     * Returns database field name with _ Language ID if needed
     *
     * @param string $sField the field name
     *
     * @return string
     */
    public function getMultiLangFieldName( $sField )
    {
        /*
        $sLangAdd = $this->getConfig()->getShopLanguage();
        if ( $sLangAdd ) {
            $sField .= '_'.$sLangAdd;
        }*/

        return $sField . oxLang::getInstance()->getLanguageTag();
    }

    /**
     * Checks if field, according to field type, must be surrounded with quote
     * symbols. Returns true if yes.
     *
     * @param string $sFieldtype Type of field
     *
     * @return bool
     */
    public function isQuoteNeeded( $sFieldtype)
    {
        $aTypesWoQuotes = array('int', 'decimal', 'float', 'tinyint', 'smallint', 'mediumint', 'bigint', 'double');
        return !in_array( $sFieldtype, $aTypesWoQuotes);
    }

    /**
     * Quotes an array.
     *
     * @param array $aStrArray array of strings to quote
     *
     * @return array
     */
    public function quoteArray( $aStrArray)
    {
        foreach ( $aStrArray as $sKey => $sString ) {
            $aStrArray[$sKey] = self::getDb()->quote($sString);
        }
        return $aStrArray;
    }

    /**
     * Call to reset table description cache
     *
     * @return null
     */
    public function resetTblDescCache()
    {
        self::$_aTblDescCache = array();
    }

    /**
     * Extracts and returns table metadata from DB.
     *
     * @param string $sTableName Name of table to invest.
     *
     * @return array
     */
    public function getTableDescription( $sTableName )
    {
        // simple cache
        if ( isset( self::$_aTblDescCache[$sTableName] ) ) {
            return self::$_aTblDescCache[$sTableName];
        }

            $aFields = self::getDb()->MetaColumns( $sTableName );

        self::$_aTblDescCache[$sTableName] = $aFields;

        return $aFields;
    }

    /**
     * Bidirectional converter for date/datetime field
     *
     * @param object $oObject       data field object
     * @param bool   $blToTimeStamp set TRUE to format MySQL compatible value
     * @param bool   $blOnlyDate    set TRUE to format "date" type field
     *
     * @return string
     */
    public function convertDBDateTime( $oObject, $blToTimeStamp = false, $blOnlyDate = false )
    {
        $sDate = $oObject->value;

        // defining time format
        $sLocalDateFormat = $this->_defineAndCheckDefaultDateValues( $blToTimeStamp );
        $sLocalTimeFormat = $this->_defineAndCheckDefaultTimeValues( $blToTimeStamp );

        // default date/time patterns
        $aDefDatePatterns = $this->_defaultDatePattern();

        // regexps to validate input
        $aDatePatterns = $this->_regexp2ValidateDateInput();
        $aTimePatterns = $this->_regexp2ValidateTimeInput();

        // date/time formatting rules
        $aDFormats  = $this->_defineDateFormattingRules();
        $aTFormats  = $this->_defineTimeFormattingRules();

        // empty date field value ? setting default value
        if ( !$sDate) {
            $this->_setDefaultDateTimeValue($oObject, $sLocalDateFormat, $sLocalTimeFormat, $blOnlyDate);
            return $oObject->value;
        }

        $blDefDateFound = false;
        $oStr = getStr();

        // looking for default values that are formatted by MySQL
        foreach ( array_keys( $aDefDatePatterns ) as $sDefDatePattern ) {
            if ( $oStr->preg_match( $sDefDatePattern, $sDate)) {
                $blDefDateFound = true;
                break;
            }
        }

        // default value is set ?
        if ( $blDefDateFound) {
            $this->_setDefaultFormatedValue($oObject, $sDate, $sLocalDateFormat, $sLocalTimeFormat, $blOnlyDate);
            return $oObject->value;
        }

        $blDateFound = false;
        $blTimeFound = false;
        $aDateMatches = array();
        $aTimeMatches = array();

        // looking for date field
        foreach ( $aDatePatterns as $sPattern => $sType) {
            if ( $oStr->preg_match( $sPattern, $sDate, $aDateMatches)) {
                $blDateFound = true;

                // now we know the type of passed date
                $sDateFormat = $aDFormats[$sLocalDateFormat][0];
                $aDFields    = $aDFormats[$sType][1];
                break;
            }
        }

        // no such date field available ?
        if ( !$blDateFound) {
            return $sDate;
        }

        if ( $blOnlyDate) {
            $this->_setDate($oObject, $sDateFormat, $aDFields, $aDateMatches);
            return $oObject->value;
        }

        // looking for time field
        foreach ( $aTimePatterns as $sPattern => $sType) {
            if ( $oStr->preg_match( $sPattern, $sDate, $aTimeMatches)) {
                $blTimeFound = true;

                // now we know the type of passed time
                $sTimeFormat = $aTFormats[$sLocalTimeFormat][0];
                $aTFields    = $aTFormats[$sType][1];

                //
                if ( $sType == "USA" && isset($aTimeMatches[4])) {
                    $iIntVal = (int) $aTimeMatches[1];
                    if ( $aTimeMatches[4] == "PM") {
                        if ( $iIntVal < 13) {
                            $iIntVal += 12;
                        }
                    } elseif ( $aTimeMatches[4] == "AM" && $aTimeMatches[1] == "12") {
                        $iIntVal = 0;
                    }

                    $aTimeMatches[1] = sprintf("%02d", $iIntVal);
                }

                break;
            }
        }

        if ( !$blTimeFound) {
            //return $sDate;
            // #871A. trying to keep date as possible correct
            $this->_setDate($oObject, $sDateFormat, $aDFields, $aDateMatches);
            return $oObject->value;
        }

        $this->_formatCorrectTimeValue($oObject, $sDateFormat, $sTimeFormat, $aDateMatches, $aTimeMatches, $aTFields, $aDFields);

        // on some cases we get empty value
        if ( !$oObject->fldmax_length) {
            return $this->convertDBDateTime( $oObject, $blToTimeStamp, $blOnlyDate);
        }
        return $oObject->value;
    }

    /**
     * Bidirectional converter for timestamp field
     *
     * @param object $oObject       oxField type object that keeps db field info
     * @param bool   $blToTimeStamp if true - converts value to database compatible timestamp value
     *
     * @return string
     */
    public function convertDBTimestamp( $oObject, $blToTimeStamp = false )
    {
         // on this case usually means that we gonna save value, and value is formatted, not plain
        $sSQLTimeStampPattern = "/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/";
        $sISOTimeStampPattern = "/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/";
        $aMatches = array();
        $oStr = getStr();

        // preparing value to save
        if ( $blToTimeStamp) {
            // reformatting value to ISO
            $this->convertDBDateTime( $oObject, $blToTimeStamp );

            if ( $oStr->preg_match( $sISOTimeStampPattern, $oObject->value, $aMatches)) {
                // changing layout
                $oObject->setValue($aMatches[1].$aMatches[2].$aMatches[3].$aMatches[4].$aMatches[5].$aMatches[6]);
                $oObject->fldmax_length = strlen( $oObject->value);
                return $oObject->value;
            }
        } else {
            // loading and formatting value
            // checking and parsing SQL timestamp value
            //$sSQLTimeStampPattern = "/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/";
            if ( $oStr->preg_match( $sSQLTimeStampPattern, $oObject->value, $aMatches ) ) {
                $iTimestamp = mktime( $aMatches[4], //h
                                        $aMatches[5], //m
                                        $aMatches[6], //s
                                        $aMatches[2], //M
                                        $aMatches[3], //d
                                        $aMatches[1]); //y
                if ( !$iTimestamp ) {
                    $iTimestamp = "0";
                }

                $oObject->setValue(trim( date( "Y-m-d H:i:s", $iTimestamp)));
                $oObject->fldmax_length = strlen( $oObject->value);
                $this->convertDBDateTime( $oObject, $blToTimeStamp );
                return $oObject->value;
            }
        }
    }

    /**
     * Bidirectional converter for date field
     *
     * @param object $oObject       oxField type object that keeps db field info
     * @param bool   $blToTimeStamp if true - converts value to database compatible timestamp value
     *
     * @return string
     */
    public function convertDBDate( $oObject, $blToTimeStamp = false )
    {
        return $this->convertDBDateTime( $oObject, $blToTimeStamp, true );
    }

    /**
     * Takes Array and creates IN() list for SQL statement
     *
     * @param array $aArray array of string to join
     *
     * @return string
     */
    public function createSQLList( $aArray )
    {
        $sRet = "";

        $blSep = false;
        foreach ( $aArray as $aToken) {
            if ( !$aToken[0]) {
                continue;
            }
            if ( $blSep) {
                $sRet .= ",";
            }
            $sRet .= "'".$aToken[0]."'";
            $blSep = true;
        }
        return $sRet;
    }

    /**
     * Start mysql transaction
     *
     * @return null
     */
    static public function startTransaction()
    {
        self::$_oDB->execute( 'START TRANSACTION' );
    }

    /**
     * Commit mysql transaction
     *
     * @return null
     */
    static public function commitTransaction()
    {
        self::$_oDB->execute( 'COMMIT' );
    }

    /**
     * RollBack mysql transaction
     *
     * @return null
     */
    static public function rollbackTransaction()
    {
        self::$_oDB->execute( 'ROLLBACK' );
    }

    /**
     * Set transaction isolation level
     * Allowed values READ UNCOMMITTED, READ COMMITTED, REPEATABLE READ, SERIALIZABLE
     *
     * @param string $sLevel level
     *
     * @return null
     */
    static public function setTransactionIsolationLevel( $sLevel = null )
    {
        $aLevels = array( 'READ UNCOMMITTED', 'READ COMMITTED', 'REPEATABLE READ', 'SERIALIZABLE' );

        if (in_array(strtoupper($sLevel), $aLevels)) {
            self::$_oDB->execute( 'SET TRANSACTION ISOLATION LEVEL ' . $sLevel );
        }
    }


    /**
     * Checks if given string is valid database field name.
     * It must contain from alphanumeric plus dot and underscore symbols
     *
     * @param string $sField field name
     *
     * @return bool
     */
    public function isValidFieldName( $sField )
    {
        return ( boolean ) getStr()->preg_match( "#^[\w\d\._]*$#", $sField );
    }

    /**
     * sets default formatted value
     *
     * @param object $oObject          date field object
     * @param string $sDate            prefered date
     * @param string $sLocalDateFormat input format
     * @param string $sLocalTimeFormat local format
     * @param bool   $blOnlyDate       marker to format only date field (no time)
     *
     * @return null
     */
    protected function _setDefaultFormatedValue( $oObject, $sDate, $sLocalDateFormat, $sLocalTimeFormat, $blOnlyDate )
    {
        $aDefTimePatterns = $this->_defaultTimePattern();
        $aDFormats  = $this->_defineDateFormattingRules();
        $aTFormats  = $this->_defineTimeFormattingRules();
        $oStr = getStr();

        foreach ( array_keys( $aDefTimePatterns ) as $sDefTimePattern ) {
            if ( $oStr->preg_match( $sDefTimePattern, $sDate ) ) {
                $blDefTimeFound = true;
                break;
            }
        }

        // setting and returning default formatted value
        if ( $blOnlyDate) {
            $oObject->setValue(trim( $aDFormats[$sLocalDateFormat][2] ));// . " " . @$aTFormats[$sLocalTimeFormat][2]);
            // increasing(decreasing) field lenght
            $oObject->fldmax_length = strlen( $oObject->value );
            return ;
        } elseif ( $blDefTimeFound ) {
            // setting value
            $oObject->setValue(trim( $aDFormats[$sLocalDateFormat][2] . " " . $aTFormats[$sLocalTimeFormat][2] ));
            // increasing(decreasing) field lenght
            $oObject->fldmax_length = strlen( $oObject->value );
            return ;
        }
    }

    /**
     * defines and checks dafault time values
     *
     * @param bool $blToTimeStamp -
     *
     * @return string
     */
    protected function _defineAndCheckDefaultTimeValues( $blToTimeStamp )
    {
        // defining time format
        // checking for default values
        $sLocalTimeFormat = $this->getConfig()->getConfigParam( 'sLocalTimeFormat' );
        if ( !$sLocalTimeFormat || $blToTimeStamp) {
            $sLocalTimeFormat = "ISO";
        }
        return $sLocalTimeFormat;
    }

    /**
     * defines and checks default date values
     *
     * @param bool $blToTimeStamp marker how to format
     *
     * @return string
     */
    protected function _defineAndCheckDefaultDateValues( $blToTimeStamp )
    {
        // defining time format
        // checking for default values
        $sLocalDateFormat = $this->getConfig()->getConfigParam( 'sLocalDateFormat' );
        if ( !$sLocalDateFormat || $blToTimeStamp) {
            $sLocalDateFormat = "ISO";
        }
        return $sLocalDateFormat;
    }

    /**
     * sets default date pattern
     *
     * @return array
     */
    protected function _defaultDatePattern()
    {
        // default date patterns
        $aDefDatePatterns = array("/^0000-00-00/"   => "ISO",
                                  "/^00\.00\.0000/" => "EUR",
                                  "/^00\/00\/0000/" => "USA"
                                 );
        return $aDefDatePatterns;
    }

    /**
     * sets default time pattern
     *
     * @return array
     */
    protected function _defaultTimePattern()
    {
        // default time patterns
        $aDefTimePatterns = array("/00:00:00$/"    => "ISO",
                                  "/00\.00\.00$/"  => "EUR",
                                  "/00:00:00 AM$/" => "USA"
                                 );
        return $aDefTimePatterns;
    }

    /**
     * regular expressions to validate date input
     *
     * @return array
     */
    protected function _regexp2ValidateDateInput()
    {
        // regexps to validate input
        $aDatePatterns = array("/^([0-9]{4})-([0-9]{2})-([0-9]{2})/"   => "ISO",
                               "/^([0-9]{2})\.([0-9]{2})\.([0-9]{4})/" => "EUR",
                               "/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/" => "USA"
                              );
        return $aDatePatterns;
    }

    /**
     * regular expressions to validate time input
     *
     * @return array
     */
    protected function _regexp2ValidateTimeInput()
    {
        // regexps to validate input
        $aTimePatterns = array("/([0-9]{2}):([0-9]{2}):([0-9]{2})$/"   => "ISO",
                               "/([0-9]{2})\.([0-9]{2})\.([0-9]{2})$/" => "EUR",
                               "/([0-9]{2}):([0-9]{2}):([0-9]{2}) ([AP]{1}[M]{1})$/" => "USA"
                              );
        return $aTimePatterns;
    }

    /**
     * define date formatting rules
     *
     * @return array
     */
    protected function _defineDateFormattingRules()
    {
        // date formatting rules
        $aDFormats  = array("ISO" => array("Y-m-d", array(2, 3, 1), "0000-00-00"),
                            "EUR" => array("d.m.Y", array(2, 1, 3), "00.00.0000"),
                            "USA" => array("m/d/Y", array(1, 2, 3), "00/00/0000")
                           );
        return $aDFormats;
    }

    /**
     * defines time formatting rules
     *
     * @return array
     */
    protected function _defineTimeFormattingRules()
    {
        // time formatting rules
        $aTFormats  = array("ISO" => array("H:i:s",   array(1, 2, 3 ), "00:00:00"),
                            "EUR" => array("H.i.s",   array(1, 2, 3 ), "00.00.00"),
                            "USA" => array("h:i:s A", array(1, 2, 3 ), "00:00:00 AM")
                           );
        return $aTFormats;
    }

    /**
     * Sets default date time value
     *
     * @param object $oObject          date field object
     * @param string $sLocalDateFormat input format
     * @param string $sLocalTimeFormat local format
     * @param bool   $blOnlyDate       marker to format only date field (no time)
     *
     * @return null
     */
    protected function _setDefaultDateTimeValue( $oObject, $sLocalDateFormat, $sLocalTimeFormat, $blOnlyDate )
    {
        $aDFormats  = $this->_defineDateFormattingRules();
        $aTFormats  = $this->_defineTimeFormattingRules();

        $sReturn = $aDFormats[$sLocalDateFormat][2];
        if ( !$blOnlyDate) {
            $sReturn .= " ".$aTFormats[$sLocalTimeFormat][2];
        }

        if ($oObject instanceof oxField) {
            $oObject->setValue(trim($sReturn));
        } else {
            $oObject->value = trim($sReturn);
        }
        // increasing(decreasing) field lenght
        $oObject->fldmax_length = strlen( $oObject->value);
    }

    /**
     * sets date
     *
     * @param object $oObject      date field object
     * @param string $sDateFormat  date format
     * @param array  $aDFields     days
     * @param array  $aDateMatches new date as array (month, year)
     *
     * @return null
     */
    protected function _setDate( $oObject, $sDateFormat, $aDFields, $aDateMatches )
    {
        // formatting correct time value
        $iTimestamp = mktime( 0, 0, 0, $aDateMatches[$aDFields[0]],
                              $aDateMatches[$aDFields[1]],
                              $aDateMatches[$aDFields[2]]);

        if ($oObject instanceof oxField) {
            $oObject->setValue(@date( $sDateFormat, $iTimestamp ));
        } else {
            $oObject->value = @date( $sDateFormat, $iTimestamp );
        }
        // we should increase (decrease) field lenght
        $oObject->fldmax_length = strlen( $oObject->value );
    }

    /**
     * Formatting correct time value
     *
     * @param object $oObject      data field object
     * @param string $sDateFormat  date format
     * @param string $sTimeFormat  time format
     * @param array  $aDateMatches new new date
     * @param array  $aTimeMatches new time
     * @param array  $aTFields     defines the time fields
     * @param array  $aDFields     defines the date fields
     *
     * @return null
     */
    protected function _formatCorrectTimeValue( $oObject, $sDateFormat, $sTimeFormat, $aDateMatches, $aTimeMatches, $aTFields, $aDFields )
    {
        // formatting correct time value
        $iTimestamp = @mktime( (int) $aTimeMatches[$aTFields[0]],
                               (int) $aTimeMatches[$aTFields[1]],
                               (int) $aTimeMatches[$aTFields[2]],
                               (int) $aDateMatches[$aDFields[0]],
                               (int) $aDateMatches[$aDFields[1]],
                               (int) $aDateMatches[$aDFields[2]] );

        if ($oObject instanceof oxField) {
            $oObject->setValue(trim( @date( $sDateFormat." ".$sTimeFormat, $iTimestamp ) ));
        } else {
            $oObject->value = trim( @date( $sDateFormat." ".$sTimeFormat, $iTimestamp ) );
        }

        // we should increase (decrease) field lenght
        $oObject->fldmax_length = strlen( $oObject->value );
    }

    /**
     * Get connection ID
     *
     * @return link identifier
     */
    protected function _getConnectionId()
    {
        if ( self::$_oDB !== null ) {
           return self::$_oDB->connectionId;
        }

        return null;
    }

    /**
     * Escape string for using in mysql statements
     *
     * @param string $sString string which will be escaped
     *
     * @return string
     */
    public function escapeString( $sString )
    {
        return mysql_real_escape_string( $sString, $this->_getConnectionId() );
    }

    /**
     * Updates shop views
     *
     * @param array $aTables If you need to update specific tables, just pass its names as array [optional]
     *
     * @return null
     */
    public function updateViews( $aTables = null )
    {
        $myConfig  = $this->getConfig();

        $oShopList = oxNew("oxshoplist" );
        $oShopList->selectString( "select * from oxshops"); // Shop view may not exist at this point

        $aTables = $aTables ? $aTables : $myConfig->getConfigParam( 'aMultiShopTables' );
        foreach ( $oShopList as $key => $oShop ) {
            $oShop->setMultiShopTables( $aTables );
            $blMultishopInherit = $myConfig->getShopConfVar( 'blMultishopInherit_oxcategories', $oShop->sOXID );
            $aMallInherit = array();
            foreach ( $aTables as $sTable ) {
                $aMallInherit[$sTable] = $myConfig->getShopConfVar( 'blMallInherit_' . $sTable, $oShop->sOXID );
            }
            $oShop->generateViews( $blMultishopInherit, $aMallInherit );
        }
    }
}
