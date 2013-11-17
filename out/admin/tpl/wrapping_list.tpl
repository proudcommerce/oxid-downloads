[{include file="headitem.tpl" title="WRAPPING_LIST_TITLE"|oxmultilangassign box="list"}]

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
    blCheck = confirm("[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]");
    if( blCheck == true)
    {   var oSearch = document.getElementById("search");
        oSearch.oxid.value=sID;
        oSearch.fnc.value='deleteentry';
        oSearch.actedit.value=0;
        oSearch.submit();

        var oTransfer = parent.edit.document.getElementById("transfer");
        oTransfer.oxid.value='-1';
        oTransfer.cl.value='[{ $default_edit }]';

        //forcing edit frame to reload after submit
        top.forceReloadingEditFrame();
    }
}

function UnassignThis( sID)
{
    blCheck = confirm("[{ oxmultilang ident="GENERAL_YOUWANTTOUNASSIGN" }]");
    if( blCheck == true)
    {
        var oSearch = document.getElementById("search");
        oSearch.oxid.value=sID;
        oSearch.fnc.value='unassignentry';
        oSearch.actedit.value=0;
        oSearch.submit();

        var oTransfer = parent.edit.document.getElementById("transfer");
        oTransfer.oxid.value='-1';
        oTransfer.cl.value='[{if $actlocation}][{$actlocation}][{else}][{ $default_edit }][{/if}]';

        //forcing edit frame to reload after submit
        top.forceReloadingEditFrame();
    }
}

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
    <input type="hidden" name="cl" value="wrapping_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup><col width="28%"><col width="30%"><col width="30%"><col width="2%"></colgroup>
<tr class="listfilter">
    <td valign="top" class="listfilter first" height="20">
        <div class="r1"><div class="b1">&nbsp;</div></div>
    </td>
    <td valign="top" class="listfilter" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}].oxname]" value="[{ $where->oxwrapping__oxname }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" colspan="2">
        <div class="r1"><div class="b1">
        <div class="find">
            <select name="changelang" class="editinput" onChange="Javascript:ChangeLanguage();">
            [{foreach from=$languages item=lang}]
            <option value="[{ $lang->id }]" [{ if $lang->selected}]SELECTED[{/if}]>[{ $lang->name }]</option>
            [{/foreach}]
            </select>
            <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
        </div>
        <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}].oxpic]" value="[{ $where->oxwrapping__oxpic }]">
        </div></div>
    </td>
</tr>
<tr>
    <td class="listheader first" height="15">&nbsp;<a href="Javascript:document.search.sort.value='[{$listTable}].oxtype';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_TYPE" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxname';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_NAME" }]</a></td>
    <td class="listheader" colspan="2"><a href="Javascript:document.search.sort.value='[{$listTable}].oxpic';document.search.submit();" class="listheader">[{ oxmultilang ident="WRAPPING_LIST_PICTURE" }]</a></td>
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
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxwrapping__oxid->value}]');" class="[{ $listclass}]">[{ if $listitem->oxwrapping__oxtype->value == "WRAP"}][{ oxmultilang ident="WRAPPING_LIST_PRESENTPACKUNG" }][{elseif $listitem->oxwrapping__oxtype->value == "CARD"}][{ oxmultilang ident="GENERAL_CARD" }][{/if}]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxwrapping__oxid->value}]');" class="[{ $listclass}]">[{ if !$listitem->oxwrapping__oxname->value }]-[{ oxmultilang ident="GENERAL_NONAME" }]-[{else}][{ $listitem->oxwrapping__oxname->value }][{/if}]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxwrapping__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxwrapping__oxpic->value }]</a></div></td>

    <td class="[{ $listclass}]">
        [{if !$readonly }]
            [{ if !$listitem->isOx() }]
            <a href="Javascript:DeleteThis('[{ $listitem->oxwrapping__oxid->value }]');" class="delete" id="del.[{$_cnt}]" title="" [{include file="help.tpl" helpid=item_delete}]></a>
            [{ /if }]
        [{/if}]
    </td>

</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
</form>
[{include file="pagenavisnippet.tpl" colspan="4"}]
</table>
</div>

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="WRAPPING_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="WRAPPING_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
