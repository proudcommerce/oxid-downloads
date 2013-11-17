[{include file="headitem.tpl" title="ADMINGB_TITLE"|oxmultilangassign box="list"}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
function EditThis( sID)
{
    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='[{if $actlocation}][{$actlocation}][{else}][{ $default_edit }][{/if}]';

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.submit();
}

function DeleteThis( sID)
{
    var blCheck = window.confirm("[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]");
    if( blCheck == true)
    {
        var oTransfer = parent.edit.document.getElementById("transfer");
        oTransfer.oxid.value='-1';
        oTransfer.cl.value='[{ $default_edit }]';

        //forcing edit frame to reload after submit
        top.forceReloadingEditFrame();

        var oSearch = document.getElementById("search");
        oSearch.oxid.value=sID;
        oSearch.fnc.value='deleteentry';
        oSearch.actedit.value=0;
        oSearch.submit();
    }
}

function ChangeEditBar( sLocation, sPos)
{
    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.cl.value=sLocation;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = document.getElementById("search");
    oSearch.actedit.value=sPos;
    oSearch.submit();
}

function ChangeLanguage()
{
    var oSearch = document.getElementById("search");
    oSearch.language.value=oSearch.changelang.value;
    oSearch.editlanguage.value=oSearch.changelang.value;
    oSearch.submit();

    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.innerHTML += '<input type="hidden" name="language" value="'+oSearch.changelang.value+'">';
    oTransfer.innerHTML += '<input type="hidden" name="editlanguage" value="'+oSearch.changelang.value+'">';

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();
}

window.onLoad = top.reloadEditFrame();

//-->
</script>

<div id="liste">


<form name="search" id="search" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="adminguestbook_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup><col width="10%"><col width="15%"><col width="63%"><col width="2%"></colgroup>
<tr>
    <td class="listfilter first" height="15"><div class="r1"><div class="b1">&nbsp;</div></div></td>
    <td class="listfilter"><div class="r1"><div class="b1">&nbsp;</div></div></td>
    <td class="listfilter" colspan="2"><div class="r1"><div class="b1">&nbsp;</div></div></td>
</tr>
<tr>
    <td class="listheader first" height="15" >[{ oxmultilang ident="GENERAL_DATE" }]</td>
    <td class="listheader">[{ oxmultilang ident="ADMINGB_LIST_AUTHOR" }]</td>
    <td class="listheader" colspan="2">[{ oxmultilang ident="ADMINGB_LIST_ENTRY" }]</td>
</tr>

[{assign var="blWhite" value=""}]
[{assign var="_cnt" value=0}]
[{foreach from=$mylist item=listitem}]
    [{assign var="_cnt" value=$_cnt+1}]
    <tr id="row.[{$_cnt}]">

    [{ if $listitem->blacklist == 1}]
        [{assign var="listclass" value=listitem3 }]
    [{ else}]
        [{assign var="listclass" value=listitem$blWhite }]
    [{ /if}]
    [{ if $listitem->getId() == $oxid }]
        [{assign var="listclass" value=listitem4 }]
    [{ /if}]
    <td valign="top" class="[{ $listclass}][{if !$listitem->oxgbentries__oxviewed->value && $listitem->getId() != $oxid }]new[{/if}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxgbentries__oxid->value}]');" class="[{ $listclass}][{if !$listitem->oxgbentries__oxviewed->value && $listitem->getId() != $oxid}]new[{/if}]">[{ $listitem->oxgbentries__oxcreate|oxformdate }]</a></div></td>
    <td valign="top" class="[{ $listclass}][{if !$listitem->oxgbentries__oxviewed->value && $listitem->getId() != $oxid}]new[{/if}]"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxgbentries__oxid->value}]');" class="[{ $listclass}][{if !$listitem->oxgbentries__oxviewed->value && $listitem->getId() != $oxid}]new[{/if}]">[{ $listitem->oxuser__oxfname->value }] [{ $listitem->oxuser__oxlname->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}][{if !$listitem->oxgbentries__oxviewed->value && $listitem->getId() != $oxid}]new[{/if}]"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxgbentries__oxid->value}]');" class="[{ $listclass}][{if !$listitem->oxgbentries__oxviewed->value && $listitem->getId() != $oxid}]new[{/if}]">[{ $listitem->oxgbentries__oxcontent->value|oxtruncate:300:"..":false  }]</a></div></td>
    <td  class="[{ $listclass}]">[{if !$readonly}]<a href="Javascript:DeleteThis('[{ $listitem->oxgbentries__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>[{/if}]</td>

</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
</form>
[{include file="pagenavisnippet.tpl"}]
</table>
</div>

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="ADMINGB_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="ADMINGB_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>