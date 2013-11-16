<form class="search" action="[{ $oViewConf->getSelfActionLink() }]" method="get" name="search">
    <div class="searchBox">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="cl" value="search">
        [{block name="header_search_field"}]
        <input class="textbox innerLabel" type="text" id="searchParam" name="searchparam" title="[{ oxmultilang ident="SEARCH_TITLE" }]" value="[{if $oView->getSearchParamForHtml()}][{$oView->getSearchParamForHtml()}][{else}][{ oxmultilang ident="SEARCH_TITLE" }][{/if}]">
        [{/block}]
        <input class="searchSubmit" type="submit" value="">
    </div>
</form>