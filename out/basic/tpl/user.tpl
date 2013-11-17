[{assign var="template_title" value="USER_LOGINTITLE"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location=$template_title}]

<!-- ordering steps -->
[{include file="inc/steps_item.tpl" highlight=2 }]
[{assign var="_blshownoregopt" value=$oView->getShowNoRegOption()}]

  [{ if !$oxcmp_user && !$oView->getLoginOption() }]
    [{if $_blshownoregopt }]
      <div class="left">
          <strong class="useroptboxhead">[{ oxmultilang ident="USER_OPTION1" }]</strong>
          <div class="useroptbox">
              <b>[{ oxmultilang ident="USER_ORDERWITHOUTREGISTER1" }]</b><br><br>
              [{ oxmultilang ident="USER_ORDERWITHOUTREGISTER2" }]<br><br>
              <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                <div>
                    [{ $oViewConf->getHiddenSid() }]
                    [{ $oViewConf->getNavFormParams() }]
                    <input type="hidden" name="cl" value="user">
                    <input type="hidden" name="fnc" value="">
                    <input type="hidden" name="option" value="1">
                    <span class="btn"><input id="test_UsrOpt1" type="submit" name="send" value="[{ oxmultilang ident="USER_NEXT" }]" class="btn"></span>
                </div>
              </form>
          </div>
      </div>
    [{/if}]
      <div class="left">
          <strong class="useroptboxhead[{if !$_blshownoregopt }]big[{/if}]">[{if !$_blshownoregopt }][{ oxmultilang ident="USER_OPTION1" }][{else}][{ oxmultilang ident="USER_OPTION2" }][{/if}]</strong>
          <div class="useroptbox[{if !$_blshownoregopt }]big[{/if}]">
              <b>[{ oxmultilang ident="USER_ALREADYCUSTOMER" }]</b><br><br>
              [{ oxmultilang ident="USER_PLEASELOGIN" }]<br><br>
              [{foreach from=$Errors.user item=oEr key=key }]
                  <div class="err">[{ $oEr->getOxMessage()}]</div>
              [{/foreach}]
              <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                <div>
                    [{ $oViewConf->getHiddenSid() }]
                    [{ $oViewConf->getNavFormParams() }]
                    <input type="hidden" name="fnc" value="login_noredirect">
                    <input type="hidden" name="cl" value="user">
                    <input type="hidden" name="option" value="2">
                    <input type="hidden" name="lgn_cook" value="0">
                    <input type="hidden" name="CustomError" value='user'>
                    <span class="fs11"><b>[{ oxmultilang ident="USER_EMAIL" }]</b></span><br>
                    <input id="test_UsrOpt2_usr" type="text" name="lgn_usr" value="" size="25"><br><br>
                    <span class="fs11"><b>[{ oxmultilang ident="USER_PWD" }]</b></span><br>
                    <input id="test_UsrOpt2_pwd" type="password" name="lgn_pwd" value="" size="25"><br><br>
                    <span class="btn"><input id="test_UsrOpt2" type="submit" name="send" value="[{ oxmultilang ident="USER_LOGIN" }]" class="btn"></span><br><br>
                    <a id="test_UsrOpt2_forgotPwd" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=forgotpwd" }]" class="link">[{ oxmultilang ident="USER_FORGOTPWD" }]</a><br><br>
                    <span class="fs11"><b>[{ oxmultilang ident="USER_OPENID" }]</b></span><br>
                    <input id="test_UsrOpt2_openid" type="text" name="lgn_openid" value="" class="openid" size="21"><br><br>
                    <span class="btn"><input id="test_UsrOpt2OpenId" type="submit" name="send" value="[{ oxmultilang ident="USER_LOGIN" }]" class="btn"></span><br>
                 </div>
              </form>
          </div>
      </div>

      <div class="left">
          <strong class="useroptboxhead[{if !$_blshownoregopt }]big[{/if}]">[{if !$_blshownoregopt }][{ oxmultilang ident="USER_OPTION2" }][{else}][{ oxmultilang ident="USER_OPTION3" }][{/if}]</strong>
          <div class="useroptbox[{if !$_blshownoregopt }]big[{/if}]">
              <b>[{ oxmultilang ident="USER_OPENPERSONALACCOUNT1" }]</b><br><br>
              [{ oxmultilang ident="USER_OPENPERSONALACCOUNT2" }] [{ $oxcmp_shop->oxshops__oxname->value }] [{ oxmultilang ident="USER_OPENPERSONALACCOUNT3" }]<br>
              <span class="fs11">
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT4" }]<br>
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT5" }]<br>
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT6" }]<br>
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT7" }]<br>
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT8" }]<br>
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT9" }]<br>
                  [{ oxmultilang ident="USER_OPENPERSONALACCOUNT10" }]
              </span>
              <br><br>
              <form action="[{ $oViewConf->getSslSelfLink() }]" method="post">
                <div>
                    [{ $oViewConf->getHiddenSid() }]
                    [{ $oViewConf->getNavFormParams() }]
                    <input type="hidden" name="cl" value="user">
                    <input type="hidden" name="fnc" value="">
                    <input type="hidden" name="option" value="3">
                    <span class="btn"><input id="test_UsrOpt3" type="submit" name="send" value="[{ oxmultilang ident="USER_LOGIN2" }]" class="btn"></span>
                </div>
              </form>
          </div>
      </div>

  [{else}]
    [{assign var="currency" value=$oView->getActCurrency() }]
    [{assign var="aMustFillFields" value=$oView->getMustFillFields() }]
    <form action="[{ $oViewConf->getSslSelfLink() }]" name="order" method="post">
      <div>
          [{ $oViewConf->getHiddenSid() }]
          [{ $oViewConf->getNavFormParams() }]
          <input type="hidden" name="option" value="[{$oView->getLoginOption()}]">
          <input type="hidden" name="cl" value="user">
          <input type="hidden" name="CustomError" value='user'>
          [{if !$oxcmp_user->oxuser__oxpassword->value }]
            <input type="hidden" name="fnc" value="createuser">
          [{else}]
            <input type="hidden" name="fnc" value="changeuser">
            <input type="hidden" name="lgn_cook" value="0">
          [{/if}]
      </div>

      [{if $oView->isLowOrderPrice()}]
        <div class="bar prevnext order">
            <div class="minorderprice">[{ oxmultilang ident="BASKET_MINORDERPRICE" }] [{ $oView->getMinOrderPrice() }] [{ $currency->sign }]</div>
        </div>
      [{else}]
        <div class="bar prevnext order">
            <div class="right arrowright">
                <input id="test_UserNextStepTop" name="userform" type="submit" value="[{ oxmultilang ident="USER_NEXTSTEP" }]">
            </div>
        </div>
      [{/if}]


      [{include file="inc/error.tpl" Errorlist=$Errors.user}]

      [{if $oView->getLoginOption() == 3}]
        <strong class="boxhead">[{ oxmultilang ident="USER_LOGIN3" }]</strong>
        <div class="box info">
            [{ oxmultilang ident="USER_ENTEREMAILANDPWD" }]<br>
            [{ oxmultilang ident="USER_RECEIVECONFIRMATION" }]
            <div class="dot_sep"></div>
            <table class="form" width="90%">
              <colgroup>
                <col width="145">
              </colgroup>
              <tr>
                <td><label>[{ oxmultilang ident="USER_EMAILADDRESS" }]</label></td>
                <td><input id="test_lgn_usr" type="text" name="lgn_usr" value="[{if $lgn_usr}][{$lgn_usr}][{else}][{$oxcmp_user->oxuser__oxusername->value}][{/if}]" size="37">&nbsp;<span class="req">*</span></td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_PASSWORD" }]</label></td>
                <td><input id="test_lgn_pwd" type="password" name="lgn_pwd" value="[{$lgn_pwd}]" size="37">&nbsp;<span class="req">*</span></td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_CONFIRMPWD" }]</label></td>
                <td><input id="test_lgn_pwd2" type="password" name="lgn_pwd2" value="[{$lgn_pwd2}]" size="37">&nbsp;<span class="req">*</span></td>
              </tr>
            </table>
        </div>
      [{/if}]

      <strong class="boxhead">[{ oxmultilang ident="USER_SEND" }]</strong>
      <div class="box info">
          <b>[{ oxmultilang ident="USER_BILLINDADDRESS" }]</b> [{ oxmultilang ident="USER_COMPLETEALLMARKEDFIELDS" }]
          <div class="dot_sep"></div>

          <table class="form" width="90%">
            <colgroup>
                <col width="145">
            </colgroup>
            [{ if !$oxcmp_user->oxuser__oxpassword->value && $oView->getLoginOption() != 3}]
              <tr>
                <td><label>[{ oxmultilang ident="USER_EMAILADDRESS2" }]</label></td>
                <td>
                    <input id="test_lgn_usr" type="text" name="lgn_usr" value="[{if $lgn_usr}][{$lgn_usr}][{else}][{$oxcmp_user->oxuser__oxusername->value}][{/if}]" size="37">
                    <span class="req">*</span></td>
              </tr>
            [{/if}]
            <tr>
              <td><label>[{ oxmultilang ident="USER_TITLE" }]</label></td>
              <td>
                <select NAME="invadr[oxuser__oxsal]">
                  <option [{ if $oxcmp_user->oxuser__oxsal->value == "USER_MR"|oxmultilangassign or $invadr.oxuser__oxsal == "USER_MR"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="USER_MR" }]</option>
                  <option [{ if $oxcmp_user->oxuser__oxsal->value == "USER_MRS"|oxmultilangassign or $invadr.oxuser__oxsal == "USER_MRS"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="USER_MRS" }]</option>
                </select>
                [{if isset($aMustFillFields.oxuser__oxsal) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_FIRSTNAME" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="255" name="invadr[oxuser__oxfname]" value="[{if $oxcmp_user->oxuser__oxfname->value }][{$oxcmp_user->oxuser__oxfname->value }][{else}][{$invadr.oxuser__oxfname }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxfname) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_LASTNAME" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="255" name="invadr[oxuser__oxlname]" value="[{if $oxcmp_user->oxuser__oxlname->value }][{$oxcmp_user->oxuser__oxlname->value }][{else}][{$invadr.oxuser__oxlname }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxlname) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_COMPANY" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="255" name="invadr[oxuser__oxcompany]" value="[{if $oxcmp_user->oxuser__oxcompany->value }][{$oxcmp_user->oxuser__oxcompany->value }][{else}][{$invadr.oxuser__oxcompany }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxcompany) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_STREET" }]</label></td>
              <td>
                <input type="text" size="28" maxlength="255" name="invadr[oxuser__oxstreet]" value="[{if $oxcmp_user->oxuser__oxstreet->value }][{$oxcmp_user->oxuser__oxstreet->value }][{else}][{$invadr.oxuser__oxstreet }][{/if}]">
                <input type="text" size="5" maxlength="16" name="invadr[oxuser__oxstreetnr]" value="[{if $oxcmp_user->oxuser__oxstreetnr->value }][{$oxcmp_user->oxuser__oxstreetnr->value }][{else}][{$invadr.oxuser__oxstreetnr }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxstreet) || isset($aMustFillFields.oxuser__oxstreetnr) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_PLZANDCITY" }]</label></td>
              <td>
                <input type="text" size="5" maxlength="16" name="invadr[oxuser__oxzip]" value="[{if $oxcmp_user->oxuser__oxzip->value }][{$oxcmp_user->oxuser__oxzip->value }][{else}][{$invadr.oxuser__oxzip }][{/if}]">
                <input type="text" size="28" maxlength="255" name="invadr[oxuser__oxcity]" value="[{if $oxcmp_user->oxuser__oxcity->value }][{$oxcmp_user->oxuser__oxcity->value }][{else}][{$invadr.oxuser__oxcity }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxzip) || isset($aMustFillFields.oxuser__oxcity) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_VATID" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="255" name="invadr[oxuser__oxustid]" value="[{if $oxcmp_user->oxuser__oxustid->value }][{$oxcmp_user->oxuser__oxustid->value }][{else}][{$invadr.oxuser__oxustid }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxustid) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_ADDITIONALINFO" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="255" name="invadr[oxuser__oxaddinfo]" value="[{if $oxcmp_user->oxuser__oxaddinfo->value }][{$oxcmp_user->oxuser__oxaddinfo->value }][{else}][{$invadr.oxuser__oxaddinfo }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxaddinfo) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_COUNTRY" }]</label></td>
              <td>
                <select name="invadr[oxuser__oxcountryid]">
                  <option value="">-</option>
                  [{foreach from=$oView->getCountryList() item=country key=country_id}]
                    <option value="[{$country->oxcountry__oxid->value}]"[{ if $oxcmp_user->oxuser__oxcountryid->value == $country->oxcountry__oxid->value }] selected[{elseif $invadr.oxuser__oxcountryid == $country->oxcountry__oxid->value}] selected[{/if}]>[{$country->oxcountry__oxtitle->value}]</option>
                  [{/foreach}]
                </select>
                [{if isset($aMustFillFields.oxuser__oxcountryid) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_PHONE" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="128" name="invadr[oxuser__oxfon]" value="[{if $oxcmp_user->oxuser__oxfon->value }][{$oxcmp_user->oxuser__oxfon->value }][{else}][{$invadr.oxuser__oxfon }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxfon) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_FAX" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="128" name="invadr[oxuser__oxfax]" value="[{if $oxcmp_user->oxuser__oxfax->value }][{$oxcmp_user->oxuser__oxfax->value }][{else}][{$invadr.oxuser__oxfax }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxfax) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_MOBIL" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="64" name="invadr[oxuser__oxmobfon]" value="[{if $oxcmp_user->oxuser__oxmobfon->value }][{$oxcmp_user->oxuser__oxmobfon->value }][{else}][{$invadr.oxuser__oxmobfon }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxmobfon) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            <tr>
              <td><label>[{ oxmultilang ident="USER_PRIVATPHONE" }]</label></td>
              <td>
                <input type="text" size="37" maxlength="64" name="invadr[oxuser__oxprivfon]" value="[{if $oxcmp_user->oxuser__oxprivfon->value }][{$oxcmp_user->oxuser__oxprivfon->value }][{else}][{$invadr.oxuser__oxprivfon }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxprivfon) }]<span class="req">*</span>[{/if}]
              </td>
            </tr>
            [{if $oViewConf->showBirthdayFields() }]
            <tr>
              <td><label>[{ oxmultilang ident="USER_BIRTHDATE" }]</label></td>
              <td valign="top">
                <table><tr><td>
                <input type="text" size="3" maxlength="2" name="invadr[oxuser__oxbirthdate][day]" value="[{if $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00"}][{$oxcmp_user->oxuser__oxbirthdate->value|regex_replace:"/^([0-9]{4})[-]([0-9]{1,2})[-]/":"" }][{else}][{$invadr.oxuser__oxbirthdate.day }][{/if}]">&nbsp;&nbsp;
                <input type="text" size="3" maxlength="2" name="invadr[oxuser__oxbirthdate][month]" value="[{if $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00" }][{$oxcmp_user->oxuser__oxbirthdate->value|regex_replace:"/^([0-9]{4})[-]/":""|regex_replace:"/[-]([0-9]{1,2})$/":"" }][{else}][{$invadr.oxuser__oxbirthdate.month }][{/if}]">&nbsp;&nbsp;
                <input type="text" size="8" maxlength="4" name="invadr[oxuser__oxbirthdate][year]" value="[{if $oxcmp_user->oxuser__oxbirthdate->value && $oxcmp_user->oxuser__oxbirthdate->value != "0000-00-00" }][{$oxcmp_user->oxuser__oxbirthdate->value|regex_replace:"/[-]([0-9]{1,2})[-]([0-9]{1,2})$/":"" }][{else}][{$invadr.oxuser__oxbirthdate.year }][{/if}]">
                [{if isset($aMustFillFields.oxuser__oxbirthdate) }]<span class="req">*</span>[{/if}]</td>
                <td><span class="fs10">&nbsp;[{ oxmultilang ident="USER_BIRTHDAYMESSAGE" }]</span></td>
                </tr></table>
              </td>
            </tr>
            [{/if}]
            <tr>
              <td><label>[{ oxmultilang ident="USER_SUBSCRIBENEWSLETTER" }]</label></td>
              <td>
                <input type="hidden" name="blnewssubscribed" value="0">
                <input id="test_newsReg" type="checkbox" name="blnewssubscribed" value="1" [{if $oView->isNewsSubscribed()}]checked[{/if}]>
                <span class="fs10">[{ oxmultilang ident="USER_SUBSCRIBENEWSLETTER_MESSAGE" }]</span>
              </td>
            </tr>
            <tr class="td_sep">
              <td valign="top"><label>[{ oxmultilang ident="USER_YOURMESSAGE" }]</label></td>
              <td>
                [{ if !$oView->getOrderRemark()}]
                  [{assign var="order_remark" value="USER_MESSAGEHERE"|oxmultilangassign}]
                [{else}]
                  [{assign var="order_remark" value=$oView->getOrderRemark()}]
                [{/if}]
                <textarea cols="60" rows="7" name="order_remark">[{$order_remark}]</textarea>
              </td>
            </tr>

          </table>

          <div class="dot_sep"></div>

          <br />
          <b>[{ oxmultilang ident="USER_SHIPPINGADDRESS" }]</b>
          <div class="dot_sep"></div>

          [{if !$oView->showShipAddress()}]
            <span class="btn"><input type="submit" name="blshowshipaddress" value="[{ oxmultilang ident="USER_DIFFERENTSHIPPINGADDRESS" }]" class="btn"></span><br /><br />
          [{else}]
            <span class="btn"><input type="submit" name="blhideshipaddress" value="[{ oxmultilang ident="USER_DISABLESHIPPINGADDRESS" }]" class="btn"></span><br /><br />
          [{/if}]

          <div class="fs10 def_color_1"><span class="req">[{ oxmultilang ident="USER_NOTE" }]</span> [{ oxmultilang ident="USER_DIFFERENTDELIVERYADDRESS" }]</div>
          [{if $oView->showShipAddress()}]
            [{assign var="delivadr" value=$oView->getDelAddress()}]
            <table class="form" width="90%">
              <colgroup>
                <col width="145">
              </colgroup>

              <tr>
                <td><label>[{ oxmultilang ident="USER_ADDRESSES" }]</label></td>
                <td>
                  <select name="oxaddressid" onchange="oxid.form.reload(this.value === '-1','order','user','');oxid.form.clear(this.value !== '-1','order',/oxaddress__/);">
                    <option value="-1" SELECTED>[{ oxmultilang ident="USER_NEWADDRESS" }]</option>
                    [{if $oxcmp_user}]
                        [{foreach from=$oxcmp_user->getUserAddresses() item=address}]
                            [{if $address->oxaddress__oxid->value == -2}]
                              <option value="-2" [{ if $address->selected}]SELECTED[{/if}]>[{ oxmultilang ident="USER_USEBILLINGADDR" }]</option>
                            [{else}]
                              <option value="[{ $address->oxaddress__oxid->value }]" [{ if $address->selected}]SELECTED[{/if}]>[{ $address->oxaddress__oxfname->value }] [{ $address->oxaddress__oxlname->value }], [{ $address->oxaddress__oxcity->value }]</option>
                            [{/if}]
                        [{/foreach}]
                    [{/if}]
                  </select>
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_TITLE2" }]</label></td>
                <td>
                  <select name="deladr[oxaddress__oxsal]">
                    <option [{ if $delivadr->oxaddress__oxsal->value == "USER_MR2"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="USER_MR2" }]</option>
                    <option [{ if $delivadr->oxaddress__oxsal->value == "USER_MRS2"|oxmultilangassign}]SELECTED[{/if}]>[{ oxmultilang ident="USER_MRS2" }]</option>
                  </select>
                  [{if isset($aMustFillFields.oxaddress__oxsal) }]<span class="req">*</span>[{/if}]
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_NAME" }]</label></td>
                <td>
                  <input type="text" size="15" maxlength="255" name="deladr[oxaddress__oxfname]" value="[{ if $delivadr->oxaddress__oxfname->value }][{$delivadr->oxaddress__oxfname->value }][{else}][{$deladr.oxaddress__oxfname}][{/if}]">
                  <input type="text" size="18" maxlength="255" name="deladr[oxaddress__oxlname]" value="[{ if $delivadr->oxaddress__oxlname->value }][{$delivadr->oxaddress__oxlname->value }][{else}][{$deladr.oxaddress__oxlname}][{/if}]">
                  [{if isset($aMustFillFields.oxaddress__oxfname) || isset($aMustFillFields.oxaddress__oxlname) }]<span class="req">*</span>[{/if}]
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_COMPANY2" }]</label></td>
                <td>
                  <input type="text" size="37" maxlength="255" name="deladr[oxaddress__oxcompany]" value="[{if $delivadr->oxaddress__oxcompany->value }][{$delivadr->oxaddress__oxcompany->value }][{else}][{$deladr.oxaddress__oxcompany}][{/if}]">
                  [{if isset($aMustFillFields.oxaddress__oxcompany) }]<span class="req">*</span>[{/if}]
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_STREET2" }]</label></td>
                <td>
                  <input type="text" size="28" maxlength="255" name="deladr[oxaddress__oxstreet]" value="[{if $delivadr->oxaddress__oxstreet->value}][{$delivadr->oxaddress__oxstreet->value}][{else}][{$deladr.oxaddress__oxstreet}][{/if}]">
                  <input type="text" size="5" maxlength="16" name="deladr[oxaddress__oxstreetnr]" value="[{if $delivadr->oxaddress__oxstreetnr->value }][{$delivadr->oxaddress__oxstreetnr->value }][{else}][{$deladr.oxaddress__oxstreetnr}][{/if}]">
                  [{if isset($aMustFillFields.oxaddress__oxstreet) || isset($aMustFillFields.oxaddress__oxstreetnr) }]<span class="req">*</span>[{/if}]
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_PLZANDCITY2" }]</label></td>
                <td>
                  <input type="text" size="5" maxlength="16" name="deladr[oxaddress__oxzip]" value="[{if $delivadr->oxaddress__oxzip->value }][{$delivadr->oxaddress__oxzip->value }][{else}][{$deladr.oxaddress__oxzip}][{/if}]">
                  <input type="text" size="28" maxlength="255" name="deladr[oxaddress__oxcity]" value="[{if $delivadr->oxaddress__oxcity->value }][{$delivadr->oxaddress__oxcity->value }][{else}][{$deladr.oxaddress__oxcity}][{/if}]">
                  [{if isset($aMustFillFields.oxaddress__oxzip) || isset($aMustFillFields.oxaddress__oxcity) }]<span class="req">*</span>[{/if}]
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_ADDITIONALINFO2" }]</label></td>
                <td>
                  <input type="text" size="37" maxlength="255" name="deladr[oxaddress__oxaddinfo]" value="[{if $delivadr->oxaddress__oxaddinfo->value }][{$delivadr->oxaddress__oxaddinfo->value }][{else}][{$deladr.oxaddress__oxaddinfo}][{/if}]">
                  [{if isset($aMustFillFields.oxaddress__oxaddinfo) }]<span class="req">*</span>[{/if}]
                </td>
              </tr>
              <tr>
                <td><label>[{ oxmultilang ident="USER_COUNTRY2" }]</label></td>
                 <td>
                  <select name="deladr[oxaddress__oxcountryid]">
                    <option value="">-</option>
                    [{foreach from=$oView->getCountryList() item=country key=country_id}]
                      <option value="[{$country->oxcountry__oxid->value}]" [{ if $delivadr->oxaddress__oxcountryid->value == $country->oxcountry__oxid->value}]selected[{elseif $deladr.oxaddress__oxcountryid == $country->oxcountry__oxid->value}]selected[{/if}]>[{$country->oxcountry__oxtitle->value}]</option>
                    [{/foreach}]
                  </select>
                  [{if isset($aMustFillFields.oxaddress__oxcountryid) }]<span class="req">*</span>[{/if}]
                 </td>
              </tr>
              <tr>
                  <td><label>[{ oxmultilang ident="USER_PHONE2" }]</label></td>
                  <td>
                    <input type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfon]" value="[{if $delivadr->oxaddress__oxfon->value }][{$delivadr->oxaddress__oxfon->value }][{else}][{$deladr.oxaddress__oxfon}][{/if}]">
                    [{if isset($aMustFillFields.oxaddress__oxfon) }]<span class="req">*</span>[{/if}]
                  </td>
              </tr>
              <tr>
                  <td><label>[{ oxmultilang ident="USER_FAX2" }]</label></td>
                  <td>
                    <input type="text" size="37" maxlength="128" name="deladr[oxaddress__oxfax]" value="[{ if $delivadr->oxaddress__oxfax->value }][{$delivadr->oxaddress__oxfax->value }][{else}][{$deladr.oxaddress__oxfax}][{/if}]">
                    [{if isset($aMustFillFields.oxaddress__oxfax) }]<span class="req">*</span>[{/if}]
                  </td>
              </tr>
            </table>
          [{/if}]
      </div>

      [{if $oView->isLowOrderPrice()}]
        <div class="bar prevnext order">
          <div class="minorderprice">[{ oxmultilang ident="BASKET_MINORDERPRICE" }] [{ $oView->getMinOrderPrice() }] [{ $currency->sign }]</div>
        </div>
      [{else}]
        <div class="bar prevnext">
            <div class="right arrowright">
                <input id="test_UserNextStepBottom" name="userform" type="submit" value="[{ oxmultilang ident="USER_NEXTSTEP" }]">
            </div>
        </div>
      [{/if}]

    </form>
    &nbsp;

  [{/if}]


[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
