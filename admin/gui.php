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
 * $Id: gui.php 23173 2009-10-12 13:29:45Z sarunas $
 */

/**
 * Look&Feel editor file
 *
 * @package core
 */
class Gui extends oxAdminView
{
    /**
     * resource directory path
     *
     * @var string
     */
    protected $_sSrcDir;

    /**
     * Look&Feel resouces folder name
     *
     * @var string
     */
    protected $_sGuiDir   = "gui/";

    /**
     * css bacground image directory name inside resource dir
     *
     * @var string
     */
    protected $_sBgDir    = "bg/";

    /**
     * Configuration file
     *
     * @var string
     */
    protected $_sGuiXml   = "gui.xml";

    /**
     * User defined colors storage file
     *
     * @var string
     */
    protected $_sUserGui  = "usergui.php";

    /**
     * Dom object holding gui.xml
     *
     * @var DomDocument
     */
    protected $_oGuiDom ;

    /**
     * Theme configuration file
     *
     * @var string
     */
    protected $_sThemeXml = "theme.xml";

    /**
     * Dom object holding theme.xml
     *
     * @var DomDocument
     */
    protected $_oThemesDom ;

    /**
     * is dom objects loaded
     *
     * @var bool
     */
    protected $_blLoaded = false;

    /**
     * Ininializes internal fields and loads dom objects
     *
     * @return null
     */
    public function init()
    {
        parent::init();

        $myConfig   = $this->getConfig();

        $this->_sSrcDir = $myConfig->getResourceDir( false );
        $this->_sGuiDir = $this->_sSrcDir.$this->_sGuiDir;


        $this->_loadGuiFiles();

        $this->sTplName = "gui.tpl";
        $this->sTplErr  = "gui_error.tpl";
    }

    /**
     * Render template and loads all needed data
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        if($this->_blLoaded) {

            $sTheme = null;
            $aColors = $aStyles = array();

            $sFilePath = $this->_sSrcDir.$this->_sUserGui;

            if(is_readable($sFilePath)){

                include($sFilePath);

                if(is_array($aColors)){
                    $this->_aViewData["user_colors"] = $aColors;
                }else{
                    $this->_aViewData["user_colors"] = array();
                }

                if(is_array($aStyles)){
                    $this->_aViewData["user_styles"] = $aStyles;
                }else{
                    $this->_aViewData["user_styles"] = array();
                }
            }

            if(!$sTheme){

                    $sTheme = 'ce';

            }

            $aThemes = $this->getThemes();
            $aColors = array();

            foreach ($aThemes as $id => $title){
                $aColors[$id] = $this->getColors($id);
            }

            $this->_aViewData["themes"] = $aThemes;

            $this->_aViewData["theme"]  = $sTheme;

            $this->_aViewData["colors"] = $aColors;

            $this->_aViewData["styles"]      = $this->getStyleTree();
            $this->_aViewData["colorstyles"] = $this->getColors($sTheme,'const','index');

            return $this->sTplName;

        } else {
            return $this->sTplErr;
        }
    }

    /**
     * Fills array with color values according to the index
     *
     * @param array $aStyles array to fill
     * @param array $aColors values to fill
     *
     * @return array
     */
    public function fillColors($aStyles,$aColors) {
        foreach ($aStyles as $sConst => $sIndex) {
            if( array_key_exists($sIndex,$aColors) ) {
                $aStyles[$sConst] = $aColors[$sIndex];
            }
        }
        return $aStyles;
    }

    /**
     * Generates css and background image files
     *
     * @param bool $blAjax do not render page if called from ajax
     *
     * @return null
     */
    public function save( $blAjax = true ) {

        $sTheme = $this->getConfig()->getParameter('t');
        $aUserColors = $this->getUserColors();
        $aUserStyles = $this->getUserStyles();

        $this->saveUserSettings($sTheme,$aUserColors,$aUserStyles);

        $aStyles = $this->fillColors( $aUserStyles, $aUserColors );

        $aErrors = array();

        $aGif = $this->getRes('gif');
        foreach ($aGif as $oGif) {
            $sTpl    = $oGif->getAttribute('tpl');
            $sFile   = $oGif->getAttribute('file');
            $sConst  = $oGif->getAttribute('const');

            $aGifStyles = $this->fillColors( $this->getResColors('gif',$sTpl), $aStyles );

            $sFile = str_replace('.gif','_'.$sTheme.'_.gif',$sFile);

            $sFilePath = $this->_sSrcDir.$sFile;

            if(file_exists($sFilePath) && !is_writable($sFilePath)){
                @chmod( $sFilePath, 0766);
                if (!is_writable($sFilePath)){
                    $aErrors[] = 'Could not write to : '.$sFilePath;
                }
            }

            $this->gif( $sTpl , $aGifStyles,  $sFile, $this->_sSrcDir);

            $aStyles[$sConst] = $sFile;
        }

        $aCss = $this->getRes('css');

        foreach ($aCss as $oCss) {
            $sStyle  = "/* OXID look&feel generated CSS */\n\n";
            $sTpl  = $oCss->getAttribute('tpl');
            $sFile = $oCss->getAttribute('file');


            $sStyle .= strtr( file_get_contents($this->_sGuiDir.$sTpl), $aStyles);

            $sFilePath = $this->_sSrcDir.$sFile;

            if(file_exists($sFilePath) && !is_writable($sFilePath)){
                @chmod( $sFilePath, 0766);
            }

            if (is_writable($sFilePath)){
                file_put_contents($sFilePath,$sStyle);
            } else {
                $aErrors[] = 'Could not write to : '.$sFilePath;
            }
        }

        if($blAjax){

            $sResponce = '';

            if(count($aErrors)){
                $sResponce = implode("\n",$aErrors);
            }

            die($sResponce);
        }

    }

    /**
     * Saves user setting to php file
     *
     * @param string $sTheme  Theme id
     * @param string $aColors Color palette
     * @param string $aStyles Element styles
     *
     * @return null
     */
    public function saveUserSettings( $sTheme, $aColors, $aStyles ){

           $sFilePath = $this->_sSrcDir.$this->_sUserGui;

        if(file_exists($sFilePath) && !is_writable($sFilePath)){
               @chmod( $sFilePath, 0766);
        }

           $sFile = "<?php \n";
           $sFile.= "/* OXID look&feel generated file */\n\n";
           $sFile.= '$sTheme  = "'.$sTheme.'";'."\n\n";
           $sFile.= '$aColors = '.var_export( (array) $aColors,true).';'."\n\n";
           $sFile.= '$aStyles = '.var_export( (array) $aStyles,true).';'."\n\n";

        file_put_contents($sFilePath,$sFile);
    }

    /**
     * Renders css preview with image preview link
     *
     * @return null
     */
    public function previewCss() {

        $aStyles = $this->fillColors( $this->getUserStyles(), $this->getUserColors() );

        $aGif = $this->getRes('gif');
        $sAdminUrl = $this->getViewConfig()->getViewConfigParam('selflink');
        foreach ($aGif as $oGif) {
            $sTpl    = $oGif->getAttribute('tpl');
            $sConst  = $oGif->getAttribute('const');

            $aGifStyles = $this->fillColors( $this->getResColors('gif',$sTpl), $aStyles );
            $aGifUrl    = $sAdminUrl.'?cl=gui&fnc=previewGif&gif='.$sTpl;

            foreach ($aGifStyles as $i => $c) {
                $aGifUrl .= "&p[{$i}]=".urlencode($c);
            }

            $aStyles[$sConst] = $aGifUrl;
        }

        //keep existing backgrounds
        $aStyles[$this->_sBgDir] = $this->getConfig()->getResourceUrl( null, false ).$this->_sBgDir;

        $aCss = $this->getRes('css');
        $sStyle  = "/* OXID GUI generated file css file */\n";
        foreach ($aCss as $oCss) {
            $sTpl = $oCss->getAttribute('tpl');
            $sStyle .= strtr( file_get_contents($this->_sGuiDir.$sTpl), $aStyles);
        }

        header('Content-type: text/css');
        die( str_replace("\n",'',$sStyle) );
    }

    /**
     * Renders image preview
     *
     * @return null
     */
    public function previewGif() {

        $myConfig = $this->getConfig();

        $sTpl     = basename($myConfig->getParameter('gif'));
        $aColors  = $myConfig->getParameter('p');

        $this->gif( $sTpl , $aColors );
        die();
    }

    /**
     * Renders and outputs or saves gif image (saves if not null, otherwise outputs directly)
     *
     * @param string $sTpl    Theme id
     * @param array  $aColors Colors array
     * @param string $sFile   File name
     * @param string $sDir    Directory name
     *
     * @return null
     */
    public function gif( $sTpl = null, $aColors = null, $sFile = null, $sDir = null ) {

        $img = imagecreatefromgif($this->_sGuiDir.$sTpl);

        foreach($aColors as $n => $c) {
            $x = $this->hex2rgb($c);
            imagecolorset($img, $n, $x[0], $x[1], $x[2]);
        }

        if(is_null($sDir)) {
            header('Content-type: image/gif');
            imagegif($img);
        }else{
            imagegif( $img, $sDir.$sFile );
        }

        imagedestroy($img);
    }

    /**
     * Returns default and posted colors array
     *
     * @return array
     */
    public function getUserColors(){

        $myConfig = $this->getConfig();

        $sThemeId = $myConfig->getParameter('t');

        $aThemeColors = $this->getColors($sThemeId);

        $aUserColors  = (array) $myConfig->getParameter('c');

        return array_merge($aUserColors,$aThemeColors);
    }

    /**
     * Returns default and posted styles array
     *
     * @return array
     */
    public function getUserStyles(){
        $myConfig = $this->getConfig();

        $sThemeId = $myConfig->getParameter('t');

        $aThemeStyles = $this->getStyles();

        $aColorStyles = $this->getColors($sThemeId,'const','index');

        $aUserStyles  = (array) $myConfig->getParameter('s');

        return array_merge($aThemeStyles,$aColorStyles,$aUserStyles);
    }


    /**
     * Loads gui and theme xml files DOM documents
     *
     * @access protected
     * @return array
     */
    protected function _loadGuiFiles() {

        $this->_blLoaded = false;

        if(is_readable($this->_sGuiDir.$this->_sGuiXml)){
            $this->_oGuiDom = new DomDocument();
            $this->_oGuiDom->preserveWhiteSpace = false;
            $this->_oGuiDom->load( $this->_sGuiDir.$this->_sGuiXml );
            $this->_blLoaded = true;
        }

         if(is_readable($this->_sGuiDir.$this->_sThemeXml) &&  $this->_blLoaded){
            $this->_oThemesDom = new DomDocument();
            $this->_oThemesDom->preserveWhiteSpace = false;
            $this->_oThemesDom->load( $this->_sGuiDir.$this->_sThemeXml );
            $this->_blLoaded = true;
        }else{
            $this->_blLoaded = false;
        }
    }

    /**
     * Returns themes array
     *
     * @return array
     */
    public function getThemes() {
        $oXPath = new DomXPath( $this->_oThemesDom );
        $oThemeList = $oXPath->query( "/themes/theme" );
        $aThemes = array();
        foreach ( $oThemeList as $oTheme ) {
            $aThemes[$oTheme->getAttribute('id')] = $oTheme->getAttribute('title');
        }
        return $aThemes;
    }

    /**
     * Returns default theme color palette
     *
     * @var string $sThemeId Theme id
     *
     * @return array
     */
    public function getColors($sThemeId,$sKey = 'index',$sValue = 'color') {
        $oXPath = new DomXPath( $this->_oThemesDom );

        $oColorList = $oXPath->query( "/themes/theme[@id='{$sThemeId}']/color" );
        $aColors = array();
        foreach ( $oColorList as $oColor ) {
            if($oColor->hasAttribute($sKey)&&$oColor->hasAttribute($sValue)){
                $aColors[$oColor->getAttribute($sKey)] = $oColor->getAttribute($sValue);
            }
        }

        return $aColors;
    }

    /**
     * Returns default styles
     *
     * @return array
     */
    public function getStyles() {
        $oXPath = new DomXPath( $this->_oGuiDom );

        $oStyleList = $oXPath->query( "/gui/css//color" );
        $aStyles = array();
        foreach ( $oStyleList as $oStyle ) {
            $aStyles[$oStyle->getAttribute('const')] = $oStyle->getAttribute('color');
        }

        return $aStyles;
    }

    /**
     * Returns gui resource from dom object
     *
     * @var string $sRes Resource name
     *
     * @return DOMNodeList
     */
    public function getRes( $sRes ) {
        $oXPath = new DomXPath( $this->_oGuiDom );
        $oList = $oXPath->query( "/gui/{$sRes}" );

        return $oList;
    }

    /**
     * Returns gui resource template colors array
     *
     * @var string $sRes Resource name
     * @var string $sTpl Template name
     *
     * @return array
     */
    public function getResColors( $sRes, $sTpl ){
        $oXPath = new DomXPath( $this->_oGuiDom );
        $oList = $oXPath->query( "/gui/{$sRes}[@tpl='{$sTpl}']/color" );

        $aList = array();
        foreach ( $oList as $oItem ) {
            $aList[$oItem->getAttribute('const')] =  $oItem->getAttribute('color');
        }

        return $aList;
    }

    /**
     * Returns gif image colors array taken from styles array
     *
     * @var string $sImageID Image id
     * @var array  $aStyles  Styles array with collors
     *
     * @return array
     */
    public function getImageColors($sImageID, $aStyles) {
        $oXPath = new DomXPath( $this->_oGuiDom );
        $oColorList = $oXPath->query( "/gui/gif[@const='{$sImageID}']/color" );

        $aColors = array();
        foreach ( $oColorList as $oColor ) {
            $aColors[$oColor->getAttribute('index')] =  $aStyles[$oColor->getAttribute('const')];
        }

        return  $aColors;
    }

   /**
     * Returns element styles tree nodes
     *
     * @var string $sImageID Image id
     * @var array  $aStyles  Styles array with collors
     *
     * @return DOMNodeList
     */
    public function getStyleTree() {
        $oXPath = new DomXPath( $this->_oGuiDom );
        $oStyleList = $oXPath->query( "/gui/css/*" );

        return $oStyleList;
    }

   /**
     * Conversts hex color code to their RGB components.
     * Supports short and long hex codes (eg $f90 and #FF9900)
     *
     * @var string $sHexColor Hex color code
     *
     * @return array
     */
    function hex2rgb($sHexColor) {

        if(strlen($sHexColor) == 4) {
            $r = 1;
            $g = 2;
            $b = 3;
            $c = 1;
            $n = 2;
        }elseif(strlen($sHexColor) == 7){
            $r = 1;
            $g = 3;
            $b = 5;
            $c = 2;
            $n = 1;
        }

        $rh = str_repeat( substr($sHexColor,$r,$c), $n);
        $gh = str_repeat( substr($sHexColor,$g,$c), $n);
        $bh = str_repeat( substr($sHexColor,$b,$c), $n);

        $rr = hexdec( $rh );
        $gg = hexdec( $gh );
        $bb = hexdec( $bh );

        return array($rr,$gg,$bb);
    }

}
