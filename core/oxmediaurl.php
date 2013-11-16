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
 * @version   SVN: $Id: oxmediaurl.php 38536 2011-09-05 09:02:38Z linas.kukulskis $
 */

/**
 * Media URL handler
 *
 * @package core
 */
class oxMediaUrl extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxmediaurls';

    /**
     * Class constructor, initiates parent constructor (parent::oxI18n()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxmediaurls' );
    }

    /**
     * Return HTML code depending on current URL
     *
     * @return string
     */
    public function getHtml()
    {
        $sUrl = $this->oxmediaurls__oxurl->value;
        //youtube link
        if (strpos($sUrl, 'youtube.com')) {
            return $this->_getYoutubeHtml();
        }

        //simple link
        return $this->getHtmlLink();
    }

    /**
     * Returns simple HTML link
     *
     * @param bool $blNewPage Whether to open link in new window (adds target=_blank to link)
     *
     * @return string
     */
    public function getHtmlLink( $blNewPage = true )
    {
        $sForceBlank = $blNewPage ? ' target="_blank"' : '';
        $sDesc = $this->oxmediaurls__oxdesc->value;
        $sUrl = $this->getLink();

        $sHtmlLink = "<a href=\"$sUrl\"{$sForceBlank}>$sDesc</a>";

        return $sHtmlLink;
    }

    /**
     * Returns  link
     *
     * @return string
     */
    public function getLink()
    {
        if ( $this->oxmediaurls__oxisuploaded->value ) {
            $sUrl = $this->getConfig()->isSsl() ? $this->getConfig()->getSslShopUrl() : $this->getConfig()->getShopUrl();
            $sUrl .= 'out/media/';
            $sUrl .= $this->oxmediaurls__oxurl->value;
        } else {
            $sUrl = $this->oxmediaurls__oxurl->value;
        }

        return $sUrl;
    }

    /**
     * Deletes record and unlinks the file
     *
     * @param string $sOXID Object ID(default null)
     *
     * @return bool
     */
    public function delete( $sOXID = null )
    {
        $sFilePath = $this->getConfig()->getConfigParam('sShopDir') . "/out/media/" .
                     $this->oxmediaurls__oxurl->value;

        if ($this->oxmediaurls__oxisuploaded->value && file_exists($sFilePath)) {
            unlink($sFilePath);
        }

        return parent::delete( $sOXID );
    }

    /**
     * Transforms the link to YouTube object, and returns it.
     *
     * @return string
     */
    protected function _getYoutubeHtml()
    {
        $sUrl = $this->oxmediaurls__oxurl->value;
        $sDesc = $this->oxmediaurls__oxdesc->value;

        //http://www.youtube.com/watch?v=J0oE3X4cgIc&feature=related
        //to
        //<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/J0oE3X4cgIc&hl=en"></param><embed src="http://www.youtube.com/v/Fx--jNQYNgA&hl=en" type="application/x-shockwave-flash" width="425" height="344"></embed></object>

        $sYoutubeUrl = str_replace("www.youtube.com/watch?v=", "www.youtube.com/v/", $sUrl);

        $sYoutubeTemplate = '%s<br><object type="application/x-shockwave-flash" data="%s" width="425" height="344"><param name="movie" value="%s"></object>';
        $sYoutubeHtml = sprintf($sYoutubeTemplate, $sDesc, $sYoutubeUrl, $sYoutubeUrl);

        return $sYoutubeHtml;
    }

}
