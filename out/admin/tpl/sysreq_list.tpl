[{include file="headitem.tpl" title="SYSREQ_MAIN_TITLE"|oxmultilangassign box="list"}]

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

<div id="liste">

</div>

[{include file="pagetabsnippet.tpl"}]

</body>
</html>