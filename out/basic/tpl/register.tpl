[{assign var="template_title" value="REGISTER_MYACCOUNT"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location=$template_title}]

<form action="[{ $oViewConf->getSslSelfLink() }]" name="order" method="post">

    <strong id="test_openAccHeader" class="boxhead">[{ oxmultilang ident="REGISTER_OPENACCOUNT" }]</strong>
    [{assign var="aMustFillFields" value=$oView->getMustFillFields() }]
    <div class="box info">
        [{ $oViewConf->getHiddenSid() }]
        [{ $oViewConf->getNavFormParams() }]
        <input type="hidden" name="fnc" value="registeruser">
        <input type="hidden" name="cl" value="register">
        <input type="hidden" name="lgn_cook" value="0">
        <input type="hidden" name="option" value="3">
        <table class="form" width="100%">
            <colgroup>
                <col width="35%">
                <col width="65%">
            </colgroup>
            <tr class="th_sep">
                <th colspan="2">[{ oxmultilang ident="REGISTER_ACCOUNTINFO" }] <small>[{ oxmultilang ident="REGISTER_COMPLETEMARKEDFIELDS" }]</small></th>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_EMAIL" }]</label></td>
                <td><input id="test_lgn_usr" type="text" name="lgn_usr" value="[{if $lgn_usr}][{$lgn_usr}][{else}][{$oxcmp_user->oxuser__oxusername->value}][{/if}]" size="37" > <span class="req">*</span></td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_PWD" }]</label></td>
                <td><input id="test_lgn_pwd" type="password" name="lgn_pwd" value="[{$lgn_pwd}]" size="37"> <span class="req">*</span></td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_CONFIRMPWD" }]</label></td>
                <td><input id="test_lgn_pwd2" type="password" name="lgn_pwd2" value="[{$lgn_pwd2}]" size="37"> <span class="req">*</span></td>
            </tr>
            <tr class="td_sep">
                <td><label>[{ oxmultilang ident="REGISTER_NEWSLETTER" }]</label></td>
                <td>
                    <input type="hidden" name="blnewssubscribed" value="0">
                    <input type="checkbox"  name="blnewssubscribed" value="1" [{if $oView->isNewsSubscribed() }]checked[{/if}]>
                </td>
            </tr>
            <tr class="th_sep">
                <th colspan="2" class="mid">[{ oxmultilang ident="REGISTER_BILLINGADDRESS" }] <small>[{ oxmultilang ident="REGISTER_COMPLETEMARKEDFIELDS2" }]</small></th>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_TITLE" }]</label></td>
                <td>
                    <select NAME="invadr[oxuser__oxsal]">
                        <option [{ if $oxcmp_user->oxuser__oxsal->value == "REGISTER_MR"|oxmultilangassign or $invadr.oxuser__oxsal == "REGISTER_MR"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="REGISTER_MR" }]</option>
                        <option [{ if $oxcmp_user->oxuser__oxsal->value == "REGISTER_MRS"|oxmultilangassign or $invadr.oxuser__oxsal == "REGISTER_MRS"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="REGISTER_MRS" }]</option>
                    </select>
                    [{if isset($aMustFillFields.oxuser__oxsal) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_FIRSTNAME" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="invadr[oxuser__oxfname]" value="[{if $oxcmp_user->oxuser__oxfname->value }][{$oxcmp_user->oxuser__oxfname->value }][{else}][{$invadr.oxuser__oxfname }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxfname)}]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_LASTNAME" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="invadr[oxuser__oxlname]" value="[{if $oxcmp_user->oxuser__oxlname->value }][{$oxcmp_user->oxuser__oxlname->value }][{else}][{$invadr.oxuser__oxlname }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxlname) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_COMPANY" }]</label></td>
                <td>
                <input type="text" size="37" maxlength="255" name="invadr[oxuser__oxcompany]" value="[{if $oxcmp_user->oxuser__oxcompany->value }][{$oxcmp_user->oxuser__oxcompany->value }][{else}][{$invadr.oxuser__oxcompany }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxcompany) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_STREET" }]</label></td>
                <td>
                    <input type="text" size="28" maxlength="255" name="invadr[oxuser__oxstreet]" value="[{if $oxcmp_user->oxuser__oxstreet->value }][{$oxcmp_user->oxuser__oxstreet->value }][{else}][{$invadr.oxuser__oxstreet }][{/if}]">
                    <input type="text" size="5" maxlength="16" name="invadr[oxuser__oxstreetnr]" value="[{if $oxcmp_user->oxuser__oxstreetnr->value }][{$oxcmp_user->oxuser__oxstreetnr->value }][{else}][{$invadr.oxuser__oxstreetnr }][{/if}]">
                    [{if isset($aMustFillFields.oxuser__oxstreet) || isset($aMustFillFields.oxuser__oxstreetnr) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_PZLCITY" }]</label></td>
                <td>
                    <input type="text" size="5" maxlength="16" name="invadr[oxuser__oxzip]" value="[{if $oxcmp_user->oxuser__oxzip->value }][{$oxcmp_user->oxuser__oxzip->value }][{else}][{$invadr.oxuser__oxzip }][{/if}]">
                    <input type="text" size="28" maxlength="255" name="invadr[oxuser__oxcity]" value="[{if $oxcmp_user->oxuser__oxcity->value }][{$oxcmp_user->oxuser__oxcity->value }][{else}][{$invadr.oxuser__oxcity }][{/if}]">
                    [{if isset($aMustFillFields.oxuser__oxzip) || isset($aMustFillFields.oxuser__oxcity) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_VATID" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="invadr[oxuser__oxustid]" value="[{if $oxcmp_user->oxuser__oxustid->value }][{$oxcmp_user->oxuser__oxustid->value }][{else}][{$invadr.oxuser__oxustid }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxustid) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_ADDITIONALINFO" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="invadr[oxuser__oxaddinfo]" value="[{if $oxcmp_user->oxuser__oxaddinfo->value }][{$oxcmp_user->oxuser__oxaddinfo->value }][{else}][{$invadr.oxuser__oxaddinfo }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxaddinfo) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_COUNTRY" }]</label></td>
                <td>
                    <select name="invadr[oxuser__oxcountryid]" >
                        <option value="">-</option>
                        [{foreach from=$oView->getCountryList() item=country key=country_id}]
                        <option value="[{$country->oxcountry__oxid->value}]"[{ if $oxcmp_user->oxuser__oxcountryid->value == $country->oxcountry__oxid->value}] selected[{elseif $invadr.oxuser__oxcountryid == $country->oxcountry__oxid->value}] selected[{/if}]>[{$country->oxcountry__oxtitle->value}]</option>
                        [{/foreach}]
                    </select>
                    [{if isset($aMustFillFields.oxuser__oxcountryid) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_PHONE" }]</label></td>
                <td><input type="text" size="37" maxlength="128" name="invadr[oxuser__oxfon]" value="[{if $oxcmp_user->oxuser__oxfon->value }][{$oxcmp_user->oxuser__oxfon->value }][{else}][{$invadr.oxuser__oxfon }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxfon) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_FAX" }]</label></td>
                <td><input type="text" size="37" maxlength="128" name="invadr[oxuser__oxfax]" value="[{if $oxcmp_user->oxuser__oxfax->value }][{$oxcmp_user->oxuser__oxfax->value }][{else}][{$invadr.oxuser__oxfax }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxfax) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_MOBIL" }]</label></td>
                <td><input type="text" size="37" maxlength="128" name="invadr[oxuser__oxmobfon]" value="[{if $oxcmp_user->oxuser__oxmobfon->value }][{$oxcmp_user->oxuser__oxmobfon->value }][{else}][{$invadr.oxuser__oxmobfon }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxmobfon) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr [{if !$oViewConf->showBirthdayFields() }]class="td_sep"[{/if}]>
                <td><label>[{ oxmultilang ident="REGISTER_PRIVATPHONE" }]</label></td>
                <td><input type="text" size="37" maxlength="128" name="invadr[oxuser__oxprivfon]" value="[{if $oxcmp_user->oxuser__oxprivfon->value }][{$oxcmp_user->oxuser__oxprivfon->value }][{else}][{$invadr.oxuser__oxprivfon }][{/if}]"> [{if isset($aMustFillFields.oxuser__oxprivfon) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            [{if $oViewConf->showBirthdayFields() }]
            <tr class="td_sep">
                <td><label>[{ oxmultilang ident="REGISTER_BIRTHDATE" }]</label></td>
                <td valign="top">
                    <input type="text" size="3" maxlength="2" name="invadr[oxuser__oxbirthdate][day]" value="[{if $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00"}][{$oxcmp_user->oxuser__oxbirthdate->value|date_format:"%d" }][{else}][{$invadr.oxuser__oxbirthdate.day }][{/if}]">&nbsp;&nbsp;
                    <input type="text" size="3" maxlength="2" name="invadr[oxuser__oxbirthdate][month]" value="[{if $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00" }][{$oxcmp_user->oxuser__oxbirthdate->value|date_format:"%m" }][{else}][{$invadr.oxuser__oxbirthdate.month }][{/if}]">&nbsp;&nbsp;
                    <input type="text" size="8" maxlength="4" name="invadr[oxuser__oxbirthdate][year]" value="[{if $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00" }][{$oxcmp_user->oxuser__oxbirthdate->value|date_format:"%Y" }][{else}][{$invadr.oxuser__oxbirthdate.year }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxbirthdate) }]<span class="req">*</span>[{/if}]
            </td>
            </tr>
            [{/if}]
            <tr class="th_sep">
                <th class="mid" colspan="2">[{ oxmultilang ident="REGISTER_SHIPPINGADDRESS" }]</th>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="showHideShippAddr">
                        [{if !$oView->showShipAddress()}]
                        <span class="btn"><input type="submit" class="btn" name="blshowshipaddress" value="[{ oxmultilang ident="REGISTER_DIFFERENTSHIPPINGADDRESS" }]"></span>
                        [{else}]
                        <span class="btn"><input type="submit" class="btn" name="blhideshipaddress" value="[{ oxmultilang ident="REGISTER_DISABLESHIPPINGADDRESS" }]"></span>
                        [{/if}]
                        <br><br>
                        <span class="note">[{ oxmultilang ident="REGISTER_NOTE" }]</span> [{ oxmultilang ident="REGISTER_DIFFERENTDELIVERYADDRESS" }]
                    </div>
                </td>
            </tr>
            [{if $oView->showShipAddress()}]
            [{assign var="delivadr" value=$oView->getDelAddress()}]
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_ADDRESSES" }]</label></td>
                <td>
                    <select name="oxaddressid" onchange="oxid.form.reload(this.value === '-1','order','user','');oxid.form.clear(this.value !== '-1','order',/oxaddress__/);">
                        <option value="-1" SELECTED>[{ oxmultilang ident="REGISTER_NEWADDRESSES" }]</option>
                        [{if $oxcmp_user }]
                            [{foreach from=$oxcmp_user->getUserAddresses() item=address}]
                            <option value="[{ $address->oxaddress__oxid->value }]" [{ if $address->selected}]SELECTED[{/if}]>[{ $address->oxaddress__oxfname->value }] [{ $address->oxaddress__oxlname->value }], [{ $address->oxaddress__oxcity->value }]</option>
                            [{/foreach}]
                        [{/if}]
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_TITLE2" }]</label></td>
                <td>
                    <select name="deladr[oxaddress__oxsal]" >
                        <option [{ if $delivadr->oxaddress__oxsal->value == "REGISTER_MR2"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="REGISTER_MR2" }]</option>
                        <option [{ if $delivadr->oxaddress__oxsal->value == "REGISTER_MRS2"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="REGISTER_MRS2" }]</option>
                    </select>
                    [{if isset($aMustFillFields.oxaddress__oxsal) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_FIRSTNAME2" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="deladr[oxaddress__oxfname]" value="[{ if $delivadr->oxaddress__oxfname->value }][{$delivadr->oxaddress__oxfname->value }][{else}][{$deladr.oxaddress__oxfname}][{/if}]"> [{if isset($aMustFillFields.oxaddress__oxfname) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_LASTNAME2" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="deladr[oxaddress__oxlname]" value="[{ if $delivadr->oxaddress__oxlname->value }][{$delivadr->oxaddress__oxlname->value }][{else}][{$deladr.oxaddress__oxlname}][{/if}]"> [{if isset($aMustFillFields.oxaddress__oxlname) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_COMPANY2" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="deladr[oxaddress__oxcompany]" value="[{if $delivadr->oxaddress__oxcompany->value }][{$delivadr->oxaddress__oxcompany->value }][{else}][{$deladr.oxaddress__oxcompany}][{/if}]"> [{if isset($aMustFillFields.oxaddress__oxcompany) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_STREET2" }]</label></td>
                <td>
                    <input type="text" size="28" maxlength="255" name="deladr[oxaddress__oxstreet]" value="[{if $delivadr->oxaddress__oxstreet->value}][{$delivadr->oxaddress__oxstreet->value}][{else}][{$deladr.oxaddress__oxstreet}][{/if}]">
                    <input type="text" size="5" maxlength="16" name="deladr[oxaddress__oxstreetnr]" value="[{if $delivadr->oxaddress__oxstreetnr->value }][{$delivadr->oxaddress__oxstreetnr->value }][{else}][{$deladr.oxaddress__oxstreetnr}][{/if}]">
                    [{if isset($aMustFillFields.oxaddress__oxstreet) || isset($aMustFillFields.oxaddress__oxstreetnr) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_PZLCITY2" }]</label></td>
                <td>
                    <input type="text" size="5" maxlength="16" name="deladr[oxaddress__oxzip]" value="[{if $delivadr->oxaddress__oxzip->value }][{$delivadr->oxaddress__oxzip->value }][{else}][{$deladr.oxaddress__oxzip}][{/if}]">
                    <input type="text" size="28" maxlength="255" name="deladr[oxaddress__oxcity]" value="[{if $delivadr->oxaddress__oxcity->value }][{$delivadr->oxaddress__oxcity->value }][{else}][{$deladr.oxaddress__oxcity}][{/if}]">
                    [{if isset($aMustFillFields.oxaddress__oxzip) || isset($aMustFillFields.oxaddress__oxcity) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_ADDITIONALINFO2" }]</label></td>
                <td><input type="text" size="37" maxlength="255" name="deladr[oxaddress__oxaddinfo]" value="[{if $delivadr->oxaddress__oxaddinfo->value }][{$delivadr->oxaddress__oxaddinfo->value }][{else}][{$deladr.oxaddress__oxaddinfo}][{/if}]"> [{if isset($aMustFillFields.oxaddress__oxaddinfo) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_COUNTRY2" }]</label></td>
                <td>
                    <select name="deladr[oxaddress__oxcountryid]" >
                        <option value="">-</option>
                        [{foreach from=$oView->getCountryList() item=country key=country_id}]
                        <option value="[{$country->oxcountry__oxid->value}]" [{ if $delivadr->oxaddress__oxcountry->value == $country->oxcountry__oxtitle->value}]selected[{elseif $deladr.oxaddress__oxcountryid == $country->oxcountry__oxid->value}]selected[{/if}]>[{$country->oxcountry__oxtitle->value}]</option>
                        [{/foreach}]
                    </select>
                    [{if isset($aMustFillFields.oxaddress__oxcountryid) }]<span class="req">*</span>[{/if}]
                </td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_PHONE2" }]</label></td>
                <td><input type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfon]" value="[{if $delivadr->oxaddress__oxfon->value }][{$delivadr->oxaddress__oxfon->value }][{else}][{$deladr.oxaddress__oxfon}][{/if}]"> [{if isset($aMustFillFields.oxaddress__oxfon) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            <tr>
                <td><label>[{ oxmultilang ident="REGISTER_FAX2" }]</label></td>
                <td><input type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfax]" value="[{ if $delivadr->oxaddress__oxfax->value }][{$delivadr->oxaddress__oxfax->value }][{else}][{$deladr.oxaddress__oxfax}][{/if}]"> [{if isset($aMustFillFields.oxaddress__oxfax) }]<span class="req">*</span>[{/if}]</td>
            </tr>
            [{/if}]
        </table>
    </div>
    <div class="bar prevnext">
        <div class="right"><input type="submit" value="[{ oxmultilang ident="REGISTER_SEND" }]"></div>
    </div>
</form>

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl" }]