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
 * @package main
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 */


    /** @name database information */
        $this->dbHost = '<dbHost_ce>'; // database host name
        $this->dbName = '<dbName_ce>'; // database name
        $this->dbUser = '<dbUser_ce>'; // database user name
        $this->dbPwd  = '<dbPwd_ce>'; // database user password
        $this->dbType = 'mysql';
        $this->sShopURL     = '<sShopURL_ce>';
        $this->sSSLShopURL  = null;
        $this->sAdminSSLURL = null;
        $this->sShopDir     = '<sShopDir_ce>';
        $this->sCompileDir  = '<sCompileDir_ce>';

    $this->sTheme = 'basic';

    // utf mode in shop 0 - off, 1 - on
    $this->iUtfMode  = '<iUtfMode>';

    //File type whitelist for file upload
    $this->aAllowedUploadTypes = array('jpg', 'gif', 'png', 'pdf', 'mp3', 'avi', 'mpg', 'mpeg', 'doc', 'xls', 'ppt');

    // timezone information
    date_default_timezone_set('Europe/Berlin');

    // Search engine friendly URL processor
    // After changing this value, you should rename oxid.php file as well
    // Always leave .php extension here unless you know what you are doing
    $this->sOXIDPHP = "oxid.php";

    //  enable debug mode for template development or bugfixing
    // -1 = Logger Messages internal use only
    //  0 = off
    //  1 = smarty
    //  2 = SQL
    //  3 = SQL + smarty
    //  4 = SQL + smarty + shoptemplate data
    //  5 = Delivery Cost calculation info
    //  6 = SMTP Debug Messages
    //  7 = oxDbDebug SQL parser
    $this->iDebug = 0;

    // Log all modifications performed in Admin
    $this->blLogChangesInAdmin = 0;

    $this->sAdminEmail = '';

    // Use browser cookies to store session id (no sid parameter in URL)
    $this->blSessionUseCookies = 1;
    // Force user to use cookies (no checkout without cookie support)
    $this->blSessionEnforceCookies = 1;

    // uncomment the following line if you want euro sign leave unchanged in output
    // the default is to convert euro sign symbol to html entity
    // $this->blSkipEuroReplace = true;


    // List of all Search-Engine Robots
    $this->aRobots = array(
                        'googlebot',
                        'ultraseek',
                        'crawl',
                        'spider',
                        'fireball',
                        'robot',
                        'spider',
                        'robot',
                        'slurp',
                        'fast',
                        'altavista',
                        'teoma',
                        );

    // Deactivate Static URL's for these Robots
    $this->aRobotsExcept = array();

    // Only for former templates: Fixed Shop Width
    $this->blFixedWidthLayout = 1;

    // set this parameter when your shop runs on different subdomains in ssl/non ssl mode
    // e.g. if you setup "ssl.shop.com"/"www.shop.com" - config value should be ".shop.com"
    $this->sCookieDomain = null;

