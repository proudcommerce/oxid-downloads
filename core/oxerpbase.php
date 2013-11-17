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
 * $Id: oxerpbase.php 18054 2009-04-09 16:47:26Z arvydas $
 */

require_once 'oxerpcompatability.php';

/**
 * oxERPBase
 *
 * @package core
 * @author Lars Jankowfsky
 * @copyright Copyright (c) 2006
 * @version $Id: oxerpbase.php 18054 2009-04-09 16:47:26Z arvydas $
 * @access public
 **/
abstract class oxERPBase
{
    static $ERROR_USER_WRONG = "ERROR: Could not login";
    static $ERROR_USER_NO_RIGHTS =  "Not sufficient rights to perform operation!";
    static $ERROR_USER_EXISTS = "ERROR: User already exists";
    static $ERROR_NO_INIT = "Init not executed, Access denied!";
    static $ERROR_DELETE_NO_EMPTY_CATEGORY = "Only empty category can be deleated";
    static $ERROR_OBJECT_NOT_EXISTING = "Object does not exist";

    static $MODE_IMPORT = "Import";
    static $MODE_DELETE = "Delete";

    /**
     * Init marker
     *
     * @var bool
     */
    protected $_blInit = false;

    /**
     * Language id
     *
     * @var int
     */
    protected $_iLanguage = null;

    /**
     * User id
     *
     * @var string
     */
    protected $_sUserID = null;

    /**
     * session id
     *
     * @var string
     */
    protected $_sSID = null;

    /**
     * Requested version
     *
     * @var string
     */
    protected static $_sRequestedVersion = '';

    /**
     * describes what db layer versions are implemented and usable with shop db version
     * 1st entry is default version (if none requested)
     *
     * @var array
     */
    protected static $_aDbLayer2ShopDbVersions = array(
        '1' => '1', '1.1' => '1', '2' => '2',
    );

    /**
     * Statistics data container
     *
     * @var array
     */
    public $_aStatistics = array();

    /**
     * Index marker
     *
     * @var int
     */
    public $_iIdx = 0;

    /**
     * Statistics getter
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->_aStatistics;
    }

    /**
     * Sesstion id getter
     *
     * @return string
     */
    public function getSessionID()
    {
        return $this->_sSID;
    }

    /**
     * Method which must be called before export
     *
     * @param string $sType export type
     *
     * @return mixed
     */
    protected abstract function _beforeExport($sType);

    /**
     * Method which must be called after export
     *
     * @param string $sType export object type
     *
     * @return mixed
     */
    protected abstract function _afterExport($sType);

    /**
     * Method which must be called before import
     *
     * @return mixed
     */
    protected abstract function _beforeImport();

    /**
     * Method which must be called after import
     *
     * @return mixed
     */
    protected abstract function _afterImport();

    /**
     * Import data getter
     *
     * @return mixed
     */
    public abstract function getImportData();

    /**
     * Import type getter
     *
     * @param array &$aData import data
     *
     * @return mixed
     */
    protected abstract function _getImportType( & $aData );

    /**
     * Import mode getter
     *
     * @param array $aData import data
     *
     * @return mixed
     */
    protected abstract function _getImportMode( $aData );

    /**
     * Abstract data modifier
     *
     * @param array  $aData data to modify
     * @param object $oType type object
     *
     * @return mixed
     */
    protected abstract function _modifyData( $aData, $oType );

    /**
     * default fallback if some handler is missing
     *
     * @param string $sMethod    method to call
     * @param array  $aArguments method arguments
     *
     * @return null
     */
    public function __call( $sMethod, $aArguments )
    {
        throw new Exception( "ERROR: Handler for Object '$sMethod' not implemented!");
    }


    // -------------------------------------------------------------------------
    //
    // public interface
    //
    // -------------------------------------------------------------------------


    /**
     * oxERPBase::Init()
     * Init ERP Framework
     * Creates Objects, checks Rights etc.
     *
     * @param mixed   $sUserName user login name
     * @param mixed   $sPassword user password
     * @param integer $iShopID   shop id
     * @param integer $iLanguage language id
     *
     * @return boolean
     **/
    public function init( $sUserName, $sPassword, $iShopID = 1, $iLanguage = 0)
    {
        $_COOKIE = array('admin_sid' => false);
        $myConfig = oxConfig::getInstance();
        $myConfig->setConfigParam( 'blAdmin', 1 );
        $myConfig->setAdminMode( true );

        $mySession = oxSession::getInstance();
        $myConfig->oActView = new FakeView();

        // hotfix #2429, #2430 MAFI
        if ($iShopID != 1) {
            $myConfig->setConfigParam('blMallUsers', false);
        }
        $myConfig->setShopId($iShopID);

        $mySession->setVar( "lang", $iLanguage);
        $mySession->setVar( "language", $iLanguage);

        $oUser = oxNew('oxuser');
        try {
            if ( !$oUser->login( $sUserName, $sPassword ) ) {
                $oUser = null;
            }
        } catch( oxUserException $e ) {
            $oUser = null;
        }

        if ( !$oUser || ( isset( $oUser->iError ) && $oUser->iError == -1000 ) ) {
            // authorization error
            throw new Exception( self::$ERROR_USER_WRONG );
        } elseif ( ( $oUser->oxuser__oxrights->value == "malladmin" || $oUser->oxuser__oxrights->value == $myConfig->getShopId() ) ) {
            $this->_sSID        = $mySession->getId();
            $this->_blInit      = true;
            $this->_iLanguage   = $iLanguage;
            $this->_sUserID     = $oUser->getId();
            //$mySession->freeze();
        } else {

            //user does not have sufficient rights for shop
            throw new Exception( self::$ERROR_USER_NO_RIGHTS );
        }

        $this->_resetIdx();

        return $this->_blInit;
    }

    /**
     * oxERPBase::loadSessionData()
     *
     * different handeling for SOAP request and CSV usage (V0.1)
     *
     * @param string $sSessionID session id
     *
     * @return null
     */
    public abstract function loadSessionData( $sSessionID );

    /**
     * Export one object type
     *
     * @param string $sType          object type
     * @param string $sWhere         where condition
     * @param int    $iStart         start fetch records from
     * @param int    $iCount         number of records to export
     * @param string $sSortFieldName sorting field name
     * @param string $sSortType      sorting direction
     *
     * @return null
     */
    public function exportType( $sType, $sWhere = null,$iStart = null, $iCount = null, $sSortFieldName = null, $sSortType = null)
    {
        $this->_beforeExport($sType);
        $this->_export( $sType, $sWhere, $iStart, $iCount, $sSortFieldName, $sSortType);
        $this->_afterExport($sType);
    }

    /**
     * imports all data set up before
     *
     * @return null
     */
    public function import()
    {
        $this->_beforeImport();
        while ( $this->_importOne() ) {
        }
        $this->_afterImport();
    }

    /**
     * Factory for ERP types
     *
     * @param string $sType instance type
     *
     * @return object
     */
    protected function _getInstanceOfType( $sType )
    {
        $sClassName = 'oxerptype_'.$sType;
        $sFullPath  = dirname(__FILE__).'/objects/'.$sClassName.'.php';

        if ( !file_exists( $sFullPath ) ) {
            throw new Exception( "Type $sType not supported in ERP interface!");
        }

        require_once $sFullPath;
        return oxNew ($sClassName);
    }

    /**
     * Exports one type
     *
     * @param string $sType          object type
     * @param string $sWhere         where condition
     * @param int    $iStart         start fetch records from
     * @param int    $iCount         number of records to export
     * @param string $sSortFieldName sorting field name
     * @param string $sSortType      sorting direction
     *
     * @return null
     */
    protected function _export( $sType, $sWhere, $iStart = null, $iCount = null, $sSortFieldName = null, $sSortType = null)
    {
        global $ADODB_FETCH_MODE;

        $myConfig = oxConfig::getInstance();
        // prepare
        $oType = $this->_getInstanceOfType( $sType);
        $sSQL  = $oType->getSQL( $sWhere, $this->_iLanguage, $myConfig->getShopId() );
        $sSQL .= $oType->getSortString( $sSortFieldName, $sSortType );
        $sFnc  = '_Export'.$oType->getFunctionSuffix();

        $save = $ADODB_FETCH_MODE;

        if ( isset( $iCount ) || isset( $iStart ) ) {
            $rs = oxDb::getDb(true)->selectLimit( $sSQL, $iCount, $iStart );
        } else {
            $rs = oxDb::getDb(true)->execute( $sSQL );
        }

        if ( $rs != false && $rs->recordCount() > 0 ) {
            while (!$rs->EOF) {
                $blExport = false;
                $sMessage = '';

                $rs->fields = $oType->addExportData( $rs->fields);

                // check rights
                $this->_checkAccess( $oType, false);

                // export now
                try{
                    $blExport = $this->$sFnc( $rs->fields );
                } catch (Exception $e) {
                    $sMessage = $e->getMessage();

                }

                $this->_aStatistics[$this->_iIdx] = array('r'=>$blExport,'m'=>$sMessage);
                //#2428 MAFI
                $this->_nextIdx();

                $rs->moveNext();
            }
        }
        $ADODB_FETCH_MODE = $save;
    }

    /**
     * Just used for developing
     *
     * @param array $sTable name of table to output its mapping
     *
     * @return null
     */
    protected function _outputMappingArray( $sTable)
    {
        $aData = getTableDescription( $sTable );

        $iIdx = 0;
        foreach ( $aData as $key => $oADODBField) {
            if ( !( is_numeric( substr( $oADODBField->name, strlen( $oADODBField->name) - 1, 1)) &&  substr( $oADODBField->name, strlen( $oADODBField->name) - 2, 1) == '_' ) ) {
                echo( "'".$oADODBField->name."'\t\t => '".$oADODBField->name."',\n" );
                $iIdx++;
            }
        }
    }

    /**
     * Returns record id
     *
     * @param object $oType type object
     * @param object $aData field data
     *
     * @return string
     */
    protected function _getKeyID( $oType, $aData )
    {
        $myConfig = oxConfig::getInstance();
        $aKeyFields = $oType->getKeyFields();

        if ( !is_array($aKeyFields ) ) {
            return false;
        }

        $oDB = oxDb::getDb();
        //$aKeys = array_intersect_key($aData,$aKeyFields);

        $aWhere = array();
        $blAllKeys = true;
        foreach ( $aKeyFields as $sKey ) {
            if ( array_key_exists( $sKey, $aData ) ) {
                $aWhere[] = $sKey.'='.$oDB->qstr( $aData[$sKey] );
            } else {
                $blAllKeys = false;
            }
        }

        if ( $blAllKeys ) {
            $sSelect = 'SELECT OXID FROM '.$oType->getTableName().' WHERE '.implode(' AND ', $aWhere );
            $sOXID = $oDB->getOne($sSelect);

            if ( isset( $sOXID ) ) {
                return $sOXID;
            }
        }

        return oxUtilsObject::getInstance()->generateUID();
    }

    /**
     * Reset import counter, if retry is detected, only failed imports are repeated
     *
     * @return null
     */
    protected function _resetIdx()
    {
        $this->_iIdx = 0;
        if ( count( $this->_aStatistics ) && isset( $this->_aStatistics[$this->_iIdx] ) ) {
            while ( isset( $this->_aStatistics[$this->_iIdx]) && $this->_aStatistics[$this->_iIdx]['r'] ) {
                $this->_iIdx ++;
            }
        }
    }

    /**
     * Increase import counter, if retry is detected, only failed imports are repeated
     *
     * @return null
     */
    protected function _nextIdx()
    {
        $this->_iIdx ++;
        if ( count( $this->_aStatistics ) && isset( $this->_aStatistics[$this->_iIdx] ) ) {
            while ( isset( $this->_aStatistics[$this->_iIdx]) && $this->_aStatistics[$this->_iIdx]['r'] ) {
                $this->_iIdx ++;
            }
        }
    }

    /**
     * Checks if user as sufficient rights
     *
     * @param object  $oType   type object
     * @param boolean $blWrite access mode
     * @param integer $sOxid   object id
     *
     * @return null
     */
    protected function _checkAccess( $oType, $blWrite, $sOxid = null )
    {
        $myConfig = oxConfig::getInstance();
        static $aAccessCache;

        if ( !$this->_blInit ) {
            throw new Exception(self::$ERROR_NO_INIT);
        }

        if ( $blWrite ) {
            //check against Shop id if it exists
            $oType->checkWriteAccess( $sOxid );
        }

        // TODO
        // add R&R check for access
        if ( $myConfig->blUseRightsRoles ) {
            static $aAccessCache;

            $sAccessMode = ( (boolean) $blWrite ) ? '2' : '1';
            $sTypeClass  = get_class($oType);

            if ( !isset( $aAccessCache[$sTypeClass][$sAccessMode] ) ) {

                $oDB = oxDb::getDb();

                //create list of user/group id's
                $aIDs = array( $oDB->qstr($this->_sUserID) );
                $sQUserGroups = 'SELECT oxgroupsid ' .
                                'FROM oxobject2group '.
                                //"WHERE oxshopid = '{$this->_iShopID}' ".
                                "WHERE oxshopid = '{$myConfig->getShopId()}' ".
                                "AND oxobjectid ='{$this->_sUserID}'";

                $rs = $oDB->execute( $sQUserGroups);
                if ($rs != false && $rs->recordCount() > 0) {
                    while (!$rs->EOF) {
                        $aIDs[] = $oDB->qstr($rs->fields[0]);
                        $rs->moveNext();
                    }
                }

                $aRParams = $oType->getRightFields();
                foreach ($aRParams as $sKey => $sParam) {
                    $aRParams[$sKey] = $oDB->qstr($sParam);
                }

                //check user rights...
                $sSelect = 'SELECT count(*) '.
                           'FROM oxfield2role as rr , oxrolefields as rf, oxobject2role as ro, oxroles as rt '.
                           "WHERE rr.OXIDX < {$sAccessMode} ".
                           'AND rr.oxroleid = ro.oxroleid  '.
                           'AND rt.oxid = ro.oxroleid '.
                           'AND rt.oxactive = 1 '.
                           //"AND rt.oxshopid = '{$this->_iShopID}'".
                           "AND rt.oxshopid = '{$myConfig->getShopId()}'".
                           'AND rf.oxparam IN ('.implode(',', $aRParams).') '.
                           'AND ro.oxobjectid IN ('.implode(',', $aIDs).') '.
                           'AND rr.oxfieldid=rf.oxid';

                $iNoAccess = $oDB->getOne($sSelect);
                $aAccessCache[$sTypeClass][$sAccessMode] = $iNoAccess;
            } else {
                $iNoAccess = $aAccessCache[$sTypeClass][$sAccessMode];
            }

            if ( $iNoAccess ) {
                throw new Exception( self::$ERROR_USER_NO_RIGHTS );
            }
        }
    }

    /**
     * Main Import Handler, imports one row/call/object...
     *
     * @return boolean
     */
    protected function _importOne()
    {
        $blRet = false;

        // import one row/call/object...
        $aData = $this->getImportData();

        if ( $aData ) {
            $blRet = true;
            $blImport = false;
            $sMessage = '';

            $sType  = $this->_getImportType( $aData);
            $sMode = $this->_getImportMode($aData);
            $oType  = $this->_getInstanceOfType( $sType);
            $aData = $this->_modifyData($aData, $oType);

            // import now
            $sFnc   = '_' . $sMode . $oType->getFunctionSuffix();

            if ( $sMode == oxERPBase::$MODE_IMPORT ) {
                $aData = $oType->addImportData( $aData );
            }

            try{
                $blImport = $this->$sFnc( $oType, $aData);
                $sMessage = '';
            }
            catch (Exception $e) {
                $sMessage = $e->getMessage();
            }

            $this->_aStatistics[$this->_iIdx] = array('r'=>$blImport,'m'=>$sMessage);

        }
        //hotfix #2428 MAFI
        $this->_nextIdx();

        return $blRet;
    }


    /**
     * Insert or Update a Row into database
     *
     * @param oxERPType $oType               object to save
     * @param array     $aData               assoc. array with fieldnames, values what should be stored in this table
     * @param bool      $blAllowCustomShopId if TRUE custom shop id allowed
     *
     * @return string | false
     */
    protected function _save( oxERPType $oType, $aData, $blAllowCustomShopId = false)
    {
        $myConfig = oxConfig::getInstance();

        // check rights
        $this->_checkAccess( $oType, true, $aData['OXID'] );

        if ( $oType->hasKeyFields() && !isset($aData['OXID'] ) ) {
            $sOXID = $this->_getKeyID($oType, $aData);
            if ( $sOXID ) {
                $aData['OXID'] = $sOXID;
            } else {
                $aData['OXID'] = oxUtilsObject::getInstance()->generateUID();
            }
        }

        return $oType->saveObject($aData, $blAllowCustomShopId);
    }

    /**
     * gets requested db layer version
     *
     * @return string
     */
    public static function getRequestedVersion()
    {
        if (!self::$_sRequestedVersion && isset($_GET['version'])) {
            self::$_sRequestedVersion = $_GET['version'];
        }
        if (!isset(self::$_aDbLayer2ShopDbVersions[self::$_sRequestedVersion])) {
            self::$_sRequestedVersion = '';
        }
        if (!self::$_sRequestedVersion) {
            reset(self::$_aDbLayer2ShopDbVersions);
            self::$_sRequestedVersion = key(self::$_aDbLayer2ShopDbVersions);
        }
        return self::$_sRequestedVersion;
    }

    /**
     * gets requested version for db fields used
     *
     * @return string
     */
    public static function getUsedDbFieldsVersion()
    {
        return self::$_aDbLayer2ShopDbVersions[self::getRequestedVersion()];
    }

    /**
     * gets requested db layer version
     *
     * @param string $sDbLayerVersion database layer version
     *
     * @return null
     */
    public static function setVersion( $sDbLayerVersion = '' )
    {
        self::$_sRequestedVersion = $sDbLayerVersion;
    }
}


// the following statements and class is just for pretending some error messages in oxconfig
if ( !class_exists( 'FakeView' ) ) {
    /**
     * Fake view object to manipulate with view on some special cases
     */
    class FakeView
    {
        /**
         * Original oxview addGlobalParams wrapper
         *
         * @return null
         */
        public function addGlobalParams()
        {
        }
    }
}


