[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="voucherserie_main">
</form>


<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="voucherserie_main">
<input type="hidden" name="fnc" value="save">
<input type="hidden" name="oxid" value="[{$oxid}]">
<input type="hidden" name="editval[oxvoucherseries__oxid]" value="[{$oxid}]">

<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext" width="355">

        <table cellspacing="2" cellpadding="0" border="0">
        <!--tr>
            <td class="edittext" width="160">
            [{ oxmultilang ident="VOUCHERSERIE_MAIN_GENERATERANDOM" }]
            </td>
            <td class="edittext" width="195">
            <input type="checkbox" name="randomNr" value="true">
            </td>
        </tr-->
        <input type="hidden" name="randomNr" value="true">
        <tr>
            <td class="edittext" width="160">
            [{ oxmultilang ident="GENERAL_NAME" }]
            </td>
            <td class="edittext" width="195">
            <input class="editinput" type="text" size="36" name="editval[oxvoucherseries__oxserienr]" value="[{$edit->oxvoucherseries__oxserienr->value}]" onClick="this.select()" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext" width="90">
            [{ oxmultilang ident="GENERAL_DESCRIPTION" }]
            </td>
            <td class="edittext">
            <input class="editinput" type="text" size="36" name="editval[oxvoucherseries__oxseriedescription]" value="[{$edit->oxvoucherseries__oxseriedescription->value}]" onClick="this.select()" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_BEGINDATE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="27" name="editval[oxvoucherseries__oxbegindate]" value="[{$edit->oxvoucherseries__oxbegindate|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] onClick="this.select()" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_ENDDATE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="27" name="editval[oxvoucherseries__oxenddate]" value="[{$edit->oxvoucherseries__oxenddate|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] onClick="this.select()" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_DISCOUNT" }]
            </td>
            <td class="edittext">
            <input class="editinput" type="text" size="15" name="editval[oxvoucherseries__oxdiscount]" value="[{$edit->oxvoucherseries__oxdiscount->value}]" onClick="this.select()" [{ $readonly }]>
            <select class="editinput" name="editval[oxvoucherseries__oxdiscounttype]" [{ $readonly }]>
                <option value="absolute" [{ if $edit->oxvoucherseries__oxdiscounttype->value == "absolute"}]selected[{/if}]>abs</option>
                <option value="percent" [{ if $edit->oxvoucherseries__oxdiscounttype->value == "percent"}]selected[{/if}]>%</option>
            </select>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="VOUCHERSERIE_MAIN_MINORDERPRICE" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="15" name="editval[oxvoucherseries__oxminimumvalue]" value="[{$edit->oxvoucherseries__oxminimumvalue->value }]" onClick="this.select()" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="VOUCHERSERIE_MAIN_ALLOWSAMESERIES" }]
            </td>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_YES" }]&nbsp;<input type="radio" name="editval[oxvoucherseries__oxallowsameseries]" value="1" [{if $edit->oxvoucherseries__oxallowsameseries->value}]checked[{/if}] [{ $readonly }]>&nbsp;&nbsp;
            [{ oxmultilang ident="GENERAL_NO" }]&nbsp;<input type="radio" name="editval[oxvoucherseries__oxallowsameseries]" value="0" [{if !$edit->oxvoucherseries__oxallowsameseries->value}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="VOUCHERSERIE_MAIN_ALLOWOTHERSERIES" }]
            </td>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_YES" }]&nbsp;<input type="radio" name="editval[oxvoucherseries__oxallowotherseries]" value="1" [{if $edit->oxvoucherseries__oxallowotherseries->value}]checked[{/if}] [{ $readonly }]>&nbsp;&nbsp;
            [{ oxmultilang ident="GENERAL_NO" }]&nbsp;<input type="radio" name="editval[oxvoucherseries__oxallowotherseries]" value="0" [{if !$edit->oxvoucherseries__oxallowotherseries->value}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="VOUCHERSERIE_MAIN_SAMESEROTHERORDER" }]
            </td>
            <td class="edittext">
            [{ oxmultilang ident="GENERAL_YES" }]&nbsp;<input type="radio" name="editval[oxvoucherseries__oxallowuseanother]" value="1" [{if $edit->oxvoucherseries__oxallowuseanother->value}]checked[{/if}] [{ $readonly }]>&nbsp;&nbsp;
            [{ oxmultilang ident="GENERAL_NO" }]&nbsp;<input type="radio" name="editval[oxvoucherseries__oxallowuseanother]" value="0" [{if !$edit->oxvoucherseries__oxallowuseanother->value}]checked[{/if}] [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" [{ $readonly }]>
            </td>
        </tr>
        </table>
    </td>
    <td width="35" valign="top" align="center">
    <img src="[{ $shop->imagedir }]/grayline_vert.gif" width="2" height="270" alt="" border="0">
    </td>
    <td width="355" valign="top">
        <table cellspacing="2" cellpadding="0" width="100%">
        <tr><td class="edittext" colspan="2"><b>[{ oxmultilang ident="VOUCHERSERIE_MAIN_NEWVOUCHER" }]</b> (optional)<br><br></td></tr>
        <tr><td class="edittext">[{ oxmultilang ident="VOUCHERSERIE_MAIN_RANDOMNUM" }]</td><td><input type="checkbox" name="randomVoucherNr" value="true" [{ $readonly }]></td></tr>
        <tr><td class="edittext">[{ oxmultilang ident="VOUCHERSERIE_MAIN_VOUCHERNUM" }]</td><td><input class="editinput" size="29" type="text" name="voucherNr" [{ $readonly }]></td></tr>
        <tr><td class="edittext" width="40%">[{ oxmultilang ident="GENERAL_SUM" }]</td><td width="60%" class="edittext"><input type="text" size="15" class="editinput" name="voucherAmount" value="0" [{ $readonly }]></td></tr>
        <tr><td><br><br></td><td></td></tr>
        <tr><td class="edittext">[{ oxmultilang ident="GENERAL_SUM" }]</td><td class="edittext"><b>[{ $status.total }]</b></td></tr>
        <tr><td class="edittext">[{ oxmultilang ident="VOUCHERSERIE_MAIN_AVAILABLE" }]</td><td class="edittext"><b>[{$status.available}]</b></td></tr>
        <tr><td class="edittext">[{ oxmultilang ident="VOUCHERSERIE_MAIN_USED" }]</td><td class="edittext"><b>[{$status.used}]</b></td></tr>
        </table>
    </td>
    </tr>
</table>
</form>
[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
