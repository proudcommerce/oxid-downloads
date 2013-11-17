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
 * $Id: oxutilsfile.php 21470 2009-08-06 13:01:57Z rimvydas.paskevicius $
 */

/**
 * File manipulation utility class
 */
class oxUtilsFile extends oxSuperCfg
{
    /**
     * oxUtils class instance.
     *
     * @var oxutils* instance
     */
    private static $_instance = null;

    /**
     * Image type and its folder information array
     *
     * @var array
     */
    protected $_aTypeToPath = array( 'ICO'  => 'icon',
                                     'CICO' => 'icon',
                                     'TH'   => '0',
                                     'TC'   => '0',
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
            static $inst = array();
            self::$_instance = $inst[oxClassCacheKey()];
        }

        if ( !self::$_instance instanceof oxUtilsFile ) {

            self::$_instance = oxNew( 'oxUtilsFile' );
            if ( defined( 'OXID_PHP_UNIT' ) ) {
                $inst[oxClassCacheKey()] = self::$_instance;
            }
        }
        return self::$_instance;
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
        if ( isset($sDir) && $sDir && substr($sDir, -1) !== '/' ) {
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
     * @param object $sValue uploadable file name
     * @param string $sType  image type
     * @param object $blDemo if true = whecks if file type is defined in oxutilsfile::_aAllowedFiles
     *
     * @return string
     */
    protected function _prepareImageName( $sValue, $sType, $blDemo = false )
    {
        // add type to name
        $aFilename = explode( ".", $sValue );
        $sFileType = trim( $aFilename[count( $aFilename )-1] );

        if ( isset( $sFileType ) ) {

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
                $sFName = preg_replace( '/[^a-zA-Z0-9_\.-]/', '', implode( '.', $aFilename ) );
            }

            $sValue = "{$sFName}_" . strtolower( $sType ) . ".{$sFileType}";
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
        return $this->getConfig()->getAbsDynImageDir() . "/{$sFolder}/";
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
        $myConfig  = $this->getConfig();
        $oUtilsPic = oxUtilspic::getInstance();

        // add file process here
        $blCopy = false;
        switch ( $sType ) {
            case 'TH':
                if ( ( $sSize = $myConfig->getConfigParam( 'sThumbnailsize' ) ) ) {
                    // convert this file
                    $aSize = explode( '*', $sSize );
                    $blCopy = $oUtilsPic->resizeImage( $sSource, $sTarget, $aSize[0], $aSize[1] );
                }
                break;
            case 'TC':
                if ( ( $sSize = $myConfig->getConfigParam( 'sCatThumbnailsize' ) ) ) {
                    // convert this file
                    $aSize = explode( '*', $sSize );
                    $blCopy = $oUtilsPic->resizeImage( $sSource, $sTarget, $aSize[0], $aSize[1] );
                }
                break;
            case 'CICO':
            case 'ICO':
                if ( ( $sSize = $myConfig->getConfigParam( 'sIconsize' ) ) ) {
                    // convert this file
                    $aSize = explode( '*', $sSize );
                    $blCopy = $oUtilsPic->resizeImage( $sSource, $sTarget, $aSize[0], $aSize[1] );
                }
                break;
            case 'P1':
            case 'P2':
            case 'P3':
            case 'P4':
            case 'P5':
            case 'P6':
            case 'P7':
            case 'P8':
            case 'P9':
            case 'P10':
            case 'P11':
            case 'P12':
                //
                $aPType = explode( 'P', $sType );
                $iPic = intval( $aPType[1] ) - 1;

                // #840A + compatibility with prev. versions
                $aDetailImageSizes = $myConfig->getConfigParam( 'aDetailImageSizes' );
                $sDetailImageSize  = $myConfig->getConfigParam( 'sDetailImageSize' );
                if ( isset( $aDetailImageSizes['oxpic'.intval( $aPType[1] )] ) ) {
                    $sDetailImageSize = $aDetailImageSizes['oxpic'.intval( $aPType[1] )];
                }

                if ( $sDetailImageSize ) {
                    // convert this file
                    $aSize = explode( '*', $sDetailImageSize );
                    $blCopy = $oUtilsPic->resizeImage( $sSource, $sTarget, $aSize[0], $aSize[1] );

                    //make an icon
                    $sIconName = $oUtilsPic->iconName( $sTarget );
                    $aSize = explode( '*', $myConfig->getConfigParam( 'sIconsize' ) );
                    $blCopy = $oUtilsPic->resizeImage( $sSource, $sIconName, $aSize[0], $aSize[1] );
                }
                break;
            case 'Z1':
            case 'Z2':
            case 'Z3':
            case 'Z4':
            case 'Z5':
            case 'Z6':
            case 'Z7':
            case 'Z8':
            case 'Z9':
            case 'Z10':
            case 'Z11':
            case 'Z12':
                //
                $aPType = explode( 'Z', $sType );
                $iPic = intval( $aPType[1] ) - 1;

                // #840A + compatibility with prev. versions
                $aZoomImageSizes = $myConfig->getConfigParam( 'aZoomImageSizes' );
                $sZoomImageSize  = $myConfig->getConfigParam( 'sZoomImageSize' );
                if ( isset( $aZoomImageSizes['oxzoom'.intval( $aPType[1] )] ) ) {
                    $sZoomImageSize = $aZoomImageSizes['oxzoom'.intval( $aPType[1] )];
                }

                //
                if ( $sZoomImageSize ) {
                    // convert this file
                    $aSize = explode( '*', $sZoomImageSize );
                    $blCopy = $oUtilsPic->resizeImage( $sSource, $sTarget, $aSize[0], $aSize[1] );
                }
                break;
        }

        return $blCopy;
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
        if ( ( $blDone = move_uploaded_file( $sSource, $sTarget ) ) ) {
            $blDone = chmod( $sTarget, 0644 );
        }

        return $blDone;
    }

    /**
     * Uploaded file processor (filters, etc), sets configuration parameters to
     * passed object and returns it.
     *
     * @param object $oObject object, that parameters are modified according to passed files
     * @param array  $aFiles  name of files to process
     *
     * @return object
     */
    public function processFiles( $oObject = null, $aFiles = array() )
    {
        $aFiles = $aFiles ? $aFiles : $_FILES;
        if ( isset( $aFiles['myfile']['name'] ) ) {

            // A. protection for demoshops - strictly defining allowed file extensions
            $blDemo = (bool) $this->getConfig()->isDemoShop();

            // process all files
            while ( list( $sKey, $sValue ) = each( $aFiles['myfile']['name'] ) ) {

                $aSource = $aFiles['myfile']['tmp_name'];
                $sSource = $aSource[$sKey];
                $aFiletype = explode( "@", $sKey );
                $sKey    = $aFiletype[1];
                $sType   = $aFiletype[0];
                $sValue  = strtolower( $sValue );

                // no file ? - skip
                if ( $sValue ) {

                    // building file name
                    $sValue = $this->_prepareImageName( $sValue, $sType, $blDemo );

                    // finding directory
                    $sTarget = $this->_getImagePath( $sType ) . $sValue;

                    // processing images
                    $blCopy = $this->_prepareImage( $sType, $sSource, $sTarget );

                    // moving ..
                    if ( !$blCopy && $sSource ) {
                        $this->_moveImage( $sSource, $sTarget );
                    }

                    // assign the name
                    if ( $oObject && isset( $sValue ) && $sValue ) {
                        $oObject->{$sKey}->setValue( $sValue );
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
        if ( !preg_match('/^[_a-z0-3\.]+$/i', $aFileInfo['name'] ) ) {
            throw new oxException( 'EXCEPTION_FILENAMEINVALIDCHARS' );
        }

        // error uploading file ?
        if ( isset( $aFileInfo['error'] ) && $aFileInfo['error'] ) {
            throw new oxException( 'EXCEPTION_FILEUPLOADERROR_'.( (int) $aFileInfo['error'] ) );
        }

        $aPathInfo = pathinfo($aFileInfo['name']);

        $sExt = $aPathInfo['extension'];
        $sFileName = $aPathInfo['filename'];

        if ( !in_array( $sExt, $this->getConfig()->getConfigParam( 'aAllowedUploadTypes' ) ) ) {
            throw new oxException( 'EXCEPTION_NOTALLOWEDTYPE' );
        }

        //file exists ?
        $iFileCounter = 0;
        $sTempFileName = $sFileName;
        while (file_exists($sBasePath . "/" .$sUploadPath . "/" . $sFileName . "." . $sExt)) {
            $iFileCounter++;
            $sFileName = $sTempFileName . "($iFileCounter)";
        }

        move_uploaded_file($aFileInfo['tmp_name'], $sBasePath . "/" .$sUploadPath . "/" . $sFileName . "." . $sExt);

        $sUrl = $this->getConfig()->getShopUrl() . "/" . $sUploadPath . "/" . $sFileName . "." . $sExt;

        //removing dublicate slashes
        $sUrl = str_replace('//', '/', $sUrl);
        $sUrl = str_replace('http:/', 'http://', $sUrl);

        return $sUrl;
    }
}
