[{include file="_header.tpl" location=$oView->getTemplateLocation() }]
[{assign var="pageNavigation" value=$oView->getPageNavigation()}]

    <div class="boxhead">
        <h1 id="test_catTitle">[{$oView->getTitle()}]</h1>
        [{if $pageNavigation->iArtCnt }]<em id="test_catArtCnt">([{ $pageNavigation->iArtCnt }])</em>[{/if}]
        [{assign var="actCategory" value=$oView->getActiveCategory()}]
        [{if $actCategory && $actCategory->oxcategories__oxdesc->value }]<small id="test_catDesc">[{$actCategory->oxcategories__oxdesc->value}]</small>[{/if}]
        [{if $rsslinks.activeCategory}]
            <a class="rss" id="rss.activeCategory" href="[{$rsslinks.activeCategory.link}]" title="[{$rsslinks.activeCategory.title}]"></a>
            [{oxscript add="oxid.blank('rss.activeCategory');"}]
        [{/if}]
    </div>

    [{capture name=list_details}]
        [{if $actCategory->oxcategories__oxthumb->value }]
          <img src="[{$actCategory->getPictureUrl()}]0/[{ $actCategory->oxcategories__oxthumb->value }]" alt="[{ $actCategory->oxcategories__oxtitle->value }]"><br>
        [{/if}]

        [{if $oView->getAttributes() }]
            <form method="post" action="[{ $oViewConf->getSelfActionLink() }]" name="_filterlist" id="_filterlist">
            <div class="catfilter">
                [{ $oViewConf->getHiddenSid() }]
                [{ $oViewConf->getNavFormParams() }]
                <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
                <input type="hidden" name="tpl" value="[{$tpl}]">
                <input type="hidden" name="fnc" value="executefilter">

                <table cellpadding="0" cellspacing="0">
                [{foreach from=$oView->getAttributes() item=oFilterAttr key=sAttrID name=testAttr}]
                    <tr>
                        <td>
                            <label id="test_attrfilterTitle_[{$sAttrID}]_[{$smarty.foreach.testAttr.iteration}]">[{ $oFilterAttr->title }]:</label>
                        </td>
                        <td>
                           <select name="attrfilter[[{ $sAttrID }]]" onchange="oxid.form.send('_filterlist');">
                               <option value="" selected>[{ oxmultilang ident="LIST_PLEASECHOOSE" }]</option>
                               [{foreach from=$oFilterAttr->aValues item=oValue}]
                               <option value="[{ $oValue->id }]" [{ if $oValue->blSelected }]selected[{/if}]>[{ $oValue->value }]</option>
                               [{/foreach}]
                           </select>
                        </td>
                    </tr>
                [{/foreach}]
                </table>

                <noscript>
                    <input type="submit" value="[{ oxmultilang ident="LIST_APPLYFILTER" }]">
                </noscript>
            </div>
            </form>
        [{/if}]

        [{if $oView->hasVisibleSubCats()}]
            [{ oxmultilang ident="LIST_SELECTOTHERCATS1" }]<b>[{$actCategory->oxcategories__oxtitle->value}]</b> [{ oxmultilang ident="LIST_SELECTOTHERCATS2" }]
            <hr>
            <ul class="list">
            [{foreach from=$oView->getSubCatList() item=category name=MoreSubCat}]
                [{if $category->getContentCats() }]
                    [{foreach from=$category->getContentCats() item=ocont name=MoreCms}]
                    <li><a id="test_MoreSubCms_[{$smarty.foreach.MoreSubCat.iteration}]_[{$smarty.foreach.MoreCms.iteration}]" href="[{$ocont->getLink()}]">[{ $ocont->oxcontents__oxtitle->value }]</a></li>
                    [{/foreach}]
                [{/if}]
                [{if $category->getIsVisible()}]
                    [{if $category->oxcategories__oxicon->value }]
                        <a id="test_MoreSubCatIco_[{$smarty.foreach.MoreSubCat.iteration}]" href="[{ $category->getLink() }]">
                            <img src="[{$category->getIconUrl() }]" alt="[{ $category->oxcategories__oxtitle->value }]">
                        </a>
                    [{else}]
                        <li><a id="test_MoreSubCat_[{$smarty.foreach.MoreSubCat.iteration}]" href="[{ $category->getLink() }]">[{ $category->oxcategories__oxtitle->value }][{ if $category->getNrOfArticles() > 0 }] ([{ $category->getNrOfArticles() }])[{/if}]</a></li>
                    [{/if}]
                [{/if}]
            [{/foreach}]
            </ul>
        [{/if}]

        [{if $actCategory->oxcategories__oxlongdesc->value }]
            <hr>
            <span id="test_catLongDesc">[{ $actCategory->oxcategories__oxlongdesc->value }]</span>
        [{/if}]
    [{/capture}]

    <div class="box [{if $smarty.capture.list_details|trim ==''}]empty[{/if}]">
    [{$smarty.capture.list_details}]
    </div>

    [{if $pageNavigation->iArtCnt }]
        [{include file="inc/list_locator.tpl" PageLoc="Top"}]
    [{/if}]

    [{foreach from=$oView->getArticleList() item=actionproduct name=test_articleList}]
        [{include file="inc/product.tpl" product=$actionproduct testid="action_"|cat:$actionproduct->oxarticles__oxid->value test_Cntr=$smarty.foreach.test_articleList.iteration}]
    [{/foreach}]


    [{if $pageNavigation->iArtCnt }]
        [{include file="inc/list_locator.tpl" PageLoc="Bottom"}]
    [{/if}]

[{insert name="oxid_tracker" title="LIST_CATEGORY"|oxmultilangassign product=""}]
[{include file="_footer.tpl" }]
