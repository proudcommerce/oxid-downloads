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
 * $Id: rss.php 21912 2009-08-27 12:05:37Z arvydas $
 */

/**
 * Shop RSS page.
 */
class Rss extends oxUBase
{
    /**
     * current rss object
     * @var oxRssFeed
     */
    protected $_oRss = null;

    /**
     * Current rss channel
     * @var object
     */
    protected $_oChannel = null;

    /**
     * Xml start and end definition
     * @var array
     */
    protected $_aXmlDef = null;

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'rss.tpl';

    /**
     * initializes objects
     *
     * @access public
     * @return void
     */
    public function init()
    {
        if (null !== ($iCur = oxConfig::getParameter('cur'))) {
            oxSession::setVar('currency', (int) $iCur);
        }
        parent::init();
        $this->_oRss = oxNew('oxRssFeed');
    }

    /**
     * Renders requested RSS feed
     *
     * Template variables:
     * <b>rss</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        $oSmarty = oxUtilsView::getInstance()->getSmarty();

        foreach ( array_keys( $this->_aViewData ) as $sViewName ) {
            $oSmarty->assign_by_ref( $sViewName, $this->_aViewData[$sViewName] );
        }

        $oSmarty->assign('channel', $this->getChannel());
        $oSmarty->assign('xmldef', $this->getXmlDef());

        header( "Content-Type: text/xml; charset=".oxLang::getInstance()->translateString( "charset" ) );
        echo $this->_processOutput( $oSmarty->fetch('rss.tpl', $this->getViewId()) );

        // returned rss xml: no further processing
        exit (0);
    }

    /**
     * Processes xml before outputting to user
     *
     * @param string $sInput input to process
     *
     * @return string
     */
    protected function _processOutput( $sInput )
    {
        return getStr()->recodeEntities( $sInput );
    }

    /**
     * getTopShop loads top shop articles to rss
     *
     * @access public
     * @return void
     */
    public function topshop()
    {
        if (in_array('oxrss_topshop', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $this->_oRss->loadTopInShop();
        } else {
            error_404_handler();
        }
    }

    /**
     * loads newest shop articles
     *
     * @access public
     * @return void
     */
    public function newarts()
    {
        if (in_array('oxrss_newest', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $this->_oRss->loadNewestArticles();
        } else {
            error_404_handler();
        }
    }

    /**
     * loads category articles
     *
     * @access public
     * @return void
     */
    public function catarts()
    {
        if (in_array('oxrss_categories', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $oCat = oxNew('oxCategory');
            if ($oCat->load(oxConfig::getParameter('cat'))) {
                $this->_oRss->loadCategoryArticles($oCat);
            }
        } else {
            error_404_handler();
        }
    }

    /**
     * loads search articles
     *
     * @access public
     * @return void
     */
    public function searcharts()
    {
        if (in_array('oxrss_search', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $this->_oRss->loadSearchArticles( oxConfig::getParameter('searchparam', true), oxConfig::getParameter('searchcnid'), oxConfig::getParameter('searchvendor'), oxConfig::getParameter('searchmanufacturer'));
        } else {
            error_404_handler();
        }
    }

    /**
     * loads recommendation lists
     *
     * @access public
     * @return void
     */
    public function recommlists()
    {
        if (in_array('oxrss_recommlists', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $oArticle = oxNew('oxarticle');
            if ($oArticle->load(oxConfig::getParameter('anid'))) {
                $this->_oRss->loadRecommLists($oArticle);
                return;
            }
        }
        error_404_handler();
    }

    /**
     * loads recommendation list articles
     *
     * @access public
     * @return void
     */
    public function recommlistarts()
    {
        if (in_array('oxrss_recommlistarts', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $oRecommList = oxNew('oxrecommlist');
            if ($oRecommList->load(oxConfig::getParameter('recommid'))) {
                $this->_oRss->loadRecommListArticles($oRecommList);
                return;
            }
        }
        error_404_handler();
    }

    /**
     * getBargain loads top shop articles to rss
     *
     * @access public
     * @return void
     */
    public function bargain()
    {
        if (in_array('oxrss_bargain', $this->getConfig()->getConfigParam( 'aRssSelected' ))) {
            $this->_oRss->loadBargain();
        } else {
            error_404_handler();
        }
    }

    /**
     * Template variable getter. Returns rss channel
     *
     * @return object
     */
    public function getChannel()
    {
        if ( $this->_oChannel === null ) {
            $this->_oChannel = false;
            if ( $this->_oRss ) {
                $this->_oChannel = $this->_oRss->getChannel();
            }
        }
        return $this->_oChannel;
    }

    /**
     * Template variable getter. Returns xml start and end definition
     *
     * @return array
     */
    public function getXmlDef()
    {
        if ( $this->_aXmlDef === null ) {
            $this->_aXmlDef = false;
            $this->_aXmlDef = array('start'=>'<?xml', 'end'=>'?>');
        }
        return $this->_aXmlDef;
    }

}
