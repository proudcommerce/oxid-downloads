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
 * @version   SVN: $Id: oxerptype_order.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

require_once 'oxerptype.php';

/**
 * ERP order description class
 */
class oxERPType_Order extends oxERPType
{
    /**
     * object fields description
     * @var array
     */
    protected $_aFieldListVersions = array(
        '1' => array(
            'OXID'           => 'OXID',
            'OXSHOPID'       => 'OXSHOPID',
            'OXUSERID'       => 'OXUSERID',
            'OXORDERDATE'    => 'OXORDERDATE', //always now()
            'OXORDERNR'      => 'OXORDERNR',
            'OXBILLCOMPANY'  => 'OXBILLCOMPANY',
            'OXBILLEMAIL'    => 'OXBILLEMAIL',
            'OXBILLFNAME'    => 'OXBILLFNAME',
            'OXBILLLNAME'    => 'OXBILLLNAME',
            'OXBILLSTREET'   => 'OXBILLSTREET',
            'OXBILLSTREETNR' => 'OXBILLSTREETNR',
            'OXBILLADDINFO'  => 'OXBILLADDINFO',
            'OXBILLUSTID'    => 'OXBILLUSTID',
            'OXBILLCITY'     => 'OXBILLCITY',
            'OXBILLCOUNTRY'  => 'OXBILLCOUNTRY',
            'OXBILLZIP'      => 'OXBILLZIP',
            'OXBILLFON'      => 'OXBILLFON',
            'OXBILLFAX'      => 'OXBILLFAX',
            'OXBILLSAL'      => 'OXBILLSAL',
            'OXDELCOMPANY'   => 'OXDELCOMPANY',
            'OXDELFNAME'     => 'OXDELFNAME',
            'OXDELLNAME'     => 'OXDELLNAME',
            'OXDELSTREET'    => 'OXDELSTREET',
            'OXDELSTREETNR'  => 'OXDELSTREETNR',
            'OXDELADDINFO'   => 'OXDELADDINFO',
            'OXDELCITY'      => 'OXDELCITY',
            'OXDELCOUNTRY'   => 'OXDELCOUNTRY',
            'OXDELZIP'       => 'OXDELZIP',
            'OXDELFON'       => 'OXDELFON',
            'OXDELFAX'       => 'OXDELFAX',
            'OXDELSAL'       => 'OXDELSAL',
            'OXPAYMENTID'    => 'OXPAYMENTID',
            'OXPAYMENTTYPE'  => 'OXPAYMENTTYPE',
            'OXDELCOST'      => 'OXDELCOST',
            'OXDELVAT'       => 'OXDELVAT',
            'OXPAYCOST'      => 'OXPAYCOST',
            'OXPAYVAT'       => 'OXPAYVAT',
            'OXWRAPCOST'     => 'OXWRAPCOST',
            'OXWRAPVAT'      => 'OXWRAPVAT',
            'OXCARDID'       => 'OXCARDID',
            'OXCARDTEXT'     => 'OXCARDTEXT',
            'OXDISCOUNT'     => 'OXDISCOUNT',
            'OXEXPORT'       => 'OXEXPORT',
            'OXBILLNR'       => 'OXBILLNR',
            'OXTRACKCODE'    => 'OXTRACKCODE',
            'OXSENDDATE'     => 'OXSENDDATE',
            'OXREMARK'       => 'OXREMARK',
            'OXVOUCHERDISCOUNT'      => 'OXVOUCHERDISCOUNT',
            'OXCURRENCY'     => 'OXCURRENCY',
            'OXCURRATE'      => 'OXCURRATE',
            'OXFOLDER'       => 'OXFOLDER', //also not possible to set from external
            'OXPIDENT'       => 'OXPIDENT',
            'OXTRANSID'      => 'OXTRANSID',
            'OXPAYID'        => 'OXPAYID',
            'OXXID'          => 'OXXID',
            'OXPAID'         => 'OXPAID',
            'OXSTORNO'       => 'OXSTORNO',
            'OXIP'           => 'OXIP',
            'OXTRANSSTATUS'  => 'OXTRANSSTATUS',
            'OXLANG'         => 'OXLANG',
            'OXINVOICENR'    => 'OXINVOICENR',
            'OXPHAPPROVED'   => 'OXPHAPPROVED',
            'OXPHISPHORDER'  => 'OXPHISPHORDER',
            'OXDELTYPE'      => 'OXDELTYPE',
            'OXPIXIEXPORT'   => 'OXPIXIEXPORT',
        ),
        '2' => array(
            'OXID' => 'OXID',
            'OXSHOPID' => 'OXSHOPID',
            'OXUSERID' => 'OXUSERID',
            'OXORDERDATE' => 'OXORDERDATE',
            'OXORDERNR' => 'OXORDERNR',
            'OXBILLCOMPANY' => 'OXBILLCOMPANY',
            'OXBILLEMAIL' => 'OXBILLEMAIL',
            'OXBILLFNAME' => 'OXBILLFNAME',
            'OXBILLLNAME' => 'OXBILLLNAME',
            'OXBILLSTREET' => 'OXBILLSTREET',
            'OXBILLSTREETNR' => 'OXBILLSTREETNR',
            'OXBILLADDINFO' => 'OXBILLADDINFO',
            'OXBILLUSTID' => 'OXBILLUSTID',
            'OXBILLUSTIDSTATUS' => 'OXBILLUSTIDSTATUS',
            'OXBILLCITY' => 'OXBILLCITY',
            'OXBILLCOUNTRYID' => 'OXBILLCOUNTRYID',
            'OXBILLZIP' => 'OXBILLZIP',
            'OXBILLFON' => 'OXBILLFON',
            'OXBILLFAX' => 'OXBILLFAX',
            'OXBILLSAL' => 'OXBILLSAL',
            'OXDELCOMPANY' => 'OXDELCOMPANY',
            'OXDELFNAME' => 'OXDELFNAME',
            'OXDELLNAME' => 'OXDELLNAME',
            'OXDELSTREET' => 'OXDELSTREET',
            'OXDELSTREETNR' => 'OXDELSTREETNR',
            'OXDELADDINFO' => 'OXDELADDINFO',
            'OXDELCITY' => 'OXDELCITY',
            'OXDELCOUNTRYID' => 'OXDELCOUNTRYID',
            'OXDELZIP' => 'OXDELZIP',
            'OXDELFON' => 'OXDELFON',
            'OXDELFAX' => 'OXDELFAX',
            'OXDELSAL' => 'OXDELSAL',
            'OXPAYMENTID' => 'OXPAYMENTID',
            'OXPAYMENTTYPE' => 'OXPAYMENTTYPE',
            'OXTOTALNETSUM' => 'OXTOTALNETSUM',
            'OXTOTALBRUTSUM' => 'OXTOTALBRUTSUM',
            'OXTOTALORDERSUM' => 'OXTOTALORDERSUM',
            'OXDELCOST' => 'OXDELCOST',
            'OXDELVAT' => 'OXDELVAT',
            'OXPAYCOST' => 'OXPAYCOST',
            'OXPAYVAT' => 'OXPAYVAT',
            'OXWRAPCOST' => 'OXWRAPCOST',
            'OXWRAPVAT' => 'OXWRAPVAT',
            'OXCARDID' => 'OXCARDID',
            'OXCARDTEXT' => 'OXCARDTEXT',
            'OXDISCOUNT' => 'OXDISCOUNT',
            'OXEXPORT' => 'OXEXPORT',
            'OXBILLNR' => 'OXBILLNR',
            'OXTRACKCODE' => 'OXTRACKCODE',
            'OXSENDDATE' => 'OXSENDDATE',
            'OXREMARK' => 'OXREMARK',
            'OXVOUCHERDISCOUNT' => 'OXVOUCHERDISCOUNT',
            'OXCURRENCY' => 'OXCURRENCY',
            'OXCURRATE' => 'OXCURRATE',
            'OXFOLDER' => 'OXFOLDER',
            'OXPIDENT' => 'OXPIDENT',
            'OXTRANSID' => 'OXTRANSID',
            'OXPAYID' => 'OXPAYID',
            'OXXID' => 'OXXID',
            'OXPAID' => 'OXPAID',
            'OXSTORNO' => 'OXSTORNO',
            'OXIP' => 'OXIP',
            'OXTRANSSTATUS' => 'OXTRANSSTATUS',
            'OXLANG' => 'OXLANG',
            'OXINVOICENR' => 'OXINVOICENR',
            'OXDELTYPE' => 'OXDELTYPE',
            'OXPIXIEXPORT' => 'OXPIXIEXPORT',
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

        $this->_sTableName = 'oxorder';
        $this->_sShopObjectName = 'oxorder';
        $this->_blRestrictedByShopId = true;
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
        if ('1' == oxERPBase::getUsedDbFieldsVersion()) {
            switch ($sField) {
                case 'OXBILLCOUNTRY':
                    return "(select oxtitle from oxcountry where oxcountry.oxid = OXBILLCOUNTRYID limit 1) as OXBILLCOUNTRY";
                    break;
                case 'OXDELCOUNTRY':
                    return "(select oxtitle from oxcountry where oxcountry.oxid = OXDELCOUNTRYID limit 1) as OXDELCOUNTRY";
                    break;
                case 'OXPHAPPROVED':
                case 'OXPHISPHORDER':
                    return "'' as $sField";
                    break;
            }
        }
        return parent::getSqlFieldName($sField, $iLanguage, $iShopID);
    }

    /**
     * issued before saving an object. can modify aData for saving
     *
     * @param oxBase $oShopObject         shop object
     * @param array  $aData               data used in assign
     * @param bool   $blAllowCustomShopId if TRUE - custom shop id is allowed
     *
     * @return array
     */
    protected function _preAssignObject($oShopObject, $aData, $blAllowCustomShopId)
    {
        $aData = parent::_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);

        if ('1' == oxERPBase::getUsedDbFieldsVersion()) {
            $oDb = oxDb::getDb();
            $sTitle = mysql_real_escape_string($aData['OXBILLCOUNTRY']);
            if ($id = $oDb->getOne("select oxid from oxcountry where oxtitle = '$sTitle' limit 1")) {
                $aData['OXBILLCOUNTRYID'] = $id;
            }
            $sTitle = mysql_real_escape_string($aData['OXDELCOUNTRY']);
            if ($id = $oDb->getOne("select oxid from oxcountry where oxtitle = '$sTitle' limit 1")) {
                $aData['OXDELCOUNTRYID'] = $id;
            }
        }
        return $aData;
    }

    /**
     * post saving hook. can finish transactions if needed or ajust related data
     *
     * @param oxBase $oShopObject shop object
     * @param data   $aData       saved object data
     *
     * @return mixed data to return
     */
    protected function _postSaveObject($oShopObject, $aData)
    {
        if ('1' == oxERPBase::getRequestedVersion()) {
            if (!$aData['OXORDERNR']) {
                // version 1 erp did NOT have order number ajustments, for using them, use 1.1 version
                if ($sOxid = $oShopObject->getId()) {
                    oxDb::getDb()->Execute("update oxorder set oxordernr=0 where oxid='$sOxid' limit 1");
                }
            }
        } elseif ( '1.1' == oxERPBase::getRequestedVersion() ) {
            return array('OXID'=>$oShopObject->getId(), 'OXORDERNR'=>$oShopObject->oxorder__oxordernr->value);
        }
        return parent::_postSaveObject($oShopObject, $aData);
    }
}
