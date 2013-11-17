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
 * @package admin
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: object_seo.php 22495 2009-09-22 07:42:43Z arvydas $
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
            $sQ = $this->_getSeoDataSql( $oObject, $iShopId, $this->_iEditLang );
            $aSeoData = oxDb::getDb(true)->getArray( $sQ );
            $aSeoData = ( is_array( $aSeoData ) && isset( $aSeoData[0] ) )?$aSeoData[0]:array();

            // setting default values if empty
            if ( !isset( $aSeoData['OXSEOURL'] ) || !$aSeoData['OXSEOURL'] ||
                 ( isset( $aSeoData['OXEXPIRED'] ) && $aSeoData['OXEXPIRED'] ) ) {
                $aSeoData['OXSEOURL'] = $this->_getSeoUrl( $oObject );
            }

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
        return "select * from oxseo where oxobjectid = ".oxDb::getDb()->quote( $oObject->getId() )." and
                oxshopid = '{$iShopId}' and oxlang = {$iLang} ";
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
        $iShopId  = $this->getConfig()->getShopId();
        return oxDb::getDb()->getOne( $this->_getSeoUrlQuery( $oObject, $iShopId ) );
    }

    /**
     * Returns query for selecting seo url
     *
     * @param object $oObject object to build query
     *
     * @return string
     */
     protected function _getSeoUrlQuery( $oObject, $iShopId )
     {
        return "select oxseourl from oxseo where oxobjectid = '".$oObject->getId()."' and oxshopid = '{$iShopId}' and oxlang = {$this->_iEditLang} ";
     }

    /**
     * Returns seo object
     * @return mixed
     */
    protected function _getObject( $sOxid )
    {
        if ( $this->_oObject === null ) {
            // load object
            $this->_oObject = oxNew( $this->_getType() );
            if ( !$this->_oObject->loadInLang( $this->_iEditLang, $sOxid ) ) {
                $this->_oObject = false;
            }
        }
        return $this->_oObject;
    }

    /**
     * Returns url type
     * @return string
     */
    protected function _getType() {}

    /**
     * Returns objects std url
     * @return string
     */
    protected function _getStdUrl( $sOxid )
    {
        return $this->_getObject( $sOxid )->getLink();
    }

    /**
     * Saves selection list parameters changes.
     *
     * @return mixed
     */
    public function save()
    {
        // saving/updating seo params
        if ( ( $sOxid = $this->getSeoEntryId() ) ) {
            $aSeoData = oxConfig::getParameter( 'aSeoData' );
            $iShopId  = $this->getConfig()->getShopId();

            // checkbox handling
            if ( !isset( $aSeoData['oxfixed'] ) ) {
                $aSeoData['oxfixed'] = 0;
            }

            $oEncoder = oxSeoEncoder::getInstance();

            // marking self and page links as expired
            $oEncoder->markAsExpired( $sOxid, $this->getconfig()->getShopId(), 1, $this->getEditLang() );

            // saving
            $oEncoder->addSeoEntry( $sOxid, $iShopId, $this->getEditLang(), $this->_getStdUrl( oxConfig::getParameter( 'oxid' ) ),
                                    $aSeoData['oxseourl'], $this->_getSeoEntryType(), $aSeoData['oxfixed'],
                                    trim( $aSeoData['oxkeywords'] ), trim( $aSeoData['oxdescription'] ), $this->processParam( $aSeoData['oxparams'] ), true );
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
     * @return string
     */
    protected function getSeoEntryId()
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
}