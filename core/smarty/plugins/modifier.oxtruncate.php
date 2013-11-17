<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * This method replaces existing Smarty function for truncating strings
 * (check Smarty documentation for details). When truncating strings
 * additionally we need to convert &#039;/&quot; entities to '/"
 * and after truncating convert them back.
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function smarty_modifier_oxtruncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0) {
        return '';
    } elseif ( $length > 0 && getStr()->strlen( $string ) > $length ) {
        $length -= getStr()->strlen( $etc );

        $string = str_replace( array('&#039;', '&quot;'), array( "'",'"' ), $string );

        if ( !$break_words ) {
            $string = getStr()->preg_replace( '/\s+?(\S+)?$/', '', getStr()->substr( $string, 0, $length + 1 ) );
        }

        $string = getStr()->substr( $string, 0, $length ).$etc;

        return str_replace( array( "'",'"' ), array('&#039;', '&quot;'), $string );
    }

    return $string;
}

/* vim: set expandtab: */

?>
