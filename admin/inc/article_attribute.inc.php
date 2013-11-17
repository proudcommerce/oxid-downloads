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
 * @package inc
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: article_attribute.inc.php 22508 2009-09-22 09:57:39Z vilma $
 */

$aColumns = array( 'container1' => array(    // field , table,         visible, multilanguage, ident
                                        array( 'oxtitle', 'oxattribute', 1, 1, 0 ),
                                        array( 'oxid',    'oxattribute', 0, 0, 1 )
                                        ),
                     'container2' => array(
                                        array( 'oxtitle', 'oxattribute', 1, 1, 0 ),
                                        array( 'oxid',    'oxobject2attribute', 0, 0, 1 ),
                                        array( 'oxvalue', 'oxobject2attribute', 0, 1, 1 ),
                                        array( 'oxattrid', 'oxobject2attribute', 0, 0, 1 ),
                                        )
                    );
/**
 * Class controls article assignment to attributes
 */
class ajaxComponent extends ajaxListComponent
{
    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $oDb         = oxDb::getDb();
        $sArtId      = oxConfig::getParameter( 'oxid' );
        $sSynchArtId = oxConfig::getParameter( 'synchoxid' );

        $sAttrViewName = getViewName('oxattribute');
        if ( $sArtId ) {
            // all categories article is in
            $sQAdd  = " from oxobject2attribute left join $sAttrViewName on $sAttrViewName.oxid=oxobject2attribute.oxattrid ";
            $sQAdd .= " where oxobject2attribute.oxobjectid = " . $oDb->quote( $sArtId ) . " ";
        } else {
            $sQAdd  = " from $sAttrViewName  where $sAttrViewName.oxid not in ( select oxobject2attribute.oxattrid from oxobject2attribute left join $sAttrViewName on $sAttrViewName.oxid=oxobject2attribute.oxattrid ";
            $sQAdd .= " where oxobject2attribute.oxobjectid = " . $oDb->quote( $sSynchArtId ) . " ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes article attributes.
     *
     * @return null
     */
    public function removeattr()
    {
        $aChosenArt = $this->_getActionIds( 'oxobject2attribute.oxid' );
        $sOxid = oxConfig::getParameter( 'oxid' );
        if ( oxConfig::getParameter( 'all' ) ) {
            $sQ = $this->_addFilter( "delete oxobject2attribute.* ".$this->_getQuery() );
            oxDb::getDb()->Execute( $sQ );

        } elseif ( is_array( $aChosenArt ) ) {
            $sQ = "delete from oxobject2attribute where oxobject2attribute.oxid in (" . implode( ", ", oxDb::getInstance()->quoteArray( $aChosenArt ) ) . ") ";
            oxDb::getDb()->Execute( $sQ );
        }
    }

    /**
     * Adds attributes to article.
     *
     * @return null
     */
    public function addattr()
    {
        $aAddCat = $this->_getActionIds( 'oxattribute.oxid' );
        $soxId   = oxConfig::getParameter( 'synchoxid');

        if ( oxConfig::getParameter( 'all' ) ) {
            $sAttrViewName = getViewName('oxattribute');
            $aAddCat = $this->_getAll( $this->_addFilter( "select $sAttrViewName.oxid ".$this->_getQuery() ) );
        }

        if ( $soxId && $soxId != "-1" && is_array( $aAddCat ) ) {
            foreach ($aAddCat as $sAdd) {
                $oNew = oxNew( "oxbase" );
                $oNew->init( "oxobject2attribute" );
                $oNew->oxobject2attribute__oxobjectid = new oxField($soxId);
                $oNew->oxobject2attribute__oxattrid   = new oxField($sAdd);
                $oNew->save();
            }
        }
    }

    /**
     * Saves attribute value
     *
     * @return null
     */
    public function saveAttributeValue ()
    {
        $oDb = oxDb::getDb();

        $soxId = oxConfig::getParameter( "oxid");
        $this->sAttributeOXID = oxConfig::getParameter( "attr_oxid");
        $sAttributeValue      = oxConfig::getParameter( "attr_value");
        if (!$this->getConfig()->isUtf()) {
            $sAttributeValue = iconv( 'UTF-8', oxLang::getInstance()->translateString("charset"), $sAttributeValue );
        }

        $oArticle = oxNew( "oxarticle" );
        if ( $oArticle->load( $soxId ) ) {


            $sLangTag = oxLang::getInstance()->getLanguageTag();
            if ( isset( $this->sAttributeOXID) && ("" != $this->sAttributeOXID)) {
                $oGroups = oxNew( "oxlist" );
                $oGroups->init( "oxbase", "oxobject2attribute" );
                $sSelect =  "select * from oxobject2attribute where oxobject2attribute.oxobjectid= " . $oDb->quote( $oArticle->oxarticles__oxid->value ) . " and ";
                $sSelect .= " oxobject2attribute.oxattrid= " . $oDb->quote( $this->sAttributeOXID ) . " ";
                $oGroups->selectString( $sSelect );
                foreach ($oGroups as $oGroup) {
                    // sets new value
                    $sFieldName = "oxobject2attribute__oxvalue".$sLangTag;
                    $oGroup->$sFieldName->setValue($sAttributeValue);
                    $oGroup->save();
                }
            }
        }
    }
}
