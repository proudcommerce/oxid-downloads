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
 * $Id: user.php 18045 2009-04-09 12:26:14Z arvydas $
 */

/**
 * User details.
 * Collects and arranges user object data (information, like shipping address, etc.).
 */
class User extends oxUBase
{
    /**
     * Current class template.
     * @var string
     */
    protected $_sThisTemplate = 'user.tpl';

    /**
     * Order step marker
     * @var bool
     */
    protected $_blIsOrderStep = true;

    /**
     * Must be filled fields
     * @var array
     */
    protected $_aMustFillFields = null;

    /**
     * Revers of option blOrderDisWithoutReg
     * @var array
     */
    protected $_blShowNoRegOpt = null;

    /**
     * Active user
     * @var object
     */
    protected $_oUser = null;

    /**
     * Selected Address
     * @var object
     */
    protected $_sSelectedAddress = null;

    /**
     * Login option
     * @var integer
     */
    protected $_iOption = null;

    /**
     * Country list
     * @var object
     */
    protected $_oCountryList = null;

    /**
     * Order remark
     * @var string
     */
    protected $_sOrderRemark = null;

    /**
     * Wishlist user id
     * @var string
     */
    protected $_sWishId = null;

    /**
     * Loads customer basket object form session (oxsession::getBasket()),
     * passes action article/basket/country list to template engine. If
     * available - loads user delivery address data (oxaddress). Returns
     * name template file to render user::_sThisTemplate.
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        if ( ( $oUser = $this->getUser() ) ) {

            if ( oxConfig::getParameter( 'blshowshipaddress' ) ) {
                // empty address to disable delivery address
                $this->showShipAddress();
                $this->getDelAddress();
                $this->_addFakeAddress( $oUser->getUserAddresses() );
            }
        }

        $this->_aViewData['blshowshipaddress'] = $this->showShipAddress();
        $this->_aViewData['delivadr']          = $this->getDelAddress();
        $this->_aViewData['blnewssubscribed']  = $this->isNewsSubscribed();

        $this->_aViewData['order_remark'] = $this->getOrderRemark();

        $this->_aViewData['oxcountrylist'] = $this->getCountryList();

        $this->_aViewData['iOption'] = $this->getLoginOption();

        $this->_aViewData['blshownoregopt'] = $this->getShowNoRegOption();

        $this->_aViewData['aMustFillFields'] = $this->getMustFillFields();

        return $this->_sThisTemplate;
    }

    /**
     * Checks if product from wishlist is added
     *
     * @return $sWishId
     */
    protected function _getWishListId()
    {
        $this->_sWishId = null;
        // added check because sometimes it throws php warning
        //if ( is_array($oBasket->getContents()))
        $this->_sWishId = false;
        // check if we have to set it here
        $oBasket = $this->getSession()->getBasket();
        foreach ( $oBasket->getContents() as $oBasketItem ) {
            if ( $this->_sWishId = $oBasketItem->getWishId() ) {
                // stop on first found
                break;
            }
        }
        return $this->_sWishId;
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
        // generate selected no shipping address
        $oDefAddress = new oxStdClass();
        $oDefAddress->oxaddress__oxid = new oxStdClass();
        $oDefAddress->oxaddress__oxid->value    = -2;
        $oDefAddress->oxaddress__oxfname = new oxStdClass();
        $oDefAddress->oxaddress__oxfname->value = '-';
        $oDefAddress->oxaddress__oxlname = new oxStdClass();
        $oDefAddress->oxaddress__oxlname->value = '-';
        $oDefAddress->oxaddress__oxcity = new oxStdClass();
        $oDefAddress->oxaddress__oxcity->value  = '-';

        $oAddresses->offsetSet( $oAddresses->count(), $oDefAddress );
    }

    /**
     * Returns active user object
     *
     * @return object
     */
    protected function _getActiveUser()
    {
        if ( $this->_oUser === null ) {
            $this->_oUser = false;
            if ( $oUser = $this->getUser() ) {
                $this->_oUser = $oUser;
            }
        }
        return $this->_oUser;
    }

    /**
     * Returns selected delivery address
     *
     * @return string
     */
    protected function _getSelectedAddress()
    {
        if ( $this->_sSelectedAddress === null ) {
            $this->_sSelectedAddress = false;
            if ( $oUser = $this->_getActiveUser() ) {
                $this->_sSelectedAddress = $oUser->getSelectedAddress( $this->_getWishListId() );
            }
        }
        return $this->_sSelectedAddress;
    }

    /**
     * Template variable getter. Returns array of must-be-filled-fields
     *
     * @return array
     */
    public function getMustFillFields()
    {
        // passing must-be-filled-fields info
        if ( $this->_aMustFillFields === null ) {
            $this->_aMustFillFields = false;
            $aMustFillFields = $this->getConfig()->getConfigParam( 'aMustFillFields');
            if ( is_array( $aMustFillFields ) ) {
                $this->_aMustFillFields = array_flip( $aMustFillFields );
            }
        }
        return $this->_aMustFillFields;
    }

    /**
     * Template variable getter. Returns reverse option blOrderDisWithoutReg
     *
     * @return bool
     */
    public function getShowNoRegOption()
    {
        if ( $this->_blShowNoRegOpt === null ) {
            $this->_blShowNoRegOpt = !$this->getConfig()->getConfigParam( 'blOrderDisWithoutReg' );
        }
        return $this->_blShowNoRegOpt;
    }

    /**
     * Template variable getter. Returns user login option
     *
     * @return integer
     */
    public function getLoginOption()
    {
        if ( $this->_iOption === null ) {
            // passing user chosen option value to display correct content
            $iOption = oxConfig::getParameter( 'option' );
            // if user chosen "Option 2"" - we should show user details only if he is authorized
            if ( $iOption == 2 && !$this->_getActiveUser() ) {
                $iOption = 0;
            }
            $this->_iOption = $iOption;
        }
        return $this->_iOption;
    }

    /**
     * Template variable getter. Returns country list
     *
     * @return object
     */
    public function getCountryList()
    {
        if ( $this->_oCountryList === null ) {
            $this->_oCountryList = false;
            // passing country list
            $oCountryList = oxNew( 'oxcountrylist' );
            $oCountryList->loadActiveCountries();
            if ( $oCountryList->count() ) {
                $this->_oCountryList = $oCountryList;
            }
        }
        return $this->_oCountryList;
    }

    /**
     * Template variable getter. Returns order remark
     *
     * @return string
     */
    public function getOrderRemark()
    {
        if ( $this->_sOrderRemark === null ) {
            $this->_sOrderRemark = false;
            if ( $sOrderRemark = oxSession::getVar( 'ordrem' ) ) {
                $this->_sOrderRemark = $sOrderRemark;
            } elseif ($sOrderRemark = oxConfig::getParameter( 'order_remark' )) {
                $this->_sOrderRemark = $sOrderRemark;
            }
        }
        return $this->_sOrderRemark;
    }

    /**
     * Template variable getter. Returns if user subscribed for newsletter
     *
     * @return bool
     */
    public function isNewsSubscribed()
    {
        if ( $this->_blNewsSubscribed === null ) {
            $blNews = false;
            if ( ( $blNews = oxConfig::getParameter( 'blnewssubscribed' ) ) === null ) {
                $blNews = true;
            }
            if ( ( $oUser = $this->_getActiveUser() ) ) {
                $blNews = $oUser->getNewsSubscription()->getOptInStatus();
            }
            $this->_blNewsSubscribed = $blNews;
        }
        return $this->_blNewsSubscribed;
    }

    /**
     * Sets if show user shipping address
     *
     * @param bool $blShowShipAddress if TRUE - shipping address is shown
     *
     * @return null
     */
    public function setShowShipAddress( $blShowShipAddress )
    {
        // does nothing, used for compat with old templates, remove it
        // after removing old templates support
    }

    /**
     * Template variable getter. Returns if to show shipping address
     *
     * @return bool
     */
    public function showShipAddress()
    {
        if ( $this->_blShowShipAddress === null ) {
            $sAddressId = (int) oxConfig::getParameter( 'oxaddressid' );
            $this->_blShowShipAddress = ( $sAddressId == -2 ) ? 0 : oxConfig::getParameter( 'blshowshipaddress' );

            if ( ( $oUser = $this->_getActiveUser() ) ) {
                // wishlist user address id
                if ( $sWishId = $this->_getWishListId() ) {
                    // if user didn't click on button to hide
                    if ( $sWishId && oxSession::getVar( 'blshowshipaddress' ) === null ) {
                        // opening address field for wishlist address information
                        oxSession::setVar( 'blshowshipaddress', 1 );
                        $this->_blShowShipAddress = 1;
                    }
                }
                // loading if only address must be shown
                if ( oxConfig::getParameter( 'blshowshipaddress' ) ) {
                    $sAddressId = $this->_getSelectedAddress();
                    if ( '-2' == $sAddressId ) {
                        // user decided to use paymetn address as delivery
                        oxSession::setVar( 'blshowshipaddress', 0 );
                        $this->_blShowShipAddress = 0;
                        // unsetting delivery address
                        oxSession::deleteVar( 'deladdrid' );
                    }
                }
            }
        }
        return $this->_blShowShipAddress;
    }

    /**
     * Sets shipping address
     *
     * @param bool $oDelAddress delivery address
     *
     * @return null
     */
    public function setDelAddress( $oDelAddress )
    {
        // disabling default behaviour ..
    }

    /**
     * Template variable getter. Returns shipping address
     *
     * @return bool
     */
    public function getDelAddress()
    {
        if ( $this->_oDelAddress === null ) {
            $this->_oDelAddress = false;
            if ( $this->showShipAddress() ) {
                $sAddressId = $this->_getSelectedAddress();
                if ( $sAddressId && $sAddressId != '-1' ) {
                    $oAdress = oxNew( 'oxbase' );
                    $oAdress->init( 'oxaddress' );
                    if ( $oAdress->load( $sAddressId ) ) {
                        $this->_oDelAddress = $oAdress;
                    }
                }
            }
        }
        return $this->_oDelAddress;
    }

}
