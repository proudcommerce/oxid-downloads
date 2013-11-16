[{assign var="editval" value=$oView->getUserData() }]
<form class="oxValidate" action="[{ $oViewConf->getSslSelfLink() }]" method="post">
    <div>
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="fnc" value="send"/>
        <input type="hidden" name="cl" value="contact"/>
        [{assign var="oCaptcha" value=$oView->getCaptcha() }]
        <input type="hidden" name="c_mach" value="[{$oCaptcha->getHash()}]"/>
    </div>
    <ul class="form clear">
        <li>
            <label>[{ oxmultilang ident="FORM_CONTACT_TITLE" }]</label>
            [{include file="form/fieldset/salutation.tpl" name="editval[oxuser__oxsal]" value=$editval.oxuser__oxsal }]
        </li>
        <li [{if $aErrors}]class="oxError"[{/if}]>
            <label>[{ oxmultilang ident="FORM_CONTACT_FIRSTNAME" }]<span class="req">*</span></label>
            <input type="text" name="editval[oxuser__oxfname]" size="70" maxlength="40" value="[{$editval.oxuser__oxfname}]" class="oxValidate oxValidate_notEmpty">
            <p class="oxValidateError">
                <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
                [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxfname}]
            </p>
        </li>
        <li [{if $aErrors}]class="oxError"[{/if}]>
            <label>[{ oxmultilang ident="FORM_CONTACT_LASTNAME" }]<span class="req">*</span></label>
            <input type="text" name="editval[oxuser__oxlname]" size=70 maxlength=40 value="[{$editval.oxuser__oxlname}]" class="oxValidate oxValidate_notEmpty">
            <p class="oxValidateError">
                <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            </p>
        </li>
        <li [{if $aErrors}]class="oxError"[{/if}]>
            <label>[{ oxmultilang ident="FORM_CONTACT_EMAIL2" }]<span class="req">*</span></label>
            <input id="contactEmail" type="text" name="editval[oxuser__oxusername]"  size=70 maxlength=40 value="[{$editval.oxuser__oxusername}]" class="oxValidate oxValidate_notEmpty oxValidate_email">
            <p class="oxValidateError">
                <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            </p>
        </li>
        <li [{if $aErrors}]class="oxError"[{/if}]>
            <label>[{ oxmultilang ident="FORM_CONTACT_SUBJECT" }]<span class="req">*</span></label>
            <input type="text" name="c_subject" size="70" maxlength=80 value="[{$oView->getContactSubject()}]" class="oxValidate oxValidate_notEmpty">
                    <p class="oxValidateError">
                <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            </p>
        </li>
        <li>
            <label>[{ oxmultilang ident="FORM_CONTACT_MESSAGE" }]</label>
            <textarea rows="15" cols="70" name="c_message" class="areabox">[{$oView->getContactMessage()}]</textarea>
        </li>
        <li class="verify">
            <label>[{ oxmultilang ident="FORM_CONTACT_VERIFICATIONCODE" }]<span class="req">*</span></label>
            [{assign var="oCaptcha" value=$oView->getCaptcha() }]
            [{if $oCaptcha->isImageVisible()}]
                <img src="[{$oCaptcha->getImageUrl()}]" alt="">
            [{else}]
                <span class="verificationCode" id="verifyTextCode">[{$oCaptcha->getText()}]</span>
            [{/if}]
            <input type="text" field="verify" name="c_mac" value="" class="oxValidate oxValidate_notEmpty">
            <p class="oxValidateError">
                <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            </p>
        </li>
        <li class="formNote">
            [{ oxmultilang ident="FORM_CONTACT_COMPLETEMARKEDFIELDS2" }]
        </li>
        <li>
            <button class="submitButton largeButton" type="submit">[{ oxmultilang ident="FORM_CONTACT_SEND" }]</button>
        </li>
    </ul>
</form>
