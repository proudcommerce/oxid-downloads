     <input type="hidden" id="passwordLength" value="[{$oViewConf->getPasswordLength()}]">
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_ACCOUNT_EMAIL" }]<span class="req">*</span></label>
        <input id="userLoginName" class="oxValidate oxValidate_notEmpty oxValidate_email" type="text" name="lgn_usr" value="[{ $oView->getActiveUsername() }]" size="37" >
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            <span class="oxError_email">[{ oxmultilang ident="EXCEPTION_INPUT_NOVALIDEMAIL" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxusername}]
        </p>
    </li>
       <li>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_ACCOUNT_NEWSLETTER" }]</label>
        <input type="hidden" name="blnewssubscribed" value="0">
        <input type="checkbox" class="checkbox"  name="blnewssubscribed" value="1" [{if $oView->isNewsSubscribed() }]checked[{/if}]>
        <span class="inputNote">[{ oxmultilang ident="FORM_FIELDSET_USER_ACCOUNT_NEWSLETTER_MESSAGE" }]</span>
    </li>