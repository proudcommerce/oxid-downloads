[{oxhasrights ident="SHOWLONGDESCRIPTION"}]
[{assign var="oLongdesc" value=$oDetailsProduct->getArticleLongDesc()}]
[{if $oLongdesc->value}]
    [{capture append="tabs"}]<a href="#description">[{oxmultilang ident="PAGE_DETAILS_TABS_DESCRIPTION"}]</a>[{/capture}]
    [{capture append="tabsContent"}]
    <div id="description" class="tabcontent">
        [{oxeval var=$oLongdesc}]
        [{if $oDetailsProduct->oxarticles__oxexturl->value}]
            <a id="productExturl" class="external" href="[{$oDetailsProduct->oxarticles__oxexturl->value}]">[{$oDetailsProduct->oxarticles__oxurldesc->value}]</a>
        [{/if}]
    </div>
    [{/capture}]
[{/if}]
[{/oxhasrights}]

[{if $oView->getAttributes()}]
    [{capture append="tabs"}]<a href="#attributes">[{oxmultilang ident="DETAILS_SPECIFICATION"}]</a>[{/capture}]
    [{capture append="tabsContent"}]<div id="attributes" class="tabcontent">[{include file="page/details/inc/attributes.tpl"}]</div>[{/capture}]
[{/if}]

[{if $oView->isPriceAlarm() && !$oDetailsProduct->isParentNotBuyable()}]
    [{capture append="tabs"}]<a href="#pricealarm">[{oxmultilang ident="DETAILS_PRICEALARM"}]</a>[{/capture}]
    [{capture append="tabsContent"}]<div id="pricealarm" class="tabcontent">[{include file="form/pricealarm.tpl"}]</div>[{/capture}]
[{/if}]

[{if $oView->getTagCloudManager() || ( ( $oView->getTagCloudManager() || $oxcmp_user) && $oDetailsProduct )}]
    [{capture append="tabs"}]<a href="#tags">[{oxmultilang ident="PAGE_DETAILS_TABS_TAGS"}]</a>[{/capture}]
    [{capture append="tabsContent"}]<div id="tags" class="tabcontent">[{oxid_include_dynamic file="page/details/inc/tags.tpl"}]</div>[{/capture}]
[{/if}]

[{if $oView->getMediaFiles() || $oDetailsProduct->oxarticles__oxfile->value}]
    [{capture append="tabs"}]<a href="#media">[{oxmultilang ident="PAGE_DETAILS_TABS_MEDIA"}]</a>[{/capture}]
    [{capture append="tabsContent"}]<div id="media" class="tabcontent">[{include file="page/details/inc/media.tpl"}]</div>[{/capture}]
[{/if}]




[{if $oView->isActive('FbComments') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbComments">[{oxmultilang ident="FACEBOOK_COMMENTS"}]</a>[{/capture}]
    [{capture append="FBtabsContent"}]<div id="productFbComments" class="tabcontent">[{include file="widget/facebook/comments.tpl"}]</div>[{/capture}]
[{/if}]

[{if $oView->isActive('FbInvite') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbInvite">[{oxmultilang ident="FACEBOOK_INVITE"}]</a>[{/capture}]
    [{capture append="FBtabsContent"}]<div id="productFbInvite" class="tabcontent">[{include file="widget/facebook/invite.tpl"}]</div>[{/capture}]
[{/if}]

[{if $oView->isActive('FbLiveStream') && $oViewConf->getFbAppId()}]
    [{capture append="FBtabs"}]<a href="#productFbLiveStream">[{oxmultilang ident="FACEBOOK_CHAT"}]</a>[{/capture}]
    [{capture append="FBtabsContent"}]<div id="productFbLiveStream" class="tabcontent">[{include file="widget/facebook/livestream.tpl"}]</div>[{/capture}]
[{/if}]

[{if $tabs}]
<div class="tabbedWidgetBox clear" style="min-height:50px;">
    <ul id="itemTabs" class="tabs clear">
    [{foreach from=$tabs item="tab"}]
        <li>[{$tab}]</li>
    [{/foreach}]
    </ul>
    <div class="widgetBoxBottomRound">
    [{foreach from=$tabsContent item="tabContent"}]
        [{$tabContent}]
    [{/foreach}]
    </div>
</div>
[{/if}]

[{if $FBtabs}]
<div class="tabbedWidgetBox clear" style="min-height:50px;">
    <ul id="itemFbTabs" class="tabs clear">
    [{foreach from=$FBtabs item="FBtab"}]
        <li>[{$FBtab}]</li>
    [{/foreach}]
    </ul>
    <div class="widgetBoxBottomRound">
    [{foreach from=$FBtabsContent item="FBtabContent"}]
        [{$FBtabContent}]
    [{/foreach}]
    </div>
</div>
[{/if}]
