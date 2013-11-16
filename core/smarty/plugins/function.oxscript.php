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
 * @package   smarty_plugins
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: function.oxscript.php 38109 2011-08-10 14:56:40Z tomas $
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File: function.oxscript.php
 * Type: string, html
 * Name: oxscript
 * Purpose: Collect needed javascript includes and calls, but execute them at the bottom of the page.
 *
 * Add [{ oxscript add="oxid.popup.load" }] where you need to add stript calls.
 * Add [{oxscript include="oxid.js"}] to include externall javascript file.
 *
 * Add [{ oxscript }] where you need to output all collected script includes and calls.
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxscript($params, &$smarty)
{
    $myConfig  = oxConfig::getInstance();
    $sSufix    = ($smarty->_tpl_vars["__oxid_include_dynamic"])?'_dynamic':'';
    $sIncludes = 'includes'.$sSufix;
    $sScripts  = 'scripts'.$sSufix;
    $iDefaultPriority = 3;

    $aScript  = (array) $myConfig->getGlobalParameter($sScripts);
    $aInclude = (array) $myConfig->getGlobalParameter($sIncludes);
    $sOutput  = '';

    if ( $params['add'] ) {
        $sScriptToken = trim( $params['add'] );
        if ( !in_array($sScriptToken, $aScript)) {
            $aScript[] = $sScriptToken;
        }
        $myConfig->setGlobalParameter($sScripts, $aScript);

    } elseif ( $params['include'] ) {
        $sUrl = $params['include'];
        if (!preg_match('#^https?://#', $sUrl)) {
            $sUrl = $myConfig->getResourceUrl($sUrl);
        }

        $iPriority = ( $params['priority'] ) ? $params['priority'] : $iDefaultPriority;
        if (!$sUrl) {
            if ($myConfig->getConfigParam( 'iDebug' ) != 0) {
                return "<div style='color:red;'>WARNING: javascript resource not found: ".  htmlspecialchars($params['include']).'</div>';
            }
        } else {
            $aInclude[$iPriority][] = $sUrl;
            $aInclude[$iPriority]   = array_unique($aInclude[$iPriority]);
            $myConfig->setGlobalParameter($sIncludes, $aInclude);
        }
    } else {
        ksort( $aInclude );

        $aOutUrls = array();

        foreach ($aInclude as $aPriority) {
            foreach ($aPriority as $sSrc) {
                //checking for dublicates #3062
                if (!in_array($sSrc, $aOutUrls)) {
                    $sOutput .= '<script type="text/javascript" src="'.$sSrc.'"></script>'.PHP_EOL;
                }
                $aOutUrls[] = $sSrc;
            }
        }
        $myConfig->setGlobalParameter($sIncludes, null);

        if (count($aScript)) {
            $sOutput .= '<script type="text/javascript">' . "\n";
            foreach ($aScript as $sScriptToken) {
                $sOutput .= $sScriptToken. "\n";
            }
            $sOutput .= '</script>' . PHP_EOL;

            $myConfig->setGlobalParameter($sScripts, null);
        }

    }

    return $sOutput;
}
