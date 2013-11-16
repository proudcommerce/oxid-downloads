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
 * @version   SVN: $Id: function.oxscript.php 28124 2010-06-03 11:27:00Z alfonsas $
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

    $sScript  = (string) $myConfig->getGlobalParameter($sScripts);
    $aInclude = (array) $myConfig->getGlobalParameter($sIncludes);
    $sOutput  = '';

    if ( $params['add'] ) {
        $sScript .= $params['add'];
        $myConfig->setGlobalParameter($sScripts, $sScript);

    } elseif ( $params['include'] ) {
        $sInclude = $params['include'];
        $aInclude[] = $myConfig->getResourceUrl($sInclude);
        $aInclude = array_unique($aInclude);
        $myConfig->setGlobalParameter($sIncludes, $aInclude);

    } else {
        foreach ($aInclude as $sSrc) {
            $sOutput .= '<script type="text/javascript" src="'.$sSrc.'"></script>'."\n";
        }
        if (strlen($sScript)) {
            $sOutput .= '<script type="text/javascript">'.$sScript.'</script>';
        }
    }

    return $sOutput;
}
