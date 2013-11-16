[{capture append="oxidBlock_content"}]
[{assign var="template_title" value="PAGE_ACCOUNT_PASSWORD_PERSONALSETTINGS"|oxmultilangassign }]
[{if $oView->isPasswordChanged() }]
     <div class="status success corners">
      [{ oxmultilang ident="PAGE_ACCOUNT_PASSWORD_PASSWORDCHANGED" }]
     </div>
[{/if}]
<h1 id="personalSettingsHeader" class="pageHead">[{ oxmultilang ident="PAGE_ACCOUNT_PASSWORD_PERSONALSETTINGS" }]</h1>
[{include file="form/user_password.tpl"}]
[{insert name="oxid_tracker" title=$template_title }]
[{/capture}]
[{capture append="oxidBlock_sidebar"}]
    [{include file="page/account/inc/account_menu.tpl" active_link="password"}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]
