[{oxstyle include="css/superfish.css"}]
<ul id="navigation" class="sf-menu">
    <li [{if $oView->getClassName()=='start'}]class="current"[{/if}]><a href="[{$oViewConf->getHomeLink()}]">[{oxmultilang ident="TOP_CATEGORIES_HOME"}]</a></li>

    [{assign var="iAllCatCount" value=$oxcmp_categories|count }]
    [{if $iAllCatCount > $oView->getTopNavigationCatCnt() }]
        [{assign var="bHasMore" value="true"}]
        [{assign var="iCatCnt" value="1"}]
    [{else}]
        [{assign var="bHasMore" value="false"}]
        [{assign var="iCatCnt" value="0"}]
    [{/if}]

    [{foreach from=$oxcmp_categories item=ocat key=catkey name=root}]
      [{if $ocat->getIsVisible() }]
        [{foreach from=$ocat->getContentCats() item=oTopCont name=MoreTopCms}]
            [{assign var="iCatCnt" value=$iCatCnt+1 }]
            [{assign var="iAllCatCount" value=$iAllCatCount+1 }]
            [{if !$bHasMore && ($iCatCnt >= $oView->getTopNavigationCatCnt()) }]
                 [{assign var="bHasMore" value="true"}]
                 [{assign var="iCatCnt" value=$iCatCnt+1}]
            [{/if}]

            [{if $iCatCnt <= $oView->getTopNavigationCatCnt()}]
                <li><a href="[{$oTopCont->getLink()}]">[{$oTopCont->oxcontents__oxtitle->value}]</a></li>
            [{else}]
                [{capture append="moreLinks"}]
                    <li><a href="[{$oTopCont->getLink()}]">[{$oTopCont->oxcontents__oxtitle->value}]</a></li>
                [{/capture}]
            [{/if}]
        [{/foreach}]

        [{assign var="iCatCnt" value=$iCatCnt+1 }]
        [{if !$bHasMore && ($iCatCnt >= $oView->getTopNavigationCatCnt()) }]
                 [{assign var="bHasMore" value="true"}]
                 [{assign var="iCatCnt" value=$iCatCnt+1}]
        [{/if}]
        [{if $iCatCnt <= $oView->getTopNavigationCatCnt()}]
            <li [{if $ocat->expanded}]class="current"[{/if}]>
                <a href="[{$ocat->getLink()}]">[{$ocat->oxcategories__oxtitle->value}][{ if $ocat->getNrOfArticles() > 0}] ([{$ocat->getNrOfArticles()}])[{/if}]</a>
                [{if $ocat->getSubCats()}]
                    <ul>
                    [{foreach from=$ocat->getSubCats() item=osubcat key=subcatkey name=SubCat}]
                        [{foreach from=$osubcat->getContentCats() item=ocont name=MoreCms}]
                            <li><a href="[{$ocont->getLink()}]">[{$ocont->oxcontents__oxtitle->value}]</a></li>
                        [{/foreach}]
                        [{if $osubcat->getIsVisible() }]
                            <li><a href="[{$osubcat->getLink()}]">[{$osubcat->oxcategories__oxtitle->value}] [{ if $osubcat->getNrOfArticles() > 0}] ([{$osubcat->getNrOfArticles()}])[{/if}]</a></li>
                        [{/if}]
                    [{/foreach}]
                    </ul>
                [{/if}]
            </li>
        [{else}]
            [{capture append="moreLinks"}]
               <li [{if $ocat->expanded}]class="current"[{/if}]>
                    <a href="[{$ocat->getLink()}]">[{$ocat->oxcategories__oxtitle->value}][{ if $ocat->getNrOfArticles() > 0}] ([{$ocat->getNrOfArticles()}])[{/if}]</a>
               </li>
            [{/capture}]
        [{/if}]
      [{/if}]
    [{/foreach}]
    [{if $iAllCatCount > $oView->getTopNavigationCatCnt()}]
        <li>
            [{assign var="_catMoreUrl" value=$oView->getCatMoreUrl()}]
            <a href="[{ oxgetseourl ident="`$_catMoreUrl`&amp;cl=alist" }]">[{ oxmultilang ident="TOP_CATEGORIES_MORE" }]</a>
            <ul>
                [{foreach from=$moreLinks item=link}]
                   [{$link}]
                [{/foreach}]
            </ul>
        </li>
    [{/if}]
</ul>
