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
 * $Id: oxerptype_country.php 20535 2009-06-30 00:58:33Z alfonsas $
 */

require_once 'oxerptype.php';

/**
 * ERP country description class
 */
class oxERPType_Country extends oxERPType
{
    /**
     * object fields description
     * @var array
     */
    protected $_aFieldListVersions = array(
        '1' => array(
            'OXID'           => 'OXID',
            'OXACTIV'       => 'OXACTIV',
            'OXTITLE'       => 'OXTITLE',
            'OXISOALPHA2'   => 'OXISOALPHA2',
            'OXISOALPHA3'   => 'OXISOALPHA3',
            'OXUNNUM3'      => 'OXUNNUM3',
            'OXORDER'       => 'OXORDER',
            'OXSHORTDESC'   => 'OXSHORTDESC',
            'OXLONGDESC'    => 'OXLONGDESC',
            'OXTITLE_1'     => 'OXTITLE_1',
            'OXTITLE_2'     => 'OXTITLE_2',
            'OXTITLE_3'     => 'OXTITLE_3',
            'OXSHORTDESC_1' => 'OXSHORTDESC_1',
            'OXSHORTDESC_2' => 'OXSHORTDESC_2',
            'OXSHORTDESC_3' => 'OXSHORTDESC_3',
            'OXLONGDESC_1'  => 'OXLONGDESC_1',
            'OXLONGDESC_2'  => 'OXLONGDESC_2',
            'OXLONGDESC_3'  => 'OXLONGDESC_3'
         ),
        '2' => array(
            'OXID' => 'OXID',
            'OXACTIVE' => 'OXACTIVE',
            'OXTITLE' => 'OXTITLE',
            'OXISOALPHA2' => 'OXISOALPHA2',
            'OXISOALPHA3' => 'OXISOALPHA3',
            'OXUNNUM3' => 'OXUNNUM3',
            'OXORDER' => 'OXORDER',
            'OXSHORTDESC' => 'OXSHORTDESC',
            'OXLONGDESC' => 'OXLONGDESC',
            'OXTITLE_1' => 'OXTITLE_1',
            'OXTITLE_2' => 'OXTITLE_2',
            'OXTITLE_3' => 'OXTITLE_3',
            'OXSHORTDESC_1' => 'OXSHORTDESC_1',
            'OXSHORTDESC_2' => 'OXSHORTDESC_2',
            'OXSHORTDESC_3' => 'OXSHORTDESC_3',
            'OXLONGDESC_1' => 'OXLONGDESC_1',
            'OXLONGDESC_2' => 'OXLONGDESC_2',
            'OXLONGDESC_3' => 'OXLONGDESC_3',
            'OXVATSTATUS' => 'OXVATSTATUS'
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

        $this->_sTableName = 'oxcountry';
        $this->_sShopObjectName = 'oxcountry';
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
                case 'OXACTIV':
                    return "OXACTIVE as OXACTIV";
                    break;
            }
        }
        return parent::getSqlFieldName($sField, $iLanguage, $iShopID);
    }
}
