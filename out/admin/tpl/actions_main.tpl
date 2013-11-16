[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]



<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="actions_main">
</form>


<form name="myedit" id="myedit" onSubmit="copyLongDesc( 'oxactions__oxlongdesc' );" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="actions_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxactions__oxid]" value="[{ $oxid }]">
<input type="hidden" name="sorting" value="">
<input type="hidden" name="stable" value="">
<input type="hidden" name="starget" value="">
<input type="hidden" name="editval[oxactions__oxlongdesc]" value="">

<table cellspacing="0" cellpadding="0" border="0" width="98%">

<tr>
    <td valign="top" class="edittext">
        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext" width="120">
            [{ oxmultilang ident="GENERAL_NAME" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="32" maxlength="[{$edit->oxactions__oxtitle->fldmax_length}]" name="editval[oxactions__oxtitle]" value="[{$edit->oxactions__oxtitle->value}]" [{ $readonly }] [{ $disableSharedEdit }]>
            [{ oxinputhelp ident="HELP_GENERAL_NAME" }]
            </td>
        </tr>

        <tr>
          <td class="edittext" width="120">
            [{ oxmultilang ident="GENERAL_ACTIVE" }]
          </td>
          <td class="edittext">
            <input class="edittext" type="checkbox" name="editval[oxactions__oxactive]" value='1' [{if $edit->oxactions__oxactive->value == 1}]checked[{/if}] [{ $readonly }]>
            [{ oxinputhelp ident="HELP_GENERAL_ACTIVE" }]
          </td>
        </tr>
        <tr>
          <td class="edittext">
          [{ if $edit->oxactions__oxtype->value < 2 }][{ oxmultilang ident="GENERAL_ACTIVFROMTILL" }][{/if}]&nbsp;
          </td>
          <td class="edittext">
          <input type="text" class="editinput" size="27" name="editval[oxactions__oxactivefrom]" value="[{$edit->oxactions__oxactivefrom|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]> ([{ oxmultilang ident="GENERAL_FROM" }])<br>
          <input type="text" class="editinput" size="27" name="editval[oxactions__oxactiveto]" value="[{$edit->oxactions__oxactiveto|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]> ([{ oxmultilang ident="GENERAL_TILL" }])
          [{ if $edit->oxactions__oxtype->value < 2 }][{ oxinputhelp ident="HELP_GENERAL_ACTIVFROMTILL" }][{/if}]
          </td>
        </tr>
        [{ if $oxid == "-1" }]
        <tr>
            <td class="edittext">
          [{ oxmultilang ident="GENERAL_TYPE" }]&nbsp;
            </td>
          <td class="edittext">
            <select class="editinput" name="editval[oxactions__oxtype]">
              <option value="1">[{ oxmultilang ident="PROMOTIONS_MAIN_TYPE_ACTION" }]</option>
              <option value="2">[{ oxmultilang ident="PROMOTIONS_MAIN_TYPE_PROMO" }]</option>
            </select>
          </td>
        </tr>
        [{ /if}]
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
                [{include file="language_edit.tpl"}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }] [{ $disableSharedEdit }]><br><br>


            [{ if $oxid != "-1"}]

                [{ if $edit->oxactions__oxtype->value < 2 }]
                <input type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNARTICLES" }]" class="edittext" onclick="JavaScript:showDialog('&cl=actions_main&aoc=1&oxid=[{ $oxid }]');" [{ $readonly }]>
                [{else}]
                <input type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNGROUPS" }]" class="edittext" onclick="JavaScript:showDialog('&cl=actions_main&oxscpromotionsaoc=2&oxid=[{ $oxid }]');" [{ $readonly }]>
                [{/if}]

            [{ /if}]

            </td>
        </tr>
        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" style="width:100%;padding-left:5px;padding-bottom:10px;">
      [{ $editor }]
    </td>
    <!-- Ende rechte Seite -->

    </tr>
</table>


</form>


</div>

<!-- START new promotion button -->
<div class="actions">
[{strip}]

  <ul>
    <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.new" href="#" onClick="Javascript:top.oxid.admin.editThis( -1 );return false" target="edit">[{ oxmultilang ident="TOOLTIPS_NEWPROMOTION" }]</a> |</li>
    [{include file="bottomnavicustom.tpl"}]

    [{ if $sHelpURL }]
    [{* HELP *}]
    <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.help" href="[{ $sHelpURL }]/[{ $shop->cl|oxlower }].html" OnClick="window.open('[{ $sHelpURL }]/[{ $shop->cl|lower }].html','OXID_Help','width=800,height=600,resizable=no,scrollbars=yes');return false;">[{ oxmultilang ident="TOOLTIPS_OPENHELP" }]</a></li>
    [{/if}]
  </ul>
[{/strip}]
</div>

<!-- END new promotion button -->

[{include file="bottomitem.tpl"}]