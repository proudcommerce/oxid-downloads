[{capture append="oxidBlock_content"}]
    <h1 class="pageHead">[{ oxmultilang ident="PAGE_INFO_LINKS_LINKS" }]</h1>
    [{foreach from=$oView->getLinksList() item=link name=linksList}]
        <h3>
            <span class="date">[{ $link->oxlinks__oxinsert->value|date_format:"%d.%m.%Y" }] - </span> <a href="[{ $link->oxlinks__oxurl->value }]" >[{ $link->oxlinks__oxurl->value }]</a>
        </h3>
    [{/foreach}]
[{/capture}]

[{include file="layout/page.tpl" sidebar="Right"}]