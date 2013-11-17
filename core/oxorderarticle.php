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
 * $Id: oxorderarticle.php 17248 2009-03-16 15:22:07Z arvydas $
 */

/**
 * Order article manager.
 * Performs copying of article.
 * @package core
 */
class oxOrderArticle extends oxBase
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxorderarticle';

    /**
     * Persisten info
     *
     * @var array
     */
    protected $_aPersParam = null;

    /**
     * ERP status info
     *
     * @var array
     */
    protected $_aStatuses = null;

    /**
     * ERP status info
     *
     * @deprecated use $_aStatuses instead
     *
     * @var array
     */
    public $aStatuses = null;

    /**
     * Persisten info
     *
     * @deprecated use $_aPersParam instead
     *
     * @var array
     */
    public $aPersParam = null;

    /**
     * Total brutto price
     *
     * @deprecated
     *
     * @var string
     */
    public $ftotbrutprice = null;

    /**
     * Brutto price
     *
     * @deprecated
     *
     * @var string
     */
    public $fbrutprice = null;

    /**
     * Netto price
     *
     * @deprecated
     *
     * @var string
     */
    public $fnetprice = null;

    /**
     * Class constructor, initiates class constructor (parent::oxbase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init( 'oxorderarticles' );
    }

    /**
     * Copies passed to method product into $this.
     *
     * @param object $oProduct product to copy
     *
     * @return null
     */
    public function copyThis( $oProduct )
    {
        $aObjectVars = get_object_vars( $oProduct );

        foreach ( $aObjectVars as $sName => $sValue ) {
            if ( isset( $oProduct->$sName->value ) ) {
                $sFieldName = preg_replace('/oxarticles__/', 'oxorderarticles__', $sName);
                $this->$sFieldName = $oProduct->$sName;
            }
        }

    }

    /**
     * Assigns DB field values to object fields.
     *
     * @param string $dbRecord DB record
     *
     * @return null
     */
    public function assign( $dbRecord )
    {
        parent::assign( $dbRecord );
        $this->_setDeprecatedValues();
    }

    /**
     * Performs stock modification for current order article. Additionally
     * executes changeable article onChange/updateSoldAmount methods to
     * update chained data
     *
     * @param double $dAddAmount           amount which will be substracled from value in db
     * @param bool   $blAllowNegativeStock amount allow or not negative stock value
     *
     * @return null
     */
    public function updateArticleStock( $dAddAmount = null, $blAllowNegativeStock = null )
    {
        // decrement stock if there is any
        $oArticle = oxNew( 'oxarticle' );
        $oArticle->load( $this->oxorderarticles__oxartid->value );
        $oArticle->beforeUpdate();

        // get real article stock count
        $iStockCount = $this->_getArtStock( $dAddAmount, $blAllowNegativeStock );

        // #874A. added oxarticles.oxtimestamp = oxarticles.oxtimestamp to keep old timestamp value
        $oArticle->oxarticles__oxstock = new oxField($iStockCount);
        oxDb::getDb()->execute( 'update oxarticles set oxarticles.oxstock = '.$iStockCount.', oxarticles.oxtimestamp = oxarticles.oxtimestamp where oxarticles.oxid = "'.$this->oxorderarticles__oxartid->value.'" ' );
        $oArticle->onChange( ACTION_UPDATE_STOCK );

        //update article sold amount
        $oArticle->updateSoldAmount( $dAddAmount * ( -1 ) );
    }

    /**
     * Adds or substracts defined amount passed by param from arcticle stock
     *
     * @param double $dAddAmount           amount which will be added/substracled from value in db
     * @param bool   $blAllowNegativeStock allow/disallow negative stock value
     *
     * @return double
     */
    protected function _getArtStock( $dAddAmount = null, $blAllowNegativeStock = null )
    {
        // #1592A. must take real value
        $sQ = 'select oxstock from oxarticles where oxid = "'.$this->oxorderarticles__oxartid->value.'" ';
        $iStockCount  = ( float ) oxDb::getDb()->getOne( $sQ );

        $iStockCount += $dAddAmount;

        // #1592A. calculating according new stock option
        if ( !$blAllowNegativeStock && $iStockCount < 0 ) {
            $iStockCount = 0;
        }

        return $iStockCount;
    }


    /**
     * Order persistent data getter
     *
     * @return array
     */
    public function getPersParams()
    {
        if ( $this->_aPersParam != null ) {
            return $this->_aPersParam;
        }

        if ( $this->oxorderarticles__oxpersparam->value ) {
            $this->_aPersParam = unserialize( $this->oxorderarticles__oxpersparam->value );
        }

        return $this->_aPersParam;
    }

    /**
     * Order persistent params setter
     *
     * @param array $aParams array of params
     *
     * @return null
     */
    public function setPersParams( $aParams )
    {
        $this->_aPersParam = $aParams;

        // serializing persisten info stored while ordering
        $this->oxorderarticles__oxpersparam = new oxField(serialize( $aParams ), oxField::T_RAW);
    }

    /**
     * Sets deprecate values
     *
     * @deprecated This method as well as all deprecated class variables is deprecated
     *
     * @return null
     */
    protected function _setDeprecatedValues()
    {

        $this->aPersParam = $this->getPersParams();

        if ( $this->oxorderarticles__oxstorno->value != 1 ) {
            $oLang = oxLang::getInstance();
            $this->ftotbrutprice = $oLang->formatCurrency( $this->oxorderarticles__oxbrutprice->value );
            $this->fbrutprice    = $oLang->formatCurrency( $this->oxorderarticles__oxbprice->value );
            $this->fnetprice     = $oLang->formatCurrency( $this->oxorderarticles__oxnprice->value );
        }
    }

    /**
     * Sets data field value
     *
     * @param string $sFieldName index OR name (eg. 'oxarticles__oxtitle') of a data field to set
     * @param string $sValue     value of data field
     * @param int    $iDataType  field type
     *
     * @return null
     */
    protected function _setFieldData( $sFieldName, $sValue, $iDataType = oxField::T_TEXT)
    {
        $sFieldName = strtolower($sFieldName);
        switch ( $sFieldName ) {
            case 'oxpersparam':
            case 'oxorderarticles__oxpersparam':
            case 'oxerpstatus':
            case 'oxorderarticles__oxerpstatus':
            case 'oxtitle':
            case 'oxorderarticles__oxtitle':
                $iDataType = oxField::T_RAW;
                break;
        }
        return parent::_setFieldData($sFieldName, $sValue, $iDataType);
    }
}
