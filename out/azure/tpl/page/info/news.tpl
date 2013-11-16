[{capture append="oxidBlock_content"}]
    <div class="newsView">
    <h1 class="pageHead">[{ oxmultilang ident="PAGE_INFO_NEWS_LATESTNEWSBY" }] [{ $oxcmp_shop->oxshops__oxname->value }]</h1>
        [{foreach from=$oView->getNews() item=oNews}]
            <div class="newsItem">
                <a name="[{ $oNews->oxnews__oxid->value}]"></a>
                <h3>
                    <span class="date">[{ $oNews->oxnews__oxdate->value|date_format:"%d.%m.%Y" }] - </span> [{ $oNews->oxnews__oxshortdesc->value}]
                </h3>
                [{oxeval var=$oNews->oxnews__oxlongdesc force=1}]
            </div>
        [{/foreach}]
    </div>
[{/capture}]

[{include file="layout/page.tpl" sidebar="Right"}]
