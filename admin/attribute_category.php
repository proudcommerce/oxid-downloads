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
 * $Id: attribute_category.php 17243 2009-03-16 15:16:57Z arvydas $
 */

/**
 * Admin category main attributes manager.
 * There is possibility to change attribute description, assign categories to
 * this attribute, etc.
 * Admin Menu: Manage Products -> Attributes -> Gruppen.
 * @package admin
 */
class Attribute_Category extends oxAdminDetails
{
    /**
     * Loads Attribute categories info, passes it to Smarty engine and
     * returns name of template file "attribute_main.tpl".
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId = oxConfig::getParameter( "oxid");
        $this->_aViewData["oxid"] =  $soxId;

            $aListAllIn = array();
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oAttr = oxNew( "oxattribute" );
            $oAttr->load( $soxId);
            $this->_aViewData["edit"] =  $oAttr;
        }

        if ( oxConfig::getParameter("aoc") ) {

            $aColumns = array();
            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/attribute_category.tpl";
        }
        return "attribute_category.tpl";
    }

    //#1152M - default sorting for attributes
    /**
     *
     * @return null
     */
    protected function _loadCategory()
    {
        $sChosenCatId = oxConfig::getParameter("chosenCatId");
        if ( isset($sChosenCatId) && $sChosenCatId) {
            $suffix = oxLang::getInstance()->getLanguageTag( $this->_iEditLang);
            $sSelect  = "select oxcategory2attribute.oxid, oxattribute.oxtitle$suffix from oxcategory2attribute, oxattribute ";
            $sSelect .= "where oxcategory2attribute.oxobjectid='$sChosenCatId' and oxattribute.oxid=oxcategory2attribute.oxattrid ";
            $sSelect .= "order by oxcategory2attribute.oxsort, oxattribute.oxpos, oxattribute.oxtitle$suffix ";
            $oDB = oxDb::getDb();
            $aList = array();
            $rs = $oDB->selectLimit( $sSelect, 1000, 0);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $oSel = new stdClass(); // #663
                    $oSel->oxcategory2attribute__oxid = new oxField($rs->fields[0]);
                    $oSel->oxattribute__oxtitle       = new oxField($rs->fields[1]);
                    $aList[] = $oSel;
                    $rs->moveNext();
                }
            }
            $this->_aViewData["chosenCatSel"] =  $aList;
            $this->_aViewData["chosenCatId"] = $sChosenCatId;
        }
    }

    /**
     * Universal method, perfoms object sorting. Return sorting value.
     *
     * @param string $sTable name of table used (default null)
     * @param string $soxId
     *
     * @return int
     */
    public function setSorting( $sTable = null, $soxId = null)
    {
        if ( $sTable == null)
            $sTable = oxConfig::getParameter("stable");
        if ( $soxId == null)
            $soxId =  oxConfig::getParameter("chosenCatId");
        $sSorting  = oxConfig::getParameter("sorting");
        $sTarget   = oxConfig::getParameter("starget");
        $aObjectId = oxConfig::getParameter( $sTarget);
        if ( !isset($soxId) || $soxId == "-1" || $sTable == null || !$sTable)
            return 0;
        $oDB = oxDb::getDb();

        //means appending article to the end, sorting number will be last number
        if ( !isset($sSorting) || !$sSorting) {
            $sSelect = "select count(*) from $sTable where $sTable.oxobjectid = '".$soxId."' ";
            return $oDB->getOne( $sSelect);
        } else if ( count($aObjectId) > 0) {
            if ( $sSorting == "up") {
                $aList = $this->_getSortingList( $sTable, $soxId);
                $sFItmId = $aObjectId[0];
                foreach ( $aList as $iNum => $aItem) {
                    if ( $aItem[0] == $sFItmId && $iNum > 0) {
                        //echo "$iNum + ".sizeof($aObjectId)." - 1";
                        $sSelect = "update $sTable set $sTable.oxsort=".( $iNum + count($aObjectId) - 1 )." where $sTable.oxid='".$aList[$iNum-1][0]."'";
                        $oDB->execute( $sSelect);
                        foreach ( $aObjectId as $iSNum => $sItem) {
                            $sSelect = "update $sTable set $sTable.oxsort=".( $iNum + $iSNum - 1)." where $sTable.oxid='".$sItem."'";
                            $oDB->execute( $sSelect);
                        }
                        break;
                    }
                }
            } elseif ( $sSorting == "down") {
                $aList = $this->_getSortingList( $sTable, $soxId);
                $sFItmId = $aObjectId[count($aObjectId)-1];
                foreach ( $aList as $iNum => $aItem) {
                    if ( $aItem[0] == $sFItmId && $iNum < (count($aList)-1)) {
                        $sSelect = "update $sTable set $sTable.oxsort=".( $iNum - count($aObjectId) + 1 )." where $sTable.oxid='".$aList[$iNum+1][0]."'";
                        $oDB->execute( $sSelect);
                        foreach ( $aObjectId as $iSNum => $sItem) {
                            $sSelect = "update $sTable set $sTable.oxsort=".( $iNum - (count($aObjectId)-$iSNum) + 2 )." where $sTable.oxid='".$sItem."'";
                            $oDB->execute( $sSelect);
                        }
                        break;
                    }
                }
            }
            $this->updateSorting( $sTable, array($soxId));
            $this->_loadCategory();
        }
    }

    /**
     * Collects and returns array of object ID's for sorting, or false on error
     *
     * @param string $sTable object's data table
     * @param string $soxId
     *
     * @return mixed
     */
    protected function _getSortingList( $sTable, $soxId)
    {
        //$soxId    = oxConfig::getParameter( "oxid");
        //if ( !isset($soxId) && $soxId == "-1")
        //    return;
        $sSelect  = "select $sTable.oxid, $sTable.oxsort, $sTable.oxattrid , ";
        $sSelect .= "$sTable.oxobjectid from $sTable where $sTable.oxobjectid = '".$soxId."' order by $sTable.oxsort";
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

    /**
     * Performs sorting in DB update.
     *
     * @param string $sTable object's data table
     * @param array  @aIds
     *
     * @return null
     */
    function updateSorting( $sTable, $aIds)
    {
        $oDB = oxDb::getDb();
        foreach ( $aIds as $soxId) {
            //$soxId    = oxConfig::getParameter( "oxid");
            //if ( !isset($soxId) && $soxId == "-1")
            //    return;
            $aList = $this->_getSortingList( $sTable, $soxId);
            // updates sorting
            foreach ( $aList as $iNum => $aItem) {
                if ( $aItem[1] != $iNum) {
                    $sSelect = "update $sTable set $sTable.oxsort=$iNum where $sTable.oxid='".$aItem[0]."'";
                    $oDB->execute( $sSelect);
                }
            }
        }
    }

}
