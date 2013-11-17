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
 * @package inc
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: selectlist_main.inc.php 22935 2009-10-05 12:26:25Z vilma $
 */

$aColumns = array( 'container1' => array(    // field , table,         visible, multilanguage, ident
                                        array( 'oxartnum', 'oxarticles', 1, 0, 0 ),
                                        array( 'oxtitle',  'oxarticles', 1, 1, 0 ),
                                        array( 'oxean',    'oxarticles', 0, 0, 0 ),
                                        array( 'oxprice',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxstock',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxid',     'oxarticles', 0, 0, 1 )
                                        ),
                     'container2' => array(
                                        array( 'oxartnum', 'oxarticles', 1, 0, 0 ),
                                        array( 'oxtitle',  'oxarticles', 1, 1, 0 ),
                                        array( 'oxean',    'oxarticles', 0, 0, 0 ),
                                        array( 'oxprice',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxstock',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxid',     'oxobject2selectlist', 0, 0, 1 ),
                                        array( 'oxid',     'oxarticles', 0, 0, 1 )
                                        ),
                     'container3' => array(
                                        array( 'oxtitle',   'oxselectlist', 1, 1, 0 ),
                                        array( 'oxsort',    'oxobject2selectlist', 1, 0, 0 ),
                                        array( 'oxident',   'oxselectlist', 0, 0, 0 ),
                                        array( 'oxvaldesc', 'oxselectlist', 0, 0, 0 ),
                                        array( 'oxid',      'oxselectlist', 0, 0, 1 )
                                                                                            )
                                    );

/**
 * Class manages article select lists configuration
 */
class ajaxComponent extends ajaxListComponent
{
    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $myConfig = $this->getConfig();

        // looking for table/view
        $sArtTable = getViewName('oxarticles');
        $sCatTable = getViewName('oxcategories');
        $sO2CView  = getViewName('oxobject2category');

        $sSelId      = oxConfig::getParameter( 'oxid' );
        $sSynchSelId = oxConfig::getParameter( 'synchoxid' );

        // category selected or not ?
        if ( !$sSelId) {
            // dodger performance
            $sQAdd  = " from $sArtTable where 1 ";
            $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?'':" and $sArtTable.oxparentid = '' ";
        } else {
            // selected category ?
            if ( $sSynchSelId && $sSelId != $sSynchSelId ) {
                $sQAdd  = " from $sO2CView as oxobject2category left join $sArtTable on ";
                $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?" ( $sArtTable.oxid=oxobject2category.oxobjectid or $sArtTable.oxparentid=oxobject2category.oxobjectid ) ":" $sArtTable.oxid=oxobject2category.oxobjectid ";
                $sQAdd .= " where oxobject2category.oxcatnid = '$sSelId' ";
            } else {
                $sQAdd  = " from $sArtTable left join oxobject2selectlist on $sArtTable.oxid=oxobject2selectlist.oxobjectid ";
                $sQAdd .= " where oxobject2selectlist.oxselnid = '$sSelId' ";
            }
        }

        if ( $sSynchSelId && $sSynchSelId != $sSelId ) {
            // dodger performance
            $sQAdd .= " and $sArtTable.oxid not in ( select oxobject2selectlist.oxobjectid from oxobject2selectlist ";
            $sQAdd .= " where oxobject2selectlist.oxselnid = '$sSynchSelId' ) ";
        }

        return $sQAdd;
    }

    /**
     * Adds filter SQL to current query
     *
     * @param string $sQ query to add filter condition
     *
     * @return string
     */
    protected function _addFilter( $sQ )
    {
        $sArtTable = getViewName('oxarticles');
        $sQ = parent::_addFilter( $sQ );

        // display variants or not ?
        $sQ .= $this->getConfig()->getConfigParam( 'blVariantsSelection' ) ? ' group by '.$sArtTable.'.oxid ' : '';
        return $sQ;
    }

    /**
     * Removes article from Selection list
     *
     * @return null
     */
    public function removeartfromsel()
    {
        $aChosenArt = $this->_getActionIds( 'oxobject2selectlist.oxid' );
        if ( oxConfig::getParameter( 'all' ) ) {

            $sQ = $this->_addFilter( "delete oxobject2selectlist.* ".$this->_getQuery() );
            oxDb::getDb()->Execute( $sQ );

        } elseif ( is_array( $aChosenArt ) ) {
            $sQ = "delete from oxobject2selectlist where oxobject2selectlist.oxid in (" . implode( ", ", oxDb::getInstance()->quoteArray( $aChosenArt ) ) . ") ";
            oxDb::getDb()->Execute( $sQ );
        }
    }

    /**
     * Adds article to Selection list
     *
     * @return null
     */
    public function addarttosel()
    {
        $aAddArticle = $this->_getActionIds( 'oxarticles.oxid' );
        $soxId       = oxConfig::getParameter( 'synchoxid');

        if ( oxConfig::getParameter( 'all' ) ) {
            $sArtTable = getViewName('oxarticles');
            $aAddArticle = $this->_getAll( $this->_addFilter( "select $sArtTable.oxid ".$this->_getQuery() ) );
        }

        if ( $soxId && $soxId != "-1" && is_array( $aAddArticle ) ) {
            $oDb = oxDb::getDb();
            foreach ($aAddArticle as $sAdd) {
                $oNewGroup = oxNew( "oxbase" );
                $oNewGroup->init( "oxobject2selectlist" );
                $oNewGroup->oxobject2selectlist__oxobjectid = new oxField( $sAdd );
                $oNewGroup->oxobject2selectlist__oxselnid = new oxField( $soxId );
                $oNewGroup->oxobject2selectlist__oxsort   = new oxField( ( int ) $oDb->getOne( "select max(oxsort) + 1 from oxobject2selectlist where oxobjectid =  " . $oDb->quote( $sAdd ) . " " ) );
                $oNewGroup->save();
            }
        }
    }

    /**
     * Formats and returns chunk of SQL query string with definition of
     * fields to load from DB. Adds subselect to get variant title from parent article
     *
     * @return string
     */
    protected function _getQueryCols()
    {
        $myConfig = $this->getConfig();
        $sLangTag = oxLang::getInstance()->getLanguageTag();

        $sQ = '';
        $blSep = false;
        $aVisiblecols = $this->_getVisibleColNames();
        foreach ( $aVisiblecols as $iCnt => $aCol ) {
            if ( $blSep )
                $sQ .= ', ';
            $sViewTable = getViewName( $aCol[1] );
            // multilanguage
            $sCol = $aCol[3]?$aCol[0].$sLangTag:$aCol[0];
            if ( $myConfig->getConfigParam( 'blVariantsSelection' ) && $aCol[0] == 'oxtitle' ) {
                $sVarSelect = "$sViewTable.oxvarselect".$sLangTag;
                $sQ .= " IF( $sViewTable.$sCol != '', $sViewTable.$sCol, CONCAT((select oxart.$sCol from $sViewTable as oxart where oxart.oxid = $sViewTable.oxparentid),', ',$sVarSelect)) as _" . $iCnt;
            } else {
                $sQ  .= $sViewTable . '.' . $sCol . ' as _' . $iCnt;
            }
            $blSep = true;
        }

        $aIdentCols = $this->_getIdentColNames();
        foreach ( $aIdentCols as $iCnt => $aCol ) {
            if ( $blSep )
                $sQ .= ', ';

            // multilanguage
            $sCol = $aCol[3]?$aCol[0].$sLangTag:$aCol[0];
            $sQ  .= getViewName( $aCol[1] ) . '.' . $sCol . ' as _' . $iCnt;
        }

        return " $sQ ";
    }

}
