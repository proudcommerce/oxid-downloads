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
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: payment_overview.php 33186 2011-02-10 15:53:43Z arvydas.vapsva $
 */

/**
 * Class for extending
 * @package admin
 */
class Payment_Overview extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxpayment object and
     * passes it to Smarty engine, returns name of template file
     * "payment_overview.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = $this->getEditObjectId();
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oPayment = oxNew( "oxpayment" );
            $oPayment->load( $soxId);
            $this->_aViewData["edit"] =  $oPayment;
        }

        return "payment_overview.tpl";
    }
}
