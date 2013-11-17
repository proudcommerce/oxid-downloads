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
 * @package core
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxvoucherserie.php 16303 2009-02-05 10:23:41Z rimvydas.paskevicius $
 */

/**
 * Voucher serie manager.
 * Manages list of available Vouchers (fetches, deletes, etc.).
 * @package core
 */
class oxVoucherSerie extends oxBase
{

    /**
     * User groups array (default null).
     * @var object
     */
    protected $_oGroups = null;

    /**
     * @var string name of object core table
     */
    protected $_sCoreTbl = 'oxvoucherseries';

    /**
     * @var string name of current class
     */
    protected $_sClassName = 'oxvoucherserie';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( $this->_sCoreTbl);
    }

    /**
     * Override delete function so we can delete user group relations first.
     *
     * @param string $sOxId object ID (default null)
     *
     * @return null
     */
    public function delete( $sOxId = null )
    {

        $this->unsetUserGroups();
        $this->deleteVoucherList();
        parent::delete( $sOxId );
    }

    /**
     * Collects and returns user group list.
     *
     * @return object
     */
    public function setUserGroups()
    {
        if ( $this->_oGroups === null ) {
            $this->_oGroups = oxNew( 'oxlist' );
            $this->_oGroups->init( 'oxgroups' );
            $sSelect  = 'select gr.* from oxgroups as gr, oxobject2group as o2g where ';
            $sSelect .= 'o2g.oxobjectid = "'. $this->getId() .'" and gr.oxid = o2g.oxgroupsid ';
            $this->_oGroups->selectString( $sSelect );
        }

        return $this->_oGroups;
    }

    /**
     * Removes user groups relations.
     *
     * @return null
     */
    public function unsetUserGroups()
    {
        $sDelete = 'delete from oxobject2group where oxobjectid = "' . $this->getId() . '"';
        oxDb::getDb()->execute( $sDelete );
    }

    /**
     * Returns array of a vouchers assigned to this serie.
     *
     * @return array
     */
    public function getVoucherList()
    {
        $oVoucherList = oxNew( 'oxvoucherlist' );
        $sSelect = 'select * from oxvouchers where oxvoucherserieid = "' . $this->getId() . '"';
        $oVoucherList->selectString( $sSelect );
        return $oVoucherList;
    }

    /**
     * Deletes assigned voucher list.
     *
     * @return null
     */
    public function deleteVoucherList()
    {
        $sDelete = 'delete from oxvouchers where oxvoucherserieid = "' . $this->getId() . '"';
        oxDb::getDb()->execute( $sDelete );
    }

    /**
     * Returns array of vouchers counts.
     *
     * @return array
     */
    public function countVouchers()
    {
        $aStatus = array();

        $oDB = oxDb::getDb();
        $sQuery = 'select count(*) as total from oxvouchers where oxvoucherserieid = "' . $this->getId() . '"';
        $aStatus['total'] = $oDB->getOne( $sQuery );

        $sQuery = 'select count(*) as used from oxvouchers where oxvoucherserieid = "' . $this->getId()  . '" and oxorderid is not NULL and oxorderid != ""';
        $aStatus['used'] = $oDB->getOne( $sQuery );

        $aStatus['available'] = $aStatus['total'] - $aStatus['used'];

        return $aStatus;
    }
}
