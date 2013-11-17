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
 * $Id: article_pictures.php 17243 2009-03-16 15:16:57Z arvydas $
 */

/**
 * Admin article picture manager.
 * Collects information about article's used pictures, there is posibility to
 * upload any other picture, etc.
 * Admin Menu: Manage Products -> Articles -> Pictures.
 * @package admin
 */
class Article_Pictures extends oxAdminDetails
{
    /**
     * Loads article information - pictures, passes data to Smarty
     * engine, returns name of template file "article_pictures.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData["edit"] = $oArticle = oxNew( "oxarticle");

        $soxId = oxConfig::getParameter( 'oxid' );
        if ( $soxId != "-1" && isset( $soxId ) ) {
            // load object
            $oArticle->load( $soxId);


            // variant handling
            if ( $oArticle->oxarticles__oxparentid->value) {
                $oParentArticle = oxNew( "oxarticle");
                $oParentArticle->load( $oArticle->oxarticles__oxparentid->value);
                $this->_aViewData["parentarticle"] =  $oParentArticle;
                $this->_aViewData["oxparentid"] =  $oArticle->oxarticles__oxparentid->value;
            }
        }

        return "article_pictures.tpl";
    }

    /**
     * Saves (uploads) pictures to server.
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig  = $this->getConfig();


        $soxId      = oxConfig::getParameter( "oxid");
        $aParams    = oxConfig::getParameter( "editval");

        $oArticle = oxNew( "oxarticle");
        $oArticle->load( $soxId);

            // shopid
            $sShopID = oxSession::getVar( "actshop");
            $aParams['oxarticles__oxshopid'] = $sShopID;
            $myUtilsPic = oxUtilsPic::getInstance();

            // #1173M - not all pic are deleted
            if ( $myUtilsPic->overwritePic( $oArticle, 'oxarticles', 'oxthumb', 'TH', '0', $aParams, $myConfig->getAbsDynImageDir() ))
                $myUtilsPic->overwritePic( $oArticle, 'oxarticles', 'oxicon', 'ICO', 'icon', $aParams, $myConfig->getAbsDynImageDir() );

            for ($i=1; $i<=$myConfig->getConfigParam( 'iPicCount' ); $i++)
                $myUtilsPic->overwritePic( $oArticle, 'oxarticles', 'oxpic'.$i, 'P'.$i, $i, $aParams, $myConfig->getAbsDynImageDir());
            for ($i=1; $i<=$myConfig->getConfigParam( 'iZoomPicCount' ); $i++)
                $myUtilsPic->overwritePic( $oArticle, 'oxarticles', 'oxzoom'.$i, 'Z'.$i, 'z'.$i, $aParams, $myConfig->getAbsDynImageDir());

        //$aParams = $oArticle->ConvertNameArray2Idx( $aParams);
        $oArticle->assign( $aParams);
        $oArticle = oxUtilsFile::getInstance()->processFiles( $oArticle );
        $oArticle->save();
    }
}
