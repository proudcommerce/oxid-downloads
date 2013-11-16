[{if $oxcmp_user}]
    [{assign var="delivadr" value=$oxcmp_user->getSelectedAddress()}]
[{/if}]
<li>
    <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_ADDRESSES" }]</label>
    <input type="hidden" name="changeClass" value="[{$onChangeClass|default:'account_user'}]">
    <select id="addressId" name="oxaddressid">
        <option value="-1">[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_NEWADDRESS" }]</option>
        [{if $oxcmp_user }]
            [{foreach from=$oxcmp_user->getUserAddresses() item=address }]
                <option value="[{$address->oxaddress__oxid->value}]" [{if $address->isSelected()}]SELECTED[{/if}]>[{$address}]</option>
            [{/foreach }]
        [{/if}]
    </select>
</li>
<li class="form" id="shippingAddressText">
    [{if $delivadr }]
        [{ include file="widget/address/shipping_address.tpl" delivadr=$delivadr}]
        <button id="userChangeShippingAddress" class="submitButton largeButton" name="changeShippingAddress" type="submit">[{ oxmultilang ident="PAGE_CHECKOUT_BASKET_CHANGE" }]</button>
        [{oxscript add="$('#userChangeShippingAddress').click( function() { $('#shippingAddressForm').show();$('#shippingAddressText').hide();return false;});"}]
    [{/if}]
</li>
<ol id="shippingAddressForm" [{if $delivadr }]style="display: none;"[{/if}]>
    <li>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_TITLE2" }][{if $oView->isFieldRequired(oxaddress__oxsal) }]<span class="req">*</span>[{/if }]</label>
          [{include file="form/fieldset/salutation.tpl" name="deladr[oxaddress__oxsal]" value=$delivadr->oxaddress__oxsal->value value2=$deladr.oxaddress__oxsal }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_FIRSTLASTNAME" }][{if $oView->isFieldRequired(oxaddress__oxfname)}]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxaddress__oxfname) }]class="oxValidate oxValidate_notEmpty"[{/if }] type="text" maxlength="255" name="deladr[oxaddress__oxfname]" value="[{if isset( $deladr.oxaddress__oxfname ) }][{ $deladr.oxaddress__oxfname }][{else}][{ $delivadr->oxaddress__oxfname->value }][{/if }]">
          [{if $oView->isFieldRequired(oxaddress__oxfname)}]
          <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxfname}]
        </p>
          [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_FIRSTLASTNAME" }][{if $oView->isFieldRequired(oxaddress__oxlname)}]<span class="req">*</span>[{/if}]</label>
        <input [{if $oView->isFieldRequired(oxaddress__oxlname)}]class="oxValidate oxValidate_notEmpty"[{/if }] type="text" maxlength="255" name="deladr[oxaddress__oxlname]" value="[{if isset( $deladr.oxaddress__oxlname ) }][{ $deladr.oxaddress__oxlname }][{else}][{ $delivadr->oxaddress__oxlname->value }][{/if }]">
        [{if $oView->isFieldRequired(oxaddress__oxlname)}]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxlname}]
        </p>
        [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_COMPANY2" }][{if $oView->isFieldRequired(oxaddress__oxcompany) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxaddress__oxcompany) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" size="37" maxlength="255" name="deladr[oxaddress__oxcompany]" value="[{if isset( $deladr.oxaddress__oxcompany ) }][{ $deladr.oxaddress__oxcompany }][{else}][{ $delivadr->oxaddress__oxcompany->value }][{/if }]">
         [{if $oView->isFieldRequired(oxaddress__oxcompany) }]
         <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxcompany}]
        </p>
         [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_STREETANDSTREETNO2" }][{if $oView->isFieldRequired(oxaddress__oxstreet) || $oView->isFieldRequired(oxaddress__oxstreetnr) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxaddress__oxstreet) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" field="pair-xsmall" maxlength="255" name="deladr[oxaddress__oxstreet]" value="[{if isset( $deladr.oxaddress__oxstreet ) }][{ $deladr.oxaddress__oxstreet }][{else}][{ $delivadr->oxaddress__oxstreet->value }][{/if }]">
          <input [{if $oView->isFieldRequired(oxaddress__oxstreetnr) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" field="xsmall" maxlength="16" name="deladr[oxaddress__oxstreetnr]" value="[{if isset( $deladr.oxaddress__oxstreetnr ) }][{ $deladr.oxaddress__oxstreetnr }][{else}][{ $delivadr->oxaddress__oxstreetnr->value }][{/if }]">
          [{if $oView->isFieldRequired(oxaddress__oxstreet) || $oView->isFieldRequired(oxaddress__oxstreetnr) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxstreet}]
        </p>
          [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_POSTALCODEANDCITY2" }][{if $oView->isFieldRequired(oxaddress__oxzip) || $oView->isFieldRequired(oxaddress__oxcity) }]<span class="req">*</span>[{/if}]</label>
         <input [{if $oView->isFieldRequired(oxaddress__oxzip) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" field="small" maxlength="50" name="deladr[oxaddress__oxzip]" value="[{if isset( $deladr.oxaddress__oxzip ) }][{ $deladr.oxaddress__oxzip }][{else}][{ $delivadr->oxaddress__oxzip->value }][{/if }]">
          <input [{if $oView->isFieldRequired(oxaddress__oxcity) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" field="pair-small" maxlength="255" name="deladr[oxaddress__oxcity]" value="[{if isset( $deladr.oxaddress__oxcity ) }][{ $deladr.oxaddress__oxcity }][{else}][{ $delivadr->oxaddress__oxcity->value }][{/if }]">
          [{if $oView->isFieldRequired(oxaddress__oxzip) || $oView->isFieldRequired(oxaddress__oxcity) }]
          <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxzip}]
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxcity}]
        </p>
      [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_ADDITIONALINFO2" }][{if $oView->isFieldRequired(oxaddress__oxaddinfo) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxaddress__oxaddinfo) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" size="37" maxlength="255" name="deladr[oxaddress__oxaddinfo]" value="[{if isset( $deladr.oxaddress__oxaddinfo ) }][{ $deladr.oxaddress__oxaddinfo }][{else}][{ $delivadr->oxaddress__oxaddinfo->value }][{/if }]">
          [{if $oView->isFieldRequired(oxaddress__oxaddinfo) }]
          <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxaddinfo}]
        </p>
          [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_COUNTRY2" }][{if $oView->isFieldRequired(oxaddress__oxcountryid) }]<span class="req">*</span>[{/if}]</label>
          <select [{if $oView->isFieldRequired(oxaddress__oxcountryid) }] class="oxValidate oxValidate_notEmpty" [{/if }] id="delCountrySelect" name="deladr[oxaddress__oxcountryid]">
            <option value="">-</option>
            [{foreach from=$oViewConf->getCountryList() item=country key=country_id }]
              <option value="[{ $country->oxcountry__oxid->value }]" [{if isset( $deladr.oxaddress__oxcountryid ) && $deladr.oxaddress__oxcountryid == $country->oxcountry__oxid->value }]selected[{elseif $delivadr->oxaddress__oxcountry->value == $country->oxcountry__oxtitle->value or $delivadr->oxaddress__oxcountry->value == $country->oxcountry__oxid->value or $delivadr->oxaddress__oxcountryid->value == $country->oxcountry__oxid->value }]selected[{/if }]>[{ $country->oxcountry__oxtitle->value }]</option>
            [{/foreach }]
          </select>
          [{if $oView->isFieldRequired(oxaddress__oxcountryid) }]
          <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxcountryid}]
        </p>
      [{/if }]
    </li>
    <li class="stateBox">
          [{include file="form/fieldset/state.tpl"
                countrySelectId="delCountrySelect"
                stateSelectName="deladr[oxaddress__oxstateid]"
                selectedStateIdPrim=$deladr.oxaddress__oxstateid
                selectedStateId=$delivadr->oxaddress__oxstateid->value
        }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_PHONE2" }][{if $oView->isFieldRequired(oxaddress__oxfon) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxaddress__oxfon) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfon]" value="[{if isset( $deladr.oxaddress__oxfon ) }][{ $deladr.oxaddress__oxfon }][{else}][{ $delivadr->oxaddress__oxfon->value }][{/if }]">
          [{if $oView->isFieldRequired(oxaddress__oxfon) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxfon}]
        </p>
          [{/if }]
    </li>
    <li [{if $aErrors}]class="oxError"[{/if}]>
        <label>[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_FAX2" }][{if $oView->isFieldRequired(oxaddress__oxfax) }]<span class="req">*</span>[{/if}]</label>
          <input [{if $oView->isFieldRequired(oxaddress__oxfax) }] class="oxValidate oxValidate_notEmpty" [{/if }] type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfax]" value="[{if isset( $deladr.oxaddress__oxfax ) }][{ $deladr.oxaddress__oxfax }][{else}][{ $delivadr->oxaddress__oxfax->value }][{/if }]">
         [{if $oView->isFieldRequired(oxaddress__oxfax) }]
        <p class="oxValidateError">
            <span class="oxError_notEmpty">[{ oxmultilang ident="EXCEPTION_INPUT_NOTALLFIELDS" }]</span>
            [{include file="message/inputvalidation.tpl" aErrors=$aErrors.oxaddress__oxfax}]
        </p>
         [{/if }]
    </li>
</ol>
[{if !$noFormSubmit}]
    <li class="formNote">[{ oxmultilang ident="FORM_USER_COMPLETEMARKEDFIELDS" }]</li>
    <li class="formSubmit">
        <button id="accUserSaveBottom" type="submit" class="submitButton" name="save" title="[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_SAVE2" }]">[{ oxmultilang ident="FORM_FIELDSET_USER_SHIPPING_SAVE2" }]</button>
    </li>
[{/if}]