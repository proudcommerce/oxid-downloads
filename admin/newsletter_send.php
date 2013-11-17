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
 * $Id: newsletter_send.php 17627 2009-03-26 15:08:34Z arvydas $
 */

/**
 * Newsletter sending manager.
 * Performs sending of newsletter to selected user groups.
 * @package admin
 */
class Newsletter_Send extends oxAdminList
{
    /**
     * Executes parent method parent::render(), creates oxnewsletter object,
     * sends newsletter to users of chosen groups and returns name of template
     * file "newsletter_send.tpl"/"newsletter_done.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig  = $this->getConfig();
        parent::render();

        $iStart = oxConfig::getParameter( "iStart");
        $iUser  = oxConfig::getParameter( "user");
        $sID      = oxConfig::getParameter( "id");

        $oNewsletter = oxNew( "oxNewsLetter" );
        $oNewsletter->load( $sID );
        $oNewsletterGroups = $oNewsletter->getGroups();

        // #493A - fetching cached newsletter info
        $oCachedNewsletter = oxSession::getVar("_oNewsletter");
        if ( isset($oCachedNewsletter)) {
            //checking if cached session id is the same as current user session id
            if ($oCachedNewsletter->sID != $sID)
                oxSession::deleteVar("_oNewsletter");
            else {
                // setting cached values
                $iStart = $oCachedNewsletter->iStart;
                $iUser  = $oCachedNewsletter->iUser;
                $sID    = $oCachedNewsletter->sID;
            }
        } else {
            // setting initial values
            $oCachedNewsletter = new oxStdClass();
            $oCachedNewsletter->iStart = $iStart;
            $oCachedNewsletter->iUser  = $iUser;
            $oCachedNewsletter->sID    = $sID;
        }

        // send emails....
        $oDB = oxDb::getDb();
        $sSelectGroups =  " ( oxobject2group.oxgroupsid in ( ";
        $blSep = false;
        foreach ( $oNewsletterGroups as $sInGroup) {
            $sSearchKey = $sInGroup->oxgroups__oxid->value;
            if ( $blSep)
                $sSelectGroups .= ",";
            $sSelectGroups .= "'$sSearchKey'";
            $blSep = true;
        }
        $sSelectGroups .= ") )";
        // no group selected
        if ( !$blSep)
            $sSelectGroups = " oxobject2group.oxobjectid is null ";

        $sSelect = "select oxnewssubscribed.oxuserid, oxnewssubscribed.oxemail, oxnewssubscribed.oxsal, oxnewssubscribed.oxfname, oxnewssubscribed.oxlname from oxnewssubscribed left join oxobject2group on oxobject2group.oxobjectid = oxnewssubscribed.oxuserid where ( oxobject2group.oxshopid = '".$myConfig->getShopID()."' or oxobject2group.oxshopid is null ) and $sSelectGroups and oxnewssubscribed.oxdboptin = 1 and (not (oxnewssubscribed.oxemailfailed = '1')) and (not(oxnewssubscribed.oxemailfailed = \"1\")) group by oxnewssubscribed.oxemail";

        $rs = $oDB->selectLimit( $sSelect, $myConfig->getConfigParam( 'iCntofMails' ), $iStart);

        ini_set("session.gc_maxlifetime", 36000);

        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $sUserID = $rs->fields[0];

                // must check if such user is in DB
                if( !$oDB->getOne("select oxid from oxuser where oxid = '$sUserID'"))
                    $sUserID = null;

                // #559
                if ( !isset( $sUserID) || !$sUserID) {
                     // there is no user object so we fake one
                    $oUser = oxNew( "oxuser" );
                    $oUser->oxuser__oxusername = new oxField($rs->fields[1]);
                    $oUser->oxuser__oxsal      = new oxField($rs->fields[2]);
                    $oUser->oxuser__oxfname    = new oxField($rs->fields[3]);
                    $oUser->oxuser__oxlname    = new oxField($rs->fields[4]);
                    $oNewsletter->prepare( $oUser, $myConfig->getConfigParam( 'bl_perfLoadAktion' ) );
                }
                else
                    $oNewsletter->prepare( $sUserID, $myConfig->getConfigParam( 'bl_perfLoadAktion' ) );

                if ( $oNewsletter->send() ) {
                     // add user history
                    $oRemark = oxNew( "oxremark" );
                    $oRemark->oxremark__oxtext     = new oxField($oNewsletter->sPlainText);
                    $oRemark->oxremark__oxparentid = new oxField($sUserID);
                    $sShopID = oxSession::getVar( "actshop");
                    $sShopID = oxSession::setVar( "keepalive", "yes");
                    $oRemark->oxremark__oxshopid   = new oxField($sShopID);
                    $oRemark->save();
                } else
                    echo( "problem sending to : ".$rs->fields[1]."<br>");


                   $rs->moveNext();
            }
        }

        // adavance mail pointer and set parameter
        $iStart += $myConfig->getConfigParam( 'iCntofMails' );

        // #493A - setting new values
        $oCachedNewsletter->iStart = $iStart;
        $oCachedNewsletter->iUser  = $iUser;
        $oCachedNewsletter->sID    = $sID;

        $this->_aViewData["iStart"]     =  $iStart;
        $this->_aViewData["user"]     =  $iUser;
        $this->_aViewData["id"]         =  $sID;


        // end ?
        if ( $iStart < $iUser) {
               $sPage = "newsletter_send.tpl";
            // #493A - saving changes
            oxSession::setVar("_oNewsletter", $oCachedNewsletter);
        } else {
               $sPage = "newsletter_done.tpl";
            // #493A - deleting cache variable
            oxSession::deleteVar("_oNewsletter");
        }

        return $sPage;
    }

    /*
     * Overrides parent method to pass referred id
     *
     * @param string $sId class name
     */
    protected function _setupNavigation( $sId )
    {
        parent::_setupNavigation( 'newsletter_list' );
    }
}
