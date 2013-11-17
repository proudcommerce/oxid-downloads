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
 * @version   SVN: $Id: oxutilsfile.php 27797 2010-05-18 15:34:13Z rimvydas.paskevicius $
 */

/**
 * File manipulation utility class
 */
class oxUtilsFile extends oxSuperCfg
{
    /**
     * oxUtils class instance.
     *
     * @var oxutils
     */
    private static $_instance = null;

    /**
     * Max pictures count
     *
     * @var int
     */
    protected $_iMaxPicImgCount  = 12;

    /**
     * Max zoom pictures count
     *
     * @var int
     */
    protected $_iMaxZoomImgCount = 12;

    /**
     * Image type and its folder information array
     *
     * @var array
     */
    protected $_aTypeToPath = array( 'ICO'  => 'icon',
                                     'CICO' => 'icon',
                                     'TH'   => '0',
                                     'TC'   => '0',
                                     'M1'   => 'master/1',
                                     'M2'   => 'master/2',
                                     'M3'   => 'master/3',
                                     'M4'   => 'master/4',
                                     'M5'   => 'master/5',
                                     'M6'   => 'master/6',
                                     'M7'   => 'master/7',
                                     'M8'   => 'master/8',
                                     'M9'   => 'master/9',
                                     'M10'  => 'master/10',
                                     'M11'  => 'master/11',
                                     'M12'  => 'master/12',
                                     'P1'   => '1',
                                     'P2'   => '2',
                                     'P3'   => '3',
                                     'P4'   => '4',
                                     'P5'   => '5',
                                     'P6'   => '6',
                                     'P7'   => '7',
                                     'P8'   => '8',
                                     'P9'   => '9',
                                     'P10'  => '10',
                                     'P11'  => '11',
                                     'P12'  => '12',
                                     'Z1'   => 'z1',
                                     'Z2'   => 'z2',
                                     'Z3'   => 'z3',
                                     'Z4'   => 'z4',
                                     'Z5'   => 'z5',
                                     'Z6'   => 'z6',
                                     'Z7'   => 'z7',
                                     'Z8'   => 'z8',
                                     'Z9'   => 'z9',
                                     'Z10'  => 'z10',
                                     'Z11'  => 'z11',
                                     'Z12'  => 'z12'
                                   );

    /**
     * Denied file types
     *
     * @var array
     */
    protected $_aBadFiles = array( 'php', 'jsp', 'cgi', 'cmf', 'exe' );


    /**
     * Allowed to upload files in demo mode ( "white list")
     *
     * @var array
     */
    protected $_aAllowedFiles = array( 'gif', 'jpg', 'png', 'pdf' );
    /**
     * Returns object instance
     *
     * @return oxUtilsFile
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            self::$_instance = modInstances::getMod( __CLASS__ );
        }

        if ( !self::$_instance instanceof oxUtilsFile ) {

            self::$_instance = oxNew( 'oxUtilsFile' );
            if ( defined( 'OXID_PHP_UNIT' ) ) {
                modInstances::addMod( __CLASS__, self::$_instance);
            }
        }
        return self::$_instance;
    }

    /**
     * Class constructor, initailizes pictures count info (_iMaxPicImgCount/_iMaxZoomImgCount)
     *
     * @return null
     */
    public function __construct()
    {
        $myConfig = $this->getConfig();

        if ( $iPicCount = $myConfig->getConfigParam( 'iPicCount' ) ) {
            $this->_iMaxPicImgCount = $iPicCount;
        }

        $this->_iMaxZoomImgCount = $this->_iMaxPicImgCount;
    }

    /**
     * Normalizes dir by adding missing trailing slash
     *
     * @param string $sDir Directory
     *
     * @return string
     */
    public function normalizeDir( $sDir )
    {
        if ( isset($sDir) && $sDir != "" && substr($sDir, -1) !== '/' ) {
            $sDir .= "/";
        }

        return $sDir;
    }

    /**
     * Copies directory tree for creating a new shop.
     *
     * @param string $sSourceDir Source directory
     * @param string $sTargetDir Target directory
     *
     * @return null
     */
    public function copyDir( $sSourceDir, $sTargetDir )
    {
        $oStr = getStr();
        $handle = opendir( $sSourceDir );
        while ( false !== ( $file = readdir( $handle ) ) ) {
            if ( $file != '.' && $file != '..' ) {
                if ( is_dir( $sSourceDir.'/'.$file ) ) {

                    // recursive
                    $sNewSourceDir = $sSourceDir.'/'.$file;
                    $sNewTargetDir = $sTargetDir.'/'.$file;
                    if ( strcasecmp( $file, 'CVS' ) &&  strcasecmp( $file, '.svn' )) {
                        @mkdir( $sNewTargetDir, 0777 );
                        $this->copyDir( $sNewSourceDir, $sNewTargetDir );
                    }
                } else {
                    $sSourceFile = $sSourceDir.'/'.$file;
                    $sTargetFile = $sTargetDir.'/'.$file;

                    //do not copy files within dyn_images
                    if ( !$oStr->strstr( $sSourceDir, 'dyn_images' ) ||  $file == 'nopic.jpg' || $file == 'nopic_ico.jpg' ) {
                        @copy( $sSourceFile, $sTargetFile );
                    }
                }
            }
        }
        closedir($handle);
    }

    /**
     * Deletes directory tree.
     *
     * @param string $sSourceDir Path to directory
     *
     * @return null
     */
    public function deleteDir( $sSourceDir )
    {
        if ( is_dir( $sSourceDir ) ) {
            if ( $oDir = dir( $sSourceDir ) ) {

                while ( false !== $sFile = $oDir->read() ) {
                    if ( $sFile == '.' || $sFile == '..' ) {
                        continue;
                    }

                    if ( !$this->deleteDir( $oDir->path . DIRECTORY_SEPARATOR . $sFile ) ) {
                        $oDir->close();
                        return false;
                    }
                }

                $oDir->close();
                return rmdir( $sSourceDir );
            }
        } elseif ( file_exists( $sSourceDir ) ) {
            return unlink ( $sSourceDir );
        }
    }

    /**
     * Reads remote stored file. Returns contents of file.
     *
     * @param string $sPath Remote file path & name
     *
     * @return string
     */
    public function readRemoteFileAsString( $sPath )
    {
        $sRet  = '';
        $hFile = @fopen( $sPath, 'r' );
        if ( $hFile ) {
            socket_set_timeout( $hFile, 2 );
            while ( !feof( $hFile ) ) {
                $sLine = fgets( $hFile, 4096 );
                $sRet .= $sLine;
            }
            fclose( $hFile );
        }

        return $sRet;
    }

    /**
     * Prepares image file name
     *
     * @param object $sValue     uploadable file name
     * @param string $sType      image type
     * @param object $blDemo     if true = whecks if file type is defined in oxutilsfile::_aAllowedFiles
     * @param string $sImagePath final image file location
     *
     * @return string
     */
    protected function _prepareImageName( $sValue, $sType, $blDemo, $sImagePath )
    {
        if ( $sValue ) {
            // add type to name
            $aFilename = explode( ".", $sValue );
            $sFileType = trim( $aFilename[count( $aFilename )-1] );

            if ( isset( $sFileType ) ) {

                $oStr = getStr();

                // unallowed files ?
                if ( in_array( $sFileType, $this->_aBadFiles ) || ( $blDemo && !in_array( $sFileType, $this->_aAllowedFiles ) ) ) {
                    oxUtils::getInstance()->showMessageAndExit( "We don't play this game, go away" );
                }

                // removing file type
                if ( count( $aFilename ) > 0 ) {
                    unset( $aFilename[count( $aFilename )-1] );
                }

                $sFName = '';
                if ( isset( $aFilename[0] ) ) {
                    $sFName = $oStr->preg_replace( '/[^a-zA-Z0-9()_\.-]/', '', implode( '.', $aFilename ) );
                }

                // removing sufix from main pictures, only zoom pictures, thumbnails
                // and icons will have it.
                $sSufix = ( $oStr->preg_match( "/P\d+/", $sType ) ) ? "" : "_".strtolower( $sType );

                $sValue = $this->_getUniqueFileName( $sImagePath, "{$sFName}", $sFileType, $sSufix );
            }
        }
        return $sValue;
    }

    /**
     * Returns image storage path
     *
     * @param string $sType image type
     *
     * @return string
     */
    protected function _getImagePath( $sType )
    {
        $sFolder = array_key_exists( $sType, $this->_aTypeToPath ) ? $this->_aTypeToPath[ $sType ] : '0';
        $sPath = $this->normalizeDir( $this->getConfig()->getAbsDynImageDir() ) . "{$sFolder}/";
        return $sPath;
    }

    /**
     * Returns array of sizes which are used to resize images. If size is not
     * defined - NULL will be returned
     *
     * @param string $sImgType image type (TH, TC, ICO etc), can be useful for modules
     * @param int    $iImgNum  number of image (e.g. numper of ZOOM1 is 1)
     * @param string $sImgConf config parameter name, which keeps size info
     *
     * @return array | null
     */
    protected function _getImageSize( $sImgType, $iImgNum, $sImgConf )
    {
        $myConfig = $this->getConfig();
        $sSize = false;

        switch ( $sImgConf ) {
            case 'aDetailImageSizes':
                $aDetailImageSizes = $myConfig->getConfigParam( $sImgConf );
                $sSize = $myConfig->getConfigParam( 'sDetailImageSize' );
                if ( isset( $aDetailImageSizes['oxpic'.$iImgNum] ) ) {
                    $sSize = $aDetailImageSizes['oxpic'.$iImgNum];
                }
                break;
            default:
                $sSize = $myConfig->getConfigParam( $sImgConf );
                break;
        }
        if ( $sSize ) {
            return explode( '*', $sSize );
        }
    }

    /**
     * Prepares (resizes anc copies) images according to its type.
     * Returns preparation status
     *
     * @param string $sType   image type
     * @param string $sSource image location
     * @param string $sTarget image copy location
     *
     * @return array
     */
    protected function _prepareImage( $sType, $sSource, $sTarget )
    {
        $oUtilsPic = oxUtilspic::getInstance();
        $oPictureHandler = oxPictureHandler::getInstance();
        $oStr = getStr();

        // picture type
        $sPicType = $oStr->preg_replace( "/\d*$/", "", $sType );

        // numper of processable picture
        $iPicNum  = (int) $oStr->preg_replace( "/^\D*/", "", $sType );
        $iPicNum = $iPicNum ? abs( $iPicNum ) : 1;

        $aSize = false;
        $blResize = false;

        // add file process here
        switch ( $sPicType ) {
            case 'TH':
                $aSize = $this->_getImageSize( $sType, $iPicNum, 'sThumbnailsize' );
                break;
            case 'TC':
                $aSize = $this->_getImageSize( $sType, $iPicNum, 'sCatThumbnailsize' );
                break;
            case 'CICO':
            case 'ICO':
                $aSize = $this->_getImageSize( $sType, $iPicNum, 'sIconsize' );
                break;
            case 'P':
                // pictures count is limited to 12
                $iPicNum = ( $iPicNum > $this->_iMaxPicImgCount ) ? $this->_iMaxPicImgCount : $iPicNum;

                //make an icon
                if ( ( $aSize = $this->_getImageSize( $sType, 1, 'sIconsize' ) ) ) {
                    $sIconTarget = dirname($sTarget) . '/' . $oPictureHandler->getIconName( $sTarget );
                    $oUtilsPic->resizeImage( $sSource, $sIconTarget, $aSize[0], $aSize[1] );
                }

                $aSize = $this->_getImageSize( $sType, $iPicNum, 'aDetailImageSizes' );
                break;
            case 'M':
            case 'WP':
            case 'FL':
                // just copy non image file to target folder
                $this->_copyFile($sSource, $sTarget);
                break;
            case 'Z':
                $aSize = $this->_getImageSize( $sType, $iPicNum, 'sZoomImageSize' );
                break;
        }

        if ( $aSize ) {
            $blResize = $oUtilsPic->resizeImage( $sSource, $sTarget, $aSize[0], $aSize[1] );
        }
        return $blResize;
    }

    /**
     * Copy file from source to target location
     *
     * @param string $sSource file location
     * @param string $sTarget file location
     *
     * @return bool
     */
    protected function _copyFile( $sSource, $sTarget )
    {
        $blDone = false;

        if ( $sSource === $sTarget ) {
            $blDone = true;
        } else {
            $blDone = copy( $sSource, $sTarget );
        }

        if ( $blDone ) {
            $blDone = @chmod( $sTarget, 0644 );
        }

        return $blDone;
    }

    /**
     * Moves image from source to target location
     *
     * @param string $sSource image location
     * @param string $sTarget image copy location
     *
     * @return bool
     */
    protected function _moveImage( $sSource, $sTarget )
    {
        $blDone = false;

        if ( $sSource === $sTarget ) {
            $blDone = true;
        } else {
            $blDone = move_uploaded_file( $sSource, $sTarget );
        }

        if ( $blDone ) {
            $blDone = @chmod( $sTarget, 0644 );
        }

        return $blDone;
    }

    /**
     * Removes temporary created image. Returns deletion state
     *
     * @param string $sImagePath temporary image path
     *
     * @return bool
     */
    protected function _removeTempImage( $sImagePath )
    {
        return unlink( $sImagePath );
    }

    /**
     * Uploaded file processor (filters, etc), sets configuration parameters to
     * passed object and returns it.
     *
     * @param object $oObject          object, that parameters are modified according to passed files
     * @param array  $aFiles           name of files to process
     * @param bool   $blUseMasterImage use master image as source for processing
     *
     * @return object
     */
    public function processFiles( $oObject = null, $aFiles = array(), $blUseMasterImage = false )
    {
        $aFiles = $aFiles ? $aFiles : $_FILES;
        if ( isset( $aFiles['myfile']['name'] ) ) {

            $oConfig = $this->getConfig();
            $oStr = getStr();

            // A. protection for demoshops - strictly defining allowed file extensions
            $blDemo = (bool) $oConfig->isDemoShop();

            // folder where images will be processed
            $sTmpFolder = $oConfig->getConfigParam( "sCompileDir" );

            // process all files
            while ( list( $sKey, $sValue ) = each( $aFiles['myfile']['name'] ) ) {

                $aSource    = $aFiles['myfile']['tmp_name'];
                $sSource    = $aSource[$sKey];
                $aFiletype  = explode( "@", $sKey );
                $sKey       = $aFiletype[1];
                $sType      = $aFiletype[0];


                //if uplading master image, master image name will be with
                //sufics "p" (eg. image_p1.jpg). This is because of compatibility
                //with previous versions
                if ( $oStr->preg_match("/(M)(\d+)/", $sType, $aMatches ) ) {
                    $sMasterImageType = "P" . $aMatches[2];
                }

                $sValue  = strtolower( $sValue );
                $sImagePath = $this->_getImagePath( $sType );

                $sImageNameType = ( $sMasterImageType ) ? $sMasterImageType : $sType;

                // checking file type and building final file name
                if ( $sSource && ( $sValue = $this->_prepareImageName( $sValue, $sImageNameType, $blDemo, $sImagePath ) ) ) {
                    // moving to tmp folder for processing as safe mode or spec. open_basedir setup
                    // usually does not allow file modification in php's temp folder
                    $sProcessPath = $sTmpFolder . basename( $sSource );
                    if ( $sProcessPath ) {

                        if ( $blUseMasterImage ) {
                            //using master image as source, so only copying it to
                            //temp dir for processing
                            $blMoved = $this->_copyFile( $sSource, $sProcessPath );
                        } else {
                            $blMoved = $this->_moveImage( $sSource, $sProcessPath );
                        }

                        if ( $blMoved ) {
                            // finding final image path
                            if ( ( $sTarget = $sImagePath . $sValue ) ) {
                                // processing image and moving to final location
                                $this->_prepareImage( $sType, $sProcessPath, $sTarget );

                                // assign the name
                                if ( $oObject ) {
                                    $oObject->{$sKey}->setValue( $sValue );
                                }
                            }
                        }

                        // removing temporary file
                        $this->_removeTempImage( $sProcessPath );
                    }
                }
            }
        }

        return $oObject;
    }

    /**
     * Checks if passed file exists and may be opened for reading. Returns true
     * on success.
     *
     * @param string $sFile Name of file to check
     *
     * @return bool
     */
    function checkFile( $sFile )
    {
        $aCheckCache = oxSession::getVar("checkcache");

        if ( isset( $aCheckCache[$sFile] ) ) {
            return $aCheckCache[$sFile];
        }

        $blRet = false;

        if (is_readable( $sFile)) {
            $blRet = true;
        } else {
            // try again via socket
            $blRet = $this->urlValidate( $sFile );
        }

        $aCheckCache[$sFile] = $blRet;
        oxSession::setVar( "checkcache", $aCheckCache );

        return $blRet;
    }

    /**
     * Checks if given URL is accessible (HTTP-Code: 200)
     *
     * @param string $sLink given link
     *
     * @return boolean
     */
    function urlValidate( $sLink )
    {
        $aUrlParts = @parse_url( $sLink );
        $sHost = ( isset( $aUrlParts["host"] ) && $aUrlParts["host"] ) ? $aUrlParts["host"] : null;

        $blValid = false;
        if ( $sHost ) {
            $sDocumentPath  = ( isset( $aUrlParts["path"] ) && $aUrlParts["path"] ) ? $aUrlParts["path"] : '/';
            $sDocumentPath .= ( isset( $aUrlParts["query"] ) && $aUrlParts["query"] ) ? '?' . $aUrlParts["query"] : '';

            $sPort = ( isset( $aUrlParts["port"] ) && $aUrlParts["port"] ) ? $aUrlParts["port"] : '80';

            // Now (HTTP-)GET $documentpath at $sHost";
            if ( ( $oConn = @fsockopen( $sHost, $sPort, $iErrNo, $sErrStr, 30 ) ) ) {
                fwrite ( $oConn, "HEAD {$sDocumentPath} HTTP/1.0\r\nHost: {$sHost}\r\n\r\n" );
                $sResponse = fgets( $oConn, 22 );
                fclose( $oConn );

                if ( preg_match( "/200 OK/", $sResponse ) ) {
                    $blValid = true;
                }
            }
        }

        return $blValid;
    }

    /**
     * Handles uploaded path. Returns new URL to the file
     *
     * @param array  $aFileInfo   Global $_FILE parameter info
     * @param string $sUploadPath RELATIVE (to config sShopDir parameter) path for uploaded file to be copied
     *
     * @throws oxException if file is not valid
     *
     * @return string
     */
    public function handleUploadedFile($aFileInfo, $sUploadPath)
    {
        $sBasePath = $this->getConfig()->getConfigParam('sShopDir');

        //checking params
        if ( !isset( $aFileInfo['name'] ) || !isset( $aFileInfo['tmp_name'] ) ) {
            throw new oxException( 'EXCEPTION_NOFILE' );
        }

        //wrong chars in file name?
        if ( !getStr()->preg_match('/^[\-_a-z0-9\.]+$/i', $aFileInfo['name'] ) ) {
            throw new oxException( 'EXCEPTION_FILENAMEINVALIDCHARS' );
        }

        // error uploading file ?
        if ( isset( $aFileInfo['error'] ) && $aFileInfo['error'] ) {
            throw new oxException( 'EXCEPTION_FILEUPLOADERROR_'.( (int) $aFileInfo['error'] ) );
        }

        $aPathInfo = pathinfo($aFileInfo['name']);

        $sExt = $aPathInfo['extension'];
        $sFileName = $aPathInfo['filename'];

        $aAllowedUploadTypes = (array) $this->getConfig()->getConfigParam( 'aAllowedUploadTypes' );
        $aAllowedUploadTypes = array_map( "strtolower", $aAllowedUploadTypes );
        if ( !in_array( strtolower( $sExt ), $aAllowedUploadTypes ) ) {
            throw new oxException( 'EXCEPTION_NOTALLOWEDTYPE' );
        }

        $sFileName = $this->_getUniqueFileName( $sBasePath . $sUploadPath, $sFileName, $sExt );
        $this->_moveImage( $aFileInfo['tmp_name'], $sBasePath . $sUploadPath . "/" . $sFileName );

        $sUrl = $this->getConfig()->getShopUrl() . $sUploadPath . "/" . $sFileName;

        //removing dublicate slashes
        $sUrl = str_replace('//', '/', $sUrl);
        $sUrl = str_replace('http:/', 'http://', $sUrl);

        return $sUrl;
    }

    /**
     * Checks if file with same name does not exist, if exists - addes number prefix
     * to file name Returns unique file name.
     *
     * @param string $sFilePath file storage path/folder (e.g. /htdocs/out/img/)
     * @param string $sFileName name of file (e.g. picture1)
     * @param string $sFileExt  file extension (e.g. gif)
     * @param string $sSufix    file name sufix (e.g. _ico)
     *
     * @return string
     */
    protected function _getUniqueFileName( $sFilePath, $sFileName, $sFileExt, $sSufix = "" )
    {
        $sFilePath     = $this->normalizeDir( $sFilePath );
        $iFileCounter  = 0;
        $sTempFileName = $sFileName;
        $oStr = getStr();

        //file exists ?
        while ( file_exists( $sFilePath . "/" . $sFileName . $sSufix . "." . $sFileExt ) ) {
            $iFileCounter++;

            //removing "(any digit)" from file name end
            $sTempFileName = $oStr->preg_replace("/\(".$iFileCounter."\)/", "", $sTempFileName );

            $sFileName = $sTempFileName . "($iFileCounter)";
        }

        return $sFileName . $sSufix . "." . $sFileExt;
    }

    /**
     * Returns image storage path
     *
     * @param string $sType image type
     *
     * @return string
     */
    public function getImageDirByType( $sType )
    {
        $sFolder = array_key_exists( $sType, $this->_aTypeToPath ) ? $this->_aTypeToPath[ $sType ] : '0';

        return $this->normalizeDir( $sFolder );
    }
}
