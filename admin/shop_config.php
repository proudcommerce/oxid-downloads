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
 * $Id: shop_config.php 21092 2009-07-22 14:42:13Z vilma $
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
    protected $_aSkipMultiline = array('aRssSelected', 'aHomeCountry', 'iShopID_TrustedShops');

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
                // category choose list
                $oCatTree = oxNew( "oxCategoryList" );
                $oCatTree->buildList($myConfig->getConfigParam( 'bl_perfLoadCatTree' ));

                foreach($oCatTree as $key => $val) {
                    if ( $val->oxcategories__oxid->value == $oShop->oxshops__oxdefcat->value) {
                        $val->selected = 1;
                        $oCatTree[$key] = $val;
                        break;
                    }
                }
                $this->_aViewData["cattree"]     =  $oCatTree;
            } catch ( Exception $oExcp ) {
                // on most cases this means that views are broken, so just
                // outputting notice and keeping functionality flow ..
                $this->_aViewData["updateViews"] = 1;
            }
        }

        // check if we right now saved a new entry
        $sSavedID = oxConfig::getParameter( "saved_oxid");

        $aConfBools = array();
        $aConfStrs = array();
        $aConfArrs = array();
        $aConfAarrs = array();


        $rs = oxDb::getDb()->Execute("select oxvarname, oxvartype, DECODE( oxvarvalue, '".$myConfig->getConfigParam( 'sConfigKey' )."') as oxvarvalue from oxconfig where oxshopid = '$soxId'");
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


        $soxId      = oxConfig::getParameter( "oxid" );
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
                $myConfig->saveShopConfVar( "bool", $sVarName, $sVarVal, $soxId );
            }
        }

        if ( is_array( $aConfStrs ) ) {
            foreach ( $aConfStrs as $sVarName => $sVarVal ) {
                $myConfig->saveShopConfVar( "str", $sVarName, $sVarVal, $soxId );
            }
        }

        if ( is_array( $aConfArrs ) ) {
            foreach ( $aConfArrs as $sVarName => $sVarVal ) {
                // home country multiple selectlist feature
                if ( is_array( $sVarVal ) ) {
                    $sValue = serialize($sVarVal);
                } else {
                    $sValue = serialize($this->_multilineToArray($sVarVal));
                }
                $myConfig->saveShopConfVar("arr", $sVarName, $sValue, $soxId);
            }
        }

        if ( is_array( $aConfAarrs ) ) {
            foreach ( $aConfAarrs as $sVarName => $sVarVal ) {
                $myConfig->saveShopConfVar( "aarr", $sVarName, serialize( $this->_multilineToAarray( $sVarVal ) ), $soxId );
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
        $soxId   = oxConfig::getParameter( "oxid" );
        $aParams = oxConfig::getParameter( "editval" );

        $oShop = oxNew( "oxshop" );
        $oShop->load( $soxId);
        $oShop->assign( $aParams);
        $oShop->save();

        oxUtils::getInstance()->rebuildCache();
    }


    /**
     * Converts simple array to multiline text. Returns this text.
     *
     * @param array $aInput Array with text
     *
     * @return string
     */
    protected function _arrayToMultiline($aInput)
    {
        if (!is_array($aInput)) {
            return '';
        }
        return implode("\n", $aInput);
    }

    /**
     * Converts Multiline text to simple array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToArray($sMultiline)
    {
        $aArr = explode("\n", $sMultiline);

        if (!is_array($aArr))
            return ;

        foreach ($aArr as $key=>$val) {
            $aArr[$key] = trim($val);
            if ($aArr[$key] == "")
                unset($aArr[$key]);
        }
        return $aArr;
    }

    /**
     * Converts associative array to multiline text. Returns this text.
     *
     * @param array $aInput Array to convert
     *
     * @return string
     */
    protected function _aarrayToMultiline($aInput)
    {
        $sMultiline = "";

        if (!is_array($aInput))
            return ;

        foreach ($aInput as $key => $val) {
            $sMultiline .= $key." => ".$val."\n";
        }
        $sMultiline = getStr()->substr($sMultiline, 0, -1);
        return $sMultiline;
    }

    /**
     * Converts Multiline text to associative array. Returns this array.
     *
     * @param string $sMultiline Multiline text
     *
     * @return array
     */
    protected function _multilineToAarray($sMultiline)
    {
        $aArr = array();

        $aLines = explode("\n", $sMultiline);

        foreach ($aLines as $sLine) {
            $sLine = trim($sLine);
            if ($sLine != "" && preg_match("/(.+)=>(.+)/", $sLine, $regs)) {
                $key = trim($regs[1]);
                $val = trim($regs[2]);
                if ($key != "" && $val != "")
                    $aArr[$key] = $val;
            }
        }

        return $aArr;
    }

}
