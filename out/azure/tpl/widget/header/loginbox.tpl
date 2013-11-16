[{assign var="bIsError" value=0 }]
[{capture name=loginErrors}]
    [{foreach from=$Errors.loginBoxErrors item=oEr key=key }]
        <p id="errorBadLogin" class="errorMsg">[{ $oEr->getOxMessage()}]</>
        [{assign var="bIsError" value=1 }]
    [{/foreach}]
[{/capture}]

[{if !$oxcmp_user->oxuser__oxpassword->value}]
    [{oxscript add="$( '#forgotPasswordOpener' ).oxModalPopup({ target: '#forgotPassword'});"}]
    <div id="forgotPassword" class="popupBox corners FXgradGreyLight glowShadow overlayPop">
        <img src="[{$oViewConf->getImageUrl()}]x.png" alt="" class="closePop">
        [{include file="form/forgotpwd_email.tpl"}]
    </div>
    <a href="#" class="trigger" title="[{ oxmultilang ident="WIDGET_LOGINBOX_LOGIN" }]">[{ oxmultilang ident="WIDGET_LOGINBOX_LOGIN" }]</a>
    <form id="login" name="login" action="[{ $oViewConf->getSslSelfLink() }]" method="post">
        <div id="loginBox" class="loginBox popBox" [{if $bIsError}]style="display: block;"[{/if}]>
            [{ $oViewConf->getHiddenSid() }]
            [{ $oViewConf->getNavFormParams() }]
            <input type="hidden" name="fnc" value="login_noredirect">
            <input type="hidden" name="cl" value="[{ $oViewConf->getActiveClassName() }]">
            <input type="hidden" name="pgNr" value="[{$oView->getActPage()}]">
            <input type="hidden" name="CustomError" value="loginBoxErrors">
            [{if $oView->getProduct()}]
                [{assign var="product" value=$oView->getProduct() }]
                <input type="hidden" name="anid" value="[{ $product->oxarticles__oxnid->value }]">
            [{/if}]
            <div class="loginForm corners fx-gradient-bg">
                <h4>[{ oxmultilang ident="WIDGET_LOGINBOX_LOGIN" }]</h4>
                <p>
                    <input id="loginEmail" type="text" name="lgn_usr" value="[{ oxmultilang ident="WIDGET_LOGINBOX_EMAIL_ADDRESS" }]" class="textbox innerLabel ">
                </p>
                <p>
                    <input type="password" name="lgn_pwd" class="textbox passwordbox innerLabel" value="[{ oxmultilang ident="WIDGET_LOGINBOX_PASSWORD" }]"><strong><a id="forgotPasswordOpener" href="#" title="[{ oxmultilang ident="WIDGET_LOGINBOX_FORGOT_PASSWORD" }]">?</a></strong>
                </p>
                [{$smarty.capture.loginErrors}]
                <p class="checkFields clear">
                    <input type="checkbox" class="checkbox" value="1" name="lgn_cook" id="remember"><label for="remember">[{ oxmultilang ident="WIDGET_LOGINBOX_REMEMBER_ME" }]</label>
                </p>
                <p>
                    <button type="submit" class="submitButton">[{ oxmultilang ident="WIDGET_LOGINBOX_LOGIN" }]</button>
                </p>
            </div>
            [{if $oViewConf->getShowFbConnect()}]
                <div class="altLoginBox corners fx-gradient-bg clear">
                    <span>[{ oxmultilang ident="WIDGET_LOGINBOX_WITH" }]</span>
                    <fb:login-button size="medium" autologoutlink="true" length="short"></fb:login-button>
                </div>
            [{/if}]
        </div>
    </form>
[{else}]
    [{ oxmultilang ident="WIDGET_LOGINBOX_GREETING" }]
    [{assign var="fullname" value=$oxcmp_user->oxuser__oxfname->value|cat:" "|cat:$oxcmp_user->oxuser__oxlname->value }]
    <a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_password" params=$oViewConf->getNavUrlParams() }]">
    [{if $fullname}]
        [{ $fullname }]
    [{else}]
        [{ $oxcmp_user->oxuser__oxusername->value|oxtruncate:25:"...":true }]
    [{/if}]
    </a>
    <a id="logoutLink" class="logoutLink" href="[{ $oViewConf->getLogoutLink() }]" title="[{ oxmultilang ident="WIDGET_LOGINBOX_LOGOUT" }]">[{ oxmultilang ident="WIDGET_LOGINBOX_LOGOUT" }]</a>
[{/if}]
