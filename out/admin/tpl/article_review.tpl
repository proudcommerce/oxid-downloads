[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function EditThis( sID)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='article_main';
    oTransfer.submit();

    var oSearch = parent.list.document.getElementById("search");
    oSearch.actedit.value = 0;
    oSearch.oxid.value=sID;
    oSearch.submit();
}

//-->
</script>

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_review">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="article_review">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[article__oxid]" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="oxparentid" value="[{ $oxparentid }]">
<input type="hidden" name="editlanguage" value="[{ $editlanguage }]">


  <table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
    <tr height="10">
      <td></td><td></td>
    </tr>
    <tr>
      <td width="15"></td>
      <td valign="top" class="edittext">

        <select name="rev_oxid" size="20" class="editinput" style="width:160px;" onChange="Javascript:document.myedit.submit();">
        [{foreach from=$allreviews item=allitem}]
        <option value="[{ $allitem->oxreviews__oxid->value }]" [{ if $allitem->selected}]SELECTED[{/if}]>[{ $allitem->oxreviews__oxcreate|oxformdate }]</option>
        [{/foreach}]
        </select><br><br>
        <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="ARTICLE_REVIEW_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"">
        <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="ARTICLE_REVIEW_DELETE" }]" onClick="Javascript:document.myedit.fnc.value='delete'""><br>

      </td>
      <!-- Anfang rechte Seite -->
      <td valign="top" class="edittext" align="left" valign="top">
      [{ if $user }]
        <table>
          [{if $blShowActBox}]
          <tr>
            <td class="edittext">[{ oxmultilang ident="ARTICLE_REVIEW_ACTIVE" }] :</td>
            <td class="edittext"><input class="edittext" type="checkbox" name="editval[oxreviews__oxactive]" value='1' [{if $editreview->oxreviews__oxactive->value == 1}]checked[{/if}] [{ $readonly }]><br></td>
          </tr>
          [{/if}]
          <tr>
            <td class="edittext">[{ oxmultilang ident="ARTICLE_REVIEW_POSTEDFROM" }]</td>
            <td class="edittext">[{ $user->oxuser__oxfname->value}] [{ $user->oxuser__oxlname->value}]</td>
          </tr>
          <tr>
            <td class="edittext" valign="top">[{ oxmultilang ident="ARTICLE_REVIEW_TEXT" }]</td>
            <td class="edittext">
              <textarea class="editinput" cols="100" rows="15" wrap="VIRTUAL" name="editval[oxreviews__oxtext]">[{$editreview->oxreviews__oxtext->value}]</textarea><br>
            </td>
          </tr>
        </table>
      [{/if}]
      </td>
    <!-- Ende rechte Seite -->
    </tr>
  </table>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]