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
 * $Id: oxerptype_article.php 18030 2009-04-09 11:36:21Z arvydas $
 */

require_once 'oxerptype.php';

/**
 * ERP article description class
 */
class oxERPType_Article extends oxERPType
{
    /**
     * object fields description
     * @var array
     */
    protected $_aFieldListVersions = array (
        '1' => array(
            'OXSHOPID'         => 'OXSHOPID',
            'OXSHOPINCL'       => 'OXSHOPINCL',
            'OXSHOPEXCL'       => 'OXSHOPEXCL',
            'OXPARENTID'       => 'OXPARENTID',
            'OXACTIV'          => 'OXACTIV',
            'OXACTIVFROM'      => 'OXACTIVFROM',
            'OXACTIVTO'        => 'OXACTIVTO',
            'OXARTNUM'         => 'OXARTNUM',
            'OXEAN'            => 'OXEAN',
            'OXDISTEAN'        => 'OXDISTEAN',
            'OXTITLE'          => 'OXTITLE',
            'OXSHORTDESC'      => 'OXSHORTDESC',
            'OXLONGDESC'       => 'OXLONGDESC',
            'OXPRICE'          => 'OXPRICE',
            'OXBLFIXEDPRICE'   => 'OXBLFIXEDPRICE',
            'OXPRICEA'         => 'OXPRICEA',
            'OXPRICEB'         => 'OXPRICEB',
            'OXPRICEC'         => 'OXPRICEC',
            'OXBPRICE'         => 'OXBPRICE',
            'OXTPRICE'         => 'OXTPRICE',
            'OXUNITNAME'       => 'OXUNITNAME',
            'OXUNITQUANTITY'   => 'OXUNITQUANTITY',
            'OXEXTURL'         => 'OXEXTURL',
            'OXURLDESC'        => 'OXURLDESC',
            'OXURLIMG'         => 'OXURLIMG',
            'OXVAT'            => 'OXVAT',
            'OXTHUMB'          => 'OXTHUMB',
            'OXICON'         => 'OXICON',
            'OXPIC1'         => 'OXPIC1',
            'OXPIC2'         => 'OXPIC2',
            'OXPIC3'         => 'OXPIC3',
            'OXPIC4'         => 'OXPIC4',
            'OXPIC5'         => 'OXPIC5',
            'OXPIC6'         => 'OXPIC6',
            'OXPIC7'         => 'OXPIC7',
            'OXPIC8'         => 'OXPIC8',
            'OXPIC9'         => 'OXPIC9',
            'OXPIC10'         => 'OXPIC10',
            'OXPIC11'         => 'OXPIC11',
            'OXPIC12'         => 'OXPIC12',
            'OXZOOM1'         => 'OXZOOM1',
            'OXZOOM2'         => 'OXZOOM2',
            'OXZOOM3'         => 'OXZOOM3',
            'OXZOOM4'         => 'OXZOOM4',
            'OXWEIGHT'        => 'OXWEIGHT',
            'OXSTOCK'         => 'OXSTOCK',
            'OXSTOCKFLAG'     => 'OXSTOCKFLAG',
            'OXSTOCKTEXT'     => 'OXSTOCKTEXT',
            'OXNOSTOCKTEXT'   => 'OXNOSTOCKTEXT',
            'OXDELIVERY'     => 'OXDELIVERY',
            'OXINSERT'       => 'OXINSERT', //always now()
            'OXTIMESTAMP'    => 'OXTIMESTAMP',
            'OXLENGTH'       => 'OXLENGTH',
            'OXWIDTH'        => 'OXWIDTH',
            'OXHEIGHT'       => 'OXHEIGHT',
            'OXAKTION'       => 'OXAKTION',
            'OXFILE'         => 'OXFILE',
            'OXSEARCHKEYS'   => 'OXSEARCHKEYS',
            'OXTEMPLATE'     => 'OXTEMPLATE',
            'OXQUESTIONEMAIL'=> 'OXQUESTIONEMAIL',
            'OXISSEARCH'     => 'OXISSEARCH',
            'OXVARNAME'      => 'OXVARNAME',
            'OXVARSTOCK'     => 'OXVARSTOCK',
            'OXVARCOUNT'     => 'OXVARCOUNT',
            'OXVARSELECT'    => 'OXVARSELECT',
            'OXVARNAME_1'    => 'OXVARNAME_1',
            'OXVARSELECT_1'  => 'OXVARSELECT_1',
            'OXVARNAME_2'   =>'OXVARNAME_2',
            'OXVARSELECT_2' =>'OXVARSELECT_2',
            'OXVARNAME_3'   =>'OXVARNAME_3',
            'OXVARSELECT_3' =>'OXVARSELECT_3',
            'OXTITLE_1'     =>'OXTITLE_1',
            'OXSHORTDESC_1' =>'OXSHORTDESC_1',
            'OXLONGDESC_1'  =>'OXLONGDESC_1',
            'OXURLDESC_1'   =>'OXURLDESC_1',
            'OXSEARCHKEYS_1'=>'OXSEARCHKEYS_1',
            'OXTITLE_2'     =>'OXTITLE_2',
            'OXSHORTDESC_2' =>'OXSHORTDESC_2',
            'OXLONGDESC_2'  =>'OXLONGDESC_2',
            'OXURLDESC_2'   =>'OXURLDESC_2',
            'OXSEARCHKEYS_2'=>'OXSEARCHKEYS_2',
            'OXTITLE_3'     =>'OXTITLE_3',
            'OXSHORTDESC_3' =>'OXSHORTDESC_3',
            'OXLONGDESC_3'  =>'OXLONGDESC_3',
            'OXURLDESC_3'   =>'OXURLDESC_3',
            'OXSEARCHKEYS_3'=>'OXSEARCHKEYS_3',
            'OXFOLDER'      =>'OXFOLDER',
            'OXSUBCLASS'    =>'OXSUBCLASS',
            'OXSTOCKTEXT_1' =>'OXSTOCKTEXT_1',
            'OXSTOCKTEXT_2' =>'OXSTOCKTEXT_2',
            'OXSTOCKTEXT_3' =>'OXSTOCKTEXT_3',
            'OXNOSTOCKTEXT_1'=>'OXNOSTOCKTEXT_1',
            'OXNOSTOCKTEXT_2'=>'OXNOSTOCKTEXT_2',
            'OXNOSTOCKTEXT_3'=>'OXNOSTOCKTEXT_3',
            'OXSORT'         => 'OXSORT',
            'OXSOLDAMOUNT'   => 'OXSOLDAMOUNT',
            'OXNONMATERIAL'  => 'OXNONMATERIAL',
            'OXFREESHIPPING' => 'OXFREESHIPPING',
            'OXREMINDACTIV'  => 'OXREMINDACTIV',
            'OXREMINDAMOUNT' => 'OXREMINDAMOUNT',
            'OXAMITEMID'    =>'OXAMITEMID',
            'OXAMTASKID'    =>'OXAMTASKID',
            'OXVENDORID'    =>'OXVENDORID',
            'OXSKIPDISCOUNTS'=> 'OXSKIPDISCOUNTS',
            'OXORDERINFO'   =>'OXORDERINFO',
            'OXSEOID'       =>'OXSEOID',
            'OXSEOID_1'     =>'OXSEOID_1',
            'OXSEOID_2'     =>'OXSEOID_2',
            'OXSEOID_3'     =>'OXSEOID_3',
            'OXPIXIEXPORT'   => 'OXPIXIEXPORT',
            'OXPIXIEXPORTED' => 'OXPIXIEXPORTED',
            'OXVPE'          => 'OXVPE',
            'OXID'           => 'OXID',
        ),
        '2' => array(
            'OXID' => 'OXID',
            'OXSHOPID' => 'OXSHOPID',
            'OXSHOPINCL' => 'OXSHOPINCL',
            'OXSHOPEXCL' => 'OXSHOPEXCL',
            'OXPARENTID' => 'OXPARENTID',
            'OXACTIVE' => 'OXACTIVE',
            'OXACTIVEFROM' => 'OXACTIVEFROM',
            'OXACTIVETO' => 'OXACTIVETO',
            'OXARTNUM' => 'OXARTNUM',
            'OXEAN' => 'OXEAN',
            'OXDISTEAN' => 'OXDISTEAN',
            'OXTITLE' => 'OXTITLE',
            'OXSHORTDESC' => 'OXSHORTDESC',
            'OXPRICE' => 'OXPRICE',
            'OXBLFIXEDPRICE' => 'OXBLFIXEDPRICE',
            'OXPRICEA' => 'OXPRICEA',
            'OXPRICEB' => 'OXPRICEB',
            'OXPRICEC' => 'OXPRICEC',
            'OXBPRICE' => 'OXBPRICE',
            'OXTPRICE' => 'OXTPRICE',
            'OXUNITNAME' => 'OXUNITNAME',
            'OXUNITQUANTITY' => 'OXUNITQUANTITY',
            'OXEXTURL' => 'OXEXTURL',
            'OXURLDESC' => 'OXURLDESC',
            'OXURLIMG' => 'OXURLIMG',
            'OXVAT' => 'OXVAT',
            'OXTHUMB' => 'OXTHUMB',
            'OXICON' => 'OXICON',
            'OXPIC1' => 'OXPIC1',
            'OXPIC2' => 'OXPIC2',
            'OXPIC3' => 'OXPIC3',
            'OXPIC4' => 'OXPIC4',
            'OXPIC5' => 'OXPIC5',
            'OXPIC6' => 'OXPIC6',
            'OXPIC7' => 'OXPIC7',
            'OXPIC8' => 'OXPIC8',
            'OXPIC9' => 'OXPIC9',
            'OXPIC10' => 'OXPIC10',
            'OXPIC11' => 'OXPIC11',
            'OXPIC12' => 'OXPIC12',
            'OXZOOM1' => 'OXZOOM1',
            'OXZOOM2' => 'OXZOOM2',
            'OXZOOM3' => 'OXZOOM3',
            'OXZOOM4' => 'OXZOOM4',
            'OXWEIGHT' => 'OXWEIGHT',
            'OXSTOCK' => 'OXSTOCK',
            'OXSTOCKFLAG' => 'OXSTOCKFLAG',
            'OXSTOCKTEXT' => 'OXSTOCKTEXT',
            'OXNOSTOCKTEXT' => 'OXNOSTOCKTEXT',
            'OXDELIVERY' => 'OXDELIVERY',
            'OXINSERT' => 'OXINSERT',
            'OXTIMESTAMP' => 'OXTIMESTAMP',
            'OXLENGTH' => 'OXLENGTH',
            'OXWIDTH' => 'OXWIDTH',
            'OXHEIGHT' => 'OXHEIGHT',
            'OXFILE' => 'OXFILE',
            'OXSEARCHKEYS' => 'OXSEARCHKEYS',
            'OXTEMPLATE' => 'OXTEMPLATE',
            'OXQUESTIONEMAIL' => 'OXQUESTIONEMAIL',
            'OXISSEARCH' => 'OXISSEARCH',
            'OXVARNAME' => 'OXVARNAME',
            'OXVARSTOCK' => 'OXVARSTOCK',
            'OXVARCOUNT' => 'OXVARCOUNT',
            'OXVARSELECT' => 'OXVARSELECT',
            'OXVARMINPRICE' => 'OXVARMINPRICE',
            'OXVARNAME_1' => 'OXVARNAME_1',
            'OXVARSELECT_1' => 'OXVARSELECT_1',
            'OXVARNAME_2' => 'OXVARNAME_2',
            'OXVARSELECT_2' => 'OXVARSELECT_2',
            'OXVARNAME_3' => 'OXVARNAME_3',
            'OXVARSELECT_3' => 'OXVARSELECT_3',
            'OXTITLE_1' => 'OXTITLE_1',
            'OXSHORTDESC_1' => 'OXSHORTDESC_1',
            'OXURLDESC_1' => 'OXURLDESC_1',
            'OXSEARCHKEYS_1' => 'OXSEARCHKEYS_1',
            'OXTITLE_2' => 'OXTITLE_2',
            'OXSHORTDESC_2' => 'OXSHORTDESC_2',
            'OXURLDESC_2' => 'OXURLDESC_2',
            'OXSEARCHKEYS_2' => 'OXSEARCHKEYS_2',
            'OXTITLE_3' => 'OXTITLE_3',
            'OXSHORTDESC_3' => 'OXSHORTDESC_3',
            'OXURLDESC_3' => 'OXURLDESC_3',
            'OXSEARCHKEYS_3' => 'OXSEARCHKEYS_3',
            'OXFOLDER' => 'OXFOLDER',
            'OXSUBCLASS' => 'OXSUBCLASS',
            'OXSTOCKTEXT_1' => 'OXSTOCKTEXT_1',
            'OXSTOCKTEXT_2' => 'OXSTOCKTEXT_2',
            'OXSTOCKTEXT_3' => 'OXSTOCKTEXT_3',
            'OXNOSTOCKTEXT_1' => 'OXNOSTOCKTEXT_1',
            'OXNOSTOCKTEXT_2' => 'OXNOSTOCKTEXT_2',
            'OXNOSTOCKTEXT_3' => 'OXNOSTOCKTEXT_3',
            'OXSORT' => 'OXSORT',
            'OXSOLDAMOUNT' => 'OXSOLDAMOUNT',
            'OXNONMATERIAL' => 'OXNONMATERIAL',
            'OXFREESHIPPING' => 'OXFREESHIPPING',
            'OXREMINDACTIV' => 'OXREMINDACTIV',
            'OXREMINDAMOUNT' => 'OXREMINDAMOUNT',
            'OXAMITEMID' => 'OXAMITEMID',
            'OXAMTASKID' => 'OXAMTASKID',
            'OXVENDORID' => 'OXVENDORID',
            'OXSKIPDISCOUNTS' => 'OXSKIPDISCOUNTS',
            'OXORDERINFO' => 'OXORDERINFO',
            'OXPIXIEXPORT' => 'OXPIXIEXPORT',
            'OXPIXIEXPORTED' => 'OXPIXIEXPORTED',
            'OXVPE' => 'OXVPE',
            'OXRATING' => 'OXRATING',
            'OXRATINGCNT' => 'OXRATINGCNT',
            'OXLONGDESC'       => 'OXLONGDESC',
            'OXLONGDESC_1'     => 'OXLONGDESC_1',
            'OXLONGDESC_2'     => 'OXLONGDESC_2',
            'OXLONGDESC_3'     => 'OXLONGDESC_3',
        ),
    );

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {

        parent::__construct();

        $this->_sTableName      = 'oxarticles';
        $this->_sShopObjectName = 'oxarticle';

    }

    /**
     * return sql column name of given table column
     *
     * @param string $sField    object field anme
     * @param int    $iLanguage language id
     * @param int    $iShopID   shop id
     *
     * @return string
     */
    protected function getSqlFieldName($sField, $iLanguage = 0, $iShopID = 1)
    {
        switch ($sField) {
            // oxlongdesc is valid in all versions
            case 'OXLONGDESC':
            case 'OXLONGDESC_1':
            case 'OXLONGDESC_2':
            case 'OXLONGDESC_3':
                // take from oxartextends
                return "(select $sField from oxartextends where ".$this->getTableName($iShopID).".oxid = oxartextends.oxid limit 1) as $sField";
        }

        if ('1' == oxERPBase::getUsedDbFieldsVersion()) {
            switch ($sField) {
                case 'OXAKTION':
                case 'OXSEOID':
                case 'OXSEOID_1':
                case 'OXSEOID_2':
                case 'OXSEOID_3':
                    return "'' as $sField";
                case 'OXACTIV':
                    return "OXACTIVE as OXACTIV";
                case 'OXACTIVFROM':
                    return "OXACTIVEFROM as OXACTIVFROM";
                case 'OXACTIVTO':
                    return "OXACTIVETO as OXACTIVTO";
            }
        }

        return parent::getSqlFieldName($sField, $iLanguage, $iShopID);
    }

    /**
     * issued before saving an object. Includes fix for multilanguage article long description saving
     *
     * @param oxBase $oShopObject         shop object
     * @param array  $aData               data used in assign
     * @param bool   $blAllowCustomShopId if TRUE - custom shop id is allowed
     *
     * @return array
     */
    protected function _preAssignObject($oShopObject, $aData, $blAllowCustomShopId)
    {
        $oCompat = oxNew('OXERPCompatability');
        if ( !$oCompat->isArticleNullLongDescComatable() ) {

            $aLongDescriptionFields = array('OXLONGDESC_1','OXLONGDESC_2','OXLONGDESC_3');

            foreach ( $aLongDescriptionFields as $iKey => $sField ) {
                if ( in_array($sField,$this->_aFieldList ) ) {
                    unset($aLongDescriptionFields[$iKey]);
                }
            }

            if ( count($aLongDescriptionFields ) ) {
                $oArtExt = oxNew('oxbase');
                $oArtExt->init('oxartextends');

                if ( $oArtExt->load($aData['OXID']) ) {
                    foreach ($aLongDescriptionFields as $sField) {
                        $sFieldName = $oArtExt->getCoreTableName()."__".strtolower( $sField );
                        $sLongDesc  = null;

                        if ($oArtExt->$sFieldName instanceof oxField) {
                            $sLongDesc = $oArtExt->$sFieldName->getRawValue();
                        } elseif (is_object($oArtExt->$sFieldName)) {
                            $sLongDesc = $oArtExt->$sFieldName->value;
                        }

                        if ( isset($sLongDesc) ) {
                            $aData[$sField] = $sLongDesc;
                        }
                    }
                }
            }
        }

        return parent::_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);
    }
}
