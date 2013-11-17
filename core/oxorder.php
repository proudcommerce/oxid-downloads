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
 * $Id: oxorder.php 21499 2009-08-07 13:49:56Z vilma $
 */

/**
 * Order manager.
 * Performs creation assigning, updating, deleting and other order functions.
 * @package core
 */
class oxOrder extends oxBase
{
    /**
     * Skip update fields
     *
     * @var unknown_type
     */
    protected $_aSkipSaveFields = array( 'oxorderdate' );

    /**
     * oxList of oxarticle objects
     *
     * @var oxList
     */
    protected $_oArticles = null;

    /**
     * Oxdeliveryset object
     *
     * @var oxDeliverySet
     */
    protected $_oDelSet   = null;

    /**
     * Gift card
     *
     * @var oxWrapping
     */
    protected $_oGiftCard = null;

    /**
     * Payment type
     *
     * @var oxPayment
     */
    protected $_oPaymentType = null;

    /**
     * User payment
     *
     * @var oxPayment
     */
    protected $_oPayment = null;

    /**
     * Order vouchers marked as used
     *
     * @var array
     */
    protected $_aVoucherList = null;

    /**
     * Order delivery costs price object
     *
     * @var oxprice
     */
    protected $_oDelPrice = null;

    /**
     * Order user
     *
     * @var oxUser
     */
    protected $_oUser = null;

    /**
     * Order basket
     *
     * @var oxBasket
     */
    protected $_oBasket = null;

    /**
     * Order wrapping costs price object
     *
     * @var oxprice
     */
    protected $_oWrappingPrice = null;

    /**
     * Order payment costs price object
     *
     * @var oxprice
     */
    protected $_oPaymentPrice = null;

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxorder';

    /**
     * Useage of seperate orders numbering for different shops
     *
     * @var bool
     */
    protected $_blSeparateNumbering = null;

    /**
     * Order language id
     *
     * @var int
     */
    protected $_iOrderLang = null;

    /**
     * If true delivery will be recalculated while recalculating order
     *
     * @var bool
     */
    protected $_blReloadDelivery = true;

    /**
     * If true discount will be recalculated while recalculating order
     *
     * @var bool
     */
    protected $_blReloadDiscount = true;

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxorder' );

        // set usage of seperate orders numbering for different shops
        $this->setSeparateNumbering( $this->getConfig()->getConfigParam( 'blSeparateNumbering') );

    }

     /**
     * Getter made for order deliveryset object access
     *
     * @param string $sName parameter name
     *
     * @return mixed
     */
    public function __get( $sName )
    {
        if ( $sName == 'oDelSet' ) {
            return $this->getDelSet();
        }

        if ( $sName == 'oxorder__oxbillcountry' ) {
            return $this->getBillCountry();
        }

        if ( $sName == 'oxorder__oxdelcountry' ) {
            return $this->getDelCountry();
        }
    }

    /**
     * Assigns data, stored in DB to oxorder object
     *
     * @param mixed $dbRecord DB record
     *
     * @return null
     */
    public function assign( $dbRecord )
    {

        parent::assign( $dbRecord );

        $oUtilsDate = oxUtilsDate::getInstance();

        // convert date's to international format
        $this->oxorder__oxorderdate = new oxField( $oUtilsDate->formatDBDate( $this->oxorder__oxorderdate->value));
        $this->oxorder__oxsenddate  = new oxField( $oUtilsDate->formatDBDate( $this->oxorder__oxsenddate->value));

        //settting deprecated template variables
        $this->_setDeprecatedValues();
    }

    /**
     * Gets country title by country id.
     *
     * @param string $sCountryId Cuntry ID
     *
     * @return string
     */
    protected function _getCountryTitle( $sCountryId )
    {
        $sTitle = null;
        if ( $sCountryId && $sCountryId != '-1' ) {
            $oCountry = oxNew( 'oxcountry' );
            $oCountry->load( $sCountryId );
            $sTitle = $oCountry->oxcountry__oxtitle->value;
        }

        return $sTitle;
    }

    /**
     * Assigns data, stored in oxorderarticles to oxorder object .
     *
     * @param bool $blExcludeCanceled excludes canceled items from list
     *
     * @return null
     */
    public function getOrderArticles( $blExcludeCanceled = false )
    {
        // checking set value
        if ( $this->_oArticles === null  ) {
            $blExcludeState = $blExcludeCanceled;
            $sTable = getViewName( "oxorderarticles" );
            $sSelect = "select {$sTable}.* from {$sTable}
                        where {$sTable}.oxorderid = '".$this->getId() . "'" .
                        ( $blExcludeCanceled ? " and {$sTable}.oxstorno != 1 ": " " ) ."
                        order by {$sTable}.oxartid";

            // order articles
            $oArticles = oxNew( 'oxlist' );
            $oArticles->init( 'oxorderarticle' );
            $oArticles->selectString( $sSelect );

            // is value was not set, just returning it
            return $oArticles;
        }
        return $this->_oArticles;
    }

    /**
     * Order article list setter
     *
     * @param object $aOrderArticleList order article list
     *
     * @return null
     */
    public function setOrderArticleList( $aOrderArticleList )
    {
        $this->_oArticles = $aOrderArticleList;
    }

    /**
     * Returns order delivery expenses price object
     *
     * @return oxprice
     */
    public function getOrderDeliveryPrice()
    {
        if ( $this->_oDelPrice != null ) {
            return $this->_oDelPrice;
        }

        $this->_oDelPrice = oxNew( 'oxprice' );
        $this->_oDelPrice->setBruttoPriceMode();
        $this->_oDelPrice->setPrice( $this->oxorder__oxdelcost->value, $this->oxorder__oxdelvat->value );
        return $this->_oDelPrice;
    }

    /**
     * Returns order wrapping expenses price object
     *
     * @return oxprice
     */
    public function getOrderWrappingPrice()
    {
        if ( $this->_oWrappingPrice != null ) {
            return $this->_oWrappingPrice;
        }

        $this->_oWrappingPrice = oxNew( 'oxprice' );
        $this->_oWrappingPrice->setBruttoPriceMode();
        $this->_oWrappingPrice->setPrice( $this->oxorder__oxwrapcost->value, $this->oxorder__oxwrapvat->value );
        return $this->_oWrappingPrice;
    }

    /**
     * Returns order payment expenses price object
     *
     * @return oxprice
     */
    public function getOrderPaymentPrice()
    {
        if ( $this->_oPaymentPrice != null ) {
            return $this->_oPaymentPrice;
        }

        $this->_oPaymentPrice = oxNew( 'oxprice' );
        $this->_oPaymentPrice->setBruttoPriceMode();
        $this->_oPaymentPrice->setPrice( $this->oxorder__oxpaycost->value, $this->oxorder__oxpayvat->value );
        return $this->_oPaymentPrice;
    }

    /**
     * Returns order netto sum (total price, including delivery, payment etc - VAT)
     * (A. this is very unprecise :())
     *
     * @return double
     */
    public function getOrderNetSum()
    {
        $dTotalNetSum = 0;

        $dTotalNetSum += $this->oxorder__oxtotalnetsum->value;
        $dTotalNetSum += $this->getOrderDeliveryPrice()->getNettoPrice();
        $dTotalNetSum += $this->getOrderWrappingPrice()->getNettoPrice();
        $dTotalNetSum += $this->getOrderPaymentPrice()->getNettoPrice();

        return $dTotalNetSum;
    }

    /**
     * Order checking, processing and saving method.
     * Before saving performed checking if order is still not executed (checks in
     * database oxorder table for order with know ID), if yes - returns error code 3,
     * if not - loads payment data, assigns all info from basket to new oxorder object
     * and saves full order with error status. Then executes payment. On failure -
     * deletes order and returns error code 2. On success - saves order (oxorder::save()),
     * removes article from wishlist (oxorder::_updateWishlist()), updates voucher data
     * (oxorder::_markVouchers()). Finally sends order confirmation email to customer
     * (oxemail::SendOrderEMailToUser()) and shop owner (oxemail::SendOrderEMailToOwner()).
     * If this is order racalculation, skipping payment execution, marking vouchers as used
     * and sending order by email to shop owner and user
     * Mailing status (1 if OK, 0 on error) is returned.
     *
     * @param oxBasket $oBasket              Shopping basket object
     * @param object   $oUser                Current user object
     * @param bool     $blRecalculatingOrder Order recalculation
     *
     * @return integer
     */
    public function finalizeOrder( oxBasket $oBasket, $oUser, $blRecalculatingOrder = false )
    {
        // check if this order is already stored
        $sGetChallenge = oxSession::getVar( 'sess_challenge' );
        if ( $this->_checkOrderExist( $sGetChallenge ) ) {
            oxUtils::getInstance()->logger( 'BLOCKER' );
            // we might use this later, this means that somebody klicked like mad on order button
            return 3;
        }

        // if not recalculating order, use sess_challenge id, else leave old order id
        if ( !$blRecalculatingOrder ) {
            // use this ID
            $this->setId( $sGetChallenge );
        }

        // copies user info
        $this->_setUser( $oUser );

        // copies basket info
        $this->_loadFromBasket( $oBasket );

        // payment information
        $oUserPayment = $this->_setPayment( $oBasket->getPaymentId() );

        // set folder information, if order is new
        // #M575 in recalcualting order case folder must be the same as it was
        if ( !$blRecalculatingOrder ) {
            $this->_setFolder();
        }

        //saving all order data to DB
        $this->save();

        // executing payment (on failure deletes order and returns error code)
        // in case when recalcualting order, payment execution is skipped
        if ( !$blRecalculatingOrder ) {
            $blRet = $this->_executePayment( $oBasket, $oUserPayment );
            if ( $blRet !== true ) {
                return $blRet;
            }
        }

        // deleting remark info only when order is finished
        oxSession::deleteVar( 'ordrem' );

        // updating order trans status (success status)
        $this->_setOrderStatus( 'OK' );

        // store orderid
        $oBasket->setOrderId( $this->getId() );

        // updating bought items stock
        $this->_updateStock();

        // updating wish lists
        $this->_updateWishlist( $oBasket->getContents(), $oUser );

        // updating users notice list
        $this->_updateNoticeList( $oBasket->getContents(), $oUser );

        // marking vouchers as used and sets them to $this->_aVoucherList (will be used in order email)
        // skipping this action in case of order recalculation
        if ( !$blRecalculatingOrder ) {
            $this->_markVouchers( $oBasket, $oUser );
        }

        // send order by email to shop owner and current user
        // skipping this action in case of order recalculation
        if ( !$blRecalculatingOrder ) {
            $iRet = $this->_sendOrderByEmail( $oUser, $oBasket, $oUserPayment );
        } else {
            $iRet = 1;
        }

        return $iRet;
    }

    /**
     * Updates order transaction status. Faster than saving whole object
     *
     * @param string $sStatus order transaction status
     *
     * @return null
     */
    protected function _setOrderStatus( $sStatus )
    {
        $sQ = 'update oxorder set oxtransstatus="'.$sStatus.'" where oxid="'.$this->getId().'" ';
        oxDb::getDb()->execute( $sQ );
    }

    /**
     * Gathers and assigns to new oxorder object customer data, payment, delivery
     * and shipping info, customer odere remark, currency, voucher, language data.
     * Additionally stores general discount and wrapping. Sets order status to "error"
     * and creates oxorderarticle objects and assigns to them basket articles.
     *
     * @param oxBasket $oBasket Shopping basket object
     *
     * @return null
     */
    protected function _loadFromBasket( oxBasket $oBasket )
    {
        $myConfig = $this->getConfig();

        // store IP Adress - default must be FALSE as it is illegal to store
        if ( $myConfig->getConfigParam( 'blStoreIPs' ) &&  $this->oxorder__oxip->value === null ) {
            $this->oxorder__oxip = new oxField(oxUtilsServer::getInstance()->getRemoteAddress(), oxField::T_RAW);
        }

        // copying main price info
        $this->oxorder__oxtotalnetsum   = new oxField(oxUtils::getInstance()->fRound($oBasket->getProductsPrice()->getNettoSum()), oxField::T_RAW);
        $this->oxorder__oxtotalbrutsum  = new oxField($oBasket->getProductsPrice()->getBruttoSum(), oxField::T_RAW);
        $this->oxorder__oxtotalordersum = new oxField($oBasket->getPrice()->getBruttoPrice(), oxField::T_RAW);

        // payment costs if available
        if ( ( $oPaymentCost = $oBasket->getCosts( 'oxpayment' ) ) ) {
            $this->oxorder__oxpaycost = new oxField($oPaymentCost->getBruttoPrice(), oxField::T_RAW);
            $this->oxorder__oxpayvat  = new oxField($oPaymentCost->getVAT(), oxField::T_RAW);
        }

        // delivery info
        if ( ( $oDeliveryCost = $oBasket->getCosts( 'oxdelivery' ) ) ) {
            $this->oxorder__oxdelcost = new oxField($oDeliveryCost->getBruttoPrice(), oxField::T_RAW);
            //V #M382: Save VAT, not VAT value for delivery costs
            $this->oxorder__oxdelvat  = new oxField($oDeliveryCost->getVAT(), oxField::T_RAW); //V #M382
            $this->oxorder__oxdeltype = new oxField($oBasket->getShippingId(), oxField::T_RAW);
        }

        // user remark
        if ( $this->oxorder__oxremark->value === null ) {
            $this->oxorder__oxremark = new oxField(oxSession::getVar( 'ordrem' ), oxField::T_RAW);
        }

        // currency
        $oCur = $myConfig->getActShopCurrencyObject();
        $this->oxorder__oxcurrency = new oxField($oCur->name);
        $this->oxorder__oxcurrate  = new oxField($oCur->rate, oxField::T_RAW);

        // store voucherdiscount
        if ( ( $oVoucherDiscount = $oBasket->getVoucherDiscount() ) ) {
            $this->oxorder__oxvoucherdiscount = new oxField($oVoucherDiscount->getBruttoPrice(), oxField::T_RAW);
        }

        // general discount
        $dDiscount = 0;
        $aDiscounts = $oBasket->getDiscounts();
        if ( count($aDiscounts) > 0 ) {
            foreach ($aDiscounts as $oDiscount) {
                $dDiscount += $oDiscount->dDiscount;
            }
        }
        if ( $dDiscount ) {
            $this->oxorder__oxdiscount = new oxField($dDiscount, oxField::T_RAW);
        }

        //order language
        $this->oxorder__oxlang = new oxField( $this->getOrderLanguage() );


        // initial status - 'ERROR'
        $this->oxorder__oxtransstatus = new oxField('ERROR', oxField::T_RAW);

        // copies basket product info ...
        $this->_setOrderArticles( $oBasket->getContents() );

        // copies wrapping info
        $this->_setWrapping( $oBasket );
    }

    /**
     * Returns language id of current order object. If order allready has
     * language defined - checks if this language is defined in shops config
     *
     * @return int
     */
    public function getOrderLanguage()
    {
        if ( $this->_iOrderLang === null ) {
            if ( isset( $this->oxorder__oxlang->value ) ) {
                $this->_iOrderLang = oxLang::getInstance()->validateLanguage( $this->oxorder__oxlang->value );
            } else {
                $this->_iOrderLang = oxLang::getInstance()->getBaseLanguage();
            }
        }
        return $this->_iOrderLang;
    }

    /**
     * Assigns to new oxorder object customer delivery and shipping info
     *
     * @param object $oUser user object
     *
     * @return null
     */
    protected function _setUser( $oUser )
    {

        $this->oxorder__oxuserid        = new oxField($oUser->getId());

        // bill address
        $this->oxorder__oxbillcompany     = clone $oUser->oxuser__oxcompany;
        $this->oxorder__oxbillemail       = clone $oUser->oxuser__oxusername;
        $this->oxorder__oxbillfname       = clone $oUser->oxuser__oxfname;
        $this->oxorder__oxbilllname       = clone $oUser->oxuser__oxlname;
        $this->oxorder__oxbillstreet      = clone $oUser->oxuser__oxstreet;
        $this->oxorder__oxbillstreetnr    = clone $oUser->oxuser__oxstreetnr;
        $this->oxorder__oxbilladdinfo     = clone $oUser->oxuser__oxaddinfo;
        $this->oxorder__oxbillustid       = clone $oUser->oxuser__oxustid;
        $this->oxorder__oxbillcity        = clone $oUser->oxuser__oxcity;
        $this->oxorder__oxbillcountryid   = clone $oUser->oxuser__oxcountryid;
        $this->oxorder__oxbillzip         = clone $oUser->oxuser__oxzip;
        $this->oxorder__oxbillfon         = clone $oUser->oxuser__oxfon;
        $this->oxorder__oxbillfax         = clone $oUser->oxuser__oxfax;
        $this->oxorder__oxbillsal         = clone $oUser->oxuser__oxsal;


        // delivery address
        if ( ( $oDelAdress = $this->getDelAddressInfo() ) ) {
            // set delivery address
            $this->oxorder__oxdelcompany   = clone $oDelAdress->oxaddress__oxcompany;
            $this->oxorder__oxdelfname     = clone $oDelAdress->oxaddress__oxfname;
            $this->oxorder__oxdellname     = clone $oDelAdress->oxaddress__oxlname;
            $this->oxorder__oxdelstreet    = clone $oDelAdress->oxaddress__oxstreet;
            $this->oxorder__oxdelstreetnr  = clone $oDelAdress->oxaddress__oxstreetnr;
            $this->oxorder__oxdeladdinfo   = clone $oDelAdress->oxaddress__oxaddinfo;
            $this->oxorder__oxdelcity      = clone $oDelAdress->oxaddress__oxcity;
            $this->oxorder__oxdelcountryid = clone $oDelAdress->oxaddress__oxcountryid;
            $this->oxorder__oxdelzip       = clone $oDelAdress->oxaddress__oxzip;
            $this->oxorder__oxdelfon       = clone $oDelAdress->oxaddress__oxfon;
            $this->oxorder__oxdelfax       = clone $oDelAdress->oxaddress__oxfax;
            $this->oxorder__oxdelsal       = clone $oDelAdress->oxaddress__oxsal;
        }
    }

    /**
     * Assigns wrapping VAT and card price + card message info
     *
     * @param oxBasket $oBasket basket object
     *
     * @return null
     */
    protected function _setWrapping( oxBasket $oBasket )
    {
        $myConfig = $this->getConfig();

        // wrapping price
        if ( ( $oWrappingCost = $oBasket->getCosts( 'oxwrapping' ) ) ) {
            $this->oxorder__oxwrapcost = new oxField($oWrappingCost->getBruttoPrice(), oxField::T_RAW);

            // wrapping VAT
            if ( $myConfig->getConfigParam( 'blCalcVatForWrapping' ) ) {
                $this->oxorder__oxwrapvat = new oxField($oWrappingCost->getVAT(), oxField::T_RAW);
            }
        }

        // greetings card
        $this->oxorder__oxcardid = new oxField($oBasket->getCardId(), oxField::T_RAW);

        // card text will be stored in database
        $this->oxorder__oxcardtext = new oxField($oBasket->getCardMessage(), oxField::T_RAW);
    }

    /**
     * Creates oxorderarticle objects and assigns to them basket articles.
     * Updates quantity of sold articles (oxarticle::updateSoldAmount()).
     *
     * @param array $aArticleList article list
     *
     * @return null
     */
    protected function _setOrderArticles( $aArticleList )
    {
        // reset articles list
        $this->_oArticles = oxNew( 'oxlist' );
        $iCurrLang = $this->getOrderLanguage();

        // add all the products we have on basket to the order
        foreach ( $aArticleList as $oContent ) {

            //$oContent->oProduct = $oContent->getArticle();
            $oProduct = $oContent->getArticle();

            // copy only if object is oxarticle type
            if ( $oProduct->isOrderArticle() ) {
                $oOrderArticle = $oProduct;
            } else {

                // if order language doe not match product language - article must be reloaded in order language
                if ( $iCurrLang != $oProduct->getLanguage() ) {
                    $oProduct->loadInLang( $iCurrLang, $oProduct->getProductId() );
                }

                // set chosen selectlist
                $sSelList = '';
                if ( count( $aChosenSelList = $oContent->getChosenSelList() ) ) {
                    foreach ( $aChosenSelList as $oItem ) {
                        if ( $sSelList ) {
                           $sSelList .= ", ";
                        }
                        $sSelList .= "{$oItem->name} : {$oItem->value}";
                    }
                }

                $oOrderArticle = oxNew( 'oxorderarticle' );
                $oOrderArticle->setIsNewOrderItem( true );
                $oOrderArticle->copyThis( $oProduct );
                $oOrderArticle->setId();

                $oOrderArticle->oxorderarticles__oxartnum     = clone $oProduct->oxarticles__oxartnum;
                $oOrderArticle->oxorderarticles__oxselvariant = new oxField( trim( $sSelList.' '.$oProduct->oxarticles__oxvarselect->value ), oxField::T_RAW );
                $oOrderArticle->oxorderarticles__oxshortdesc  = new oxField( $oProduct->oxarticles__oxshortdesc->value, oxField::T_RAW );
                $oOrderArticle->oxorderarticles__oxtitle      = new oxField( trim( $oProduct->oxarticles__oxtitle->value.' '.$oOrderArticle->oxorderarticles__oxselvariant->value ), oxField::T_RAW );

                // copying persistent parameters ...
                if ( !is_array( $aPersParams = $oProduct->getPersParams() ) ) {
                    $aPersParams = $oContent->getPersParams();
                }
                if ( is_array( $aPersParams ) && count( $aPersParams )) {
                    $oOrderArticle->oxorderarticles__oxpersparam = new oxField( serialize( $aPersParams ), oxField::T_RAW );
                }
            }

            // ids, titles, numbers ...
            $oOrderArticle->oxorderarticles__oxorderid = new oxField( $this->getId() );
            $oOrderArticle->oxorderarticles__oxartid   = new oxField( $oContent->getProductId() );
            $oOrderArticle->oxorderarticles__oxamount  = new oxField( $oContent->getAmount() );

            // prices
            $oPrice = $oContent->getPrice();
            $oOrderArticle->oxorderarticles__oxnetprice  = new oxField( $oPrice->getNettoPrice(), oxField::T_RAW );
            $oOrderArticle->oxorderarticles__oxvatprice  = new oxField( $oPrice->getVatValue(), oxField::T_RAW );
            $oOrderArticle->oxorderarticles__oxbrutprice = new oxField( $oPrice->getBruttoPrice(), oxField::T_RAW );
            $oOrderArticle->oxorderarticles__oxvat       = new oxField( $oPrice->getVat(), oxField::T_RAW );

            $oUnitPtice = $oContent->getUnitPrice();
            $oOrderArticle->oxorderarticles__oxnprice = new oxField( $oUnitPtice->getNettoPrice(), oxField::T_RAW );
            $oOrderArticle->oxorderarticles__oxbprice = new oxField( $oUnitPtice->getBruttoPrice(), oxField::T_RAW );

            // wrap id
            $oOrderArticle->oxorderarticles__oxwrapid = new oxField( $oContent->getWrappingId(), oxField::T_RAW );

            // items shop id
            $oOrderArticle->oxorderarticles__oxordershopid = new oxField( $oContent->getShopId(), oxField::T_RAW );

            // add information for eMail
            //P
            //TODO: check if this assign is needed at all
            $oOrderArticle->oProduct = $oProduct;

            // simulatin order article list
            $this->_oArticles->offsetSet( $oOrderArticle->getId(), $oOrderArticle );
        }
    }

    /**
     * Executes payment. Additionally loads oxPaymentGateway object, initiates
     * it by adding payment parameters (oxPaymentGateway::setPaymentParams())
     * and finally executes it (oxPaymentGateway::executePayment()). On failure -
     * deletes order and returns * error code 2.
     *
     * @param oxBasket $oBasket      basket object
     * @param object   $oUserpayment user payment object
     *
     * @return  integer 2 or an error code
     */
    protected function _executePayment( oxBasket $oBasket, $oUserpayment )
    {
        $oPayTransaction = $this->_getGateway();
        $oPayTransaction->setPaymentParams( $oUserpayment );

        if ( !$oPayTransaction->executePayment( $oBasket->getPrice()->getBruttoPrice(), $this ) ) {
            $this->delete();

            // checking for error messages
            if ( method_exists( $oPayTransaction, 'getLastError' ) ) {
                if ( ( $sLastError = $oPayTransaction->getLastError() ) ) {
                    return $sLastError;
                }
            }

            // checking for error codes
            if ( method_exists( $oPayTransaction, 'getLastErrorNo' ) ) {
                if ( ( $iLastErrorNo = $oPayTransaction->getLastErrorNo() ) ) {
                    return $iLastErrorNo;
                }
            }

            return 2; // means no authentication
        }
        return true; // everything fine
    }

    /**
     * Returns the correct gateway. At the moment only switch between default
     * and IPayment, can be extended later.
     *
     * @return object $oPayTransaction payment gateway object
     */
    protected function _getGateway()
    {
        return oxNew( 'oxPaymentGateway' );
    }

    /**
     * Creats and returns user payment.
     *
     * @param string $sPaymentid used payment id
     *
     * @return object $oUserpayment payment object
     */
    protected function _setPayment( $sPaymentid )
    {
        // copying payment info fields
        $aDynvalue = oxSession::getVar( 'dynvalue' );
        $aDynvalue = $aDynvalue ? $aDynvalue : oxConfig::getParameter( 'dynvalue' );

        // loading payment object
        $oPayment = oxNew( 'oxpayment' );

        if (!$oPayment->load( $sPaymentid )) {
            return null;
        }

        // #756M Preserve already stored payment information
        if ( !$aDynvalue && ( $oUserpayment = $this->getPaymentType() ) ) {
            $aStoredDynvalue = $oUserpayment->getDynValues();
            foreach ( $aStoredDynvalue as $oVal ) {
                $aDynvalue[$oVal->name] = $oVal->value;
            }
        }

        $oPayment->setDynValues( oxUtils::getInstance()->assignValuesFromText( $oPayment->oxpayments__oxvaldesc->value ) );

        // collecting dynamic values
        $aDynVal = array();

        $aPaymentDynValues = $oPayment->getDynValues();
        foreach ( $aPaymentDynValues  as $key => $oVal ) {
            if ( isset( $aDynvalue[$oVal->name] ) ) {
                $oVal->value = $aDynvalue[$oVal->name];
            }

            //$oPayment->setDynValue($key, $oVal);
            $aPaymentDynValues[$key] = $oVal;
            $aDynVal[$oVal->name] = $oVal->value;
        }

        // Store this payment information, we might allow users later to
        // reactivate already give payment informations

        $oUserpayment = oxNew( 'oxuserpayment' );
        $oUserpayment->oxuserpayments__oxuserid     = clone $this->oxorder__oxuserid;
        $oUserpayment->oxuserpayments__oxpaymentsid = new oxField($sPaymentid, oxField::T_RAW);
        $oUserpayment->oxuserpayments__oxvalue      = new oxField(oxUtils::getInstance()->assignValuesToText( $aDynVal ), oxField::T_RAW);
        $oUserpayment->oxpayments__oxdesc           = clone $oPayment->oxpayments__oxdesc;
        $oUserpayment->setDynValues( $aPaymentDynValues );
        $oUserpayment->save();

        // storing payment information to order
        $this->oxorder__oxpaymentid   = new oxField($oUserpayment->getId(), oxField::T_RAW);
        $this->oxorder__oxpaymenttype = clone $oUserpayment->oxuserpayments__oxpaymentsid;

        // returning user payment object which will be used later in code ...
        return $oUserpayment;
    }

    /**
     * Assigns oxfolder as new
     *
     * @return null
     */
    protected function _setFolder()
    {
        $myConfig = $this->getConfig();
        $this->oxorder__oxfolder    = new oxField(key( $myConfig->getShopConfVar(  'aOrderfolder', $myConfig->getShopId() ) ), oxField::T_RAW);
    }

    /**
     * aAdds/removes user chosen article to/from his noticelist
     * or wishlist (oxuserbasket::addItemToBasket()).
     *
     * @param array  $aArticleList basket products
     * @param object $oUser        user object
     *
     * @return null
     */
    protected function _updateWishlist( $aArticleList, $oUser )
    {

        foreach ( $aArticleList as $oContent) {
            if ( ( $sWishId = $oContent->getWishId() ) ) {

                // checking which wishlist user uses ..
                if ( $sWishId == $oUser->getId() ) {
                    $oUserBasket = $oUser->getBasket( 'wishlist' );
                } else {
                    $aWhere = array( 'oxuserbaskets.oxuserid' => $sWishId, 'oxuserbaskets.oxtitle' => 'wishlist' );
                    $oUserBasket = oxNew( 'oxuserbasket' );
                    $oUserBasket->assignRecord( $oUserBasket->buildSelectString( $aWhere ) );
                }

                // updating users wish list
                if ( $oUserBasket ) {
                    if ( !($sProdId = $oContent->getWishArticleId() )) {
                        $sProdId = $oContent->getProductId();
                    }
                    $oUserBasketItem = $oUserBasket->getItem( $sProdId, $oContent->getSelList() );
                    $dNewAmount = $oUserBasketItem->oxuserbasketitems__oxamount->value - $oContent->getAmount();
                    if ( $dNewAmount < 0) {
                        $dNewAmount = 0;
                    }
                    $oUserBasket->addItemToBasket( $sProdId, $dNewAmount, $oContent->getSelList(), true );
                }
            }
        }
    }

    /**
     * After order is finished this method cleans up users notice list, by
     * removing bought items from users notice list
     *
     * @param array  $aArticleList array of basket products
     * @param oxuser $oUser        basket user object
     *
     * @return null
     */
    protected function _updateNoticeList( $aArticleList, $oUser )
    {
        // loading users notice list ..
        if ( $oUserBasket = $oUser->getBasket( 'noticelist' ) ) {
            // only if wishlist is enabled
            foreach ( $aArticleList as $oContent) {
                $sProdId = $oContent->getProductId();

                // updating users notice list
                $oUserBasketItem = $oUserBasket->getItem( $sProdId, $oContent->getSelList() );
                $dNewAmount = $oUserBasketItem->oxuserbasketitems__oxamount->value - $oContent->getAmount();
                if ( $dNewAmount < 0) {
                    $dNewAmount = 0;
                }
                $oUserBasket->addItemToBasket( $sProdId, $dNewAmount, $oContent->getSelList(), true );
            }
        }
    }

    /**
     * Bought item stock updater
     *
     * @return null
     */
    protected function _updateStock()
    {
        $myConfig = $this->getConfig();

        // ordered articles
        if ( $myConfig->getConfigParam( 'blUseStock' ) ) {
            $oOrderArticles = $this->getOrderArticles();
            foreach ( $oOrderArticles as $oOrderArticle ) {
                if ( $oOrderArticle->isNewOrderItem() ) {
                    $oOrderArticle->updateArticleStock( $oOrderArticle->oxorderarticles__oxamount->value * (-1), $myConfig->getConfigParam( 'blAllowNegativeStock' ) );
                }
            }
        }
    }

    /**
     * Markes voucher as used (oxvoucher::markAsUsed())
     * and sets them to $this->_aVoucherList.
     *
     * @param oxBasket $oBasket basket object
     * @param oxUser   $oUser   user object
     *
     * @deprecated sets deprecated values for usage in mail templates
     *
     * @return null
     */
    protected function _markVouchers( $oBasket, $oUser )
    {
        $this->_aVoucherList = $oBasket->getVouchers();

        if ( is_array( $this->_aVoucherList ) ) {
            foreach ( array_keys( $this->_aVoucherList ) as $sVoucherId ) {
                $oVoucher = oxNew( 'oxvoucher' );
                $oVoucher->load( $sVoucherId );
                $oVSerie = $oVoucher->getSerie();
                $oVoucher->markAsUsed( $this->oxorder__oxid->value, $oUser->oxuser__oxid->value, $oVSerie->oxvoucherseries__oxdiscount );

                // -- set deprecated values for email templates
                $oVoucher->oxmodvouchers__oxvouchernr = $oVoucher->oxvouchers__oxvouchernr;
                $oVoucher->oxmodvouchers__oxdiscount     = clone $oVSerie->oxvoucherseries__oxdiscount;
                $oVoucher->oxmodvouchers__oxdiscounttype = clone $oVSerie->oxvoucherseries__oxdiscounttype;
                // -- set deprecated values for email templates

                $this->_aVoucherList[$sVoucherId] = $oVoucher;
            }
        }
    }

    /**
     * Updates/inserts order object and related info to DB
     *
     * @return null
     */
    public function save()
    {
        if ( ( $blSave = parent::save() ) ) {

            // saving order articles
            $oOrderArticles = $this->getOrderArticles();
            if ( $oOrderArticles && count( $oOrderArticles ) > 0 ) {
                foreach ( $oOrderArticles as $oOrderArticle ) {
                    $oOrderArticle->save();
                }
            }
        }

        return $blSave;
    }

    /**
     * Loads and returns delivery adress object or null
     * if deladrid is not configured, or object was not loaded
     *
     * @return  object
     */
    public function getDelAddressInfo()
    {
        $oDelAdress = null;
        if ( ( $soxAddressId = oxConfig::getParameter( 'deladrid' ) ) ) {
            $oDelAdress = oxNew( 'oxbase' );
            $oDelAdress->init( 'oxaddress' );
            $oDelAdress->load( $soxAddressId );

            //get delivery country name from delivery country id
            if ( $oDelAdress->oxaddress__oxcountryid->value && $oDelAdress->oxaddress__oxcountryid->value != -1 ) {
                 $oCountry = oxNew( 'oxcountry' );
                 $oCountry->load( $oDelAdress->oxaddress__oxcountryid->value );
                 $oDelAdress->oxaddress__oxcountry = clone $oCountry->oxcountry__oxtitle;
            }
        }
        return $oDelAdress;
    }

    /**
     * Function whitch cheks if article stock is valid.
     * If not displays error and returns false.
     *
     * @param object $oBasket basket object
     *
     * @throws oxOutOfStockException exception
     *
     * @return null
     */
    public function validateStock( $oBasket )
    {
        foreach ( $oBasket->getContents() as $oContent ) {
            $oProd = $oContent->getArticle();

            // check if its still available
            $iOnStock = $oProd->checkForStock( $oContent->getAmount() );
            if ( $iOnStock !== true ) {
                $oEx = oxNew( 'oxOutOfStockException' );
                $oEx->setMessage( 'EXCEPTION_OUTOFSTOCK_OUTOFSTOCK' );
                $oEx->setArticleNr( $oProd->oxarticles__oxartnum->value );
                $oEx->setRemainingAmount( $oProd->oxarticles__oxstock->value );
                throw $oEx;
            }
        }
    }

    /**
     * Inserts order object information in DB. Returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {
        $myConfig = $this->getConfig();
        $oUtilsDate = oxUtilsDate::getInstance();

        //V #M525 orderdate must be the same as it was
        if ( !$this->oxorder__oxorderdate->value ) {
            $this->oxorder__oxorderdate = new oxField(date( 'Y-m-d H:i:s', $oUtilsDate->getTime() ), oxField::T_RAW);
        } else {
            $this->oxorder__oxorderdate = new oxField( $oUtilsDate->formatDBDate( $this->oxorder__oxorderdate->value, true ));
        }
        $this->oxorder__oxshopid    = new oxField($myConfig->getShopId(), oxField::T_RAW);

        $this->oxorder__oxsenddate  = new oxField( $oUtilsDate->formatDBDate( $this->oxorder__oxsenddate->value, true ));

        if ( ( $blInsert = parent::_insert() ) ) {
            // setting order number
            if ( !$this->oxorder__oxordernr->value ) {
                $aWhere = '';
                // separate order numbers for shops ...
                if ( $this->_blSeparateNumbering ) {
                    $aWhere = array( 'oxshopid = "'.$myConfig->getShopId().'"' );
                }
                $this->_setRecordNumber( 'oxordernr', $aWhere );
            }
        }
        return $blInsert;
    }

    /**
     * Updates object parameters to DB.
     *
     * @return null
     */
    protected function _update()
    {
        $this->oxorder__oxsenddate = new oxField(oxUtilsDate::getInstance()->formatDBDate( $this->oxorder__oxsenddate->value, true ));
        return parent::_update();
    }

    /**
     * Updates stock information, deletes current ordering details from DB,
     * returns true on success.
     *
     * @param string $sOxId Ordering ID (default null)
     *
     * @return bool
     */
    public function delete( $sOxId = null )
    {
        if ( $sOxId ) {
            if ( !$this->load( $sOxId ) ) {
                // such order does not exist
                return false;
            }
        } elseif ( !$sOxId ) {
            $sOxId = $this->getId();
        }

        // no order id is passed
        if ( !$sOxId ) {
            return false;
        }


        // update article stock information and delete order articles
        $myConfig = $this->getConfig();
        $blUseStock = $myConfig->getConfigParam( 'blUseStock' );
        $oOrderArticles = $this->getOrderArticles( $blUseStock );
        foreach ( $oOrderArticles as $oOrderArticle ) {
            if ( $blUseStock ) {
                $oOrderArticle->updateArticleStock( $oOrderArticle->oxorderarticles__oxamount->value, $myConfig->getConfigParam('blAllowNegativeStock') );
            }
            $oOrderArticle->delete();
        }

        // #440 - deleting user payment info
        if ( $oPaymentType = $this->getPaymentType() ) {
            $oPaymentType->delete();
        }

        return parent::delete( $sOxId );
    }

    /**
     * Recalculates order. Starts transactions, deletes current order and order articles from DB,
     * adds current order articles to virtual basket and finaly recalculates order by calling oxorder::finalizeOrder()
     * If no errors, finishing transaction.
     *
     * @param array $aNewArticles      article list of new order
     *
     * @return null
     */
    public function recalculateOrder( $aNewArticles = array() )
    {
        oxDb::startTransaction();

        try {
            $oBasket = $this->_getOrderBasket();

            // add this order articles to virtual basket and recalculates basket
            $this->_addOrderArticlesToBasket( $oBasket, $this->getOrderArticles( true ) );

            // adding new articles to existing order
            $this->_addArticlesToBasket( $oBasket, $aNewArticles );

            // recalculating basket
            $oBasket->calculateBasket( true );

            //finalizing order (skipping payment execution, vouchers marking and mail sending)
            $iRet = $this->finalizeOrder( $oBasket, $this->getOrderUser(), true );

            //if finalizing order failed, rollback transaction
            if ( $iRet !== 1 ) {
                oxDb::rollbackTransaction();
            } else {
                oxDb::commitTransaction();
            }

        } catch( Exception $oE ) {
            // if exception, rollBack everything
            oxDb::rollbackTransaction();
        }
    }

    protected $_oOrderBasket = null;
    /**
     * Returns basket object filled up with discount, delivery, wrapping and all other info
     *
     * @param bool $blStockCheck perform stock check or not (default true)
     *
     * @return oxbasket
     */
    protected function _getOrderBasket( $blStockCheck = true )
    {
        $this->_oOrderBasket = oxNew( "oxbasket" );

        // setting stock check mode
        $this->_oOrderBasket->setStockCheckMode( $blStockCheck );

        // setting virtual basket user
        $this->_oOrderBasket->setBasketUser( $this->getOrderUser() );

        // transferring order id
        $this->_oOrderBasket->setOrderId( $this->getId() );

        // setting basket currency order uses
        $aCurrencies = $this->getConfig()->getCurrencyArray();
        foreach ( $aCurrencies as $oCur ) {
            if ($oCur->name == $this->oxorder__oxcurrency->value) {
                $oBasketCur = $oCur;
                break;
            }
        }

        // setting currency
        $this->_oOrderBasket->setBasketCurrency( $oBasketCur );

        // set basket card id and message
        $this->_oOrderBasket->setCardId( $this->oxorder__oxcardid->value );
        $this->_oOrderBasket->setCardMessage( $this->oxorder__oxcardtext->value );

        if ( $this->_blReloadDiscount ) {
            // disabling availability check
            $this->_oOrderBasket->setSkipVouchersChecking( true );

            // add previously used vouchers
            $sQ = 'select oxid from oxvouchers where oxorderid = "'.$this->getId().'"';
            $aVouchers = oxDb::getDb( true )->getAll( $sQ );
            foreach ( $aVouchers as $aVoucher ) {
                $this->_oOrderBasket->addVoucher( $aVoucher['oxid'] );
            }
        } else {
            $this->_oOrderBasket->setDiscountCalcMode( false );
            $this->_oOrderBasket->setVoucherDiscount( $this->oxorder__oxvoucherdiscount->value );
            $this->_oOrderBasket->setTotalDiscount( $this->oxorder__oxdiscount->value );
        }

        // must be kept old delivery?
        if ( !$this->_blReloadDelivery ) {
            $this->_oOrderBasket->setDeliveryPrice( $this->getOrderDeliveryPrice() );
        } else {
            //  set shipping
            $this->_oOrderBasket->setShipping( $this->oxorder__oxdeltype->value );
            $this->_oOrderBasket->setDeliveryPrice( null );
        }

        //set basket payment
        $this->_oOrderBasket->setPayment( $this->oxorder__oxpaymenttype->value );

        return $this->_oOrderBasket;
    }

    /**
     * Sets new delivery id for order and forces order to recalculate using new delivery type.
     * Order is not recalculated automatically, to do this oxOrder::recalculateOrder() must be called ;
     *
     * @param string $sDeliveryId new delivery id
     *
     * @return null
     */
    public function setDelivery( $sDeliveryId )
    {
        $this->reloadDelivery( true );
        $this->oxorder__oxdeltype = new oxField( $sDeliveryId );
    }

    /**
     * Returns current order user object
     *
     * @return oxuser
     */
    public function getOrderUser()
    {
        if ($this->_oUser) {
            return $this->_oUser;
        }
        $this->_oUser = oxNew( "oxuser" );
        $this->_oUser->load( $this->oxorder__oxuserid->value );

        return $this->_oUser;
    }

    /**
     * Fake entries, pdf is generated in modules.. myorder.
     *
     * @param mixed $oPdf pdf object
     *
     * @return null
     */
    public function pdfFooter( $oPdf )
    {
    }

    /**
     * Fake entries, pdf is generated in modules.. myorder.
     *
     * @param mixed $oPdf pdf object
     *
     * @return null
     */
    public function pdfHeaderplus( $oPdf )
    {
    }

    /**
     * Fake entries, pdf is generated in modules.. myorder.
     *
     * @param mixed $oPdf pdf object
     *
     * @return null
     */
    public function pdfHeader( $oPdf )
    {
    }

    /**
     * Fake entries, pdf is generated in modules.. myorder.
     *
     * @param string $sFilename file name
     * @param int    $iSelLang  selected language
     *
     * @return null
     */
    public function genPdf( $sFilename, $iSelLang = 0 )
    {
    }

    /**
     * Returns order invoice number.
     *
     * @return integer
     */
    public function getInvoiceNum()
    {
        $sQ = 'select max(oxorder.oxinvoicenr) from oxorder where oxorder.oxshopid = "'.$this->getConfig()->getShopId().'" ';
        return ( ( int ) oxDb::getDb()->getOne( $sQ ) + 1 );
    }

    /**
     * Returns next possible (free) order bill number.
     *
     * @return integer
     */
    public function getNextBillNum()
    {
        $sQ = 'select max(cast(oxorder.oxbillnr as unsigned)) from oxorder where oxorder.oxshopid = "'.$this->getConfig()->getShopId().'" ';
        return ( ( int ) oxDb::getDb()->getOne( $sQ ) + 1 );
    }

    /**
     * Loads possible shipping sets for this order
     *
     * @return oxdeliverysetlist
     */
    public function getShippingSetList()
    {
        // in which country we deliver
        if ( !( $sShipId = $this->oxorder__oxdelcountryid->value ) ) {
            $sShipId = $this->oxorder__oxbillcountryid->value;
        }

        $oBasket = $this->_getOrderBasket( false );

        // add this order articles to basket and recalculate basket
        $this->_addOrderArticlesToBasket( $oBasket, $this->getOrderArticles() );

        // recalculating basket
        $oBasket->calculateBasket( true );

        // load fitting deliveries list
        $oDeliveryList = oxNew( "oxDeliveryList", "core" );
        $oDeliveryList->setCollectFittingDeliveriesSets( true );

        return $oDeliveryList->getDeliveryList( $oBasket, $this->getOrderUser(), $sShipId );
    }

    /**
     * Get vouchers numbers list which were used with this order
     *
     * @return array
     */
    public function getVoucherNrList()
    {
        $oDB = oxDb::getDb( true );
        $aVouchers = array();
        $sSelect = "select oxvouchernr from oxvouchers where oxorderid = '".$this->oxorder__oxid->value."'";
        $rs = $oDB->execute( $sSelect);
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $aVouchers[] = $rs->fields['oxvouchernr'];
                $rs->moveNext();
            }
        }
        return $aVouchers;
    }

    /**
     * Returns orders total price
     *
     * @param bool $blToday if true calculates only current day orders
     *
     * @return int
     */
    public function getOrderSum( $blToday = false )
    {
        $sSelect  = 'select sum(oxtotalordersum / oxcurrate) from oxorder where ';
        $sSelect .= 'oxshopid = "'.$this->getConfig()->getShopId().'" and oxorder.oxstorno != "1" ';

        if ( $blToday ) {
            $sSelect .= 'and oxorderdate like "'.date( 'Y-m-d').'%" ';
        }

        return ( double ) oxDb::getDb()->getOne( $sSelect );
    }

    /**
     * Returns orders count
     *
     * @param bool $blToday if true calculates only current day orders
     *
     * @return int
     */
    public function getOrderCnt( $blToday = false )
    {
        $sSelect  = 'select count(*) from oxorder where ';
        $sSelect .= 'oxshopid = "'.$this->getConfig()->getShopId().'"  and oxorder.oxstorno != "1" ';

        if ( $blToday ) {
            $sSelect .= 'and oxorderdate like "'.date( 'Y-m-d').'%" ';
        }

        return ( int ) oxDb::getDb()->getOne( $sSelect );
    }


    /**
     * Checking if this order is already stored.
     *
     * @param string $sOxId order ID
     *
     * @return bool
     */
    protected function _checkOrderExist( $sOxId = null )
    {
        if ( !$sOxId) {
            return false;
        }

        if ( oxDb::getDb()->getOne( 'select oxid from oxorder where oxid = "'.$sOxId.'"' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Send order to shop owner and user
     *
     * @param oxUser    $oUser    order user
     * @param oxBasket  $oBasket  current order basket
     * @param oxPayment $oPayment order payment
     *
     * @return bool
     */
    protected function _sendOrderByEmail( $oUser = null, $oBasket = null, $oPayment = null )
    {
        $iRet = 0;

        // add user, basket and payment to order
        $this->_oUser    = $oUser;
        $this->_oBasket  = $oBasket;
        $this->_oPayment = $oPayment;

        $oxEmail = oxNew( 'oxemail' );

        // send order email to user
        if ( $oxEmail->sendOrderEMailToUser( $this ) ) {
            // mail to user was successfully sent
            $iRet = 1;
        }

        // send order email to shop owner
        $oxEmail->sendOrderEMailToOwner( $this );

        return $iRet;
    }

    /**
     * Returns order basket
     *
     * @return oxBasket
     */
    public function getBasket()
    {
        return $this->_oBasket;
    }

    /**
     * Returns order payment
     *
     * @return oxBasket
     */
    public function getPayment()
    {
        return $this->_oPayment;
    }

    /**
     * Returns order vouchers marked as used
     *
     * @return array
     */
    public function getVoucherList()
    {
        return $this->_aVoucherList;
    }

    /**
     * Returns order deliveryset object
     *
     * @return oxDeliverySet
     */
    public function getDelSet()
    {
        if ( $this->_oDelSet == null ) {
            // load deliveryset info
            $this->_oDelSet = oxNew( 'oxdeliveryset' );
            $this->_oDelSet->load( $this->oxorder__oxdeltype->value );
        }

        return $this->_oDelSet;
    }

    /**
     * Get payment type
     *
     * @return oxPayment
     */
    public function getPaymentType()
    {
        if ( $this->oxorder__oxpaymentid->value && $this->_oPaymentType === null ) {
            $this->_oPaymentType = false;
            $oPaymentType = oxNew( 'oxuserpayment' );
            if ( $oPaymentType->load( $this->oxorder__oxpaymentid->value ) ) {
                $this->_oPaymentType = $oPaymentType;
            }
        }

        return $this->_oPaymentType;
    }

    /**
     * Get gift card
     *
     * @return oxWrapping
     */
    public function getGiftCard()
    {
        if ( $this->oxorder__oxcardid->value && $this->_oGiftCard == null ) {
            $this->_oGiftCard = oxNew( 'oxwrapping' );
            $this->_oGiftCard->load( $this->oxorder__oxcardid->value );
        }

        return $this->_oGiftCard;
    }

    /**
     * Set usage of seperate orders numbering for different shops
     *
     * @param bool $blSeparateNumbering use or not separate orders numbering
     *
     * @return bool
     */
    public function setSeparateNumbering( $blSeparateNumbering = null )
    {
        $this->_blSeparateNumbering = $blSeparateNumbering;
    }

    /**
     * Get users payment type from last order
     *
     * @param string $sUserId order user id
     *
     * @return string $sLastPaymentId payment id
     */
    public function getLastUserPaymentType( $sUserId)
    {
        $sQ = 'select oxorder.oxpaymenttype from oxorder where oxorder.oxshopid="'.$this->getConfig()->getShopId().'" and oxorder.oxuserid="'.$sUserId.'" order by oxorder.oxorderdate desc ';
        $sLastPaymentId = oxDb::getDb()->getOne( $sQ );
        return $sLastPaymentId;
    }

    /**
     * Make select list array from oxorderarticles__oxselvariant string.
     * This select list array is used when recalculating order and adding
     * items to basket (oxBasket::addToBaske())
     *
     * @param string $sArtId           order article ID
     * @param string $sOrderArtSelList select list string stored in oxorderarticles__oxselvariant
     *
     * @deprecated use oxOrderArticle::getOrderArticleSelectList
     *
     * @return array()
     */
    protected function _makeSelListArray( $sArtId = null, $sOrderArtSelList = null )
    {
        $oOrder = oxNew( 'oxorderArticle' );
        return $oOrder->getOrderArticleSelectList( $sArtId, $sOrderArtSelList );
    }

    /**
     * Adds order articles back to virtual basket. Needed for recalculating order.
     *
     * @param oxUser $oUser            basket user object
     * @param array  $aOrderArticles   order articles
     *
     * @return oxBasket
     */
    protected function _addOrderArticlesToBasket( $oBasket, $aOrderArticles )
    {
        // if no order articles, return empty basket
        if ( count( $aOrderArticles ) > 0 ) {

            //adding order articles to basket
            foreach ( $aOrderArticles as $oOrderArticle ) {
                $oBasket->addOrderArticleToBasket( $oOrderArticle );
            }
        }
    }

    /**
     * Adds new products to basket/order
     *
     * @param oxbasket $oBasket   basket to add articles
     * @param array    $aArticles article array
     */
    protected function _addArticlesToBasket( $oBasket, $aArticles )
    {
        // if no order articles
        if ( count($aArticles ) > 0 ) {

            //adding order articles to basket
            foreach ( $aArticles as $oArticle ) {
                $aSel = isset( $oArticle->oxorderarticles__oxselvariant ) ? $oArticle->oxorderarticles__oxselvariant->value : null;
                $aPersParam = isset( $oArticle->oxorderarticles__oxpersparam ) ? $oArticle->getPersParams() : null;
                $oBasket->addToBasket( $oArticle->oxorderarticles__oxartid->value,
                                       $oArticle->oxorderarticles__oxamount->value,
                                       $aSel, $aPersParam );
            }
        }
    }

    /**
     * Sets deprecate values
     *
     * @deprecated This method as well as all deprecated class variables is deprecated
     *
     * @return null
     */
    protected function _setDeprecatedValues()
    {
        if ( $this->oxorder__oxstorno->value != 1 ) {
            $oCur = $this->getConfig()->getActShopCurrencyObject();
            $oLang = oxLang::getInstance();

            $this->totalnetsum   = $this->oxorder__oxtotalnetsum->value;
            $this->totalbrutsum  = $this->oxorder__oxtotalbrutsum->value;
            $this->totalorder    = $this->oxorder__oxtotalordersum->value;
            $this->ftotalnetsum  = $oLang->formatCurrency( $this->oxorder__oxtotalnetsum->value, $oCur );
            $this->ftotalbrutsum = $oLang->formatCurrency( $this->oxorder__oxtotalbrutsum->value, $oCur );
            $this->fdelcost      = $oLang->formatCurrency( $this->oxorder__oxdelcost->value, $oCur );
            $this->fpaycost      = $oLang->formatCurrency( $this->oxorder__oxpaycost->value, $oCur );
            $this->fwrapcost     = $oLang->formatCurrency( $this->oxorder__oxwrapcost->value, $oCur );
            $this->ftotalorder   = $this->getTotalOrderSum();
            $this->totalvouchers = 0;

            if ( $this->oxorder__oxvoucherdiscount->value ) {
                $this->totalvouchers  = $oLang->formatCurrency( $this->oxorder__oxvoucherdiscount->value, $oCur );
            }

            if ( $this->oxorder__oxdiscount->value ) {
                $this->discount  = $this->oxorder__oxdiscount->value;
                $this->fdiscount = $oLang->formatCurrency( $this->oxorder__oxdiscount->value, $oCur );
            }
        }
    }

    /**
     * Get total sum from last order
     *
     * @return string
     */
    public function getTotalOrderSum()
    {
        $oCur = $this->getConfig()->getActShopCurrencyObject();
        return number_format( $this->oxorder__oxtotalordersum->value, $oCur->decimal, '.', '');
    }

    /**
     * Get billing country name from billing country id
     *
     * @return oxField
     */
    public function getBillCountry()
    {
        if ( !$this->oxorder__oxbillcountry->value ) {
            $this->oxorder__oxbillcountry = new oxField($this->_getCountryTitle( $this->oxorder__oxbillcountryid->value ));
        }
        return $this->oxorder__oxbillcountry;
    }

    /**
     * Get delivery country name from delivery country id
     *
     * @return oxField
     */
    public function getDelCountry()
    {
        if ( !$this->oxorder__oxdelcountry->value ) {
            $this->oxorder__oxdelcountry = new oxField($this->_getCountryTitle( $this->oxorder__oxdelcountryid->value ));
        }
        return $this->oxorder__oxdelcountry;
    }

    /**
     * Tells to keep old or reload delivery costs while recalculating order
     *
     * @param bool $blReload reload state marker
     *
     * @return null
     */
    public function reloadDelivery( $blReload )
    {
        $this->_blReloadDelivery = $blReload;
    }

    /**
     * Tells to keep old or reload discount while recalculating order
     *
     * @param bool $blReload reload state marker
     *
     * @return null
     */
    public function reloadDiscount( $blReload )
    {
        $this->_blReloadDiscount = $blReload;
    }

}
