<b>[{ oxmultilang ident="REPORT_TOP_VIEWED_PRODUCTS" }] :</b><br>
<br>
[{if $drawStat}]
<table class="report_searchstrings_table" cellpadding="0" cellspacing="0" width="800">
    <tr>
      <td class="report_searchstrings_td">
       <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
         <td colspan="[{$allCols}]" align="center">&nbsp;</td>
        </tr>
        <tr>
         <td colspan="[{$allCols}]" align="center"><b>[{ oxmultilang ident="REPORT_TOP_VIEWED_PRODUCTS_VIEWEDITEMS" }]</b></td>
        </tr>
        <tr>
         <td colspan="[{$allCols}]" align="center">&nbsp;</td>
        </tr>
        <tr>
         <td></td>
         [{foreach name=outer item=classe from=$classes}]
          [{foreach key=key item=curr_point from=$classe}]
          <td class="[{$curr_point}]">[{$key}]</td>
          [{/foreach}]
         [{/foreach}]
         <td class="report_searchstrings_scale_empty_right"></td>
        </tr>
        [{foreach name=outer item=percent from=$percents}]
         [{foreach key=key item=curr_point from=$percent}]
         <tr>
          <td class="report_searchstrings_scale" style="height: 20px;width: 150px;">&nbsp;[{ $curr_point.nm }]&nbsp;</td><td colspan="[{$cols}]"><img src="[{ $shop->basedir }]/out/admin/img/slide.jpg" height="10" width="[{$curr_point.pc}]%"></td><td width="5"></td>
         </tr>
         [{/foreach}]
        [{/foreach}]
        <tr>
         <td>&nbsp;</td><td>&nbsp;</td>
        </tr>
       </table>
      </td>
    </tr>
  </table>
[{else}]
<b>[{ oxmultilang ident="GENERAL_NODATA" }]</b>
[{/if}]
<br>
<br>
