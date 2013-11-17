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
 * @package   core
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxdbmetadatahandler.php 23405 2009-10-20 15:29:03Z rimvydas.paskevicius $
 */

/**
 * class for pictures processing
 * @package core
 */
class oxPictureHandler extends oxSuperCfg
{
    /**
     * oxUtils class instance.
     *
     * @var oxutils
     */
    private static $_instance = null;


    /**
     * Returns object instance
     *
     * @return oxPictureHandler
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            self::$_instance = modInstances::getMod( __CLASS__ );
        }

        if ( !self::$_instance instanceof oxPictureHandler ) {

            self::$_instance = oxNew( 'oxPictureHandler' );
            if ( defined( 'OXID_PHP_UNIT' ) ) {
                modInstances::addMod( __CLASS__, self::$_instance);
            }
        }
        return self::$_instance;
    }

    /**
     * Generates article pictures (icon, thumbnail, zoom picture) from master
     * picture.
     *
     * @param oxArticle $oObject article object
     * @param int       $iIndex  master picture index
     *
     * @return null
     */
    public function generateArticlePictures( $oObject, $iIndex )
    {
        $iGeneratedImages = (int)$oObject->oxarticles__oxpicsgenerated->value;
        $oConfig = $this->getConfig();
        $oUtilsFile = oxUtilsFile::getInstance();

        if ( $iGeneratedImages < $iIndex ) {

            $iTotalGenerated = $iGeneratedImages;

            for ( $iNr = $iGeneratedImages + 1; $iNr <= $iIndex; $iNr++ ) {

                $sField = "oxarticles__oxpic" . $iNr;

                if ( $oObject->$sField->value ) {

                    $sMasterPictureSource = $oConfig->getMasterPictureDir() . $iNr . "/" . basename($oObject->$sField->value);

                    if ( file_exists( $sMasterPictureSource ) ) {
                        $sNewName = $this->_getArticleMasterPictureName( $oObject, $iNr );

                        $aFiles = array();

                        // main product picture
                        $sType = "P" . $iNr . "@oxarticles__oxpic" . $iNr;
                        $aFiles['myfile']['name'][$sType] = $sNewName;
                        $aFiles['myfile']['tmp_name'][$sType] = $sMasterPictureSource;

                        // zoom picture
                        $sType = "Z" . $iNr . "@oxarticles__oxzoom" . $iNr;
                        $oObject->{"oxarticles__oxzoom" . $iNr} =  new oxField();
                        $aFiles['myfile']['name'][$sType] = $sNewName;
                        $aFiles['myfile']['tmp_name'][$sType] = $sMasterPictureSource ;

                        $oUtilsFile->processFiles( $oObject, $aFiles, true, false );

                        // if this is picture with number #1, also generating
                        // thumbnail and icon
                        if ( $iNr == 1 ) {
                            $aFiles = array();
                            // Thumbnail
                            $sType = "TH@oxarticles__oxthumb";
                            $aFiles['myfile']['name'][$sType] = $sNewName;
                            $aFiles['myfile']['tmp_name'][$sType] = $sMasterPictureSource;

                            // Icon
                            $sType = "ICO@oxarticles__oxicon";
                            $aFiles['myfile']['name'][$sType] = $sNewName;
                            $aFiles['myfile']['tmp_name'][$sType] = $sMasterPictureSource;

                            $oUtilsFile->processFiles( null, $aFiles, true, false );
                        }

                        $iTotalGenerated++;
                    }
                }
            }

            if ( $iTotalGenerated > $iGeneratedImages ) {
                $oObject->updateAmountOfGeneratedPictures( $iIndex );
            }
        }
    }

    /**
     * Get article master picture file name.
     *
     * @param oxArticle $oObject article object
     * @param int       $iIndex  master picture index
     *
     * @return string
     */
    protected function _getArticleMasterPictureName( $oObject, $iIndex )
    {
        $sName = '';

        if ( $iIndex ) {
            $sName = $oObject->{"oxarticles__oxpic".$iIndex}->value;
            $sName = $this->_getBaseMasterImageFileName( $sName );

        }

        return $sName;
    }

    /**
     * Deletes master picture and all images generated from it.
     * If third parameter is false, skips master image delete, only
     * all generated images will be deleted.
     *
     * @param oxArticle $oObject               article object
     * @param int       $iIndex                master picture index
     * @param bool      $blDeleteMasterPicture delete master picture, default is true
     *
     * @return null
     */
    public function deleteArticleMasterPicture( $oObject, $iIndex, $blDeleteMasterPicture = true )
    {
        $oDB        = oxDb::getDb( true );
        $myConfig   = $this->getConfig();
        $myUtilsPic = oxUtilsPic::getInstance();
        $oUtilsFile = oxUtilsFile::getInstance();

        $aDelPics = array();
        $sAbsDynImageDir = $myConfig->getAbsDynImageDir();
        $sMasterImage = $oObject->{"oxarticles__oxpic".$iIndex}->value;

        if ( !$sMasterImage || $sMasterImage == "nopic.jpg" ) {
            return;
        }

        $aDelPics = array();

        if ( $blDeleteMasterPicture ) {
            // master picture
            $aDelPics[] = array("sField"    => "oxpic".$iIndex,
                                "sDir"      => $oUtilsFile->getImageDirByType( "M".$iIndex ),
                                "sFileName" => $sMasterImage);
        }

        // general picture
        $aDelPics[] = array("sField"    => "oxpic".$iIndex,
                            "sDir"      => $oUtilsFile->getImageDirByType( "P".$iIndex ),
                            "sFileName" => $sMasterImage);

        // zoom picture
        $sZoomPicName = $this->getZoomName( $sMasterImage, $iIndex );
        $aDelPics[] = array("sField"    => "oxpic1",
                            "sDir"      => $oUtilsFile->getImageDirByType( "Z".$iIndex ),
                            "sFileName" => $sZoomPicName);

        if ( $iIndex == 1 ) {
            // deleting generated main icon picture if custom main icon
            // file name not equal with generated from master picture
            if ( $this->getMainIconName( $sMasterImage ) != basename($oObject->oxarticles__oxicon->value) ) {
                $aDelPics[] = array("sField"    => "oxpic1",
                                    "sDir"      => $oUtilsFile->getImageDirByType( "ICO" ),
                                    "sFileName" => $this->getMainIconName( $sMasterImage ));
            }

            // deleting generated thumbnail picture if custom thumbnail
            // file name not equal with generated from master picture
            if ( $this->getThumbName( $sMasterImage ) != basename($oObject->oxarticles__oxthumb->value) ) {
                $aDelPics[] = array("sField"    => "oxpic1",
                                    "sDir"      => $oUtilsFile->getImageDirByType( "TH" ),
                                    "sFileName" => $this->getThumbName( $sMasterImage ));
            }
        }

        foreach ( $aDelPics as $aPic ) {
            $myUtilsPic->safePictureDelete( $aPic["sFileName"], $myConfig->getAbsDynImageDir() . $aPic["sDir"], "oxarticles", $aPic["sField"] );
        }

        //deleting custom zoom pic (compatibility mode)
        if ( $oObject->{"oxarticles__oxzoom".$iIndex}->value ) {
            if ( basename($oObject->{"oxarticles__oxzoom".$iIndex}->value) !== "nopic.jpg" ) {
                // deleting old zoom picture
                $this->deleteZoomPicture( $oObject, $iIndex );
            }
        }

    }

    /**
     * Deletes custom main icon, which name is specified in oxicon field.
     *
     * @param oxArticle $oObject article object
     *
     * @return null
     */
    public function deleteMainIcon( $oObject )
    {
        $oDB        = oxDb::getDb( true );
        $myConfig   = $this->getConfig();
        $myUtilsPic = oxUtilsPic::getInstance();
        $oUtilsFile = oxUtilsFile::getInstance();

        $sMainIcon = $oObject->oxarticles__oxicon->value;

        if ( !$sMainIcon ) {
            return;
        }

        $aDelPics = array();
        $sAbsDynImageDir = $myConfig->getAbsDynImageDir();

        $aDelPics = array();

        // deleting article main icon and thumb picture
        $aPic = array("sField"    => "oxicon",
                      "sDir"      => $oUtilsFile->getImageDirByType( "ICO" ),
                      "sFileName" => $sMainIcon);

        $myUtilsPic->safePictureDelete( $aPic["sFileName"], $myConfig->getAbsDynImageDir() . $aPic["sDir"], "oxarticles", $aPic["sField"] );
    }

    /**
     * Deletes custom thumbnail, which name is specified in oxthumb field.
     *
     * @param oxArticle $oObject article object
     *
     * @return null
     */
    public function deleteThumbnail( $oObject )
    {
        $oDB        = oxDb::getDb( true );
        $myConfig   = $this->getConfig();
        $myUtilsPic = oxUtilsPic::getInstance();
        $oUtilsFile = oxUtilsFile::getInstance();

        $aDelPics = array();
        $sAbsDynImageDir = $myConfig->getAbsDynImageDir();
        $sThumb = $oObject->oxarticles__oxthumb->value;

        if ( !$sThumb ) {
            return;
        }

        $aDelPics = array();

        // deleting article main icon and thumb picture
        $aPic = array("sField"    => "oxthumb",
                      "sDir"      => $oUtilsFile->getImageDirByType( "TH" ),
                      "sFileName" => $sThumb);

        $myUtilsPic->safePictureDelete( $aPic["sFileName"], $myConfig->getAbsDynImageDir() . $aPic["sDir"], "oxarticles", $aPic["sField"] );
    }

    /**
     * Deletes custom zoom picture, which name is specified in oxzoom field.
     *
     * @param oxArticle $oObject article object
     * @param int       $iIndex  zoom picture index
     *
     * @return null
     */
    public function deleteZoomPicture( $oObject, $iIndex )
    {
        // checking if oxzoom field exists
        $oDbHandler = oxNew( "oxDbMetaDataHandler" );
        $iZoomPicCount = (int) $this->getConfig()->getConfigParam( 'iZoomPicCount' );

        if ( $iIndex > $iZoomPicCount || !$oDbHandler->fieldExists( "oxzoom".$iIndex, "oxarticles" ) ) {
            return;
        }

        $oDB        = oxDb::getDb( true );
        $myConfig   = $this->getConfig();
        $myUtilsPic = oxUtilsPic::getInstance();
        $oUtilsFile = oxUtilsFile::getInstance();

        $aDelPics = array();
        $sAbsDynImageDir = $myConfig->getAbsDynImageDir();
        $sZoomPicName = basename($oObject->{"oxarticles__oxzoom".$iIndex}->value);

        if ( !$sZoomPicName ) {
            return;
        }

        $aDelPics = array();

        // deleting zoom picture
        $aPic = array("sField"    => "oxzoom".$iIndex,
                      "sDir"      => $oUtilsFile->getImageDirByType( "Z".$iIndex ),
                      "sFileName" => $sZoomPicName);

        $myUtilsPic->safePictureDelete( $aPic["sFileName"], $myConfig->getAbsDynImageDir() . $aPic["sDir"], "oxarticles", $aPic["sField"] );
    }

    /**
     * Returns article picture icon name for selected article picture
     *
     * @param string $sFilename file name
     *
     * @return string
     */
    public function getIconName( $sFilename )
    {
        $sIconName = getStr()->preg_replace( '/(\.jpg|\.gif|\.png)$/i', '_ico\\1', basename($sFilename) );

        return $sIconName;
    }

    /**
     * Returns article main icon name generated from master picture
     *
     * @param string $sMasterImageFile master image file name
     *
     * @return string
     */
    public function getMainIconName( $sMasterImageFile )
    {
        $sMasterImageFile = $this->_getBaseMasterImageFileName( $sMasterImageFile );
        $sIconName = getStr()->preg_replace( '/(\.jpg|\.gif|\.png)$/i', '_ico\\1', $sMasterImageFile );

        return $sIconName;
    }

    /**
     * Returns thumb image name generated from master picture
     *
     * @param string $sMasterImageFile master image file name
     *
     * @return string
     */
    public function getThumbName( $sMasterImageFile )
    {
        $sMasterImageFile = $this->_getBaseMasterImageFileName( $sMasterImageFile );
        $sThumbName = getStr()->preg_replace( '/(\.jpg|\.gif|\.png)$/i', '_th\\1', $sMasterImageFile );

        return $sThumbName;
    }

    /**
     * Returns zoom image name generated from master picture
     *
     * @param string $sMasterImageFile master image file name
     * @param string $iIndex           master image index
     *
     * @return string
     */
    public function getZoomName( $sMasterImageFile, $iIndex )
    {
        $sMasterImageFile = $this->_getBaseMasterImageFileName( $sMasterImageFile );
        $sZoomName = getStr()->preg_replace( '/(\.jpg|\.gif|\.png)$/i', '_z'.$iIndex.'\\1', $sMasterImageFile );

        return $sZoomName;
    }

    /**
     * Gets master image file name and removes suffics (e.g. _p1) from file end.
     *
     * @param string $sMasterImageFile master image file name
     *
     * @return null
     */
    protected function _getBaseMasterImageFileName( $sMasterImageFile )
    {
        $sMasterImageFile = getStr()->preg_replace( '/_p\d+(\.jpg|\.gif|\.png)$/i', '\\1', $sMasterImageFile );

        return basename( $sMasterImageFile );
    }
}

