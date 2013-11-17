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

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="oxidCopy" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="deliveryset_payment">
    <input type="hidden" name="language" value="[{ $actlang }]">
</form>


<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="deliveryset_payment">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxdeliveryset__oxid]" value="[{ $oxid }]">
<input type="hidden" name="language" value="[{ $actlang }]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
  <tr>
    <td valign="top" class="edittext">
        <input [{ $readonly }] type="button" value="[{ oxmultilang ident="DELIVERYSET_PAYMENT_ASSIGNPAYMENT" }]" class="edittext" onclick="JavaScript:showDialog('?cl=deliveryset_payment&aoc=1&oxid=[{ $oxid }]');">
    </td>

    <td valign="top" width="50%">

    </td>
  </tr>
</table>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]