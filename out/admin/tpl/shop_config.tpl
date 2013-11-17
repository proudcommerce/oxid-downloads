[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function _groupExp(el) {
    var _cur = el.parentNode;

    if (_cur.className == "exp") _cur.className = "";
      else _cur.className = "exp";
}
//-->
</script>

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

[{cycle assign="_clear_" values=",2" }]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="shop_config">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{ $shop->id }]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="shop_config">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxshops__oxid]" value="[{ $oxid }]">


    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_GLOBAL" }]</b></a>
            <dl>
                <dt>
                    <select class="select" multiple size="4" name=confarrs[aHomeCountry][] [{ $readonly}]>
                        [{ foreach from=$countrylist item=oCountry}]
                        <option value="[{$oCountry->oxcountry__oxid->value}]"[{if $oCountry->selected}] selected[{/if}]>[{$oCountry->oxcountry__oxtitle->value}]</option>
                        [{/foreach}]
                    </select>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_INLANDCUSTOMERS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_INLANDCUSTOMERS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_SEARCH" }]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blAutoSearchOnCat] value=false>
                    <input type=checkbox name=confbools[blAutoSearchOnCat] value=true  [{if ($confbools.blAutoSearchOnCat)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_AUTOSEARCHONCAT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_AUTOSEARCHONCAT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aSearchCols] [{ $readonly}]>[{$confarrs.aSearchCols}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SEARCHFIELDS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SEARCHFIELDS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blSearchUseAND] value=false>
                    <input type=checkbox name=confbools[blSearchUseAND] value=true  [{if ($confbools.blSearchUseAND)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SEARCHUSEAND" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_STOCK" }]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blUseStock] value=false>
                    <input type=checkbox name=confbools[blUseStock] value=true  [{if ($confbools.blUseStock)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_USESTOCK" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_USESTOCK" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowNegativeStock] value=false>
                    <input type=checkbox name=confbools[blAllowNegativeStock] value=true  [{if ($confbools.blAllowNegativeStock)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_USENEGATIVESTOCK" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_USENEGATIVESTOCK" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input type=text class="txt" name=confstrs[sStockWarningLimit] value="[{$confstrs.sStockWarningLimit}]" [{ $readonly}]>
            [{ oxinputhelp ident="HELP_SHOP_CONFIG_STOCKWARNINGLIMIT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_STOCKWARNINGLIMIT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStockOnDefaultMessage] value=false>
                    <input type=checkbox name=confbools[blStockOnDefaultMessage] value=true  [{if ($confbools.blStockOnDefaultMessage)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_STOCKONDEFAULTMESSAGE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_STOCKONDEFAULTMESSAGE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStockOffDefaultMessage] value=false>
                    <input type=checkbox name=confbools[blStockOffDefaultMessage] value=true  [{if ($confbools.blStockOffDefaultMessage)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_STOCKOFFDEFAULTMESSAGE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_STOCKOFFDEFAULTMESSAGE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_ARTICLES" }]</b></a>
            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aNrofCatArticles] [{ $readonly}]>[{$confarrs.aNrofCatArticles}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NROFCATARTICLES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NROFCATARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofSimilarArticles] value="[{$confstrs.iNrofSimilarArticles}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NUMBEROFSIMILARARTICLES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NUMBEROFSIMILARARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofCustomerWhoArticles] value="[{$confstrs.iNrofCustomerWhoArticles}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NROFCUSTOMERWHOARTICLES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NROFCUSTOMERWHOARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofNewcomerArticles] value="[{$confstrs.iNrofNewcomerArticles}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NROFNEWCOMERARTICLES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NROFNEWCOMERARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofCrossellArticles] value="[{$confstrs.iNrofCrossellArticles}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NUMBEROFCROSSSELLARTICLES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NUMBEROFCROSSSELLARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowSorting] value=false>
                    <input type=checkbox name=confbools[blShowSorting] value=true  [{if ($confbools.blShowSorting)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SORTITEMSLIST" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SORTITEMSLIST" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aSortCols] [{ $readonly}]>[{$confarrs.aSortCols}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SORTFIELDS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SORTFIELDS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blOverrideZeroABCPrices] value=false>
                    <input type=checkbox name=confbools[blOverrideZeroABCPrices] value=true  [{if ($confbools.blOverrideZeroABCPrices)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_OVERRIDEZEROABCPRICES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_OVERRIDEZEROABCPRICES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blWarnOnSameArtNums] value=false>
                    <input type=checkbox name=confbools[blWarnOnSameArtNums] value=true [{if ($confbools.blWarnOnSameArtNums)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_WARNONSAMEARTNUMS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_WARNONSAMEARTNUMS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blNewArtByInsert] value=false>
                    <input type=checkbox name=confbools[blNewArtByInsert] value=true  [{if ($confbools.blNewArtByInsert)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NEWARTBYINSERT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NEWARTBYINSERT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blDisableDublArtOnCopy] value=false>
                    <input type=checkbox name=confbools[blDisableDublArtOnCopy] value=true [{if ($confbools.blDisableDublArtOnCopy)}]checked[{/if}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_DISABLEARTDUBLICATES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DISABLEARTDUBLICATES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_ORDER" }]</b></a>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sMidlleCustPrice] value="[{$confstrs.sMidlleCustPrice}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_MIDLLECUSTOMERPRICE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MIDLLECUSTOMERPRICE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sLargeCustPrice] value="[{$confstrs.sLargeCustPrice}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_LARGECUSTOMERPRICE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_LARGECUSTOMERPRICE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blAllowUnevenAmounts] value=false>
                    <input type=checkbox name=confbools[blAllowUnevenAmounts] value=true  [{if ($confbools.blAllowUnevenAmounts)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_ALLOWUNEVENAMOUNTS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ALLOWUNEVENAMOUNTS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMinOrderPrice] value="[{$confstrs.iMinOrderPrice}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_MINORDERPRICE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MINORDERPRICE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowBirthdayFields] value=false>
                    <input type=checkbox name=confbools[blShowBirthdayFields] value=true  [{if ($confbools.blShowBirthdayFields)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWBIRTHDAYFIELDS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SHOWBIRTHDAYFIELDS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowOrderButtonOnTop] value=false>
                    <input type=checkbox name=confbools[blShowOrderButtonOnTop] value=true  [{if ($confbools.blShowOrderButtonOnTop)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWORDERBUTTONONTHETOP" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SHOWORDERBUTTONONTHETOP" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blConfirmAGB] value=false>
                    <input type=checkbox name=confbools[blConfirmAGB] value=true  [{if ($confbools.blConfirmAGB)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CONFIRMAGB" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CONFIRMAGB" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStoreCreditCardInfo] value=false>
                    <input type=checkbox name=confbools[blStoreCreditCardInfo] value=true  [{if ($confbools.blStoreCreditCardInfo)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_STORECREDITCARDINFO" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_STORECREDITCARDINFO" }]<br>[{ oxmultilang ident="SHOP_CONFIG_ATTENTION" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_VAT" }]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blCalcVATForDelivery] value=false>
                    <input type=checkbox name=confbools[blCalcVATForDelivery] value=true  [{if ($confbools.blCalcVATForDelivery)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CALCULATEVATFORDELIVERY" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CALCULATEVATFORDELIVERY" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blDeliveryVatOnTop] value=false>
                    <input type=checkbox name=confbools[blDeliveryVatOnTop] value=true  [{if ($confbools.blDeliveryVatOnTop)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CALCDELVATONTOP" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CALCDELVATONTOP" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blCalcVATForPayCharge] value=false>
                    <input type=checkbox name=confbools[blCalcVATForPayCharge] value=true  [{if ($confbools.blCalcVATForPayCharge)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CALCULATEVATOFORPAYCHARGE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CALCULATEVATOFORPAYCHARGE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[dDefaultVAT] value="[{$confstrs.dDefaultVAT}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_DEFAULTVAT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DEFAULTVAT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blCalcVatForWrapping] value=false>
                    <input type=checkbox name=confbools[blCalcVatForWrapping] value=true  [{if ($confbools.blCalcVatForWrapping)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CALCULATEVATFORWRAPPING" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CALCULATEVATFORWRAPPING" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShippingCountryVat] value=false>
                    <input type=checkbox name=confbools[blShippingCountryVat] value=true  [{if ($confbools.blShippingCountryVat)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHIPPINGCOUNTRYVAT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SHIPPINGCOUNTRYVAT" }]
                </dd>
                <div class="spacer"></div>
            </dl>


         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_PICTURES" }]</b></a>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iUseGDVersion] value="[{$confstrs.iUseGDVersion}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_USEGDVERSION" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_USEGDVERSION" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sIconsize] value="[{$confstrs.sIconsize}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_ICONSIZE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ICONSIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blAutoIcons] value=false>
                    <input type=checkbox name=confbools[blAutoIcons] value=true  [{if ($confbools.blAutoIcons)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_AUTOICONS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_AUTOICONS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sThumbnailsize] value="[{$confstrs.sThumbnailsize}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_THUMBNAILSIZE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_THUMBNAILSIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aDetailImageSizes] [{ $readonly}]>[{$confaarrs.aDetailImageSizes}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_DETAILIMAGESIZE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DETAILIMAGESIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sZoomImageSize] value="[{$confstrs.sZoomImageSize}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_ZOOMIMAGESIZE" }]
                </dt>
                <dd>
                [{ oxmultilang ident="SHOP_CONFIG_ZOOMIMAGESIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCatThumbnailsize] value="[{$confstrs.sCatThumbnailsize}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CATEGORYTHUMBNAILSIZE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CATEGORYTHUMBNAILSIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_SHOP_FRONTEND" }]</b></a>
            <dl>
                <dt>
                </dt>
                <dd>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SELECTSHOPCOMPONENTS" }][{ oxmultilang ident="SHOP_CONFIG_SELECTSHOPCOMPONENTS" }]
                    <div style="margin-left:10px;">
                        <input type=hidden name=confbools[bl_showVouchers] value=false>
                        <input type=checkbox name=confbools[bl_showVouchers] value=true  [{if ($confbools.bl_showVouchers)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWVOUCHERS" }]
                        [{ oxmultilang ident="SHOP_CONFIG_SHOWVOUCHERS" }]
                        <br>

                        <input type=hidden name=confbools[bl_showOpenId] value=false>
                        <input type=checkbox name=confbools[bl_showOpenId] value=true  [{if ($confbools.bl_showOpenId)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWOPENID" }]
                        [{ oxmultilang ident="SHOP_CONFIG_SHOWOPENID" }]
                        <br>

                        <input type=hidden name=confbools[bl_showGiftWrapping] value=false>
                        <input type=checkbox name=confbools[bl_showGiftWrapping] value=true  [{if ($confbools.bl_showGiftWrapping)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWGIFTWRAPPING" }]
                        [{ oxmultilang ident="SHOP_CONFIG_SHOWGIFTWRAPPING" }]
                        <br>

                        <input type=hidden name=confbools[bl_showListmania] value=false>
                        <input type=checkbox name=confbools[bl_showListmania] value=true  [{if ($confbools.bl_showListmania)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWLISTMANIA" }]
                        [{ oxmultilang ident="SHOP_CONFIG_SHOWLISTMANIA" }]
                        <br>

                        <input type=hidden name=confbools[bl_showWishlist] value=false>
                        <input type=checkbox name=confbools[bl_showWishlist] value=true  [{if ($confbools.bl_showWishlist)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWWISHLIST" }]
                        [{ oxmultilang ident="SHOP_CONFIG_SHOWWISHLIST" }]
                        <br>

                        <input type=hidden name=confbools[bl_showCompareList] value=false>
                        <input type=checkbox name=confbools[bl_showCompareList] value=true  [{if ($confbools.bl_showCompareList)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_SHOWCOMPARELIST" }]
                        [{ oxmultilang ident="SHOP_CONFIG_SHOWCOMPARELIST" }]
                    </div>
                </dd>
                <div class="spacer"></div>
            </dl>


            <dl>
                <dt>
                    <input type=hidden name=confbools[blTopNaviLayout] value=false>
                    <input type=checkbox name=confbools[blTopNaviLayout] value=true  [{if ($confbools.blTopNaviLayout)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_TOPNAVILAYOUT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_TOPNAVILAYOUT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type="button" value="[{if isset($defcat) && isset($defcat->oxcategories__oxtitle)}][{$defcat->oxcategories__oxtitle->value}][{else}]---[{/if}]" onclick="JavaScript:showDialog('&cl=shop_config&aoc=1&oxid=[{$oxid|escape:'url'}]');">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_TOPNAVICATCOUNT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ACTIVECATEGORYBYSTART" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[iTopNaviCatCount] value="[{$confstrs.iTopNaviCatCount}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_ACTIVECATEGORYBYSTART" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_TOPNAVICATCOUNT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCntOfNewsLoaded] value="[{$confstrs.sCntOfNewsLoaded}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CNTOFNEWS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CNTOFNEWS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_ADMINISTRATION" }]</b></a>
            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aCMSfolder] [{ $readonly}]>[{$confaarrs.aCMSfolder}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CMSFOLDER" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CMSFOLDER" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blOrderOptInEmail] value=false>
                    <input type=checkbox name=confbools[blOrderOptInEmail] value=true  [{if ($confbools.blOrderOptInEmail)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_ORDEROPTINEMAIL" }]
                </dt>
                <dd>
                   [{ oxmultilang ident="SHOP_CONFIG_ORDEROPTINEMAIL" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aOrderfolder] [{ $readonly}]>[{$confaarrs.aOrderfolder}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_ORDERFOLDER" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ORDERFOLDER" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sLocalDateFormat]" class="select" [{ $readonly}]>
                        <option value="ISO" [{if $confstrs.sLocalDateFormat == "ISO"}]selected[{/if}]>ISO: YYYY-MM-DD</option>
                        <option value="EUR" [{if $confstrs.sLocalDateFormat == "EUR"}]selected[{/if}]>EUR: DD.MM.YYYY</option>
                        <option value="USA" [{if $confstrs.sLocalDateFormat == "USA"}]selected[{/if}]>USA: MM/DD/YYYY</option>
                    </select>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_DATEFORMAT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DATEFORMAT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sLocalTimeFormat]" class="select" [{ $readonly}]>
                        <option value="ISO" [{if $confstrs.sLocalTimeFormat == "ISO"}]selected[{/if}]>ISO: HH:MM:SS</option>
                        <option value="EUR" [{if $confstrs.sLocalTimeFormat == "EUR"}]selected[{/if}]>EUR: HH.MM.SS</option>
                        <option value="USA" [{if $confstrs.sLocalTimeFormat == "USA"}]selected[{/if}]>USA: HH:MM:SS AM (PM)</option>
                    </select>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_TIMEFORMAT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_TIMEFORMAT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name="confbools[blLoadDynContents]" value="false">
                    <input type=checkbox name="confbools[blLoadDynContents]" value="true"  [{if ($confbools.blLoadDynContents)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_LOAD_DYNAMIC_PAGES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_LOAD_DYNAMIC_PAGES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name="confbools[blCheckForUpdates]" value="false">
                    <input type=checkbox name="confbools[blCheckForUpdates]" value="true"  [{if ($confbools.blCheckForUpdates)}]checked[{/if}] [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CHECK_UPDATES" }]
                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_OTHER_SETTINGS" }]</b></a>
            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aMustFillFields] [{ $readonly}]>[{$confarrs.aMustFillFields}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_MUSTFILLFIELDS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MUSTFILLFIELDS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMaxGBEntriesPerDay] value="[{$confstrs.iMaxGBEntriesPerDay}]" [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_MAXGBENTRIESPERDAY" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MAXGBENTRIESPERDAY" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aCurrencies] [{ $readonly}]>[{$confarrs.aCurrencies}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_SETORDELETECURRENCY" }]
                </dt>
                <dd>
                  [{ oxmultilang ident="SHOP_CONFIG_SETORDELETECURRENCY" }]<br>
                  [name]@[rate]@[decimal separator]@[thousand separator]@[symbol]@[decimal precision]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blExclNonMaterialFromDelivery] value=false>
                    <input type=checkbox name=confbools[blExclNonMaterialFromDelivery] value=true  [{if ($confbools.blExclNonMaterialFromDelivery)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_EXCLUDENONMATERIALPRODUCTS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_EXCLUDENONMATERIALPRODUCTS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blBidirectCross] value=false>
                    <input type=checkbox name=confbools[blBidirectCross] value=true  [{if ($confbools.blBidirectCross)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_BIDIRECTCROSS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_BIDIRECTCROSS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRatingLogsTimeout] value="[{$confstrs.iRatingLogsTimeout}]" [{ $readonly }]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_DELETERATINGLOGS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DELETERATINGLOGS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRssItemsCount] value="[{$confstrs.iRssItemsCount}]" [{ $readonly }]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSITEMSCOUNT" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_RSSITEMSCOUNT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                </dt>
                <dd>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSSELECTED" }][{ oxmultilang ident="SHOP_CONFIG_RSSSELECTED" }]
                    <div style="margin-left:10px;">
                        <input type=hidden name=confbools[bl_rssTopShop] value=false>
                        <input type=checkbox name=confbools[bl_rssTopShop] value=true  [{if ($confbools.bl_rssTopShop)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSTOPSHOP" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSTOPSHOP" }]
                        <br />

                        <input type=hidden name=confbools[bl_rssBargain] value=false>
                        <input type=checkbox name=confbools[bl_rssBargain] value=true  [{if ($confbools.bl_rssBargain)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSBARGAIN" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSBARGAIN" }]
                        <br />

                        <input type=hidden name=confbools[bl_rssNewest] value=false>
                        <input type=checkbox name=confbools[bl_rssNewest] value=true  [{if ($confbools.bl_rssNewest)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSNEWEST" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSNEWEST" }]
                        <br />

                        <input type=hidden name=confbools[bl_rssCategories] value=false>
                        <input type=checkbox name=confbools[bl_rssCategories] value=true  [{if ($confbools.bl_rssCategories)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSCATEGORIES" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSCATEGORIES" }]
                        <br />

                        <input type=hidden name=confbools[bl_rssSearch] value=false>
                        <input type=checkbox name=confbools[bl_rssSearch] value=true  [{if ($confbools.bl_rssSearch)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSSEARCH" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSSEARCH" }]
                        <br />

                        <input type=hidden name=confbools[bl_rssRecommLists] value=false>
                        <input type=checkbox name=confbools[bl_rssRecommLists] value=true  [{if ($confbools.bl_rssRecommLists)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSARTRECOMMLISTS" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSARTRECOMMLISTS" }]
                        <br />

                        <input type=hidden name=confbools[bl_rssRecommListArts] value=false>
                        <input type=checkbox name=confbools[bl_rssRecommListArts] value=true  [{if ($confbools.bl_rssRecommListArts)}]checked[{/if}] [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_SHOP_CONFIG_RSSRECOMMLISTARTS" }]
                        [{ oxmultilang ident="SHOP_CONFIG_RSSRECOMMLISTARTS" }]
                    </div>
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blCalculateDelCostIfNotLoggedIn] value=false>
                    <input type=checkbox name=confbools[blCalculateDelCostIfNotLoggedIn] value=true  [{if ($confbools.blCalculateDelCostIfNotLoggedIn)}]checked[{/if}] [{ $readonly}]>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_DELIVERYCOSTS" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DELIVERYCOSTS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input type=hidden name=confbools[blEnterNetPrice] value=false>
            <input type=checkbox name=confbools[blEnterNetPrice] value=true  [{if ($confbools.blEnterNetPrice)}]checked[{/if}] [{ $readonly}]>
            [{ oxinputhelp ident="HELP_SHOP_CONFIG_ENTERNETPRICE" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ENTERNETPRICE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sGiCsvFieldEncloser] value="[{$confstrs.sGiCsvFieldEncloser}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CSVFIELDENCLOSER" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCSVSign] value="[{$confstrs.sCSVSign}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CSVSEPARATOR" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CSVSEPARATOR" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sDecimalSeparator] value="[{$confstrs.sDecimalSeparator}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_CSVDECIMALSEPARATOR" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CSVDECIMALSEPARATOR" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iExportNrofLines] value="[{$confstrs.iExportNrofLines}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_EXPORTNUMBEROFLINES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_EXPORTNUMBEROFLINES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iExportTickerRefresh] value="[{$confstrs.iExportTickerRefresh}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_EXPORTTICKERREFRESH" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_EXPORTTICKERREFRESH" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iImportNrofLines] value="[{$confstrs.iImportNrofLines}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_IMPORTNUMBEROFLINES" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_IMPORTNUMBEROFLINES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iImportTickerRefresh] value="[{$confstrs.iImportTickerRefresh}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_IMPORTTICKERREFRESH" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_IMPORTTICKERREFRESH" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iCntofMails] value="[{$confstrs.iCntofMails}]">
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_NUMBEROFEMAILSPERTICK" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NUMBEROFEMAILSPERTICK" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aLexwareVAT] [{ $readonly}]>[{$confaarrs.aLexwareVAT}]</textarea>
                    [{ oxinputhelp ident="HELP_SHOP_CONFIG_MWSTSETTING" }]
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MWSTSETTING" }]
                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>


    <br>

    <input type="submit" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly}]>


</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
