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
 * @package setup
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: lang.php 23462 2009-10-21 15:15:23Z vilma $
 */

$aLang = array(

'charset'                                         => 'ISO-8859-15',
'HEADER_META_MAIN_TITLE'                          => "OXID eShop Installationsassistent",
'HEADER_TEXT_SETUP_NOT_RUNS_AUTOMATICLY'          => "Sollte das Setup nicht nach einigen Sekunden automatisch weiterspringen, dann klicken Sie bitte",
'FOOTER_OXID_ESALES'                              => "&copy; OXID eSales AG 2003-".@date("Y"),

'TAB_0_TITLE'                                     => "Voraussetzungen",
'TAB_1_TITLE'                                     => "Willkommen",
'TAB_2_TITLE'                                     => "Lizenzbedingungen",
'TAB_3_TITLE'                                     => "Datenbank",
'TAB_4_TITLE'                                     => "Verzeichnisse",
'TAB_5_TITLE'                                     => "Lizenz",
'TAB_6_TITLE'                                     => "Fertigstellen",

'TAB_0_DESC'                                      => "&Uuml;berpr&uuml;fen, ob ihr System die Voraussetzungen erf&uuml;llt",
'TAB_1_DESC'                                      => "Herzlich willkommen<br>zur Installation von OXID eShop",
'TAB_2_DESC'                                      => "Best&auml;tigen Sie die Lizenzbedingungen",
'TAB_3_DESC'                                      => "Verbindung testen,<br>Tabellen anlegen",
'TAB_4_DESC'                                      => "Einrichten Ihres Shops,<br>Schreiben der Konfigurationsdatei",
'TAB_5_DESC'                                      => "Lizenzschl&uuml;ssel eintragen",
'TAB_6_DESC'                                      => "Installation erfolgreich",

'HERE'                                            => "hier",

'ERROR_NOT_AVAILABLE'                             => "FEHLER: %s nicht vorhanden!",
'ERROR_CHMOD'                                     => "FEHLER: Kann %s nicht auf chmod(0755) setzen!",
'ERROR_NOT_WRITABLE'                              => "FEHLER: %s nicht beschreibbar!",
'ERROR_DB_CONNECT'                                => "FEHLER: Keine Datenbank Verbindung m&ouml;glich!",
'ERROR_OPENING_SQL_FILE'                          => "FEHLER: Kann SQL Datei %s nicht &ouml;ffnen!",
'ERROR_FILL_ALL_FIELDS'                           => "FEHLER: Bitte alle notwendigen Felder ausf&uuml;llen!",
'ERROR_COULD_NOT_CONNECT_TO_DB'                   => "FEHLER: Keine Datenbank Verbindung m&ouml;glich!",
'ERROR_COULD_NOT_CREATE_DB'                       => "FEHLER: Datenbank %s nicht vorhanden und kann auch nicht erstellt werden!",
'ERROR_DB_ALREADY_EXISTS'                         => "FEHLER: Es scheint als ob in der Datenbank %s bereits eine OXID Datenbank vorhanden ist. Bitte l&ouml;schen Sie diese!",
'ERROR_BAD_SQL'                                   => "FEHLER: (Tabellen)Probleme mit folgenden SQL Befehlen: ",
'ERROR_BAD_DEMODATA'                              => "FEHLER: (Demodaten)Probleme mit folgenden SQL Befehlen: ",
'ERROR_CONFIG_FILE_IS_NOT_WRITABLE'               => "FEHLER: %s/config.inc.php"." nicht beschreibbar!",
'ERROR_BAD_SERIAL_NUMBER'                         => "FEHLER: Falsche Serienummer!",
'ERROR_COULD_NOT_OPEN_CONFIG_FILE'                => "Konnte config.inc.php nicht &ouml;ffnen. Bitte in unserer FAQ oder im Forum nachlesen oder den OXID Support kontaktieren.",

'MOD_PHP_EXTENNSIONS'                             => 'PHP Erweiterungen',
'MOD_PHP_CONFIG'                                  => 'PHP Konfiguration',
'MOD_SERVER_CONFIG'                               => 'Server-Konfiguration',

'MOD_MOD_REWRITE'                                 => 'Apache mod_rewrite Modul',
'MOD_ALLOW_URL_FOPEN'                             => 'allow_url_fopen oder fsockopen auf Port 80',
'MOD_PHP4_COMPAT'                                 => 'Zend Kompatibilit&auml;tsmodus muss ausgeschaltet sein',
'MOD_PHP_VERSION'                                  => 'PHP mindestens Version 5.2.0',
'MOD_REQUEST_URI'                                 => 'REQUEST_URI vorhanden',
'MOD_LIB_XML2'                                     => 'LIB XML2',
'MOD_PHP_XML'                                     => 'DOM',
'MOD_J_SON'                                        => 'JSON',
'MOD_I_CONV'                                       => 'ICONV',
'MOD_TOKENIZER'                                   => 'Tokenizer',
'MOD_BC_MATH'                                      => 'BCMath',
'MOD_MYSQL_CONNECT'                               => 'MySQL Modul für MySQL 5',
'MOD_GD_INFO'                                     => 'GDlib v2 [v1] incl. JPEG Unterst&uuml;tzung',
'MOD_INI_SET'                                     => 'ini_set erlaubt',
'MOD_REGISTER_GLOBALS'                            => 'register_globals muss ausgeschaltet sein',
'MOD_ZEND_OPTIMIZER'                              => 'Zend Optimizer installiert',
'MOD_ZEND_PLATFORM_OR_SERVER'                     => 'Zend Platform oder Zend Server installiert',
'MOD_MB_STRING'                                   => 'mbstring',
'MOD_UNICODE_SUPPORT'                             => 'UTF-8 Unterstützung',

'STEP_0_ERROR_TEXT'                               => 'Ihr System erf&uuml;llt nicht alle n&ouml;tigen Systemvoraussetzungen',
'STEP_0_TEXT'                                     => '<ul class="req">'.
                                                     '<li class="pass"> - Die Voraussetzung ist erf&uuml;llt.</li>'.
                                                     '<li class="pmin"> - Die Voraussetzung ist nicht oder nur teilweise erf&uuml;llt. Der OXID eShop funktioniert trotzdem und kann installiert werden.</li>'.
                                                     '<li class="fail"> - Die Voraussetzung ist nicht erf&uuml;llt. Der OXID eShop funktioniert nicht ohne diese Voraussetzung und kann nicht installiert werden.</li>'.
                                                     '<li class="null"> - Die Voraussetzung konnte nicht &uuml;berpr&uuml;ft werden.'.
                                                     '</ul>',
'STEP_0_DESC'                                     => 'In diesem Schritt wird &uuml;berpr&uuml;ft, ob Ihr System die Voraussetzungen erf&uuml;llt:',
'STEP_0_TITLE'                                    => 'Systemvoraussetzungen &uuml;berpr&uuml;fen',

'STEP_1_TITLE'                                    => "Willkommen",
'STEP_1_DESC'                                     => "Willkommen beim Installationsassistenten f&uuml;r den OXID eShop",
'STEP_1_TEXT'                                     => "<p>Um eine erfolgreiche und einfache Installation zu gew&auml;hrleisten, nehmen Sie sich bitte die Zeit, die folgenden Punkte aufmerksam zu lesen und Schritt f&uuml;r Schritt auszuf&uuml;hren.</p> <p>Viel Erfolg mit Ihrem OXID eShop w&uuml;nscht Ihnen</p>",
'STEP_1_ADDRESS'                                  => "OXID eSales AG<br>
                                                      Bertoldstr. 48<br>
                                                      79098 Freiburg<br>
                                                      Deutschland<br>",
'STEP_1_CHECK_UPDATES'                            => 'Regelmäßig überprüfen, ob Aktualisierungen vorhanden sind.',
'BUTTON_BEGIN_INSTALL'                            => "Shopinstallation beginnen",
'BUTTON_PROCEED_INSTALL'                          => "Setup beginnen",

'STEP_2_TITLE'                                    => "Lizenzbedingungen",
'BUTTON_RADIO_LICENCE_ACCEPT'                     => "Ich akzeptiere die Lizenzbestimmungen.",
'BUTTON_RADIO_LICENCE_NOT_ACCEPT'                 => "Ich akzeptiere die Lizenzbestimmungen nicht.",
'BUTTON_LICENCE'                                  => "Lizenzbedingungen annehmen",

'STEP_3_TITLE'                                    => "Datenbank",
'STEP_3_DESC'                                     => "Nun wird die Datenbank erstellt und mit den notwendigen Tabellen bef&uuml;llt. Dazu ben&ouml;tigen wir einige Angaben von Ihnen:",
'STEP_3_DB_HOSTNAME'                              => "Datenbank Hostname oder IP Adresse",
'STEP_3_DB_USER_NAME'                             => "Datenbank Benutzername",
'STEP_3_DB_PASSWORD'                              => "Datenbank Passwort",
'STEP_3_DB_PASSWORD_SHOW'                         => "Passwort anzeigen",
'STEP_3_DB_DATABSE_NAME'                          => "Datenbank Name",
'STEP_3_DB_DEMODATA'                              => "Demodaten",
'STEP_3_UTFMODE'                                  => "UTF-8 Zeichenkodierung benutzen",
'STEP_3_UTFNOTSUPPORTED'                          => "Der OXID eShop kann nicht im UTF-8 Modus verwendet werden, weil:",
'STEP_3_UTFNOTSUPPORTED1'                         => " das mbstring PHP-Modul fehlt",
'STEP_3_UTFNOTSUPPORTED2'                         => " die installierte PCRE-Version UTF-8 nicht unterstützt",
'STEP_3_UTFINFO'                                  => "Die UTF-8 Zeichenkodierung kann besser mit Sonderzeichen umgehen als andere Zeichenkodierungen. Dies ist insbesondere für vielsprachige eShops wichtig. Allerdings ist der eShop mit UTF-8 geringfügig langsamer als mit der Standard-Zeichenkodierung (ISO 8859-15). <br /> Wenn Sie vorhaben, viele verschiedene Sprachen im eShop zu benutzen, sollten sie UTF-8 verwenden. Wenn Sie nur Sprachen mit ähnlichen Zeichensätzen (z. B. Deutsch, Englisch, Französisch) im eShop benutzen möchten, benötigen Sie UTF-8 nicht.",
'STEP_3_CREATE_DB_WHEN_NO_DB_FOUND'               => "Falls die Datenbank nicht vorhanden ist, wird versucht diese anzulegen",
'BUTTON_RADIO_INSTALL_DB_DEMO'                    => "Demodaten installieren",
'BUTTON_RADIO_NOT_INSTALL_DB_DEMO'                => "Demodaten <strong>nicht</strong> installieren",
'BUTTON_DB_INSTALL'                               => "Datenbank jetzt erstellen",

'STEP_3_1_TITLE'                                  => "Datenbank - in Arbeit...",
'STEP_3_1_DB_CONNECT_IS_OK'                       => "Datenbank Verbindung erfolgreich gepr&uuml;ft...",
'STEP_3_1_DB_CREATE_IS_OK'                        => "Datenbank %s erfolgreich erstellt...",
'STEP_3_1_CREATING_TABLES'                        => "Erstelle Tabellen, kopiere Daten...",

'STEP_3_2_TITLE'                                  => "Datenbank - Tabellen erstellen...",
'STEP_3_2_CONTINUE_INSTALL_OVER_EXISTING_DB'      => "Falls Sie dennoch installieren wollen und die alten Daten &uuml;berschreiben, klicken Sie",
'STEP_3_2_CREATING_DATA'                          => "Datenbank erfolgreich erstellt!<br>Bitte warten...",

'STEP_4_TITLE'                                    => "Einrichten des OXID eShops",
'STEP_4_DESC'                                     => "Bitte geben Sie hier die f&uuml;r den Betrieb notwendigen Daten ein:",
'STEP_4_SHOP_URL'                                 => "Shop URL",
'STEP_4_SHOP_DIR'                                 => "Verzeichnis auf dem Server zum Shop",
'STEP_4_SHOP_TMP_DIR'                             => "Verzeichnis auf dem Server zum TMP Verzeichnis",
'STEP_4_DELETE_SETUP_DIR'                         => "Den Setup Ordner automatisch entfernen",

'STEP_4_1_TITLE'                                  => "Verzeichnisse - in Arbeit...",
'STEP_4_1_DATA_WAS_WRITTEN'                       => "Kontrolle und Schreiben der Dateien erfolgreich!<br>Bitte warten...",
'BUTTON_WRITE_DATA'                               => "Daten jetzt speichern",

'STEP_5_TITLE'                                    => "OXID eShop Lizenz",
'STEP_5_DESC'                                     => "Bitte geben Sie nun Ihren OXID eShop Lizenzschl&uuml;ssel ein:",
'STEP_5_LICENCE_KEY'                              => "Lizenzschl&uuml;ssel",
'STEP_5_LICENCE_DESC'                             => "Der mit der Demo Version ausgelieferte Lizenzschl&uuml;ssel (oben bereits ausgef&uuml;llt) ist 30 Tage g&uuml;ltig .<br>
                                                      Nach Ablauf der 30 Tage k&ouml;nnen alle Ihre &Auml;nderungen nach Eingabe eines g&uuml;ltigen Lizenzschl&uuml;ssels weiterhin benutzt werden.",
'BUTTON_WRITE_LICENCE'                            => "Lizenzschl&uuml;ssel speichern",

'STEP_5_1_TITLE'                                  => "Lizenzschl&uuml;ssel - in Arbeit...",
'STEP_5_1_SERIAL_ADDED'                           => "Lizenzschl&uuml;ssel erfolgreich gespeichert!<br>Bitte warten...",

'STEP_6_TITLE'                                    => "OXID eShop Einrichtung erfolgreich",
'STEP_6_DESC'                                     => "Die Einrichtung Ihres OXID eShops wurde erfolgreich abgeschlossen.",
'STEP_6_LINK_TO_SHOP'                             => "Hier geht es zu Ihrem Shop",
'STEP_6_LINK_TO_SHOP_ADMIN_AREA'                  => "Zugang zu Ihrer Shop Administration",
'STEP_6_TO_SHOP'                                  => "Zum Shop",
'STEP_6_TO_SHOP_ADMIN'                            => "Zur Shop Administration",
'STEP_6_ADDITIONAL_LOGIN_INFO'                    => 'Nutzen Sie "admin" als Benutzer und Passwort',

'ATTENTION'                                       => "Bitte beachten Sie",
'SETUP_DIR_DELETE_NOTICE'                         => "WICHTIG: Bitte l&ouml;schen Sie Ihr Setup Verzeichnis falls dieses nicht bereits automatisch entfernt wurde!",
'SETUP_CONFIG_PERMISSIONS'                        => "WICHTIG: Aus Sicherheitsgründen setzen Sie Ihre config.inc.php Datei auf read-only-Modus!",

'SELECT_SETUP_LANG'                               => "Sprache f&uuml;r die Installation",
'SELECT_COUNTRY_LANG'                             => "Ihr Standort",
'SELECT_SETUP_LANG_SUBMIT'                        => "Ausw&auml;hlen",
'USE_DYNAMIC_PAGES'                               => "Um Ihren Gesch&auml;ftserfolg zu vergr&ouml;&szlig;ern, laden Sie weitere Informationen vom OXID Server nach. <br>Mehr Informationen in unserern ",
'PRIVACY_POLICY'                                  => "Datenschutzerl&auml;uterungen",

'LOAD_DYN_CONTENT_NOTICE'                         => "<p>Falls die Option &quot;Weitere Informationen&quot; nachladen eingeschaltet ist, sehen Sie ein zus&auml;tzliches Men&uuml; im Admin Bereich Ihres OXID eShops.</p><p>Mittels dieses Men&uuml;s erhalten Sie weitere Informationen &uuml;ber E-Commerce Services wie z.B. Google Produktsuche oder econda.</p> <p>Sie k&ouml;nnen diese Einstellung im Admin Bereich jederzeit wieder &auml;ndern.</p>",
'ERROR_SETUP_CANCELLED'                           => "Das Setup wurde abgebrochen, weil Sie die Lizenzvereinbarungen nicht akzeptiert haben.",
'BUTTON_START_INSTALL'                            => "Setup erneut starten",

);

$aLang['MOD_MEMORY_LIMIT']                        = 'PHP Memory limit (min. 14MB, 30MB empfohlen)';
