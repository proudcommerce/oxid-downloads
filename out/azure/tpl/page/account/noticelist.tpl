[{capture append="oxidBlock_content"}]
     <h1 class="pageHead">[{ oxmultilang ident="PAGE_ACCOUNT_NOTICELIST_MYWISHLIST" }]</h1>
    [{if $oView->getNoticeProductList() }]
        [{include file="widget/product/list.tpl" type="line" listId="noticelistProductList" title="" products=$oView->getNoticeProductList() removeFunction="tonoticelist" owishid=$oxcmp_user->oxuser__oxid->value}]
    [{else}]
        <div class="box info">
          [{ oxmultilang ident="PAGE_ACCOUNT_NOTICELIST_EMPTYWISHLIST" }]
        </div>
    [{/if}]
[{/capture}]
[{capture append="oxidBlock_sidebar"}]
    [{include file="page/account/inc/account_menu.tpl" active_link="noticelist"}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]