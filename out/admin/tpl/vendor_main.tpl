[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
[{ if $updatelist == 1}]
    UpdateList('[{ $oxid }]');
[{ /if}]

function UpdateList( sID)
{
    var oSearch = parent.list.document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.submit();
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
}

function ChangeLstrt()
{
    var oSearch = document.getElementById("search");
    if (oSearch != null && oSearch.lstrt != null)
        oSearch.lstrt.value=0
}

function UnlockSave(obj)
{   var saveButton = document.myedit.saveArticle;
    if ( saveButton != null && obj != null )
    {   if (obj.value.length > 0)
            saveButton.disabled = false;
        else
            saveButton.disabled = true;
    }
}
function ChangeLanguage(obj)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.language.value=obj.value;
    oTransfer.submit();
}
function SetSticker( sStickerId, oObject)
{
    if ( oObject.selectedIndex != -1)
    {   oSticker = document.getElementById(sStickerId);
        oSticker.style.display = "";
        oSticker.style.backgroundColor = "#FFFFCC";
        oSticker.style.borderWidth = "1px";
        oSticker.style.borderColor = "#000000";
        oSticker.style.borderStyle = "solid";
        oSticker.innerHTML         = oObject.item(oObject.selectedIndex).innerHTML;
    }
    else
        oSticker.style.display = "none";
}

//-->
</script>

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="oxidCopy" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="vendor_main">
    <input type="hidden" name="language" value="[{ $actlang }]">
</form>

<form name="myedit" id="myedit" enctype="multipart/form-data" action="[{ $shop->selflink }]" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="vendor_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="oxparentid" value="[{ $oxparentid }]">
<input type="hidden" name="editval[oxvendor__oxid]" value="[{ $oxid }]">
<input type="hidden" name="language" value="[{ $actlang }]">

<table border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">

        <tr>
            <td class="edittext" width="120">
            [{ oxmultilang ident="GENERAL_ACTIVE" }]
            </td>
            <td class="edittext">
            <input class="edittext" type="checkbox" name="editval[oxvendor__oxactive]" value='1' [{if $edit->oxvendor__oxactive->value == 1}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_TITLE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="40" maxlength="[{$edit->oxvendor__oxtitle->fldmax_length}]" name="editval[oxvendor__oxtitle]" value="[{$edit->oxvendor__oxtitle->value}]" [{if !$oxparentid}]onchange="JavaScript:UnlockSave(this);" onkeyup="JavaScript:UnlockSave(this);" onmouseout="JavaScript:UnlockSave(this);"[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_SHORTDESC" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="40" maxlength="[{$edit->oxvendor__oxshortdesc->fldmax_length}]" name="editval[oxvendor__oxshortdesc]" value="[{$edit->oxvendor__oxshortdesc->value}]" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_ICON" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="25" maxlength="[{$edit->oxvendor__oxicon->fldmax_length}]" name="editval[oxvendor__oxicon]" value="[{$edit->oxvendor__oxicon->value}]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="VENDOR_MAIN_ICONUPLOAD" }]:<br>
            </td>
            <td class="edittext">
            <input class="editinput" name="myfile[ICO@oxvendor__oxicon]" type="file" [{ $readonly }]>
            </td>
        </tr>

        [{if $oxid != "-1"}]
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
                [{include file="language_edit.tpl"}]
            </td>
        </tr>
        [{/if}]
        <tr>
            <td class="edittext"><br><br>
            </td>
            <td class="edittext"><br><br>
            <input type="submit" class="edittext" name="saveArticle" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"" [{ $readonly }] [{ if !$edit->oxvendor__oxtitle->value && !$oxparentid }]disabled[{/if}] [{ $readonly }]><br>
            </td>
        </tr>


        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="55%">
    [{ if $oxid != "-1"}]
    <input [{ $readonly }] type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNARTICLES" }]" class="edittext" onclick="JavaScript:showDialog('?cl=vendor_main&aoc=1&oxid=[{ $oxid }]');" [{ $readonly }]>
    [{ /if}]
    </td>
    <!-- Ende rechte Seite -->

    </tr>
</table>

</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]