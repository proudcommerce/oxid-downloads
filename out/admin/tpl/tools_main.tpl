[{include file="headitem.tpl" title="TOOLS_MAIN_TITLE"|oxmultilangassign}]
[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]
<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="oxidCopy" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="tools_main">
</form>

<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="tools_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="voxid" value="[{ $oxid }]">
    <input type="hidden" name="oxparentid" value="[{ $oxparentid }]">
    <input type="hidden" name="editval[oxarticles__oxid]" value="[{ $oxid }]">
</form>

<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post" target="list" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="tools_list">
<input type="hidden" name="fnc" value="performsql">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext" width="50%">
        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext" valign="top">
                [{ oxmultilang ident="TOOLS_MAIN_UPDATESQL" }]&nbsp;&nbsp;&nbsp;
            </td>
            <td class="edittext">
                <textarea class="confinput" style="width: 370; height: 182" name="updatesql" [{ $readonly }]></textarea>
            </td>
        </tr>
        <tr>
            <td class="edittext">
                [{ oxmultilang ident="TOOLS_MAIN_SQLDUMB" }]&nbsp;&nbsp;&nbsp;
            </td>
            <td class="edittext"><br>
                <input type="file" style="width: 370" class="edittext" name="myfile[SQL1@usqlfile]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="TOOLS_MAIN_START" }]" [{if !$blIsMallAdmin}]disabled[{/if}] [{ $readonly }]>
            </td>
        </tr>
        </table>
</form>

    </td>
    <td valign="top" class="edittext" align="left">
    <br>
        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext">
            </td>
        </tr>
        </table>

    </td>
    </tr>
</table>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]