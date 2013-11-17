<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta name="copyright" content="OXID eShop  © oxid eSales GmbH 2003,2004,2005,2006,2007,2008 - http://www.oxid-esales.com"><title>OXID eShop 4 {Standart}&nbsp;-&nbsp;Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link href="[{$BaseTplUrl}]oxid.css" rel="stylesheet" type="text/css">
</head>

  [{if $ActStyle }]
    <style>[{ $ActStyle }]</style>
  [{/if}]

  <body marginheight="0" marginwidth="0">

    [{if $ActBlocks.blFixedWidthLayout }]
      <div align="center"><div class="containertop_fixed" align="center">
    [{else}]
      <div class="containertop" align="center">
    [{/if}]

      <div class="boxheader">
        <table width="100%">
          <colgroup><col width="20%"><col width="80%"></colgroup>
          <tbody><tr height="100%">
            <td><a href="#"><img src="[{ if $ActLogo }]index.php?cl=looknfeel_preview_logo&standart[{else}][{$ResourceUrl}]/images/logo.jpg[{/if}]" class="logo_header"></a></td>
            <td align="right" valign="bottom">
              <table align="right" cellpadding="4">
                <tbody><tr>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">Kontakt</a></td>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">Gästebuch</a></td>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">AGB</a></td>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">Merkzettel</a></td>
                </tr>
                <tr>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">Hilfe</a></td>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">Links</a></td>
                  <td class="link_header">|&nbsp; <a href="#" class="link_header">Impressum</a></td>
                                      <td class="link_header">|&nbsp; <a href="#" class="link_header">Wunschzettel</a></td>
                                  </tr>
                              </tbody></table>
            </td>
          </tr>
        </tbody></table>
      </div>
      <div class="boxheadermenu">
        <div class="headermenu_fixedbox">

        [{if $ActBlocks.bl_perfLoadLanguages }]
        <a href="#" class="language_activ">Deutsch</a> | <a href="#" class="language">English</a>
        [{/if}]

              </div>
                <div class="headermenu_autobox"><a href="#" class="link_headermenu">Home</a></div>
        <div class="headermenu_autobox"><a href="#" class="link_headermenu">Newsletter</a></div>
        <div class="headermenu_autobox"><a href="#" class="link_headermenu">Mein Konto</a></div>
        <div class="headermenu_autobox" style="border-right: 0px none; border-bottom: 0px none;"></div>
        <div class="headermenu_autobox" style="float: right;"><a href="#" class="link_headermenu">Warenkorb</a></div>
      </div>
    </div>


    [{if $ActBlocks.blFixedWidthLayout }]
      <table cellpading="0" id="main_table_fixed" align="center" cellspacing="0">
    [{else}]
      <table cellpading="0" id="main_table" align="center" cellspacing="0">
    [{/if}]
      <tbody><tr>
        <td class="containerleft" valign="top">
          <div class="boxleft">Artikelsuche</div>
<div class="boxleft-content">
  <!-- ox_mod01 inc_leftitem -->
  <!-- ox_mod02 inc_leftitem -->
  <form  method="post" id="search_form" onsubmit="return false;">
    <input name="cl" value="search" type="hidden">
    <input name="searchparam" id="searchparam" value="" class="search_input" type="text">

    [{if $ActBlocks.bl_perfLoadCatTree }]

  <br>
  <select class="search_input" name="searchcnid"><option value=""> - alle Kategorien - </option><option value="8a142c3e4143562a5.46426637">Geschenke (32)</option><option value="8a142c3e49b5a80c1.23676990">- Bar-Equippment (13)</option><option value="8a142c3e4d3253c95.46563530">- Fantasy (5)</option><option value="8a142c3e44ea4e714.31136811">- Wohnen (4)</option><option value="8a142c3e60a535f16.78077188">--  Uhren (6)</option></select>
  <br>
  <select class="search_input" name="searchvendor"><option value=""> - alle Hersteller - </option><option value="68342e2955d7401e6.18967838">Haller Stahlwaren (5)</option><option value="77442e37fdf34ccd3.94620745">Bush</option></select>
    [{/if}]
<input value="GO!" class="search_go" type="submit">
  </form>


  [{if $ActBlocks.bl_perfLoadCatTree }]
            <img src="[{$ResourceUrl}]/images/hr_left.gif" class="hr_image">
    <div class="categorybox_rootcategory_exp">
      <a href="#" class="categorybox_rootcategory_exp"><img src="[{$ResourceUrl}]/images/rootcat_open.gif" class="categorybox_catpic" border="0">&nbsp;Geschenke (32)</a>
    </div>
                  <div class="categorybox_subcategory">&nbsp;&nbsp;&nbsp;<a href="#" class="categorybox_subcategory"><img src="[{$ResourceUrl}]/images/subcat.gif" class="categorybox_catpic" border="0">&nbsp;Bar-Equippment (13)</a></div>
              <div class="categorybox_subcategory">&nbsp;&nbsp;&nbsp;<a href="#" class="categorybox_subcategory"><img src="[{$ResourceUrl}]/images/subcat.gif" class="categorybox_catpic" border="0">&nbsp;Fantasy (5)</a></div>
              <div class="categorybox_subcategory">&nbsp;&nbsp;&nbsp;<a href="#" class="categorybox_subcategory"><img src="[{$ResourceUrl}]/images/subcat_more.gif" class="categorybox_catpic" border="0">&nbsp;Wohnen (4)</a></div>
                  <div class="categorybox_subcategory">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="categorybox_subcategory"><img src="[{$ResourceUrl}]/images/subcat.gif" class="categorybox_catpic" border="0">&nbsp;Uhren (6)</a></div>
                      <img src="[{$ResourceUrl}]/images/hr_left.gif" class="hr_image">
  <div class="categorybox_rootcategory">
    <a href="#" class="categorybox_rootcategorylink"><img src="[{$ResourceUrl}]/images/rootcat_closed.gif" class="categorybox_catpic" border="0">&nbsp;Nach Marke / Hersteller</a>
  </div>

  [{/if}]
    </div>


<!-- ox_mod03 inc_leftitem -->
<!-- ox_mod04 inc_leftitem -->

  [{if $ActBlocks.bl_perfShowLeftBasket}]
  <div class="boxleft"><a href="#" class="boxleft-td">Warenkorb</a></div>
  <div class="boxleft-content">
    <table>
  <tbody><tr>
    <td class="boxleft-td">Artikel:</td>
    <td class="boxleft-td">3</td>
  </tr>
  <tr>
    <td class="boxleft-td">Anzahl:</td>
    <td class="boxleft-td">3</td>
  </tr>
    <tr>
    <td class="boxleft-td"><b class="boxleft-td">Warenwert:</b></td>
    <td class="boxleft-td"><b class="boxleft-td">130,90 &#8364;</b></td>
  </tr>
</tbody></table>
    <img src="[{$ResourceUrl}]/images/hr_left.gif" class="hr_image">
    <div style="text-align: right;">
      <form  method="post" onsubmit="return false;">
        <input name="cl" value="basket" type="hidden">&nbsp;
        <a href="#"><img src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img" border="0"></a>
        <input value="Warenkorb anzeigen" class="warenkorb_button" type="submit">
      </form>
    </div>
  </div>
  [{/if}]

  [{if $ActBlocks.bl_perfLoadCurrency && $ActBlocks.bl_perfShowLeftBasket}]
  <div class="boxleft">Währung</div>
  <div class="boxleft-content">
          <a href="#" class="currency_activ">EUR</a>
     |     <a href="#" class="currency">GBP</a>
     |     <a href="#" class="currency">CHF</a>
    </div>
  [{/if}]

<div class="boxleft">Informationen</div>
<div class="boxleft-content">
  <table cellpadding="0" cellspacing="3">

  <tbody><tr>
    <td><img src="[{$ResourceUrl}]/images/arrow_info.gif" alt="">&nbsp;<a href="#" class="boxleft-td">Kundeninformationen</a></td>
  </tr>
  <tr>
    <td><img src="[{$ResourceUrl}]/images/arrow_info.gif" alt="">&nbsp;<a href="#" class="boxleft-td">Wie bestellen?</a></td>
  </tr>
  <tr>
    <td><img src="[{$ResourceUrl}]/images/arrow_info.gif" alt="">&nbsp;<a href="#" class="boxleft-td">Versand und Kosten</a></td>
  </tr>
  <tr>
    <td><img src="[{$ResourceUrl}]/images/arrow_info.gif" alt="">&nbsp;<a href="#" class="boxleft-td">Datenschutz</a></td>
  </tr>
    <tr>
    <td><img src="[{$ResourceUrl}]/images/arrow_info.gif" alt="">&nbsp;<a href="#" class="boxleft-td">Newsletter</a></td>
  </tr>
  </tbody></table>
</div>

<!-- ox_mod05 inc_leftitem -->
<!-- ox_mod06 inc_leftitem -->


<div class="boxleft">Partner und Siegel</div>
<div class="boxleft-content" style="padding-left: 0px; padding-right: 0px;" align="center">
  <table cellpading="0" cellspacing="0">
    <tbody><tr><td>&nbsp;</td></tr>
    <tr>
      <td align="center">
                  <form name="formSiegel" method="get"  onsubmit="return false;">
  <input src="[{$CommonResourceUrl}]/images/trusted-empfehlung.gif" alt="Trusted Shops G�tesiegel - Bitte hier klicken." border="0" type="image">
  <input name="shop_id" value="XXX" type="hidden">
</form>
              </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td align="center">
</td>
    </tr>
  </tbody></table>
</div>
<!-- ox_mod07 inc_leftitem -->
<!-- ox_mod08 inc_leftitem -->
        </td>
        <td style="height: 100%; width: 100%;" valign="top">
          <table cellpading="0" style="height: 100%; width: 100%;" align="center" cellspacing="0">
            <tbody><tr>
              <td colspan="2" style="height: 1%;" valign="top">
                <div class="locationrow">
                <table style="width: 100%;" cellpading="0" cellspacing="0">
                  <tbody><tr>
                    <td align="left" valign="bottom"> Sie sind hier: / Home</td>
                                          <td align="right" valign="bottom"> <a href="#">Alle Preise inkl. MwSt., zzgl. Versandkosten. </a></td>
                                      </tr>
                </tbody></table>
                </div>
              </td>
            </tr>
            <tr>
            <td class="containermain" valign="top">

<!-- ox_mod01 start -->
<div class="containerhalfrow">
  <div class="productrow_noborder">
    <font size="3"><strong>Willkommen</strong> <strong>Hans Mustermann </strong><br>
</font>
<div><strong>&nbsp;</strong></div>
Dies ist eine Demo-Installation des <strong>OXID eShop 4</strong>.
Also keine Sorge, wenn Sie bestellen: Die&nbsp;Ware wird weder
ausgeliefert, noch in Rechnung gestellt. Die gezeigten Produkte (und
Preise) dienen nur zur Veranschaulichung der umfangreichen
Funktionalität des Systems.
<div><strong>&nbsp;</strong></div>
<div><strong>Wir wünschen viel Spass beim testen!</strong></div>
<div><strong>Ihr OXID eSales Team</strong></div>
  </div>
</div>

      <div class="containerhalfrow">
      <div class="producttitlerow_blue">
                        Angebot der Woche
                  </div>
    <div class="productrow_borderaftertitle_half">



          <table>
        <tbody><tr>
          <td valign="top">
            <div class="product_image_xxs_container">
              <a href="#">
                <img src="[{$CommonResourceUrl}]/products/1849_th.jpg" alt="Bar Butler 6 BOTTLES" class="product_image">
              </a>
            </div>
          </td>
          <td valign="top">
            <form name="basket"  method="post"  onsubmit="return false;">
              <input name="cl" value="start" type="hidden">
              <input name="fnc" value="tobasket" type="hidden">
              <input name="aid" value="1849" type="hidden">
              <input name="anid" value="1849" type="hidden">
              <input name="cnid" value="" type="hidden">
              <input name="pgNr" value="-1" type="hidden">
              <input name="am" value="1" type="hidden">
                            <div class="product_title" title="Bar Butler 6 BOTTLES ">
                <a href="#">Bar Butler 6 BOTTLES</a>
                <div class="product_artnr">Art.Nr.: 1849</div>
              </div>
              <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                                <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                                            <div class="product_variant">
                              </div>

                              <div class="product_price_new">89,90 &#8364;<sup><a href="#">*</a></sup></div>

                              <input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="in den Warenkorb" class="warenkorb_button2" onclick="showBasketWnd();" type="submit">

            </form>
          </td>
        </tr>
      </tbody></table>



  </div>
</div>


  <div class="containerfullrow">
      <div class="producttitlerow_red">
                        <table width="100%">
            <colgroup><col valign="top" width="49%"><col valign="top" width="51%"></colgroup>
            <tbody><tr>
              <td class="producttitlecell_red">
                                  UNSER SCHNÄPPCHEN!
                              </td>
              <td class="producttitle_description">
                Gültig bis 31.12.2005. Solange Vorrat reicht
              </td>
            </tr>
          </tbody></table>
                          </div>
    <div class="productrow_borderaftertitle_full">


          <table width="100%">
        <colgroup><col span="2" valign="top" width="50%"></colgroup>
        <tbody><tr>
          <td align="center" valign="middle">
            <div class="product_image_xxxs_container">
              <a href="#">
                <img src="[{$CommonResourceUrl}]/products/1964_p1.jpg" alt="Original BUSH Beach Radio" class="product_image">
              </a>
            </div>
          </td>
          <td valign="middle">
            <form name="basket"  method="post" onsubmit="return false;">
              <input name="cl" value="start" type="hidden">
              <input name="fnc" value="tobasket" type="hidden">
              <input name="aid" value="1964" type="hidden">
              <input name="anid" value="1964" type="hidden">
              <input name="cnid" value="" type="hidden">
              <input name="pgNr" value="-1" type="hidden">
              <input name="am" value="1" type="hidden">
              <div class="product_title_big">
                <a href="#">Original BUSH Beach Radio</a>
                <div class="product_artnr">Art.Nr.: 1964</div>
              </div>
              <div class="product_description">Das Original aus Filmen wie "Eis am Stil" &amp; Co.</div>
              <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a> &nbsp;
                                                <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br><br>

                              <table>
                  <tbody><tr>
                    <td colspan="2" valign="top">
                      <span class="product_price_old_text">Statt</span> <span class="product_price_old">89,90 &#8364;</span>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top">
                      <span class="product_price_new_text">jetzt nur</span>
                    </td>
                    <td valign="top">
                      <span class="product_price_new_big">79,90 &#8364;<sup><a href="#">*</a></sup></span><br>
                    </td>
                  </tr>
                </tbody></table>

                                              <input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="in den Warenkorb" class="warenkorb_button2" onclick="showBasketWnd();" type="submit">
                          </form>
          </td>
        </tr>
      </tbody></table>




  </div>
</div>

<!-- ox_mod02 start -->


  <div class="containerfullrow">
  <div class="productcategoryrow">
    Die Dauerbrenner
  </div>
</div>

              <div class="containerhalfrow">

      <div class="productrow_fullborder_half">


          <table>
        <tbody><tr>
          <td valign="top">
            <div class="product_image_xxs_container">
              <a href="#">
                <img src="[{$CommonResourceUrl}]/products/2077_th.jpg" alt="Tischlampe SPHERE" class="product_image">
              </a>
            </div>
          </td>
          <td valign="top">
            <form name="basket"  method="post" onsubmit="return false;">
              <input name="cl" value="start" type="hidden">
              <input name="fnc" value="tobasket" type="hidden">
              <input name="aid" value="2077" type="hidden">
              <input name="anid" value="2077" type="hidden">
              <input name="cnid" value="" type="hidden">
              <input name="pgNr" value="-1" type="hidden">
              <input name="am" value="1" type="hidden">
                            <div class="product_title" title="Tischlampe SPHERE ">
                <a href="#">Tischlampe SPHERE</a>
                <div class="product_artnr">Art.Nr.: 2077</div>
              </div>
              <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                                <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                                            <div class="product_variant">
                                  Variante: :<br>
                  <select name="aid" class="variant_select"><option value="8a142c4113f3b7aa3.13470399">violett</option><option value="8a142c410f55ed579.98106125">rot</option><option value="8a142c4100e0b2f57.59530204">orange</option></select>
                              </div>

                              <div class="product_price_new">19,00 &#8364;<sup><a href="#">*</a></sup></div>

                              <input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="in den Warenkorb" class="warenkorb_button2" onclick="showBasketWnd();" type="submit">

            </form>
          </td>
        </tr>
      </tbody></table>



  </div>
</div>
                      <div class="containerhalfrow">

      <div class="productrow_fullborder_half">


          <table>
        <tbody><tr>
          <td valign="top">
            <div class="product_image_xxs_container">
              <a href="#">
                <img src="[{$CommonResourceUrl}]/products/1651_th.jpg" alt="Bierbrauset PROSIT" class="product_image">
              </a>
            </div>
          </td>
          <td valign="top">
            <form name="basket"  method="post" onsubmit="return false;">
              <input name="cl" value="start" type="hidden">
              <input name="fnc" value="tobasket" type="hidden">
              <input name="aid" value="1651" type="hidden">
              <input name="anid" value="1651" type="hidden">
              <input name="cnid" value="" type="hidden">
              <input name="pgNr" value="-1" type="hidden">
              <input name="am" value="1" type="hidden">
                            <div class="product_title" title="Bierbrauset PROSIT ">
                <a href="#">Bierbrauset PROSIT</a>
                <div class="product_artnr">Art.Nr.: 1651</div>
              </div>
              <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                                <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                                            <div class="product_variant">
                              </div>

                              <div class="product_price_new"><span style="font-size: 50%;">ab</span> 23,00 &#8364;<sup><a href="#">*</a></sup></div>

                              <input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="in den Warenkorb" class="warenkorb_button2" onclick="showBasketWnd();" type="submit">

            </form>
          </td>
        </tr>
      </tbody></table>



  </div>
</div>

<!-- ox_mod03 start -->

  <div class="containerfullrow">
  <div class="productcategoryrow">
    Frisch eingetroffen!
  </div>
</div>

  <div class="containerquarterrow">

      <div class="productrow_fullborder_quarter">




          <div class="product_image_xs_container">
        <a href="#">
          <img src="[{$CommonResourceUrl}]/products/2028_th.jpg" alt="Wanduhr EXIT" class="product_image" align="middle">
        </a>
      </div>

      <form name="basket"  method="post" onsubmit="return false;">
        <input name="cl" value="start" type="hidden">
        <input name="fnc" value="tobasket" type="hidden">
        <input name="aid" value="d8842e3cb356356f4.93820547" type="hidden">
        <input name="anid" value="d8842e3cb356356f4.93820547" type="hidden">
        <input name="cnid" value="" type="hidden">
        <input name="pgNr" value="-1" type="hidden">
        <input name="am" value="1" type="hidden">

                <div class="product_title" title="Wanduhr EXIT ">
          <a href="#">Wanduhr EXIT</a>
          <div class="product_artnr">Art.Nr.: 2028</div>
        </div>

        <div class="product_links">
          <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                    <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                              </div>

                  <div class="product_price_new">22,00 &#8364;<sup><a href="#">*</a></sup></div>

                  <div align="center"><input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="Warenkorb" class="warenkorb_mini_button2" onclick="showBasketWnd();" type="submit"></div>

        <div><!-- IE fix --></div>

      </form>

  </div>
</div>
  <div class="containerquarterrow">

      <div class="productrow_fullborder_quarter">




          <div class="product_image_xs_container">
        <a href="#">
          <img src="[{$CommonResourceUrl}]/products/2024_th.jpg" alt="Popcornschale PINK" class="product_image" align="middle">
        </a>
      </div>

      <form name="basket"  method="post" onsubmit="return false;">
        <input name="cl" value="start" type="hidden">
        <input name="fnc" value="tobasket" type="hidden">
        <input name="aid" value="2024" type="hidden">
        <input name="anid" value="2024" type="hidden">
        <input name="cnid" value="" type="hidden">
        <input name="pgNr" value="-1" type="hidden">
        <input name="am" value="1" type="hidden">

                <div class="product_title" title="Popcornschale PINK ">
          <a href="#">Popcornschale PINK</a>
          <div class="product_artnr">Art.Nr.: 2024</div>
        </div>

        <div class="product_links">
          <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                    <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                              </div>

                  <div class="product_price_new">11,00 &#8364;<sup><a href="#">*</a></sup></div>

                  <div align="center"><input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="Warenkorb" class="warenkorb_mini_button2" onclick="showBasketWnd();" type="submit"></div>

        <div><!-- IE fix --></div>

      </form>

  </div>
</div>
  <div class="containerquarterrow">

      <div class="productrow_fullborder_quarter">




          <div class="product_image_xs_container">
        <a href="#">
          <img src="[{$CommonResourceUrl}]/products/1432_th.jpg" alt="Badeschaum Tainted Love" class="product_image" align="middle">
        </a>
      </div>

      <form name="basket"  method="post" onsubmit="return false;">
        <input name="cl" value="start" type="hidden">
        <input name="fnc" value="tobasket" type="hidden">
        <input name="aid" value="be642cada8422f150.29332483" type="hidden">
        <input name="anid" value="be642cada8422f150.29332483" type="hidden">
        <input name="cnid" value="" type="hidden">
        <input name="pgNr" value="-1" type="hidden">
        <input name="am" value="1" type="hidden">

                <div class="product_title" title="Badeschaum Tainted Love ">
          <a href="#">Badeschaum Tainted<br>Love</a>
          <div class="product_artnr">Art.Nr.: 1432</div>
        </div>

        <div class="product_links">
          <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                    <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                              </div>

                  <div class="product_price_new">15,90 &#8364;<sup><a href="#">*</a></sup></div>

                  <div align="center"><input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="Warenkorb" class="warenkorb_mini_button2" onclick="showBasketWnd();" type="submit"></div>

        <div><!-- IE fix --></div>

      </form>

  </div>
</div>
  <div class="containerquarterrow">

      <div class="productrow_fullborder_quarter">




          <div class="product_image_xs_container">
        <a href="#">
          <img src="[{$CommonResourceUrl}]/products/1952_th.jpg" alt="Hangover Pack LITTLE HELPER" class="product_image" align="middle">
        </a>
      </div>

      <form name="basket"  method="post" onsubmit="return false;">
        <input name="cl" value="start" type="hidden">
        <input name="fnc" value="tobasket" type="hidden">
        <input name="aid" value="1952" type="hidden">
        <input name="anid" value="1952" type="hidden">
        <input name="cnid" value="" type="hidden">
        <input name="pgNr" value="-1" type="hidden">
        <input name="am" value="1" type="hidden">

                <div class="product_title" title="Hangover Pack LITTLE HELPER ">
          <a href="#">Hangover Pack<br>LITTLE HELPER</a>
          <div class="product_artnr">Art.Nr.: 1952</div>
        </div>

        <div class="product_links">
          <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                    <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                              </div>

                  <div class="product_price_new">6,00 &#8364;<sup><a href="#">*</a></sup></div>

                  <div align="center"><input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="Warenkorb" class="warenkorb_mini_button2" onclick="showBasketWnd();" type="submit"></div>

        <div><!-- IE fix --></div>

      </form>

  </div>
</div>

              <div class="containerfullrow">
  <div class="productcategoryrow">
    Kategorien
  </div>
</div>
                            <div class="containerhalfrow">
      <div class="producttitlerow_blue">
              <a href="#" class="fontgray1">          Bar-Equippment (13)
        </a>          </div>
    <div class="productrow_borderaftertitle_half">



          <table>
        <tbody><tr>
          <td valign="top">
            <div class="product_image_xxs_container">
              <a href="#">
                <img src="[{$CommonResourceUrl}]/products/1126_th.jpg" alt="Bar-Set ABSINTH" class="product_image">
              </a>
            </div>
          </td>
          <td valign="top">
            <form name="basket"  method="post" onsubmit="return false;">
              <input name="cl" value="start" type="hidden">
              <input name="fnc" value="tobasket" type="hidden">
              <input name="aid" value="1126" type="hidden">
              <input name="anid" value="1126" type="hidden">
              <input name="cnid" value="" type="hidden">
              <input name="pgNr" value="-1" type="hidden">
              <input name="am" value="1" type="hidden">
                            <div class="product_title" title="Bar-Set ABSINTH ">
                <a href="#">Bar-Set ABSINTH</a>
                <div class="product_artnr">Art.Nr.: 1126</div>
              </div>
              <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                                <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                                            <div class="product_variant">
                              </div>

                              <div class="product_price_new">34,00 &#8364;<sup><a href="#">*</a></sup></div>

                              <input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="in den Warenkorb" class="warenkorb_button2" onclick="showBasketWnd();" type="submit">

            </form>
          </td>
        </tr>
      </tbody></table>



  </div>
</div>
                                    <div class="containerhalfrow">
      <div class="producttitlerow_blue">
              <a href="#" class="fontgray1">          Geschenke (32)
        </a>          </div>
    <div class="productrow_borderaftertitle_half">



          <table>
        <tbody><tr>
          <td valign="top">
            <div class="product_image_xxs_container">
              <a href="#">
                <img src="[{$CommonResourceUrl}]/products/1873_th.jpg" alt="Purse GLAM" class="product_image">
              </a>
            </div>
          </td>
          <td valign="top">
            <form name="basket"  method="post" onsubmit="return false;">
              <input name="cl" value="start" type="hidden">
              <input name="fnc" value="tobasket" type="hidden">
              <input name="aid" value="be642cad637adf214.28850610" type="hidden">
              <input name="anid" value="be642cad637adf214.28850610" type="hidden">
              <input name="cnid" value="" type="hidden">
              <input name="pgNr" value="-1" type="hidden">
              <input name="am" value="1" type="hidden">
                            <div class="product_title" title="Purse GLAM ">
                <a href="#">Purse GLAM</a>
                <div class="product_artnr">Art.Nr.: 1873</div>
              </div>
              <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> mehr Info</a><br>
                                                <a class="details" href="#"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> vergleichen</a><br>
                                            <div class="product_variant">
                              </div>

                              <div class="product_price_new">14,90 &#8364;<sup><a href="#">*</a></sup></div>

                              <input name="goButton" src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img2" onclick="showBasketWnd();" border="0" type="image"><input value="in den Warenkorb" class="warenkorb_button2" onclick="showBasketWnd();" type="submit">

            </form>
          </td>
        </tr>
      </tbody></table>



  </div>
</div>

<!-- ox_mod04 start -->


              </td>
              <td class="containerright" style="height: 100%;" valign="top">
                <table style="height: 100%; border-collapse: collapse;">

  <!-- ox_mod01 inc_rightitem -->
  <!-- ox_mod02 inc_rightitem -->

      <tbody>

      [{if $ActBlocks.bl_perfShowRightBasket}]
      <tr>
      <td>
        <div class="boxrightmyaccountborder">
          <div class="boxrightmyaccount">
            <a href="#" class="boxrightmyaccount-td">Warenkorb</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="[{$ResourceUrl}]/images/cart.gif" height="11" width="13">
          </div>
          <div class="boxrightmyaccount-content">
              <table>
          <tbody><tr>
        <td>
          <div class="product_image_s_container">
            <a href="#"><img src="[{$CommonResourceUrl}]/products/1964_ico.jpg" border="0"></a>
          </div>
        </td>
        <td class="boxrightmyaccount-td" valign="top">
          <a class="boxrightmyaccount-td" href="#">Original BUSH B...</a><br>
          ( 1 Stk )
        </td>
      </tr>
          <tr>
        <td>
          <div class="product_image_s_container">
            <a href="#"><img src="[{$CommonResourceUrl}]/products/1651_ico.jpg" border="0"></a>
          </div>
        </td>
        <td class="boxrightmyaccount-td" valign="top">
          <a class="boxrightmyaccount-td" href="#">Bierbrauset PRO...</a><br>
          ( 1 Stk )
        </td>
      </tr>
          <tr>
        <td>
          <div class="product_image_s_container">
            <a href="#"><img src="[{$CommonResourceUrl}]/products/2028_ico.jpg" border="0"></a>
          </div>
        </td>
        <td class="boxrightmyaccount-td" valign="top">
          <a class="boxrightmyaccount-td" href="#">Wanduhr EXIT </a><br>
          ( 1 Stk )
        </td>
      </tr>
      </tbody></table>
  <img src="[{$ResourceUrl}]/images/hr_right.gif" class="hr_image">

<table>
  <tbody><tr>
    <td class="boxrightmyaccount-td">Artikel:</td>
    <td class="boxrightmyaccount-td">3</td>
  </tr>
  <tr>
    <td class="boxrightmyaccount-td">Anzahl:</td>
    <td class="boxrightmyaccount-td">3</td>
  </tr>
    <tr>
    <td class="boxrightmyaccount-td"><b>Warenwert:</b></td>
    <td class="boxrightmyaccount-td"><b>130,90 &#8364;</b></td>
  </tr>
</tbody></table>
            <img src="[{$ResourceUrl}]/images/hr_right.gif" class="hr_image">
            <div style="text-align: right;">
              <form  method="post" onsubmit="return false;">
                <input name="cl" value="basket" type="hidden">
                &nbsp; <a href="#"><img src="[{$ResourceUrl}]/images/tobasket_button.gif" class="warenkorb_img" border="0"></a>
                <input value="Warenkorb anzeigen" class="warenkorb_button" type="submit">
              </form>
            </div>
          </div>
        </div>
      </td>
    </tr>
    [{/if}]

    [{if $ActBlocks.bl_perfLoadCurrency && $ActBlocks.bl_perfShowRightBasket}]
      <tr>
      <td>
        <div class="boxrightmyaccountborder">
          <div class="boxrightmyaccount">Währung</div>
          <div class="boxrightmyaccount-content">
                  <a href="#" class="currency_activ">EUR</a>
     |     <a href="#" class="currency">GBP</a>
     |     <a href="#" class="currency">CHF</a>
            </div>
        </div>
      </td>
    </tr>
    [{/if}]

  <!-- ox_mod03 inc_rightitem -->
  <!-- ox_mod04 inc_rightitem -->

  <tr>
    <td>
      <div class="boxrightmyaccountborder">
        <div class="boxrightmyaccount"><a href="#" class="boxrightmyaccount-td">Mein Konto</a></div>
        <div class="boxrightmyaccount-content">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
      <td class="boxrightmyaccount-td">
        Sie sind angemeldet als:<br>
                <b>"admin"</b> <br>
        (Hans Mustermann)
      </td>
    </tr>
    <tr>
      <td align="right">
        <form  method="post" onsubmit="return false;">
          <input name="fnc" value="logout" type="hidden">
          <input name="cl" value="start" type="hidden">
          <input name="cnid" value="" type="hidden">
          <input name="redirect" value="1" type="hidden">
          <input name="lang" value="0" type="hidden">
          <input name="send" value="Abmelden" class="login_button" type="submit">
        </form>
      </td>
    </tr>
  </tbody></table>

<table cellpadding="0" cellspacing="0" width="100%">



</table>
<br></div>
      </div>
    </td>
  </tr>

      <tr>
      <td>
        <div class="boxrightmyaccountborder">
          <div class="boxrightmyaccount"><a href="#" class="boxrightmyaccount-td">Newsletter</a></div>
          <div class="boxrightmyaccount-content"><form  method="post" onsubmit="return false;">
  <input name="fnc" value="fill" type="hidden">
  <input name="cl" value="newsletter" type="hidden">
  <input name="editval[oxuser__oxcountry]" value="Deutschland" type="hidden">
    <table cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
      <td class="boxrightmyaccount-td">eMail:&nbsp; &nbsp; </td>
      <td class="boxrightmyaccount-td"> <input name="editval[oxuser__oxusername]" value="" size="20" class="login_input" type="text"></td>
    </tr>
    <tr>
      <td></td>
      <td><input name="send" value="Abonnieren" class="login_button" type="submit"></td>
    </tr>
  </tbody></table>
</form>
<br></div>
        </div>
      </td>
    </tr>

  <!-- ox_mod05 inc_rightitem -->
  <!-- ox_mod06 inc_rightitem -->

      <tr>
      <td>
        <div class="boxrightproductborder">
          <div class="boxrightproduct">TOP of the Shop</div>
          <div class="boxrightproduct-content"><table>
      <tbody><tr>
      <td>
        <div class="product_image_s_container">
          <a href="#">
            <img src="[{$CommonResourceUrl}]/products/2080_ico.jpg" border="0">
          </a>
        </div>
      </td>
      <td class="boxrightproduct-td">
        <a href="#" class="boxrightproduct-td">Barzange PROFI </a>
                  <br><b> 17,00 &#8364;<sup><a href="#">*</a></sup></b>
              </td>
    </tr>
      <tr>
      <td>
        <div class="product_image_s_container">
          <a href="#">
            <img src="[{$CommonResourceUrl}]/products/1351_ico.jpg" border="0">
          </a>
        </div>
      </td>
      <td class="boxrightproduct-td">
        <a href="#" class="boxrightproduct-td">Kühlwürfel NORDIC&nbsp;...</a>
                  <br><b> 12,00 &#8364;<sup><a href="#">*</a></sup></b>
              </td>
    </tr>
      <tr>
      <td>
        <div class="product_image_s_container">
          <a href="#">
            <img src="[{$CommonResourceUrl}]/products/1940_ico.jpg" border="0">
          </a>
        </div>
      </td>
      <td class="boxrightproduct-td">
        <a href="#" class="boxrightproduct-td">Schale SCHALLPLATTE </a>
                  <br><b> 12,00 &#8364;<sup><a href="#">*</a></sup></b>
              </td>
    </tr>
      <tr>
      <td>
        <div class="product_image_s_container">
          <a href="#">
            <img src="[{$CommonResourceUrl}]/products/2000_ico.jpg" border="0">
          </a>
        </div>
      </td>
      <td class="boxrightproduct-td">
        <a href="#" class="boxrightproduct-td">Wanduhr ROBOT </a>
                  <br><b> 29,00 &#8364;<sup><a href="#">*</a></sup></b>
              </td>
    </tr>
  </tbody></table>
<br></div>
        </div>
      </td>
    </tr>

      <tr>
      <td>
        <div class="boxrightproductborder">
          <div class="boxrightproduct">Schnäppchen</div>
          <div class="boxrightproduct-content">
            <span class="boxrightproduct-td"><table> <tbody><tr><td>
<div class="product_image_s_container"><a href="#"><img alt="" src="[{$CommonResourceUrl}]/products/2061_ico.jpg" border="0"></a></div> </td><td class="boxrightproduct-td"> <a href="#" class="boxrightproduct-td"><strong>Herzkissen<br>
PLAYBOY</strong></a><br>
  <a href="#" class="details" onclick="showBasketWnd();"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> Jetzt bestellen! </a>  </td></tr> </tbody><tbody><tr><td>
<div class="product_image_s_container"><a href="#"><img alt="" src="[{$CommonResourceUrl}]/products/1771_ico.jpg" border="0"></a></div> </td><td class="boxrightproduct-td"> <a href="#" class="boxrightproduct-td"><strong>Wanduhr DIGITAL</strong></a><br>
  <a href="#" class="details" onclick="showBasketWnd();"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> Jetzt bestellen! </a>  </td></tr> </tbody><tbody><tr><td>
<div class="product_image_s_container"><a href="#"><img alt="" src="[{$CommonResourceUrl}]/products/1876_ico.jpg" border="0"></a></div> </td><td class="boxrightproduct-td"> <a href="#" class="boxrightproduct-td"><strong>Barwagen LOUNGE</strong></a><br>
  <a href="#" class="details" onclick="showBasketWnd();"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> Jetzt bestellen! </a>  </td></tr> </tbody><tbody><tr><td>
<div class="product_image_s_container"><a href="#"><img alt="" src="[{$CommonResourceUrl}]/products/1431_ico.jpg" border="0"></a></div> </td><td class="boxrightproduct-td"> <a href="#" class="boxrightproduct-td"><strong>Eiswürfel HERZ</strong></a><br>
  <a href="#" class="details" onclick="showBasketWnd();"><img src="[{$ResourceUrl}]/images/arrow_details.gif" alt="" border="0"> Jetzt bestellen! </a>  </td></tr></tbody></table></span>
          </div>
        </div>
      </td>
    </tr>


  <!-- ox_mod07 inc_rightitem -->
  <!-- ox_mod08 inc_rightitem -->




  <!-- ox_mod09 inc_rightitem -->
  <!-- ox_mod10 inc_rightitem -->

  <tr>
    <td style="height: 100%;">
              <table class="boxrightproductborder" style="height: 100%;"><tbody><tr><td class="boxrightproduct-content" style="height: 100%;"></td></tr></tbody></table>
          </td>
  </tr>

  <!-- ox_mod11 inc_rightitem -->
  <!-- ox_mod12 inc_rightitem -->
</tbody></table>
              </td>
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>

    [{if $ActBlocks.blFixedWidthLayout }]
      <div class="containerbottom_fixed" align="center">
    [{else}]
      <div class="containerbottom" align="center">
    [{/if}]

      <div class="boxfooter">
        <div id="delivery_link" style="float: left; padding-left: 200px;"><a href="#">* Alle Preise inkl. MwSt., zzgl. Versandkosten. </a></div>
        <img src="[{$ResourceUrl}]/images/barrcode.gif" align="middle"> © <a href="#" >Shop software von OXID eSales</a> &nbsp;
      </div>
      <div class="boxfootermenu">
        <a href="#" class="link_footer">Home</a>
        | <a href="#" class="link_footer">Kontakt</a>
        | <a href="#" class="link_footer">Hilfe</a>
        | <a href="#" class="link_footer">Gästebuch</a>
        | <a href="#" class="link_footer">Links</a>
        | <a href="#" class="link_footer">Impressum</a>
        | <a href="#" class="link_footer">AGB</a>
        | <a href="#" class="link_footer">Kundeninformationen</a>
        <br>
        <a href="#" class="link_footer">Warenkorb</a>
        | <a href="#" class="link_footer">Mein&nbsp;Konto</a>
        | <a href="#" class="link_footer"> Mein&nbsp;Merkzettel </a>
                  | <a href="#" class="link_footer"> Mein&nbsp;Wunschzettel </a>
                          <br><br>
        <div style="float: left;"><img src="[{$CommonResourceUrl}]/images/cc.jpg"></div>
        <div style="float: right;"><a href="#"><img src="[{$CommonResourceUrl}]/images/oxid_powered.jpg" alt="Shopsoftware und Shopsysteme von OXID eSales" border="0" height="30" width="80"></a></div>
      </div>
    </div>
      [{if $ActBlocks.blFixedWidthLayout }]
      </div>
      [{/if}]
      </body></html>
