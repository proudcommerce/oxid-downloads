[{assign var="template_title" value="SUGGEST_TITLE"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location=$template_title}]
[{assign var="product" value=$oView->getProduct()}]

<strong id="test_recommendHeader" class="boxhead">[{$template_title}]</strong>
[{ if !$success }]
[{assign var="editval" value=$oView->getSuggestData()}]
  <div class="box info" >
    [{ oxmultilang ident="SUGGEST_RECOMMENDPRODUCT" }]<br><br>
    <ul class="suggest">
        <li>[{ oxmultilang ident="SUGGEST_ENTERYOURADDRESSANDMESSAGE" }]</li>
        <li>[{ oxmultilang ident="SUGGEST_CLICKONSEND" }]</li>
    </ul>
    <br>
    <div class="dot_sep mid"></div>

    <form action="[{ $oViewConf->getSelfActionLink() }]" method="post">
      <div>
          [{ $oViewConf->getHiddenSid() }]
          [{ $oViewConf->getNavFormParams() }]
          <input type="hidden" name="fnc" value="send">
          <input type="hidden" name="cl" value="suggest">
          <input type="hidden" name="anid" value="[{ $product->oxarticles__oxnid->value }]">
          <input type="hidden" name="CustomError" value='suggest'>
          <table>
            <tr>
              <td><b>[{ oxmultilang ident="SUGGEST_CARDTO" }]</b></td>
              <td ></td>
            </tr>
            <tr>
              <td>[{ oxmultilang ident="SUGGEST_RECIPIENTNAME" }]</td>
              <td ><input type="text" name="editval[rec_name]" size=73 maxlength=73 value="[{$editval->rec_name}]" ></td>
            </tr>
            <tr>
              <td>[{ oxmultilang ident="SUGGEST_RECIPIENTEMAIL" }]</td>
              <td><input type="text" name="editval[rec_email]" size=73 maxlength=73 value="[{$editval->rec_email}]" ></td>
            </tr>
            <tr>
              <td><br></td>
              <td><br></td>
            </tr>
            <tr>
              <td><b>[{ oxmultilang ident="SUGGEST_FROM" }]</b></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>[{ oxmultilang ident="SUGGEST_SENDERNAME" }]</td>
              <td><input type="text" name="editval[send_name]" size=73 maxlength=73 value="[{$editval->send_name}]" ></td>
            </tr>
            <tr>
              <td>[{ oxmultilang ident="SUGGEST_SENDEREMAIL" }]</td>
              <td><input type="text" name="editval[send_email]" size=73 maxlength=73 value="[{$editval->send_email}]" ></td>
            </tr>
            <tr>
              <td>[{ oxmultilang ident="SUGGEST_CAPTION" }]</td>
              <td><input type="text" name="editval[send_subject]" size=73 maxlength=73 value="[{if $editval->send_subject}][{$editval->send_subject}][{else}][{ oxmultilang ident="SUGGEST_SUBJECT" }] [{ $product->oxarticles__oxtitle->value|strip_tags }][{/if}]" ></td>
            </tr>
            <tr>
              <td valign="top">[{ oxmultilang ident="SUGGEST_YOURMESSAGE" }]</td>
              <td>
                <textarea cols="70" rows="8" name="editval[send_message]" >[{if $editval->send_message}][{$editval->send_message}][{else}][{ oxmultilang ident="SUGGEST_MESSAGE1" }] [{ $oxcmp_shop->oxshops__oxname->value }] [{ oxmultilang ident="SUGGEST_MESSAGE2" }][{/if}]</textarea>
              </td>
            </tr>
            <tr>
              <td></td>
              <td align="right"><span class="btn"><input  type="submit" value="[{ oxmultilang ident="SUGGEST_SEND" }]" class="btn"></span></td>
            </tr>
        </table>
      </div>
    </form>
    <div class="dot_sep mid"></div>
    [{ oxmultilang ident="SUGGEST_ABOUTDATAPROTECTION" }]<br>
  </div>
[{/if}]

<div class="clear_left">
    &nbsp;
</div>


[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]
