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
 * @package views
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxcmp_utils.php 18919 2009-05-11 07:59:10Z arvydas $
 */

/**
 * Transparent shop utilities class.
 * Some specific utilities, such as fetching article info, etc. (Class may be used
 * for overriding).
 * @subpackage oxcmp
 */
class oxcmp_utils extends oxView
{
    /**
     * Marking object as component
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * If passed article ID (by URL or posted form) - loads article,
     * otherwise - loads list of action articles oxarticlelist::loadAktionArticles().
     * In this case, the last list object will be used. Loaded article info
     * is serialized and outputted to client system.
     *
     * @return null
     */
    public function getArticle()
    {
        $myConfig = $this->getConfig();
        $sId = oxConfig::getParameter( 'oxid' );
        $oProduct = null;
        if ( $sId ) {
            $oProduct = oxNewArticle( $sId );
        } else {
            if ( $myConfig->getConfigParam( 'bl_perfLoadAktion' ) ) {
                $oArtList = oxNew( 'oxarticlelist');
                $oArtList->loadAktionArticles( 'OXAFFILIATE' );
                $oProduct = $oArtList->current();
            }
        }

        if ( !$oProduct ) {
            die( 'OXID__Problem : no valid oxid !' );
        }

        $aExport = array();

        $aClassVars = get_object_vars( $oProduct );
        $oStr = getStr();

        // add all database fields
        while ( list( $sFieldName, ) = each( $aClassVars ) ) {
            if ( $oStr->strstr( $sFieldName, 'oxarticles' ) ) {
                $sName = str_replace( 'oxarticles__', '', $sFieldName );
                $aExport[$sName] = $oProduct->$sFieldName->value;
            }
        }

        $aExport['vatPercent'] = $oProduct->vatPercent;
        $aExport['netPrice']   = $oProduct->netPrice;
        $aExport['brutPrice']  = $oProduct->brutPrice;
        $aExport['vat']        = $oProduct->vat;
        $aExport['fprice']     = $oProduct->fprice;
        $aExport['ftprice']    = $oProduct->ftprice;

        $aExport['oxdetaillink']     = $oProduct->oxdetaillink;
        $aExport['oxmoredetaillink'] = $oProduct->getMoreDetailLink();
        $aExport['tobasketlink']     = $oProduct->tobasketlink;
        $aExport['thumbnaillink']    = $myConfig->getDynImageDir() ."/". $aExport['oxthumb'];

        // stop shop here
        die( serialize( $aExport ) );
    }


    /**
     * Adds/removes chosen article to/from article comparison list
     *
     * @param object $sProductId product id
     * @param double $dAmount    amount
     * @param array  $aSel       (default null)
     * @param bool   $blOverride allow override
     * @param bool   $blBundle   bundled
     *
     * @return  void
     */
    public function toCompareList( $sProductId = null, $dAmount = null, $aSel = null, $blOverride = false, $blBundle = false )
    {
        //disables adding of articles if current client is Search Engine
        if ( oxUtils::getInstance()->isSearchEngine() ) {
            return;
        }

        // #657 special treatment if we want to put on comparelist
        $myConfig = $this->getConfig();

        $blAddCompare  = oxConfig::getParameter( 'addcompare' );
        $blRemoveCompare = oxConfig::getParameter( 'removecompare' );
        $sProductId = $sProductId?$sProductId:oxConfig::getParameter( 'aid' );
        if ( ($blAddCompare || $blRemoveCompare) && $sProductId ) {

            // toggle state in session array
            $aItems = oxConfig::getParameter( 'aFiltcompproducts' );
            if ($blAddCompare && !isset( $aItems[$sProductId] ) ) {
                $aItems[$sProductId] = true;
            }

            if ($blRemoveCompare) {
                unset( $aItems[$sProductId] );
            }

            oxSession::setVar( 'aFiltcompproducts', $aItems );

            // #843C there was problem then field "blIsOnComparisonList" was not set to article object
            if ( ( $oProduct = $this->_oParent->getViewProduct() ) ) {
                if ( isset( $aItems[$oProduct->getId()] ) ) {
                    $oProduct->setOnComparisonList( true );
                } else {
                    $oProduct->setOnComparisonList( false );
                }
            }

            $aViewProds = $this->_oParent->getViewProductList();
            if ( is_array( $aViewProds ) && count( $aViewProds ) ) {
                foreach ( $aViewProds as $oProduct ) {
                    if ( isset( $aItems[$oProduct->getId()] ) ) {
                        $oProduct->setOnComparisonList( true );
                    } else {
                        $oProduct->setOnComparisonList( false );
                    }
                }
            }

            return;
        }
    }

    /**
     * If session user is set loads user noticelist (oxuser::GetBasket())
     * and adds article to it.
     *
     * @param string $sProductId Product/article ID (default null)
     * @param double $dAmount    amount of good (default null)
     * @param array  $aSel       (default null)
     *
     * @return bool
     */
    public function toNoticeList( $sProductId = null, $dAmount = null, $aSel = null)
    {
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return; // we shouldnt call this if not logged in
        }

        $sProductId = $sProductId ? $sProductId : oxConfig::getParameter( 'itmid' );
        $sProductId = $sProductId ? $sProductId : oxConfig::getParameter( 'aid' );
        $dAmount    = isset( $dAmount ) ? $dAmount : oxConfig::getParameter( 'am' );
        $aSel       = $aSel ? $aSel : oxConfig::getParameter( 'sel' );

        // processing amounts
        $dAmount = str_replace( ',', '.', $dAmount );
        if ( !$this->getConfig()->getConfigParam( 'blAllowUnevenAmounts' ) ) {
            $dAmount = round( ( string ) $dAmount );
        }

        $oBasket = $oUser->getBasket( 'noticelist' );
        $oBasket->addItemToBasket( $sProductId, abs( $dAmount ), $aSel );

        // recalculate basket count
        $oBasket->getItemCount(true);
    }

    /**
     * If session user is set loads user wishlist (oxuser::GetBasket()) and
     * adds article to it.
     *
     * @param string $sProductId Product/article ID (default null)
     * @param double $dAmount    amount of good (default null)
     * @param array  $aSel       (default null)
     *
     * @return false
     */
    public function toWishList( $sProductId = null, $dAmount = null, $aSel = null )
    {
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return; // we shouldnt call this if not logged in
        }

        $sProductId = $sProductId ? $sProductId : oxConfig::getParameter( 'itmid' );
        $sProductId = $sProductId ? $sProductId : oxConfig::getParameter( 'aid' );
        $dAmount    = isset( $dAmount ) ? $dAmount : oxConfig::getParameter( 'am' );
        $aSel       = $aSel ? $aSel : oxConfig::getParameter( 'sel' );

        // processing amounts
        $dAmount = str_replace( ',', '.', $dAmount );
        if ( !$this->getConfig()->getConfigParam( 'blAllowUnevenAmounts' ) ) {
            $dAmount = round( ( string ) $dAmount );
        }

        $oBasket = $oUser->getBasket( 'wishlist' );
        $oBasket->addItemToBasket( $sProductId, abs( $dAmount ), $aSel );

        // recalculate basket count
        $oBasket->getItemCount(true);
    }

    /**
     *  Set viewdata, call parent::render
     *
     * @return null
     */
    public function render()
    {
        $myConfig  = $this->getConfig();
        parent::render();

        if ( ( $oUser = $this->getUser() ) ) {

            // calculating user friends wishlist item count
            if ( ( $sUserId = oxConfig::getParameter( 'wishid' ) ) ) {
                $oWishUser = oxNew( 'oxuser' );
                if ( $oWishUser->load( $sUserId ) ) {
                    $this->_oParent->setWishlistName( $oWishUser->oxuser__oxfname->value );
                    // Passing to view. Left for compatibility reasons for a while. Will be removed in future
                    $this->_oParent->addTplParam( 'ShowWishlistName', $this->_oParent->getWishlistName() );
                }
            }
        }

        // add content for mainmenu
        $oContentList = oxNew( 'oxcontentlist' );
        $oContentList->loadMainMenulist();
        $this->_oParent->setMenueList( $oContentList );
        // Passing to view. Left for compatibility reasons for a while. Will be removed in future
        $this->_oParent->addTplParam( 'aMenueList', $this->_oParent->getMenueList() );

        // Performance
        if ( !$myConfig->getConfigParam( 'bl_perfLoadCompare' ) ||
            ( $myConfig->getConfigParam( 'blDisableNavBars' ) && $myConfig->getActiveView()->getIsOrderStep() ) ) {
            $this->_oParent->addTplParam('isfiltering', false );
            return;
        }

        // load nr. of items which are currently shown in comparison
        $aItems = oxConfig::getParameter( 'aFiltcompproducts' );
        if ( is_array( $aItems ) && count( $aItems ) ) {

            $oArticle = oxNew( 'oxarticle' );
            // counts how many pages
            $sAddSql  = implode( "','", array_keys( $aItems ) );
            $sSelect  = "select count(oxid) from oxarticles where oxarticles.oxid in ( '".$sAddSql."' ) ";
            $sSelect .= 'and '.$oArticle->getSqlActiveSnippet();

            $iCnt = (int) oxDb::getDb()->getOne( $sSelect );

            //add amount of compared items to view data
            $this->_oParent->setCompareItemsCnt( $iCnt );
            // Passing to view. Left for compatibility reasons for a while. Will be removed in future
            $this->_oParent->addTplParam( 'oxcmp_compare', $this->_oParent->getCompareItemsCnt() );

            // return amount of items
            return $iCnt;
        }
    }
}
