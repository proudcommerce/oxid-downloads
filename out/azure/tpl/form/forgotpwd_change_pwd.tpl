 <form class="oxValidate" action="[{ $oViewConf->getSelfActionLink() }]" name="order" method="post">
    	[{assign var="aErrors" value=$oView->getFieldValidationErrors()}]
          [{ $oViewConf->getHiddenSid() }]
          [{ $oViewConf->getNavFormParams() }]
          <input type="hidden" name="fnc" value="updatePassword">
          <input type="hidden" name="uid" value="[{ $oView->getUpdateId() }]">
          <input type="hidden" name="cl" value="forgotpwd">
          <input type="hidden" id="passwordLength" value="[{$oViewConf->getPasswordLength()}]">
      <ul class="form clear">
            <li [{if $aErrors}]class="oxError"[{/if}]>
            	<label>[{ oxmultilang ident="PAGE_ACCOUNT_FORGOTPWD_NEWPASSWORD" }]</label>
            	<input type="password" name="password_new" class="oxValidate oxValidate_notEmpty oxValidate_length oxValidate_match textbox">
            	<p class="oxValidateError">
                    <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
                    <span class="oxError_length">[{ oxmultilang ident="EXCEPTION_INPUT_PASSTOOSHORT" }]</span>
                    <span class="oxError_match">[{ oxmultilang ident="EXCEPTION_USER_PWDDONTMATCH" }]</span>
                    [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxpassword}]
           	 	</p>
            </li>
            <li [{if $aErrors}]class="oxError"[{/if}]>
            	<label>[{ oxmultilang ident="PAGE_ACCOUNT_FORGOTPWD_CONFIRMPASSWORD" }]</label>
            	<input type="password" name="password_new_confirm" class="oxValidate oxValidate_notEmpty oxValidate_length oxValidate_match textbox">
            	<p class="oxValidateError">
                    <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
                    <span class="oxError_length">[{ oxmultilang ident="EXCEPTION_INPUT_PASSTOOSHORT" }]</span>
                    <span class="oxError_match">[{ oxmultilang ident="EXCEPTION_USER_PWDDONTMATCH" }]</span>
                    [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxpassword}]
            	</p>
            </li>
            <li class="formSubmit">
            	<button class="submitButton" type="submit" name="save" value="[{ oxmultilang ident="PAGE_ACCOUNT_FORGOTPWD_UPDATEPASSWORD" }]">[{ oxmultilang ident="PAGE_ACCOUNT_FORGOTPWD_UPDATEPASSWORD" }]</button>
            </li>
      </ul>
    </form>