<li [{if $aErrors}]class="oxError"[{/if}]>
    <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_EMAIL" }] <span class="req">*</span></label>
    <input class="oxValidate oxValidate_notEmpty oxValidate_email oxValidate_enterPass oxValidate_enterPassTarget[oxValidate_pwd] textbox" type="text" name="invadr[oxuser__oxusername]" value="[{if isset( $invadr.oxuser__oxusername ) }][{ $invadr.oxuser__oxusername }][{else }][{ $oxcmp_user->oxuser__oxusername->value }][{/if }]" size="37">
    <p class="oxValidateError">
        <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
        <span class="oxError_email">[{ oxmultilang ident="EXCEPTION_INPUT_NOVALIDEMAIL" }]</span>
        [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxusername}]
    </p>
</li>
<li class="oxValidate_pwd[{if $aErrors}] oxError[{/if}][{if $aErrors.oxuser__oxpassword}] oxInValid[{/if}]" [{if !$aErrors.oxuser__oxpassword}]style="display:none;"[{/if}]>
    <label>[{oxmultilang ident="FORM_FIELDSET_USER_BILLING_PWD"}] <span class="req">*</span></label>
    <input class="oxValidate oxValidate_notEmpty textbox" type="password" size="37" name="user_password">
    <p class="oxValidateError" [{if $aErrors.oxuser__oxpassword}]style="display:block;"[{/if}]>
        <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
        <span class="oxError_length">[{ oxmultilang ident="EXCEPTION_INPUT_PASSTOOSHORT" }]</span>
        [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxpassword}]
    </p>
</li>