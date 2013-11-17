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


<form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="actions_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[oxactions__oxid]" value="[{ $oxid }]">
<input type="hidden" name="sorting" value="">
<input type="hidden" name="stable" value="">
<input type="hidden" name="starget" value="">

<table cellspacing="0" cellpadding="0" border="0" width="98%">

<tr>
    <td valign="top" class="edittext">
      [{ if $oxid != "-1"}]
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
          <td class="edittext" colspan="2">
            <br />
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
          [{ oxmultilang ident="GENERAL_ACTIVFROMTILL" }]&nbsp;
          </td>
          <td class="edittext">
          [{ oxmultilang ident="GENERAL_FROM" }]&nbsp;<input type="text" class="editinput" size="27" name="editval[oxactions__oxactivefrom]" value="[{$edit->oxactions__oxactivefrom|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]><br>
          [{ oxmultilang ident="GENERAL_TILL" }]&nbsp;&nbsp;<input type="text" class="editinput" size="27" name="editval[oxactions__oxactiveto]" value="[{$edit->oxactions__oxactiveto|oxformdate}]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]>
          [{ oxinputhelp ident="HELP_GENERAL_ACTIVFROMTILL" }]
          </td>
        </tr>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }] [{ $disableSharedEdit }]><br>
            </td>
        </tr>
        </table>
      [{ else }]
        [{ oxmultilang ident="ACTIONS_MAIN_NOTSELECTED" }]
      [{ /if}]
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left" width="50%">
    [{ if $oxid != "-1"}]

        <input type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNARTICLES" }]" class="edittext" onclick="JavaScript:showDialog('?cl=actions_main&aoc=1&oxid=[{ $oxid }]');" [{ $readonly }]>

    [{ /if}]
    </td>
    <!-- Ende rechte Seite -->

    </tr>
</table>


</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
