<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty modifier plugin
 *
 * Type:     modifier<br>
 * Name:     oxaddslashes<br>
 * Purpose:  Quote string with slashes
 *
 * @param string
 * @return string
 */
function smarty_modifier_oxaddslashes( $string )
{
    return addslashes( $string );
}