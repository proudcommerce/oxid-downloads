<div id="miniBasket" class="basketBox">
[{if $oxcmp_basket->getProductsCount()}]
    [{oxhasrights ident="TOBASKET"}]
    [{if $oxcmp_basket->getProductsCount() gte 8}]
        [{assign var="scrollableBasket" value=true}]
    [{/if}]
        [{assign var="currency" value=$oView->getActCurrency() }]
        [{if $scrollableBasket}]
            [{oxscript include="js/scrollpane/jquery.jscrollpane.min.js"}]
            [{oxscript include="js/scrollpane/jquery.mousewheel.js"}]
            [{oxscript include="js/scrollpane/mwheelIntent.js"}]
            [{oxstyle include="css/jquery.jscrollpane.css"}]
        [{/if}]
        <div id="basketFlyout" class="basketFlyout corners[{if $scrollableBasket}] scrollable[{/if}]">
            <p class="title">
                <strong>[{$oxcmp_basket->getItemsCount()}] [{ oxmultilang ident="WIDGET_MINIBASKET_ITEMS_IN_BASKET" }]</strong>
                <img src="[{$oViewConf->getImageUrl()}]x.png" alt="" class="closePop">
            </p>
            [{if $scrollableBasket}]
                <div class="basketItems">
                <hr>
            [{/if}]
            <ul>
            [{foreach from=$oxcmp_basket->getContents() name=miniBasketList item=_product}]
                [{ assign var="minibasketItemTitle" value=$_product->getTitle() }]
                <li>
                    <a href="[{$_product->getLink()}]" title="[{ $minibasketItemTitle|strip_tags }]">
                        <span class="item">
                            [{if $_product->getAmount() gt 1}]
                                [{$_product->getAmount()}] x
                            [{/if}]
                            [{ $minibasketItemTitle|strip_tags }]
                        </span>
                        <strong class="price">[{$_product->getFTotalPrice()}] [{ $currency->sign}]</strong>
                    </a>
                </li>
            [{/foreach}]
            </ul>
            [{if $scrollableBasket}]
                </div>
                <hr>
            [{/if}]
            <p class="totals">
               <span class="item">[{ oxmultilang ident="WIDGET_MINIBASKET_TOTAL" }]</span><strong class="price">[{ $oxcmp_basket->getFProductsPrice()}] [{ $currency->sign}]</strong>
            </p>
            <hr>
            [{include file="widget/minibasket/countdown.tpl"}]
            <p class="functions clear">
               [{if $oxcmp_user}]
                    <a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=payment" }]" class="submitButton largeButton">[{ oxmultilang ident="WIDGET_MINIBASKET_CHECKOUT" }]</a>
               [{else}]
                    <a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=user" }]" class="submitButton largeButton">[{ oxmultilang ident="WIDGET_MINIBASKET_CHECKOUT" }]</a>
               [{/if}]
               <a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=basket" }]" class="textButton">[{ oxmultilang ident="WIDGET_MINIBASKET_DISPLAY_BASKET" }]</a>
            </p>
        </div>
    [{/oxhasrights}]
[{/if}]
[{if $oxcmp_basket->getItemsCount() > 0}]
<span class="counter FXgradOrange">
    [{insert name="oxid_newbasketitem" tpl="widget/minibasket/newbasketitemmsg.tpl" type="message"}]

        <span id="countValue">
         [{$oxcmp_basket->getItemsCount()}]
         </span>

</span>
[{/if}]
<img src="[{$oViewConf->getImageUrl()}]basket.png" class="minibasketIcon" alt="Basket">
</div>
