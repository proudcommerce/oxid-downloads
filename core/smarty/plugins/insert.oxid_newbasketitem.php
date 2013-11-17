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
 * @package smartyPlugins
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: insert.oxid_newbasketitem.php 23250 2009-10-14 13:40:12Z alfonsas $
 */

/*
* Smarty plugin
* -------------------------------------------------------------
* File: insert.oxid_newbasketitem.php
* Type: string, html
* Name: newbasketitem
* Purpose: Used for tracking in econda, etracker etc.
* -------------------------------------------------------------
*/
function smarty_insert_oxid_newbasketitem($params, &$smarty)
{
    $myConfig  = oxConfig::getInstance();

    $aTypes = array('0' => 'none','1' => 'message', '2' =>'popup', '3' =>'basket');
    $iType  = $myConfig->getConfigParam( 'iNewBasketItemMessage' );

    // If corect type of message is expected
    if($iType && $params['type'] && $params['type'] != $aTypes[$iType] ){
        return '';
    }

    //name of template file where is stored message text
    $sTemplate = $params['tpl']?$params['tpl']:'inc_newbasketitem.snippet.tpl';

    //allways render for ajaxstyle popup
    $blRender = $params['ajax'];

    //fetching article data
    $oNewItem = oxSession::getVar( '_newitem' );
    $oBasket  = oxSession::getInstance()->getBasket();

    if ( $oNewItem ) {
        // loading article object here because on some system passing article by session couses problems
        $oNewItem->oArticle = oxNew( 'oxarticle' );
        $oNewItem->oArticle->Load( $oNewItem->sId );

        // passing variable to template with unique name
        $smarty->assign( '_newitem', $oNewItem );

        // deleting article object data
        oxSession::deleteVar( '_newitem' );

        $blRender = true;
    }

    // returning generated message content
    if( $blRender ) {
        return $smarty->fetch( $sTemplate );
    }
}
