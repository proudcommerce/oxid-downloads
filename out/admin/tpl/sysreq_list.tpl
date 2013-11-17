[{include file="headitem.tpl" title="SYSREQ_MAIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
<!--
function ChangeEditBar( sLocation, sPos)
{
    var oSearch = document.getElementById("search");
    oSearch.actedit.value=sPos;
    oSearch.submit();

    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.cl.value=sLocation;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();
}

window.onLoad = top.reloadEditFrame();

//-->
</script>

<div id="liste">

</div>

[{include file="pagetabsnippet.tpl"}]

</body>
</html>