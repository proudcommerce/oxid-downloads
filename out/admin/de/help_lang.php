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
 * @package lang
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: help_lang.php 18083 2009-04-10 11:58:34Z vilma $
 */

/**
 * In this file, all help content displayed in eShop admin is stored.
 * 3 different types of help are stored:
 *
 *   1) Tooltips
 *      Syntax for identifier: TOOLTIP_TABNAME_INPUTNAME, e.g. TOOLTIP_ARTICLE_MAIN_OXSEARCHWORDS
 *
 *   2) Additional Information, popping up when clicking on icon
 *      Syntax for identifier: HELP_TABNAME_INPUTNAME, e.g. HELP_SHOP_CONFIG_BIDIRECTCROSS.
 *      !!!The INPUTNAME is same as in lang.php for avoiding even more different Identifiers.!!!
 * 		In some cases, in lang.php GENERAL_ identifiers are used. In this file, always the tab name is used.
 *
 *   3) Links to Manual pages
 *      Syntax for identifier: MANUAL_TABNAME, e.g. MANUAL_ARTICLE_EXTENDED
 *
 *
 * HTML Tags for markup:
 * <var>...</var> for names of input fields, selectlists and Buttons, e.g. <var>Active</var>
 * <code>...</code> for preformatted text
 * <kdb>...</kdb> for input in input fields (also options in selectlists)
 * <strong>...</strong> for warning and important things
 * <ul><li> for lists
 */

$aLang =  array(
'charset'                                       => 'ISO-8859-15',
'HELP_SHOP_SYSTEM_OTHERCOUNTRYORDER'			=> 	"Diese Einstellung beeinflusst das Verhalten des OXID eShops, wenn für ein Land, in das Benutzer bestellen wollen, keine Versandkosten definiert sind:<br />" .
                                                    "<ul><li>Wenn die Einstellung aktiv ist, erhalten diese Benutzer im Bestellprozess eine Meldung: Die Versandkosten werden ihnen nachträglich mitgeteilt, wenn Sie damit einverstanden ist. Sie können mit der Bestellung fortfahren.</li>" .
                                                    "<li>Wenn die Option ausgeschaltet ist, können Benutzer aus Ländern, für die keine Versandkosten definiert sind, nicht bestellen.</li></ul>",

'HELP_SHOP_SYSTEM_DISABLENAVBARS'				=>	"Wenn Sie diese Einstellung aktivieren, werden die meisten Navigationselemente im Bestellprozess ausgeblendet. Dadurch werden die Benutzer beim Bestellen nicht unnötig abgelenkt.",

'HELP_SHOP_SYSTEM_DEFAULTIMAGEQUALITY'			=>	"Empfehlenswerte Einstellungen sind ca. 40-80:<br />" .
                                                    "<ul><li>Unterhalb von ca. 40 werden deutliche Kompressionsartefakte sichtbar, und die Bilder wirken unscharf.</li>".
                                                    "<li>Oberhalb von ca. 80 kann man kaum eine Verbesserung der Bildqualität feststellen, während die Dateigröße enorm zunimmt.</li></ul><br />".
                                                    "Die Standardeinstellung ist 75.",

'HELP_SHOP_SYSTEM_SHOWVARIANTREVIEWS'			=>	"Diese Einstellung beeinflusst das Verhalten, wenn Varianten bewertet werden: Wenn die Einstellung aktiv ist, dann werden die Bewertungen der Varianten auch beim Vater-Artikel angezeigt.",

'HELP_SHOP_SYSTEM_VARIANTSSELECTION'			=>	"Im eShop gibt es oft Listen, in denen Sie Artikel zuordnen können, z. B. wenn Sie Artikel zu Rabatten zuordnen. Wenn die Einstellung aktiv ist, werden in diesen Listen auch  Varianten angezeigt.",

'HELP_SHOP_SYSTEM_VARIANTPARENTBUYABLE'			=>	"Hier können Sie einstellen, ob der Vater-Artikel gekauft werden kann:" .
                                                    "<ul><li>Wenn die Einstellung aktiv ist, kann auch der Vater-Artikel gekauft werden.</li>" .
                                                    "<li>Wenn die Einstellung nicht aktiv ist, können nur die Varianten gekauft werden.</li></ul>",

'HELP_SHOP_SYSTEM_VARIANTINHERITAMOUNTPRICE'	=>	"Diese Einstellung beeinflusst das Verhalten des eShops, wenn beim Vater-Artikel Staffelpreise eingerichtet sind: Wenn die Einstellung aktiv ist, werden die Staffelpreise auch bei den Varianten verwendet.",

'HELP_SHOP_SYSTEM_ISERVERTIMESHIFT'				=>	"Es kann sein, dass sich der Server in einer anderen Zeitzone befindet. Mit dieser Einstellung können Sie die Zeitverschiebung korrigieren: Geben Sie die Anzahl der Stunden, die zur Serverzeit addiert/abgezogen werden sollen ein, z. B. <kdb>+2</kdb> oder <kdb>-2</kdb>",

'HELP_SHOP_SYSTEM_INLINEIMGEMAIL'				=>	"Wenn die Einstellung aktiv ist, werden die Bilder, die in E-Mails verwendet werden, zusammen mit der E-Mail versendet. Wenn die Einstellung nicht aktiv ist, lädt das E-Mail Programm die Bilder herunter, wenn Benutzer die E-Mail öffnen.",



'HELP_SHOP_CONFIG_TOPNAVILAYOUT'				=>	"In der Kategorien-Navigation werden die Kategorien angezeigt. Die Kategorien-Navigation wird normalerweise links angezeigt. Wenn Sie diese Einstellung aktivieren, wird die Kategorien-Navigation anstatt links oben angezeigt.",

'HELP_SHOP_CONFIG_ORDEROPTINEMAIL'				=>	"Wenn Double-Opt-In aktiviert ist, erhalten die Benutzer eine E-Mail mit einem Bestätigungs-Link, wenn sie sich für den Newsletter registrieren. Erst, wenn sie diesen Link besuchen, sind sie für den Newsletter angemeldet.<br />" .
                                                    "Double-Opt-In schützt vor Anmeldungen, die nicht gewollt sind. Ohne Double-Opt-In können beliebige E-Mail Adressen für den Newsletter angemeldet werden. Dies wird z. B. auch von Spam-Robotern gemacht. Durch Double-Opt-In kann der Besitzer der E-Mail Adresse bestätigen, dass er den Newsletter wirklich empfangen will.",

'HELP_SHOP_CONFIG_BIDIRECTCROSS'				=>	"Durch Crossselling können zu einem Artikel passende Artikel angeboten werden. Crossselling-Artikel werden im eShop bei <i>Kennen Sie schon?</i> angezeigt.<br />" .
                                                    "Wenn z. B. einem Auto als Crossselling-Artikel Winterreifen zugeordnet sind, werden beim Auto die Winterreifen angezeigt." .
                                                    "Wenn Bidirektionales Crossselling aktiviert ist, funktioniert Crossselling in beide Richtungen: bei den Winterreifen wird das Auto angezeigt.",

'HELP_SHOP_CONFIG_ICONSIZE'						=>	"Icons sind die kleinsten Bilder eines Artikels. Icons werden z. B. <br />" .
                                                    "<ul><li>im Warenkorb angezeigt.</li>" .
                                                    "<li>angezeigt, wenn Artikel in der rechten Leiste aufgelistet werden (z.B. bei den Aktionen <i>Top of the Shop</i> und <i>Schnäppchen</i>.</li></ul>" .
                                                    "Damit das Design des eShops nicht durch zu große Icons gestört wird, werden zu große Icons automatisch verkleinert. Die maximale Größe können Sie hier eingeben.<br />" ,

'HELP_SHOP_CONFIG_THUMBNAILSIZE'				=>  "Thumbnails sind kleine Bilder eines Artikels. Thumbnails werden z. B. <br />" .
                                                    "<ul><li>in Artikellisten angezeigt. Artikellisten sind z. B. Kategorieansichten (alle Artikel in einer Kategorie werden aufgelistet) und die Suchergebnisse.</li>" .
                                                    "<li>in Aktionen angezeigt, die in der Mitte der Startseite angezeigt werden, z. B. <i>Die Dauerbrenner</i> und <i>Frisch eingetroffen!</i>.</li></ul>" .
                                                    "Damit das Design des eShops nicht durch zu große Thumbnails gestört wird, werden zu große Thumbnails automatisch verkleinert. Die maximale Größe können Sie hier eingeben.",

'HELP_SHOP_CONFIG_STOCKONDEFAULTMESSAGE'		=>	"Bei jedem Artikel können Sie einrichten, welche Meldung den Benutzern angezeigt wird, wenn der Artikel auf Lager ist. " .
                                                    'Wenn diese Einstellung aktiv ist, wird den Benutzern auch dann eine Meldung angezeigt, wenn bei einem Artikel keine eigene Meldung hinterlegt ist. Dann die Standardmeldung "sofort lieferbar" angezeigt.',

'HELP_SHOP_CONFIG_STOCKOFFDEFAULTMESSAGE'		=>	"Bei jedem Artikel können Sie einrichten, welche Meldung den Benutzern angezeigt wird, wenn der Artikel nicht auf Lager ist. " .
                                                    'Wenn diese Einstellung aktiv ist, wird den Benutzern auch dann eine Meldung angezeigt, wenn bei einem Artikel keine eigene Meldung hinterlegt ist. Dann die Standardmeldung "Dieser Artikel ist nicht auf Lager und muss erst nachbestellt werden" angezeigt.',

'HELP_SHOP_CONFIG_OVERRIDEZEROABCPRICES'		=>	"Sie können für bestimmte Benutzer spezielle Preise einrichten. Dadurch können Sie bei jedem Artikel A, B, und C-Preise eingeben. Wenn Benutzer z. B. in der Benutzergruppe Preis A sind, werden ihnen die A-Preise anstatt dem normalen Artikelpreis angezeigt.<br />" .
                                                    "Wenn die Einstellung aktiv ist, wird diesen Benutzern der normale Artikelpreis angezeigt, wenn für den Artikel kein A, B oder C-Preis vorhanden ist.<br />" .
                                                    "Sie sollten diese Einstellung aktivieren, wenn Sie A,B und C-Preise verwenden: Ansonsten wird den bestimmten Benutzern ein Preis von 0,00 angezeigt, wenn kein A,B oder C-Preis hinterlegt ist.",

'HELP_SHOP_CONFIG_SEARCHFIELDS'					=>	"Hier können Sie die Datenbankfelder der Artikel eingeben, in denen gesucht wird. Geben Sie pro Zeile nur ein Datenbankfeld ein.<br />" .
                                                    "Die am häufigsten benötigten Einträge sind:" .
                                                    "<ul><li>oxtitle = Titel (Name) der Artikel</li>" .
                                                    "<li>oxshortdesc = Kurzbeschreibung der Artikel</li>" .
                                                    "<li>oxsearchkeys = Suchwörter, die bei den Artikeln eingetragen sind</li>" .
                                                    "<li>oxartnum = Artikelnummern</li>" .
                                                    "<li>oxtags	= Stichworte, bei den Artikeln eingetragen sind</li></ul>",

'HELP_SHOP_CONFIG_SORTFIELDS'					=>	"Hier können Sie die Datenbankfelder der Artikel eingeben, nach denen Artikellisten sortiert werden können. Geben Sie pro Zeile nur ein Datenbankfeld ein.<br />" .
                                                    "Die am häufigsten benötigten Einträge sind:" .
                                                    "<ul><li>oxtitle = Titel (Name) der Artikel</li>" .
                                                    "<li>oxprice = Preis der Artikel</li>" .
                                                    "<li>oxvarminprice = Der niedrigste Preis der Artikel, wenn Varianten mit verschiedenen Preisen verwendet werden.</li>" .
                                                    "<li>oxartnum = Artikelnummern</li>" .
                                                    "<li>oxrating = Die Bewertung der Artikel</li>" .
                                                    "<li>oxstock = Lagerbestand der Artikel</li></ul>",

'HELP_SHOP_CONFIG_MUSTFILLFIELDS'				=>	"Hier können Sie eingeben, welche Felder von Benutzern ausgefüllt werden müssen, wenn Sie sich registrieren. Sie müssen die entsprechenden Datenbankfelder angeben. Geben Sie pro Zeile nur ein Datenbankfeld ein.<br />" .
                                                    "Die am häufigsten benötigten Einträge für die Benutzerdaten sind:" .
                                                    "<ul><li>oxuser__oxfname = Vorname</li>" .
                                                    "<li>oxuser__oxlname = Nachname</li>" .
                                                    "<li>oxuser__oxstreet = Straße</li>" .
                                                    "<li>oxuser__oxstreetnr = Hausnummer</li>" .
                                                    "<li>oxuser__oxzip = Postleitzahl</li>" .
                                                    "<li>oxuser__oxcity = Stadt</li>" .
                                                    "<li>oxuser__oxcountryid = Land</li>" .
                                                    "<li>oxuser__oxfon = Telefonnummer</li></ul><br />" .
                                                    "Sie können auch angeben, welche Felder ausgefüllt werden müssen, wenn Benutzer eine Lieferadresse eingeben. Die am häufigsten benötigten Einträge sind:" .
                                                    "<ul><li>oxaddress__oxfname = Vorname</li>" .
                                                    "<li>oxaddress__oxlname = Nachname</li>" .
                                                    "<li>oxaddress__oxstreet = Straße</li>" .
                                                    "<li>oxaddress__oxstreetnr = Straßennummer</li>" .
                                                    "<li>oxaddress__oxzip = Postleitzahl</li>" .
                                                    "<li>oxaddress__oxcity = Stadt</li>" .
                                                    "<li>oxaddress__oxcountryid = Land</li>" .
                                                    "<li>oxaddress__oxfon = Telefonnummer</li></ul>",

'HELP_SHOP_CONFIG_USENEGATIVESTOCK'				=>	"Mit <var>Negative Lagerbestände erlauben</var> können Sie einstellen, welcher Lagerbestand berechnet wird, wenn ein Artikel ausverkauft ist:<br />" .
                                                    "<ul><li>Wenn die Einstellung aktiv ist, werden negative Lagerbestände berechnet, wenn weitere Exemplare bestellt werden.</li>" .
                                                    "<li>Wenn die Einstellung nicht aktiv ist, fällt der Lagerbestand eines Artikels nie unter 0. Auch dann nicht, wenn der Artikel bereits ausverkauft ist und noch weitere Exemplare bestellt werden.</li></ul>",

'HELP_SHOP_CONFIG_NEWARTBYINSERT'  				=>	"Auf der Startseite Ihres eShops werden die unter \"Frisch eingetroffen!\" die neusten Artikel in Ihrem eShop angezeigt. Sie können die Artikel, die hier angezeigt werden, manuell einstellen oder automatisch berechnen lassen. Mit dieser Einstellung wählen Sie, wie die neusten Artikel berechnet werden sollen: Nach dem Datum, an dem die Artikel erstellt wurden, oder nach dem Datum der letzten Änderung.",

'HELP_SHOP_CONFIG_LOAD_DYNAMIC_PAGES'			=>	"Wenn diese Einstellung aktiv ist, werden zusätzliche Informationen zu anderen OXID-Produkten im Administrationsbereich angezeigt, z. B. zu OXID eFire. Welche Informationen geladen werden, hängt vom Standort ihres eShops ab.",

'HELP_SHOP_CONFIG_DELETERATINGLOGS'				=>	"Wenn Benutzer einen Artikel bewerten, können Sie den Artikel nicht erneut bewerten. Hier können Sie einstellen, dass die Benutzer nach einer bestimmten Anzahl von Tagen den Artikel erneut bewerten können.",



'HELP_SHOP_PERF_NEWESTARTICLES'					=>	"In Ihrem eShop wird eine Liste mit den neusten Artikeln (Frisch eingetroffen!) angezeigt. Hier können Sie einstellen, wie die Liste generiert wird:" .
                                                    "<ul><li><kbd>ausgeschaltet</kbd>: Die Liste wird nicht angezeigt</li>" .
                                                    "<li><kbd>manuell</kbd>: Sie können unter <em>Kundeninformationen -> Aktionen verwalten</em> in der Aktion <var>Frisch eingetroffen</var> einstellen, welche Artikel in der Liste angezeigt werden</li>" .
                                                    "<li><kbd>automatisch</kbd>: Die Liste der neusten Artikel wird automatisch berechnet.</li></ul>",

'HELP_SHOP_PERF_TOPSELLER'						=>	"In Ihrem eShop wird eine Liste mit den meistverkauften Artikeln (Top of the Shop) angezeigt. Hier können Sie einstellen, wie die Liste generiert wird:" .
                                                    "<ul><li><kbd>ausgeschaltet</kbd>: Die Liste wird nicht angezeigt</li>" .
                                                    "<li><kbd>manuell</kbd>: Sie können unter <em>Kundeninformationen -> Aktionen verwalten</em> in der Aktion <var>Topseller</var> einstellen, welche Artikel in der Liste angezeigt werden</li>" .
                                                    "<li><kbd>automatisch</kbd>: Die Liste der meistverkauften Artikel wird automatisch berechnet.</li></ul>",

'HELP_SHOP_PERF_LOADFULLTREE'					=>	"Wenn die Einstellung aktiv ist, wird in der Kategoriennavigation der komplette Kategoriebaum angezeigt (Alle Kategorien sind \"ausgeklappt\"). Diese Einstellung funktioniert nur, wenn die Kategoriennavigation <strong>nicht</strong> oben angezeigt wird.",

'HELP_SHOP_PERF_LOADACTION'						=>	"Wenn die Einstellung aktiv ist, werden Aktionen wie 'Die Dauerbrenner', 'Top of the Shop', 'Frisch eingetroffen!' geladen und angezeigt.",

'HELP_SHOP_PERF_LOADREVIEWS'					=>	"Benutzer können Artikel bewerten und Kommentare zu Artikeln verfassen. Wenn die Einstellung aktiv ist, werden die bereits abgegebenen Kommentare und Bewertungen beim Artikel angezeigt.",

'HELP_SHOP_PERF_USESELECTLISTPRICE'				=>	"In Auswahllisten können Sie Preis Auf/Abschläge einstellen. Wenn diese Einstellung aktiv ist, werden die Auf/Abschläge berechnet, ansonsten nicht.",

'HELP_SHOP_PERF_DISBASKETSAVING'				=>	"Der Warenkorb von angemeldeten Benutzern wird gespeichert. Wenn sich die Benutzer bei einem weiteren Besuch in Ihrem eShop anmelden, wird der gespeicherte Warenkorb automatisch wieder geladen. Wenn sie diese Einstellung aktivieren, werden die Warenkörbe nicht mehr gespeichert.",

'HELP_SHOP_PERF_LOADDELIVERY'					=>	"Wenn Sie diese Einstellung ausschalten, berechnet der eShop keine Versandkosten: es werden immer 0,00 EUR als Versandkosten angegeben.",

'HELP_SHOP_PERF_LOADPRICE'						=>	"Wenn Sie diese Einstellung ausschalten, wird der Artikelpreis nicht mehr berechnet und bei den Artikeln kein Preis mehr angezeigt. ",

'HELP_SHOP_PERF_PARSELONGDESCINSMARTY'			=>	"Wenn die Einstellung aktiv ist, werden die Beschreibungstexte von Artikeln und Kategorien mit Smarty ausgeführt: Dann können Sie Smarty-Tags in die Beschreibungstexte einbinden (z. B. Variablen ausgeben). <br />" .
                                                    "Wenn die Einstellung nicht aktiv ist, werden die Beschreibungstexte so eingegeben, wie sie im Editor eingegeben werden.",

'HELP_SHOP_PERF_LOADATTRIBUTES'					=>	"Normalerweise werden die Attribute eines Artikels nur in der Detailansicht des Artikels geladen. Wenn die Einstellung aktiv ist, werden die Attribute immer zusammen mit dem Artikel geladen (z. B. wenn der Artikel in einem Suchergebnis vorkommt).<br />" .
                                                    "Diese Einstellung kann nützlich sein, wenn Sie die Templates anpassen und die Attribute eines Artikels auch an anderen Stellen anzeigen möchten.",
'HELP_SHOP_PERF_LOADSELECTLISTSINALIST'			=>	"Normalerweise werden Auswahllisten nur in der Detailansicht eines Artikels angezeigt. Wenn Sie die Einstellung aktivieren, werden die Auswahllisten auch in Artikellisten (z. B. Suchergebnisse, Kategorieansichten) angezeigt.",



'HELP_SHOP_SEO_TITLEPREFIX'						=>	"Jede einzelne Seite hat einen Titel. Er wird im Browser als Titel des Browser-Fensters angezeigt. Mit Titel Prefix und Titel Postfix haben Sie die Möglichkeit, vor und hinter jeden Seitentitel Text einzufügen:<br />" .
                                                    "<ul><li>Geben Sie in <var>Titel Prefix</var> den Text ein, der vor dem Titel erscheinen soll.</li>" .
                                                    "<li>Geben Sie in <var>Titel Postfix</var> den Text ein, der hinter dem Titel erscheinen soll.</li></ul>",

'HELP_SHOP_SEO_TITLESUFFIX'						=>	"Jede einzelne Seite hat einen Titel. Er wird im Browser als Titel des Browser-Fensters angezeigt. Mit Titel Prefix und Titel Postfix haben Sie die Möglichkeit, vor und hinter jeden Seitentitel Text einzufügen:<br />" .
                                                    "<ul><li>Geben Sie Titel Prefix den Text ein, der vor dem Titel erscheinen soll.</li>" .
                                                    "<li>Geben Sie in Titel Postfix den Text ein, der hinter dem Titel erscheinen soll.</li></ul>",

'HELP_SHOP_SEO_IDSSEPARATOR'					=>	"Das Trennzeichen wird verwendet, wenn Kategorie- oder Artikelnamen aus mehreren Worten bestehen. Das Trennzeichen wird anstelle eines Leerzeichens in die URL eingefügt, z. B. www.ihronlineshop.de/Kategorie-aus-mehreren-Worten/Artikel-aus-mehreren-Worten.html<br />" .
                                                    "Wenn Sie kein Trennzeichen eingeben, wird der Bindestrich - als Trennzeichen verwendet",

'HELP_SHOP_SEO_SAFESEOPREF'						=>	"Wenn mehrere Artikel den gleichen Namen haben und in der gleichen Kategorie sind, würden sie die gleiche SEO URL erhalten. Damit das nicht passiert, wird das SEO Suffix angehängt. Dadurch werden gleiche SEO URLs vermieden. Wenn Sie kein SEO Suffix angeben, wird 'oxid' als Standard verwendet.",

'HELP_SHOP_SEO_REPLACECHARS'					=>	"Bestimmte Sonderzeichen wie Umlaute (Ä,Ö,Ü) sollten in URLs nicht vorkommen, da Sie Probleme verursachen können. In dem Eingabefeld wird angegeben, mit welchen Zeichen die Sonderzeichen ersetzt werden. Die Syntax ist <code>Sonderzeichen => Ersatzzeichen</code>, z. B. <code>Ü => Ue</code>.<br />" .
                                                    "Für die deutsche Sprache sind die Ersetzungen bereits eingetragen.",

'HELP_SHOP_SEO_RESERVEDWORDS'					=>	"Bestimmte URLs sind im eShop festgelegt, z.B. www.ihronlineshop.de/admin, um den Administrationsbereich zu öffnen. Wenn eine Kategorie 'admin' heißen würde, wäre die SEO URL zu dieser Kategorie ebenfalls www.ihronlineshop.de/admin - die Kategorie könnte nicht geöffnet werden. Deswegen wird an solche SEO URLs automatisch das SEO Suffix angehängt. Mit dem Eingabefeld können Sie einstellen, an welche SEO URLs das SEO Suffix automatisch angehängt werden soll.",

'HELP_SHOP_SEO_SKIPTAGS'						=>	"Wenn bei Artikeln oder Kategorien keine SEO-Einstellungen für die META-Tags vorhanden sind, werden diese Informationen aus der Beschreibung generiert. Dabei können Wörter weggelassen werden, die besonders häufig vorkommen. Alle Wörter die in diesem Eingabefeld stehen, werden bei der automatischen Generierung ignoriert.",

'HELP_SHOP_SEO_STATICURLS'						=>	"Für bestimmte Seiten (z. B. AGB's) im eShop können Sie feste suchmaschinenfreundliche URLs festlegen. Wenn Sie eine statische URL auswählen, wird in dem Feld <var>Standard URL</var> die normale URL angezeigt. In den Eingabefeldern weiter unten können Sie für jede Sprache suchmaschinenfreundliche URLs eingeben.",



'HELP_SHOP_MAIN_PRODUCTIVE'						=>	"Wenn die Einstellung <b>nicht</b> aktiv ist, werden am unteren Ende jeder Seite Informationen zu Ladezeiten angezeigt. Außerdem werden Debug-Informationen angezeigt. Diese Informationen sind für Entwickler wichtig, wenn sie den OXID eShop anpassen.<br />" .
                                                    "<b>Aktivieren Sie diese Einstellung, bevor ihr eShop öffentlich zugänglich gemacht wird! Dadurch wird den Benutzern nur der eShop ohne die zusätzlichen Informationen angezeigt.</b>",

'HELP_SHOP_MAIN_ACTIVE'							=>	"Mit <var>Aktiv</var> können Sie ihren kompletten eShop ein- und ausschalten. Wenn ihr eShop ausgeschaltet ist, wird Ihren Kunden eine Meldung angezeigt, dass der eShop vorübergehend offline ist. Das kann für Wartungsarbeiten am eShop nützlich sein.",

'HELP_SHOP_MAIN_INFOEMAIL'						=>	"An diese E-Mail Adresse werden E-Mails gesendet, wenn die Benutzer E-Mails über das Kontaktformular senden.",

'HELP_SHOP_MAIN_ORDEREMAIL'						=>	"Wenn Benutzer bestellen, erhalten sie eine E-Mail, in der die Bestellung nochmals zusammengefasst ist. Wenn die Benutzer auf diese E-Mail antworten, wird die Antwort an die <var>Bestell E-Mail Reply</var> gesendet.",

'HELP_SHOP_MAIN_OWNEREMAIL'						=>	"Wenn Benutzer bestellen, wird an Sie als eShop-Administrator eine E-Mail gesendet, dass eine Bestellung im eShop gemacht wurde. Diese E-Mails werden an <var>Bestellungen an</var> gesendet.",



'HELP_ARTICLE_MAIN_ALDPRICE'					=>	"Mit <var>Alt. Preise</var> können Sie für bestimmte Benutzer spezielle Preise einrichten. Wie das funktioniert, erfahren Sie im <a href=\"http://www.oxid-esales.com/de/resources/help-faq/eshop-manual/fuer-bestimmte-benutzer-besondere-preise-einrichten\">Handbuch auf der OXID eSales Website.</a>.",



'HELP_ARTICLE_EXTEND_UNITQUANTITY'				=>	"Mit <var>Menge</var> und <var>Mengeneinheit</var> können Sie den Grundpreis des Artikels (Preis pro Mengeneinheit) einstellen (z. B. 1,43 EUR pro Liter): Geben Sie bei <var>Menge</var> die Menge des Artikels (z. B. 1,5) und bei <var>Mengeneinheit</var> die entsprechende Mengeneinheit (z. B. Liter) ein. Dann wird der Grundpreis pro Mengeneinheit berechnet und beim Artikel angezeigt.",

'HELP_ARTICLE_EXTEND_EXTURL'					=>	"Bei <var>Externe URL</var> können Sie einen Link eingeben, wo weitere Informationen zu dem Artikel erhältlich sind (z. B. auf der Hersteller-Website). Bei <var>Text für ext. URL</var> können Sie den Text eingeben, der verlinkt wird (z. B. <kbd>weitere Informationen vom Hersteller</kbd>).",

'HELP_ARTICLE_EXTEND_TPRICE'					=>	"Bei <var>UVP</var> können Sie die Unverbindliche Preisempfehlung des Herstellers eingeben. Wenn Sie die UVP eingeben, wird diese den Benutzern angezeigt: Beim Artikel wird über dem Preis \"statt UVP nur\" angezeigt.",

'HELP_ARTICLE_EXTEND_QUESTIONEMAIL'				=>	"Bei <var>Alt. Anspr.partn.</var> können Sie eine E-Mail Adresse eingeben. Wenn die Benutzer eine Frage zu diesem Artikel absenden, wird Sie an diese E-Mail Adresse geschickt. Wenn keine E-Mail Adresse eingetragen ist, wird die Anfrage an die normale Info E-Mail Adresse geschickt.",

'HELP_ARTICLE_EXTEND_SKIPDISCOUNTS'				=>	"Wenn <var>Alle neg. Nachlässe ignorieren</var> aktiviert ist, werden für diesen Artikel keine negativen Nachlässe berechnet. Das sind z. B. Rabatte und Gutscheine.",



'HELP_ARTICLE_STOCK_STOCKFLAG'					=>	"Hier können Sie einstellen, wie sich der eShop verhält, wenn der Artikel ausverkauft ist:<br />" .
                                                    "<ul><li>Standard: Der Artikel kann auch dann bestellt werden, wenn er ausverkauft ist.</li>" .
                                                    '<li>Fremdlager: Der Artikel kann immer gekauft werden und wird immer als "auf Lager" angezeigt. (In einem Fremdlager kann der Lagerbestand nicht ermittelt werden. Deswegen wird der Artikel immer als â¤¸auf Lagerâ¤½ geführt).</li>' .
                                                    "<li>Wenn Ausverkauft offline: Der Artikel wird nicht angezeigt, wenn er ausverkauft ist.</li>" .
                                                    "<li>Wenn Ausverkauft nicht bestellbar: Der Artikel wird angezeigt, wenn er ausverkauft ist, aber er kann nicht bestellt werden.</li></ul>",

'HELP_ARTICLE_STOCK_REMINDACTIV'				=>	"Hier können Sie einrichten, dass Ihnen eine E-Mail gesendet wird, sobald der der Lagerbestand unter den hier eingegebenen Wert sinkt. Dadurch werden Sie rechtzeitig informiert, wenn der Artikel fast ausverkauft ist. Setzen Sie hierzu das Häkchen und geben Sie den Bestand ein, ab dem Sie informiert werden wollen.",

'HELP_ARTICLE_STOCK_DELIVERY'					=>	"Hier können Sie eingeben, ab wann ein Artikel wieder lieferbar ist, wenn er ausverkauft ist. Das Format ist Jahr-Monat-Tag, z. B. 2008-10-21.",



'HELP_ARTICLE_SEO_FIXED'						=>	"Sie können die SEO URLs vom eShop neu berechnen lassen. Eine Artikelseite bekommt z. B. eine neue SEO URL, wenn Sie den Titel des Artikels ändern. Die Einstellung <var>URL fixiert</var> unterbindet das: Wenn sie aktiv ist, wird die alte SEO URL beibehalten und keine neue SEO URL berechnet.",

'HELP_ARTICLE_SEO_KEYWORDS'						=>	"Diese Stichwörter werden in den HTML-Quelltext (Meta Keywords) eingebunden. Diese Information wird von Suchmaschinen ausgewertet. Hier können Sie passende Stichwörter zu dem Artikel eingeben. Wenn Sie nichts eingeben, werden die Stichwörter automatisch erzeugt.",

'HELP_ARTICLE_SEO_DESCRIPTION'					=>	"Dieser Beschreibungstext wird in den HTML-Quelltext (Meta Description) eingebunden. Dieser Text wird von vielen Suchmaschinen bei den Suchergebnissen angezeigt. Hier können Sie eine passende Beschreibung zu dem Artikel eingeben. Wenn Sie nichts eingeben, wird die Beschreibung automatisch erzeugt.",

'HELP_ARTICLE_SEO_ACTCAT'						=>	"Sie können für einen Artikel unterschiedliche SEO URLs festlegen: Für bestimmte Kategorien und für den Hersteller des Artikels. Mit <var>Aktive Kategorie/Hersteller</var> können Sie wählen, welche SEO URL Sie anpassen möchten.",



'HELP_CATEGORY_MAIN_HIDDEN'						=>	"Mit <var>Versteckt</var> können Sie einstellen, ob die Kategorie den Benutzern angezeigt werden soll. Wenn eine Kategorie versteckt ist, wird Sie den Benutzern nicht angezeigt, auch wenn die Kategorie aktiv ist.",

'HELP_CATEGORY_MAIN_PARENTID'					=>	"Bei <var>Unterkategorie von</var> stellen Sie ein, an welcher Stelle die Kategorie erscheinen soll:" .
                                                    "<ul>" .
                                                    "<li>Wenn die Kategorie keiner anderen Kategorie untergeordnet sein soll, dann wählen Sie <kbd>--</kbd> aus.</li>" .
                                                    "<li>Wenn die Kategorie einer anderen Kategorie untergeordnet sein soll, dann wählen Sie die entsprechende Kategorie aus.</li>",

'HELP_CATEGORY_MAIN_EXTLINK'					=>	"Bei <var>Externer Link</var> können Sie einen Link eingeben, der geöffnet wird, wenn Benutzer auf die Kategorie klicken. <strong>Verwenden Sie diese Funktion nur, wenn Sie einen Link in der Kategorien-Navigation anzeigen wollen. Die Kategorie verliert dadurch Ihre normale Funktion!</strong>",

'HELP_CATEGORY_MAIN_PRICEFROMTILL'				=>	"Mit <var>Preis von/bis</var> können sie einstellen, dass in der Kategorie <strong>alle</strong> Artikel angezeigt werden, die einen bestimmten Preis haben. Im ersten Eingabefeld wird die Untergrenze eingegeben, in das zweite Eingabefeld die Obergrenze. Dann werden in der Kategorie <strong>alle Artikel Ihres eShops</strong> angezeigt, die einen entsprechenden Preis haben.",

'HELP_CATEGORY_MAIN_DEFSORT'					=>	"Mit <var>Schnellsortierung</var> stellen Sie ein, wie die Artikel in der Kategorie sortiert werden. Welche Möglichkeiten Sie haben, erfahren Sie im <a href=\"http://www.oxid-esales.com/de/resources/help-faq/eshop-manual/artikel-sortieren\">eShop Handbuch</a> auf der OXID eSsales Website.",

'HELP_CATEGORY_MAIN_SORT'						=>	"Mit <var>Sortierung</var> können Sie festlegen, in welcher Reihenfolge die Kategorien angezeigt werden: Die Kategorie mit der kleinsten Zahl wird oben angezeigt, die Kategorie mit der größten Zahl unten.",

'HELP_CATEGORY_MAIN_THUMB'						=>	"Bei <var>Bild</var> und <var>Bild hochladen</var> können Sie ein Bild für die Kategorie hochladen. Das Bild wird in der Kategorie oben angezeigt. Wählen Sie bei <var>Bild hochladen</var> das Bild aus, das Sie hochladen möchten. Wenn Sie auf Speichern klicken, wird das Bild hochgeladen. Nachdem das Bild hochgeladen ist, wird der Dateiname des Bildes in <var>Bild</var> angezeigt.",

'HEOLP_CATEGORY_MAIN_SKIPDISCOUNTS'				=>	"Wenn<var> Alle neg. Nachlässe ignorieren</var> aktiv ist, werden für alle Artikel in dieser Kategorie keine negativen Nachlässe berechnet.",



'HELP_CATEGORY_SEO_FIXED'						=>	"Sie können die SEO URLs vom eShop neu berechnen lassen. Eine Kategorie bekommt z. B. eine neue SEO URL, wenn Sie den Titel der Kategorie ändern. Die Einstellung <var>URL fixiert</var> unterbindet das: Wenn sie aktiv ist, wird die alte SEO URL beibehalten und keine neue SEO URL berechnet.",

'HELP_CATEGORY_SEO_SHOWSUFFIX'					=>	"Diese Einstellung bestimmt, ob das Suffix für den Fenstertitel angezeigt wird, wenn die Kategorieseite im eShop aufgerufen wird. Das Titel Suffix können Sie unter <em>Stammdaten -> Grundeinstellungen -> SEO -> Titel Suffix</em> einstellen.",

'HELP_CATEGORY_SEO_KEYWORDS'					=>	"Diese Stichwörter werden in den HTML-Quelltext (Meta Keywords) eingebunden. Diese Information wird von Suchmaschinen ausgewertet. Hier können Sie passende Stichwörter zu der Kategorie eingeben. Wenn Sie nichts eingeben, werden die Stichwörter automatisch erzeugt.",

'HELP_CATEGORY_SEO_DESCRIPTION'					=>	"Dieser Beschreibungstext wird in den HTML-Quelltext (Meta Description) eingebunden. Dieser Text wird von vielen Suchmaschinen bei den Suchergebnissen angezeigt. Hier können Sie eine passende Beschreibung für die Kategorie eingeben. Wenn Sie nichts eingeben, wird die Beschreibung automatisch erzeugt.",



'HELP_CONTENT_MAIN_SNIPPET'						=>	"Wenn Sie <var>Snippet</var> auswählen, können Sie die CMS-Seite in anderen Seiten mit Hilfe des Idents einbinden: [{ oxcontent ident=\"Ident_der_CMS_Seite\" }]",

'HELP_CONTENT_MAIN_MAINMENU'					=>	"Wenn Sie <var>Hauptmenü</var> auswählen, wird in der oberen Menüleiste ein Link zu der CMS-Seite angezeigt (bei AGB und Impressum).",

'HELP_CONTENT_MAIN_CATEGORY'					=>	"Wenn Sie <var>Kategorie</var> auswählen, wird in der Kategoriennavigation unter den normalen Kategorien ein Link zu der CMS-Seite angezeigt.",

'HELP_CONTENT_MAIN_MANUAL'						=>	"Wenn Sie <var>Manuell</var> auswählen, wird ein Link erzeugt, mit dem Sie die CMS-Seite in andere CMS-Seiten einbinden können. Der Link wird weiter unten angezeigt, wenn Sie auf Speichern klicken.",



'HELP_CONTENT_SEO_FIXED'						=>	"Sie können die SEO URLs vom eShop neu berechnen lassen. Eine CMS-Seite bekommt z. B. eine neue SEO URL, wenn Sie den Titel der CMS-Seite ändern. Die Einstellung <var>URL fixiert</var> unterbindet das: Wenn sie aktiv ist, wird die alte SEO URL beibehalten und keine neue SEO URL berechnet.",

'HELP_CONTENT_SEO_KEYWORDS'						=>	"Diese Stichwörter werden in den HTML-Quelltext (Meta Keywords) eingebunden. Diese Information wird von Suchmaschinen ausgewertet. Hier können Sie passende Stichwörter zu der CMS-Seite eingeben. Wenn Sie nichts eingeben, werden die Stichwörter automatisch erzeugt.",

'HELP_CONTENT_SEO_DESCRIPTION'					=>	"Dieser Beschreibungstext wird in den HTML-Quelltext (Meta Description) eingebunden. Dieser Text wird von vielen Suchmaschinen bei den Suchergebnissen angezeigt. Hier können Sie eine passende Beschreibung für die CMS-Seite eingeben. Wenn Sie nichts eingeben, wird die Beschreibung automatisch erzeugt.",



'HELP_DELIVERY_MAIN_COUNTRULES'					=>	"Mit dieser Einstellung können Sie auswählen, wie oft der Preis Auf-/Abschlag berechnet wird:<br />" .
                                                    "<ul><li>Einmal pro Warenkorb: Der Preis wird einmal für die gesamte Bestellung berechnet.</li>" .
                                                    "<li>Einmal pro unterschiedlichem Artikel: Der Preis wird für jeden unterschiedlichen Artikel im Warenkorb einmal berechnet. Wie oft ein Artikel bestellt wird, ist dabei egal.</li>" .
                                                    "<li>Für jeden Artikel: Der Preis wird für jeden Artikel im Warenkorb berechnet.</li></ul>",

'HELP_DELIVERY_MAIN_CONDITION'					=>	"Mit <var>Bedingung</var> können Sie einstellen, dass die Versandkostenregel nur für eine bestimmte Bedingung gültig ist. Sie können zwischen 4 Bedingungen wählen:<br />" .
                                                    "<ul><li>Menge: Anzahl aller Artikel im Warenkorb.</li>" .
                                                    "<li>Größe: Die Gesamtgröße aller Artikel.</li>" .
                                                    "<li>Gewicht: Das Gesamtgewicht der Bestellung in Kilogramm.</li>" .
                                                    "<li>Preis: Der Einkaufswert der Bestellung.</li></ul>" .
                                                    "Mit den Eingabefeldern <b>>=</b> (größer gleich) und <b><=</b> (kleiner gleich) können Sie den Bereich einstellen, für den die Bedingung gültig sein soll. Bei <b><=</b> muss eine größere Zahl als bei <b>>=</b> eingegeben werden.",

'HELP_DELIVERY_MAIN_PRICE'						=>	"Mit <var>Preis Auf-/Abschlag</var> können Sie eingeben, wie hoch die Versandkosten sind. Der Preis kann auf zwei verschiedene Arten berechnet werden:" .
                                                    "<ul>" .
                                                    "<li>Mit <kbd>abs</kbd> wird der Preis absolut angegeben (z. B.: Mit <kbd>6,90</kbd> werden 6,90 Euro berechnet).</li>" .
                                                    "<li>Mit <kbd>%</kbd> wird der Preis relativ zum Einkaufswert angegeben (z. B.: Mit <kbd>10</kbd> werden 10% des Einkaufswerts berechnet).</li>",

'HELP_DELIVERY_MAIN_ORDER'						=>	"Mit <var>Reihenfolge der Regelberechnung</var> können Sie festlegen, in welcher Reihenfolge die Versandkostenregeln berechnet werden: Die Versandkostenregel mit der kleinsten Zahl wird als erstes berechnet. Die Reihenfolge ist wichtig, wenn die Einstellung <var>Keine weiteren Regeln nach dieser berechnen</var> verwendet wird.",

'HELP_DELIVERY_MAIN_FINALIZE'					=>	"Mit <var>Keine weiteren Regeln nach dieser berechnen</var> können Sie einstellen, dass keine weitere Versandkostenregeln berechnet werden, falls diese Versandkostenregel gültig ist und berechnet wird. Für diese Einstellung ist die Reihenfolge wichtig, in der die Versandkostenregeln berechnet werden: Sie wird durch <var>Reihenfolge der Regelberechnung</var> festgelegt.",



'HELP_DELIVERYSET_MAIN_POS'						=>	"Mit <var>Sortierung</var> können Sie einstellen, in welcher Reihenfolge die Versandarten den Benutzern angezeigt werden:<br />" .
                                                    "<ul><li>Die Versandart mit der niedrigsten Zahl wird ganz oben angezeigt.</li>" .
                                                    "<li>Die Versandart mit der höchsten Zahl wird ganz unten angezeigt.</li></ul>",



'HELP_DISCOUNT_MAIN_PRICE'						=>	"Mit <var>Einkaufswert</var> können Sie einstellen, dass der Rabatt nur für bestimmte Einkaufswerte gültig ist. Wenn der Rabatt für alle Einkaufswerte gültig sein soll, dann geben Sie in <var>von</var> <kbd>0</kbd> ein und in <var>bis</var> <kbd>0</kbd> ein.",

'HELP_DISCOUNT_MAIN_AMOUNT'						=>	"Mit <var>Einkaufsmenge</var> können Sie einstellen, dass der Rabatt nur für bestimmte Einkaufsmengen gültig ist. Wenn Sie möchten, dass der Rabatt für alle Einkaufsmengen gültig ist, dann geben Sie in <var>von</var> <kbd>0</kbd> ein und in <var>bis</var> <kbd>0</kbd> ein.",

'HELP_DISCOUNT_MAIN_REBATE'						=>	"Bei <var>Rabatt</var> stellen Sie ein, wie hoch der Rabatt sein soll. Mit der Auswahlliste hinter dem Eingabefeld können Sie auswählen, ob der Rabatt absolut oder prozentual sein soll:" .
                                                    "<ul>" .
                                                    "<li><kbd>abs</kbd>: Der Rabatt ist absolut, z. B. 5 Euro.</li>" .
                                                    "<li><kbd>%</kbd>: Der Rabatt ist prozentual, z. B. 10 Prozent vom Einkaufswert.</li>" .
                                                    "</ul>",



'HELP_PAYMENT_MAIN_SORT'						=>	"Mit <var>Sortierung</var> können Sie einstellen, in welcher Reihenfolge die Zahlungsarten den Benutzern angezeigt werden:<br />" .
                                                    "<ul><li>Die Zahlungsart mit der niedrigsten Zahl wird an erster Stelle angezeigt.</li>" .
                                                    "<li>Die Zahlungsart mit der höchten Zahl wird an letzter Stelle angezeigt.</li></ul>",

'HELP_PAYMENT_MAIN_FROMBONI'					=>	"Hier können Sie einstellen, dass die Zahlungsarten nur Benutzern zur Verfügung stehen, die mindestens einen bestimmten Bonitätsindex haben. Den Bonitätsindex können Sie für jeden Benutzer unter <b><i><Benutzer verwalten -> Benutzer -> Erweitert</i></b> eingeben",

'HELP_PAYMENT_MAIN_SELECTED'					=>	"Mit <var>Ausgewählt</var> können Sie bestimmen, welche Zahlungsart als Standard ausgewählt sein soll, wenn die Benutzer zwischen den Zahlungsarten wählen können.",

'HELP_PAYMENT_MAIN_AMOUNT'						=>	"Mit <var>Einkaufswert</var> können Sie einstellen, dass die Zahlungsart nur für bestimmte Einkaufswerte gültig ist. Mit den Feldern <var>von</var> und <var>bis</var> können Sie den Bereich einstellen.<br />" .
                                                    "Wenn die Zahlungsart für jeden Einkaufswert gültig sein soll, müssen Sie eine Bedingung eingeben, die immer gültig ist: Geben sie in das Feld <var>von</var> <kbd>0</kdb> ein, in das Feld <var>bis</var> <kbd>999999999</kbd>.",

'HELP_PAYMENT_MAIN_ADDPRICE'					=>	"Bei <var>Preis Auf-/Abschlag</var> wird der Preis für die Zahlungsart eingegeben. Die Preise können auf zwei verschiedene Arten angegeben werden:" .
                                                    "<ul>" .
                                                    "<li>Mit <kbd>abs</kbd> wird der Preis absolut angegeben (z. B.: Wenn Sie <kbd>7,50</kbd> eingebem, werden 7,50 Euro berechnet.)</li>" .
                                                    "<li>Mit <kbd>%</kbd> wird der Preis relativ zum Einkaufspreis berechnet (z. B.: Wenn Sie <kbd>2</kbd> eingeben, werden 2 Prozent des Einkaufspreises)</li>",



'HELP_SELECTLIST_MAIN_IDENTTITLE'				=>	"Bei <var>Arbeitstitel</var> können Sie einen zusätzlichen Titel eingeben, der den Benutzern Ihres eShops nicht angezeigt wird. Sie können den Arbeitstitel dazu verwenden um ähnliche Auswahllisten zu unterscheiden (z. B. <i>Größe für Hosen</i> und <i>Größe für Hemden</i>).",

'HELP_SELECTLIST_MAIN_FIELDS'					=>	"In der Liste <var>Felder</var> werden alle vorhandenen Ausführungen der Auswahlliste angezeigt. Mit den Eingabefeldern rechts neben <var>Felder</var> können Sie neue Ausführungen anlegen. Weitere Informationen finden Sie im <a href=\"http://www.oxid-esales.com/de/resources/help-faq/eshop-manual/einfache-varianten-mit-auswahllisten-umsetzen\">eShop Handbuch</a>.",



'HELP_USER_MAIN_HASPASSWORD'					=>	"Hier wird angezeigt, ob der Benutzer ein Passwort hat. Daran können Sie unterscheiden, ob sich der Benutzer bei der Bestellung registriert hat:" .
                                                    "<ul><li>Wenn ein Passwort vorhanden ist, hat sich der Benutzer registriert.</li>" .
                                                    "<li>Wenn kein Passwort vorhanden ist, hat der Benutzer bestellt ohne sich zu registrieren.</li></ul>",



'HELP_USER_EXTEND_NEWSLETTER'					=>	"Diese Einstellung zeigt an, ob der Benutzer den Newsletter abonniert hat oder nicht.",

'HELP_USER_EXTEND_EMAILFAILED'					=>	"Wenn an die E-Mail Adresse des Benutzers keine E-Mails versendet werden können (z. B. weil die Adresse falsch eingetragen ist), dann setzen Sie hier das Häkchen. Dann werden dem Benutzer keine Newsletter mehr zugesendet. Andere E-Mails werden weiterhin versendet.",

'HELP_USER_EXTEND_DISABLEAUTOGROUP'				=>	"Die Benutzer werden automatisch zu Benutzergruppen zugeordnet. Wenn Sie diese Einstellung aktivieren, wird dieser Benutzer nicht mehr automatisch zu Benutzergruppen zugeordnet. Die automatischen Benutzergruppen-Zuordnungen werden im <a href=\"http://www.oxid-esales.com/de/resources/help-faq/eshop-manual/automatische-benutzergruppen-zuordnungen\">eShop Handbuch</a> auf der OXID eSales Website aufgelistet.",

'HELP_USER_EXTEND_BONI'							=>	"Hier können Sie einen Zahlenwert für die Bonität des Benutzers eingeben. Mit der Bonität können Sie beeinflussen, welche Zahlungsarten dem Benutzer zur Verfügung stehen.",



'HELP_MANUFACTURER_MAIN_ICON'					=>	"Bei <var>Icon</var> und <var>Hersteller-Icon hochladen</var> können Sie ein Bild für den Hersteller hochladen (z. B. das Logo des Herstellers). Wählen Sie bei <var>Hersteller-Icon hochladen</var> das Bild aus, das Sie hochladen möchten. Wenn Sie auf Speichern klicken, wird das Bild hochgeladen. Nachdem das Bild hochgeladen ist, wird der Dateiname des Bildes in <var>Icon</var> angezeigt.",



'HELP_MANUFACTURER_SEO_FIXED'					=>	"Sie können die SEO URLs vom eShop neu berechnen lassen. Eine Herstellerseite bekommt z. B. eine neue SEO URL, wenn Sie den Titel des Herstellers ändern. Die Einstellung <var>URL fixiert</var> unterbindet das: Wenn sie aktiv ist, wird die alte SEO URL beibehalten und keine neue SEO URL berechnet.",

'HELP_MANUFACTURER_SEO_SHOWSUFFIX'				=>	"Diese Einstellung bestimmt, ob das Suffix für den Fenstertitel angezeigt wird, wenn die Herstellerseite im eShop aufgerufen wird. Das Titel Suffix können Sie unter <em>Stammdaten -> Grundeinstellungen -> SEO -> Titel Suffix</em> einstellen.",

'HELP_MANUFACTURER_SEO_KEYWORDS'				=>	"Diese Stichwörter werden in den HTML-Quelltext (Meta Keywords) eingebunden. Diese Information wird von Suchmaschinen ausgewertet. Hier können Sie passende Stichwörter zu dem Hersteller eingeben. Wenn Sie nichts eingeben, werden die Stichwörter automatisch erzeugt.",

'HELP_MANUFACTURER_SEO_DESCRIPTION'				=>	"Dieser Beschreibungstext wird in den HTML-Quelltext (Meta Description) eingebunden. Dieser Text wird von vielen Suchmaschinen bei den Suchergebnissen angezeigt. Hier können Sie eine passende Beschreibung für den Hersteller eingeben. Wenn Sie nichts eingeben, wird die Beschreibung automatisch erzeugt.",


'HELP_VOUCHERSERIE_MAIN_DISCOUNT'				=>	"Bei <var>Rabatt</var> stellen Sie ein, wie hoch der Rabatt des Gutscheins sein soll sein soll. Mit der Auswahlliste hinter dem Eingabefeld können Sie auswählen, ob der Rabatt absolut oder prozentual sein soll:" .
                                                    "<ul>" .
                                                    "<li><kbd>abs</kbd>: Der Rabatt ist absolut, z. B. 5 Euro.</li>" .
                                                    "<li><kbd>%</kbd>: Der Rabatt ist prozentual, z. B. 10 Prozent vom Einkaufswert.</li>" .
                                                    "</ul>",

'HELP_VOUCHERSERIE_MAIN_ALLOWSAMESERIES'		=>	"Hier können Sie einstellen, ob Benutzer mehrere Gutscheine dieser Gutscheinserie bei einer Bestellung einlösen dürfen.",

'HELP_VOUCHERSERIE_MAIN_ALLOWOTHERSERIES'		=>	"Hier können Sie einstellen, ob Benutzer Gutscheine verschiedener Gutscheinserien bei einer Bestellung einlösen dürfen.",

'HELP_VOUCHERSERIE_MAIN_SAMESEROTHERORDER'		=>	"Hier können Sie einstellen, ob Benutzer Gutscheine dieser Gutscheinserie bei mehreren Bestellungen einlösen dürfen.",

'HELP_VOUCHERSERIE_MAIN_RANDOMNUM'				=>	"Wenn Sie diese Einstellung aktivieren, wird für jeden Gutschein eine Zufallsnummer erzeugt.",

'HELP_VOUCHERSERIE_MAIN_VOUCHERNUM'				=>	"Hier können Sie eine Gutscheinnummer eingeben. Diese wird verwendet wenn Sie neue Gutscheine anlegen. Wenn Sie mehrere Gutscheine anlegen, erhalten alle Gutscheine die gleiche Nummer.",

'HELP_WRAPPING_MAIN_PICTURE'					=>	"Bei <var>Bild</var> und <var>Bild hochladen</var> können Sie ein Bild für die Geschenkverpackung hochladen. Wählen Sie bei <var>Bild hochladen</var> das Bild aus, das Sie hochladen möchten. Wenn Sie auf Speichern klicken, wird das Bild hochgeladen. Nachdem das Bild hochgeladen ist, wird der Dateiname des Bildes in <var>Bild</var> angezeigt.",
);
