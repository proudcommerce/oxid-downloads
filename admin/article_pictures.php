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
 * @version   SVN: $Id: article_pictures.php 28928 2010-07-22 14:19:41Z arvydas $
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
        $myConfig = $this->getConfig();

        if ( $myConfig->isDemoShop() ) {
            // disabling uploading pictures if this is demo shop
            $oEx = new oxExceptionToDisplay();
            $oEx->setMessage( 'ARTICLE_PICTURES_UPLOADISDISABLED' );
            oxUtilsView::getInstance()->addErrorToDisplay( $oEx, false );

            return;
        }


        $soxId   = oxConfig::getParameter( "oxid");
        $aParams = oxConfig::getParameter( "editval");
        $aParams['oxarticles__oxshopid'] = oxSession::getVar( "actshop" );

        $oArticle = oxNew( "oxarticle");
        $oArticle->load( $soxId );

        // deleting master and all related images
        $aIndexes = $this->_getUploadedMasterPicIndexes();
        $iMin = min( $aIndexes );
        $iMin = $iMin < 1 ? 1 : $iMin;

        for ( $i = $iMin; $i <= $this->getConfig()->getConfigParam( 'iPicCount' ); $i++ ) {
            $this->_resetMasterPicture( $oArticle, $iIndex, in_array( $i, $aIndexes ) );
        }

        $oArticle->assign( $aParams );
        $oArticle = oxUtilsFile::getInstance()->processFiles( $oArticle );
        $oArticle->updateAmountOfGeneratedPictures( $iMin - 1 );
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
        $myConfig = $this->getConfig();

        if ( $myConfig->isDemoShop() ) {
            // disabling uploading pictures if this is demo shop
            $oEx = new oxExceptionToDisplay();
            $oEx->setMessage( 'ARTICLE_PICTURES_UPLOADISDISABLED' );
            oxUtilsView::getInstance()->addErrorToDisplay( $oEx, false );

            return;
        }

        $sOxId  = oxConfig::getParameter( "oxid" );
        $iIndex = oxConfig::getParameter( "masterPicIndex" );

        $oArticle = oxNew( "oxarticle" );
        $oArticle->load( $sOxId );

        if ( $iIndex == "ICO" ) {
            // deleting main icon
            $this->_deleteMainIcon( $oArticle );
        } elseif ( $iIndex == "TH" ) {
            // deleting thumbnail
            $this->_deleteThumbnail( $oArticle );
        } else {
            $iIndex = (int) $iIndex;
            if ( $iIndex > 0 ) {
                // deleting master picture
                $this->_resetMasterPicture( $oArticle, $iIndex, true );

                // reseting others following master image
                for ( $i = $iIndex + 1; $i <= $this->getConfig()->getConfigParam( 'iPicCount' ); $i++ ) {
                    $this->_resetMasterPicture( $oArticle, $iIndex );
                }

                $oArticle->updateAmountOfGeneratedPictures( $iIndex - 1 );
            }
        }

        $oArticle->save();
    }

    /**
     * Deletes selected master picture and all pictures generated
     * from master picture
     *
     * @param oxArticle $oArticle       article object
     * @param int       $iIndex         master picture index
     * @param bool      $blDeleteMaster if TRUE - deletes and unsets master image file
     *
     * @return null
     */
    protected function _resetMasterPicture( $oArticle, $iIndex, $blDeleteMaster = false )
    {
        if ( $oArticle->{"oxarticles__oxpic".$iIndex}->value ) {

            $oPicHandler = oxPictureHandler::getInstance();
            $oPicHandler->deleteArticleMasterPicture( $oArticle, $iIndex, $blDeleteMaster );

            if ( $blDeleteMaster ) {
                //reseting master picture field
                $oArticle->{"oxarticles__oxpic".$iIndex} = new oxField();
            }

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
     * Returns uploaded master image indexes
     *
     * @return array
     */
    protected function _getUploadedMasterPicIndexes()
    {
        $aIndexes = array();
        if ( isset( $_FILES['myfile']['name'] ) ) {
            $iIndex = $this->getConfig()->getConfigParam( 'iPicCount' );
            $oStr = getStr();

            while ( list( $sKey, $sValue ) = each( $_FILES['myfile']['name'] ) ) {
                if ( $sValue ) {
                    $oStr->preg_match( "/^M(\d+)/", $sKey, $aMatches );
                    $iPicIndex = isset( $aMatches[1] ) ? (int) $aMatches[1] : 1;
                    $iPicIndex = ( $iPicIndex > $iIndex  ) ? $iIndex : $iPicIndex;
                    if ( !in_array( $iPicIndex, $aIndexes ) ) {
                        $aIndexes[] = $iPicIndex;
                    }
                }
            }
        }

        return $aIndexes;
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
}