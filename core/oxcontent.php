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
 * $Id: oxcontent.php 22539 2009-09-22 13:05:20Z sarunas $
 */

/**
 * Content manager.
 * Base object for content pages
 *
 * @package core
 */
class oxContent extends oxI18n
{
    /**
     * Core database table name. $_sCoreTbl could be only original data table name and not view name.
     *
     * @var string
     */
    protected $_sCoreTbl = 'oxcontents';

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxcontent';

    /**
     * Array of fields to skip when saving
     * Overrids oxBase variable
     *
     * @var array
     */
    protected $_aSkipSaveFields = array( 'oxtimestamp' );

    /**
     * noparamlink link to this content
     *
     * @var string
     */
    protected $_sNoparamlink = null;

    /**
     * expanded state of a content category
     *
     * @var bool
     */
    protected $_blExpanded = null;

    /**
     * Marks that current object is managed by SEO
     *
     * @var bool
     */
    protected $_blIsSeoObject = true;

    /**
     * Extra getter to guarantee compatibility with templates
     *
     * @param string $sName parameter name
     *
     * @return mixed
     */
    public function __get( $sName )
    {
        switch ( $sName ) {
            case 'expanded':
                return $this->getExpanded();
                break;
        }
        return parent::__get( $sName );
    }

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxcontents' );
    }

    /**
     * returns the expanded state of the content category
     *
     * @return bool
     */
    public function getExpanded()
    {
        if ( !isset( $this->_blExpanded ) ) {
            $this->_blExpanded = ( $this->getId() == oxConfig::getParameter( 'oxcid' ) );
        }
        return $this->_blExpanded;
    }

    /**
     * Loads Content by using field oxloadid instead of oxid
     *
     * @param string $sLoadId content load ID
     *
     * @return bool
     */
    public function loadByIdent( $sLoadId )
    {
        $sSelect = $this->buildSelectString( array( 'oxcontents.oxloadid' => $sLoadId,
                                                    'oxcontents.'.$this->getSqlFieldName( 'oxactive' ) => '1',
                                                    'oxcontents.oxshopid' => $this->getConfig()->getShopId() ) );

        $sRes = $this->assignRecord( $sSelect );
        return $sRes;
    }

    /**
     * Replace the "&amp;" into "&" and call base class
     *
     * @param array $dbRecord database record
     *
     * @return null
     */
    public function assign( $dbRecord )
    {

        parent::assign( $dbRecord );
        $this->oxcontents__oxcontent->setValue(str_replace( '&amp;', '&', $this->oxcontents__oxcontent->value ), oxField::T_RAW);
        // workaround for firefox showing &lang= as &9001;= entity, mantis#0001272
        $this->oxcontents__oxcontent->setValue(str_replace( '&lang=', '&amp;lang=', $this->oxcontents__oxcontent->value ), oxField::T_RAW);
        $this->getLink();
    }

    /**
     * getLink returns link for this content in the frontend
     *
     * @param integer $iLang language
     *
     * @access public
     * @return string
     */
    public function getLink($iLang = null)
    {
        if (isset($iLang)) {
            $iLang = (int) $iLang;
            if ($iLang == (int) $this->getLanguage()) {
                $iLang = null;
            }
        }
        if ( $this->_sNoparamlink === null || isset($iLang) ) {
            if ( oxUtils::getInstance()->seoIsActive() ) {
                $sNoparamlink = oxSeoEncoderContent::getInstance()->getContentUrl( $this, $iLang );
            } else {
                $sNoparamlink = $this->getStdLink($iLang);
            }

            if (isset($iLang)) {
                return $sNoparamlink;
            } else {
                $this->_sNoparamlink = $sNoparamlink;
            }
        }

        return $this->_sNoparamlink;
    }

    /**
     * Returns standard URL to product
     *
     * @param integer $iLang language
     *
     * @return string
     */
    public function getStdLink($iLang = null)
    {
        $sAdd = '';

        if (isset($iLang) && !oxUtils::getInstance()->seoIsActive()) {
            $iLang = (int) $iLang;
            if ($iLang != (int) $this->getLanguage()) {
                $sAdd .= "&amp;lang={$iLang}";
            }
        }
        if ($this->oxcontents__oxcatid->value && $this->oxcontents__oxcatid->value != 'oxrootid') {
            $oDb = oxDb::getDb();
            $sParentId = $oDb->getOne("select oxparentid from oxcategories where oxid = ".$oDb->quote($this->oxcontents__oxcatid->value));
            if ($sParentId && 'oxrootid' != $sParentId) {
                $sAdd .= "&amp;cnid=$sParentId";
            }
        }
        return $this->getConfig()->getShopHomeURL() . "cl=content&amp;oxcid=" . $this->getId() . $sAdd;
    }

    /**
     * Sets data field value
     *
     * @param string $sFieldName index OR name (eg. 'oxarticles__oxtitle') of a data field to set
     * @param string $sValue     value of data field
     * @param int    $iDataType  field type
     *
     * @return null
     */
    protected function _setFieldData( $sFieldName, $sValue, $iDataType = oxField::T_TEXT)
    {
        if ('oxcontent' === strtolower($sFieldName) || 'oxcontents__oxcontent' === strtolower($sFieldName)) {
            $iDataType = oxField::T_RAW;
        }

        return parent::_setFieldData($sFieldName, $sValue, $iDataType);
    }

    /**
     * Delete this object from the database, returns true on success.
     *
     * @param string $sOXID Object ID(default null)
     *
     * @return bool
     */
    public function delete( $sOXID = null)
    {
        if ( !$sOXID ) {
        	$sOXID = $this->getId();
        }
        if (parent::delete($sOXID)) {
            oxSeoEncoderContent::getInstance()->onDeleteContent($sOXID);
            return true;
        }
        return false;
    }
}
