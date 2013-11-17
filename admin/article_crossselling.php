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
 * $Id: article_crossselling.php 17243 2009-03-16 15:16:57Z arvydas $
 */

/**
 * Admin article crosselling/accesories manager.
 * Creates list of available articles, there is ability to assign or remove
 * assigning of article to crosselling/accesories with other products.
 * Admin Menu: Manage Products -> Articles -> Crosssell.
 * @package admin
 */
class Article_Crossselling extends oxAdminDetails
{

    /**
     * Collects article crosselling and attributes information, passes
     * them to Smarty engine and returns name or template file
     * "article_crossselling.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['edit'] = $oArticle = oxNew( 'oxarticle' );

        // crossselling
        $sChosenArtCat = $this->_getCategoryTree( "artcattree", oxConfig::getParameter( "artcat"));

        // accessoires
        $sChosenArtCat2 = $this->_getCategoryTree( "artcattree2", oxConfig::getParameter( "artcat2"));

        $soxId = oxConfig::getParameter( "oxid");
        if ( $soxId != "-1" && isset( $soxId ) ) {   // load object
            $oArticle->load( $soxId);

            if ($oArticle->isDerived())
                $this->_aViewData['readonly'] = true;
        }

        $aColumns = array();
        $iAoc = oxConfig::getParameter("aoc");
        if ( $iAoc == 1 ) {

            include_once 'inc/article_crossselling.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/article_crossselling.tpl";
        } elseif ( $iAoc == 2 ) {

            include_once 'inc/article_accessories.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/article_accessories.tpl";
        }
        return "article_crossselling.tpl";
    }

    /**
     * Universal method, perfoms object sorting. Return sorting value.
     *
     * @param string $sTable name of table used (default null)
     *
     * @return int
     */
    public function setSorting( $sTable = null)
    {
        if ( $sTable == null)
            $sTable = oxConfig::getParameter("stable");
        $soxId     = oxConfig::getParameter("oxid");
        $sSorting  = oxConfig::getParameter("sorting");
        $sTarget   = oxConfig::getParameter("starget");
        $aObjectId = oxConfig::getParameter( $sTarget);


        if ( !isset($soxId) || $soxId == "-1" || $sTable == null || !$sTable)
            return 0;

        $oDB = oxDb::getDb();
        //means appending article to the end, sorting number will be last number
        if ( !isset($sSorting) || !$sSorting) {
            $sSelect = "select count(*) from $sTable where $sTable.oxarticlenid = '".$soxId."' ";
            return $oDB->getOne( $sSelect);
        } else if ( count($aObjectId) > 0) {
            if ( $sSorting == "up") {
                $aList = $this->_getSortingList( $sTable);
                $sFItmId = $aObjectId[0];
                foreach ( $aList as $iNum => $aItem) {
                    if ( $aItem[2] == $sFItmId && $iNum > 0) {
                        $sSelect = "update $sTable set $sTable.oxsort=".( $iNum + count($aObjectId) - 1 )." where $sTable.oxobjectid='".$aList[$iNum-1][2]."'";
                        $oDB->execute( $sSelect);
                        foreach ( $aObjectId as $iSNum => $sItem) {
                            $sSelect = "update $sTable set $sTable.oxsort=".( $iNum + $iSNum - 1 )." where $sTable.oxobjectid='".$sItem."'";
                            $oDB->execute( $sSelect);
                        }
                        break;
                    }
                }
            } elseif ( $sSorting == "down") {
                $aList = $this->_getSortingList( $sTable);
                $sFItmId = $aObjectId[count($aObjectId)-1];
                foreach ( $aList as $iNum => $aItem) {
                    if ( $aItem[2] == $sFItmId && $iNum < (count($aList)-1)) {
                        $sSelect = "update $sTable set $sTable.oxsort=".( $iNum - count($aObjectId) + 1 )." where $sTable.oxobjectid='".$aList[$iNum+1][2]."'";
                        $oDB->execute( $sSelect);
                        foreach ( $aObjectId as $iSNum => $sItem) {
                            $sSelect = "update $sTable set $sTable.oxsort=".( $iNum - $iSNum + 1 )." where $sTable.oxobjectid='".$sItem."'";
                            $oDB->execute( $sSelect);
                        }
                        break;
                    }
                }
            }
            $this->updateSorting( $sTable );
        }
    }


    /**
     * Methods used for updating sorting info after some objects were removed/added
     *
     * @param string $sTable object's data table
     *
     * @return null
     */
    public function updateSorting( $sTable )
    {
        $soxId = oxConfig::getParameter( "oxid");
        if ( !isset($soxId) && $soxId == "-1")
            return;

        $aList = $this->_getSortingList( $sTable);
        // updates sorting
        $oDB = oxDb::getDb();
        foreach ( $aList as $iNum => $aItem) {
            if ( $aItem[1] != $iNum) {
                $sSelect = "update $sTable set $sTable.oxsort=$iNum where $sTable.oxid='".$aItem[0]."'";
                $oDB->execute( $sSelect);
            }
        }
    }


    /**
     * Collects and returns array of object ID's for sorting, or false on error
     *
     * @param string $sTable object's data table
     *
     * @return mixed
     */
    protected function _getSortingList( $sTable)
    {
        $soxId    = oxConfig::getParameter( "oxid");


        if ( !isset($soxId) && $soxId == "-1")
            return;
        $sSelect  = "select $sTable.oxid, $sTable.oxsort, $sTable.oxobjectid , ";
        $sSelect .= "$sTable.oxarticlenid from $sTable where $sTable.oxarticlenid = '".$soxId."' order by $sTable.oxsort";
        $oDB = oxDb::getDb();
        $aList = array();
        $rs = $oDB->selectLimit( $sSelect, 1000, 0);
        //fetches assigned article list
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $aList[] = array($rs->fields[0], $rs->fields[1], $rs->fields[2], $rs->fields[3]);
                $rs->moveNext();
            }
        }
        return $aList;
    }

}
