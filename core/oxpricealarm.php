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
 * @package   core
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: oxpricealarm.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

/**
 * Pricealarm manager.
 * Performs Pricealarm data/objetcs loading, deleting.
 * @package core
 */
class oxPricealarm extends oxBase
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxpricealarm';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()), loads
     * base shop objects.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxpricealarm' );
    }

    /**
     * Inserts object data into DB, returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {
        // set oxinsert value
        $this->oxpricealarm__oxinsert = new oxField(date( 'Y-m-d', oxUtilsDate::getInstance()->getTime() ));

        return parent::_insert();
    }
}
