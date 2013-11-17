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
 * $Id: alist.php 18520 2009-04-24 08:10:19Z vilma $
 */

/**
 * List of articles for a selected product group.
 * Collects list of articles, according to it generates links for list gallery,
 * metatags (for search engines). Result - "list.tpl" template.
 * OXID eShop -> (Any selected shop product category).
 */
class aList extends oxUBase
{
    /**
     * Count of all articles in list.
     * @var integer
     */
    protected $_iAllArtCnt = 0;

    /**
     * Number of possible pages.
     * @var integer
     */
    protected $_iCntPages = null;

    /**
     * Current class default template name.
     * @var string
     */
    protected $_sThisTemplate = 'list.tpl';

    /**
     * New layout list template
     * @var string
     */
    protected $_sThisMoreTemplate = 'list_more.tpl';

    /**
     * Category path string
     * @var string
     */
    protected $_sCatPathString = null;

    /**
     * Marked which defines if current view is sortable or not
     * @var bool
     */
    protected $_blShowSorting = true;

    /**
     * Category attributes.
     * @var array
     */
    protected $_aAttributes = null;

    /**
     * Category article list
     * @var array
     */
    protected $_aCatArtList = null;

    /**
     * Category tree html path
     * @var string
     */
    protected $_sCatTreeHtmlPath = null;

    /**
     * If category has subcategories
     * @var bool
     */
    protected $_blHasVisibleSubCats = null;

    /**
     * List of category's subcategories
     * @var array
     */
    protected $_aSubCatList = null;

    /**
     * Page navigation
     * @var object
     */
    protected $_oPageNavigation = null;

    /**
     * Active object is category.
     * @var bool
     */
    protected $_blIsCat = null;

    /**
     * Recomendation list
     * @var object
     */
    protected $_oRecommList = null;

    /**
     * Category title
     * @var string
     */
    protected $_sCatTitle = null;

    /**
     * Category seo url state
     * @var bool
     */
    protected $_blFixedUrl = null;

    /**
     * Generates (if not generated yet) and returns view ID (for
     * template engine caching).
     *
     * @return string   $this->_sViewId view id
     */
    public function getViewId()
    {
        if ( !isset( $this->_sViewId ) ) {
            $sCatId   = oxConfig::getParameter( 'cnid' );
            $iActPage = $this->getActPage();
            $iArtPerPage = oxConfig::getParameter( '_artperpage' );
            $sParentViewId = parent::getViewId();

            // shorten it
                $this->_sViewId = md5( $sParentViewId.'|'.$sCatId.'|'.$iActPage.'|'.$iArtPerPage );

        }

        return $this->_sViewId;
    }

    /**
     * Executes parent::render(), loads active category, prepares article
     * list sorting rules. According to category type loads list of
     * articles - regular (oxarticlelist::LoadCategoryArticles()) or price
     * dependent (oxarticlelist::LoadPriceArticles()). Generates page navigation data
     * such as previous/next window URL, number of available pages, generates
     * metatags info (oxubase::_convertForMetaTags()) and returns name of
     * template to render.
     *
     * Template variables:
     * <b>articlelist</b>, <b>filterattributes</b>, <b>pageNavigation</b>,
     * <b>subcatlist</b>, <b>meta_keywords</b>, <b>meta_description</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        $oCategory  = null;
        $blContinue = true;
        $this->_blIsCat = false;

        // A. checking for fake "more" category
        if ( 'oxmore' == oxConfig::getParameter( 'cnid' ) && $myConfig->getConfigParam( 'blTopNaviLayout' ) ) {

            // overriding some standard value and parameters
            $this->_sThisTemplate = $this->_sThisMoreTemplate;
            $oCategory = oxNew( 'oxcategory' );
            $oCategory->oxcategories__oxactive = new oxField( 1, oxField::T_RAW );
            $this->setActCategory( $oCategory );
        } elseif ( ( $oCategory = $this->getActCategory() ) ) {
            $blContinue = ( bool ) $oCategory->oxcategories__oxactive->value;
            $this->_blIsCat = true;
        }


        // category is inactive ?
        if ( !$blContinue || !$oCategory ) {
            oxUtils::getInstance()->redirect( $myConfig->getShopURL().'index.php' );
        }

        $this->_aViewData['filterattributes'] = $this->getAttributes();

        $this->_aViewData['articlelist']       = $this->getArticleList();
        $this->_aViewData['similarrecommlist'] = $this->getSimilarRecommLists();

        // loading actions
        $this->_aViewData['articlebargainlist'] = $this->getBargainArticleList();
        $this->_aViewData['aTop5Articles']      = $this->getTop5ArticleList();

        $this->_aViewData['pageNavigation'] = $this->getPageNavigation();

        $this->_aViewData['actCatpath']        = $this->getCatTreePath();
        $this->_aViewData['template_location'] = $this->getTemplateLocation();

        // add to the parent view
        $this->_aViewData['actCategory'] = $this->getActCategory();

        $oCat = $this->getActCategory();
        if ($oCat && is_array($myConfig->getConfigParam( 'aRssSelected' )) && in_array('oxrss_categories', $myConfig->getConfigParam( 'aRssSelected' ))) {
            $oRss = oxNew('oxrssfeed');
            $this->addRssFeed($oRss->getCategoryArticlesTitle($oCat), $oRss->getCategoryArticlesUrl($oCat), 'activeCategory');
        }

        //Gets subcategory tree from category tree
        $this->_aViewData['hasVisibleSubCats'] = $this->hasVisibleSubCats();
        $this->_aViewData['subcatlist']        = $this->getSubCatList();

        $this->_aViewData['title'] = $this->getTitle();

        parent::render();

        return $this->getTemplateName();
    }

    /**
     * Stores chosen category filter into session.
     *
     * Session variables:
     * <b>session_attrfilter</b>
     *
     * @return null
     */
    public function executefilter()
    {
        // store this into session
        $aFilter = oxConfig::getParameter( 'attrfilter', 1 );
        $sActCat = oxConfig::getParameter( 'cnid' );
        $aSessionFilter = oxSession::getVar( 'session_attrfilter' );
        $aSessionFilter[$sActCat] = $aFilter;
        oxSession::setVar( 'session_attrfilter', $aSessionFilter );
    }

    /**
     * Loads and returns article list of active category.
     *
     * @param string $oCategory category object
     *
     * @return array
     */
    protected function _loadArticles( $oCategory )
    {
        $myConfig = $this->getConfig();

        $iNrofCatArticles = (int) $myConfig->getConfigParam( 'iNrofCatArticles' );
        $iNrofCatArticles = $iNrofCatArticles?$iNrofCatArticles:1;

        // load only articles which we show on screen
        $oArtList = oxNew( 'oxarticlelist' );
        $oArtList->setSqlLimit( $iNrofCatArticles * $this->getActPage(), $iNrofCatArticles );
        $oArtList->setCustomSorting( $this->getSortingSql( $oCategory->getId() ) );

        if ( $oCategory->oxcategories__oxpricefrom->value || $oCategory->oxcategories__oxpriceto->value ) {
            $dPriceFrom = $oCategory->oxcategories__oxpricefrom->value;
            $dPriceTo   = $oCategory->oxcategories__oxpriceto->value;

            $this->_iAllArtCnt = $oArtList->loadPriceArticles( $dPriceFrom, $dPriceTo, $oCategory );
        } else {
            $aSessionFilter = oxSession::getVar( 'session_attrfilter' );

            $sActCat = oxConfig::getParameter( 'cnid' );
            $this->_iAllArtCnt = $oArtList->loadCategoryArticles( $sActCat, $aSessionFilter );
        }

        $this->_iCntPages = round( $this->_iAllArtCnt/$iNrofCatArticles + 0.49 );

        return $oArtList;
    }

    /**
     * Returns active product id to load its seo meta info
     *
     * @return string
     */
    protected function _getSeoObjectId()
    {
        if ( ( $oCategory = $this->getActCategory() ) ) {
            return $oCategory->getId();
        }
    }

    /**
     * Returns string built from category titles
     *
     * @return string
     */
    protected function _getCatPathString()
    {
        if ( $this->_sCatPathString === null ) {

            // marking as allready set
            $this->_sCatPathString = false;

            //fetching category path
            if ( is_array( $aPath = $this->getCatTreePath() ) ) {

                $oStr = getStr();
                $this->_sCatPathString = '';
                foreach ( $aPath as $oCat ) {
                    if ( $this->_sCatPathString ) {
                        $this->_sCatPathString .= ', ';
                    }
                    $this->_sCatPathString .= $oStr->strtolower( $oCat->oxcategories__oxtitle->value );
                }
            }
        }
        return $this->_sCatPathString;
    }

    /**
     * Returns current view meta description data
     *
     * @param string $sMeta     category path
     * @param int    $iLength   max length of result, -1 for no truncation
     * @param bool   $blDescTag if true - performs additional dublicate cleaning
     *
     * @return  string  $sString    converted string
     */
    protected function _prepareMetaDescription( $sMeta, $iLength = 1024, $blDescTag = false )
    {
        // using language constant ..
        $sDescription = oxLang::getInstance()->translateString( 'INC_HEADER_YOUAREHERE' );

        // appending parent title
        if ( $oCategory = $this->_getCategory() ) {
            if ( ( $oParent = $oCategory->getParentCategory() ) ) {
                $sDescription .= " {$oParent->oxcategories__oxtitle->value} -";
            }

            // adding cateogry title
            $sDescription .= " {$oCategory->oxcategories__oxtitle->value}.";
        }

        // and final component ..
        if ( ( $sSuffix = $this->getConfig()->getActiveShop()->oxshops__oxstarttitle->value ) ) {
            $sDescription .= " {$sSuffix}";
        }

        // making safe for output
        $sDescription = getStr()->cleanStr($sDescription);
        return trim( strip_tags( getStr()->html_entity_decode( $sDescription ) ) );
    }

    /**
     * Metatags - description and keywords - generator for search
     * engines. Uses string passed by parameters, cleans HTML tags,
     * string dublicates, special chars. Also removes strings defined
     * in $myConfig->aSkipTags (Admin area).
     *
     * @param string $sMeta     category path
     * @param int    $iLength   max length of result, -1 for no truncation
     * @param bool   $blDescTag if true - performs additional dublicate cleaning
     *
     * @return  string  $sString    converted string
     */
    protected function _collectMetaDescription( $sMeta, $iLength = 1024, $blDescTag = false )
    {
        //formatting description tag
        $sAddText = ( $oCategory = $this->getActCategory() ) ? trim( $oCategory->oxcategories__oxlongdesc->value ):'';
        $aArticleList = $this->getArticleList();
        if ( !$sAddText && count($aArticleList)) {
            foreach ( $aArticleList as $oArticle ) {
                if ( $sAddText ) {
                    $sAddText .= ', ';
                }
                $sAddText .= $oArticle->oxarticles__oxtitle->value;
            }
        }

        if ( !$sMeta ) {
            $sMeta = trim( $this->_getCatPathString() );
        }

        if ( $sMeta ) {
            $sMeta = "{$sMeta} - {$sAddText}";
        } else {
            $sMeta = $sAddText;
        }

        return parent::_prepareMetaDescription( $sMeta, $iLength, $blDescTag );
    }

    /**
     * Returns current view keywords seperated by comma
     *
     * @param string $sKeywords data to use as keywords
     *
     * @return string
     */
    protected function _prepareMetaKeyword( $sKeywords )
    {
        $sKeywords = '';
        if ( ( $oCategory = $this->getActCategory() ) ) {
            if ( ( $oParent = $oCategory->getParentCategory() ) ) {
                $sKeywords = $oParent->oxcategories__oxtitle->value;
            }

            $sKeywords = ( $sKeywords ? $sKeywords . ', ' : '' ) . $oCategory->oxcategories__oxtitle->value;
            $aSubCats  = $oCategory->getSubCats();
            if ( is_array( $aSubCats ) ) {
                foreach ( $aSubCats as $oSubCat ) {
                    $sKeywords .= ', '.$oSubCat->oxcategories__oxtitle->value;
                }
            }
        }

        $sKeywords = parent::_prepareMetaDescription( $sKeywords, -1, false );
        $aSkipTags = $this->getConfig()->getConfigParam( 'aSkipTags' );
        $sKeywords = $this->_removeDuplicatedWords( $sKeywords, $aSkipTags );

        return trim( $sKeywords );
    }

    /**
     * Creates a string of keyword filtered by the function prepareMetaDescription and without any duplicates
     * additional the admin defined strings are removed
     *
     * @param string $sKeywords category path
     *
     * @return string
     */
    protected function _collectMetaKeyword( $sKeywords )
    {
        $iMaxTextLenght = 60;
        $sText = '';

        if ( count( $aArticleList = $this->getArticleList() ) ) {
            $oStr = getStr();
            foreach ( $aArticleList as $oProduct ) {
                $sDesc = strip_tags( trim( $oStr->strtolower( $oProduct->getArticleLongDesc()->value ) ) );
                if ( $oStr->strlen( $sDesc ) > $iMaxTextLenght ) {
                    $sMidText = $oStr->substr( $sDesc, 0, $iMaxTextLenght );
                    $sDesc   .= $oStr->substr( $sMidText, 0, ( $oStr->strlen( $sMidText ) - $oStr->strpos( strrev( $sMidText ), ' ' ) ) );
                }
                if ( $sText ) {
                    $sText .= ', ';
                }
                $sText .= $sDesc;
            }
        }

        if ( !$sKeywords ) {
            $sKeywords = $this->_getCatPathString();
        }

        if ( $sKeywords ) {
            $sText = "{$sKeywords}, {$sText}";
        }

        return parent::_prepareMetaKeyword( $sText );
    }

    /**
     * Assigns Template name ($this->_sThisTemplate) for article list
     * preview. Name of template can be defined in admin or passed by
     * URL ("tpl" variable).
     *
     * @return string
     */
    public function getTemplateName()
    {
        // assign template name
        if ( ( $sTplName = basename( oxConfig::getParameter( 'tpl' ) ) ) ) {
            $this->_sThisTemplate = $sTplName;
        } elseif ( ( $oCategory = $this->getActCategory() ) && $oCategory->oxcategories__oxtemplate->value ) {
            $this->_sThisTemplate = $oCategory->oxcategories__oxtemplate->value;
        }

        return $this->_sThisTemplate;
    }

    /**
     * Adds page number parameter to current Url and returns formatted url
     *
     * @param string $sUrl  url to append page numbers
     * @param int    $iPage current page number
     * @param int    $iLang requested language
     *
     * @return string
     */
    protected function _addPageNrParam( $sUrl, $iPage, $iLang = null)
    {
        if ( oxUtils::getInstance()->seoIsActive() && ( $oCategory = $this->getActCategory() ) ) {
            if ( $iPage ) { // only if page number > 0
                $sUrl = oxSeoEncoderCategory::getInstance()->getCategoryPageUrl( $oCategory, $iPage, $iLang, $this->_isFixedUrl( $oCategory ) );
            }
        } else {
            $sUrl = parent::_addPageNrParam( $sUrl, $iPage, $iLang );
        }
        return $sUrl;
    }

    /**
     * Returns category seo url status (fixed or not)
     *
     * @param oxcategory $oCategory active category
     *
     * @return bool
     */
    protected function _isFixedUrl( $oCategory )
    {
        if ( $this->_blFixedUrl == null ) {
            $sId = $oCategory->getId();
            $iLang = $oCategory->getLanguage();
            $sShopId = $this->getConfig()->getShopId();
            $this->_blFixedUrl = oxDb::getDb()->getOne( "select oxfixed from oxseo where oxobjectid = '{$sId}' and oxshopid = '{$sShopId}' and oxlang = '{$iLang}' and oxparams = '' " );
        }
        return $this->_blFixedUrl;
    }

    /**
     * Returns true if we have category
     *
     * @return bool
     */
    protected function _isActCategory()
    {
        return $this->_blIsCat;
    }

    /**
     * Template variable getter. Returns active category
     *
     * @deprecated
     *
     * @return bool
     */
    protected function _getCategory()
    {
        return $this->getActCategory();
    }

    /**
     * Generates Url for page navigation
     *
     * @return string
     */
    public function generatePageNavigationUrl( )
    {
        if ( ( oxUtils::getInstance()->seoIsActive() && ( $oCategory = $this->getActCategory() ) ) ) {
            return $oCategory->getLink();
        } else {
            return parent::generatePageNavigationUrl( );
        }
    }

    /**
     * Returns SQL sorting string with additional checking if category has its own sorting configuration
     *
     * @param string $sCnid sortable item id
     *
     * @return string
     */
    public function getSorting( $sCnid )
    {
        // category has own sorting
        $aSorting = parent::getSorting( $sCnid );
        $oActCat = $this->getActCategory();
        if ( !$aSorting && $oActCat && $oActCat->oxcategories__oxdefsort->value ) {
            $sSortBy  = getViewName( 'oxarticles' ).".{$oActCat->oxcategories__oxdefsort->value}";
            $sSortDir = ( $oActCat->oxcategories__oxdefsortmode->value ) ? " desc " : null;

            $this->setItemSorting( $sCnid, $sSortBy, $sSortDir );
            $aSorting = array ( 'sortby' => $sSortBy, 'sortdir' => $sSortDir );
        }
        return $aSorting;
    }

    /**
     * Returns title suffix used in template
     *
     * @return string
     */
    public function getTitleSuffix()
    {
        if ( $this->getActCategory()->oxcategories__oxshowsuffix->value ) {
            return $this->getConfig()->getActiveShop()->oxshops__oxtitlesuffix->value;
        }
    }

    /**
     * returns object, assosiated with current view.
     * (the object that is shown in frontend)
     *
     * @return object
     */
    protected function _getSubject()
    {
        return $this->getActCategory();
    }

    /**
     * Template variable getter. Returns array of attribute values
     * we do have here in this category
     *
     * @return array
     */
    public function getAttributes()
    {
        // #657 gather all attribute values we do have here in this category
        $this->_aAttributes = false;
        if ( ( $oCategory = $this->getActCategory() ) ) {
            $aAttributes = $oCategory->getAttributes();
            if ( count( $aAttributes ) ) {
                $this->_aAttributes = $aAttributes;
            }
        }
        return $this->_aAttributes;
    }

    /**
     * Template variable getter. Returns category's article list
     *
     * @return array
     */
    public function getArticleList()
    {
        if ( $this->_aArticleList === null ) {
            if ( $this->_isActCategory() && ( $oCategory = $this->getActCategory() ) ) {
                $aArticleList = $this->_loadArticles( $oCategory );
                if ( count( $aArticleList ) ) {
                    $this->_aArticleList = $aArticleList;
                }
            }
        }
        return $this->_aArticleList;
    }

    /**
     * Template variable getter. Returns recommendation list
     *
     * @return object
     */
    public function getSimilarRecommLists()
    {
        if ( $this->_oRecommList === null ) {
            $this->_oRecommList = false;
            if ( $aCatArtList = $this->getArticleList() ) {
                $oRecommList = oxNew('oxrecommlist');
                $this->_oRecommList = $oRecommList->getRecommListsByIds( $aCatArtList->arrayKeys());
            }
        }
        return $this->_oRecommList;
    }

    /**
     * Template variable getter. Returns category path
     *
     * @return string
     */
    public function getCatTreePath()
    {
        if ( $this->_sCatTreePath === null ) {
             $this->_sCatTreePath = false;
            // category path
            if ( $oCatTree = $this->getCategoryTree() ) {
                $this->_sCatTreePath = $oCatTree->getPath();
            }
        }
        return $this->_sCatTreePath;
    }

    /**
     * Template variable getter. Returns category html path
     *
     * @return string
     */
    public function getTemplateLocation()
    {
        if ( $this->_sCatTreeHtmlPath === null ) {
             $this->_sCatTreeHtmlPath = false;
            // category path
            if ( $oCatTree = $this->getCategoryTree() ) {
                $this->_sCatTreeHtmlPath = $oCatTree->getHtmlPath();
            }
        }
        return $this->_sCatTreeHtmlPath;
    }

    /**
     * Template variable getter. Returns true if category has active
     * subcategories.
     *
     * @return bool
     */
    public function hasVisibleSubCats()
    {
        if ( $this->_blHasVisibleSubCats === null ) {
            $this->_blHasVisibleSubCats = false;
            if ( $oClickCat = $this->getActCategory() ) {
                $this->_blHasVisibleSubCats = $oClickCat->getHasVisibleSubCats();
            }
        }
        return $this->_blHasVisibleSubCats;
    }

    /**
     * Template variable getter. Returns list of subategories.
     *
     * @return array
     */
    public function getSubCatList()
    {
        if ( $this->_aSubCatList === null ) {
            $this->_aSubCatList = array();
            if ( $oClickCat = $this->getActCategory() ) {
                $this->_aSubCatList = $oClickCat->getSubCats();
            }
        }
        return $this->_aSubCatList;
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
     * Template variable getter. Returns category title.
     *
     * @return string
     */
    public function getTitle()
    {
        if ( $this->_sCatTitle === null ) {
            $this->_sCatTitle = false;
            if ( ( $oCategory = $this->getActCategory() ) ) {
                $this->_sCatTitle = $oCategory->oxcategories__oxtitle->value;
            }
        }
        return $this->_sCatTitle;
    }

    /**
     * Template variable getter. Returns Top 5 article list
     *
     * @return array
     */
    public function getTop5ArticleList()
    {
        if ( $this->_aTop5ArticleList === null ) {
            $this->_aTop5ArticleList = false;
            $myConfig = $this->getConfig();
            if ( $myConfig->getConfigParam( 'bl_perfLoadAktion' ) && $this->_isActCategory() ) {
                // top 5 articles
                $oArtList = oxNew( 'oxarticlelist' );
                $oArtList->loadTop5Articles();
                if ( $oArtList->count() ) {
                    $this->_aTop5ArticleList = $oArtList;
                }
            }
        }
        return $this->_aTop5ArticleList;
    }

    /**
     * Template variable getter. Returns bargain article list
     *
     * @return array
     */
    public function getBargainArticleList()
    {
        if ( $this->_aBargainArticleList === null ) {
            $this->_aBargainArticleList = array();
            if ( $this->getConfig()->getConfigParam( 'bl_perfLoadAktion' ) && $this->_isActCategory() ) {
                $oArtList = oxNew( 'oxarticlelist' );
                $oArtList->loadAktionArticles( 'OXBARGAIN' );
                if ( $oArtList->count() ) {
                    $this->_aBargainArticleList = $oArtList;
                }
            }
        }
        return $this->_aBargainArticleList;
    }

    /**
     * Template variable getter. Returns active search
     *
     * @return object
     */
    public function getActiveCategory()
    {
        return $this->getActCategory();
    }

}
