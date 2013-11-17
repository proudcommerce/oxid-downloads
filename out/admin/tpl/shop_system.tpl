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
    <input type="hidden" name="cl" value="shop_system">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{ $shop->id }]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="shop_system">
<input type="hidden" name="fnc" value="save">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxshops__oxid]" value="[{ $oxid }]">


    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_ORDER" }]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowFinalStep] value=false>
                    <input type=checkbox name=confbools[blShowFinalStep] value=true  [{if ($confbools.blShowFinalStep)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SHOWFINALSTEP" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input [{ $readonly }] type=hidden name=confbools[blOtherCountryOrder] value=false>
                    <input type=checkbox name=confbools[blOtherCountryOrder] value=true  [{if ($confbools.blOtherCountryOrder)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_OTHERCOUNTRYORDER" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
            <input [{ $readonly }] type=hidden name=confbools[blDisableNavBars] value=false>
            <input type=checkbox name=confbools[blDisableNavBars] value=true  [{if ($confbools.blDisableNavBars)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_DISABLENAVBARS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blStoreIPs] value=false>
                    <input type=checkbox name=confbools[blStoreIPs] value=true  [{if ($confbools.blStoreIPs)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_STOREIPS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input [{ $readonly }] type=hidden name=confbools[blOrderDisWithoutReg] value=false>
                    <input type=checkbox name=confbools[blOrderDisWithoutReg] value=true  [{if ($confbools.blOrderDisWithoutReg)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_ORDERDISNOREG" }]
                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_VARIANTS" }]</b></a>
            <dl>
                <dt>
                    <input type=hidden name=confbools[blVariantsSelection] value=false>
                    <input type=checkbox name=confbools[blVariantsSelection] value=true  [{if ($confbools.blVariantsSelection)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_VARIANTSSELECTION" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blVariantParentBuyable] value=false>
                    <input type=checkbox name=confbools[blVariantParentBuyable] value=true  [{if ($confbools.blVariantParentBuyable)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                  [{ oxmultilang ident="SHOP_SYSTEM_VARIANTPARENTBUYABLE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blVariantInheritAmountPrice] value=false>
                    <input type=checkbox class="confinput" name=confbools[blVariantInheritAmountPrice] value=true  [{if ($confbools.blVariantInheritAmountPrice)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_VARIANTINHERITAMOUNTPRICE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blShowVariantReviews] value=false>
                    <input type=checkbox class="confinput" name=confbools[blShowVariantReviews] value=true  [{if ($confbools.blShowVariantReviews)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SHOWVARIANTREVIEWS" }]
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
                    <input type=text  class="txt" name=confstrs[sDefaultImageQuality] value="[{$confstrs.sDefaultImageQuality}]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_DEFAULTIMAGEQUALITY" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input [{ $readonly }] type=hidden name=confbools[blInlineImgEmail] value=false>
                    <input type=checkbox name=confbools[blInlineImgEmail] value=true  [{if ($confbools.blInlineImgEmail)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_INLINEIMGEMAIL" }]
                </dd>
                <div class="spacer"></div>
            </dl>
         </div>
    </div>

    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b>[{ oxmultilang ident="SHOP_OPTIONS_GROUP_MODULES" }]</b></a>
            <dl>
                <dt>
                    <textarea wrap="off" class="txtfield" style="width: 430px;" name=confaarrs[aModules] [{ $readonly }]>[{$confaarrs.aModules}]</textarea>
                </dt>
                <dd>
                  [{ oxmultilang ident="SHOP_SYSTEM_MODULES" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sUtilModule] value="[{$confstrs.sUtilModule}]" [{ $readonly }]>
                </dt>
                <dd>
                  [{ oxmultilang ident="SHOP_SYSTEM_UTILMODULE" }]
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
                    <textarea class="txtfield" name=confaarrs[aInterfaceProfiles] [{ $readonly }]>[{$confaarrs.aInterfaceProfiles}]</textarea>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_INTERFACEPROFILES" }]
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
                    <input type=text  class="txt" name=confstrs[iServerTimeShift] value="[{$confstrs.iServerTimeShift}]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_ISERVERTIMESHIFT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select class="select" name=confstrs[iNewBasketItemMessage] [{ $readonly }]>
                        <option value="0"  [{if ($confstrs.iNewBasketItemMessage==0)}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SHOWNEWBASKETITEMMESSAGE_NONE" }]</option>
                        <option value="1"  [{if ($confstrs.iNewBasketItemMessage==1)}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SHOWNEWBASKETITEMMESSAGE_MESSAGE" }]</option>
                        <option value="2"  [{if ($confstrs.iNewBasketItemMessage==2)}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SHOWNEWBASKETITEMMESSAGE_POPUP" }]</option>
                        <option value="3"  [{if ($confstrs.iNewBasketItemMessage==3)}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SHOWNEWBASKETITEMMESSAGE_TOBASKET" }]</option>
                    </select>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SHOWNEWBASKETITEMMESSAGE" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aDeniedDynGroups] [{ $readonly }]>[{$confarrs.aDeniedDynGroups}]</textarea>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_DENIEDDYNGROUPS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iAttributesPercent] value="[{$confstrs.iAttributesPercent}]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_ATTRIBUTESPERCENT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=hidden name=confbools[blGBModerate] value=false>
                    <input type=checkbox name=confbools[blGBModerate] value=true  [{if ($confbools.blGBModerate)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_GBMODERATE" }]
                </dd>
                <div class="spacer"></div>
            </dl>


            <dl>
                <dt>
                    <textarea class="txtfield" name=confarrs[aLogSkipTags] [{ $readonly }]>[{$confarrs.aLogSkipTags}]</textarea>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_LOGSKIPTAGS" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input [{ $readonly }] type=hidden name=confbools[blLogging] value=false>
                    <input type=checkbox name=confbools[blLogging] value=true  [{if ($confbools.blLogging)}]checked[{/if}] [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_BLLOGGING" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            [{if !$isdemoshop}]
            <dl>
                <dt>
                    <select name=confstrs[iSmartyPhpHandling] [{ $readonly }]>
                        <option value="[{$smarty.const.SMARTY_PHP_PASSTHRU}]"  [{if $confstrs.iSmartyPhpHandling==$smarty.const.SMARTY_PHP_PASSTHRU}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SMARTYPHPHANDLING_REMOVE" }]</option>
                        <option value="[{$smarty.const.SMARTY_PHP_QUOTE}]"  [{if $confstrs.iSmartyPhpHandling==$smarty.const.SMARTY_PHP_QUOTE}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SMARTYPHPHANDLING_PASSTHRU" }]</option>
                        <option value="[{$smarty.const.SMARTY_PHP_REMOVE}]"  [{if $confstrs.iSmartyPhpHandling==$smarty.const.SMARTY_PHP_REMOVE}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SMARTYPHPHANDLING_QUOTE" }]</option>
                        <option value="[{$smarty.const.SMARTY_PHP_ALLOW}]"  [{if $confstrs.iSmartyPhpHandling==$smarty.const.SMARTY_PHP_ALLOW}]selected[{/if}]>[{ oxmultilang ident="SHOP_SYSTEM_SMARTYPHPHANDLING_ALLOW" }]</option>
                    </select>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SMARTYPHPHANDLING" }]
                </dd>
                <div class="spacer"></div>
            </dl>
            [{/if}]

            <dl>
                <dt>
                    <select class="select" name=confstrs[sShopCountry] [{ $readonly }]>
                        [{ foreach from=$shop_countries item=sShopCountry key=sCountryCode}]
                        <option value="[{$sCountryCode}]"[{if $sCountryCode == $confstrs.sShopCountry}] selected[{/if}]>[{$sShopCountry}]</option>
                        [{/foreach}]
                    </select>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SHOP_LOCATION" }]
                </dd>
                <div class="spacer"></div>
            </dl>


            <!--
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iSessionTimeout] value="[{$confstrs.iSessionTimeout}]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SESSIONTIMEOUT" }]
                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iSessionTimeoutAdmin] value="[{$confstrs.iSessionTimeoutAdmin }]" [{ $readonly }]>
                </dt>
                <dd>
                    [{ oxmultilang ident="SHOP_SYSTEM_SESSIONTIMEOUTADMIN" }]
                </dd>
                <div class="spacer"></div>
            </dl>
            -->

         </div>
    </div>

    <br>
    <input type="submit" class="confinput" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"" [{ $readonly }]>



</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
