<div class="compareItem">
    [{assign var="currency" value=$oView->getActCurrency()}]
    [{if $showMainLink}]
        [{assign var='_productLink' value=$product->getMainLink()}]
    [{else}]
        [{assign var='_productLink' value=$product->getLink()}]
    [{/if}]

    <a href="[{ $_productLink }]" class="picture" [{if $oView->noIndex() }]rel="nofollow"[{/if}]>
      <img src="[{if $size=='big'}][{$product->getPictureUrl(1) }][{elseif $size=='thinest'}][{$product->getIconUrl() }][{else}][{ $product->getThumbnailUrl() }][{/if}]" alt="[{ $product->oxarticles__oxtitle->value|strip_tags }] [{ $product->oxarticles__oxvarselect->value|default:'' }]">
    </a>

    <strong class="title">
        <a class="fn" href="[{ $_productLink }]" [{if $oView->noIndex() }]rel="nofollow"[{/if}]>[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]</a>
    </strong>
    <span class="identifier">
        [{if $product->oxarticles__oxweight->value }]
            <div>
                <span title="weight">[{ oxmultilang ident="PAGE_PRODUCT_INC_PRODUCT_ARTWEIGHT" }]</span>
                <span class="value">[{ $product->oxarticles__oxweight->value }] [{ oxmultilang ident="PAGE_PRODUCT_INC_PRODUCT_ARTWEIGHT2" }]</span>
            </div>
        [{/if}]
        <span title="sku">[{ oxmultilang ident="PAGE_PRODUCT_INC_PRODUCT_ARTNOMBER2" }]</span>
        <span class="value">[{ $product->oxarticles__oxartnum->value }]</span>
    </span>

    [{if $size=='thin' || $size=='thinest'}]
        <span class="flag [{if $product->getStockStatus() == -1}]red[{elseif $product->getStockStatus() == 1}]orange[{elseif $product->getStockStatus() == 0}]green[{/if}]">&nbsp;</span>
    [{/if}]

    [{assign var="aVariantSelections" value=$product->getVariantSelections(null,null,1)}]
    [{assign var="blShowToBasket" value=true}] [{* tobasket or more info ? *}]
    [{if $product->isNotBuyable()||($aVariantSelections&&$aVariantSelections.selections)||$product->hasMdVariants()||($oViewConf->showSelectListsInList() && $product->getSelections(1))||$product->getVariants()}]
        [{assign var="blShowToBasket" value=false}]
    [{/if}]

    <form name="tobasket.[{$testid}]" [{if $blShowToBasket}]action="[{ $oViewConf->getSelfActionLink() }]" method="post"[{else}]action="[{$_productLink}]" method="get"[{/if}]>
        <div class="variants">
            [{oxhasrights ident="TOBASKET"}]
                [{ if $blShowToBasket}]
                    [{ $oViewConf->getHiddenSid() }]
                    [{ $oViewConf->getNavFormParams() }]
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
                    [{if $recommid}]
                        <input type="hidden" name="recommid" value="[{ $recommid }]">
                    [{/if}]
                    <input type="hidden" name="pgNr" value="[{ $oView->getActPage() }]">
                [{/if}]
            [{/oxhasrights}]

            [{if $aVariantSelections && $aVariantSelections.selections }]
                <div class="selectorsBox js-fnSubmit clear" id="compareVariantSelections_[{$testid}]">
                    [{foreach from=$aVariantSelections.selections item=oSelectionList key=iKey}]
                        [{include file="widget/product/selectbox.tpl" oSelectionList=$oSelectionList}]
                    [{/foreach}]
                </div>
            [{elseif $oViewConf->showSelectListsInList()}]
                [{assign var="oSelections" value=$product->getSelections(1)}]
                [{if $oSelections}]
                    <div class="selectorsBox js-fnSubmit clear" id="compareSelections_[{$testid}]">
                        [{foreach from=$oSelections item=oList name=selections}]
                            [{include file="widget/product/selectbox.tpl" oSelectionList=$oList sFieldName="sel" iKey=$smarty.foreach.selections.index blHideDefault=true sSelType="seldrop"}]
                        [{/foreach}]
                    </div>
                [{/if}]
            [{/if}]
        </div>

        <div class="tobasket">
            [{oxhasrights ident="SHOWARTICLEPRICE"}]
                [{if $product->getFTPrice() > $product->getFPrice()}]
                    <p class="oldPrice">
                        <strong>[{oxmultilang ident="DETAILS_REDUCEDFROM"}] <del>[{$product->getFTPrice()}] [{$currency->sign}]</del></strong>
                    </p>
                 [{/if}]
            [{/oxhasrights}]
            <div class="tobasketFunction clear">
                [{oxhasrights ident="SHOWARTICLEPRICE"}]
                    <label id="productPrice_[{$testid}]" class="price">
                        <strong>[{$product->getFPrice()}] [{$currency->sign}]</strong>
                    </label>
                    [{if $product->loadAmountPriceInfo()}]
                        <a class="selector corners FXgradBlueDark" href="#priceinfo"><img src="[{$oViewConf->getImageUrl('selectbutton.png')}]" alt="Select"></a>
                    [{/if}]
                [{/oxhasrights}]
                [{ if $blShowToBasket }]
                    [{oxhasrights ident="TOBASKET"}]
                        <p class="fn clear">
                            <input type="text" name="am" value="1" size="3" autocomplete="off" class="textbox" title="[{ oxmultilang ident="DETAILS_QUANTITY" }]">
                            <button type="submit" class="submitButton largeButton" title="[{oxmultilang ident="PAGE_PRODUCT_INC_PRODUCT_ADDTOCARD2"}]">[{oxmultilang ident="PAGE_PRODUCT_INC_PRODUCT_ADDTOCARD2"}]</button>
                        </p>
                    [{/oxhasrights}]
                [{else}]
                    <span >
                        <a id="variantMoreInfo_[{$testid}]" class="submitButton" href="[{ $_productLink }]" onclick="oxid.mdVariants.getMdVariantUrl('mdVariant_[{$testid}]'); return false;">[{ oxmultilang ident="PAGE_PRODUCT_INC_PRODUCT_VARIANTS_MOREINFO" }]</a>
                    </span>
                [{/if}]
            </div>
        </div>
    </form>
</div>