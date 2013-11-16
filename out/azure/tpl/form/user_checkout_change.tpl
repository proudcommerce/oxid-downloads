<form class="oxValidate" action="[{ $oViewConf->getSslSelfLink() }]" name="order" method="post">
[{assign var="aErrors" value=$oView->getFieldValidationErrors()}]
[{ $oViewConf->getHiddenSid() }]
[{ $oViewConf->getNavFormParams() }]
<input type="hidden" name="cl" value="user">
<input type="hidden" name="option" value="[{$oView->getLoginOption()}]">
<input type="hidden" name="fnc" value="changeuser">
<input type="hidden" name="lgn_cook" value="0">
<input type="hidden" name="blshowshipaddress" value="1">

<div class="lineBox clear">
    <a href="[{ oxgetseourl ident=$oViewConf->getBasketLink() }]" class="submitButton largeButton" id="userBackStepBottom">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_BACKSTEP" }]</a>
    <button id="userNextStepTop" class="submitButton largeButton nextStep" name="userform" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_NEXTSTEP" }]</button>
</div>

<div class="checkoutCollumns clear">
    <div class="collumn">
        <h3 class="blockHead">[{ oxmultilang ident="FORM_REGISTER_BILLINGADDRESS" }]</h3>
        <ul class="form" style="display: none;" id="addressForm">
            [{ include file="form/fieldset/user_billing.tpl" noFormSubmit=true blSubscribeNews=true blOrderRemark=true}]
        </ul>
        <ul class="form" id="addressText">
            [{ include file="widget/address/billing_address.tpl" noFormSubmit=true blSubscribeNews=true blOrderRemark=true}]
            <button id="userChangeAddress" class="submitButton largeButton" name="changeBillAddress" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_CHANGE" }]</button>
        </ul>
        [{oxscript add="$('#userChangeAddress').click( function() { $('#addressForm').show();$('#addressText').hide();return false;});"}]
    </div>
    <div class="collumn">
        <h3 class="blockHead">[{ oxmultilang ident="FORM_REGISTER_SHIPPINGADDRESS" }]</h3>
        <p><input type="checkbox" name="blshowshipaddress" id="showShipAddress" [{if !$oView->showShipAddress()}]checked[{/if}] value="0"><label for="showShipAddress">[{ oxmultilang ident="FORM_REGISTER_USE_BILLINGADDRESS_FOR_SHIPPINGADDRESS" }]</label></p>
        <ul id="shippingAddress" class="form" [{if !$oView->showShipAddress()}]style="display: none;"[{/if}]>
        [{ include file="form/fieldset/user_shipping.tpl" noFormSubmit=true onChangeClass='user'}]
        </ul>
    </div>
</div>

[{oxscript add="$('#showShipAddress').change( function() { $('#shippingAddress').toggle($(this).is(':not(:checked)'));});"}]

<div class="lineBox clear">
    <a href="[{ oxgetseourl ident=$oViewConf->getBasketLink() }]" class="submitButton largeButton" id="userBackStepBottom">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_BACKSTEP" }]</a>
    <button id="userNextStepBottom" class="submitButton largeButton nextStep" name="userform" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_NEXTSTEP" }]</button>
</div>

</form>