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
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: shop_config.php 26303 2010-03-04 16:11:37Z sarunas $
 */

/**
 * Admin shop config manager.
 * Collects shop config information, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> General.
 * @package admin
 */
class Shop_Config extends oxAdminDetails
{
    protected $_sThisTemplate = 'shop_config.tpl';
    protected $_aSkipMultiline = array('aHomeCountry', 'iShopID_TrustedShops');

    /**
     * Executes parent method parent::render(), passes shop configuration parameters
     * to Smarty and returns name of template file "shop_config.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig  = $this->getConfig();

        parent::render();


        $soxId = oxConfig::getParameter( "oxid");
        if ( !$soxId)
            $soxId = $myConfig->getShopId();
            //$soxId = oxSession::getVar("actshop");

        $sSavedID = oxConfig::getParameter( "saved_oxid");
        if ( ($soxId == "-1" || !isset( $soxId)) && isset( $sSavedID) ) {
            $soxId = $sSavedID;
            oxSession::deleteVar( "saved_oxid");
            $this->_aViewData["oxid"] =  $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] =  "1";
        }

        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $this->_aViewData["edit"] = $oShop = $this->_getEditShop( $soxId );

            try {
                // category choosen as default
                $this->_aViewData["defcat"] = null;
                if ($oShop->oxshops__oxdefcat->value) {
                    $oCat = oxNew( "oxCategory" );
                    if ($oCat->load($oShop->oxshops__oxdefcat->value)) {
                        $this->_aViewData["defcat"] = $oCat;
                    }
                }
            } catch ( Exception $oExcp ) {
                // on most cases this means that views are broken, so just
                // outputting notice and keeping functionality flow ..
                $this->_aViewData["updateViews"] = 1;
            }

            $iAoc = oxConfig::getParameter("aoc");
            if ( $iAoc == 1 ) {
                include_once 'inc/shop_default_category.inc.php';
                $this->_aViewData['oxajax'] = $aColumns;

                return "popups/shop_default_category.tpl";
            }

        }

        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");

        $aConfBools = array();
        $aConfStrs = array();
        $aConfArrs = array();
        $aConfAarrs = array();

        $oDb = oxDb::getDb();
        $rs = $oDb->Execute("select oxvarname, oxvartype, DECODE( oxvarvalue, ".$oDb->quote( $myConfig->getConfigParam( 'sConfigKey' ) ).") as oxvarvalue from oxconfig where oxshopid = '$soxId'");
        if ($rs != false && $rs->recordCount() > 0) {
            $oStr = getStr();
            while (!$rs->EOF) {
                $sVarName = $rs->fields[0];
                $sVarType = $rs->fields[1];
                $sVarVal  = $rs->fields[2];

                if ($sVarType == "bool")
                    $aConfBools[$sVarName] = ($sVarVal == "true" || $sVarVal == "1");
                if ($sVarType == "str" || $sVarType == "int") {
                    $aConfStrs[$sVarName] = $sVarVal;
                    if ( $aConfStrs[$sVarName] ) {
                        $aConfStrs[$sVarName] = $oStr->htmlentities( $aConfStrs[$sVarName] );
                    }
                }
                if ($sVarType == "arr") {
                    if (in_array($sVarName, $this->_aSkipMultiline)) {
                        $aConfArrs[$sVarName] = unserialize( $sVarVal );
                    } else {
                        $aConfArrs[$sVarName] = $oStr->htmlentities( $this->_arrayToMultiline( unserialize( $sVarVal ) ) );
                    }
                }
                if ($sVarType == "aarr") {
                    if (in_array($sVarName, $this->_aSkipMultiline)) {
                        $aConfAarrs[$sVarName] = unserialize( $sVarVal );
                    } else {
                        $aConfAarrs[$sVarName] = $oStr->htmlentities( $this->_aarrayToMultiline( unserialize( $sVarVal ) ) );
                    }
                }
                $rs->moveNext();
            }
        }

        $aConfStrs["sVersion"] = $myConfig->getConfigParam( 'sVersion' );

        $this->_aViewData["confbools"] = $aConfBools;
        $this->_aViewData["confstrs"] = $aConfStrs;
        $this->_aViewData["confarrs"] = $aConfArrs;
        $this->_aViewData["confaarrs"] = $aConfAarrs;

        $this->_aViewData["confarrs"] =  $aConfArrs;

        // #251A passing country list
        $oCountryList = oxNew( "oxCountryList" );
        $oCountryList->loadActiveCountries( oxLang::getInstance()->getTplLanguage() );

        if ( isset($aConfArrs["aHomeCountry"]) && count($aConfArrs["aHomeCountry"]) && count($oCountryList)) {
            foreach ( $oCountryList as $sCountryId => $oCountry) {
                if ( in_array($oCountry->oxcountry__oxid->value, $aConfArrs["aHomeCountry"]))
                    $oCountryList[$sCountryId]->selected = "1";
            }
        }

        $this->_aViewData["countrylist"] = $oCountryList;

        return $this->_sThisTemplate;
    }

    /**
     * Saves shop configuration variables
     *
     * @return null
     */
    public function saveConfVars()
    {
        $myConfig = $this->getConfig();


        $sOxId      = oxConfig::getParameter( "oxid" );
        $aConfBools = oxConfig::getParameter( "confbools" );
        $aConfStrs  = oxConfig::getParameter( "confstrs" );
        $aConfArrs  = oxConfig::getParameter( "confarrs" );
        $aConfAarrs = oxConfig::getParameter( "confaarrs" );

        // special case for min order price value
        if ( $aConfStrs['iMinOrderPrice'] ) {
            $aConfStrs['iMinOrderPrice'] = str_replace( ',', '.', $aConfStrs['iMinOrderPrice'] );
        }

        if ( is_array( $aConfBools ) ) {
            foreach ( $aConfBools as $sVarName => $sVarVal ) {
                $myConfig->saveShopConfVar( "bool", $sVarName, $sVarVal, $sOxId );
            }
        }

        if ( is_array( $aConfStrs ) ) {
            foreach ( $aConfStrs as $sVarName => $sVarVal ) {
                $myConfig->saveShopConfVar( "str", $sVarName, $sVarVal, $sOxId );
            }
        }

        if ( is_array( $aConfArrs ) ) {
            foreach ( $aConfArrs as $sVarName => $aVarVal ) {
                // home country multiple selectlist feature
                if ( !is_array( $aVarVal ) ) {
                    $aVarVal = $this->_multilineToArray( $aVarVal );
                }
                $myConfig->saveShopConfVar( "arr", $sVarName, $aVarVal, $sOxId );
            }
        }

        if ( is_array( $aConfAarrs ) ) {
            foreach ( $aConfAarrs as $sVarName => $aVarVal ) {
                $myConfig->saveShopConfVar( "aarr", $sVarName, $this->_multilineToAarray( $aVarVal ), $sOxId );
            }
        }
    }

    /**
     * Saves changed shop configuration parameters.
     *
     * @return mixed
     */
    public function save()
    {
        // saving config params
        $this->saveConfVars();

        //saving additional fields ("oxshops__oxdefcat"") that goes directly to shop (not config)
        $oShop = oxNew( "oxshop" );
        if ( $oShop->load( oxConfig::getParameter( "oxid" ) ) ) {
            $oShop->assign( oxConfig::getParameter( "editval" ) );
            $oShop->save();

            oxUtils::getInstance()->rebuildCache();
        }
    }


    /**
     * Converts simple array to multiline text. Returns this text.
     *
     * @param array $aInput Array with text
     *
     * @return string
     */
    protected function _arrayToMultiline( $aInput )
    {
        $sVal = '';
        if ( is_array( $aInput ) ) {
            $sVal = implode( "\n", $aInput );
        }
        return $sVal;
    }

    /**
     * Converts Multiline text to simple array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToArray( $sMultiline )
    {
        $aArr = explode( "\n", $sMultiline );
        if ( is_array( $aArr ) ) {
            foreach ( $aArr as $sKey => $sVal ) {
                $aArr[$sKey] = trim( $sVal );
                if ( $aArr[$sKey] == "" ) {
                    unset( $aArr[$sKey] );
                }
            }
            return $aArr;
        }
    }

    /**
     * Converts associative array to multiline text. Returns this text.
     *
     * @param array $aInput Array to convert
     *
     * @return string
     */
    protected function _aarrayToMultiline( $aInput )
    {
        if ( is_array( $aInput ) ) {
            $sMultiline = '';
            foreach ( $aInput as $sKey => $sVal ) {
                if ( $sMultiline ) {
                    $sMultiline .= "\n";
                }
                $sMultiline .= $sKey." => ".$sVal;
            }
            return $sMultiline;
        }
    }

    /**
     * Converts Multiline text to associative array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToAarray( $sMultiline )
    {
        $aArr = array();
        $aLines = explode( "\n", $sMultiline );
        foreach ( $aLines as $sLine ) {
            $sLine = trim( $sLine );
            if ( $sLine != "" && preg_match( "/(.+)=>(.+)/", $sLine, $aRegs ) ) {
                $sKey = trim( $aRegs[1] );
                $sVal = trim( $aRegs[2] );
                if ( $sKey != "" && $sVal != "" ) {
                    $aArr[$sKey] = $sVal;
                }
            }
        }

        return $aArr;
    }

}
