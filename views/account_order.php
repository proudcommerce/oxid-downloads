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
 * $Id: account_order.php 16306 2009-02-05 10:28:05Z rimvydas.paskevicius $
 */

/**
 * Current user order history review.
 * When user is logged in order review fulfils history about user
 * submitted orders. There is some details information, such as
 * ordering date, number, recipient, order status, some base
 * ordered articles information, button to add article to basket.
 * OXID eShop -> MY ACCOUNT -> Newsletter.
 */
class Account_Order extends Account
{

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'account_order.tpl';

    /**
     * collecting orders
     *
     * @var array
     */
    protected $_aOrderList = null;

    /**
     * collecting article which ordered
     *
     * @var array
     */
    protected $_aArticlesList  = null;

    /**
     * If user is not logged in - returns name of template account_order::_sThisLoginTemplate,
     * or if user is allready logged in - returns name of template
     * account_order::_sThisTemplate
     *
     * Template variables:
     * <b>orderlist</b>
     *
     * @return string $_sThisTemplate current template file name
     */
    public function render()
    {
        parent::render();

        // is logged in ?
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        // Load Orderlist
        $this->_aViewData['orderlist'] = $this->getOrderList();

        // Load orders articles
        $this->_aViewData['articlesList'] = $this->getOrderArticleList();

        return $this->_sThisTemplate;
    }

    /**
     * Template variable getter. Returns orders
     *
     * @return array
     */
    public function getOrderList()
    {
        if ( $this->_aOrderList === null ) {
            $this->_aOrderList = false;

            // Load user Orderlist
            if ( $oUser = $this->getUser() ) {
                $this->_aOrderList = $oUser->getOrders();
            }
        }

        return $this->_aOrderList;
    }

    /**
     * Template variable getter. Returns ordered articles
     *
     * @return oxarticlelist | false
     */
    public function getOrderArticleList()
    {
        if ( $this->_aArticlesList === null ) {

            // marking as set
            $this->_aArticlesList = false;
            $oOrdersList = $this->getOrderList();
            if ( $oOrdersList && $oOrdersList->count() ) {
                $this->_aArticlesList = oxNew( 'oxarticlelist' );
                $this->_aArticlesList->loadOrderArticles( $oOrdersList );
            }
        }

        return $this->_aArticlesList;
    }
}
