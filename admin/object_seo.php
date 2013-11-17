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
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: object_seo.php 28019 2010-05-31 08:07:40Z arvydas $
 */

/**
 * Base seo config class
 */
class Object_Seo extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(),
     * and returns name of template file
     * "object_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        if ( ( $oObject= $this->_getObject( oxConfig::getParameter( 'oxid' ) ) ) ) {

            $iShopId  = $this->getConfig()->getShopId();
            $oOtherLang = $oObject->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oObject->loadInLang( key( $oOtherLang ), oxConfig::getParameter( 'oxid' ) );
            }
            $this->_aViewData['edit'] = $oObject;

            $aLangs = oxLang::getInstance()->getLanguageNames();
            foreach ( $aLangs as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData['otherlang'][$id] = clone $oLang;
            }

            // loading SEO part
            $this->_getSeoUrl( $oObject );
            $sQ = $this->_getSeoDataSql( $oObject, $iShopId, $this->_iEditLang );
            $aSeoData = oxDb::getDb(true)->getArray( $sQ );
            $aSeoData = ( is_array( $aSeoData ) && isset( $aSeoData[0] ) )?$aSeoData[0]:array();

            // passing to view
            $this->_aViewData['aSeoData'] = $aSeoData;
        }

        return 'object_seo.tpl';
    }

    /**
     * Returns SQL to fetch seo data
     *
     * @param oxbase $oObject object to load seo info
     * @param int    $iShopId active shop id
     * @param int    $iLang   active language id
     *
     * @return string
     */
    protected function _getSeoDataSql( $oObject, $iShopId, $iLang )
    {
        return "select * from oxseo
               left join oxobject2seodata on
                   oxobject2seodata.oxobjectid = oxseo.oxobjectid and
                   oxobject2seodata.oxshopid = oxseo.oxshopid and
                   oxobject2seodata.oxlang = oxseo.oxlang
               where
                   oxseo.oxobjectid = ".oxDb::getDb()->quote( $oObject->getId() )." and
                   oxseo.oxshopid = '{$iShopId}' and oxseo.oxlang = {$iLang} ";
    }

    /**
     * Returns objects seo url
     *
     * @param object $oObject object to return url
     *
     * @return string
     */
    protected function _getSeoUrl( $oObject )
    {
        return oxDb::getDb()->getOne( $this->_getSeoUrlQuery( $oObject, $this->getConfig()->getShopId() ) );
    }

    /**
     * Returns query for selecting seo url
     *
     * @param object $oObject object to build query
     * @param int    $iShopId shop id
     *
     * @return string
     */
     protected function _getSeoUrlQuery( $oObject, $iShopId )
     {
        return "select oxseourl from oxseo where oxobjectid = '".$oObject->getId()."' and oxshopid = '{$iShopId}' and oxlang = {$this->_iEditLang} ";
     }

    /**
     * Returns seo object
     *
     * @param string $sOxid object id
     *
     * @return mixed
     */
    protected function _getObject( $sOxid )
    {
        if ( $this->_oObject === null && ( $sType = $this->_getType() ) ) {
            $this->_oObject = false;

            // load object
            $oObject = oxNew( $sType );
            if ( $oObject->loadInLang( $this->_iEditLang, $sOxid ) ) {
                $this->_oObject = $oObject;
            }
        }
        return $this->_oObject;
    }

    /**
     * Returns url type
     *
     * @return string
     */
    protected function _getType()
    {
    }

    /**
     * Returns objects std url
     *
     * @param string $sOxid object id
     *
     * @return string
     */
    protected function _getStdUrl( $sOxid )
    {
        return $this->_getObject( $sOxid )->getBaseStdLink( $this->_iEditLang, true, false );
    }

    /**
     * Saves selection list parameters changes.
     *
     * @return mixed
     */
    public function save()
    {
        // saving/updating seo params
        if ( ( $sOxid = $this->_getSeoEntryId() ) ) {
            $aSeoData = oxConfig::getParameter( 'aSeoData' );
            $iShopId  = $this->getConfig()->getShopId();

            // checkbox handling
            if ( !isset( $aSeoData['oxfixed'] ) ) {
                $aSeoData['oxfixed'] = 0;
            }

            $oEncoder = $this->_getEncoder();

            // marking self and page links as expired
            $oEncoder->markAsExpired( $sOxid, $iShopId, 1, $this->getEditLang() );

            // saving
            $oEncoder->addSeoEntry( $sOxid, $iShopId, $this->getEditLang(), $this->_getStdUrl( $sOxid ),
                                    $aSeoData['oxseourl'], $this->_getSeoEntryType(), $aSeoData['oxfixed'],
                                    trim( $aSeoData['oxkeywords'] ), trim( $aSeoData['oxdescription'] ), $this->processParam( $aSeoData['oxparams'] ), true, $this->_getAltSeoEntryId() );
        }
    }

    /**
     * Returns edit language id
     *
     * @return int
     */
    public function getEditLang()
    {
        return $this->_iEditLang;
    }

    /**
     * Returns seo entry ident
     *
     * @deprecated should be used object_seo::_getSeoEntryId()
     *
     * @return string
     */
    protected function getSeoEntryId()
    {
        return $this->_getSeoEntryId();
    }

    /**
     * Returns alternative seo entry id
     *
     * @return null
     */
    protected function _getAltSeoEntryId()
    {
    }

    /**
     * Returns seo entry ident
     *
     * @return string
     */
    protected function _getSeoEntryId()
    {
        return oxConfig::getParameter( 'oxid' );
    }

    /**
     * Returns seo entry type
     *
     * @return string
     */
    protected function _getSeoEntryType()
    {
        return $this->_getType();
    }

    /**
     * Processes parameter before writing to db
     *
     * @param string $sParam parameter to process
     *
     * @return string
     */
    public function processParam( $sParam )
    {
        return $sParam;
    }

    /**
     * Returns current object type seo encoder object
     *
     * @return oxSeoEncoder
     */
    protected function _getEncoder()
    {
        return oxSeoEncoder::getInstance();
    }
}
