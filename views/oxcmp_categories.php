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
 * @package views
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxcmp_categories.php 18244 2009-04-15 14:49:32Z arvydas $
 */

/**
 * Transparent category manager class (executed automatically).
 * @subpackage oxcmp
 */
class oxcmp_categories extends oxView
{
    /**
     * More category object.
     * @var object
     */
    protected $_oMoreCat = null;

    /**
     * Marking object as component
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Executes parent::init(), searches for active category in URL,
     * session, post variables ("cnid", "cdefnid"), active article
     * ("anid", usually article details), then loads article and
     * category if any of them available. Generates category/navigation
     * list.
     *
     * @return null
     */
    public function init()
    {
        parent::init();

        // Performance
        $myConfig = $this->getConfig();
        if ( $myConfig->getConfigParam( 'blDisableNavBars' ) &&
             $myConfig->getActiveView()->getIsOrderStep() ) {
            return;
        }

        $sActProduct = oxConfig::getParameter( 'anid' );
        $sActCont = oxConfig::getParameter( 'tpl' );
        $sActManufacturer = oxConfig::getParameter( 'mnid' );
        $sActCat = $sActManufacturer ? null : oxConfig::getParameter( 'cnid' );
        $sActTag = oxConfig::getParameter( 'searchtag' );

        $blArtLoaded = false;
        if ( $sActProduct ) {
            // some views has specific product getters..
            $oProduct = $this->_oParent->getViewProduct();
            $blArtLoaded = $oProduct ? true : false;
            if ( !$blArtLoaded ) {
                $oProduct = oxNew( 'oxarticle' );
                $oProduct->setSkipAbPrice( true );
                if ( $oProduct->load( $sActProduct ) ) {

                    // storing for reuse
                    $this->_oParent->setViewProduct( $oProduct );
                    $blArtLoaded = true;
                }
            }
        }

        // loaded article - then checking additional parameters
        if ( $blArtLoaded ) {
            $sActCat = $this->_addAdditionalParams( $oProduct, $sActCat, $sActManufacturer, $sActCont, $sActTag );
        }

        // Checking for the default category
        if ( $sActCat === null && !$blArtLoaded && !$sActCont && !$sActManufacturer && !$sActTag ) {
            // set remote cat
            $sActCat = $myConfig->getActiveShop()->oxshops__oxdefcat->value;
            if ( $sActCat == 'oxrootid' ) {
                // means none selected
                $sActCat= null;
            }
        }

        if ( $myConfig->getConfigParam( 'bl_perfLoadVendorTree' ) ) {
            // building vendor tree
            $this->_loadVendorTree( $sActCat );
        }

        if ( $myConfig->getConfigParam( 'bl_perfLoadManufacturerTree' ) ) {
            // building Manufacturer tree
            $this->_loadManufacturerTree( $sActManufacturer );
        }

        if ( $myConfig->getConfigParam( 'bl_perfLoadCatTree' ) ) {

            // building categorytree for all purposes (nav, search and simple category trees)
            $this->_loadCategoryTree( $sActCat );

            if ( $myConfig->getConfigParam( 'blTopNaviLayout' ) ) {
                $this->_oMoreCat = $this->_getMoreCategory( $sActCat, $sActCont );
            }

            if ( oxUtils::getInstance()->seoIsActive() ) {
                // tracking active category
                $this->_oParent->setSessionCategoryId( $sActCat );
            }
        }
    }

    /**
     * Category tree loader
     *
     * @param string $sActCat active category id
     *
     * @return null
     */
    protected function _loadCategoryTree( $sActCat )
    {
        $myConfig = $this->getConfig();
        if ( $myConfig->getConfigParam( 'bl_perfLoadCatTree' ) ) {
            $oCategoryTree = oxNew( 'oxcategorylist' );
            $oCategoryTree->buildTree( $sActCat, $myConfig->getConfigParam( 'blLoadFullTree' ), $myConfig->getConfigParam( 'bl_perfLoadTreeForSearch' ), $myConfig->getConfigParam( 'blTopNaviLayout' ) );

            // setting active category tree
            $this->_oParent->setCategoryTree( $oCategoryTree );

            // setting active category
            $this->_oParent->setActCategory( $oCategoryTree->getClickCat() );
        }
    }

    /**
     * Vendor tree loader
     *
     * @param string $sActVendor active vendor id
     *
     * @return null
     */
    protected function _loadVendorTree( $sActVendor )
    {
        $myConfig = $this->getConfig();
        if ( $myConfig->getConfigParam( 'bl_perfLoadVendorTree' ) ) {
            $oVendorTree = oxNew( 'oxvendorlist' );
            $oVendorTree->buildVendorTree( 'vendorlist', $sActVendor, $myConfig->getShopHomeURL() );

            // setting active vendor list
            $this->_oParent->setVendorTree( $oVendorTree );

            // setting active vendor
            if ( ( $oVendor = $oVendorTree->getClickVendor() ) ) {
                $this->_oParent->setActVendor( $oVendor );
            }
        }
    }

    /**
     * Manufacturer tree loader
     *
     * @param string $sActManufacturer active Manufacturer id
     *
     * @return null
     */
    protected function _loadManufacturerTree( $sActManufacturer )
    {
        $myConfig = $this->getConfig();
        if ( $myConfig->getConfigParam( 'bl_perfLoadManufacturerTree' ) ) {
            $oManufacturerTree = oxNew( 'oxmanufacturerlist' );
            $oManufacturerTree->buildManufacturerTree( 'manufacturerlist', $sActManufacturer, $myConfig->getShopHomeURL() );

            // setting active Manufacturer list
            $this->_oParent->setManufacturerTree( $oManufacturerTree );

            // setting active Manufacturer
            if ( ( $oManufacturer = $oManufacturerTree->getClickManufacturer() ) ) {
                $this->_oParent->setActManufacturer( $oManufacturer );
            }
        }
    }

    /**
     * Executes parent::render(), loads expanded/clicked category object,
     * adds parameters template engine and returns list of category tree.
     *
     * @return oxcategorylist
     */
    public function render()
    {
        parent::render();

        // Performance
        $myConfig = $this->getConfig();

        if ( $myConfig->getConfigParam( 'bl_perfLoadVendorTree' ) &&
             ( $oVendorTree = $this->_oParent->getVendorTree() )) {
            $this->_oParent->setVendorlist( $oVendorTree );
            $this->_oParent->setRootVendor( $oVendorTree->getRootCat() );

            // Passing to view. Left for compatibility reasons for a while. Will be removed in future
            $this->_oParent->addTplParam( 'rootvendor', $this->_oParent->getRootVendor() );
            $this->_oParent->addTplParam( 'aVendorlist', $this->_oParent->getVendorlist() );
            $this->_oParent->addTplParam( 'sVendorID', $this->_oParent->getVendorId() );
        }

        if ( $myConfig->getConfigParam( 'bl_perfLoadManufacturerTree' ) &&
             ( $oManufacturerTree = $this->_oParent->getManufacturerTree() ) ) {
            $this->_oParent->setManufacturerlist( $oManufacturerTree );
            $this->_oParent->setRootManufacturer( $oManufacturerTree->getRootCat() );

            // Passing to view. Left for compatibility reasons for a while. Will be removed in future
            $this->_oParent->addTplParam( 'rootmanufacturer', $this->_oParent->getRootManufacturer() );
            $this->_oParent->addTplParam( 'aManufacturerlist', $this->_oParent->getManufacturerlist() );
            $this->_oParent->addTplParam( 'sManufacturerID', $this->_oParent->getManufacturerId() );
        }

        if ( $myConfig->getConfigParam( 'bl_perfLoadCatTree' ) &&
             ( $oCategoryTree = $this->_oParent->getCategoryTree() ) ) {

            // we loaded full category tree ?
            if ( $myConfig->getConfigParam( 'bl_perfLoadTreeForSearch' ) ) {
                $this->_oParent->setSearchCatTree( $oCategoryTree );
                // Passing to view. Left for compatibility reasons for a while. Will be removed in future
                $this->_oParent->addTplParam( 'aSearchCatTree', $this->_oParent->getSearchCatTree() );
            }

            // new navigation ?
            if ( $myConfig->getConfigParam( 'blTopNaviLayout' ) ) {
                $this->_oParent->setCatMore( $this->_oMoreCat );
                // Passing to view. Left for compatibility reasons for a while. Will be removed in future
                $this->_oParent->addTplParam( 'navcategorytree', $oCategoryTree );
                $this->_oParent->addTplParam( 'navcategorycount', $oCategoryTree->count() );
                $this->_oParent->addTplParam( 'navcatmore', $this->_oParent->getCatMore() );
            }

            return $oCategoryTree;
        }
    }

    /**
     * Generates fake top navigation category 'oxmore' and handles expanding
     *
     * @param string $sActCat  active category id
     * @param string $sActCont active template
     *
     * @return oxStdClass
     */
    protected function _getMoreCategory( $sActCat, $sActCont )
    {
        $myConfig = $this->getConfig();
        $iTopCount = $myConfig->getConfigParam( 'iTopNaviCatCount' );
        $blExpanded = false;

        if ( $sActCat == 'oxmore' ) {
            $blExpanded = true;
        } else {
            $oCategoryTree = $this->_oParent->getCategoryTree();
            if ( $oCategoryTree ) {
                $iCnt = 0;
                foreach ( $oCategoryTree as $oCat ) {
                    $iCnt++;

                    if ( ( $aContent = $oCat->getContentCats() ) ) {
                        foreach ( $aContent as $oContent ) {
                            if ( $sActCont == $oContent->getId() && $iCnt > $iTopCount ) {
                                $blExpanded = true;
                                break 2;
                            }
                            $iCnt++;
                        }
                    }

                    if ( $oCat->getExpanded() && $iCnt > $iTopCount ) {
                        $blExpanded = true;
                        break;
                    }
                }
            }
        }

        $oMoreCat = new oxStdClass();
        $oMoreCat->closelink = $oMoreCat->openlink = $myConfig->getShopHomeURL().'cnid=oxmore';
        $oMoreCat->expanded  = $blExpanded;
        return $oMoreCat;
    }

    /**
     * Adds additional parameters: active category, list type and category id
     *
     * @param oxarticle $oProduct         loaded product
     * @param string    $sActCat          active category id
     * @param string    $sActManufacturer active manufacturer id
     * @param string    $sActCont         active template
     * @param string    $sActTag          active tag
     *
     * @return string $sActCat
     */
    protected function _addAdditionalParams( $oProduct, $sActCat, $sActManufacturer, $sActCont, $sActTag )
    {
        $sSearchPar = oxConfig::getParameter( 'searchparam' );
        $sSearchCat = oxConfig::getParameter( 'searchcnid' );
        $sSearchVnd = oxConfig::getParameter( 'searchvendor' );
        $sSearchMan = oxConfig::getParameter( 'searchmanufacturer' );
        $sListType  = oxConfig::getParameter( 'listtype' );

        if ( oxUtils::getInstance()->seoIsActive() ) {
            // tracking active category
            if ( ( $sSessCat = $this->_oParent->getSessionCategoryId() ) !== null ) {
                $sActCat = $sSessCat;
            }
        }

        // search ?
        //if ( !$sListType ) {
        // removed this check according to problems: if listtype is set, but active category not.
        // e.g. in details change language

        if ( !$sListType && ( $sSearchPar || $sSearchCat || $sSearchVnd ) ) {
            // setting list type directly
            $sListType = 'search';
        } else {

            // manufacturer ?
            $blManufacturer = false;
            if ( $this->getConfig()->getConfigParam( 'bl_perfLoadManufacturerTree' ) && $sActManufacturer ) {
                // such Manufacturer is available ?
                if ( $sActManufacturer == $oProduct->getManufacturerId() ) {
                    $blManufacturer = true;
                    // setting list type directly
                    $sListType = 'manufacturer';
                }
            }

            // vendor ?
            $blVendor = false;
            if ( !$blManufacturer && $sActCat && $this->getConfig()->getConfigParam( 'bl_perfLoadVendorTree' ) && eregi( '^v_.?', $sActCat ) ) {
                // such vendor is available ?
                if ( substr( $sActCat, 2 ) == $oProduct->getVendorId() ) {
                    $blVendor = true;
                    // setting list type directly
                    $sListType = 'vendor';
                }
            }

            // tag ?
            $blTags = false;
            if ( !$blVendor && !$blManufacturer && $sActTag ) {
                $blTags = true;
                // setting list type..
                $sListType = 'tag';
            }

            // category ?
            if ( $sActCat && !$blVendor && !$blManufacturer && !$blTags ) {
                if ( !$oProduct->isAssignedToCategory( $sActCat ) ) {
                    // article is assigned to any category ?
                    $aArticleCats = $oProduct->getCategoryIds();
                    if ( is_array( $aArticleCats ) && count( $aArticleCats ) ) {
                        $sActCat = reset( $aArticleCats );
                        // setting list type directly
                        $sListType = null;
                    } elseif ( ( $sActCat = $oProduct->getManufacturerId() ) ) {
                        // not assigned to any category ? maybe it is assigned to Manufacturer ?
                        // setting list type directly
                        $sListType = 'manufacturer';
                    } elseif ( ( $sActCat = $oProduct->getVendorId() ) ) {
                        // not assigned to any category ? maybe it is assigned to vendor ?
                        // setting list type directly
                        $sListType = 'vendor';
                    } else {
                        $sActCat = null;
                    }
                }
            } elseif ( !$sActCat && !$sActCont && !$sActManufacturer && !$blTags ) {
                $aArticleCats = $oProduct->getCategoryIds();
                if ( is_array( $aArticleCats ) && count( $aArticleCats ) ) {
                    $sActCat = reset( $aArticleCats );
                    // setting list type directly
                    $sListType  = null;
                } elseif ( ( $sActCat = $oProduct->getManufacturerId() ) ) {
                    // not assigned to any category ? maybe it is assigned to Manufacturer ?
                    // setting list type directly
                    $sListType = 'manufacturer';
                } elseif ( ( $sActCat = $oProduct->getVendorId() ) ) {
                    // not assigned to any category ? maybe it is assigned to vendor ?
                    // setting list type directly
                    $sListType = 'vendor';
                }
            }
        }

        //set list type and category id
        $this->_oParent->setListType( $sListType );
        $this->_oParent->setCategoryId( $sActCat );

        return $sActCat;
    }
}
