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
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: content.php 40522 2011-12-12 12:40:30Z linas.kukulskis $
 */

/**
 * CMS - loads pages and displays it
 */
class Content extends oxUBase
{
    /**
     * Content id.
     * @var string
     */
    protected $_sContentId = null;

    /**
     * Content object
     * @var object
     */
    protected $_oContent = null;

    /**
     * Current view template
     * @var string
     */
    protected $_sThisTemplate = 'page/info/content.tpl';

    /**
     * Current view plain template
     * @var string
     */
    protected $_sThisPlainTemplate = 'page/info/content_plain.tpl';

    /**
     * Current view content category (if available)
     * @var oxcontent
     */
     protected $_oContentCat = null;

     /**
      * Ids of contents which can be accessed without any restrictions when private sales is ON
      * @var array
      */
     protected $_aPsAllowedContents = array( "oxagb", "oxrightofwithdrawal", "oximpressum" );

     /**
     * Current view content title
     * @var sting
     */
    protected $_sContentTitle = null;

    /**
     * Returns prefix ID used by template engine.
     *
     * @return string    $this->_sViewId
     */
    public function getViewId()
    {
        if ( !isset( $this->_sViewId ) ) {
            $this->_sViewId = parent::getViewId().'|'.oxConfig::getParameter( 'oxcid' );
        }
        return $this->_sViewId;
    }

    /**
     * Executes parent::render(), passes template variables to
     * template engine and generates content. Returns the name
     * of template to render content::_sThisTemplate
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        $oContent = $this->getContent();
        if ( $oContent && !$this->_canShowContent( $oContent->oxcontents__oxloadid->value ) ) {
            oxUtils::getInstance()->redirect( $this->getConfig()->getShopHomeURL() . 'cl=account' );
        }

        $sTpl = false;
        if ( $sTplName = $this->_getTplName() ) {
            $this->_sThisTemplate = $sTpl = $sTplName;
        } elseif ( $oContent ) {
            $sTpl = $oContent->getId();
        }

        if ( !$sTpl ) {
            error_404_handler();
        }

        // sometimes you need to display plain templates (e.g. when showing popups)
        if ( $this->showPlainTemplate() ) {
            $this->_sThisTemplate = $this->_sThisPlainTemplate;
        }

        $this->getViewConfig()->setViewConfigParam( 'tpl', $sTpl );
        return $this->_sThisTemplate;
    }

    /**
     * Checks if content can be shown
     *
     * @param string $sContentIdent ident of content to display
     *
     * @return bool
     */
    protected function _canShowContent( $sContentIdent )
    {
        $blCan = true;
        if ( $this->isEnabledPrivateSales() &&
             !$this->getUser() && !in_array( $sContentIdent, $this->_aPsAllowedContents ) ) {
            $blCan = false;
        }
        return $blCan;
    }

    /**
     * Returns current view meta data
     * If $sMeta parameter comes empty, sets to it current content title
     *
     * @param string $sMeta     category path
     * @param int    $iLength   max length of result, -1 for no truncation
     * @param bool   $blDescTag if true - performs additional dublicate cleaning
     *
     * @return string
     */
    protected function _prepareMetaDescription( $sMeta, $iLength = 200, $blDescTag = false )
    {
        if ( !$sMeta ) {
            $sMeta = $this->getContent()->oxcontents__oxtitle->value;
        }
        return parent::_prepareMetaDescription( $sMeta, $iLength, $blDescTag );
    }

    /**
     * Returns current view keywords seperated by comma
     * If $sKeywords parameter comes empty, sets to it current content title
     *
     * @param string $sKeywords               data to use as keywords
     * @param bool   $blRemoveDuplicatedWords remove dublicated words
     *
     * @return string
     */
    protected function _prepareMetaKeyword( $sKeywords, $blRemoveDuplicatedWords = true )
    {
        if ( !$sKeywords ) {
            $sKeywords = $this->getContent()->oxcontents__oxtitle->value;
        }
        return parent::_prepareMetaKeyword( $sKeywords, $blRemoveDuplicatedWords );
    }

    /**
     * If current content is assigned to category returns its object
     *
     * @return oxcontent
     */
    public function getContentCategory()
    {
        if ( $this->_oContentCat === null ) {
            // setting default status ..
            $this->_oContentCat = false;
            if ( ( $oContent = $this->getContent() ) && $oContent->oxcontents__oxtype->value == 2 ) {
                $this->_oContentCat = $oContent;
            }
        }
        return $this->_oContentCat;
    }

    /**
     * Returns true if user forces to display plain template or
     * if private sales switched ON and user is not logged in
     *
     * @return bool
     */
    public function showPlainTemplate()
    {
        $blPlain = (bool) oxConfig::getParameter( 'plain' );
        if ( $blPlain === false ) {
            $oUser = $this->getUser();
            if ( $this->isEnabledPrivateSales() &&
                 ( !$oUser || ( $oUser && !$oUser->isTermsAccepted() ) ) ) {
                $blPlain = true;
            }
        }

        return (bool) $blPlain;
    }

    /**
     * Returns active content id to load its seo meta info
     *
     * @return string
     */
    protected function _getSeoObjectId()
    {
        return oxConfig::getParameter( 'oxcid' );
    }

    /**
     * Template variable getter. Returns active content id.
     * If no content id specified, uses "impressum" content id
     *
     * @return object
     */
    public function getContentId()
    {
        if ( $this->_sContentId === null ) {
            $oConfig    = $this->getConfig();
            $sContentId = oxConfig::getParameter( 'oxcid' );

            if ( !$sContentId ) {
                //trying to load content id from tpl variable
                //usage of tpl variable as content id is deprecated
                $sContentId = oxConfig::getParameter( 'tpl' );
            }

            if ( !$sContentId ) {
                //get default content id (impressum)
                $sContentId = parent::getContentId();
            }

            $this->_sContentId = false;
            $oContent = oxNew( 'oxcontent' );
            if ( $oContent->load( $sContentId ) && $oContent->oxcontents__oxactive->value ) {
                $this->_sContentId = $sContentId;
                $this->_oContent = $oContent;
            }
        }
        return $this->_sContentId;
    }

    /**
     * Template variable getter. Returns active content
     *
     * @return object
     */
    public function getContent()
    {
        if ( $this->_oContent === null ) {
            $this->_oContent = false;
            if ( $this->getContentId() ) {
                return $this->_oContent;
            }
        }
        return $this->_oContent;
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
        return $this->getContent();
    }

    /**
     * Returns name of template
     *
     * @return string
     */
    protected function _getTplName()
    {
        // assign template name
        $sTplName = oxConfig::getParameter( 'tpl');

        if ( $sTplName ) {
            // security fix so that you cant access files from outside template dir
            $sTplName = basename( $sTplName );

            //checking if it is template name, not content id
            if ( !getStr()->preg_match("/\.tpl$/", $sTplName) ) {
                $sTplName = null;
            } else {
                $sTplName = 'message/'.$sTplName;
            }
        }

        return $sTplName;
    }

    /**
     * Returns Bread Crumb - you are here page1/page2/page3...
     *
     * @return array
     */
    public function getBreadCrumb()
    {
        $oContent = $this->getContent();

        $aPaths = array();
        $aPath = array();

        $aPath['title'] = $oContent->oxcontents__oxtitle->value;
        $aPath['link']  = $this->getLink();
        $aPaths[] = $aPath;

        return $aPaths;
    }

    /**
     * Template variable getter. Returns tag title
     *
     * @return string
     */
    public function getTitle()
    {
        if ( $this->_sContentTitle === null ) {
            $oContent = $this->getContent();
            $this->_sContentTitle = $oContent->oxcontents__oxtitle->value;
        }

        return $this->_sContentTitle;
    }
}
