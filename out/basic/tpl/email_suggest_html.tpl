<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <title>[{ oxmultilang ident="EMAIL_SUGGEST_HTML_PRODUCTPOSTCARDFROM" }] [{ $shop->oxshops__oxname->value }]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
  </head>
  <body marginwidth="0" marginheight="0">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CECCCD">
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="1" cellpadding="0">
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top : 10px; padding-bottom : 10px; padding-left : 10px;  padding-right : 10px;">
                  <tr>
                    <td bgcolor="#ffffff" align="left"><font face="Arial" size="4" color="#808080">&nbsp;&nbsp;[{ oxmultilang ident="EMAIL_SUGGEST_HTML_POSTCARDFROM" }] </font></td>
                    <td bgcolor="#ffffff" align="right"><img src="[{$oViewConf->getNoSslImageDir()}]/logo_white.gif" border="0" hspace="0" vspace="0" alt="[{ $shop->oxshops__oxname->value }]" align="texttop"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CECCCD">
      <tr>
        <td bgcolor="#CECCCD" width="1" rowspan="2"><img src="[{$oViewConf->getNoSslImageDir()}]/leer.gif" width="1" border="0" hspace="0" vspace="0" alt=""></td>
        <td>
          <table border="0" width="100%" cellspacing="10" cellpadding="0" bgcolor="#FFFFFF">
            <tr>
              <td bgcolor="#FFFFFF" align="top" width="30%"><a href="[{ $oViewConf->getBaseDir() }]index.php?cl=details&anid=[{ $product->sOXID}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]"><img src="[{$product->nossl_dimagedir}]/[{$product->oxarticles__oxpic1->value }]" border="0" hspace="0" vspace="0" alt="[{ $product->oxarticles__oxtitle->value|strip_tags }]"></a></td>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                  <tr>
                    <td valign="top">
                      <table border="0" width="100%"cellspacing="10" cellpadding="0" bgcolor="#FFFFFF">
                        <tr>
                          <td bgcolor="#FFFFFF"></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF" width="10%"><font face="Verdana,Arial" size="1"><b>[{ oxmultilang ident="EMAIL_SUGGEST_HTML_FROM" }]</b></font></td>
                          <td bgcolor="#FFFFFF" align="left"><font face="Verdana,Arial" size="1">[{$userinfo->send_name|oxescape}]<br></font></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF" width="10%"><font face="Verdana,Arial" size="1"><b>[{ oxmultilang ident="EMAIL_SUGGEST_HTML_EMAIL" }]</b></font></td>
                          <td bgcolor="#FFFFFF" align="left"><font face="Verdana,Arial" size="1">[{$userinfo->send_email|oxescape}]</font></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF" width="10%"><font face="Verdana,Arial" size="1"><b>[{ oxmultilang ident="EMAIL_SUGGEST_HTML_TO" }]</b></font></td>
                          <td bgcolor="#FFFFFF" align="left"><font face="Verdana,Arial" size="1">[{$userinfo->rec_name|oxescape}]</font></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF" width="10%"><font face="Verdana,Arial" size="1"><b>[{ oxmultilang ident="EMAIL_SUGGEST_HTML_EMAIL2" }]</b></font></td>
                          <td bgcolor="#FFFFFF" align="left"><font face="Verdana,Arial" size="1">[{$userinfo->rec_email|oxescape}]</font></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF" colspan="2"><font face="Verdana,Arial" size="1">[{$userinfo->send_message|oxescape|nl2br}]</font></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF" colspan="2"><font face="Verdana,Arial" size="1">[{ oxmultilang ident="EMAIL_SUGGEST_HTML_MENYGREETINGS" }] [{$userinfo->send_name|oxescape}]</font></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td><font face="Verdana,Arial" size="2" fontcolor="#777777"><a href="[{ $oViewConf->getBaseDir() }]index.php?cl=details&anid=[{ $product->sOXID}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]"><b>[{ $product->oxarticles__oxtitle->value }]</b></a></font></td>
            </tr>
            <tr>
              <td><font face="Verdana,Arial" size="1" fontcolor="#777777"><a href="[{ $oViewConf->getBaseDir() }]index.php?cl=details&anid=[{ $product->sOXID}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]">[{ $product->oxarticles__oxshortdesc->value }]</a></font></td>
            </tr>
          </table>
        <td bgcolor="#CECCCD" width="1" rowspan="2"><img src="[{$oViewConf->getNoSslImageDir()}]/leer.gif" width="1" border="0" hspace="0" vspace="0" alt=""></td>
      </tr>
      <tr><td height="1"></td></tr>
    </table>
    <br><br>
    [{ oxcontent ident="oxemailfooter" }]
  </body>
</html>
