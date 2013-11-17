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
 * @version   SVN: $Id: oxi18n.php 26071 2010-02-25 15:12:55Z sarunas $
 */

/**
 * @package core
 */

/**
 * Class handling multilanguage data fields
 *
 */
class oxI18n extends oxBase
{

    /**
     * Name of class.
     *
     * @var string
     */
    protected $_sClassName = 'oxI18n';

    /**
     * Active object language.
     *
     * @var int
     */
    protected $_iLanguage = null;

    /**
     * Sometimes you need to deal with all fields not only with active
     * language, then set to false (default is true).
     *
     * @var bool
     */
    protected $_blEmployMultilanguage = true;

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();

        // set default language
        //$this->setLanguage();

        //T2008-02-22
        //lets try to differentiate cache keys for oxI18n and oxBase
        //in order not to load cached structure for the instances of oxbase classe called on same table
        if ($this->_sCacheKey) {
            $this->_sCacheKey .= "_i18n";
        }
    }

    /**
     * Sets object language.
     *
     * @param string $iLang string (default null)
     *
     * @return null;
     */
    public function setLanguage( $iLang = null )
    {
        $this->_iLanguage = (int) $iLang;
    }

    /**
     * Returns object language
     *
     * @return int
     */
    public function getLanguage()
    {
        if ( $this->_iLanguage === null ) {
            $this->_iLanguage = oxLang::getInstance()->getBaseLanguage();
        }
        return $this->_iLanguage;
    }

    /**
     * Object multilanguage mode setter (set true to enable multilang mode).
     * This setter affects init() method so it should be called before init() is executed
     *
     * @param bool $blEmployMultilanguage New $this->_blEmployMultilanguage value
     *
     * @return null;
     */
    public function setEnableMultilang( $blEmployMultilanguage )
    {
        if ($this->_blEmployMultilanguage != $blEmployMultilanguage) {
            $this->_blEmployMultilanguage = $blEmployMultilanguage;
            if (!$blEmployMultilanguage) {
                //#63T
                $this->modifyCacheKey("_nonml");
            }
            if (count($this->_aFieldNames) > 1) {
                $this->_initDataStructure();
            }
        }
    }

    /**
     * Checks if this field is multlingual
     * (returns false if language = 0)
     *
     * @param string $sFieldName Field name
     *
     * @return bool
     */
    public function isMultilingualField($sFieldName)
    {
        if (isset($this->_aFieldNames[$sFieldName])) {
            return (bool) $this->_aFieldNames[$sFieldName];
        }

        //not inited field yet
        //and note that this is should be called only in first call after tmp dir is empty
        startProfile('!__CACHABLE2__!');
        $blIsMultilang = (bool) $this->_getFieldStatus($sFieldName);
        stopProfile('!__CACHABLE2__!');
        return (bool) $blIsMultilang;
    }

    /**
     * Returns true, if object has multilanguage fields.
     * In oxi18n it is always returns true.
     *
     * @return bool
     */
    public function isMultilang()
    {
        return true;
    }

    /**
     * Loads object data from DB in passed language, returns true on success.
     *
     * @param integer $iLanguage Load this language compatible data
     * @param string  $sOxid     object ID
     *
     * @return bool
     */
    public function loadInLang( $iLanguage, $sOxid)
    {
        // set new lang to this object
        $this->setLanguage($iLanguage);
        return $this->load( $sOxid);
    }

    /**
     * Lazy loading cache key modifier.
     *
     * @param string $sCacheKey  kache  key
     * @param bool   $blOverride marker to force override cache key
     *
     * @return null;
     */
    public function modifyCacheKey( $sCacheKey, $blOverride = false )
    {
        if ($blOverride) {
            $this->_sCacheKey = $sCacheKey."|i18n";
        } else {
            $this->_sCacheKey .= $sCacheKey;
        }

        if (!$sCacheKey) {
            $this->_sCacheKey = null;
        }
    }

    /**
     * Handles multilanguage fields during assignment
     *
     * @param array $dbRecord Associative data values array
     *
     * @return null
     */
    public function assign($dbRecord)
    {
        $sLangTag = oxLang::getInstance()->getLanguageTag($this->getLanguage());
        if ($this->_blEmployMultilanguage && $sLangTag) {
            foreach ($dbRecord as $sField => $sVal) {
                //handling multilang
                if (isset($dbRecord[$sField . $sLangTag])) {
                    $dbRecord[$sField] = $dbRecord[$sField . $sLangTag];
                }
            }
        }

        return parent::assign($dbRecord);
    }

    /**
     * Returns an array of languages in which object multilanguage
     * fields are already setted
     *
     * @return array
     */
    public function getAvailableInLangs()
    {
        $aLanguages = oxLang::getInstance()->getLanguageNames();

        $aObjFields = $this->_getAllFields(true);
        $aMultiLangFields = array();

        //selecting all object multilang fields
        foreach ($aObjFields as $sKey => $sValue ) {

            //skipping oxactive field
            if ( preg_match('/^oxactive(_(\d{1,2}))?$/', $sKey) ) {
                continue;
            }

            $iFieldLang = $this->_getFieldLang( $sKey );

            //checking, if field is multilanguage
            if ( $this->isMultilingualField($sKey) || $iFieldLang >  0 ) {
                $sNewKey = preg_replace('/_(\d{1,2})$/', '', $sKey);
                $aMultiLangFields[$sNewKey][] = (int) $iFieldLang;
            }
        }

        // if no multilanguage fields, return default languages array
        if ( count($aMultiLangFields) < 1 ) {
            return $aLanguages;
        }

        $query = "select * from {$this->_sCoreTable} where oxid = '" . $this->getId() . "'";
        $rs = oxDb::getDb( true )->getAll($query);

        $aNotInLang = $aLanguages;

        // checks if object field data is not empty in all available languages
        // and formats not available in languages array
        if ( is_array($rs) && count($rs[0]) ) {
            foreach ( $aMultiLangFields as $sFieldId => $aMultiLangIds ) {

                foreach ( $aMultiLangIds as $sMultiLangId ) {
                    $sFieldName = ( $sMultiLangId == 0 ) ? $sFieldId : $sFieldId.'_'.$sMultiLangId;
                    if ( $rs['0'][strtoupper($sFieldName)] ) {
                        unset( $aNotInLang[$sMultiLangId] );
                        continue;
                    }
                }
            }
        }

        $aIsInLang = array_diff( $aLanguages, $aNotInLang );

        return $aIsInLang;
    }

    /**
     * Returns field name depending on object language.
     *
     * @param string $sField Field name
     *
     * @return string
     */
    public function getSqlFieldName($sField)
    {
        $iLang = $this->getLanguage();
        if ($iLang && $this->_blEmployMultilanguage && $this->isMultilingualField($sField)) {
            $sField .= "_" . $iLang;
        }

        return $sField;
    }

    /**
     * Returns SQL select string with checks if items are available
     *
     * @param bool $blForceCoreTable forces core table usage (optional)
     *
     * @return string
     */
    public function getSqlActiveSnippet( $blForceCoreTable = false )
    {
        $sQ = '';
            $sTable = $this->getCoreTableName();

        // has 'active' or 'active_x' field ?
        if ( $this->isMultilingualField( 'oxactive' ) ) {
            $sQ = " $sTable.oxactive".oxLang::getInstance()->getLanguageTag( $this->getLanguage() )." = 1 ";
        } elseif ( isset( $this->_aFieldNames['oxactive'] ) ) {
            $sQ = " $sTable.oxactive = 1 ";
        }

        // has 'activefrom'/'activeto' fields ?
        if ( isset( $this->_aFieldNames['oxactivefrom'] ) && isset( $this->_aFieldNames['oxactiveto'] ) ) {

            $sDate = date( 'Y-m-d H:i:s', oxUtilsDate::getInstance()->getTime() );

            $sQ = $sQ?" $sQ or ":'';
            $sQ = "( $sQ ( $sTable.oxactivefrom < '$sDate' and $sTable.oxactiveto > '$sDate' ) ) ";
        }

        return $sQ;
    }

    /**
     * Returns _aFieldName[] value. 0 means - non multilanguage, 1 - multilanguage field.
     * This method is slow, so we should make sure it is called only when tmp dir is cleaned (and then the results are cached).
     *
     * @param string $sFieldName Field name
     *
     * @return int
     */
    protected function _getFieldStatus($sFieldName)
    {
        $aAllField = $this->_getAllFields(true);
        if (isset($aAllField[$sFieldName."_1"])) {
            return 1;
        }
        return 0;
    }

    /**
     * Returns the list of fields. This function is slower and its result is normally cached.
     * Basically we have 3 separate cases here:
     *  1. We are in admin so we need extended info for all fields (name, field length and field type)
     *  2. Object is not lazy loaded so we will return all data fields as simple array, as we nede only names
     *  3. Object is lazy loaded so we will return empty array as all fields are loaded on request (in __get()).
     *
     * @param bool $blForceFullStructure Whether to force loading of full data structure
     *
     * @return array
     */
    protected function _getNonCachedFieldNames($blForceFullStructure = false)
    {
        //Tomas
        //TODO: clean this
        $aFields = parent::_getNonCachedFieldNames($blForceFullStructure);

        if (!$this->_blEmployMultilanguage) {
            return $aFields;
        }

        //lets do some pointer manipulation
        if ($aFields) {
            //non admin fields
            $aWorkingFields = &$aFields;
        } else {
            //most likely admin fields so we remove another language
            $aWorkingFields = &$this->_aFieldNames;
        }

        //we have an array of fields, lets remove multilanguage fields
        foreach ($aWorkingFields as $sName => $sVal) {
            if ($this->_getFieldLang($sName)) {
                unset($aWorkingFields[$sName]);
            } else {
                $aWorkingFields[$sName] = $this->_getFieldStatus($sName);
            }
        }

        return $aWorkingFields;
    }

    /**
     * Gets multilanguage field language. In case of oxtitle_2 it will return 2. 0 is returned if language ending is not defined.
     *
     * @param string $sFieldName Field name
     *
     * @return bool
     */
    protected function _getFieldLang($sFieldName)
    {
        startProfile('_getFieldLang');
        $oStr = getStr();
        if ( !$oStr->strstr($sFieldName, '_')) {
            return 0;
        }
        if (preg_match('/_(\d{1,2})$/', $sFieldName, $aRegs)) {
            $sRes = $aRegs[1];
        } else {
            $sRes = 0;
        }

        stopProfile('_getFieldLang');
        return $sRes;
    }

    /**
     * Update this Object into the database, this function only works on
     * the main table, it will not save any dependend tables, which might
     * be loaded through oxlist.
     *
     * @throws oxObjectException Throws on failure inserting
     *
     * @return bool
     */
    protected function _update()
    {
        $blRet = parent::_update();

        // currently only multilanguage objects are SEO
        // if current object is managed by SEO and SEO is ON
        if ( $blRet && $this->_blIsSeoObject && $this->isAdmin() ) {
            // marks all object db entries as expired
            oxSeoEncoder::getInstance()->markAsExpired( $this->getId() );
        }

        return $blRet;
    }

    /**
     * Adds additional field to meta structure. Skips language fields
     *
     * @param string $sName   Field name
     * @param string $sStatus Field status (0-non multilang field, 1-multilang field)
     * @param string $sType   Field type
     * @param string $sLength Field Length
     *
     * @return null;
     */
    /*
    protected function _addField($sName, $sStatus, $sType = null, $sLength = null)
    {
        if ($this->_blEmployMultilanguage && $this->_getFieldLang($sName))
            return;

        return parent::_addField($sName, $sStatus, $sType, $sLength);
    }*/
}
