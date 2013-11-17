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
 * $Id: oxerptype_artextends.php 23188 2009-10-13 06:59:38Z sarunas $
 */

require_once 'oxerptype.php';

/**
 * ERP article extended data description class
 */
class oxERPType_Artextends extends oxERPType
{
    /**
     * object fields description
     * @var array
     */
    protected $_aFieldListVersions = array (
        '2' => array(
            'OXID' => 'OXID',
            'OXLONGDESC'       => 'OXLONGDESC',
            'OXLONGDESC_1'     => 'OXLONGDESC_1',
            'OXLONGDESC_2'     => 'OXLONGDESC_2',
            'OXLONGDESC_3'     => 'OXLONGDESC_3',
            'OXTAGS'           => 'OXTAGS',
            'OXTAGS_1'         => 'OXTAGS_1',
            'OXTAGS_2'         => 'OXTAGS_2',
            'OXTAGS_3'         => 'OXTAGS_3',
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

        $this->_sTableName      = 'oxartextends';

        if (oxERPBase::getRequestedVersion() < 2) {
            $this->_sTableName = '';
            $this->_aFieldList = array();
        }
    }
}
