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
 * $Id: oxutilsobject.php 17248 2009-03-16 15:22:07Z arvydas $
 */

/**
 * object manipulation class
 */
class oxUtilsObject extends oxSuperCfg
{
    /**
     * Cache class names
     *
     * @var array
     */
    protected $_aClassNameCache = array();

    /**
     * The array of already loaded articles
     *
     * @var array
     */
    protected static $_aLoadedArticles = array();

    /**
     * The array of already initialised instances
     *
     * @var unknown_type
     */
    protected static $_aInstanceCache = array();

    /**
     * oxUtils class instance.
     *
     * @var oxutils* instance
     */
    private static $_instance = null;

    /**
     * Returns object instance
     *
     * @return oxutilsobject
     */
    public static function getInstance()
    {
        // disable caching for test modules
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            static $inst = array();
            self::$_instance = $inst[oxClassCacheKey()];
        }

        if ( !self::$_instance instanceof oxUtilsObject ) {

            // allow modules
            $oUtilsObject = new oxUtilsObject();
            self::$_instance = $oUtilsObject->oxNew( 'oxUtilsObject' );

            if ( defined( 'OXID_PHP_UNIT' ) ) {
                $inst[oxClassCacheKey()] = self::$_instance;
            }
        }
        return self::$_instance;
    }

    /**
     * Creates and returns new object. If creation is not available, dies and outputs
     * error message.
     *
     * @param string $sClassName Name of class
     * @param string $sParams    Parameters to object
     *
     * @throws oxSystemComponentException in case that class does not exists
     *
     * @return object
     */
    public function oxNew( $sClassName, $sParams = null )
    {

        $sClassName = strtolower( $sClassName );
        $sCacheKey  = ($sParams !== null )?$sClassName.md5( serialize( $sParams ) ):$sClassName;

        if ( !defined( 'OXID_PHP_UNIT' ) ) {
            if ( isset( self::$_aInstanceCache[$sCacheKey] ) ) {
                return clone self::$_aInstanceCache[$sCacheKey];
            }
        }

        // performance
        if ( isset( $this->_aClassNameCache[$sClassName] ) ) {
            $sActionClassName = $this->_aClassNameCache[$sClassName];
        } else {
            $sActionClassName = $this->getClassName( $sClassName );
            //expect __autoload() (oxfunctions.php) to do its job when class_exists() is called
            if ( !class_exists( $sActionClassName ) ) {
                $oEx = new oxSystemComponentException();
                $oEx->setMessage('EXCEPTION_SYSTEMCOMPONENT_CLASSNOTFOUND');
                $oEx->setComponent($sClassName);
                $oEx->debugOut();
                throw $oEx;
            }
            // performance
            $this->_aClassNameCache[$sClassName] = $sActionClassName;
        }

        if ( $sParams ) {
            $oActionObject = new $sActionClassName( $sParams );
        } else {
            $oActionObject = new $sActionClassName();
        }

        if ( $oActionObject instanceof oxBase ) {
            self::$_aInstanceCache[$sCacheKey] = clone $oActionObject;
        }

        return $oActionObject;
    }

    /**
     * Creates and returns oxarticle (or subclass) object.
     *
     * @param string $sOxID       ID to load subclass type from database
     * @param array  $aProperties array of properties to assign
     *
     * @return object
     */
    public function oxNewArticle( $sOxID, $aProperties = array())
    {
        if ( $sOxID && isset( self::$_aLoadedArticles[$sOxID] ) ) {
            return self::$_aLoadedArticles[$sOxID];
        }

        $oActionObject = $this->oxNew( 'oxarticle' );

        // adding object prioperties
        foreach ( $aProperties as $sPropertyName => $sPropertyVal ) {
            $oActionObject->$sPropertyName = $sPropertyVal;
        }

        $oActionObject->load( $sOxID );

        self::$_aLoadedArticles[$sOxID] = $oActionObject;
        return $oActionObject;
    }

    /**
     * Resests instance cache
     *
     * @param string $sClassName class name in the cache
     *
     * @return null;
     */
    public function resetInstanceCache($sClassName = null)
    {
        if ($sClassName && isset(self::$_aInstanceCache[$sClassName])) {
            unset(self::$_aInstanceCache[$sClassName]);
            return;
        }

        //looping due to possible memory "leak".
        if (is_array(self::$_aInstanceCache)) {
            foreach (self::$_aInstanceCache as $sKey => $oInstance) {
                unset(self::$_aInstanceCache[$sKey]);
            }
        }

        self::$_aInstanceCache = array();
    }

    /**
     * Returns generated unique ID.
     *
     * @return string
     */
    public function generateUId()
    {
        return substr( $this->getSession()->getId(), 0, 3 ) . substr( md5( uniqid( '', true ).'|'.microtime() ), 0, 29 );
    }


    /**
     * Returns name of class file, according to class name.
     *
     * @param string $sClassName Class name
     *
     * @return string
     */
    public function getClassName( $sClassName )
    {
        $aModules = $this->getConfig()->getConfigParam( 'aModules' );
        if ( is_array( $aModules ) && array_key_exists( $sClassName, $aModules ) ) {
            //multiple inheritance implementation
            //in case we have multiple modules:
            //like oxoutput => sub/suboutput1&sub/suboutput2&sub/suboutput3
            $aClassChain = explode( "&", $aModules[$sClassName] );

            $sParent = $sClassName;

            //security: just preventing string termination
            $sParent = str_replace(chr(0), '', $sParent);

            //building middle classes if needed
            $sClassName = $this->_makeSafeModuleClassParents( $aClassChain, $sParent );
        }

        // check if there is a path, if yes, remove it
        $sClassName = basename( $sClassName );

        return $sClassName;
    }

    /**
     * Returns if modul exists.
     *
     * @param string $sClassName  Class name
     * @param string $sModuleName Modul name
     *
     * @return bool
     */
    public function isModuleActive( $sClassName, $sModuleName )
    {
        $aModules = $this->getConfig()->getConfigParam( 'aModules' );
        if ( is_array( $aModules ) && array_key_exists( $sClassName, $aModules ) ) {
            $aClassChain = explode( "&", $aModules[$sClassName] );
            foreach ($aClassChain as $sModule) {
                if ( basename($sModule) == $sModuleName ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Creates middle classes if needed.
     *
     * @param array  $aClassChain Module names
     * @param string $sBaseModule Oxid base class
     *
     * @throws oxSystemComponentException missing system component exception
     *
     * @return string
     */
    private function _makeSafeModuleClassParents( $aClassChain, $sBaseModule )
    {
        $myConfig = $this->getConfig();
        $sParent = $sBaseModule;

        //building middle classes if needed
        foreach ($aClassChain as $sModule) {
            //creating middle classes
            //e.g. class suboutput1_parent extends oxoutput {}
            //     class suboutput2_parent extends suboutput1 {}
            //$sModuleClass = $this->getClassName($sModule);

            //security: just preventing string termination
            $sModule = str_replace(chr(0), '', $sModule);

            //get parent and module class names from sub/suboutput2
            $sParentClass = basename($sParent);
            $sModuleClass = basename($sModule);

            //P
            //$sInitClass = "class ".$sModuleClass."_parent extends $sParentClass { function ".$sModuleClass."_parent(){ return ".$sParentClass."::".$sParentClass."();} }";
            $sInitClass = "class ".$sModuleClass."_parent extends $sParentClass {}";

            //initializing middle class
            if (!class_exists($sModuleClass."_parent", false)) {
                eval($sInitClass);
            }
            $sParentPath = $myConfig->getConfigParam( 'sShopDir' )."/modules/".$sModule.".php";

            //including original file
            if ( file_exists( $sParentPath ) ) {
                require_once $sParentPath;
            } elseif ( !class_exists( $sModuleClass ) ) { //to avoid problems with unitest and only throw a exception if class does not exists MAFI
                $oEx = new oxSystemComponentException();
                $oEx->setMessage('EXCEPTION_SYSTEMCOMPONENT_CLASSNOTFOUND');
                $oEx->setComponent($sModule);
            }

            $sParent = $sModule;
        }

        //returning the last module from the chain
        $sClassName = $aClassChain[count($aClassChain) - 1];
        return $sClassName;
    }
}
