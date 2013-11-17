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
 * @version   SVN: $Id: oxattributelist.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

/**
 * Attribute list manager.
 */
class oxAttributeList extends oxList
{
    /**
     * Class constructor
     *
     * @param string $sObjectsInListName Associated list item object type
     *
     * @return null
     */
    public function __construct( $sObjectsInListName = 'oxattribute')
    {
        parent::__construct( 'oxattribute');
    }

    /**
     * Load all attributes by article Id's
     *
     * @param array $aIds article id's
     *
     * @return array $aAttributes;
     */
    public function loadAttributesByIds( $aIds)
    {
        if (!count($aIds)) {
            return;
        }

        foreach ($aIds as $iKey => $sVal) {
            $aIds[$iKey] = mysql_real_escape_string($sVal);
        }

        $sAttrViewName = getViewName( 'oxattribute' );
        $sLangAdd = oxLang::getInstance()->getLanguageTag();
        $sSelect  = "select $sAttrViewName.oxid, $sAttrViewName.oxtitle$sLangAdd, oxobject2attribute.oxvalue$sLangAdd, oxobject2attribute.oxobjectid ";
        $sSelect .= "from oxobject2attribute ";
        $sSelect .= "left join $sAttrViewName on $sAttrViewName.oxid = oxobject2attribute.oxattrid ";
        $sSelect .= "where oxobject2attribute.oxobjectid in ( '".implode("','", $aIds)."' ) ";
        $sSelect .= "order by oxobject2attribute.oxpos, $sAttrViewName.oxpos";

        return $this->_createAttributeListFromSql( $sSelect);
    }

    /**
     * Fills array with keys and products with value
     *
     * @param string $sSelect SQL select
     *
     * @return array $aAttributes
     */
    protected function _createAttributeListFromSql( $sSelect)
    {
        $aAttributes = array();
        $rs = oxDb::getDb()->Execute( $sSelect);
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                if ( !isset( $aAttributes[$rs->fields[0]])) {
                    $aAttributes[$rs->fields[0]] = new stdClass();
                }

                $aAttributes[$rs->fields[0]]->title = $rs->fields[1];
                if ( !isset( $aAttributes[$rs->fields[0]]->aProd[$rs->fields[3]])) {
                    $aAttributes[$rs->fields[0]]->aProd[$rs->fields[3]] = new stdClass();
                }
                $aAttributes[$rs->fields[0]]->aProd[$rs->fields[3]]->value = $rs->fields[2];
                $rs->moveNext();
            }
        }
        return $aAttributes;
    }

    /**
     * Load attributes by article Id
     *
     * @param string $sArtId article ids
     *
     * @return null;
     */
    public function loadAttributes( $sArtId)
    {
        if ( !$sArtId) {
            return;
        }

        $sSuffix = oxLang::getInstance()->getLanguageTag();

        $sAttrViewName = getViewName( 'oxattribute' );
        $sSelect  = "select $sAttrViewName.*, o2a.* ";
        $sSelect .= "from oxobject2attribute as o2a ";
        $sSelect .= "left join $sAttrViewName on $sAttrViewName.oxid = o2a.oxattrid ";
        $sSelect .= "where o2a.oxobjectid = '$sArtId' and o2a.oxvalue$sSuffix != '' ";
        $sSelect .= "order by o2a.oxpos, $sAttrViewName.oxpos";

        $this->selectString( $sSelect );
    }
}
