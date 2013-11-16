[{capture append="oxidBlock_content"}]
    [{assign var="template_title" value="PAGE_INFO_NEWS_LATESTNEWSBY"|oxmultilangassign}]
    <div>
    <h1 class="pageHead">[{ oxmultilang ident="PAGE_INFO_NEWS_LATESTNEWSBY" }] [{ $oxcmp_shop->oxshops__oxname->value }]</h1>
        [{foreach from=$oView->getNews() item=oNews}]
            <div>
                <h3>
                    <span>[{ $oNews->oxnews__oxdate->value|date_format:"%d.%m.%Y" }] - </span> [{ $oNews->oxnews__oxshortdesc->value}]
                </h3>
                [{oxeval var=$oNews->oxnews__oxlongdesc force=1}]
            </div>
        [{/foreach}]
    </div>
    [{ insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]
