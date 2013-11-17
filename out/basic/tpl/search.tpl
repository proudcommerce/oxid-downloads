[{assign var="search_title" value="SEARCH_TITLE"|oxmultilangassign}]
[{assign var="template_title" value="$search_title - $searchparamforhtml"}]

[{include file="_header.tpl" title=$template_title location="SEARCH_LOCATION"|oxmultilangassign }]
[{assign var="pageNavigation" value=$oView->getPageNavigation() }]

  <!-- page locator -->
  [{if $pageNavigation->iArtCnt }]
    [{include file="inc/list_locator.tpl" PageLoc="Top"}]
  [{else}]
    <div class="msg">[{ oxmultilang ident="SEARCH_NOITEMSFOUND" }]</div>
  [{/if}]

  [{if $oView->getArticleList() }]
    [{assign var="search_head" value="SEARCH_HITSFOR"|oxmultilangassign}]
    [{assign var="search_head" value=$pageNavigation->iArtCnt|cat:" "|cat:$search_head|cat:" &quot;"|cat:$oView->getSearchParamForHtml()|cat:"&quot;"}]

    [{if $rsslinks.searchArticles}]
        [{assign var="search_head" value="`$search_head` <a class=\"rss\" id=\"rss.searchArticles\" href=\"`$rsslinks.searchArticles.link`\" title=\"`$rsslinks.searchArticles.title`\"></a>"}]
        [{oxscript add="oxid.blank('rss.searchArticles');"}]
    [{/if}]

    [{foreach from=$oView->getArticleList() name=search item=product}]

      [{if $smarty.foreach.search.first && !$smarty.foreach.search.last}]
        [{assign var="search_class" value="firstinlist"}]
      [{elseif $smarty.foreach.search.last}]
        [{assign var="search_class" value="lastinlist"}]
      [{else}]
        [{assign var="search_class" value="inlist"}]
      [{/if}]

      [{include file="inc/product.tpl" product=$product size="thin" head=$search_head class=$search_class testid="Search_"|cat:$product->oxarticles__oxid->value test_Cntr=$smarty.foreach.search.iteration}]

      [{assign var="search_head" value=""}]
      [{if !$smarty.foreach.search.last }]
        <div class="separator"></div>
      [{/if}]

    [{/foreach}]
  [{/if}]


  <!-- page locator -->
  [{if $pageNavigation->iArtCnt }]
    [{include file="inc/list_locator.tpl" PageLoc="Bottom"}]
  [{/if}]

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
