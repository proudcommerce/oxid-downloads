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
 * $Id: tags.php 16306 2009-02-05 10:28:05Z rimvydas.paskevicius $
 */

/**
 * Shows bigger tag cloud
 */
class Tags extends oxUBase
{
    /**
     * Class template
     *
     * @var string
     */
    protected $_sThisTemplate = "tags.tpl";

    /**
     * Executes parent::render(), loads article list according active tag
     *
     * Template variables:
     * <b>articlelist</b>, <b>pageNavigation</b>, <b>subcatlist</b>,
     * <b>meta_keywords</b>, <b>meta_description</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['tagCloud'] = $this->getTagCloud();
        $this->_aViewData['blMoreTags'] = $this->isMoreTagsVisible();
        $this->_aViewData['oView'] = $this;

        return $this->_sThisTemplate;
    }

    /**
     * Get HTML formated tag cloud.
     *
     * @return string
     */
    public function getTagCloud()
    {
        $oTagHandler = oxNew('oxTagCloud');
        return $oTagHandler->getTagCloud(null, true);
    }

    /**
     * Should "More tags" link be visible.
     *
     * @return bool
     */
    public function isMoreTagsVisible()
    {
        return false;
    }

    /**
     * Returns SEO suffix for page title
     *
     * @return string
     */
    public function getTitleSuffix()
    {
    }
}
