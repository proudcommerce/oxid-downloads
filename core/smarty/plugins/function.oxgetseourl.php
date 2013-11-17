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
 * $Id: function.oxgetseourl.php 16303 2009-02-05 10:23:41Z rimvydas.paskevicius $
 */


/*
* Smarty function
* -------------------------------------------------------------
* Purpose: output SEO style url
* add [{ oxgetseourl ident="..." }] where you want to display content
* -------------------------------------------------------------
*/
function smarty_function_oxgetseourl( $params, &$smarty )
{
    $sOxid = isset( $params['oxid'] ) ? $params['oxid'] : null;
    $sType = isset( $params['type'] ) ? $params['type'] : null;
    $sUrl  = $sIdent = isset( $params['ident'] ) ? $params['ident'] : null;

    // requesting specified object SEO url
    if ( $sType ) {
        $oObject = oxNew( $sType );

        // special case for content type object when ident is provided
        if ( $sType == 'oxcontent' && $sIdent && $oObject->loadByIdent( $sIdent ) ) {
            $sUrl = $oObject->getLink();
        } elseif ( $sOxid && $oObject->load( $sOxid ) ) {
            $sUrl = $oObject->getLink();
        }
    } elseif ( $sUrl && oxUtils::getInstance()->seoIsActive() ) {
        // if SEO is on ..
        $oEncoder = oxSeoEncoder::getInstance();
        if ( ( $sStaticUrl = $oEncoder->getStaticUrl( $sUrl ) ) ) {
            $sUrl = $sStaticUrl;
        }
    }

    $sDynParams = isset( $params['params'] )?$params['params']:false;
    if ( $sDynParams ) {
        require_once $smarty->_get_plugin_filepath( 'modifier','oxaddparams' );
        $sUrl = smarty_modifier_oxaddparams( $sUrl, $sDynParams );
    }

    return $sUrl;
}
