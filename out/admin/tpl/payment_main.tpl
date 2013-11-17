
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

  [{ if $shopid != "oxbaseshop" }]
    [{assign var="readonly" value="readonly disabled"}]
  [{else}]
    [{assign var="readonly" value=""}]
  [{/if}]

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

//-->
</script>

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="payment_main">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>


<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="payment_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxpayments__oxid]" value="[{ $oxid }]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">

<tr>

    <td valign="top" class="edittext">

        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext" width="70">
            [{ oxmultilang ident="GENERAL_ACTIVE" }]
            </td>
            <td class="edittext">
            <input class="edittext" type="checkbox" name="editval[oxpayments__oxactive]" value='1' [{if $edit->oxpayments__oxactive->value == 1}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext" width="100">
            [{ oxmultilang ident="PAYMENT_MAIN_NAME" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="25" maxlength="[{$edit->oxpayments__oxdesc->fldmax_length}]" name="editval[oxpayments__oxdesc]" value="[{$edit->oxpayments__oxdesc->value}]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="PAYMENT_MAIN_ADDPRICE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="15" maxlength="[{$edit->oxpayments__oxaddsum->fldmax_length}]" name="editval[oxpayments__oxaddsum]" value="[{$edit->oxpayments__oxaddsum->value }]" [{ $readonly }]>
                <select name="editval[oxpayments__oxaddsumtype]" class="editinput" [{include file="help.tpl" helpid=addsumtype}] [{ $readonly }]>
                [{foreach from=$sumtype item=sum}]
                <option value="[{ $sum }]" [{ if $sum == $edit->oxpayments__oxaddsumtype->value}]SELECTED[{/if}]>[{ $sum }]</option>
                [{/foreach}]
                </select>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="PAYMENT_MAIN_FROMBONI" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="25" maxlength="[{$edit->oxpayments__oxfromboni->fldmax_length}]" name="editval[oxpayments__oxfromboni]" value="[{$edit->oxpayments__oxfromboni->value}]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="PAYMENT_MAIN_AMOUNT" }]
            </td>
            <td class="edittext">
            [{ oxmultilang ident="PAYMENT_MAIN_FROM" }] <input type="text" class="editinput" size="5" maxlength="[{$edit->oxpayments__oxfromamount->fldmax_length}]" name="editval[oxpayments__oxfromamount]" value="[{$edit->oxpayments__oxfromamount->value}]" [{ $readonly }]>  [{ oxmultilang ident="PAYMENT_MAIN_TILL" }] <input type="text" class="editinput" size="5" maxlength="[{$edit->oxpayments__oxtoamount->fldmax_length}]" name="editval[oxpayments__oxtoamount]" value="[{$edit->oxpayments__oxtoamount->value}]" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="PAYMENT_MAIN_SELECTED" }]
            </td>
            <td class="edittext">
            <input type="checkbox" name="editval[oxpayments__oxchecked]" value="1" [{if $edit->oxpayments__oxchecked->value}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_SORT" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="25" maxlength="[{$edit->oxpayments__oxsort->fldmax_length}]" name="editval[oxpayments__oxsort]" value="[{$edit->oxpayments__oxsort->value}]" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_SHORTDESC" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="25" maxlength="[{$edit->oxpayments__oxlongdesc->fldmax_length}]" name="editval[oxpayments__oxlongdesc]" value="[{$edit->oxpayments__oxlongdesc->value}]" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext" valign="top">
            [{ oxmultilang ident="GENERAL_FIELDS" }]
            </td>
            <td class="edittext">
            <select name="aFields[]" size="3" multiple class="editinput" style="width: 150px;" [{ $readonly }]>
               [{foreach from=$aFieldNames item=sField}]
                <option value="[{ $sField->name }]">[{ $sField->name }]</option>
                [{/foreach}]
             </select>
            </td>
        </tr>
        <tr>
            <td class="edittext" valign="top">
            </td>
            <td class="edittext">
                      <input type="text" class="edittext" name="sAddField" value="" size="128" style="width: 150px;"><br>
                <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_FIELDS_ADD" }]" onClick="Javascript:document.myedit.fnc.value='addfield'"" [{ $readonly }] style="width: 125px;"><br>
              <br>
            </td>
        </tr>
        <tr>
            <td class="edittext" valign="top">
            </td>
            <td class="edittext">
                <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_FIELDS_DELETE" }]" onClick="Javascript:document.myedit.fnc.value='delfields'"" [{ $readonly }] style="width: 150px;">
            </td>
        </tr>



        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"" [{ $readonly }] style="width: 150px;">
            </td>
        </tr>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
                [{include file="language_edit.tpl"}]
            </td>
        </tr>

        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="50%">
    [{ if $oxid != "-1"}]

        <input [{ $readonly }] type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNGROUPS" }]" class="edittext" onclick="JavaScript:showDialog('?cl=payment_main&aoc=1&oxid=[{ $oxid }]');">

    [{ /if}]
    </td>

    </tr>
</table>

</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
