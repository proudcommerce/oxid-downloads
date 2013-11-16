[{include file="headitem.tpl" box="list"
    title="NEWSLETTER_SEND_TITLE"|oxmultilangassign box="list"
    meta_refresh_sec="2"
    meta_refresh_url="`$oViewConf->getSelfLink()`&cl=newsletter_send&iStart=`$iStart`&user=`$user`&id=`$id`"
}]

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
}
//-->
</script>
<body>

<form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{include file="_formparams.tpl" cl="pricealarm_list" lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang editlanguage=$actlang}]
</form>

<div class="liste">
[{foreach from=$oView->getMailErrors() item=sError}]
  [{ $sError }]
[{/foreach}]
<center>
<h1>[{ oxmultilang ident="NEWSLETTER_SEND_SEND1" }] : [{ $iStart}] [{ oxmultilang ident="NEWSLETTER_SEND_SEND2" }] [{$user}].</h1>
</center>
</div>

[{include file="pagetabsnippet.tpl" noOXIDCheck="true"}]
</body>
</html>

