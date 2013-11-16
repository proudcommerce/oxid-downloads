<div class="box" [{if $_boxId}]id="[{$_boxId}]"[{/if}]>
    [{if $_sHeaderIdent}]
        <h3 class="clear [{if $_sHeaderCssClass}] [{$_sHeaderCssClass}][{/if}]">
            [{ oxmultilang ident=$_sHeaderIdent }]
            [{assign var='rsslinks' value=$oView->getRssLinks() }]
            [{if $rsslinks.topArticles}]
                <a class="rss external" id="rssTopProducts" href="[{$rsslinks.topArticles.link}]" title="[{$rsslinks.topArticles.title}]"><img src="[{$oViewConf->getImageUrl()}]rss.png" alt="[{$rsslinks.topArticles.title}]"><span class="FXgradOrange corners glowShadow">[{$rsslinks.topArticles.title}]</span></a>
            [{/if }]
        </h3>
    [{/if}]
    [{oxscript add="$( '.articleBox' ).oxArticleBox();" }]
    <ul class="articleBox featuredList">
    [{foreach from=$_oBoxProducts item=_oBoxProduct name=_sProdList}]
            [{ assign var="currency" value=$oView->getActCurrency()}]
            [{ assign var="_sTitle" value="`$_oBoxProduct->oxarticles__oxtitle->value` `$_oBoxProduct->oxarticles__oxvarselect->value`"|strip_tags}]
            <li class="articleImage" [{if !$smarty.foreach._sProdList.first}] style="display:none;" [{/if}]>
                <div align="center">
                    <a class="articleBoxImage" href="[{ $_oBoxProduct->getMainLink() }]">
                        <img src="[{$_oBoxProduct->getIconUrl()}]" alt="[{$_sTitle}]">
                    </a>
                </div>
            </li>
            <li class="articleTitle">
                <a href="[{ $_oBoxProduct->getMainLink() }]">
                    [{ $_sTitle }]<br>
                    [{oxhasrights ident="SHOWARTICLEPRICE"}]
                        [{if $_oBoxProduct->getFPrice()}]
                            <strong>[{ $_oBoxProduct->getFPrice() }] [{ $currency->sign}]</strong>
                        [{/if}]
                    [{/oxhasrights}]
                </a>
            </li>
    [{/foreach}]
    </ul>
</div>