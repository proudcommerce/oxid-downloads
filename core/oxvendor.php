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
 * @package core
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxvendor.php 18024 2009-04-09 11:29:24Z arvydas $
 */

/**
 * @package core
 */
class oxVendor extends oxI18n
{

    protected static $_aRootVendor = array();
    /**
     * @var string name of object core table
     */
    protected $_sCoreTbl   = 'oxvendor';

    /**
     * @var string Name of current class
     */
    protected $_sClassName = 'oxvendor';

    /**
     * Marker to load vendor article count info
     *
     * @var bool
     */
    protected $_blShowArticleCnt = false;

    /**
     * Vendor article count (default is -1, which means not calculated)
     *
     * @var int
     */
    protected $_iNrOfArticles = -1;

    /**
     * Marks that current object is managed by SEO
     *
     * @var bool
     */
    protected $_blIsSeoObject = true;

    /**
     * Visibility of a vendor
     *
     * @var int
     */
    protected $_blIsVisible;

    /**
     * has visible endors state of a category
     *
     * @var int
     */
    protected $_blHasVisibleSubCats;

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        $this->setShowArticleCnt( $this->getConfig()->getConfigParam( 'bl_perfShowActionCatArticleCnt' ) );
        parent::__construct();
        $this->init( 'oxvendor');
    }

    /**
     * Extra getter to guarantee compatibility with templates
     *
     * @param string $sName name of variable to return
     *
     * @return mixed
     */
    public function __get( $sName )
    {
        switch ( $sName ) {
            case 'oxurl':
            case 'openlink':
            case 'closelink':
            case 'link':
                $sValue = $this->getLink();
                break;
            case 'iArtCnt':
                $sValue = $this->getNrOfArticles();
                break;
            case 'isVisible':
                $sValue = $this->getIsVisible();
                break;
            case 'hasVisibleSubCats':
                $sValue = $this->getHasVisibleSubCats();
                break;
            default:
                $sValue = parent::__get( $sName );
                break;
        }
        return $sValue;
    }

    /**
     * Marker to load vendor article count info setter
     *
     * @param bool $blShowArticleCount Marker to load vendor article count
     *
     * @return null
     */
    public function setShowArticleCnt( $blShowArticleCount = false )
    {
        $this->_blShowArticleCnt = $blShowArticleCount;
    }

    /**
     * Assigns to $this object some base parameters/values.
     *
     * @param array $dbRecord parameters/values
     *
     * @return null
     */
    public function assign( $dbRecord )
    {
        parent::assign( $dbRecord );

        // vendor article count is stored in cache
        if ( $this->_blShowArticleCnt && !$this->isAdmin() ) {
            $this->_iNrOfArticles = oxUtilsCount::getInstance()->getVendorArticleCount( $this->getId() );
        }

        $this->oxvendor__oxnrofarticles = new oxField($this->_iNrOfArticles, oxField::T_RAW);
    }


    /**
     * getRootVendor creates root vendor object
     *
     * @param integer $iLang language
     *
     * @static
     * @access public
     * @return void
     */
    public static function getRootVendor( $iLang = null)
    {
        if (!isset($iLang)) {
            $iLang = oxLang::getInstance()->getBaseLanguage();
        }
        if (!self::$_aRootVendor[$iLang]) {
            $oRootVendor = oxNew( 'oxvendor' );
            $oRootVendor->setId( 'root' );
            $oRootVendor->setLanguage( $iLang );
            $oRootVendor->oxvendor__oxicon      = new oxField('');
            $oRootVendor->oxvendor__oxtitle     = new oxField(oxLang::getInstance()->translateString( 'byVendor', $iLang, false ) );
            $oRootVendor->oxvendor__oxshortdesc = new oxField('');
            self::$_aRootVendor[$iLang] = $oRootVendor;
        }
        return self::$_aRootVendor[$iLang];
    }

    /**
     * Returns vendor link Url
     *
     * @param integer $iLang language
     *
     * @return string
     */
    public function getLink( $iLang = null )
    {
        if ( isset( $iLang ) ) {
            $iLang = (int) $iLang;
            if ( $iLang == (int) $this->getLanguage() ) {
                $iLang = null;
            }
        }

        if ( $this->link === null || isset( $iLang ) ) {

            if ( oxUtils::getInstance()->seoIsActive() ) {
                $sLink = oxSeoEncoderVendor::getInstance()->getVendorUrl( $this, $iLang );
            } else {
                $sLink = $this->getStdLink( $iLang );
            }

            if ( isset( $iLang ) ) {
                return $sLink;
            }

            $this->link = $sLink;
        }

        return $this->link;
    }

    /**
     * Returns standard URL to vendor
     *
     * @param integer $iLang language
     *
     * @return string
     */
    public function getStdLink($iLang = null)
    {
        $sLangUrl = '';

        if (isset($iLang) && !oxUtils::getInstance()->seoIsActive()) {
            $iLang = (int) $iLang;
            if ($iLang != (int) $this->getLanguage()) {
                $sLangUrl = "&amp;lang={$iLang}";
            }
        }
        return $this->getConfig()->getShopHomeURL().'cl=vendorlist&amp;cnid=v_'.$this->getId().$sLangUrl;
    }

    /**
     * returns number or articles of this vendor
     *
     * @return integer
     */
    public function getNrOfArticles()
    {
        if ( !$this->_blShowArticleCnt || $this->isAdmin() ) {
            return -1;
        }

        return $this->_iNrOfArticles;
    }

    /**
     * returns the sub category array
     *
     * @return array
     */
    public function getSubCats()
    {
    }

    /**
     * returns the visibility of a vendor
     *
     * @return bool
     */
    public function getIsVisible()
    {
        return $this->_blIsVisible;
    }

    /**
     * sets the visibilty of a category
     *
     * @param bool $blVisible vendors visibility status setter
     *
     * @return null
     */
    public function setIsVisible( $blVisible )
    {
        $this->_blIsVisible = $blVisible;
    }

    /**
     * returns if a vendor has visible sub categories
     *
     * @return bool
     */
    public function getHasVisibleSubCats()
    {
        if ( !isset( $this->_blHasVisibleSubCats ) ) {
            $this->_blHasVisibleSubCats = false;
        }

        return $this->_blHasVisibleSubCats;
    }

    /**
     * sets the state of has visible sub vendors
     *
     * @param bool $blHasVisibleSubcats marker if vendor has visible subcategories
     *
     * @return null
     */
    public function setHasVisibleSubCats( $blHasVisibleSubcats )
    {
        $this->_blHasVisibleSubCats = $blHasVisibleSubcats;
    }

    /**
     * Returns article picture
     *
     * @return strin
     */
    public function getIconUrl()
    {
        return $this->getConfig()->getPictureUrl( 'icon/'.$this->oxvendor__oxicon->value );
    }

    /**
     * Empty method, called in templates when vendor is used in same code like category
     *
     * @return null
     */
    public function getContentCats()
    {
    }

    /**
     * Delete this object from the database, returns true on success.
     *
     * @param string $sOXID Object ID(default null)
     *
     * @return bool
     */
    public function delete( $sOXID = null)
    {
        if (parent::delete($sOXID)) {
            oxSeoEncoderVendor::getInstance()->onDeleteVendor($this);
            return true;
        }
        return false;
    }
}