[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

[{ if $readonly}]
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
    <input type="hidden" name="cl" value="country_list">
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
    <col width="40%">

    <col width="35%">
    <col width="20%">

    <col width="1%">
</colgroup>
<tr class="listitem">
    <td valign="top" class="listfilter first" align="center">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="3" maxlength="128" name="where[oxcountry.oxactive]" value="[{ $where->oxcountry__oxactive }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="50" maxlength="128" name="where[oxcountry.oxtitle]" value="[{ $where->oxcountry__oxtitle }]">
        </div></div>
    </td>
    <td valign="top" class="listfilter">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="50" maxlength="128" name="where[oxcountry.oxshortdesc]" value="[{ $where->oxcountry__oxshortdesc }]">
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

        <input class="listedit" type="text" size="5" maxlength="128" name="where[oxcountry.oxisoalpha3]" value="[{ $where->oxcountry__oxisoalpha3 }]">
        </div></div>
    </td>

</tr>

<tr>
    <td class="listheader first" height="15" align="center"><a href="Javascript:document.search.sort.value='oxactive';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ARTICLE_OXACTIVE" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxtitle';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_COUNTRY" }]</a></td>
    <td class="listheader" height="15"><a href="Javascript:document.search.sort.value='oxshortdesc';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_SHORTDESC" }]</a></td>
    <td class="listheader" height="15" colspan="2"><a href="Javascript:document.search.sort.value='oxisoalpha3';document.search.submit();" class="listheader">[{ oxmultilang ident="COUNTRY_LIST_ISO" }]</a></td>
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
    <td valign="top" class="[{ $listclass}][{ if $listitem->oxcountry__oxactive->value == 1}] active[{/if}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxcountry__oxid->value}]');" class="[{ $listclass}]">
     &nbsp;
    </a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxcountry__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->oxcountry__oxtitle->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxcountry__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->oxcountry__oxshortdesc->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxcountry__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->oxcountry__oxisoalpha3->value }]</a></div></td>
    <td align="right" class="[{ $listclass}]">
    [{if !$readonly}]
    <a href="Javascript:DeleteThis('[{ $listitem->oxcountry__oxid->value }]');" class="delete" id="del.[{$_cnt}]" title="" [{include file="help.tpl" helpid=item_delete}]></a>
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
[{include file="pagenavisnippet.tpl" colspan="5"}]

</table>

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