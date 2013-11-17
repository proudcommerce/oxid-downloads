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
 * @package views
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: suggest.php 18680 2009-04-29 14:20:46Z vilma $
 */

/**
 * Article suggestion page.
 * Collects some article base information, sets default recomendation text,
 * sends suggestion mail to user.
 */
class Suggest extends oxUBase
{
    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'suggest.tpl';

    /**
     * Required fields to fill before sending suggest email
     * @var array
     */
    protected $_aReqFields = array( 'rec_name', 'rec_email', 'send_name', 'send_email', 'send_message', 'send_subject' );

    /**
     * CrossSelling articlelist
     * @var object
     */
    protected $_oCrossSelling = null;

    /**
     * Similar products articlelist
     * @var object
     */
    protected $_oSimilarProducts = null;

    /**
     * Recommlist
     * @var object
     */
    protected $_oRecommList = null;

    /**
     * Recommlist
     * @var object
     */
    protected $_aSuggestData = null;

    /**
     * Loads and passes article and related info to template engine
     * (oxarticle::getReviews(), oxarticle::getCrossSelling(),
     * oxarticle::GetSimilarProducts()), executes parent::render()
     * and returns template file name to render suggest::_sThisTemplate.
     *
     * Template variables:
     * <b>product</b>, <b>reviews</b>, <b>crossselllist</b>,
     * <b>similarlist</b>
     *
     * @return  string  current template file name
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['product']           = $this->getProduct();
        $this->_aViewData['crossselllist']     = $this->getCrossSelling();
        $this->_aViewData['similarlist']       = $this->getSimilarProducts();
        $this->_aViewData['similarrecommlist'] = $this->getRecommList();

        $this->_aViewData['editval'] = $this->getSuggestData();

        return $this->_sThisTemplate;
    }

    /**
     * Sends product suggestion mail and returns a URL according to
     * URL formatting rules.
     *
     * Template variables:
     * <b>editval</b>, <b>error</b>
     *
     * @return  null
     */
    public function send()
    {
        $aParams = oxConfig::getParameter( 'editval' );
        if ( !is_array( $aParams ) ) {
            return;
        }

        // storing used written values
        $oParams = new Oxstdclass();
        reset( $aParams );
        while ( list( $sName, $sValue ) = each( $aParams ) ) {
            $oParams->$sName = $sValue;
        }
        $this->_aSuggestData = $oParams;

        $oUtilsView = oxUtilsView::getInstance();
        // filled not all fields ?
        foreach ( $this->_aReqFields as $sFieldName ) {
            if ( !isset( $aParams[$sFieldName] ) || !$aParams[$sFieldName] ) {
                $oUtilsView->addErrorToDisplay('SUGGEST_COMLETECORRECTLYFIELDS');
                return;
            }
        }

        $sReturn = "";
        // #1834M - specialchar search
        $sSearchParamForLink = rawurlencode( oxConfig::getParameter( 'searchparam', true ) );
        if ( $sSearchParamForLink ) {
            $sReturn .= "&searchparam=$sSearchParamForLink";
        }

        $sSearchCatId = oxConfig::getParameter( 'searchcnid' );
        if ( $sSearchCatId ) {
            $sReturn .= "&searchcnid=$sSearchCatId";
        }

        $sSearchVendor = oxConfig::getParameter( 'searchvendor' );
        if ( $sSearchVendor ) {
            $sReturn .= "&searchvendor=$sSearchVendor";
        }

        if ( ( $sSearchManufacturer = oxConfig::getParameter( 'searchmanufacturer' ) ) ) {
            $sReturn .= "&searchmanufacturer=$sSearchManufacturer";
        }

        $sListType = oxConfig::getParameter( 'listtype' );
        if ( $sListType ) {
            $sReturn .= "&listtype=$sListType";
        }

        // sending suggest email
        $oEmail = oxNew( 'oxemail' );
        $oProduct = $this->getProduct();
        if ( $oProduct && $oEmail->sendSuggestMail( $oParams, $oProduct ) ) {
            return 'details?anid='.$oProduct->getId().$sReturn;
        } else {
            oxUtilsView::getInstance()->addErrorToDisplay('SUGGEST_INVALIDMAIL');
        }
    }

    /**
     * Template variable getter. Returns search product
     *
     * @return object
     */
    public function getProduct()
    {
        if ( $this->_oProduct === null ) {
            $this->_oProduct = false;

            if ( $sAnid = oxConfig::getParameter( 'anid' ) ) {
                $this->_oProduct = oxNewArticle( $sAnid );
            }
        }
        return $this->_oProduct;
    }

    /**
     * Template variable getter. Returns recommlist's reviews
     *
     * @return array
     */
    public function getCrossSelling()
    {
        if ( $this->_oCrossSelling === null ) {
            $this->_oCrossSelling = false;
            if ( $oProduct = $this->getProduct() ) {
                $this->_oCrossSelling = $oProduct->getCrossSelling();
            }
        }
        return $this->_oCrossSelling;
    }

    /**
     * Template variable getter. Returns recommlist's reviews
     *
     * @return array
     */
    public function getSimilarProducts()
    {
        if ( $this->_oSimilarProducts === null ) {
            $this->_oSimilarProducts = false;
            if ( $oProduct = $this->getProduct() ) {
                $this->_oSimilarProducts = $oProduct->getSimilarProducts();
            }
        }
        return $this->_oSimilarProducts;
    }

    /**
     * Template variable getter. Returns recommlist's reviews
     *
     * @return array
     */
    public function getRecommList()
    {
        if ( $this->_oRecommList === null ) {
            $this->_oRecommList = false;
            if ( $oProduct = $this->getProduct() ) {
                $oRecommList = oxNew('oxrecommlist');
                $this->_oRecommList = $oRecommList->getRecommListsByIds( array( $oProduct->getId() ) );
            }
        }
        return $this->_oRecommList;
    }

    /**
     * Template variable getter. Returns active object's reviews
     *
     * @return array
     */
    public function getSuggestData()
    {
        return $this->_aSuggestData;
    }

    /**
     * get link of current view
     *
     * @param int $iLang requested language
     *
     * @return string
     */
    public function getLink( $iLang = null )
    {
        $sLink = parent::getLink( $iLang );
        
        // active category
        if ( $sVal = oxConfig::getParameter( 'cnid' ) ) {
            $sLink .= ( ( strpos( $sLink, '?' ) === false ) ? '?' : '&amp;' ) . "cnid={$sVal}";
        }

        // active article
        if ( $sVal= oxConfig::getParameter( 'anid' ) ) {
            $sLink .= ( ( strpos( $sLink, '?' ) === false ) ? '?' : '&amp;' ) . "anid={$sVal}";
        }

        return $sLink;
    }

}
