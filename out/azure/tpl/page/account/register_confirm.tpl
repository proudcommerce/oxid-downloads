[{capture append="oxidBlock_content"}]
    <h1 id="openAccHeader" class="pageHead">[{ oxmultilang ident="PAGE_ACCOUNT_REGISTER_CONFIRM_WELCOME" }]</h1>
    <div class="box info">
      [{ oxmultilang ident="PAGE_ACCOUNT_REGISTER_CONFIRM_CONFIRMED" }]
    </div>
[{/capture}]
[{if $oView->isActive('PsLogin') }]
    [{include file="layout/popup.tpl"}]
[{else}]
    [{include file="layout/page.tpl" sidebar="Right"}]
[{/if}]
