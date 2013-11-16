[{assign var="template_title" value="NEWS_TITLE"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location=$template_title}]

<strong class="boxhead">[{ oxmultilang ident="NEWS_LATESTNEWSBY" }] [{ $oxcmp_shop->oxshops__oxname->value }]</strong>
<div class="box info">
    <dl class="news">
    [{foreach from=$oView->getNews() item=oNews}]
        <dt>
            <a name="[{ $oNews->oxnews__oxid->value}]">[{ $oNews->oxnews__oxdate->value|date_format:"%d.%m.%Y" }] - [{ $oNews->oxnews__oxshortdesc->value}]</a>
        </dt>
        <dd>
            [{oxeval var=$oNews->oxnews__oxlongdesc force=1}]
        </dd>
    [{/foreach}]
    </dl>
</div>

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
