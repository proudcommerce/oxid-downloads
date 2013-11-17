[{include file="popups/headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
    initAoc = function()
    {

        YAHOO.oxid.container1 = new YAHOO.oxid.aoc( 'container1',
                                                    [ [{ foreach from=$oxajax.container1 item=aItem key=iKey }]
                                                       [{$sSep}][{strip}]{ key:'_[{ $iKey }]', ident: [{if $aItem.4 }]true[{else}]false[{/if}]
                                                       [{if !$aItem.4 }],
                                                       label: '[{ oxmultilang ident="GENERAL_AJAX_SORT_"|cat:$aItem.0|oxupper }]',
                                                       visible: [{if $aItem.2 }]true[{else}]false[{/if}]
                                                       [{/if}]}
                                                      [{/strip}]
                                                      [{assign var="sSep" value=","}]
                                                      [{ /foreach }] ],
                                                    '[{ $oViewConf->getAjaxLink() }]cmpid=container1&container=attribute_category&synchoxid=[{ $oxid }]'
                                                    );

        [{assign var="sSep" value=""}]

        YAHOO.oxid.container2 = new YAHOO.oxid.aoc( 'container2',
                                                    [ [{ foreach from=$oxajax.container2 item=aItem key=iKey }]
                                                       [{$sSep}][{strip}]{ key:'_[{ $iKey }]', ident: [{if $aItem.4 }]true[{else}]false[{/if}]
                                                       [{if !$aItem.4 }],
                                                       label: '[{ oxmultilang ident="GENERAL_AJAX_SORT_"|cat:$aItem.0|oxupper }]',
                                                       visible: [{if $aItem.2 }]true[{else}]false[{/if}],
                                                       formatter: YAHOO.oxid.aoc.custFormatter
                                                       [{/if}]}
                                                      [{/strip}]
                                                      [{assign var="sSep" value=","}]
                                                      [{ /foreach }] ],
                                                    '[{ $oViewConf->getAjaxLink() }]cmpid=container2&container=attribute_category&oxid=[{ $oxid }]'
                                                    );

        YAHOO.oxid.container1.getDropAction = function()
        {
            return 'fnc=addcattoattr';
        }

        YAHOO.oxid.container2.getDropAction = function()
        {
            return 'fnc=removecatfromattr';
        }

        YAHOO.oxid.container3 = null;
        YAHOO.oxid.container2.subscribe( "dataReturnEvent", function( oParam ) {
            if ( YAHOO.oxid.container3.oContextMenu ) {
                YAHOO.oxid.container3.oContextMenu.destroy();
            }
            YAHOO.oxid.container3 = null;
            $('container3').innerHTML = '';
            $D.setStyle( $('orderup'), 'visibility', 'hidden' );
            $D.setStyle( $('orderdown'), 'visibility', 'hidden' );
        })
        //
        YAHOO.oxid.container2.subscribe( "rowSelectEvent", function( oParam )
        {
            var sOxid = oParam.record._oData._3;
            if ( YAHOO.oxid.container3 == null) {
                YAHOO.oxid.container3 = new YAHOO.oxid.aoc( 'container3',
                                                [ [{ foreach from=$oxajax.container3 item=aItem key=iKey }]
                                                   { key:'_[{ $iKey }]', ident: [{if $aItem.4 }]true[{else}]false[{/if}], sortable: false
                                                   [{if !$aItem.4 }],
                                                   label: '[{ oxmultilang ident="GENERAL_AJAX_SORT_"|cat:$aItem.0|oxupper }]',
                                                   visible: [{if $aItem.2 }]true[{else}]false[{/if}],
                                                   formatter: YAHOO.oxid.aoc.custFormatter
                                                   [{/if}]},
                                                  [{ /foreach }] ],
                                                '[{ $oViewConf->getAjaxLink() }]cmpid=container1&container=attribute_order&oxid='+sOxid,
                                                { selectionMode:'single' }
                                                )
                //
                YAHOO.oxid.container3._lastRecord = false;
                YAHOO.oxid.container3.subscribe( "dataReturnEvent", function()
                {
                    $D.setStyle( $('orderup'), 'visibility', 'hidden' );
                    $D.setStyle( $('orderdown'), 'visibility', 'hidden' );
                })
                YAHOO.oxid.container3.subscribe( "rowClickEvent", function( oParam )
                {
                    var sVisibility = 'hidden';
                    if ( YAHOO.oxid.container3.getSelectedRows().length ) {
                        sVisibility = '';
                    }
                    $D.setStyle($('orderup'), 'visibility', sVisibility );
                    $D.setStyle($('orderdown'), 'visibility', sVisibility );
                })
                YAHOO.oxid.container3.setOrderUp = function()
                {
                    var aSelRows = YAHOO.oxid.container3.getSelectedRows();
                    if ( aSelRows.length ) {
                        sOxid = YAHOO.oxid.container3.getRecord(aSelRows[0])._oData._2;
                        YAHOO.oxid.container3.modRequest = function( sRequest ) { return sRequest+'&fnc=setSorting&sortoxid='+sOxid+'&direction=up'; }
                        YAHOO.oxid.container3.getDataSource().flushCache();
                        YAHOO.oxid.container3.getPage( 0 );
                    }
                }
                YAHOO.oxid.container3.setOrderDown = function()
                {
                    var aSelRows = YAHOO.oxid.container3.getSelectedRows();
                    if ( aSelRows.length ) {
                        sOxid = YAHOO.oxid.container3.getRecord(aSelRows[0])._oData._2;
                        YAHOO.oxid.container3.modRequest = function( sRequest ) { return sRequest+'&fnc=setSorting&sortoxid='+sOxid+'&direction=down'; }
                        YAHOO.oxid.container3.getDataSource().flushCache();
                        YAHOO.oxid.container3.getPage( 0 );
                    }
                }

                $E.addListener( $('orderup'), "click", YAHOO.oxid.container3.setOrderUp, $('orderup') );
                $E.addListener( $('orderdown'), "click", YAHOO.oxid.container3.setOrderDown, $('orderdown') );
                YAHOO.oxid.container3._lastRecord = oParam.record;
            } else if ( !YAHOO.oxid.container3._lastRecord || YAHOO.oxid.container3._lastRecord != oParam.record ) {
                YAHOO.oxid.container3._lastRecord = oParam.record;
                YAHOO.oxid.container3.modRequest = function( sRequest ) { return sRequest+'&oxid='+sOxid; }
                YAHOO.oxid.container3.getPage( 0 );
            }
        })
    }
    $E.onDOMReady( initAoc );
</script>

    <table width="100%">
        <colgroup>
            <col span="3" width="33%" />
            <col width="1%" />
        </colgroup>
        <tr class="edittext">
            <td colspan="4">[{ oxmultilang ident="GENERAL_AJAX_DESCRIPTION" }]<br>[{ oxmultilang ident="GENERAL_FILTERING" }]<br /><br /></td>
        </tr>
        <tr class="edittext">
            <td align="center"><b>[{ oxmultilang ident="ATTRIBUTE_CATEGORY_ALLCATEGORY" }]</b></td>
            <td align="center"><b>[{ oxmultilang ident="ATTRIBUTE_CATEGORY_ATRCATEGORY" }]</b></td>
            <td align="center" valign="top">[{ oxmultilang ident="ATTRIBUTE_CATEGORY_ATRLIST" }]</td>
            <td></td>
        </tr>
        <tr>
            <td valign="top" id="container1"></td>
            <td valign="top" id="container2"></td>
            <td valign="top" id="container3"></td>
            <td valign="middle" rowspan="2">
            <input class="edittext" type="button" id="orderup" value="/\" style="visibility:hidden"><br><br>
            <input class="edittext" type="button" id="orderdown" value="\/" style="visibility:hidden">
            </td>
        </tr>
        <tr>
            <td><input type="button" class="edittext oxid-aoc-button" value="[{ oxmultilang ident="GENERAL_AJAX_ASSIGNALL" }]" id="container1_btn"></td>
            <td><input type="button" class="edittext oxid-aoc-button" value="[{ oxmultilang ident="GENERAL_AJAX_UNASSIGNALL" }]" id="container2_btn"></td>
            <td></td>
            <td></td>
        </tr>
    </table>

</body>
</html>

