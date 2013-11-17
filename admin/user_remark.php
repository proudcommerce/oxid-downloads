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
 * $Id: user_remark.php 17191 2009-03-13 12:21:00Z arvydas $
 */

/**
 * Admin user history settings manager.
 * Collects user history settings, updates it on user submit, etc.
 * Admin Menu: User Administration -> Users -> History.
 * @package admin
 */
class User_Remark extends oxAdminDetails
{
    /**
     * Executes parent method parent::render(), creates oxuser, oxlist and
     * oxRemark objects, passes data to Smarty engine and returns name of
     * template file "user_remark.tpl".
     * @return string
     */
    public function render()
    {
        parent::render();

        $soxId      = oxConfig::getParameter( "oxid");
        $sRemoxId   = oxConfig::getParameter( "rem_oxid");
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oUser = oxNew( "oxuser" );
            $oUser->load( $soxId);
            $this->_aViewData["edit"] =  $oUser;

            // all remark
            $oRems = oxNew( "oxlist" );
            $oRems->init( "oxremark");
            $sSelect = "select * from oxremark where oxparentid='".$oUser->oxuser__oxid->value."' order by oxcreate desc";
            $oRems->selectString( $sSelect );
            foreach ($oRems as $key => $val) {
                if ( $val->oxremark__oxid->value == $sRemoxId) {
                    $val->selected = 1;
                    $oRems[$key] = $val;
                    break;
                }
            }

            $this->_aViewData["allremark"] = $oRems;

            if ( isset( $sRemoxId)) {
                $oRemark = oxNew( "oxRemark" );
                $oRemark->load( $sRemoxId);
                $this->_aViewData["remarktext"]      = $oRemark->oxremark__oxtext->value;
                $this->_aViewData["remarkheader"]    = $oRemark->oxremark__oxheader->value;
            }
        }

        return "user_remark.tpl";
    }

    /**
     * Saves user history text changes.
     *
     * @return mixed
     */
    public function save()
    {

        $soxId      = oxConfig::getParameter( "oxid");
        $sRemoxId   = oxConfig::getParameter( "rem_oxid");

        $oRemark = oxNew( "oxremark" );
        $oRemark->load( $sRemoxId);

        $sNewText   = oxConfig::getParameter( "remarktext");
        $sNewHeader = oxConfig::getParameter( "remarkheader");
        $oRemark->oxremark__oxtext = new oxField($sNewText);
        $oRemark->oxremark__oxheader = new oxField($sNewHeader);
        $oRemark->oxremark__oxparentid = new oxField($soxId);

        $oRemark->save();
    }

    /**
     * Deletes user actions history record.
     *
     * @return null
     */
    public function delete()
    {
        $sRemoxId = oxConfig::getParameter( "rem_oxid");
        $oRemark  = oxNew( "oxRemark" );
        $oRemark->load( $sRemoxId);
        $oRemark->delete();
    }
}
