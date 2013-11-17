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
 * @package setup
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: index.php 23384 2009-10-20 12:58:12Z vilma $
 */


error_reporting( (E_ALL ^ E_NOTICE) | E_STRICT );

global $aSetupSteps;
$aSetupSteps['STEP_SYSTEMREQ']  = 100;      // 0
$aSetupSteps['STEP_WELCOME']    = 200;      // 1
$aSetupSteps['STEP_LICENSE']    = 300;      // 2
$aSetupSteps['STEP_DB_INFO']    = 400;      // 3
$aSetupSteps['STEP_DB_CONNECT'] = 410;      // 31
$aSetupSteps['STEP_DB_CREATE']  = 420;      // 32
$aSetupSteps['STEP_DIRS_INFO']  = 500;      // 4
$aSetupSteps['STEP_DIRS_WRITE'] = 510;      // 41
$aSetupSteps['STEP_FINISH'] = 700;          // 6

ob_start();

$sVerPrefix = '';

    $sVerPrefix = '_ce';


    $sBaseShopId = 'oxbaseshop';

// Session Handling
$sSID = null;
if ( isset( $_GET['sid'] ) ) {
    $sSID = $_GET['sid'];
} elseif ( isset( $_POST['sid'] ) ) {
    $sSID = $_POST['sid'];
}

// creating array to store persistent data
global $aPersistentData;
$aPersistentData = array();

//decoding data from "sid" variable
if ( isset( $sSID ) && strlen( $sSID ) ) {
    $aSIDData = base64_decode( $sSID );
    if ( $aSIDData !== false ) {
        // unserializing persistent data
        $aPersistentData = unserialize( $aSIDData );
    }
}
$sSetupLang = getSetupLang();
include_once $sSetupLang . '/lang.php';

//storring country value settings to session
if ( isset( $_POST['country_lang'] ) ) {
    // store to session
    $aPersistentData['country_lang'] = $_POST['country_lang'];
}

//storring dyn pages settings to session
if ( isset( $_POST['use_dynamic_pages'] ) ) {
    // store to session
    $aPersistentData['use_dynamic_pages'] = $_POST['use_dynamic_pages'];
}

//storring dyn pages settings to session
if ( isset( $_POST['check_for_updates'] ) ) {
    // store to session
    $aPersistentData['check_for_updates'] = $_POST['check_for_updates'];
}

// startup
if ( isset( $_GET['istep'] ) && $_GET['istep'] ) {
    $istep = $_GET['istep'];
} elseif ( isset( $_POST['istep'] ) && $_POST['istep'] ) {
    $istep = $_POST['istep'];
} else {
    $istep = $aSetupSteps['STEP_SYSTEMREQ'];
}

// store eula to session
if ( isset( $_POST['iEula'] ) ) {
    // store to session
    $aPersistentData['eula'] = $iEula = $_POST['iEula'];
} elseif ( isset( $aPersistentData['eula'] ) ) {
    $iEula = $aPersistentData['eula'];
} else {
    $iEula = 0;
}

// routing table
if ( !$iEula && $istep > $aSetupSteps['STEP_LICENSE'] ) {
    $istep = $aSetupSteps['STEP_FINISH'];
    $sMessage = $aLang['ERROR_SETUP_CANCELLED'];
    include "headitem.php";
    ?>
    </br></br>
    <form action="index.php" method="post">
    <input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
    <input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_WELCOME']; ?>">
    <input type="submit" id="step0Submit" class="edittext" value="<?php echo( $aLang['BUTTON_START_INSTALL'] ) ?>">
    </form>
    <?php
    include "bottomitem.php";
    exit();
}


function getSetupLang()
{
    global $aPersistentData, $aSetupSteps;

    $aLangs = array( 'en', 'de' );

    $sBrowserLang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    $sBrowserLang = ( in_array($sBrowserLang, $aLangs) ) ? $sBrowserLang : $aLangs[0];

    if ( !empty($_POST['setup_lang']) ) {
        $aPersistentData['setup_lang'] = $_POST['setup_lang'];
        if (!empty($_POST['setup_lang_submit'])) {
            //updating setup language, so disabling redirect to next step, just reloading same step
            $_GET['istep'] = $_POST['istep'] = $aSetupSteps['STEP_WELCOME'];
        }
    } elseif ( empty($aPersistentData['setup_lang'])  ) {
        $aPersistentData['setup_lang'] = $sBrowserLang;
    }

    return $aPersistentData['setup_lang'];
}

function checkFileOrDirectory( $sPath )
{
    global $aLang, $aSetupSteps;

    $sMessage = "";
    if ( !file_exists( $sPath) ) {
        global $iRedir2Step;
        $iRedir2Step = $aSetupSteps['STEP_DIRS_INFO'];
        $sMessage .= sprintf( $aLang['ERROR_NOT_AVAILABLE'], $sPath ) . "<br>";
        return $sMessage;
    }
    if ( !@chmod( $sPath, 0755)) {
        global $iRedir2Step;
        $iRedir2Step = $aSetupSteps['STEP_DIRS_INFO'];
        $sMessage .= sprintf( $aLang['ERROR_CHMOD'], $sPath ) . "<br>";
    }
    if ( !is_writable( $sPath) ) {
        global $iRedir2Step;
        $iRedir2Step = $aSetupSteps['STEP_DIRS_INFO'];
        $sMessage .= sprintf( $aLang['ERROR_NOT_WRITABLE'], $sPath ) . "<br>";
        return $sMessage;
    }
    return;
}

function ParseQuery( $sSQL)
{   // parses query into single pieces
    $aRet       = array();
    $blComment  = false;
    $blQuote    = false;
    $sThisSQL   = "";

    $aLines = explode( "\n", $sSQL);

    // parse it
    foreach ( $aLines as $sLine) {
        $iLen = strlen( $sLine);
        for ( $i = 0; $i < $iLen; $i++) {
            if ( !$blQuote && ( $sLine[$i] == '#' || ( $sLine[0] == '-' && $sLine[1] == '-')))
                $blComment = true;
            // add this char to current command
            if ( !$blComment)
                $sThisSQL .= $sLine[$i];
            // test if quote on
            if ( ($sLine[$i] == '\'' && $sLine[$i-1] != '\\') )
                $blQuote = !$blQuote;   // toggle
            // now test if command end is reached
            if ( !$blQuote && $sLine[$i] == ';') {
                // add this
                $sThisSQL = trim( $sThisSQL);
                if ( $sThisSQL) {
                    $sThisSQL = str_replace( "\r", "", $sThisSQL);
                    $aRet[] = $sThisSQL;
                }
                $sThisSQL = "";
            }
        }
        // comments and quotes can't run over newlines
        $blComment  = false;
        $blQuote    = false;
    }

    return $aRet;
}

function OpenDatabase( $aDB)
{
    global $aLang, $aSetupSteps;

    // ok open DB
    $oDB = @mysql_connect( $aDB['dbHost'], $aDB['dbUser'], $aDB['dbPwd']);
    if ( !$oDB) {
        $iRedir2Step = $aSetupSteps['STEP_DB_INFO'];
        $sMessage = $aLang['ERROR_DB_CONNECT'] . " - " . mysql_error();
        include("headitem.php");
        include("bottomitem.php");
        exit();
    }
    @mysql_select_db( $aDB['dbName'], $oDB);

    return $oDB;
}

function QueryFile( $sFilename, $aDB)
{
    global $aLang, $aSetupSteps;

    $sProblems= "";

    $fp = @fopen( $sFilename, "r");
    if ( !$fp) {
        // problems with file
        $iRedir2Step = $aSetupSteps['STEP_DB_INFO'];
        $sMessage = sprintf( $aLang['ERROR_OPENING_SQL_FILE'], $sFilename );
        include("headitem.php");
        include("bottomitem.php");
        exit();
    }

    $sQuery = fread ($fp, filesize( $sFilename));
    fclose ($fp);

    $aQueries = ParseQuery( $sQuery);

    $sDBVersion = GetDatabaseVersion($aDB);
    $oDB = OpenDatabase( $aDB);

    if ( version_compare($sDBVersion, "5")>0) {
        //disable STRICT db mode if there are set any (mysql >= 5).
        mysql_query("SET @@session.sql_mode = ''", $oDB);
    }

    // P
    /*
    if ( version_compare($sDBVersion, "4.1.1")>0) {
        //set default charset (mysql >= 4.1.1).
        mysql_query("ALTER DATABASE ".$aDB['dbName']." CHARACTER SET latin1 COLLATE latin1_general_ci;", $oDB);
    }
    */

    $sProblems = "";
    foreach ( $aQueries as $sQuery) {
        if ( !mysql_query( $sQuery, $oDB)) {
            $sProblems .= $sQuery . "<br>";
        }
    }

    return $sProblems;
}

function GetDatabaseVersion($aDB)
{
    $oDB = OpenDatabase( $aDB);
    $rRecords = mysql_query("SHOW VARIABLES LIKE 'version'", $oDB);
    $aRow = mysql_fetch_row($rRecords);

    return $aRow[1];
}

function AlreadySetUp()
{
    global $sVerPrefix;
return false;
    $sConfig = join("", file("../config.inc.php"));
    if ( strpos($sConfig, "<dbHost$sVerPrefix>") === false)
        return true;
    return false;
}

function generateUID()
{
    $suID = substr( session_id(), 0, 3) . uniqid( "", true);

    return $suID;
}

function getSID()
{   global $aPersistentData;

    $sPersData = serialize( $aPersistentData);
    return base64_encode( $sPersData);
}

function removeDir( $sPath, $blDeleteSuccess)
{

    // setting path to remove
    $d = dir( $sPath);
    $d->handle;
    while (false !== ($entry = $d->read())) {

        if ( $entry != "." &&  $entry != "..") {

            $sFilePath = $sPath."/".$entry;

            if ( is_file($sFilePath)) {
                // setting file status deletable
                $blThisChMod = is_writable($sFilePath) ? true : @chmod( $sFilePath, 0755);
                //deleting file if possible
                if ( $blThisChMod) $blThisChMod = @unlink ( $sFilePath);
                // setting global deletion status
                $blDeleteSuccess = $blDeleteSuccess * $blThisChMod;
            } elseif ( is_dir($sFilePath)) {
                // removing direcotry contents
                removeDir( $sFilePath, $blDeleteSuccess);
                // setting directory status deletable
                $blThisChMod = is_writable($sFilePath) ? true : @chmod( $sFilePath, 0755);
                //deleting directory if possible
                if ( $blThisChMod) $blThisChMod = @rmdir ( $sFilePath);
                // setting global deletion status
                $blDeleteSuccess = $blDeleteSuccess * $blThisChMod;
            } else  // there are some other objects ?
                $blDeleteSuccess = $blDeleteSuccess * false;
        }

    }
    $d->close();

    return $blDeleteSuccess;
}

function saveDynPagesSettings()
{
    global $aPersistentData;
    global $sVerPrefix;

    $oConfk = new Conf();

        $sBaseOut = 'oxbaseshop';
        // disabling usage of dynamic pages if shop country is international
        if (empty($aPersistentData['country_lang'])) {
            $aPersistentData['use_dynamic_pages'] = 'false';
        }

    $sID1 = generateUID();

    $sQConfDelete1 = "delete from oxconfig where oxvarname = 'blLoadDynContents'";
    $sQConfInsert1 = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue)
                             values('$sID1', '$sBaseOut', 'blLoadDynContents', 'bool', ENCODE( '".$aPersistentData['use_dynamic_pages']."', '".$oConfk->sConfigKey."'))";

    $sID2 = generateUID();

    $sQConfDelete2 = "delete from oxconfig where oxvarname = 'sShopCountry'";
    $sQConfInsert2 = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue)
                             values('$sID2', '$sBaseOut', 'sShopCountry', 'str', ENCODE( '".$aPersistentData['country_lang']."', '".$oConfk->sConfigKey."'))";

    $sID3 = generateUID();

    $sQConfDelete3 = "delete from oxconfig where oxvarname = 'blCheckForUpdates'";
    $sQConfInsert3 = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue)
                             values('$sID3', '$sBaseOut', 'blCheckForUpdates', 'bool', ENCODE( '".$aPersistentData['check_for_updates']."', '".$oConfk->sConfigKey."'))";

    mysql_query($sQConfDelete1);
    mysql_query($sQConfInsert1);
    mysql_query($sQConfDelete2);
    mysql_query($sQConfInsert2);
    mysql_query($sQConfDelete3);
    mysql_query($sQConfInsert3);
}

function setMySqlCollation( $iUtfMode )
{
    $aCollation = array();
    if ($iUtfMode) {
        $aCollation[] = "ALTER SCHEMA CHARACTER SET utf8 COLLATE utf8_general_ci";
        $aCollation[] = "set names 'utf8'";
        $aCollation[] = "set character_set_database=utf8";
        $aCollation[] = "SET CHARACTER SET latin1";
        $aCollation[] = "SET CHARACTER_SET_CONNECTION = utf8";
        $aCollation[] = "SET character_set_results = utf8";
        $aCollation[] = "SET character_set_server = utf8";
    } else {
        $aCollation[] = "ALTER SCHEMA CHARACTER SET latin1 COLLATE latin1_general_ci";
        $aCollation[] = "SET CHARACTER SET latin1";
    }

    foreach( $aCollation as $sSql ) {
        mysql_query( $sSql ) or die(mysql_error());
    }
}

function convertConfigTableToUtf()
{
   $oConfk = new Conf();

   $sSql = "SELECT oxvarname, oxvartype, DECODE( oxvarvalue, '".$oConfk->sConfigKey."') AS oxvarvalue FROM oxconfig WHERE oxvartype IN ('str', 'arr', 'aarr') ";
   $aRes = mysql_query( $sSql ) or die(mysql_error());

   $aConverted = array();

   while ( $aRow = mysql_fetch_assoc($aRes) ) {

       if ( $aRow['oxvartype'] == 'arr' || $aRow['oxvartype'] == 'aarr' ) {
           $aRow['oxvarvalue'] = unserialize( $aRow['oxvarvalue'] );
       }

       $aRow['oxvarvalue'] = convertToUtf8( $aRow['oxvarvalue'] );

       $aConverted[] = $aRow;
   }

   foreach ( $aConverted as $sKey => $sValue ) {

       if ( is_array($sValue['oxvarvalue']) ) {
           $sVarValue = mysql_real_escape_string(serialize($sValue['oxvarvalue']));
       } else {
           $sVarValue = is_string($sValue['oxvarvalue']) ? mysql_real_escape_string($sValue['oxvarvalue']) : $sValue['oxvarvalue'];
       }

       $sSql = "UPDATE oxconfig SET oxvarvalue = ENCODE( '".$sVarValue."', '".$oConfk->sConfigKey."') WHERE oxvarname = '" . $sValue['oxvarname'] . "'; ";
       mysql_query( $sSql ) or die(mysql_error());
   }
}

function convertToUtf8( $data )
{
    if ( is_array($data) ) {

        $aKeys = array_keys( $data );
        $aValues = array_values( $data );

        //converting keys
        if (count($data) > 1 ) {
            foreach ( $aKeys as $sKeyIndex => $sKeyValue ) {
                if ( is_string($sKeyValue) ) {
                    $aKeys[$sKeyIndex] = iconv( 'iso-8859-15', 'utf-8', $sKeyValue );
                }
            }

            $data = array_combine( $aKeys, $aValues );

            //converting values
            foreach ( $data as $sKey => $sValue ) {
                if ( is_array($sValue) ) {
                    convertToUtf8( $sValue );
                }

                if ( is_string($sValue) ) {
                    $data[$sKey] = iconv( 'iso-8859-15', 'utf-8', $sValue );
                }
            }
        }
    } else {
        $data = iconv( 'iso-8859-15', 'utf-8', $data );
    }

    return $data;
}

class Conf
{
    function Conf()
    {
        require("../core/oxconfk.php");
    }
}

/**
 * Translates module name
 *
 * @param string $sModuleName name of module
 *
 * @return string
 */
function getModuleName( $sModuleName )
{
    global $aLang;
    return $aLang['MOD_'.strtoupper( $sModuleName ) ];
}

/**
 * $iModuleState - module status:
 * -1 - unable to datect, should not block
 *  0 - missing, blocks setup
 *  1 - fits min requirements
 *  2 - exists required or better
 *
 * @param string $sModuleName name of module
 *
 * @return string
 */
function getModuleClass( $iModuleState )
{
    switch ( $iModuleState ) {
        case 2:
            $sClass = 'pass';
            break;
        case 1:
            $sClass = 'pmin';
            break;
        case -1:
            $sClass = 'null';
            break;
        default:
            $sClass = 'fail';
            break;
    }
    return $sClass;
}

// startpage, licence
if ( $istep == $aSetupSteps['STEP_SYSTEMREQ'] ) {

    // ---------------------------------------------------------
    // WELCOME
    // ---------------------------------------------------------
    $title = $aLang['STEP_0_TITLE'];
    include "headitem.php";
?>
<strong><?php echo( $aLang['STEP_0_DESC'] ) ?></strong><br><br>

<table cellpadding="1" cellspacing="0">
    <tr>
        <td nowrap><?php echo($aLang['SELECT_SETUP_LANG']) ?>: </td>
        <td>
            <form action="index.php" id="langSelectionForm" method="post">
            <select name="setup_lang" onChange="document.getElementById('langSelectionForm').submit();" style="font-size: 11px;">
                <option value="en">English</option>
                <option value="de" <?php if ($aPersistentData['setup_lang'] == 'de') echo 'selected'; ?>>Deutsch</option>
            </select>
            <noscript>
            <input type="submit" name="setup_lang_submit" value="<?php echo($aLang['SELECT_SETUP_LANG_SUBMIT']) ?>" style="font-size: 11px;">
            </noscript>
            <input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
            <input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_SYSTEMREQ']; ?>">
            </form>
        </td>
    </tr>
</table>
<br>

    <ul class="req">
    <?php
    $blContinue = true;
    require_once "../core/oxsysrequirements.php";
    $oSysReq = new oxSysRequirements();
    $aInfo = $oSysReq->getSystemInfo();
    foreach ( $aInfo as $sGroup => $aModules ) {
        // translating
        $sGroupName  = getModuleName( $sGroup );
        echo "<li class='group'>{$sGroupName}<ul>";
        foreach ( $aModules as $sModule => $iModuleState ) {
            // translating
            $sModuleName = getModuleName( $sModule );
            $sClass = getModuleClass( $iModuleState );
            $blContinue = $blContinue && ( bool ) abs( $iModuleState );
            echo "<li id=\"$sModule\" class=\"{$sClass}\">{$sModuleName}</li>\n";
        }
        echo "</ul></li>";
    }
    ?>
    <li class="clear"></li></ul>

    <?php echo( $aLang['STEP_0_TEXT'] ) ?>
    <br><br>

    <?php if ( $blContinue ) { ?>
    <form action="index.php" method="post">
    <input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
    <input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_WELCOME']; ?>">
    <input type="submit" id="step0Submit" class="edittext" value="<?php echo( $aLang['BUTTON_PROCEED_INSTALL'] ) ?>">
    </form>
    <?php } else {
              echo '<b>',$aLang['STEP_0_ERROR_TEXT'],'</b>';
          }
// startpage, licence
}


if ( $istep == $aSetupSteps['STEP_WELCOME'] ) {
    // ---------------------------------------------------------
    // WELCOME
    // ---------------------------------------------------------
    $title = $aLang['STEP_1_TITLE'];
    include "headitem.php";
    include "../admin/shop_countries.php";


    //setting admin area default language
    if ( $aPersistentData['setup_lang'] == 'en') {
        $iAdminLang = 1;
    } else {
        $iAdminLang = 0;
    }

    setcookie("oxidadminlanguage", $iAdminLang, time()+31536000, "/");

?>
<script>
    function showPopUp(url,w,h,r){
        if (url !== null && url.length > 0) {
            var iLeft = (window.screen.width - w)/2;
            var iTop = (window.screen.height - h)/2;
            var _cfg = "status=yes,scrollbars=no,menubar=no,top="+iTop+",left="+iLeft+",width="+w+",height="+h+(r?",resizable=yes":"");
            window.open(url, "_blank", _cfg);
        }
    }

    function update_dynpages_checkbox() {
        sValue = document.forms[0].country_lang.value;
        if ( sValue == '' ) {
           document.getElementById('use_dynamic_pages_ckbox').style.display = 'none';;
           document.getElementById('use_dynamic_pages_desc').style.display = 'none';;

        } else {
           document.getElementById('use_dynamic_pages_ckbox').style.display = '';;
           document.getElementById('use_dynamic_pages_desc').style.display = '';;
        }
    }


</script>

<strong><?php echo( $aLang['STEP_1_DESC'] ) ?></strong><br>
<br>
<form action="index.php" method="post">
<table cellpadding="1" cellspacing="0">
    <tr>
        <td style="padding-top: 5px;"><?php echo($aLang['SELECT_COUNTRY_LANG']) ?>: </td>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" height="29">
              <tr>
                <td style="padding-right: 3px;">
                    <select name="country_lang" style="font-size: 11px;"
                    onChange="update_dynpages_checkbox();"
                    >

                        <?php
                        foreach ( $aCountries[$sSetupLang] as $sKey => $sValue ) {
                            $sSelected = ( isset( $aPersistentData['country_lang'] ) && $aPersistentData['country_lang'] == $sKey ) ? 'selected' : '';
                            echo "<option value=\"$sKey\" $sSelected>$sValue</option>\n";
                        }
                        ?>
                    </select>
                </td>
                <noscript>
                <td>
                    <input type="submit" name="setup_lang_submit" value="<?php echo($aLang['SELECT_SETUP_LANG_SUBMIT']) ?>" style="font-size: 11px;">
                </td>
                </noscript>
               <td>
                &nbsp;&nbsp;
                    <input type="hidden" value="false" name="use_dynamic_pages">
                    <input type="checkbox" id="use_dynamic_pages_ckbox" value="true" name="use_dynamic_pages" valign="" style="vertical-align:middle; width:20px; height:22px;<?php  if (empty($aPersistentData['country_lang'])) echo " display: none;"?>" >
              <td>
              <td id="use_dynamic_pages_desc" style="<?php  if (empty($aPersistentData['country_lang'])) echo "display: none;"?>">
                    <?php echo($aLang['USE_DYNAMIC_PAGES']) ?><a href="<?php echo $sSetupLang; ?>/dyn_content_notice.php" onClick="showPopUp('<?php echo $sSetupLang; ?>/dyn_content_notice.php', 400, 200, 1); return false;" target="_blank"><u><?php echo($aLang['PRIVACY_POLICY']) ?></u></a>.
              </td>
            </tr>
          </table>

        </td>
    </tr>
    <input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
   </table>
    <br>
    <input type="hidden" value="false" name="check_for_updates">
    <input type="checkbox" id="check_for_updates_ckbox" value="true" name="check_for_updates" valign="" style="vertical-align:middle; width:20px; height:22px;" >
    <?php echo($aLang['STEP_1_CHECK_UPDATES']) ?>
    <br>
    <br>
    <?php echo( $aLang['STEP_1_TEXT'] ) ?>
    <br><br>
    <?php echo( $aLang['STEP_1_ADDRESS'] ) ?>

    <br>

    <input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_LICENSE']; ?>">
    <input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
    <input type="submit" id="step1Submit" class="edittext" value="<?php echo( $aLang['BUTTON_BEGIN_INSTALL'] ) ?>">
</form>

<?PHP
}


if ( $istep == $aSetupSteps['STEP_LICENSE'] ) {
    // ---------------------------------------------------------
    // LICENCE
    // ---------------------------------------------------------
    $title = $aLang['STEP_2_TITLE'];
    include "headitem.php";
?>
<textarea cols="180" rows="20" class="edittext" style="width: 98%; padding: 7px;">
<?php
    $sLicenseFile = "lizenz.txt";

    $aLicence = file( $sSetupLang . "/" . $sLicenseFile );
    foreach ( $aLicence as $sLine)
        echo( $sLine);
?>
</textarea>
<form action="index.php" method="post">
  <input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_DB_INFO']; ?>">
  <input type="radio" name="iEula" value="1"><?php echo( $aLang['BUTTON_RADIO_LICENCE_ACCEPT'] ) ?><br>
  <input type="radio" name="iEula" value="0" checked><?php echo( $aLang['BUTTON_RADIO_LICENCE_NOT_ACCEPT'] ) ?><br><br>
  <input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
  <input type="submit" id="step2Submit" class="edittext" value="<?php echo( $aLang['BUTTON_LICENCE'] ) ?>">
</form>
<?PHP
}


if ( $istep == $aSetupSteps['STEP_DB_INFO'] ) {
    // ---------------------------------------------------------
    // ENTER DATABASE INFO
    // ---------------------------------------------------------

    $title = $aLang['STEP_3_TITLE'];
    include "headitem.php";

    $aDB = @$aPersistentData['aDB'];
    if ( !isset( $aDB)) {
        // default values
        $aDB['dbHost'] = "localhost";
        $aDB['dbUser'] = "";
        $aDB['dbPwd'] = "";
        $aDB['dbName'] = "";
        $aDB['dbiDemoData'] = 1;
    }

    // mb string library info
    require_once "../core/oxsysrequirements.php";
    $oSysReq = new oxSysRequirements();
    $blMbStringOn = $oSysReq->getModuleInfo( 'mb_string' );
    $blUnicodeSupport = $oSysReq->getModuleInfo( 'unicode_support' );
?>
<script>
/**
 * Replaces password type field into plain and vice versa
 */
function changeField()
{
    var oField = document.getElementsByName( "aDB[dbPwd]" );
    doChange( oField[0], oField[1] );
    doChange( oField[1], oField[0] )
}
function doChange( oField1, oField2 )
{
    if ( oField1.disabled ) {
        oField1.disabled = '';
        oField1.style.display = '';
        oField1.value = oField2.value;
    } else {
        oField1.disabled = 'disabled';
        oField1.style.display = 'none';
        oField2.value = oField1.value;
    }
}
</script>

<?php echo( $aLang['STEP_3_DESC'] ) ?><br>
<br>
<form action="index.php" method="post">
<input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_DB_CONNECT']; ?>">

<table cellpadding="0" cellspacing="5" border="0">
  <tr>
    <td><?php echo( $aLang['STEP_3_DB_HOSTNAME'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aDB[dbHost]" class="editinput" value="<?php echo( $aDB['dbHost']);?>"> </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_3_DB_DATABSE_NAME'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aDB[dbName]" class="editinput" value="<?php echo( $aDB['dbName']);?>"><br>&nbsp;&nbsp;(<?php echo( $aLang['STEP_3_CREATE_DB_WHEN_NO_DB_FOUND'] ) ?>)</td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_3_DB_USER_NAME'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aDB[dbUser]" class="editinput" value="<?php echo( $aDB['dbUser']);?>"> </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_3_DB_PASSWORD'] ) ?>:</td>
    <td>
        &nbsp;&nbsp;<input size="40" name="aDB[dbPwd]" id="sDbPass" class="editinput" type="password" value="<?php echo( $aDB['dbPwd']);?>"><input size="40" name="aDB[dbPwd]" id="sDbPassPlain" class="editinput" type="text" disabled="disabled" style="display:none">
        <input type="checkbox" id="sDbPassCheckbox" onClick="JavaScript:changeField();"><?php echo( $aLang['STEP_3_DB_PASSWORD_SHOW'] ) ?>
    </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_3_DB_DEMODATA'] ) ?>:</td>
    <td>
        &nbsp;&nbsp;<input type="radio" name="aDB[dbiDemoData]" value="1" <?php if( $aDB['dbiDemoData'] == 1) echo( "checked"); ?>><?php echo( $aLang['BUTTON_RADIO_INSTALL_DB_DEMO'] ) ?><br>
        &nbsp;&nbsp;<input type="radio" name="aDB[dbiDemoData]" value="0" <?php if( $aDB['dbiDemoData'] == 0) echo( "checked"); ?>><?php echo( $aLang['BUTTON_RADIO_NOT_INSTALL_DB_DEMO'] ) ?><br>
    </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_3_UTFMODE'] ) ?>:</td>
    <td>
        &nbsp;&nbsp;<input type="checkbox" name="aDB[iUtfMode]" value="1" <?php if( (isset($aDB['iUtfMode']) && $aDB['iUtfMode'] == 1) && $blMbStringOn > 1 && $blUnicodeSupport > 1) { echo( "checked"); } echo ($blMbStringOn > 1 && $blUnicodeSupport > 1) ? '' : 'disabled'; ?>>
        <?php
            if ( $blMbStringOn > 1 && $blUnicodeSupport > 1 ) {
                echo ( $aLang['STEP_3_UTFINFO'] );
            } else {
                echo ( $aLang['STEP_3_UTFNOTSUPPORTED'] );
                if ( $blMbStringOn < 2 ) {
                    echo ( $aLang['STEP_3_UTFNOTSUPPORTED1'] );
                }
                if ( ($blMbStringOn + $blUnicodeSupport) == 2) {
                    echo ",";
                }
                if ( $blUnicodeSupport < 2 ) {
                    echo ( $aLang['STEP_3_UTFNOTSUPPORTED2'] );
                }
                echo ".";
            }
        ?>
        <br>
    </td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
<input type="submit" id="step3Submit" class="edittext" value="<?php echo( $aLang['BUTTON_DB_INSTALL'] ) ?>">
</form>
<?PHP
}


if ( $istep == $aSetupSteps['STEP_DB_CONNECT'] ) {
    // ---------------------------------------------------------
    // CHECK DATABASE
    // ---------------------------------------------------------

    $title = $aLang['STEP_3_1_TITLE'];
    $aDB = @$_POST['aDB'];
    $aPersistentData['aDB'] = $aDB;

    // check if iportant parameters are set
    if ( !$aDB['dbHost'] || !$aDB['dbName'] ) {
        $iRedir2Step = $aSetupSteps['STEP_DB_INFO'];
        $sMessage = $aLang['ERROR_FILL_ALL_FIELDS'];
        include "headitem.php";
        include "bottomitem.php";
        exit();
    }
    // ok check DB Connection
    $oDB = @mysql_connect( $aDB['dbHost'], $aDB['dbUser'], $aDB['dbPwd']);
    if ( !$oDB) {
        $iRedir2Step = $aSetupSteps['STEP_DB_INFO'];
        $sMessage = $aLang['ERROR_COULD_NOT_CONNECT_TO_DB'] . " - ". mysql_error();
        include "headitem.php";
        include "bottomitem.php";
        exit();
    }
    // check if database is there, if not try to create it
    $blCreated = 0;
    if ( @mysql_select_db( $aDB['dbName'], $oDB) == false) {
        // try to create one
        if ( !mysql_query( "create database ". $aDB['dbName'], $oDB)) {
            // no success !
            $iRedir2Step = $aSetupSteps['STEP_DB_INFO'];
            $sMessage = sprintf($aLang['ERROR_COULD_NOT_CREATE_DB'], $aDB['dbName']) . " - ". mysql_error();
            include "headitem.php";
            include "bottomitem.php";
            exit();
        } else
            $blCreated = 1;
    }
    // success !
    mysql_close( $oDB);

    $iRedir2Step = $aSetupSteps['STEP_DB_CREATE'];
    include "headitem.php";
    echo( "<b>" . $aLang['STEP_3_1_DB_CONNECT_IS_OK'] . "</b><br>");
    if ( $blCreated)
        echo( "<b>" . sprintf($aLang['STEP_3_1_DB_CREATE_IS_OK'], $aDB['dbName']) . "</b><br>");
    echo( "<br>" . $aLang['STEP_3_1_CREATING_TABLES'] . "<br>");
}


if ( $istep == $aSetupSteps['STEP_DB_CREATE'] ) {
    // ---------------------------------------------------------
    // CREATE DATABASE
    // ---------------------------------------------------------
    $title = $aLang['STEP_3_2_TITLE'];
    $aDB = @$aPersistentData['aDB'];
    $blOverwrite = @$_GET['ow'];
    if ( !isset( $blOverwrite))
        $blOverwrite = false;

    $oDB = OpenDatabase( $aDB);
    // check if DB is already UP and running
    if ( !$blOverwrite && mysql_query( "select * from oxconfig", $oDB) != false) {
        // DB already UP ?
        $sMessage = sprintf($aLang['ERROR_DB_ALREADY_EXISTS'], $aDB['dbName']);
        $sMessage .= "<br><br>" . $aLang['STEP_3_2_CONTINUE_INSTALL_OVER_EXISTING_DB'] . " <a href=\"index.php?sid=".getSID()."&istep=".$aSetupSteps['STEP_DB_CREATE']."&ow=1\" id=\"step3Continue\" style=\"text-decoration: underline;\">" . $aLang['HERE'] . "</a>";
        include "headitem.php";
        include "bottomitem.php";
        exit();
    }

    $sqlDir = 'sql';

    //settting database collation
    setMySqlCollation( isset($aDB['iUtfMode'])?$aDB['iUtfMode']:0 );

    $sProblems = QueryFile(  "$sqlDir/database.sql" ,$aDB);
    if ( strlen( $sProblems)) {
        // there where problems with queries
        $sMessage = $aLang['ERROR_BAD_SQL'] . "<br><br>'".$sProblems;
        include("headitem.php");
        include("bottomitem.php");
        exit();
    }

    if ( $aDB['dbiDemoData'] == '1') {
        // install demodata
        $sProblems = QueryFile(  "$sqlDir/demodata.sql" ,$aDB);
        if ( strlen( $sProblems)) {
            // there where problems with queries
            $sMessage = $aLang['ERROR_BAD_DEMODATA'] . "<br><br>'".$sProblems;
            include "headitem.php";
            include "bottomitem.php";
            exit();
        }
    }

    //update dyn pages / shop country config options (from first step)
    saveDynPagesSettings();

    //applying utf-8 specific queries
    if ( isset($aDB['iUtfMode'])?$aDB['iUtfMode']:0 ) {
        QueryFile(  "$sqlDir/latin1_to_utf8.sql" ,$aDB);

        //converting oxconfig table field 'oxvarvalue' values to utf
        setMySqlCollation( 0 );
        convertConfigTableToUtf();
    }

    $oConfk = new Conf();
    $sQUtfMode = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue)
                                 values('$sIDIMU', '$sBaseShopId', 'iSetUtfMode', 'str', ENCODE( '".((int) isset( $aDB['iUtfMode'] ) ? $aDB['iUtfMode'] : 0 )."', '".$oConfk->sConfigKey."') )";

    mysql_query($sQUtfMode);

    $iRedir2Step = $aSetupSteps['STEP_DIRS_INFO'];
    $sMessage = $aLang['STEP_3_2_CREATING_DATA'];
    include "headitem.php";
    include "bottomitem.php";
    exit();
}


if ( $istep == $aSetupSteps['STEP_DIRS_INFO'] ) {
    $title =  $aLang['STEP_4_TITLE'];
    include "headitem.php";

    $aPath = null;// @$aPersistentData['aPath'];
    $aSetupConfig = @$aPersistentData['aSetupConfig'];
    if ( !isset( $aPath)) {
        // default values
        $aPath['sDIR'] = "";
        $aPath['sURL'] = "";

        $aServerVars = & $_SERVER;

        // try path translated
        if ( isset( $aServerVars['PATH_TRANSLATED']))
            $sFilepath = $aServerVars['PATH_TRANSLATED'];
        else
            $sFilepath = $aServerVars['SCRIPT_FILENAME'];
        $aTemp = preg_split( "/\\\|\//", $sFilepath);
        foreach ( $aTemp as $sDir) {
            if ( stristr( $sDir, "setup"))
                break;
            $aPath['sDIR'] .= str_replace('\\', '/', $sDir) . "/";
        }
        $aPath['sTMP'] = $aPath['sDIR'] . "tmp/";

        // try referer
        $sFilepath = @$aServerVars['HTTP_REFERER'];
        if ( !isset( $sFilepath) || !$sFilepath)
            $sFilepath = "http://" . @$aServerVars['HTTP_HOST'] . @$aServerVars['SCRIPT_NAME'];

        $aTemp = explode( "/", $sFilepath);
        foreach ( $aTemp as $sDir) {
            if ( stristr( $sDir, "setup"))
                break;
            $aPath['sURL'] .= $sDir . "/";
        }

    }

?>
<br><br>
<?php echo( $aLang['STEP_4_DESC'] ) ?><br>
<br>
<form action="index.php" method="post">
<input type="hidden" name="istep" value="<?php echo $aSetupSteps['STEP_DIRS_WRITE']; ?>">

<table cellpadding="0" cellspacing="5" border="0">
  <tr>
    <td><?php echo( $aLang['STEP_4_SHOP_URL'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aPath[sURL]" class="editinput" value="<?php echo( $aPath['sURL']);?>"> </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_4_SHOP_DIR'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aPath[sDIR]" class="editinput" value="<?php echo( $aPath['sDIR']);?>"> </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_4_SHOP_TMP_DIR'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aPath[sTMP]" class="editinput" value="<?php echo( $aPath['sTMP']);?>"> </td>
  </tr>
  <tr>
    <td><?php echo( $aLang['STEP_4_DELETE_SETUP_DIR'] ) ?>:</td>
    <td>&nbsp;&nbsp;<input size="40" name="aSetupConfig[blDelSetupDir]" class="editinput" type="checkbox" value="1" <?php if ( isset($aSetupConfig['blDelSetupDir'])) { if ( $aSetupConfig['blDelSetupDir']) {echo ("checked");}} else { echo ("checked");}?>> </td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo( getSID()); ?>">
<input type="submit" id="step4Submit" class="edittext" value="<?php echo( $aLang['BUTTON_WRITE_DATA'] ) ?>">
</form>
<?PHP
}


if ( $istep == $aSetupSteps['STEP_DIRS_WRITE'] ) {
    // ---------------------------------------------------------
    // CHECK PATH
    // ---------------------------------------------------------

    $title = $aLang['STEP_4_1_TITLE'];
    $aPath = @$_POST['aPath'];
    $aSetupConfig = @$_POST['aSetupConfig'];

    // correct them
    $aPath['sURL'] = str_replace( "\\", "/", $aPath['sURL']);
    if ( $aPath['sURL'] && $aPath['sURL'][strlen($aPath['sURL'])-1] == '/')
        $aPath['sURL'] = substr( $aPath['sURL'], 0, strlen($aPath['sURL']) - 1);
    $aPath['sDIR'] = str_replace( "\\", "/", $aPath['sDIR']);
    if ( $aPath['sDIR'] && $aPath['sDIR'][strlen($aPath['sDIR'])-1] == '/')
        $aPath['sDIR'] = substr( $aPath['sDIR'], 0, strlen($aPath['sDIR']) - 1);
    $aPath['sTMP'] = str_replace( "\\", "/", $aPath['sTMP']);
    if ( $aPath['sTMP'] && $aPath['sTMP'][strlen($aPath['sTMP'])-1] == '/')
        $aPath['sTMP'] = substr( $aPath['sTMP'], 0, strlen($aPath['sTMP']) - 1);
    // using same array to pass additional setup variable
    if ( isset( $aSetupConfig['blDelSetupDir']) && $aSetupConfig['blDelSetupDir'])
        $aSetupConfig['blDelSetupDir'] = 1;
    else
        $aSetupConfig['blDelSetupDir'] = 0;

    $aPersistentData['aPath'] = $aPath;
    $aPersistentData['aSetupConfig'] = $aSetupConfig;

    // check if important parameters are set
    if ( !$aPath['sURL'] || !$aPath['sDIR'] || !$aPath['sTMP']) {
        $iRedir2Step = $aSetupSteps['STEP_DIRS_INFO'];
        $sMessage = $aLang['ERROR_FILL_ALL_FIELDS'];
        include "headitem.php";
        include "bottomitem.php";
        exit();
    }

    $sBaseOut = 'out/pictures';
    // check paths and rights
    $aPaths = array($aPath['sDIR']."/config.inc.php",
                    $aPath['sDIR']."/$sBaseOut/0",
                    $aPath['sDIR']."/$sBaseOut/1",
                    $aPath['sDIR']."/$sBaseOut/2",
                    $aPath['sDIR']."/$sBaseOut/3",
                    $aPath['sDIR']."/$sBaseOut/4",
                    $aPath['sDIR']."/$sBaseOut/5",
                    $aPath['sDIR']."/$sBaseOut/6",
                    $aPath['sDIR']."/$sBaseOut/7",
                    $aPath['sDIR']."/$sBaseOut/8",
                    $aPath['sDIR']."/$sBaseOut/9",
                    $aPath['sDIR']."/$sBaseOut/10",
                    $aPath['sDIR']."/$sBaseOut/11",
                    $aPath['sDIR']."/$sBaseOut/12",
                    $aPath['sDIR']."/$sBaseOut/icon",
                    $aPath['sDIR']."/$sBaseOut/z1",
                    $aPath['sDIR']."/$sBaseOut/z2",
                    $aPath['sDIR']."/$sBaseOut/z3",
                    $aPath['sDIR']."/$sBaseOut/z4",
                    $aPath['sDIR']."/out/basic/src/bg",
                    $aPath['sDIR']."/out/basic/src",
                    $aPath['sDIR']."/log",
                    $aPath['sTMP']);

    foreach ( $aPaths as $sPath) {
        $sMessage = checkFileOrDirectory($sPath);
        if ( $sMessage) {
            include "headitem.php";
            include "bottomitem.php";
            exit();
        }
    }

    // write it now
    $aDB = @$aPersistentData['aDB'];
    $sConfPath = $aPath['sDIR']."/config.inc.php";

    $fp = fopen( $sConfPath, "r");
    if ( $fp) {
        $sConfFile = fread( $fp, filesize( $sConfPath));
        fclose( $fp);
    } else
        die( sprintf($aLang['ERROR_COULD_NOT_OPEN_CONFIG_FILE'], $sConfPath) );

    $aReplace["<dbHost$sVerPrefix>"]      = $aDB['dbHost'];
    $aReplace["<dbName$sVerPrefix>"]      = $aDB['dbName'];
    $aReplace["<dbUser$sVerPrefix>"]      = $aDB['dbUser'];
    $aReplace["<dbPwd$sVerPrefix>"]       = $aDB['dbPwd'];
    $aReplace["<sShopURL$sVerPrefix>"]    = $aPath['sURL'];
    $aReplace["<sShopDir$sVerPrefix>"]    = $aPath['sDIR'];
    $aReplace["<sCompileDir$sVerPrefix>"] = $aPath['sTMP'];
    $aReplace["<iUtfMode>"]               = (int) ( isset( $aDB['iUtfMode'] ) ? $aDB['iUtfMode'] : 0 );
    $sConfFile = strtr( $sConfFile, $aReplace);
    $fp = fopen( $sConfPath, "w");
    if ( $fp) {
        fwrite($fp, $sConfFile);
        fclose($fp);
    } else {
        // error ? strange !?
        $iRedir2Step = $aSetupSteps['STEP_DIRS_INFO'];
        $sMessage = sprintf($aLang['ERROR_CONFIG_FILE_IS_NOT_WRITABLE'], $aPath['sDIR']);
        include "headitem.php";
        include "bottomitem.php";
        exit();
    }


        $iRedir2Step = $aSetupSteps['STEP_FINISH'];

    $sMessage = $aLang['STEP_4_1_DATA_WAS_WRITTEN'];
    include "headitem.php";
    include "bottomitem.php";
    exit();

}









if ( $istep == $aSetupSteps['STEP_FINISH'] ) {
    // ---------------------------------------------------------
    // END
    // ---------------------------------------------------------
    $title = $aLang['STEP_6_TITLE'];
    include "headitem.php";

     $aPath = @$aPersistentData['aPath'];
     $aSetupConfig = @$aPersistentData['aSetupConfig'];

?>
<?php echo( $aLang['STEP_6_DESC'] ) ?><br>
<br>
<table cellspacing="5" cellpadding="5">
<tr>
    <td><?php echo( $aLang['STEP_6_LINK_TO_SHOP'] ) ?>: </td>
    <td><a href="<?php echo( $aPath['sURL']); ?>/" target="_new" id="linkToShop" style="text-decoration: underline"><strong><?php echo( $aLang['STEP_6_TO_SHOP'] ) ?></strong></a></td>
</tr>
<tr>
    <td><?php echo( $aLang['STEP_6_LINK_TO_SHOP_ADMIN_AREA'] ) ?>: </td>
    <td><a href="<?php echo( $aPath['sURL']); ?>/admin/" target="_new" id="linkToAdmin" style="text-decoration: underline"><strong><?php echo( $aLang['STEP_6_TO_SHOP_ADMIN'] ) ?></strong></a>
    (<?php echo( $aLang['STEP_6_ADDITIONAL_LOGIN_INFO'] ) ?>)
    </td>
</tr>
</table>
<br>
<?php

     //finalizing installation
     $blRemoved = true;
     if ( isset( $aSetupConfig['blDelSetupDir']) && $aSetupConfig['blDelSetupDir']) {
         // outputting previous HTML contents to browser
         flush();

         // caching "bottomitem.php" contents
         ob_start();
         include  "bottomitem.php";
         $sBottomItem = ob_get_clean();

         // removing setup files
         //Commented until deployment
         $blRemoved = removeDir("../setup", true);
     }
     $sConfPath = $aPath['sDIR']."/config.inc.php";
     $sPerms = substr( decoct( fileperms($sConfPath) ), 2 );
     if ( !$blRemoved || $sPerms > 644 ) {
?>
<strong style="font-size:16px;color:red;"><?php echo( $aLang['ATTENTION'] ) ?>:</strong><br>
<br>
<?php }
      if ( !$blRemoved ) {
?>
<strong style="font-size:16px;color:red;"><?php echo( $aLang['SETUP_DIR_DELETE_NOTICE'] ) ?></strong><br><br>
<?php
      }
      if ( $sPerms > 644 ) {
?>
<strong style="font-size:16px;color:red;"><?php echo( $aLang['SETUP_CONFIG_PERMISSIONS'] ) ?></strong><br>
<?php
     }
     if ( isset( $sBottomItem)) {
         // outputting bottom item contents
         echo $sBottomItem;
         exit();
     }
}

include "bottomitem.php";

ob_end_flush();
