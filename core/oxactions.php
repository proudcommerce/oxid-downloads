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
 * @version   SVN: $Id: oxactions.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

/**
 * Article actions manager. Collects and keeps actions of chosen article.
 * @package core
 */
class oxActions extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = "oxactions";

    /**
     * Class constructor. Executes oxActions::init(), initiates parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( "oxactions" );
    }

    /**
     * Adds an article to this actions
     *
     * @param string $sOxId id of the article to be added
     *
     * @return null
     */
    public function addArticle( $sOxId )
    {
        $oNewGroup = oxNew( 'oxbase' );
        $oNewGroup->init( 'oxactions2article' );
        $oNewGroup->oxactions2article__oxshopid = new oxField($this->getShopId());
        $oNewGroup->oxactions2article__oxactionid = new oxField($this->getId());
        $oNewGroup->oxactions2article__oxartid = new oxField($sOxId);
        $oNewGroup->oxactions2article__oxsort = new oxField(((int) oxDb::getDb(true)->getOne("select max(oxsort) from oxactions2article where oxactionid = '".$this->getId()."' and oxshopid = '".$this->getShopId()."'") + 1 ));
        $oNewGroup->save();
    }

    /**
     * Removes an article from this actions
     *
     * @param string $sOxId id of the article to be removed
     *
     * @return null
     */
    public function removeArticle( $sOxId )
    {
        // remove actions from articles also
        $oDb = oxDb::getDb(true);
        $sDelete = "delete from oxactions2article where oxactionid = '".$this->getId()."' and oxartid = ".$oDb->quote($sOxId)." and oxshopid = '" . $this->getShopId() . "'";
        $oDb->execute( $sDelete );

        return ( bool ) $oDb->affected_Rows();
    }

    /**
     * Removes article action, returns true on success. For
     * performance - you can not load action object - just pass
     * action ID.
     *
     * @param string $sOxId Object ID
     *
     * @return bool
     */
    public function delete( $sOxId = null )
    {
        if ( !$sOxId ) {
            $sOxId = $this->getId();
        }
        if ( !$sOxId ) {
            return false;
        }


        // remove actionss from articles also
        $oDb = oxDb::getDb(true);
        $sDelete = "delete from oxactions2article where oxactionid = ".$oDb->quote($sOxId)." and oxshopid = '" . $this->getShopId() . "'";
        $oDb->execute( $sDelete );

        return parent::delete( $sOxId );
    }
}
