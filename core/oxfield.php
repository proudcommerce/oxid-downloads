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
 * $Id: oxfield.php 18029 2009-04-09 11:34:25Z arvydas $
 */

/**
 * Database field description object.
 * @package core
 */
class oxField // extends oxSuperCfg
{
    const T_TEXT = 1;
    const T_RAW  = 2;

    /**
     * Constructor
     * Initial value assigment is coded here by not calling a function is for performance
     * because oxField is created MANY times and even a function call matters
     *
     * @param mixed $value Field value
     * @param int   $type  Value type
     *
     * @return null
     */
    public function __construct($value = null, $type = self::T_TEXT)
    {
        // duplicate content here is needed for performance.
        // as this function is called *many* (a lot) times, it is crucial to be fast here!
        switch ($type) {
            case self::T_TEXT:
            default:
                $this->rawValue = $value;
                break;
            case self::T_RAW:
                $this->value = $value;
                break;
        }
    }

    /**
     * Checks if $name is set
     *
     * @param string $sName Variable name
     *
     * @return boolean
     */
    public function __isset( $sName )
    {
        switch ( $sName ) {
            case 'rawValue':
                return ($this->rawValue !== null);
            case 'value':
                return ($this->value !== null);
                //return true;
        }
        return false;
    }

    /**
     * Magic getter
     *
     * @param string $sName Variable name
     *
     * @return string | null
     */
    public function __get( $sName )
    {
        switch ( $sName ) {
            case 'rawValue':
                return $this->value;
            case 'value':
                if (is_string($this->rawValue)) {
                    $this->value = getStr()->htmlspecialchars( $this->rawValue );
                } else {
                    // TODO: call htmlentities for each (recursive ???)
                    $this->value = $this->rawValue;
                }
                if ($this->rawValue == $this->value) {
                    unset($this->rawValue);
                }
                return $this->value;
            default:
                return null;
        }
    }

    /**
     * TODO: remove this
     *
     * @return unknown
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Converts to formatted db date
     *
     * @return null
     */
    public function convertToFormattedDbDate()
    {
        $this->setValue(oxUtilsDate::getInstance()->formatDBDate( $this->rawValue ), self::T_RAW);
    }

    /**
     * Converts to pseudo html - new lines to <br /> tags
     *
     * @return null
     */
    public function convertToPseudoHtml()
    {
        $this->setValue( str_replace( "\r", '', nl2br( getStr()->htmlspecialchars( $this->rawValue ) ) ), self::T_RAW );
    }

    /**
     * Initila field value
     *
     * @param mixed $value Field value
     * @param int   $type  Value type
     *
     * @return null
     */
    protected function _initValue( $value = null, $type = self::T_TEXT)
    {
        switch ($type) {
            case self::T_TEXT:
                $this->rawValue = $value;
                break;
            case self::T_RAW:
                $this->value = $value;
                break;
        }
    }

    /**
     * Sets field value and type
     *
     * @param mixed $value Field value
     * @param int   $type  Value type
     *
     * @return null
     */
    public function setValue($value = null, $type = self::T_TEXT)
    {
        unset($this->rawValue);
        unset($this->value);
        $this->_initValue($value, $type);
    }

    /**
     * Return raw value
     *
     * @return string
     */
    public function getRawValue()
    {
        if (null === $this->rawValue) {
            return $this->value;
        };
        return $this->rawValue;
    }
}
