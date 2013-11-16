<form class="oxValidate" action="[{ $oViewConf->getSslSelfLink() }]" name="order" method="post">
[{assign var="aErrors" value=$oView->getFieldValidationErrors()}]
[{ $oViewConf->getHiddenSid() }]
[{ $oViewConf->getNavFormParams() }]
<input type="hidden" name="fnc" value="registeruser">
<input type="hidden" name="cl" value="register">
<input type="hidden" name="lgn_cook" value="0">
<input type="hidden" id="reloadAddress" name="reloadaddress" value="">
<input type="hidden" name="option" value="3">
    <h3 class="blockHead">[{ oxmultilang ident="FORM_REGISTER_ACCOUNTINFO" }]</h3>
    <ul class="form">
        [{ include file="form/fieldset/user_account.tpl" }]
        [{if $oView->isActive('PsLogin') }]
            <li>
                <span>
                    [{oxifcontent ident="oxagb" object="oCont"}]
                        [{assign var="sLink" value=$oCont->getLink()|replace:'&amp;':'&' }]
                        [{oxscript add="$( '#orderOpenAgbBottom' ).oxModalPopup({target: '#popup', loadUrl: '$sLink&plain=1'});"}]
                        [{oxmultilang ident="FORM_REGISTER_IAGREETOTERMS1" }]
                        <a id="orderOpenAgbBottom" rel="nofollow" href="[{ $oCont->getLink() }]"  class="fontunderline">[{ oxmultilang ident="FORM_REGISTER_IAGREETOTERMS2" }]</a>
                        [{ oxmultilang ident="FORM_REGISTER_IAGREETOTERMS3" }],&nbsp;
                    [{/oxifcontent}]
                    [{oxifcontent ident="oxrightofwithdrawal" object="oCont"}]
                        [{assign var="sLink" value=$oCont->getLink()|replace:'&amp;':'&' }]
                        [{oxscript add="$( '#orderOpenWithdrawalBottom' ).oxModalPopup({target: '#popup', loadUrl: '$sLink&plain=1'});"}]
                        [{oxmultilang ident="FORM_REGISTER_IAGREETORIGHTOFWITHDRAWAL1" }]
                        <a id="orderOpenWithdrawalBottom" rel="nofollow" href="[{ $oCont->getLink() }]">[{ $oCont->oxcontents__oxtitle->value }]</a>
                        [{ oxmultilang ident="FORM_REGISTER_IAGREETORIGHTOFWITHDRAWAL3" }]
                    [{/oxifcontent}]
                </span>
                <input type="hidden" name="ord_agb" value="0">
                <input id="orderConfirmAgbBottom" type="checkbox" class="chk" name="ord_agb" value="1">
            </li>
        [{/if}]
    </ul>
    <h3 class="blockHead">[{ oxmultilang ident="FORM_REGISTER_BILLINGADDRESS" }]</h3>
    <ul class="form">[{ include file="form/fieldset/user_billing.tpl" }]</ul>
</form>
 <div id="popup" class="popupBox corners FXgradGreyLight glowShadow overlayPop">
     <img src="[{$oViewConf->getImageUrl()}]x.png" alt="" class="closePop">
</div>