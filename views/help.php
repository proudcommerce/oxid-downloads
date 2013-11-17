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
 * @package views
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: help.php 17315 2009-03-17 16:18:58Z arvydas $
 */

/**
 * Shop help window.
 * Arranges shop help information window, with help texts. (Help
 * text may be changed in file (shop directory) -> help ->
 * default.inc.tpl ). OXID eShop -> HELP.
 */
class Help extends oxUBase
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'help.tpl';

    /**
     * Help text
     * @var string
     */
    protected $_sHelpText = null;

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * Loads help text, executes parent::render() and returns name
     * of template file to render help::_sThisTemplate.
     *
     * Template variables:
     * <b>helptext</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        $this->_aViewData["helptext"] = $this->getHelpText();

        parent::render();

        return $this->_sThisTemplate;
    }

    /**
     * Template variable getter. Returns help text
     *
     * @return string
     */
    public function getHelpText()
    {
        if ( $this->_sHelpText === null ) {
            $this->_sHelpText = false;
            $sLang = oxLang::getInstance()->getTplLanguage();
            $sTpl  = basename( oxConfig::getParameter( 'tpl' ) );
            $sHelpPage = $sTpl?$sTpl:oxConfig::getParameter( 'page' );

            $sHelpText = null;
            $sHelpPath = getShopBasePath()."help/{$sLang}/{$sHelpPage}.inc.tpl";
            if ( $sHelpPage && is_readable( $sHelpPath ) ) {
                $sHelpText = file_get_contents( $sHelpPath );
            }

            if ( !$sHelpText ) {
                $sHelpPath = getShopBasePath()."help/{$sLang}/default.inc.tpl";
                if ( is_readable( $sHelpPath ) ) {
                    $sHelpText = file_get_contents( $sHelpPath );
                }
            }

            if ( $sHelpText ) {
                $this->_sHelpText = $sHelpText;
            }
        }
        return $this->_sHelpText;
    }
}
