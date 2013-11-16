<div class="checkoutOptions clear">
    [{if $oView->getShowNoRegOption() }]
    <div class="lineBox option" id="optionNoRegistration">
        <h3>[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_NOREGISTRATION" }]</h3>
        <p>[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_NOREGISTRATION_DESCRIPTION" }]</p>
        <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
            <p>
                [{ $oViewConf->getHiddenSid() }]
                [{ $oViewConf->getNavFormParams() }]
                <input type="hidden" name="cl" value="user">
                <input type="hidden" name="fnc" value="">
                <input type="hidden" name="option" value="1">
                <button class="submitButton nextStep" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_NEXT" }]</button>
            </p>
        </form>
    </div>
    [{/if}]
    <div class="lineBox option" id="optionRegistration">
        <h3>[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_REGISTRATION" }]</h3>
        <p>[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_REGISTRATION_DESCRIPTION" }]</p>
        <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
            <p>
                [{ $oViewConf->getHiddenSid() }]
                [{ $oViewConf->getNavFormParams() }]
                <input type="hidden" name="cl" value="user">
                <input type="hidden" name="fnc" value="">
                <input type="hidden" name="option" value="3">
                <button class="submitButton nextStep" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_NEXT" }]</button>
            </p>
        </form>
    </div>
    <div class="lineBox option" id="optionLogin">
        <h3>[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_LOGIN" }]</h3>
        <p>[{ oxmultilang ident="PAGE_CHECKOUT_USER_OPTION_LOGIN_DESCRIPTION" }]</p>
        [{ include file="form/login.tpl"}]
    </div>
</div>