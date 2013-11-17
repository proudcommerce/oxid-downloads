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
 * @package core
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxutilspic.php 21092 2009-07-22 14:42:13Z vilma $
 */

/**
 * Image manipulation class
 */
class oxUtilsPic extends oxSuperCfg
{
    /**
     * Image types 'enum'
     *
     * @var array
     */
    protected $_aImageTypes = array("GIF" => IMAGETYPE_GIF, "JPG" => IMAGETYPE_JPEG, "PNG" => IMAGETYPE_PNG);

    /**
     * oxUtils class instance.
     *
     * @var oxutilspic
     */
    private static $_instance = null;

    /**
     * Returns image utils instance
     *
     * @return oxUtilsPic
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            static $inst = array();
            self::$_instance = $inst[oxClassCacheKey()];
        }

        if ( !self::$_instance instanceof oxUtilsPic ) {


            self::$_instance = oxNew( 'oxUtilsPic' );

            if ( defined( 'OXID_PHP_UNIT' ) ) {
                $inst[oxClassCacheKey()] = self::$_instance;
            }
        }
        return self::$_instance;
    }


    /**
     * Resizes image to desired width and height, returns true on success.
     *
     * @param string $sSrc           Source of image file
     * @param string $sTarget        Target to write resized image file
     * @param mixed  $iDesiredWidth  Width of resized image
     * @param mixed  $iDesiredHeight Height of resized image
     *
     * @return bool
     */
    public function resizeImage( $sSrc, $sTarget, $iDesiredWidth, $iDesiredHeight )
    {
        $blResize = false;
        $myConfig = $this->getConfig();

        // use this GD Version
        if ( ( $iUseGDVersion = $myConfig->getConfigParam( 'iUseGDVersion' ) ) &&
               function_exists( 'imagecreate' ) && file_exists( $sSrc ) &&
              ( $aImageInfo = @getimagesize( $sSrc ) ) ) {

            // #1837/1177M - do not resize smaller pictures
            if ( $iDesiredWidth < $aImageInfo[0] || $iDesiredHeight < $aImageInfo[1] ) {
                if ( $aImageInfo[0] >= $aImageInfo[1]*( (float) ( $iDesiredWidth / $iDesiredHeight ) ) ) {
                    $iNewHeight = round( ( $aImageInfo[1] * (float) ( $iDesiredWidth / $aImageInfo[0] ) ), 0 );
                    $iNewWidth = $iDesiredWidth;
                } else {
                    $iNewHeight = $iDesiredHeight;
                    $iNewWidth = round( ( $aImageInfo[0] * (float) ( $iDesiredHeight / $aImageInfo[1] ) ), 0 );
                }
            } else {
                $iNewWidth = $aImageInfo[0];
                $iNewHeight = $aImageInfo[1];
            }

            if ( $iUseGDVersion == 1) {
                $hDestinationImage = imagecreate( $iNewWidth, $iNewHeight );
            } else {
                $hDestinationImage = imagecreatetruecolor( $iNewWidth, $iNewHeight );
            }

            $blResize = $this->_resize( $aImageInfo, $sSrc, $hDestinationImage, $sTarget, $iNewWidth, $iNewHeight, $iUseGDVersion, $myConfig->getConfigParam( 'blDisableTouch' ), $myConfig->getConfigParam( 'sDefaultImageQuality' ) );
        }
        return $blResize;
    }


    /**
     * deletes the given picutre and checks before if the picture is deletable
     *
     * @param string $sPicName        Name of picture file
     * @param string $sAbsDynImageDir the absolute image diectory, where to delete the given image ($myConfig->getAbsDynImageDir())
     * @param string $sTable          in which table
     * @param string $sField          table field value
     *
     * @return bool
     */
    public function safePictureDelete( $sPicName, $sAbsDynImageDir, $sTable, $sField )
    {
        $blDelete = false;
        if ( $this->_isPicDeletable( $sPicName, $sTable, $sField ) ) {
            $blDelete = $this->_deletePicture( $sPicName, $sAbsDynImageDir );
        }
        return $blDelete;
    }

    /**
     * Removes picture file from disk.
     *
     * @param string $sPicName        name of picture
     * @param string $sAbsDynImageDir the absolute image diectory, where to delete the given image ($myConfig->getAbsDynImageDir())
     *
     * @return null
     */
    protected function _deletePicture( $sPicName, $sAbsDynImageDir )
    {
        $blDeleted = false;
        $myConfig  = $this->getConfig();

        if ( !$myConfig->isDemoShop() && ( strpos( $sPicName, 'nopic.jpg' ) === false
                || strpos( $sPicName, 'nopic_ico.jpg' ) === false ) ) {

            $sFile = "$sAbsDynImageDir/$sPicName";
            if ( file_exists( $sFile ) ) {
                $blDeleted = unlink( $sFile );
            }

            // additionally deleting icon ..
            $sIconFile = preg_replace( "/(\.[a-z0-9]*$)/i", "_ico\\1", $sFile );
            if ( file_exists( $sIconFile ) ) {
                unlink( $sIconFile );
            }
        }
        return $blDeleted;
    }


    /**
     * Checks if current picture file is used in more than one table entry, returns
     * true if one, false if more than one.
     *
     * @param string $sPicName Name of picture file
     * @param string $sTable   in which table
     * @param string $sField   table field value
     *
     * @return bool
     */
    protected function _isPicDeletable( $sPicName, $sTable, $sField )
    {
        if ( !$sPicName || strpos( $sPicName, 'nopic.jpg' ) !== false || strpos( $sPicName, 'nopic_ico.jpg' ) !== false ) {
            return false;
        }

        $iCountUsed = oxDb::getDb()->getOne( "select count(*) from $sTable where $sField = '$sPicName' group by $sField " );

        if ( $iCountUsed > 1) {
            return false;
        }
        return true;
    }

    /**
     * Deletes picture if new is uploaded or changed
     *
     * @param object $oObject         in whitch obejct search for old values
     * @param string $sPicTable       pictures table
     * @param string $sPicField       where picture are stored
     * @param string $sPicType        how does it call in $_FILE array
     * @param string $sPicDir         directory of pic
     * @param array  $aParams         new input text array
     * @param string $sAbsDynImageDir the absolute image diectory, where to delete the given image ($myConfig->getAbsDynImageDir())
     *
     * @return null
     */
    public function overwritePic( $oObject, $sPicTable, $sPicField, $sPicType, $sPicDir, $aParams, $sAbsDynImageDir )
    {
        $blDelete = false;
        $sPic = $sPicTable.'__'.$sPicField;
        if ( isset( $oObject->{$sPic} ) &&
             ( $_FILES['myfile']['size'][$sPicType.'@'.$sPic] > 0 || $aParams[$sPic] != $oObject->{$sPic}->value ) ) {
            $blDelete = $this->safePictureDelete($oObject->{$sPic}->value, $sAbsDynImageDir, $sPicTable, $sPicField );
        }

        return $blDelete;
    }

    /**
     * Returns icon name for give image filename
     *
     * @param string $sFilename file name(withou path)
     *
     * @return string
     */
    public function iconName( $sFilename )
    {
        $sIconName = preg_replace( '/(\.jpg|\.gif|\.png)$/i', '_ico\\1', $sFilename );

        return $sIconName;
    }


    /**
     * Resizes and saves GIF image. This method was separated due to GIF transparency problems.
     *
     * @param string $sSrc            image file
     * @param string $sTarget         destination file
     * @param int    $iNewWidth       new width
     * @param int    $iNewHeight      new height
     * @param int    $iOriginalWidth  original width
     * @param int    $iOriginalHeigth original height
     * @param int    $iGDVer          GD packet version
     * @param bool   $blDisableTouch  false if "touch()" should be called
     *
     * @return bool
     */
    protected function _resizeGif( $sSrc, $sTarget, $iNewWidth, $iNewHeight, $iOriginalWidth, $iOriginalHeigth, $iGDVer, $blDisableTouch )
    {
        $hDestinationImage = imagecreate( $iNewWidth, $iNewHeight );
        $hSourceImage = imagecreatefromgif( $sSrc );
        $iTransparentColor = imagecolorresolve( $hSourceImage, 255, 255, 255 );
        $iFillColor = imagecolorresolve( $hDestinationImage, 255, 255, 255 );
        imagefill( $hDestinationImage, 0, 0, $iFillColor );
        imagecolortransparent( $hSourceImage, $iTransparentColor );

        if ( $iGDVer == 1 ) {
            imagecopyresized( $hDestinationImage, $hSourceImage, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOriginalWidth, $iOriginalHeigth );
        } else {
            imagecopyresampled( $hDestinationImage, $hSourceImage, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOriginalWidth, $iOriginalHeigth );
        }

        imagecolortransparent( $hDestinationImage, $fillColor );
        if ( !$blDisableTouch ) {
            touch( $sTarget );
        }
        imagegif( $hDestinationImage, $sTarget );
        imagedestroy( $hDestinationImage );
        imagedestroy( $hSourceImage );
        return true;
    }

    /**
     * type dependant image resizing
     *
     * @param array  $aImageInfo        Contains information on image's type / width / height
     * @param string $sSrc              source image
     * @param string $hDestinationImage Destination Image
     * @param string $sTarget           Resized Image target
     * @param int    $iNewWidth         Resized Image's width
     * @param int    $iNewHeight        Resized Image's height
     * @param mixed  $iGdVer            used GDVersion, if null or false returns false
     * @param bool   $blDisableTouch    false if "touch()" should be called for gif resizing
     * @param string $iDefQuality       quality for "imagejpeg" function
     *
     * @return bool
     */
    protected function _resize( $aImageInfo, $sSrc, $hDestinationImage, $sTarget, $iNewWidth, $iNewHeight, $iGdVer, $blDisableTouch, $iDefQuality )
    {
        startProfile("PICTURE_RESIZE");

        $blSuccess = false;
        switch ( $aImageInfo[2] ) {    //Image type
            case ( $this->_aImageTypes["GIF"] ):
                //php does not process gifs until 7th July 2004 (see lzh licensing)
                if ( function_exists( "imagegif" ) ) {
                    imagedestroy( $hDestinationImage );
                    $blSuccess = $this->_resizeGif( $sSrc, $sTarget, $iNewWidth, $iNewHeight, $aImageInfo[0], $aImageInfo[1], $iGdVer, $blDisableTouch );
                }
                break;
            case ( $this->_aImageTypes["JPG"] ):
                $hSourceImage = imagecreatefromjpeg( $sSrc );
                if ( $this->_copyAlteredImage( $hDestinationImage, $hSourceImage, $iNewWidth, $iNewHeight, $aImageInfo, $sTarget, $iGdVer, $blDisableTouch ) ) {
                    imagejpeg( $hDestinationImage, $sTarget, $iDefQuality );
                    imagedestroy( $hDestinationImage );
                    imagedestroy( $hSourceImage );
                    $blSuccess = true;
                }
                break;
            case ( $this->_aImageTypes["PNG"] ):
                $hSourceImage = imagecreatefrompng( $sSrc );

                if ( !imageistruecolor( $hSourceImage ) ) {
                    $hDestinationImage = imagecreate( $iNewWidth, $iNewHeight );
                }

                // fix for transparent images sets image to transparent
                $imgWhite = imagecolorallocate( $hDestinationImage, 255, 255, 255 );
                imagefill( $hDestinationImage, 0, 0, $imgWhite );
                imagecolortransparent( $hDestinationImage, $imgWhite );
                //end of fix

                if ( $this->_copyAlteredImage( $hDestinationImage, $hSourceImage, $iNewWidth, $iNewHeight, $aImageInfo, $sTarget, $iGdVer, $blDisableTouch ) ) {
                    imagepng( $hDestinationImage, $sTarget );
                    imagedestroy( $hDestinationImage );
                    imagedestroy( $hSourceImage );
                    $blSuccess = true;
                }
                break;
        }

        stopProfile("PICTURE_RESIZE");

        return $blSuccess;
    }

    /**
     * create and copy the resized image
     *
     * @param string $sDestinationImage file + path of destination
     * @param string $sSourceImage      file + path of source
     * @param int    $iNewWidth         new width of the image
     * @param int    $iNewHeight        new height of the image
     * @param array  $aImageInfo        additional info
     * @param string $sTarget           target file path
     * @param int    $iGdVer            used gd version
     * @param bool   $blDisableTouch    wether Touch() should be called or not
     *
     * @return null
     */
    protected function _copyAlteredImage( $sDestinationImage, $sSourceImage, $iNewWidth, $iNewHeight, $aImageInfo, $sTarget, $iGdVer, $blDisableTouch )
    {
        if ( $iGdVer == 1 ) {
            $blSuccess = imagecopyresized( $sDestinationImage, $sSourceImage, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $aImageInfo[0], $aImageInfo[1] );
        } else {
            $blSuccess = imagecopyresampled( $sDestinationImage, $sSourceImage, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $aImageInfo[0], $aImageInfo[1] );
        }

        if ( !$blDisableTouch && $blSuccess ) {
            @touch( $sTarget );
        }

        return $blSuccess;
    }

}
