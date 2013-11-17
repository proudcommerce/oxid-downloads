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
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: wrapping.php 26071 2010-02-25 15:12:55Z sarunas $
 */

/**
 * Managing Gift Wrapping
 */
class Wrapping extends oxUBase
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'wrapping.tpl';

    /**
     * Basket items array
     *
     * @var array
     */
    protected $_aBasketItemList = null;

    /**
     * Wrapping objects list
     *
     * @var oxlist
     */
    protected $_oWrappings = null;

    /**
     * Card objects list
     *
     * @var oxlist
     */
    protected $_oCards = null;

    /**
     * Executes parent::render(), loads basket article objects,
     * forms wrapping and gift cards list. Returns name of template
     * file to render wishlist::_sThisTemplate.
     *
     * Template variables:
     * <b>basketitemlist</b>, <b>wrappings</b>, <b>cards</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        //for older templates
        $this->_aViewData['basketitemlist'] = $this->getBasketItems();
        $this->_aViewData['wrappings']      = $this->getWrappingList();
        $this->_aViewData['cards']          = $this->getCardList();

        return $this->_sThisTemplate;
    }

    /**
     * Returns array of shopping basket articles
     *
     * @return array
     */
    public function getBasketItems()
    {
        if ( $this->_aBasketItemList === null ) {
            $this->_aBasketItemList = false;

            // passing basket articles
            if ( $oBasket = $this->getSession()->getBasket() ) {
                $this->_aBasketItemList = $oBasket->getBasketArticles();
            }
        }

        return $this->_aBasketItemList;
    }

    /**
     * Return basket wrappings list if available
     *
     * @return oxlist
     */
    public function getWrappingList()
    {
        if ( $this->_oWrappings === null ) {
            $this->_oWrappings = new oxlist();

            // load wrapping papers
            if ( $this->getViewConfig()->getShowGiftWrapping() ) {
                $this->_oWrappings = oxNew( 'oxwrapping' )->getWrappingList( 'WRAP' );
            }
        }
        return $this->_oWrappings;
    }

    /**
     * Returns greeting cards list if available
     *
     * @return oxlist
     */
    public function getCardList()
    {
        if ( $this->_oCards === null ) {
            $this->_oCards = new oxlist();

            // load gift cards
            if ( $this->getViewConfig()->getShowGiftWrapping() ) {
                $this->_oCards = oxNew( 'oxwrapping' )->getWrappingList( 'CARD' );
            }
        }

        return $this->_oCards;
    }

    /**
     * Updates wrapping data in session basket object
     * (oxsession::getBasket()) - adds wrapping info to
     * each article in basket (if possible). Plus adds
     * gift message and chosen card ( takes from GET/POST/session;
     * oBasket::giftmessage, oBasket::chosencard). Then sets
     * basket back to session (oxsession::setBasket()). Returns
     * "order" to redirect to order confirmation secreen.
     *
     * @return string
     */
    public function changeWrapping()
    {
        $aWrapping = oxConfig::getParameter( 'wrapping' );
        if ( $this->getViewConfig()->getShowGiftWrapping() &&
             is_array( $aWrapping ) && count( $aWrapping ) ) {
            $oBasket = $this->getSession()->getBasket();

            // setting wrapping info
            foreach ( $oBasket->getContents() as $sKey => $oBasketItem ) {
                // wrapping ?
                if ( isset( $aWrapping[$sKey] ) ) {
                    $oBasketItem->setWrapping( $aWrapping[$sKey] );
                }
            }

            $oBasket->setCardMessage( oxConfig::getParameter( 'giftmessage' ) );
            $oBasket->setCardId( oxConfig::getParameter( 'chosencard' ) );
        }

        return 'order';
    }
}
