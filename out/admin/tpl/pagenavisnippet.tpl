[{if $pagenavi}]
<tr>
<td class="pagination" colspan="[{$colspan|default:"2"}]">
  <div class="r1">
    <div class="b1">

    <table cellspacing="0" cellpadding="0" border="0" width="100%">
      <tr>
        <td id="nav.site" class="pagenavigation" align="left" width="33%">
            [{ oxmultilang ident="NAVIGATION_PAGE" }] [{ $pagenavi->actpage}] / [{ $pagenavi->pages}]</td>
        </td>
        <td class="pagenavigation" height="22" align="center" width="33%">
           [{foreach key=iPage from=$pagenavi->changePage item=page}]
             <a id="nav.page.[{$iPage}]" class="pagenavigation[{if $iPage == $pagenavi->actpage }] pagenavigationactive[{/if}]" href="[{ $shop->selflink }]&cl=[{ $shop->cl }]&amp;oxid=[{ $oxid }]&amp;jumppage=[{$iPage}]&amp;sort=[{ $sort }]&amp;actedit=[{ $actedit }]&amp;language=[{ $actlang }]&amp;editlanguage=[{ $actlang }][{ $whereparam }]&amp;folder=[{$folder}]&amp;pwrsearchfld=[{$pwrsearchfld}]">[{$iPage}]</a>
           [{/foreach}]
        </td>
        <td class="pagenavigation" align="right" width="33%">
          <a id="nav.first" class="pagenavigation" href="[{ $shop->selflink }]&cl=[{ $shop->cl }]&amp;oxid=[{ $oxid }]&amp;jumppage=1&amp;sort=[{ $sort }]&amp;actedit=[{ $actedit }]&amp;language=[{ $actlang }]&amp;editlanguage=[{ $actlang }][{ $whereparam }]&amp;folder=[{$folder}]&amp;pwrsearchfld=[{$pwrsearchfld}]">[{ oxmultilang ident="GENERAL_LIST_FIRST" }]</a>
          <a id="nav.prev" class="pagenavigation" href="[{ $shop->selflink }]&cl=[{ $shop->cl }]&amp;oxid=[{ $oxid }]&amp;jumppage=[{if $pagenavi->actpage-1 > 0}][{$pagenavi->actpage-1 > 0}][{else}]1[{/if}]&amp;sort=[{ $sort }]&amp;actedit=[{ $actedit }]&amp;language=[{ $actlang }]&amp;editlanguage=[{ $actlang }][{ $whereparam }]&amp;folder=[{$folder}]&amp;pwrsearchfld=[{$pwrsearchfld}]">[{ oxmultilang ident="GENERAL_LIST_PREV" }]</a>
          <a id="nav.next" class="pagenavigation" href="[{ $shop->selflink }]&cl=[{ $shop->cl }]&amp;oxid=[{ $oxid }]&amp;jumppage=[{if $pagenavi->actpage+1 > $pagenavi->pages}][{$pagenavi->actpage}][{else}][{$pagenavi->actpage+1}][{/if}]&amp;sort=[{ $sort }]&amp;actedit=[{ $actedit }]&amp;language=[{ $actlang }]&amp;editlanguage=[{ $actlang }][{ $whereparam }]&amp;folder=[{$folder}]&amp;pwrsearchfld=[{$pwrsearchfld}]">[{ oxmultilang ident="GENERAL_LIST_NEXT" }]</a>
          <a id="nav.last" class="pagenavigation" href="[{ $shop->selflink }]&cl=[{ $shop->cl }]&amp;oxid=[{ $oxid }]&amp;jumppage=[{$pagenavi->pages}]&amp;sort=[{ $sort }]&amp;actedit=[{ $actedit }]&amp;language=[{ $actlang }]&amp;editlanguage=[{ $actlang }][{ $whereparam }]&amp;folder=[{$folder}]&amp;pwrsearchfld=[{$pwrsearchfld}]">[{ oxmultilang ident="GENERAL_LIST_LAST" }]</a>
        </td>
      </tr>
    </table>
    </div>
  </div>
</td>
</tr>
[{/if}]
