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
 * $Id: contact.php 18040 2009-04-09 12:22:44Z arvydas $
 */

/**
 * Contact window.
 * Arranges "CONTACT" window, by creating form for user opinion (etc.)
 * submission. After user correctly
 * fulfils all required fields all information is sent to shop owner by
 * email. OXID eShop -> CONTACT.
 */
class Contact extends oxUBase
{
    /**
     * Entered user data.
     * @var array
     */
    protected $_aUserData = null;

    /**
     * Entered contact subject.
     * @var string
     */
    protected $_sContactSubject = null;

    /**
     * Entered conatct message.
     * @var string
     */
    protected $_sContactMessage = null;

    /**
     * Class handling CAPTCHA image.
     * @var object
     */
    protected $_oCaptcha = null;

    /**
     * Contact email send status.
     * @var object
     */
    protected $_blContactSendStatus = null;

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'contact.tpl';

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * Executes parent::render(), loads action articles and sets parameters
     * (subject, message) used by template engine. Returns name
     * of template to render contact::_sThisTemplate
     *
     * Template variables:
     * <b>c_subject</b>, <b>c_message</b>, <b>editval</b>, <b>useantispam</b>
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['editval']   = $this->getUserData();
        $this->_aViewData['c_subject'] = $this->getContactSubject();
        $this->_aViewData['c_message'] = $this->getContactMessage();

        //captcha
        $this->_aViewData['oCaptcha'] = $this->getCaptcha();
        $this->_aViewData['success']  = $this->getContactSendStatus();

        return $this->_sThisTemplate;
    }

    /**
     * Composes and sends user written message, returns false if some parameters
     * are missing.
     *
     * Template variables:
     * <b>error</b>, <b>success</b>
     *
     * @return bool
     */
    public function send()
    {
        // spam spider prevension
        $sMac     = oxConfig::getParameter( 'c_mac' );
        $sMacHash = oxConfig::getParameter( 'c_mach' );
        $oCaptcha = oxNew('oxCaptcha');
        if ( !$oCaptcha->pass($sMac, $sMacHash ) ) {
            // even if there is no exception, use this as a default display method
            oxUtilsView::getInstance()->addErrorToDisplay( 'EXCEPTION_INPUT_NOTALLFIELDS' );
            return false;
        }

        $aParams  = oxConfig::getParameter( 'editval' );
        $sSubject = oxConfig::getParameter( 'c_subject' );
        $sBody    = oxConfig::getParameter( 'c_message' );
        if ( !$aParams['oxuser__oxfname'] || !$aParams['oxuser__oxlname'] || !$aParams['oxuser__oxusername'] || !$sSubject ) {
            // even if there is no exception, use this as a default display method
            oxUtilsView::getInstance()->addErrorToDisplay( 'EXCEPTION_INPUT_NOTALLFIELDS' );
            return false;
        }

        $sMessage  = oxLang::getInstance()->translateString( 'CONTACT_FROM' )." ".$aParams['oxuser__oxsal']." ".$aParams['oxuser__oxfname']." ".$aParams['oxuser__oxlname']."(".$aParams['oxuser__oxusername'].")\n\n";
        $sMessage .= nl2br( $sBody );

        $oEmail = oxNew( 'oxemail' );
        if ( $oEmail->sendContactMail( $aParams['oxuser__oxusername'], $sSubject, $sMessage ) ) {
            $this->_blContactSendStatus = 1;
        }
    }

    /**
     * Template variable getter. Returns entered user data
     *
     * @return object
     */
    public function getUserData()
    {
        if ( $this->_oUserData === null ) {
            $this->_oUserData = oxConfig::getParameter( 'editval' );
        }
        return $this->_oUserData;
    }

    /**
     * Template variable getter. Returns entered user data
     *
     * @return object
     */
    public function getContactSubject()
    {
        if ( $this->_sContactSubject === null ) {
            $this->_sContactSubject = oxConfig::getParameter( 'c_subject' );
        }
        return $this->_sContactSubject;
    }

    /**
     * Template variable getter. Returns entered user data
     *
     * @return object
     */
    public function getContactMessage()
    {
        if ( $this->_sContactMessage === null ) {
            $this->_sContactMessage = oxConfig::getParameter( 'c_message' );
        }
        return $this->_sContactMessage;
    }

    /**
     * Template variable getter. Returns object of handling CAPTCHA image
     *
     * @return object
     */
    public function getCaptcha()
    {
        if ( $this->_oCaptcha === null ) {
            $this->_oCaptcha = oxNew('oxCaptcha');
        }
        return $this->_oCaptcha;
    }

    /**
     * Template variable getter. Returns status if email was send succesfull
     *
     * @return object
     */
    public function getContactSendStatus()
    {
        return $this->_blContactSendStatus;
    }
}
