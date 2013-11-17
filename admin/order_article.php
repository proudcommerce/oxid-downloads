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
 * @package admin
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: order_article.php 20543 2009-06-30 06:24:21Z arvydas $
 */

/**
 * Admin order article manager.
 * Collects order articles information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders -> Articles.
 * @package admin
 */
class Order_Article extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxorder and oxvoucherlist
     * objects, appends voucherlist information to order object and passes data
     * to Smarty engine, returns name of template file "order_article.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = oxConfig::getParameter( "oxid" );
        if ( $soxId != "-1" && isset( $soxId ) ) {
            // load object
            $oOrder = oxNew( "oxorder" );
            $oOrder->load( $soxId );

            $this->_aViewData["edit"] =  $oOrder;
        }

        return "order_article.tpl";
    }

    /**
     * Adds article to order list.
     *
     * @return null
     */
    public function addThisArticle()
    {
        if ( ( $sArtNum = oxConfig::getParameter( 'sArtNum' ) ) ) {
            $dAmount  = oxConfig::getParameter( 'am' );
            $sOrderId = oxConfig::getParameter( 'oxid' );

            //get article id
            $sQ = "select oxid from oxarticles where oxarticles.oxartnum = '{$sArtNum}'";
            if ( ( $sArtId = oxDb::getDb()->getOne( $sQ ) ) && $dAmount > 0 ) {
                $oOrderArticle = oxNew( 'oxorderArticle' );
                $oOrderArticle->oxorderarticles__oxartid  = new oxField( $sArtId );
                $oOrderArticle->oxorderarticles__oxartnum = new oxField( $sArtNum );
                $oOrderArticle->oxorderarticles__oxamount = new oxField( $dAmount );
                $aOrderArticles[] = $oOrderArticle;

                $oOrder = oxNew( 'oxorder' );
                if ( $oOrder->load( $sOrderId ) ) {
                    $oOrder->recalculateOrder( $aOrderArticles );
                }
            }
        }
    }

    /**
     * Removes article from order list.
     *
     * @return null
     */
    public function deleteThisArticle()
    {
        // get article id
        $sOrderArtId = oxConfig::getParameter( 'sArtID' );
        $sOrderId = oxConfig::getParameter( 'oxid' );

        $oOrderArticle = oxNew( 'oxorderarticle' );
        $oOrder = oxNew( 'oxorder' );

        // order and order article exits?
        if ( $oOrderArticle->load( $sOrderArtId ) && $oOrder->load( $sOrderId ) ) {
            $myConfig = $this->getConfig();

            // restoring stock info if needed
            if ( $myConfig->getConfigParam( 'blUseStock' ) ) {
                $oOrderArticle->updateArticleStock( $oOrderArticle->oxorderarticles__oxamount->value, $myConfig->getConfigParam('blAllowNegativeStock') );
            }

            // deleting record
            $oOrderArticle->delete();

            // recalculating order
            $oOrder->recalculateOrder();
        }
    }

    /**
     * Cancels order item
     *
     * @return null
     */
    public function storno()
    {
        $myConfig = $this->getConfig();

        $sOrderArtId = oxConfig::getParameter( 'sArtID' );
        $oArticle = oxNew( 'oxorderarticle' );
        $oArticle->load( $sOrderArtId );

        if ( $oArticle->oxorderarticles__oxstorno->value == 1 ) {
            $oArticle->oxorderarticles__oxstorno->setValue( 0 );
            $sStockSign = -1;
        } else {
            $oArticle->oxorderarticles__oxstorno->setValue( 1 );
            $sStockSign = 1;
        }

        // stock information
        if ( $myConfig->getConfigParam( 'blUseStock' ) ) {
            $oArticle->updateArticleStock( $oArticle->oxorderarticles__oxamount->value * $sStockSign, $myConfig->getConfigParam('blAllowNegativeStock') );
        }

        $sQ = "update oxorderarticles set oxstorno = '{$oArticle->oxorderarticles__oxstorno->value}' where oxid = '{$sOrderArtId}'";
        oxDb::getDb()->execute( $sQ );

        //get article id
        $sQ = "select oxartid from oxorderarticles where oxid = '{$sOrderArtId}'";
        if ( ( $sArtId = oxDb::getDb()->getOne( $sQ ) ) ) {
            $oOrder = oxNew( 'oxorder' );
            if ( $oOrder->load( oxConfig::getParameter( 'oxid' ) ) ) {
                $oOrder->recalculateOrder();
            }
        }
    }

    /**
     * Updates order articles stock and recalculates order
     *
     * @return null
     */
    public function updateOrder()
    {
        $aOrderArticles = oxConfig::getParameter( 'aOrderArticles' );

        $oOrder = oxNew( 'oxorder' );
        if ( is_array( $aOrderArticles ) && $oOrder->load( oxConfig::getParameter( 'oxid' ) ) ) {

            $myConfig = $this->getConfig();
            $oOrderArticles = $oOrder->getOrderArticles();

            $blUseStock = $myConfig->getConfigParam( 'blUseStock' );
            foreach ( $oOrderArticles as $oOrderArticle ) {
                $sItemId = $oOrderArticle->getId();
                if ( isset( $aOrderArticles[$sItemId] ) ) {

                    // update stock
                    if ( $blUseStock ) {
                        $oOrderArticle->setNewAmount( $aOrderArticles[$sItemId]['oxamount'] );
                    } else {
                        $oOrderArticle->assign( $aOrderArticles[$sItemId] );
                        $oOrderArticle->save();
                    }
                }
            }

            // recalculating order
            $oOrder->recalculateOrder();
        }
    }
}
