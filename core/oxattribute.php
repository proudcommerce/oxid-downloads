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
 * @version   SVN: SVN: $Id: oxattribute.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

/**
 * Article attributes manager.
 * Collects and keeps attributes of chosen article.
 * @package core
 */
class oxAttribute extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxattribute';

    /**
     * Object core table name
     *
     * @var string
     */
    protected $_sCoreTbl = 'oxattribute';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( $this->_sCoreTbl);
    }

    /**
     * Removes attributes from articles, returns true on success.
     *
     * @param string $sOXID Object ID
     *
     * @return bool
     */
    public function delete( $sOXID = null )
    {
        if( !$sOXID)
            $sOXID = $this->getId();
        if( !$sOXID)
            return false;


        // remove attributes from articles also
        $oDB = oxDb::getDb();
        $sOxidQuoted = $oDB->quote($sOXID);
        $sDelete = "delete from oxobject2attribute where oxattrid = ".$sOxidQuoted;
        $rs = $oDB->execute( $sDelete);

        // #657 ADDITIONAL removes attribute connection to category
        $sDelete = "delete from oxcategory2attribute where oxattrid = ".$sOxidQuoted;
        $rs = $oDB->execute( $sDelete);

        return parent::delete( $sOXID);
    }

    /**
     * Assigns attribute to variant
     *
     * @param array $aMDVariants article ids with selectionlist values
     * @param array $aSelTitle   selection list titles
     *
     * @return null
     */
    public function assignVarToAttribute( $aMDVariants, $aSelTitle )
    {
        $myLang    = oxLang::getInstance();
        $aConfLanguages = $myLang->getLanguageIds();
        $sAttrId = $this->_getAttrId( $aSelTitle[0] );
        if ( !$sAttrId ) {
            $sAttrId = $this->_createAttribute( $aSelTitle );
        }
        foreach ( $aMDVariants as $sVarId => $oValue ) {
            if ( strpos( $sVarId, "mdvar_" ) === 0 ) {
                foreach ( $oValue as $sId ) {
                    //var_dump($sVarId, $oAttribute->oxattribute__oxid->value);
                    $sVarId = substr($sVarId, 6);
                    $oNewAssign = oxNew( "oxbase" );
                    $oNewAssign->init( "oxobject2attribute" );
                    $sNewId = oxUtilsObject::getInstance()->generateUID();
                    if ($oNewAssign->load($sId)) {
                        $oNewAssign->oxobject2attribute__oxobjectid = new oxField($sVarId);
                        $oNewAssign->setId($sNewId);
                        $oNewAssign->save();
                    }
                }
            } else {
                $oNewAssign = oxNew( "oxbase" );
                $oNewAssign->init( "oxobject2attribute" );
                $oNewAssign->oxobject2attribute__oxobjectid = new oxField($sVarId);
                $oNewAssign->oxobject2attribute__oxattrid   = new oxField($sAttrId);
                foreach ($aConfLanguages as $sKey => $sLang) {
                    $sPrefix = $myLang->getLanguageTag($sKey);
                    $oNewAssign->{'oxobject2attribute__oxvalue'.$sPrefix} = new oxField($oValue[$sKey]->name);
                }
                $oNewAssign->save();
            }
        }
    }

    /**
     * Searches for attribute by oxtitle. If exists returns attribute id
     *
     * @param string $sSelTitle selection list title
     *
     * @return mixed attribute id or false
     */
    protected function _getAttrId( $sSelTitle )
    {
        $oDb = oxDb::getDB();
        $sAttViewName = getViewName('oxattribute');
        return $oDb->getOne("select oxid from $sAttViewName where LOWER(oxtitle) = " . $oDb->quote(getStr()->strtolower($sSelTitle)));
    }

    /**
     * Checks if attribute exists
     *
     * @param array $aSelTitle selection list title
     *
     * @return string attribute id
     */
    protected function _createAttribute( $aSelTitle )
    {
        $myLang    = oxLang::getInstance();
        $aConfLanguages = $myLang->getLanguageIds();
        $oAttr = oxNew('oxbase');
        $oAttr->init('oxattribute');
        foreach ($aConfLanguages as $sKey => $sLang) {
           $sPrefix = $myLang->getLanguageTag($sKey);
           $oAttr->{'oxattribute__oxtitle'.$sPrefix} = new oxField($aSelTitle[$sKey]);
        }
        $oAttr->save();
        return $oAttr->getId();
    }

    /**
     * Returns all oxobject2attribute Ids of article
     *
     * @param string $sArtId article ids
     *
     * @return null;
     */
    public function getAttributeAssigns( $sArtId)
    {
        if ( !$sArtId) {
            return;
        }
        $sSelect  = "select o2a.oxid ";
        $sSelect .= "from oxobject2attribute as o2a ";
        $sSelect .= "where o2a.oxobjectid = '$sArtId' ";
        $sSelect .= "order by o2a.oxpos";

        $aIds = array();
        $rs = oxDb::getDb()->Execute( $sSelect);
        if ($rs != false && $rs->recordCount() > 0) {
            while (!$rs->EOF) {
                $aIds[] = $rs->fields[0];
                $rs->moveNext();
            }
        }
        return $aIds;
    }
}
