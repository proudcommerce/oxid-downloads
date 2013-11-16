<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <title>Look &amp; Feel</title>
    <style type="text/css" > @import url("[{ $ResourceUrl }]/setup.css");</style>
</head>
<body>

<script language=JavaScript src="[{ $ResourceUrl }]/picker/cp_picker.js"></script>
<script language=JavaScript >
  ColorPickerPath = "[{ $ResourceUrl }]/picker/";
</script>

<table width="100%"><tr><td valign="top" width="20%">

    <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td [{if $istab==1 }] class="tab_active" [{else}] class="tab_inactive" style="background-image: url([{ $shop->imagedir }]/whitedot.gif);"[{/if}] nowrap ><a href="[{$shop->selflink}]&cl=looknfeel&amp;istab=1">[{ oxmultilang ident="LOOKNFEEL_TAB_LOOKNFEEL" }]</a></td>
        <td [{if $istab==2 }] class="tab_active" [{else}] class="tab_inactive" style="background-image: url([{ $shop->imagedir }]/whitedot.gif);"[{/if}] nowrap ><a href="[{$shop->selflink}]&cl=looknfeel&amp;istab=2">[{ oxmultilang ident="LOOKNFEEL_TAB_COLORS" }]</a></td>
        <td [{if $istab==3 }] class="tab_active" [{else}] class="tab_inactive" style="background-image: url([{ $shop->imagedir }]/whitedot.gif);"[{/if}] nowrap ><a href="[{$shop->selflink}]&cl=looknfeel&amp;istab=3">[{ oxmultilang ident="LOOKNFEEL_TAB_EXTENDED }]</a></td>
        <td class="tab_line">&nbsp;</td>
    </tr>
    <tr><td colspan="4" class="tab_panel">

    <form name="myedit" id="myedit" action="[{ $shop->selflink }]" method="post" onSubmit="return chkInsert()" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
    [{ $shop->hiddensid }]
    <input type="hidden" name="cl" value="looknfeel">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="istab" value="[{ $istab }]">

      [{* ---===  Look&feel ===--- *}]

      [{if $istab==1 }]
        <b>[{ oxmultilang ident="LOOKNFEEL_COLOR_PROFILE" }]:</b><br><br>
        <select class="edittext" name='sTheme' style="width:100%">
          [{foreach from=$Themes item=TheneItem  key=ThemeID}]
            <option label="[{ oxmultilang ident=$TheneItem.title }]" value="[{ $ThemeID }]" [{if $ThemeID==$actTheme}]selected="selected"[{/if}]>[{ oxmultilang ident=$TheneItem.title }]</option>
          [{/foreach}]
        </select>

        <br><br><b>[{ oxmultilang ident="LOOKNFEEL_LOGO" }]:</b><br><br>
        <input type="hidden" name="sUploadedLogo" value="[{ $ActLogo }]" >
        <input class="edittext" type="file" style="width:100%" name="sLogo" >
        [{if $ActLogo }]
          <input style="padding:0px;margin:0px;" type="checkbox" name="blResetLogo" value="1">[{ oxmultilang ident="LOOKNFEEL_LOGO_RESET" }]<br><br>
        [{/if}]

        <br><br><b>[{ oxmultilang ident="LOOKNFEEL_VISIBLE_BLOCKS" }]:</b><br><br>
        <table cellspacing="0" cellpadding="0" border="0">
        [{foreach from=$Blocks item=BlockItem key=BlockID}]
          <tr>
            <td>[{ oxmultilang ident=$BlockItem.title }]</td>
            <td>
              <input type='hidden' name='aBlocks[[{ $BlockID }]]' value='0'>
              <input [{if $ActBlocks[$BlockID]==1 }]checked[{/if}] type='checkbox' name='aBlocks[[{ $BlockID }]]' value='1'>
            </td>
          </tr>
        [{/foreach}]
        </table>
      [{else}]
        <input type="hidden" name="sTheme" value="[{ $actTheme }]" >
        <input type="hidden" name="sUploadedLogo" value="[{ $actLogo }]" >
      [{/if}]

      [{* ---===  Colors ===--- *}]

      [{if $istab==2 }]
        <b>[{ oxmultilang ident="LOOKNFEEL_COLOR_PALETTE" }]:</b><br><br>
        <table width=100%>
        [{foreach from=$unyqeColors item=ColorItem key=i}]
          <tr>
            <td width="30%">
              <input id="attr_[{ $i }]field" name="aModAttributes[[{ $ColorItem.all_attributes }]]" class="edittext" size="9" type="text" value="[{ $ColorItem.value }]" >
            </td>
            <td width="70%" onClick="javascript:pickColor('attr_[{ $i }]','attr_[{ $i }]field');" style='cursor:pointer;'>
              <div title="[{ $ColorItem.all_attributes }]" onClick="javascript:pickColor('attr_[{ $i }]','attr_[{ $i }]field');" style='cursor:pointer;border:1px dotted black;background-color:[{ $ColorItem.value }];' id='attr_[{ $i }]'>&nbsp;&nbsp;&nbsp;</div>
            </td>
          </tr>
        [{/foreach}]
        </table>
      [{/if}]



      [{* ---===  Extended ===--- *}]

      [{if $istab==3 }]

        <table>

        [{assign var="prevGroup" value="" }]

        [{foreach from=$Attributes item=AttributeItem key=AttributeID}]

          [{if $prevGroup != $AttributeItem.group }]
            <tr><td colspan='3'><b>[{ oxmultilang ident=$AttributeItem.group }]</b></td></tr>
          [{/if}]

          <tr>
            <td>[{ oxmultilang ident=$AttributeItem.title }]</td>
              [{if $AttributeItem.type == "color" }]
              <td><input class="edittext" size="9" type="text" id="attr_[{ $AttributeID }]field" name="aAttributes[[{ $AttributeID }]]" value="[{ $ActAttributes[$AttributeID] }]"></td>
              <td onClick="javascript:pickColor('attr_[{ $i }]','attr_[{ $i }]field');" style="cursor:pointer;"><div onClick="javascript:pickColor('attr_[{ $AttributeID }]','attr_[{ $AttributeID }]field');" style="cursor:pointer;border:1px dotted black;background-color:[{ $ActAttributes[$AttributeID] }];" id='attr_[{ $AttributeID }]'>&nbsp;&nbsp;&nbsp;</div></td>
            [{else}]
              <td colspan="2"><input class="edittext" size="9" style="width:100%" type="text" id="attr_[{ $AttributeID }]field" name="aAttributes[[{ $AttributeID }]]" value="[{ $ActAttributes[$AttributeID] }]"></td>
            [{/if}]
          </tr>

          [{assign var="prevGroup" value=$AttributeItem.group }]

        [{/foreach}]

        </table>

      [{/if}]

      <hr size="1"><br>
      <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="LOOKNFEEL_BUTTON_PREVIEW" }]">
      <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="LOOKNFEEL_BUTTON_APPLY" }]" onClick="Javascript:document.myedit.fnc.value='save'">
    </form>

</td></tr>
</table>

</td><td width="80%" valign="top">
  <iframe name="preview" class="design_frame" frameborder="1" src="[{$shop->selflink}]&cl=looknfeel_preview" ></iframe>
</td></tr></table>

</body>
</html>
