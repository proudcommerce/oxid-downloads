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
 * @link      http://www.oxid-esales.com
 * @package   main
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: index.php 26911 2010-03-26 17:29:31Z tomas $
 */

// Setting error reporting mode
    error_reporting( E_ALL ^ E_NOTICE );

//Know exactly where in the code the event occurred.
//Zend platform only.
if (function_exists('monitor_set_aggregation_hint') && isset($_REQUEST['cl'])) {
    $sAgregationHint = htmlentities($_REQUEST['cl'], ENT_QUOTES, 'UTF-8') . '/';
    if (isset($_REQUEST['fnc']))
        $sAgregationHint .= htmlentities($_REQUEST['fnc'], ENT_QUOTES, 'UTF-8');
    monitor_set_aggregation_hint($sAgregationHint);
}


//setting basic configuration parameters
ini_set('session.name', 'sid' );
ini_set('session.use_cookies', 0 );
ini_set('session.use_trans_sid', 0);
ini_set('url_rewriter.tags', '');
ini_set('magic_quotes_runtime', 0);

/**
 * Returns shop base path.
 *
 * @return string
 */
function getShopBasePath()
{
    return dirname(__FILE__).'/';
}


if ( !function_exists( 'isAdmin' )) {
    /**
     * Returns false.
     *
     * @return bool
     */
    function isAdmin()
    {
        return false;
    }
}

// custom functions file
require getShopBasePath() . 'modules/functions.php';

// Generic utility method file
require_once getShopBasePath() . 'core/oxfunctions.php';


// set the exception handler already here to catch everything, also uncaught exceptions from the config or utils

// initializes singleton config class
$myConfig = oxConfig::getInstance();

// reset it so it is done with oxnew
$iDebug = $myConfig->getConfigParam('iDebug');
set_exception_handler(array(oxNew('oxexceptionhandler', $iDebug), 'handleUncaughtException'));
// Admin handling
if ( isAdmin() ) {
    $myConfig->setConfigParam( 'blAdmin', true );
    $myConfig->setConfigParam( 'blTemplateCaching', false );
    if ($sAdminDir)
        $myConfig->setConfigParam( 'sAdminDir', $sAdminDir );
    else
        $myConfig->setConfigParam( 'sAdminDir', "admin" );
}

//Invoked over search engine URLs
if (isSearchEngineUrl()) {
    oxNew('oxSeoDecoder')->processSeoCall();
}

//Starting the shop
$oShopControl = oxNew('oxShopControl');

startProfile('start');
$oShopControl->start();
stopProfile('start');


//DEBUG INFO
if (!isAdmin() && $iDebug) {
    echo  "cl=".oxConfig::getInstance()->getActiveView()->getClassName();
    if ($sFnc = oxConfig::getInstance()->getActiveView()->getFncName())
        echo " fnc=$sFnc";

    echo "<br>\n";
}


if (!isAdmin() && $iDebug && is_array($aProfileTimes)) {
    echo "----------------------------------------------------------<br>".PHP_EOL;
    $iTotalTime = $oShopControl->getTotalTime();
    arsort($aProfileTimes);
    echo "<table cellspacing='10px' style='border: 1px solid #000'>";
    foreach ($aProfileTimes as $sKey => $sVal) {
        echo "<tr><td style='border-bottom: 1px dotted #000;min-width:300px;'>Profile $sKey: </td><td style='border-bottom: 1px dotted #000;min-width:100px;'>" . round($sVal, 5) ."s</td>" ;
        if ($iTotalTime) {
            echo "<td style='border-bottom: 1px dotted #000;min-width:100px;'>".round($sVal*100/$iTotalTime, 2)."%</td>";
        }
        if ($aExecutionCounts[$sKey]) {
            echo " <td style='border-bottom: 1px dotted #000;min-width:50px;padding-right:30px;' align='right'>" . $aExecutionCounts[$sKey] .  "</td>"
                 ."<td style='border-bottom: 1px dotted #000;min-width:15px; '>*</td>"
                 ."<td style='border-bottom: 1px dotted #000;min-width:100px;'>" . round($sVal / $aExecutionCounts[$sKey], 5) . "s</td></tr>" . PHP_EOL;
        } else {
            echo " <td colspan=3 style='border-bottom: 1px dotted #000;min-width:100px;'> not stopped correctly! </td></tr>" . PHP_EOL;
        }
    }
    echo "</table>";
}

if (!isAdmin() && ($iDebug == 7)) {
    echo "----------------------------------------------------------<br>".PHP_EOL;
    echo "-- oxdebugdb --<br>".PHP_EOL;
    $oDbgDb = oxNew('oxdebugdb');
    $aWarnings = $oDbgDb->getWarnings();
    $iNr = 1;
    foreach ($aWarnings as $w) {
        echo "{$w['check']}: {$w['time']} - <span style='color:#900000;margin:5px'>".htmlentities($w['sql'], ENT_QUOTES, 'UTF-8')."</span>";
        echo "<div id='dbgdb_trace_$iNr' style='display:none'>".nl2br($w['trace'])."</div>";
        echo "<a style='color:#00AA00;margin:5px;cursor:pointer' onclick='var el=document.getElementById(\"dbgdb_trace_$iNr\"); if (el.style.display==\"block\")el.style.display=\"none\"; else el.style.display = \"block\";'>TRACE (show/hide)</a><br><br>";
        ++$iNr;
    }
}

if (!isAdmin() && ($iDebug == 2 || $iDebug == 3 || $iDebug == 4)) {
            $oPerfMonitor = @NewPerfMonitor( oxDb::getDb() );
            if ( $oPerfMonitor )
                $oPerfMonitor->UI( 5 );
}
