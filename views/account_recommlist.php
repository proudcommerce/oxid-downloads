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
 * $Id: account_recommlist.php 22274 2009-09-11 07:01:32Z arvydas $
 */

/**
 * Current user recommlist manager.
 * When user is logged in in this manager window he can modify his
 * own recommlists status - remove articles from list or store
 * them to shopping basket, view detail information.
 */
class Account_Recommlist extends Account
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'account_recommlist.tpl';

    /**
     * Is recomendation list entry was saved this marker gets value TRUE. Default is FALSE
     *
     * @var bool
     */
    protected $_blSavedEntry = false;

    /**
     * returns the recomm list articles
     *
     * @var object
     */
    protected $_oActRecommListArticles = null;

    /**
     * returns the recomm list article. Whether the variable is empty, it list nothing
     *
     * @var array
     */
    protected $_aUserRecommLists = null;

    /**
     * returns the recomm list articles
     *
     * @var object
     */
    protected $_oActRecommList = null;

    /**
     * List items count
     *
     * @var int
     */
    protected $_iAllArtCnt = 0;

    /**
     * Page navigation
     * @var object
     */
    protected $_oPageNavigation = null;

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
        if ( !( $oUser = $this->getUser() ) ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        $this->_aViewData['recommlists']    = $this->getRecommLists();
        $this->_aViewData['itemList']       = $this->getActiveRecommItems();
        $this->_aViewData['actvrecommlist'] = $this->getActiveRecommList();

        if ( !( $this->getActiveRecommList() ) ) {
            // list of found oxrecommlists
            if ( $this->getRecommLists()->count() ) {
                $this->_iAllArtCnt = $oUser->getRecommListsCount();

                $iNrofCatArticles = (int) $this->getConfig()->getConfigParam( 'iNrofCatArticles' );
                $iNrofCatArticles = $iNrofCatArticles ? $iNrofCatArticles : 10;
                $this->_iCntPages  = round( $this->_iAllArtCnt / $iNrofCatArticles + 0.49 );
            }
        }

        $this->_aViewData['pageNavigation'] = $this->getPageNavigation();
        return $this->_sThisTemplate;
    }

    /**
     * Returns array of params => values which are used in hidden forms and as additional url params
     *
     * @return array
     */
    public function getNavigationParams()
    {
        $aParams = parent::getNavigationParams();

        // adding recommendation list id to list product urls
        if ( ( $oList = $this->getActiveRecommList() ) ) {
            $aParams['recommid'] = $oList->getId();
        }

        return $aParams;
    }

    /**
     * return recomm list from the user
     *
     * @return array
     */
    public function getRecommLists()
    {
        if ( $this->_aUserRecommLists === null ) {
            $this->_aUserRecommLists = false;
            if ( ( $oUser = $this->getUser() ) ) {
                // recommendation list
                $this->_aUserRecommLists = $oUser->getUserRecommLists();
            }
        }
        return $this->_aUserRecommLists;
    }

    /**
     * return all articles in the recomm list
     *
     * @return null
     */
    public function getActiveRecommItems()
    {
        if ( $this->_oActRecommListArticles === null ) {
            $this->_oActRecommListArticles = false;

            if ( ( $oRecommList = $this->getActiveRecommList() ) ) {
                $oItemList = $oRecommList->getArticles();

                if ( $oItemList->count() ) {
                    foreach ( $oItemList as $oItem ) {
                        $oItem->text = $oRecommList->getArtDescription( $oItem->getId() );
                    }
                    $this->_oActRecommListArticles = $oItemList;
                }
            }
        }

        return $this->_oActRecommListArticles;
    }

    /**
     * return the active entrys
     *
     * @return null
     */
    public function getActiveRecommList()
    {
        if ( $this->_oActRecommList === null ) {
            $this->_oActRecommList = false;

            if ( ( $oUser = $this->getUser() ) &&
                 ( $sRecommId = oxConfig::getParameter( 'recommid' ) )) {

                $oRecommList = oxNew( 'oxrecommlist' );
                if ( ( $oRecommList->load( $sRecommId ) ) && $oUser->getId() === $oRecommList->oxrecommlists__oxuserid->value ) {
                    $this->_oActRecommList = $oRecommList;
                }
            }
        }

        return $this->_oActRecommList;
    }

    public function setActiveRecommList( $oRecommList )
    {
        $this->_oActRecommList = $oRecommList;
    }

    /**
     * add new recommlist
     *
     * @return null
     */
    public function saveRecommList()
    {
        if ( ( $oUser = $this->getUser() ) ) {
            if ( !( $oRecommList = $this->getActiveRecommList() ) ) {
                $oRecommList = oxNew( 'oxrecommlist' );
                $oRecommList->oxrecommlists__oxuserid = new oxField( $oUser->getId());
                $oRecommList->oxrecommlists__oxshopid = new oxField( $this->getConfig()->getShopId() );
            }

            $sTitle  = trim( ( string ) oxConfig::getParameter( 'recomm_title', 1 ) );
            $sAuthor = trim( ( string ) oxConfig::getParameter( 'recomm_author', 1 ) );
            $sText   = trim( ( string ) oxConfig::getParameter( 'recomm_desc', 1 ) );

            $oRecommList->oxrecommlists__oxtitle  = new oxField( $sTitle, oxField::T_RAW );
            $oRecommList->oxrecommlists__oxauthor = new oxField( $sAuthor, oxField::T_RAW );
            $oRecommList->oxrecommlists__oxdesc   = new oxField( $sText, oxField::T_RAW );

            try {
                // marking entry as saved
                $this->_blSavedEntry = (bool) $oRecommList->save();
            } catch (oxObjectException $oEx ) {
                //add to display at specific position
                oxUtilsView::getInstance()->addErrorToDisplay( $oEx, false, true, 'user' );
            }
        }
    }

    /**
     * List entry saving status getter. Saving status is
     *
     * @return bool
     */
    public function isSavedList()
    {
        return $this->_blSavedEntry;
    }

    /**
     * Delete recommlist
     *
     * @return null
     */
    public function editList()
    {
        // deleting on demand
        if ( ( $sAction = oxConfig::getParameter( 'deleteList' ) ) &&
             ( $oRecommList = $this->getActiveRecommList() ) ) {
            $oRecommList->delete();
            $this->setActiveRecommList( false );
        }
    }

    /**
     * Delete recommlist
     *
     * @return null
     */
    public function removeArticle()
    {
        if ( ( $sArtId = oxConfig::getParameter( 'aid' ) ) &&
             ( $oRecommList = $this->getActiveRecommList() ) ) {
            $oRecommList->removeArticle( $sArtId );
        }
    }

    /**
     * Template variable getter. Returns page navigation
     *
     * @return object
     */
    public function getPageNavigation()
    {
        if ( $this->_oPageNavigation === null ) {
            $this->_oPageNavigation = false;
            if ( !$this->getActiveRecommlist() ) {
                $this->_oPageNavigation = $this->generatePageNavigation();
            }
        }
        return $this->_oPageNavigation;
    }
}
