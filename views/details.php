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
 * $Id: details.php 21122 2009-07-24 08:19:07Z arvydas $
 */

/**
 * Article details information page.
 * Collects detailed article information, possible variants, such information
 * as crosselling, similarlist, picture gallery list, etc.
 * OXID eShop -> (Any chosen product).
 * @package main
 */
class Details extends oxUBase
{
    /**
     * List of article variants.
     *
     * @var array
     */
    protected $_aVariantList = null;

    /**
     * Current class default template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'details.tpl';

    /**
     * Current product parent article object
     *
     * @var oxarticle
     */
    protected $_oParent = null;

    /**
     * Marker if user can rate current product
     *
     * @var bool
     */
    protected $_blCanRate = null;

    /**
     * Marked which defines if current view is sortable or not
     * @var bool
     */
    protected $_blShowSorting = true;

    /**
     * If tags will be changed
     * @var bool
     */
    protected $_blEditTags = null;

    /**
     * All tags
     * @var array
     */
    protected $_aTags = null;

    /**
     * Tag cloud
     * @var array
     */
    protected $_sTagCloud = null;

    /**
     * Returns user recommlist
     * @var array
     */
    protected $_aUserRecommList = null;

    /**
     * Returns login form anchor
     * @var string
     */
    protected $_sLoginFormAnchor = null;

    /**
     * Class handling CAPTCHA image.
     * @var object
     */
    protected $_oCaptcha = null;

    /**
     * Media files
     * @var array
     */
    protected $_aMediaFiles = null;

    /**
     * History (last seen) products
     * @var array
     */
    protected $_aLastProducts = null;

    /**
     * Current product's vendor
     * @var oxvendor
     */
    protected $_oVendor = null;

    /**
     * Current product's manufacturer
     * @var oxmanufacturer
     */
    protected $_oManufacturer = null;

    /**
     * Current product's category
     * @var object
     */
    protected $_oCategory = null;

    /**
     * Current product's attributes
     * @var object
     */
    protected $_aAttributes = null;

    /**
     * Parent article name
     * @var string
     */
    protected $_sParentName = null;

    /**
     * Parent article url
     * @var string
     */
    protected $_sParentUrl = null;

    /**
     * Picture gallery
     * @var array
     */
    protected $_aPicGallery = null;

    /**
     * Select lists
     * @var array
     */
    protected $_aSelectLists = null;

    /**
     * Reviews of current article
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
     * Similar recommlists
     * @var object
     */
    protected $_oRecommList = null;

    /**
     * Accessoires of current article
     * @var object
     */
    protected $_oAccessoires = null;

    /**
     * List of customer also bought thies products
     * @var object
     */
    protected $_aAlsoBoughtArts = null;

    /**
     * Search title
     * @var string
     */
    protected $_sSearchTitle = null;

    /**
     * Marker if active product was fully initialized before returning it
     * (see details::getProduct())
     * @var bool
     */
    protected $_blIsInitialized = false;

    /**
     * Current view link type
     *
     * @var int
     */
    protected $_iLinkType = null;

    /**
     * Returns current product parent article object if it is available
     *
     * @param string $sParentId parent product id
     *
     * @return oxarticle
     */
    protected function _getParentProduct( $sParentId )
    {
        if ( $sParentId && $this->_oParent === null ) {
            $this->_oParent = false;
            if ( ( $oParent = oxNewArticle( $sParentId ) ) ) {
                $this->_processProduct( $oParent );
                $this->_oParent = $oParent;
            }
        }
        return $this->_oParent;
    }

    /**
     * loading full list of variants,
     * if we are child and do not have any variants then please load all parent variants as ours
     *
     * @return null
     */
    public function loadVariantInformation()
    {
        if ( $this->_aVariantList === null ) {
            $oProduct = $this->getProduct();

            //loading full list of variants
            $this->_aVariantList = $oProduct->getVariants( false );

            //if we are child and do not have any variants then please load all parent variants as ours
            if ( ( $oParent = $this->_getParentProduct( $oProduct->oxarticles__oxparentid->value ) ) && count( $this->_aVariantList ) == 0 ) {
                $myConfig = $this->getConfig();

                $this->_aVariantList = $oParent->getVariants( false );

                //in variant list parent may be NOT buyable
                if ( $oParent->blNotBuyableParent ) {
                    $oParent->blNotBuyable = true;
                }

                //lets additionally add parent article if it is sellable
                if ( $myConfig->getConfigParam( 'blVariantParentBuyable' ) ) {
                    //#1104S if parent is buyable load selectlists too
                    $oParent->aSelectlist = $oParent->getSelectLists();
                    $this->_aVariantList = array_merge( array( $oParent ), $this->_aVariantList->getArray() );
                }

                //..and skip myself from the list
                if ( isset( $this->_aVariantList[$oProduct->getId()] ) ) {
                    unset( $this->_aVariantList[$oProduct->getId()] );
                }
            }

            // setting link type for variants ..
            foreach ( $this->_aVariantList as $oVariant ) {
                $this->_processProduct( $oVariant );
            }

        }
        return $this->_aVariantList;
    }

    /**
     * In case list type is "search" returns search parameters which will be added to product details link
     *
     * @return string | null
     */
    protected function _getAddUrlParams()
    {
        if ( $this->getListType() == "search" ) {
            return $this->getDynUrlParams();
        }
    }

    /**
     * Processes product by setting link type and in case list type is search adds search parameters to details link
     *
     * @param object $oProduct
     *
     * @return null
     */
    protected function _processProduct( $oProduct )
    {
        $oProduct->setLinkType( $this->getLinkType() );
        if ( $sAddParams = $this->_getAddUrlParams() ) {
            $oProduct->appendLink( $sAddParams );
        }
    }

    /**
     * Returns prefix ID used by template engine.
     *
     * @return  string  $this->_sViewID view id
     */
    public function getViewId()
    {
        if ( isset( $this->_sViewId )) {
            return $this->_sViewId;
        }

            $sViewId = parent::getViewId().'|'.oxConfig::getParameter( 'anid' ).'|'.count( $this->getVariantList() ).'|';


        return $this->_sViewId = $sViewId;
    }


    /**
     * Executes parent method parent::init() and newly loads article
     * object if users language was changed.
     *
     * @return null
     */
    public function init()
    {
        parent::init();

        $oProduct = $this->getProduct();

        // assign template name
        if ( $oProduct->oxarticles__oxtemplate->value ) {
            $this->_sThisTemplate = $oProduct->oxarticles__oxtemplate->value;
        }

        if ( ( $sTplName = oxConfig::getParameter( 'tpl' ) ) ) {
            $this->_sThisTemplate = basename ( $sTplName );
        }
    }

    /**
     * If possible loads additional article info (oxarticle::getCrossSelling(),
     * oxarticle::getAccessoires(), oxarticle::getReviews(), oxarticle::GetSimilarProducts(),
     * oxarticle::GetCustomerAlsoBoughtThisProducts()), forms variants details
     * navigation URLs
     * loads selectlists (oxarticle::GetSelectLists()), prerares HTML meta data
     * (details::_convertForMetaTags()). Returns name of template file
     * details::_sThisTemplate
     *
     * Template variables:
     * <b>product</b>, <b>ispricealarm</b>, <b>reviews</b>, <b>crossselllist</b>,
     * <b>accessoirelist</b>, <b>similarlist</b>, <b>customerwho</b>,
     * <b>variants</b>, <b>amountprice</b>, <b>selectlist</b>, <b>sugsucc</b>,
     * <b>meta_description</b>, <b>meta_keywords</b>, <b>blMorePic</b>,
     * <b>parent_url</b>, <b>draw_parent_url</b>, <b>parentname</b>, <b>sBackUrl</b>,
     * <b>allartattr</b>, <b>sSearchTitle</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        $oProduct = $this->getProduct();

        //loading amount price list
        $oProduct->loadAmountPriceInfo();

        // Passing to view. Left for compatibility reasons for a while. Will be removed in future
        $this->_aViewData['product'] = $oProduct;

        $this->_aViewData["aTags"]      = $this->getTags();
        $this->_aViewData["blEditTags"] = $this->getEditTags();
        startProfile("tagCloud");
        $this->_aViewData['tagCloud']   = $this->getTagCloud();
        stopProfile("tagCloud");

        $this->_aViewData['loginformanchor'] = $this->getLoginFormAnchor();

        $this->_aViewData['ispricealarm'] = $this->isPriceAlarm();

        $this->_aViewData['customerwho']    = $this->getAlsoBoughtThiesProducts();
        $this->_aViewData['accessoirelist'] = $this->getAccessoires();
        $this->_aViewData['similarlist']    = $this->getSimilarProducts();
        $this->_aViewData['crossselllist']  = $this->getCrossSelling();

        $this->_aViewData['variants'] = $this->getVariantList();

        $this->_aViewData['reviews'] = $this->getReviews();

        $this->_aViewData['selectlist'] = $this->getSelectLists();

        $this->_aViewData["actpicid"]  = $this->getActPictureId();
        $this->_aViewData["actpic"]    = $this->getActPicture();
        $this->_aViewData['blMorePic'] = $this->morePics();
        $this->_aViewData['ArtPics']   = $this->getPictures();
        $this->_aViewData['ArtIcons']  = $this->getIcons();
        $this->_aViewData['blZoomPic'] = $this->showZoomPics();
        $this->_aViewData['aZoomPics'] = $this->getZoomPics();
        $this->_aViewData['iZoomPic']  = $this->getActZoomPic();

        $this->_aViewData['parentname']      = $this->getParentName();
        $this->_aViewData['parent_url']      = $this->getParentUrl();
        $this->_aViewData['draw_parent_url'] = $this->drawParentUrl();

        $this->_aViewData['pgNr'] = $this->getActPage();

        parent::render();

        $this->_aViewData['allartattr'] = $this->getAttributes();

        // #1102C
        $this->_aViewData['oCategory'] = $this->getCategory();

        $this->_aViewData['oVendor'] = $this->getVendor();

        $this->_aViewData['oManufacturer'] = $this->getManufacturer();

        $this->_aViewData['aLastProducts'] = $this->getLastProducts();

        // #785A loads and sets locator data
        $oLocator = oxNew( 'oxlocator', $this->getListType() );
        $oLocator->setLocatorData( $oProduct, $this );

        //media files
        $this->_aViewData['aMediaUrls'] = $this->getMediaFiles();

        if (in_array('oxrss_recommlists', $myConfig->getConfigParam( 'aRssSelected' )) && $this->getSimilarRecommLists()) {
            $oRss = oxNew('oxrssfeed');
            $this->addRssFeed($oRss->getRecommListsTitle( $oProduct ), $oRss->getRecommListsUrl( $oProduct ), 'recommlists');
        }


        //antispam
        $this->_aViewData['oCaptcha']     = $this->getCaptcha();
        $this->_aViewData['sSearchTitle'] = $this->getSearchTitle();
        $this->_aViewData['actCatpath']   = $this->getCatTreePath();

        return $this->_sThisTemplate;
    }

    /**
     * Returns current view meta data
     * If $sMeta parameter comes empty, sets to it article title and description.
     * It happens if current view has no meta data defined in oxcontent table
     *
     * @param string $sMeta     user defined description, description content or empty value
     * @param int    $iLength   max length of result, -1 for no truncation
     * @param bool   $blDescTag if true - performs additional dublicate cleaning
     *
     * @return string
     */
    protected function _prepareMetaDescription( $sMeta, $iLength = 200, $blDescTag = false )
    {
        if ( !$sMeta ) {
            $oProduct = $this->getProduct();

            $sMeta = $oProduct->getArticleLongDesc()->value;
            $sMeta = str_replace( array( '<br>', '<br />', '<br/>' ), "\n", $sMeta );
            $sMeta = $oProduct->oxarticles__oxtitle->value.' - '.$sMeta;
            $sMeta = strip_tags( $sMeta );
        }
        return parent::_prepareMetaDescription( $sMeta, $iLength, $blDescTag );
    }

    /**
     * Returns current view keywords seperated by comma
     * If $sKeywords parameter comes empty, sets to it article title and description.
     * It happens if current view has no meta data defined in oxcontent table
     *
     * @param string $sKeywords               user defined keywords, keywords content or empty value
     * @param bool   $blRemoveDuplicatedWords remove dublicated words
     *
     * @return string
     */
    protected function _prepareMetaKeyword( $sKeywords, $blRemoveDuplicatedWords = true )
    {
        $myConfig = $this->getConfig();

        if ( !$sKeywords ) {
            $oProduct = $this->getProduct();
            $aKeywords[] = trim( $this->getTitle() );

            if ( $oCatTree = $this->getCategoryTree() ) {
                foreach ( $oCatTree->getPath() as $oCat ) {
                    $aKeywords[] = trim( $oCat->oxcategories__oxtitle->value );
                }
            }

            $sKeywords = implode( ", ", $aKeywords );

            $sKeywords = parent::_prepareMetaKeyword( $sKeywords, $blRemoveDuplicatedWords );

            //adding searchkeys info
            if ( $sSearchKeys = trim( $oProduct->oxarticles__oxsearchkeys->value ) ) {
                $sKeywords .= ", " . parent::_prepareMetaKeyword( $sSearchKeys, false );
            }
        }

        return $sKeywords;
    }

    /**
     * Checks if rating runctionality is on and allwed to user
     *
     * @return bool
     */
    public function canRate()
    {
        if ( $this->_blCanRate === null ) {

            $this->_blCanRate = false;
            $myConfig = $this->getConfig();

            if ( $myConfig->getConfigParam( 'bl_perfLoadReviews' ) &&
                 $oUser = $this->getUser() ) {

                $oRating = oxNew( 'oxrating' );
                $this->_blCanRate = $oRating->allowRating( $oUser->getId(), 'oxarticle', $this->getProduct()->getId() );
            }
        }
        return $this->_blCanRate;
    }

    /**
     * Saves user ratings and review text (oxreview object)
     *
     * @return null
     */
    public function saveReview()
    {
        $sReviewText = trim( ( string ) oxConfig::getParameter( 'rvw_txt', true ) );
        $dRating     = oxConfig::getParameter( 'artrating' );
        if ($dRating < 0 || $dRating > 5) {
            $dRating = null;
        }

        $sArtId  = oxConfig::getParameter( 'anid' );
        $sUserId = oxSession::getVar( 'usr' );

        //save rating
        if ( $dRating && $sUserId ) {
            $oProduct = $this->getProduct();

            $oRating = oxNew( 'oxrating' );
            $blRate = $oRating->allowRating( $sUserId, 'oxarticle', $oProduct->getId());
            if ( $blRate) {
                $oRating->oxratings__oxuserid = new oxField($sUserId);
                $oRating->oxratings__oxtype   = new oxField('oxarticle', oxField::T_RAW);
                $oRating->oxratings__oxobjectid = new oxField($sArtId);
                $oRating->oxratings__oxrating = new oxField($dRating);
                $oRating->save();
                $oProduct->addToRatingAverage( $dRating);
            } else {
                $dRating = null;
            }
        }

        if ( $sReviewText && $sUserId ) {
            $oReview = oxNew( 'oxreview' );
            $oReview->oxreviews__oxobjectid = new oxField($sArtId);
            $oReview->oxreviews__oxtype = new oxField('oxarticle', oxField::T_RAW);
            $oReview->oxreviews__oxtext = new oxField($sReviewText, oxField::T_RAW);
            $oReview->oxreviews__oxlang = new oxField(oxLang::getInstance()->getBaseLanguage());
            $oReview->oxreviews__oxuserid = new oxField($sUserId);
            $oReview->oxreviews__oxrating = new oxField(( $dRating) ? $dRating : null);
            $oReview->save();
        }
    }

    /**
     * Show login template
     *
     * @return null
     */
    public function showLogin()
    {
        $this->_sThisTemplate = 'account_login.tpl';
        if ( $sAnchor = $this->getConfig()->getParameter("anchor") ) {
            $this->_sLoginFormAnchor = $sAnchor;
        }
    }

    /**
     * Adds article to selected recommlist
     *
     * @return null
     */
    public function addToRecomm()
    {
        $sRecommText = trim( ( string ) oxConfig::getParameter( 'recomm_txt' ) );
        $sRecommList = oxConfig::getParameter( 'recomm' );
        $sArtId      = oxConfig::getParameter( 'anid' );

        if ( $sArtId ) {
            $oRecomm = oxNew( 'oxrecommlist' );
            $oRecomm->load( $sRecommList);
            $oRecomm->addArticle( $sArtId, $sRecommText );
        }
    }

    /**
     * Adds tag from parameter
     *
     * @return null;
     */
    public function addTags()
    {
        $sTag  = $this->getConfig()->getParameter('newTags', true );
        $sTag .= " ".getStr()->html_entity_decode( $this->getConfig()->getParameter( 'highTags', true ) );

        $oProduct = $this->getProduct();
        $oProduct->addTag( $sTag );

        //refresh
        $oTagHandler = oxNew( 'oxTagCloud' );
        $this->_sTagCloud = $oTagHandler->getTagCloud( $oProduct->getId() );
    }

    /**
     * Sets tags editing mode
     *
     * @return null
     */
    public function editTags()
    {
        $oTagCloud = oxNew("oxTagCloud");
        $this->_aTags = $oTagCloud->getTags( $this->getProduct()->getId() );
        $this->_blEditTags = true;
    }

    /**
     * Returns active product id to load its seo meta info
     *
     * @return string
     */
    protected function _getSeoObjectId()
    {
        if ( $oProduct = $this->getProduct() ) {
            return $oProduct->getId();
        }
    }

    /**
     * loading full list of attributes
     *
     * @return array $_aAttributes
     */
    public function getAttributes()
    {
        if ( $this->_aAttributes === null ) {
            // all attributes this article has
            $aArtAttributes = $this->getProduct()->getAttributes();

            //making a new array for backward compatibility
            $this->_aAttributes = array();
            if ( count( $aArtAttributes ) ) {
                foreach ( $aArtAttributes as $sKey => $oAttribute ) {
                    $this->_aAttributes[$sKey] = new stdClass();
                    $this->_aAttributes[$sKey]->title = $oAttribute->oxattribute__oxtitle->value;
                    $this->_aAttributes[$sKey]->value = $oAttribute->oxattribute__oxvalue->value;
                }
            }
        }

        return $this->_aAttributes;
    }


    /**
     * Returns if tags will be edit
     *
     * @return bool
     */
    public function getEditTags()
    {
        return $this->_blEditTags;
    }

    /**
     * Returns all tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->_aTags;
    }

    /**
     * Returns tag cloud
     *
     * @return string
     */
    public function getTagCloud()
    {
        if ( $this->_sTagCloud === null ) {
            $this->_sTagCloud = false;
            $oTagHandler = oxNew('oxTagCloud');
            $this->_sTagCloud = $oTagHandler->getTagCloud($this->getProduct()->getId());
        }
        return $this->_sTagCloud;
    }


    /**
     * Returns login form anchor
     *
     * @return array
     */
    public function getLoginFormAnchor()
    {
        return $this->_sLoginFormAnchor;
    }

    /**
     * Returns current product
     *
     * @return oxarticle
     */
    public function getProduct()
    {
        $myConfig = $this->getConfig();
        $myUtils  = oxUtils::getInstance();

        if ( $this->_oProduct === null ) {

            //this option is only for lists and we must reset value
            //as blLoadVariants = false affect "ab price" functionality
            $myConfig->setConfigParam( 'blLoadVariants', true );

            $sOxid = oxConfig::getParameter( 'anid' );

            // object is not yet loaded
            $this->_oProduct = oxNew( 'oxarticle' );
            //$this->_oProduct->setSkipAbPrice( true );

            if ( !$this->_oProduct->load( $sOxid ) ) {
                $myUtils->redirect( $myConfig->getShopHomeURL() );
                $myUtils->showMessageAndExit( '' );
            }
        }

        // additional checks
        if ( !$this->_blIsInitialized ) {
            if ( !$this->_oProduct->isVisible() ) {
                $myUtils->redirect( $myConfig->getShopHomeURL() );
                $myUtils->showMessageAndExit( '' );
            }

            $this->_processProduct( $this->_oProduct );
            $this->_blIsInitialized = true;
        }

        return $this->_oProduct;
    }

    /**
     * Returns current view link type
     *
     * @return int
     */
    public function getLinkType()
    {
        if ( $this->_iLinkType === null ) {
            $sListType = oxConfig::getParameter( 'listtype' );
            if ( 'vendor' == $sListType ) {
                $this->_iLinkType = OXARTICLE_LINKTYPE_VENDOR;
            } elseif ( 'manufacturer' == $sListType ) {
                $this->_iLinkType = OXARTICLE_LINKTYPE_MANUFACTURER;
            } elseif ( 'tag' == $sListType ) {
                $this->_iLinkType = OXARTICLE_LINKTYPE_TAG;
            } else {
                $this->_iLinkType = OXARTICLE_LINKTYPE_CATEGORY;

                // price category has own type..
                if ( ( $oCat = $this->getCategory() ) && $oCat->isPriceCategory() ) {
                    $this->_iLinkType = OXARTICLE_LINKTYPE_PRICECATEGORY;
                }
            }
        }

        return $this->_iLinkType;
    }

    /**
     * Returns variant lists of current product
     *
     * @return array
     */
    public function getVariantList()
    {
        return $this->loadVariantInformation();
    }

    /**
     * Template variable getter. Returns object of handling CAPTCHA image
     *
     * @return object
     */
    public function getCaptcha()
    {
        if ( $this->_oCaptcha === null ) {
            $this->_oCaptcha = oxNew('oxCaptcha');
        }
        return $this->_oCaptcha;
    }

    /**
     * Template variable getter. Returns media files of current product
     *
     * @return array
     */
    public function getMediaFiles()
    {
        if ( $this->_aMediaFiles === null ) {
            $aMediaFiles = $this->getProduct()->getMediaUrls();
            $this->_aMediaFiles = count($aMediaFiles) ? $aMediaFiles : false;
        }
        return $this->_aMediaFiles;
    }

    /**
     * Template variable getter. Returns last seen products
     *
     * @return array
     */
    public function getLastProducts()
    {
        if ( $this->_aLastProducts === null ) {
            //last seen products for #768CA
            $oProduct = $this->getProduct();
            $sArtId = $oProduct->oxarticles__oxparentid->value?$oProduct->oxarticles__oxparentid->value:$oProduct->getId();

            $oHistoryArtList = oxNew( 'oxarticlelist' );
            $oHistoryArtList->loadHistoryArticles( $sArtId );
            $this->_aLastProducts = $oHistoryArtList;
        }
        return $this->_aLastProducts;
    }

    /**
     * Template variable getter. Returns product's vendor
     *
     * @return object
     */
    public function getVendor()
    {
        if ( $this->_oVendor === null ) {
            $this->_oVendor = $this->getProduct()->getVendor( false );
        }
        return $this->_oVendor;
    }

    /**
     * Template variable getter. Returns product's vendor
     *
     * @return object
     */
    public function getManufacturer()
    {
        if ( $this->_oManufacturer === null ) {
            $this->_oManufacturer = $this->getProduct()->getManufacturer( false );
        }
        return $this->_oManufacturer;
    }

    /**
     * Template variable getter. Returns product's root category
     *
     * @return object
     */
    public function getCategory()
    {
        if ( $this->_oCategory === null ) {
            $this->_oCategory = $this->getProduct()->getCategory();
        }
        return $this->_oCategory;
    }

    /**
     * Template variable getter. Returns if draw parent url
     *
     * @return bool
     */
    public function drawParentUrl()
    {
        return $this->getProduct()->isVariant();
    }

    /**
     * Template variable getter. Returns parent article name
     *
     * @return string
     */
    public function getParentName()
    {
        if ( $this->_sParentName === null ) {
            $this->_sParentName = false;
            if ( ( $oParent = $this->_getParentProduct( $this->getProduct()->oxarticles__oxparentid->value ) ) ) {
                $this->_sParentName = $oParent->oxarticles__oxtitle->value;
            }
        }
        return $this->_sParentName;
    }

    /**
     * Template variable getter. Returns parent article name
     *
     * @return string
     */
    public function getParentUrl()
    {
        if ( $this->_sParentUrl === null ) {
            $this->_sParentUrl = false;
            if ( ( $oParent = $this->_getParentProduct( $this->getProduct()->oxarticles__oxparentid->value ) ) ) {
                $this->_sParentUrl = $oParent->getLink();
            }
        }
        return $this->_sParentUrl;
    }

    /**
     * Template variable getter. Returns picture galery of current article
     *
     * @return array
     */
    public function getPictureGallery()
    {
        if ( $this->_aPicGallery === null ) {
            //get picture gallery
            $this->_aPicGallery = $this->getProduct()->getPictureGallery();
        }
        return $this->_aPicGallery;
    }

    /**
     * Template variable getter. Returns id of active picture
     *
     * @return string
     */
    public function getActPictureId()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['ActPicID'];
    }

    /**
     * Template variable getter. Returns active picture
     *
     * @return object
     */
    public function getActPicture()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['ActPic'];
    }

    /**
     * Template variable getter. Returns true if there more pictures
     *
     * @return bool
     */
    public function morePics()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['MorePics'];
    }

    /**
     * Template variable getter. Returns pictures of current article
     *
     * @return array
     */
    public function getPictures()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['Pics'];
    }

    /**
     * Template variable getter. Returns selected picture
     *
     * @param string $sPicNr picture number
     *
     * @return string
     */
    public function getArtPic( $sPicNr )
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['Pics'][$sPicNr];
    }

    /**
     * Template variable getter. Returns icons of current article
     *
     * @return array
     */
    public function getIcons()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['Icons'];
    }

    /**
     * Template variable getter. Returns if to show zoom pictures
     *
     * @return bool
     */
    public function showZoomPics()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['ZoomPic'];
    }

    /**
     * Template variable getter. Returns zoom pictures
     *
     * @return array
     */
    public function getZoomPics()
    {
        $aPicGallery = $this->getPictureGallery();
        return $aPicGallery['ZoomPics'];
    }

    /**
     * Template variable getter. Returns active zoom picture id
     *
     * @return array
     */
    public function getActZoomPic()
    {
        return 1;
    }

    /**
     * Template variable getter. Returns selectlists of current article
     *
     * @return array
     */
    public function getSelectLists()
    {
        if ( $this->_aSelectLists === null ) {
            $this->_aSelectLists = false;
            if ( $this->getConfig()->getConfigParam( 'bl_perfLoadSelectLists' ) ) {
                $this->_aSelectLists = $this->getProduct()->getSelectLists();
            }
        }
        return $this->_aSelectLists;
    }

    /**
     * Template variable getter. Returns reviews of current article
     *
     * @return array
     */
    public function getReviews()
    {
        if ( $this->_aReviews === null ) {
            $this->_aReviews = false;
            if ( $this->getConfig()->getConfigParam( 'bl_perfLoadReviews' ) ) {
                $this->_aReviews = $this->getProduct()->getReviews();
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
     * Template variable getter. Returns similar article list
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
    public function getSimilarRecommLists()
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
     * Template variable getter. Returns accessoires of article
     *
     * @return object
     */
    public function getAccessoires()
    {
        if ( $this->_oAccessoires === null ) {
            $this->_oAccessoires = false;
            if ( $oProduct = $this->getProduct() ) {
                $this->_oAccessoires = $oProduct->getAccessoires();
            }
        }
        return $this->_oAccessoires;
    }

    /**
     * Template variable getter. Returns list of customer also bought thies products
     *
     * @return object
     */
    public function getAlsoBoughtThiesProducts()
    {
        if ( $this->_aAlsoBoughtArts === null ) {
            $this->_aAlsoBoughtArts = false;
            if ( $oProduct = $this->getProduct() ) {
                $this->_aAlsoBoughtArts = $oProduct->getCustomerAlsoBoughtThisProducts();
            }
        }
        return $this->_aAlsoBoughtArts;
    }

    /**
     * Template variable getter. Returns if pricealarm is disabled
     *
     * @return object
     */
    public function isPriceAlarm()
    {
        // #419 disabling pricealarm if article has fixed price
        $oProduct = $this->getProduct();
        if ( isset( $oProduct->oxarticles__oxblfixedprice->value ) && $oProduct->oxarticles__oxblfixedprice->value ) {
            return 0;
        }
        return 1;
    }

    /**
     * returns object, assosiated with current view.
     * (the object that is shown in frontend)
     *
     * @param int $iLang language id
     *
     * @return object
     */
    protected function _getSubject( $iLang )
    {
        return $this->getProduct();
    }

    /**
     * Returns search title. It will be setted in oxlocator
     *
     * @return string
     */
    public function getSearchTitle()
    {
        return $this->_sSearchTitle;
    }

    /**
     * Returns search title setter
     *
     * @param string $sTitle search title
     *
     * @return null
     */
    public function setSearchTitle( $sTitle )
    {
        $this->_sSearchTitle = $sTitle;
    }

    /**
     * active category path setter
     *
     * @param string $sActCatPath category tree path
     *
     * @return string
     */
    public function setCatTreePath( $sActCatPath )
    {
        $this->_sCatTreePath = $sActCatPath;
    }

    /**
     * If product details are accessed by vendor url
     * view must not be indexable
     *
     * @return int
     */
    public function noIndex()
    {
        $sListType = oxConfig::getParameter( 'listtype' );
        if ( $sListType && ( 'vendor' == $sListType || 'manufacturer' == $sListType ) ) {
            return $this->_iViewIndexState = VIEW_INDEXSTATE_NOINDEXFOLLOW;
        }
        return parent::noIndex();
    }

    /**
     * Returns current view title. Default is null
     *
     * @return null
     */
    public function getTitle()
    {
        if ( $oProduct = $this->getProduct() ) {
            return $oProduct->oxarticles__oxtitle->value . ( $oProduct->oxarticles__oxvarselect->value ? ' ' . $oProduct->oxarticles__oxvarselect->value : '' );
        }
    }


    /**
     * Template variable getter. Returns current tag
     *
     * @return string
     */
    public function getTag()
    {
        return oxConfig::getParameter("searchtag", 1);
    }
}
