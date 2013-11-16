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
 * @version   SVN: $Id: oxerptype_orderarticle.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

require_once 'oxerptype.php';

/**
 * ERP order article description class
 */
class oxERPType_OrderArticle extends oxERPType
{
    /**
     * object fields description
     * @var array
     */
    protected $_aFieldListVersions = array(
        '1' => array(
            'OXID'          => 'OXID',
            'OXORDERID'     => 'OXORDERID',
            'OXAMOUNT'      => 'OXAMOUNT',
            'OXARTID'       => 'OXARTID',
            'OXARTNUM'      => 'OXARTNUM',
            'OXTITLE'       => 'OXTITLE',
            'OXSHORTDESC'   => 'OXSHORTDESC',
            'OXSELVARIANT'  => 'OXSELVARIANT',
            'OXNETPRICE'    => 'OXNETPRICE',
            'OXBRUTPRICE'   => 'OXBRUTPRICE',
            'OXVAT'         => 'OXVAT',
            'OXPERSPARAM'   => 'OXPERSPARAM', //that value is a array, therefore it can be added in the way val1,val2 etc. (its written serialized in the db)
            'OXPRICE'       => 'OXPRICE',
            'OXBPRICE'      => 'OXBPRICE',
            'OXTPRICE'      => 'OXTPRICE',
            'OXWRAPID'      => 'OXWRAPID',
            'OXEXTURL'      => 'OXEXTURL',
            'OXURLDESC'     => 'OXURLDESC',
            'OXURLIMG'      => 'OXURLIMG',
            'OXTHUMB'      => 'OXTHUMB',
            'OXPIC1'        => 'OXPIC1',
            'OXPIC2'        => 'OXPIC2',
            'OXPIC3'        => 'OXPIC3',
            'OXPIC4'        => 'OXPIC4',
            'OXPIC5'        => 'OXPIC5',
            'OXWEIGHT'      => 'OXWEIGHT',
            'OXSTOCK'       => 'OXSTOCK',
            'OXDELIVERY'    => 'OXDELIVERY',
            'OXINSERT'      => 'OXINSERT',
            'OXTIMESTAMP'   => 'OXTIMESTAMP',
            'OXLENGTH'      => 'OXLENGTH',
            'OXWIDTH'       => 'OXWIDTH',
            'OXHEIGHT'      => 'OXHEIGHT',
            'OXAKTION'      => 'OXAKTION',
            'OXFILE'        => 'OXFILE',
            'OXSEARCHKEYS'  => 'OXSEARCHKEYS',
            'OXTEMPLATE'    => 'OXTEMPLATE',
            'OXQUESTIONEMAIL'=> 'OXQUESTIONEMAIL',
            'OXISSEARCH'    => 'OXISSEARCH',
            'OXFOLDER'      => 'OXFOLDER',
            'OXSUBCLASS'    => 'OXSUBCLASS',
            'OXSTORNO'      => 'OXSTORNO',
            'OXORDERSHOPID' => 'OXORDERSHOPID',
            'OXTOTALVAT'    => 'OXTOTALVAT',
            'OXERPSTATUS'   => 'OXERPSTATUS'
        ),
        '2' => array(
            'OXID' => 'OXID',
            'OXORDERID' => 'OXORDERID',
            'OXAMOUNT' => 'OXAMOUNT',
            'OXARTID' => 'OXARTID',
            'OXARTNUM' => 'OXARTNUM',
            'OXTITLE' => 'OXTITLE',
            'OXSHORTDESC' => 'OXSHORTDESC',
            'OXSELVARIANT' => 'OXSELVARIANT',
            'OXNETPRICE' => 'OXNETPRICE',
            'OXBRUTPRICE' => 'OXBRUTPRICE',
            'OXVATPRICE' => 'OXVATPRICE',
            'OXVAT' => 'OXVAT',
            'OXPERSPARAM' => 'OXPERSPARAM',
            'OXPRICE' => 'OXPRICE',
            'OXBPRICE' => 'OXBPRICE',
            'OXNPRICE' => 'OXNPRICE',
            'OXWRAPID' => 'OXWRAPID',
            'OXEXTURL' => 'OXEXTURL',
            'OXURLDESC' => 'OXURLDESC',
            'OXURLIMG' => 'OXURLIMG',
            'OXTHUMB' => 'OXTHUMB',
            'OXPIC1' => 'OXPIC1',
            'OXPIC2' => 'OXPIC2',
            'OXPIC3' => 'OXPIC3',
            'OXPIC4' => 'OXPIC4',
            'OXPIC5' => 'OXPIC5',
            'OXWEIGHT' => 'OXWEIGHT',
            'OXSTOCK' => 'OXSTOCK',
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
            'OXFOLDER' => 'OXFOLDER',
            'OXSUBCLASS' => 'OXSUBCLASS',
            'OXSTORNO' => 'OXSTORNO',
            'OXORDERSHOPID' => 'OXORDERSHOPID',
            'OXERPSTATUS' => 'OXERPSTATUS',
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

        $this->_sTableName = 'oxorderarticles';
        $this->_sShopObjectName = 'oxorderarticle';
    }

    /**
     * Returns formattted sql
     *
     * @param object $sWhere    where condition
     * @param object $iLanguage active language [optional]
     * @param object $iShopID   shop id [optional]
     *
     * @return string
     */
    public function getSQL( $sWhere, $iLanguage = 0, $iShopID = 1)
    {
        $oStr = getStr();
        if ( $oStr->strstr( $sWhere, 'where')) {
            $sWhere .= ' and ';
        } else {
            $sWhere .= ' where ';
        }

        $sWhere .= 'oxordershopid = \''.$iShopID.'\'';
        return parent::getSQL( $sWhere, $iLanguage, $iShopID );
    }

    /**
     * Checks for write access. If access is not granted exception is thrown
     *
     * @param object $sOxid object id
     *
     * @return null
     */
    public function checkWriteAccess($sOxid)
    {
        $myConfig = oxConfig::getInstance();

        $oDB = oxDb::getDb();

        $sSql = "select oxordershopid from ". $this->getTableName($myConfig->getShopId()) ." where oxid = ".$oDB->quote( $sOxid );
        $sRes = $oDB->getOne($sSql);

        if ( $sRes && $sRes != $myConfig->getShopId() ) {
            throw new Exception( oxERPBase::$ERROR_USER_NO_RIGHTS );
        }
    }

    /**
     * return sql column name of given table column
     *
     * @param string $sField    field name
     * @param int    $iLanguage language id
     * @param int    $iShopID   shop id
     *
     * @return string
     */
    protected function getSqlFieldName($sField, $iLanguage = 0, $iShopID = 1)
    {
        if ('1' == oxERPBase::getUsedDbFieldsVersion()) {
            switch ($sField) {
                case 'OXTOTALVAT':
                    // We need to round this value here
                    return "round(OXVATPRICE * OXAMOUNT, 5) as OXTOTALVAT";
                    break;
                case 'OXTPRICE':
                case 'OXAKTION':
                    return "'' as $sField";
                    break;
            }
        }
        return parent::getSqlFieldName($sField, $iLanguage, $iShopID);
    }

    /**
     * We have the possibility to add some data
     *
     * @param array $aFields fields to export
     *
     * @return array
     */
    public function addExportData($aFields)
    {
        if ( isset( $aFields['OXTOTALVAT'] ) ) {
            // And we need to cast this value here, to remove trailing zeroes added after mysql round
            $aFields['OXTOTALVAT'] = (double) $aFields['OXTOTALVAT'];
        }

        if ( strlen( $aFields['OXPERSPARAM'] ) ) {
            $aPersVals = @unserialize($aFields['OXPERSPARAM']);
            if ( is_array( $aPersVals ) ) {
                $aFields['OXPERSPARAM'] = implode( '|', $aPersVals );
            }
        }
        return $aFields;
    }


    /**
     * issued before saving an object. can modify aData for saving
     *
     * @param oxBase $oShopObject         shop object
     * @param array  $aData               data to assign
     * @param bool   $blAllowCustomShopId if true - custom shop id allowed
     *
     * @return array
     */
    protected function _preAssignObject($oShopObject, $aData, $blAllowCustomShopId)
    {
        $aData = parent::_preAssignObject($oShopObject, $aData, $blAllowCustomShopId);
        if ('1' == oxERPBase::getUsedDbFieldsVersion()) {
            $oDb = oxDb::getDb();
            if ($aData['OXAMOUNT']) {
                $aData['OXVATPRICE'] = $aData['OXTOTALVAT'] / $aData['OXAMOUNT'];
            }
        }

        // check if data is not serialized
        $aPersVals = @unserialize($aData['OXPERSPARAM']);
        if (!is_array($aPersVals)) {
            // data is a string with | separation, prepare for oxid
            $aPersVals = explode( "|", $aData['OXPERSPARAM']);
            $aData['OXPERSPARAM'] = serialize($aPersVals);
        }

        return $aData;
    }
}
