[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

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
    <input type="hidden" name="cl" value="article_list">
    <input type="hidden" name="lstrt" value="[{ $lstrt }]">
    <input type="hidden" name="sort" value="[{ $sort }]">
    <input type="hidden" name="actedit" value="[{ $actedit }]">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="language" value="[{ $actlang }]">
    <input type="hidden" name="editlanguage" value="[{ $actlang }]">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <colgroup>
        <col width="3%">
        <col width="10%">
        <col width="45%">
        <col width="30%">
        <col width="2%">
    </colgroup>
    <tr class="listitem">
     <td valign="top" class="listfilter first" align="right">
        <div class="r1"><div class="b1">&nbsp;</div></div>
    </td>
    <td valign="top" class="listfilter" align="left">
        <div class="r1"><div class="b1">
        <input class="listedit" type="text" size="9" maxlength="128" name="where[[{$listTable}].oxartnum]" value="[{ $where->oxarticles__oxartnum}]">
        </div></div>
    </td>
    <td height="20" valign="middle" class="listfilter" nowrap>
        <div class="r1"><div class="b1">
        <select name="art_category" class="editinput" onChange="Javascript:document.search.lstrt.value=0;document.search.submit();">
        <option value="">[{ oxmultilang ident="ARTICLE_LIST_ALLPRODUCTS" }]</option>
        [{foreach from=$cattree->aList item=pcat}]
        <option value="[{ $pcat->oxcategories__oxid->value }]" [{ if $pcat->selected}]SELECTED[{/if}]>[{ $pcat->oxcategories__oxtitle->value|oxtruncate:20:"..":true }]</option>
        [{/foreach}]
        </select>
        <select name="pwrsearchfld" class="editinput" onChange="Javascript:document.search.lstrt.value=0;document.search.sort.value=this.value;document.forms.search.submit();">
            [{foreach from=$pwrsearchfields key=field item=desc}]
            [{assign var="ident" value=GENERAL_ARTICLE_$desc}]
            [{assign var="ident" value=$ident|oxupper }]
            <option value="[{ $desc }]" [{ if $pwrsearchfld == $desc|oxupper }]SELECTED[{/if}]>[{ oxmultilang|oxtruncate:20:"..":true ident=$ident }]</option>
            [{/foreach}]
        </select>
        <input class="listedit" type="text" size="20" maxlength="128" name="where[[{$listTable}].[{$pwrsearchfld}]]" value="[{ $pwrsearchinput}]" [{include file="help.tpl" helpid=searchfieldoxdynamic}]>
        </div></div>
    </td>
    <td valign="top" class="listfilter" colspan="2" nowrap>
        <div class="r1"><div class="b1">
        <div class="find">
        <select name="changelang" class="editinput" onChange="Javascript:ChangeLanguage();">
        [{foreach from=$languages item=lang}]
        <option value="[{ $lang->id }]" [{ if $lang->selected}]SELECTED[{/if}]>[{ $lang->name }]</option>
        [{/foreach}]
        </select>
        <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]" onClick="Javascript:document.search.lstrt.value=0;">
        </div>
        <input class="listedit" type="text" size="25" maxlength="128" name="where[[{$listTable}].oxshortdesc]" value="[{ $where->oxarticles__oxshortdesc}]" [{include file="help.tpl" helpid=searchfieldoxshortdesc}]>
        </div></div>
    </td>
</tr>
<tr class="listitem">
<tr>
    <td class="listheader first" height="15" width="30" align="center"><a href="Javascript:document.search.sort.value='oxactive';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</a></td>
    <td class="listheader"><a href="Javascript:document.search.sort.value='oxartnum';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ARTNUM" }]</a></td>
    <td class="listheader" height="15">&nbsp;<a href="Javascript:document.search.sort.value='[{ $pwrsearchfld|oxlower }]';document.search.submit();" class="listheader">[{assign var="ident" value=GENERAL_ARTICLE_$pwrsearchfld }][{assign var="ident" value=$ident|oxupper }][{ oxmultilang ident=$ident }]</a></td>
    <td class="listheader" colspan="2"><a href="Javascript:document.search.sort.value='oxshortdesc';document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_SHORTDESC" }]</a></td>
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
    [{ if $listitem->oxarticles__oxid->value == $oxid }]
        [{assign var="listclass" value=listitem4 }]
    [{ /if}]
    <td valign="top" class="[{ $listclass}][{ if $listitem->oxarticles__oxactive->value == 1}] active[{/if}]" height="15"><div class="listitemfloating">&nbsp</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxarticles__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxarticles__oxartnum->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:EditThis('[{ $listitem->oxarticles__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->pwrsearchval|oxtruncate:200:"..":false }]</a></div></td>
    <td valign="top" class="[{ $listclass}]"><div class="listitemfloating"><a href="Javascript:EditThis('[{ $listitem->oxarticles__oxid->value }]');" class="[{ $listclass}]">[{ $listitem->oxarticles__oxshortdesc->value|strip_tags|oxtruncate:45:"..":true }]</a></div></td>
    <td class="[{ $listclass}]">
      [{if !$readonly}]
          <a href="Javascript:DeleteThis('[{ $listitem->oxarticles__oxid->value }]');" class="delete" id="del.[{$_cnt}]"title="" [{include file="help.tpl" helpid=item_delete}]></a>
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
    parent.parent.sMenuItem    = "[{ oxmultilang ident="GENERAL_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="ARTICLE_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>

