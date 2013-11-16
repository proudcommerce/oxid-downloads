[{oxstyle include="css/checkout.css"}]
[{capture append="oxidBlock_content"}]

[{* ordering steps *}]
[{include file="page/checkout/inc/steps.tpl" active=1 }]

[{assign var="currency" value=$oView->getActCurrency() }]
[{if !$oxcmp_basket->getProductsCount()  }]
    <div class="status corners error">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_EMPTYSHIPPINGCART" }]</div>
[{else }]
    <div class="lineBox clear">
        [{if $oView->showBackToShop()}]
            <div class="backtoshop">
                <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                    [{ $oViewConf->getHiddenSid() }]
                    <input type="hidden" name="cl" value="basket">
                    <input type="hidden" name="fnc" value="backtoshop">
                    <button type="submit" class="submitButton">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_CONTINUESHOPPING" }]</button>
                </form>
            </div>
        [{/if}]

        [{if $oView->isLowOrderPrice() }]
            <div>[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_MINORDERPRICE" }] [{ $oView->getMinOrderPrice() }] [{ $currency->sign }]</div>
        [{else}]
            [{block name="basket_btn_next_top"}]
                <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                    [{ $oViewConf->getHiddenSid() }]
                    <input type="hidden" name="cl" value="user">
                    <button type="submit" class="submitButton largeButton nextStep">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_NEXTSTEP" }]</button>
                </form>
            [{/block}]
        [{/if}]
    </div>

    [{include file="page/checkout/inc/basketcontents.tpl" editable=true}]

    [{if $oViewConf->getShowVouchers()}]
        <form name="voucher" action="[{ $oViewConf->getSelfActionLink() }]" method="post" class="left oxValidate">
            <div class="couponBox" id="coupon">
                [{foreach from=$Errors.basket item=oEr key=key}]
                    [{if $oEr->getErrorClassType() == 'oxVoucherException'}]
                    <div class="inlineError">
                        [{ oxmultilang ident="PAGE_CHECKOUT_BASKET_COUPONNOTACCEPTED1" }] <strong>&ldquo;[{ $oEr->getValue('voucherNr') }]&rdquo;</strong> [{ oxmultilang ident="PAGE_CHECKOUT_BASKET_COUPONNOTACCEPTED2" }]<br>
                        <strong>[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_REASON" }]</strong>
                        [{ $oEr->getOxMessage() }]
                    </div>
                    [{/if}]
                [{/foreach}]
                <label>[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_ENTERCOUPONNUMBER" }]</label>
                [{ $oViewConf->getHiddenSid() }]
                <input type="hidden" name="cl" value="basket">
                <input type="hidden" name="fnc" value="addVoucher">
                <input type="text" size="20" name="voucherNr" class="textbox oxValidate oxValidate_notEmpty">
                <button type="submit" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_SUBMITCOUPON" }]</button>
                <p class="oxValidateError">
                    <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
                </p>
                <input type="hidden" name="CustomError" value='basket'>
            </div>
        </form>
    [{/if}]
    <div class="lineBox clear">
        [{if $oView->showBackToShop()}]
            <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                <div class="backtoshop">
                    [{ $oViewConf->getHiddenSid() }]
                    <input type="hidden" name="cl" value="basket">
                    <input type="hidden" name="fnc" value="backtoshop">
                    <button type="submit" class="submitButton largeButton">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_CONTINUESHOPPING" }]</button>
                </div>
            </form>
        [{/if}]

        [{if $oView->isLowOrderPrice() }]
            <div>[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_MINORDERPRICE" }] [{ $oView->getMinOrderPrice() }] [{ $currency->sign }]</div>
        [{else}]
            [{block name="basket_btn_next_bottom"}]
            <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                [{ $oViewConf->getHiddenSid() }]
                <input type="hidden" name="cl" value="user">
                <button type="submit" class="submitButton largeButton nextStep">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_NEXTSTEP" }]</button>
            </form>
            [{/block}]
        [{/if}]
    </div>
[{/if }]
[{if $oView->isWrapping() }]
[{include file="page/checkout/inc/wrapping.tpl"}]
[{/if}]
[{oxscript add="$(function(){oxid.initBasket();});"}]
[{insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{include file="layout/page.tpl"}]