[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]
<script type="text/javascript">
<!--
window.onload = function ()
{
    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.cl.value="pricealarm_mail";
    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();
    window.onload = top.reloadEditFrame();
}
//-->
</script>

<form name="search" id="search" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="pricealarm_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">
</form>

<div id="liste">
<center>
<h1>[{$iAllCnt}] [{ oxmultilang ident="PRICEALARM_DONE_SENDEMAIL" }]</h1>
<a href="JavaScript:var oSearch = document.getElementById('search');oSearch.submit();"><b>[{ oxmultilang ident="PRICEALARM_DONE_GOTOPRICEALARM" }]</b></a>
</center>
</div>

[{include file="pagetabsnippet.tpl" noOXIDCheck="true"}]
</body>
</html>
