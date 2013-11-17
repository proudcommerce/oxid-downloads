<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html id="top">
<head>
    <title>[{ oxmultilang ident="NAVIGATION_TITLE" }]</title>
    <link rel="stylesheet" href="[{$shop->basetpldir}]nav.css">
    <link rel="stylesheet" href="[{$shop->basetpldir}]colors.css">
    <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
</head>
<body>

    <ul>
      <li class="act">
          <a href="[{$shop->selflink}]?cl=navigation&amp;item=home.tpl" target="basefrm" class="rc"><b>[{ oxmultilang ident="NAVIGATION_HOME" }]</b></a>
      </li>
      <li class="sep">
          <a href="[{$shop->selflink}]?cl=navigation&amp;fnc=logout" target="_parent" class="rc"><b>[{ oxmultilang ident="NAVIGATION_LOGOUT" }]</b></a>
      </li>
    </ul>

    <div class="version">
        <b>
            [{$fulledition}]
            [{$version}]_[{$revision}]
        </b>
    </div>

</body>
</html>