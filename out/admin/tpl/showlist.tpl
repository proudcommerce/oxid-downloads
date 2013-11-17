[{include file="headitem.tpl" title="SHOWLIST_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function editThis( sID)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='[{$editfile}]';
    oTransfer.submit();
    if (parent.list != null)
    {
        var oSearch = parent.list.document.getElementById("search");
        oSearch.sort.value = '';
        oSearch.cl.value='[{$editfile}]_list';
        oSearch.actedit.value=1;
        oSearch.submit();
    }
}

function changeLanguage()
{
    var oList = document.getElementById("showlist");
    oList.language.value=oList.changelang.value;
    oList.editlanguage.value=oList.changelang.value;
    oList.submit();
}
//-->
</script>

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="">
    <input type="hidden" name="updatelist" value="1">
    [{if $blEmployMultilanguage}]
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">
    [{/if}]
</form>

[{if $sql}]
    <span class="listitem">
    [{ oxmultilang ident="SHOWLIST_SQL" }] :[{$sql}]<br>
    [{ oxmultilang ident="SHOWLIST_CNT" }] : [{$resultcount}]<br>
    </span>
[{/if}]
[{ if $noresult }]
    <span class="listitem">
        <b>[{ oxmultilang ident="SHOWLIST_NORESULTS" }]</b><br><br>
    </span>
[{/if}]
<table cellspacing="0" cellpadding="0" border="0" width="99%">
<tr class="listitem">
<td height="4"></td>
</tr>
<tr>
    <td class="listedit" height="15">&nbsp;</td>
    <form name="showlist" id="showlist" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="showlist">
    <input type="hidden" name="sort" value="">
    <input type="hidden" name="file" value="[{ $sFile }]">
    [{if $blEmployMultilanguage}]
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">
    [{/if}]
    [{foreach from=$finput key=iSel item=field}]
    [{if $field != "oxid"}]
        <td class="listedit">
        [{if $field != "none" }]
                <input class="listedit" type="text" size="15" maxlength="128" name="where[[{$iSel}]]" value="[{$where.$iSel}]">
        [{/if}]
        </td>
       [{/if}]
    [{/foreach}]
    <td class="listedit" nowrap>
    [{if $blEmployMultilanguage}]
    <select name="changelang" class="editinput" onChange="Javascript:changeLanguage();">
        [{foreach from=$languages item=lang}]
        <option value="[{ $lang->id }]" [{ if $lang->selected}]SELECTED[{/if}]>[{ $lang->name }]</option>
        [{/foreach}]
        </select>
    [{/if}]
    <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
    </td>
</tr>
<tr>
    <td class="listheader" height="15">&nbsp;</td>
    [{foreach from=$fieldnameslist key=iSel item=field}]
    [{ if $field.multilang != "oxid" }]
        <td class="listheader"><a href="javascript:document.forms.showlist.sort.value='[{$field.sorting}]';document.forms.showlist.submit();" class="listheader">[{$field.multilang}]</a></td>
    [{/if}]
    [{/foreach}]
    <td class="listheader" height="15">&nbsp;</td>
</tr>

[{foreach from=$resultset item=row}]
<tr>
    <td class="listitem[{ $blWhite }]" height="15">&nbsp;</td>
    [{foreach from=$fieldlist key=iSel item=field}]
        [{ if $field != "oxid" }]
        <td class="listitem[{ $blWhite }]"><a href="Javascript:editThis( '[{$row.oxid}]');" class="listitem[{ $blWhite }]">[{ $row.$field }]</a></td>
        [{/if}]
    [{/foreach}]
    <td class="listitem[{ $blWhite }]" height="15">&nbsp;</td>
</tr>
[{if $blWhite == "2"}]
    [{assign var="blWhite" value=""}]
[{else}]
    [{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]

</table>
</form>
[{ if $sumresult}]
<span class="listitem">
<b>[{ oxmultilang ident="SHOWLIST_SUM" }]:</b><br>
[{foreach from=$sumresult key=iSel item=field}]
[{ $iSel }]: [{ $field }]<br>
[{/foreach}]
</span>

[{/if}]
<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "";
    parent.parent.sMenuSubItem = "[{$header}]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
