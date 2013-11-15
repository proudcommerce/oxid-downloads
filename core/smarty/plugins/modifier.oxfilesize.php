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
 * @copyright (C) OXID eSales AG 2003-2012
 * @version OXID eShop CE
 * @version   SVN: $Id: $
 */

/**
 * Smarty modifier
 * -------------------------------------------------------------
 * Name:     oxfilesize<br>
 * Purpose:  {$var|oxfilesize} Convert integer file size to readable format
 * -------------------------------------------------------------
 *
 * @param int $iSize Integer size value
 *
 * @return string
 */
function smarty_modifier_oxfilesize($iSize)
{
    if ($iSize < 1024) {
        return $iSize. " B";
    }

    $iSize = $iSize/1024;

    if ($iSize < 1024) {
        return sprintf("%.1f KB", $iSize);
    }

    $iSize = $iSize/1024;

    if ($iSize < 1024) {
        return sprintf("%.1f MB", $iSize);
    }

    $iSize = $iSize/1024;

    return sprintf("%.1f GB", $iSize);

}
