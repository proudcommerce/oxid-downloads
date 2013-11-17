[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="voucherserie_export">
</form>


<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="voucherserie_export">
<input type="hidden" name="fnc" value="export">
<input type="hidden" name="oxid" value="[{$oxid}]">


    [{if ($exportCompleted) }]
    [{ oxmultilang ident="VOUCHERSERIE_EXPORT_COMPLETED" }]
    [{else}]
    <table cellspacing="2" cellpadding="0" border="0">
    <tr>
        <td class="edittext" width="50">
        [{ oxmultilang ident="GENERAL_FILE" }]:
        </td>
        <td class="edittext">
        <input class="editinput" type="text" size="100" name="filepath" value="[{ $filepath }]" [{ $readonly }]>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
        <input type="submit" class="edittext" name="export" value="[{ oxmultilang ident="VOUCHERSERIE_EXPORT_EXPORT" }]" [{ $readonly }]>
        </td>
    </tr>
    </table>
    [{/if}]


</form>
[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
