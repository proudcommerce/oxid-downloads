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
 * $Id: oximex.php 17694 2009-03-31 12:06:13Z vilma $
 */

/**
 * @package core
 */
class oxImex extends oxBase
{
    /**
     * Exports table contents to file, returns true on success.
     *
     * @param integer $iStart    Start writing export data from
     * @param integer $iLines    Write number of lines
     * @param string  $sFilepath Path to export file
     *
     * @deprecated
     *
     * @return bool
     */
    public function export( $iStart, $iLines, $sFilepath)
    {
        if ( !$this->getViewName()) {
            return false;
        } elseif ( $this->getViewName() == "lexware") {
            return $this->exportLexwareArticles( $iStart, $iLines, $sFilepath);
        }

        $myConfig = $this->getConfig();
        $oDB      = oxDb::getDb();

        $sWhere = "";

        $sSearch = $this->_sCoreTbl . "__oxshopid";
        if ( isset( $this->$sSearch)) {
            $sWhere = " where oxshopid = '".$myConfig->getShopId()."' ";
        }

        $sSelect = "select count(oxid) from ".$this->getViewName().$sWhere;
        $iSize = $oDB->getOne( $sSelect);
        if ( $iStart < $iSize) {
            // if first, delete the file
            $fp = fopen( $sFilepath, "a");

            $sSelect = "select * from ".$this->getViewName().$sWhere;
            $rs = $oDB->selectLimit( $sSelect, $iLines, $iStart);
            // #573 defining decimal separator
            $blDecReplace = false;
            $sDecimalSeparator = $myConfig->getConfigParam( 'sDecimalSeparator' );
            if ( $sDecimalSeparator != ".") {
                $blDecReplace = true;
            }

            while (!$rs->EOF) {
                $sLine = "\"".$this->_sCoreTbl."\"";

                foreach ( $rs->fields as $iNum => $field) {
                    $sLine .= $myConfig->getConfigParam( 'sCSVSign' );

                    if ( !is_numeric( $field)) {
                        $sLine .= "\"".$this->interFormSimple($field)."\"";
                    } else {
                        if ( $blDecReplace) {
                            $field = str_replace( ".", $sDecimalSeparator, $field );
                        }
                        $sLine .= $field;
                    }
                }
                $sLine .= "\r\n";

                fputs( $fp, $sLine);

                $rs->moveNext();
            }

            fclose( $fp);

            return true;
        }

        return false;
    }

    /**
     * Exports users table contents to file, returns true on success.
     *
     * @param integer $iStart    Start writing export data from
     * @param integer $iLines    Write number of lines
     * @param string  $sFilepath Path to export file
     *
     * @deprecated
     *
     * @return bool
     */
    public function exportUsers( $iStart, $iLines, $sFilepath)
    {
        $myConfig  = $this->getConfig();

        $aGroups = oxSession::getVar("_agroups");

        if ( !$this->getViewName() || !$aGroups) {
            return false;
        }

        $oDB = oxDb::getDb();

        $sWhere = "";
        $sInGroup = "";
        $blSep = false;
        foreach ($aGroups as $sGroupId => $iAct) {
            if ($blSep) {
                $sInGroup .= ", ";
            }
            $sInGroup .= "'".$sGroupId."'";
            $blSep = true;
        }

        $sSelect  = "select count(".$this->getViewName().".oxid) from ".$this->getViewName()." ";
        $sSelect .= "left join oxobject2group on ".$this->getViewName().".oxid=oxobject2group.oxobjectid ";
        $sSelect .= "where oxobject2group.oxgroupsid in ($sInGroup) ";
        $sSearch = $this->getViewName() . "__oxshopid";
        if ( isset( $this->$sSearch)) {
            $sSelect .= $sWhere = "and ".$this->getViewName().".oxshopid = '".$myConfig->getShopId()."' ";
        }

        $iSize = $oDB->getOne( $sSelect);
        if ( $iStart < $iSize) {   // #387A creating object to fetch field information
            $oObj = oxNew( "oxbase" );
            $oObj->init($this->getViewName());

            // if first, delete the file
            $fp = fopen( $sFilepath, "a");

            $sSelect  = "select * from ".$this->getViewName()." ";
            $sSelect .= "left join oxobject2group on ".$this->getViewName().".oxid=oxobject2group.oxobjectid ";
            $sSelect .= "where oxobject2group.oxgroupsid in ($sInGroup) ".$sWhere;
            $rs = $oDB->selectLimit( $sSelect, $iLines, $iStart);

            // #573 defining decimal separator
            $blDecReplace = false;
            $sDecimalSeparator = $myConfig->getConfigParam( 'sDecimalSeparator' );
            if ( $sDecimalSeparator != "." ) {
                $blDecReplace = true;
            }

            while (!$rs->EOF) {
                $sLine = "\"".$this->getViewName()."\"";

                foreach ( $rs->fields as $iNum => $field) {
                    $sLine .= $myConfig->getConfigParam( 'sCSVSign' );

                    if ( !is_numeric( $field)) {   // #387A
                        $oFieldObj = null;
                        $aIdx2FldName = $oObj->getIdx2FldName();
                        if ( isset($aIdx2FldName[$iNum])) {
                            $sFieldName = $aIdx2FldName[$iNum];
                            //#1096S full copy instead of reference.
                            $oFieldObj = clone $oObj->$sFieldName;
                        }

                        $sLine .= "\"".$this->interForm($field, $oFieldObj)."\"";
                    } else {
                        if ( $blDecReplace) {
                            $field = str_replace( ".", $sDecimalSeparator, $field );
                        }
                        $sLine .= $field;
                    }
                }
                $sLine .= "\r\n";

                fputs( $fp, $sLine);

                $rs->moveNext();
            }

            fclose( $fp);
            return true;
        }

        return false;
    }

    /**
     * Performs Lexware export to file.
     *
     * @param integer $iStart    Start writing to file from line
     * @param integer $iLines    Amount of lines to write
     * @param string  $sFilepath Path to export file
     *
     * @return bool
     */
    public function exportLexwareArticles( $iStart, $iLines, $sFilepath)
    {
        $myConfig = $this->getConfig();
        $oDB      = oxDb::getDb();

        $sArticleTable = getViewName('oxarticles');

        $sSelect = "select count(oxid) from $sArticleTable ";
        $iSize = $oDB->getOne( $sSelect);

        if ( $iStart < $iSize) {
            $fp = fopen( $sFilepath, "ab");
            if ( !$iStart) {   // first time, write header
                fwrite( $fp, "\"Artikelnummer\";\"Bezeichnung\";\"Einheit\";\"Gewicht\";\"Matchcode\";\"Preis pro Anzahl\";\"Warengruppe\";\"Warengr.-Kurzbez.\";\"Warengr.-Steuersatz\";\"Warengr.-Konto Inland\";\"Warengr.-Konto Ausland\";\"Warengr.-Konto EG\";\"Preis 1\";\"Preis 2\";\"Preis 3\";\"Preis I/1\";\"Preis I/2\";\"Preis I/3\";\"Preis II/1\";\"Preis II/2\";\"Preis II/3\";\"Preis III/1\";\"Preis III/2\";\"Preis III/3\";\"B/N\";\"Lagerartikel\";\"EK 1\";\"Währung EK1\";\"EK 2\";\"Währung EK2\";\"Staffelmenge 1\";\"Staffelmenge 2\";\"Staffelmenge 3\";\"Lieferantennummer 1\";\"Lieferantennummer 2\";\"Bestellmenge Lf.1\";\"Bestellmenge Lf.2\";\"Bestellnr. Lf.1\";\"Bestellnr. Lf.2\";\"Lieferzeit Lf.1\";\"Lieferzeit Lf.2\";\"Lagerbestand\";\"Mindestbestand\";\"Lagerort\";\"Bestellte Menge\";\"Stückliste\";\"Internet\";\"Text\"\r\n");
            }
            $oldMode = $oDB->setFetchMode( ADODB_FETCH_ASSOC);
            $sSelect = "select * from $sArticleTable ";
            $rs = $oDB->selectLimit( $sSelect, $iLines, $iStart);
            $oDB->setFetchMode( $oldMode);

            while (!$rs->EOF) {
                $oArticle = oxNew( "oxarticle" );
                $blAdmin = $this->isAdmin();
                // TODO: this workaround should be overworked
                $this->setAdminMode( false );
                $oArticle->load( $rs->fields['OXID']);
                $this->setAdminMode( $blAdmin );

                $sSelect = "select oxtitle from oxarticles where oxid = '".$oArticle->oxarticles__oxparentid->value."'";
                $oTitle = $oDB->getOne( $sSelect);
                if ($oTitle != false && strlen ($oTitle)) {
                    $nTitle = $this->interForm($oTitle);
                } else {
                    $nTitle = $this->interForm($oArticle->oxarticles__oxtitle->value);
                }


                $sToFile = $oArticle->oxarticles__oxartnum->value            // Artikelnummer
                //.";".$this->interForm($oArticle->oxarticles__oxshortdesc->value." ".$oArticle->oxarticles__oxvarselect->value) // Bezeichnung
                .";".$nTitle." ".$this->interForm($oArticle->oxarticles__oxvarselect->value) // Bezeichnung
                .";"."Stueck"                        // Einheit
                .";".$oArticle->oxarticles__oxweight->value                  // Gewicht
                .";".$oArticle->oxarticles__oxartnum->value                  // Matchcode
                .";"."1,000"                         // Preis pro Anzahl
                .";"                                  // Warengruppe
                .";"                                  // Warengr.-Kurzbez.
                .";"                                 // Warengr.-Steuersatz
                .";"                                  // Warengr.-Konto Inland
                .";"                                  // Warengr.-Konto Ausland
                .";"                                  // Warengr.-Konto EG
                .";".number_format($oArticle->oxarticles__oxprice->value, 2, '.', '')  // Preis 1
                .";"                                  // Preis 2
                .";"                                 // Preis 3
                .";"                                 // Preis I/1
                .";"                                 // Preis I/2
                .";"                                  // Preis I/3
                .";"                                  // Preis II/1
                .";"                                  // Preis II/2
                .";"                                  // Preis II/3
                .";"                                  // Preis III/1
                .";"                                  // Preis III/2
                .";"                                  // Preis III/3
                .";"                           // B/N
                .";"                           // Lagerartikel
                //.";".number_format($oArticle->oxarticles__oxtprice->value, 2, '.', '')// EK 1
                // #343 fix
                .";".number_format($oArticle->oxarticles__oxbprice->value, 2, '.', '')// EK 1
                .";"                           // Währung EK1
                .";"                           // EK 2
                .";"                           // Währung EK2
                .";"                           // Staffelmenge 1
                .";"                           // Staffelmenge 2
                .";"                           // Staffelmenge 3
                .";"                           // Lieferantennummer 1
                .";"                           // Lieferantennummer 2
                .";"                           // Bestellmenge Lf.1
                .";"                           // Bestellmenge Lf.2
                .";"                           // Bestellnr. Lf.1
                .";"                           // Bestellnr. Lf.2
                .";"                           // Lieferzeit Lf.1
                .";"                           // Lieferzeit Lf.2
                .";".$oArticle->oxarticles__oxstock->value           // Lagerbestand
                .";"                           // Mindestbestand
                .";"                           // Lagerort
                .";"                           // Bestellte Menge
                .";"                           // Stückliste
                .";1"                              // Internet
                .";".$this->interForm( $oArticle->oxarticles__oxshortdesc->value.$oArticle->oxarticles__oxlongdesc->value)// Text
                .";";
                $sToFile .= "\r\n";

                fwrite( $fp, $sToFile);
                $rs->moveNext();
            }

            fclose( $fp );
            return true;
        }

        return false;

    }

    /**
     * Ensures, that the given data can be put in the csv
     *
     * @param string $nValue given string
     *
     * @return string
     */
    function interFormSimple( $nValue )
    {
        $nValue = str_replace( "\r", "", $nValue );
        $nValue = str_replace( "\n", " ", $nValue );
        $nValue = str_replace( '"', '""', $nValue );
        return $nValue;
    }

    /**
     * Replaces some special chars to HTML compatible codes, returns string
     * with replaced chars.
     *
     * @param string $nValue string to replace special chars
     * @param object $oObj   object
     *
     * @return string
     */
    function interForm( $nValue, $oObj = null)
    {   // thnx to Volker Dörk for this function and his help here

        // #387A skipping conversion for fields where info must be passed in original format
        $aFieldTypesToSkip = array("text", "oxshortdesc", "oxlongdesc");
        $blSkipStrpTags = false;
        if ( $oObj != null) {
            // using object field "fldtype", to skip processing because usually
            // this type of field is used for HTML text
            //
            // you may change field to "fldname" and add to $aFieldTypesToSkip
            // "oxlongdesc" value to skip only longdesc field
            //
            if ( in_array($oObj->fldtype, $aFieldTypesToSkip)) {
                $blSkipStripTags = true;
            } elseif ( in_array($oObj->fldname, $aFieldTypesToSkip)) {
                $blSkipStripTags = true;
            }
        }

        //removing simple & (and not  &uuml; chars)
        //(not full just a simple check for existing customers for cases like Johnson&Johnson)

        $oStr = getStr();
        if ( $oStr->strpos( $nValue, "&" ) !== false && $oStr->strpos($nValue, ";" ) == false ) {
            $nValue = str_replace("&", "&amp;", $nValue);
        }

        $nValue = str_replace( "&nbsp;", " ", $nValue);
        $nValue = str_replace( "&auml;", "ä", $nValue);
        $nValue = str_replace( "&ouml;", "ö", $nValue);
        $nValue = str_replace( "&uuml;", "ü", $nValue);
        $nValue = str_replace( "&Auml;", "Ä", $nValue);
        $nValue = str_replace( "&Ouml;", "Ö", $nValue);
        $nValue = str_replace( "&Uuml;", "Ü", $nValue);
        $nValue = str_replace( "&szlig;", "ß", $nValue);

        // usually & symbol goes (or should go) like that:
        // "& text...", so we predict that this is a rule
        // and replace it with special HTML code
        $nValue = str_replace( "& ", "&amp; ", $nValue);

        $nValue = str_replace( "\"", "'", $nValue);
        $nValue = str_replace( "(", "'", $nValue);
        $nValue = str_replace( ")", "'", $nValue);
        $nValue = str_replace( "\r\n", "", $nValue);
        $nValue = str_replace( "\n", "", $nValue);

        if ( !$blSkipStripTags) {
            $nValue = strip_tags( $nValue );
        }

        return $nValue;
    }

    /**
     * Returns formatted price (grouped thousands, etc.).
     *
     * @param float $nPrice Price to format
     *
     * @return string
     */
    function internPrice( $nPrice)
    {  // thnx to Volker Dörk for this function and his help here
        $nPrice = $this->interForm($nPrice);
        $nPrice = number_format($nPrice, 2, '.', '');
        return $nPrice;
    }

    /**
     * Performs CSV format file reading and parsing, returns true on success.
     *
     * @param integer $iStart    Start reading from
     * @param integer $iLines    Read number of lines
     * @param string  $sFilepath Path to file
     *
     * @deprecated
     *
     * @return bool
     */
    function import( $iStart, $iLines, $sFilepath)
    {
        $myConfig  = $this->getConfig();
        $blContinue = true;

        $fp     = fopen( $sFilepath, "r");
        $iEnd   = $iStart+$iLines;
        $aData  = null;

        //array of tables whitch were updated
        $aProcTables = oxSession::getVar("_aProcTables");
        if ( !$aProcTables) {
            $aProcTables = array();
        }

        // #573 defining decimal separator
        $blDecReplace = false;
        $sDecimalSeparator = $myConfig->getConfigParam( 'sDecimalSeparator' );
        if ( $sDecimalSeparator != "." ) {
            $blDecReplace = true;
        }

        for ( $i = 0; $i<=$iEnd; $i++) {
            $aData = $this->_oxFGetCsv( $fp, 40960, $myConfig->getConfigParam( 'sCSVSign' ) );

            if ( $aData && $i >= $iStart) {   // import

                // read table description if needed
                $sTable = $aData[0];
                $aProcTables[] = $sTable;
                $this->init( $sTable);
                // remove table from line
                $aData = array_splice( $aData, 1, count($aData)-1);
                foreach ($aData as $key => $value) {
                    if ( $value == "''" || $value == "" || !$value) {
                        $value = null;
                    }

                    // #573 - fixing import
                    $sKey = $this->_aIdx2FldName[$key];
                    if ( $blDecReplace && $this->$sKey->fldtype == "double") {
                        $value = str_replace( $sDecimalSeparator, ".", $value );
                    }

                    $aData[$key] = trim($value);
                }
                $this->assign($aData);
                $this->save();
            }

            if ( feof( $fp)) {
                $blContinue = false;
                if ( $sTable == "oxcategories" || in_array( "oxcategories", $aProcTables)) {
                    $oDB = oxDb::getDb();
                    $oDB->execute( "update oxcategories set oxhidden = '0' where oxhidden='' ");
                }
                $aProcTables = array();
                break;
            }
        }
        oxSession::setVar( "_aProcTables", $aProcTables);
        fclose( $fp);

        return $blContinue;
    }

    /**
     * Returns XML compatible text for LexwareOrders export.
     *
     * @param integer $iFromOrderNr Order from (default null)
     * @param integer $iToOrderNr   Order number
     *
     * @return string
     */
    function exportLexwareOrders( $iFromOrderNr = null, $iToOrderNr = null)
    {
        // thnx to Volker Dörk for this function and his help here
        $myConfig = $this->getConfig();

        $sRet = "";

        $sNewLine = "\r\n";

        $oOrderlist = oxNew( "oxlist" );
        $oOrderlist->init( "oxorder" );

        $sSelect = "select * from oxorder where 1 ";

        if ( !empty( $iFromOrderNr)) {
            $sSelect .= "and oxordernr >= $iFromOrderNr ";
        }
        if ( !empty( $iToOrderNr)) {
            $sSelect .= "and oxordernr <= $iToOrderNr ";
        }

        $oOrderlist->selectString( $sSelect);

        if ( !$oOrderlist->count() ) {
            return null;
        }

        $sExport  = "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>$sNewLine";
        $sExport .= "<Bestellliste>$sNewLine";
        $sRet .= $sExport;

        //foreach (array_keys( $oOrderlist) as $key) {
        foreach ($oOrderlist->arrayKeys() as $key) {
            $oOrder = $oOrderlist[$key];

            $oUser = oxNew( "oxuser" );
            $oUser->load( $oOrder->oxorder__oxuserid->value);

            $sExport  = "<Bestellung zurückgestellt=\"Nein\" bearbeitet=\"Nein\" übertragen=\"Nein\">$sNewLine";
            $sExport .= "<Bestellnummer>".$oOrder->oxorder__oxordernr->value."</Bestellnummer>$sNewLine";
            $sExport .= "<Standardwaehrung>978</Standardwaehrung>$sNewLine";
            $sExport .= "<Bestelldatum>$sNewLine";
            $sDBDate = oxUtilsDate::getInstance()->formatDBDate($oOrder->oxorder__oxorderdate->value);
            $sExport .= "<Datum>".substr($sDBDate, 0, 10)."</Datum>$sNewLine";
            $sExport .= "<Zeit>".substr($sDBDate, 11, 8)."</Zeit>$sNewLine";
            $sExport .= "</Bestelldatum>$sNewLine";
            $sExport .= "<Kunde>$sNewLine";

            $sExport .= "<Kundennummer>"./*$this->interForm($oUser->oxuser__oxcustnr->value).*/"</Kundennummer>$sNewLine";
            $sExport .= "<Firmenname>".$this->interForm($oOrder->oxorder__oxbillcompany->value)."</Firmenname>$sNewLine";
            $sExport .= "<Vorname>".$this->interForm($oOrder->oxorder__oxbillfname->value)."</Vorname>$sNewLine";
            $sExport .= "<Name>".$this->interForm($oOrder->oxorder__oxbilllname->value)."</Name>$sNewLine";
            $sExport .= "<Strasse>".$this->interForm($oOrder->oxorder__oxbillstreet->value)." ".$this->interForm($oOrder->oxorder__oxbillstreetnr->value)."</Strasse>$sNewLine";
            $sExport .= "<PLZ>".$this->interForm($oOrder->oxorder__oxbillzip->value)."</PLZ>$sNewLine";
            $sExport .= "<Ort>".$this->interForm($oOrder->oxorder__oxbillcity->value)."</Ort>$sNewLine";
            $sExport .= "<Bundesland>".""."</Bundesland>$sNewLine";
            $sExport .= "<Land>".$this->interForm($oOrder->oxorder__oxbillcountry->value)."</Land>$sNewLine";
            $sExport .= "<Email>".$this->interForm($oUser->oxuser__oxusername->value)."</Email>$sNewLine";
            $sExport .= "<Telefon>".$this->interForm($oOrder->oxorder__oxbillfon->value)."</Telefon>$sNewLine";
            $sExport .= "<Telefon2>".$this->interForm($oUser->oxuser__oxprivfon->value)."</Telefon2>$sNewLine";
            $sExport .= "<Fax>".$this->interForm($oOrder->oxorder__oxbillfax->value)."</Fax>$sNewLine";

            // lieferadresse
            if ( $oOrder->oxorder__oxdellname->value) {
                $sDelComp   = $oOrder->oxorder__oxdelcompany->value;
                $sDelfName  = $oOrder->oxorder__oxdelfname->value;
                $sDellName  = $oOrder->oxorder__oxdellname->value;
                $sDelStreet = $oOrder->oxorder__oxdelstreet->value;
                $sDelZip    = $oOrder->oxorder__oxdelzip->value;
                $sDelCity   = $oOrder->oxorder__oxdelcity->value;
                $sDelCountry= $oOrder->oxorder__oxdelcountry->value;
            } else {
                $sDelComp   = "";
                $sDelfName  = "";
                $sDellName  = "";
                $sDelStreet = "";
                $sDelZip    = "";
                $sDelCity   = "";
                $sDelCountry= "";
            }

            $sExport .= "<Lieferadresse>$sNewLine";
            $sExport .= "<Firmenname>".$this->interForm($sDelComp)."</Firmenname>$sNewLine";
            $sExport .= "<Vorname>".$this->interForm($sDelfName)."</Vorname>$sNewLine";
            $sExport .= "<Name>".$this->interForm($sDellName)."</Name>$sNewLine";
            $sExport .= "<Strasse>".$this->interForm($sDelStreet)."</Strasse>$sNewLine";
            $sExport .= "<PLZ>".$this->interForm($sDelZip)."</PLZ>$sNewLine";
            $sExport .= "<Ort>".$this->interForm($sDelCity)."</Ort>$sNewLine";
            $sExport .= "<Bundesland>".""."</Bundesland>$sNewLine";
            $sExport .= "<Land>".$this->interForm($sDelCountry)."</Land>$sNewLine";
            $sExport .= "</Lieferadresse>$sNewLine";
            $sExport .= "<Matchcode>".$this->interForm($oOrder->oxorder__oxbilllname->value).", ".$this->interForm($oOrder->oxorder__oxbillfname->value)."</Matchcode>$sNewLine";

            // ermitteln ob steuerbar oder nicht
            $sCountry = strtolower( $oUser->oxuser__oxcountryid->value);
            $aHomeCountry = $myConfig->getConfigParam( 'aHomeCountry' );
            if ( is_array( $aHomeCountry ) && in_array( $sCountry, $aHomeCountry ) ) {
                $sSteuerbar = "ja";
            } else {
                $sSteuerbar = "nein";
            }

            $sExport .= "<fSteuerbar>".$this->interForm($sSteuerbar)."</fSteuerbar>$sNewLine";
            $sExport .= "</Kunde>$sNewLine";
            $sExport .= "<Artikelliste>$sNewLine";
            $sRet .= $sExport;

            $dSumNetPrice = 0;
            $dSumBrutPrice = 0;

            /*
            if( $oOrder->oxorder__oxdelcost->value)
            {   // add virtual article for delivery costs
                $oDelCost = oxNew( "oxorderarticle" );
                $oDelCost->oxorderarticles__oxvat->setValue(0);
                $oDelCost->oxorderarticles__oxnetprice->setValue($oOrder->oxorder__oxdelcost->value);
                $oDelCost->oxorderarticles__oxamount->setValue(1);
                $oDelCost->oxorderarticles__oxtitle->setValue("Versandkosten");
                $oDelCost->oxorderarticles__oxbrutprice->setValue($oOrder->oxorder__oxdelcost->value);
                $oOrder->oArticles['oxdelcostid'] = $oDelCost;
            }*/

            $oOrderArticles = $oOrder->getOrderArticles();
            foreach ($oOrderArticles->arrayKeys() as $key) {
                $oOrderArt = $oOrderArticles->offsetGet($key);

                $dVATSet = array_search( $oOrderArt->oxorderarticles__oxvat->value, $myConfig->getConfigParam( 'aLexwareVAT' ) );
                $sExport  = "   <Artikel>$sNewLine";
                //$sExport .= "   <Artikelzusatzinfo><Nettostaffelpreis>".$this->InternPrice( $oOrderArt->oxorderarticles__oxnetprice->value)."</Nettostaffelpreis></Artikelzusatzinfo>$sNewLine";
                $sExport .= "   <Artikelzusatzinfo><Nettostaffelpreis></Nettostaffelpreis></Artikelzusatzinfo>$sNewLine";
                $sExport .= "   <SteuersatzID>".$dVATSet."</SteuersatzID>$sNewLine";
                $sExport .= "   <Steuersatz>".$this->internPrice($oOrderArt->oxorderarticles__oxvat->value/100)."</Steuersatz>$sNewLine";
                $sExport .= "   <Artikelnummer>".$oOrderArt->oxorderarticles__oxartnum->value."</Artikelnummer>$sNewLine";
                $sExport .= "   <Anzahl>".$oOrderArt->oxorderarticles__oxamount->value."</Anzahl>$sNewLine";
                $sExport .= "   <Produktname>".$this->interForm( $oOrderArt->oxorderarticles__oxtitle->value);
                if ( $oOrderArt->oxorderarticles__oxselvariant->value) {
                    $sExport .= "/".$oOrderArt->oxorderarticles__oxselvariant->value;
                }
                $sExport .= "   </Produktname>$sNewLine";
                $sExport .= "   <Rabatt>0.00</Rabatt>$sNewLine";
                $sExport .= "   <Preis>".$this->internPrice($oOrderArt->oxorderarticles__oxbrutprice->value/$oOrderArt->oxorderarticles__oxamount->value)."</Preis>$sNewLine";
                $sExport .= "   </Artikel>$sNewLine";
                $sRet .= $sExport;

                $dSumNetPrice   += $oOrderArt->oxorderarticles__oxnetprice->value;
                $dSumBrutPrice  += $oOrderArt->oxorderarticles__oxbrutprice->value;
            }

            $dDiscount = $oOrder->oxorder__oxvoucherdiscount->value + $oOrder->oxorder__oxdiscount->value;
            $sExport  = "<GesamtRabatt>".$this->internPrice( $dDiscount)."</GesamtRabatt>$sNewLine";
            $sExport .= "<GesamtNetto>".$this->internPrice($dSumNetPrice)."</GesamtNetto>$sNewLine";
            $sExport .= "<Lieferkosten>".$this->internPrice($oOrder->oxorder__oxdelcost->value)."</Lieferkosten>$sNewLine";
            $sExport .= "<Zahlungsartkosten>0.00</Zahlungsartkosten>$sNewLine";
            $sExport .= "<GesamtBrutto>".$this->internPrice($dSumBrutPrice)."</GesamtBrutto>$sNewLine";

            $oUserpayment = oxNew( "oxuserpayment" );
            $oUserpayment->load( $oOrder->oxorder__oxpaymentid->value);
            $sPayment = $oUserpayment->oxuserpayments__oxvalue->value;
            $sPayment = str_replace( "__", "", $sPayment);
            $sPayment = str_replace( "@@", ",", $sPayment);

            $oPayment = oxNew( "oxpayment" );
            $oPayment->load( $oOrder->oxorder__oxpaymenttype->value);


            $sExport .= "<Bemerkung>".strip_tags( $oOrder->oxorder__oxremark->value)."</Bemerkung>$sNewLine";
            $sRet .= $sExport;

            $sExport  = "</Artikelliste>$sNewLine";

            $sExport .= "<Zahlung>$sNewLine";
            $oPayment = oxNew( "oxpayment" );
            $oPayment->load( $oOrder->oxorder__oxpaymenttype->value);

            //print_r($oPayment);

            $sExport .= "<Art>".$oPayment->oxpayments__oxdesc->value."</Art>$sNewLine";
            $sExport .= "</Zahlung>$sNewLine";

            $sExport .= "</Bestellung>$sNewLine";
            $sRet .= $sExport;


            $oOrder->oxorder__oxexport->setValue(1);
            $oOrder->save();

        }
        $sExport = "</Bestellliste>$sNewLine";
        $sRet .= $sExport;

        return $sRet;
    }

    /**
     * CSV file parser. Returns an array of parsed values.
     *
     * @param mixed   $fp      Resource to file
     * @param integer $iMaxLen Max file line length
     * @param string  $sSep    parameter/value separator
     *
     * @deprecated
     *
     * @return array
     */
    protected function _oxFGetCsv( $fp, $iMaxLen, $sSep )
    {
        $aRet = null;

        $iField = 0;
        $iQuote = 0;

        for ( $i=0; $i<$iMaxLen; $i++) {
            $c = fread( $fp, 1);

            if ( ($c === false || !isset( $c)) || (($c == "\n") && !$iQuote)) {
                break;  // end
            } elseif ( $c == $sSep && !$iQuote) {
                $iField++;
                $aRet[$iField] = "";
                continue;
            } elseif ( $c == "\"") {
                if ( $iQuote) {
                    $iQuote--;
                } else {
                    $iQuote++;
                }
            }
            if ( !isset( $aRet[$iField])) {
                $aRet[$iField] = "";
            }
            $aRet[$iField] .= $c;
        }

        if ( count( $aRet) > 1) {
            $oStr = getStr();
            // remove " or '
            foreach ( $aRet as $key => $sField) {
                $sField = trim($sField);
                if ( $sField) {
                    if ( $sField[0] == "\"" || $sField[0] == "'") {
                        $sField = $oStr->substr( $sField, 1);
                    }

                    $iLen = $oStr->strlen( $sField) - 1;
                    if ( $sField[$iLen] == "\"" || $sField[$iLen] == "'") {
                        $sField = $oStr->substr( $sField, 0, $iLen);
                    }

                    $aRet[$key] = $sField;
                }
            }
            // process "" qoutes
            return str_replace('""', '"', $aRet);
        } else {
            return null;
        }
    }
}
