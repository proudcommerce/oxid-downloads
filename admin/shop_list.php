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
 * $Id: shop_list.php 19181 2009-05-18 15:14:56Z rimvydas.paskevicius $
 */

/**
 * Admin shop list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Main Menu -> Core Settings.
 * @package admin
 */
class Shop_List extends oxAdminList
{
    protected $_blUpdateMain = false;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSort = 'oxshops.oxname';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxshop';

    /**
     * Navigation frame reload marker
     *
     * @var bool
     */
    protected $_blUpdateNav = null;

    /**
     * Sets SQL query parameters (such as sorting),
     * executes parent method parent::Init().
     *
     * @return null
     */
    public function init()
    {
        $this->_blEmployMultilanguage = false;
        parent::Init();


        $this->_blEmployMultilanguage = true;
    }

    /**
     * Executes parent method parent::render() and returns name of template
     * file "shop_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $soxId = oxConfig::getParameter( 'oxid' );
        if ( !$soxId ) {
            $soxId = $myConfig->getShopId();
        }

        $sSavedID = oxConfig::getParameter( 'saved_oxid' );
        if ( ( $soxId == '-1' || !isset( $soxId ) ) && isset( $sSavedID ) ) {
            $soxId = $sSavedID;
            oxSession::deleteVar( 'saved_oxid' );
            // for reloading upper frame
            $this->_aViewData['updatelist'] = '1';
        }

        if ( $soxId != '-1' && isset( $soxId ) ) {
            // load object
            $oShop = oxNew( 'oxshop' );
            if ( !$oShop->load( $soxId ) ) {
                $soxId = $myConfig->getBaseShopId();
                $oShop->load( $soxId );
            }
            $this->_aViewData['editshop'] = $oShop;
        }

        // default page number 1
        $this->_aViewData['default_edit'] = 'shop_main';
        $this->_aViewData['updatemain']   = $this->_blUpdateMain;

        $this->_aViewData['oxid'] =  $soxId;

        if ( $this->_aViewData['updatenav'] ) {
            //skipping requirements checking when reloading nav frame
            oxSession::setVar( "navReload", true );
        }

        //making sure we really change shops on low level
        if ( $soxId && $soxId != '-1' ) {
            $myConfig->setShopId( $soxId );
            oxSession::setVar( 'currentadminshop', $soxId );
        }

        return 'shop_list.tpl';
    }

    /**
     * Sets SQL WHERE condition. Returns array of conditions.
     *
     * @return array
     */
    public function buildWhere()
    {
        // we override this to add our shop if we are not malladmin
        $this->_aWhere = parent::buildWhere();

        $blisMallAdmin = oxSession::getVar( 'malladmin' );
        if ( !$blisMallAdmin) {
            // we only allow to see our shop
            $sShopID = oxSession::getVar( "actshop" );
            $this->_aWhere['oxshops.oxid'] = "$sShopID";
        }

        return $this->_aWhere;
    }

    /**
     * Deletes selected shop files from server.
     *
     * @return null
     */
    public function deleteEntry()
    {
        $myConfig  = $this->getConfig();

        $soxId   = oxConfig::getParameter( "oxid");


        // try to remove directories
        $soxId   = strtr($soxId, "\\/", "__");
        $sTarget = $myConfig->getConfigParam( 'sShopDir' ) . "/out/" . $soxId;
        oxUtilsFile::getInstance()->deleteDir( $sTarget);

        $oDelete = oxNew( "oxShop" );
        $aTables = $myConfig->getConfigParam( 'aMultiShopTables' );
        $oDelete->setMultiShopTables($aTables);


        $oDelete->delete( $soxId );


        // if removing acutally selected shop then switch to shop 1
        if ( $soxId == $myConfig->getShopId()) {
            $sShopId = $myConfig->getBaseShopId();
        } else {
            $sShopId = $myConfig->getShopId();
        }

        $myConfig->setShopId( $sShopId );

        $this->init();
    }
}
