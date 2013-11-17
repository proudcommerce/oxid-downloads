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
 * $Id: user_list.php 17243 2009-03-16 15:16:57Z arvydas $
 */

/**
 * Admin user list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: User Administration -> Users.
 * @package admin
 */
class User_List extends oxAdminList
{
    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxuser';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSort = "oxuser.oxusername";

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'oxuserlist';

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'user_list.tpl';

    /**
     * Sets SQL query parameters (such as sorting),
     * executes parent method parent::Init().
     *
     * @return null
     */
    public function init()
    {
        parent::Init();

        // set mark for blacklists
        foreach ( $this->_oList as $name => $value ) {
            if ( $value->inGroup( "oxidblacklist") || $value->inGroup( "oxidblocked")) {
                $value->blacklist = "1";
                $this->_oList[$name] = $value;
            }
            $this->_oList[$name]->blPreventDelete = false;
            if (!$this->_allowAdminEdit($name)) {
                $this->_oList[$name]->blPreventDelete = true;
            }
        }
    }

    /**
     * Admin user is allowed to be deleted only by mall admin
     *
     * @return null
     *
     */
    public function deleteEntry()
    {
        $soxId = oxConfig::getParameter( "oxid");
        if (!$this->_allowAdminEdit($soxId))
            return;

        return parent::deleteEntry();
    }

    /**
     * Prepares SQL where query according SQL condition array and attaches it to SQL end.
     * For each search value if german umlauts exist, adds them
     * and replaced by spec. char to query
     *
     * @param array  $aWhere  SQL condition array
     * @param string $sqlFull SQL query string
     *
     * @return string
     */
    public function _prepareWhereQuery( $aWhere, $sqlFull )
    {
        $aNameWhere = null;
        if ( isset( $aWhere['oxuser.oxlname'] ) && ( $sName = $aWhere['oxuser.oxlname'] ) ) {
            // check if this is search string (contains % sign at begining and end of string)
            $blIsSearchValue = $this->_isSearchValue( $sName );
            $sName = $this->_processFilter( $sName );
            $aNameWhere['oxuser.oxfname'] = $aNameWhere['oxuser.oxlname'] = $sName;

            // unsetting..
            unset( $aWhere['oxuser.oxlname'] );
        }
        $sQ = parent::_prepareWhereQuery( $aWhere, $sqlFull );

        if ( $aNameWhere ) {

            $aVal = explode( ' ', $sName );
            $sQ .= ' and (';
            $sSqlBoolAction = '';
            $myUtilsString = oxUtilsString::getInstance();

            foreach ( $aNameWhere as $sFieldName => $sValue ) {

                //for each search field using AND anction
                foreach ( $aVal as $sVal ) {

                    $sQ .= " {$sSqlBoolAction} {$sFieldName} ";

                    //for search in same field for different values using AND
                    $sSqlBoolAction = ' or ';

                    $sQ .= $this->_buildFilter( $sVal, $blIsSearchValue );

                    // trying to search spec chars in search value
                    // if found, add cleaned search value to search sql
                    $sUml = $myUtilsString->prepareStrForSearch( $sVal );
                    if ( $sUml ) {
                        $sQ .= " or {$sFieldName} ";
                        $sQ .= $this->_buildFilter( $sUml, $blIsSearchValue );
                    }
                }
            }

            // end for AND action
            $sQ .= ' ) ';
        }


        return $sQ;
    }

}
