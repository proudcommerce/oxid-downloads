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
 * @package   views
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxcmp_cur.php 26071 2010-02-25 15:12:55Z sarunas $
 */

/**
 * Currency manager class.
 * @subpackage oxcmp
 */
class oxcmp_cur extends oxView
{
    /**
     * Array of available currencies.
     * @var array
     */
    public $aCurrencies    = null;

    /**
     * Active currency object.
     * @var object
     */
    protected $_oActCur        = null;

    /**
     * Marking object as component
     * @var bool
     */
    protected $_blIsComponent = true;

    /**
     * Checks for currency parameter set in URL, session or post
     * variables. If such were found - loads all currencies possible
     * in shop, searches if passed is available (if no - default
     * currency is set the first defined in admin). Then sets currency
     * parameter so session ($myConfig->setActShopCurrency($iCur)),
     * loads basket and forces ir to recalculate (oBasket->blCalcNeeded
     * = true). Finally executes parent::init().
     *
     * @return null
     */
    public function init()
    {
        // Performance
        $myConfig = $this->getConfig();
        if ( !$myConfig->getConfigParam( 'bl_perfLoadCurrency' ) ) {
            //#861C -  show first currency
            $aCurrencies = $myConfig->getCurrencyArray();
            $this->_oActCur = current( $aCurrencies );
            return;
        }

        $iCur = oxConfig::getParameter( 'cur' );
        if ( isset( $iCur ) ) {
            $aCurrencies = $myConfig->getCurrencyArray();
            if (!isset( $aCurrencies[$iCur] ) ) {
                $iCur = 0;
            }

            // set new currency
            $myConfig->setActShopCurrency( $iCur );

            // recalc basket
            $oBasket = $this->getSession()->getBasket();

            $oBasket->onUpdate();
        }

        $iActCur = $myConfig->getShopCurrency();
        $this->aCurrencies = $myConfig->getCurrencyArray( $iActCur );

        $this->_oActCur     = $this->aCurrencies[$iActCur];

        //setting basket currency (M:825)
        if ( !isset( $oBasket ) ) {
            $oBasket = $this->getSession()->getBasket();
        }

        $oBasket->setBasketCurrency( $this->_oActCur );

        $sClass = $this->getConfig()->getActiveView()->getClassName();
        $sURL  = $myConfig->getShopCurrentURL();
        $sURL .= "cl={$sClass}";

        // #921 S
        // name of function
        $sVal = oxConfig::getParameter( 'fnc' );
        $aFnc = array( 'tobasket', 'login_noredirect', 'addVoucher' );
        if ( $sVal && in_array( $sVal, $aFnc ) ) {
            $sVal = '';
        }

        if ( $sVal ) {
            $sURL .= "&amp;fnc={$sVal}";
        }

        // active category
        if ( $sVal = oxConfig::getParameter( 'cnid' ) ) {
            $sURL .= "&amp;cnid={$sVal}";
        }

        // active article
        if ( $sVal= oxConfig::getParameter( 'anid' ) ) {
            $sURL .= "&amp;anid={$sVal}";
        }

        // active template
        if ( $sVal = basename( oxConfig::getParameter( 'tpl' ) ) ) {
            $sURL .= "&amp;tpl={$sVal}";
        }

        // number of active page
        $iPgNr = ( int ) oxConfig::getParameter( 'pgNr' );
        if ( $iPgNr > 0 ) {
            $sURL .= "&amp;pgNr={$iPgNr}";
        }

        // #1184M - specialchar search
        // search parameter
        if ( $sVal = rawurlencode( oxConfig::getParameter( 'searchparam', true ) ) ) {
            $sURL .= "&amp;searchparam={$sVal}";
        }

        // search category
        if ( $sVal = oxConfig::getParameter( 'searchcnid' ) ) {
            $sURL .= "&amp;searchcnid={$sVal}";
        }

        // search vendor
        if ( $sVal = oxConfig::getParameter( 'searchvendor' ) ) {
            $sURL .= "&amp;searchvendor={$sVal}";
        }

        // search manufacturer
        if ( $sVal = oxConfig::getParameter( 'searchmanufacturer' ) ) {
            $sURL .= "&amp;searchmanufacturer={$sVal}";
        }

        reset( $this->aCurrencies );
        while ( list( , $oItem ) = each( $this->aCurrencies ) ) {
            $oItem->link = oxUtilsUrl::getInstance()->processUrl("{$sURL}&amp;cur={$oItem->id}");
        }

        parent::init();
    }

    /**
     * Executes parent::render(), passes currency object to template
     * engine and returns currencies array.
     *
     * Template variables:
     * <b>currency</b>
     *
     * @return array
     */
    public function render()
    {
        parent::render();
        $oParentView = $this->getParent();
        $oParentView->setActCurrency( $this->_oActCur );
        // Passing to view. Left for compatibility reasons for a while. Will be removed in future
        $oParentView->addTplParam( 'currency', $oParentView->getActCurrency() );
        return $this->aCurrencies;
    }
}
