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
 * $Id: recommlist.php 18934 2009-05-11 13:53:24Z vilma $
 */

/**
 * Article suggestion page.
 * Collects some article base information, sets default recomendation text,
 * sends suggestion mail to user.
 */
class RecommList extends oxUBase
{
    /**
     * List type
     * @var string
     */
    protected $_sListType = 'recommlist';

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'recommlist.tpl';

    /**
     * Active recommendation's list id
     * @var string
     */
    protected $_sRecommId = null;

    /**
     * Active recommendation's list
     * @var object
     */
    protected $_oActiveRecommList = null;

    /**
     * Active recommlist's items
     * @var object
     */
    protected $_oActiveRecommItems = null;

    /**
     * Other recommendations list
     * @var oxrecommlist
     */
    protected $_oOtherRecommList = null;

    /**
     * Recommlist reviews
     * @var array
     */
    protected $_aReviews = null;

    /**
     * Is review active
     * @var bool
     */
    protected $_blReviewActive = null;

    /**
     * Can user rate
     * @var bool
     */
    protected $_blRate = null;

    /**
     * Rating value
     * @var double
     */
    protected $_dRatingValue = null;

    /**
     * Ratng count
     * @var integer
     */
    protected $_iRatingCnt = null;

    /**
     * Searched recommendations list
     * @var object
     */
    protected $_oSearchRecommLists = null;

    /**
     * Search string
     * @var string
     */
    protected $_sSearch = null;

    /**
     * Template location
     *
     * @var string
     */
    protected $_sTplLocation = null;

    /**
     * Page navigation
     * @var object
     */
    protected $_oPageNavigation = null;

    /**
     * Collects current view data, return current template file name
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        $myConfig = $this->getConfig();

        $this->_iAllArtCnt = 0;
        $this->_aViewData['actvrecommlist'] = $this->getActiveRecommList();
        $this->_aViewData['itemList'] = $this->getActiveRecommItems();

        // loading other recommlists
        $this->_aViewData['similarrecommlist'] = $this->getSimilarRecommLists();

        // Loads rating parameters
        $this->_aViewData['rate']      = $this->canRate();
        $this->_aViewData['rating']    = $this->getRatingValue();
        $this->_aViewData['ratingcnt'] = $this->getRatingCount();
        $this->_aViewData['reviews']   = $this->getReviews();

        // list of found oxrecommlists
        $this->_aViewData['recommlists'] = $this->getRecommLists();
        if ( $sOxid = $this->getRecommId()) {
            $oActiveRecommList = $this->getActiveRecommList();
            $oList = $this->getActiveRecommItems();
            if ( $oList && $oList->count()) {
                    $this->_iAllArtCnt = $oActiveRecommList->getArtCount();
            }

            if (in_array('oxrss_recommlistarts', $myConfig->getConfigParam( 'aRssSelected' ))) {
                $oRss = oxNew('oxrssfeed');
                $this->addRssFeed($oRss->getRecommListArticlesTitle($oActiveRecommList), $oRss->getRecommListArticlesUrl($this->_oActiveRecommList), 'recommlistarts');
            }

        } else {
            if ( ($oList = $this->getRecommLists()) && $oList->count() ) {
                $oRecommList = oxNew( 'oxrecommlist' );
                $this->_iAllArtCnt = $oRecommList->getSearchRecommListCount( $this->getRecommSearch() );
            }
        }

        if ( !$oList = $this->getActiveRecommItems() ) {
            $oList = $this->getRecommLists();
        }

        if ( $oList && $oList->count() ) {
            $iNrofCatArticles = (int) $this->getConfig()->getConfigParam( 'iNrofCatArticles' );
            $iNrofCatArticles = $iNrofCatArticles ? $iNrofCatArticles : 10;
            $this->_iCntPages  = round( $this->_iAllArtCnt / $iNrofCatArticles + 0.49 );
        }

        $this->_aViewData['pageNavigation'] = $this->getPageNavigation();
        $this->_aViewData['template_location']   = $this->getTemplateLocation();
        $this->_aViewData['searchrecomm']        = $this->getRecommSearch();
        $this->_aViewData['searchrecommforhtml'] = $this->getSearchForHtml();

        $this->_aViewData['loginformanchor'] = $this->getLoginFormAnchor();

        // processing list articles
        $this->_processListArticles();

        return $this->_sThisTemplate;
    }

    /**
     * Iterates through list articles and performs list view specific tasks
     *
     * @return null
     */
    protected function _processListArticles()
    {
        $sAddParams = $this->getAddUrlParams();
        if ( $sAddParams && $this->_oActiveRecommItems ) {
            foreach ( $this->_oActiveRecommItems as $oArticle ) {
                $oArticle->appendLink( $sAddParams );
            }
        }
    }

    /**
     * Returns additional URL paramerets which must be added to list products urls
     *
     * @return string
     */
    public function getAddUrlParams()
    {
        $sAddParams  = parent::getAddUrlParams();
        $sAddParams .= ($sAddParams?'&amp;':'') . "listtype={$this->_sListType}";

        if ( $sParam = rawurlencode( oxConfig::getParameter( 'searchrecomm', true ) ) ) {
            $sAddParams .= "&amp;searchrecomm={$sParam}";
        }

        if ( $oRecommList = $this->getActRecommList() ) {
            $sAddParams .= '&amp;recommid='.$oRecommList->getId();
        }

        return $sAddParams;
    }

    /**
     * Saves user ratings and review text (oxreview object)
     *
     * @return null
     */
    public function saveReview()
    {
        $sReviewText = trim( ( string ) oxConfig::getParameter( 'rvw_txt' , true ) );
        $dRating     = oxConfig::getParameter( 'recommlistrating' );
        if ( $dRating < 0 || $dRating > 5 ) {
            $dRating = null;
        }

        $sRLId  = oxConfig::getParameter( 'recommid' );
        $sUserId = oxSession::getVar( 'usr' );

        $oRecommList = oxNew('oxrecommlist');
        if (!$oRecommList->load($sRLId)) {
            return;
        }

        //save rating
        if ( $dRating && $sUserId ) {
            $oRating = oxNew( 'oxrating' );
            $blRate = $oRating->allowRating( $sUserId, 'oxrecommlist', $oRecommList->getId());
            if ( $blRate) {
                $oRating->oxratings__oxuserid   = new oxField($sUserId);
                $oRating->oxratings__oxtype     = new oxField('oxrecommlist', oxField::T_RAW);
                $oRating->oxratings__oxobjectid = new oxField($sRLId);
                $oRating->oxratings__oxrating   = new oxField($dRating);
                $oRating->save();
                $oRecommList->addToRatingAverage( $dRating);
            } else {
                $dRating = null;
            }
        }

        if ( $sReviewText && $sUserId ) {
            $oReview = oxNew( 'oxreview' );
            $oReview->oxreviews__oxobjectid = new oxField($sRLId);
            $oReview->oxreviews__oxtype     = new oxField('oxrecommlist', oxField::T_RAW);
            $oReview->oxreviews__oxtext     = new oxField($sReviewText, oxField::T_RAW);
            $oReview->oxreviews__oxlang     = new oxField(oxLang::getInstance()->getBaseLanguage());
            $oReview->oxreviews__oxuserid   = new oxField($sUserId);
            $oReview->oxreviews__oxrating   = new oxField(( $dRating) ? $dRating : null);
            $oReview->save();
        }
    }

    /**
     * Show login template
     *
     * @return null;
     */
    public function showLogin()
    {
        $this->_sThisTemplate = 'account_login.tpl';
        $this->_sLoginFormAnchor = "review";
    }

    /**
     * Returns array of params => values which are used in hidden forms and as additional url params
     *
     * @return array
     */
    public function getNavigationParams()
    {
        $aParams = parent::getNavigationParams();
        $aParams['recommid'] = oxConfig::getParameter( 'recommid' );

        return $aParams;
    }

    /**
     * Template variable getter. Returns active recommlists
     *
     * @return object
     */
    public function getActiveRecommList()
    {
        if ( $this->_oActiveRecommList === null ) {
            $this->_oActiveRecommList = false;
            if ( $sOxid = $this->getRecommId()) {
                $this->_oActiveRecommList = oxNew( 'oxrecommlist' );
                $this->_oActiveRecommList->load( $sOxid);
            }
        }
        return $this->_oActiveRecommList;
    }

    /**
     * Template variable getter. Returns active recommlist's items
     *
     * @return object
     */
    public function getActiveRecommItems()
    {
        if ( $this->_oActiveRecommItems === null ) {
            $this->_oActiveRecommItems = false;
            if ( $oActiveRecommList = $this->getActiveRecommList()) {
                // sets active page
                $iActPage = (int) oxConfig::getParameter( 'pgNr' );
                $iActPage = ($iActPage < 0) ? 0 : $iActPage;

                // load only lists which we show on screen
                $iNrofCatArticles = $this->getConfig()->getConfigParam( 'iNrofCatArticles' );
                $iNrofCatArticles = $iNrofCatArticles ? $iNrofCatArticles : 10;

                $oList = $oActiveRecommList->getArticles($iNrofCatArticles * $iActPage, $iNrofCatArticles);

                if ( $oList && $oList->count() ) {
                    foreach ( $oList as $oItem) {
                        $oItem->text = $oActiveRecommList->getArtDescription( $oItem->getId() );
                    }
                    $this->_oActiveRecommItems = $oList;
                }
            }
        }
        return $this->_oActiveRecommItems;
    }

    /**
     * Template variable getter. Returns other recommlists
     *
     * @return object
     */
    public function getSimilarRecommLists()
    {
        if ( $this->_oOtherRecommList === null ) {
            $this->_oOtherRecommList = false;
            if ( $oActiveRecommList = $this->getActiveRecommList() ) {
                if ( $oList = $this->getActiveRecommItems() ) {
                    $oRecommLists  = $oActiveRecommList->getRecommListsByIds( $oList->arrayKeys());
                    //do not show the same list
                    unset($oRecommLists[$this->getRecommId()]);
                    $this->_oOtherRecommList = $oRecommLists;
                }
            }
        }
        return $this->_oOtherRecommList;
    }

    /**
     * Template variable getter. Returns recommlist id
     *
     * @return string
     */
    public function getRecommId()
    {
        if ( $this->_sRecommId === null ) {
            $this->_sRecommId = false;
            if ( $sOxid = oxConfig::getParameter( 'recommid' )) {
                $this->_sRecommId = $sOxid;
            }
        }
        return $this->_sRecommId;
    }

    /**
     * Template variable getter. Returns recommlist's reviews
     *
     * @return array
     */
    public function getReviews()
    {
        if ( $this->_aReviews === null ) {
            $this->_aReviews = false;
            if ( $this->isReviewActive() ) {
                if ( $oActiveRecommList = $this->getActiveRecommList() ) {
                    $this->_aReviews = $oActiveRecommList->getReviews();
                }
            }
        }
        return $this->_aReviews;
    }

    /**
     * Template variable getter. Returns if review modul is on
     *
     * @return bool
     */
    public function isReviewActive()
    {
        $myConfig  = $this->getConfig();
        if ( $this->_blReviewActive === null ) {
            $this->_blReviewActive = false;
            if ( $myConfig->getConfigParam( 'bl_perfLoadReviews' ) ) {
                $this->_blReviewActive = true;
            }
        }
        return $this->_blReviewActive;
    }

    /**
     * Template variable getter. Returns if user can rate
     *
     * @return bool
     */
    public function canRate()
    {
        if ( $this->_blRate === null ) {
            $this->_blRate = false;
            if ( $this->isReviewActive() ) {
                if ( $oActiveRecommList = $this->getActiveRecommList() ) {
                    $oRating = oxNew( 'oxrating' );
                    $this->_blRate = $oRating->allowRating( oxSession::getVar( 'usr' ), 'oxrecommlist', $oActiveRecommList->getId());
                }
            }
        }
        return $this->_blRate;
    }

    /**
     * Template variable getter. Returns rating value
     *
     * @return double
     */
    public function getRatingValue()
    {
        if ( $this->_dRatingValue === null ) {
            $this->_dRatingValue = false;
            if ( $this->isReviewActive() ) {
                if ( $oActiveRecommList = $this->getActiveRecommList() ) {
                    $this->_dRatingValue = round( $oActiveRecommList->oxrecommlists__oxrating->value, 1);
                }
            }
        }
        return $this->_dRatingValue;
    }

    /**
     * Template variable getter. Returns rating count
     *
     * @return integer
     */
    public function getRatingCount()
    {
        if ( $this->_iRatingCnt === null ) {
            $this->_iRatingCnt = false;
            if ( $this->isReviewActive() ) {
                if ( $oActiveRecommList = $this->getActiveRecommList() ) {
                    $this->_iRatingCnt = $oActiveRecommList->oxrecommlists__oxratingcnt->value;
                }
            }
        }
        return $this->_iRatingCnt;
    }

    /**
     * Template variable getter. Returns searched recommlist
     *
     * @return object
     */
    public function getRecommLists()
    {
        if ( $this->_oSearchRecommLists === null ) {
            $this->_oSearchRecommLists = array();
            if ( !$this->getRecommId()) {
                $sSearch = $this->getRecommSearch();
                // list of found oxrecommlists
                $oRecommList = oxNew( 'oxrecommlist' );
                $oList = $oRecommList->getSearchRecommLists( $sSearch );
                if ( $oList && $oList->count() ) {
                    $this->_oSearchRecommLists = $oList;
                }
            }
        }
        return $this->_oSearchRecommLists;
    }

    /**
     * Template variable getter. Returns search string
     *
     * @return string
     */
    public function getRecommSearch()
    {
        if ( $this->_sSearch === null ) {
            $this->_sSearch = false;
            if ( $sSearch = oxConfig::getParameter( 'searchrecomm', true ) ) {
                $this->_sSearch = $sSearch;
            }
        }
        return $this->_sSearch;
    }

    /**
     * Template variable getter. Returns template location
     *
     * @return string
     */
    public function getTemplateLocation()
    {
        if ( $this->_sTplLocation === null ) {
            $this->_sTplLocation = false;
            $oLang = oxLang::getInstance();
            if ( $sSearchparam = $this->getRecommSearch() ) {
                $sUrl = $this->getConfig()->getShopHomeURL();
                $sLink = "{$sUrl}cl=recommlist&amp;searchrecomm=".rawurlencode( $sSearchparam );
                $sTitle = $oLang->translateString('RECOMMLIST');
                $sTitle .= " / ".$oLang->translateString('RECOMMLIST_SEARCH').' "'.$sSearchparam.'"';
                $this->_sTplLocation = "<a href='".$sLink."'>".$sTitle."</a>";
            } else {
                $this->_sTplLocation = $oLang->translateString('RECOMMLIST');
            }
        }
        return $this->_sTplLocation;
    }

    /**
     * Template variable getter. Returns search string
     *
     * @return string
     */
    public function getSearchForHtml()
    {
        return oxConfig::getParameter( 'searchrecomm' );
    }

    /**
     * Template variable getter. Returns search string
     *
     * @return string
     */
    public function getLoginFormAnchor()
    {
        return $this->_sLoginFormAnchor;
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
            $this->_oPageNavigation = $this->generatePageNavigation();
        }
        return $this->_oPageNavigation;
    }

    /**
     * Template variable getter. Returns additional params for url
     *
     * @return string
     */
    public function getAdditionalParams()
    {
        $sAddParams = parent::getAdditionalParams();

        if ( $sOxid = $this->getRecommId() ) {
            $sAddParams .= "&amp;recommid={$sOxid}";
        }

        if ( $sSearch = $this->getRecommSearch() ) {
            $sAddParams .= "&amp;searchrecomm=". rawurlencode( $sSearch );
        }

        return $sAddParams;
    }
}
