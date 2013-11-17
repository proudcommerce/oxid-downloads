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
 * @package admin
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: category_update.php 16302 2009-02-05 10:18:49Z rimvydas.paskevicius $
 */

/**
 * Class for updating category tree structure in DB.
 * @package admin
 */
class Category_Update extends oxAdminView
{
    /**
     * Loads category tree object, executes update and returns name of template
     * file "category_update.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // parent categorie tree
        $oCatTree = oxNew( "oxCategoryList" );
        $oCatTree->updateCategoryTree();

        return "category_update.tpl";
    }
}
