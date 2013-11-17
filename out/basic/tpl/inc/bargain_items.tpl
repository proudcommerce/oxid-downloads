  [{foreach from=$oView->getBargainArticleList() item=_product name=bargainList}]
  <div class="listitem bargain">
      [{ assign var="sBargainArtTitle" value="`$_product->oxarticles__oxtitle->value` `$_product->oxarticles__oxvarselect->value`" }]
      <a id="test_picBargain_[{$smarty.foreach.bargainList.iteration}]" href="[{$_product->getLink()}]" class="picture">
          <img src="[{$_product->getIconUrl()}]" alt="[{ $sBargainArtTitle|strip_tags }]">
      </a>
      <b><a id="test_titleBargain_[{$smarty.foreach.bargainList.iteration}]" href="[{$_product->getLink()}]">[{ $sBargainArtTitle|strip_tags }]</a></b>
      [{ if $_product->isBuyable() }]
          <br>
          <a id="test_orderBargain_[{$smarty.foreach.bargainList.iteration}]" href="[{$_product->getToBasketLink()}]&amp;am=1" class="link" onclick="oxid.popup.load();" rel="nofollow">
              [{ oxmultilang ident="INC_RIGHTLIST_ORDERNOW" }]
          </a>
      [{/if}]
   </div>
  [{/foreach}]
