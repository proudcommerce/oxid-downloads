[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box=" "}]

<script type="text/javascript">
<!--
[{ if $updatelist == 1}]
    UpdateList('[{ $oxid }]');
[{ /if}]

function UpdateList( sID)
{
    var oSearch = parent.list.document.getElementById("search");
    oSearch.search.oxid.value=sID;
    oSearch.submit();

    //parent.list.document.search.oxid.value=sID;
    //parent.list.document.search.submit();
}

function EditThis( sID)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='article_main';
    oTransfer.submit();

    var oSearch = parent.list.document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.submit();
    
    //document.transfer.oxid.value=sID;
    //document.transfer.cl.value='article_main';
    //document.transfer.submit();
    
    //parent.list.document.search.oxid.value=sID;
    //parent.list.document.search.submit();
}

function ChangeLstrt()
{
    var oSearch = document.getElementById("search");
        
    //if (document.search != null && document.search.lstrt != null)
    if ( oSearch != null && oSearch.lstrt != null)
        oSearch.lstrt.value=0
        //document.search.lstrt.value=0
}

function UnlockSave(obj)
{   var saveButton = document.myedit.saveArticle;
    if ( saveButton != null && obj != null )
    {
        if (obj.value.length > 0)
        {
            saveButton.disabled = false;
        }
        else
        {
            saveButton.disabled = true;
        }
    }
}
//-->
</script>

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="oxidCopy" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_main">
    <input type="hidden" name="w" value="main">
</form>

<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="article_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="oxparentid" value="[{ $oxparentid }]">
<input type="hidden" name="editval[oxarticles__oxid]" value="[{ $oxid }]">

</form><br /><br />
<div class="center">

[{if $isdemo}]
    <h1>[{ oxmultilang ident="SYSTEMINFO_DEMOMODE" }]</h1>
[{/if}]

<table border="0" cellpadding="3" width="600">
<tr class="h">
    <th>[{ oxmultilang ident="SYSTEMINFO_VARIABLE" }]</th>
    <th>[{ oxmultilang ident="SYSTEMINFO_VALUE" }]</th>
</tr>
[{foreach key=name item=value from=$aSystemInfo}]
<tr>
    <td class="e">[{$name}]</td>
    <td class="v">[{$value}]</td>
</tr>
[{/foreach}]
</table>
</div>