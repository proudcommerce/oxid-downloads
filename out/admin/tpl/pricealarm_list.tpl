[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]


  [{ if $shopid != "oxbaseshop" }]
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
    <input type="hidden" name="cl" value="pricealarm_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup>
    <col width="15%">
    <col width="15%">
    <col width="10%">
    <col width="10%">
    <col width="30%">
    <col width="10%">
    <col width="8%">
    <col width="2%">
</colgroup>
<tr class="listitem">
    <td valign="top" class="listfilter first" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxpricealarm.oxemail]" value="[{ $where->oxpricealarm__oxemail }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxuser.oxlname]" value="[{ $where->oxuser__oxlname }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxpricealarm.oxinsert]" value="[{ $where->oxpricealarm__oxinsert }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxpricealarm.oxsended]" value="[{ $where->oxpricealarm__oxsended }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="20" maxlength="128" name="where[oxarticles.oxtitle]" value="[{ $where->oxarticles__oxtitle }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" height="20">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="5" maxlength="128" name="where[oxpricealarm.oxprice]" value="[{ $where->oxpricealarm__oxprice }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter" height="20" [{if count($mylist) > 0}]colspan="2"[{/if}]>
        <div class="r1"><div class="b1">
        <div class="find"><input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]"></div>
        <input class="listedit" type="text" size="5" maxlength="128" name="where[oxarticles.oxprice]" value="[{ $where->oxarticles__oxprice }]">
        </div></div>
    </td>
</tr>
<tr>
    <td class="listheader first" height="15">&nbsp;<a href="Javascript:document.search.sort.value='oxpricealarm.oxemail';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_EMAIL" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxuser.oxlname,oxuser.oxfname';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_NAME" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxpricealarm.oxinsert';document.search.submit();" class="listheader">[{ oxmultilang ident="PRICEALARM_LIST_CONFIRMDATE" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxpricealarm.oxsended';document.search.submit();" class="listheader">[{ oxmultilang ident="PRICEALARM_LIST_SENDDATE" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxarticles.oxtitle';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ITEM" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxpricealarm.oxprice';document.search.submit();" class="listheader">[{ oxmultilang ident="PRICEALARM_LIST_CUSTOMERSPRICE" }]</a></td>
    <td class="listheader" height="15"  [{if count($mylist) > 0}]colspan="2"[{/if}]>&nbsp;<a href="Javascript:document.search.sort.value='oxarticles.oxprice';document.search.submit();" class="listheader">[{ oxmultilang ident="PRICEALARM_LIST_STANDARTPRICE" }]</a></td>
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
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->oxpricealarm__oxemail->value }]</a></div></td>
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->oxpricealarm__userlname->value }] [{ $listitem->oxpricealarm__userfname->value }]</a></div></td>
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->oxpricealarm__oxinsert|oxformdate }]</a></div></td>
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->oxpricealarm__oxsended|oxformdate }]</a></div></td>
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->oxpricealarm__articletitle->value }]</a></div></td>
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->fpricealarmprice }]&nbsp;[{ $listitem->oxpricealarm__oxcurrency->value }]</a></div></td>
    <td valign="top" class="[{$listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->oxpricealarm__oxid->value}]');" class="[{if $listitem->iStatus==1}]listitemred[{elseif $listitem->iStatus==2}]listitemgreen[{else}][{$listclass}][{/if}]">[{ $listitem->fprice }]&nbsp;[{ $listitem->oxpricealarm__oxcurrency->value }]</a></div></td>
    <td class="[{$listclass}]">
      [{ if !$listitem->isOx() }]
        [{ if $readonly == ""}]
          <a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->oxpricealarm__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>
        [{/if}]
      [{/if}]
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
    parent.parent.sMenuItem    = "[{ oxmultilang ident="PRICEALARM_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="PRICEALARM_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
