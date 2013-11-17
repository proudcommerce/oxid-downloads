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
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: voucherserie_main.php 29410 2010-08-18 14:08:28Z arvydas $
 */

/**
 * Admin article main voucherserie manager.
 * There is possibility to change voucherserie name, description, valid terms
 * and etc.
 * Admin Menu: Shop Settings -> Vouchers -> Main.
 * @package admin
 */
class VoucherSerie_Main extends DynExportBase
{
    /**
     * Export class name
     *
     * @var string
     */
    public $sClassDo = "voucherSerie_generate";

    /**
     * Voucher serie object
     *
     * @var oxvoucherserie
     */
    protected $_oVoucherSerie = null;

    /**
     * Current class template name
     *
     * @var string
     */
    protected $_sThisTemplate = "voucherserie_main.tpl";

    /**
     * Executes parent method parent::render(), creates oxvoucherserie object,
     * passes data to Smarty engine and returns name of template file
     * "voucherserie_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = oxConfig::getParameter( "oxid" );

        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid" );
        if ( ( $soxId == "-1" || !isset( $soxId ) ) && isset( $sSavedID ) ) {
            $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid" );
            $this->_aViewData["oxid"] = $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] = "1";
        }

        if ( $soxId != "-1" && isset( $soxId ) ) {
            // load object
            $oVoucherSerie = oxNew( "oxvoucherserie" );
            $oVoucherSerie->load( $soxId );
            $this->_aViewData["edit"]   = $oVoucherSerie;

        }

        return $this->_sThisTemplate;
    }

    /**
     * Saves main Voucherserie parameters changes.
     *
     * @return mixed
     */
    public function save()
    {

        // Parameter Processing
        $soxId          = oxConfig::getParameter( "oxid" );
        $aSerieParams   = oxConfig::getParameter("editval");

        // Voucher Serie Processing
        $oVoucherSerie = oxNew( "oxvoucherserie" );
        // if serie already exist use it
        if ( $soxId != "-1" ) {
            $oVoucherSerie->load( $soxId );
        } else {
            $aSerieParams["oxvoucherseries__oxid"] = null;
        }


        $this->_aViewData["updatelist"] = "1";

        $oVoucherSerie->assign( $aSerieParams );
        $oVoucherSerie->save();

        // set oxid if inserted
        if ( $soxId == "-1" ) {
            oxSession::setVar("saved_oxid", $oVoucherSerie->getId() );
        }
    }

    /**
     * Returns voucher status information array
     *
     * @return array
     */
    public function getStatus()
    {
        if ( $oSerie = $this->_getVoucherSerie() ) {
           return $oSerie->countVouchers();
        }
    }

    /**
     * Overriding parent function, doing nothing..
     *
     * @return null
     */
    public function prepareExport()
    {
    }


    /**
     * Returns voucher serie object
     *
     * @return oxvoucherserie
     */
    protected function _getVoucherSerie()
    {
        if ( $this->_oVoucherSerie == null ) {
            $oVoucherSerie = oxNew( "oxvoucherserie" );
            if ( $oVoucherSerie->load( oxConfig::getParameter( "voucherid" ) ) ) {
                $this->_oVoucherSerie = $oVoucherSerie;
            }
        }
        return $this->_oVoucherSerie;
    }

    /**
     * Prepares Export
     *
     * @return null
     */
    public function start()
    {
        parent::start();

        // saving export info
        oxSession::setVar( "voucherid", oxConfig::getParameter( "voucherid" ) );
        oxSession::setVar( "voucherAmount", abs( (int) oxConfig::getParameter( "voucherAmount" ) ) );
        oxSession::setVar( "randomVoucherNr", oxConfig::getParameter( "randomVoucherNr" ) );
        oxSession::setVar( "voucherNr", oxConfig::getParameter( "voucherNr" ) );
    }
}
