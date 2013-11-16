[{foreach from=$Errors.dyn_cmp_openidlogin_right item=oEr key=key }]
  <p class="err">[{ $oEr->getOxMessage()}]</p>
[{/foreach}]
  <form name="ropenidlogin" action="[{ $oViewConf->getSslSelfLink() }]" method="post">
    <div class="form">
        [{ $oViewConf->getHiddenSid() }]
        [{$_login_additional_form_parameters}]
        <input type="hidden" name="fnc" value="login_noredirect">
        <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
        <input type="hidden" name="pgNr" value="[{$_login_pgnr-1}]">
        <input type="hidden" name="tpl" value="[{$_login_tpl}]">
        <input type="hidden" name="CustomError" value='dyn_cmp_openidlogin_right'>
        [{if $oView->getProduct()}]
          [{assign var="product" value=$oView->getProduct() }]
          <input type="hidden" name="anid" value="[{ $product->oxarticles__oxnid->value }]">
        [{/if}]
        <label for="test_RightLogin_OpenId" >[{ oxmultilang ident="INC_CMP_LOGIN_RIGHT_OPENID" }]</label>
        <input id="test_RightLogin_OpenId" type="text" name="lgn_openid" value="" class="txt openid">
        <span class="btn"><input id="test_RightLogin_OpenIdLogin" type="submit" name="send" value="[{ oxmultilang ident="INC_CMP_LOGIN_RIGHT_LOGIN" }]" class="btn"></span>
    </div>
  </form>
