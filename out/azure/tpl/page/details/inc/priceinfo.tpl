[{if $oDetailsProduct->loadAmountPriceInfo()}]
[{assign var="currency" value=$oView->getActCurrency()}]
  <ul class="pricePopup Links corners shadow" id="priceinfo">
    <li><span><h4>[{oxmultilang ident="DETAILS_MOREYOUBUYMOREYOUSAVE"}]</h4></span></li>
    <li><label>[{oxmultilang ident="DETAILS_FROM"}]</label><span>[{oxmultilang ident="DETAILS_PCS"}]</span></li>
    [{foreach from=$oDetailsProduct->loadAmountPriceInfo() item=priceItem name=amountPrice}]
        <li><label>[{$priceItem->oxprice2article__oxamount->value}]</label>
        <span>
        [{if $priceItem->oxprice2article__oxaddperc->value}]
          [{$priceItem->oxprice2article__oxaddperc->value}] [{oxmultilang ident="DETAILS_DISCOUNT"}]
        [{else}]
          [{$priceItem->fbrutprice}] [{$currency->sign}]
        [{/if}]
        </span></li>
    [{/foreach}]
  </ul>
[{/if}]
<!--
  <div class="selectorbox ox-selectorbox corners ox-expand-toright" id="priceinfo">
    <div class="selectorbox-nose"><div></div></div>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <th colspan="2">
          <a class="selector-button corners fx-gradient" href="#cmplinks"><span class="hover"></span></a>
          [{oxmultilang ident="DETAILS_MOREYOUBUYMOREYOUSAVE"}]
        </th>
      </tr>
      <tr class="description">
        <td>[{oxmultilang ident="DETAILS_FROM"}]</td>
        <td class="price">[{oxmultilang ident="DETAILS_PCS"}]</td>
      </tr>
      [{foreach from=$oDetailsProduct->loadAmountPriceInfo() item=priceItem name=amountPrice}]
      <tr [{if $smarty.foreach.amountPrice.last}]class="last"[{/if}]>
        <td class="amount">[{$priceItem->oxprice2article__oxamount->value}]</td>
        <td class="price">
        [{if $priceItem->oxprice2article__oxaddperc->value}]
          [{$priceItem->oxprice2article__oxaddperc->value}] [{oxmultilang ident="DETAILS_DISCOUNT"}]
        [{else}]
          [{$priceItem->fbrutprice}] [{$currency->sign}]
        [{/if}]
        </td>
      </tr>
      [{/foreach}]
    </table>
  </div>
-->