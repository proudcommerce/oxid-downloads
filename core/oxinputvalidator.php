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
 * $Id: oxinputvalidator.php 21536 2009-08-11 16:35:04Z tomas $
 */

/**
 * Includes credit card validation class.
 */
require_once oxConfig::getInstance()->getConfigParam( 'sCoreDir' ) . "ccval/ccval.php";

/**
 * Calss for validating input
 *
 */
class oxInputValidator
{
    /**
     * Required fields for credit card payment
     *
     * @var array
     */
    protected $_aRequiredCCFields = array( 'kktype',
                                           'kknumber',
                                           'kkmonth',
                                           'kkyear',
                                           'kkname',
                                           'kkpruef'
                                          );

    /**
     * Possible credit card types
     *
     * @var array
     */
    protected $_aPossibleCCType = array( 'mcd', // Master Card
                                         'vis', // Visa
                                         'amx', // American Express
                                         'dsc', // Discover
                                         'dnc', // Diners Club
                                         'jcb', // JCB
                                         'swi', // Switch
                                         'dlt', // Delta
                                         'enr'  // EnRoute
                                        );

    /**
     * Required fields for debit cards
     *
     * @var array
     */
    protected $_aRequiredDCFields = array( 'lsbankname',
                                           'lsblz',
                                           'lsktonr',
                                           'lsktoinhaber'
                                         );

   /**
     * Class constructor. The constructor is defined in order to be possible to call parent::__construct() in modules.
     *
     * @return null;
     */
	public function __construct()
	{
	}

    /**
     * Validates basket amount
     *
     * @param float $dAmount amount of article
     *
     * @throws oxArticleInputException if amount is not numeric or smaller 0
     *
     * @return float
     */
    public function validateBasketAmount( $dAmount )
    {
        $dAmount = str_replace( ',', '.', $dAmount );

        if ( !is_numeric( $dAmount ) || $dAmount < 0) {
            $oEx = oxNew( 'oxArticleInputException' );
            $oEx->setMessage('EXCEPTION_INPUT_INVALIDAMOUNT');
            throw $oEx;
        }

        if ( !oxConfig::getInstance()->getConfigParam( 'blAllowUnevenAmounts' ) ) {
            $dAmount = round( ( string ) $dAmount );
        }

        //negative amounts are not allowed
        //$dAmount = abs($dAmount);

        return $dAmount;
    }

    /**
     * Validates payment input data for credit card and debit note
     *
     * @param string $sPaymentId the payment id of current payment
     * @param array  &$aDynvalue values of payment
     *
     * @return bool
     */
    public function validatePaymentInputData( $sPaymentId, & $aDynvalue )
    {
        $blOK = true;

        switch( $sPaymentId ) {
            case 'oxidcreditcard':

                $blOK = false;

                foreach ( $this->_aRequiredCCFields as $sFieldName ) {
                    if ( !isset( $aDynvalue[$sFieldName] ) || !trim( $aDynvalue[$sFieldName] ) ) {
                        break 2;
                    }
                }

                if ( in_array( $aDynvalue['kktype'], $this->_aPossibleCCType ) ) {
                    $sType = $aDynvalue['kktype'];
                } else {
                    $sType = null;
                    break;
                }

                $blResult = ccval( $aDynvalue['kknumber'], $sType, $aDynvalue['kkmonth'].substr( $aDynvalue['kkyear'], 2, 2 ) );
                if ( $blResult ) {
                    $blOK = true;
                }

                break;

            case "oxiddebitnote":

                $blOK = false;

                foreach ( $this->_aRequiredDCFields as $sFieldName ) {
                    if ( !isset( $aDynvalue[$sFieldName] ) || !trim( $aDynvalue[$sFieldName] ) ) {
                        break 2;
                    }
                }

                // cleaning up spaces
                $aDynvalue['lsblz']   = str_replace( ' ', '', $aDynvalue['lsblz'] );
                $aDynvalue['lsktonr'] = str_replace( ' ', '', $aDynvalue['lsktonr'] );

                //if konto number is shorter than 10, add zeros in front of number
                if ( strlen( $aDynvalue['lsktonr'] ) < 10 ) {
                    $sNewNum = str_repeat( '0', 10 - strlen( $aDynvalue['lsktonr'] ) ).$aDynvalue['lsktonr'];
                    $aDynvalue['lsktonr'] = $sNewNum;
                }

                if ( preg_match( "/^\d{5,8}$/", $aDynvalue['lsblz'] ) && preg_match( "/\d{10}/", $aDynvalue['lsktonr'] ) ) {
                    $blOK = true;
                }
                break;
        }

        return $blOK;
    }
}
