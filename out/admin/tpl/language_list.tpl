[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

[{ if $readonly}]
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
    <input type="hidden" name="cl" value="language_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup>
    <col width="4%">
    <col width="5%">
    <col width="90%">
    <col width="1%">
</colgroup>
<tr class="listitem">
    <td valign="top" class="listfilter first" align="center">
        <div class="r1"><div class="b1">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        </div></div>
    </td>
    <td valign="top" class="listfilter" colspan="2">
        <div class="r1"><div class="b1">
        </div></div>
    </td>

</tr>

<tr>
    <td class="listheader first" height="15" align="center"><a href="Javascript:document.search.sort.value='active';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ARTICLE_OXACTIVE" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='abbr';document.search.submit();" class="listheader">[{ oxmultilang ident="LANGUAGE_ABBERVATION" }]</a></td>
    <td class="listheader" height="15" colspan="2"><a href="Javascript:document.search.sort.value='name';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_LANGUAGE_NAME" }]</a></td>
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
    [{ if $listitem->oxid == $oxid }]
        [{assign var="listclass" value=listitem4 }]
    [{ /if}]
    <td valign="top" class="[{ $listclass}][{ if $listitem->active == 1}] active[{/if}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxid}]');" class="[{ $listclass}]">
     &nbsp;
    </a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxid}]');" class="[{ $listclass}]">[{ $listitem->abbr }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxid}]');" class="[{ $listclass}]">[{if $listitem->default}]<b>[{/if}][{ $listitem->name }][{if $listitem->default}]</b>[{/if}]</a></div></td>
    <td align="right" class="[{ $listclass}]">
    [{if !$readonly}]
    <a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->oxid }]');" class="delete" id="del.[{$_cnt}]" title="" [{include file="help.tpl" helpid=item_delete}]></a>
    [{/if}]
    </td>
</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
[{include file="pagenavisnippet.tpl" colspan="5"}]
</table>
</form>
</div>


[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="COUNTRY_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="COUNTRY_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>