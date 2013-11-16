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
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: attribute_main.inc.php 29956 2010-09-23 15:51:52Z tomas $
 */

$aColumns = array( 'container1' => array(    // field , table,         visible, multilanguage, ident
                                        array( 'oxartnum', 'oxarticles', 1, 0, 0 ),
                                        array( 'oxtitle',  'oxarticles', 1, 1, 0 ),
                                        array( 'oxean',    'oxarticles', 1, 0, 0 ),
                                        array( 'oxmpn',    'oxarticles', 0, 0, 0 ),
                                        array( 'oxprice',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxstock',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxid',     'oxarticles', 0, 0, 1 )
                                        ),
                     'container2' => array(
                                        array( 'oxartnum', 'oxarticles', 1, 0, 0 ),
                                        array( 'oxtitle',  'oxarticles', 1, 1, 0 ),
                                        array( 'oxean',    'oxarticles', 1, 0, 0 ),
                                        array( 'oxmpn',    'oxarticles', 0, 0, 0 ),
                                        array( 'oxprice',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxstock',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxid',     'oxobject2attribute', 0, 0, 1 )
                                        )
                    );


/**
 * Class manages article attributes
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
        $oDb      = oxDb::getDb();

        $sArticleTable = getViewName('oxarticles');
        $sO2CView      = getViewName('oxobject2category');

        $sDelId      = oxConfig::getParameter( 'oxid' );
        $sSynchDelId = oxConfig::getParameter( 'synchoxid' );

        // category selected or not ?
        if ( !$sDelId) {
            // dodger performance
            $sQAdd  = " from $sArticleTable where 1 ";
            $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?'':" and $sArticleTable.oxparentid = '' ";
        } else {
            // selected category ?
            if ( $sSynchDelId && $sDelId != $sSynchDelId ) {
                $sQAdd  = " from $sO2CView as oxobject2category left join $sArticleTable on ";
                $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?" ( $sArticleTable.oxid=oxobject2category.oxobjectid or $sArticleTable.oxparentid=oxobject2category.oxobjectid)":" $sArticleTable.oxid=oxobject2category.oxobjectid ";
                $sQAdd .= " where oxobject2category.oxcatnid = " . $oDb->quote( $sDelId ) . " ";
            } else {
                $sQAdd  = " from oxobject2attribute left join $sArticleTable on $sArticleTable.oxid=oxobject2attribute.oxobjectid ";
                $sQAdd .= " where oxobject2attribute.oxattrid = " . $oDb->quote( $sDelId ) . " and $sArticleTable.oxid is not null ";
            }
        }

        if ( $sSynchDelId && $sSynchDelId != $sDelId ) {
            $sQAdd .= " and $sArticleTable.oxid not in ( select oxobject2attribute.oxobjectid from oxobject2attribute where oxobject2attribute.oxattrid = " . $oDb->quote( $sSynchDelId ) . " ) ";
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
        $sQ = parent::_addFilter( $sQ );

        // display variants or not ?
        if ( $this->getConfig()->getConfigParam( 'blVariantsSelection' ) ) {
            $sQ .= ' group by '.getViewName( 'oxarticles' ).'.oxid ';

            $oStr = getStr();
            if ( $oStr->strpos( $sQ, "select count( * ) " ) === 0 ) {
                $sQ = "select count( * ) from ( {$sQ} ) as _cnttable";
            }
        }
        return $sQ;
    }

    /**
     * Removes article from Attribute list
     *
     * @return null
     */
    public function removeattrarticle()
    {
        $aChosenCat = $this->_getActionIds( 'oxobject2attribute.oxid' );
        if ( oxConfig::getParameter( 'all' ) ) {

            $sQ = parent::_addFilter( "delete oxobject2attribute.* ".$this->_getQuery() );
            oxDb::getDb()->Execute( $sQ );

        } elseif ( is_array( $aChosenCat ) ) {
            $sQ = "delete from oxobject2attribute where oxobject2attribute.oxid in (" . implode( ", ", oxDb::getInstance()->quoteArray( $aChosenCat ) ) . ") ";
            oxDb::getDb()->Execute( $sQ );
        }
    }

    /**
     * Adds article to Attribute list
     *
     * @return null
     */
    public function addattrarticle()
    {
        $aAddArticle = $this->_getActionIds( 'oxarticles.oxid' );
        $soxId       = oxConfig::getParameter( 'synchoxid' );

        // adding
        if ( oxConfig::getParameter( 'all' ) ) {
            $sArticleTable = getViewName( 'oxarticles' );
            $aAddArticle = $this->_getAll( $this->_addFilter( "select $sArticleTable.oxid ".$this->_getQuery() ) );
        }

        $oCategory = oxNew( "oxattribute" );


        if ( $oCategory->load( $soxId) && is_array( $aAddArticle ) ) {
            foreach ($aAddArticle as $sAdd) {
                $oNewGroup = oxNew( "oxbase" );
                $oNewGroup->init( "oxobject2attribute" );
                $oNewGroup->oxobject2attribute__oxobjectid = new oxField($sAdd);
                $oNewGroup->oxobject2attribute__oxattrid   = new oxField($oCategory->oxattribute__oxid->value);
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
