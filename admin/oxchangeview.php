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
 * $Id: oxchangeview.php 16302 2009-02-05 10:18:49Z rimvydas.paskevicius $
 */



/**
 * Error constants
 */
DEFINE("ERR_GENERAL", -1);
DEFINE("ERR_NOT_SELECTED_FILE", 1);
DEFINE("ERR_MISSING_FILE", 2);
DEFINE("ERR_WRONG_FORMAT", 3);
DEFINE("ERR_IO_FAILURE", 4);

/**
 * oxChange framework class encapsulating a method for defining implementation class.
 * Performs export function according to user chosen categories.
 * @package admin
 */
class oxChangeView extends oxAdminView
{

    protected $_sThisTemplate = "oxchange.tpl";

    public $sPopupTemplate = "oxchange_popup.tpl";

    protected $_sImplClass = "oxchange";
    protected $_sImplClass_do = "oxchange_do";
    protected $_sImplClass_import = "oxchange_import";
    protected $_sImplClass_export = "oxchange_export";
    protected $_sImplClass_orders = "oxchange_orders";
    protected $_sImplClass_articles = null;

    //first page class
    protected $_sFirstStartClass = "oxchange_import";

    //output paths and files

    protected $_sImportPath = null;
    protected $_sExportPath = null;

    protected $_sDefaultExportPath = "/export/";
    protected $_sDefaultImportPath = "/export/";

    protected $_sImportFileType = "txt";
    protected $_sExportFileType = "txt";


    /**
     * Sets ups oxChange class names by "impl" parameter
     *
     * @return null
     */
    protected function _getImplClassNames()
    {
        $sImplClass = oxConfig::getParameter("impl");

        if ($sImplClass)
            oxSession::setVar("impl", $sImplClass);

        if (!$sImplClass)
            $sImplClass = oxSession::getVar("impl");


        if ($sImplClass) {
            if (file_exists($sImplClass.".php")) {
                $this->_sImplClass = $sImplClass;
                $this->_sExportPath = $this->_sDefaultExportPath.$sImplClass."/";
                $this->_sImportPath = $this->_sDefaultImportPath.$sImplClass."/";
            } else {
                $this->_sExportPath = $this->_sDefaultExportPath;
                $this->_sImportPath = $this->_sDefaultImportPath;
            }


            if (file_exists($sImplClass."_do.php")) {
                $this->_sImplClass_do = $sImplClass."_do";
            }

            if (file_exists($sImplClass."_import.php"))
                $this->_sImplClass_import = $sImplClass."_import";

            if (file_exists($sImplClass."_orders.php"))
                $this->_sImplClass_orders = $sImplClass."_orders";

            if (file_exists($sImplClass."_articles.php"))
                $this->_sImplClass_articles = $sImplClass."_articles";
        }
    }

    /**
     * Calls parent rendering methods, sends implementation class names to template
     * and returns default template name
     *
     * @return string
    */

    public function render()
    {
        parent::render();
        $this->_getImplClassNames();
        $this->_aViewData["implclass"] = $this->_sImplClass;
        $this->_aViewData["implclass_do"] = $this->_sImplClass_do;
        $this->_aViewData["implclass_import"] = $this->_sImplClass_import;
        $this->_aViewData["implclass_export"] = $this->_sImplClass_export;
        $this->_aViewData["implclass_orders"] = $this->_sImplClass_orders;
        $this->_aViewData["implclass_articles"] = $this->_sImplClass_articles;

        $this->_aViewData["firststartclass"] = $this->_sFirstStartClass;
        return $this->_sThisTemplate;
    }


    /**
     * Returns order export full filename
     *
     * @return string
     */
    public function getExportFile()
    {
        $this->_getImplClassNames();

        $sOrderExportFile = $this->getConfig()->getConfigParam( 'sShopDir' );
        $sOrderExportFile .= $this->_sExportPath;
        $sOrderExportFile .= $this->_sImplClass;
        $sOrderExportFile .= ".".$this->_sExportFileType;

        return $sOrderExportFile;
    }

    /**
     * Returns order import full filename
     *
     * @return string
     */
    public function getImportFile()
    {
        $this->_getImplClassNames();
        $sImportFile = $this->getConfig()->getConfigParam( 'sShopDir' );
        $sImportFile .= $this->_sImportPath;
        $sImportFile .= $this->_sImplClass;
        $sImportFile .= ".".$this->_sImportFileType;

        return $sImportFile;
    }

}
