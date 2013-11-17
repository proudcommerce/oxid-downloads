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
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxopenidsession.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

/**
 * oxSession wrapper for OpenId
 * Performs session managing function, such as variables deletion,
 * initialisation and other session functions.
 *
 * @package core
 */
class oxOpenIdSession extends oxSession
{

    /**
     * Singleton instance keeper.
     */
    protected static $_instance = null;

    /**
     * get oxOpenIdSession object instance (create if needed)
     *
     * @return oxOpenIdSession
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance  = oxNew( 'oxOpenIdSession' );
        }
        return self::$_instance;
    }

    /**
     * Calls parent::setVar
     *
     * @param string $name  Name of parameter to store
     * @param mixed  $value Value of parameter
     *
     * @return null
     */
    public function set( $name, $value)
    {
        parent::setVar( $name, $value);
    }

    /**
     * Calls parent::getVar
     *
     * @param string $name Name of parameter
     *
     * @return mixed
     */
    public function get( $name )
    {
        return parent::getVar( $name );
    }

    /**
     * Calls parent::deleteVar
     *
     * @param string $name Name of parameter to destroy
     *
     * @return null
     */
    public function del( $name )
    {
        parent::deleteVar( $name );
    }

}
