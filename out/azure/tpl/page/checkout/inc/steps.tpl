<ul class="checkoutSteps clear">
    [{if $oxcmp_basket->getProductsCount() }]
        [{assign var=showStepLinks value=true}]
    [{/if}]
    <li class="step1[{ if $active == 1}] active [{elseif $active > 1}] passed [{/if}]">
        <span>
        [{if $showStepLinks}]<a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getBasketLink() }]">[{/if}]
        [{ oxmultilang ident="PAGE_CHECKOUT_STEPS_BASKET" }]
        [{if $showStepLinks}]</a>[{/if}]
        </span>
    </li>
    [{assign var=showStepLinks value=false}]
    [{if !$oView->isLowOrderPrice() && $oxcmp_basket->getProductsCount() }]
        [{assign var=showStepLinks value=true}]
    [{/if}]
    <li class="step2[{ if $active == 2}] active [{elseif $active > 2}] passed [{/if}]">
        <span>
        [{if $showStepLinks}]<a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getOrderLink() }]">[{/if}]
        [{ oxmultilang ident="PAGE_CHECKOUT_STEPS_SEND" }]
        [{if $showStepLinks}]</a>[{/if}]
        </span>
    </li>
    [{assign var=showStepLinks value=false}]
    [{if $active != 1 && $oxcmp_user && !$oView->isLowOrderPrice() && $oxcmp_basket->getProductsCount() }]
        [{assign var=showStepLinks value=true}]
    [{/if}]
    <li class="step3[{ if $active == 3}] active [{elseif $active > 3}] passed [{/if}]">
        <span>
        [{if $showStepLinks}]<a rel="nofollow" [{if $oViewConf->getActiveClassName() == "user"}]id="paymentStep"[{/if}] href="[{ oxgetseourl ident=$oViewConf->getPaymentLink() }]">[{/if}]
        [{ oxmultilang ident="PAGE_CHECKOUT_STEPS_PAY" }]
        [{if $showStepLinks}]</a>[{/if}]
        </span>
    </li>
    [{assign var=showStepLinks value=false}]
    [{if $active != 1 && $oxcmp_user && $oxcmp_basket->getProductsCount() && $oView->getPaymentList() && !$oView->isLowOrderPrice()}]
        [{assign var=showStepLinks value=true}]
    [{/if}]
    <li class="step4[{ if $active == 4}] active [{elseif $active > 4}] passed [{/if}]">
        <span>
        [{if $showStepLinks}]<a rel="nofollow" [{if $oViewConf->getActiveClassName() == "payment"}]id="orderStep"[{/if}] href="[{ oxgetseourl ident=$oViewConf->getOrderConfirmLink() }]">[{/if}]
        [{ oxmultilang ident="PAGE_CHECKOUT_STEPS_ORDER" }]
        [{if $showStepLinks}]</a>[{/if}]
        </span>
    </li>
    <li class="step5[{ if $active == 5}] activeLast [{else}] defaultLast [{/if}] ">
        <span>
        [{ oxmultilang ident="PAGE_CHECKOUT_STEPS_LASTSTEP" }]
        </span>
    </li>
</ul>