[{assign var="currency" value=$oView->getActCurrency()}]
[{if $showMainLink}]
    [{assign var='_productLink' value=$product->getMainLink()}]
[{else}]
    [{assign var='_productLink' value=$product->getLink()}]
[{/if}]
[{capture name=product_price}]
    [{oxhasrights ident="SHOWARTICLEPRICE"}]
        [{if $product->getFTPrice()}]
        <span class="priceOld">
            [{ oxmultilang ident="WIDGET_PRODUCT_PRODUCT_REDUCEDFROM" }] <del>[{ $product->getFTPrice()}] [{ $currency->sign}]</del>
        </span>
        [{/if}]
        [{if $product->getFPrice()}]
            <strong>[{ $product->getFPrice() }] [{ $currency->sign}][{if !($product->hasMdVariants() || ($oViewConf->showSelectListsInList() && $product->getSelections(1)) || $product->getVariantList())}] *[{/if}]</strong>
        [{/if}]
        [{if $product->getPricePerUnit()}]
            <span id="productPricePerUnit_[{$testid}]" class="pricePerUnit">
                [{$product->oxarticles__oxunitquantity->value}] [{$product->oxarticles__oxunitname->value}] | [{$product->getPricePerUnit()}] [{ $currency->sign}]/[{$product->oxarticles__oxunitname->value}]
            </span>
        [{elseif $product->oxarticles__oxweight->value  }]
            <span id="productPricePerUnit_[{$testid}]" class="pricePerUnit">
                <span class="type" title="weight">[{ oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ARTWEIGHT" }]</span>
                <span class="value">[{ $product->oxarticles__oxweight->value }] [{ oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ARTWEIGHT2" }]</span>
            </span>
        [{/if }]
    [{/oxhasrights}]
[{/capture}]
<a id="[{$testid}]" href="[{$_productLink}]" class="titleBlock title fn" title="[{ $product->oxarticles__oxtitle->value}]">
    [{ $product->oxarticles__oxtitle->value }]
    <div class="gridPicture"><img src="[{$product->getThumbnailUrl()}]" alt="[{ $product->oxarticles__oxtitle->value }]"></div>
</a>
<div class="priceBlock">
    [{oxhasrights ident="TOBASKET"}]
    [{ if !$product->isNotBuyable()}]
        [{$smarty.capture.product_price}]
        [{if $product->hasMdVariants() || ($oViewConf->showSelectListsInList() && $product->getSelections(1)) || $product->getVariantList()}]
            <a href="[{ $_productLink }]" class="toCart button">[{ oxmultilang ident="WIDGET_PRODUCT_PRODUCT_MOREINFO" }]</a>
        [{else}]
            [{assign var="listType" value=$oView->getListType()}]
            <a href="[{$oView->getLink()|oxaddparams:"listtype=`$listType`&amp;fnc=tobasket&amp;aid=`$product->oxarticles__oxid->value`&amp;am=1" }]" class="toCart button" title="[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ADDTOCART" }]">[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ADDTOCART" }]</a>
        [{/if}]
    [{/if}]
    [{/oxhasrights}]
</div>
