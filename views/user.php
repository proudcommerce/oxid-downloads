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
 * @version   SVN: $Id: user.php 28585 2010-06-23 09:23:38Z sarunas $
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
     * Revers of option blOrderDisWithoutReg
     * @var array
     */
    protected $_blShowNoRegOpt = null;

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
     * available - loads user delivery address data (oxaddress). If user
     * is connected using Facebook connect calls user::_fillFormWithFacebookData to
     * prefill form data with data taken from user Facebook account. Returns
     * name template file to render user::_sThisTemplate.
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        $myConfig  = $this->getConfig();
        if ($myConfig->getConfigParam( 'blPsBasketReservationEnabled' )) {
            $this->getSession()->getBasketReservations()->renewExpiration();
        }

        $oBasket = $this->getSession()->getBasket();
        if ( $myConfig->getConfigParam( 'blPsBasketReservationEnabled' ) && (!$oBasket || ( $oBasket && !$oBasket->getProductsCount() )) ) {
            oxUtils::getInstance()->redirect( $myConfig->getShopHomeURL() .'cl=basket' );
        }

        parent::render();

        if ( $this->showShipAddress() && $oUser = $this->getUser()) {
                $this->getDelAddress();
                $this->_addFakeAddress( $oUser->getUserAddresses() );
        }

        $this->_aViewData['blshowshipaddress'] = $this->showShipAddress();
        $this->_aViewData['delivadr']          = $this->getDelAddress();
        $this->_aViewData['blnewssubscribed']  = $this->isNewsSubscribed();

        $this->_aViewData['order_remark'] = $this->getOrderRemark();

        $this->_aViewData['oxcountrylist'] = $this->getCountryList();

        $this->_aViewData['iOption'] = $this->getLoginOption();

        $this->_aViewData['blshownoregopt'] = $this->getShowNoRegOption();

        $this->_aViewData['aMustFillFields'] = $this->getMustFillFields();

        if ( $myConfig->getConfigParam( "bl_showFbConnect" ) && !$this->getUser() ) {
             $this->_fillFormWithFacebookData();
        }

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
     * Generats fake address for selection
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

        //T2009-08-19
        //deprecated part
        //no more fields are used in templates anymore
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
    /*
    protected function _getActiveUser()
    {
        if ( $this->_oUser === null ) {
            $this->_oUser = false;
            if ( $oUser = $this->getUser() ) {
                $this->_oUser = $oUser;
            }
        }
        return $this->_oUser;
    }*/

    /**
     * Returns selected delivery address
     *
     * @return string
     */
    protected function _getSelectedAddress()
    {
        if ( $this->_sSelectedAddress === null ) {
            $this->_sSelectedAddress = false;
            if ( $oUser = $this->getUser() ) {
                $this->_sSelectedAddress = $oUser->getSelectedAddress( $this->_getWishListId() );
            }
        }
        return $this->_sSelectedAddress;
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
            if ( $iOption == 2 && !$this->getUser() ) {
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
                $this->_sOrderRemark = oxConfig::checkSpecialChars( $sOrderRemark );
            } elseif ( $sOrderRemark = oxConfig::getParameter( 'order_remark' ) ) {
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
                $blNews = false;
            }
            if ( ( $oUser = $this->getUser() ) ) {
                $blNews = $oUser->getNewsSubscription()->getOptInStatus();
            }
            $this->_blNewsSubscribed = $blNews;
        }

        if (is_null($this->_blNewsSubscribed))
            $this->_blNewsSubscribed = false;

        return  $this->_blNewsSubscribed;
    }

    /**
     * Sets if show user shipping address
     *
     * @param bool $blShowShipAddress if TRUE - shipping address is shown
     *
     * @deprecated
     *
     * @return null
     */
    public function setShowShipAddress( $blShowShipAddress )
    {
        // does nothing, used for compat with old templates, remove it
        // after removing old templates support
    }

    /**
     * Template variable getter. Checks to show or not shipping address entry form
     *
     * @return bool
     */
    public function showShipAddress()
    {

        if ( $this->_blShowShipAddress === null ) {

            $sAddressId = (int) oxConfig::getParameter( 'oxaddressid' );
            $this->_blShowShipAddress = ( $sAddressId == -2 ) ? 0 : oxConfig::getParameter( 'blshowshipaddress' );

            if ( ( $oUser = $this->getUser() ) ) {
                // wishlist user address id
                if ( $sWishId = $this->_getWishListId() ) {
                    // if user didn't click on button to hide
                    if ( $sWishId && oxSession::getVar( 'blshowshipaddress' ) === null ) {
                        // opening address field for wishlist address information
                        oxSession::setVar( 'blshowshipaddress', true );
                        $this->_blShowShipAddress = true;
                    }
                }
            }

            if ( '-2' == $sAddressId ) {
                // user decided to use paymetn address as delivery
                oxSession::setVar( 'blshowshipaddress', 0 );
                // unsetting delivery address
                $this->_blShowShipAddress = false;
            }
        }

        //if still not set then take it from session
        if ( $this->_blShowShipAddress === null ) {
            $this->_blShowShipAddress = oxSession::getVar( 'blshowshipaddress');
        }

        if ( $this->_blShowShipAddress === null ) {
            $this->_blShowShipAddress = false;
        }

        return $this->_blShowShipAddress;
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
                    $oAdress = oxNew( 'oxaddress' );
                    if ( $oAdress->load( $sAddressId ) ) {
                        $this->_oDelAddress = $oAdress;
                        $this->_aViewData['deladr'] = null;
                    }
                }
            }
        }
        return $this->_oDelAddress;
    }

    /**
     * Fills user form with date taken from Facebook
     *
     * @return null
     */
    protected function _fillFormWithFacebookData()
    {
        // Create our Application instance.
        $oFacebook = oxFb::getInstance();

        if ( $oFacebook->isConnected() ) {
            $aMe  = $oFacebook->api('/me');

            $aInvAdr = $this->_aViewData['invadr'];

            if ( !$aInvAdr["oxuser__oxfname"] ) {
                $aInvAdr["oxuser__oxfname"] = $aMe["first_name"];
            }

            if ( !$aInvAdr["oxuser__oxlname"] ) {
                $aInvAdr["oxuser__oxlname"] = $aMe["last_name"];
            }

            $this->_aViewData['invadr'] = $aInvAdr;
        }
    }
}
