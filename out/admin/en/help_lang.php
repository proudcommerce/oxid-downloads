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

'HELP_SHOP_SYSTEM_OTHERCOUNTRYORDER'			=> 	"Here you can set if orders can be made in countries for which no shipping costs are defined:" .
                                                    "<ul><li>If the setting is checked, users can order: The users are notified that they are informed about the shipping costs manually.</li>" .
                                                    "<li>If the setting is unchecked, users from countries for which no shipping costs are defined cannot order.</li></ul>",

'HELP_SHOP_SYSTEM_DISABLENAVBARS'				=>	"If this setting is checked, most navigation elements aren't shown during checkout. Thereby users aren't distracted unnecessarily during checkout.",

'HELP_SHOP_SYSTEM_DEFAULTIMAGEQUALITY'			=>	"Recommended settings are from 40-80:<br />" .
                                                    "<ul><li>Under 40, the compression gets clearly visible and the pictures are blurred.</li>".
                                                    "<li>Above 80 hardly any quality improvement can be detected, but the filesize increases enormously.</li></ul><br />".
                                                    "The default value is 75.",

'HELP_SHOP_SYSTEM_SHOWVARIANTREVIEWS'			=>	"This setting affects how reviews for variants are handled: If the setting is checked, remarks from variants are also shown at the parent product.",

'HELP_SHOP_SYSTEM_VARIANTSSELECTION'			=>	"In eShop there are many lists for assigning products, e.g. assigning products to discounts. If this setting is checked, variants are shown in these lists, too.",

'HELP_SHOP_SYSTEM_VARIANTPARENTBUYABLE'			=>	"This setting affects if parent products can be bought:" .
                                                    "<ul><li>If the setting is checked, the parent products can be bought, too.</li>" .
                                                    "<li>If the setting is unchecked, only variants of the parent product can be bought.</li></ul>",

'HELP_SHOP_SYSTEM_VARIANTINHERITAMOUNTPRICE'	=>	"Here you can set whether scales prices are inherited from the parent product: If the setting is checked, the scale prices of the parent product are also used for its variants.",

'HELP_SHOP_SYSTEM_ISERVERTIMESHIFT'				=>	"The server the eShop is running on can be in a different time zone. With this setting the time shift can be adjusted: Enter the amount of hours that are to be added/subtracted from the server time, e. g. <kdb>+2</kdb> or <kdb>-2</kdb>",

'HELP_SHOP_SYSTEM_INLINEIMGEMAIL'				=>	"If the setting is checked, the pictures in e-mails are sent together with the e-mail. If the setting is unchecked, the pictures are downloaded by the e-mail program when the e-mail is opened.",



'HELP_SHOP_CONFIG_TOPNAVILAYOUT'				=>	"Usually, the category navigation is shown on the left. If this setting is checked, the category navigation is shown at top instead.",

'HELP_SHOP_CONFIG_ORDEROPTINEMAIL'				=>	"If double-opt-in is active, users get an e-mail with a confirmation link when they register for the newsletter. Only if this confirmation link is used the user is registered for the newsletter.<br />" .
                                                    "Double-opt-in protects users from unwanted registrations. Without double-opt-in, any e-mail address can be registered for the newsletter. With double-opt-in, the owner of the e-mail address has to confirm the registration.",

'HELP_SHOP_CONFIG_BIDIRECTCROSS'				=>	"With crossselling you can offer fitting products for a product: If e.g. to a car tires are assigned as crossselling product, the tires are shown with the car.<br />" .
                                                    "If bidirectional crossselling is activated, it works in both directions: The car is shown with the tires, too.",

'HELP_SHOP_CONFIG_ICONSIZE'						=>	"Icons are the smallest pictures of a product. Icons are used: <br />" .
                                                    "<ul><li>in the shopping cart.</li>" .
                                                    "<li>if products are shown in the right menu (e.g. in <i>TOP of the Shop</i> and <i>Bargain</i>).</li></ul>" .
                                                    "For avoiding design problems caused by too big icons the icons are resized. You can enter the max. size for icons here." ,

'HELP_SHOP_CONFIG_THUMBNAILSIZE'				=>  "Thumbnails are small product pictures. Thumbnails are used:<br />" .
                                                    "<ul><li>in product lists.</li>" .
                                                    "<li>in Promotions displayed in the middle of the front page, e. g. <i>Just arrived!</i>.</li></ul>" .
                                                    "For avoiding design problems caused by too big thumbnails the thumbnails are resized. You can enter the max. size for thumbnails here.",

'HELP_SHOP_CONFIG_STOCKONDEFAULTMESSAGE'		=>	"For each product you can set up a message if the product is on stock.<br />" .
                                                    "If this setting is active, a message is shown if no specific message for for a product is entered. The default message <i>Ready for shipping</i> is shown.",

'HELP_SHOP_CONFIG_STOCKOFFDEFAULTMESSAGE'		=>	"For each product you can set up a message if the product is not in stock.<br />" .
                                                    "If this setting is active, a message is shown if no specific message for for a product is entered. The default message <i>This item is not in stock and must be back-ordered</i> is shown.",

'HELP_SHOP_CONFIG_OVERRIDEZEROABCPRICES'		=>	"You can set up special prices for specific users: For each product you can enter A, B and C prices. If users are in the user group <i>Price A</i>, the A price is shown to them instead of the normal price.<br />" .
                                                    "If this setting is checked, the normal product price is used if no A, B or C price is available.<br />" .
                                                    "You should activate this setting if you are using A, B and C prices: Otherwise 0,00 is displayed to the according users if no A, B or C price is set.",

'HELP_SHOP_CONFIG_SEARCHFIELDS'					=>	"Here you can define the database fields in which the product search searches. Enter one field per row.<br />" .
                                                    "The most common entries are:" .
                                                    "<ul><li>oxtitle = Title</li>" .
                                                    "<li>oxshortdesc = Short description</li>" .
                                                    "<li>oxsearchkeys = Search terms entered for each product</li>" .
                                                    "<li>oxartnum = Product number</li>" .
                                                    "<li>oxtags	= Tags entered for each product</li></ul>",

'HELP_SHOP_CONFIG_SORTFIELDS'					=>	"Here you can define the database fields which can be used for sorting product lists. Enter one field per row.<br />" .
                                                    "The most common entries are:" .
                                                    "<ul><li>oxtitle = Title</li>" .
                                                    "<li>oxprice = Price</li>" .
                                                    "<li>oxvarminprice = The lowest price if variants with different prices are used.</li>" .
                                                    "<li>oxartnum = Product numbers</li>" .
                                                    "<li>oxrating = Rating of the products</li>" .
                                                    "<li>oxstock = Stock</li></ul>",

'HELP_SHOP_CONFIG_MUSTFILLFIELDS'				=>	"Here you can set the mandatory fields for user registration. Enter one field per row.<br />" .
                                                    "The most common entries are:" .
                                                    "<ul><li>oxuser__oxfname = First name</li>" .
                                                    "<li>oxuser__oxlname = Last name</li>" .
                                                    "<li>oxuser__oxstreet = Street</li>" .
                                                    "<li>oxuser__oxstreetnr = House number</li>" .
                                                    "<li>oxuser__oxzip = ZIP</li>" .
                                                    "<li>oxuser__oxcity = City</li>" .
                                                    "<li>oxuser__oxcountryid = Country</li>" .
                                                    "<li>oxuser__oxfon = Telephone number</li></ul><br />" .
                                                    "You can also define the mandatory fields if users enter a different delivery address. The most common entries are:" .
                                                    "<ul><li>oxaddress__oxfname = First name</li>" .
                                                    "<li>oxaddress__oxlname = Last name</li>" .
                                                    "<li>oxaddress__oxstreet = Street</li>" .
                                                    "<li>oxaddress__oxstreetnr = House number</li>" .
                                                    "<li>oxaddress__oxzip = ZIP</li>" .
                                                    "<li>oxaddress__oxcity = City</li>" .
                                                    "<li>oxaddress__oxcountryid = Country</li>" .
                                                    "<li>oxaddress__oxfon = Telephone number</li></ul>",

'HELP_SHOP_CONFIG_USENEGATIVESTOCK'				=>	"With <var>Allow negative Stock Values</var> you can define how stock levels are calculated of products are out of stock:<br />" .
                                                    "<ul><li>If the setting is checked, negative stock values are calculated if further units are bought.</li>" .
                                                    "<li>If the setting is unchecked, the stock value never falls below 0, even if further units are bought.</li></ul>",

'HELP_SHOP_CONFIG_NEWARTBYINSERT'  				=>	"On the front page of your eShop the newest products are shown in <i>Just arrived!</i>. With this setting you define how the newest products are calculated: by date of creation or by date of last change.",

'HELP_SHOP_CONFIG_LOAD_DYNAMIC_PAGES'			=>	"If this setting is checked, additional information about other oxid products is shown in the menu, e.g. about OXID eFire. Which information is loaded depends on the location of your eShop.",

'HELP_SHOP_CONFIG_DELETERATINGLOGS'				=>	"If users rate a product, they cannot rate the product again. Here you can set after how many days users are allowed to rate a product again. Leave empty to disable - products can be rated only once per user.",



'HELP_SHOP_PERF_NEWESTARTICLES'					=>	"A list of newest products are shown in <i>Just arrived!</i>. Here you can set how the list is generated:" .
                                                    "<ul><li><kbd>inactive</kbd>: The list is not shown.</li>" .
                                                    "<li><kbd>manual</kbd>: You can define the products in <em>Customer Info -> Promotions -></em> in the promotion <i>Just arrived!</i>.</li>" .
                                                    "<li><kbd>automatic</kbd>: The products are calculated automatically.</li></ul>",

'HELP_SHOP_PERF_TOPSELLER'						=>	"A list of most often sold products is shown in <i>Top of the Shop</i>. Here you can set how the list is generated:" .
                                                    "<ul><li><kbd>inactive</kbd>: The list is not shown.</li>" .
                                                    "<li><kbd>manual</kbd>: You can define the products in <em>Customer Info -> Promotions -></em> in the promotion <i>Top of the Shop</i>.</li>" .
                                                    "<li><kbd>automatic</kbd>: The products are calculated automatically.</li></ul>",

'HELP_SHOP_PERF_LOADFULLTREE'					=>	"If this setting is checked, the complete category tree is shown in the category navigation (all categories are expanded). This only works if the category navigation is not shown at top.",

'HELP_SHOP_PERF_LOADACTION'						=>	"If this setting is checked, promotions like <i>Just arrived!</i> and <i>Top of the Shop</i> are loaded and shown.",

'HELP_SHOP_PERF_LOADREVIEWS'					=>	"Users can rate and comment products. If this setting is checked, the existing reviews/comments are loaded and shown with the product.",

'HELP_SHOP_PERF_USESELECTLISTPRICE'				=>	"In selection lists surcharges/discounts can be set up. If this setting is checked, the surcharges/discounts are loaded and applied. If unchecked, the surcharges/discounts aren't applied.",

'HELP_SHOP_PERF_DISBASKETSAVING'				=>	"The shopping cart of registered users is saved. When they visit your eShop again, the shopping cart contents are loaded. If you activate this setting, the shopping carts aren't saved any more.",

'HELP_SHOP_PERF_LOADDELIVERY'					=>	"If you deactivate this setting, no shipping costs are calculated: The shipping costs are always 0.00 EUR.",

'HELP_SHOP_PERF_LOADPRICE'						=>	"If you deactivate this setting, no product prices are calculated: No prices are shown.",

'HELP_SHOP_PERF_PARSELONGDESCINSMARTY'			=>	"If this setting is active, the descriptions of products and categories are parsed trough Smarty: You can use Smarty tags (e. g. for using variables) <br />",

'HELP_SHOP_PERF_LOADATTRIBUTES'					=>	"Normally attributes are only loaded in the detail view of a product. If the setting is active, the attributes are always loaded with a product.<br />" .
                                                    "This setting can be useful if you want to adept templates, e. g. showing the attributes in product lists also.",

'HELP_SHOP_PERF_LOADSELECTLISTSINALIST'			=>	"Normally selection lists are only shown in the detail view of a product. If you activate this setting, the selection lists are also shown in product lists (e. g. search results, categories).",



'HELP_SHOP_SEO_TITLEPREFIX'						=>	"Each page has a title. this title is shown in the top bar of the browser window. With <var>Title Prefix</var> and <var>Title Suffix</var> you can fill in text before and after page titles:<br />" .
                                                    "<ul><li>In <var>Title Prefix</var>, enter the text to be displayed in front of the title.</li></ul>",

'HELP_SHOP_SEO_TITLESUFFIX'						=>	"Each page has a title. this title is shown in the top bar of the browser window. With <var>Title Prefix</var> and <var>Title Suffix</var> you can fill in text before and after page titles:<br />" .
                                                    "<ul><li>In <var>Title Suffix</var> enter the text to be displayed behind the title.</li></ul>",

'HELP_SHOP_SEO_IDSSEPARATOR'					=>	"The separator is used if category names and product names consist of several words. The separator is used instead of spaces, e.g. www.youreshop.com/category-name-of-several-words<br />" .
                                                    "If no separator is entered, - is used.",

'HELP_SHOP_SEO_SAFESEOPREF'						=>	"If several products have the same name and are in the same category, they would get the same SEO URL. For avoiding this, the SEO Suffix is attached. If no SEO Suffix is defined, <i>oxid</i> is used.",

'HELP_SHOP_SEO_REPLACECHARS'					=>	"Some special characters like German umlauts should be removed from URLs. They can cause problems. Here you can define how they are replaced. The syntax is <code>special character => replacement character</code>, e.g. <code>Ü => Ue</code>.<br />" .
                                                    "For German, the replacements are already entered.",

'HELP_SHOP_SEO_RESERVEDWORDS'					=>	"Some URLs are defined in OXID eShop, like www.youreshop.com/admin for accessing eShop admin. If a category was named <i>admin</i> the SEO URL would be www.youreshop.com/admin too - the category couldn't be accessed. Therefore the SEO suffix is attached to these URLs. You can define here which URLs are suffixed automatically.",

'HELP_SHOP_SEO_SKIPTAGS'						=>	"If no META tags are defined for products and categories, the META tags are created automatically. thereby very common words can be omitted. All words entered here are omitted when creating the META tags.",

'HELP_SHOP_SEO_STATICURLS'						=>	"For special pages (e. g. general terms and conditions) you can enter fixed SEO URLs. When selecting a static URL, the normal URL is shown in <var>Standard URL</var>. In the input fields below you can define a SEO URL for each language.",



'HELP_SHOP_MAIN_PRODUCTIVE'						=>	"If this setting is <b>not</b> active, information about execution times and debug information are displayed  at the bottom of each page. These information is useful when customizing eShop.<br />" .
                                                    "<b>Activate this setting when the eShop is launched. Thereby only the eShop without additional information is displayed to your users.</b>",

'HELP_SHOP_MAIN_ACTIVE'							=>	"With <var>Active</var> you can enable/disable the complete eShop. If the eShop is disabled, a message saying the eShop is temporary offline is displayed to the users. This can be useful for maintenance.",

'HELP_SHOP_MAIN_INFOEMAIL'						=>	"All e-mails sent via the contact page are sent to this e-mail address.",

'HELP_SHOP_MAIN_ORDEREMAIL'						=>	"When users order they receive an email with a summary of the order. Answers to this e-mail are sent to <var>Order E-mail reply</var>.",

'HELP_SHOP_MAIN_OWNEREMAIL'						=>	"When users order, you recieve an e-mail with a summary of the order. These e-mails are sent to <var>Order E-mails to</var>.",



'HELP_ARTICLE_MAIN_ALDPRICE'					=>	"With <var>Alt. Prices</var> you can set up special prices for certain users. More information is available in the <a href=\"http://www.oxid-esales.com/de/resources/help-faq/eshop-manual/set-alternative-prices-special-users\">eShop Manual</a> on the OXID eSales website.",



'HELP_ARTICLE_EXTEND_UNITQUANTITY'				=>	"With <var>Quantity</var> and <var>Unit</var> you can display the price per quantity unit (e. g. 1.43 EUR per liter). In <var>Quantity</var>, enter the amount of the product (e. g. <kbd>1.5</kbd>), in <var>Unit</var> the according quantity unit (e. g. <kbd>liter</kbd>). The price per quantity unit is calculated and displayed with the product.",

'HELP_ARTICLE_EXTEND_EXTURL'					=>	"In <var>External URL</var> you can enter a link where further information about the product is available (e. g. on the manufacturer's website). In <var>Text for external URL</var> you can enter the text which is linked, e .g. <kbd>Further information on the manufacturer's website</kbd>.",

'HELP_ARTICLE_EXTEND_TPRICE'					=>	"In <var>RRP</var> you can enter the recommended retail price of the manufacturer. If you enter the RRP it is shown to the users: Above the product price <i>Reduced from RRP now only</i> is displayed.",

'HELP_ARTICLE_EXTEND_QUESTIONEMAIL'				=>	"At <var>Alt. Contact</var> you can enter an e-mail address. If users submit questions on this product, they will be sent to this e-mail address. If no e-mail address is entered, the query will be send to the normal info e-mail address.",

'HELP_ARTICLE_EXTEND_SKIPDISCOUNTS'				=>	"If <var>Skip all negative discounts</var> is active, negative allowances will not be calculated for this product. These include discounts and vouchers.",



'HELP_ARTICLE_STOCK_STOCKFLAG'					=>	"At <var>Delivery status</var> you can select from 4 settings:" .
                                                    "<ul><li><kbd>Standard</kbd>: The product can then also be ordered if it is sold out.</li>" .
                                                    "<li><kbd>External storehouse</kbd>: The product can always be purchased and is always displayed as <i>in stock</i>. (The stock level cannot be given for external storehouse. Therefore, the product is always shown as <i>in stock</i>).</li>" .
                                                    "<li><kbd>If out of stock, offline</kbd>: The product is not displayed if it is sold out.</li>" .
                                                    "<li><kbd>If out of stock, not orderable</kbd>: The product is displayed if it is sold out but it cannot be ordered.</li></ul>",

'HELP_ARTICLE_STOCK_REMINDACTIV'				=>	"With <var>Send e-mail if stock falls below value</var> you can specify that an e-mail will be sent as soon as the stock level falls below the value entered. Select the check box and then enter the level at which you want to be notified.",

'HELP_ARTICLE_STOCK_DELIVERY'					=>	"Here you can enter the date when the product will be available again if it is sold out. The format is year-month-day, e. g. 2009-02-16.",



'HELP_ARTICLE_SEO_FIXED'						=>	"You can let the eShop recalculate the SEO URLs. A product page gets a new SEO URL if e. g. the title of the product has changed. The setting <var>Fixed URL</var> prevents this: If it is active, the old SEO URL is kept and no new SEO URL is calculated.",

'HELP_ARTICLE_SEO_KEYWORDS'						=>	"These keywords are integrated in the HTML sourcecode of the product page (META keywords). This information is used by search engines. Suitable keywords for the product can be entered here. If it's left blank, the keywords are generated automatically.",

'HELP_ARTICLE_SEO_DESCRIPTION'					=>	"This description is integrated in the HTML sourcecode of the product page (META description). This text is often displayed in result pages of search engines. A suitable description can be entered here. If it's left blank, the description is generated automatically.",

'HELP_ARTICLE_SEO_ACTCAT'						=>	"You can define several SEO URLs for products: For certain categories and manufacturer pages. With <var>Active Category/Vendor</var> you can select the SEO URL you want to edit.",



'HELP_CATEGORY_MAIN_HIDDEN'						=>	"With <var>Hidden</var> <ou can define if this category is shown to users. If a category is hidden it is not shown to the users, even if it is active.",

'HELP_CATEGORY_MAIN_PARENTID'					=>	"In <var>Subcategory Of</var> you specify the point at which the category is to appear:<br />" .
                                                    "<ul><li>If the category is not to be a subcategory of any other category, then select <kbd>--</kbd> Off.</li>" .
                                                    "<li>If the category is to be a subcategory of another category, then select the appropriate category.</li></ul>",

'HELP_CATEGORY_MAIN_EXTLINK'					=>	"With <var>External Link</var>, you can enter a link that opens when users click on the category. <strong>Use this function only if you want to display a link in the category navigation. It causes the category to lose its normal function!</strong>",

'HELP_CATEGORY_MAIN_PRICEFROMTILL'				=>	"With <var>Price From/To</var> you can specify that <strong>all</strong> products in a certain price range are shown in this category. Enter the lower limit in the first entry field and the upper limit in the second entry field. Then <strong>all products of the eShop</strong> within this price range are shown in this category.",

'HELP_CATEGORY_MAIN_DEFSORT'					=>	"With <var>Fast Sorting</var> you specify the manner in which the products in the category will be sorted. To learn about the available options, refer to <a href=\"http://www.oxid-esales.com/en/resources/help-faq/eshop-manual/sorting-products\">the eShop manual</a> an the OXID eSales website.",

'HELP_CATEGORY_MAIN_SORT'						=>	"You can use <var>Sorting</var> to define the order in which categories are displayed: The category with the lowest number is displayed at the top, and the category with the highest number at the bottom.",

'HELP_CATEGORY_MAIN_THUMB'						=>	"With <var>Picture</var> and <var>Upload Picture</var> you can upload a picture for this category. The picture is shown at top of the category is viewed. Select the picture in <var>Upload Picture</var>. When clicking on <var>Save</var>, th picture is uploaded. After uploading, the filename of the picture is shown in <var>Picture</var>.",

'HEOLP_CATEGORY_MAIN_SKIPDISCOUNTS'				=>	"<li>If <var>Skip all negative discounts</var> is active, negative allowances will not be calculated for any products in this category.",



'HELP_CATEGORY_SEO_FIXED'						=>	"You can let the eShop recalculate the SEO URLs. A category page gets a new SEO URL if e. g. the title of the category has changed. The setting <var>Fixed URL</var> prevents this: If it is active, the old SEO URL is kept and no new SEO URL is calculated.",

'HELP_CATEGORY_SEO_KEYWORDS'					=>	"These keywords are integrated in the HTML sourcecode of the category page (META keywords). This information is used by search engines. Suitable keywords for the category can be entered here. If it's left blank, the keywords are generated automatically.",

'HELP_CATEGORY_SEO_DESCRIPTION'					=>	"This description is integrated in the HTML sourcecode of the category page (META description). This text is often displayed in result pages of search engines. A suitable description can be entered here. If it's left blank, the description is generated automatically.",

'HELP_CATEGORY_SEO_SHOWSUFFIX'					=>	"With this setting you can specify if the title suffix is shown in the browser window title when the category page is opened. The title suffix can be set in <em>Master Settings -> Core Settings -> SEO -> Title Suffix</em>.",



'HELP_CONTENT_MAIN_SNIPPET'						=>	"If you select <var>Snippet</var> you can include this CMS page within other CMS pages by its ident: [{ oxcontent ident=\"ident_of_the_cms_page\" }]",

'HELP_CONTENT_MAIN_MAINMENU'					=>	"If you select <var>Upper Menu</var>, a link to this CMS page is shown in the upper menu (At Terms and About Us).",

'HELP_CONTENT_MAIN_CATEGORY'					=>	"If you select <var>Category</var>, a link to this CMS page is shown in the category navigation below the other categories.",

'HELP_CONTENT_MAIN_MANUAL'						=>	"If you select <var>Manual</var>, a link is created which you can use to include this CMS page in other CMS pages. The link is shown below when you click on <var>Save</var>",



'HELP_CONTENT_SEO_FIXED'						=>	"You can let the eShop recalculate the SEO URLs. A CMS page gets a new SEO URL if e. g. the title of the CMS page has changed. The setting <var>Fixed URL</var> prevents this: If it is active, the old SEO URL is kept and no new SEO URL is calculated.",

'HELP_CONTENT_SEO_KEYWORDS'						=>	"These keywords are integrated in the HTML sourcecode of the CMS page (META keywords). This information is used by search engines. Suitable keywords for the CMS page can be entered here. If it's left blank, the keywords are generated automatically.",

'HELP_CONTENT_SEO_DESCRIPTION'					=>	"This description is integrated in the HTML sourcecode of the CMS page (META description). This text is often displayed in result pages of search engines. A suitable description can be entered here. If it's left blank, the description is generated automatically.",



'HELP_DELIVERY_MAIN_COUNTRULES'					=>	"Under <var>Calculation Rules</var> you can select how often the price is calculated:" .
                                                    "<ul><li><kbd>Once per cart</kbd>: Price is calculated once for the entire order.</li>" .
                                                    "<li><kbd>Once for each different product</kbd>: Price is calculated once for each different product in the shopping cart. It makes no difference what quantity of a product is ordered.</li>" .
                                                    "<li><kbd>For each product</kbd>: price is calculated for each product in the shopping cart.</li></ul>",

'HELP_DELIVERY_MAIN_CONDITION'					=>	"In <var>Condition</var> you can specify that the shipping cost rule applies only to a certain condition. You can choose from among 4 conditions:" .
                                                    "<ul><li><kbd>Amount</kbd>: Number of products in the shopping car.</li>" .
                                                    "<li><kbd>Size</kbd>: Total size of all products. In order for this setting to be used properly, the size must be entered for products.</li>" .
                                                    "<li><kbd>Weight</kbd>: Total weight of the order in kilograms. In order for this setting to be used properly, the weight must be entered for products.</li>" .
                                                    "<li><kbd>Price</kbd>: Purchase price of the order.</li></ul>" .
                                                    "You can use the entry fields <var>>=</var> (greater than or equal to) and <var><=</var> (less than or equal to) to specify the range to which the condition is to apply. A larger number must be entered for <var><=</var> than for <var>=></var>.",

'HELP_DELIVERY_MAIN_PRICE'						=>	"You can use <var>Price Surcharge/Discount</var> to specify the magnitude of the shipping costs. The price can be calculated in two different ways:" .
                                                    "<ul><li>With <kbd>abs</kbd>, the price is specified absolutely (e.g.: with <kbd>6.90</kbd>, a price of EUR 6.90 is calculated).</li>" .
                                                    "<li>With <kbd>%</kbd>, the price is specified relative to the purchase price (e.g.: With <kbd>10</kbd>, a price of 10% of the purchase price is calculated).</li></ul>",

'HELP_DELIVERY_MAIN_ORDER'						=>	"You can use <var>Order of rule processing</var> to specify the order in which the shipping cost rules will be run. The shipping cost rule with the lowest number is run first. The order is important if the setting <var>Don't calculate further rules if this rule matches</var> is used.",

'HELP_DELIVERY_MAIN_FINALIZE'					=>	"You can use <var>Don't calculate further rules if this rule matches</var> to specify that no further rules are to be run if this shipping cost rule is valid and is being run. For this option, the order in which the shipping cost rules are run is important. It is specified through the <var>Order of Rule processing</var>.",



'HELP_DELIVERYSET_MAIN_POS'						=>	"<var>Sorting</var> specifies the order in which the shipping methods are displayed to users: The shipping method with the lowest number is displayed at the top.",



'HELP_DISCOUNT_MAIN_PRICE'						=>	"You can use <var>Purchase Price</var> to specify that the discount is only valid for certain purchase prices. If the discount is to be valid for all purchase prices, enter <kbd>0</kbd> in <var>From</var> and <kbd>0</kbd> in <var>To</var>.",

'HELP_DISCOUNT_MAIN_AMOUNT'						=>	"You can use <var>Quantity</var> to specify that the discount is only valid for certain purchase quantities. If you want the discount to be valid for all purchase quantities, enter <kbd>0</kbd> in <var>From</var> and <kbd>0</kbd> in <var>To</var>.",

'HELP_DISCOUNT_MAIN_REBATE'						=>	"In <var>Discount</var>, you specify the magnitude of the discount. You can use the selection list after the entry field to specify whether the discount is to be applied as an absolute discount or as a percentage discount:" .
                                                    "<ul><li><kbd>abs</kbd>: The discount is an absolute discount, e.g. EUR 5.</li>" .
                                                    "<li><kbd>%</kbd>: The discount is a percentage discount, e.g. 10 percent of the purchase price.</li>",



'HELP_PAYMENT_MAIN_SORT'						=>	"In <var>Sorting</var> you can specify the order in which the payment methods are to be displayed to users: The payment method with the lowest sort number is displayed on top.",

'HELP_PAYMENT_MAIN_FROMBONI'					=>	"You can use <var>Min. Credit Rating</var> to specify that payment methods are only available to users who have a certain credit index or higher. You can enter the credit rating for each user in <em>Administer Users -> Users -> Extended</em>.",

'HELP_PAYMENT_MAIN_SELECTED'					=>	"You can use <var>Selected</var> to define which payment method is be selected as the default method if the user can choose between several payment methods.",

'HELP_PAYMENT_MAIN_AMOUNT'						=>	"You can use <var>Purchase Price</var> to specify that the payment method is only valid for certain purchase prices. The <var>from</var> and <var>to</var> fields allow you to set a range.<br />" .
                                                    "If the payment method is to be valid for any purchase price, you must specify a condition that is always met: Enter <kbd>0</kbd> in the <var>from</var>  and <kbd>99999999</kbd> in the <var>to</var> field.",

'HELP_PAYMENT_MAIN_ADDPRICE'					=>	"In <var>Price Surcharge/Discount</var>, the price is entered for the payment method. The price can be specified in two different ways:" .
                                                    "<ul><li>With <kbd>abs</kbd> the price is entered for the payment method (e.g.: if you enter <kbd>7.50</kbd> a price of EUR 7.50 is calculated.)</li>" .
                                                    "<li>With <kbd>%</kbd>, the price is calculated relative to the purchase price (e.g.: if you enter <kbd>2</kbd>, the price is 2 percent of the purchase price)</li></ul>",


'HELP_SELECTLIST_MAIN_IDENTTITLE'				=>	"In <var>Working Title</var>, you can enter an additional name that is not displayed to users of your eShop. You can use the working title to differentiate between similar selection lists (e.g., Sizes for trousers and Sizes for shirts).",

'HELP_SELECTLIST_MAIN_FIELDS'					=>	"All available options are displayed in the <var>Fields</var> list. You can use the entry fields to the right to set up new options. Further information is available in the <a href=\"http://www.oxid-esales.com/en/resources/help-faq/eshop-manual/implementing-simple-variants-selection-lists\">eShop manual</a> on the OXID eSales website.",



'HELP_USER_MAIN_HASPASSWORD'					=>	"Here you can distinguish if users registered when ordering:" .
                                                    "<ul><li>If a password is set, the user registered.</li>" .
                                                    "<li>If no password is set, the user ordered without registering.</li></ul>",



'HELP_USER_EXTEND_NEWSLETTER'					=>	"This setting shows if the user subscribed to the newsletter.",

'HELP_USER_EXTEND_EMAILFAILED'					=>	"If no e-mails can be sent to the e-mail address of this user, check this setting. Then no newsletters are sent to this user any more. Other e-mails are still sent.",

'HELP_USER_EXTEND_DISABLEAUTOGROUP'				=>	"Users are automatically assigned to certain user groups. This setting prevents this: If checked, the users isn't automatically added to any user groups.",

'HELP_USER_EXTEND_BONI'							=>	"Here you can enter a numerical value for the credit rating of the user. With the credit rating you can influence which payment methods are available to this user.",



'HELP_MANUFACTURER_MAIN_ICON'					=>	"With <var>Icon</var> and <var>Upload Icon</var> you can upload a picture for this manufacturer (e. g. the logo).In <var>Upload Icon</var>, select the Picture you want to upload. When clicking on <var>Save</var> the picture is uploaded. After uploading, the filename is shown in <var>Icon</var>.",



'HELP_MANUFACTURER_SEO_FIXED'					=>	"You can let the eShop recalculate the SEO URLs. A manufacturer page gets a new SEO URL if e. g. the title of the manufacturer has changed. The setting <var>Fixed URL</var> prevents this: If it is active, the old SEO URL is kept and no new SEO URL is calculated.",

'HELP_MANUFACTURER_SEO_KEYWORDS'				=>	"These keywords are integrated in the HTML sourcecode of the manufacturer page (META keywords). This information is used by search engines. Suitable keywords for the manufacturer can be entered here. If left blank, the keywords are generated automatically.",

'HELP_MANUFACTURER_SEO_DESCRIPTION'				=>	"This description is integrated in the HTML sourcecode of the manufacturer page (META description). This text is often displayed in result pages of search engines. A suitable description can be entered here. If left blank, the description is generated automatically.",

'HELP_MANUFACTURER_SEO_SHOWSUFFIX'				=>	"With this setting you can specify if the title suffix is shown in the browser window title when the manufacturer page is opened. The title suffix can be set in <em>Master Settings -> Core Settings -> SEO -> Title Suffix</em>.",



'HELP_VOUCHERSERIE_MAIN_DISCOUNT'				=>	"In <var>Discount</var>, you specify the magnitude of the discount. You can use the selection list after the entry field to specify whether the discount is to be applied as an absolute discount or as a percentage discount:" .
                                                    "<ul><li><kbd>abs</kbd>: The discount is an absolute discount, e.g. EUR 5.</li>" .
                                                    "<li><kbd>%</kbd>: The discount is a percentage discount, e.g. 10 percent of the purchase price.</li>",



'HELP_VOUCHERSERIE_MAIN_ALLOWSAMESERIES'		=>	"Here you can set whether users are allowed to use several coupons of this coupon series in a single order.",

'HELP_VOUCHERSERIE_MAIN_ALLOWOTHERSERIES'		=>	"Here you can set if users are allowed to use coupons together with coupons of other coupon series in a single order.",

'HELP_VOUCHERSERIE_MAIN_SAMESEROTHERORDER'		=>	"Here you can set if users can use coupons of this coupon series in multiple orders.",

'HELP_VOUCHERSERIE_MAIN_RANDOMNUM'				=>	"If this setting is active a random number is calculated for each coupon.",

'HELP_VOUCHERSERIE_MAIN_VOUCHERNUM'				=>	"Here you can enter a coupon number. This number is used when creating new coupons if <var>Random Numbers</var> is deactivated. All Coupons get the same coupon number.",

'HELP_WRAPPING_MAIN_PICTURE'					=>	"With <var>Picture</var> and <var>Upload Picture</var> you can upload a picture for the gift wrapping. In <var>Upload Picture</var>, select the picture to upload. When clicking on <var>Save</var>, the picture is uploaded. After uploading, the filename is shown in <var>Picture</var>.",
);