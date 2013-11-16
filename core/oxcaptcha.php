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
 * @version   SVN: $Id: oxcaptcha.php 28103 2010-06-02 14:24:32Z michael.keiluweit $
 */

/**
 * Class handling CAPTCHA image
 * This class requires utility file utils/verificationimg.php as image generator
 *
 */
class oxCaptcha extends oxSuperCfg
{
    /**
     * CAPTCHA length
     *
     * @var int
     */
    protected $_iMacLength = 5;

    /**
     * Captcha text
     *
     * @var string
     */
    protected $_sText = null;

    /**
     * Possible CAPTCHA chars, no ambiguities
     *
     * @var string
     */
    private $_sMacChars  = 'abcdefghijkmnpqrstuvwxyz23456789';

    /**
     * Returns text
     *
     * @return string
     */
    public function getText()
    {
        if (!$this->_sText) {
            for ( $i=0; $i < $this->_iMacLength; $i++ ) {
                $this->_sText .= strtolower($this->_sMacChars{ rand( 0, strlen($this->_sMacChars) - 1 ) });
            }
        }


        return $this->_sText;
    }

    /**
     * Returns text hash
     *
     * @param string $sText User supplie text
     *
     * @return string
     */
    public function getHash($sText = null)
    {
        if (!$sText) {
            $sText = $this->getText();
        }

        $sText = strtolower($sText);

        return md5( "ox{$sText}" );
    }

    /**
     * Returns url to CAPTCHA image generator.
     *
     * @return string
     */
    public function getImageUrl()
    {
        $sUrl = $this->getConfig()->getCoreUtilsURL() . "verificationimg.php?e_mac=";
        $sUrl .= oxUtils::getInstance()->strMan( $this->getText() );

        return $sUrl;
    }

    /**
     * Checks if image could be generated
     *
     * @return bool
     */
    public function isImageVisible()
    {
        return (( function_exists('imagecreatetruecolor') || function_exists( 'imagecreate' ) ) && $this->getConfig()->getConfigParam( 'iUseGDVersion' ) > 1 );
    }

    /**
     * Verifies captcha input vs supplied hash. Returns true on success.
     *
     * @param string $sMac     User supplie text
     * @param string $sMacHash Generated hash
     *
     * @return bool
     */
    public function pass($sMac, $sMacHash)
    {
        return strlen( $sMacHash ) == 32 && $this->getHash($sMac) == $sMacHash;
    }
}

