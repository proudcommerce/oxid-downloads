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
 * @package   views
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxcmp_basket.php 26611 2010-03-17 12:02:26Z sarunas $
 */

/**
 * Main shopping basket manager. Arranges shopping basket
 * contents, updates amounts, prices, taxes etc.
 * @subpackage oxcmp
 */
class oxcmp_basket extends oxView
{

    /**
     * Marking object as component
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Parameters which are kept when redirecting after user
     * puts something to basket
     * @var array
     */
    public $aRedirectParams = array( 'cnid',        // category id
                                     'mnid',        // manufacturer id
                                     'anid',        // active article id
                                     'tpl',         // spec. template
                                     'listtype',    // list type
                                     'searchcnid',  // search category
                                     'searchvendor',// search vendor
                                     'searchmanufacturer',// search manufacturer
                                     'searchtag',   // search tag
                                     'searchrecomm',// search recomendation
                                     'recommid'     // recomm. list id
                                    );

    /**
     * Loads basket ($oBasket = $mySession->getBasket()), calls oBasket->calculateBasket,
     * executes parent::render() and returns basket object.
     *
     * @return object   $oBasket    basket object
     */
    public function render()
    {
        // recalculating
        if ( $oBasket = $this->getSession()->getBasket() ) {
            $oBasket->calculateBasket( false );
        }

        parent::render();

        return $oBasket;
    }

    /**
     * Basket content update controller.
     * Before adding article - check if client is not a search engine. If
     * yes - exits method by returning false. If no - executes
     * oxcmp_basket::_addItems() and puts article to basket.
     * Returns position where to redirect user browser.
     *
     * @param string $sProductId Product ID (default null)
     * @param double $dAmount    Product amount (default null)
     * @param array  $aSel       (default null)
     * @param array  $aPersParam (default null)
     * @param bool   $blOverride If true means increase amount of chosen article (default false)
     *
     * @return mixed
     */
    public function tobasket( $sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = false )
    {
        // adding to basket is not allowed ?
        $myConfig = $this->getConfig();
        if ( oxUtils::getInstance()->isSearchEngine() ) {
            return;
        }

        // adding articles
        if ( $aProducts = $this->_getItems( $sProductId, $dAmount, $aSel, $aPersParam, $blOverride ) ) {

            $this->_setLastCall( 'tobasket', $aProducts, $this->getSession()->getBasket()->getBasketSummary() );
            $oBasketItem = $this->_addItems( $aProducts );

            // new basket item marker
            if ( $oBasketItem && $myConfig->getConfigParam( 'iNewBasketItemMessage' ) != 0 ) {
                $oNewItem = new OxstdClass();
                $oNewItem->sTitle  = $oBasketItem->getTitle();
                $oNewItem->sId     = $oBasketItem->getProductId();
                $oNewItem->dAmount = $oBasketItem->getAmount();
                $oNewItem->dBundledAmount = $oBasketItem->getdBundledAmount();

                // passing article
                oxSession::setVar( '_newitem', $oNewItem );
            }
        }

        if ( $this->getConfig()->getConfigParam( 'iNewBasketItemMessage' ) == 3 ) {
            // redirect to basket
            return $this->_getRedirectUrl();
        }
    }

    /**
     * Similar to tobasket, except that as product id "bindex" parameter is (can be) taken
     *
     * @param string $sProductId Product ID (default null)
     * @param double $dAmount    Product amount (default null)
     * @param array  $aSel       (default null)
     * @param array  $aPersParam (default null)
     * @param bool   $blOverride If true means increase amount of chosen article (default false)
     *
     * @return mixed
     */
    public function changebasket( $sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = true )
    {
        // adding to basket is not allowed ?
        if ( oxUtils::getInstance()->isSearchEngine() ) {
            return;
        }

        // fetching item ID
        if (!$sProductId) {
            $sBasketItemId = oxConfig::getParameter( 'bindex' );

            if ( $sBasketItemId ) {
                $oBasket = $this->getSession()->getBasket();
                //take params
                $aBasketContents = $oBasket->getContents();
                $sProductId = isset( $aBasketContents[$sBasketItemId] )?$aBasketContents[$sBasketItemId]->getProductId():null;
            } else {
                $sProductId = oxConfig::getParameter( 'aid' );
            }
        }

        // fetching other needed info
        $dAmount = isset( $dAmount )?$dAmount:oxConfig::getParameter( 'am' );
        $aSel = isset( $aSel )?$aSel:oxConfig::getParameter( 'sel' );
        $aPersParam = $aPersParam?$aPersParam:oxConfig::getParameter( 'persparam' );

        // adding articles
        if ( $aProducts = $this->_getItems( $sProductId, $dAmount, $aSel, $aPersParam, $blOverride ) ) {

            // information that last call was changebasket
            $oBasket = $this->getSession()->getBasket();
            $oBasket->onUpdate();
            $this->_setLastCall( 'changebasket', $aProducts, $oBasket->getBasketSummary() );

            $oBasketItem = $this->_addItems( $aProducts );
        }

    }

    /**
     * Adds articles to shopping basket or updates amount.
     * and special handling, stores wishid so that we can substract them
     * properly from wishlist. Puts article to basket (oxcmp_basket::tobasket())
     * and sets delivery address of user whose wish list article was loaded.
     *
     * @param string $sProductId Product ID (default null)
     * @param double $dAmount    Product amount (default null)
     * @param array  $aSel       (default null)
     * @param array  $aPersParam (default null)
     * @param bool   $blOverride If true means increase amount of chosen article (default false)
     *
     * @return mixed
     */
    public function wl_tobasket( $sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = false )
    {
        // adding to basket is not allowed ?
        if ( oxUtils::getInstance()->isSearchEngine() ) {
            return;
        }

        // collecting info
        $sProductId = $sProductId?$sProductId:oxConfig::getParameter( 'aid' );
        $dAmount    = $dAmount?$dAmount:oxConfig::getParameter( 'am' );
        $aSel       = $aSel?$aSel:oxConfig::getParameter( 'sel' );
        $aPersParam = $aPersParam?$aPersParam:oxConfig::getParameter( 'persparam' );

        // adding articles
        if ( $aProducts = $this->_getItems( $sProductId, $dAmount, $aSel, $aPersParam, $blOverride ) ) {

            $oBasketItem = $this->_addItems( $aProducts );
            $oBasketItem->setWishArticleId( oxConfig::getParameter( 'anid' ) );

            // information that last call was tobasket
            $this->_setLastCall( 'tobasket', $aProducts, $this->getSession()->getBasket()->getBasketSummary() );

            // fetching user info
            $oUser = $this->getUser();
            $oWishUser = oxNew( 'oxuser' );
            $sUserId   = oxConfig::getParameter( 'owishid' )?oxConfig::getParameter( 'owishid' ):oxConfig::getParameter( 'wishid' );
            if ( $oWishUser->load( $sUserId ) && $oUser ) {
                // checking if this user isn't assigned to delivery address list and if not - adding him this address
                $oUser->addUserAddress( $oWishUser );
                $oBasketItem->setWishId( $sUserId );
            }
        }

        return $this->_getRedirectUrl();
    }

    /**
     * Formats and returns redirect URL where shop must be redirected after
     * storing something to basket
     *
     * @return string   $sClass.$sPosition  redirection URL
     */
    protected function _getRedirectUrl()
    {

        // active class
        $sClass = oxConfig::getParameter( 'cl' );
        $sClass = $sClass?$sClass.'?':'start?';
        $sPosition = '';

        // setting redirect parameters
        foreach ( $this->aRedirectParams as $sParamName ) {
            $sParamVal  = oxConfig::getParameter( $sParamName );
            $sPosition .= $sParamVal?$sParamName.'='.$sParamVal.'&':'';
        }

        // special treatment
        // search param
        $sParam     = rawurlencode( oxConfig::getParameter( 'searchparam', true ) );
        $sPosition .= $sParam?'searchparam='.$sParam.'&':'';

        // current page number
        $iPageNr    = (int) oxConfig::getParameter( 'pgNr' );
        $sPosition .= ( $iPageNr > 0 )?'pgNr='.$iPageNr.'&':'';

        // reload and backbutton blocker
        if ( $this->getConfig()->getConfigParam( 'iNewBasketItemMessage' ) == 3 ) {

            // saving return to shop link to session
            oxSession::setVar( '_backtoshop', $sClass.$sPosition );

            // redirecting to basket
            $sClass = 'basket?';
        }

        return $sClass.$sPosition;
    }

    /**
     * Collects and returns array of items to add to basket. Product info is taken not only from
     * given parameters, but additionally from request 'aproducts' parameter
     *
     * @param string $sProductId product ID
     * @param double $dAmount    product amount
     * @param array  $aSel       product select lists
     * @param array  $aPersParam product persistent parameters
     * @param bool   $blOverride amount override status
     *
     * @return mixed
     */
    protected function _getItems( $sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = false )
    {
        // collecting items to add
        $aProducts = oxConfig::getParameter( 'aproducts' );

        // collecting specified item
        $sProductId = $sProductId?$sProductId:oxConfig::getParameter( 'aid' );
        if ( $sProductId ) {

            // additionally fething current product info
            $dAmount = isset( $dAmount ) ? $dAmount : oxConfig::getParameter( 'am' );

            // select lists
            $aSel = isset( $aSel )?$aSel:oxConfig::getParameter( 'sel' );

            // persistent parameters
            $aPersParam = $aPersParam?$aPersParam:oxConfig::getParameter( 'persparam' );

            $sBasketItemId = oxConfig::getParameter( 'bindex' );

            $aProducts[$sProductId] = array( 'am' => $dAmount,
                                             'sel' => $aSel,
                                             'persparam' => $aPersParam,
                                             'override'  => $blOverride,
                                             'basketitemid' => $sBasketItemId
                                           );
        }

        if ( is_array( $aProducts ) && count( $aProducts ) ) {

            if (oxConfig::getParameter( 'removeBtn' ) !== null) {
                //setting amount to 0 if removing article from basket
                foreach ( $aProducts as $sProductId => $aProduct ) {
                    if ( isset($aProduct['remove']) && $aProduct['remove']) {
                        $aProducts[$sProductId]['am'] = 0;
                    } else {
                        unset ($aProducts[$sProductId]);
                    }
                }
            }

            return $aProducts;
        }

        return false;
    }

    /**
     * Adds all articles user wants to add to basket. Returns
     * last added to basket item.
     *
     * @param array $aProducts products to add array
     *
     * @return  object  $oBasketItem    last added basket item
     */
    protected function _addItems ( $aProducts )
    {
        $oActView   = $this->getConfig()->getActiveView();
        $sErrorDest = $oActView->getErrorDestination();

        $oBasket = $this->getSession()->getBasket();
        foreach ( $aProducts as $sProductId => $aProductInfo ) {

            // collecting input

            $sProductId = isset( $aProductInfo['aid'] ) ? $aProductInfo['aid'] : $sProductId;

            $dAmount = isset( $aProductInfo['am'] )?$aProductInfo['am']:0;
            $aSelList = isset( $aProductInfo['sel'] )?$aProductInfo['sel']:null;
            $aPersParam = isset( $aProductInfo['persparam'] )?$aProductInfo['persparam']:null;
            $blOverride = isset( $aProductInfo['override'] )?$aProductInfo['override']:null;
            $blIsBundle = isset( $aProductInfo['bundle'] )?true:false;
            $sOldBasketItemId = isset( $aProductInfo['basketitemid'] )?$aProductInfo['basketitemid']:null;

            try {
                $oBasketItem = $oBasket->addToBasket( $sProductId, $dAmount, $aSelList, $aPersParam, $blOverride, $blIsBundle, $sOldBasketItemId );
            } catch ( oxOutOfStockException $oEx ) {
                $oEx->setDestination( $sErrorDest );
                // #950 Change error destination to basket popup
                if ( !$sErrorDest  && $this->getConfig()->getConfigParam( 'iNewBasketItemMessage') == 2) {
                    $sErrorDest = 'popup';
                }
                oxUtilsView::getInstance()->addErrorToDisplay( $oEx, false, (bool) $sErrorDest, $sErrorDest );
            } catch ( oxArticleInputException $oEx ) {
                //add to display at specific position
                $oEx->setDestination( $sErrorDest );
                oxUtilsView::getInstance()->addErrorToDisplay( $oEx, false, (bool) $sErrorDest, $sErrorDest );
            } catch ( oxNoArticleException $oEx ) {
                //ignored, best solution F ?
            }
        }

        return $oBasketItem;
    }

    /**
     * Setting last call data to session (data used by econda)
     *
     * @param string $sCallName    name of action ('tobasket', 'changebasket')
     * @param array  $aProductInfo data which comes from request when you press button "to basket"
     * @param array  $aBasketInfo  array returned by oxbasket::getBasketSummary()
     *
     * @return null
     */
    protected function _setLastCall( $sCallName, $aProductInfo, $aBasketInfo )
    {
        $aProducts = array();

        // collecting amounts info
        foreach ( $aProductInfo as $sProdId => $aProdData ) {
            $aProducts[$sProdId] = $aProdData;
            // setting previous amount
            $aProducts[$sProdId]['oldam'] = isset( $aBasketInfo->aArticles[$aProdData['aid']] ) ? $aBasketInfo->aArticles[$aProdData['aid']] : 0;
        }

        oxSession::setVar( 'aLastcall', array( $sCallName => $aProducts ) );
    }
}
