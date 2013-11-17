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
 * $Id: account_password.php 18038 2009-04-09 12:21:40Z arvydas $
 */


/**
 * Current user password change form.
 * When user is logged in he may change his Billing and Shipping
 * information (this is important for ordering purposes).
 * Information as email, password, greeting, name, company, address,
 * etc. Some fields must be entered. OXID eShop -> MY ACCOUNT
 * -> Update your billing and delivery settings.
 */
class Account_Password extends Account
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'account_password.tpl';

    /**
     * Whether the password had been changed.
     *
     * @var bool
     */
    protected $_blPasswordChanged = false;

    /**
     * If user has password (for openid).
     *
     * @var bool
     */
    protected $_blHasPassword = true;

    /**
     * If user is not logged in - returns name of template account_user::_sThisLoginTemplate,
     * or if user is allready logged in additionally loads user delivery address
     * info and forms country list. Returns name of template account_user::_sThisTemplate
     *
     * @return string $_sThisTemplate current template file name
     */
    public function render()
    {
        parent::render();
        //T2008-07-30
        //to maintain compatibility we still set the old template variable using new getter in render
        $this->_aViewData['blpasswordchanged'] = $this->isPasswordChanged();

        // is logged in ?
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }
        if ( $oUser->oxuser__oxisopenid->value == 1 && strpos( $oUser->oxuser__oxpassword->value, 'openid_' ) === 0 ) {
            $this->_blHasPassword = false;
        }

        return $this->_sThisTemplate;
    }

    /**
     * changes current user password
     *
     * @return null
     */
    public function changePassword()
    {
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return;
        }

        $sOldPass  = oxConfig::getParameter( 'password_old' );
        $sNewPass  = oxConfig::getParameter( 'password_new' );
        $sConfPass = oxConfig::getParameter( 'password_new_confirm' );

        try {
            $oUser->checkPassword( $sNewPass, $sConfPass, true );
        } catch ( Exception $oExcp ) {
            switch ( $oExcp->getMessage() ) {
                case 'EXCEPTION_INPUT_EMPTYPASS':
                case 'EXCEPTION_INPUT_PASSTOOSHORT':
                    return oxUtilsView::getInstance()->addErrorToDisplay('ACCOUNT_PASSWORD_ERRPASSWORDTOSHORT', false, true);
                default:
                    return oxUtilsView::getInstance()->addErrorToDisplay('ACCOUNT_PASSWORD_ERRPASSWDONOTMATCH', false, true);
                }
        }

        if ( !$sOldPass || !$oUser->isSamePassword( $sOldPass ) ) {
            oxUtilsView::getInstance()->addErrorToDisplay('ACCOUNT_PASSWORD_ERRINCORRECTCURRENTPASSW', false, true, 'user');
            return;
        }

        // testing passed - changing password
        $oUser->setPassword( $sNewPass );
        if ( $oUser->save() ) {
            $this->_blPasswordChanged = true;
        }
    }

    /**
     * Template variable getter. Returns true when password had been changed.
     *
     * @return bool
     */
    public function isPasswordChanged()
    {
        return $this->_blPasswordChanged;
    }

    /**
     * Template variable getter. Returns true if user has password.
     *
     * @return bool
     */
    public function hasPassword()
    {
        return $this->_blHasPassword;
    }
}
