<form class="oxValidate" action="[{ $oViewConf->getSelfActionLink() }]" name="order" method="post">
[{assign var="aErrors" value=$oView->getFieldValidationErrors()}]
<h3 class="blockHead">[{ oxmultilang ident="FORM_USER_BILLINGADDRESS" }]</h3>
<div class="customFields">
    [{ $oViewConf->getHiddenSid() }]
    [{ $oViewConf->getNavFormParams() }]
    <input type="hidden" name="fnc" value="changeuser_testvalues">
    <input type="hidden" name="cl" value="account_user">
    <input type="hidden" name="CustomError" value='user'>
    <input type="hidden" name="blshowshipaddress" value="1">
</div>
<ul class="form clear" style="display: none;" id="addressForm">
    [{ include file="form/fieldset/user_email.tpl" }]
    [{ include file="form/fieldset/user_billing.tpl" }]
</ul>
<ul class="form" id="addressText">
    [{ include file="widget/address/billing_address.tpl"}]
    <button id="userChangeAddress" class="submitButton largeButton" name="changeBillAddress" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_CHANGE" }]</button>
</ul>
[{oxscript add="$('#userChangeAddress').click( function() { $('#addressForm').show();$('#addressText').hide();return false;});"}]

<h3 id="addShippingAddress" class="blockHead">[{ oxmultilang ident="FORM_USER_SHIPPINGADDRESSES" }]</h3>
<p><input type="checkbox" name="blshowshipaddress" id="showShipAddress" [{if !$oView->showShipAddress()}]checked[{/if}] value="0"><label for="showShipAddress">[{ oxmultilang ident="FORM_REGISTER_USE_BILLINGADDRESS_FOR_SHIPPINGADDRESS" }]</label></p>
<ul id="shippingAddress" class="form clear" [{if !$oView->showShipAddress()}] style="display: none;" [{/if}]>
[{ include file="form/fieldset/user_shipping.tpl" }]
</ul>
</form>
[{oxscript add="$('#showShipAddress').change( function() { $('#shippingAddress').toggle($(this).is(':not(:checked)'));});"}]

