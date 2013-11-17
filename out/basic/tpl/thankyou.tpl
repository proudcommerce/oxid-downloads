[{assign var="template_title" value="THANKYOU_TITLE"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location="THANKYOU_LOCATION"|oxmultilangassign}]

[{if $oView->showFinalStep()}]
  <!-- ordering steps -->
  [{include file="inc/steps_item.tpl" highlight=5}]
[{/if}]

[{assign var="order" value=$oView->getOrder()}]

  <strong class="boxhead thankyouboxhead">[{ oxmultilang ident="THANKYOU_TITLE" }]</strong>
  <div class="box info">
    <div>
        [{ oxmultilang ident="THANKYOU_THANKYOU1" }] [{ oxmultilang ident="THANKYOU_THANKYOU2" }] [{ $oxcmp_shop->oxshops__oxname->value }]. <br>
        [{ oxmultilang ident="THANKYOU_REGISTEREDYOUORDERNO1" }] [{ $order->oxorder__oxordernr->value }] [{ oxmultilang ident="THANKYOU_REGISTEREDYOUORDERNO2" }]<br>
        [{if !$oView->getMailError() }]
          [{ oxmultilang ident="THANKYOU_YOURECEIVEDORDERCONFIRM" }]<br>
        [{else}]<br>
          [{ oxmultilang ident="THANKYOU_CONFIRMATIONNOTSUCCEED" }]<br>
        [{/if}]
        <br>
        [{ oxmultilang ident="THANKYOU_WEWILLINFORMYOU" }]<br><br>
        <a id="test_BackToShop" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getHomeLink() }]" class="black_link"><b>[{ oxmultilang ident="THANKYOU_BACKTOSHOP" }] [{ $oxcmp_shop->oxshops__oxname->value }]</b>.</a><br>
    </div>
  </div>

  [{if $oxcmp_user->oxuser__oxpassword->value}]
  <strong class="boxhead">[{ oxmultilang ident="THANKYOU_PREVIOUSORDER" }]</strong>
  <div class="box info">
        <a id="test_OrderHistory" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_order" }]" class="black_link"><b>[{ oxmultilang ident="THANKYOU_ORDERHISTORY" }]</b></a>
  </div><br><br>
  [{/if}]

  [{if ( $oView->getTrustedShopId()) || $oxcmp_shop->oxshops__oxadbutlerid->value ||
       $oxcmp_shop->oxshops__oxaffilinetid->value || $oxcmp_shop->oxshops__oxsuperclicksid->value ||
       $oxcmp_shop->oxshops__oxaffiliweltid->value || $oxcmp_shop->oxshops__oxaffili24id->value }]

  <strong class="boxhead">[{ oxmultilang ident="THANKYOU_PARTNERFROM" }] [{ $oxcmp_shop->oxshops__oxname->value }]</strong>
  <div class="box info">
      <div>
        [{if $oView->getTrustedShopId()}]
          <br>
          <div>
            <div class="etrustlogocol">
            <a href="https://www.trustedshops.com/shop/certificate.php?shop_id=[{$oView->getTrustedShopId()}]" target="_blank">
                <img style="border:0px none;" src="[{$oViewConf->getImageUrl()}]/trustedshops_m.gif" title="[{ oxmultilang ident="INC_TRUSTEDSHOPS_ITEM_IMGTITLE" }]">
            </a>
            </div>
            <div class="etrustdescocol">
                <form id="formTShops" name="formTShops" method="post" action="https://www.trustedshops.com/shop/protection.php" target="_blank">
                  <div>
                      <input type="hidden" name="_charset_">
                      <input name="shop_id" type=hidden value="[{$oView->getTrustedShopId()}]">
                      <input name="title" type=hidden value="[{ $order->oxorder__oxbillsal->value }]">
                      <input name="email" type="hidden" value="[{ $oxcmp_user->oxuser__oxusername->value }]">
                      <input name="first_name" type=hidden value="[{ $order->oxorder__oxbillfname->value }]">
                      <input name="last_name" type=hidden value="[{ $order->oxorder__oxbilllname->value }]">
                      <input name="street" type=hidden value="[{ $order->oxorder__oxbillstreet->value }]">
                      <input name="zip" type=hidden value="[{ $order->oxorder__oxbillzip->value }]">
                      <input name="city" type=hidden value="[{ $order->oxorder__oxillcity->value }]">
                      <input name="country" type=hidden value="[{ $oView->getCountryISO3() }]">
                      <input name="phone" type=hidden value="[{ $order->oxorder__oxbillfon->value }]">
                      <input name="fax" type=hidden value="[{ $order->oxorder__oxbillfax->value }]">
                      <input name="delivery" type=hidden value="">
                      <input name="amount" type=hidden value="[{ $order->getTotalOrderSum() }]">
                      <input name="curr" type=hidden value="[{ $order->oxorder__oxcurrency->value }]">
                      <input name="payment" type=hidden value="">
                      <input name="KDNR" type="hidden" value="[{ $oxcmp_user->oxuser__oxcustnr->value }]">
                      <input name="ORDERNR" type="hidden" value="[{ $order->oxorder__oxordernr->value }]">
                      [{ oxmultilang ident="THANKYOU_TRUSTEDSHOPMESSAGE" }]<br><br>
                      <span class="btn"><input type="submit" id="btnProtect" name="btnProtect" class="btn" value="[{ oxmultilang ident="THANKYOU_LOGGIN" }]"></span>
                  </div>
                </form>
            </div>
          </div>
        [{/if}]
        <!-- Anfang Tracking-Code fuer Partnerprogramme -->

        [{ if $oxcmp_shop->oxshops__oxadbutlerid->value }]
          <!--Adbutler-->
          [{assign var="discountnetprice" value=$basket->getDiscountedNettoPrice()}]
          [{assign var="currencycovindex" value=$oView->getCurrencyCovIndex()}]
          <img src="https://james.adbutler.de/lsgen.php?pid=[{$oxcmp_shop->oxshops__oxadbutlerid->value }]&amp;summe=[{ $discountnetprice * $currencycovindex |string_format:"%.2f"}]&amp;opts=[{$oxcmp_user->oxuser__oxlname->value }]&kdnr=[{$order->oxorder__oxordernr->value }]" WIDTH="1" HEIGHT="1">
          <!--Adbutler ende-->
        [{/if}]

        [{ if $oxcmp_shop->oxshops__oxaffilinetid->value }]
          <!--Affilinet-->
          [{assign var="discountnetprice" value=$basket->getDiscountedNettoPrice()}]
          [{assign var="currencycovindex" value=$oView->getCurrencyCovIndex()}]
          <img src="https://partners.webmasterplan.com/registersale.asp?site=[{$oxcmp_shop->oxshops__oxaffilinetid->value }]&amp;order=[{$order->oxorder__oxordernr->value }]&amp;curr=eur&amp;price=[{$discountnetprice * $currencycovindex|string_format:"%.2f"}]" WIDTH="1" HEIGHT="1">
          <!--Affilinet Ende-->
        [{/if}]

        [{ if $oxcmp_shop->oxshops__oxsuperclicksid->value }]
          <!--Superclix-Code-->
          [{assign var="discountnetprice" value=$basket->getDiscountedNettoPrice()}]
          [{assign var="currencycovindex" value=$oView->getCurrencyCovIndex()}]
          <img src="https://clix.superclix.de/cgi-bin/code.cgi?pp=[{$oxcmp_shop->oxshops__oxsuperclicksid->value }]&amp;cashflow=[{$discountnetprice * $currencycovindex |string_format:"%.2f"}]&amp;tax=1.00&amp;goods=[{$order->oxorder__oxordernr->value }]" width="1" height="1">
          <!--Superclix Ende-->
        [{/if}]

        [{ if $oxcmp_shop->oxshops__oxaffiliweltid->value }]
          <!--Affiliwelt-Code-->
          <!--img src="https://www.affiliwelt.net/partner/sregistering.php3?ID=[{$oxcmp_shop->oxshops__oxaffiliweltid->value }]&track=[{$order->oxorder__oxordernr->value }]&wert=[{ $basket->getDiscountedNettoPrice()}]&mone=EUR" width="1" height="1" border="0"-->
          [{assign var="discountnetprice" value=$basket->getDiscountedNettoPrice()}]
          [{assign var="currencycovindex" value=$oView->getCurrencyCovIndex()}]
          <img src="https://www.affiliwelt.net/tracking.php?prid=[{$oxcmp_shop->oxshops__oxaffiliweltid->value }]&amp;bestid=[{$order->oxorder__oxordernr->value }]&amp;beschreibung=OXID&preis=[{ $discountnetprice * $currencycovindex |string_format:"%.2f"}]" width="1" height="1">
          <!--Affiliwelt Ende-->
        [{/if}]

        [{ if $oxcmp_shop->oxshops__oxaffili24id->value }]
          <!--Affili24.com-->
          [{assign var="discountnetprice" value=$basket->getDiscountedNettoPrice()}]
          [{assign var="currencycovindex" value=$oView->getCurrencyCovIndex()}]
          <img src="https://partners.affili24.com/registering.php?ID=[{$oxcmp_shop->oxshops__oxaffili24id->value }]&amp;track=[{$order->oxorder__oxordernr->value }]&amp;wert=[{ $discountnetprice * $currencycovindex |string_format:"%.2f"}]" width="1" height="1">
          <!--Affili24 Ende-->
        [{/if}]

        <!-- Ende Tracking-Code fuer Partnerprogramme -->

        <br />
      </div>
    </div>
  [{/if}]


[{if $oView->showFinalStep()}]
    [{if $oView->getAlsoBoughtThiesProducts()}]

      [{assign var="tmpListType" value=$oView->getListType()}]
      [{assign var="sListType" value=""}]

      <strong class="head2">[{ oxmultilang ident="THANKYOU_ALSOBOUGHT"}]</strong>
      [{foreach from=$oView->getAlsoBoughtThiesProducts() item=actionproduct}]
          [{include file="inc/product.tpl" product=$actionproduct size="small" testid="AlsoBought_"|cat:$actionproduct->oxarticles__oxid->value }]
      [{/foreach}]

    [{/if}]
[{/if}]

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
