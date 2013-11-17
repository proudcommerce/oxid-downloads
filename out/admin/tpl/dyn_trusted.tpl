[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE_1"|oxmultilangassign}]

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="1">
    <input type="hidden" name="cl" value="">
</form>
        
        <table cellspacing="0" cellpadding="0" border="0">
        <form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
        [{ $shop->hiddensid }]
        <input type="hidden" name="cl" value="dyn_trusted">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="editval[oxshops__oxid]" value="[{ $oxid }]">
    		[{ if $errorsaving }]
                <tr>
                  <td colspan="2">
                    [{ if $errorsaving eq 1 }]
                    <div class="error">[{ oxmultilang ident="DYN_TRUSTED_TRUSTEDSHOP_ERROR" }]</div>
                    [{/if}]
                  </td>
                </tr>
                [{ /if}]
            <tr>
             <td align="left" class="saveinnewlangtext">
                [{ oxmultilang ident="GENERAL_LANGUAGE" }]&nbsp;&nbsp;
             </td>
      		 <td valign="left" class="edittext">
      		  	[{ oxmultilang ident="DYN_TRUSTED_TRUSTEDSHOP" }]&nbsp;&nbsp;
      		 </td>
            </tr>
            [{foreach from=$alllang key=lang item=language}]
            <tr>
              <td align="left">
                [{ $language }]
              </td>
              <td valign="left" class="edittext">
      			 <input type=text class="editinput" style="width:270px" name="aShopID_TrustedShops[[{$lang}]]" value="[{$aShopID_TrustedShops.$lang}]" maxlength="40" [{ $readonly }]>
      		  </td>
    		</tr>
            [{/foreach}]
            <tr>
              <td class="edittext">
              </td>
              <td class="edittext"><br>
                <input type="submit" class="confinput" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'; return true;" [{ $readonly }]>
              </td>
            </tr>
            </form>
        </table>


[{include file="bottomnaviitem.tpl" }]
[{include file="bottomitem.tpl"}]
