  <b>[{ oxmultilang ident="REPORT_CONVERSION_RATE" }]:</b>
  <br><br>

  [{if $oView->drawReport()}]
    <b>[{ oxmultilang ident="GENERAL_INSHOPPERMONTH" }]:</b>
    <br><br>
    <img src="[{$shop->selflink}]&cl=reports/report_conversion_rate&fnc=visitor_month&time_from=[{ $time_from }]&time_to=[{ $time_to }]" hspace="0" vspace="0" border="0" align="baseline" alt="">
    <br><br>
    <b>[{ oxmultilang ident="GENERAL_INSHOPPERWEEK" }]:</b>
    <br><br>
    <img src="[{$shop->selflink}]&cl=reports/report_conversion_rate&fnc=visitor_week&time_from=[{ $time_from }]&time_to=[{ $time_to }]" hspace="0" vspace="0" border="0" align="baseline" alt="">
  [{else}]
    <b>[{ oxmultilang ident="GENERAL_NODATA" }]</b>
  [{/if}]

  <br><br>
