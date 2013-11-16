[{assign var="invadr" value=$oView->getInvoiceAddress()}]
    <li>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_TITLE" }][{if $oView->isFieldRequired(oxuser__oxsal) }]<span class="req">*</span>[{/if}]</label>
        [{include file="form/fieldset/salutation.tpl" name="invadr[oxuser__oxsal]" value=$oxcmp_user->oxuser__oxsal->value }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_FIRSTNAME" }][{if $oView->isFieldRequired(oxuser__oxfname) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxfname) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="255" name="invadr[oxuser__oxfname]" value="[{if isset( $invadr.oxuser__oxfname ) }][{ $invadr.oxuser__oxfname }][{else }][{ $oxcmp_user->oxuser__oxfname->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxfname)}]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxfname}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_LASTNAME" }][{if $oView->isFieldRequired(oxuser__oxlname) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxlname) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="255" name="invadr[oxuser__oxlname]" value="[{if isset( $invadr.oxuser__oxlname ) }][{ $invadr.oxuser__oxlname }][{else }][{ $oxcmp_user->oxuser__oxlname->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxlname)}]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxlname}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_COMPANY" }][{if $oView->isFieldRequired(oxuser__oxcompany) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxcompany) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="255" name="invadr[oxuser__oxcompany]" value="[{if isset( $invadr.oxuser__oxcompany ) }][{ $invadr.oxuser__oxcompany }][{else }][{ $oxcmp_user->oxuser__oxcompany->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxcompany) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxcompany}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_STREETANDSTREETNO" }][{if $oView->isFieldRequired(oxuser__oxstreet) || $oView->isFieldRequired(oxuser__oxstreetnr) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxstreet) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" field="pair-xsmall" maxlength="255" name="invadr[oxuser__oxstreet]" value="[{if isset( $invadr.oxuser__oxstreet ) }][{ $invadr.oxuser__oxstreet }][{else }][{ $oxcmp_user->oxuser__oxstreet->value }][{/if}]">
          <input [{if $oView->isFieldRequired(oxuser__oxstreetnr) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" field="xsmall" maxlength="16" name="invadr[oxuser__oxstreetnr]" value="[{if isset( $invadr.oxuser__oxstreetnr ) }][{ $invadr.oxuser__oxstreetnr }][{else }][{ $oxcmp_user->oxuser__oxstreetnr->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxstreet) || $oView->isFieldRequired(oxuser__oxstreetnr) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxstreet}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_POSTALCODEANDCITY" }][{if $oView->isFieldRequired(oxuser__oxzip) || $oView->isFieldRequired(oxuser__oxcity) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxzip) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" field="small" maxlength="16" name="invadr[oxuser__oxzip]" value="[{if isset( $invadr.oxuser__oxzip ) }][{ $invadr.oxuser__oxzip }][{else }][{ $oxcmp_user->oxuser__oxzip->value }][{/if}]">
          <input [{if $oView->isFieldRequired(oxuser__oxcity) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" field="pair-small" maxlength="255" name="invadr[oxuser__oxcity]" value="[{if isset( $invadr.oxuser__oxcity ) }][{ $invadr.oxuser__oxcity }][{else }][{ $oxcmp_user->oxuser__oxcity->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxzip) || $oView->isFieldRequired(oxuser__oxcity) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxzip}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_VATIDNO" }][{if $oView->isFieldRequired(oxuser__oxustid) }]<span class="req">*</span>[{/if}]</label>
         <input [{if $oView->isFieldRequired(oxuser__oxustid) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="255" name="invadr[oxuser__oxustid]" value="[{if isset( $invadr.oxuser__oxustid ) }][{ $invadr.oxuser__oxustid }][{else}][{ $oxcmp_user->oxuser__oxustid->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxustid) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxustid}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_ADDITIONALINFO" }][{if $oView->isFieldRequired(oxuser__oxaddinfo) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxaddinfo) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="255" name="invadr[oxuser__oxaddinfo]" value="[{if isset( $invadr.oxuser__oxaddinfo ) }][{ $invadr.oxuser__oxaddinfo }][{else }][{ $oxcmp_user->oxuser__oxaddinfo->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxaddinfo) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxaddinfo}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_COUNTRY" }][{if $oView->isFieldRequired(oxuser__oxcountryid) }]<span class="req">*</span>[{/if}]</label>
          <select [{if $oView->isFieldRequired(oxuser__oxcountryid) }] class="oxValidate oxValidate_notEmpty" [{/if}] id="invCountrySelect" name="invadr[oxuser__oxcountryid]">
               <option value="">-</option>
            [{foreach from=$oViewConf->getCountryList() item=country key=country_id }]
                <option value="[{ $country->oxcountry__oxid->value }]" [{if isset( $invadr.oxuser__oxcountryid ) && $invadr.oxuser__oxcountryid == $country->oxcountry__oxid->value}] selected[{elseif $oxcmp_user->oxuser__oxcountryid->value == $country->oxcountry__oxid->value}] selected[{/if}]>[{ $country->oxcountry__oxtitle->value }]</option>
            [{/foreach }]
          </select>
          [{if $oView->isFieldRequired(oxuser__oxcountryid) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxcountryid}]
        </p>
          [{/if}]
    </li>
    <li class="stateBox">
          [{include file="form/fieldset/state.tpl"
                countrySelectId="invCountrySelect"
                stateSelectName="invadr[oxuser__oxstateid]"
                selectedStateIdPrim=$invadr.oxuser__oxstateid
                selectedStateId=$oxcmp_user->oxuser__oxstateid->value
         }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_PHONE" }][{if $oView->isFieldRequired(oxuser__oxfon) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxfon) }]class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="128" name="invadr[oxuser__oxfon]" value="[{if isset( $invadr.oxuser__oxfon ) }][{ $invadr.oxuser__oxfon }][{else }][{ $oxcmp_user->oxuser__oxfon->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxfon) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxfon}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_FAX" }][{if $oView->isFieldRequired(oxuser__oxfax) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxuser__oxfax) }] class="oxValidate oxValidate_notEmpty" [{/if}]type="text" size="37" maxlength="128" name="invadr[oxuser__oxfax]" value="[{if isset( $invadr.oxuser__oxfax ) }][{ $invadr.oxuser__oxfax }][{else }][{ $oxcmp_user->oxuser__oxfax->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxfax) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxfax}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_CELLUARPHONE" }][{if $oView->isFieldRequired(oxuser__oxmobfon) }]<span class="req">*</span>[{/if}]</label>
         <input [{if $oView->isFieldRequired(oxuser__oxmobfon) }] class="oxValidate oxValidate_notEmpty"[{/if}]type="text" size="37" maxlength="64" name="invadr[oxuser__oxmobfon]" value="[{if isset( $invadr.oxuser__oxmobfon ) }][{$invadr.oxuser__oxmobfon }][{else}][{$oxcmp_user->oxuser__oxmobfon->value }][{/if}]">
          [{if $oView->isFieldRequired(oxuser__oxmobfon) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxmobfon}]
        </p>
          [{/if}]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_EVENINGPHONE" }][{if $oView->isFieldRequired(oxuser__oxprivfon) }]<span class="req">*</span>[{/if}]</label>
        <input [{if $oView->isFieldRequired(oxuser__oxprivfon) }] class="oxValidate oxValidate_notEmpty" [{/if}] type="text" size="37" maxlength="64" name="invadr[oxuser__oxprivfon]" value="[{if isset( $invadr.oxuser__oxprivfon ) }][{$invadr.oxuser__oxprivfon }][{else}][{$oxcmp_user->oxuser__oxprivfon->value }][{/if}]">
        [{if $oView->isFieldRequired(oxuser__oxprivfon) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxprivfon}]
        </p>
        [{/if}]
    </li>
    [{if $oViewConf->showBirthdayFields() }]
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_BIRTHDATE" }][{if $oView->isFieldRequired(oxuser__oxbirthdate) }]<span class="req">*</span>[{/if}]</label>

          <input [{if $oView->isFieldRequired(oxuser__oxbirthdate) }] class="oxValidate oxValidate_notEmpty" [{/if}] type="text" field="small" maxlength="2" name="invadr[oxuser__oxbirthdate][day]" value="[{if isset( $invadr.oxuser__oxbirthdate.day ) }][{$invadr.oxuser__oxbirthdate.day }][{elseif $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00"}][{$oxcmp_user->oxuser__oxbirthdate->value|regex_replace:"/^([0-9]{4})[-]([0-9]{1,2})[-]/":"" }][{/if}]">
          <input [{if $oView->isFieldRequired(oxuser__oxbirthdate) }] class="oxValidate oxValidate_notEmpty" [{/if}] type="text" field="small" maxlength="2" name="invadr[oxuser__oxbirthdate][month]" value="[{if isset( $invadr.oxuser__oxbirthdate.month ) }][{$invadr.oxuser__oxbirthdate.month }][{elseif $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00" }][{$oxcmp_user->oxuser__oxbirthdate->value|regex_replace:"/^([0-9]{4})[-]/":""|regex_replace:"/[-]([0-9]{1,2})$/":"" }][{/if}]">
         <input [{if $oView->isFieldRequired(oxuser__oxbirthdate) }] class="oxValidate oxValidate_notEmpty" [{/if}] type="text" field="small" maxlength="4" name="invadr[oxuser__oxbirthdate][year]" value="[{if isset( $invadr.oxuser__oxbirthdate.year ) }][{$invadr.oxuser__oxbirthdate.year }][{elseif $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00" }][{$oxcmp_user->oxuser__oxbirthdate->value|regex_replace:"/[-]([0-9]{1,2})[-]([0-9]{1,2})$/":"" }][{/if}]">

          [{if $oView->isFieldRequired(oxuser__oxbirthdate) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxuser__oxbirthdate}]
        </p>
          [{/if}]
    </li>
    [{/if}]

    [{if $blSubscribeNews}]
    <li>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SUBSCRIBENEWSLETTER" }]</label>
        <input type="hidden" name="blnewssubscribed" value="0">
        <input id="subscribeNewsletter" type="checkbox" name="blnewssubscribed" value="1" [{if $oView->isNewsSubscribed()}]checked[{/if}]>
        <br>
        <div class="note">[{ oxmultilang ident="FORM_FIELDSET_USER_SUBSCRIBENEWSLETTER_MESSAGE" }]</div>
    </li>
    [{/if}]

    [{if $blOrderRemark}]
    <li>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_YOURMESSAGE" }]</label>
        [{ if !$oView->getOrderRemark()}]
          [{assign var="order_remark" value="FORM_FIELDSET_USER_MESSAGEHERE"|oxmultilangassign}]
        [{else}]
          [{assign var="order_remark" value=$oView->getOrderRemark()}]
        [{/if}]
        <textarea cols="60" rows="7" name="order_remark" class="areabox">[{$order_remark}]</textarea>
    </li>
    [{/if}]

    [{if !$noFormSubmit}]
    <li class="formNote">[{ oxmultilang ident="FORM_USER_COMPLETEMARKEDFIELDS" }]</li>
    <li class="formSubmit">
        <button id="accUserSaveTop" type="submit" name="save" class="submitButton" title="[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_SAVE" }]">[{ oxmultilang ident="FORM_FIELDSET_USER_BILLING_SAVE" }]</button>
    </li>
    [{/if}]