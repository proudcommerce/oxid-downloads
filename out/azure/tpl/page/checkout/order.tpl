[{oxstyle include="css/checkout.css"}]
[{capture append="oxidBlock_content"}]
[{* ordering steps *}]
[{include file="page/checkout/inc/steps.tpl" active=4 }]
<div class="lineBox clear">
    <span class="title">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_TITLE2" }]<span>
</div>
<form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
<h3 class="section">
   <strong>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_BASKET" }]</strong>
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="basket">
    <input type="hidden" name="fnc" value="">
    <button type="submit" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_MODIFY4" }]</button>
</h3>
</form>
[{ if $oView->isConfirmAGBActive() && $oView->isConfirmAGBError() == 1 }]
    <div>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_READANDCONFIRMTERMS" }]</div>
[{/if}]
[{ if !$oxcmp_basket->getProductsCount()  }]
    <div>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_BASKETEMPTY" }]</div>
[{else}]
    [{assign var="currency" value=$oView->getActCurrency() }]

    [{if $oView->isLowOrderPrice()}]
        [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_MINORDERPRICE" }] [{ $oView->getMinOrderPrice() }] [{ $currency->sign }]
    [{elseif $oView->showOrderButtonOnTop()}]
        <div class="lineBox clear">
            <form action="[{ $oViewConf->getSslSelfLink() }]" method="post" id="orderConfirmAgbTop">
                [{ $oViewConf->getHiddenSid() }]
                [{ $oViewConf->getNavFormParams() }]
                <input type="hidden" name="cl" value="order">
                <input type="hidden" name="fnc" value="[{$oView->getExecuteFnc()}]">
                <input type="hidden" name="challenge" value="[{$challenge}]">
                <span class="agb">
                [{if $oView->isConfirmAGBActive()}]
                    <input type="hidden" name="ord_agb" value="0">
                    <input class="checkbox" type="checkbox" name="ord_agb" value="1">
                    [{oxifcontent ident="oxrighttocancellegend" object="oContent"}]
                        [{ $oContent->oxcontents__oxcontent->value }]
                    [{/oxifcontent}]
                [{else}]
                    <input type="hidden" name="ord_agb" value="1">
                    [{oxifcontent ident="oxrighttocancellegend2" object="oContent"}]
                        [{ $oContent->oxcontents__oxcontent->value }]
                    [{/oxifcontent}]
                [{/if}]
                </span>
                <button type="submit" class="submitButton largeButton nextStep">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SUBMITORDER" }]</button>
            </form>
        </div>
    [{/if}]

    [{block name="order_basket"}]
        [{include file="page/checkout/inc/basketcontents.tpl" editable=false}]
    [{/block}]


    [{ if $oViewConf->getShowVouchers() && $oxcmp_basket->getVouchers()}]
        [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_USEDCOUPONS" }]
        <div>
            [{foreach from=$Errors.basket item=oEr key=key }]
                [{if $oEr->getErrorClassType() == 'oxVoucherException'}]
                    [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_COUPONNOTACCEPTED1" }] [{ $oEr->getValue('voucherNr') }] [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_COUPONNOTACCEPTED2" }]<br>
                    [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_REASON" }]
                    [{ $oEr->getOxMessage() }]<br>
                [{/if}]
            [{/foreach}]
            [{foreach from=$oxcmp_basket->getVouchers() item=sVoucher key=key name=aVouchers}]
                [{ $sVoucher->sVoucherNr }]<br>
            [{/foreach }]
        </div>
    [{/if}]

    <div class="orderData" id="orderAddress">
        <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
            <h3 class="section">
            <strong>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_ADDRESSES" }]</strong>
            [{ $oViewConf->getHiddenSid() }]
            <input type="hidden" name="cl" value="user">
            <input type="hidden" name="fnc" value="">
            <button type="submit" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_MODIFYADDRESS" }]</button>
            </h3>
        </form>

    <dl>
        <dt>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_BILLINGADDRESS" }]</dt>
        <dd>
            [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_EMAIL" }]&nbsp;[{ $oxcmp_user->oxuser__oxusername->value }]<br>
            [{if $oxcmp_user->oxuser__oxcompany->value }] [{ $oxcmp_user->oxuser__oxcompany->value }]&nbsp;<br> [{/if}]
            [{ $oxcmp_user->oxuser__oxsal->value|oxmultilangsal}]&nbsp;[{ $oxcmp_user->oxuser__oxfname->value }]&nbsp;[{ $oxcmp_user->oxuser__oxlname->value }]<br>
            [{if $oxcmp_user->oxuser__oxaddinfo->value }] [{ $oxcmp_user->oxuser__oxaddinfo->value }]<br> [{/if}]
            [{ $oxcmp_user->oxuser__oxstreet->value }]&nbsp;[{ $oxcmp_user->oxuser__oxstreetnr->value }]<br>
            [{ $oxcmp_user->getState() }]
            [{ $oxcmp_user->oxuser__oxzip->value }]&nbsp;[{ $oxcmp_user->oxuser__oxcity->value }]<br>
            [{ $oxcmp_user->oxuser__oxcountry->value }]<br><br>
            [{if $oxcmp_user->oxuser__oxfon->value }] [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_PHONE" }] [{ $oxcmp_user->oxuser__oxfon->value }]&nbsp;<br> [{/if}]
        </dd>

        <dt>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SHIPPINGADDRESS" }]</dt>
        <dd>
            [{assign var="oDelAdress" value=$oView->getDelAddress() }]
            [{if $oDelAdress }]
                [{if $oDelAdress->oxaddress__oxcompany->value }] [{ $oDelAdress->oxaddress__oxcompany->value }]&nbsp;<br> [{/if}]
                [{ $oDelAdress->oxaddress__oxsal->value|oxmultilangsal}]&nbsp;[{ $oDelAdress->oxaddress__oxfname->value }]&nbsp;[{ $oDelAdress->oxaddress__oxlname->value }]<br>
                [{if $oDelAdress->oxaddress__oxaddinfo->value }] [{ $oDelAdress->oxaddress__oxaddinfo->value }]<br> [{/if}]
                [{ $oDelAdress->oxaddress__oxstreet->value }]&nbsp;[{ $oDelAdress->oxaddress__oxstreetnr->value }]<br>
                [{ $oDelAdress->getState() }]
                [{ $oDelAdress->oxaddress__oxzip->value }]&nbsp;[{ $oDelAdress->oxaddress__oxcity->value }]<br>
                [{ $oDelAdress->oxaddress__oxcountry->value }]<br><br>
                [{if $oDelAdress->oxaddress__oxfon->value }] [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_PHONE2" }] [{ $oDelAdress->oxaddress__oxfon->value }]&nbsp;<br>[{/if}]
            [{/if}]
        </dd>
    </dl>

    <div>
        [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_WHATIWANTEDTOSAY" }] [{ $oView->getOrderRemark() }]
    </div>
    </div>


    [{block name="shippingAndPayment"}]
        <div class="orderData" id="orderShipping">
        <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
            <h3 class="section">
                <strong>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SHIPPINGCARRIER" }]</strong>
                [{ $oViewConf->getHiddenSid() }]
                <input type="hidden" name="cl" value="payment">
                <input type="hidden" name="fnc" value="">
                <button type="submit" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_MODIFY2" }]</button>
            </h3>
        </form>
        [{assign var="oShipSet" value=$oView->getShipSet() }]
        [{ $oShipSet->oxdeliveryset__oxtitle->value }]
        </div>

        <div class="orderData" id="orderPayment">
            <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                <h3 class="section">
                    <strong>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_PAYMENTMETHOD" }]</strong>
                    [{ $oViewConf->getHiddenSid() }]
                    <input type="hidden" name="cl" value="payment">
                    <input type="hidden" name="fnc" value="">
                    <button type="submit" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_MODIFY3" }]</button>
                </h3>
            </form>
            [{assign var="payment" value=$oView->getPayment() }]
            [{ $payment->oxpayments__oxdesc->value }]
        </div>
    [{/block}]

    [{if $oView->isLowOrderPrice() }]
        [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_MINORDERPRICE" }] [{ $oView->getMinOrderPrice() }] [{ $currency->sign }]
    [{else}]
        <form action="[{ $oViewConf->getSslSelfLink() }]" method="post" id="orderConfirmAgbBottom">
            [{ $oViewConf->getHiddenSid() }]
            [{ $oViewConf->getNavFormParams() }]
            <input type="hidden" name="cl" value="order">
            <input type="hidden" name="fnc" value="[{$oView->getExecuteFnc()}]">
            <input type="hidden" name="challenge" value="[{$challenge}]">
            <input type="hidden" name="ord_agb" value="1">
                <div class="agb">
                    [{if $oView->isActive('PsLogin') }]
                        <input type="hidden" name="ord_agb" value="1">
                    [{else}]
                        <h3 class="section">
                            <strong>[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_TERMS_TITLE" }]</strong>
                        </h3>
                        [{if $oView->isConfirmAGBActive()}]
                            <input type="hidden" name="ord_agb" value="0">
                            <input class="checkbox" type="checkbox" name="ord_agb" value="1">
                            [{oxifcontent ident="oxrighttocancellegend" object="oContent"}]
                                [{ $oContent->oxcontents__oxcontent->value }]
                            [{/oxifcontent}]
                        [{else}]
                            <input type="hidden" name="ord_agb" value="1">
                            [{oxifcontent ident="oxrighttocancellegend2" object="oContent"}]
                                [{ $oContent->oxcontents__oxcontent->value }]
                            [{/oxifcontent}]
                        [{/if}]

                    [{/if}]
                </div>
            <div class="lineBox clear">
                <a href="[{ oxgetseourl ident=$oViewConf->getPaymentLink() }]" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_BACKSTEP" }]</a>
                <button type="submit" class="submitButton nextStep largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_SUBMITORDER" }]</button>
            </div>
        </form>
    [{/if}]
[{/if}]
[{ insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{assign var="template_title" value="PAGE_CHECKOUT_ORDER_TITLE"|oxmultilangassign}]
[{include file="layout/page.tpl" title=$template_title location=$template_title}]