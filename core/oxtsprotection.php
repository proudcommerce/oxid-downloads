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
 * @package   core
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxactions.php 28344 2010-06-15 11:32:21Z sarunas $
 */

/**
 * TRusted shops protection product manager.
 * @package core
 */
class oxtsprotection extends oxSuperCfg
{
    /**
     * TS protection products
     *
     * @var array
     */
    protected $_aProducts = null;
    
    /**
     * TS protection product Ids
     *
     * @var array
     */
    protected $_aProductIds = null;

    /**
     * TS protection product nett prices
     *
     * @var array
     */
    protected $_aProductNettPrices = null;

    /**
     * TS protection product amounts
     *
     * @var array
     */
    protected $_aProductAmounts = null;

    /**
     * Buyer protection products
     *
     * @var array
     */
    protected $_sTsProtectProducts = array( "TS080501_500_30_EUR"   => array( "netto" => "0.82", "amount" => "500" ),
                                       "TS080501_1500_30_EUR"  => array( "netto" => "2.47", "amount" => "1500" ),
                                       "TS080501_2500_30_EUR"  => array( "netto" => "4.12", "amount" => "2500" ),
                                       "TS080501_5000_30_EUR"  => array( "netto" => "8.24", "amount" => "5000" ),
                                       "TS080501_10000_30_EUR" => array( "netto" => "16.47", "amount" => "10000" ),
                                       "TS080501_20000_30_EUR" => array( "netto" => "32.94", "amount" => "20000" )
                                );

    /**
     * Buyer protection products
     *
     * @var array
     */
    protected $_sTsCurrencyProducts = array( "TS080501_500_30_EUR"   => array( "GBP" => "TS100629_500_30_GBP", "CHF" => "TS100629_1500_30_GBP", "USD" => "TS080501_500_30_USD" ),
                                       "TS080501_1500_30_EUR"  => array( "GBP" => "TS100629_1500_30_GBP", "CHF" => "TS100629_1500_30_CHF", "USD" => "TS100629_1500_30_USD" ),
                                       "TS080501_2500_30_EUR"  => array( "GBP" => "TS100629_2500_30_GBP", "CHF" => "TS100629_2500_30_CHF", "USD" => "TS100629_2500_30_USD" ),
                                       "TS080501_5000_30_EUR"  => array( "GBP" => "TS100629_5000_30_GBP", "CHF" => "TS100629_5000_30_CHF", "USD" => "TS100629_5000_30_USD" ),
                                       "TS080501_10000_30_EUR" => array( "GBP" => "TS100629_1000_30_GBP", "CHF" => "TS100629_10000_30_CHF", "USD" => "TS100629_10000_30_USD" ),
                                       "TS080501_20000_30_EUR" => array( "GBP" => "TS100629_2000_30_GBP", "CHF" => "TS100629_20000_30_CHF", "USD" => "TS100629_20000_30_USD" )
                                );

    /**
     * Class constructor, loads base objects.
     *
     * @return null
     */
    public function __construct()
    {
        $this->_aProductIds        = $this->_getTsProductIds();
        $this->_aProductNettPrices = $this->_getProductNettPrices();
        $this->_aProductAmounts    = $this->_getProductAmounts();
    }

    /**
     * Returns array of TS protection products according to order price
     *
     * @param double $dPrice order price
     *
     * @return array
     */
    public function getTsProducts( $dPrice )
    {
        $oProduct = $this->getProduct( 0 );
        $aTsProducts = array($oProduct);
        for ($i=0; $i<count($this->_aProductIds); $i++ ) {
            if ( $this->_aProductAmounts[$i] < $dPrice ) {
                $oProduct = $this->getProduct( $i+1 );
                $aTsProducts[] = $oProduct;
            } else {
                $i = count($this->_aProductIds);
            }
        }
        return $aTsProducts;
    }

    /**
     * Returns TS protection product by id
     *
     * @param string $sTsId TS protection product id
     *
     * @return object
     */
    public function getTsProduct( $sTsId )
    {
        $sKey = array_search( $sTsId, $this->_aProductIds );
        $oProduct = $this->getProduct( $sKey );
        return $oProduct;
    }

    /**
     * Creats and returns TS protection product by key
     *
     * @param string $sKey key
     *
     * @return object
     */
    public function getProduct( $sKey )
    {
        $oConfig  = $this->getConfig();
        $dVat     = $oConfig->getConfigParam( 'dDefaultVAT' );
        $dPrice   = oxPrice::netto2Brutto($this->_aProductNettPrices[$sKey], $dVat);
        $oProduct = new oxStdClass();
        $oProduct->oPrice = oxNew( 'oxPrice' );
        $oProduct->oPrice->setPrice( $dPrice );
        if ( $oConfig->getConfigParam( 'blCalcVATForPayCharge' ) ) {
            $oProduct->oPrice->setVat( $dVat );
        }
        $oProduct->sTsId = $this->_aProductIds[$sKey];
        $oProduct->iAmount = $this->_aProductAmounts[$sKey];
        $oProduct->fPrice = $oProduct->oPrice->getBruttoPrice();
        return $oProduct;
    }

    /**
     * Executes TS protection
     *
     * @param array  $aValues    Order values
     * @param string $sPaymentId Order payment id
     *
     * @return bool
     */
    public function requestForTsProtection( $aValues, $sPaymentId )
    {
        $oConfig = $this->getConfig();
        $iLangId = (int) oxLang::getInstance()->getBaseLanguage();
        $blTsTestMode = $oConfig->getConfigParam( 'tsTestMode' );
        $aTsUser = $oConfig->getConfigParam( 'aTsUser' );
        $aTsPassword = $oConfig->getConfigParam( 'aTsPassword' );
        $aTrustedShopIds = $oConfig->getConfigParam( 'iShopID_TrustedShops' );
        if ( $aTrustedShopIds && $aTrustedShopIds[$iLangId] ) {
            try {
                if ( $blTsTestMode ) {
                    $sSoapUrl = $oConfig->getConfigParam( 'sTsTestProtectionUrl' );
                } else {
                    $sSoapUrl = $oConfig->getConfigParam( 'sTsProtectionUrl' );
                }
                $sFunction = 'requestForProtectionV2';
                $sVersion = $this->getConfig()->getVersion();
                $sEdition = $this->getConfig()->getFullEdition();
                $sTsPaymentId = $this->_getTsPaymentId($sPaymentId);
                $tsProductId = $this->_getTsProductCurrId($aValues['tsProductId'], $aValues['currency']);
                $aValues['tsId']    = $aTrustedShopIds[$iLangId];
                $aValues['paymentType'] = $sTsPaymentId;
                $aValues['shopSystemVersion'] = $sEdition . " " . $sVersion;
                $aValues['wsUser'] = $aTsUser[$iLangId];
                $aValues['wsPassword'] = $aTsPassword[$iLangId];
                $aValues['orderDate'] = str_replace(" ", "T", $aValues['orderDate']);
                $oSoap = new SoapClient($sSoapUrl);
                $aResults = $oSoap->{$sFunction}($aValues['tsId'],$tsProductId,$aValues['amount'],$aValues['currency'],$aValues['paymentType'],
                $aValues['buyerEmail'],$aValues['shopCustomerID'],$aValues['shopOrderID'],$aValues['orderDate'],$aValues['shopSystemVersion'],
                $aValues['wsUser'],$aValues['wsPassword']);

                if ( isset($aResults) && "" != $aResults ) {
                    if ( $aResults == "-10001" ) {
                        oxUtils::getInstance()->logger( "NO_VALID_SHOP" );
                        return false;
                    }
                    if ( $aResults == "-11111" ) {
                        oxUtils::getInstance()->logger( "SYSTEM_ERROR" );
                        return false;
                    }
                    return $aResults;
                }
            } catch( Exception $eException ) {
                oxUtils::getInstance()->logger( "Soap-Error: " . $eException->faultstring );
                return false;
            }
        }
        return null;

    }

    /**
     * Executes TS certificate check
     *
     * @param integer $iTrustedShopId Trusted shop Id
     * @param bool    $blTsTestMode   if test mode is on
     *
     * @return object
     */
    public function checkCertificate( $iTrustedShopId, $blTsTestMode )
    {
        if ( $iTrustedShopId ) {
            try {
                if ( $blTsTestMode ) {
                    $sSoapUrl = 'https://qa.trustedshops.de/ts/services/TsProtection?wsdl';
                } else {
                    $sSoapUrl = 'https://www.trustedshops.de/ts/services/TsProtection?wsdl';
                }
                $sFunction = 'checkCertificate';
                $aValues['tsId']    = $iTrustedShopId;
                $oSoap = new SoapClient($sSoapUrl);
                $aResults = $oSoap->{$sFunction}($aValues['tsId']);
                if ( isset($aResults) ) {
                    return $aResults;
                }
            } catch( Exception $eException ) {
                oxUtils::getInstance()->logger( "Soap-Error: " . $eException->faultstring );
                return false;
            }
        }
        return null;

    }

    /**
     * Returns TS payment id by shop payment id
     *
     * @param string $sPaymentId payment id
     *
     * @return string
     */
    protected function _getTsPaymentId( $sPaymentId )
    {
        $aPayment = oxNew("oxpayment");
        if ( $aPayment->load($sPaymentId) ) {
            $sTsPaymentId = $aPayment->oxpayments__oxtspaymentid->value;
        }
        return $sTsPaymentId;
    }

    /**
     * Returns TS protection product Ids
     *
     * @return array
     */
    protected function _getTsProductIds()
    {
        if ($this->_aProductIds == null) {
            $this->_aProductIds = false;
            if ( $aTsProducts = $this->_getTsProducts()) {
                $this->_aProductIds = array_keys($aTsProducts);
            }
        }
        return $this->_aProductIds;
    }

    /**
     * Returns TS protection product nettprices
     *
     * @return array
     */
    protected function _getProductNettPrices()
    {
        if ($this->_aProductNettPrices == null) {
            $this->_aProductNettPrices = false;
            if ( $aTsProducts = $this->_getTsProducts()) {
                $this->_aProductNettPrices = array();
                foreach ( $aTsProducts as $aTsProduct ) {
                    $this->_aProductNettPrices[] = $aTsProduct['netto'];
                }
            }
        }
        return $this->_aProductNettPrices;
    }

    /**
     * Returns TS protection product amounts
     *
     * @return array
     */
    protected function _getProductAmounts()
    {
        if ($this->_aProductAmounts == null) {
            $this->_aProductAmounts = false;
            if ( $aTsProducts = $this->_getTsProducts()) {
                $this->_aProductAmounts = array();
                foreach ( $aTsProducts as $aTsProduct ) {
                    $this->_aProductAmounts[] = $aTsProduct['amount'];
                }
            }
        }
        return $this->_aProductAmounts;
    }

    /**
     * Returns TS protection products
     *
     * @return array
     */
    protected function _getTsProducts()
    {
        if ($this->_aProducts == null) {
            $this->_aProducts = false;
            if ( $aTsProducts = $this->_sTsProtectProducts) {
                $this->_aProducts = $aTsProducts;
            }
        }
        return $this->_aProducts;
    }

    /**
     * Returns TS protection product id by currency
     *
     * @param string $sTsId product id
     * @param string $sCurr active currency
     *
     * @return array
     */
    protected function _getTsProductCurrId( $sTsId, $sCurr )
    {
        $sTsCurrId = $sTsId;
        if ($sCurr != 'EUR') {
            $aTsCurrId = $this->_sTsCurrencyProducts[$sTsId];
            $sTsCurrId = $aTsCurrId[$sCurr];
        }
        return $sTsCurrId;
    }

}
