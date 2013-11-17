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
 * $Id: account_newsletter.php 20619 2009-07-03 06:16:01Z vilma $
 */

/**
 * Current user newsletter manager.
 * When user is logged in in this manager window he can modify
 * his newletter subscription status - simply register or
 * unregister from newsletter. OXID eShop -> MY ACCOUNT -> Newsletter.
 */
class Account_Newsletter extends Account
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'account_newsletter.tpl';

    /**
     * Whether the newsletter option had been changed.
     *
     * @var bool
     */
    protected $_blNewsletter = null;

    /**
     * Whether the newsletter option had been changed give some affirmation.
     *
     * @var integer
     */
    protected $_iSubscriptionStatus = 0;

    /**
     * If user is not logged in - returns name of template account_newsletter::_sThisLoginTemplate,
     * or if user is allready logged in - returns name of template
     * Account_Newsletter::_sThisTemplate
     *
     * Template variables:
     * <b>blnewsletter</b>, <b>actshop</b>
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // is logged in ?
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        //to maintain compatibility we still set the old template variable using new getter in render
        $this->_aViewData['blnewsletter'] = $this->isNewsletter();

        //loading shop info
        $this->_aViewData['actshop'] = $this->getConfig()->getActiveShop();

        return $this->_sThisTemplate;
    }


    /**
     * Template variable getter. Returns true when newsletter had been changed.
     *
     * @return bool
     */
    public function isNewsletter()
    {
        if ( $this->_blNewsletter === null ) {

            // initiating status
            $this->_blNewsletter = false;

            // now checking real subscription status
            $oUser = $this->getUser();
            if ( $oUser &&  $oUser->inGroup( 'oxidnewsletter' ) && ( $oUser->getNewsSubscription()->getOptInStatus() == 1 ) ) {
                $this->_blNewsletter = true;
            }
        }

        return $this->_blNewsletter;
    }

    /**
     * Removes or adds user to newsletter group according to
     * current subscription status. Returns true on success.
     *
     * @return bool
     */
    public function subscribe()
    {
        // is logged in ?
        $oUser = $this->getUser();
        if ( !$oUser ) {
            return false;
        }

        if ( ! ( $iStatus = oxConfig::getParameter( 'status' ) ) ) {
            $oUser->removeFromGroup( 'oxidnewsletter' );
            $oUser->getNewsSubscription()->setOptInStatus( 0 );
            $this->_iSubscriptionStatus = -1;
        } else {
            // assign user to newsletter group
            $oUser->addToGroup( 'oxidnewsletter' );
            $oUser->getNewsSubscription()->setOptInEmailStatus( 0 );
            $oUser->getNewsSubscription()->setOptInStatus( 1 );
            $this->_iSubscriptionStatus = 1;
        }

        //to maintain compatibility we still set the old template variable using new getter
        if ( $this->getSubscriptionStatus() == 1) {
            $this->_aViewData['blsubscribed'] = true;
        }
        if ( $this->getSubscriptionStatus() == -1) {
            $this->_aViewData['blsubscribed'] = false;
        }
    }

    /**
     * Template variable getter. Returns 1 when newsletter had been changed to "yes"
     * else return -1 if had been changed to "no".
     *
     * @return integer
     */
    public function getSubscriptionStatus()
    {
        return $this->_iSubscriptionStatus;
    }
}
