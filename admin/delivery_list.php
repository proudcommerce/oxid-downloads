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
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: delivery_list.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * Admin delivery list manager.
 * Collects delivery base information (description), there is ability to
 * filter them by description, title or delete them.
 * Admin Menu: Shop Settings -> Shipping & Handling.
 * @package admin
 */
class Delivery_List extends oxAdminList
{
    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxdelivery';

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'oxdeliverylist';

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'delivery_list.tpl';

    /**
     * Sets SQL query parameters (such as sorting),
     * executes parent method parent::Init().
     *
     * @return null
     */
    public function init()
    {
        $sViewName = getViewName('oxdelivery');
        $this->_sDefSort = "$sViewName.oxsort";
        parent::Init();
    }
}
