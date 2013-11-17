<?php
/**
 * EMOS PHP Bib 2
 *
 * Copyright (c) 2004 - 2007 ECONDA GmbH Karlsruhe
 * All rights reserved.
 *
 * ECONDA GmbH
 * Haid-und-Neu-Str. 7
 * 76131 Karlsruhe
 * Tel. +49 (721) 6630350
 * Fax +49 (721) 66303510
 * info@econda.de
 * www.econda.de
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * Neither the name of the ECONDA GmbH nor the names of its contributors may
 * be used to endorse or promote products derived from this software without
 * specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * $Id: emos.php 18483 2009-04-22 14:53:46Z arvydas $
 */

/**
 * PHP Helper Class to construct a ECONDA Monitor statement for the later
 * inclusion in a HTML/PHP Page.
 */
class EMOS
{
    /**
     * the EMOS statement consists of 3 parts
     * 1.   the inScript :<code><script type="text/javascript" src="emos2.js"></script>
     * 2,3. a part before and after this inScript (preScript/postScript)</code>
     *
     * @var string
     */
    public $preScript = "";

    /**
     * Here we store the call to the js bib
     *
     * @var string
     */
    public $inScript = "";

    /**
     * if we must put something behind the call to the js bin we put it here
     *
     * @var string
     */
    public $postScript = "";

    /**
     * path to the empos2.js script-file
     *
     * @var string
     */
    public $pathToFile = "";

    /**
     * Name of the script-file
     *
     * @var string
     */
    public $scriptFileName = "emos2.js";

    /**
     * if we use pretty print, we will set the lineseparator
     *
     * @var string
     */
    public $br = "\n";

    /**
     * if we use pretty print, we will set the tab here
     *
     * @var string
     */
    public $tab = "\t";

    /**
     * session id for 1st party sessions
     *
     * @var string
     */
    public $emsid = "";

    /**
     * visitor id for 1st partyx visitors
     *
     * @var string
     */
    public $emvid = "";

    /**
     * add compatibility function for php < 5.1
     *
     * @param string $sStr string to decode
     *
     * @return string
     */
    public function htmlspecialchars_decode_php4( $sStr )
    {
        return strtr( $sStr, array_flip( get_html_translation_table( HTML_SPECIALCHARS ) ) );
    }

    /**
     * Constructor
     * Sets the path to the emos2.js js-bib and prepares the later calls
     *
     * @param string $sPathToFile     The path to the js-bib (/opt/myjs)
     * @param string $sScriptFileName If we want to have annother Filename than emos2.js you can set it here
     *
     * @return null
     */
    public function __construct( $sPathToFile = "", $sScriptFileName = "emos2.js" )
    {
        $this->pathToFile = $sPathToFile;
        $this->scriptFileName = $sScriptFileName;
        $this->prepareInScript();
    }

    /**
     * formats data/values/params by eliminating named entities and xml-entities
     *
     * @param EMOS_Item $oItem item to format its parameters
     *
     * @return null
     */
    public function emos_ItemFormat( $oItem )
    {
        $oItem->productID = $this->emos_DataFormat( $oItem->productID );
        $oItem->productName = $this->emos_DataFormat( $oItem->productName );
        $oItem->productGroup = $this->emos_DataFormat( $oItem->productGroup );
        $oItem->variant1 = $this->emos_DataFormat( $oItem->variant1 );
        $oItem->variant2 = $this->emos_DataFormat( $oItem->variant2 );
        $oItem->variant3 = $this->emos_DataFormat( $oItem->variant3 );

        return $oItem;
    }

    /**
     * formats data/values/params by eliminating named entities and xml-entities
     *
     * @param string $sStr data input to format
     *
     * @return null
     */
    public function emos_DataFormat( $sStr )
    {
        $sStr = urldecode($sStr);
        //2007-05-10 Fix incompatibility with php4
        if ( function_exists('htmlspecialchars_decode' ) ) {
            $sStr = htmlspecialchars_decode( $sStr, ENT_QUOTES );
        } else {
            $sStr = $this->htmlspecialchars_decode_php4( $sStr );
        }
        $sStr = getStr()->html_entity_decode( $sStr );
        $sStr = strip_tags( $sStr );
        $sStr = trim( $sStr );

        //2007-05-10 replace translated &nbsp; with spaces
        $nbsp = chr(0xa0);
        $sStr = str_replace( $nbsp, " ", $sStr );
        $sStr = str_replace( "\"", "", $sStr );
        $sStr = str_replace( "'", "", $sStr );
        $sStr = str_replace( "%", "", $sStr );
        $sStr = str_replace( ",", "", $sStr );
        $sStr = str_replace( ";", "", $sStr );
        /* remove unnecessary white spaces*/
        while ( true ) {
            $sStr_temp = $sStr;
            $sStr = str_replace( "  ", " ", $sStr );

            if ( $sStr == $sStr_temp ) {
                break;
            }
        }
        $sStr = str_replace( " / ", "/", $sStr );
        $sStr = str_replace( " /", "/", $sStr );
        $sStr = str_replace( "/ ", "/", $sStr );

        $sStr = getStr()->substr( $sStr, 0, 254 );
        $sStr = rawurlencode( $sStr );
        return $sStr;
    }

    /**
     * sets the 1st party session id
     *
     * @param string $sSid session id to set as parameter
     *
     * @return null
     */
    public function setSid( $sSid = "" )
    {
        if ( $sSid ) {
            $this->emsid = $sSid;
            $this->appendPreScript( "<a name=\"emos_sid\" title=\"$sSid\"></a>\n" );
        }
    }

    /**
     * set 1st party visitor id
     *
     * @param string $sVid visitor id
     *
     * @return null
     */
    public function setVid( $sVid = "" )
    {
        if ( $sVid ) {
            $this->emvid = $sVid;
            $this->appendPreScript( "<a name=\"emos_vid\" title=\"$sVid\"></a>" );
        }
    }

    /**
     * switch on pretty printing of generated code. If not called, the output
     * will be in one line of html.
     *
     * @return null
     */
    public function prettyPrint()
    {
        $this->br .= "\n";
        $this->tab .= "\t";
    }

    /**
     * Concatenates the current command and the $inScript
     *
     * @param string $sStringToAppend string to append
     *
     * @return null
     */
    public function appendInScript( $sStringToAppend )
    {
        $this->inScript.= $sStringToAppend;
    }

    /**
     * Concatenates the current command and the $proScript
     *
     * @param string $sStringToAppend string to append
     *
     * @return null
     */
    public function appendPreScript( $sStringToAppend )
    {
        $this->preScript.= $sStringToAppend;
    }

    /**
     * Concatenates the current command and the $postScript
     *
     * @param string $sStringToAppend string to append
     *
     * @return null
     */
    public function appendPostScript( $sStringToAppend )
    {
        $this->postScript.= $sStringToAppend;
    }

    /**
     * sets up the inScript Part with Initialisation Params
     *
     * @return null
     */
    public function prepareInScript()
    {
        $this->inScript .= "<script type=\"text/javascript\" " .
        "src=\"" . $this->pathToFile . $this->scriptFileName . "\">" .
        "</script>" . $this->br;
    }

    /**
     * returns the whole statement
     *
     * @return string
     */
    public function toString()
    {
        return $this->preScript.$this->inScript.$this->postScript;
    }

    /**
     * constructs a emos anchor tag
     *
     * @param string $sTitle link name
     * @param string $sRel   link rel value
     * @param string $sRev   revision
     *
     * @return string
     */
    public function getAnchorTag( $sTitle = "", $sRel = "", $sRev = "" )
    {
        $sRel = $this->emos_DataFormat( $sRel );
        $sRev = $this->emos_DataFormat( $sRev );
        $anchor = "<a name=\"emos_name\" " .
        "title=\"$sTitle\" " .
        "rel=\"$sRel\" " .
        "rev=\"$sRev\"></a>$this->br";
        return $anchor;
    }

    /**
     * adds a anchor tag for content tracking
     * <a name="emos_name" title="content" rel="$sContent" rev=""></a>
     *
     * @param string $sContent content to add
     *
     * @return null
     */
    public function addContent( $sContent )
    {
        $this->appendPreScript( $this->getAnchorTag( "content", $sContent ) );
    }

    /**
     * adds a anchor tag for orderprocess tracking
     * <a name="emos_name" title="orderProcess" rel="$sProcessStep" rev=""></a>
     *
     * @param string $sProcessStep process step to add
     *
     * @return null
     */
    public function addOrderProcess( $sProcessStep )
    {
        $this->appendPreScript( $this->getAnchorTag( "orderProcess", $sProcessStep ) );
    }

    /**
     * adds a anchor tag for siteid tracking
     * <a name="emos_name" title="siteid" rel="$sIiteId" rev=""></a>
     *
     * @param string $sIiteId site id to add
     *
     * @return null
     */
    public function addSiteID( $sIiteId )
    {
        $this->appendPreScript( $this->getAnchorTag( "siteid", $sIiteId ) );
    }

    /**
     * adds a anchor tag for language tracking
     * <a name="emos_name" title="langid" rel="$sLangId" rev=""></a>
     *
     * @param string $sLangId language id to add
     *
     * @return null
     */
    public function addLangID( $sLangId )
    {
        $this->appendPreScript( $this->getAnchorTag( "langid", $sLangId ) );
    }

    /**
     * adds a anchor tag for country tracking
     * <a name="emos_name" title="countryid" rel="$sCountryId" rev=""></a>
     *
     * @param string $sCountryId country id to add
     *
     * @return null
     */
    public function addCountryID( $sCountryId )
    {
        $this->appendPreScript( $this->getAnchorTag( "countryid", $sCountryId ) );
    }

    /**
     * adds a Page ID to the current window (window.emosPageId)
     *
     * @param string $sPageId page id to add
     *
     * @return null
     */
    public function addPageID( $sPageId )
    {
        $this->appendPreScript( "\n<script type=\"text/javascript\">\n window.emosPageId = '$sPageId';\n</script>\n" );
    }

    /**
     * adds a anchor tag for search tracking
     * <a name="emos_name" title="search" rel="$sQueryString" rev="$iNumberOfHits"></a>
     *
     * @param string $sQueryString  query string
     * @param int    $iNumberOfHits number of hits
     *
     * @return null
     */
    public function addSearch( $sQueryString, $iNumberOfHits )
    {
        $this->appendPreScript( $this->getAnchorTag( "search", $sQueryString, $iNumberOfHits ) );
    }

    /**
     * adds a anchor tag for registration tracking
     * The userid gets a md5() to fullfilll german datenschutzgesetz
     * <a name="emos_name" title="register" rel="$sUserId" rev="$sResult"></a>
     *
     * @param string $sUserId user id
     * @param string $sResult registration result
     *
     * @return null
     */
    public function addRegister( $sUserId, $sResult )
    {
        $this->appendPreScript($this->getAnchorTag( "register", md5( $sUserId ), $sResult ) );
    }


    /**
     * adds a anchor tag for login tracking
     * The userid gets a md5() to fullfilll german datenschutzgesetz
     * <a name="emos_name" title="login" rel="$sUserId" rev="$sResult"></a>
     *
     * @param string $sUserId user id
     * @param string $sResult login result
     *
     * @return null
     */
    public function addLogin( $sUserId, $sResult )
    {
        $this->appendPreScript( $this->getAnchorTag( "login", md5( $sUserId ), $sResult ) );
    }

    /**
     * adds a anchor tag for contact tracking
     * <a name="emos_name" title="scontact" rel="$sContactType" rev=""></a>
     *
     * @param string $sContactType contant type
     *
     * @return null
     */
    public function addContact( $sContactType )
    {
        $this->appendPreScript( $this->getAnchorTag( "scontact", $sContactType ) );
    }

    /**
     * adds a anchor tag for download tracking
     * <a name="emos_name" title="download" rel="$sDownloadLabel" rev=""></a>
     *
     * @param string $sDownloadLabel download label
     *
     * @return null
     */
    public function addDownload( $sDownloadLabel )
    {
        $this->appendPreScript( $this->getAnchorTag( "download", $sDownloadLabel ) );
    }

    /**
     * constructs a emosECPageArray of given $sEvent type
     *
     * @param EMOS_Item $oItem  a instance of class EMOS_Item
     * @param string    $sEvent Type of this event ("add","c_rmv","c_add")
     *
     * @return string
     */
    public function getEmosECPageArray( $oItem, $sEvent )
    {
        $oItem = $this->emos_ItemFormat( $oItem );
        $out = "<script type=\"text/javascript\">$this->br" .
        "<!--$this->br" .
        "$this->tab var emosECPageArray = new Array();$this->br" .
        "$this->tab emosECPageArray['event'] = '$sEvent';$this->br" .
        "$this->tab emosECPageArray['id'] = '$oItem->productID';$this->br" .
        "$this->tab emosECPageArray['name'] = '$oItem->productName';$this->br" .
        "$this->tab emosECPageArray['preis'] = '$oItem->price';$this->br" .
        "$this->tab emosECPageArray['group'] = '$oItem->productGroup';$this->br" .
        "$this->tab emosECPageArray['anzahl'] = '$oItem->quantity';$this->br" .
        "$this->tab emosECPageArray['var1'] = '$oItem->variant1';$this->br" .
        "$this->tab emosECPageArray['var2'] = '$oItem->variant2';$this->br" .
        "$this->tab emosECPageArray['var3'] = '$oItem->variant3';$this->br" .
        "// -->$this->br" .
        "</script>$this->br";
        return $out;
    }

    /**
     * constructs a emosBillingPageArray of given $sEvent type
     *
     * @param string $sBillingId      billing id
     * @param string $sCustomerNumber customer number
     * @param int    $iTotal          total number
     * @param string $sCountry        customer country title
     * @param string $sCip            customer ip
     * @param string $sCity           customer city title
     *
     * @return null
     */
    public function addEmosBillingPageArray( $sBillingId = "", $sCustomerNumber = "", $iTotal = 0, $sCountry = "", $sCip = "", $sCity = "" )
    {
        $out = $this->getEmosBillingArray( $sBillingId, $sCustomerNumber, $iTotal, $sCountry, $sCip, $sCity, "emosBillingPageArray" );
        $this->appendPreScript( $out );
    }

    /**
     * gets a emosBillingArray for a given ArrayName
     *
     * @param string $sBillingId      billing id
     * @param string $sCustomerNumber customer number
     * @param int    $iTotal          total number
     * @param string $sCountry        customer country title
     * @param string $sCip            customer ip
     * @param string $sCity           customer city title
     * @param string $sArrayName      name of JS array
     *
     * @return string
     */
    public function getEmosBillingArray( $sBillingId = "", $sCustomerNumber = "", $iTotal = 0, $sCountry = "", $sCip = "", $sCity = "", $sArrayName = "" )
    {
        /******************* prepare data *************************************/
        /* md5 the customer id to fullfill requirements of german datenschutzgeesetz */
        $sCustomerNumber = md5( $sCustomerNumber );

        $sCountry = $this->emos_DataFormat( $sCountry );
        $sCip = $this->emos_DataFormat( $sCip) ;
        $sCity = $this->emos_DataFormat( $sCity );

        /* get a / separated location stzring for later drilldown */
        $ort = "";
        if ( $sCountry ) {
            $ort .= "$sCountry/";
        }

        if ( $sCip ) {
            $ort .= substr( $sCip, 0, 1 )."/".substr( $sCip, 0, 2 )."/";
        }

        if ( $sCity ) {
            $ort .= "$sCity/";
        }

        if ( $sCip ) {
            $ort.=$sCip;
        }

        /******************* get output** *************************************/
        /* get the real output of this funktion */
        $out = "";
        $out .= "<script type=\"text/javascript\">$this->br" .
        "<!--$this->br" .
        "$this->tab var $sArrayName = new Array();$this->br" .
        "$this->tab $sArrayName" . "['0'] = '$sBillingId';$this->br" .
        "$this->tab $sArrayName" . "['1'] = '$sCustomerNumber';$this->br" .
        "$this->tab $sArrayName" . "['2'] = '$ort';$this->br" .
        "$this->tab $sArrayName" . "['3'] = '$iTotal';$this->br" .
        "// -->$this->br" .
        "</script>$this->br";
        return $out;
    }

    /**
     * adds a emosBasket Page Array to the preScript
     *
     * @param array $aBasket basket items
     *
     * @return null
     */
    public function addEmosBasketPageArray( $aBasket )
    {
        $out = $this->getEmosBasketPageArray( $aBasket, "emosBasketPageArray" );
        $this->appendPreScript( $out );
    }

    /**
     * returns a emosBasketArray of given Name
     *
     * @param array  $aBasket    basket items
     * @param atring $sArrayName name of JS array
     *
     * @return string
     */
    public function getEmosBasketPageArray( $aBasket, $sArrayName )
    {
        $out = "<script type=\"text/javascript\">$this->br" .
        "<!--$this->br" .
        "var $sArrayName = new Array();$this->br";
        $count = 0;
        foreach ( $aBasket as $oItem ) {
            $oItem = $this->emos_ItemFormat( $oItem );
            $out .= $this->br;
            $out .= "$this->tab $sArrayName"."[$count]=new Array();$this->br";
            $out .= "$this->tab $sArrayName"."[$count][0]='$oItem->productID';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][1]='$oItem->productName';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][2]='$oItem->price';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][3]='$oItem->productGroup';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][4]='$oItem->quantity';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][5]='$oItem->variant1';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][6]='$oItem->variant2';$this->br";
            $out .= "$this->tab $sArrayName"."[$count][7]='$oItem->variant3';$this->br";
            $count++;
        }
        $out .= "// -->$this->br" .
        "</script>$this->br";

        return $out;
    }

    /**
     * adds a detailView to the preScript
     *
     * @param EMOS_Item $oItem item to add to view
     *
     * @return null
     */
    public function addDetailView( $oItem )
    {
        $this->appendPreScript( $this->getEmosECPageArray( $oItem, "view" ) );
    }

    /**
     * adds a removeFromBasket to the preScript
     *
     * @param EMOS_Item $oItem item to remove from basket
     *
     * @return null
     */
    public function removeFromBasket( $oItem )
    {
        $this->appendPreScript( $this->getEmosECPageArray( $oItem, "c_rmv" ) );
    }

    /**
     * adds a addToBasket to the preScript
     *
     * @param EMOS_Item $oItem item to add to basket
     *
     * @return null
     */
    public function addToBasket( $oItem )
    {
        $this->appendPreScript( $this->getEmosECPageArray( $oItem, "c_add" ) );
    }

    /**
     * constructs a generic EmosCustomPageArray from a PHP Array
     *
     * @param array $aListOfValues list of custom values to assign to emos tracker
     *
     * @return string
     */
    public function getEmosCustomPageArray( $aListOfValues )
    {
        $out = "<script type=\"text/javascript\">$this->br" .
        "<!--$this->br" .
        "$this->tab var emosCustomPageArray = new Array();$this->br";

        $iCounter = 0;
        foreach ( $aListOfValues as $sValue ) {
            $sValue = $this->emos_DataFormat( $sValue );
            $out .= "$this->tab emosCustomPageArray[$iCounter] = '$sValue';$this->br";
            $iCounter ++;
        }

        return $out . "// -->$this->br" ."</script>$this->br";
    }

    /**
     * constructs a emosCustomPageArray with 8 Variables and shortcut
     *
     * @param string $cType  Type of this event - shortcut in config
     * @param string $cVar1  first variable of this custom event (optional)
     * @param string $cVar2  second variable of this custom event (optional)
     * @param string $cVar3  third variable of this custom event (optional)
     * @param string $cVar4  fourth variable of this custom event (optional)
     * @param string $cVar5  fifth variable of this custom event (optional)
     * @param string $cVar6  sixth variable of this custom event (optional)
     * @param string $cVar7  seventh variable of this custom event (optional)
     * @param string $cVar8  eighth variable of this custom event (optional)
     * @param string $cVar9  nineth variable of this custom event (optional)
     * @param string $cVar10 tenth variable of this custom event (optional)
     * @param string $cVar11 eleventh variable of this custom event (optional)
     * @param string $cVar12 twelveth variable of this custom event (optional)
     * @param string $cVar13 thirteenth variable of this custom event (optional)
     *
     * @return null
     */
    public function addEmosCustomPageArray( $cType = 0, $cVar1 = 0, $cVar2 = 0, $cVar3 = 0, $cVar4 = 0,
                                            $cVar5 = 0, $cVar6 = 0, $cVar7 = 0, $cVar8 = 0, $cVar9 = 0,
                                            $cVar10 = 0, $cVar11 = 0, $cVar12 = 0, $cVar13 = 0 )
    {
        $aValues[0] = $cType;
        if ( $cVar1 ) {
            $aValues[1] = $cVar1;
        }

        if ( $cVar2 ) {
            $aValues[2] = $cVar2;
        }

        if ( $cVar3 ) {
            $aValues[3] = $cVar3;
        }

        if ( $cVar4 ) {
            $aValues[4] = $cVar4;
        }

        if ( $cVar5 ) {
            $aValues[5] = $cVar5;
        }

        if ( $cVar6 ) {
            $aValues[6] = $cVar6;
        }

        if ( $cVar7 ) {
            $aValues[7] = $cVar7;
        }

        if ( $cVar8 ) {
            $aValues[8] = $cVar8;
        }

        if ( $cVar9 ) {
            $aValues[9] = $cVar9;
        }

        if ( $cVar10 ) {
            $aValues[10] = $cVar10;
        }

        if ( $cVar11 ) {
            $aValues[11] = $cVar11;
        }

        if ( $cVar12 ) {
            $aValues[12] = $cVar12;
        }

        if ( $cVar13 ) {
            $aValues[13] = $cVar13;
        }

        $this->appendPreScript( $this->getEmosCustomPageArray( $aValues ) );
    }

    /**
     * Returns string form event definition
     *
     * @param EMOS_Item $oItem  item used to freate event from it
     * @param string    $sEvent event namet
     *
     * @return string
     */
    public function getEmosECEvent( $oItem, $sEvent )
    {
        $oItem = $this->emos_ItemFormat( $oItem );
        $out = "emos_ecEvent('$sEvent'," .
        "'$oItem->productID'," .
        "'$oItem->productName'," .
        "'$oItem->price'," .
        "'$oItem->productGroup'," .
        "'$oItem->quantity'," .
        "'$oItem->variant1'" .
        "'$oItem->variant2'" .
        "'$oItem->variant3');";
        return $out;
    }

    /**
     * Returns view event definition
     *
     * @param EMOS_Item $oItem viewable item
     *
     * @return string
     */
    public function getEmosViewEvent( $oItem )
    {
        return $this->getEmosECEvent( $oItem, "view" );
    }

    /**
     * Returns add to basket event definition
     *
     * @param EMOS_Item $oItem basket item added to basket
     *
     * @return string
     */
    public function getEmosAddToBasketEvent( $oItem )
    {
        return $this->getEmosECEvent( $oItem, "c_add" );
    }

    /**
     * Returns remove from basket event definition
     *
     * @param EMOS_Item $oItem basket item to bark as removed
     *
     * @return string
     */
    public function getRemoveFromBasketEvent( $oItem )
    {
        return $this->getEmosECEvent( $oItem, "c_rmv" );
    }

    /**
     * Returns billing event array
     *
     * @param string $sBillingId      billing id
     * @param string $sCustomerNumber customer number
     * @param int    $iTotal          total number
     * @param string $sCountry        customer country title
     * @param string $sCip            customer ip
     * @param string $sCity           customer city title
     *
     * @return string
     */
    public function getEmosBillingEventArray( $sBillingId = "", $sCustomerNumber = "", $iTotal = 0, $sCountry = "", $sCip = "", $sCity = "" )
    {
        return $this->getEmosBillingArray( $sBillingId, $sCustomerNumber, $iTotal, $sCountry, $sCip, $sCity, "emosBillingArray" );
    }

    /**
     * Returns basket event array
     *
     * @param array $aBasket basket items
     *
     * @return string
     */
    public function getEMOSBasketEventArray( $aBasket )
    {
        return $this->getEmosBasketArray( $aBasket, "emosBasketArray" );
    }
}


/**
 * A Class to hold products as well a basket items
 * If you want to track a product view, set the quantity to 1.
 * For "real" basket items, the quantity should be given in your
 * shopping systems basket/shopping cart.
 *
 * Purpose of this class:
 * This class provides a common subset of features for most shopping systems
 * products or basket/cart items. So all you have to do is to convert your
 * products/articles/basket items/cart items to a EMOS_Items. And finally use
 * the functionaltiy of the EMOS class.
 * So for each shopping system we only have to do the conversion of the cart/basket
 * and items and we can (hopefully) keep the rest of code.
 *
 * Shopping carts:
 *	A shopping cart / basket is a simple Array[] of EMOS items.
 *	Convert your cart to a Array of EMOS_Items and your job is nearly done.
 */
class EMOS_Item
{
    /**
     * unique Identifier of a product e.g. article number
     *
     * @var string
     */
    public $productID = "NULL";

    /**
     * the name of a product
     *
     * @var string
     */
    public $productName = "NULL";

    /**
     * the price of the product, it is your choice wether its gross or net
     *
     * @var string
     */
    public $price = "NULL";

    /**
     * the product group for this product, this is a drill down dimension
     * or tree-like structure
     * so you might want to use it like this:
     * productgroup/subgroup/subgroup/product
     *
     * @var string
     */
    public $productGroup = "NULL";

    /**
     * the quantity / number of products viewed/bought etc..
     *
     * @var string
     */
    public $quantity = "NULL";

    /**
     * variant of the product e.g. size, color, brand ....
     * remember to keep the order of theses variants allways the same
     * decide which variant is which feature and stick to it
     *
     * @var string
     */
    public $variant1 = "NULL";
    public $variant2 = "NULL";
    public $variant3 = "NULL";
}
