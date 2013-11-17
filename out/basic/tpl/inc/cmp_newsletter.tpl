<form action="[{ $oViewConf->getSelfActionLink() }]" method="post">
  <div>
      [{ $oViewConf->getHiddenSid() }]
      <input type="hidden" name="fnc" value="fill">
      <input type="hidden" name="cl" value="newsletter">
      [{if $oView->getProduct()}]
          [{assign var="product" value=$oView->getProduct() }]
          <input type="hidden" name="anid" value="[{ $product->oxarticles__oxnid->value }]">
      [{/if}]
      <table width="100%" class="form">
        <colgroup>
          <col width="30%">
          <col width="70%">
        </colgroup>
        <tr>
          <td><label>[{ oxmultilang ident="INC_CMP_NEWSLETTER_EMAIL" }]</label></td>
          <td><input id="test_RightNewsLetterUsername" type="text" name="editval[oxuser__oxusername]" value="" class="fullsize"></td>
        </tr>
        <tr>
          <td></td>
          <td><span class="btn"><input id="test_RightNewsLetterSubmit" type="submit" name="send" value="[{ oxmultilang ident="INC_CMP_NEWSLETTER_SUBSCRIBE" }]" class="btn"></span></td>
        </tr>
      </table>
   </div>
</form>
