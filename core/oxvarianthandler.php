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
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: oxvarianthandler.php 22524 2009-09-22 11:47:27Z tomas $
 */

/**
 * oxVariantHandler encapsulates methods dealing with multidimensional variant and variant names.
 *
 * @package core
 */
class oxVariantHandler extends oxSuperCfg
{
    /**
     * Variant names
     *
     * @var array
     */
    protected $_oArticles = null;

    /**
     * Multidimensional variant separator
     *
     * @var string
     */
    protected $_sMdSeparator = " | ";

    /**
     * Multidimensional variant tree structure
     *
     * @var OxMdVariant
     */
    protected $_oMdVariants = null;

    /**
     * Sets internal variant name array from article list.
     *
     * @param oxList[string]oxArticle $oArticles Variant list as
     *
     * @return null
     */
    public function init( $oArticles )
    {
        $this->_oArticles = $oArticles;
    }

    /**
     * Returns multidimensional variant structure
     *
     * @param object $oVariants all article variants
     * @param string $sParentId parent article id
     *
     * @return OxMdVariants
     */
    public function buildMdVariants( $oVariants, $sParentId )
    {
        $oMdVariants = oxNew( "OxMdVariant" );
        $oMdVariants->setParentId( $sParentId );
        $oMdVariants->setName( "_parent_product_" );
        foreach ( $oVariants as $sKey => $oVariant ) {
            $aNames = explode( trim( $this->_sMdSeparator ), $oVariant->oxarticles__oxvarselect->value );
            foreach ( $aNames as $sNameKey => $sName ) {
                $aNames[$sNameKey] = trim($sName);
            }
            $oMdVariants->addNames( $sKey,
                                    $aNames,
                                    $oVariant->getPrice()->getBruttoPrice(),
                                    $oVariant->getLink() );
        }

        return $oMdVariants;
    }

    /**
     * Generate variants from selection lists
     *
     * @param array  $aSels    ids of selection list
     * @param object $oArticle parent article
     *
     * @return null
     */
    public function genVariantFromSell( $aSels, $oArticle )
    {
        $oVariants = $oArticle->getAdminVariants();
        $myConfig  = $this->getConfig();
        $myUtils   = oxUtils::getInstance();
        $myLang    = oxLang::getInstance();
        $aConfLanguages = $myLang->getLanguageIds();

        foreach ($aSels as $sSelId) {
            $oSel = oxNew("oxbase");
            $oSel->init( 'oxselectlist' );
            $oSel->load( $sSelId );
            $sVarNameUpdate = "";
            foreach ($aConfLanguages as $sKey => $sLang) {
                $sPrefix = $myLang->getLanguageTag($sKey);
                $aSelValues = $myUtils->assignValuesFromText($oSel->{"oxselectlist__oxvaldesc".$sPrefix}->value );
                foreach ($aSelValues as $sI => $oValue ) {
                    $aValues[$sI][$sKey] = $oValue;
                }
                $aSelTitle[$sKey] = $oSel->{"oxselectlist__oxtitle".$sPrefix}->value;
                $sMdSeparator = ($oArticle->oxarticles__oxvarname->value) ? $this->_sMdSeparator: '';
                if ( $sVarNameUpdate ) {
                    $sVarNameUpdate .= ", ";
                }
                $sVarName = oxDb::getDb()->quote($sMdSeparator.$aSelTitle[$sKey]);
                $sVarNameUpdate .= "oxvarname".$sPrefix." = CONCAT(oxvarname".$sPrefix.", ".$sVarName.")";
            }
            $oMDVariants = $this->_assignValues( $aValues, $oVariants, $oArticle, $aConfLanguages);
            if ( $myConfig->getConfigParam( 'blUseMultidimensionVariants' ) ) {
                $oAttribute = oxNew("oxattribute");
                $oAttribute->assignVarToAttribute( $oMDVariants, $aSelTitle );
            }
            $this->_updateArticleVarName( $sVarNameUpdate, $oArticle->oxarticles__oxid->value );
        }
    }

    /**
     * Assigns values of selection list to variants
     *
     * @param array  $aValues        multilang values of selection list
     * @param object $oVariants      variant list
     * @param object $oArticle       parent article
     * @param array  $aConfLanguages array of all active languages
     *
     * @return mixed
     */
    protected function _assignValues( $aValues, $oVariants, $oArticle, $aConfLanguages)
    {
        $myConfig = $this->getConfig();
        $myLang    = oxLang::getInstance();
        $iCounter = 0;
        $aVarselect = array(); //multilanguage names of existing variants
        //iterating through all select list values (eg. $oValue->name = S, M, X, XL)
        for ( $i=0; $i<count($aValues); $i++ ) {
            $oValue = $aValues[$i][0];
            $dPriceMod = $this->_getValuePrice( $oValue, $oArticle->oxarticles__oxprice->value);
            if ( $oVariants->count() > 0 ) {
                //if we have any existing variants then copying each variant with $oValue->name
                foreach ( $oVariants as $oSimpleVariant ) {
                    if ( !$iCounter ) {
                        //we just update the first variant
                        $oVariant = oxNew("oxarticle");
                        $oVariant->setEnableMultilang(false);
                        $oVariant->load($oSimpleVariant->oxarticles__oxid->value);
                        $oVariant->oxarticles__oxprice->setValue( $oVariant->oxarticles__oxprice->value + $dPriceMod );
                        //assign for all languages
                        foreach ( $aConfLanguages as $sKey => $sLang ) {
                            $oValue = $aValues[$i][$sKey];
                            $sPrefix = $myLang->getLanguageTag($sKey);
                            $aVarselect[$oSimpleVariant->oxarticles__oxid->value][$sKey] = $oVariant->{"oxarticles__oxvarselect".$sPrefix}->value;
                            $oVariant->{'oxarticles__oxvarselect'.$sPrefix}->setValue($oVariant->{"oxarticles__oxvarselect".$sPrefix}->value.$this->_sMdSeparator.$oValue->name);
                        }
                        $oVariant->oxarticles__oxsort->setValue($oVariant->oxarticles__oxsort->value * 10);
                        $oVariant->save();
                        $sVarId = $oSimpleVariant->oxarticles__oxid->value;
                    } else {
                        //we create new variants
                        foreach ($aVarselect[$oSimpleVariant->oxarticles__oxid->value] as $sKey => $sVarselect) {
                            $oValue = $aValues[$i][$sKey];
                            $sPrefix = $myLang->getLanguageTag($sKey);
                            $aParams['oxarticles__oxvarselect'.$sPrefix] = $sVarselect.$this->_sMdSeparator.$oValue->name;
                        }
                        $aParams['oxarticles__oxartnum'] = $oSimpleVariant->oxarticles__oxartnum->value . "-" . $iCounter;
                        $aParams['oxarticles__oxprice'] = $oSimpleVariant->oxarticles__oxprice->value + $dPriceMod;
                        $aParams['oxarticles__oxsort'] = $oSimpleVariant->oxarticles__oxsort->value*10 + 10*$iCounter;
                        $aParams['oxarticles__oxstock'] = 0;
                        $aParams['oxarticles__oxstockflag'] = $oSimpleVariant->oxarticles__oxstockflag->value;
                        $sVarId = $this->_craeteNewVariant( $aParams, $oArticle->oxarticles__oxid->value );
                        if ( $myConfig->getConfigParam( 'blUseMultidimensionVariants' ) ) {
                            $oAttrList = oxNew('oxattribute');
                            $aIds = $oAttrList->getAttributeAssigns( $oSimpleVariant->oxarticles__oxid->value);
                            $aMDVariants["mdvar_".$sVarId] = $aIds;
                        }
                    }
                    if ( $myConfig->getConfigParam( 'blUseMultidimensionVariants' ) ) {
                        $aMDVariants[$sVarId] = $aValues[$i];
                    }
                }
                $iCounter++;
            } else {
                //in case we don't have any variants then we just create variant(s) with $oValue->name
                $iCounter++;
                foreach ($aConfLanguages as $sKey => $sLang) {
                    $oValue = $aValues[$i][$sKey];
                    $sPrefix = $myLang->getLanguageTag($sKey);
                    $aParams['oxarticles__oxvarselect'.$sPrefix] = $oValue->name;
                }
                $aParams['oxarticles__oxartnum'] = $oArticle->oxarticles__oxartnum->value . "-" . $iCounter ;
                $aParams['oxarticles__oxprice'] = $oArticle->oxarticles__oxprice->value + $dPriceMod;
                $aParams['oxarticles__oxsort'] = 5000 + $iCounter * 1000;
                $aParams['oxarticles__oxstock'] = 0;
                $aParams['oxarticles__oxstockflag'] = $oArticle->oxarticles__oxstockflag->value;
                $sVarId = $this->_craeteNewVariant( $aParams, $oArticle->oxarticles__oxid->value );
                if ( $myConfig->getConfigParam( 'blUseMultidimensionVariants' ) ) {
                    $aMDVariants[$sVarId] = $aValues[$i];
                }
            }
        }
        return $aMDVariants;
    }

    /**
     * Returns article price
     *
     * @param object $oValue       selection list value
     * @param double $dParentPrice parent article price
     *
     * @return double
     */
    protected function _getValuePrice( $oValue, $dParentPrice)
    {
        $myConfig = $this->getConfig();
        $dPriceMod = 0;
        if ( $myConfig->getConfigParam( 'bl_perfLoadSelectLists' ) && $myConfig->getConfigParam( 'bl_perfUseSelectlistPrice' ) ) {
            if ($oValue->priceUnit == 'abs') {
                $dPriceMod = $oValue->price;
            } elseif ($oValue->priceUnit == '%') {
                $dPriceModPerc = abs($oValue->price)*$dParentPrice/100.0;
                if (($oValue->price) >= 0.0) {
                    $dPriceMod = $dPriceModPerc;
                } else {
                    $dPriceMod = -$dPriceModPerc;
                }
            }
        }
        return $dPriceMod;
    }

    /**
     * Creates new article variant.
     *
     * @param array  $aParams   assigned parameters
     * @param string $sParentId parent article id
     *
     * @return null
     */
    protected function _craeteNewVariant( $aParams = null, $sParentId = null)
    {
        // checkbox handling
        $aParams['oxarticles__oxactive'] = 0;

            // shopid
            $sShopID = oxSession::getVar( "actshop");
            $aParams['oxarticles__oxshopid'] = $sShopID;

        // varianthandling
        $aParams['oxarticles__oxparentid'] = $sParentId;

        $oArticle = oxNew("oxbase");
        $oArticle->init( 'oxarticles' );
        $oArticle->assign( $aParams);

            //echo $aParams['oxarticles__oxartnum']."---";
            $oArticle->save();

        return $oArticle->getId();
    }

    /**
     * Inserts article variant name for all languages
     *
     * @param string $sUpdate query for update variant name
     * @param string $sArtId  parent article id
     *
     * @return null
     */
    protected function _updateArticleVarName( $sUpdate, $sArtId )
    {
        $sUpdate = "update oxarticles set " . $sUpdate . " where oxid = '" . $sArtId . "'";

        oxDb::getDb()->Execute( $sUpdate);
    }

    /**
     * Check if variant is multidimensional
     *
     * @param oxArticle $oArticle Article object
     *
     * @return bool
     */
    public function isMdVariant( $oArticle )
    {
        if ( $this->getConfig()->getConfigParam( 'blUseMultidimensionVariants' ) ) {
            if ( strpos( $oArticle->oxarticles__oxvarselect->value, trim($this->_sMdSeparator) ) !== false ) {
                return true;
            }
        }

        return false;
    }

}
