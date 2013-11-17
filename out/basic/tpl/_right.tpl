<div class="forms">

    [{if $oView->showRightBasket()}]
        [{oxid_include_dynamic file="dyn/mini_basket.tpl" type="basket" extended=true testid="RightBasket"}]
    [{/if}]

    <strong class="h2"><a id="test_RightSideAccountHeader" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account" }]">[{ oxmultilang ident="INC_RIGHTITEM_MYACCOUNT" }]</a></strong>
    <div class="box">
        [{oxid_include_dynamic file="dyn/cmp_login_right.tpl" type="login" pgnr=$pageNavigation->actPage tpl=$tpl additional_form_parameters="`$AdditionalFormParameters`"|cat:$oViewConf->getNavFormParams() }]
        [{oxid_include_dynamic file="dyn/cmp_login_links.tpl" type="login_links"}]
    </div>

    [{if !$oxcmp_user->oxuser__oxpassword->value && $oViewConf->getShowOpenIdLogin() }]
        <strong class="h2"><a id="test_RightSideOpenIdHeader" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account"}]">[{ oxmultilang ident="INC_RIGHTITEM_OPENID" }]</a></strong>
        <div class="box">
            [{oxid_include_dynamic file="dyn/cmp_openidlogin_right.tpl" type="login" pgnr=$pageNavigation->actPage tpl=$tpl additional_form_parameters="`$AdditionalFormParameters`"|cat:$oViewConf->getNavFormParams() }]
        </div>
    [{/if}]

    [{if $oView->showNewsletter()}]
        <strong class="h2"><a id="test_RightSideNewsLetterHeader" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=newsletter" }]">[{ oxmultilang ident="INC_RIGHTITEM_NEWSLETTER" }]</a></strong>
        <div class="box">[{include file="inc/cmp_newsletter.tpl" }]</div>
    [{/if}]

</div>

[{ if $oView->getTop5ArticleList() }]
    <strong class="h2" id="test_RightSideTop5Header">
        [{ oxmultilang ident="INC_RIGHTITEM_TOPOFTHESHOP" }]
        [{if $rsslinks.topArticles}]
            <a class="rss" id="rss.topArticles" href="[{$rsslinks.topArticles.link}]" title="[{$rsslinks.topArticles.title}]"></a>
            [{oxscript add="oxid.blank('rss.topArticles');"}]
        [{/if}]
    </strong>
    <div class="box">
        <div>[{include file="inc/top_items.tpl" }]</div>
    </div>
[{ /if }]

[{if count($oView->getBargainArticleList()) > 0 }]
    <strong class="h2" id="test_RightSideBarGainHeader">
        [{ oxmultilang ident="INC_RIGHTITEM_BARGAIN" }]
        [{if $rsslinks.bargainArticles}]
            <a class="rss" id="rss.bargainArticles" href="[{$rsslinks.bargainArticles.link}]" title="[{$rsslinks.bargainArticles.title}]"></a>
            [{oxscript add="oxid.blank('rss.bargainArticles');"}]
        [{/if}]
    </strong>
    <div class="box">
        <div>[{include file="inc/bargain_items.tpl"}]</div>
    </div>
[{ /if }]

[{if $oViewConf->getShowListmania()}]
    [{ if $oView->getSimilarRecommLists() }]
        <strong class="h2" id="test_RightSideRecommlistHeader">
            [{ oxmultilang ident="INC_RIGHTITEM_RECOMMLIST" }]
            [{if $rsslinks.recommlists}]
                <a class="rss" id="rss.recommlists" href="[{$rsslinks.recommlists.link}]" title="[{$rsslinks.recommlists.title}]"></a>
                [{oxscript add="oxid.blank('rss.recommlists');"}]
            [{/if}]
        </strong>
        <div class="box">
            <div>[{include file="inc/right_recommlist.tpl" list=$oView->getSimilarRecommLists()}]</div>
            <br>
            <span class="def_color_1">[{ oxmultilang ident="INC_RIGHTITEM_SEARCHFORLISTS" }]</span>
            <form name="basket" action="[{ $oViewConf->getSelfActionLink() }]" method="post" class="recommlistsearch">
              <div>
                  [{ $oViewConf->getHiddenSid() }]
                  <input type="hidden" name="cl" value="recommlist">
                  <input type="text" name="searchrecomm" id="searchrecomm" value="[{$oView->getRecommSearch()}]" class="search_input">
                  <span class="btn"><input id="test_searchRecommlist" type="submit" value="GO!" class="btn"></span>
              </div>
            </form>
        </div>
    [{ /if }]

    [{ if !$oView->getSimilarRecommLists() && $oView->getRecommSearch() }]
        <strong class="h2" id="test_RightSideRecommlistHeader">[{ oxmultilang ident="INC_RIGHTITEM_RECOMMLIST" }]</strong>
        <div class="box">
            <span class="def_color_1">[{ oxmultilang ident="INC_RIGHTITEM_SEARCHFORLISTS" }]</span>
            <form name="basket" action="[{ $oViewConf->getSelfActionLink() }]" method="post" class="recommlistsearch">
              <div>
                  [{ $oViewConf->getHiddenSid() }]
                  <input type="hidden" name="cl" value="recommlist">
                  <input type="text" name="searchrecomm" value="[{$oView->getRecommSearch()}]" class="search_input">
                  <span class="btn"><input id="test_searchRecommlist" type="submit" value="GO!" class="btn"></span>
              </div>
            </form>
        </div>
    [{ /if }]
[{/if}]

[{ if $oView->getAccessoires() }]
    <strong class="h2" id="test_RightSideAccessoiresHeader">[{ oxmultilang ident="INC_RIGHTITEM_ACCESSORIES" }]</strong>
    <div class="box">
        <div>[{include file="inc/rightlist.tpl" list=$oView->getAccessoires() altproduct=$product test_Type=accessoire}]</div>
    </div>
[{ /if }]


[{ if $oView->getSimilarProducts() }]
    <strong class="h2" id="test_RightSideSimilListHeader">[{ oxmultilang ident="INC_RIGHTITEM_SIMILARPRODUCTS" }]</strong>
    <div class="box">
        <div>[{include file="inc/rightlist.tpl" list=$oView->getSimilarProducts() altproduct=$product test_Type=similarlist}]</div>
    </div>
[{ /if }]

[{ if $oView->getCrossSelling()}]
    <strong class="h2" id="test_RightSideCrossListHeader">[{ oxmultilang ident="INC_RIGHTITEM_HAVEPOUSEEN" }]</strong>
    <div class="box">
        <div>[{include file="inc/rightlist.tpl" list=$oView->getCrossSelling() altproduct=$product test_Type=cross}]</div>
    </div>
[{ /if }]

[{ if $oView->getAlsoBoughtTheseProducts() }]
    <strong class="h2" id="test_RightSideCustWhoHeader">[{ oxmultilang ident="INC_RIGHTITEM_CUSTOMERWHO" }]</strong>
    <div class="box">
        <div>[{include file="inc/rightlist.tpl" list=$oView->getAlsoBoughtTheseProducts() altproduct=$product test_Type=customerwho}]</div>
    </div>
[{ /if }]
