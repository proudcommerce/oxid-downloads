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
 * $Id: oxopenidhttpfetcher.php 23173 2009-10-12 13:29:45Z sarunas $
 */

require_once "Auth/Yadis/HTTPFetcher.php";
require_once "Auth/OpenID.php";

/**
 * Auth_Yadis_ParanoidHTTPFetcher class wrapper.
 *
 * @package core
 */
class oxOpenIdHTTPFetcher extends Auth_Yadis_ParanoidHTTPFetcher
{
    /**
     * Performs GET action with given parameters. Returns response from opendid server
     *
     * @param object $url           url to execute
     * @param object $extra_headers additional post headers [optional]
     *
     * @return mixed
     */
    public function get($url, $extra_headers = null)
    {
        if (!$this->canFetchURL($url)) {
            return null;
        }

        $stop = time() + $this->timeout;
        $off = $this->timeout;

        $redir = true;

        while ($redir && ($off > 0)) {
            $this->reset();

            $c = curl_init();

            if ($c === false) {
                Auth_OpenID::log(
                    "curl_init returned false; could not " .
                    "initialize for URL '%s'", $url);
                return null;
            }

            if (defined('CURLOPT_NOSIGNAL')) {
                curl_setopt($c, CURLOPT_NOSIGNAL, true);
            }

            if (!$this->allowedURL($url)) {
                Auth_OpenID::log("Fetching URL not allowed: %s",
                                 $url);
                return null;
            }

            curl_setopt($c, CURLOPT_WRITEFUNCTION,
                        array(&$this, "_writeData"));
            curl_setopt($c, CURLOPT_HEADERFUNCTION,
                        array(&$this, "_writeHeader"));

            if ($extra_headers) {
                curl_setopt($c, CURLOPT_HTTPHEADER, $extra_headers);
            }

            $cv = curl_version();
            if ( is_array( $cv ) ) {
                $curl_user_agent = 'curl/'.$cv['version'];
            } else {
                $curl_user_agent = $cv;
            }

            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($c, CURLOPT_USERAGENT,
                        Auth_OpenID_USER_AGENT.' '.$curl_user_agent);
            curl_setopt($c, CURLOPT_TIMEOUT, $off);
            curl_setopt($c, CURLOPT_URL, $url);

            curl_exec($c);

            $code = curl_getinfo($c, CURLINFO_HTTP_CODE);
            $body = $this->data;
            $headers = $this->headers;

            if (!$code) {
                Auth_OpenID::log("Got no response code when fetching %s", $url);
                Auth_OpenID::log("CURL error (%s): %s",
                                 curl_errno($c), curl_error($c));
                return null;
            }

            if (in_array($code, array(301, 302, 303, 307))) {
                $url = $this->_findRedirect($headers);
                $redir = true;
            } else {
                $redir = false;
                curl_close($c);

                $new_headers = array();

                foreach ($headers as $header) {
                    if (strpos($header, ': ')) {
                        list($name, $value) = explode(': ', $header, 2);
                        $new_headers[$name] = $value;
                    }
                }

                Auth_OpenID::log(
                    "Successfully fetched '%s': GET response code %s",
                    $url, $code);

                return new Auth_Yadis_HTTPResponse($url, $code,
                                                    $new_headers, $body);
            }

            $off = $stop - time();
        }

        return null;
    }

    /**
     * Performs POST action with given parameters. Returns response from opendid server
     *
     * @param object $url           url to execute
     * @param object $body          post body
     * @param object $extra_headers additional post headers [optional]
     *
     * @return mixed
     */
    public function post($url, $body, $extra_headers = null)
    {
        if (!$this->canFetchURL($url)) {
            return null;
        }

        $this->reset();

        $c = curl_init();

        if (defined('CURLOPT_NOSIGNAL')) {
            curl_setopt($c, CURLOPT_NOSIGNAL, true);
        }

        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $body);
        curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_WRITEFUNCTION,
                    array(&$this, "_writeData"));

        curl_exec($c);

        $code = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if (!$code) {
            Auth_OpenID::log("Got no response code when fetching %s", $url);
            return null;
        }

        $body = $this->data;

        curl_close($c);

        $new_headers = $extra_headers;

        foreach ($this->headers as $header) {
            if (strpos($header, ': ')) {
                list($name, $value) = explode(': ', $header, 2);
                $new_headers[$name] = $value;
            }

        }

        Auth_OpenID::log("Successfully fetched '%s': POST response code %s",
                         $url, $code);

        return new Auth_Yadis_HTTPResponse($url, $code,
                                           $new_headers, $body);
    }

    /**
     * Returns TRUE if ssl is supported by curl
     *
     * @return bool
     */
    protected function _isSSL()
    {
        $v = curl_version();
        if ( is_array( $v ) ) {
            return in_array('https', $v['protocols']);
        }
        return false;
    }
}
