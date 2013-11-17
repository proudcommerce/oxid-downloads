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
 * @version   SVN: $Id: oxerptype.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * ERP base description class
 */
class oxERPType
{
    /**
     * Error message
     * @var string
     */
    public static $ERROR_WRONG_SHOPID = "Wrong shop id, operation not allowed!";

    /**
     * Table name
     * @var string
     */
    protected $_sTableName        = null;

    /**
     * Function suffix
     * @var string
     */
    protected $_sFunctionSuffix   = null;

    /**
     * Field list
     * @var array
     */
    protected $_aFieldList        = null;

    /**
     * Kaye field list
     * @var array
     */
    protected $_aKeyFieldList     = null;

    /**
     * Shop object name
     * @var string
     */
    protected $_sShopObjectName   = null;

    /**
     * If true a export will be restricted vias th oxshopid column of the table
     *
     * @var unknown_type
     */
    protected $_blRestrictedByShopId = false;

    /**
     * versioning support for db layers
     *
     * @var array
     */
    protected $_aFieldListVersions = null;

    /**
     * getter for _sFunctionSuffix
     *
     * @return string
     */
    public function getFunctionSuffix()
    {
        return $this->_sFunctionSuffix;
    }

    /**
     * getter for _sShopObjectName
     *
     * @return string
     */
    public function getShopObjectName()
    {
        return $this->_sShopObjectName;
    }

    /**
     * getter for _sTableName
     *
     * @return string
     */
    public function getBaseTableName()
    {
        return $this->_sTableName;
    }

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {
        $this->_sFunctionSuffix = str_replace( "oxERPType_", "", get_class( $this));
        if (isset($this->_aFieldListVersions)) {
            $this->_aFieldList = $this->_aFieldListVersions[oxERPBase::getUsedDbFieldsVersion()];
        }
    }

    /**
     * setter for the function prefix
     *
     * @param string $sNew new function suffix
     *
     * @return null
     */
    public function setFunctionSuffix( $sNew )
    {
        $this->_sFunctionSuffix = $sNew;
    }

    /**
     * Setter for field list
     *
     * @param array $aFieldList field list to store at oxErpType::_aFieldList
     *
     * @return null
     */
    public function setFieldList($aFieldList)
    {
        $this->_aFieldList = $aFieldList;
    }

    /**
     * Returns table or Viewname
     *
     * @param int $iShopID table shop id
     *
     * @return string
     */
    public function getTableName( $iShopID = 1 )
    {
        return getViewName( $this->_sTableName, $iShopID );
    }

    /**
     * Creates Array with [iLanguage][sFieldName]
     *
     * @return array
     */
    private function _getMultilangualFields()
    {
        $aRet = array();

        $aData = oxDb::getInstance()->getTableDescription( $this->_sTableName);

        foreach ( $aData as $key => $oADODBField) {
            $iLang = substr( $oADODBField->name, strlen( $oADODBField->name) - 1, 1);
            if ( is_numeric( $iLang) &&  substr( $oADODBField->name, strlen( $oADODBField->name) - 2, 1) == '_') {
                // multilangual field
                $sMainFld = str_replace( '_'.$iLang, "", $oADODBField->name);
                $aRet[$iLang][$sMainFld] = $oADODBField->name.' as '.$sMainFld;
            }
        }

        return $aRet;
    }

    /**
     * return sql column name of given table column
     *
     * @param string $sField    object field anme
     * @param int    $iLanguage language id
     * @param int    $iShopID   shop id
     *
     * @return string
     */
    protected function getSqlFieldName($sField, $iLanguage = 0, $iShopID = 1)
    {
        if ( $iLanguage ) {
            $aMultiLang = $this->_getMultilangualFields();
            // we need to load different fields
            if ( isset( $aMultiLang[$iLanguage][$sField] ) ) {
                $sField = $aMultiLang[$iLanguage][$sField];
            }
        }
        return $sField;
    }

    /**
     * return sql column name of given table column
     *
     * @param string $sWhere    where condition
     * @param int    $iLanguage language id
     * @param int    $iShopID   shop id
     *
     * @return string
     */
    public function getSQL( $sWhere, $iLanguage = 0, $iShopID = 1)
    {
        if ( !$this->_aFieldList ) {
            return;
        }

        $sSQL    = 'select ';
        $blSep = false;

        foreach ( $this->_aFieldList as $sField) {
            if ( $blSep ) {
                $sSQL .= ',';
            }

            $sSQL .= $this->getSqlFieldName($sField, $iLanguage, $iShopID);
            $blSep = true;
        }

        if ( $this->_blRestrictedByShopId ) {
            $oStr = getStr();
            if ( $oStr->strstr( $sWhere, 'where')) {
                $sWhere .= ' and ';
            } else {
                $sWhere .= ' where ';
            }

            $sWhere .= 'oxshopid = \''.$iShopID.'\'';
        }

        $sSQL .= ' from '.$this->getTableName($iShopID).' '.$sWhere;

        return $sSQL;
    }

    /**
     * returns the "order by " string for  a sql query
     *
     * @param string $sFieldName order by that field
     * @param string $sType      allowed values ASC and DESC
     *
     * @return string
     */
    public function getSortString($sFieldName = null, $sType = null)
    {
        $sRes = " order by ";
        if ($sFieldName) {
            $sRes .= $sFieldName;
        } else {
            $sRes .= "oxid";
        }
        if ($sType && ($sType == "ASC" || $sType == "DESC")) {
            $sRes .= " ". $sType;
        }
        return $sRes;
    }

    /**
     * Basic access check for writing data, checks for same shopid, should be overridden if field oxshopid does not exist
     *
     * @param string $sOxid the oxid of the object
     *
     * @throws Exception exceltion is thrown when user has no write access
     *
     * @return null
     */
    public function checkWriteAccess($sOxid)
    {
        $oObj = oxNew("oxbase");
        $oObj->init($this->_sTableName);
        if ( $oObj->load( $sOxid ) ) {
            $sFld = $this->_sTableName.'__oxshopid';
            if ( isset( $oObj->$sFld ) ) {
                $sRes = $oObj->$sFld->value;
                if ( $sRes && $sRes != oxConfig::getInstance()->getShopId() ) {
                    throw new Exception( oxERPBase::$ERROR_USER_NO_RIGHTS);
                }
            }
        }
    }

    /**
     * checks done to make sure deletion is possible and allowed
     *
     * @param string $sId id of object
     *
     * @throws Exception exception is thrown when deletion is not possible
     *
     * @return object of given type
     */
    public function getObjectForDeletion( $sId)
    {
        $myConfig = oxConfig::getInstance();

        if ( !isset( $sId ) ) {
            throw new Exception( "Missing ID!");
        }

        $oObj = oxNew( $this->getShopObjectName(), "core");

        if ( !$oObj->exists( $sId ) ) {
            throw new Exception( $this->getShopObjectName(). " " . $sId. " does not exists!");
        }

        //We must load the object here, to check shopid and return it for further checks
        if ( !$oObj->load( $sId ) ) {
            //its possible that access is restricted allready
            throw new Exception( "No right to delete object {$sId} !");
        }

        if ( !$this->_isAllowedToEdit($oObj->getShopId() ) ) {
            throw new Exception( "No right to delete object {$sId} !");
        }

        return $oObj;
    }

    /**
     * Checks if user rights alllows to edit. Returns TRUE if rights allow
     *
     * @param int $iShopId shop id
     *
     * @return bool
     */
    protected function _isAllowedToEdit($iShopId)
    {
        if ($oUsr = oxUser::getAdminUser()) {
            if ($oUsr->oxuser__oxrights->value == "malladmin") {
                return true;
            } elseif ($oUsr->oxuser__oxrights->value == (int) $iShopId) {
                return true;
            }
        }
        return false;
    }

    /**
     * direct sql check if it is allowed to delete the OXID of the current table
     *
     * @param string $sId entry id
     *
     * @return null
     */
    protected function _directSqlCheckForDeletion($sId)
    {
        $oDb =oxDb::getDb();
        $sSql = "select oxshopid from ".$this->_sTableName." where oxid = " .$oDb->quote( $sId );
        try {
            $iShopId = $oDb->getOne($sSql);
        } catch (Exception $e) {
            // no shopid was found
            return;
        }
        if ( !$this->_isAllowedToEdit( $iShopId ) ) {
            throw new Exception( "No right to delete object {$sId} !");
        }
    }

    /**
     * default check if it is allowed to delete the OXID of the current table
     *
     * @param string $sId object id
     *
     * @return null
     */
    public function checkForDeletion($sId)
    {

        if ( !isset( $sId ) ) {
            throw new Exception( "Missing ID!");
        }
        // malladmin can do it
        if ($oUsr = oxUser::getAdminUser()) {
            if ($oUsr->oxuser__oxrights->value == "malladmin") {
                return;
            }
        }
        try {
            $this->getObjectForDeletion($sId);
        } catch (oxSystemComponentException $e) {
            if ($e->getMessage() == 'EXCEPTION_SYSTEMCOMPONENT_CLASSNOTFOUND') {
                $this->_directSqlCheckForDeletion($sId);
            } else {
                throw $e;
            }
        }
    }

    /**
     * default deletion of the given OXID in the current table
     *
     * @param string $sID deletable object id
     *
     * @return bool
     */
    public function delete($sID)
    {
        $oDb = oxDb::getDb();
        $sSql = "delete from ".$this->_sTableName." where oxid = " . $oDb->quote( $sID );

        return $oDb->Execute($sSql);
    }

    /**
     * default delete call to the given object
     *
     * @param object $oObj object to delete
     * @param string $sID  object id
     *
     * @return bool
     */
    public function deleteObject($oObj, $sID)
    {

        return $oObj->delete($sID);
    }

    /**
     * We have the possibility to add some data
     *
     * @param array $aFields export fields
     *
     * @return array
     */
    public function addExportData( $aFields)
    {
        return $aFields;
    }

    /**
     * allows to modify data before import
     *
     * @param array $aFields import fields
     *
     * @deprecated
     * @see _preAssignObject
     *
     * @return array
     */
    public function addImportData($aFields)
    {
        return $aFields;
    }

    /**
     * used for the RR implementation, right now not really used
     *
     * @return array
     */
    public function getRightFields()
    {
        $aRParams = array();

        foreach ( $this->_aFieldList as $sField ) {
            $aRParams[] = strtolower($this->_sTableName.'__'.$sField);
        }
        return $aRParams;
    }

    /**
     * returns the predefined field list
     *
     * @return array
     */
    public function getFieldList()
    {
        return $this->_aFieldList;
    }

    /**
     * returns the keylist array
     *
     * @return array
     */
    public function getKeyFields()
    {
        return $this->_aKeyFieldList;
    }

    /**
     * returns try if type has key fields array
     *
     * @return bool
     */
    public function hasKeyFields()
    {
        if ( isset( $this->_aKeyFieldList ) && is_array( $this->_aKeyFieldList ) ) {
            return true;
        }
        return false;
    }

    /**
     * issued before saving an object. can modify aData for saving
     *
     * @param oxBase $oShopObject         shop object
     * @param array  $aData               data used in assign
     * @param bool   $blAllowCustomShopId if TRUE - custom shop id is allowed
     *
     * @return array
     */
    protected function _preAssignObject($oShopObject, $aData, $blAllowCustomShopId)
    {
        if ( !isset( $aData['OXID'] ) ) {
            throw new Exception( "OXID missing, seems to be wrong Format!");
        }
        if ( !$oShopObject->exists( $aData['OXID'] ) ) {
            //$aData['OXSHOPID'] = $this->_iShopID;
            if ( !$blAllowCustomShopId ) {
                if (isset($aData['OXSHOPID'])) {
                    $aData['OXSHOPID'] = oxConfig::getInstance()->getShopId();
                }
            }
            if ( !array_key_exists('OXSHOPINCL', $aData ) ) {
                $aData['OXSHOPINCL'] = oxUtils::getInstance()->getShopBit($aData['OXSHOPID']);
            }
            if ( !array_key_exists( 'OXSHOPEXCL', $aData ) ) {
                $aData['OXSHOPEXCL'] = 0;
            }
        }
        if (isset($aData['OXACTIV'])) {
            $aData['OXACTIVE'] = $aData['OXACTIV'];
        }
        if (isset($aData['OXACTIVFROM'])) {
            $aData['OXACTIVEFROM'] = $aData['OXACTIVFROM'];
        }
        if (isset($aData['OXACTIVTO'])) {
            $aData['OXACTIVETO'] = $aData['OXACTIVTO'];
        }
        for ($i=1;$i<4;$i++) {
            if (isset($aData['OXACTIV_'.$i])) {
                $aData['OXACTIVE_'.$i] = $aData['OXACTIV_'.$i];
            }
        }
        // null values support
        foreach ($aData as $key => $val) {
            if ( !strlen( (string) $val ) ) {
                // oxbase whill quote it as string if db does not support null for this field
                $aData[$key] = null;
            }
        }
        return $aData;
    }

    /**
     * prepares object for saving in shop
     * returns true if save can proceed further
     *
     * @param object $oShopObject shop object
     * @param array  $aData       object data
     *
     * @return boolean
     */
    protected function _preSaveObject($oShopObject, $aData)
    {
        return true;
    }

    /**
     * saves data by calling object saving
     *
     * @param array $aData               object data to save
     * @param bool  $blAllowCustomShopId if TRUE - custom shop id allowed
     *
     * @return string | false
     */
    public function saveObject($aData, $blAllowCustomShopId)
    {
        $sObjectName = $this->getShopObjectName();
        if ( $sObjectName ) {
            $oShopObject = oxNew( $sObjectName, 'core');
            if ( $oShopObject instanceof oxI18n ) {
                $oShopObject->setLanguage(0);
                $oShopObject->setEnableMultilang(false);
            }
        } else {
            $oShopObject = oxNew( 'oxbase', 'core');
            $oShopObject->init($this->getBaseTableName());
        }

        $aData = $this->_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);


        $oShopObject->load( $aData['OXID']);

        $oShopObject->assign( $aData );

        if ($blAllowCustomShopId) {
            $oShopObject->setIsDerived(false);
        }

        if ($this->_preSaveObject($oShopObject, $aData)) {
            // store
            if ( $oShopObject->save() ) {
                return $this->_postSaveObject($oShopObject, $aData);
            }
        }

        return false;
    }

    /**
     * post saving hook. can finish transactions if needed or ajust related data
     *
     * @param oxBase $oShopObject shop object
     * @param data   $aData       post save data
     *
     * @return mixed data to return
     */
    protected function _postSaveObject($oShopObject, $aData)
    {
        // returning ID on success
        return $oShopObject->getId();
    }
}

