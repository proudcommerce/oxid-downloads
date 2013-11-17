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
 * $Id: oxcategorylist.php 22537 2009-09-22 13:00:18Z sarunas $
 */


/**
 * Category list manager.
 * Collects available categories, performs some SQL queries to create category
 * list structure.
 * @package core
 */
class oxCategoryList extends oxList
{
    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'oxcategory';

    /**
     * Performance option mapped to config option blDontShowEmptyCategories
     *
     * @var boolean
     */
    protected $_blHideEmpty = false;

    /**
     * Performance option used to force full tree loading
     *
     * @var boolean
     */
    protected $_blForceFull = false;

    /**
     * Performance option used to force loading to desired level (currently wokrs only [0,1,2]
     *
     * @var integer
     */
    protected $_iForceLevel = 1;

    /**
     * Active category id, used in path building, and performance optimization
     *
     * @var string
     */
    protected $_sActCat     = null;

    /**
     * Active category path array
     *
     * @var array
     */
    protected $_aPath = array();

    /**
     * Category update info array
     *
     * @var array
     */
    protected $_aUpdateInfo = array();

    /**
     * Class constructor, initiates parent constructor (parent::oxList()).
     *
     * @param string $sObjectsInListName optional parameter, the objects contained in the list, always oxcategory
     *
     * @return null
     */
    public function __construct( $sObjectsInListName = 'oxcategory')
    {
        $this->_blHideEmpty = $this->getConfig()->getConfigParam('blDontShowEmptyCategories');
        parent::__construct( $sObjectsInListName );
    }

    /**
     * return fields to select while loading category tree
     *
     * @param string $sTable   table name
     * @param array  $aColumns required column names (optional)
     * 
     * @return string return
     */
    protected function _getSqlSelectFieldsForTree($sTable, $aColumns = null)
    {
        if ($aColumns && count($aColumns)) {
            foreach ($aColumns as $key=>$val) {
                $aColumns[$key].=' as '.$val;
            }
            return "$sTable.".implode(", $sTable.", $aColumns);
        }

        $oBaseObject = $this->getBaseObject();
        $sLangSuffix = oxLang::getInstance()->getLanguageTag();

        $sFieldList = "$sTable.oxid as oxid, $sTable.oxactive$sLangSuffix as oxactive,"
                    ." $sTable.oxhidden as oxhidden, $sTable.oxparentid as oxparentid,"
                    ." $sTable.oxdefsort as oxdefsort, $sTable.oxdefsortmode as oxdefsortmode,"
                    ." $sTable.oxleft as oxleft, $sTable.oxright as oxright,"
                    ." $sTable.oxrootid as oxrootid, $sTable.oxsort as oxsort,"
                    ." $sTable.oxtitle$sLangSuffix as oxtitle, $sTable.oxdesc$sLangSuffix as oxdesc,"
                    ." $sTable.oxpricefrom as oxpricefrom, $sTable.oxpriceto as oxpriceto,"
                    ." $sTable.oxicon as oxicon ";

            $sFieldList.= ",not $sTable.".$oBaseObject->getSqlFieldName( 'oxactive' )." as remove";


        return $sFieldList;
    }

    /**
     * constructs the sql string to get the category list
     *
     * @param bool   $blReverse list loading order, true for tree, false for simple list (optional, default false)
     * @param array  $aColumns  required column names (optional)
     * @param string $sOrder    order by string (optional)
     *
     * @return string
     */
    protected function _getSelectString($blReverse = false, $aColumns = null, $sOrder = null)
    {
        $sViewName  = $this->getBaseObject()->getViewName();
        $sFieldList = $this->_getSqlSelectFieldsForTree($sViewName, $aColumns);

        //$sFieldList = $oBaseObject->getSelectFields();
        //excluding long desc
        if (!$this->isAdmin() && !$this->_blHideEmpty && !$this->_blForceFull) {
            $oCat = oxNew('oxcategory');
            if (!($this->_sActCat && $oCat->load($this->_sActCat) && $oCat->oxcategories__oxrootid->value)) {
                $oCat = null;
                $this->_sActCat = null;
            }
            $sUnion = $this->_getDepthSqlUnion($oCat, $aColumns);
            $sWhere = $this->_getDepthSqlSnippet($oCat);
        } else {
            $sUnion = '';
            $sWhere = '1';
        }

        if (!$sOrder) {
            $sOrdDir    = $blReverse?'desc':'asc';
            $sOrder     = "oxrootid $sOrdDir, oxleft $sOrdDir";
        }

        return "select $sFieldList from $sViewName where $sWhere $sUnion order by $sOrder";
    }

    /**
     * constructs the sql snippet responsible for depth optimizations,
     * loads only selected category's siblings and some ($this->_iForceLevel
     * dependant) top layers from root categories
     *
     * @param oxCategory $oCat selected category
     *
     * @return string
     */
    protected function _getDepthSqlSnippet($oCat)
    {
        $sViewName  = $this->getBaseObject()->getViewName();
        $sDepthSnippet = ' ( 0';

        // load compleate tree of active category, if it exists
        if ($oCat) {
            // select children here, siblings will be selected from union
            $sDepthSnippet .= " or ($sViewName.oxparentid = ".oxDb::getDb()->quote($oCat->oxcategories__oxid->value).")";
        }

        // load 1'st category level (roots)
        if ($this->_iForceLevel >= 1) {
            $sDepthSnippet .= " or $sViewName.oxparentid = 'oxrootid'";
        }

        // load 2'nd category level ()
        if ($this->_iForceLevel >= 2) {
            $sDepthSnippet .= " or $sViewName.oxrootid = $sViewName.oxparentid or $sViewName.oxid = $sViewName.oxrootid";
        }

        $sDepthSnippet .= ' ) ';
        return $sDepthSnippet;
    }

    /**
     * returns sql snippet for union of select category's and its upper level
     * siblings of the same root (siblings of the categotry, and parents and
     * grandparents etc)
     *
     * @param oxCategory $oCat     current category object
     * @param array      $aColumns required column names (optional)
     *
     * @return string
     */
    protected function _getDepthSqlUnion($oCat, $aColumns = null)
    {
        if (!$oCat) {
            return '';
        }
        return "UNION SELECT ".$this->_getSqlSelectFieldsForTree('maincats', $aColumns)
                ." FROM oxcategories AS subcats"
                ." LEFT JOIN oxcategories AS maincats on maincats.oxparentid = subcats.oxparentid"
                ." WHERE subcats.oxrootid = ".oxDb::getDb()->quote($oCat->oxcategories__oxrootid->value)
                ." AND subcats.oxleft <= ". (int)$oCat->oxcategories__oxleft->value
                ." AND subcats.oxright >= ".(int)$oCat->oxcategories__oxright->value;
    }


    /**
     * Fetches reversed raw categories and does all necesarry postprocessing for
     * removing invisible or forbidden categories, duilding oc navigation path,
     * adding content categories and building tree structure.
     *
     * @param string $sActCat                 Active category (default null)
     * @param bool   $blLoadFullTree          ($myConfig->getConfigParam( 'blLoadFullTree' ) )
     * @param bool   $blPerfLoadTreeForSearch ($myConfig->getConfigParam( 'bl_perfLoadTreeForSearch' ) )
     * @param bool   $blTopNaviLayout         ($myConfig->getConfigParam( 'blTopNaviLayout' ) )
     *
     * @return null
     */
    public function buildTree($sActCat, $blLoadFullTree, $blPerfLoadTreeForSearch, $blTopNaviLayout)
    {
        startProfile("buildTree");

        $this->_sActCat     = $sActCat;
        $this->_blForceFull = $blLoadFullTree || $blPerfLoadTreeForSearch;
        $this->_iForceLevel = $blTopNaviLayout?2:1;

        $sSelect = $this->_getSelectString(true);
        $this->selectString($sSelect);

        // PostProcessing
        if ( !$this->isAdmin() ) {
            // remove inactive categories
            $this->_ppRemoveInactiveCategories();

            // add active cat as full object
            $this->_ppLoadFullCategory($sActCat);

            // builds navigation path
            $this->_ppAddPathInfo();

            // add content categories
            $this->_ppAddContentCategories();

            // build tree structure
            $this->_ppBuildTree();
        }

        stopProfile("buildTree");
    }

    /**
     * set full category object in tree
     *
     * @param string $sId category id
     *
     * @return null
     */
    protected function _ppLoadFullCategory( $sId )
    {
        $oNewCat = oxNew('oxcategory');
        if ($oNewCat->load($sId)) {
            // replace aArray object with fully loaded category
            $this->_aArray[$sId] = $oNewCat;
        }
    }

    /**
     * Fetches raw categories and does postprocessing for adding depth information
     *
     * @param bool $blLoad usually used with config option bl_perfLoadCatTree
     *
     * @return null
     */
    public function buildList($blLoad)
    {

        if (!$blLoad) {
            return;
        }

        startProfile('buildCategoryList');

        $this->_blForceFull = true;
        $this->selectString($this->_getSelectString(false));

        // PostProcessing
        // add tree depth info
        $this->_ppAddDepthInformation();
        stopProfile('buildCategoryList');
    }

    /**
     * setter for shopID
     *
     * @param int $sShopID ShopID
     *
     * @return null
     */
    public function setShopID($sShopID)
    {
        $this->_sShopID = $sShopID;
    }

    /**
     * Getter for active category path
     *
     * @return array
     */
    public function getPath()
    {
        return $this->_aPath;
    }

    /**
     * Returns HTML formated active category path
     *
     * @return string
     */
    public function getHtmlPath()
    {
        $sHtmlCatTree = '';
        $sSep         = '';
        foreach ( $this->_aPath as $oCategory ) {
            $sHtmlCatTree .= " $sSep<a href='".$oCategory->getLink()."'>".$oCategory->oxcategories__oxtitle->value."</a>";
            $sSep = '/ ';
        }
        return $sHtmlCatTree;
    }

    /**
     * Getter for active category
     *
     * @return oxcategory
     */
    public function getClickCat()
    {
        if (count($this->_aPath)) {
            return end($this->_aPath);
        }
    }

    /**
     * Getter for active root category
     *
     * @return oxcategory
     */
    public function getClickRoot()
    {
        if (count($this->_aPath)) {
            return array(reset($this->_aPath));
        }
    }

    /**
     * Category list postprocessing routine, responsible for removal of inactive of forbidden categories, and subcategories.
     *
     * @return null
     */
    protected function _ppRemoveInactiveCategories()
    {
        // Colect all items whitch must be remove
        $aRemoveList = array();
        foreach ($this->_aArray as $oCat) {
            if ($oCat->oxcategories__remove->value) {
                if (isset($aRemoveList[$oCat->oxcategories__oxrootid->value])) {
                    $aRemoveRange = $aRemoveList[$oCat->oxcategories__oxrootid->value];
                } else {
                    $aRemoveRange = array();
                }
                $aRemoveList[$oCat->oxcategories__oxrootid->value] = array_merge(range($oCat->oxcategories__oxleft->value, $oCat->oxcategories__oxright->value), $aRemoveRange);
            }
            unset($oCat->oxcategories__remove);
        }

        // Remove colected items from list.
        foreach ($this->_aArray as $oCat) {
            if (isset($aRemoveList[$oCat->oxcategories__oxrootid->value]) && in_array($oCat->oxcategories__oxleft->value, $aRemoveList[$oCat->oxcategories__oxrootid->value])) {
                unset( $this->_aArray[$oCat->oxcategories__oxid->value] );
            }
        }
    }

    /**
     * Category list postprocessing routine, responsible for generation of active category path
     *
     * @return null
     */
    protected function _ppAddPathInfo()
    {

        if (is_null($this->_sActCat)) {
            return;
        }

        $aPath = array();
        $sCurrentCat  = $this->_sActCat;

        while ($sCurrentCat != 'oxrootid' && isset($this[$sCurrentCat])) {
            $oCat = $this[$sCurrentCat];
            $oCat->setExpanded(true);
            $aPath[$sCurrentCat] = $oCat;
            $sCurrentCat = $oCat->oxcategories__oxparentid->value;
        }

        $this->_aPath = array_reverse($aPath);
    }

    /**
     * Category list postprocessing routine, responsible adding of content categories
     *
     * @return null
     */
    protected function _ppAddContentCategories()
    {
        // load content pages for adding them into menue tree
        $oContentList = oxNew( "oxcontentlist" );
        $oContentList->loadCatMenues();

        foreach ($oContentList as $sCatId => $aContent) {
            if (array_key_exists($sCatId, $this->_aArray)) {
                $this[$sCatId]->setContentCats($aContent);

            }
        }
    }

    /**
     * Category list postprocessing routine, responsible building an sorting of hierarchical category tree
     *
     * @return null
     */
    protected function _ppBuildTree()
    {
        startProfile("_sortCats");
        $aIds = $this->sortCats();
        stopProfile("_sortCats");
        $aTree = array();
        foreach ($this->_aArray as $oCat) {
            $sParentId = $oCat->oxcategories__oxparentid->value;
            if ( $sParentId != 'oxrootid') {
                $this->_aArray[$sParentId]->setSortingIds( $aIds );
                $this->_aArray[$sParentId]->setSubCat($oCat, $oCat->getId(), true);
            } else {
                $aTree[$oCat->getId()] = $oCat;
            }
        }

        // run sorting only once on each parent
        $aParents = array();
        foreach ($this->_aArray as $oCat) {
            $aParents[$oCat->oxcategories__oxparentid->value] = true;
        }
        if ($aParents['oxrootid']) {
            unset($aParents['oxrootid']);
        }
        foreach (array_keys($aParents) as $sParent) {
            $this->_aArray[$sParent]->sortSubCats();
        }

        // Sort root categories
        $oCategory = oxNew('oxcategory');
        $oCategory->setSortingIds( $aIds );
        uasort($aTree, array( $oCategory, 'cmpCat' ) );

        $this->assign($aTree);
    }

    /**
     * Sorts category tree after oxsort and oxtitle fields and returns ids.
     *
     * @return array $aIds
     */
    public function sortCats()
    {
        $sViewName  = getViewName('oxcategories');
        $sSortSql = $this->_getSelectString(false, array('oxid', 'oxparentid', 'oxsort', 'oxtitle'), 'oxparentid, oxsort, oxtitle');
        $aIds = array();
        $oDB = oxDb::getDb();
        $rs = $oDB->execute($sSortSql);
        $cnt = 0;
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $aIds[$rs->fields[0]] = $cnt;
                $cnt++;
                $rs->moveNext();
            }
        }
        return $aIds;
    }

    /**
     * Category list postprocessing routine, responsible adding depth information.
     * Requires not reversed category list!
     *
     * @return null
     */
    protected function _ppAddDepthInformation()
    {

        $aStack = array();
        $iDepth = 0;
        $sPrevParent = '';

        foreach ($this->_aArray as $oCat) {

            $sParentId = $oCat->oxcategories__oxparentid->value;
            if ( $oCat->oxcategories__oxparentid->value == 'oxrootid' ) {
                $iDepth = 1;
                $aStack = array($sParentId => '0');
            }

            if ($sPrevParent != $sParentId && isset($aStack[$sParentId]) ) {
                $iDepth -= count($aStack)- $aStack[$sParentId];
                $aStack = array_slice($aStack, 0, $iDepth-1, true);
            }

            if ( !isset($aStack[$sParentId])) {
                $aStack[$sParentId] = $iDepth;
                $iDepth++;
            }

            $oCat->oxcategories__oxtitle->setValue(str_repeat('-', $iDepth-1).' '.$oCat->oxcategories__oxtitle->value);
            $sPrevParent = $sParentId;
        }

    }

    /**
     * Rebuilds nested sets information by updating oxleft and oxright category attributes, from oxparentid
     *
     * @param bool   $blVerbose Set to true for outputing the update status for user,
     * @param string $sShopID   the shop id
     *
     * @return null
     */
    public function updateCategoryTree($blVerbose = true, $sShopID = null)
    {
        $oDB = oxDb::getDb();
        $sWhere = '1';


        $oDB->execute("update oxcategories set oxleft = 0, oxright = 0 where $sWhere");
        $oDB->execute("update oxcategories set oxleft = 1, oxright = 2 where oxparentid = 'oxrootid' and $sWhere");

        // Get all root categories
        $rs = $oDB->execute("select oxid, oxtitle from oxcategories where oxparentid = 'oxrootid' and $sWhere order by oxsort");
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $this->_aUpdateInfo[] = "<b>Processing : ".$rs->fields[1]."</b>(".$rs->fields[0].")<br>";
                if ( $blVerbose ) {
                    echo next( $this->_aUpdateInfo );
                }
                $oxRootId = $rs->fields[0];

                $updn = $this->_updateNodes($oxRootId, true, $oxRootId);
                $rs->moveNext();
            }
        }
    }

    /**
     * Returns update log data array
     *
     * @return array
     */
    public function getUpdateInfo()
    {
        return $this->_aUpdateInfo;
    }

    /**
     * Recursivly updates root nodes, this method is used (only) in updateCategoryTree()
     *
     * @param string $oxRootId rootid of tree
     * @param bool   $isroot   is the current node root?
     * @param string $thisRoot the id of the root
     *
     * @return null
     */
    protected function _updateNodes($oxRootId, $isroot, $thisRoot)
    {
        $oDB = oxDb::getDb();

        if ($isroot) {
            $thisRoot = $oxRootId;
        }

        // Get sub categories of root categorie
        $rs = $oDB->execute("update oxcategories set oxrootid = ".$oDB->quote($thisRoot)." where oxparentid = ".$oDB->quote($oxRootId));
        $rs = $oDB->execute("select oxid, oxparentid from oxcategories where oxparentid = ".$oDB->quote($oxRootId)." order by oxsort");
        // If there are sub categories
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $parentId = $rs->fields[1];
                $actOxid = $rs->fields[0];
                $sActOxidQuoted = $oDB->quote($actOxid);

                // Get the data of the parent category to the current Cat
                $rs3 = $oDB->execute("select oxrootid, oxright from oxcategories where oxid = ".$oDB->quote($parentId));
                while (!$rs3->EOF) {
                    $parentOxRootId = $rs3->fields[0];
                    $parentRight    = (int)$rs3->fields[1];
                    $rs3->moveNext();
                }
                $sParentOxRootIdQuoted = $oDB->quote($parentOxRootId);
                $oDB->execute("update oxcategories set oxleft = oxleft + 2 where oxrootid = $sParentOxRootIdQuoted and oxleft > '$parentRight' and oxright >= '$parentRight' and oxid != $sActOxidQuoted");
                $oDB->execute("update oxcategories set oxright = oxright + 2 where oxrootid = $sParentOxRootIdQuoted and oxright >= '$parentRight' and oxid != $sActOxidQuoted");
                $oDB->execute("update oxcategories set oxleft = $parentRight, oxright = ($parentRight + 1) where oxid = $sActOxidQuoted");
                $this->_updateNodes($actOxid, false, $thisRoot);
                $rs->moveNext();
            }
        }
    }

    /**
     * Extra getter to guarantee compatibility with templates
     *
     * @param string $sName variable name
     *
     * @return unknown
     */
    public function __get($sName)
    {
        switch ($sName) {
            case 'aPath':
            case 'aFullPath':
                return $this->getPath();
                break;
        }
        return parent::__get($sName);
    }

}
