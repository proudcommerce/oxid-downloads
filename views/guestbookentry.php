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
 * $Id: guestbookentry.php 23173 2009-10-12 13:29:45Z sarunas $
 */

/**
 * Guest book entry manager class.
 * Manages guestbook entries, denies them, etc.
 */
class GuestbookEntry extends GuestBook
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'guestbookentry.tpl';

    /**
     * Guestbook form id, prevents double entry submit
     * @var string
     */
    protected $_sGbFormId = null;

    /**
     * Method applies validation to entry and saves it to DB.
     * On error/success returns name of action to perform
     * (on error: "guestbookentry?error=x"", on success: "guestbook").
     *
     * @return string
     */
    public function saveEntry()
    {
        $sReviewText = trim( ( string ) oxConfig::getParameter( 'rvw_txt', true ) );
        $sShopId     = $this->getConfig()->getShopId();
        $sUserId     = oxSession::getVar( 'usr' );

        // guest book`s entry is validated
        if ( !$sUserId ) {
            oxUtilsView::getInstance()->addErrorToDisplay( 'GUESTBOOKENTRY_ERRLOGGINTOWRITEENTRY' );
            //return to same page
            return;
        }

        if ( !$sShopId ) {
            oxUtilsView::getInstance()->addErrorToDisplay( 'GUESTBOOKENTRY_ERRUNDEFINEDSHOP' );
            return 'guestbookentry';
        }

        // empty entries validation
        if ( '' == $sReviewText ) {
            oxUtilsView::getInstance()->addErrorToDisplay( 'GUESTBOOKENTRY_ERRREVIEWCONTAINSNOTEXT' );
            return 'guestbookentry';
        }

        // flood protection
        $oEntrie = oxNew( 'oxgbentry' );
        if ( $oEntrie->floodProtection( $sShopId, $sUserId ) ) {
            oxUtilsView::getInstance()->addErrorToDisplay( 'GUESTBOOKENTRY_ERRMAXIMUMNOMBEREXCEEDED' );
            return 'guestbookentry';
        }

        // double click protection
        $sFormId = oxConfig::getParameter( "gbFormId" );
        $sSessionFormId = oxSession::getVar( "gbSessionFormId" );
        if ( $sFormId && $sFormId == $sSessionFormId ) {
            // here the guest book entry is saved
            $oEntry = oxNew( 'oxgbentry' );
            $oEntry->oxgbentries__oxshopid  = new oxField($sShopId);
            $oEntry->oxgbentries__oxuserid  = new oxField($sUserId);
            $oEntry->oxgbentries__oxcontent = new oxField($sReviewText);
            $oEntry->save();

            // regenerating form id
            $this->getFormId();
        }

        return 'guestbook';
    }

    /**
     * Guestbook form id getter (prevents double entry submit)
     *
     * @return string
     */
    public function getFormId()
    {
        if ( $this->_sGbFormId === null ) {
            $this->_sGbFormId = oxUtilsObject::getInstance()->generateUId();
            oxSession::setVar( 'gbSessionFormId', $this->_sGbFormId );
        }
        return $this->_sGbFormId;
    }
}
