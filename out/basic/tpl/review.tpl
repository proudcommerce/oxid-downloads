[{assign var="product" value=$oView->getProduct()}]
[{assign var="template_title" value=$product->oxarticles__oxtitle->value|cat:" "|cat:$product->oxarticles__oxvarselect->value}]
[{include file="_header.tpl" title=$template_title location=$template_title}]

[{if !$oxcmp_user->oxuser__oxusername->value && !$oView->getProduct()}]
  [{include file="inc/cmp_login.tpl" }]
[{else}]
  <strong class="boxhead">[{$template_title}]</strong>
  <div class="box info">
    <table width="100%">
      <colgroup>
        <col width="20%">
        <col width="75%">
      </colgroup>
      <tr>
        <td>
          <a rel="nofollow" href="[{ $product->getLink()|oxaddparams:$oViewConf->getNavUrlParams() }]">
            <img src="[{$product->getThumbnailUrl()}]" alt="[{ $product->oxarticles__oxtitle->value|strip_tags }][{if $product->oxarticles__oxvarselect->value}] [{ $product->oxarticles__oxvarselect->value }][{/if}]">
          </a>
        </td>
        <td>
          <div>
            <a rel="nofollow" href="[{ $product->getLink()|oxaddparams:$oViewConf->getNavUrlParams() }]"><b>[{ $product->oxarticles__oxtitle->value }][{if $product->oxarticles__oxvarselect->value}] [{ $product->oxarticles__oxvarselect->value }][{/if}]</b></a>
          </div>
          <div>[{ oxmultilang ident="REVIEW_ARTNUMBER" }] [{ $product->oxarticles__oxartnum->value }]</div>
          [{oxhasrights ident="SHOWSHORTDESCRIPTION"}]
          <div>[{ $product->oxarticles__oxshortdesc->value }]</div>
          [{/oxhasrights}]
        </td>
      </tr>
    </table>
  </div>

  [{if $oView->getReviewSendStatus()}]
    <strong class="boxhead">[{ oxmultilang ident="REVIEW_REVIEW" }]</strong>
    <div class="box info">
      [{ oxmultilang ident="REVIEW_THANKYOUFORREVIEW" }]
    </div>
  [{else}]
    <strong class="boxhead">[{ oxmultilang ident="REVIEW_YOURREVIEW" }]</strong>
    <div class="box info">
      <form action="[{ $oViewConf->getSelfActionLink() }]" method="post">
        <div>
            [{ if $oView->canRate() }]
              <table>
                <tr title="5 [{ oxmultilang ident="REVIEW_STARS" }]"><td><input type="radio" name="artrating" value="5" class="rating_review_input"></td><td class="rating_review_background fivestar">&nbsp;</td></tr>
                <tr title="4 [{ oxmultilang ident="REVIEW_STARS" }]"><td><input type="radio" name="artrating" value="4" class="rating_review_input"></td><td class="rating_review_background fourstar">&nbsp;</td></tr>
                <tr title="3 [{ oxmultilang ident="REVIEW_STARS" }]"><td><input type="radio" name="artrating" value="3" class="rating_review_input"></td><td class="rating_review_background threestar">&nbsp;</td></tr>
                <tr title="2 [{ oxmultilang ident="REVIEW_STARS" }]"><td><input type="radio" name="artrating" value="2" class="rating_review_input"></td><td class="rating_review_background twostar">&nbsp;</td></tr>
                <tr title="1 [{ oxmultilang ident="REVIEW_STAR" }]"><td><input type="radio" name="artrating" value="1" class="rating_review_input"></td><td class="rating_review_background onestar">&nbsp;</td></tr>
              </table>
            [{/if}]
            [{ $oViewConf->getHiddenSid() }]
            [{ $oViewConf->getNavFormParams() }]
            <input type="hidden" name="fnc" value="savereview">
            <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
            <input type="hidden" name="anid" value="[{ $product->oxarticles__oxid->value }]">
            <input type="hidden" name="reviewuserid" value="[{$oView->getReviewUserId()}]">
            <textarea cols="102" rows="15" name="rvw_txt" class="fullsize"></textarea><br>
            <span class="btn"><input type="submit" value="[{ oxmultilang ident="REVIEW_TOSAVEREVIEW" }]" class="btn"></span>
         </div>
      </form>
    </div>
  [{/if}]

  [{ if $oView->getReviews() }]
    <strong class="boxhead">[{ oxmultilang ident="REVIEW_PASTREVIEW" }]</strong>
    <div class="box info">
        [{foreach from=$oView->getReviews() item=review}]
        <dl class="review">
            <dt>
                <span class="left"><b>[{ $review->oxuser__oxfname->value }]</b> [{ oxmultilang ident="DETAILS_PERSPARAM_WRITES" }]</span>
                <span class="right param"><b>[{ oxmultilang ident="DETAILS_PERSPARAM_TIME" }]</b>&nbsp;[{ $review->oxreviews__oxcreate->value|date_format:"%H:%M" }]</span>
                <span class="right param"><b>[{ oxmultilang ident="DETAILS_PERSPARAM_DATE" }]</b>&nbsp;[{ $review->oxreviews__oxcreate->value|date_format:"%d.%m.%Y" }]</span>
                <span class="right param">[{if $review->oxreviews__oxrating->value }]<b>[{ oxmultilang ident="DETAILS_PERSPARAM_RATING" }]</b>&nbsp;[{ $review->oxreviews__oxrating->value }][{/if}]</span>
            </dt>
            <dd>
                [{ $review->oxreviews__oxtext->value }]
            </dd>
        </dl>
        [{/foreach}]
    </div>
  [{/if}]

[{/if}]

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
