[{assign var="template_title" value="RECOMMLIST_TITLE"|oxmultilangassign}]
[{assign var="template_title" value=$template_title|cat:" - "|cat:$oView->getSearchForHtml()}]
[{include file="_header.tpl" title=$template_title location=$oView->getTemplateLocation()}]
[{assign var="pageNavigation" value=$oView->getPageNavigation()}]

[{if $oView->getActiveRecommList() }]
    [{assign var="_actvrecommlist" value=$oView->getActiveRecommList() }]

    <strong id="test_recommlistHeaderAuthor" class="head">
        [{$_actvrecommlist->oxrecommlists__oxtitle->value}] <span class="recomm_author">([{ oxmultilang ident="RECOMMLIST_LISTBY" }] [{ $_actvrecommlist->oxrecommlists__oxauthor->value }])</span>
        [{if $rsslinks.recommlistarts}]
            <a class="rss" id="rss.recommlistarts" href="[{$rsslinks.recommlistarts.link}]" title="[{$rsslinks.recommlistarts.title}]"></a>
            [{oxscript add="oxid.blank('rss.recommlistarts');"}]
        [{/if}]
    </strong>
    <div class="box info">
      <div class="right">
          [{ if !$oxcmp_user}]
            [{assign var="star_title" value="RECOMMLIST_LOGGIN"|oxmultilangassign }]
          [{ elseif !$oView->canRate() }]
            [{assign var="star_title" value="RECOMMLIST_ALREADYRATED"|oxmultilangassign }]
          [{ else }]
            [{assign var="star_title" value="RECOMMLIST_RATETHISLIST"|oxmultilangassign }]
          [{/if}]
          [{math equation="x*y" x=20 y=$oView->getRatingValue() assign="currentRate" }]
          <ul id="star_rate_top" class="rating">
            <li class="current_rate" style="width: [{$currentRate}]%;"><a title="[{$star_title}]"><b>1</b></a></li>
            <li class="one"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $_actvrecommlist->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $rate }]href="#review" onclick="showReview(1);"[{/if}] title="[{$star_title}]"><b>1</b></a></li>
            <li class="two"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $_actvrecommlist->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $rate }]href="#review" onclick="showReview(2);"[{/if}] title="[{$star_title}]"><b>2</b></a></li>
            <li class="three"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $_actvrecommlist->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $rate }]href="#review" onclick="showReview(3);"[{/if}] title="[{$star_title}]"><b>3</b></a></li>
            <li class="four"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $_actvrecommlist->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $rate }]href="#review" onclick="showReview(4);"[{/if}] title="[{$star_title}]"><b>4</b></a></li>
            <li class="five"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $_actvrecommlist->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $rate }]href="#review" onclick="showReview(5);"[{/if}] title="[{$star_title}]"><b>5</b></a></li>
          </ul>
          [{if $oView->getRatingCount()}]
            <a id="star_rating_text" rel="nofollow" href="#review" onclick="showReview();" class="fs10 link2">[{$oView->getRatingCount()}] [{if $oView->getRatingCount() == 1}][{ oxmultilang ident="RECOMMLIST_RATINGREZULT" }][{else}][{ oxmultilang ident="RECOMMLIST_RATINGREZULTS" }] [{/if}]</a>
          [{else}]
            <a id="star_rating_text" rel="nofollow" href="#review" onclick="showReview();" class="fs10 link2">[{ oxmultilang ident="RECOMMLIST_NORATINGS" }]</a>
          [{/if}]
      </div>

      <div id="test_recommlistDesc" class="recomlistdesc">
        [{ $_actvrecommlist->oxrecommlists__oxdesc->value }]
      </div>

      <div class="clear_both"></div>

    </div>
    [{if $pageNavigation->iArtCnt }]
      [{include file="inc/list_locator.tpl" PageLoc="Bottom" where="Bottom"}]
    [{/if}]
      [{if $oView->getActiveRecommItems() }]
        [{include file="inc/recommlist.tpl" recommid=$_actvrecommlist->getId()}]
      [{/if}]
    [{if $pageNavigation->iArtCnt }]
      [{include file="inc/list_locator.tpl" PageLoc="Bottom" where="Bottom"}]
    [{/if}]


    <strong class="boxhead" id="test_reviewHeader">[{ oxmultilang ident="RECOMMLIST_LISTREVIEW" }]</strong>
    <div id="review" class="box info">
      [{ if $oxcmp_user }]
        <form action="[{ $oViewConf->getSelfActionLink() }]" method="post" id="rating">
            <div id="write_review">
                [{ if $oView->canRate() }]
                <input type="hidden" name="recommlistrating" value="0">
                <ul id="star_rate" class="rating">
                    <li id="current_rate" class="current_rate" style="width: 0px;"><a title="[{$star_title}]"><b>1</b></a></li>
                    [{ assign var="__params" value="anid=`$product->oxarticles__oxnid->value`&amp;"|cat:$oViewConf->getNavUrlParams() }]
                    <li class="one"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(1);return false;" title="1 [{ oxmultilang ident="RECOMMLIST_STAR" }]"><b>1</b></a></li>
                    <li class="two"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(2);return false;" title="2 [{ oxmultilang ident="RECOMMLIST_STARS" }]"><b>2</b></a></li>
                    <li class="three"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(3);return false;" title="3 [{ oxmultilang ident="RECOMMLIST_STARS" }]"><b>3</b></a></li>
                    <li class="four"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(4);return false;" title="4 [{ oxmultilang ident="RECOMMLIST_STARS" }]"><b>4</b></a></li>
                    <li class="five"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=__params }]" onclick="showReview(5);return false;" title="5 [{ oxmultilang ident="RECOMMLIST_STARS" }]"><b>5</b></a></li>
                </ul>
                [{/if}]
                [{ $oViewConf->getHiddenSid() }]
                [{ $oViewConf->getNavFormParams() }]
                <input type="hidden" name="fnc" value="savereview">
                <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
                <input type="hidden" name="recommid" value="[{$_actvrecommlist->oxrecommlists__oxid->value}]">
                <input type="hidden" name="reviewuserid" value="[{$oView->getReviewUserId()}]">
                <textarea cols="102" rows="15" name="rvw_txt" class="fullsize"></textarea><br>
                <span class="btn"><input id="test_reviewSave" type="submit" value="[{ oxmultilang ident="RECOMMLIST_SAVEREVIEW" }]" class="btn"></span>
            </div>
        </form>
        <a id="write_new_review" rel="nofollow" class="fs10" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params="recommid=`$_actvrecommlist->oxrecommlists__oxid->value`&amp;"|cat:$oViewConf->getNavUrlParams() }]" onclick="showReview();return false;"><b>[{ oxmultilang ident="RECOMMLIST_WRITEREVIEW" }]</b></a>
      [{else}]
        <a id="test_Reviews_login" rel="nofollow" href="[{ $_actvrecommlist->getLink()|oxaddparams:"fnc=showLogin"}]" class="fs10"><b>[{ oxmultilang ident="RECOMMLIST_LOGGINTOWRITEREVIEW" }]</b></a>
      [{/if}]

      [{if $oView->getReviews() }]
       [{foreach from=$oView->getReviews() item=review name=ReviewsCounter}]
        <dl class="review">
            <dt>
                <span id="test_ReviewName_[{$smarty.foreach.ReviewsCounter.iteration}]" class="left"><b>[{ $review->oxuser__oxfname->value }]</b> [{ oxmultilang ident="RECOMMLIST_WRITES" }]</span>
                <span id="test_ReviewTime_[{$smarty.foreach.ReviewsCounter.iteration}]" class="right param"><b>[{ oxmultilang ident="RECOMMLIST_TIME" }]</b>&nbsp;[{ $review->oxreviews__oxcreate->value|date_format:"%H:%M" }]</span>
                <span id="test_ReviewDate_[{$smarty.foreach.ReviewsCounter.iteration}]" class="right param"><b>[{ oxmultilang ident="RECOMMLIST_DATE" }]</b>&nbsp;[{ $review->oxreviews__oxcreate->value|date_format:"%d.%m.%Y" }]</span>
                <span id="test_ReviewRating_[{$smarty.foreach.ReviewsCounter.iteration}]" class="right param">[{if $review->oxreviews__oxrating->value }]<b>[{ oxmultilang ident="RECOMMLIST_RATING" }]</b>&nbsp;[{ $review->oxreviews__oxrating->value }][{/if}]</span>
            </dt>
            <dd id="test_ReviewText_[{$smarty.foreach.ReviewsCounter.iteration}]">
                [{ $review->oxreviews__oxtext->value }]
            </dd>
        </dl>

       [{/foreach}]
      [{else}]
        <div class="dot_sep mid"></div>
        [{ oxmultilang ident="RECOMMLIST_REVIEWNOTAVAILABLE" }]
      [{/if}]
    </div>

[{else}]
      [{assign var="hitsfor" value="RECOMMLIST_HITSFOR"|oxmultilangassign }]
      [{assign var="title" value="`$pageNavigation->iArtCnt` `$hitsfor` &quot;"|cat:$oView->getRecommSearch()|cat:"&quot;" }]
      [{include file="inc/recomm_lists.tpl" template_title=$title}]
[{/if}]

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
