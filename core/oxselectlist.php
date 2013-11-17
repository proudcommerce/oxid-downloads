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
 * $Id: oxselectlist.php 22531 2009-09-22 12:30:48Z vilma $
 */

/**
 * @package core
 */
class oxSelectlist extends oxI18n
{
    /**
     * Select list fields array
     * @var array
     */
    protected $_aFieldList = null;

    /**
     * Current class name
     * @var string
     */
    protected $_sClassName = 'oxselectlist';

    /**
     * Class constructor, sets callback so that Shopowner is able to
     * add any information to the article.
     *
     * @param string $sObjectsInListName optional and having no effect
     *
     * @return null
     */
    public function __construct( $sObjectsInListName = 'oxselectlist')
    {
        parent::__construct();
        $this->init( 'oxselectlist' );
    }

    /**
     * Returns select list value list.
     *
     * @param double $dVat VAT value
     *
     * @return array
     */
    public function getFieldList( $dVat = null )
    {
        if ( $this->_aFieldList == null && $this->oxselectlist__oxvaldesc->value ) {
            $this->_aFieldList = oxUtils::getInstance()->assignValuesFromText( $this->oxselectlist__oxvaldesc->value, $dVat );
            foreach ( $this->_aFieldList as $sKey => $oField ) {
                $this->_aFieldList[$sKey]->name = strip_tags( $this->_aFieldList[$sKey]->name );
            }
        }
        return $this->_aFieldList;
    }

    /**
     * Removes selectlists from articles.
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

        // remove selectlists from articles also
        if ( $blRemove = parent::delete( $sOXID ) ) {
            $oDb = oxDb::getDb();
            $oDb->execute( "delete from oxobject2selectlist where oxselnid = " . $oDb->quote( $sOXID ) . " " );
        }

        return $blRemove;
    }
}
