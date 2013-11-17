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
 * $Id: oxcountry.php 16303 2009-02-05 10:23:41Z rimvydas.paskevicius $
 */


/**
 * @package core
 */
class oxCountry extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxcountry';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxcountry' );
    }

    /**
     * returns true if this country is a foreign country
     *
     * @return bool
     */
    public function isForeignCountry()
    {
        return !in_array($this->getId(), $this->getConfig()->getConfigParam( 'aHomeCountry' ));
    }

    /**
     * returns true if this country is marked as EU
     *
     * @return bool
     */
    public function isInEU()
    {
        return (bool) ($this->oxcountry__oxvatstatus->value == 1);
    }
}
