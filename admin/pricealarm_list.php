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
 * $Id: pricealarm_list.php 17243 2009-03-16 15:16:57Z arvydas $
 */

/**
 * Admin pricealarm list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Customer News -> pricealarm.
 * @package admin
 */
class PriceAlarm_List extends oxAdminList
{
    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxpricealarm';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSort = "oxpricealarm.oxuserid";

    /**
     * Modifying SQL query to load additional article and customer data
     *
     * @param object $oListObject list main object
     *
     * @return string
     */
    protected function _buildSelectString( $oListObject = null )
    {
        $sSql  = "select oxpricealarm.*, oxarticles.oxtitle AS articletitle, ";
        $sSql .= "oxuser.oxlname as userlname, oxuser.oxfname as userfname ";
        $sSql .= "from oxpricealarm ";
        $sSql .= "left join oxarticles on oxarticles.oxid = oxpricealarm.oxartid ";
        $sSql .= "left join oxuser on oxuser.oxid = oxpricealarm.oxuserid WHERE 1 ";

        return $sSql;
    }

    /**
     * Executes parent method parent::render() and returns name of
     * template file "pricealarm_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();


        $oDefCurr = $myConfig->getActShopCurrencyObject();
        $myUtils  = oxUtils::getInstance();
        $myLang   = oxLang::getInstance();

        foreach ( $this->_aViewData["mylist"] as $oListItem ) {

            $oArticle = oxNew( "oxarticle" );
            $oArticle->load($oListItem->oxpricealarm__oxartid->value);

            //$oListI = $this->_aViewData["mylist"][$sItemId];
            $oThisCurr = $myConfig->getCurrencyObject( $oListItem->oxpricealarm__oxcurrency->value );

            // #869A we should perform currency conversion
            // (older versions doesn't have currency info - assume as it is default - first in currency array)
            if ( !$oThisCurr ) {
                $oThisCurr = $myConfig->getCurrencyObject( $oDefCurr->name );
                $oListItem->oxpricealarm__oxcurrency->setValue($oDefCurr->name);
            }

            // #889C - Netto prices in Admin
            // (we have to call $oArticle->getPrice() to get price with VAT)
            $dArtPrice = $oArticle->getPrice()->getBruttoPrice() * $oThisCurr->rate;
            $dArtPrice = $myUtils->fRound( $dArtPrice );

            $oListItem->fprice = $myLang->formatCurrency( $dArtPrice, $oThisCurr );

            if ( $oArticle->oxarticles__oxparentid->value && !$oArticle->oxarticles__oxtitle->value) {
                $oParent = oxNew( "oxarticle" );
                $oParent->load($oArticle->oxarticles__oxparentid->value);
                $oListItem->oxpricealarm__articletitle = new oxField( $oParent->oxarticles__oxtitle->value." ".$oArticle->oxarticles__oxvarselect->value );
            }

            $oListItem->fpricealarmprice = $myLang->formatCurrency( $oListItem->oxpricealarm__oxprice->value, $oThisCurr);

            // neutral status
            $oListItem->iStatus = 0;

            // shop price is less or equal
            if ( $oListItem->oxpricealarm__oxprice->value >= $dArtPrice)
                $oListItem->iStatus = 1;

            // suggestion to user is sent
            if ( $oListItem->oxpricealarm__oxsended->value != "0000-00-00 00:00:00")
                $oListItem->iStatus = 2;
        }

        return "pricealarm_list.tpl";
    }

    /**
     * Builds and returns array of SQL WHERE conditions
     *
     * @return array
     */
    public function buildWhere()
    {
        $this->_aWhere = parent::buildWhere();

        if ( !is_array($this->_aWhere))
            $this->_aWhere = array();

        // updating price fields values for correct search in DB
        if ( $this->_aWhere['oxpricealarm.oxprice'] ) {
            $sPriceParam = (double) str_replace(array('%',','), array('', '.'), $this->_aWhere['oxpricealarm.oxprice']);
            $this->_aWhere['oxpricealarm.oxprice'] = '%'. $sPriceParam. '%';
        }

        if ( $this->_aWhere['oxarticles.oxprice'] ) {
            $sPriceParam = (double) str_replace(array('%',','), array('', '.'), $this->_aWhere['oxarticles.oxprice']);
            $this->_aWhere['oxarticles.oxprice'] = '%'. $sPriceParam. '%';
        }

        return $this->_aWhere;
    }

}
