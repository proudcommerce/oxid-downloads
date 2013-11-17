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
 */

/**
 * Admin voucerserie data export manager.
 * Performs voucherserie data export to user chosen cvs format file.
 * Admin Menu: Shop Settings -> Vouchers -> Export.
 * @package admin
 */
class VoucherSerie_Export extends oxAdminDetails
{
    /**
     * Executes parent method parent::render() and returns name of template
     * file "voucherserie_export.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $sFilepath = oxConfig::getParameter( "filepath" );
        if ( !isset( $sFilepath)) {
            $sFilepath = getShopBasePath();
            $sFilepath.= "export/oxexport.csv";
        }

        $this->_aViewData["filepath"] =  $sFilepath;
        return "voucherserie_export.tpl";
    }

    /**
     * Performs Voucherserie export to export file.
     *
     * @return null
     */
    public function export()
    {
        $sFilepath = oxConfig::getParameter( "filepath");
        oxSession::setVar( "filepath", $sFilepath);

        $oSerie = oxNew( "oxvoucherserie" );
        $oSerie->load(oxConfig::getParameter("oxid"));

        $oDB = oxDb::getDb();

        $sSelect = "select oxvouchernr from oxvouchers where oxvoucherserieid = '" . $oSerie->oxvoucherseries__oxid->value . "'";
        $rs = $oDB->execute($sSelect);
        // if first, delete the file
        $fp = @fopen( $sFilepath, "w");
        if ( $fp) {
             fputs( $fp, "Gutschein\n");
            while (!$rs->EOF) {

                $sLine = "";

                foreach ( $rs->fields as $field)
                    $sLine .= $field/*.$myConfig->sCSVSign*/;
                $sLine .= "\n";

                fputs( $fp, $sLine);

                $rs->moveNext();
            }
            fclose( $fp);
            $this->_aViewData["exportCompleted"] = true;
        } else
            $this->_aViewData["exportCompleted"] =false;
    }
}
