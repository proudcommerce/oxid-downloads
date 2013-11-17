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
 * @version   SVN: $Id: oxopenidgenericconsumer.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

require_once "Auth/OpenID/Consumer.php";
require_once "Auth/Yadis/Manager.php";
require_once "Auth/Yadis/PlainHTTPFetcher.php";

/**
 * Auth_OpenID_GenericConsumer class wrapper.
 *
 * @package core
 */
class oxOpenIdGenericConsumer extends Auth_OpenID_GenericConsumer
{
    /**
     * This method initializes a new {@link Auth_OpenID_Consumer}
     * instance to access the library.
     *
     * @param object &$store an object that implements the interface in Auth_OpenID_OpenIDStore.
     *
     * @return null
     */
    public function oxOpenIdGenericConsumer( &$store )
    {
        $this->store =& $store;
        $this->negotiator =& Auth_OpenID_getDefaultNegotiator();
        $this->_use_assocs = ($this->store ? true : false);

        $this->fetcher = $this->getHTTPFetcher();

        $this->session_types = Auth_OpenID_getAvailableSessionTypes();
    }

    /**
     * Returns an HTTP fetcher object.
     *
     * @param int $iTimeout timeout
     *
     * @return mixed
     */
    public function getHTTPFetcher( $iTimeout = 20 )
    {
        if (Auth_Yadis_Yadis::curlPresent() &&
            (!defined('Auth_Yadis_CURL_OVERRIDE'))) {
            $fetcher = new oxOpenIdHTTPFetcher( $iTimeout );
        } else {
            $fetcher = new Auth_Yadis_PlainHTTPFetcher( $iTimeout );
        }
        return $fetcher;
    }

}
