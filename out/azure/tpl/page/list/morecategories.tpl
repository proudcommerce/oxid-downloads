[{capture append="oxidBlock_content"}]
    <h1 class="pageHead">[{ oxmultilang ident="PAGE_PRODUCT_MORECATEGORIES" }]</h1>

    [{assign var="_navcategorytree" value=$oView->getCategoryTree()}]
    [{assign var="iSubCategoriesCount" value=0}]
    [{if $_navcategorytree|count}]
        <ul class="subcatList clear">
            <li>
            [{foreach from=$_navcategorytree item=category name=MoreSubCat}]
            
                [{* CMS top categories *}]
                [{if $category->getContentCats() }]
                    [{foreach from=$category->getContentCats() item=ocont name=MoreCms}]
                        [{assign var="iSubCategoriesCount" value=$iSubCategoriesCount+1}]
                        <div class="box">
                            <h3>
                                <a id="moreSubCms_[{$smarty.foreach.MoreSubCat.iteration}]_[{$smarty.foreach.MoreCms.iteration}]" href="[{$ocont->getLink()}]">[{ $ocont->oxcontents__oxtitle->value }]</a>
                            </h3>
                            <ul class="content"></ul>
                        </div>
                    [{/foreach}]
                [{/if }]
                [{if $iSubCategoriesCount%4 == 0}]
                </li><li>
                [{/if}]
                [{* TOP categories *}]
                [{if $category->getIsVisible()}]
                    [{assign var="iSubCategoriesCount" value=$iSubCategoriesCount+1}]
                    [{assign var="iconUrl" value=$category->getIconUrl()}]
                    <div class="box">
                        <h3>
                            <a id="moreSubCat_[{$smarty.foreach.MoreSubCat.iteration}]" href="[{ $category->getLink() }]">
                                [{$category->oxcategories__oxtitle->value }][{ if $category->getNrOfArticles() > 0 }] ([{ $category->getNrOfArticles() }])[{/if}]
                            </a>
                        </h3>
                        [{* Top categories subcategories *}]
                        [{if $category->getSubCats() || $category->getContentCats()}]
                            <ul class="content">
                                [{if $iconUrl}]
                                    <li class="subcatPic">
                                        <a href="[{ $category->getLink() }]">
                                            <img src="[{$category->getIconUrl() }]" alt="[{ $category->oxcategories__oxtitle->value }]" height="100" width="168">
                                        </a>
                                    </li>
                                [{/if}]
                                [{foreach from=$category->getSubCats() item=subcategory}]
                                    [{* CMS subcategories  *}] 
                                    [{ foreach from=$subcategory->getContentCats() item=ocont name=MoreCms}]
                                        <li>
                                            <a href="[{$ocont->getLink()}]"><strong>[{ $ocont->oxcontents__oxtitle->value }]</strong></a>
                                        </li>
                                    [{/foreach }]
                                    <li>
                                        <a href="[{ $subcategory->getLink() }]">
                                            <strong>[{ $subcategory->oxcategories__oxtitle->value }]</strong>[{ if $subcategory->getNrOfArticles() > 0 }] ([{ $subcategory->getNrOfArticles() }])[{/if}]
                                        </a>
                                    </li>
                                [{/foreach}]
                            </ul>
                        [{else}]
                            <div class="content[{if $iconUrl}] catPicOnly[{/if}]">
                                [{if $iconUrl}]
                                    <div class="subcatPic">
                                        <a href="[{ $category->getLink() }]">
                                            <img src="[{$category->getIconUrl() }]" alt="[{ $category->oxcategories__oxtitle->value }]" height="100" width="168">
                                        </a>
                                    </div>
                                [{/if}]
                            </div>
                        [{/if}]
                    </div>
            [{/if}]
            [{if $iSubCategoriesCount%4 == 0}]
            </li><li>
            [{/if}]
        [{/foreach}]
        </li>
        </ul>

    [{/if}]
[{/capture}]


[{include file="layout/page.tpl" sidebar="Left"}]
