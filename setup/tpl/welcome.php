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
 * @package   setup
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: lang.php 25584 2010-02-03 12:11:40Z arvydas $
 */
require "_header.php"; ?>
<strong><?php $this->getText('STEP_1_DESC'); ?></strong><br>
<br>
<form action="index.php" method="post">
<table cellpadding="1" cellspacing="0">
    <tr>
        <td style="padding-top: 5px;"><?php $this->getText('SELECT_COUNTRY_LANG'); ?>: </td>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" height="29">
              <tr>
                <td style="padding-right: 3px;">
                    <select name="location_lang" style="font-size: 11px;"
                    onChange="update_dynpages_checkbox();"
                    >

                        <?php
                        $aLocations   = $this->getViewParam( "aLocations" );
                        $sSetupLang   = $this->getViewParam( "sSetupLang" );
                        $sLocationLang = $this->getViewParam( "sLocationLang" );

                        if ( isset( $aLocations[$sSetupLang] ) ) {
                            foreach ( $aLocations[$sSetupLang] as $sKey => $sValue ) {
                                $sSelected = ( $sLocationLang !== null && $sLocationLang == $sKey ) ? 'selected' : '';
                                ?><option value="<?php echo $sKey; ?>" <?php echo $sSelected; ?>><?php echo $sValue; ?></option><?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <noscript>
                <td>
                    <input type="submit" name="setup_lang_submit" value="<?php $this->getText('SELECT_SETUP_LANG_SUBMIT'); ?>" style="font-size: 11px;">
                </td>
                </noscript>
               <td>
                &nbsp;&nbsp;
                    <input type="hidden" value="false" name="use_dynamic_pages">
                    <input type="checkbox" id="use_dynamic_pages_ckbox" value="true" name="use_dynamic_pages" valign="" style="vertical-align:middle; width:20px; height:22px;<?php  if ( $sLocationLang === null ) echo " display: none;"?>" >
              </td>
              <td id="use_dynamic_pages_desc" style="<?php  if ( $sLocationLang === null ) echo "display: none;"?>">
                    <?php $this->getText('USE_DYNAMIC_PAGES'); ?><a href="<?php echo $sSetupLang; ?>/dyn_content_notice.php" onClick="showPopUp('<?php echo $sSetupLang; ?>/dyn_content_notice.php', 400, 200, 1); return false;" target="_blank"><u><?php $this->getText('PRIVACY_POLICY'); ?></u></a>.
              </td>
            </tr>
          </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 5px;"><?php $this->getText('SELECT_SELIVERY_COUNTRY'); ?>: </td>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" height="29">
                <tr>
                    <td>
                        <select name="country_lang" style="font-size: 11px;">
                            <?php
                                $aCountries   = $this->getViewParam( "aCountries" );
                                $sSetupLang   = $this->getViewParam( "sSetupLang" );
                                $sCountryLang = $this->getViewParam( "sCountryLang" );

                                if ( isset( $aCountries[$sSetupLang] ) ) {
                                    foreach ( $aCountries[$sSetupLang] as $sKey => $sValue ) {
                                        $sSelected = ( $sCountryLang !== null && $sCountryLang == $sKey ) ? 'selected' : '';
                                        ?><option value="<?php echo $sKey; ?>" <?php echo $sSelected; ?>><?php echo $sValue; ?></option><?php
                                    }
                                }
                            ?>
                        </select>
                    </td>
                    <td style="padding: 0px 5px;">
                        <?php $this->getText('SELECT_DELIVERY_COUNTRY_HINT'); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" name="sid" value="<?php $this->getSid(); ?>">
   </table>
    <br>
    <input type="hidden" value="false" name="check_for_updates">
    <input type="checkbox" id="check_for_updates_ckbox" value="true" name="check_for_updates" valign="" style="vertical-align:middle; width:20px; height:22px;" >
    <?php $this->getText('STEP_1_CHECK_UPDATES'); ?>
    <br><br>
    <?php $this->getText('STEP_1_TEXT'); ?>
    <br><br>
    <?php $this->getText('STEP_1_ADDRESS'); ?>
    <br>
    <input type="hidden" name="istep" value="<?php $this->getSetupStep( 'STEP_LICENSE' ); ?>">
    <input type="hidden" name="sid" value="<?php $this->getSid(); ?>">
    <input type="submit" id="step1Submit" class="edittext" value="<?php $this->getText('BUTTON_BEGIN_INSTALL'); ?>">
</form>
<?php require "_footer.php";