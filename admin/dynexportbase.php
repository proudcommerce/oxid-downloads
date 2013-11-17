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
 * $Id: dynexportbase.php 21075 2009-07-21 11:59:29Z arvydas $
 */

/**
 * Error constants
 */
DEFINE("ERR_SUCCESS", -2);
DEFINE("ERR_GENERAL", -1);
DEFINE("ERR_FILEIO", 1);


/**
 * DynExportBase framework class encapsulating a method for defining implementation class.
 * Performs export function according to user chosen categories.
 * @package admin
 * @subpackage dyn
 */
class DynExportBase extends oxAdminDetails
{
    public $sClass_do            = "";
    public $sClass_main          = "";

    //output paths and files
    public $sExportPath          = "export/";
    public $sExportFileType      = "txt";
    public $sExportFileName      = "dynexport";
    public $fpFile               = null;
    public $iExportPerTick       = 30;

    // protected
    protected $_sFilePath        = null;
    protected $_aExportResultset = array();

    protected $_sThisTemplate = "dynexportbase.tpl";


    /**
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();

        // set generic frame template
        $this->_sFilePath = $this->getConfig()->getConfigParam( 'sShopDir' ) . "/". $this->sExportPath . $this->sExportFileName . "." . $this->sExportFileType;

    }

    /**
     * Calls parent rendering methods, sends implementation class names to template
     * and returns default template name
     *
     * @return string
    */
    public function render()
    {
        parent::render();

        // assign all member variables to template
        $aClass_vars = get_object_vars( $this);
        while (list($name, $value) = each($aClass_vars)) {
            $this->_aViewData[$name] = $value;
        }

        $this->_aViewData['sOutputFile']     = $this->_sFilePath;
        $this->_aViewData['sDownloadFile']   = $this->getConfig()->getConfigParam( 'sShopURL' ) . $this->sExportPath . $this->sExportFileName . "." . $this->sExportFileType;

        return $this->_sThisTemplate;
    }

    /**
     * Prepares and fill all data which all the dyn exports needs
     *
     * @return null
    */
    public function createMainExportView()
    {
        $myConfig = $this->getConfig();
        // parent categorie tree
        $oCatTree = oxNew( "oxCategoryList" );
        $oCatTree->buildList($myConfig->getConfigParam( 'bl_perfLoadCatTree' ));
        $this->_aViewData["cattree"] =  $oCatTree;

        // Countries for calculating delivery costs
        //$oCountries = oxNew( "oxCountryList" );
        //$oCountries->select();

        /*$aCountries = array("Deutschland", "Österreich", "Schweiz", "Liechtenstein", "Italien",
                            "Luxemburg", "Frankreich", "Schweden", "Finnland", "Grossbritannien",
                            "Irland", "Holland", "Belgien", "Portugal", "Spanien", "Griechenland");

        foreach ($aCountries as $sCountry)
        {
            $oCountry->oxcountry__oxid->value = $sCountry;
            $oCountry->oxcountry__oxtitle->value = $sCountry;
            $oCountries[] = $oCountry;
        }

        $this->_aViewData["countrylist"] =  $oCountries;
        */
    }

    /**
     * Prepares Export
     *
     * @return null
     */
    public function start()
    {
        // delete file, if its already there
        $this->fpFile = @fopen( $this->_sFilePath, "w");
        if ( !isset( $this->fpFile) || !$this->fpFile) {
            // we do have an error !
            $this->stop( ERR_FILEIO);
        } else {
            $this->_aViewData['refresh'] = 0;
            $this->_aViewData['iStart']  = 0;
            fclose( $this->fpFile);

            // prepare it
            $iEnd = $this->prepareExport();
            oxSession::setVar( "iEnd", $iEnd);
            $this->_aViewData['iEnd'] = $iEnd;
        }
    }

    /**
     * Stops Export
     *
     * @param integer $iError
     *
     * @return null
     */
    public function stop( $iError = 0)
    {
        if ( $iError) {
            $this->_aViewData['iError'] = $iError;
        }

        // delete temporary heap table
        $sHeapTable = $this->_getHeapTableName();
        oxDb::getDb()->Execute( "drop TABLE if exists $sHeapTable ");
    }

    /**
     * virtual function must be overloaded
     *
     * @param integer $iCnt counter
     *
     * @return bool
     */
    public function nextTick( $iCnt)
    {
        return false;
    }

    /**
     * writes one line into open export file
     *
     * @param string $sLine exported line
     *
     * @return null
     */
    public function write( $sLine)
    {

        $sLine = $this->removeSID( $sLine);
        $sLine = str_replace( array("\r\n","\n"), "", $sLine);
        fwrite( $this->fpFile, $sLine."\r\n");
    }

    /**
     * Does Export
     *
     * @return null
     */
    public function run()
    {
        $blContinue = true;
        $iExportedItems = 0;

        $this->fpFile = @fopen( $this->_sFilePath, "a");
        if ( !isset( $this->fpFile) || !$this->fpFile) {
            // we do have an error !
            $this->stop( ERR_FILEIO);
        } else {
            // file is open
            $iStart = oxConfig::getParameter("iStart");
            // load from session
            $this->_aExportResultset = oxConfig::getParameter( "aExportResultset");

            for ( $i = $iStart; $i < $iStart + $this->iExportPerTick; $i++) {
                if ( ( $iExportedItems = $this->nextTick( $i ) ) === false ) {
                    // end reached
                    $this->stop( ERR_SUCCESS);
                    $blContinue = false;
                    break;
                }
            }
            if ( $blContinue) {
                // make ticker continue
                $this->_aViewData['refresh'] = 0;
                $this->_aViewData['iStart']  = $i;
                $this->_aViewData['iExpItems'] = $iExportedItems;
            }
            fclose( $this->fpFile);
        }
    }

    /**
     * Removes Session ID from $sInput
     *
     * @param string $sInput
     *
     * @return null
     */
    public function removeSID( $sInput)
    {

        $mySession = $this->getSession();

        // remove sid from link
        $sOutput = str_replace( "sid=".$mySession->getId()."/", "", $sInput);
        $sOutput = str_replace( "sid/".$mySession->getId()."/", "", $sOutput);
        $sOutput = str_replace( "sid=".$mySession->getId()."&amp;", "", $sOutput);
        $sOutput = str_replace( "sid=".$mySession->getId()."&", "", $sOutput);
        $sOutput = str_replace( "sid=".$mySession->getId(), "", $sOutput);

        return $sOutput;
    }

    /**
     * Shortens a string to $iMaxSize adding "..."
     *
     * @param string  $sInput
     * @param integer $iMaxSize
     * @param bool    $blRemoveNewline
     *
     * @return string
     */
    public function shrink( $sInput, $iMaxSize, $blRemoveNewline = true)
    {

        if ( $blRemoveNewline) {
            $sOutput = str_replace( "\r\n", " ", $sInput);
            $sOutput = str_replace( "\n", " ", $sOutput);
        } else {
            $sOutput = $sInput;
        }

        $sOutput = str_replace( "\t", "    ", $sOutput);
        // remove html entities, remove html tags
        $sOutput = $this->_unHTMLEntities( strip_tags( $sOutput));

        $oStr = getStr();
        if ( $oStr->strlen( $sOutput) > $iMaxSize - 3) {
            $sOutput = $oStr->substr( $sOutput, 0, $iMaxSize - 5) . "...";
        }
        return $sOutput;
    }

    /**
     * Loads all article parent categories and returns titles separated by "/"
     *
     * @param object &$oArticle  Article object
     * @param string $sSeparator separator (default "/")
     *
     * @return string
     */
    public function getCategoryString( & $oArticle, $sSeparator = "/")
    {
        $sLang = oxLang::getInstance()->getBaseLanguage();
        $oDB = oxDb::getDb();

        $sCatView = getViewName('oxcategories');
        $sO2CView = getViewName('oxobject2category');

        //selecting category
        $sQ =  "select oxobject2category.oxcatnid, $sCatView.oxleft, $sCatView.oxright, $sCatView.oxrootid from $sO2CView as oxobject2category left join $sCatView on $sCatView.oxid = oxobject2category.oxcatnid ";
        $sQ .= "where oxobject2category.oxobjectid='".$oArticle->getId()."' and $sCatView.oxactive".(($sLang)?"_$sLang":"")." = 1 order by oxobject2category.oxtime ";

        $aRet = array();
        $rs = $oDB->execute( $sQ);
        if ($rs != false && $rs->recordCount() > 0) {
            $sCatID = $rs->fields[0];
            $sLeft = $rs->fields[1];
            $sRight = $rs->fields[2];
            $sRootID = $rs->fields[3];

            //selecting all parent category titles
                $sQ  = "select oxtitle".(($sLang)?"_$sLang":"")." from oxcategories where ";
                $sQ .= "oxrootid = '$sRootID' and oxright >= $sRight and oxleft <= $sLeft order by oxleft ";

            $rs = $oDB->execute( $sQ);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aRet[] = $rs->fields[0];
                    $rs->moveNext();
                }
            }
        }
        $sRet = implode($sSeparator, $aRet);
        return $sRet;
    }

    /**
     * Loads article default category
     *
     * @param object $oArticle Article object
     *
     * @return record set
     */
    public function getDefaultCategoryString($oArticle)
    {
        $sLang = oxLang::getInstance()->getBaseLanguage();
        $oDB = oxDb::getDb();

        $sCatView = getViewName('oxcategories');
        $sO2CView = getViewName('oxobject2category');

        //selecting category
        $sQ =  "select $sCatView.oxtitle".(($sLang)?"_$sLang":"")." from $sO2CView as oxobject2category left join $sCatView on $sCatView.oxid = oxobject2category.oxcatnid ";
        $sQ .= "where oxobject2category.oxobjectid='".$oArticle->getId()."' and $sCatView.oxactive".(($sLang)?"_$sLang":"")." = 1 order by oxobject2category.oxtime ";

        $rs = $oDB->getOne( $sQ);

        return $rs;
    }

    /**
     * Converts field for CSV
     *
     * @param string $sInput
     *
     * @return string
     */
    public function prepareCSV( $sInput)
    {
        $sInput = oxUtilsString::getInstance()->prepareCSVField( $sInput);
        $sOutput = str_replace( "&nbsp;", " ", $sInput);
        $sOutput = str_replace( "&euro;", "€", $sOutput);
        $sOutput = str_replace( "|", "", $sOutput);

        return $sOutput;
    }

    /**
     * Changes special chars to be XML compatible
     *
     * @param string $sInput string which have to be changed
     *
     * @return string
     */
    public function prepareXML($sInput)
    {

        $sOutput = str_replace("&", "&amp;", $sInput);
        $sOutput = str_replace("\"", "&quot;", $sOutput);
        $sOutput = str_replace(">", "&gt;", $sOutput);
        $sOutput = str_replace("<", "&lt;", $sOutput);
        $sOutput = str_replace("'", "&apos;", $sOutput);
        return $sOutput;
    }

    /**
     * Searches for deepest path to a categorie this article is assigned to
     *
     * @param object &$oArticle article object
     *
     * @return string
     */
    public function getDeepestCategoryPath( & $oArticle)
    {
        $sRet = "";
        $this->_loadRootCats();
        $sRet = $this->_findDeepestCatPath($oArticle);

        return $sRet;
    }

    /**
     * create export resultset
     *
     * @return int
     */
    public function prepareExport()
    {
        $oDB = oxDb::getDb();

        $sHeapTable = $this->_getHeapTableName();

        // #1070 Saulius 2005.11.28
        // check mySQL version

        $rs = $oDB->execute("SHOW VARIABLES LIKE 'version'");
        $sMysqlVersion = $rs->fields[1];

        $sTableCharset = $this->_generateTableCharSet($sMysqlVersion);

        // create heap table
        $blRet = $this->_createHeapTable($sHeapTable, $sTableCharset);
        if ( $blRet == false) {
            // error
            die( "Could not create HEAP Table $sHeapTable\n<br>");
        }

        $aChosenCat = oxConfig::getParameter( "acat");
        $sCatAdd = $this->_getCatAdd($aChosenCat);

        if ( !$this->_insertArticles($sHeapTable, $sCatAdd) ) {
            die( "Could not insert Articles in Table $sHeapTable\n<br>");
        }

        /* commented due to upper changes
        if( isset( $blExportVars) && $blExportVars)
        {   // now add variants if there are some
            $oDB->Execute( "insert into ".$sHeapTable." select oxarticles.oxid from oxarticles, $sHeapTable where oxarticles.oxparentid = $sHeapTable.oxid AND ".oxDb::getInstance()->getActiveSnippet( "oxarticles"));
        }*/
        $this->_removeParentArticles($sHeapTable);
        $this->_setSessionParams();

        // get total cnt
        $iCnt = $oDB->getOne("select count(*) from $sHeapTable");

        return $iCnt;
    }

     /**
     * get's one oxid for exporting
     *
     * @param integer $iCnt        counter
     * @param bool    &$blContinue false is used to stop exporting
     *
     * @return mixed
     */
    public function getOneArticle( $iCnt, & $blContinue)
    {
        $myConfig  = $this->getConfig();
        //[Alfonsas 2006-05-31] setting specific parameter
        //to be checked in oxarticle.php init() method
        $myConfig->setConfigParam( 'blExport', true );

        $oArticle   = null;
        $blContinue = true;

        $sHeapTable = $this->_getHeapTableName();

        $oArticle = $this->_initArticle($sHeapTable, $iCnt);
        if (!isset($oArticle)) {
            $blContinue = false;
            return null;
        }

        $oArticle = $this->_setCampaignDetailLink($oArticle);

        //[Alfonsas 2006-05-31] unsetting specific parameter
        //to be checked in oxarticle.php init() method
        $myConfig->setConfigParam( 'blExport', false );

        return $oArticle;
    }

    /**
     * Make sure that string is never empty.
     *
     * @param string $sInput   string that will be replaced
     * @param string $sReplace string that will replace
     *
     * @return string
     */
    public function assureContent( $sInput, $sReplace = null)
    {
        if ( !strlen( $sInput)) {
            if ( !isset( $sReplace) || !strlen($sReplace)) {
                $sReplace = "-";
            }
            $sInput = $sReplace;
        }
        return $sInput;
    }

    /**
     * Replace HTML Entities
     * Replacement for html_entity_decode which is only available from PHP 4.3.0 onj
     *
     * @param string $sInput string to replace
     *
     * @return string
     */
    protected function _unHTMLEntities( $sInput)
    {

        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        return strtr( $sInput, $trans_tbl);
    }

    /**
     * Create valid Heap table name
     *
     * @return string
     */
    protected function _getHeapTableName()
    {
        $mySession = $this->getSession();

        // table name must not start with any digit
        $sHeapname = "tmp_".str_replace( "0", "", md5($mySession->getId()));

        return $sHeapname;
    }

    /**
     * generates table charset
     *
     * @param string $sMysqlVersion MySql version
     *
     * @return string
     */
    private function _generateTableCharSet($sMysqlVersion)
    {
        $oDB = oxDb::getDb(true);

        //if MySQL >= 4.1.0 set charsets and collations
        if (version_compare($sMysqlVersion, '4.1.0', '>=')>0) {
            $sMysqlCharacterSet = null;
            $sMysqlCollation = null;
            $rs = $oDB->execute( "SHOW FULL COLUMNS FROM `oxarticles` WHERE field like 'OXID'" );
            if ( isset( $rs->fields['Collation'] ) && ( $sMysqlCollation = $rs->fields['Collation'] ) ) {
                $rs = $oDB->execute( "SHOW COLLATION LIKE '{$sMysqlCollation}'" );
                if ( isset( $rs->fields['Charset'] ) ) {
                    $sMysqlCharacterSet = $rs->fields['Charset'];
                }
            }

            if ( $sMysqlCollation && $sMysqlCharacterSet ) {
                $sTableCharset = "DEFAULT CHARACTER SET ".$sMysqlCharacterSet." COLLATE ".$sMysqlCollation;
            } else {
                $sTableCharset = "";
            }
        } else {
            $sTableCharset = "";
        }
        return $sTableCharset;
    }

    /**
     * creates heaptable
     *
     * @param string $sHeapTable    table name
     * @param string $sTableCharset table charset
     *
     * @return bool
     */
    private function _createHeapTable($sHeapTable, $sTableCharset)
    {

        $oDB = oxDb::getDb();

        $sSQL = "CREATE TABLE if not exists $sHeapTable ( oxid char(32) NOT NULL default '' ) TYPE=HEAP ".$sTableCharset;

        $rs = $oDB->execute( $sSQL);
        if ( $rs == false) {
            // error
            return false;
        }
        $oDB->execute( "truncate table $sHeapTable ");
        return true;
    }

    /**
     * creates additional cat string
     *
     * @param array $aChosenCat
     *
     * @return string
     */
    private function _getCatAdd($aChosenCat)
    {

        $sCatAdd        = null;
        if ( isset( $aChosenCat)) {
            $sCatAdd = " and ( ";
            $blSep = false;
            foreach ( $aChosenCat as $sCat) {
                if ( $blSep) {
                    $sCatAdd .= " or ";
                }
                $sCatAdd .= "oxobject2category.oxcatnid = '$sCat'";
                $blSep = true;
            }
            $sCatAdd .= ")";
        }
        return $sCatAdd;
    }

    /**
     * inserts articles into heaptable
     *
     * @param string $sHeapTable
     * @param string $sCatAdd
     *
     * @return bool
     */
    private function _insertArticles($sHeapTable, $sCatAdd)
    {
        $oDB = oxDb::getDb();
        $sSearchString = oxConfig::getParameter( "search");
        $blExportVars  = oxConfig::getParameter( "blExportVars");

        $language = oxLang::getInstance()->getLanguageTag( 0);
        $sShopID = $this->getConfig()->getShopID();

        $sO2CView = getViewName('oxobject2category');
        $oArticle = oxNew( 'oxarticle' );
        $sArticleTable = $oArticle->getViewName();

        $sSelect  = "insert into $sHeapTable select $sArticleTable.oxid from $sArticleTable, $sO2CView as oxobject2category where ";
        $sSelect .= $oArticle->getSqlActiveSnippet();

        if ( !$blExportVars) {
            $sSelect .= " and $sArticleTable.oxid = oxobject2category.oxobjectid and $sArticleTable.oxparentid='' ";
        } else {
            $sSelect .= " and ( $sArticleTable.oxid = oxobject2category.oxobjectid or $sArticleTable.oxparentid = oxobject2category.oxobjectid ) ";
        }

        // remove type 3
        //if( !$blExportStock3)
        //  $sSelect = str_replace( "or oxarticles.oxstockflag = 3", "", $sSelect);

        if ( isset( $sSearchString) && strlen( $sSearchString)) {
            $sSelect .= "and ( $sArticleTable.OXTITLE".$language." like '%$sSearchString%' ";
            $sSelect .= "or $sArticleTable.OXSHORTDESC".$language."  like '%$sSearchString%' ";
            $sSelect .= "or $sArticleTable.oxsearchkeys  like '%$sSearchString%') ";
        }
        if ( $sCatAdd) {
            $sSelect .= $sCatAdd;
        }
            if( !$sCatAdd)
                $sSelect .= " and oxarticles.oxshopid = '$sShopID' ";

        // add minimum stock value
        $dMinStock = oxConfig::getParameter( "sExportMinStock");
        if ( isset( $dMinStock) && $dMinStock && $this->getConfig()->getConfigParam( 'blUseStock' ) ) {
            $dMinStock = str_replace( array( ";", " ", "/", "'"), "", $dMinStock);
            $sSelect .= " and $sArticleTable.oxstock >= $dMinStock";
        }
        $sSelect .= " group by $sArticleTable.oxid";

        if ( $oDB->execute( $sSelect) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * removes parent articles so that we only have variants itself
     *
     * @param string $sHeapTable table name
     *
     * @return null
     */
    private function _removeParentArticles($sHeapTable)
    {
        $oDB = oxDb::getDb();
        $blExportParentVars = oxConfig::getParameter( "blExportMainVars");
        $sArticleTable = getViewName('oxarticles');

        if ( !isset( $blExportParentVars) || !$blExportParentVars) {
            // we need to remove again parent articles so that we only have the variants itself
            $rs = $oDB->execute( "select $sHeapTable.oxid from $sHeapTable, $sArticleTable where $sHeapTable.oxid = $sArticleTable.oxparentid group by $sHeapTable.oxid");
            $sDel = "delete from $sHeapTable where oxid in ( ";
            $blSep = false;
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if ( $blSep) {
                        $sDel .= ",";
                    }
                    $sDel .= "'".$rs->fields[0]."'";
                    $blSep = true;
                    $rs->moveNext();
                }
            }
            $sDel .= " )";
            $oDB->execute( $sDel);

        }
    }

    /**
     * stores some info in session
     *
     * @return null
     *
     */
    private function _setSessionParams()
    {
        // reset it from session
        oxSession::deleteVar("sExportDelCost");
        $dDelCost = oxConfig::getParameter( "sExportDelCost");
        if ( isset( $dDelCost)) {
            $dDelCost = str_replace( array( ";", " ", "/", "'"), "", $dDelCost);
            $dDelCost = str_replace( ",", ".", $dDelCost);
            oxSession::setVar( "sExportDelCost", $dDelCost);
        }

        oxSession::deleteVar("sExportMinPrice");
        $dMinPrice = oxConfig::getParameter( "sExportMinPrice");
        if ( isset( $dMinPrice)) {
            $dMinPrice = str_replace( array( ";", " ", "/", "'"), "", $dMinPrice);
            $dMinPrice = str_replace( ",", ".", $dMinPrice);
            oxSession::setVar( "sExportMinPrice", $dMinPrice);
        }

        // #827
        oxSession::deleteVar("sExportCampaign");
        $sCampaign = oxConfig::getParameter( "sExportCampaign");
        if ( isset( $sCampaign)) {
            $sCampaign = str_replace( array( ";", " ", "/", "'"), "", $sCampaign);
            oxSession::setVar( "sExportCampaign", $sCampaign);
        }
        // reset it from session
        oxSession::deleteVar("blAppendCatToCampaign");
        // now retrieve it from get or post.
        $blAppendCatToCampaign = oxConfig::getParameter( "blAppendCatToCampaign");
        if ( isset( $blAppendCatToCampaign) && $blAppendCatToCampaign) {
            oxSession::setVar( "blAppendCatToCampaign", $blAppendCatToCampaign);
        }
    }

    /**
     * Load all root cat's == all trees
     *
     * @return null
     */
    private function _loadRootCats()
    {
        $myConfig = $this->getConfig();
        if ( !isset( $myConfig->aCatLvlCache) || !count( $myConfig->aCatLvlCache)) {
            $myConfig->aCatLvlCache = array();

            $sLang = oxLang::getInstance()->getBaseLanguage();
            $sCatView = getViewName('oxcategories');
            $oDb = oxDb::getDb();

            // Load all root cat's == all trees
            $sSQL = "select oxid from $sCatView where oxparentid = 'oxrootid'";
            $rs = $oDb->Execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    // now load each tree
                    $sSQL = "SELECT s.oxid, s.oxtitle".(($sLang)?"_$sLang":"").", s.oxparentid, count( * ) AS LEVEL FROM oxcategories v, oxcategories s WHERE s.oxrootid = '".$rs->fields[0]."' and v.oxrootid='".$rs->fields[0]."' and s.oxleft BETWEEN v.oxleft AND v.oxright  AND s.oxhidden = '0' GROUP BY s.oxleft order by level";
                    $rs2 = $oDb->Execute( $sSQL);
                    if ($rs2 != false && $rs2->recordCount() > 0) {
                        while (!$rs2->EOF) {
                            // store it
                            $oCat = new stdClass();
                            $oCat->_sOXID        = $rs2->fields[0];
                            $oCat->oxtitle      = $rs2->fields[1];
                            $oCat->oxparentid   = $rs2->fields[2];
                            $oCat->ilevel       = $rs2->fields[3];
                            $myConfig->aCatLvlCache[$oCat->_sOXID] = $oCat;

                            $rs2->moveNext();
                        }
                    }
                    $rs->moveNext();
                }
            }
        }
    }

    /**
     * finds deepest category path
     *
     * @param object $oArticle article object
     *
     * @return string
     */
    private function _findDeepestCatPath($oArticle)
    {
        $myConfig = $this->getConfig();
        $sRet = "";

        // find deepest
        $aIDs   = $oArticle->getCategoryIds();
        if ( isset( $aIDs) && count( $aIDs)) {
            $SIDMAX     = null;
            $dMaxLvl    = 0;
            foreach ( $aIDs as $key => $sCatID) {
                if ( $dMaxLvl < $myConfig->aCatLvlCache[$sCatID]->ilevel) {
                    $dMaxLvl = $myConfig->aCatLvlCache[$sCatID]->ilevel;
                    $SIDMAX = $sCatID;
                }
            }
            // generate path
            $sRet = $myConfig->aCatLvlCache[$SIDMAX]->oxtitle;
            // endless
            for ( ;;) {
                $SIDMAX = @$myConfig->aCatLvlCache[$SIDMAX]->oxparentid;
                if ( !isset( $SIDMAX) || $SIDMAX == "oxrootid") {
                    break;
                }
                $sRet = $myConfig->aCatLvlCache[$SIDMAX]->oxtitle."/".$sRet;
            }
        }
        return $sRet;
    }

    /**
     * initialize article
     *
     * @param string $sHeapTable
     * @param int    $iCnt
     *
     * @return object
     */
    private function _initArticle($sHeapTable, $iCnt)
    {
        $myConfig = $this->getConfig();
        $oDB = oxDb::getDb();

        $oArticle   = null;

        $rs = $oDB->selectLimit( "select oxid from $sHeapTable", 1, $iCnt);
        if ($rs != false && $rs->recordCount() > 0) {
            $sOXID = $rs->fields[0];

            //$oArticle = oxNewArticle( $sOXID, array('blLoadParentData' => true));
            //$oArticle->blLoadParentData = true;
            //2007-02-10T
            $oArticle = oxNew( 'oxarticle' );
            $oArticle->setLoadParentData(true);
            $oArticle->Load( $sOXID);

            // check price
            $dMinPrice = oxConfig::getParameter( "sExportMinPrice");
            if ( isset( $dMinPrice) && ($oArticle->brutPrice < $dMinPrice)) {
                return null;
            }
        } else {
            return null;
        }

         //Saulius: variant title added
        $sTitle = $oArticle->oxarticles__oxvarselect->value?" ".$oArticle->oxarticles__oxvarselect->value:"";
        $oArticle->oxarticles__oxtitle->setValue($oArticle->oxarticles__oxtitle->value.$sTitle);


        // check for variant url - exporting direct variant links
        if ($oArticle->oxarticles__oxparentid->value) {
            $oArticle->oxdetaillink = str_replace($oArticle->oxarticles__oxparentid->value, $sOXID, $oArticle->oxdetaillink);
        }
        return $oArticle;
    }

    /**
     * sets detail link for campaigns
     *
     * @param object $oArticle article object
     *
     * @return object
     */
    private function _setCampaignDetailLink($oArticle)
    {
        // #827
        $sCampaign = oxConfig::getParameter( "sExportCampaign" );
        if ( $sCampaign ) {
            // modify detaillink
            //#1166R - pangora - campaign
            $oArticle->appendLink( "campaign=$sCampaign" );

            if (oxConfig::getParameter( "blAppendCatToCampaign")) {
                if ( $sCat = $this->getCategoryString($oArticle) ) {
                    $oArticle->appendLink( "/$sCat" );
                }
            }
        }
        return $oArticle;
    }

    /**
     *
     */
    public function getViewId()
    {
        return 'dyn_interface';
    }
}
