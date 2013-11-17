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
 * @version   SVN: $Id: list_review.php 27134 2010-04-09 13:50:28Z arvydas $
 */

/**
 * user list "view" class.
 * @package admin
 */
class List_Review extends Article_List
{
    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType  = 'oxlist';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxreview';

    /**
     * Viewable list size getter
     *
     * @return int
     */
    protected function _getViewListSize()
    {
        return $this->_getUserDefListSize();
    }

    /**
     * Executes parent method parent::render(), passes data to Smarty engine
     * and returns name of template file "list_review.tpl".
     *
     * @return string
     */
    public function render()
    {
        oxAdminList::render();

        $this->_aViewData["viewListSize"]  = $this->_getViewListSize();
        $this->_aViewData["whereparam"]    = $this->_aViewData["whereparam"] . '&amp;viewListSize='.$this->_getViewListSize();
        $this->_aViewData["menustructure"] = $this->getNavigation()->getDomXml()->documentElement->childNodes;
        $this->_aViewData["articleListTable"] = getViewName('oxarticles');

        return "list_review.tpl";
    }

    /**
     * Sets view filter data
     *
     *  - aViewData['where'] containts filter data like $object->oxarticles__oxtitle = filter_value
     *  - aViewData['whereparam'] contains string which can be later used in url. and
     *    looks like &amp;where[oxarticles.oxtitle]=_filter_value_&amp;art_category=_filter_categry_;
     *
     * @return null
     */
    protected function _setFilterParams()
    {
        parent::_setFilterParams();

        // build where
        if ( is_array( $aWhere = oxConfig::getParameter( 'where' ) ) ) {

            $myUtils  = oxUtils::getInstance();
            $sTable = 'oxarticles';

            $oSearchKeys = isset( $this->_aViewData['where'] ) ? $this->_aViewData['where'] : new oxStdClass();

            while ( list( $sName, $sValue ) = each( $aWhere ) ) {
                $sFieldName = str_replace( getViewName( $sTable ) . '.', $sTable . '.', $sName );
                $sFieldName = $myUtils->getArrFldName( $sFieldName );
                $oSearchKeys->$sFieldName = $sValue;
            }
            $this->_aViewData['where'] = $oSearchKeys;
        }
    }

    /**
     * Returns select query string
     *
     * @param object $oObject list item object
     *
     * @return string
     */
    protected function _buildSelectString( $oObject = null )
    {
        $sArtTable = getViewName('oxarticles');
        $sLangTag = oxLang::getInstance()->getLanguageTag( $this->_iEditLang );

        $sSql  = "select oxreviews.oxid, oxreviews.oxcreate, oxreviews.oxtext, oxreviews.oxobjectid, {$sArtTable}.oxparentid, {$sArtTable}.oxtitle{$sLangTag} as oxtitle, {$sArtTable}.oxvarselect{$sLangTag} as oxvarselect, oxparentarticles.oxtitle{$sLangTag} as parenttitle, ";
        $sSql .= "concat( {$sArtTable}.oxtitle{$sLangTag}, if(isnull(oxparentarticles.oxtitle{$sLangTag}), '', oxparentarticles.oxtitle{$sLangTag}), {$sArtTable}.oxvarselect{$sLangTag}) as arttitle from oxreviews ";
        $sSql .= "left join $sArtTable as {$sArtTable} on {$sArtTable}.oxid=oxreviews.oxobjectid and 'oxarticle' = oxreviews.oxtype ";
        $sSql .= "left join $sArtTable as oxparentarticles on oxparentarticles.oxid = {$sArtTable}.oxparentid ";
        $sSql .= "where 1 and oxreviews.oxlang = '{$this->_iEditLang}' ";
        return $sSql;
    }

    /**
     * Adds filtering conditions to query string
     *
     * @param array  $aWhere filter conditions
     * @param string $sSql   query string
     *
     * @return string
     */
    protected function _prepareWhereQuery( $aWhere, $sSql )
    {
        $oStr = getStr();
        $sArtTable = getViewName('oxarticles');
        $sArtTitleField = "{$sArtTable}.oxtitle";
        $sSqlForTitle = null;
        $sLangTag = oxLang::getInstance()->getLanguageTag( $this->_iEditLang );

        $sSql = parent::_prepareWhereQuery( $aWhere, $sSql );

        //removing parent id checking from sql
        $sStr = "/\s+and\s+".getViewName( 'oxarticles' )."\.oxparentid\s*=\s*''/";
        $sSql = $oStr->preg_replace( $sStr, " ", $sSql );

        // if searching in article title field, updating sql for this case
        if ( $this->_aWhere[$sArtTitleField] ) {
            $sSqlForTitle = " (CONCAT( {$sArtTable}.oxtitle{$sLangTag}, if(isnull(oxparentarticles.oxtitle{$sLangTag}), '', oxparentarticles.oxtitle{$sLangTag}), {$sArtTable}.oxvarselect{$sLangTag})) ";
            $sSql = $oStr->preg_replace( "/{$sArtTable}\.oxtitle\s+like/", "$sSqlForTitle like", $sSql );
        }

        return " $sSql and {$sArtTable}.oxid is not null ";
    }

    /**
     * Adds order by to SQL query string.
     *
     * @param string $sSql sql string
     *
     * @return string
     */
    protected function _prepareOrderByQuery( $sSql = null )
    {
        if ( $sSort = oxConfig::getParameter( "sort" ) ) {
            $sSql .= " order by ".oxDb::getInstance()->escapeString( $sSort ) ." ";
        }

        return $sSql;
    }
}
