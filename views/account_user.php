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
 * @package   views
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: account_user.php 26071 2010-02-25 15:12:55Z sarunas $
 */

/**
 * Current user Data Maintenance form.
 * When user is logged in he may change his Billing and Shipping
 * information (this is important for ordering purposes).
 * Information as email, password, greeting, name, company, address
 * etc. Some fields must be entered. OXID eShop -> MY ACCOUNT
 * -> Update your billing and delivery settings.
 */
class Account_User extends Account
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'account_user.tpl';

    /**
     * returns Deliver Adress.
     *
     * @var oxbase
     */
    protected $_oDelAddress = null;

    /**
     * returns Country.
     *
     * @var oxcountrylist
     */
    protected $_oCountryList = null;

    /**
     * If user is not logged in - returns name of template account_user::_sThisLoginTemplate,
     * or if user is allready logged in additionally loads user delivery address
     * info and forms country list. Returns name of template account_user::_sThisTemplate
     *
     * Template variables:
     * <b>oxcountrylist</b>, <b>aMustFillFields</b>, <b>delivadr</b>
     *
     * @return  string  $_sThisTemplate current template file name
     */
    public function render()
    {
        parent::render();

        // is logged in ?
        if ( !( $this->getUser() ) ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        //for older templates
        $this->_aViewData['delivadr']        = $this->getDeliverAddress();
        $this->_aViewData['oxcountrylist']   = $this->getCountryList();
        $this->_aViewData['aMustFillFields'] = $this->getMustFillFields();

        return $this->_sThisTemplate;
    }

    /**
     * Return deliver address
     *
     * @return oxAddress | null
     */
    public function getDeliverAddress()
    {
        // is logged in ?
        if ( $oUser = $this->getUser() ) {
            $oAdresses = $oUser->getUserAddresses();
            if ( $oAdresses->count() ) {
                foreach ( $oAdresses as $oAddress ) {
                    if ( $oAddress->selected == 1 ) {
                        $this->_aViewData['deladr'] = null;
                        return $oAddress;
                    }
                }
                $oAdresses->rewind();
                return $oAdresses->current();
            }
        }
    }

    /**
     * Return country list
     *
     * @return oxcountrylist
     */
    public function getCountryList()
    {
        if ( $this->_oCountryList === null ) {
            // passing country list
            $this->_oCountryList = oxNew( 'oxcountrylist' );
            $this->_oCountryList->loadActiveCountries();
        }
        return $this->_oCountryList;
    }
}
