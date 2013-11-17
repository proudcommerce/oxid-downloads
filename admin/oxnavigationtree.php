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
 * $Id: oxnavigationtree.php 20610 2009-07-02 12:25:48Z sarunas $
 */

/**
 * Navigation tree control class
 * @package admin
 */
class OxNavigationTree extends oxSuperCfg
{
    /**
     * stores DOM object for all navigation tree
     */
    protected $_oDom = null;
    
    /**
     * keeps unmodified dom
     */
    protected $_oInitialDom = null;

    /**
     * Dynamix XML path
     *
     * @var string
     */
    protected $_sDynIncludeUrl = null;

    /**
     * Trys to load data form session, otherwise loads xml form menu.xml, user.xml
     * and trys to get dynscreen.xml
     *
     * @return null
     */
    public function init()
    {
        // initiating menu tree
        if ( ( $oDom = $this->getDomXml() ) ) {

            // removes items denied by user group
            $this->_checkGroups( $oDom );

            // removes items denied by user rights
            $this->_checkRights( $oDom );

            // check config params
            $this->_checkDemoShopDenials( $oDom );


            $this->_cleanEmptyParents($oDom, '//SUBMENU[@id][@list]', 'TAB');
            $this->_cleanEmptyParents($oDom, '//MAINMENU[@id]', 'SUBMENU');
        }
    }

    /**
     * clean empty nodes from tree
     *
     * @param object $oDom         dom object
     * @param string $sParentXPath parent xpath
     * @param string $sChildXPath  child xpath from parent
     *
     * @return null
     */
    protected function _cleanEmptyParents($oDom, $sParentXPath, $sChildXPath)
    {
        $oXPath = new DomXPath( $oDom );
        $oNodeList = $oXPath->query( $sParentXPath );

        foreach ( $oNodeList as $oNode ) {
            $sId = $oNode->getAttribute( 'id' );
            $oChildList = $oXPath->query( "{$sParentXPath}[@id='$sId']/$sChildXPath" );
            if (!$oChildList->length) {
                $oNode->parentNode->removeChild( $oNode );
            }
        }
    }

    /**
     * Adds links to xml nodes to resolve paths
     *
     * @param DomDocument $oDom where to add links
     *
     * @return null
     */
    protected function _addLinks( $oDom )
    {
        $sURL   = $this->_getAdminUrl();
        $oXPath = new DomXPath( $oDom );

        // building
        $oNodeList = $oXPath->query( "//SUBMENU[@cl]" );
        foreach ( $oNodeList as $oNode ) {
            // fetching class
            $sCl = $oNode->getAttribute( 'cl' );
            $sCl = $sCl?"cl=$sCl":'';

            // fetching params
            $sParam = $oNode->getAttribute( 'clparam' );
            $sParam = $sParam?"&$sParam":'';

            // setting link
            $oNode->setAttribute( 'link', "{$sURL}{$sCl}{$sParam}" );
        }
    }

    /**
     * Loads data form XML file, and merges it with main oDomXML.
     *
     * @param string      $sMenuFile which file to load
     * @param DomDocument $oDom      where to load
     *
     * @return null
     */
    protected function _loadFromFile( $sMenuFile, $oDom )
    {
        $oDomFile = new DomDocument();
        $oDomFile->preserveWhiteSpace = false;
        if ( @$oDomFile->load( $sMenuFile ) ) {
            $this->_merge( $oDomFile, $oDom );
        }
    }

    /**
     * Adds to element DynTabs
     *
     * @param object $oDom dom element to add links
     *
     * @return null
     */
    protected function _addDynLinks( $oDom )
    {
        $myConfig = $this->getConfig();
        $myUtilsFile = oxUtilsFile::getInstance();

        //$iLanguage = (int) $myConfig->getConfigParam( 'iAdminLanguage' );
        $iLanguage = oxLang::getInstance()->getTplLanguage();
        $sURL = $this->_getAdminUrl();

        $oXPath = new DomXPath( $oDom );
        $oNodeList = $oXPath->query( "//OXMENU[@type='dyn']/MAINMENU/SUBMENU" );
        foreach ( $oNodeList as $oNode ) {

            // fetching class
            $sCl = $oNode->getAttribute( 'cl' );
            $sCl = "cl=dynscreen&amp;menu=$sCl";

            // fetching params
            $sParam = $oNode->getAttribute( 'clparam' );
            $sParam = $sParam?"&$sParam":'';

            // setting list node if its is not set yet
            if ( !$oNode->getAttribute( 'list' ) ) {
                $oNode->setAttribute( 'list', 'dynscreen_list' );
                $oNode->setAttribute( 'listparam', 'menu='.$oNode->getAttribute( 'cl' ) );
            }

            // setting link
            $oNode->setAttribute( 'link', "{$sURL}{$sCl}{$sParam}" );

            // setting id
            $oNode->parentNode->setAttribute( 'id', 'dyn_menu' );

            // setting id to its parent

            // fetching class
            $sFile = $oNode->getAttribute( 'cl' );

            // always display the "about" tab no matter what licence
            if ( $myUtilsFile->checkFile( "{$this->_sDynIncludeUrl}pages/{$sFile}_about.php" ) ) {
                $oTabElem = new DOMElement( 'TAB' );
                $oNode->appendChild( $oTabElem );
                $oTabElem->setAttribute( 'external', 'true' );
                $oTabElem->setAttribute( 'location', "{$this->_sDynIncludeUrl}pages/{$sFile}_about.php" );
                $oTabElem->setAttribute( 'id', 'dyn_about' );
            }

            // checking for technics page
            if ( $myUtilsFile->checkFile( "{$this->_sDynIncludeUrl}/pages/{$sFile}_technics.php" ) ) {
                $oTabElem = new DOMElement( 'TAB' );
                $oNode->appendChild( $oTabElem );
                $oTabElem->setAttribute( 'external', 'true' );
                $oTabElem->setAttribute( 'location', "{$this->_sDynIncludeUrl}/pages/{$sFile}_technics.php" );
                $oTabElem->setAttribute( 'id', 'dyn_interface' );
            }

            // checking for setup page
            if ( file_exists( $myConfig->getConfigParam( 'sShopDir' )."/".$myConfig->getConfigParam( 'sAdminDir' )."/{$sFile}.php" ) ) {
                $oTabElem = new DOMElement( 'TAB' );
                $oNode->appendChild( $oTabElem );
                $oTabElem->setAttribute( 'id', 'dyn_interface' );
                $oTabElem->setAttribute( 'cl', $sFile );
            }
        }
    }

    /**
     * Removes form tree elements whitch doesn't have requred user rights
     *
     * @param object $oDom DOMDocument
     *
     * @return null
     */
    protected function _checkRights( $oDom )
    {
        $oXPath    = new DomXPath( $oDom );
        $oNodeList = $oXPath->query( '//*[@rights or @norights]' );

        foreach ( $oNodeList as $oNode ) {
            // only allowed modules/user rights or so
            if ( ( $sReq = $oNode->getAttribute( 'rights' ) ) ) {
                $aPerm = explode( ',', $sReq );
                foreach ( $aPerm as $sPerm ) {
                    if ( $sPerm && !$this->_hasRights( $sPerm ) ) {
                        $oNode->parentNode->removeChild( $oNode );
                    }
                }
                // not allowed modules/user rights or so
            } elseif ( ( $sNoReq = $oNode->getAttribute( 'norights' ) ) ) {
                $aPerm = explode( ',', $sNoReq );
                foreach ( $aPerm as $sPerm ) {
                    if ( $sPerm && $this->_hasRights( $sPerm ) ) {
                        $oNode->parentNode->removeChild( $oNode );
                    }
                }
            }
        }
    }

    /**
     * Removes form tree elements whitch doesn't have requred groups
     *
     * @param DOMDocument $oDom document to check group
     *
     * @return null
     */
    protected function _checkGroups( $oDom )
    {
        $oXPath    = new DomXPath( $oDom );
        $oNodeList = $oXPath->query( "//*[@nogroup or @group]" );

        foreach ( $oNodeList as $oNode ) {
            // allowed only for groups
            if ( ( $sReq = $oNode->getAttribute('group') ) ) {
                $aPerm = explode( ',', $sReq );
                foreach ( $aPerm as $sPerm ) {
                    if ( $sPerm && !$this->_hasGroup( $sPerm ) ) {
                        $oNode->parentNode->removeChild($oNode);
                    }
                }
                // not allowed for groups
            } elseif ( ( $sNoReq = $oNode->getAttribute('nogroup') ) ) {
                $aPerm = explode( ',', $sNoReq );
                foreach ( $aPerm as $sPerm ) {
                    if ( $sPerm && $this->_hasGroup( $sPerm ) ) {
                        $oNode->parentNode->removeChild($oNode);
                    }
                }
            }
        }
    }

    /**
     * Removes form tree elements if this is demo shop and elements have disableForDemoShop="1"
     *
     * @param DOMDocument $oDom document to check group
     *
     * @return null
     */
    protected function _checkDemoShopDenials( $oDom )
    {
        if (!$this->getConfig()->isDemoShop()) {
            // nothing to check for non demo shop
            return;
        }

        $oXPath    = new DomXPath( $oDom );
        $oNodeList = $oXPath->query( "//*[@disableForDemoShop]" );
        foreach ( $oNodeList as $oNode ) {
            if ( $oNode->getAttribute('disableForDemoShop') ) {
                $oNode->parentNode->removeChild($oNode);
            }
        }
    }

    /**
     * Copys attributes form one element to another
     *
     * @param object $oDomElemTo   DOMElement
     * @param object $oDomElemFrom DOMElement
     *
     * @return null
     */
    protected function _copyAttributes( $oDomElemTo, $oDomElemFrom )
    {
        foreach ( $oDomElemFrom->attributes as $oAttr ) {
            $oDomElemTo->setAttribute( $oAttr->nodeName, $oAttr->nodeValue );
        }
    }

    /**
     * Merges nodes of newly added menu xml file
     *
     * @param object $oDomElemTo   merge target
     * @param object $oDomElemFrom merge source
     * @param object $oXPathTo     node path
     * @param object $oDomDocTo    node to append child
     * @param string $sQueryStart  node query
     *
     * @return null
     */
    protected function _mergeNodes( $oDomElemTo, $oDomElemFrom, $oXPathTo, $oDomDocTo, $sQueryStart )
    {
        foreach ( $oDomElemFrom->childNodes as $oFromNode ) {
            if ( $oFromNode->nodeType != XML_ELEMENT_NODE ) {
                continue;
            }

            $sFromAttrName = $oFromNode->getAttribute( 'id' );
            $sFromNodeName = $oFromNode->tagName;

            // find current item
            $sQuery   = "{$sQueryStart}/{$sFromNodeName}[@id='{$sFromAttrName}']";
            $oCurNode = $oXPathTo->query( $sQuery );

            // if not found - append
            if ( $oCurNode->length == 0 ) {
                $oDomElemTo->appendChild( $oDomDocTo->importNode( $oFromNode, true ) );
                continue;
            }

            $oCurNode = $oCurNode->item( 0 );

            // if found copy all attributes and check childnodes
            $this->_copyAttributes( $oCurNode, $oFromNode );

            if ( $oFromNode->childNodes->length ) {
                $this->_mergeNodes( $oCurNode, $oFromNode, $oXPathTo, $oDomDocTo, $sQuery );
            }
        }
    }

    /**
     * If oDomXML exist meges nodes
     *
     * @param DomDocument $oDomNew   what to merge
     * @param DomDocument $oDom      where to merge
     *
     * @return null
     */
    protected function _merge( $oDomNew, $oDom )
    {
        $oXPath = new DOMXPath( $oDom );
        $this->_mergeNodes( $oDom->documentElement, $oDomNew->documentElement, $oXPath, $oDom, '/OX' );
    }

    /**
     * Returns from oDomXML tree tabs DOMNodeList, which belongs to $sClass
     *
     * @param string $sId         class name
     * @param int    $iAct        current tab number
     * @param bool   $blSetActive marks tab as active
     *
     * @return DOMNodeList
     */
    public function getTabs( $sId, $iAct, $blSetActive = true )
    {
        $oXPath = new DOMXPath( $this->_oDom );
        $oNodeList = $oXPath->query( "//SUBMENU[@cl='$sId' or @list='$sId']/TAB | //SUBMENU/../TAB[@cl='$sId']" );

        $iAct = ( $iAct > $oNodeList->length )?( $oNodeList->length - 1 ):$iAct;

        if ( $blSetActive ) {
            foreach ( $oNodeList as $iPos => $oNode ) {
                if ( $iPos == $iAct ) {
                    // marking active node
                    $oNode->setAttribute( 'active', 1 );
                }
            }
        }

        return $oNodeList;
    }

    /**
     * Returns active TAB class name
     *
     * @param string $sId  class name
     * @param int    $iAct active tab number
     *
     * @return string
     */
    public function getActiveTab( $sId, $iAct )
    {
        $oNodeList = $this->getTabs( $sId, $iAct, false );

        $iAct = ( $iAct > $oNodeList->length )?( $oNodeList->length - 1 ):$iAct;

        if ( $oNodeList->length && ( $oNode = $oNodeList->item( $iAct ) ) ) {
            return $oNode->getAttribute( 'cl' );
        }
    }

    /**
     * returns from oDomXML tree buttons stdClass, which belongs to $sClass
     *
     * @param string $sClass class name
     *
     * @return mixed
     */
    public function getBtn( $sClass )
    {
        $oXPath = new DOMXPath($this->_oDom);
        $oNodeList = $oXPath->query("//TAB[@cl='$sClass']/../BTN");
        if ($oNodeList->length) {
            $oButtons = new stdClass();
            foreach ($oNodeList as $oNode) {
                $sBtnID = $oNode->getAttribute('id');
                $oButtons->$sBtnID = 1;
            }
            return $oButtons;
        }
        return null;
    }

    /**
     * Returns array witn pathes + names ox manu xml files. Paths are checked
     *
     * @return array
     */
    protected function _getMenuFiles()
    {
        $myConfig  = $this->getConfig();
        $myOxUtlis = oxUtils::getInstance();

        $sFullAdminDir = getShopBasePath() . $myConfig->getConfigParam( 'sAdminDir' );
        $sMenuFile = "/menu.xml";

        $sTmpDir = $myConfig->getConfigParam( 'sCompileDir' );
        $sDynLang = $this->_getDynMenuLang();
        $sLocalDynPath = "{$sTmpDir}{$sDynLang}_dynscreen.xml";


        // including std file
        if ( file_exists( $sFullAdminDir.$sMenuFile ) ) {
            $aFilesToLoad[] = $sFullAdminDir.$sMenuFile;
        }

        // including custom file
        if ( file_exists( "$sFullAdminDir/user.xml" ) ) {
            $aFilesToLoad[] = "$sFullAdminDir/user.xml";
        }

        // including module files
        $sSourceDir = getShopBasePath() . 'modules';
        $handle = opendir( $sSourceDir );
        while ( false !== ( $sFile = readdir( $handle ) ) ) {
            if ( $sFile != '.' && $sFile != '..') {
                $sDir = "$sSourceDir/$sFile";
                if ( is_dir( $sDir ) && file_exists( "$sDir/menu.xml" ) ) {
                        $aFilesToLoad[] = "$sDir/menu.xml";
                }
            }
        }

        $blLoadDynContents = $myConfig->getConfigParam( 'blLoadDynContents' );
        $sShopCountry      = $myConfig->getConfigParam( 'sShopCountry' );

        // including dyn menu file
        $sDynPath = null;

        if ( $blLoadDynContents ) {
            if ( $sShopCountry ) {
                $sRemoteDynUrl = $this->_getDynMenuUrl( $sDynLang, $blLoadDynContents );

                // very basic check if its valid xml file
                if ( ( $sDynPath = $myOxUtlis->getRemoteCachePath( $sRemoteDynUrl, $sLocalDynPath ) ) ) {
                    $sDynPath = $this->_checkDynFile( $sDynPath );
                }
            }
        } else {
            if ( $sShopCountry ) {
                //non international country
            }
        }

        // loading dynpages
        if ( $sDynPath ) {
            $aFilesToLoad[] = $sDynPath;
        }
        return $aFilesToLoad;
    }

    /**
     * Checks if dyn file is valid for inclusion
     *
     * @param string $sDynFilePath dyn file path
     *
     * @return bool
     */
    protected function _checkDynFile( $sDynFilePath )
    {
        $sDynFile = null;
        if ( file_exists( $sDynFilePath ) ) {
            $sLine = null;
            if ( ( $rHandle = @fopen($sDynFilePath, 'r' ) ) ) {
                $sLine = stream_get_line( $rHandle, 100, "?>");
                fclose( $rHandle );

                // checking xml file header
                if ( $sLine && stripos( $sLine, '<?xml' ) !== false ) {
                    $sDynFile = $sDynFilePath;
                }
            }

            // cleanup ..
            if ( !$sDynFile ) {
                @unlink( $sDynFilePath );
            }
        }

        return $sDynFile;
    }

    /**
     * get initial dom, not modified by init method
     *
     * @return DOMDocument
     */
    protected function _getInitialDom()
    {
        if ( !$this->_oInitialDom ) {
            $myOxUtlis = oxUtils::getInstance();

            if ( is_array( $aFilesToLoad = $this->_getMenuFiles() ) ) {

                // now checking if xml files are newer than cached file
                $blReload = false;

                $sVersionPrefix = '';



                    $sVersionPrefix = 'ce';

                $sDynLang = $this->_getDynMenuLang();
                $sCacheFile = $this->getConfig()->getConfigParam( 'sCompileDir' ) . "/ox{$sVersionPrefix}"."c_menu_{$sDynLang}_xml.txt";

                $sCacheContents = $myOxUtlis->fromFileCache( 'menu_' . $sDynLang . '_xml' );

                if ( $sCacheContents && file_exists( $sCacheFile ) && ( $iCacheModTime = filemtime( $sCacheFile ) ) ) {
                    foreach ( $aFilesToLoad as $sDynPath ) {
                        if ( $iCacheModTime < filemtime( $sDynPath ) ) {
                            $blReload = true;
                        }
                    }
                } else {
                    $blReload = true;
                }

                $this->_oInitialDom = new DOMDocument();
                if ( true || $blReload ) {
                    // fully reloading and building pathes
                    $this->_oInitialDom->appendChild( new DOMElement( 'OX' ) );

                    foreach ( $aFilesToLoad as $sDynPath ) {
                        $this->_loadFromFile( $sDynPath, $this->_oInitialDom );
                    }

                    // adds links to menu items
                    $this->_addLinks( $this->_oInitialDom );

                    // adds links to dynamic parts
                    $this->_addDynLinks( $this->_oInitialDom );
                    // writing to cache
                    $myOxUtlis->toFileCache( 'menu_' . $sDynLang . '_xml', $this->getDomXml()->saveXML() );
                } else {
                    // loading from cached file
                    $this->_oInitialDom->preserveWhiteSpace = false;
                    $this->_oInitialDom->loadXML( $sCacheContents );
                }
            }
        }
        return $this->_oInitialDom;
    }

    /**
     * Returns DomXML
     *
     * @return DOMDocument
     */
    public function getDomXml()
    {
        if ( !$this->_oDom ) {
            $this->_oDom = clone $this->_getInitialDom();
        }

        return $this->_oDom;
    }

    /**
     * Returns DOMNodeList of given navigation classes
     *
     * @return DOMNodeList
     */
    public function getListNodes( $aNodes )
    {
        $oXPath = new DOMXPath( $this->_oDom );
        $oNodeList = $oXPath->query( "//SUBMENU[@cl='".implode("' or @cl='",$aNodes)."']" );

        if ( $oNodeList->length ) {
            return $oNodeList;
        }
    }

    /**
     * Formats and returns url for list area
     *
     * @param string $sId tab related class
     *
     * @return string
     */
    public function getListUrl( $sId )
    {
        $oXPath = new DOMXPath( $this->_oDom );
        $oNodeList = $oXPath->query( "//SUBMENU[@cl='{$sId}']" );
        if ( $oNodeList->length && ( $oNode = $oNodeList->item( 0 ) ) ) {
            $sCl = $oNode->getAttribute('list');
            $sCl = $sCl?"cl=$sCl":'';

            $sParams = $oNode->getAttribute('listparam');
            $sParams = $sParams?"&$sParams":'';

            return "{$sCl}{$sParams}";
        }
    }

    /**
     * Formats and returns url for edit area
     *
     * @param string $sId     tab related class
     * @param int    $iActTab active tab
     *
     * @return string
     */
    public function getEditUrl( $sId, $iActTab )
    {
        $oXPath = new DOMXPath( $this->_oDom );
        $oNodeList = $oXPath->query( "//SUBMENU[@cl='{$sId}']/TAB" );

        $iActTab = ( $iActTab > $oNodeList->length )?( $oNodeList->length -1 ):$iActTab;
        if ( $oNodeList->length ) {
            foreach ( $oNodeList as $iPos => $oNode ) {
                if ( $iActTab != $iPos ) {
                    continue;
                }

                // special case for external resources
                if ( $oNode->getAttribute( 'external' ) ) {
                    return $oNode->getAttribute( 'location' );
                }

                $sCl = $oNode->getAttribute('cl');
                $sCl = $sCl?"cl=$sCl":'';

                $sParams = $oNode->getAttribute('clparam');
                $sParams = $sParams?"&$sParams":'';

                return "{$sCl}{$sParams}";
            }
        }
    }

    /**
     * Admin url getter
     *
     * @return string
     */
    protected function _getAdminUrl()
    {
        $myConfig = $this->getConfig();

        if ( ( $sAdminSslUrl = $myConfig->getConfigParam( 'sAdminSSLURL' ) ) ) {
            $sURL = trim( $sAdminSslUrl, '/' );
        } else {
            $sURL = trim( $myConfig->getConfigParam( 'sShopURL' ), '/' ).'/admin';
        }
        return "{$sURL}/index.php?";
    }

    /**
     * Checks if user has required rights
     *
     * @param string $sRights session user rights
     *
     * @return bool
     */
    protected function _hasRights( $sRights )
    {
        return $this->getUser()->oxuser__oxrights->value == $sRights;
    }

    /**
     * Checks if user in required group
     *
     * @param string $sGroupId active group id
     *
     * @return bool
     */
    protected function _hasGroup( $sGroupId )
    {
        return $this->getUser()->inGroup( $sGroupId );
    }

    /**
     * Returns id of class assigned to current node
     *
     * @param string $sClassName active class name
     *
     * @return string
     */
    public function getClassId( $sClassName )
    {
        $sClassId = null;

        $oXPath = new DOMXPath( $this->_getInitialDom() );
        $oNodeList = $oXPath->query( "//*[@cl='{$sClassName}' or @list='{$sClassName}']" );

        if ( $oNodeList->length ) {
            foreach ( $oNodeList as $oNode ) {
                $sClassId = $oNode->getAttribute( 'id' );
                break;
            }
        }

        return $sClassId;
    }

    public function getShopVersionNr()
    {
        $myConfig = $this->getConfig();


        if ( $sShopID = $myConfig->getShopId() ) {
            $sQ = "select oxversion from oxshops where oxid = '$sShopID' ";
            $sVersion = oxDb::getDb()->getOne( $sQ );
        }

        $sVersion = preg_replace("/(^[^0-9]+)(.+)$/", "$2", $sVersion);

        return trim( $sVersion );
    }


    /*
     * Get dynamic pages url or local path
     *
     * @param int    $iLang              language id
     * @param string $$blLoadDynContents get local or remote content path
     *
     * @return string
     */
    protected function _getDynMenuUrl( $iLang, $blLoadDynContents )
    {
        $myConfig = $this->getConfig();

        if ( !$blLoadDynContents) {
            // getting dyn info from oxid server is off, so getting local menu path
            $sFullAdminDir = getShopBasePath() . $myConfig->getConfigParam( 'sAdminDir' );
            $sUrl = $sFullAdminDir . "/dynscreen_local.xml";
        } else {
            $oAdminView = oxNew( 'oxadminview' );
            $this->_sDynIncludeUrl = $oAdminView->getServiceUrl( $iLang );
            $sUrl .= $this->_sDynIncludeUrl . "menue/dynscreen.xml";
        }

        return $sUrl;
    }

    /*
     * Get dynamic pages language code
     *
     * @return string
     */
    protected function _getDynMenuLang()
    {
        $myConfig = $this->getConfig();
        $oLang = oxLang::getInstance();

        $iDynLang = $myConfig->getConfigParam( 'iDynInterfaceLanguage' );
        $iDynLang = isset( $iDynLang )?$iDynLang:( $oLang->getTplLanguage() );

        $aLanguages = $oLang->getLanguageArray();
        $sLangAbr = $aLanguages[$iDynLang]->abbr;

        return $sLangAbr;
    }
}
