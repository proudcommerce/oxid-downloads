[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

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
    {
        var oSearch = document.getElementById("search");
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


<form name="search" id="search" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="newsletter_list">
<input type="hidden" name="lstrt" value="[{ $lstrt }]">
<input type="hidden" name="sort" value="[{ $sort }]">
<input type="hidden" name="actedit" value="[{ $actedit }]">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="fnc" value="">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<colgroup><col width="98%"><col width="2%"></colgroup>
<tr class="listitem">
    <td valign="top" class="listfilter first" height="20" colspan="2">
    <div class="r1"><div class="b1">
    <div class="find"><input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]"></div>
    <input class="listedit" type="text" size="60" maxlength="128" name="where[oxnewsletter.oxtitle]" value="[{ $where->oxnewsletter__oxtitle }]">
    </div></div>
    </td>
</tr>
<tr>
    <td class="listheader first" height="15" colspan="2"><a href="Javascript:document.search.sort.value='oxnewsletter.oxtitle';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_TITLE" }]</a></td>
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
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxnewsletter__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->oxnewsletter__oxtitle->value }]</a></div></td>
    <td class="[{ $listclass}]">
    [{ if !$listitem->isOx() }]
        <a href="Javascript:DeleteThis('[{ $listitem->oxnewsletter__oxid->value }]');" class="delete" id="del.[{$_cnt}]" align="middle" [{include file="help.tpl" helpid=item_delete}]></a></td>
    [{/if}]
</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
</form>
[{include file="pagenavisnippet.tpl" cplspan="2"}]
</table>
</div>




    [{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="NEWSLETTER_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="NEWSLETTER_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
