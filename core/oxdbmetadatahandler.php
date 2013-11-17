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
 * $Id: oxdbmetadatahandler.php 23405 2009-10-20 15:29:03Z rimvydas.paskevicius $
 */

/**
 * Class handling CAPTCHA image
 * This class requires utility file utils/verificationimg.php as image generator
 *
 */
class oxDbMetaDataHandler extends oxSuperCfg
{
    /**
     *
     * @var unknown_type
     */
    protected $_aDbTablesFields = null;

    /**
     *
     * @var unknown_type
     */
    protected $_aTables = null;

    /**
     *
     * @var unknown_type
     */
    protected $_iCurrentMaxLangId;

    /**
     *
     * @var array Tables which shloud be skipped from reseting
     */
    protected $_aSkipTablesOnReset = array( "oxcountry" );

    /**
     *
     * @var unknown_type
     */
    //protected $_aTablesFieldsSimpleMode = null;

    /**
     *  Get table fields
     *
     *  @param string $sTableName table name
     *
     *  @return array
     */
    public function getFields( $sTableName )
    {

        if ( empty($this->_aDbTablesFields[$sTableName]) ) {
            $oaFields = oxDb::getInstance()->getTableDescription( $sTableName );

            $this->_aDbTablesFields[$sTableName] = array();

            foreach( $oaFields as $oField ) {
                $this->_aDbTablesFields[$sTableName][] = $oField->name;
            }
        }

        return $this->_aDbTablesFields[$sTableName];
    }

    /**
     * Check if field exists in table
     *
     * @param string $sFieldName field name
     * @param string $sTableName table name
     *
     * @return bool
     */
    public function fieldExists( $sFieldName, $sTableName )
    {
        $aTableFields = $this->getFields( $sTableName );

        if ( is_array($aTableFields) ) {
            $sFieldName = strtoupper( $sFieldName );
            if ( in_array( $sFieldName, $aTableFields ) ) {
                return true;
            }
        }

        return false;
    }


    /**
     * Get all tables names from db. Views tables are not incuded in
     * this list.
     *
     * @return array
     */
    public function getAllTables()
    {
        if ( empty($this->_aTables) ) {

            $aTables = oxDb::getDb()->getAll("show tables");

            foreach ( $aTables as $aTableInfo) {
                $sTableName = $aTableInfo[0];

                $this->_aTables[] = $aTableInfo[0];
            }
        }

        return $this->_aTables;
    }

    /**
     * Get sql string for dublicating multilang field
     *
     * @param string $sOldFieldName     old field name, which will be copied
     * @param string $sNewFieldName     new field name
     * @param string $sTableName        table name in which new field will be added
     * @param string $sInsertAfterField insert after field name
     *
     * @return string
     *
     */
    protected function _getDublicatedFieldSql( $sOldFieldName, $sNewFieldName, $sTableName, $sInsertAfterField = null )
    {
        if ( empty($sOldFieldName) || empty($sNewFieldName) || empty($sTableName) ) {
            return;
        }

        $aRes = oxDb::getDb()->getAll("show create table {$sTableName}");
        $sSql = $aRes[0][1];

        preg_match( "/.*,\s+(['`]?".$sOldFieldName."['`]?\s+[^,]+),.*/", $sSql, $aMatch );
        $sFieldSql = $aMatch[1];

        $sFullSql = "";

        if ( !empty($sFieldSql) ) {

            $sFieldSql = preg_replace( "/" . $sOldFieldName . "/", $sNewFieldName, $sFieldSql );
            $sFullSql = "ALTER TABLE `$sTableName` ADD " . $sFieldSql;

            if ( $sInsertAfterField ) {
                $sFullSql .= " AFTER `$sInsertAfterField`";
            }
        }

        return $sFullSql;
    }

    /**
     * Get sql string for dublicating indexes for new multilang field
     *
     * @param string $sOldFieldName old field name, which index will be copied
     * @param string $sNewFieldName new field name
     * @param string $sTableName    table name in which new index will be added
     *
     * @return string
     */
    protected function _getDublicatedFieldIndexesSql( $sOldFieldName, $sNewFieldName, $sTableName )
    {
        if ( empty($sOldFieldName) || empty($sNewFieldName) || empty($sTableName) ) {
            return;
        }

        $aRes = oxDb::getDb()->getAll("show create table {$sTableName}");
        $sSql = $aRes[0][1];

        preg_match_all("/([\w]+\s+)?\bKEY\s+(`[^`]+`)?\s*\([^)]+\)/iU", $sSql, $aMatch);

        $aIndexes = $aMatch[0];

        $aNewIndexSql = array();
        $sFullSql = "";

        if ( !empty($aIndexes) ) {

            foreach( $aIndexes as $sIndexSql ) {
                if ( preg_match("/\([^)]*\b" . $sOldFieldName . "\b[^)]*\)/i", $sIndexSql )  ) {

                    //removing index name - new will be added automaticly
                    $sIndexSql = preg_replace("/(.*\bKEY\s+)`[^`]+`/", "$1", $sIndexSql );

                    //replacing old field name with new one
                    $sIndexSql = preg_replace("/\b" . $sOldFieldName . "\b/", $sNewFieldName, $sIndexSql );

                    $sFullSql = "ALTER TABLE `$sTableName` ADD ". $sIndexSql;
                    $aNewIndexSql[] = $sFullSql;
                }
            }

        }

        return $aNewIndexSql;
    }

    /**
     * Get max language ID used in shop. For checking is used table "oxarticle"
     * field "oxtitle"
     *
     * @return int
     */
    public function getCurrentMaxLangId()
    {
        if ( isset($this->_iCurrentMaxLangId) ) {
            return $this->_iCurrentMaxLangId;
        }

        $this->_iCurrentMaxLangId = 0;

        $aFields = $this->getFields( "oxarticles" );
        $aIds = array();

        //checking max "oxarticles" table field "oxtitle" lang suffics value (_1 ,_2 ...)
        foreach( $aFields as $sFieldName ) {
            if ( preg_match("/^OXTITLE_(\d+)$/i", $sFieldName, $aMatches) ) {
                $aIds[] = (int) $aMatches[1];
            }
        }

        if ( count($aIds) > 0 ) {
            $this->_iCurrentMaxLangId = max($aIds );
        }

        return $this->_iCurrentMaxLangId;
    }

    /**
     * Get next available language ID
     *
     * @return int
     */
    public function getNextLangId()
    {
        return $this->getCurrentMaxLangId() + 1;
    }

    /**
     * Get table multilanguge fields
     *
     * @param string $sTableName table name
     *
     * @return array
     */
    public function getMultilangFields( $sTableName )
    {
        $aFields = $this->getFields( $sTableName );
        $aMultiLangFields = array();

        foreach( $aFields as $sFieldName ) {
            if ( preg_match("/(.+)_1$/", $sFieldName, $aMatches) ) {
                $aMultiLangFields[] = $aMatches[1];
            }
        }

        return $aMultiLangFields;
    }

    /**
     * Add new multilanguages fields to table. Dublicates all multilanguage
     * fields and fields indexes with next available language ID
     *
     *  @param string $sTableName table name
     *
     */
    public function addNewMultilangField( $sTableName )
    {
        $aSql = array();
        $aIndexesSql = array();

        $aFields = $this->getMultilangFields( $sTableName );
        $iLangNewBaseId = $this->getNextLangId();
        $iCurrentMaxLangId = $this->getCurrentMaxLangId();

        if ( is_array($aFields) && count($aFields) > 0 ) {
            foreach( $aFields as $sFieldName ) {
                $sNewFieldName = $sFieldName . "_" . $iLangNewBaseId;
                $sLastMultilangFieldName = ( !empty($iCurrentMaxLangId) ) ? $sFieldName . "_" .  $iCurrentMaxLangId : $sFieldName;

                if ( !$this->fieldExists( $sNewFieldName, $sTableName ) ) {
                    //getting creat field sql
                    $aSql[] = $this->_getDublicatedFieldSql( $sFieldName, $sNewFieldName, $sTableName, $sLastMultilangFieldName );

                    //getting create index sql on added field
                    $aFieldIndexSql = $this->_getDublicatedFieldIndexesSql( $sLastMultilangFieldName, $sNewFieldName, $sTableName );
                    if ( !empty($aFieldIndexSql) ) {
                        $aIndexesSql = array_merge( $aIndexesSql, $aFieldIndexSql );
                    }
                }
            }
        }

        if ( !empty($aSql) ) {
            $this->_executeSql( $aSql );
        }

        if ( !empty($aIndexesSql) ) {
            $this->_executeSql( $aIndexesSql );
        }
    }


    /**
     * Reseting all multilanguage fields with specific language id
     * to default value in selected table
     */
    public function resetMultilangFields( $iLangId, $sTableName )
    {
        $iLangId = (int)$iLangId;

        if ( $iLangId === 0 ) {
            return;
        }

        $aSql = array();

        $aFields = $this->getMultilangFields( $sTableName );
        if ( is_array($aFields) && count($aFields) > 0 ) {
            foreach( $aFields as $sFieldName ) {
                $sFieldName = $sFieldName . "_" . $iLangId;

                if ( $this->fieldExists( $sFieldName, $sTableName ) ) {
                    //reseting field value to default
                    $aSql[] = "UPDATE {$sTableName} SET {$sFieldName} = DEFAULT;";
                }
            }
        }

        if ( !empty($aSql) ) {
            $this->_executeSql( $aSql );
        }
    }

    /**
     * Add new language to database. Scans all tables and adds new
     * multilanguage fields
     *
     * @return null
     */
    public function addNewLangToDb()
    {
        $aTable = $this->getAllTables();

        foreach( $aTable as $sTableName ) {
            $this->addNewMultilangField( $sTableName );
        }
    }

    /**
     * Reseting all multilanguage fields with specific language id
     * to default value in all tables. Only if language ID > 0.
     *
     */
    public function resetLanguage( $iLangId )
    {
        if ( (int)$iLangId === 0 ) {
            return;
        }

        $aTables = $this->getAllTables();

        // removing tables which does not requires reset
        foreach ( $this->_aSkipTablesOnReset as $sSkipTable ) {

            if ( ($iSkipId = array_search( $sSkipTable, $aTables )) !== false ) {
                unset( $aTables[$iSkipId] );
            }
        }

        foreach( $aTables as $sTableName ) {
            $this->resetMultilangFields( $iLangId, $sTableName );
        }
    }

    /**
     * Executes arrary of sql strings
     *
     * @param array $aSql
     *
     * @return null
     */
    protected function _executeSql( $aSql )
    {
        $oDb = oxDb::getDb();

        if ( is_array($aSql) && !empty($aSql) ) {
            foreach( $aSql as $sSql) {
                $oDb->execute( $sSql );
            }
        }
    }

}

