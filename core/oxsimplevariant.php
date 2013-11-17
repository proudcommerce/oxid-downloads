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
 * $Id: oxshoplist.php 16303 2009-02-05 10:23:41Z rimvydas.paskevicius $
 */

/**
 * Lightweight variant handler. Implemnets only absolutely needed oxArticle methods.
 *
 * @package core
 */
class oxSimpleVariant extends oxI18n
{

    /**
     * Use lazy loading for this item
     *
     * @var bool
     */
    protected $_blUseLazyLoading = true;

    /**
     * Variant price
     *
     * @var oxPrice
     */
    protected $_oPrice = null;

    /**
     * Parent article
     *
     * @var oxArticle
     */
    protected $_oParent = null;

    /**
     * Initializes instance
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_sCacheKey = "simplevariants";
        $this->init( 'oxarticles' );
    }

    /**
     * Implementing (faking) performance friendly method from oxArticle
     *oxbase
     *
     * @return null
     */
    public function getSelectLists()
    {
        return null;
    }

    /**
     * Implementing (faking) performance friendly method from oxArticle
     *
     * @return oxPrice
     */
    public function getPrice()
    {
        if (!is_null($this->_oPrice)) {
            return $this->_oPrice;
        }

        $this->_oPrice = oxNew("oxPrice");
        $dPrice = $this->oxarticles__oxprice->value;
        if (!$dPrice) {
            $dPrice = $this->_getParentPrice();
        }

        $this->_oPrice->setPrice($dPrice, $this->_dVat);

        $this->_applyParentVat($this->_oPrice);
        $this->_applyCurrency($this->_oPrice);
        // apply discounts
        $this->_applyParentDiscounts($this->_oPrice);

        return $this->_oPrice;
    }

    /**
     * Applies currency factor
     *
     * @param oxPrice $oPrice Price object
     * @param object  $oCur   Currency object
     *
     * @return null
     */
    protected function _applyCurrency(oxPrice $oPrice, $oCur = null )
    {
        if ( !$oCur ) {
            $oCur = $this->getConfig()->getActShopCurrencyObject();
        }

        $oPrice->multiply($oCur->rate);
    }

    /**
     * Applies discounts which should be applied in general case (for 0 amount)
     *
     * @param oxprice $oPrice Price object
     *
     * @return null
     */
    protected function _applyParentDiscounts( $oPrice )
    {
        $oParent = $this->getParent();
        if (!($oParent instanceof oxarticle)) {
            return;
        }

        $oParent->applyDiscountsForVariant( $oPrice );

    }

    /**
     * apply parent article VAT to given price
     *
     * @param oxPrice $oPrice price object
     *
     * @return null
     */
    protected function _applyParentVat($oPrice)
    {
        $oParent = $this->getParent();
        if (!($oParent instanceof oxarticle)) {
            return;
        }

        $oParent->applyVats($oPrice);
    }

    /**
     * Price setter
     *
     * @param object $oPrice price object
     *
     * @return null;
     */
    public function setPrice($oPrice)
    {
        $this->_oPrice = $oPrice;
    }

    /**
     * Returns formated product price.
     *
     * @return double
     */
    public function getFPrice()
    {
        if ( $oPrice = $this->getPrice() ) {
            return oxLang::getInstance()->formatCurrency( $oPrice->getBruttoPrice() );
        } else {
            return null;
        }
    }

    /**
     * Sets parent article
     *
     * @param oxArticle $oParent Parent article
     *
     * @return null
     */
    public function setParent($oParent)
    {
        $this->_oParent = $oParent;
    }

    /**
     * Parent article getter.
     *
     * @return oxArticle
     */
    public function getParent()
    {
        return $this->_oParent;
    }

    /**
     * Returns parent price. Assuming variant parent has been assigned before function execution.
     *
     * @return double
     */
    protected function _getParentPrice()
    {

        if (isset($this->_oParent->oxarticles__oxprice->value)) {
            return $this->_oParent->oxarticles__oxprice->value;
        }

        return 0;
    }
}