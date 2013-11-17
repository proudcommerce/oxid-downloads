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
 * $Id: oxbasketitem.php 21488 2009-08-07 08:48:48Z vilma $
 */

/**
 * UserBasketItem class, responsible for storing most important fields
 * @package core
 */
class oxBasketItem extends oxSuperCfg
{
    /**
     * Product ID
     *
     * @var string
     */
    protected $_sProductId = null;

    /**
     * Basket product title
     *
     * @var string
     */
    protected $_sTitle = null;

    /**
     * Variant var select
     *
     * @var string
     */
    protected $_sVarSelect = null;

    /**
     * Product icon name
     *
     * @var string
     */
    protected $_sIcon = null;

    /**
     * Product details link
     *
     * @var string
     */
    protected $_sLink = null;

    /**
     * Item price
     *
     * @var oxPrice
     */
    protected $_oPrice = null;

    /**
     * Item unit price
     *
     * @var oxPrice
     */
    protected $_oUnitPrice = null;

    /**
     * Basket item total amount
     *
     * @var double
     */
    protected $_dAmount = 0.0;

    /**
     * Total basket item weight
     *
     * @var double
     */
    protected $_dWeight = 0;

    /**
     * Basket item select lists
     *
     * @var string
     */
    protected $_aSelList = array();

    /**
     * Shop id where product was put into basket
     *
     * @var string
     */
    protected $_sShopId = null;

    /**
     * Native product shop Id
     *
     * @var string
     */
    protected $_sNativeShopId = null;

    /**
     * Skip discounts marker
     *
     * @var boolean
     */
    protected $_blSkipDiscounts = false;

    /**
     * Persistent basket item parameters
     *
     * @var array
     */
    protected $_aPersistentParameters = array();

    /**
     * Buundle marker - marks if item is bundle or not
     *
     * @var boolean
     */
    protected $_blBundle = false;

    /**
     * Discount bundle marker - marks if item is discount bundle or not
     *
     * @var boolean
     */
    protected $_blIsDiscountArticle = false;

    /**
     * This item article
     *
     * @var oxArticle
     */
    protected $_oArticle = null;

    /**
     * Image NON SSL url
     *
     * @var string
     */
    protected $_sDimageDirNoSsl = null;

    /**
     * Image SSL url
     *
     * @var string
     */
    protected $_sDimageDirSsl = null;

    /**
     * User chosen selectlists
     *
     * @var array
     */
    protected $_aChosenSelectlist = array();

    /**
     * Used wrapping paper Id
     *
     * @var string
     */
    protected $_sWrappingId = null;

    /**
     * Wishlist user Id
     *
     * @var string
     */
     protected $_sWishId = null;

    /**
     * Wish article Id
     *
     * @var string
     */
    protected $_sWishArticleId = null;

    /**
     * Article stock check (live db check) status
     *
     * @var bool
     */
    protected $_blCheckArticleStock = true;

    /**
     * Assigns basic params to basket item
     *  - oxbasketitem::_setArticle();
     *  - oxbasketitem::setAmount();
     *  - oxbasketitem::_setSelectList();
     *  - oxbasketitem::setPersParams();
     *  - oxbasketitem::setBundle().
     *
     * @param string $sProductID product id
     * @param double $dAmount    amount
     * @param array  $aSel       selection
     * @param array  $aPersParam persistent params
     * @param bool   $blBundle   bundle
     *
     * @throws oxNoArticleException, oxOutOfStockException, oxArticleInputException
     *
     * @return null
     */
    public function init( $sProductID, $dAmount, $aSel = null, $aPersParam = null, $blBundle = null )
    {
        $this->_setArticle( $sProductID );
        $this->setAmount( $dAmount );
        $this->_setSelectList( $aSel );
        $this->setPersParams( $aPersParam );
        $this->setBundle( $blBundle );
    }

    /**
     * Initializes basket item from oxorderarticle object
     *  - oxbasketitem::_setFromOrderArticle() - assigns $oOrderArticle parameter
     *  to oxBasketItem::_oArticle. Thus oxOrderArticle is used as oxArticle (calls
     *  standard methods implemented by oxIArticle interface);
     *  - oxbasketitem::setAmount();
     *  - oxbasketitem::_setSelectList();
     *  - oxbasketitem::setPersParams().
     *
     * @param oxorderarticle $oOrderArticle order article to load info from
     *
     * @return null
     */
    public function initFromOrderArticle( $oOrderArticle )
    {
        $this->_setFromOrderArticle( $oOrderArticle );
        $this->setAmount( $oOrderArticle->oxorderarticles__oxamount->value );
        $this->_setSelectList( $oOrderArticle->getOrderArticleSelectList() );
        $this->setPersParams( $oOrderArticle->getPersParams() );
    }

    /**
     * Marks if item is discount bundle ( oxbasketitem::_blIsDiscountArticle )
     *
     * @param bool $blIsDiscountArticle if item is discount bundle
     *
     * @return null
     */
    public function setAsDiscountArticle( $blIsDiscountArticle )
    {
        $this->_blIsDiscountArticle = $blIsDiscountArticle;
    }

    /**
     * Sets stock control mode
     *
     * @param bool $blStatus stock control mode
     *
     * @return null
     */
    public function setStockCheckStatus( $blStatus )
    {
        $this->_blCheckArticleStock = $blStatus;
    }

    /**
     * Returns stock control mode
     *
     * @return bool
     */
    public function getStockCheckStatus()
    {
        return $this->_blCheckArticleStock;
    }

    /**
     * Sets item amount and weight which depends on amount
     * ( oxbasketitem::dAmount, oxbasketitem::dWeight )
     *
     * @param double $dAmount    amount
     * @param bool   $blOverride overide current amoutn or not
     *
     * @throws oxOutOfStockException, oxArticleInputException
     *
     * @return null
     */
    public function setAmount( $dAmount, $blOverride = true )
    {
        //validating amount
        $oValidator = oxNew( 'oxinputvalidator' );

        try {
            $dAmount = $oValidator->validateBasketAmount( $dAmount );
        } catch( oxArticleInputException $oEx ) {
            $oEx->setArticleNr( $this->getProductId() );
            // setting additional information for excp and then rethrowing
            throw $oEx;
        }

        $oArticle = $this->getArticle();


        // setting default
        $iOnStock = true;

        if ( $blOverride ) {
            $this->_dAmount  = $dAmount;
        } else {
            $this->_dAmount += $dAmount;
        }

        // checking for stock
        if ( $this->getStockCheckStatus() == true ) {
            $iOnStock = $oArticle->checkForStock( $this->_dAmount );
            if ( $iOnStock !== true ) {
                if ( $iOnStock === false ) {
                    // no stock !
                    $this->_dAmount = 0;
                } else {
                    // limited stock
                    $this->_dAmount = $iOnStock;
                    $blOverride = true;
                }
            }
        }

        // calculating general weight
        $this->_dWeight = $oArticle->oxarticles__oxweight->value * $this->_dAmount;

        if ( $iOnStock !== true ) {
            $oEx = oxNew( 'oxOutOfStockException' );
            $oEx->setMessage( 'EXCEPTION_OUTOFSTOCK_OUTOFSTOCK' );
            $oEx->setArticleNr( $oArticle->oxarticles__oxartnum->value );
            $oEx->setRemainingAmount( $this->_dAmount );
            throw $oEx;
        }
    }

    /**
     * Sets $this->oPrice
     *
     * @param object $oPrice price
     *
     * @return null
     */
    public function setPrice( $oPrice )
    {
        $this->_oPrice = oxNew( 'oxprice' );
        $this->_oPrice->setBruttoPriceMode();
        $this->_oPrice->setVat( $oPrice->getVAT() );
        $this->_oPrice->addPrice( $oPrice );

        $this->_oUnitPrice = oxNew( 'oxprice' );
        $this->_oUnitPrice->setBruttoPriceMode();
        $this->_oUnitPrice->setVat( $oPrice->getVAT() );
        $this->_oUnitPrice->addPrice( $oPrice );

        $this->_setDeprecatedValues();
    }

    /**
     * Getter which returns image path according to SSL mode
     *
     * @return string
     */
    public function getImageUrl()
    {
        $blIsSSl = $this->getConfig()->isSsl();
        if ( $blIsSSl ) {
            return $this->_sDimageDirSsl;
        } else {
            return $this->_sDimageDirNoSsl;
        }
    }

    /**
     * Retrieves the article
     *
     * @param string $sProductId product id
     *
     * @throws oxArticleException exception
     *
     * @return oxarticle
     */
    public function getArticle( $sProductId = null )
    {
        if ( $this->_oArticle === null ) {
            $sProductId = $sProductId ? $sProductId : $this->_sProductId;
            if ( !$sProductId ) {
                //this excpetion may not be caught, anyhow this is a critical exception
                $oEx = oxNew( 'oxArticleException' );
                $oEx->setMessage( 'EXCEPTION_ARTICLE_NOPRODUCTID' );
                throw $oEx;
            }

            $this->_oArticle = oxNew( 'oxarticle' );

            // performance:
            // - skipping variants loading
            // - skipping 'ab' price info
            // - load parent field
            $this->_oArticle->setNoVariantLoading( true );
            $this->_oArticle->setSkipAbPrice( true );
            $this->_oArticle->setLoadParentData( true );
            if ( !$this->_oArticle->load( $sProductId ) ) {
                $oEx = oxNew( 'oxNoArticleException' );
                $oEx->setMessage( 'EXCEPTION_ARTICLE_ARTICELDOESNOTEXIST' );
                $oEx->setArticleNr( $sProductId );
                throw $oEx;
            }

            // cant put not buyable product to basket
            if ( !$this->_oArticle->isBuyable() ) {
                $oEx = oxNew( 'oxArticleInputException' );
                $oEx->setMessage( 'EXCEPTION_ARTICLE_ARTICELNOTBUYABLE' );
                $oEx->setArticleNr( $sProductId );
                throw $oEx;
            }
        }

        return $this->_oArticle;
    }

    /**
     * Returns bundle amount
     *
     * @return double
     */
    public function getdBundledAmount()
    {
        return $this->isBundle()?$this->_dAmount:0;
    }

    /**
     * Returns the price.
     *
     * @return oxprice
     */
    public function getPrice()
    {
        return $this->_oPrice;
    }

    /**
     * Returns the price.
     *
     * @return oxprice
     */
    public function getUnitPrice()
    {
        return $this->_oUnitPrice;
    }

    /**
     * Returns the amount of item.
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->_dAmount;
    }

    /**
     * returns the price.
     *
     * @return double
     */
    public function getWeight()
    {
        return $this->_dWeight;
    }

    /**
     * Returns product title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_sTitle;
    }

    /**
     * Returns product icon URL
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->_sIcon;
    }

    /**
     * Returns product details URL
     *
     * @return string
     */
    public function getLink()
    {
        return $this->_sLink;
    }

    /**
     * Returns ID of shop from which this product was added into basket
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->_sShopId;
    }

    /**
     * Returns user passed select list information
     *
     * @return array
     */
    public function getSelList()
    {
        return $this->_aSelList;
    }

    /**
     * Returns user chosen select list information
     *
     * @return array
     */
    public function getChosenSelList()
    {
        return $this->_aChosenSelectlist;
    }

    /**
     * Returns true if product is bundle
     *
     * @return bool
     */
    public function isBundle()
    {
        return $this->_blBundle;
    }

    /**
     * Returns true if product is given as discount
     *
     * @return bool
     */
    public function isDiscountArticle()
    {
        return $this->_blIsDiscountArticle;
    }

    /**
     * Returns true if discount must be skipped for current product
     *
     * @return bool
     */
    public function isSkipDiscount()
    {
        return $this->_blSkipDiscounts;
    }

     /**
     * Special getter function for backwards compatability.
     * Executes methods by rule "get".$sVariableName and returns
     * result processed by executed function.
     *
     * @param string $sName parameter name
     *
     * @return mixed
     */
    public function __get( $sName )
    {
        if ( $sName == 'oProduct' ) {
            return $this->getArticle();
        }
    }

    /**
     * Does not return _oArticle var on serialisation
     *
     * @return array
     */
    public function __sleep()
    {
        $aRet = array();
        foreach ( get_object_vars( $this ) as $sKey => $sVar ) {
            if ( $sKey != '_oArticle' ) {
                $aRet[] = $sKey;
            }
        }
        return $aRet;
    }

    /**
     * Sets object deprecated values
     *
     * @deprecated This method is deprecated as all deprecated values are
     *
     * @return null
     */
    protected function _setDeprecatedValues()
    {
        $oPrice = $this->getPrice();

        // product VAT percent
        $this->vatPercent = $this->getVatPercent();

        // VAT value
        $this->dvat = $oPrice->getVATValue();

        // unit non formatted price
        $this->dprice = $oPrice->getBruttoPrice();

        // formatted unit price
        $this->fprice = $this->getFUnitPrice();

        // non formatted unit NETTO price
        $this->dnetprice = $oPrice->getNettoPrice();

        $this->_oPrice->multiply( $this->getAmount() );

        // non formatted total NETTO price
        $this->dtotalnetprice = $oPrice->getNettoPrice();

        // formatter total NETTO price
        $this->ftotalnetprice = oxLang::getInstance()->formatCurrency( $oPrice->getNettoPrice() );

        // non formatted total BRUTTO price
        $this->dtotalprice = $oPrice->getBruttoPrice();

        // formatted total BRUTTO price
        $this->ftotalprice = $this->getFTotalPrice();

        // total VAT
        $this->dtotalvat = $oPrice->getVATValue();

        // formatted title
        $this->title = $this->getTitle();

        // icon URL
        $this->icon  = $this->getIcon();

        // details URL
        $this->link  = $this->getLink();

        // amount of items in basket
        $this->dAmount  = $this->getAmount();

        // weight
        $this->dWeight  = $this->getWeight();

        // select list
        $this->aSelList = $this->getSelList();

        // product id
        $this->sProduct = $this->getProductId();

        // product id
        $this->varselect = $this->getVarSelect();

        // is bundle ?
        $this->blBundle = $this->isBundle();

        // bundle amount
        $this->dBundledAmount = $this->getdBundledAmount();

        // skip discounts ?
        $this->blSkipDiscounts     = $this->isSkipDiscount();

        // is discount item ?
        $this->blIsDiscountArticle = $this->isDiscountArticle();

        // dyn image location
        $this->dimagedir = $this->getImageUrl();

        // setting wrapping paper info
        $this->wrapping  = $this->getWrappingId();

        $this->oWrap = $this->getWrapping();

        $this->aPersParam = $this->getPersParams();

        //chosen select list
        $this->chosen_selectlist = $this->getChosenSelList();
    }

    /**
     * Assigns general product parameters to oxbasketitem object :
     *  - sProduct    - oxarticle object ID;
     *  - title       - products title;
     *  - icon        - icon name;
     *  - link        - details URL's;
     *  - sShopId     - current shop ID;
     *  - sNativeShopId  - article shop ID;
     *  - _sDimageDirNoSsl - NON SSL mode image path;
     *  - _sDimageDirSsl   - SSL mode image path;
     *
     * @param string $sProductId product id
     *
     * @throws oxNoArticleException exception
     *
     * @return null
     */
    protected function _setArticle( $sProductId )
    {
        $oArticle = $this->getArticle( $sProductId );

        // product ID
        $this->_sProductId = $sProductId;

        // products title
        $this->_sTitle = $oArticle->oxarticles__oxtitle->value;
        if ( $oArticle->oxarticles__oxvarselect->value ) {
            $this->_sTitle     = $this->_sTitle. ', ' . $oArticle->oxarticles__oxvarselect->value;
            $this->_sVarSelect = $oArticle->oxarticles__oxvarselect->value;
        }

        // icon and details URL's
        $this->_sIcon = $oArticle->oxarticles__oxicon->value;
        $this->_sLink = $oArticle->getLink();

        // shop Ids
        $this->_sShopId       = $this->getConfig()->getShopId();
        $this->_sNativeShopId = $oArticle->oxarticles__oxshopid->value;

        // SSL/NON SSL image paths
        $this->_sDimageDirNoSsl = $oArticle->nossl_dimagedir;
        $this->_sDimageDirSsl   = $oArticle->ssl_dimagedir;
    }

    /**
     * Assigns general product parameters to oxbasketitem object:
     *  - sProduct    - oxarticle object ID;
     *  - title       - products title;
     *  - sShopId     - current shop ID;
     *  - sNativeShopId  - article shop ID;
     *
     * @param oxorderarticle $oOrderArticle order article
     *
     * @return null
     */
    protected function _setFromOrderArticle( $oOrderArticle )
    {
        // overriding whole article
        $this->_oArticle = $oOrderArticle;

        // product ID
        $this->_sProductId = $oOrderArticle->getProductId();

        // products title
        $this->_sTitle = $oOrderArticle->oxarticles__oxtitle->value;

        // shop Ids
        $this->_sShopId       = $this->getConfig()->getShopId();
        $this->_sNativeShopId = $oOrderArticle->oxarticles__oxshopid->value;
    }

    /**
     * Stores item select lists ( oxbasketitem::aSelList )
     *
     * @param array $aSelList item select lists
     *
     * @return null
     */
    protected function _setSelectList( $aSelList )
    {
        // checking for default select list
        $aSelectLists = $this->getArticle()->getSelectLists();
        if ( !$aSelList || is_array($aSelList) && count($aSelList) == 0 ) {
            if ( $iSelCnt = count( $aSelectLists ) ) {
                $aSelList = array_fill( 0, $iSelCnt, '0' );
            }
        }

        $this->_aSelList = $aSelList;

        //
        if ( count( $this->_aSelList ) && is_array($this->_aSelList) ) {
            foreach ( $this->_aSelList as $conkey => $iSel ) {
                $this->_aChosenSelectlist[$conkey] = new Oxstdclass();
                $this->_aChosenSelectlist[$conkey]->name  = $aSelectLists[$conkey]['name'];
                $this->_aChosenSelectlist[$conkey]->value = $aSelectLists[$conkey][$iSel]->name;
            }
        }
    }

    /**
     * Get persistent parameters ( oxbasketitem::_aPersistentParameters )
     *
     * @return array
     */
    public function getPersParams()
    {
        return $this->_aPersistentParameters;
    }

    /**
     * Stores items persistent parameters ( oxbasketitem::_aPersistentParameters )
     *
     * @param array $aPersParam items persistent parameters
     *
     * @return null
     */
    public function setPersParams( $aPersParam )
    {
        $this->_aPersistentParameters = $aPersParam;
    }

    /**
     * Marks if item is bundle ( oxbasketitem::blBundle )
     *
     * @param bool $blBundle if item is bundle
     *
     * @return null
     */
    public function setBundle( $blBundle )
    {
        $this->_blBundle = $blBundle;
    }

    /**
     * Used to set "skip discounts" status for basket item
     *
     * @param bool $blSkip set true to skip discounts
     *
     * @return null
     */
    public function setSkipDiscounts( $blSkip )
    {
        $this->_blSkipDiscounts = $blSkip;
    }

    /**
     * Returns product Id
     *
     * @return string product id
     */
    public function getProductId()
    {
        return $this->_sProductId;
    }

    /**
     * Product wrapping paper id setter
     *
     * @param string $sWrapId wrapping paper id
     *
     * @return null
     */
    public function setWrapping( $sWrapId )
    {
        $this->_sWrappingId = $sWrapId;
    }

    /**
     * Returns wrapping paper ID (if such was applied)
     *
     * @return string
     */
    public function getWrappingId()
    {
        return $this->_sWrappingId;
    }

    /**
     * Returns basket item wrapping object
     *
     * @return oxwrapping
     */
    public function getWrapping()
    {
        $oWrap = null;
        if ( $sWrapId = $this->getWrappingId() ) {
            $oWrap = oxNew( 'oxwrapping' );
            $oWrap->load( $sWrapId );
        }
        return $oWrap;
    }

    /**
     * Returns wishlist user Id
     *
     * @return string
     */
    public function getWishId()
    {
        return $this->_sWishId;
    }

    /**
     * Wish user id setter
     *
     * @param string $sWishId user id
     *
     * @return null
     */
    public function setWishId( $sWishId )
    {
        $this->_sWishId = $sWishId;
    }

    /**
     * Wish article Id setter
     *
     * @param string $sArticleId wish article id
     *
     * @return null
     */
    public function setWishArticleId( $sArticleId )
    {
        $this->_sWishArticleId = $sArticleId;
    }

    /**
     * Returns wish article Id
     *
     * @return string
     */
    public function getWishArticleId()
    {
        return $this->_sWishArticleId;
    }

    /**
     * Returns formatted unit price
     *
     * @return string
     */
    public function getFUnitPrice()
    {
        return oxLang::getInstance()->formatCurrency( $this->getUnitPrice()->getBruttoPrice() );
    }

    /**
     * Returns formatted total price
     *
     * @return string
     */
    public function getFTotalPrice()
    {
        return oxLang::getInstance()->formatCurrency( $this->getPrice()->getBruttoPrice() );
    }

    /**
     * Returns formatted total price
     *
     * @return string
     */
    public function getVatPercent()
    {
        return oxLang::getInstance()->formatVat( $this->getPrice()->getVat() );
    }

    /**
     * Returns varselect value
     *
     * @return string
     */
    public function getVarSelect()
    {
        return $this->_sVarSelect;
    }
}
