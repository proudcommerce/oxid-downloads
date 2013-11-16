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
 * @version   SVN: $Id: function.oxstyle.php 28124 2010-06-03 11:27:00Z alfonsas $
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File: function.oxstyle.php
 * Type: string, html
 * Name: oxstyle
 * Purpose: Collect and output css files.
 *
 * Add [{oxsstyle include="oxis.css"}] to include externall css file.
 *
 * Add [{oxstyle}] where you need to output all collected css.
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxstyle($params, &$smarty)
{
    $myConfig = oxConfig::getInstance();
    $sSufix   = ($smarty->_tpl_vars["__oxid_include_dynamic"])?'_dynamic':'';
    $sCtyles  = 'conditional_styles'.$sSufix;
    $sStyles  = 'styles'.$sSufix;

    $aCtyles  = (array) $myConfig->getGlobalParameter($sCtyles);
    $aStyles  = (array) $myConfig->getGlobalParameter($sStyles);

    $sOutput  = '';
    if ( $params['include'] ) {
        $sStyle = $params['include'];
        if (!preg_match('#^https?://#', $sStyle)) {
            $sStyle = $myConfig->getResourceUrl($sStyle);
        }
        if ($params['if']) {
            $aCtyles[$sStyle] = $params['if'];
            $myConfig->setGlobalParameter($sCtyles, $aCtyles);
        } else {
            $aStyles[] = $sStyle;
            $aStyles = array_unique($aStyles);
            $myConfig->setGlobalParameter($sStyles, $aStyles);
        }
    } else {
        foreach ($aStyles as $sSrc) {
            $sOutput .= '<link rel="stylesheet" type="text/css" href="'.$sSrc.'">'.PHP_EOL;
        }
        foreach ($aCtyles as $sSrc => $sCondition) {
            $sOutput .= '<!--[if '.$sCondition.']><link rel="stylesheet" type="text/css" href="'.$sSrc.'"><![endif]-->'.PHP_EOL;
        }
    }

    return $sOutput;
}
