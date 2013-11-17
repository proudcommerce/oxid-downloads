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
 * $Id: discount_main.php 17189 2009-03-13 12:19:59Z arvydas $
 */

/**
 * Admin article main discount manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Shop Settings -> Discounts -> Main.
 * @package admin
 */
class Discount_Main extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates article category tree, passes
     * data to Smarty engine and returns name of template file "discount_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        parent::render();

        $sOxId = oxConfig::getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");
        if ( ($sOxId == "-1" || !isset( $sOxId)) && isset( $sSavedID) ) {
            $sOxId = $sSavedID;
            //$myConfig->delParameter( "saved_oxid");
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $sOxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        $sITMDisp = "none";

        if ( $sOxId != "-1" && isset( $sOxId)) {
            // load object
            $oDiscount = oxNew( "oxdiscount" );
            $oDiscount->loadInLang( $this->_iEditLang, $sOxId );

            $oOtherLang = $oDiscount->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oDiscount->loadInLang( key($oOtherLang), $sOxId );
            }

            $this->_aViewData["edit"] =  $oDiscount;


            // remove already created languages
            $aLang = array_diff ( oxLang::getInstance()->getLanguageNames(), $oOtherLang );

            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }

            if ( $oDiscount->oxdiscount__oxaddsumtype->value == "itm")
                $sITMDisp = "";

            // ITM load articles from chosen categorie
            $sITMChosenArtCat = oxConfig::getParameter( "itmartcat");
            $this->_aViewData["itmarttree"] = $this->_loadArticleList( $oDiscount->oxdiscount__oxitmartid->value, $sITMChosenArtCat);
            // generating category tree for artikel choose select list
            $this->_getCategoryTree( "artcattree", $sITMChosenArtCat);
        }

        // ITM display ?
        $this->_aViewData["itm_disp"]  =  $sITMDisp;

        if ( oxConfig::getParameter("aoc") ) {

            $aColumns = array();
            include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/discount_main.tpl";
        }
        return "discount_main.tpl";
    }

    /**
     * Saves changed selected discount parameters.
     *
     * @return mixed
     */
    public function save()
    {

        $sOxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");

            // shopid
            $sShopID = oxSession::getVar( "actshop");
            $aParams['oxdiscount__oxshopid'] = $sShopID;
        $oAttr = oxNew( "oxdiscount" );
        if ( $sOxId != "-1")
            $oAttr->load( $sOxId );
        else
            $aParams['oxdiscount__oxid'] = null;

        // checkbox handling
        if ( !isset( $aParams['oxdiscount__oxactive']))
            $aParams['oxdiscount__oxactive'] = 0;


        //$aParams = $oAttr->ConvertNameArray2Idx( $aParams);
        $oAttr->setLanguage(0);
        $oAttr->assign( $aParams );
        $oAttr->setLanguage($this->_iEditLang);
        $oAttr = oxUtilsFile::getInstance()->processFiles( $oAttr );
        $oAttr->save();
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $sOxId == "-1")
            oxSession::setVar( "saved_oxid", $oAttr->oxdiscount__oxid->value );
    }

    /**
     * Saves changed selected discount parameters in different language.
     *
     * @return null
     */
    public function saveinnlang()
    {

        $sOxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");

            // shopid
            $sShopID = oxSession::getVar( "actshop");
            $aParams['oxdiscount__oxshopid'] = $sShopID;
        $oAttr = oxNew( "oxdiscount" );
        if ( $sOxId != "-1")
            $oAttr->load( $sOxId);
        else
            $aParams['oxdiscount__oxid'] = null;
        // checkbox handling
        if ( !isset( $aParams['oxdiscount__oxactive']))
            $aParams['oxdiscount__oxactive'] = 0;


        //$aParams = $oAttr->ConvertNameArray2Idx( $aParams);
        $oAttr->setLanguage(0);
        $oAttr->assign( $aParams);
        $oAttr->setLanguage($this->_iEditLang);
        $oAttr = oxUtilsFile::getInstance()->processFiles( $oAttr );
        $oAttr->save();
        $this->_aViewData["updatelist"] = "1";

        // set oxid if inserted
        if ( $sOxId == "-1")
            oxSession::setVar( "saved_oxid", $oAttr->oxdiscount__oxid->value );
    }

    /**
     * Loads articlelist from chosen categorie
     *
     * @param string $sItmartid        discount itm article id
     * @param string $sITMChosenArtCat chosen category id
     *
     * @return array $aList
     */
    protected function _loadArticleList( $sItmartid, $sITMChosenArtCat)
    {
        $sArticleTable = getViewName("oxarticles");
        $sO2CView = getViewName('oxobject2category');
        $sSuffix = oxLang::getInstance()->getLanguageTag();
        $sSelect = "select $sArticleTable.oxid, $sArticleTable.oxartnum, $sArticleTable.oxtitle$sSuffix from $sArticleTable ";
        if ( !isset( $sITMChosenArtCat) || !$sITMChosenArtCat || $sITMChosenArtCat == "oxrootid") {
            $sSelect .= "where $sArticleTable.oxid = '".$sItmartid."' ";
        } elseif ( $sITMChosenArtCat != "-1" && $sITMChosenArtCat != "oxrootid") {
            $oArticle = oxNew( 'oxarticle' );
            $sSelect .= "left join $sO2CView as oxobject2category on $sArticleTable.oxid=oxobject2category.oxobjectid where oxobject2category.oxcatnid = '$sITMChosenArtCat' and ".$oArticle->getSqlActiveSnippet()." order by oxobject2category.oxpos";
        } else {
            $sSelect .= "left join $sO2CView as oxobject2category on $sArticleTable.oxid=oxobject2category.oxobjectid where oxobject2category.oxcatnid is null AND $sArticleTable.oxparentid = '' ";
        }
        // We do NOT use Shop Framework here as we do have to much overhead
        // this list can be up to 1000 entries
        $oDB = oxDb::getDb();
        $aList = array();
        $oArt = new stdClass();
        $oArt->oxarticles__oxid     = new oxField("");
        $oArt->oxarticles__oxartnum = new oxField("");
        $oArt->oxarticles__oxtitle  = new oxField(" -- ");
        $aList[] = $oArt;
        $rs = $oDB->selectLimit( $sSelect, 1000, 0);
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $oArt = new stdClass(); // #663
                $oArt->oxarticles__oxid     = new oxField($rs->fields[0]);
                $oArt->oxarticles__oxnid    = new oxField($rs->fields[0]);
                $oArt->oxarticles__oxartnum = new oxField($rs->fields[1]);
                $oArt->oxarticles__oxtitle  = new oxField($rs->fields[2]);
                if ( $oArt->oxarticles__oxid == $sItmartid)
                    $oArt->selected = 1;
                else
                    $oArt->selected = 0;
                $aList[] = $oArt;
                $rs->moveNext();
            }
        }

        return $aList;
    }
}
