[{if $oView->getSearchTitle() }]
  [{ assign var="template_location" value=$oView->getSearchTitle()}]
[{else}]
  [{ assign var="template_location" value=""}]
  [{ assign var="blSep" value=""}]
  [{foreach from=$oView->getCatTreePath() item=oCatPath}]
    [{ if $blSep == "y"}]
      [{ assign var="template_location" value=$template_location|cat:" / "}]
    [{/if}]
    [{ assign var="template_location" value=$template_location|cat:"<a href=\""|cat:$oCatPath->getLink()|cat:"\">"|cat:$oCatPath->oxcategories__oxtitle->value|cat:"</a>"}]
    [{ assign var="blSep" value="y"}]
  [{/foreach}]
[{/if}]


[{include file="_header.tpl" location=$template_location }]

<!-- article locator -->
[{include file="inc/details_locator.tpl" where="Top" actCategory=$oView->getActiveCategory()}]

<!-- ox_mod01 details -->
[{assign var="currency" value=$oView->getActCurrency() }]
[{assign var="product" value=$oView->getProduct() }]
<div class="product details head big">

    <strong id="test_detailsHeader" class="h4 big">[{oxmultilang ident="DETAILS_PERSPARAM_PRODUCTDETAILS"}]</strong>

    <h1 id="test_product_name">[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]</h1>
    <tt id="test_product_artnum">[{ oxmultilang ident="INC_PRODUCTITEM_ARTNOMBER2" }] [{ $product->oxarticles__oxartnum->value }]</tt>

    <div class="picture">
      <img src="[{ $oView->getActPicture() }]" id="product_img" alt="[{ $product->oxarticles__oxtitle->value|strip_tags }] [{ $product->oxarticles__oxvarselect->value|default:'' }]">
    </div>

    <div class="exturls">
    [{if $oView->showZoomPics() }]
        [{assign var="aZoomPics" value=$oView->getZoomPics() }]
        [{assign var="iZoomPic" value=$oView->getActZoomPic() }]
        [{assign var="sZoomPopup" value="inc/popup_zoom.tpl" }]
        <a id="test_zoom" rel="nofollow" href="[{$product->getMoreDetailLink()}]" onmouseover="" onclick="oxid.popup.zoom();oxid.image('zoom_img','[{$aZoomPics[$iZoomPic].file}]');return false;"><b>[{ oxmultilang ident="DETAILS_PERSPARAM_ZOOM" }]</b></a>
    [{/if}]

    [{if $product->oxarticles__oxfile->value}]
        <a id="product_file" href="[{$product->getFileUrl()}][{ $product->oxarticles__oxfile->value }]"><b>[>] [{ $product->oxarticles__oxfile->value }]</b></a>
        [{oxscript add="oxid.blank('product_file');"}]
    [{/if}]

    [{if $product->oxarticles__oxexturl->value}]
        <a id="product_exturl" class="details" href="http://[{ $product->oxarticles__oxexturl->value }]"><b>[>] [{ $product->oxarticles__oxurldesc->value }]</b></a>
        [{oxscript add="oxid.blank('product_exturl');"}]
    [{/if}]

    </div>


    [{oxhasrights ident="SHOWSHORTDESCRIPTION"}]
        <div id="test_product_shortdesc" class="desc">[{ $product->oxarticles__oxshortdesc->value }]</div>
    [{/oxhasrights}]

    [{ if !$oxcmp_user}]
      [{assign var="star_title" value="DETAILS_PERSPARAM_LOGGIN"|oxmultilangassign }]
    [{ elseif !$oView->canRate() }]
      [{assign var="star_title" value="DETAILS_PERSPARAM_ALREADYRATED"|oxmultilangassign }]
    [{ else }]
      [{assign var="star_title" value="DETAILS_PERSPARAM_RATETHISARTICLE"|oxmultilangassign }]
    [{/if}]
    [{math equation="x*y" x=20 y=$product->getArticleRatingAverage() assign="currentRate" }]
    <br>
    <ul id="star_rate_top" class="rating">
      <li class="current_rate" style="width: [{$currentRate}]%;"><a title="[{$star_title}]"><b>1</b></a></li>
      <li class="one"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $oView->canRate() }]href="#review" onclick="showReview(1);"[{/if}] title="[{$star_title}]"><b>1</b></a></li>
      <li class="two"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $oView->canRate() }]href="#review" onclick="showReview(2);"[{/if}] title="[{$star_title}]"><b>2</b></a></li>
      <li class="three"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $oView->canRate() }]href="#review" onclick="showReview(3);"[{/if}] title="[{$star_title}]"><b>3</b></a></li>
      <li class="four"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $oView->canRate() }]href="#review" onclick="showReview(4);"[{/if}] title="[{$star_title}]"><b>4</b></a></li>
      <li class="five"><a rel="nofollow" [{ if !$oxcmp_user}]href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"}]"[{ elseif $oView->canRate() }]href="#review" onclick="showReview(5);"[{/if}] title="[{$star_title}]"><b>5</b></a></li>
    </ul>
    [{if $product->oxarticles__oxratingcnt->value}]
      <a id="star_rating_text" rel="nofollow" href="#review" onclick="showReview();" class="fs10 link2">[{$product->oxarticles__oxratingcnt->value}] [{if $product->oxarticles__oxratingcnt->value == 1}][{ oxmultilang ident="DETAILS_PERSPARAM_RATINGREZULT" }][{else}][{ oxmultilang ident="DETAILS_PERSPARAM_RATINGREZULTS" }] [{/if}]</a>
    [{else}]
      <a id="star_rating_text" rel="nofollow" href="#review" onclick="showReview();" class="fs10 link2">[{ oxmultilang ident="DETAILS_PERSPARAM_NORATINGS" }]</a>
    [{/if}]

    <div class="cats">
        [{ assign var="oManufacturer" value=$oView->getManufacturer()}]
        [{if ($oManufacturer && $oView->getListType()!='manufacturer') }]
          [{if $oManufacturer->oxmanufacturers__oxicon->value}]
              <img src="[{$oManufacturer->getIconUrl()}]" alt="[{ $oManufacturer->oxmanufacturers__oxtitle->value}]">
          [{/if}]
          <b>[{ oxmultilang ident="DETAILS_PERSPARAM_MANUFACTURER" }]</b>
          [{if !$oManufacturer->isReadOnly()}]
              <a id="test_manufacturer_[{$oManufacturer->oxmanufacturers__oxid->value}]" href="[{ $oManufacturer->getLink() }]">[{ $oManufacturer->oxmanufacturers__oxtitle->value}]</a>
          [{else}]
              [{ $oManufacturer->oxmanufacturers__oxtitle->value}]
          [{/if}]
          <br>
        [{else}]
          [{ assign var="oVendor" value=$oView->getVendor()}]
          [{if ($oVendor && $oView->getListType()!='vendor') }]
            [{if $oVendor->oxvendor__oxicon->value}]
                <img src="[{$oVendor->getIconUrl()}]" alt="[{ $oVendor->oxvendor__oxtitle->value}]">
            [{/if}]
            <b>[{ oxmultilang ident="DETAILS_PERSPARAM_VENDOR" }]</b>
            [{if !$oVendor->isReadOnly()}]
                <a id="test_vendor_[{$oVendor->oxvendor__oxid->value}]" href="[{ $oVendor->getLink() }]">[{ $oVendor->oxvendor__oxtitle->value}]</a>
            [{else}]
                [{ $oVendor->oxvendor__oxtitle->value}]
            [{/if}]
            <br>
          [{/if}]
        [{/if}]
        [{ assign var="oCategory" value=$oView->getCategory()}]
        [{if $oCategory && $oView->getListType()!='list'}]
            <b>[{ oxmultilang ident="DETAILS_PERSPARAM_CATEGORY" }]</b>
            <a id="test_category_[{$oCategory->oxcategories__oxid->value }]" href="[{ $oCategory->getLink() }]">[{ $oCategory->oxcategories__oxtitle->value }]</a>
        [{/if}]
    </div>

    <div class="status">

      [{if $product->getStockStatus() == -1}]
      <div class="flag red"></div>
        [{ if $product->oxarticles__oxnostocktext->value  }]
            [{ $product->oxarticles__oxnostocktext->value  }]
        [{elseif $oViewConf->getStockOffDefaultMessage() }]
            [{ oxmultilang ident="DETAILS_PERSPARAM_NOTONSTOCK" }]
        [{/if}]

        [{ if $product->getDeliveryDate() }]
          <br>[{ oxmultilang ident="DETAILS_PERSPARAM_AVAILABLEON" }] [{ $product->getDeliveryDate() }]
        [{/if}]

      [{elseif $product->getStockStatus() == 1}]

      <div class="flag orange"></div>
      <b>[{ oxmultilang ident="DETAILS_PERSPARAM_LOWSTOCK" }]</b>

      [{elseif $product->getStockStatus() == 0}]

      <div class="flag green"></div>

      [{ if $product->oxarticles__oxstocktext->value  }]
        [{ $product->oxarticles__oxstocktext->value  }]
      [{elseif $oViewConf->getStockOnDefaultMessage() }]
        [{ oxmultilang ident="DETAILS_PERSPARAM_READYFORSHIPPING" }]
      [{/if}]

      [{/if}]

    </div>


    <form action="[{ $oViewConf->getSelfActionLink() }]" method="post">

    <div>
    [{ $oViewConf->getHiddenSid() }]
    [{ $oViewConf->getNavFormParams() }]
    <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
    <input type="hidden" name="fnc" value="tobasket">
    <input type="hidden" name="aid" value="[{ $product->oxarticles__oxid->value }]">
    <input type="hidden" name="anid" value="[{ $product->oxarticles__oxnid->value }]">
    </div>

    [{if $oView->getSelectLists() }]
    [{foreach key=iSel from=$oView->getSelectLists() item=oList}]
     <div class="variants">
      <label>[{ $oList.name }]:</label>
        <select id="test_select_[{$product->oxarticles__oxid->value}]_[{$iSel}]" name="sel[[{$iSel}]]" onchange="JavaScript:setSellList(this);">
          [{foreach key=iSelIdx from=$oList item=oSelItem}]
            [{ if $oSelItem->name }]<option value="[{$iSelIdx}]">[{ $oSelItem->name }]</option>[{/if}]
          [{/foreach}]
        </select>
    </div>
    [{/foreach}]
    [{/if}]

    [{oxhasrights ident="SHOWARTICLEPRICE"}]
        <div class="price">
            [{if $product->getFTPrice() }]
                <b class="old">[{ oxmultilang ident="DETAILS_PERSPARAM_REDUCEDFROM" }] <del>[{ $product->getFTPrice()}] [{ $currency->sign}]</del></b>
                <span class="desc">[{ oxmultilang ident="DETAILS_PERSPARAM_REDUCEDTEXT" }]</span><br>
                <sub class="only">[{ oxmultilang ident="DETAILS_PERSPARAM_NOWONLY" }]</sub>
            [{/if}]
            [{if $product->getFPrice() }]
                <big id="test_product_price">[{ $product->getFPrice() }] [{ $currency->sign}]</big>
            [{/if}]
            [{assign var="oCont" value=$oView->getContentByIdent("oxdeliveryinfo") }]
            <sup class="dinfo">[{ oxmultilang ident="DETAILS_PERSPARAM_PLUSSHIPPING" }]<a href="[{ $oCont->getLink() }]" rel="nofollow">[{ oxmultilang ident="DETAILS_PERSPARAM_PLUSSHIPPING2" }]</a></sup>
        </div>
    [{/oxhasrights}]

    [{if $product->getPricePerUnit()}]
    <div id="test_product_price_unit" class="pperunit">
        ([{$product->getPricePerUnit()}] [{ $currency->sign}]/[{$product->oxarticles__oxunitname->value}])
    </div>
    [{/if}]

    [{if $product->oxarticles__oxvpe->value > 1}]
    <div class="packing">
        [{ oxmultilang ident="DETAILS_PERSPARAM_VPE_MESSAGE_1" }] [{ $product->oxarticles__oxvpe->value}] [{ oxmultilang ident="DETAILS_PERSPARAM_VPE_MESSAGE_2" }]
    </div>
    [{/if}]

    [{oxhasrights ident="SHOWARTICLEPRICE"}]
     [{if $product->loadAmountPriceInfo()}]
       <table class="amprice">
         <tr>
            <th colspan="2">[{ oxmultilang ident="DETAILS_PERSPARAM_MOREYOUBUYMOREYOUSAVE" }]</th>
         </tr>
         [{foreach from=$product->loadAmountPriceInfo() item=priceItem}]
           <tr>
             <td class="am">[{ oxmultilang ident="DETAILS_PERSPARAM_FROM" }] [{$priceItem->oxprice2article__oxamount->value}] [{ oxmultilang ident="DETAILS_PERSPARAM_PCS" }]</td>
             <td id="test_amprice_[{$priceItem->oxprice2article__oxamount->value}]_[{$priceItem->oxprice2article__oxamountto->value}]" class="pr">
               [{if $priceItem->oxprice2article__oxaddperc->value}]
                 - [{$priceItem->oxprice2article__oxaddperc->value}] [{ oxmultilang ident="DETAILS_PERSPARAM_DISCOUNT" }]
               [{else}]
                 - [{$priceItem->fbrutprice}] [{ $currency->sign}]
               [{/if}]
             </td>
           </tr>
         [{/foreach}]
       </table>
    [{/if}]
    [{/oxhasrights}]

    [{if $size!='big'}] [{$smarty.capture.product_price}] [{/if}]

    [{oxhasrights ident="TOBASKET"}]
        [{ if $product->isBuyable() }]
            <div class="amount">
                <label>[{ oxmultilang ident="DETAILS_PERSPARAM_QUANTITY" }]</label><input id="test_AmountToBasket" type="text" name="am" value="1" size="3">
            </div>
            <div class="tocart"><input id="test_toBasket" type="submit" value="[{if $size=='small'}][{oxmultilang ident="INC_PRODUCTITEM_ADDTOCARD3" }][{else}][{oxmultilang ident="INC_PRODUCTITEM_ADDTOCARD2"}][{/if}]" onclick="oxid.popup.load();"></div>
            [{if $oView->isPriceAlarm()}]
            <div class="pricealarm">
                <a id="test_PriceAlarmLink" rel="nofollow" href="#preisalarm_link">[{ oxmultilang ident="DETAILS_PERSPARAM_PRICEALARM" }]</a>
            </div>
            [{/if}]
        [{else}]
            [{if $oView->isPriceAlarm() && !$product->isParentNotBuyable()}]
            <div class="pricealarm">
                <a rel="nofollow" href="#preisalarm_link">[{ oxmultilang ident="DETAILS_PERSPARAM_PRICEALARM2" }]</a>
            </div>
            [{/if}]
        [{/if}]
    [{/oxhasrights}]
    </form>

    <div class="actions">
        [{if $isfiltering }]
            [{oxid_include_dynamic file="dyn/compare_links.tpl" testid="" type="compare" aid=$product->oxarticles__oxid->value anid=$product->oxarticles__oxnid->value in_list=$product->isOnComparisonList() page=$pageNavigation->actPage-1 text_to_id="DETAILS_PERSPARAM_COMPARE" text_from_id="DETAILS_PERSPARAM_REMOVEFROMCOMPARELIST"}]
        [{/if}]

        <a id="test_suggest" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=suggest" params="anid=`$product->oxarticles__oxnid->value`"|cat:$oViewConf->getNavUrlParams() }]">[{ oxmultilang ident="DETAILS_PERSPARAM_RECOMMEND" }]</a>

        [{ if $oxcmp_user }]
            <a id="test_Recommlist" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=recommadd" params="aid=`$product->oxarticles__oxnid->value`&amp;anid=`$product->oxarticles__oxnid->value`"|cat:$oViewConf->getNavUrlParams() }]" class="details">[{ oxmultilang ident="DETAILS_PERSPARAM_ADDTORECOMMLIST" }]</a>
        [{ else}]
            <a id="test_LoginToRecommlist" class="reqlogin" rel="nofollow" href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"|cat:$oViewConf->getNavUrlParams() }]">[{ oxmultilang ident="DETAILS_PERSPARAM_LOGGINTOACCESSRECOMMLIST" }]</a>
        [{ /if}]

        [{if $oxcmp_user }]
            <a id="slist" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl="|cat:$oViewConf->getActiveClassName() params="aid=`$product->oxarticles__oxnid->value`&amp;anid=`$product->oxarticles__oxnid->value`&amp;fnc=tonoticelist&amp;am=1"|cat:$oViewConf->getNavUrlParams() }]" rel="nofollow">[{ oxmultilang ident="DETAILS_PERSPARAM_ADDTONOTICELIST" }]</a>
        [{else}]
            <a id="test_LoginToNotice" class="reqlogin" href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"|cat:$oViewConf->getNavUrlParams() }]" rel="nofollow">[{ oxmultilang ident="DETAILS_PERSPARAM_LOGGINTOACCESSNOTICELIST" }]</a>
        [{/if}]

        [{if $oxcmp_user }]
            <a id="wlist" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl="|cat:$oViewConf->getActiveClassName() params="aid=`$product->oxarticles__oxnid->value`&anid=`$product->oxarticles__oxnid->value`&amp;fnc=towishlist&amp;am=1"|cat:$oViewConf->getNavUrlParams() }]" rel="nofollow">[{ oxmultilang ident="DETAILS_PERSPARAM_ADDTOWISHLIST" }]</a>
        [{else}]
            <a id="test_LoginToWish" class="reqlogin" href="[{ $product->getLink()|oxaddparams:"fnc=showLogin"|cat:$oViewConf->getNavUrlParams() }]" rel="nofollow">[{ oxmultilang ident="DETAILS_PERSPARAM_LOGGINTOACCESSWISHLIST" }]</a>
        [{/if}]
    </div>

    [{include file="inc/bookmarks.tpl"}]

</div>

<div class="product moredetails">
  [{if $oView->morePics() }]
    <div class="morepics">
    [{foreach from=$oView->getIcons() key=picnr item=ArtIcon name=MorePics}]
        <a id="test_MorePics_[{$smarty.foreach.MorePics.iteration}]" rel="nofollow" href="[{ $product->getLink()|oxaddparams:"actpicid=`$picnr`" }]" onclick="oxid.image('product_img','[{$product->getPictureUrl($picnr)}]');return false;"><img src="[{$product->getIconUrl($picnr)}]" alt=""></a>
    [{/foreach}]
    </div>
    [{/if}]

    <div class="longdesc">
        <strong class="h3" id="test_productFullTitle">[{ $product->oxarticles__oxtitle->value }][{if $product->oxarticles__oxvarselect->value}] [{ $product->oxarticles__oxvarselect->value }][{/if}]</strong>
        [{oxhasrights ident="SHOWLONGDESCRIPTION"}]
         <div id="test_product_longdesc">[{ $product->oxarticles__oxlongdesc->value }]</div>
        [{/oxhasrights}]

        <div class="question">
            <a id="test_QuestionMail" href="mailto:[{$product->oxarticles__oxquestionemail->value|default:$oxcmp_shop->oxshops__oxinfoemail->value}]?subject=[{ 'DETAILS_PERSPARAM_QUESTIONSSUBJECT'|oxmultilangassign|escape:'url' }]%20[{$product->oxarticles__oxartnum->value|escape:'url'}]">[{ oxmultilang ident="DETAILS_PERSPARAM_QUESTIONS" }]</a>
        </div>
    </div>

</div>


[{ if $oView->getAttributes() }]
<strong id="test_specsHeader" class="boxhead">[{ oxmultilang ident="DETAILS_PERSPARAM_SPECIFICATION" }]</strong>
<div class="box">
    <table width="100%" class="attributes">
      <colgroup><col width="50%" span="2"></colgroup>
      [{foreach from=$oView->getAttributes() item=oAttr name=attribute}]
          <tr [{if $smarty.foreach.attribute.last}]class="last"[{/if}]>
            <td id="test_attrTitle_[{$smarty.foreach.attribute.iteration}]"><b>[{ $oAttr->title }]</b></td>
            <td id="test_attrValue_[{$smarty.foreach.attribute.iteration}]">[{ $oAttr->value }]</td>
          </tr>
      [{/foreach}]
    </table>
</div>
[{/if}]

[{include file="inc/media.tpl"}]

[{include file="inc/tags.tpl"}]

[{if $oView->isPriceAlarm() && !$product->isParentNotBuyable()}]
<strong id="preisalarm_link" class="boxhead">[{ oxmultilang ident="DETAILS_PERSPARAM_PRICEALARM3" }]</strong>
<div class="box">
    <p>[{ oxmultilang ident="DETAILS_PERSPARAM_PRICEALARMMESSAGE" }]</p>
    <form name="pricealarm" action="[{ $oViewConf->getSelfActionLink() }]" method="post">
    <div>
        [{ $oViewConf->getHiddenSid() }]
        [{ $oViewConf->getNavFormParams() }]
        <input type="hidden" name="cl" value="pricealarm">
        <input type="hidden" name="fnc" value="addme">
        <input type="hidden" name="pa[aid]" value="[{ $product->oxarticles__oxid->value }]">
        [{assign var="oCaptcha" value=$oView->getCaptcha() }]
        <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>
    </div>

    <table class="pricealarm" width="100%" summary="[{ oxmultilang ident="DETAILS_PERSPARAM_PRICEALARM3" }]">
        <colgroup>
            <col width="20%">
            <col width="10%">
            <col width="22%" span="2">
            <col width="6%">
            <col width="20%">
        </colgroup>
        <tr>
          <th colspan="2"><label class="nobold">[{ oxmultilang ident="CONTACT_VERIFICATIONCODE" }]</label></th>
          <th><label>[{ oxmultilang ident="DETAILS_PERSPARAM_EMAIL" }]</label></th>
          <th colspan="3"><label class="hl">[{ oxmultilang ident="DETAILS_PERSPARAM_YOURPRICE" }]</label></th>
        </tr>
        <tr>
            <td>
             [{if $oCaptcha->isImageVisible()}]
               <img src="[{$oCaptcha->getImageUrl()}]" alt="[{ oxmultilang ident="CONTACT_VERIFICATIONCODE" }]" width="80" height="18">
             [{else}]
               <div class="verification_code">[{$oCaptcha->getText()}]</div>
             [{/if}]
            </td>
            <td><input type="text" name="c_mac" value="" size="5"></td>
            <td><input type="text" name="pa[email]" value="[{ if $oxcmp_user }][{ $oxcmp_user->oxuser__oxusername->value }][{/if}]" size="20" maxlength="128"></td>
            <td><input type="text" name="pa[price]" value="[{oxhasrights ident="SHOWARTICLEPRICE"}][{ if $product }][{ $product->getFPrice() }][{/if}][{/oxhasrights}]" size="20" maxlength="32"></td>
            <td><b class="hl">[{ $currency->sign}]</b></td>
            <td>
                <span class="btn">
                    <input id="test_PriceAlarmSubmit" type="submit" name="submit" value="[{ oxmultilang ident="DETAILS_PERSPARAM_SEND" }]" class="btn">
                </span>
            </td>
        </tr>
      </table>

      </form>
</div>
[{/if}]

[{if $oView->getVariantList() || $oView->drawParentUrl()}]

    <strong id="test_variantHeader" class="boxhead">
        [{if $oView->drawParentUrl()}]
            <a id="test_backToParent" href="[{$oView->getParentUrl()}]">[{oxmultilang ident="INC_PRODUCT_VARIANTS_BACKTOMAINPRODUCT"|oxmultilangassign|cat:" "|cat:$oView->getParentName() }]</a>
        [{else}]
            [{oxmultilang ident="INC_PRODUCT_VARIANTS_VARIANTSELECTIONOF"|oxmultilangassign|cat:" `$product->oxarticles__oxtitle->value`" }]
        [{/if}]
    </strong>
    <div class="box variantslist">

    [{ if $oView->drawParentUrl()}]
      <b id="test_variantHeader1">[{ oxmultilang ident="INC_PRODUCT_VARIANTS_OTHERVARIANTSOF" }] [{ $oView->getParentName() }]</b>
      <br>
      <div class="txtseparator inbox"></div>
    [{/if}]

    [{foreach from=$oView->getVariantList() name=variants item=variant_product}]

        [{if $smarty.foreach.variants.first}]
          [{assign var="details_variants_class" value="firstinlist"}]

        [{elseif $smarty.foreach.variants.last}]
          [{assign var="details_variants_class" value="lastinlist"}]
          <div class="separator inbox"></div>
        [{else}]
          [{assign var="details_variants_class" value="inlist"}]
          <div class="separator inbox"></div>
        [{/if}]

        [{$variants_head}]

        [{include file="inc/product.tpl" product=$variant_product size="thinest" altproduct=$product->getId() isfiltering=false class=$details_variants_class testid="Variant_"|cat:$variant_product->oxarticles__oxid->value}]

        [{assign var="details_variants_head" value=""}]

    [{/foreach}]

    </div>

[{/if}]

<strong id="test_reviewHeader" class="boxhead">[{ oxmultilang ident="DETAILS_PERSPARAM_PRODUCTREVIEW" }]</strong>
<div id="review" class="box info">
  [{ if $oxcmp_user }]
    <form action="[{ $oViewConf->getSelfActionLink() }]" method="post" id="rating">
        <div id="write_review">
            [{ if $oView->canRate() }]
            <input type="hidden" name="artrating" value="0">
            <ul id="star_rate" class="rating">
                <li id="current_rate" class="current_rate" style="width: 0px;"><a title="[{$star_title}]"><b>1</b></a></li>
                [{ assign var="__params" value="anid=`$product->oxarticles__oxnid->value`&amp;"|cat:$oViewConf->getNavUrlParams() }]
                <li class="one"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(1);return false;" title="1 [{ oxmultilang ident="DETAILS_PERSPARAM_STAR" }]"><b>1</b></a></li>
                <li class="two"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(2);return false;" title="2 [{ oxmultilang ident="DETAILS_PERSPARAM_STARS" }]"><b>2</b></a></li>
                <li class="three"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(3);return false;" title="3 [{ oxmultilang ident="DETAILS_PERSPARAM_STARS" }]"><b>3</b></a></li>
                <li class="four"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(4);return false;" title="4 [{ oxmultilang ident="DETAILS_PERSPARAM_STARS" }]"><b>4</b></a></li>
                <li class="five"><a rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params=$__params }]" onclick="showReview(5);return false;" title="5 [{ oxmultilang ident="DETAILS_PERSPARAM_STARS" }]"><b>5</b></a></li>
            </ul>
            [{/if}]
            [{ $oViewConf->getHiddenSid() }]
            [{ $oViewConf->getNavFormParams() }]
            <input type="hidden" name="fnc" value="savereview">
            <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
            <input type="hidden" name="reviewuserid" value="[{$oView->getReviewUserId()}]">
            <input type="hidden" name="anid" value="[{ $product->oxarticles__oxid->value }]">
            <textarea cols="102" rows="15" name="rvw_txt" class="fullsize"></textarea><br>
            <span class="btn"><input id="test_reviewSave" type="submit" value="[{ oxmultilang ident="DETAILS_PERSPARAM_SAVEREVIEW" }]" class="btn"></span>
        </div>
    </form>
    <a id="write_new_review" rel="nofollow" class="fs10" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=review" params="anid=`$product->oxarticles__oxnid->value`&amp;"|cat:$oViewConf->getNavUrlParams() }]" onclick="showReview();return false;"><b>[{ oxmultilang ident="DETAILS_PERSPARAM_WRITEREVIEW" }]</b></a>
  [{else}]
    <a id="test_Reviews_login" rel="nofollow" href="[{ $product->getLink()|oxaddparams:"fnc=showLogin&anchor=review"|cat:$oViewConf->getNavUrlParams() }]" class="fs10"><b>[{ oxmultilang ident="DETAILS_PERSPARAM_LOGGINTOWRITEREVIEW" }]</b></a>
  [{/if}]

  [{if $oView->getReviews() }]
   [{foreach from=$reviews item=review name=ReviewsCounter}]

    <dl class="review">
        <dt>
            <span class="left"><b id="test_ReviewName_[{$smarty.foreach.ReviewsCounter.iteration}]">[{ $review->oxuser__oxfname->value }]</b> [{ oxmultilang ident="DETAILS_PERSPARAM_WRITES" }]</span>
            <span class="right param"><b id="test_ReviewTime_[{$smarty.foreach.ReviewsCounter.iteration}]">[{ oxmultilang ident="DETAILS_PERSPARAM_TIME" }]</b>&nbsp;[{ $review->oxreviews__oxcreate->value|date_format:"%H:%M" }]</span>
            <span class="right param"><b id="test_ReviewDate_[{$smarty.foreach.ReviewsCounter.iteration}]">[{ oxmultilang ident="DETAILS_PERSPARAM_DATE" }]</b>&nbsp;[{ $review->oxreviews__oxcreate->value|date_format:"%d.%m.%Y" }]</span>
            <span class="right param">[{if $review->oxreviews__oxrating->value }]<b id="test_ReviewRating_[{$smarty.foreach.ReviewsCounter.iteration}]">[{ oxmultilang ident="DETAILS_PERSPARAM_RATING" }]</b>&nbsp;[{ $review->oxreviews__oxrating->value }][{/if}]</span>
        </dt>
        <dd id="test_ReviewText_[{$smarty.foreach.ReviewsCounter.iteration}]">
            [{ $review->oxreviews__oxtext->value }]
        </dd>
    </dl>

   [{/foreach}]
  [{else}]
    <div class="dot_sep mid"></div>
    [{ oxmultilang ident="DETAILS_PERSPARAM_REVIEWNOTAVAILABLE" }]
  [{/if}]
</div>


[{ include file="inc/product.tpl" product=$product size="thin" isfiltering=false head="DETAILS_PERSPARAM_CURRENTPRODUCT"|oxmultilangassign testid="current"}]



[{oxid_include_dynamic file="dyn/last_seen_products.tpl" type="lastproducts" aid=$product->oxarticles__oxid->value aparentid=$product->oxarticles__oxparentid->value testid="LastSeen" }]

<!-- article locator -->
[{include file="inc/details_locator.tpl" where="Bottom"}]

[{ insert name="oxid_tracker" title="DETAILS_PERSPARAM_TITLE"|oxmultilangassign product=$product cpath=$oView->getCatTreePath() }]
[{include file="_footer.tpl" popup=$sZoomPopup }]
