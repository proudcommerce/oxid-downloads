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
 * $Id: order_package.php 17585 2009-03-25 08:54:05Z vilma $
 */

/**
 * Admin order package manager.
 * Collects order package information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders.
 * @package admin
 */
class Order_Package extends oxAdminDetails
{
    /**
     * Searches array for some value, on success returns value, else returns false.
     *
     * @param string $needle   String to search
     * @param array  $haystick Searchable array
     *
     * @return mixed
     */
    public function myarray_search ($needle, $haystick)
    {    // uses key instead of value

        foreach ($haystick as $key => $val) {
            if ($needle === $key) {
                return($key);
            }
        }

        return false;
    }

    /**
     * Executes parent method parent::render(), fetches order info from DB,
     * passes it to Smarty engine and returns name of template file.
     * "order_package.tpl"
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $oDB = oxDb::getDb();
        $OldMode = $oDB->setFetchMode( ADODB_FETCH_ASSOC);

        $sSelect  = "select * from oxwrapping";
        $rs = $oDB->selectLimit( $sSelect, 5000, 0);

        $aWrappings = array();
        if ($rs != false && $rs->recordCount() > 0) {
            while ( !$rs->EOF) {
                $key = $rs->fields['OXID'];
                $aWrappings[$key] = new oxStdClass();
                $aWrappings[$key]->fields = $rs->fields;
                $rs->moveNext();
            }
        }

        $sSelect  = "select oxorder.*, oxorderarticles.*, oxorder.oxid as orderid, oxorderarticles.oxid as orderartid from oxorder left join oxorderarticles on oxorder.oxid = oxorderarticles.oxorderid where oxorder.oxsenddate = '0000-00-00 00:00:00' and oxorder.oxshopid = '".$myConfig->getShopId()."' and oxorderarticles.oxstorno != '1' order by oxorder.oxorderdate asc";
        $rs = $oDB->selectLimit( $sSelect, 5000, 0);

        $iCnt = 0;

        $aOrders = array();

        if ($rs != false && $rs->recordCount() > 0) {
            while ( !$rs->EOF) {
                $key = $rs->fields['orderid'];

                if ( !$aOrders[$key] ) {
                    $aOrders[$key] = new oxStdClass();
                }
                $aOrders[$key]->fields = $rs->fields;
                if ( !$aOrders[$key]->articles ) {
                	$aOrders[$key]->articles = array();
                }
                $aOrders[$key]->articles[$rs->fields['orderartid']] = $rs->fields;

                $sLangAppend = '';
                if ($rs->fields['OXLANG'])
                    $sLangAppend = '_'.$rs->fields['OXLANG'];

                if ($rs->fields['OXCARDID'] && isset($aWrappings[$rs->fields['OXCARDID']]))
                    $aOrders[$key]->sPostCardName = $aWrappings[$rs->fields['OXCARDID']]->fields['OXNAME'.$sLangAppend];
                else
                    $aOrders[$key]->oPostCard = "";

                $aArticle =  & $aOrders[$key]->articles[$rs->fields['orderartid']];
                if ($rs->fields['OXWRAPID'] && isset($aWrappings[$rs->fields['OXWRAPID']]))
                    $aArticle['sPostCardName'] = $aWrappings[$rs->fields['OXWRAPID']]->fields['OXNAME'.$sLangAppend];
                else
                    $aArticle['sPostCardName'] = '';

                $rs->moveNext();
                $iCnt++;
            }
        }

        $oDB->setFetchMode( $OldMode);

        $this->_aViewData['resultset']     = @$aOrders;

        return "order_package.tpl";
    }
}
