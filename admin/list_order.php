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
 * @version   SVN: $Id: list_order.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * user list "view" class.
 * @package admin
 */
class List_Order extends Order_List
{
    /**
     * Viewable list size getter
     *
     * @return int
     */
    protected function _getViewListSize()
    {
        return $this->_getUserDefListSize();
    }

    /**
     * Executes parent method parent::render(), passes data to Smarty engine
     * and returns name of template file "list_review.tpl".
     *
     * @return string
     */
    public function render()
    {
        oxAdminList::render();

        $this->_aViewData["viewListSize"]  = $this->_getViewListSize();
        $this->_aViewData["whereparam"]    = $this->_aViewData["whereparam"] . '&amp;viewListSize='.$this->_getViewListSize();
        $this->_aViewData["menustructure"] = $this->getNavigation()->getDomXml()->documentElement->childNodes;

        return "list_order.tpl";
    }


    /**
     * Adding folder check
     *
     * @param array  $aWhere  SQL condition array
     * @param string $sqlFull SQL query string
     *
     * @return $sQ
     */
    public function _prepareWhereQuery( $aWhere, $sqlFull )
    {
        return oxAdminList::_prepareWhereQuery( $aWhere, $sqlFull );
    }

    /**
     * Calculates list items count
     *
     * @param string $sSql SQL query used co select list items
     *
     * @return null
     */
    protected function _calcListItemsCount( $sSql )
    {
        // count SQL
        $sSql = preg_replace( '/select .* from/', 'select count(*) from ', $sSql );

        // removing order by
        $sSql = preg_replace( '/order by .*$/', '', $sSql );

        // con of list items which fits current search conditions
        $this->_iListSize = oxDb::getDb()->getOne( "select count(*) from ( $sSql ) as test" );

        // set it into session that other frames know about size of DB
        oxSession::setVar( 'iArtCnt', $this->_iListSize );
    }

    /**
     * Returns select query string
     *
     * @param object $oObject Object
     *
     * @return string
     */
    protected function _buildSelectString( $oObject = null )
    {
        return 'select oxorderarticles.oxid, oxorder.oxid as oxorderid, max(oxorder.oxorderdate) as oxorderdate, oxorderarticles.oxartnum, sum( oxorderarticles.oxamount ) as oxorderamount, oxorderarticles.oxtitle, round( sum(oxorderarticles.oxbrutprice*oxorder.oxcurrate),2) as oxprice from oxorderarticles left join oxorder on oxorder.oxid=oxorderarticles.oxorderid where 1 ';
    }

    /**
     * Adds order by to SQL query string.
     *
     * @param string $sSql sql string
     *
     * @return string
     */
    protected function _prepareOrderByQuery( $sSql = null )
    {
        // calculating sum
        $sSumQ = preg_replace("/select .*? from/", "select round( sum(oxorderarticles.oxbrutprice*oxorder.oxcurrate),2) from", $sSql );
        $this->_aViewData["sumresult"] = oxDb::getDb()->getOne( $sSumQ );

        $sSql = " $sSql group by oxorderarticles.oxartnum";
        if ( $sSort = oxConfig::getParameter( "sort" ) ) {
            if ($sSort == 'oxorder.oxorderdate') {
                $sSql .= " order by max(oxorder.oxorderdate) DESC";
            } else {
                $sSql .= " order by " . oxDb::getInstance()->escapeString( $sSort );
            }
        }
        return $sSql;
    }
}
