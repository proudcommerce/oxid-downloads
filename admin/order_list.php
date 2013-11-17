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
 * $Id: order_list.php 18765 2009-05-04 13:11:33Z vilma $
 */

/**
 * Admin order list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Orders -> Display Orders.
 * @package admin
 */
class Order_List extends oxAdminList
{
    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxorder';

    /**
     * Enable/disable sorting by DESC (SQL) (defaultfalse - disable).
     *
     * @var bool
     */
    protected $_blDesc = true;

        /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSort = "oxorder.oxorderdate";

    /**
     * Executes parent method parent::render() and returns name of template
     * file "order_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $aFolders = $this->getConfig()->getConfigParam( 'aOrderfolder' );
        $sFolder  = oxConfig::getParameter( "folder" );
        // first display new orders
        if ( !$sFolder && is_array( $aFolders )) {
            $aNames = array_keys( $aFolders );
            $sFolder = $aNames[0];
        }

        $aSearch    = array( 'oxorderarticles' => 'ARTID', 'oxpayments' => 'PAYMENT');
        $sSearch    = oxConfig::getParameter( "addsearch" );
        $sSearchfld = oxConfig::getParameter( "addsearchfld" );

        $this->_aViewData["folder"]       = $sFolder ? $sFolder : -1;
        $this->_aViewData["addsearchfld"] = $sSearchfld ? $sSearchfld : -1;
        $this->_aViewData["asearch"]      = $aSearch;
        $this->_aViewData["addsearch"]    = $sSearch;
        $this->_aViewData["afolder"]      = $aFolders;

        return "order_list.tpl";
    }

    /**
     * Adding folder check
     *
     * @param array  $aWhere  SQL condition array
     * @param string $sqlFull SQL query string
     *
     * @return $sQ
     */
    protected function _prepareWhereQuery( $aWhere, $sqlFull )
    {
        $sQ = parent::_prepareWhereQuery( $aWhere, $sqlFull );
        $myConfig = $this->getConfig();
        $aFolders = $myConfig->getConfigParam( 'aOrderfolder' );
        $sFolder = oxConfig::getParameter( 'folder' );
        //searchong for empty oxfolder fields
        if ( $sFolder && $sFolder != '-1' ) {
            $sQ .= " and ( oxorder.oxfolder = '".$sFolder."' ";
            //deprecated check for old orders
            $sQ .= "or oxorder.oxfolder = '".oxLang::getInstance()->translateString($sFolder)."')";
        } elseif ( !$sFolder && is_array( $aFolders ) ) {
            $aFolderNames = array_keys( $aFolders );
            $sQ .= " and ( oxorder.oxfolder = '".$aFolderNames[0]."' ";
            //deprecated check for old orders
            $sQ .= "or oxorder.oxfolder = '".oxLang::getInstance()->translateString($aFolderNames[0])."')";
        }

        return $sQ;
    }

    /**
     * Builds and returns SQL query string. Adds additional order check.
     *
     * @param object $oListObject list main object
     *
     * @return string
     */
    protected function _buildSelectString( $oListObject = null )
    {
        $sSql = parent::_buildSelectString( $oListObject );

        $sSearch      = oxConfig::getParameter( 'addsearch' );
        $sSearch      = trim( $sSearch );
        $sSearchField = oxConfig::getParameter( 'addsearchfld' );

        if ( $sSearch ) {
            switch ($sSearchField) {
            case 'oxorderarticles':
                $sQ = "oxorder left join oxorderarticles on oxorderarticles.oxorderid=oxorder.oxid where ( oxorderarticles.oxartnum like '%{$sSearch}%' or oxorderarticles.oxtitle like '%{$sSearch}%' ) and ";
                break;
            case 'oxpayments':
                $sQ = "oxorder left join oxpayments on oxpayments.oxid=oxorder.oxpaymenttype where oxpayments.oxdesc like '%{$sSearch}%' and ";
                break;
            default:
                $sQ = "oxorder where oxorder.oxpaid like '%{$sSearch}%' and ";
                break;
            }
            $sSql = str_replace( 'oxorder where', $sQ, $sSql);
        }

        return $sSql;
    }

    /**
     *
     * @return null
     */
    public function storno()
    {
        $myConfig = $this->getConfig();
        $soxId    = oxConfig::getParameter( "oxid");

        $oOrder = oxNew( "oxorder" );
        $oOrder->load( $soxId);
        $oOrder->oxorder__oxstorno->setValue(1);
        $oOrder->save();

        // stock information
        $blUseStock = $myConfig->getConfigParam( 'blUseStock' );
        $blAllowNegativeStock = $myConfig->getConfigParam('blAllowNegativeStock');
        $oDB = oxDb::getDb();
        foreach ( $oOrder->getOrderArticles() as $oArticle) {
            if ( $oArticle->oxorderarticles__oxstorno->value == 0) {
                if ( $blUseStock )
                    $oArticle->updateArticleStock($oArticle->oxorderarticles__oxamount->value, $blAllowNegativeStock );
                $oDB->execute( "update oxorderarticles set oxorderarticles.oxstorno = '1' where oxorderarticles.oxid = '".$oArticle->oxorderarticles__oxid->value."' ");
            }
        }


        //we call init() here to loads list items after sorno()
        $this->init();
    }

    /**
     * Adds order by to SQL query string.
     *
     * @param string $sSql sql string
     *
     * @return string
     */
    protected function _prepareOrderByQuery( $sSql = null )
    {
        $sSortParam = oxConfig::getParameter( 'sort' );

        //setting sort order as ASC for oxbilllname column
        if ( $sSortParam && $sSortParam == 'oxorder.oxbilllname' )
           $this->_blDesc = false;

        return parent::_prepareOrderByQuery( $sSql );
    }
}
