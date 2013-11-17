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
 * @package admin
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: newsletter_selection.php 17189 2009-03-13 12:19:59Z arvydas $
 */

/**
 * Newsletter user group selection manager.
 * Adds/removes chosen user group to/from newsletter mailing.
 * Admin Menu: Customer News -> Newsletter -> Selection.
 * @package admin
 */
class Newsletter_Selection extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxlist object and
     * collects user groups information, passes it's data to Smarty engine
     * and returns name of template file "newsletter_selection.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // all newslettergroups
        $oGroups = oxNew( "oxlist" );
        $oGroups->init( "oxgroups" );
        $oGroups->selectString( "select * from oxgroups" );


        $soxId = oxConfig::getParameter( "oxid");
        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");
        if ( ($soxId == "-1" || !isset( $soxId)) && isset( $sSavedID) ) {
            $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oNewsletter = oxNew( "oxnewsletter" );
            $oNewsletter->load( $soxId );
            $oNewsletterGroups = $oNewsletter->getGroups();
            $this->_aViewData["edit"] =  $oNewsletter;

            // remove already added groups
            foreach ( $oNewsletterGroups as $oInGroup ) {
                foreach ( $oGroups as $sKey => $oGroup ) {
                    if ( $oGroup->oxgroups__oxid->value == $oInGroup->oxgroups__oxid->value ) {
                        // already in, so lets remove it
                        $oGroups->offsetUnset( $sKey );
                        break;
                    }
                }
            }

            // get nr. of users in these groups
            // we do not use lists here as we dont need this overhead right now
            $oDB = oxDb::getDb();
            $sSelectGroups =  "  ( oxobject2group.oxgroupsid in ( ";
            $blSep = false;
            foreach ( $oNewsletterGroups as $sInGroup) {
                $sSearchKey = $sInGroup->oxgroups__oxid->value;
                if ( $blSep)
                    $sSelectGroups .= ",";
                $sSelectGroups .= "'$sSearchKey'";
                $blSep = true;
            }
            $sSelectGroups .= ") ) ";
            // no group selected
            if ( !$blSep)
                $sSelectGroups = " oxobject2group.oxobjectid is null ";

            $sSelect = "select oxnewssubscribed.oxemail from oxnewssubscribed left join oxobject2group on oxobject2group.oxobjectid = oxnewssubscribed.oxuserid where ( oxobject2group.oxshopid = '".$this->getConfig()->getShopID()."' or oxobject2group.oxshopid is null ) and $sSelectGroups and oxnewssubscribed.oxdboptin = 1 and (not (oxnewssubscribed.oxemailfailed = '1')) and (not(oxnewssubscribed.oxemailfailed = \"1\")) group by oxnewssubscribed.oxemail";

            $rs = $oDB->execute( $sSelect);
            $iCnt = 0;
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $iCnt++;
                    $rs->moveNext();
                }
            }
            $this->_aViewData["user"] =  $iCnt;


            if ( oxConfig::getParameter("aoc") ) {

                $aColumns = array();
                include_once 'inc/'.strtolower(__CLASS__).'.inc.php';
                $this->_aViewData['oxajax'] = $aColumns;

                return "popups/newsletter_selection.tpl";
            }
        }

        $this->_aViewData["allgroups"] =  $oGroups;

        return "newsletter_selection.tpl";
    }

    /**
     * Saves newsletter selection changes.
     *
     * @return string
     */
    public function save()
    {
        $soxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");

        // shopid
        $sShopID = oxSession::getVar( "actshop");
        $aParams['oxnewsletter__oxshopid'] = $sShopID;

        $oNewsletter = oxNew( "oxNewsLetter" );
        if( $soxId != "-1")
            $oNewsletter->load( $soxId );
        else
            $aParams['oxnewsletter__oxid'] = null;
        //$aParams = $oNewsletter->ConvertNameArray2Idx( $aParams);
        $oNewsletter->assign( $aParams );
        $oNewsletter->save();
        // set oxid if inserted
        if ( $soxId == "-1")
            oxSession::setVar( "saved_oxid", $oNewsletter->oxnewsletter__oxid->value);
    }
}
