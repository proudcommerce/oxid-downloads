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
 * $Id: register.php 19252 2009-05-21 07:52:04Z arvydas $
 */

/**
 * User registration window.
 * Collects and arranges user object data (information, like shipping address, etc.).
 */
class Register extends User
{
    /**
     * Current class template.
     *
     * @var string
     */
    protected $_sThisTemplate = 'register.tpl';

    /**
     * Order step marker
     * @var bool
     */
    protected $_blIsOrderStep = false;

    /**
     * Successful registration confirmation template
     *
     * @var string
     */
    protected $_sSuccessTemplate = 'register_success.tpl';

    /**
     * Array of fields which must be filled during registration
     *
     * @var array
     */
    protected $_aMustFillFields = null;

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * Executes parent::render(), passes error code to template engine,
     * returns name of template to render register::_sThisTemplate.
     *
     * @return string   current template file name
     */
    public function render()
    {
        parent::render();

        // checking registration status
        if ( $this->getRegistrationStatus() ) {

            //for older templates
            $this->_aViewData['error']   = $this->getRegistrationError();
            $this->_aViewData['success'] = $this->getRegistrationStatus();

            return $this->_sSuccessTemplate;
        }

        $this->_aViewData['aMustFillFields'] = $this->getMustFillFields();

        return $this->_sThisTemplate;
    }

    /**
     * Returns registration error code (if it was set)
     *
     * @return int | null
     */
    public function getRegistrationError()
    {
        return oxConfig::getParameter( 'newslettererror' );
    }

    /**
     * Return registration status (if it was set)
     *
     * @return int | null
     */
    public function getRegistrationStatus()
    {
        return oxConfig::getParameter( 'success' );
    }

    /**
     * Returns array of fields which must be filled during registration
     *
     * @return array | bool
     */
    public function getMustFillFields()
    {
        if ( $this->_aMustFillFields === null ) {
            $this->_aMustFillFields = false;

            // passing must-be-filled-fields info
            $aMustFillFields = $this->getConfig()->getConfigParam( 'aMustFillFields' );
            if ( is_array( $aMustFillFields ) ) {
                $this->_aMustFillFields = array_flip( $aMustFillFields );
            }
        }
        return $this->_aMustFillFields;
    }

    /**
     * Return deliver address
     *
     * @return oxbase | false
     */
    public function getDelAddress()
    {
        // is logged in ?
        if ( $this->_oDelAddress === null ) {
            $this->_oDelAddress = false;

            if ( $oUser = $this->getUser() ) {
                $sAddressId = $oUser->getSelectedAddress();
                if ( $sAddressId && $sAddressId != '-1' ) {
                    $this->_oDelAddress = oxNew( 'oxbase' );
                    $this->_oDelAddress->init( 'oxaddress' );
                    $this->_oDelAddress->load( $sAddressId );
                }
            }
        }

        return $this->_oDelAddress;
    }

    /**
     * Generats facke address for selection
     *
     * @param object $oAddresses user address list
     *
     * @return null
     */
    protected function _addFakeAddress( $oAddresses )
    {
    }

}