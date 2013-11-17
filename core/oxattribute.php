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
 * $Id: oxattribute.php 16303 2009-02-05 10:23:41Z rimvydas.paskevicius $
 */

/**
 * Article attributes manager.
 * Collects and keeps attributes of chosen article.
 * @package core
 */
class oxAttribute extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxattribute';

    /**
     * Object core table name
     *
     * @var string
     */
    protected $_sCoreTbl = 'oxattribute';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( $this->_sCoreTbl);
    }

    /**
     * Removes attributes from articles, returns true on success.
     *
     * @param string $sOXID Object ID
     *
     * @return bool
     */
    public function delete( $sOXID = null )
    {
        if( !$sOXID)
            $sOXID = $this->getId();
        if( !$sOXID)
            return false;


        // remove attributes from articles also
        $oDB = oxDb::getDb();
        $sDelete = "delete from oxobject2attribute where oxattrid = '$sOXID' ";
        $rs = $oDB->execute( $sDelete);

        // #657 ADDITIONAL removes attribute connection to category
        $sDelete = "delete from oxcategory2attribute where oxattrid = '$sOXID' ";
        $rs = $oDB->execute( $sDelete);

        return parent::delete( $sOXID);
    }
}
