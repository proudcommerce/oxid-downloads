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
 * @version   SVN: $Id: category_main.inc.php 26071 2010-02-25 15:12:55Z sarunas $
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
                                        array( 'oxid',     'oxarticles', 0, 0, 1 )
                                        )
                    );


/**
 * Class manages category articles
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

        $sArticleTable = getViewName('oxarticles');
        $sO2CView      = getViewName('oxobject2category');

        $sOxid      = oxConfig::getParameter( 'oxid' );
        $sSynchOxid = oxConfig::getParameter( 'synchoxid' );
        $oDb        = oxDb::getDb();

        $sShopID    = $myConfig->getShopId();

        // category selected or not ?
        if ( !$sOxid && $sSynchOxid ) {

            // dodger performance
            $sQAdd  = ' from '.$sArticleTable.' where 1 ';
        } else {

            // copied from oxadminview (not sure if this works)
            $sJoin = " {$sArticleTable}.oxid={$sO2CView}.oxobjectid ";

            $sSubSelect = '';
            if ( $sSynchOxid && $sOxid != $sSynchOxid ) {

                $sSubSelect  = ' and '.$sArticleTable.'.oxid not in ( ';
                $sSubSelect .= "select $sArticleTable.oxid from $sO2CView left join $sArticleTable ";
                $sSubSelect .= "on $sJoin where $sO2CView.oxcatnid =  " . $oDb->quote( $sSynchOxid ) . " ";
                $sSubSelect .= 'and '.$sArticleTable.'.oxid is not null ) ';
            }

            $sQAdd  = " from $sO2CView join $sArticleTable ";
            $sQAdd .= " on $sJoin where $sO2CView.oxcatnid = '" . $sOxid . "' ";
            $sQAdd .= " and $sArticleTable.oxid is not null $sSubSelect ";
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
        if ( !$this->getConfig()->getConfigParam( 'blVariantsSelection' ) ) {
            $sQ .=  " and {$sArtTable}.oxparentid = '' ";
        }

        return $sQ;
    }

    /**
     * Adds article to category
     * Creates new list
     *
     * @return null
     */
    public function addarticle()
    {
        $myConfig = $this->getConfig();

        $aArticles  = $this->_getActionIds( 'oxarticles.oxid' );
        $sCategoryID = oxConfig::getParameter( 'synchoxid');
        $sShopID     = $myConfig->getShopId();
        $oDb         = oxDb::getDb();
        $sArticleTable = getViewName( 'oxarticles' );

        // adding
        if ( oxConfig::getParameter( 'all' ) ) {
            $aArticles = $this->_getAll( $this->_addFilter( "select $sArticleTable.oxid ".$this->_getQuery() ) );
        } else {
            // appending variants
            $aVariants = $this->_getAll( "select $sArticleTable.oxid from $sArticleTable where $sArticleTable.oxparentid in ( " . implode( ", ", oxDb::getInstance()->quoteArray( $aArticles ) ) . ")" );
            if ( count( $aVariants ) ) {
                $aArticles = array_merge( $aArticles, $aVariants );
            }
        }

        if ( is_array($aArticles)) {


            $sO2CView = getViewName('oxobject2category');

            $oNew = oxNew( 'oxbase' );
            $oNew->init( 'oxobject2category' );
            $myUtilsObject = oxUtilsObject::getInstance();
            $oActShop = $myConfig->getActiveShop();

            foreach ( $aArticles as $sAdd) {

                // check, if it's already in, then don't add it again
                $sSelect = "select 1 from $sO2CView as oxobject2category where oxobject2category.oxcatnid= " . $oDb->quote( $sCategoryID ) . " and oxobject2category.oxobjectid = " . $oDb->quote( $sAdd ) . "";
                if ( $oDb->getOne( $sSelect ) )
                    continue;

                $oNew->oxobject2category__oxid       = new oxField($oNew->setId( $myUtilsObject->generateUID() ));
                $oNew->oxobject2category__oxobjectid = new oxField($sAdd);
                $oNew->oxobject2category__oxcatnid   = new oxField($sCategoryID);
                $oNew->oxobject2category__oxtime     = new oxField(time());
                $oNew->save();
            }

            $this->resetArtSeoUrl( $aArticles );
            $this->resetCounter( "catArticle", $sCategoryID );
        }
    }

    /**
     * Removes article from category
     *
     * @return null
     */
    public function removearticle()
    {
        $aArticles = $this->_getActionIds( 'oxarticles.oxid' );
        $sCategoryID = oxConfig::getParameter( 'oxid');
        $sShopID     = $this->getConfig()->getShopId();
        $oDb = oxDb::getDb();

        // adding
        if ( oxConfig::getParameter( 'all' ) ) {

            $sO2CView = getViewName('oxobject2category');
            $sQ = $this->_addFilter( "delete $sO2CView.* ".$this->_getQuery() );
            $oDb->Execute( $sQ );

        } elseif ( is_array( $aArticles ) && count( $aArticles ) ) {
            if ( $this->getConfig()->getConfigParam( 'blVariantsSelection' ) ) {
                $sQ = "delete from oxobject2category where oxcatnid=".$oDb->quote( $sCategoryID )." and oxobjectid in ( select oxid from oxarticles where oxparentid in (" . implode( ", ", oxDb::getInstance()->quoteArray( $aArticles ) ) . ") )";
                $oDb->execute( $sQ );
            }
            $sQ = "delete from oxobject2category where oxcatnid=".$oDb->quote( $sCategoryID )." and oxobjectid in ( " . implode( ", ", oxDb::getInstance()->quoteArray( $aArticles ) ) . ")";
            $oDb->execute( $sQ );

        }

        $this->resetArtSeoUrl( $sAdd );
        $this->resetCounter( "catArticle", $sCategoryID );
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
