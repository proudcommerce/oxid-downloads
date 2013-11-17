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
 * $Id: report_base.php 22452 2009-09-21 08:28:12Z arvydas $
 */

/**
 * config to fetch paths
 */
$myConfig = oxConfig::getInstance();

/**
 * needed libraries location
 */
$sIncPath = $myConfig->getConfigParam( 'sShopDir' ).'/'.$myConfig->getConfigParam( 'sAdminDir' );

/**
 * switching cache off
 */
DEFINE( 'USE_CACHE', false );
DEFINE( 'CACHE_DIR', $myConfig->getConfigParam( 'sCompileDir' ) );

/**
 * including libraries
 */
require_once "$sIncPath/reports/jpgraph/jpgraph.php";
require_once "$sIncPath/reports/jpgraph/jpgraph_bar.php";
require_once "$sIncPath/reports/jpgraph/jpgraph_line.php";
require_once "$sIncPath/reports/jpgraph/jpgraph_pie.php";
require_once "$sIncPath/reports/jpgraph/jpgraph_pie3d.php";

if ( !class_exists( 'report_base' ) ) {
    /**
     * Base reports class
     * @package admin
     */
    class Report_base extends oxAdminView
    {
        /**
         * Smarty object
         * @return
         */
        protected $_oSmarty = null;

        /**
         * Returns name of template to render
         *
         * @return string
         */
        public function render()
        {
            return $this->_sThisTemplate;
        }

        /**
         * Smarty object setter
         *
         * @param smarty $oSmarty smarty object
         *
         * @return null
         */
        public function setSmarty( $oSmarty )
        {
            $this->_oSmarty = $oSmarty;
        }

        /**
         * Returns Smarty object
         *
         * @return smarty
         */
        public function getSmarty()
        {
            return $this->_oSmarty;
        }
    }
}