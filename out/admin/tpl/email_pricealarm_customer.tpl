<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_PRICEALARMIN" }][{ $shop->oxshops__oxname->value }]</title>
<meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
</head>
<body bgcolor="#FFFFFF" link="#355222" alink="#355222" vlink="#355222" style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;">
<br>
<img src="[{$shopImageDir}]logo_white.gif" border="0" hspace="0" vspace="0" alt="[{ $shop->oxshops__oxname->value }]" align="texttop"><br>
<br>
[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_HY" }]<br>
<br>
[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_HAVEPRICEALARM" }] [{ $shop->oxshops__oxname->value }]!<br>
<br>
[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_ITEM1" }] [{ $product->oxarticles__oxtitle->value }] [{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_ITEM2" }] [{ $bidprice }] [{ $currency->sign}]
[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_ITEM3" }] [{ $product->fprice }] [{ $currency->sign }] [{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_ITEM4" }]<br>
<br>
[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_CLICKHERE1" }]<a href="[{$shop->basedir}]index.php?cl=details&anid=[{ $product->oxarticles__oxid->value }]" style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;"><b>[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_CLICKHERE2" }]</b></a>.
<br>
<br>
[{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_TEAM1" }] [{ $shop->oxshops__oxname->value }] [{ oxmultilang ident="EMAIL_PRICEALARM_CUSTOMER_TEAM2" }]<br>
<br><br>
[{ oxcontent ident="oxemailfooter" }]
</body>
</html>

