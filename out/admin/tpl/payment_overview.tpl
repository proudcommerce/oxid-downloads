[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="user_overview">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<table cellspacing="0" cellpadding="0" border="0" width="99%" height="100%">
<tr>
<td valign="top" background="[{$oViewConf->getImageUrl()}]/edit_back.gif" width="100%">

&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_REVIEW" }]<br>
<br>
&nbsp;&nbsp;&nbsp;[{$edit->oxpayments__oxdesc->value }] <br>
<br>
&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="PAYMENT_OVERVIEW_HOWMANYPAYMENTS" }]<br>
&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="GENERAL_RETURN" }]<br>

</td>
</tr>
[{include file="bottomnaviitem.tpl"}]
</table>
[{include file="bottomitem.tpl"}]
