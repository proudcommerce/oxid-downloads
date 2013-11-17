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
 * $Id: account_wishlist.php 17315 2009-03-17 16:18:58Z arvydas $
 */

/**
 * Current user wishlist manager.
 * When user is logged in in this manager window he can modify his
 * own wishlist status - remove articles from wishlist or store
 * them to shopping basket, view detail information. Additionally
 * user can view wishlist of some other user by entering users
 * login name in special field. OXID eShop -> MY ACCOUNT
 *  -> Newsletter.
 */
class Account_Wishlist extends Account
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'account_wishlist.tpl';

    /**
     * If true, list will be shown, if false - will not
     *
     * @var bool
     */
    protected $_blShowSuggest = null;

    /**
     * Wheter the var is false the wishlist will be shown
     *
     * @var wishlist
     */
    protected $_oWishList = null;

    /**
     * list the wishlist items
     *
     * @var wishlist
     */
    protected $_aRecommList = null;

    /**
     * Wheter the var is false the productlist will not be list
     *
     * @var wishlist
     */
    protected $_oEditval = null;

    /**
     * If sending failed give false back
     *
     * @var integer / bool
     */
    protected $_iSendWishList = null;

    /**
     * Wishlist search param
     *
     * @var string
     */
    protected $_sSearchParam = null;

    /**
     * List of users which were found according to search condition
     *
     * @var oxlist
     */
    protected $_oWishListUsers = false;

    /**
     * Wishlist email sending status
     *
     * @var bool
     */
    protected $_blEmailSent = false;

    /**
     * User entered values for sending email
     *
     * @var array
     */
    protected $_aEditValues = false;

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * If user is logged in loads his wishlist articles (articles may be accessed by
     * oxuser::GetBasket()), loads similar articles (is available) for
     * the last article in list loaded by oxarticle::GetSimilarProducts() and
     * returns name of template to render account_wishlist::_sThisTemplate
     *
     * Template variables:
     * <b>blshowsuggest</b>, <b>wishlist</b>
     *
     * @return  string  $_sThisTemplate current template file name
     */
    public function render()
    {
        parent::render();

        // is logged in ?
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }
        // for older templates
        $this->_aViewData['blshowsuggest'] = $this->showSuggest();
        $this->_aViewData['similarrecommlist'] = $this->getSimilarRecommLists();

        $this->_aViewData['wishlist'] = $this->getWishList();
        if ( $this->getWishList() ) {
            $this->_aViewData['wishlist']->aList = $this->getWishProductList();
        }

        return $this->_sThisTemplate;
    }

    /**
     * check if the wishlist is allowed
     *
     * @return bool
     */
    public function showSuggest()
    {
        if ( $this->_blShowSuggest === null ) {
            $this->_blShowSuggest = ( bool ) oxConfig::getParameter( 'blshowsuggest' );
        }
        return $this->_blShowSuggest;
    }

    /**
     * Show the Wishlist
     *
     * @return oxuserbasket | bool
     */
    public function getWishList()
    {
        if ( $this->_oWishList === null ) {
            $this->_oWishList = false;

            if ( $oUser = $this->getUser() ) {
                $oWishList = $oUser->getBasket( 'wishlist' );
                if ( $oWishListProducts = $oWishList->getArticles() ) {
                    // wish list
                    $this->_aWishProductList = $oWishListProducts;
                    $this->_oWishList = $oWishList;
                }
            }
        }

        return $this->_oWishList;
    }

    /**
     * Returns array of producst assigned to user wish list
     *
     * @return array | bool
     */
    public function getWishProductList()
    {
        if ( $this->_aWishProductList === null ) {
            $this->_aWishProductList = false;

            if ( $oWishList = $this->getWishList() ) {
                return $this->_aWishProductList;
            }
        }
        return $this->_aWishProductList;
    }

    /**
     * Template variable getter. Returns an string if there is something in the list
     *
     * @return array
     */
    public function getSimilarRecommLists()
    {
        // recomm list
        if ( $this->_aRecommList === null ) {

            // just ensuring that next call will skip this check
            $this->_aRecommList = false;

            // loading recomm list
            $aWishProdList = $this->getWishProductList();
            if ( is_array( $aWishProdList ) && ( $oSimilarProd = current( $aWishProdList ) ) ) {
                $oRecommList = oxNew('oxrecommlist');
                $this->_aRecommList = $oRecommList->getRecommListsByIds( array( $oSimilarProd->getId() ) );
            }
        }

        return $this->_aRecommList;
    }

    /**
     * Sends wishlist mail to recipient. On errors returns false.
     *
     * Template variables:
     * <b>editval</b>, <b>error</b>, <b>success</b>
     *
     * @return bool
     */
    public function sendWishList()
    {
        $aParams = oxConfig::getParameter( 'editval' );

        if ( !is_array( $aParams ) ) {
            return;
        }

        //setting pointer to first element
        reset( $aParams );
        $oParams = new OxstdClass();
        while ( list( $sName, $sValue ) = each( $aParams ) ) {
            $oParams->$sName = $sValue;
        }
        $this->_aEditValues = $oParams;

        if ( !$aParams['rec_name'] || !$aParams['rec_email'] ) {
            oxUtilsView::getInstance()->addErrorToDisplay('ACCOUNT_WISHLIST_ERRCOMLETEFIELDSCORRECTLY', false, true );
            return;
        }

        if ( $oUser = $this->getUser() ) {

            $oParams->send_email = $oUser->oxuser__oxusername->value;
            $oParams->send_name  = $oUser->oxuser__oxfname->value.' '.$oUser->oxuser__oxlname->value;
            $oParams->send_id    = $oUser->getId();

            $oEmail = oxNew( 'oxemail' );
            if ( !$oEmail->sendWishlistMail( $oParams ) ) {
                oxUtilsView::getInstance()->addErrorToDisplay( 'ACCOUNT_WISHLIST_ERRWRONGEMAIL', false, true );
                return;
            } else {
                $this->_blEmailSent = true;
            }
        }

        $this->_aViewData['success'] = $this->isWishListEmailSent();
        $this->_aViewData['editval'] = $this->getEnteredData();
    }

    /**
     * If email was sent.
     *
     * @return bool
     */
    public function isWishListEmailSent()
    {
        return $this->_blEmailSent;
    }

    /**
     * Terurns user entered values for sending email.
     *
     * @return array
     */
    public function getEnteredData()
    {
        return $this->_aEditValues;
    }

    /**
     * Changes wishlist status - public/non public. Returns false on
     * error (if user is not logged in).
     *
     * @return bool
     */
    public function togglePublic()
    {
        if ( $oUser = $this->getUser() ) {

            $blPublic = (int) oxConfig::getParameter( 'blpublic' );
            $blPublic = ( $blPublic == 1 )?$blPublic:0;

            $oBasket = $oUser->getBasket( 'wishlist' );
            $oBasket->oxuserbaskets__oxpublic = new oxField($blPublic);
            $oBasket->save();
        }
    }

    /**
     * Searches for wishlist of another user. Returns false if no
     * searching conditions set (no login name defined).
     *
     * Template variables:
     * <b>wish_result</b>, <b>search</b>
     *
     * @return bool
     */
    public function searchForWishList()
    {
        if ( $sSearch = oxConfig::getParameter( 'search' ) ) {

            // search for baskets
            $oUserList = oxNew( 'oxuserlist' );
            $oUserList->loadWishlistUsers( $sSearch );
            if ( $oUserList->count() ) {
                $this->_oWishListUsers = $oUserList;
            }

            $this->_sSearchParam = $sSearch;
        }

        $this->_aViewData['search'] = $this->getWishListSearchParam();
        $this->_aViewData['wish_result'] = $this->getWishListUsers();
    }

    /**
     * Returns a list of users which were found according to search condition.
     * If no users were found - false is returned
     *
     * @return oxlist | bool
     */
    public function getWishListUsers()
    {
        return $this->_oWishListUsers;
    }

    /**
     * Returns wish list search parameter
     *
     * @return string
     */
    public function getWishListSearchParam()
    {
        return $this->_sSearchParam;
    }
}
