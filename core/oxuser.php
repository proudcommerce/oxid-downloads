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
 * @version   SVN: $Id: oxuser.php 28659 2010-06-28 13:32:01Z rimvydas.paskevicius $
 */

/**
 * User manager.
 * Performs user managing function, as assigning to groups, updating
 * information, deletion and other.
 * @package core
 */
class oxUser extends oxBase
{
    /**
     * Shop control variable
     * @var string
     */
    protected $_blDisableShopCheck = true;

    /**
     * Current Subscription Object if there is any
     * @var object
     */
    protected $_oNewsSubscription = null;

    /**
     * Core database table name. $_sCoreTbl could be only original data table name and not view name.
     * @var string
     */
    protected $_sCoreTbl = 'oxuser';

    /**
     * Current object class name
     * @var string
     */
    protected $_sClassName = 'oxuser';

    /**
     * User wish / notice list
     *
     * @var array
     */
    protected $_aBaskets = array();

    /**
     * User groups list
     *
     * @var oxlist
     */
    protected $_oGroups;

    /**
     * User address list array
     *
     * @var oxlist
     */
    protected $_aAddresses = array();

    /**
     * User payment list
     *
     * @var oxlist
     */
    protected $_oPayments;

    /**
     * User recommendation list
     *
     * @var oxlist
     */
    protected $_oRecommList;

    /**
     * Mall user status
     *
     * @var bool
     */
    protected $_blMallUsers = false;

    /**
     * user cookies
     *
     * @var array
     */
    protected static $_aUserCookie = array();

    /**
     * Notice list item's count
     *
     * @var integer
     */
    protected $_iCntNoticeListArticles = null;

    /**
     * Wishlist item's count
     *
     * @var integer
     */
    protected $_iCntWishListArticles = null;

    /**
     * User recommlist count
     *
     * @var integer
     */
    protected $_iCntRecommLists = null;

    /**
     * Password update key
     *
     * @var string
     */
     protected $_sUpdateKey = null;

    /**
     * User loaded from cookie
     *
     * @var bool
     */
     protected $_blLoadedFromCookie  = null;

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     *
     * @return null
     */
    public function __construct()
    {
        $this->setMallUsersStatus( $this->getConfig()->getConfigParam( 'blMallUsers' ) );

        parent::__construct();
        $this->init( 'oxuser' );
    }

    /**
     * Sets mall user status
     *
     * @param bool $blOn mall users is on or off
     *
     * @return null
     */
    public function setMallUsersStatus( $blOn = false )
    {
        $this->_blMallUsers = $blOn;
    }

    /**
     * Getter for special not frequently used fields
     *
     * @param string $sParamName name of parameter to get value
     *
     * @return mixed
     */
    public function __get( $sParamName )
    {
        // it saves memory using - loads data only if it is used
        switch ( $sParamName ) {
            case 'oGroups':
                return $this->_oGroups = $this->getUserGroups();
                break;
            case 'iCntNoticeListArticles':
                return $this->_iCntNoticeListArticles = $this->getNoticeListArtCnt();
                break;
            case 'iCntWishListArticles':
                return $this->_iCntWishListArticles = $this->getWishListArtCnt();
                break;
            case 'iCntRecommLists':
                return $this->_iCntRecommLists = $this->getRecommListsCount();
                break;
            case 'oAddresses':
                return $this->getUserAddresses();
                break;
            case 'oPayments':
                return $this->_oPayments = $this->getUserPayments();
                break;
            case 'oxuser__oxcountry':
                return $this->oxuser__oxcountry = $this->getUserCountry();
                break;
            case 'sDBOptin':
                return $this->sDBOptin = $this->getNewsSubscription()->getOptInStatus();
                break;
            case 'sEmailFailed':
                return $this->sEmailFailed = $this->getNewsSubscription()->getOptInEmailStatus();
                break;
        }
    }

    /**
     * Returns user newsletter subscription controller object
     *
     * @return object oxnewssubscribed
     */
    public function getNewsSubscription()
    {
        if ( $this->_oNewsSubscription !== null ) {
            return $this->_oNewsSubscription;
        }

        $this->_oNewsSubscription = oxNew( 'oxnewssubscribed' );

        // if subscription object is not set yet - we should create one
        if ( !$this->_oNewsSubscription->loadFromUserId( $this->getId() ) ) {
            if ( !$this->_oNewsSubscription->loadFromEmail( $this->oxuser__oxusername->value ) ) {

                // no subscription defined yet - creating one
                $this->_oNewsSubscription->oxnewssubscribed__oxuserid = new oxField($this->getId(), oxField::T_RAW);
                $this->_oNewsSubscription->oxnewssubscribed__oxemail  = new oxField($this->oxuser__oxusername->value, oxField::T_RAW);
                $this->_oNewsSubscription->oxnewssubscribed__oxsal    = new oxField($this->oxuser__oxsal->value, oxField::T_RAW);
                $this->_oNewsSubscription->oxnewssubscribed__oxfname  = new oxField($this->oxuser__oxfname->value, oxField::T_RAW);
                $this->_oNewsSubscription->oxnewssubscribed__oxlname  = new oxField($this->oxuser__oxlname->value, oxField::T_RAW);
            }
        }

        return $this->_oNewsSubscription;
    }

    /**
     * Returns user country (object) according to passed parameters or they
     * are taken from user object ( oxid, country id) and session (language)
     *
     * @param string $sCountryId country id (optional)
     * @param int    $iLang      active language (optional)
     *
     * @return string
     */
    public function getUserCountry( $sCountryId = null, $iLang = null )
    {
        $oDb = oxDb::getDb();
        if ( !$sCountryId ) {
            $sCountryId = $this->oxuser__oxcountryid->value;
        }

        $sQ = "select oxtitle".oxLang::getInstance()->getLanguageTag( $iLang )." from oxcountry where oxid = " . $oDb->quote( $sCountryId ) . " ";
        $this->oxuser__oxcountry = new oxField( $oDb->getOne( $sQ ), oxField::T_RAW);

        return $this->oxuser__oxcountry;
    }

    /**
     * Returns user countryid according to passed name
     *
     * @param string $sCountry country
     *
     * @return string
     */
    public function getUserCountryId( $sCountry = null )
    {
        $oDb = oxDb::getDb();
        $sQ = "select oxid from oxcountry where oxactive = '1' and oxisoalpha2 = " . $oDb->quote( $sCountry ) . " ";
        $sCountryId = $oDb->getOne( $sQ );

        return $sCountryId;
    }

    /**
     * Returns assigned user groups list object
     *
     * @param string $sOXID object ID (default is null)
     *
     * @return object
     */
    public function getUserGroups( $sOXID = null )
    {

        if ( isset( $this->_oGroups ) ) {
            return $this->_oGroups;
        }

        if ( !$sOXID ) {
            $sOXID = $this->getId();
        }

        $this->_oGroups = oxNew( 'oxlist', 'oxgroups' );
        //$this->oGroups->Init( 'oxbase' );
        //$this->oGroups->oLstoTpl->Init( array( 'oxgroups', 'oxobject2group' ) );
        $sSelect  = 'select oxgroups.* from oxgroups left join oxobject2group on oxobject2group.oxgroupsid = oxgroups.oxid ';
        $sSelect .= 'where oxobject2group.oxobjectid = ' . oxDb::getDb()->quote( $sOXID ) . ' ';
        $this->_oGroups->selectString( $sSelect );
        return $this->_oGroups;
    }

    /**
     * Returns user defined Address list object
     *
     * @param string $sUserId object ID (default is null)
     *
     * @return object
     */
    public function getUserAddresses( $sUserId = null )
    {
        $sUserId = isset( $sUserId ) ? $sUserId : $this->getId();
        if ( !isset( $this->_aAddresses[$sUserId] ) ) {
            $sSelect = "select * from oxaddress where oxaddress.oxuserid = " . oxDb::getDb()->quote( $sUserId ) . "";

            //P
            $this->_aAddresses[$sUserId] = oxNew( 'oxlist' );
            $this->_aAddresses[$sUserId]->init( "oxaddress" );
            $this->_aAddresses[$sUserId]->selectString( $sSelect );

            // marking selected
            if ( $sAddressId = $this->getSelectedAddressId() ) {
                foreach ( $this->_aAddresses[$sUserId] as $oAddress ) {
                    $oAddress->selected = 0;
                    if ( $oAddress->getId() === $sAddressId ) {
                        $oAddress->selected = 1;
                        break;
                    }
                }
            }
        }
        return $this->_aAddresses[$sUserId];
    }

    /**
     * Selected user address setter
     *
     * @param string $sAddressId selected address id
     *
     * @return null
     */
    public function setSelectedAddressId( $sAddressId )
    {
        $this->_sSelAddressId = $sAddressId;
    }

    /**
     * Returns user chosen address id ("oxaddressid" or "deladrid")
     *
     * @return string
     */
    public function getSelectedAddressId()
    {
        if ( $this->_sSelAddressId !== null ) {
            return $this->_sSelAddressId;
        }

        $sAddressId = oxConfig::getParameter( "oxaddressid");
        if ( !$sAddressId && !oxConfig::getParameter( 'reloadaddress' ) ) {
            $sAddressId = oxSession::getVar( "deladrid" );
        }
        return $sAddressId;
    }

    /**
     * Sets in the array oxuser::_aAddresses selected address.
     * Returns user selected Address id.
     *
     * @param bool $sWishId wishlist user id
     *
     * @return string $sAddressId
     */
    public function getSelectedAddress( $sWishId = false )
    {
        $oAddresses = $this->getUserAddresses();
        if ( $oAddresses->count() ) {
            if ( $sAddressId = $this->getSelectedAddressId() ) {
                foreach ( $oAddresses as $oAddress ) {
                    if ( $oAddress->selected == 1 ) {
                        $sAddressId = $oAddress->getId();
                        break;
                    }
                }
            } elseif ( $sWishId ) {
                foreach ( $oAddresses as $oAddress ) {
                    $oAddress->selected = 0;
                    if ( $oAddress->oxaddress__oxaddressuserid->value == $sWishId ) {
                        $oAddress->selected = 1;
                        $sAddressId = $oAddress->getId();
                    }
                }
            }

            // in case none is set - setting first one
            if ( !$sAddressId ) {
                $oAddresses->rewind();
                $oAddress = $oAddresses->current();
                $oAddress->selected = 1;
                $sAddressId = $oAddress->getId();
            }
        }

        return $sAddressId;
    }

    /**
     * Returns user payment history list object
     *
     * @param string $sOXID object ID (default is null)
     *
     * @return object oxlist with oxuserpayments objects
     */
    public function getUserPayments( $sOXID = null )
    {
        if ( $this->_oPayments === null ) {

            if ( !$sOXID ) {
                $sOXID = $this->getId();
            }

            $sSelect = 'select * from oxuserpayments where oxuserid = ' . oxDb::getDb()->quote( $sOXID ) . ' ';

            $this->_oPayments = oxNew( 'oxlist' );
            $this->_oPayments->init( 'oxUserPayment' );
            $this->_oPayments->selectString( $sSelect );

            $myUtils = oxUtils::getInstance();
            foreach ( $this->_oPayments as $oPayment ) {
                // add custom fields to this class
                $oPayment = $myUtils->assignValuesFromText( $val->oxuserpayments__oxvalue->value );
            }
        }

        return $this->_oPayments;
    }

    /**
     * Saves (updates) user object data information in DB. Return true on success.
     *
     * @return bool
     */
    public function save()
    {
        $myConfig  = oxConfig::getInstance();

        $blAddRemark = false;
        if ( $this->oxuser__oxpassword->value && $this->oxuser__oxregister->value < 1 ) {
            $blAddRemark = true;
            //save oxregister value
            $this->oxuser__oxregister = new oxField(date( 'Y-m-d H:i:s' ), oxField::T_RAW);
        }

        // setting user rights
        $this->oxuser__oxrights = new oxField($this->_getUserRights(), oxField::T_RAW);

        // processing birth date which came from output as array
        if ( is_array( $this->oxuser__oxbirthdate->value ) ) {
            $this->oxuser__oxbirthdate = new oxField($this->convertBirthday( $this->oxuser__oxbirthdate->value ), oxField::T_RAW);
        }

        // checking if user Facebook ID should be updated
        if ( $myConfig->getConfigParam( "bl_showFbConnect" ) ) {
            $oFb = oxFb::getInstance();
            if ( $oFb->isConnected() && $oFb->getUser() ) {
                 $this->oxuser__oxfbid = new oxField( $oFb->getUser() );
            }
        }

        $blRet = parent::save();

        //add registered remark
        if ( $blAddRemark && $blRet ) {
            $oRemark = oxNew( 'oxremark' );
            $oRemark->oxremark__oxtext     = new oxField(oxLang::getInstance()->translateString( 'usrRegistered' ), oxField::T_RAW);
            $oRemark->oxremark__oxtype     = new oxField('r', oxField::T_RAW);
            $oRemark->oxremark__oxparentid = new oxField($this->getId(), oxField::T_RAW);
            $oRemark->save();
        }

        return $blRet;
    }

    /**
     * Overrides parent isDerived check and returns true
     *
     * @return bool
     */
    public function allowDerivedUpdate()
    {
        return true;
    }

    /**
     * Checks if this object is in group, returns true on success.
     *
     * @param string $sGroupID user group ID
     *
     * @return bool
     */
    public function inGroup( $sGroupID )
    {
        $blIn = false;
        if ( ( $oGroups = $this->getUserGroups() ) ) {
            $blIn = isset( $oGroups[ $sGroupID ] );
        }

        return $blIn;
    }

    /**
     * Removes user data stored in some DB tables (such as oxuserpayments, oxaddress
     * oxobject2group, oxremark, etc). Return true on success.
     *
     * @param string $sOXID object ID (default null)
     *
     * @return bool
     */
    public function delete( $sOXID = null )
    {

        if ( !$sOXID ) {
            $sOXID = $this->getId();
        }
        if ( !$sOXID ) {
            return false;
        }

        $blDeleted = parent::delete( $sOXID );

        if ( $blDeleted ) {
            $oDB = oxDb::getDb();
            $sOXIDQuoted = $oDB->quote($sOXID);

            // deleting stored payment, address, group dependencies, remarks info
            $rs = $oDB->execute( "delete from oxaddress where oxaddress.oxuserid = {$sOXIDQuoted}" );
            $rs = $oDB->execute( "delete from oxobject2group where oxobject2group.oxobjectid = {$sOXIDQuoted}" );

            // deleting notice/wish lists
            $rs = $oDB->execute( "delete oxuserbasketitems.* from oxuserbasketitems, oxuserbaskets where oxuserbasketitems.oxbasketid = oxuserbaskets.oxid and oxuserid = {$sOXIDQuoted}" );
            $rs = $oDB->execute( "delete from oxuserbaskets where oxuserid = {$sOXIDQuoted}" );

            // deleting newsletter subscription
            $rs = $oDB->execute( "delete from oxnewssubscribed where oxuserid = {$sOXIDQuoted}" );

            // delivery and delivery sets
            $rs = $oDB->execute( "delete from oxobject2delivery where oxobjectid = {$sOXIDQuoted}");

            // discounts
            $rs = $oDB->execute( "delete from oxobject2discount where oxobjectid = {$sOXIDQuoted}");


            // and leaving all order related information
            $rs = $oDB->execute( "delete from oxremark where oxparentid = {$sOXIDQuoted} and oxtype !='o'" );

            $blDeleted = $rs->EOF;
        }

        return $blDeleted;
    }

    /**
     * Loads object (user) details from DB. Returns true on success.
     *
     * @param string $oxID User ID
     *
     * @return bool
     */
    public function load( $oxID )
    {

        $blRet = parent::Load( $oxID );

        // convert date's to international format
        if ( isset( $this->oxuser__oxcreate->value ) ) {
            $this->oxuser__oxcreate->setValue(oxUtilsDate::getInstance()->formatDBDate( $this->oxuser__oxcreate->value ));
        }

        return $blRet;
    }

    /**
     * Checks if user exists in database.
     *
     * @param string $sOXID object ID (default null)
     *
     * @return bool
     */
    public function exists( $sOXID = null )
    {
        $oDb = oxDb::getDb();
        if ( !$sOXID ) {
            $sOXID = $this->getId();
        }

        $sSelect = 'SELECT oxid FROM '.$this->getViewName().'
                    WHERE ( oxusername = '.$oDb->quote( $this->oxuser__oxusername->value).'';

        if ( $sOXID ) {
            $sSelect.= " or oxid = ".$oDb->quote( $sOXID ) . " ) ";
        } else {
            $sSelect.= ' ) ';
        }

        if ( !$this->_blMallUsers && $this->oxuser__oxrights->value != 'malladmin') {
            $sSelect .= ' AND oxshopid = "'.$this->getConfig()->getShopId().'" ';
        }

        $blExists = false;
        if ( ( $sOxid = oxDb::getDb()->getOne( $sSelect ) ) ) {
             // update - set oxid
            $this->setId( $sOxid );
            $blExists = true;
        }
        return $blExists;
    }

    /**
     * Returns object with ordering information (order articles list).
     *
     * @param int $iLimit how many entries to load
     * @param int $iPage  which page to start
     *
     * @return object
     */
    public function getOrders( $iLimit = false, $iPage = 0 )
    {
        $myConfig = $this->getConfig();
        $oOrders = oxNew( 'oxlist' );
        $oOrders->init( 'oxorder' );

        if ( $iLimit !== false ) {
            $oOrders->setSqlLimit( $iLimit * $iPage, $iLimit );
        }

        //P
        // Lists does not support loading from two tables, so orders
        // articles now are loaded in account_order.php view and no need to use blLoadProdInfo
        // forcing to load product info which is used in templates
        // $oOrders->aSetBeforeAssign['blLoadProdInfo'] = true;

        //loading order for registered user
        if ( $this->oxuser__oxregister->value > 1 ) {
            $sQ = 'select * from oxorder where oxuserid = "'.$this->getId().'" and oxorderdate >= ' . oxDb::getDb()->quote( $this->oxuser__oxregister->value ) . ' ';

            //#1546 - shopid check added, if it is not multishop

            $sQ .= ' order by oxorderdate desc ';
            $oOrders->selectString( $sQ );
        }

        return $oOrders;
    }

    /**
     * Caclulates amount of orders made by user
     *
     * @return int
     */
    public function getOrderCount()
    {
        $iCnt = 0;
        if ( $this->getId() && $this->oxuser__oxregister->value > 1 ) {
            $oDb = oxDb::getDb();
            $sQ  = 'select count(*) from oxorder where oxuserid = "'.$this->getId().'" AND oxorderdate >= ' . $oDb->quote( $this->oxuser__oxregister->value) . ' and oxshopid = "'.$this->getConfig()->getShopId().'" ';
            $iCnt = (int) $oDb->getOne( $sQ );
        }

        return $iCnt;
    }

    /**
     * Returns amount of articles in noticelist
     *
     * @return int
     */
    public function getNoticeListArtCnt()
    {
        if ( $this->_iCntNoticeListArticles === null ) {
            $this->_iCntNoticeListArticles = 0;
            if ( $this->getId() ) {
                $this->_iCntNoticeListArticles = $this->getBasket( 'noticelist' )->getItemCount();
            }
        }
        return $this->_iCntNoticeListArticles;
    }

    /**
     * Calculating user wishlist item count
     *
     * @return int
     */
    public function getWishListArtCnt()
    {
        if ( $this->_iCntWishListArticles === null ) {
            $this->_iCntWishListArticles = false;
            if ( $this->getId() ) {
                $this->_iCntWishListArticles = $this->getBasket( 'wishlist' )->getItemCount();
            }
        }
        return $this->_iCntWishListArticles;
    }

    /**
     * Returns user country ID, but If delivery address is given - returns
     * delivery country.
     *
     * @return string
     */
    public function getActiveCountry()
    {
        $sDeliveryCountry = '';
        if ( $soxAddressId = oxConfig::getParameter( 'deladrid' ) ) {
            $oDelAddress = oxNew( 'oxaddress' );
            $oDelAddress->load( $soxAddressId );
            $sDeliveryCountry = $oDelAddress->oxaddress__oxcountryid->value;
        } elseif ( $this->getId() ) {
            $sDeliveryCountry = $this->oxuser__oxcountryid->value;
        } else {
            $oUser = oxNew( 'oxuser' );
            if ( $oUser->loadActiveUser() ) {
                $sDeliveryCountry = $oUser->oxuser__oxcountryid->value;
            }
        }

        return $sDeliveryCountry;
    }

    /**
     * Inserts new or updates existing user
     *
     * @throws oxUserException exception
     *
     * @return bool
     */
    public function createUser()
    {
        $oDB = oxDb::getDb();
        $sShopID = $this->getConfig()->getShopId();

        // check if user exists AND there is no password - in this case we update otherwise we try to insert
        $sSelect = "select oxid from oxuser where oxusername = " . $oDB->quote( $this->oxuser__oxusername->value ) . " and oxpassword = '' ";
        if ( !$this->_blMallUsers ) {
            $sSelect .= " and oxshopid = '{$sShopID}' ";
        }
        $sOXID = $oDB->getOne( $sSelect );

        // user without password found - lets use
        if ( isset( $sOXID ) && $sOXID ) {
            // try to update
            $this->delete( $sOXID );
        } elseif ( $this->_blMallUsers ) {
            // must be sure if there is no dublicate user
            $sQ = "select oxid from oxuser where oxusername = " . $oDB->quote( $this->oxuser__oxusername->value ) . " and oxusername != '' ";
            if ( $oDB->getOne( $sQ ) ) {
                $oEx = oxNew( 'oxUserException' );
                $oLang = oxLang::getInstance();
                $oEx->setMessage( sprintf( $oLang->translateString( 'EXCEPTION_USER_USEREXISTS', $oLang->getTplLanguage() ), $this->oxuser__oxusername->value ) );
                throw $oEx;
            }
        }

        $this->oxuser__oxshopid = new oxField( $sShopID, oxField::T_RAW );
        if ( ( $blOK = $this->save() ) ) {
            // dropping/cleaning old delivery address/payment info
            $oDB->execute( "delete from oxaddress where oxaddress.oxuserid = " . $oDB->quote( $this->oxuser__oxid->value ) . " " );
            $oDB->execute( "update oxuserpayments set oxuserpayments.oxuserid = " . $oDB->quote( $this->oxuser__oxusername->value ) . " where oxuserpayments.oxuserid = " . $oDB->quote( $this->oxuser__oxid->value ) . " " );
        } else {
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage( 'EXCEPTION_USER_USERCREATIONFAILED' );
            throw $oEx;
        }

        return $blOK;
    }

    /**
     * Adds user to the group
     *
     * @param string $sGroupID group id
     *
     * @return bool
     */
    public function addToGroup( $sGroupID )
    {
        if ( !$this->inGroup( $sGroupID ) ) {
            $oNewGroup = oxNew( 'oxobject2group' );
            $oNewGroup->oxobject2group__oxobjectid = new oxField($this->getId(), oxField::T_RAW);
            $oNewGroup->oxobject2group__oxgroupsid = new oxField($sGroupID, oxField::T_RAW);
            if ( $oNewGroup->save() ) {
                $this->_oGroups[$sGroupID] = $oNewGroup;
                return true;
            }
        }
        return false;
    }

    /**
     * Removes user from passed user group.
     *
     * @param string $sGroupID group id
     *
     * @return null
     */
    public function removeFromGroup( $sGroupID = null )
    {
        if ( $sGroupID != null && $this->inGroup( $sGroupID ) ) {
            $oGroups = oxNew( 'oxlist' );
            $oGroups->init( 'oxobject2group' );
            $sSelect = 'select * from oxobject2group where oxobject2group.oxobjectid = "'.$this->getId().'" and oxobject2group.oxgroupsid = "'.$sGroupID.'" ';
            $oGroups->selectString( $sSelect );
            foreach ( $oGroups as $oRemgroup ) {
                if ( $oRemgroup->delete() ) {
                    unset( $this->_oGroups[$oRemgroup->oxobject2group__oxgroupsid->value] );
                }
            }
        }
    }

    /**
     * Called after saving an order.
     *
     * @param object $oBasket  Shopping basket object
     * @param int    $iSuccess order success status
     *
     * @return null
     */
    public function onOrderExecute( $oBasket, $iSuccess )
    {

        if ( is_numeric( $iSuccess ) && $iSuccess != 2 && $iSuccess <= 3 ) {
            //adding user to particular customer groups
            if ( !$this->oxuser__oxdisableautogrp->value ) {

                $myConfig = $this->getConfig();
                $dMidlleCustPrice = (float) $myConfig->getConfigParam( 'sMidlleCustPrice' );
                $dLargeCustPrice  = (float) $myConfig->getConfigParam( 'sLargeCustPrice' );

                $this->addToGroup( 'oxidcustomer' );
                $dBasketPrice = $oBasket->getPrice()->getBruttoPrice();
                if ( $dBasketPrice < $dMidlleCustPrice ) {
                    $this->addToGroup( 'oxidsmallcust' );
                }
                if ( $dBasketPrice >= $dMidlleCustPrice && $dBasketPrice < $dLargeCustPrice ) {
                    $this->addToGroup( 'oxidmiddlecust' );
                }
                if ( $dBasketPrice >= $dLargeCustPrice ) {
                    $this->addToGroup( 'oxidgoodcust' );
                }
            }

            if ( $this->inGroup( 'oxidnotyetordered' ) ) {
                $this->removeFromGroup( 'oxidnotyetordered' );
            }
        }
    }

    /**
     * Returns notice, wishlist or saved basket object
     *
     * @param string $sName name/type of basket
     *
     * @return oxuserbasket
     */
    public function getBasket( $sName )
    {
        if ( !isset( $this->_aBaskets[$sName] ) ) {
            $oBasket = oxNew( 'oxuserbasket' );
            $aWhere = array( 'oxuserbaskets.oxuserid' => $this->getId(), 'oxuserbaskets.oxtitle' => $sName );

            // creating if it does not exist
            if ( !$oBasket->assignRecord( $oBasket->buildSelectString( $aWhere ) ) ) {
                $oBasket->oxuserbaskets__oxtitle  = new oxField($sName);
                $oBasket->oxuserbaskets__oxuserid = new oxField($this->getId());

                // marking basket as new (it will not be saved in DB yet)
                $oBasket->setIsNewBasket();
            }

            $this->_aBaskets[$sName] = $oBasket;
        }

        return $this->_aBaskets[$sName];
    }

    /**
     * User birthday converter. Usually this data comes in array form, so before
     * writing into DB it must be converted into string
     *
     * @param array $aData dirthday data
     *
     * @return string
     */
    public function convertBirthday( $aData )
    {

        // preparing data to process
        $iYear  = isset($aData['year'])?((int) $aData['year']):false;
        $iMonth = isset($aData['month'])?((int) $aData['month']):false;
        $iDay   = isset($aData['day'])?((int) $aData['day']):false;

        // leaving empty if not set
        if ( !$iYear && !$iMonth && !$iDay )
            return "";

        // year
        if ( !$iYear || $iYear < 1000 || $iYear > 9999)
            $iYear = date('Y');

        // month
        if ( !$iMonth || $iMonth < 1 || $iMonth > 12)
            $iMonth = 1;

        // maximum nuber of days in month
        $iMaxDays = 31;
        switch( $iMonth) {
            case 2 :
                if ($iMaxDays > 28)
                    $iMaxDays = ($iYear % 4 == 0 && ($iYear % 100 != 0 || $iYear % 400 == 0)) ? 29 : 28;
                break;
            case 4  :
            case 6  :
            case 9  :
            case 11 :
                $iMaxDays = min(30, $iMaxDays);
                break;
        }

        // day
        if ( !$iDay || $iDay < 1 || $iDay > $iMaxDays) {
            $iDay = 1;
        }

        // whole date
        return sprintf("%04d-%02d-%02d", $iYear, $iMonth, $iDay);
    }

    /**
     * No logic set, only returns "1000". You should extend this function
     * according your needs.
     *
     * @return integer
     */
    public function getBoni()
    {
        return 1000;
    }

    /**
     * If there is a directove to add user to dynamic group (usually
     * by URL - "dgr=any_group") - tries to add user to it. First
     * checks if passed dynamic group is not in denied group list
     * (defined ar oxConfig::aDeniedDynGroups) and if not - adds user
     * to this group.
     *
     * @param string $sDynGoup         deny group (oxSession::getVar( 'dgr' ))
     * @param array  $aDeniedDynGroups ($myConfig->getConfigParam( 'aDeniedDynGroups' ))
     *
     * @return bool
     */
    public function addDynGroup( $sDynGoup, $aDeniedDynGroups )
    {
        // preparing input
        $sDynGoup = strtolower( trim( $sDynGoup ) );

        // setting denied groups from admin settings also
        $aDisabledDynGroups = array_merge( array( 'oxidadmin' ), (array) $aDeniedDynGroups );

        // default state ..
        $blAdd = false;

        // user assignment to dyn group is not allowed
        if ( $this->oxuser__oxdisableautogrp->value || !$sDynGoup ) {
            $blAdd = false;
        } elseif ( in_array( $sDynGoup, $aDisabledDynGroups ) ) {
            // trying to add user to prohibited user group?
            $blAdd = false;
        } elseif ( $this->addToGroup( $sDynGoup ) ) {
            $blAdd = true;
        }

        // cleanup
        oxSession::deleteVar( 'dgr' );

        return $blAdd;
    }

    /**
     * Performs bunch of checks if user profile data is correct; on any
     * error exception is thrown
     *
     * @param string $sLogin      user login name
     * @param string $sPassword   user password
     * @param string $sPassword2  user password to compare
     * @param array  $aInvAddress array of user profile data
     * @param array  $aDelAddress array of user profile data
     *
     * @throws oxUserException, oxInputException
     *
     * @return null
     *
     */
    public function checkValues( $sLogin, $sPassword, $sPassword2, $aInvAddress, $aDelAddress )
    {
        // 1. checking user name
        $sLogin = $this->_checkLogin( $sLogin, $aInvAddress );

        // 2. cheking email
        $this->_checkEmail( $sLogin );

        // 3. password
        $this->_checkPassword( $sPassword, $sPassword2, ((int) oxConfig::getParameter( 'option' ) == 3) );

        // 4. required fields
        $this->_checkRequiredFields( $aInvAddress, $aDelAddress );

        // 5. country check
        $this->_checkCountries( $aInvAddress, $aDelAddress );

        // 6. vat id check.
            $this->_checkVatId( $aInvAddress );
    }

    /**
     * Sets newsletter subscription status to user
     *
     * @param bool $blSubscribe subscribes/unsubscribes user from newsletter
     * @param bool $blSendOptIn if to send confirmation email
     *
     * @return bool
     */
    public function setNewsSubscription( $blSubscribe, $blSendOptIn )
    {
        // assigning to newsletter
        $blSuccess = false;
        $myConfig  = $this->getConfig();

        // user wants to get newsletter messages or no ?
        $oNewsSubscription = $this->getNewsSubscription();
        if ( $blSubscribe && $oNewsSubscription->getOptInStatus() != 1 ) {
            if ( !$blSendOptIn ) {

                // double-opt-in check is disabled - assigning automatically
                $this->addToGroup( 'oxidnewsletter' );
                // and setting subscribed status
                $oNewsSubscription->setOptInStatus( 1 );
                $blSuccess = true;
            } else {

                // double-opt-in check enabled - sending confirmation email and setting waiting status
                $oNewsSubscription->setOptInStatus( 2 );

                // sending double-opt-in mail
                $oEmail = oxNew( 'oxemail' );
                $blSuccess = $oEmail->sendNewsletterDBOptInMail( $this );
            }
        } elseif ( !$blSubscribe ) {
            // removing user from newsletter subscribers
            $this->removeFromGroup( 'oxidnewsletter' );
            $oNewsSubscription->setOptInStatus( 0 );
            $blSuccess = true;
        }

        return $blSuccess;
    }

    /**
     * When changing/updating user information in frontend this method validates user
     * input. If data is fine - automatically assigns this values. Additionally calls
     * methods (oxuser::_setAutoGroups, oxuser::setNewsSubscription) to perform automatic
     * groups assignment and returns newsletter subscription status. If some action
     * fails - exception is thrown.
     *
     * @param string $sUser       user login name
     * @param string $sPassword   user password
     * @param string $sPassword2  user confirmation password
     * @param array  $aInvAddress user billing address
     * @param array  $aDelAddress delivery address
     *
     * @throws oxUserException, oxInputException, oxConnectionException
     *
     * @return bool
     */
    public function changeUserData( $sUser, $sPassword, $sPassword2, $aInvAddress, $aDelAddress )
    {

        // validating values before saving. If validation fails - exception is thrown
        $this->checkValues( $sUser, $sPassword, $sPassword2, $aInvAddress, $aDelAddress );

        // input data is fine - lets save updated user info
        $this->assign( $aInvAddress );


        // update old or add new delivery address
        $this->_assignAddress( $aDelAddress );

        // saving new values
        if ( $this->save() ) {

            // assigning automatically to specific groups
            $sCountryId = isset( $aInvAddress['oxuser__oxcountryid'] )?$aInvAddress['oxuser__oxcountryid']:'';
            $this->_setAutoGroups( $sCountryId );
        }
    }

    /**
     * Adds new address info to user copied from passed user. Returns new
     * address ID
     *
     * @param object $oUser user object to copy address info
     *
     * @return mixed
     */
    public function addUserAddress( $oUser )
    {
        if ( $this->_hasUserAddress( $oUser->getId() ) ) {
            return false;
        }

        $oAddress = oxNew( 'oxaddress' );
        $oAddress->oxaddress__oxuserid        = new oxField($this->getId(), oxField::T_RAW);
        $oAddress->oxaddress__oxaddressuserid = new oxField($oUser->getId(), oxField::T_RAW);
        $oAddress->oxaddress__oxfname         = new oxField($oUser->oxuser__oxfname->value, oxField::T_RAW);
        $oAddress->oxaddress__oxlname         = new oxField($oUser->oxuser__oxlname->value, oxField::T_RAW);
        $oAddress->oxaddress__oxstreet        = new oxField($oUser->oxuser__oxstreet->value, oxField::T_RAW);
        $oAddress->oxaddress__oxstreetnr      = new oxField($oUser->oxuser__oxstreetnr->value, oxField::T_RAW);
        $oAddress->oxaddress__oxcity          = new oxField($oUser->oxuser__oxcity->value, oxField::T_RAW);
        $oAddress->oxaddress__oxzip           = new oxField($oUser->oxuser__oxzip->value, oxField::T_RAW);
        $oAddress->oxaddress__oxcountry       = new oxField($oUser->oxuser__oxcountry->value, oxField::T_RAW);
        $oAddress->oxaddress__oxcountryid     = new oxField($oUser->oxuser__oxcountryid->value, oxField::T_RAW);
        $oAddress->oxaddress__oxcompany       = new oxField($oUser->oxuser__oxcompany->value, oxField::T_RAW);

        // adding new address
        if ( $oAddress->save() ) {
            // resetting addresses
            $this->_aAddresses = null;
            return $oAddress->getId();
        }
    }

    /**
     * creates new address entry or updates existing
     *
     * @param array $aDelAddress address data array
     *
     * @return null
     */
    protected function _assignAddress( $aDelAddress )
    {
        if (isset($aDelAddress) && count($aDelAddress)) {
            $sAddressId = oxConfig::getParameter( 'oxaddressid' );
            $sMyAddressId = ( $sAddressId === null || $sAddressId == -1 || $sAddressId == -2 ) ?  null : $sAddressId;
            $aDelAddress['oxaddress__oxid'] = $sMyAddressId;
            $oAddress = oxNew( 'oxaddress' );
            $oAddress->assign( $aDelAddress );
            $oAddress->oxaddress__oxuserid  = new oxField( $this->getId(), oxField::T_RAW );
            $oAddress->oxaddress__oxcountry = $this->getUserCountry( $oAddress->oxaddress__oxcountryid->value );
            $oAddress->save();

            // resetting addresses
            $this->_aAddresses = null;

            // saving delivery Address for later use
            oxSession::setVar( 'deladrid', $oAddress->getId() );
        } else {
            // resetting
            oxSession::setVar( 'deladrid', null );
        }
    }

    /**
     * Performs user login by username and password. Fetches user data from DB.
     * Registers in session. Returns true on success, FALSE otherwise.
     *
     * @param string $sUser     User username
     * @param string $sPassword User password
     * @param bool   $blCookie  (default false)
     *
     * @throws oxConnectionException, oxCookieException, oxUserException
     *
     * @return bool
     */
    public function login( $sUser, $sPassword, $blCookie = false)
    {
        if ( $this->isAdmin() && !count( oxUtilsServer::getInstance()->getOxCookie() ) ) {
            $oEx = oxNew( 'oxCookieException' );
            $oEx->setMessage( 'EXCEPTION_COOKIE_NOCOOKIE' );
            throw $oEx;
        }

        $myConfig = $this->getConfig();
        if ( $sPassword ) {

            $sShopID = $myConfig->getShopId();
            $oDb = oxDb::getDb();

            $sUserSelect = is_numeric( $sUser ) ? "oxuser.oxcustnr = {$sUser} " : "oxuser.oxusername = " . $oDb->quote( $sUser );
            $sPassSelect = " oxuser.oxpassword = MD5( CONCAT( ".$oDb->quote( $sPassword ).", UNHEX( oxuser.oxpasssalt ) ) ) ";
            $sShopSelect = "";


            // admin view: can only login with higher than 'user' rights
            if ( $this->isAdmin() ) {
                $sShopSelect = " and ( oxrights != 'user' ) ";
            }

            $sWhat = "oxid";

            $sSelect =  "select $sWhat from oxuser where oxuser.oxactive = 1 and {$sPassSelect} and {$sUserSelect} {$sShopSelect} ";
            if ( $myConfig->isDemoShop() && $this->isAdmin() ) {
                if ( $sPassword == "admin" && $sUser == "admin" ) {
                    $sSelect = "select $sWhat from oxuser where oxrights = 'malladmin' {$sShopSelect} ";
                } else {
                    $oEx = oxNew( 'oxUserException' );
                    $oEx->setMessage( 'EXCEPTION_USER_NOVALIDLOGIN' );
                    throw $oEx;
                }
            }

            // load from DB
            $aData = $oDb->getAll( $sSelect );
            $sOXID = @$aData[0][0];
            if ( isset( $sOXID ) && $sOXID && !@$aData[0][1] ) {

                if ( !$this->load( $sOXID ) ) {
                    $oEx = oxNew( 'oxUserException' );
                    $oEx->setMessage( 'EXCEPTION_USER_NOVALIDLOGIN' );
                    throw $oEx;
                }
            }
        }


        //login successfull?
        if ( $this->oxuser__oxid->value ) {
            // yes, successful login
            if ( $this->isAdmin() ) {
                oxSession::setVar( 'auth', $this->oxuser__oxid->value );
            } else {
                oxSession::setVar( 'usr', $this->oxuser__oxid->value );
            }

            // cookie must be set ?
            if ( $blCookie ) {
                oxUtilsServer::getInstance()->setUserCookie( $this->oxuser__oxusername->value, $this->oxuser__oxpassword->value, $myConfig->getShopId() );
            }
            return true;
        } else {
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage( 'EXCEPTION_USER_NOVALIDLOGIN' );
            throw $oEx;
        }
    }

    /**
     * Performs user login by username and password. Fetches user data from DB.
     * Registers in session. Returns true on success, FALSE otherwise.
     *
     * @param string $sUser User username
     *
     * @throws oxConnectionException, oxUserException
     *
     * @return bool
     */
    public function openIdLogin( $sUser )
    {
        $myConfig = $this->getConfig();
        $sShopID = $myConfig->getShopId();
        $oDb = oxDb::getDb();

        $sUserSelect = "oxuser.oxusername = " . $oDb->quote( $sUser );
        $sShopSelect = "";


        $sSelect =  "select oxid from oxuser where oxuser.oxactive = 1 and {$sUserSelect} {$sShopSelect} ";

        // load from DB
        $aData = $oDb->getAll( $sSelect );
        $sOXID = @$aData[0][0];
        if ( isset( $sOXID ) && $sOXID ) {

            if ( !$this->load( $sOXID ) ) {
                $oEx = oxNew( 'oxUserException' );
                $oEx->setMessage( 'EXCEPTION_USER_NOVALIDLOGIN' );
                throw $oEx;
            }
        }

        //login successfull?
        if ( $this->oxuser__oxid->value ) {
            // yes, successful login
            oxSession::setVar( 'usr', $this->oxuser__oxid->value );
            return true;
        } else {
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage( 'EXCEPTION_USER_NOVALIDLOGIN' );
            throw $oEx;
        }
    }

    /**
     * Logs out session user. Returns true on success
     *
     * @return bool
     */
    public function logout()
    {
        // deleting session info
        oxSession::deleteVar( 'usr' );  // for front end
        oxSession::deleteVar( 'auth' ); // for back end
        oxSession::deleteVar( 'dgr' );
        oxSession::deleteVar( 'dynvalue' );
        oxSession::deleteVar( 'paymentid' );
        // oxSession::deleteVar( 'deladrid' );

        // delete cookie
        oxUtilsServer::getInstance()->deleteUserCookie( $this->getConfig()->getShopID() );

        // unsetting global user
        $this->setUser( null );

        return true;
    }

    /**
     * Loads active admin user object (if possible). If
     * user is not available - returns false.
     *
     * @return bool
     */
    public function loadAdminUser()
    {
        return $this->loadActiveUser( true );
    }

    /**
     * Loads active user object. If
     * user is not available - returns false.
     *
     * @param bool $blForceAdmin (default false)
     *
     * @return bool
     */
    public function loadActiveUser( $blForceAdmin = false )
    {
        $myConfig = $this->getConfig();

        $blAdmin = $myConfig->isAdmin() || $blForceAdmin;
        $oDB = oxDb::getDb();

        // first - checking session info
        $sUserID = $blAdmin ? oxSession::getVar( 'auth' ) : oxSession::getVar( 'usr' );
        $blFoundInCookie = false;

        //trying automatic login (by 'remember me' cookie)
        if ( !$sUserID && !$blAdmin ) {
            $sShopID = $myConfig->getShopId();
            if ( ( $sSet = oxUtilsServer::getInstance()->getUserCookie( $sShopID ) ) ) {
                $aData = explode( '@@@', $sSet );
                $sUser = $aData[0];
                $sPWD  = @$aData[1];

                $sSelect =  'select oxid, oxpassword from oxuser where oxuser.oxpassword != "" and  oxuser.oxactive = 1 and oxuser.oxusername = '.$oDB->quote($sUser);


                $oDB = oxDb::getDb();
                $rs = $oDB->execute( $sSelect );
                if ( $rs != false && $rs->recordCount() > 0 ) {
                    while (!$rs->EOF) {
                        $sTest = crypt( $rs->fields[1], 'ox' );
                        if ( $sTest == $sPWD ) {
                            // found
                            $sUserID = $rs->fields[0];
                            $blFoundInCookie = true;
                            break;
                        }
                        $rs->moveNext();
                    }
                }
            }
        }

        // Checking if user is connected via Facebook connect.
        // If yes, trying to login user using user Facebook ID
        if ( $myConfig->getConfigParam( "bl_showFbConnect") && !$sUserID && !$blAdmin ) {
            $oFb = oxFb::getInstance();
            if ( $oFb->isConnected() && $oFb->getUser() ) {
                $sUserSelect = "oxuser.oxfbid = " . $oDB->quote( $oFb->getUser() );
                $sShopSelect = "";


                $sSelect =  "select oxid from oxuser where oxuser.oxactive = 1 and {$sUserSelect} {$sShopSelect} ";
                $sUserID = $oDB->getOne( $sSelect );
            }
        }

        // checking user results
        if ( $sUserID ) {
            if ( $this->load( $sUserID ) ) {
                // storing into session
                if ($blAdmin) {
                    oxSession::setVar( 'auth', $sUserID );
                } else {
                    oxSession::setVar( 'usr', $sUserID );
                }

                // marking the way user was loaded
                $this->_blLoadedFromCookie = $blFoundInCookie;
                return true;
            }
        } else {
            // no user
            oxSession::deleteVar( 'usr' );
            oxSession::deleteVar( 'auth' );

            return false;
        }
    }

    /**
     * Login for Ldap
     *
     * @param string $sUser       User username
     * @param string $sPassword   User password
     * @param string $sShopID     Shop id
     * @param string $sShopSelect Shop select
     *
     * @throws $oEx if user is wrong
     *
     * @return null
     */
    protected function _ldapLogin( $sUser, $sPassword, $sShopID, $sShopSelect)
    {
        include "oxldap.php";
        $myConfig = $this->getConfig();
        $oDb = oxDb::getDb();
        //$throws oxConnectionException
        $aLDAPParams = $myConfig->getConfigParam( 'aLDAPParams' );
        $oLDAP = new oxLDAP( $aLDAPParams['HOST'], $aLDAPParams['PORT'] );
        // maybe this is LDAP user but supplied email Address instead of LDAP login
        $sLDAPKey = $oDb->getOne("select oxldapkey from oxuser where oxuser.oxactive = 1 and oxuser.oxusername = ".$oDb->quote($sUser)." $sShopSelect");
        if ( isset( $sLDAPKey) && $sLDAPKey) {
            $sUser = $sLDAPKey;
        }

        //$throws oxConnectionException
        $oLDAP->login( $sUser, $sPassword, $aLDAPParams['USERQUERY'], $aLDAPParams['BASEDN'], $aLDAPParams['FILTER']);

        $aData = $oLDAP->mapData($aLDAPParams['DATAMAP']);
        if ( isset( $aData['OXUSERNAME']) && $aData['OXUSERNAME']) {
            // login successful

            // check if user is already in database
            $sSelect =  "select oxid from oxuser where oxuser.oxusername = ".$oDb->quote($aData['OXUSERNAME'])." $sShopSelect";
            $sOXID = $oDb->getOne( $sSelect);

            if ( !isset( $sOXID) || !$sOXID) {
                // we need to create a new user
                //$oUser->oxuser__oxid->setValue($oUser->setId());
                $this->setId();

                // map all user data fields
                foreach ( $aData as $fldname => $value) {
                    $sField = "oxuser__".strtolower( $fldname);
                    $this->$sField->setValue($aData[$fldname]);
                }

                $this->oxuser__oxactive->setValue(1);
                $this->oxuser__oxshopid->setValue($sShopID);
                $this->oxuser__oxldapkey->setValue($sUser);
                $this->oxuser__oxrights->setValue("user");
                $this->setPassword( "ldap user" );

                $this->save();
            } else {
                // LDAP user is already in OXID DB, load it
                $this->load( $sOXID);
            }

        } else {
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage('EXCEPTION_USER_NOVALUES');
            throw $oEx;
        }
    }

    /**
     * Returns user rights index. Index cannot be higher than current session
     * user rights index.
     *
     * @return string
     */
    protected function _getUserRights()
    {
        // previously user had no rights defined
        if ( !$this->oxuser__oxrights->value )
            return 'user';

        $oDB = oxDb::getDb();
        $myConfig    = $this->getConfig();
        $sAuthRights = null;

        // choosing possible user rights index
        $sAuthUserID = $this->isAdmin()?oxSession::getVar( 'auth' ):null;
        $sAuthUserID = $sAuthUserID?$sAuthUserID:oxSession::getVar( 'usr' );
        if ( $sAuthUserID ) {
            $sAuthRights = $oDB->getOne( 'select oxrights from '.$this->getViewName().' where oxid='.$oDB->quote( $sAuthUserID ) );
        }

        //preventing user rights edit for non admin
        $aRights = array();

        // selecting current users rights ...
        if ( $sCurrRights = $oDB->getOne( 'select oxrights from '.$this->getViewName().' where oxid="'.$this->getId().'"' ) ) {
            $aRights[] = $sCurrRights;
        }
        $aRights[] = 'user';

        if ( !$sAuthRights || !( $sAuthRights == 'malladmin' || $sAuthRights == $myConfig->getShopId() ) ) {
            return current( $aRights );
        } elseif ( $sAuthRights == $myConfig->getShopId() ) {
            $aRights[] = $sAuthRights;
            if ( !in_array( $this->oxuser__oxrights->value, $aRights ) ) {
                return current( $aRights );
            }
        }

        // leaving as it was set ...
        return $this->oxuser__oxrights->value;
    }

    /**
     * Tries to fetch and set next record number in DB. Returns true on success
     *
     * @param string $sMaxField  field name where record number is stored
     * @param array  $aWhere     (optional) shop filter add SQL string
     * @param int    $iMaxTryCnt (optional) max number of tryouts
     *
     * @return bool
     */
    protected function _setRecordNumber( $sMaxField, $aWhere = null ,$iMaxTryCnt = 5 )
    {

        /*if ( !$myConfig->blMallUsers ) {
            $sShopID = $myConfig->getShopId();
            $aWhere = array(" {$this->getViewName()}.oxshopid = '$sShopID' ");
        }*/

        return parent::_setRecordNumber( $sMaxField, $aWhere, $iMaxTryCnt );
    }

    /**
     * Inserts user object data to DB. Returns true on success.
     *
     * @return bool
     */
    protected function _insert()
    {

        // set oxcreate date
        $this->oxuser__oxcreate = new oxField(date( 'Y-m-d H:i:s' ), oxField::T_RAW);

        if ( !isset( $this->oxuser__oxboni->value ) ) {
            $this->oxuser__oxboni = new oxField($this->getBoni(), oxField::T_RAW);
        }

        return parent::_insert();
    }

    /**
     * Updates changed user object data to DB. Returns true on success.
     *
     * @return bool
     */
    protected function _update()
    {
        //V #M418: for not registered users, don't change boni during update
        if (!$this->oxuser__oxpassword->value && $this->oxuser__oxregister->value < 1) {
            $this->_aSkipSaveFields[] = 'oxboni';
        }

        // don't change this field
        $this->_aSkipSaveFields[] = 'oxcreate';
        if ( !$this->isAdmin() ) {
            $this->_aSkipSaveFields[] = 'oxcustnr';
            $this->_aSkipSaveFields[] = 'oxrights';
        }

        // updating subscription information
        if ( ( $blUpdate = parent::_update() ) ) {
            $this->getNewsSubscription()->updateSubscription( $this );
        }

        return $blUpdate;
    }

    /**
     * Checks if user name does not break logics:
     *  - if user wants to UPDATE his login name, performing check if
     *    user entered correct password
     *  - additionally checking for user name dublicates. This is usually
     *    needed when creating new users.
     * On any error exception is thrown.
     *
     * @param string $sLogin      user preferred login name
     * @param array  $aInvAddress user information
     *
     * @throws oxUserException, oxInputException
     *
     * @return string login name
     */
    protected function _checkLogin( $sLogin, $aInvAddress )
    {
        $myConfig = $this->getConfig();

        $sLogin   = ( isset( $aInvAddress['oxuser__oxusername'] ) )?$aInvAddress['oxuser__oxusername']:$sLogin;

        // check only for users with password during registration
        // if user wants to change user name - we must check if passwords are ok before changing
        if ( $this->oxuser__oxpassword->value && $sLogin != $this->oxuser__oxusername->value ) {

            // on this case password must be taken directly from request
            $sNewPass = (isset( $aInvAddress['oxuser__oxpassword']) && $aInvAddress['oxuser__oxpassword'] )?$aInvAddress['oxuser__oxpassword']:oxConfig::getParameter( 'user_password' );
            if ( !$sNewPass ) {

                // 1. user forgot to enter password
                $oEx = oxNew( 'oxInputException' );
                $oEx->setMessage('EXCEPTION_INPUT_NOTALLFIELDS');
                throw $oEx;
            } else {

                // 2. entered wrong password
                if ( !$this->isSamePassword( $sNewPass ) ) {
                    $oEx = oxNew( 'oxUserException' );
                    $oEx->setMessage('EXCEPTION_USER_PWDDONTMATCH');
                    throw $oEx;
                }
            }
        }

        if ( $this->checkIfEmailExists( $sLogin ) ) {
            //if exists then we do now allow to do that
            $oEx = oxNew( 'oxUserException' );
            $oLang = oxLang::getInstance();
            $oEx->setMessage( sprintf( $oLang->translateString( 'EXCEPTION_USER_USEREXISTS', $oLang->getTplLanguage() ), $sLogin ) );
            throw $oEx;
        }

        return $sLogin;
    }

    /**
     * Checks for already used email
     *
     * @param string $sEmail user email/login
     *
     * @return null
     */
    public function checkIfEmailExists( $sEmail )
    {
        $myConfig = $this->getConfig();
        $oDB = oxDb::getDb();
        $iShopId = $myConfig->getShopId();
        $blExists = false;

        $sQ = 'select oxshopid, oxrights, oxpassword from oxuser where oxusername = '. $oDB->quote( $sEmail );
        if ( ( $sOxid = $this->getId() ) ) {
            $sQ .= " and oxid <> '$sOxid' ";
        }

        $oRs = $oDB->execute( $sQ );
        if ( $oRs != false && $oRs->recordCount() > 0 ) {

            if ( $this->_blMallUsers ) {

                $blExists = true;
                if ( $oRs->fields[1] == 'user' && !$oRs->fields[2] ) {

                    // password is not set - allow to override
                    $blExists = false;
                }
            } else {

                $blExists = false;
                while ( !$oRs->EOF ) {
                    if ( $oRs->fields[1] != 'user' ) {

                        // exists admin with same login - must not allow
                        $blExists = true;
                        break;
                    } elseif ( $oRs->fields[0] == $iShopId && $oRs->fields[2] ) {

                        // exists same login (with password) in same shop
                        $blExists = true;
                        break;
                    }

                    $oRs->moveNext();
                }
            }
        }
        return $blExists;
    }

    /**
     * Returns user recommendation list object
     *
     * @param string $sOXID object ID (default is null)
     *
     * @return object oxlist with oxrecommlist objects
     */
    public function getUserRecommLists( $sOXID = null )
    {
        if ( !$sOXID )
            $sOXID = $this->getId();

        // sets active page
        $iActPage = (int) oxConfig::getParameter( 'pgNr' );
        $iActPage = ($iActPage < 0) ? 0 : $iActPage;

        // load only lists which we show on screen
        $iNrofCatArticles = $this->getConfig()->getConfigParam( 'iNrofCatArticles' );
        $iNrofCatArticles = $iNrofCatArticles ? $iNrofCatArticles : 10;


        $oRecommList = oxNew( 'oxlist' );
        $oRecommList->init( 'oxrecommlist' );
        $oRecommList->setSqlLimit( $iNrofCatArticles * $iActPage, $iNrofCatArticles );
        $iShopId = $this->getConfig()->getShopId();
        $sSelect = 'select * from oxrecommlists where oxuserid ='. oxDb::getDb()->quote( $sOXID ) . ' and oxshopid ="'. $iShopId .'"';
        $oRecommList->selectString( $sSelect );

        return $oRecommList;
    }

    /**
     * Returns recommlist count
     *
     * @param string $sOx object ID (default is null)
     *
     * @return int
     */
    public function getRecommListsCount( $sOx = null )
    {
        if ( !$sOx ) {
            $sOx = $this->getId();
        }

        if ( $this->_iCntRecommLists === null || $sOx ) {
            $oDb = oxDb::getDb();
            $this->_iCntRecommLists = 0;
            $iShopId = $this->getConfig()->getShopId();
            $sSelect = 'select count(oxid) from oxrecommlists where oxuserid = ' . $oDb->quote( $sOx ) . ' and oxshopid ="'. $iShopId .'"';
            $this->_iCntRecommLists = $oDb->getOne( $sSelect );
        }
        return $this->_iCntRecommLists;
    }

    /**
     * Checks if email (used as login) is not empty and is
     * valid. On any error exception is thrown.
     *
     * @param string $sEmail user email/login
     *
     * @return null
     */
    protected function _checkEmail( $sEmail )
    {
        // missing email address (user login name) ?
        if ( !$sEmail ) {
            $oEx = oxNew( 'oxInputException' );
            $oEx->setMessage('EXCEPTION_INPUT_NOTALLFIELDS');
            throw $oEx;
        }

        // invalid email address ?
        if ( !oxUtils::getInstance()->isValidEmail( $sEmail ) ) {
            $oEx = oxNew( 'oxInputException' );
            $oEx->setMessage( 'EXCEPTION_INPUT_NOVALIDEMAIL' );
            throw $oEx;
        }
    }

    /**
     * Checking if user password is fine. In case of error
     * exception is thrown
     *
     * @param string $sNewPass      new user password
     * @param string $sConfPass     retyped user password
     * @param bool   $blCheckLenght option to check password lenght
     *
     * @throws oxUserException, oxInputException
     *
     * @deprecated use public oxuser::checkPassword() instead
     *
     * @return null
     */
    protected function _checkPassword( $sNewPass, $sConfPass, $blCheckLenght = false )
    {
        $this->checkPassword( $sNewPass, $sConfPass, $blCheckLenght );
    }

    /**
     * Checking if user password is fine. In case of error
     * exception is thrown
     *
     * @param string $sNewPass      new user password
     * @param string $sConfPass     retyped user password
     * @param bool   $blCheckLenght option to check password lenght
     *
     * @throws oxUserException, oxInputException
     *
     * @return null
     */
    public function checkPassword( $sNewPass, $sConfPass, $blCheckLenght = false )
    {
        //  no password at all
        if ( $blCheckLenght && getStr()->strlen( $sNewPass ) == 0 ) {
            $oEx = oxNew( 'oxInputException' );
            $oEx->setMessage('EXCEPTION_INPUT_EMPTYPASS');
            throw $oEx;
        }

        //  password is too short ?
        if ( $blCheckLenght &&  getStr()->strlen( $sNewPass ) < 6 ) {
            $oEx = oxNew( 'oxInputException' );
            $oEx->setMessage('EXCEPTION_INPUT_PASSTOOSHORT');
            throw $oEx;
        }

        //  passwords do not match ?
        if ( $sNewPass != $sConfPass ) {
            $oEx = oxNew( 'oxUserException' );
            $oEx->setMessage('EXCEPTION_USER_PWDDONTMATCH');
            throw $oEx;
        }
    }

    /**
     * Checks if user defined countries (billing and delivery) are active
     *
     * @param array $aInvAddress billing address info
     * @param array $aDelAddress delivery address info
     *
     * @throws oxInputException
     *
     * @return null
     */
    protected function _checkCountries( $aInvAddress, $aDelAddress )
    {
        $sBillCtry = isset( $aInvAddress['oxuser__oxcountryid'] ) ? $aInvAddress['oxuser__oxcountryid'] : null;
        $sDelCtry  = isset( $aDelAddress['oxaddress__oxcountryid'] ) ? $aDelAddress['oxaddress__oxcountryid'] : null;

        if ( $sBillCtry || $sDelCtry ) {
            $oDb = oxDb::getDb();

            if ( ( $sBillCtry == $sDelCtry ) || ( !$sBillCtry && $sDelCtry ) || ( $sBillCtry && !$sDelCtry ) ) {
                $sBillCtry = $sBillCtry ? $sBillCtry : $sDelCtry;
                $sQ = "select oxactive from oxcountry where oxid = ".$oDb->quote( $sBillCtry )." ";
            } else {
                $sQ = "select ( select oxactive from oxcountry where oxid = ".$oDb->quote( $sBillCtry )." ) and
                              ( select oxactive from oxcountry where oxid = ".$oDb->quote( $sDelCtry )." ) ";
            }

            if ( !$oDb->getOne( $sQ ) ) {
                $oEx = oxNew( 'oxUserException' );
                $oEx->setMessage('EXCEPTION_INPUT_NOTALLFIELDS' );
                throw $oEx;
            }
        }
    }

    /**
     * Checking if all required fields were filled. In case of error
     * exception is thrown
     *
     * @param array $aInvAddress billing address
     * @param array $aDelAddress delivery address
     *
     * @throws oxInputExcpetion exception
     *
     * @return null
     */
    protected function _checkRequiredFields( $aInvAddress, $aDelAddress )
    {
        // collecting info about required fields
        $aMustFields = array( 'oxuser__oxfname',
                              'oxuser__oxlname',
                              'oxuser__oxstreetnr',
                              'oxuser__oxstreet',
                              'oxuser__oxzip',
                              'oxuser__oxcity' );

        // config shoud override default fields
        $aMustFillFields = $this->getConfig()->getConfigParam( 'aMustFillFields' );
        if ( is_array( $aMustFillFields ) ) {
            $aMustFields = $aMustFillFields;
        }

        // assuring data to check
        $aInvAddress = is_array( $aInvAddress )?$aInvAddress:array();
        $aDelAddress = is_array( $aDelAddress )?$aDelAddress:array();

        // collecting fields
        $aFields = array_merge( $aInvAddress, $aDelAddress );


        // check delivery address ?
        $blCheckDel = false;
        if ( count( $aDelAddress ) ) {
            $blCheckDel = true;
        }

        // checking
        foreach ( $aMustFields as $sMustField ) {

            // A. not nice, but we keep all fields info in one config array, and must support baskwards compat.
            if ( !$blCheckDel && strpos( $sMustField, 'oxaddress__' ) === 0 ) {
                continue;
            }

            if ( isset( $aFields[$sMustField] ) && is_array( $aFields[$sMustField] ) ) {
                $this->_checkRequiredArrayFields( $sMustField, $aFields[$sMustField] );
            } elseif ( !isset( $aFields[$sMustField] ) || !trim( $aFields[$sMustField] ) ) {
                   $oEx = oxNew( 'oxInputException' );
                   $oEx->setMessage('EXCEPTION_INPUT_NOTALLFIELDS');
                   throw $oEx;
            }
        }
    }

    /**
     * Checks if all values are filled up
     *
     * @param strinf $sFieldName   checking field name
     * @param array  $aFieldValues field values
     *
     * @throws oxInputExcpetion exception
     *
     * @return null
     */
    protected function _checkRequiredArrayFields( $sFieldName, $aFieldValues )
    {
        foreach ( $aFieldValues as $sValue ) {
            if ( !trim( $sValue ) ) {
                $oEx = oxNew( 'oxInputException' );
                $oEx->setMessage('EXCEPTION_INPUT_NOTALLFIELDS');
                throw $oEx;
            }
        }
    }

    /**
     * Checks if user passed VAT id is valid. Exception is thrown
     * if id is not valid
     *
     * @param array $aInvAddress user input array
     *
     * @throws oxInputException, oxConnectionException
     *
     * @return null
     */
    protected function _checkVatId( $aInvAddress )
    {
        if ( $aInvAddress['oxuser__oxustid'] ) {

            if (!($sCountryId = $aInvAddress['oxuser__oxcountryid'])) {
                // no country
                return;
            }
            $oCountry = oxNew('oxcountry');
            if (!$oCountry->load($sCountryId)) {
                throw new oxObjectException();
            }
            if ($oCountry->isForeignCountry() && $oCountry->isInEU()) {
                    if (strncmp($aInvAddress['oxuser__oxustid'], $oCountry->oxcountry__oxisoalpha2->value, 2)) {
                        $oEx = oxNew( 'oxInputException' );
                        $oEx->setMessage( 'VAT_MESSAGE_ID_NOT_VALID' );
                        throw $oEx;
                    }
            }
        }
    }

    /**
     * Automatically assigns user to specific groups
     * according to users country information
     *
     * @param string $sCountryId users country id
     *
     * @return null
     */
    protected function _setAutoGroups( $sCountryId )
    {
        // assigning automatically to specific groups
        $blForeigner = true;
        $blForeignGroupExists = false;
        $blInlandGroupExists = false;

        $aHomeCountry = $this->getConfig()->getConfigParam( 'aHomeCountry' );
        // foreigner ?
        if ( is_array($aHomeCountry)) {
            if (in_array($sCountryId, $aHomeCountry)) {
                $blForeigner = false;
            }
        } elseif ($sCountryId == $aHomeCountry) {
            $blForeigner = false;
        }

        if ( $this->inGroup( 'oxidforeigncustomer' ) ) {
            $blForeignGroupExists = true;
            if ( !$blForeigner ) {
                $this->removeFromGroup( 'oxidforeigncustomer' );
            }
        }

        if ( $this->inGroup( 'oxidnewcustomer' ) ) {
            $blInlandGroupExists = true;
            if ( $blForeigner ) {
                $this->removeFromGroup( 'oxidnewcustomer' );
            }
        }

        if ( !$this->oxuser__oxdisableautogrp->value ) {
            if ( !$blForeignGroupExists && $blForeigner ) {
                $this->addToGroup( 'oxidforeigncustomer' );
            }
            if ( !$blInlandGroupExists && !$blForeigner ) {
                $this->addToGroup( 'oxidnewcustomer' );
            }
        }
    }

    /**
     * Checks if user allready has user address
     *
     * @param object $sUserId user to check Id
     *
     * @return bool
     */
    protected function _hasUserAddress( $sUserId )
    {
        $oAddresses = $this->getUserAddresses();
        if ( $oAddresses && count($oAddresses)>0 ) {
            $oAddresses->rewind() ;
            foreach ($oAddresses as $key => $oAddress) {
                if ( $oAddress->oxaddress__oxaddressuserid->value == $sUserId ) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Sets user info into cookie
     *
     * @param string  $sUser     user ID
     * @param string  $sPassword password
     * @param string  $sShopId   shop ID (default null)
     * @param integer $iTimeout  timeout value (default 31536000)
     *
     * @deprecated should be used oxUtilsServer::setUserCookie()
     *
     * @return null
     */
    protected function _setUserCookie( $sUser, $sPassword,  $sShopId = null, $iTimeout = 31536000 )
    {
        oxUtilsServer::getInstance()->setUserCookie( $sUser, $sPassword, $sShopId, $iTimeout );
    }

    /**
     * Deletes user cookie data
     *
     * @param string $sShopId shop ID (default null)
     *
     * @deprecated should be used oxUtilsServer::deleteUserCookie()
     *
     * @return null
     */
    protected function _deleteUserCookie( $sShopId = null )
    {
        oxUtilsServer::getInstance()->deleteUserCookie( $sShopId );
    }

    /**
     * Returns cookie stored used login data
     *
     * @param string $sShopId shop ID (default null)
     *
     * @deprecated should be used oxUtilsServer::getUserCookie()
     *
     * @return string
     */
    protected static function _getUserCookie( $sShopId = null )
    {
        return oxUtilsServer::getInstance()->getUserCookie( $sShopId );
    }


    /**
     * Tries to load user object by passed update id. Update id is
     * generated when user forgot passwords and wants to update it
     *
     * @param string $sUid update id
     *
     * @return oxuser
     */
    public function loadUserByUpdateId( $sUid )
    {
        $oDb = oxDb::getDb();
        $sQ = "select oxid from ".$this->getViewName()." where oxupdateexp >= ".time()." and MD5( CONCAT( oxid, oxshopid, oxupdatekey ) ) = ".$oDb->quote( $sUid );
        if ( $sUserId = $oDb->getOne( $sQ ) ) {
            return $this->load( $sUserId );
        }
    }

    /**
     * Generates or resets and saves users update key
     *
     * @param bool $blReset marker to reset update info
     *
     * @return null
     */
    public function setUpdateKey( $blReset = false )
    {
        $sUpKey  = $blReset ? '' : oxUtilsObject::getInstance()->generateUId();
        $iUpTime = $blReset ? 0 : oxUtilsDate::getInstance()->getTime() + $this->getUpdateLinkTerm();

        // generating key
        $this->oxuser__oxupdatekey = new oxField( $sUpKey, oxField::T_RAW );

        // setting expiration time for 6 hours
        $this->oxuser__oxupdateexp = new oxField( $iUpTime, oxField::T_RAW );

        // saving
        $this->save();
    }

    /**
     * Return password update link validity term (seconds). Default 3600 * 6
     *
     * @return int
     */
    public function getUpdateLinkTerm()
    {
        return 3600 * 6;
    }

    /**
     * Checks if password update key is not expired yet
     *
     * @param string $sKey key
     *
     * @return bool
     */
    public function isExpiredUpdateId( $sKey )
    {
        $oDb = oxDb::getDb();
        $sQ = "select 1 from ".$this->getViewName()." where oxupdateexp >= ".time()." and MD5( CONCAT( oxid, oxshopid, oxupdatekey ) ) = ".$oDb->quote( $sKey );
        return !( (bool) $oDb->getOne( $sQ ) );
    }

    /**
     * Returns user passwords update id
     *
     * @return string
     */
    public function getUpdateId()
    {
        if ( $this->_sUpdateKey === null ) {
            $this->setUpdateKey();
            $this->_sUpdateKey = md5( $this->getId() . $this->oxuser__oxshopid->value . $this->oxuser__oxupdatekey->value );
        }
        return $this->_sUpdateKey;
    }

    /**
     * Encodes and returns given password
     *
     * @param string $sPassword password to encode
     * @param string $sSalt     any unique string value
     *
     * @return string
     */
    public function encodePassword( $sPassword, $sSalt )
    {
        $oDb = oxDb::getDb();
        return $oDb->getOne( "select MD5( CONCAT( ".$oDb->quote( $sPassword ).", UNHEX( '{$sSalt}' ) ) )" );
    }

    /**
     * Returns safe salt value (heximal representation)
     *
     * @param string $sSalt any unique string value
     *
     * @return string
     */
    public function prepareSalt( $sSalt )
    {
        return ( $sSalt ? oxDb::getDb()->getOne( "select HEX( '{$sSalt}' )" ) : '' );
    }

    /**
     * Returns plains password salt representation
     *
     * @param string $sSaltHex heximal representation of password salt value
     *
     * @return string
     */
    public function decodeSalt( $sSaltHex )
    {
        return ( $sSaltHex ? oxDb::getDb()->getOne( "select UNHEX( '{$sSaltHex}' )" ) : '' );
    }

    /**
     * Sets new password for user ( save is not called)
     *
     * @param string $sPassword password
     *
     * @return null
     */
    public function setPassword( $sPassword = null )
    {
        // setting salt if password is not empty
        $sSalt = $sPassword ? $this->prepareSalt( oxUtilsObject::getInstance()->generateUID() ) : '';

        // encoding only if password was not empty (e.g. user registration without pass)
        $sPassword = $sPassword ? $this->encodePassword( $sPassword, $sSalt ) : '';

        $this->oxuser__oxpassword = new oxField( $sPassword, oxField::T_RAW );
        $this->oxuser__oxpasssalt = new oxField( $sSalt, oxField::T_RAW );
    }

     /**
      * Checks if user entered password is the same as old
      *
      * @param string $sNewPass new password
      *
      * @return bool
      */
    public function isSamePassword( $sNewPass )
    {
        return $this->encodePassword( $sNewPass, $this->oxuser__oxpasssalt->value ) == $this->oxuser__oxpassword->value;
    }

     /**
      * Returns if user was loaded from cookie
      *
      * @return bool
      */
    public function isLoadedFromCookie()
    {
        return $this->_blLoadedFromCookie;
    }

    /**
     * Returns password hash. In case password in db is plain or decodable
     * password is processed and hash returned
     *
     * @return string
     */
    public function getPasswordHash()
    {
        $sHash = null;
        if ( $this->oxuser__oxpassword->value ) {
            if ( strpos( $this->oxuser__oxpassword->value, 'ox_' ) === 0 ) {
                // decodable pass ?
                $this->setPassword( oxUtils::getInstance()->strRem( $this->oxuser__oxpassword->value ) );
            } elseif ( ( strlen( $this->oxuser__oxpassword->value ) < 32 ) && ( strpos( $this->oxuser__oxpassword->value, 'openid_' ) !== 0 ) ) {
                // plain pass ?
                $this->setPassword( $this->oxuser__oxpassword->value );
            }
            $sHash = $this->oxuser__oxpassword->value;
        }
        return $sHash;
    }

    /**
     * Loads active admin user object (if possible). If
     * user is not available - returns false.
     *
     * @deprecated use non static method loadAdminUser
     *
     * @return bool
     */
    public static function getAdminUser()
    {
        return self::getActiveUser( true );
    }

    /**
     * Loads active user object. If
     * user is not available - returns false.
     *
     * @param bool $blForceAdmin (default false)
     *
     * @deprecated use non static method loadActiveUser
     *
     * @return bool
     */
    public static function getActiveUser( $blForceAdmin = false )
    {
        $oUser = oxNew( 'oxuser' );
        if ( $oUser->loadActiveUser( $blForceAdmin ) ) {
            return $oUser;
        } else {
            return false;
        }
    }

    /**
     * Generates random password for new openid users
     *
     * @param integer $iLength random password length
     *
     * @return string
     */
    public function getOpenIdPassword( $iLength = 25 )
    {
        $sPassword= "openid_".substr( oxUtilsObject::getInstance()->generateUId(), 0, $iLength);
        return $sPassword;
    }

    /**
     * Generates user password and username hash for review
     *
     * @param string $sUserId userid
     *
     * @return string
     */
    public function getReviewUserHash( $sUserId )
    {
        $oDb = oxDb::getDb();
        $sReviewUserHash = $oDb->getOne('select md5(concat("oxid", oxpassword, oxusername )) from oxuser where oxid = ' . $oDb->quote( $sUserId ) .'');
        return $sReviewUserHash;
    }

    /**
     * Gets from review user hash user id
     *
     * @param string $sReviewUserHash review user hash
     *
     * @return string
     */
    public function getReviewUserId( $sReviewUserHash )
    {
        $oDb = oxDb::getDb();
        $sUserId = $oDb->getOne('select oxid from oxuser where md5(concat("oxid", oxpassword, oxusername )) = ' . $oDb->quote( $sReviewUserHash ) .'');
        return $sUserId;
    }

    /**
     * Returns string representation of user state
     *
     * @return string
     */
    public function getState()
    {
        return $this->oxuser__oxstateid->value;
    }

    /**
     * Checks if user accepted latest shopping terms and conditions version
     *
     * @return bool
     */
    public function isTermsAccepted()
    {
        $sShopId = $this->getConfig()->getShopId();
        $sUserId = $this->getId();
        return (bool) oxDb::getDb()->getOne( "select 1 from oxacceptedterms where oxuserid='{$sUserId}' and oxshopid='{$sShopId}'" );
    }

    /**
     * Writes terms acceptance info to db
     *
     * @return null
     */
    public function acceptTerms()
    {
        $sUserId  = $this->getId();
        $sShopId  = $this->getConfig()->getShopId();
        $sVersion = oxNew( "oxcontent" )->getTermsVersion();

        oxDb::getDb()->execute( "replace oxacceptedterms set oxuserid='{$sUserId}', oxshopid='{$sShopId}', oxtermversion='{$sVersion}'" );
    }

    /**
     * Assigns registration points for invited user and
     * its inviter (calls oxUser::setInvitationCreditPoints())
     *
     * @param string $sUserId inviter user id
     *
     * @return bool
     */
    public function setCreditPointsForRegistrant( $sUserId )
    {
        $blSet   = false;
        $iPoints = $this->getConfig()->getConfigParam( 'dPointsForRegistration' );
        if ( $iPoints ) {
            $this->oxuser__oxpoints = new oxField( $iPoints, oxField::T_RAW );
            if ( $blSet = $this->save() ) {
                $oDb = oxDb::getDb();

                // updating users statistics
                $oDb->execute( "UPDATE oxinvitations SET oxpending = '0', oxaccepted = '1' where oxuserid = ". $oDb->quote( $sUserId ) );

                $oInvUser = oxNew( "oxuser" );
                if ( $oInvUser->load( $sUserId ) ) {
                    $blSet = $oInvUser->setCreditPointsForInviter();
                }
            }

            oxSession::deleteVar( 'su' );
        }

        return $blSet;
    }

    /**
     * Assigns credit points to inviter
     *
     * @return bool
     */
    public function setCreditPointsForInviter()
    {
        $blSet   = false;
        $iPoints = $this->getConfig()->getConfigParam( 'dPointsForInvitation' );
        if ( $iPoints ) {
            $iNewPoints = $this->oxuser__oxpoints->value + $iPoints;
            $this->oxuser__oxpoints = new oxField( $iNewPoints, oxField::T_RAW );
            $blSet = $this->save();
        }

        return $blSet;
    }

    /**
     * Updates user Facebook ID
     *
     * @return null
     */
    public function updateFbId()
    {
        $oFb = oxFb::getInstance();
        $blRet = false;

        if ( $oFb->isConnected() && $oFb->getUser() ) {
             $this->oxuser__oxfbid = new oxField( $oFb->getUser() );
             $blRet = $this->save();
        }

        return $blRet;
    }
}