[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function loadLang(obj)
{
    var langvar = document.getElementById("agblang");
    if (langvar != null )
        langvar.value = obj.value;
    document.myedit.submit();
}
//-->
</script>

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="shop_agb">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<table cellspacing="0" cellpadding="0" border="0" style="width:99%;height:100%;">
  <tr>
    <td width="100%" align="center" valign="top" bgcolor="#E7EAED" style="border : 1px #000000; border-style : none none solid none;">
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%;height:100%;padding-bottom:20px;padding-top:20px;padding-left:20px;padding-right:40px;">
        <form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post" onSubmit="wp_submit_editors()">
        [{ $oViewConf->getHiddenSid() }]
        <input type="hidden" name="cl" value="shop_agb">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{ $oxid }]">
        <input type="hidden" name="voxid" value="[{ $oxid }]">
        <input type="hidden" name="editval[oxshops__oxid]" value="[{ $oxid }]">
        <input type="hidden" name="agblang" value="[{$agblang}]">
        <tr>
          <td valign="top" class="edittext">
            <table cellspacing="0" cellpadding="0" border="0" style="width:100%;height:100%;">
              <tr>
                <td valign="top" class="edittext">
                [{if $languages}]<b>[{ oxmultilang ident="GENERAL_LANGUAGE" }]</b>
                <select name="agblang" class="editinput" onchange="Javascript:loadLang(this)" [{ $readonly }]>
                  [{foreach key=key item=item from=$languages}]
                    <option value="[{$key}]"[{if $agblang == $key}] SELECTED[{/if}]>[{$item->name}]</option>
                  [{/foreach}]
                </select>
                [{/if}]
                </td>
              </tr>
              <tr>
                <td valign="top" class="edittext" align="left" style="width:100%;height:99%;">
                  <table id="editorframe" style="width:100%;height:99%;" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td valign="top" rowspan="2"><img src="[{$oViewConf->getImageUrl()}]/whitedot.gif" id="editorheight" width="0" height="100%"></td>
                      <td valign="top"><img src="[{$oViewConf->getImageUrl()}]/whitedot.gif" id="editorwidth" width="100%" height="0"></td>
                    </tr>
                    <tr>
                      <td valign="top">
                      [{ $editor }]
                      </td>
                    </tr>
                   <tr>
                     <td></td>
                     <td>
                     </td>
                   </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td valign="top" class="edittext"><br>
                <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }]>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  </form>
[{include file="bottomnaviitem.tpl"}]
</table>
[{include file="bottomitem.tpl"}]
