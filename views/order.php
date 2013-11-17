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
 * $Id: order.php 22381 2009-09-17 13:11:50Z vilma $
 */

/**
 * Order manager. Arranges user ordering data, checks/validates
 * it, on success stores ordering data to DB.
 */
class Order extends oxUBase
{
    /**
     * Payment object
     * @var object
     */
    protected $_oPayment = null;

    /**
     * Active basket
     * @var object
     */
    protected $_oBasket = null;

    /**
     * Order user remark
     * @var string
     */
    protected $_sOrderRemark = null;

    /**
     * Basket articlelist
     * @var object
     */
    protected $_oBasketArtList = null;

    /**
     * Remote Address
     * @var string
     */
    protected $_sRemoteAddress = null;

    /**
     * Delivery address
     * @var object
     */
    protected $_oDelAddress = null;

    /**
     * Shipping set
     * @var object
     */
    protected $_oShipSet = null;

    /**
     * Config option "blConfirmAGB"
     * @var bool
     */
    protected $_blConfirmAGB = null;

    /**
     * Config option "blShowOrderButtonOnTop"
     * @var bool
     */
    protected $_blShowOrderButtonOnTop = null;

    /**
     * Boolean of option "blConfirmAGB" error
     * @var bool
     */
    protected $_blConfirmAGBError = null;

    /**
     * Config option "blConfirmCustInfo". Will be removed later
     * For compatibility reasons for a while. will be removed in future
     * @var bool
     */
    protected $_blConfirmCustInfo = null;

    /**
     * Boolean of option "blConfirmCustInfo" error
     * For compatibility reasons for a while. will be removed in future
     * @var bool
     */
    protected $_blConfirmCustInfoError = null;

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'order.tpl';

    /**
     * Order step marker
     *
     * @var bool
     */
    protected $_blIsOrderStep = true;

    /**
     * Count of wrapping + cards options
     */
    protected $_iWrapCnt = null;

    /**
     * Loads basket oxsession::getBasket(), sets $this->oBasket->blCalcNeeded = true to
     * recalculate, sets back basket to session oxsession::setBasket(), executes
     * parent::init().
     *
     * @return null
     */
    public function init()
    {
        // disabling performance control variable
        $this->getConfig()->setConfigParam( 'bl_perfCalcVatOnlyForBasketOrder', false );

        // recalc basket cause of payment stuff
        if ( $oBasket = $this->getBasket() ) {
            $oBasket->onUpdate();
        }

        parent::init();
    }

    /**
     * Executes parent::render(), if basket is empty - redirects to main page
     * and exits the script (oxorder::validateOrder()). Loads and passes payment
     * info to template engine. Refreshes basket articles info by additionally loading
     * each article object (oxorder::getProdFromBasket()), adds customer addressing/delivering
     * data (oxorder::getDelAddressInfo()) and delivery sets info (oxorder::getShipping()).
     * Returns name of template to render order::_sThisTemplate.
     *
     * Template variables:
     * <b>payment</b>, <b>execute_fnc</b>, <b>order_remark</b>,
     * <b>basketitemlist</b>, <b>iplog</b>,<b>oDelAdress</b>,
     * <b>oShipSet</b>, <b>blConfirmAGB</b>
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        // can we proceed with ordering ?
        $oBasket = $this->getBasket();
        $oUser = $this->getUser();

        if ( !$oBasket || !$oUser || ( $oBasket && !$oBasket->getProductsCount() ) ) {
            oxUtils::getInstance()->redirect( $myConfig->getShopHomeURL() );
        }

        // payment is set ?
        if ( !$this->getPayment() ) {
            // redirecting to payment step on error ..
            oxUtils::getInstance()->redirect( $myConfig->getShopCurrentURL().'&cl=payment' );
        }

        parent::render();

        $this->_aViewData['payment']     = $this->getPayment();
        $this->_aViewData['execute_fnc'] = $this->getExecuteFnc();

        // user order remark
        $this->_aViewData['order_remark'] = $this->getOrderRemark();

        // passing basket articles
        $this->_aViewData['basketitemlist'] = $this->getBasketArticles();

        // reload blocker
        if ( !oxSession::getVar( 'sess_challenge' ) ) {
            oxSession::setVar( 'sess_challenge', oxUtilsObject::getInstance()->generateUID() );
        }

        // passing delivery address information
        $this->_aViewData['oDelAdress'] = $this->getDelAddress();

        // multiple shipping options
        $this->_aViewData['oShipSet'] = $this->getShipSet();

        //config options ( what order confirmation checkboxes must be displayed )
        $this->_aViewData['blConfirmAGB']      = $this->isConfirmAGBActive();
        $this->_aViewData['blConfirmCustInfo'] = $this->isConfirmCustInfoActive();

        $this->_aViewData['agb_err']      = $this->isConfirmAGBError();
        $this->_aViewData['custinfo_err'] = $this->isConfirmCustInfoError();

        // for old templates
        $this->_aViewData['iswishlist'] = (bool) $this->_aViewData['iswishlist'] & $this->isWrapping();

        return $this->_sThisTemplate;
    }

    /**
     * Checks for order rules confirmation ("ord_agb", "ord_custinfo" form values)(if no
     * rules agreed - returns to order view), loads basket contents (plus applied
     * price/amount discount if available - checks for stock, checks user data (if no
     * data is set - returns to user login page). Stores order info to database
     * (oxorder::finalizeOrder()). According to sum for items automatically assigns user to
     * special user group ( oxuser::onOrderExecute(); if this option is not disabled in
     * admin). Finally you will be redirected to next page (order::_getNextStep()).
     *
     * Template variables:
     * <b>agb_err</b>, <b>custinfo_err</b>
     *
     * @return string
     */
    public function execute()
    {
        $myConfig = $this->getConfig();

        if ( !oxConfig::getParameter( 'ord_agb' ) && $myConfig->getConfigParam( 'blConfirmAGB' ) ) {
            $this->_blConfirmAGBError = 1;
            return;
        }

        // for compatibility reasons for a while. will be removed in future
        if ( oxConfig::getParameter( 'ord_custinfo' ) !== null && !oxConfig::getParameter( 'ord_custinfo' ) && $this->isConfirmCustInfoActive() ) {
            $this->_blConfirmCustInfoError =  1;
            return;
        }

        // additional check if we really really have a user now
        if ( !$oUser= $this->getUser() ) {
            return 'user';
        }

        // get basket contents
        $oBasket  = $this->getSession()->getBasket();
        if ( $oBasket->getProductsCount() ) {

            try {
                $oOrder = oxNew( 'oxorder' );

                // validating stock
                $oOrder->validateStock( $oBasket );

                // finalizing ordering process (storing order into DB, executing payment, setting status ...)
                $iSuccess = $oOrder->finalizeOrder( $oBasket, $oUser );

                // performing special actions after user finishes order (assignment to special user groups)
                $oUser->onOrderExecute( $oBasket, $iSuccess );

                // proceeding to next view
                return $this->_getNextStep( $iSuccess );
            } catch ( oxOutOfStockException $oEx ) {
                oxUtilsView::getInstance()->addErrorToDisplay( $oEx, false, true, 'basket' );
            } catch ( oxNoArticleException $oEx ) {
                oxUtilsView::getInstance()->addErrorToDisplay( $oEx );
            } catch ( oxArticleInputException $oEx ) {
                oxUtilsView::getInstance()->addErrorToDisplay( $oEx );
            }
        }
    }

    /**
     * Returns next order step. If ordering was sucessfull - returns string "thankyou" (possible
     * additional parameters), otherwise - returns string "payment" with additional
     * error parameters.
     *
     * @param integer $iSuccess status code
     *
     * @return  string  $sNextStep  partial parameter url for next step
     */
    protected function _getNextStep( $iSuccess )
    {
        $sNextStep = 'thankyou';

        //little trick with switch for multiple cases
        switch ( true ) {
            case ( is_numeric( $iSuccess ) && ( $iSuccess == 0 ) ):
                $sNextStep = 'thankyou?mailerror=1';
                break;
            case ( $iSuccess == 2 ):
                // no authentication, kick back to payment methods
                oxSession::setVar( 'payerror', 2 );
                $sNextStep = 'payment?payerror=2';
                break;
            case ( $iSuccess == 3 ):
                break;  // reload blocker activ
            case ( $iSuccess > 3 ):
                oxSession::setVar( 'payerror', $iSuccess );
                $sNextStep = 'payment?payerror='.$iSuccess;
                break;
            case ( !is_numeric( $iSuccess ) && strlen( $iSuccess ) > 0 ):
                //instead of error code getting error text and setting payerror to -1
                oxSession::setVar( 'payerror', -1 );
                $iSuccess = urlencode( $iSuccess );
                $sNextStep = 'payment?payerror=-1&payerrortext='.$iSuccess;
                break;
            default:
                break;
        }

        return $sNextStep;
    }

    /**
     * Template variable getter. Returns payment object
     *
     * @return object
     */
    public function getPayment()
    {
        if ( $this->_oPayment === null ) {
            $this->_oPayment = false;

            $oBasket = $this->getBasket();
            $oUser = $this->getUser();

            // payment is set ?
            $sPaymentid = $oBasket->getPaymentId();
            $aDynvalue  = oxConfig::getParameter( 'dynvalue' );
            $oPayment   = oxNew( 'oxpayment' );

            //getting basket price form payment
            $dBasketPrice = $oBasket->getPriceForPayment();

            if ( $sPaymentid && $oPayment->load( $sPaymentid ) &&
                $oPayment->isValidPayment( $aDynvalue, $this->getConfig()->getShopId(), $oUser, $dBasketPrice, oxConfig::getParameter( 'sShipSet' ) ) ) {
                $this->_oPayment = $oPayment;
            }
        }
        return $this->_oPayment;
    }

    /**
     * Template variable getter. Returns active basket
     *
     * @return object
     */
    public function getBasket()
    {
        if ( $this->_oBasket === null ) {
            $this->_oBasket = false;
            if ( $oBasket = $this->getSession()->getBasket() ) {
                $this->_oBasket = $oBasket;
            }
        }
        return $this->_oBasket;
    }

    /**
     * Template variable getter. Returns execution function name
     *
     * @return string
     */
    public function getExecuteFnc()
    {
        return 'execute';
    }

    /**
     * Template variable getter. Returns user remark
     *
     * @return string
     */
    public function getOrderRemark()
    {
        if ( $this->_sOrderRemark === null ) {
            $this->_sOrderRemark = false;
            if ( $sRemark = oxSession::getVar( 'ordrem' ) ) {
                $this->_sOrderRemark = $sRemark;
            }
        }
        return $this->_sOrderRemark;
    }

    /**
     * Template variable getter. Returns basket article list
     *
     * @return object
     */
    public function getBasketArticles()
    {
        if ( $this->_oBasketArtList === null ) {
            $this->_oBasketArtList = false;
            if ( $oBasket = $this->getBasket() ) {
                $this->_oBasketArtList = $oBasket->getBasketArticles();
            }
        }
        return $this->_oBasketArtList;
    }

    /**
     * Template variable getter. Returns delivery address
     *
     * @return object
     */
    public function getDelAddress()
    {
        if ( $this->_oDelAddress === null ) {
            $this->_oDelAddress = false;
            $oOrder = oxNew( 'oxorder' );
            $this->_oDelAddress = $oOrder->getDelAddressInfo();
        }
        return $this->_oDelAddress;
    }

    /**
     * Template variable getter. Returns shipping set
     *
     * @return object
     */
    public function getShipSet()
    {
        if ( $this->_oShipSet === null ) {
            $this->_oShipSet = false;
            if ( $oBasket = $this->getBasket() ) {
                $oShipSet = oxNew( 'oxdeliveryset' );
                if ( $oShipSet->load( $oBasket->getShippingId() )) {
                    $this->_oShipSet = $oShipSet;
                }
            }
        }
        return $this->_oShipSet;
    }

    /**
     * Template variable getter. Returns if option "blConfirmAGB" is on
     *
     * @return bool
     */
    public function isConfirmAGBActive()
    {
        if ( $this->_blConfirmAGB === null ) {
            $this->_blConfirmAGB = false;
            $this->_blConfirmAGB = $this->getConfig()->getConfigParam( 'blConfirmAGB' );
        }
        return $this->_blConfirmAGB;
    }

    /**
     * Template variable getter. Returns if option "blConfirmCustInfo" is on.
     * For compatibility reasons for a while. will be removed in future
     *
     * @return bool
     */
    public function isConfirmCustInfoActive()
    {
        if ( $this->_blConfirmCustInfo === null ) {
            $this->_blConfirmCustInfo = false;
            $sConf = $this->getConfig()->getConfigParam( 'blConfirmCustInfo' );
            if ( $sConf != null ) {
                $this->_blConfirmCustInfo = $this->getConfig()->getConfigParam( 'blConfirmCustInfo' );
            }
        }
        return $this->_blConfirmCustInfo;
    }

    /**
     * Template variable getter. Returns if option "blConfirmAGB" was not set
     *
     * @return bool
     */
    public function isConfirmAGBError()
    {
        return $this->_blConfirmAGBError;
    }

    /**
     * Template variable getter. Returns if option "blConfirmCustInfo" was not set.
     * For compatibility reasons for a while. will be removed in future.
     *
     * @return bool
     */
    public function isConfirmCustInfoError()
    {
        return $this->_blConfirmCustInfoError;
    }

    /**
     * Template variable getter. Returns if option "blShowOrderButtonOnTop" is on
     *
     * @return bool
     */
    public function showOrderButtonOnTop()
    {
        if ( $this->_blShowOrderButtonOnTop === null ) {
            $this->_blShowOrderButtonOnTop = false;
            $this->_blShowOrderButtonOnTop = $this->getConfig()->getConfigParam( 'blShowOrderButtonOnTop' );
        }
        return $this->_blShowOrderButtonOnTop;
    }

    /**
     * Returns wrapping options availability state (TRUE/FALSE)
     *
     * @return bool
     */
    public function isWrapping()
    {
        if ( $this->_iWrapCnt === null ) {
            $this->_iWrapCnt = 0;

            $oWrap = oxNew( 'oxwrapping' );
            $this->_iWrapCnt += $oWrap->getWrappingCount( 'WRAP' );
            $this->_iWrapCnt += $oWrap->getWrappingCount( 'CARD' );
        }

        return (bool) $this->_iWrapCnt;
    }
}
