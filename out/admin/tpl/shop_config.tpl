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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_AUTOSEARCHONCAT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aSearchCols] [{ $readonly}]>[{$confarrs.aSearchCols}]</textarea>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_USENEGATIVESTOCK" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input type=text class="txt" name=confstrs[sStockWarningLimit] value="[{$confstrs.sStockWarningLimit}]" [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NROFCATARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofSimilarArticles] value="[{$confstrs.iNrofSimilarArticles}]" [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NUMBEROFSIMILARARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofCustomerWhoArticles] value="[{$confstrs.iNrofCustomerWhoArticles}]" [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NROFCUSTOMERWHOARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofNewcomerArticles] value="[{$confstrs.iNrofNewcomerArticles}]" [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NROFNEWCOMERARTICLES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iNrofCrossellArticles] value="[{$confstrs.iNrofCrossellArticles}]" [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_SORTITEMSLIST" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aSortCols] [{ $readonly}]>[{$confarrs.aSortCols}]</textarea>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MIDLLECUSTOMERPRICE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sLargeCustPrice] value="[{$confstrs.sLargeCustPrice}]" [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ALLOWUNEVENAMOUNTS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMinOrderPrice] value="[{$confstrs.iMinOrderPrice}]" [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CALCULATEVATOFORPAYCHARGE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[dDefaultVAT] value="[{$confstrs.dDefaultVAT}]" [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CALCULATEVATFORWRAPPING" }]
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_USEGDVERSION" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sIconsize] value="[{$confstrs.sIconsize}]" [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_AUTOICONS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sThumbnailsize] value="[{$confstrs.sThumbnailsize}]" [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_THUMBNAILSIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aDetailImageSizes] [{ $readonly}]>[{$confaarrs.aDetailImageSizes}]</textarea>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DETAILIMAGESIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aZoomImageSizes] [{ $readonly}]>[{$confaarrs.aZoomImageSizes}]</textarea>
                </dt>
                <dd>
                [{ oxmultilang ident="SHOP_CONFIG_ZOOMIMAGESIZE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCatThumbnailsize] value="[{$confstrs.sCatThumbnailsize}]" [{ $readonly}]>
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
                    <input type=hidden name=confbools[blTopNaviLayout] value=false>
                    <input type=checkbox name=confbools[blTopNaviLayout] value=true  [{if ($confbools.blTopNaviLayout)}]checked[{/if}] [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_TOPNAVILAYOUT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type="button" value="[{if isset($defcat) && isset($defcat->oxcategories__oxtitle)}][{$defcat->oxcategories__oxtitle->value}][{else}]---[{/if}]" onclick="JavaScript:showDialog('?cl=shop_config&aoc=1&oxid=[{$oxid|escape:'url'}]');">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_ACTIVECATEGORYBYSTART" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" style="width:70" name=confstrs[iTopNaviCatCount] value="[{$confstrs.iTopNaviCatCount}]" [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_TOPNAVICATCOUNT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sCntOfNewsLoaded] value="[{$confstrs.sCntOfNewsLoaded}]" [{ $readonly}]>
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
                </dt>
                <dd>
                   [{ oxmultilang ident="SHOP_CONFIG_ORDEROPTINEMAIL" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aOrderfolder] [{ $readonly}]>[{$confaarrs.aOrderfolder}]</textarea>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MUSTFILLFIELDS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iMaxGBEntriesPerDay] value="[{$confstrs.iMaxGBEntriesPerDay}]" [{ $readonly}]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_MAXGBENTRIESPERDAY" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aCurrencies] [{ $readonly}]>[{$confarrs.aCurrencies}]</textarea>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_BIDIRECTCROSS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRatingLogsTimeout] value="[{$confstrs.iRatingLogsTimeout}]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_DELETERATINGLOGS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRssItemsCount] value="[{$confstrs.iRssItemsCount}]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_RSSITEMSCOUNT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confarrs[aRssSelected] value=''>
                    <select class="select" multiple name=confarrs[aRssSelected][] [{ $readonly}]>
                      [{if $confarrs.aRssSelected|is_array}]
                        <option value="oxrss_topshop"[{if in_array('oxrss_topshop', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSTOPSHOP" }]</option>
                        <option value="oxrss_bargain"[{if in_array('oxrss_bargain', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSBARGAIN" }]</option>
                        <option value="oxrss_newest"[{if in_array('oxrss_newest', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSNEWEST" }]</option>
                        <option value="oxrss_categories"[{if in_array('oxrss_categories', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSCATEGORIES" }]</option>
                        <option value="oxrss_search"[{if in_array('oxrss_search', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSSEARCH" }]</option>
                        <option value="oxrss_recommlists"[{if in_array('oxrss_recommlists', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSARTRECOMMLISTS" }]</option>
                        <option value="oxrss_recommlistarts"[{if in_array('oxrss_recommlistarts', $confarrs.aRssSelected)}] selected[{/if}]>[{ oxmultilang ident="SHOP_CONFIG_RSSRECOMMLISTARTS" }]</option>
                      [{else}]
                        <option value="oxrss_topshop">[{ oxmultilang ident="SHOP_CONFIG_RSSTOPSHOP" }]</option>
                        <option value="oxrss_bargain">[{ oxmultilang ident="SHOP_CONFIG_RSSBARGAIN" }]</option>
                        <option value="oxrss_newest">[{ oxmultilang ident="SHOP_CONFIG_RSSNEWEST" }]</option>
                        <option value="oxrss_categories">[{ oxmultilang ident="SHOP_CONFIG_RSSCATEGORIES" }]</option>
                        <option value="oxrss_search">[{ oxmultilang ident="SHOP_CONFIG_RSSSEARCH" }]</option>
                        <option value="oxrss_recommlists">[{ oxmultilang ident="SHOP_CONFIG_RSSARTRECOMMLISTS" }]</option>
                        <option value="oxrss_recommlistarts">[{ oxmultilang ident="SHOP_CONFIG_RSSRECOMMLISTARTS" }]</option>
                      [{/if}]
                    </select>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_RSSSELECTED" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blCalculateDelCostIfNotLoggedIn] value=false>
                    <input type=checkbox name=confbools[blCalculateDelCostIfNotLoggedIn] value=true  [{if ($confbools.blCalculateDelCostIfNotLoggedIn)}]checked[{/if}] [{ $readonly}]>
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
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CSVSEPARATOR" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sDecimalSeparator] value="[{$confstrs.sDecimalSeparator}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_CSVDECIMALSEPARATOR" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iExportNrofLines] value="[{$confstrs.iExportNrofLines}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_EXPORTNUMBEROFLINES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iExportTickerRefresh] value="[{$confstrs.iExportTickerRefresh}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_EXPORTTICKERREFRESH" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iImportNrofLines] value="[{$confstrs.iImportNrofLines}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_IMPORTNUMBEROFLINES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iImportTickerRefresh] value="[{$confstrs.iImportTickerRefresh}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_IMPORTTICKERREFRESH" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iCntofMails] value="[{$confstrs.iCntofMails}]">
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_CONFIG_NUMBEROFEMAILSPERTICK" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confaarrs[aLexwareVAT] [{ $readonly}]>[{$confaarrs.aLexwareVAT}]</textarea>
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
