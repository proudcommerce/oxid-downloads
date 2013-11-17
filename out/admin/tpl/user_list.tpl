[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

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


<form name="search" id="search" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="user_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup>
    <col width="20%">
    <col width="20%">
    <col width="19%">
    <col width="10%">
    <col width="10%">
    <col width="10%">
    <col width="10%">
    <col width="1%">
<colgroup>
<tr class="listitem">
    <td valign="top" class="listfilter first" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxuser.oxlname]" value="[{ $where->oxuser__oxlname }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxuser.oxusername]" value="[{ $where->oxuser__oxusername }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxuser.oxstreet]" value="[{ $where->oxuser__oxstreet }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="10" maxlength="128" name="where[oxuser.oxzip]" value="[{ $where->oxuser__oxzip }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxuser.oxcity]" value="[{ $where->oxuser__oxcity }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="15" maxlength="128" name="where[oxuser.oxfon]" value="[{ $where->oxuser__oxfon }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" colspan="2" nowrap>
        <div class="r1"><div class="b1">
        <div class="find"><input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]"></div>
        <input class="listedit" type="text" size="5" maxlength="128" name="where[oxuser.oxcustnr]" value="[{ $where->oxuser__oxcustnr }]">
        </div>
        </div></div>
    </td>
</tr>
<tr>
    <td class="listheader first" height="15">&nbsp;<a href="Javascript:document.search.sort.value='oxuser.oxlname';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_NAME" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxuser.oxusername';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_EMAIL" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxuser.oxstreet';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_STREET" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxuser.oxzip';document.search.submit();" class="listheader">[{ oxmultilang ident="USER_LIST_ZIP" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxuser.oxcity';document.search.submit();" class="listheader">[{ oxmultilang ident="USER_LIST_PLACE" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxuser.oxfon';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_TELEPHONE" }]</a></td>
    <td class="listheader" colspan="2"><a href="Javascript:document.search.sort.value='oxuser.oxcustnr';document.search.submit();" class="listheader">[{ oxmultilang ident="USER_LIST_CUSTOMERNUM" }]</a></td>
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
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value}]');" class="[{ $listclass}]">[{ if !$listitem->oxuser__oxlname->value }]-kein Name-[{else}][{ $listitem->oxuser__oxlname->value }][{/if}] [{ $listitem->oxuser__oxfname->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxuser__oxusername->value|oxtruncate:21:"...":true }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxuser__oxstreet->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxuser__oxzip->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxuser__oxcity->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxuser__oxfon->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxuser__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxuser__oxcustnr->value }]</a></div></td>

    <td class="[{ $listclass}]">
        [{ if !$listitem->isOx() && !$readonly  && !$listitem->blPreventDelete}]
        <a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->oxuser__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
        [{ /if }]
    </td>

</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
[{include file="pagenavisnippet.tpl" colspan="8"}]
</table>
</form>
</div>

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="USER_LIST_MENNUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="USER_LIST_MENNUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
