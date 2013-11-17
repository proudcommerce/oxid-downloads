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
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: article_pictures.php 26699 2010-03-20 12:41:56Z arvydas $
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

        $this->_aViewData["iPicCount"] =  $this->getConfig()->getConfigParam( 'iPicCount' );

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
        $iPicCount = $this->getConfig()->getConfigParam( 'iPicCount' );

        $oArticle = oxNew( "oxarticle");
        $oArticle->load( $soxId);

        // shopid
        $aParams['oxarticles__oxshopid'] = oxSession::getVar( "actshop");
        $myUtilsPic = oxUtilsPic::getInstance();

        // deleting master image and all generated images
        $this->_deleteMasterPicture( $oArticle, $iIndex );

        $oArticle->assign( $aParams );
        $oArticle = oxUtilsFile::getInstance()->processFiles( $oArticle );

        // setting generated images amount and reseting other pictures fields
        $this->_updateGeneratedPicsAmount( $oArticle );

        // reseting upladed pictures oxzoom fields
        $this->_cleanupZoomFields( $oArticle );

        // reseting all generated pictures where master picture
        // index is higher than lowest currently uploaded master picture index
        $iFrom  = $this->_getMinUploadedMasterPicIndex();
        for ( $i=$iFrom; $i<= $iPicCount; $i++ ) {
            $this->_resetMasterPicture( $oArticle, $i );
        }

        $oArticle->save();
    }

    /**
     * Deletes selected master picture and all other master pictures
     * where master picture index is higher than currently deleted index.
     * Also deletes custom icon and thumbnail.
     *
     * @return null
     */
    public function deletePicture()
    {
        $sOxId  = oxConfig::getParameter( "oxid");
        $iIndex = oxConfig::getParameter( "masterPicIndex" );
        $iPicCount = $this->getConfig()->getConfigParam( 'iPicCount' );

        $oArticle = oxNew( "oxarticle" );
        $oArticle->load( $sOxId );

        // deleting main icon
        if ( $iIndex == "ICO" ) {
            $this->_deleteMainIcon( $oArticle );
        }

        // deleting thumbnail
        if ( $iIndex == "TH" ) {
            $this->_deleteThumbnail( $oArticle );
        }

        // deleting master picture
        if ( (int)$iIndex > 0 ) {
            $this->_deleteMasterPicture( $oArticle, $iIndex );

            // reseting all generated pictures where master picture
            // index is higher than currently deleted index
            for ( $i=$iIndex+1; $i<= $iPicCount; $i++ ) {
                $this->_resetMasterPicture( $oArticle, $i );
            }

            //updating amount of generated pictures
            if ( $iIndex > 0 ) {
                $oArticle->updateAmountOfGeneratedPictures( $iIndex-1 );
            }
        }

        $oArticle->save();
    }

    /**
     * Deletes selected master picture and all pictures generated
     * from master picture
     *
     * @param oxArticle $oArticle article object
     * @param int       $iIndex   master picture index
     *
     * @return null
     */
    protected function _deleteMasterPicture( $oArticle, $iIndex )
    {
        if ( $oArticle->{"oxarticles__oxpic".$iIndex}->value ) {

            $oPicHandler = oxPictureHandler::getInstance();
            $oPicHandler->deleteArticleMasterPicture( $oArticle, $iIndex );

            //reseting master picture field
            $oArticle->{"oxarticles__oxpic".$iIndex} = new oxField();

            // cleaning oxzoom fields
            if ( isset( $oArticle->{"oxarticles__oxzoom".$iIndex} ) ) {
                $oArticle->{"oxarticles__oxzoom".$iIndex} = new oxField();
            }

            if ( $iIndex == 1 ) {
                $this->_cleanupCustomFields( $oArticle );
            }
        }
    }

    /**
     * Deletes main icon file
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _deleteMainIcon( $oArticle )
    {
        if ( $oArticle->oxarticles__oxicon->value ) {

            $oPicHandler = oxPictureHandler::getInstance();
            $oPicHandler->deleteMainIcon( $oArticle );

            //reseting field
            $oArticle->oxarticles__oxicon = new oxField();
        }
    }

    /**
     * Deletes thumbnail file
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _deleteThumbnail( $oArticle )
    {
        if ( $oArticle->oxarticles__oxthumb->value ) {

            $oPicHandler = oxPictureHandler::getInstance();
            $oPicHandler->deleteThumbnail( $oArticle );

            //reseting field
            $oArticle->oxarticles__oxthumb = new oxField();
        }
    }

    /**
     * Resets selected master picture. Deletes all pictures generated
     * from master picture, but leaves master picture untouched.
     *
     * @param oxArticle $oArticle article object
     * @param int       $iIndex   master picture index
     *
     * @return null
     */
    protected function _resetMasterPicture( $oArticle, $iIndex )
    {
        if ( $oArticle->{"oxarticles__oxpic".$iIndex}->value ) {

            $oPicHandler = oxPictureHandler::getInstance();
            $oPicHandler->deleteArticleMasterPicture( $oArticle, $iIndex, false );

            // cleaning oxzoom fields
            if ( isset( $oArticle->{"oxarticles__oxzoom".$iIndex} ) ) {
                $oArticle->{"oxarticles__oxzoom".$iIndex} = new oxField();
            }

            if ( $iIndex == 1 ) {
                $this->_cleanupCustomFields( $oArticle );
            }
        }
    }


    /**
     * Update article already generated pictures amount
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _updateGeneratedPicsAmount( $oArticle )
    {
        $iMinUploadedIndex = $this->_getMinUploadedMasterPicIndex();

        if ( $iMinUploadedIndex > 0 && $iMinUploadedIndex <= $oArticle->oxarticles__oxpicsgenerated->value ) {
            $oArticle->updateAmountOfGeneratedPictures( $iMinUploadedIndex-1 );
        }
    }

    /**
     * Get min uploaded master image index number.
     *
     * @return int
     */
    protected function _getMinUploadedMasterPicIndex()
    {
        if ( isset( $_FILES['myfile']['name'] ) ) {
            $iIndex = $this->getConfig()->getConfigParam( 'iPicCount' );

            while ( list( $sKey, $sValue ) = each( $_FILES['myfile']['name'] ) ) {
                if ( !empty($sValue) ) {
                    preg_match( "/^M(\d+)/", $sKey, $aMatches );
                    $iPicIndex = $aMatches[1];
                    $iIndex = ( $iIndex > $iPicIndex ) ? $iPicIndex : $iIndex;
                }
            }
        }

        return (int)$iIndex;
    }

    /**
     * Cleans up article oxzoom fields for all uploaded pictures.
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _cleanupZoomFields( $oArticle )
    {
        $myConfig  = $this->getConfig();
        if ( isset( $_FILES['myfile']['name'] ) ) {
            $iIndex = 0;

            while ( list( $sKey, $sValue ) = each( $_FILES['myfile']['name'] ) ) {
                if ( !empty($sValue) ) {
                    $iIndex = $this->_getUploadedMasterPicIndex( $sKey );
                    $oArticle->{"oxarticles__oxzoom".$iIndex} = new oxField();
                }
            }
        }
    }

    /**
     * Cleans up article custom fields oxicon and oxthumb. If there is custom
     * icon or thumb picture, leaves records untouched.
     *
     * @param oxArticle $oArticle article object
     *
     * @return null
     */
    protected function _cleanupCustomFields( $oArticle )
    {
        $myConfig  = $this->getConfig();

        $sIcon  = $oArticle->oxarticles__oxicon->value;
        $sThumb = $oArticle->oxarticles__oxthumb->value;

        if ( $sIcon == "nopic_ico.jpg" ) {
            $oArticle->oxarticles__oxicon = new oxField();
        }

        if ( $sThumb == "nopic.jpg" ) {
            $oArticle->oxarticles__oxthumb = new oxField();
        }
    }

    /**
     * Get uploaded master image index.
     *
     * @param string $sFileType image type
     *
     * @return int
     */
    protected function _getUploadedMasterPicIndex( $sFileType )
    {
        $iPicIndex = 0;

        if ( preg_match( "/^M(\d+)/", $sFileType, $aMatches ) ) {
            $iPicIndex = $aMatches[1];
        }

        return (int) $iPicIndex;
    }
}
