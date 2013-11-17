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
 * $Id: review.php 23584 2009-10-26 10:01:11Z arvydas $
 */

/**
 * Review of chosen article.
 * Collects article review data, saves new review to DB.
 */
class Review extends oxUBase
{
    /**
     * Review user id
     * @var string
     */
    protected $_sReviewUserId = null;

    /**
     * Active object ($_oProduct or $_oActiveRecommList)
     * @var object
     */
    protected $_oActObject = null;

    /**
     * Active recommendations list
     * @var object
     */
    protected $_oActiveRecommList = null;

    /**
     * Active recommlist's items
     * @var object
     */
    protected $_oActiveRecommItems = null;

    /**
     * Can user rate
     * @var bool
     */
    protected $_blRate = null;

    /**
     * Array of reviews
     * @var array
     */
    protected $_aReviews = null;

    /**
     * CrossSelling articlelist
     * @var object
     */
    protected $_oCrossSelling = null;

    /**
     * Similar products articlelist
     * @var object
     */
    protected $_oSimilarProducts = null;

    /**
     * Recommlist
     * @var object
     */
    protected $_oRecommList = null;

    /**
     * Review send status
     * @var bool
     */
    protected $_blReviewSendStatus = null;

    /**
     * Page navigation
     * @var object
     */
    protected $_oPageNavigation = null;

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'review.tpl';

    /**
     * Current class login template name.
     * @var string
     */
    protected $_sThisLoginTemplate = 'review_login.tpl';

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * Executes parent::init(), Loads user chosen product object (with all data).
     *
     * @return null
     */
    public function init()
    {
        $myConfig = $this->getConfig();

        parent::init();

        $this->_oActiveRecommList = $this->getActiveRecommList();
        if ( oxConfig::getParameter( 'recommid' ) && !$this->_oActiveRecommList ) {
            oxUtils::getInstance()->redirect( $myConfig->getShopHomeURL() );
        }
    }

    /**
     * Executes parent::render, loads article reviews and additional data
     * (oxarticle::getReviews(), oxarticle::getCrossSelling(),
     * oxarticle::GetSimilarProducts()). Returns name of template file to
     * render review::_sThisTemplate.
     *
     * Template variables:
     * <b>product</b>, <b>reviews</b>, <b>crossselllist</b>,
     * <b>similarlist</b>
     *
     * @return  string  current template file name
     */
    public function render()
    {
        parent::render();

        if ( !$this->_checkDirectReview($this->getReviewUserId()) ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        $sReviewUser = oxConfig::getParameter( 'reviewuser' );
        $this->_aViewData['reviewuserid'] = ( !$sReviewUser ) ? oxConfig::getParameter( 'reviewuserid' ) : $sReviewUser;

        $this->_aViewData['reviews'] = $this->getReviews();
        $this->_aViewData['product'] = $this->getProduct();

        // loading product reviews
        $this->_aViewData['crossselllist']     = $this->getCrossSelling();
        $this->_aViewData['similarlist']       = $this->getSimilarProducts();
        $this->_aViewData['similarrecommlist'] = $this->getRecommList();


        $this->_aViewData['actvrecommlist'] = $this->getActiveRecommList();
        $this->_aViewData['itemList']       = $this->getActiveRecommItems();

        if ( $oActiveRecommList = $this->getActiveRecommList() ) {
            $oList = $this->getActiveRecommItems();
            if ( $oList && $oList->count()) {
                $this->_iAllArtCnt = $oActiveRecommList->getArtCount();
            }
            // load only lists which we show on screen
            $iNrofCatArticles = $this->getConfig()->getConfigParam( 'iNrofCatArticles' );
            $iNrofCatArticles = $iNrofCatArticles ? $iNrofCatArticles : 10;
            $this->_iCntPages  = round( $this->_iAllArtCnt / $iNrofCatArticles + 0.49 );
        }

        $this->_aViewData['pageNavigation'] = $this->getPageNavigation();
        $this->_aViewData['rate'] = $this->canRate();
        $this->_aViewData['success'] = $this->getReviewSendStatus();

        return $this->_sThisTemplate;
    }

    /**
     * Saves user review text (oxreview object)
     *
     * Template variables:
     * <b>success</b>
     *
     * @return null
     */
    public function saveReview()
    {
        $sReviewUserId = $this->getReviewUserId();

        if ( !$this->_checkDirectReview($sReviewUserId) ) {
            return;
        }

        $sReviewText = trim( ( string ) oxConfig::getParameter( 'rvw_txt' ) );
        $dRating     = ( oxConfig::getParameter( 'rating' ) ) ? oxConfig::getParameter( 'rating' ) : oxConfig::getParameter( 'artrating' );

        if ($dRating < 0 || $dRating > 5) {
            $dRating = null;
        }
        // #1590M
        /*if ( !$sReviewText ) {
            $this->_aViewData['success'] = true;
            return;
        }*/

        $sType     = $this->_getActiveType();
        $sObjectId = $this->_getActiveObject()->getId();
        if ($sType && $sObjectId) {
            //save rating
            if ( $dRating ) {
                $oRating = oxNew( 'oxrating' );
                $oRating->oxratings__oxuserid   = new oxField($sReviewUserId);
                $oRating->oxratings__oxtype     = new oxField($sType, oxField::T_RAW);
                $oRating->oxratings__oxobjectid = new oxField($sObjectId);
                $oRating->oxratings__oxrating   = new oxField($dRating);
                $oRating->save();
                if ( $oProduct = $this->getProduct() ) {
                    $oProduct->addToRatingAverage( $dRating);
                } elseif ($this->_oActiveRecommList) {
                    $this->_oActiveRecommList->addToRatingAverage( $dRating);
                }
                $this->_blReviewSendStatus = true;
            }

            if ( $sReviewText ) {
                $oReview = oxNew( 'oxreview' );
                $oReview->oxreviews__oxuserid   = new oxField($sReviewUserId);
                $oReview->oxreviews__oxtype     = new oxField($sType, oxField::T_RAW);
                $oReview->oxreviews__oxobjectid = new oxField($sObjectId);
                $oReview->oxreviews__oxtext     = new oxField($sReviewText);
                $oReview->oxreviews__oxlang     = new oxField(oxLang::getInstance()->getBaseLanguage());
                $oReview->oxreviews__oxrating   = new oxField(( $dRating) ? $dRating : null);
                $oReview->save();
                $this->_blReviewSendStatus = true;
            }
        }
    }

    /**
     * checks if given user id is current user, if not, cheks if given user id can use direct review
     *
     * @param string $sReviewUserId user to check
     *
     * @return boolean
     */
    protected function _checkDirectReview( $sReviewUserId )
    {
        $oUser = $this->getUser();
        $blAllow = false;
        if ($oUser && ($sReviewUserId == $oUser->getId())) {
            $blAllow = true;
        } else {
            $blAllow = $this->_allowDirectReview( $sReviewUserId );
        }
        return $blAllow;
    }

    /**
     * Returns bool whether user is allowed to write review without logging in,
     * only providing reviewuserid URL parameter.
     *
     * @param string $sUserId user id
     *
     * @return bool
     */
    protected function _allowDirectReview( $sUserId )
    {
        $oUser = oxNew( 'oxuser' );
        if ( !$oUser->exists( $sUserId ) ) {
            return false;
        }

        return true;
    }

    /**
     * Template variable getter. Returns review user id
     *
     * @return string
     */
    public function getReviewUserId()
    {
        if ( $this->_sReviewUserId === null ) {
            $this->_sReviewUserId = false;

            //review user from order email
            $sReviewUser = oxConfig::getParameter( 'reviewuser' );
            $sReviewUser = ( !$sReviewUser ) ? oxConfig::getParameter( 'reviewuserid' ) : $sReviewUser;
            if ( $sReviewUser ) {
                $oUser = oxNew( 'oxuser' );
                $sReviewUserId = $oUser->getReviewUserId( $sReviewUser );
            }

            $oUser = $this->getUser();
            if (!$sReviewUserId && $oUser) {
                $sReviewUserId = $oUser->getId();
            }
            $this->_sReviewUserId = $sReviewUserId;
        }
        return $this->_sReviewUserId;
    }

    /**
     * Template variable getter. Returns search product
     *
     * @return object
     */
    public function getProduct()
    {
        if ( $this->_oProduct === null ) {
            $this->_oProduct = false;

            if ( $sAnid = oxConfig::getParameter( 'anid' ) ) {
                $this->_oProduct = oxNewArticle( $sAnid );
            }
        }
        return $this->_oProduct;
    }

    /**
     * Template variable getter. Returns active object (oxarticle or oxrecommlist)
     *
     * @return object
     */
    protected function _getActiveObject()
    {
        if ( $this->_oActObject === null ) {
            $this->_oActObject = false;

            if ( $oProduct = $this->getProduct() ) {
                $this->_oActObject = $oProduct;
            } elseif ( $this->_oActiveRecommList ) {
                $this->_oActObject = $this->_oActiveRecommList;
            }
        }
        return $this->_oActObject;
    }

    /**
     * Template variable getter. Returns active type (oxarticle or oxrecommlist)
     *
     * @return string
     */
    protected function _getActiveType()
    {
        if ( $this->getProduct() ) {
            $sType = 'oxarticle';
        } elseif ($this->_oActiveRecommList) {
            $sType = 'oxrecommlist';
        }
        return $sType;
    }

    /**
     * Template variable getter. Returns active recommlist
     *
     * @return object
     */
    public function getActiveRecommList()
    {
        if ( $this->_oActiveRecommList === null ) {
            $this->_oActiveRecommList = false;

            if ( $sRecommId = oxConfig::getParameter( 'recommid' ) ) {
                $oActiveRecommList = oxNew('oxrecommlist');
                if ( $oActiveRecommList->load( $sRecommId ) ) {
                    $this->_oActiveRecommList = $oActiveRecommList;
                }
            }
        }
        return $this->_oActiveRecommList;
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
            $sType     = $this->_getActiveType();
            $sObjectId = $this->_getActiveObject()->getId();
            $oRating = oxNew( 'oxrating' );
            $this->_blRate = $oRating->allowRating( $this->getReviewUserId(), $sType, $sObjectId);

        }
        return $this->_blRate;
    }

    /**
     * Template variable getter. Returns active object's reviews
     *
     * @return array
     */
    public function getReviews()
    {
        if ( $this->_aReviews === null ) {
            $this->_aReviews = false;
            if ( $oObject = $this->_getActiveObject() ) {
                $this->_aReviews = $oObject->getReviews();
            }
        }
        return $this->_aReviews;
    }

    /**
     * Template variable getter. Returns crosssellings
     *
     * @return object
     */
    public function getCrossSelling()
    {
        if ( $this->_oCrossSelling === null ) {
            $this->_oCrossSelling = false;
            if ( $oProduct = $this->getProduct() ) {
                $this->_oCrossSelling = $oProduct->getCrossSelling();
            }
        }
        return $this->_oCrossSelling;
    }

    /**
     * Template variable getter. Returns similar products
     *
     * @return object
     */
    public function getSimilarProducts()
    {
        if ( $this->_oSimilarProducts === null ) {
            $this->_oSimilarProducts = false;
            if ( $oProduct = $this->getProduct() ) {
                $this->_oSimilarProducts = $oProduct->getSimilarProducts();
            }
        }
        return $this->_oSimilarProducts;
    }

    /**
     * Template variable getter. Returns recommlists
     *
     * @return object
     */
    public function getRecommList()
    {
        if ( $this->_oRecommList === null ) {
            $this->_oRecommList = false;
            if ( $oProduct = $this->getProduct() ) {
                $oRecommList = oxNew('oxrecommlist');
                $this->_oRecommList = $oRecommList->getRecommListsByIds( array( $oProduct->getId() ) );
            }
        }
        return $this->_oRecommList;
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
     * Template variable getter. Returns review send status
     *
     * @return bool
     */
    public function getReviewSendStatus()
    {
        return $this->_blReviewSendStatus;
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
            if ( $this->_oActiveRecommList ) {
                $this->_oPageNavigation = $this->generatePageNavigation();
            }
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
        if ( $oActRecommList = $this->getActiveRecommList() ) {
            $sAddParams .= '&amp;recommid='.$oActRecommList->getId();
        }
        return $sAddParams;
    }

    /**
     * returns additional url params for dynamic url building
     *
     * @return string
     */
    public function getDynUrlParams()
    {
        $sParams = parent::getDynUrlParams();

        if ( $sVal = oxConfig::getParameter( 'cnid' ) ) {
            $sParams .= "&amp;cnid={$sVal}";
        }
        if ( $sVal= oxConfig::getParameter( 'anid' ) ) {
            $sParams .= "&amp;anid={$sVal}";
        }
        if ( $sVal= oxConfig::getParameter( 'listtype' ) ) {
            $sParams .= "&amp;listtype={$sVal}";
        }

        return $sParams;
    }
}
