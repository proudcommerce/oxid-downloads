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
 * $Id: modifier.oxmultilangassign.php 17246 2009-03-16 15:18:58Z arvydas $
 */

/*
* Smarty function
* -------------------------------------------------------------
* Purpose: Output multilang string for admin
* add [{ oxmultilang ident="..." }] where you want to display content
* -------------------------------------------------------------
*/
function smarty_modifier_oxmultilangassign( $sIdent )
{
    if ( !isset( $sIdent ) ) {
        $sIdent = 'IDENT MISSING';
    }

    $oLang = oxLang::getInstance();
    $iLang = $oLang->getTplLanguage();

    if ( !isset( $iLang ) ) {
        $iLang = $oLang->getBaseLanguage();
        if ( !isset( $iLang ) ) {
            $iLang = 0;
        }
    }

    try {
        $sTranslation = $oLang->translateString( $sIdent, $iLang, isAdmin() );
    } catch ( oxLanguageException $oEx ) {
        // is thrown in debug mode and has to be caught here, as smarty hangs otherwise!
    }

    if ( $sTranslation == $sIdent ) {
        $sTranslation = '<b>ERROR : Translation for '.$sIdent.' not found!</b>';
    }

    return $sTranslation;
}
