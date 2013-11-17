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
 * $Id: manufacturer_main.inc.php 14035 2008-11-06 14:48:53Z arvydas $
 */

$aColumns = array( 'container1' => array(    // field , table,       visible, multilanguage, ident
                                        array( 'oxartnum', 'oxarticles', 1, 0, 0 ),
                                        array( 'oxtitle',  'oxarticles', 1, 1, 0 ),
                                        array( 'oxean',    'oxarticles', 1, 0, 0 ),
                                        array( 'oxprice',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxstock',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxid',     'oxarticles', 0, 0, 1 )
                                        ),
                     'container2' => array(
                                        array( 'oxartnum', 'oxarticles', 1, 0, 0 ),
                                        array( 'oxtitle',  'oxarticles', 1, 1, 0 ),
                                        array( 'oxean',    'oxarticles', 1, 0, 0 ),
                                        array( 'oxprice',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxstock',  'oxarticles', 0, 0, 0 ),
                                        array( 'oxid',     'oxarticles', 0, 0, 1 )
                                        )
                    );

/**
 * Class manages manufacturer assignment to articles
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
        $sO2CView  = getViewName('oxobject2category');

        $sManufacturerId      = oxConfig::getParameter( 'oxid' );
        $sSynchManufacturerId = oxConfig::getParameter( 'synchoxid' );

        // Manufacturer selected or not ?
        if ( !$sManufacturerId ) {
            // dodger performance
            $sQAdd  = ' from '.$sArtTable.' where '.$sArtTable.'.oxshopid="'.$myConfig->getShopId().'" and 1 ';
            $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' ) ?'':" and $sArtTable.oxparentid = '' and $sArtTable.oxmanufacturerid != '$sSynchManufacturerId' ";
        } else {
            // selected category ?
            if ( $sSynchManufacturerId && $sSynchManufacturerId != $sManufacturerId ) {
                $sQAdd  = " from $sO2CView left join $sArtTable on ";
                $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?" ( $sArtTable.oxid = $sO2CView.oxobjectid or $sArtTable.oxparentid = oxobject2category.oxobjectid )":" $sArtTable.oxid = $sO2CView.oxobjectid ";
                $sQAdd .= 'where '.$sArtTable.'.oxshopid="'.$myConfig->getShopId().'" and '.$sO2CView.'.oxcatnid = "'.$sManufacturerId.'" and '.$sArtTable.'.oxmanufacturerid != "'. $sSynchManufacturerId .'" ';
                $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?'':" and $sArtTable.oxparentid = '' ";
            } else {
                $sQAdd  = " from $sArtTable where $sArtTable.oxmanufacturerid = '$sManufacturerId' ";
                $sQAdd .= $myConfig->getConfigParam( 'blVariantsSelection' )?'':" and $sArtTable.oxparentid = '' ";
            }
        }

        return $sQAdd;
    }

    /**
     * Return fully formatted query for data loading
     *
     * @param string $sQ part of initial query
     *
     * @return string
     */
    protected function _getDataQuery( $sQ )
    {
        $sArtTable = getViewName('oxarticles');
        $sQ = parent::_getDataQuery( $sQ );

        // display variants or not ?
        $sQ .= $this->getConfig()->getConfigParam( 'blVariantsSelection' ) ? ' group by '.$sArtTable.'.oxid ' : '';
        return $sQ;
    }

    /**
     * Removes article from Manufacturer config
     *
     * @return null
     */
    public function removeManufacturer()
    {
        $myConfig   = $this->getConfig();
        $aRemoveArt = $this->_getActionIds( 'oxarticles.oxid' );

        if ( oxConfig::getParameter( 'all' ) ) {
            $sArtTable = getViewName( 'oxarticles' );
            $aRemoveArt = $this->_getAll( $this->_addFilter( "select $sArtTable.oxid ".$this->_getQuery() ) );
        }

        if ( is_array(  $aRemoveArt ) ) {
            $sSelect = "update oxarticles set oxmanufacturerid = null where oxid in ( ".implode(", ", oxDb::getInstance()->quoteArray( $aRemoveArt ) ).") ";
            oxDb::getDb()->Execute( $sSelect);

            $this->resetCounter( "manufacturerArticle", oxConfig::getParameter( 'oxid' ) );
        }
    }

    /**
     * Adds article to Manufacturer config
     *
     * @return null
     */
    public function addManufacturer()
    {
        $myConfig = $this->getConfig();

        $aAddArticle = $this->_getActionIds( 'oxarticles.oxid' );
        $soxId       = oxConfig::getParameter( 'synchoxid' );

        if ( oxConfig::getParameter( 'all' ) ) {
            $sArtTable = getViewName( 'oxarticles' );
            $aAddArticle = $this->_getAll( $this->_addFilter( "select $sArtTable.oxid ".$this->_getQuery() ) );
        }

        if ( $soxId && $soxId != "-1" && is_array( $aAddArticle ) ) {
            $sSelect = "update oxarticles set oxmanufacturerid = '$soxId' where oxid in ( ".implode(", ", oxDb::getInstance()->quoteArray( $aAddArticle ) )." )";

            oxDb::getDb()->Execute( $sSelect);
            $this->resetCounter( "manufacturerArticle", $soxId );
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
