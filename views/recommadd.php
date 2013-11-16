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
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: recommadd.php 25466 2010-02-01 14:12:07Z alfonsas $
 */

/**
 * Handles adding article to recommendation list process.
 * Due to possibility of external modules we recommned to extend the vews from oxUBase view.
 * However expreimentally we extend RecommAdd from Details view here.
 */
class RecommAdd extends Details
{
    /**
     * Template name
     *
     * @var string
     */
    protected $_sThisTemplate = 'recommadd.tpl';

    /**
     * User recommendation lists
     *
     * @var array
     */
    protected $_aUserRecommList = null;

    /**
     * Executes oxUBase::init()
     *
     * @return null
     */
    public function init()
    {
        oxUBase::init();
    }

    /**
     * Returns user recommlists
     *
     * @return array
     */
    public function getRecommLists()
    {
        if ( $this->_aUserRecommList === null ) {
            $oUser   = $this->getUser();
            if ( $oUser) {
                $this->_aUserRecommList = $oUser->getUserRecommLists();
            }
        }
        return $this->_aUserRecommList;
    }


}
