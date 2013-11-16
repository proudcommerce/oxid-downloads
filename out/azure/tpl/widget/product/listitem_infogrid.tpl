[{assign var="currency" value=$oView->getActCurrency()}]
[{if $showMainLink}]
    [{assign var='_productLink' value=$product->getMainLink()}]
[{else}]
    [{assign var='_productLink' value=$product->getLink()}]
[{/if}]
[{assign var="aVariantSelections" value=$product->getVariantSelections(null,null,1)}]
[{assign var="blShowToBasket" value=true}] [{* tobasket or more info ? *}]
[{if $product->isNotBuyable()||($aVariantSelections&&$aVariantSelections.selections)||$product->hasMdVariants()||($oViewConf->showSelectListsInList() && $product->getSelections(1))||$product->getVariants()}]
    [{assign var="blShowToBasket" value=false}]
[{/if}]

<form name="tobasket[{$testid}]" [{if $blShowToBasket}]action="[{ $oViewConf->getSelfActionLink() }]" method="post"[{else}]action="[{$_productLink}]" method="get"[{/if}]>
    [{ $oViewConf->getNavFormParams() }]
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="pgNr" value="[{ $oView->getActPage() }]">
    [{if $recommid}]
        <input type="hidden" name="recommid" value="[{ $recommid }]">
    [{/if}]
    [{oxhasrights ident="TOBASKET"}]
        [{ if $blShowToBasket}]
            <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
            [{if $owishid}]
                <input type="hidden" name="owishid" value="[{$owishid}]">
            [{/if}]
            [{if $toBasketFunction}]
                <input type="hidden" name="fnc" value="[{$toBasketFunction}]">
            [{else}]
              <input type="hidden" name="fnc" value="tobasket">
            [{/if}]
            <input type="hidden" name="aid" value="[{ $product->oxarticles__oxid->value }]">
            [{if $altproduct}]
                <input type="hidden" name="anid" value="[{ $altproduct }]">
            [{else}]
                <input type="hidden" name="anid" value="[{ $product->oxarticles__oxnid->value }]">
            [{/if}]
            <input type="hidden" name="am" value="1">
        [{/if}]
    [{/oxhasrights}]

    <a href="[{$_productLink}]" class="gridPicture">
        <img src="[{$product->getThumbnailUrl()}]" alt="[{ $product->oxarticles__oxtitle->value }]">
    </a>
    <div class="listDetails">
        <div class="titleBox">
            <a id="[{$testid}]" href="[{$_productLink}]" class="title fn" title="[{ $product->oxarticles__oxtitle->value}]">
                [{ $product->oxarticles__oxtitle->value }]
            </a>
        </div>

        <div class="selectorsBox">
            [{ if $aVariantSelections && $aVariantSelections.selections }]
                <div id="variantselector_[{$testid}]" class="variantBox selectorsBox fnSubmit clear">
                    [{foreach from=$aVariantSelections.selections item=oSelectionList key=iKey}]
                        [{include file="widget/product/selectbox.tpl" oSelectionList=$oSelectionList}]
                    [{/foreach}]
                </div>
            [{elseif $oViewConf->showSelectListsInList()}]
                [{assign var="oSelections" value=$product->getSelections(1)}]
                [{if $oSelections}]
                    <div id="selectlistsselector_[{$testid}]" class="selectorsBox fnSubmit clear">
                        [{foreach from=$oSelections item=oList name=selections}]
                            [{include file="widget/product/selectbox.tpl" oSelectionList=$oList sFieldName="sel" iKey=$smarty.foreach.selections.index blHideDefault=true sSelType="seldrop"}]
                        [{/foreach}]
                    </div>
                [{/if}]
            [{/if }]
        </div>

        <div class="priceBox">
            <div class="content">
               [{* To do:  place for compare link }]
                  <a class="compare" href="#">VERGLEICHEN</a><br><br>
               [{* ======================================= *}]
                [{oxhasrights ident="SHOWARTICLEPRICE"}]
                    [{if $product->getFTPrice()}]
                        <span class="oldPrice">[{ oxmultilang ident="WIDGET_PRODUCT_PRODUCT_REDUCEDFROM" }] <del>[{ $product->getFTPrice()}] [{ $currency->sign}]</del></span>
                    [{/if}]
                    [{if $product->getFPrice()}]
                        <span class="price">[{ $product->getFPrice() }] [{ $currency->sign}] [{if !($product->hasMdVariants() || ($oViewConf->showSelectListsInList() && $product->getSelections(1)) || $product->getVariantList())}]*[{/if}]</span>
                    [{/if}]
                    [{ if $product->getPricePerUnit()}]
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
            </div>
        </div>
        <div class="buttonBox">
            [{ if $blShowToBasket }]
                [{oxhasrights ident="TOBASKET"}]
                    <button type="submit" class="submitButton largeButton">[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ADDTOCART" }]</button>
                [{/oxhasrights}]
            [{else}]
                <a class="submitButton largeButton" href="[{ $_productLink }]" >[{ oxmultilang ident="WIDGET_PRODUCT_PRODUCT_MOREINFO" }]</a>
            [{/if}]
        </div>
    </div>
</form>
