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
 * $Id: tag.php 18244 2009-04-15 14:49:32Z arvydas $
 */

/**
 * Tag filter for articles
 */
class Tag extends aList
{
    /**
     * List type
     * @var string
     */
    protected $_sListType = 'tag';

    /**
     * Marked which defines if current view is sortable or not
     * @var bool
     */
    protected $_blShowSorting = true;

    /**
     * Current tag
     *
     * @var string
     */
    protected $_sTag;

    /**
     * Current tag title
     *
     * @var string
     */
    protected $_sTagTitle;

    /**
     * Page navigation
     * @var object
     */
    protected $_oPageNavigation = null;

    /**
     * Template location
     *
     * @var string
     */
    protected $_sTemplateLocation;

    /**
     * Unsets SEO category, initiates tag view and calls parent::init();
     *
     * @return null
     */
    public function init()
    {
        if ( oxUtils::getInstance()->seoIsActive() ) {
            // cleaning category id tracked by SEO
            $this->setSessionCategoryId( null );
        }

        $this->_sTag = oxConfig::getParameter("searchtag", 1);
        return parent::init();
    }

    /**
     * Executes parent::render(), loads article list according active tag
     *
     * Template variables:
     * <b>articlelist</b>, <b>pageNavigation</b>, <b>subcatlist</b>,
     * <b>meta_keywords</b>, <b>meta_description</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        oxUBase::render();

        $myConfig = $this->getConfig();

        // load vendor
        $this->_aViewData['articlelist']       = $this->getArticleList();
        $this->_aViewData['title']             = $this->getTitle();
        $this->_aViewData['template_location'] = $this->getTemplateLocation();
        $this->_aViewData['searchtag']         = $this->getTag();

        $this->_aViewData['pageNavigation']    = $this->getPageNavigation();
        $this->_aViewData['actCategory']    = $this->getActiveCategory();

        // processing list articles
        $this->_processListArticles();

        return $this->_sThisTemplate;
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

        if ( $sParam = rawurlencode( oxConfig::getParameter( 'searchtag', 1 ) ) ) {
            $sAddParams .= "&amp;searchtag={$sParam}";
        }

        return $sAddParams;
    }

    /**
     * Loads and returns article list of active vendor.
     *
     * @param object $oVendor vendor object
     *
     * @return array
     */
    protected function _loadArticles( $oVendor )
    {
        // load only articles which we show on screen
        $iNrofCatArticles = (int) $this->getConfig()->getConfigParam( 'iNrofCatArticles' );
        $iNrofCatArticles = $iNrofCatArticles?$iNrofCatArticles:1;

        $oArtList = oxNew( 'oxarticlelist' );
        $oArtList->setSqlLimit( $iNrofCatArticles * $this->getActPage(), $iNrofCatArticles );
        $oArtList->setCustomSorting( $this->getSortingSql( 'oxtags' ) );

        // load the articles
        $this->_iAllArtCnt = $oArtList->loadTagArticles( $this->_sTag, oxLang::getInstance()->getBaseLanguage());
        $this->_iCntPages  = round( $this->_iAllArtCnt / $iNrofCatArticles + 0.49 );

        return $oArtList;
    }

    /**
     * Returns string for "we are here string"
     *
     * @return string
     */
    protected function _getCatPathString()
    {
        return $this->_sTag;
    }

    /**
     * Returns active product id to load its seo meta info
     *
     * @return string
     */
    protected function _getSeoObjectId()
    {
        return md5("tag" . $this->_sTag);
    }

    /**
     * Returns search sorting config
     *
     * @param string $sCnid sortable item id
     *
     * @return string
     */
    public function getSorting( $sCnid )
    {
        return parent::getSorting( "oxtags" );
    }

    /**
     * Sets search sorting config
     *
     * @param string $sCnid      sortable item id
     * @param string $sSortBy    sort field
     * @param string $sSortOrder sort order
     *
     * @return null
     */
    public function setItemSorting( $sCnid, $sSortBy, $sSortOrder  = null )
    {
        parent::setItemSorting( "oxtags", $sSortBy, $sSortOrder );
    }

    /**
     * Generates Url for page navigation
     *
     * @return string
     */
    public function generatePageNavigation( )
    {
        return oxUBase::generatePageNavigation( );
    }

    /**
     * Generates Url for page navigation
     *
     * @return string
     */
    public function generatePageNavigationUrl( )
    {
        return oxUBase::generatePageNavigationUrl( );
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
        return oxUBase::_addPageNrParam( $sUrl, $iPage, $iLang );
    }

    /**
     * Template variable getter. Returns article list
     *
     * @return array
     */
    public function getArticleList()
    {
        if ( $this->_aArticleList === null ) {
            if ( ( $this->getTag() ) ) {
                $this->_aArticleList = $this->_loadArticles( null );
            }
        }
        return $this->_aArticleList;
    }

    /**
     * Template variable getter. Returns current tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->_sTag;
    }

    /**
     * Template variable getter. Returns tag title
     *
     * @return string
     */
    public function getTitle()
    {
        if ( $this->_sTagTitle === null ) {
            $this->_sTagTitle = false;
            if ( ( $sTag = $this->getTag() ) ) {
                $oStr = getStr();
                $sTitle = $oStr->ucfirst( $sTag );
                $this->_sTagTitle = $oStr->htmlspecialchars( $sTitle );
            }
        }
        return $this->_sTagTitle;
    }

    /**
     * Template variable getter. Returns template location
     *
     * @return string
     */
    public function getTemplateLocation()
    {
        if ( $this->_sTemplateLocation === null ) {
            $this->_sTemplateLocation = false;
            if ( ( $sTag = $this->getTag() ) ) {
                $oStr = getStr();
                $sTitle = $oStr->ucfirst( $sTag );
                $this->_sTemplateLocation = oxLang::getInstance()->translateString('TAGS')." / ".$oStr->htmlspecialchars( $sTitle );
            }
        }
        return $this->_sTemplateLocation;
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
     * Template variable getter. Returns active search
     *
     * @return object
     */
    public function getActiveCategory()
    {
        return $this->getActTag();
    }

    /**
     * Returns current view keywords seperated by comma
     * (calls parent::_collectMetaKeyword())
     *
     * @param string $sKeywords data to use as keywords
     *
     * @return string
     */
    protected function _prepareMetaKeyword( $sKeywords )
    {
        return parent::_collectMetaKeyword( $sKeywords );
    }

    /**
     * Returns current view meta description data
     * (calls parent::_collectMetaDescription())
     *
     * @param string $sMeta     category path
     * @param int    $iLength   max length of result, -1 for no truncation
     * @param bool   $blDescTag if true - performs additional dublicate cleaning
     *
     * @return  string  $sString    converted string
     */
    protected function _prepareMetaDescription( $sMeta, $iLength = 1024, $blDescTag = false )
    {
        return parent::_collectMetaDescription( $sMeta, $iLength, $blDescTag );
    }
}
