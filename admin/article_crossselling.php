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
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: article_crossselling.php 33186 2011-02-10 15:53:43Z arvydas.vapsva $
 */

/**
 * Admin article crosselling/accesories manager.
 * Creates list of available articles, there is ability to assign or remove
 * assigning of article to crosselling/accesories with other products.
 * Admin Menu: Manage Products -> Articles -> Crosssell.
 * @package admin
 */
class Article_Crossselling extends oxAdminDetails
{
    /**
     * Collects article crosselling and attributes information, passes
     * them to Smarty engine and returns name or template file
     * "article_crossselling.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['edit'] = $oArticle = oxNew( 'oxarticle' );

        // crossselling
        $sChosenArtCat = $this->_getCategoryTree( "artcattree", oxConfig::getParameter( "artcat"));

        // accessoires
        $sChosenArtCat2 = $this->_getCategoryTree( "artcattree2", oxConfig::getParameter( "artcat2"));

        $soxId = $this->getEditObjectId();
        if ( $soxId != "-1" && isset( $soxId ) ) {
            // load object
            $oArticle->load( $soxId);

            if ($oArticle->isDerived())
                $this->_aViewData['readonly'] = true;
        }

        $aColumns = array();
        $iAoc = oxConfig::getParameter("aoc");
        if ( $iAoc == 1 ) {

            include_once 'inc/article_crossselling.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/article_crossselling.tpl";
        } elseif ( $iAoc == 2 ) {

            include_once 'inc/article_accessories.inc.php';
            $this->_aViewData['oxajax'] = $aColumns;

            return "popups/article_accessories.tpl";
        }
        return "article_crossselling.tpl";
    }
}
