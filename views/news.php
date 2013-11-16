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
 * @version   SVN: $Id: news.php 26736 2010-03-22 13:40:09Z sarunas $
 */

/**
 * Shop news window.
 * Arranges news texts. OXID eShop -> (click on News box on left side).
 */
class News extends oxUBase
{
    /**
     * Newslist
     * @var object
     */
    protected $_oNewsList = null;

    /**
     * Current class login template name.
     * @var string
     */
    protected $_sThisTemplate = 'news.tpl';

    /**
     * Sign if to load and show top5articles action
     * @var bool
     */
    protected $_blTop5Action = true;

    /**
     * Sign if to load and show bargain action
     * @var bool
     */
    protected $_blBargainAction = true;

    /**
     * Loads news list oxnewslist::LoadNews(), action articles,
     * executes parent::render() and returns name of template
     * file to render news::_sThisTemplate.
     *
     * Template variables:
     * <b>news</b>
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        parent::render();

        $this->_aViewData['news'] = $this->getNews();

        // loading actions
        $this->_loadActions();

        return $this->_sThisTemplate;
    }

    /**
     * Template variable getter. Returns newslist
     *
     * @return object
     */
    public function getNews()
    {
        if ( $this->_oNewsList === null ) {
            $this->_oNewsList = false;
            $oActNews = oxNew( 'oxnewslist' );
            $oActNews->loadNews();
            if ( count($oActNews) ) {
                $this->_oNewsList = $oActNews;
            }
        }
        return $this->_oNewsList;
    }

}
