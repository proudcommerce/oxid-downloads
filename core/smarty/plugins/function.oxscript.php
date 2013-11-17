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
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: function.oxscript.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * File: function.oxscript.php
 * Type: string, html
 * Name: oxscript
 * Purpose: Collect needed javascript calls, and execute them at he bottom of the page
 * add [{ oxscript add="oxid.popup.load" }] where you need to add stripts and
 * execute and  [{ oxscript }] where you need to output all collected script calls
 * -------------------------------------------------------------
 *
 * @param array  $params  params
 * @param Smarty &$smarty clever simulation of a method
 *
 * @return string
 */
function smarty_function_oxscript($params, &$smarty)
{
    $myConfig = oxConfig::getInstance();

    $sVarname = 'scripts';
    if ($smarty->_tpl_vars["__oxid_include_dynamic"]) {
        $sVarname .= '_dynamic';
    }

    $sScript = $myConfig->getGlobalParameter($sVarname);

    if ( $params['add'] ) {
        $myConfig->setGlobalParameter($sVarname, $sScript.$params['add']);
        return '';
    } else {
        return $sScript;
    }
}
