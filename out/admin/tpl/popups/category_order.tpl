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
                                                    '[{ $oViewConf->getAjaxLink() }]cmpid=container1&container=category_order&synchoxid=[{ $oxid }]'
                                                    );

        [{assign var="sSep" value=""}]

        YAHOO.oxid.container2 = new YAHOO.oxid.aoc( 'container2',
                                                    [ [{ foreach from=$oxajax.container2 item=aItem key=iKey }]
                                                       [{$sSep}][{strip}]{ key:'_[{ $iKey }]', ident: [{if $aItem.4 }]true[{else}]false[{/if}]
                                                       [{if !$aItem.4 }],
                                                       label: '[{ oxmultilang ident="GENERAL_AJAX_SORT_"|cat:$aItem.0|oxupper }]',
                                                       visible: [{if $aItem.2 }]true[{else}]false[{/if}],
                                                       sortable: false
                                                       [{/if}]}
                                                      [{/strip}]
                                                      [{assign var="sSep" value=","}]
                                                      [{ /foreach }] ],
                                                    '[{ $oViewConf->getAjaxLink() }]cmpid=container2&container=category_order&oxid=[{ $oxid }]'
                                                    );
        // disabling filters for second container
        if ( YAHOO.oxid.container2._aFilters ) {
            for ( var i=0; i < YAHOO.oxid.container2._aFilters.length; i++ ) {
                YAHOO.oxid.container2._aFilters[i].disabled = true;
            }
        }

        YAHOO.oxid.container1.getDropAction = function()
        {
            return 'fnc=addcatorderarticle';
        }
        YAHOO.oxid.container2.getDropAction = function()
        {
            return 'fnc=removecatorderarticle';
        }

        YAHOO.oxid.container1.onSuccessCalback = function( oResponse )
        {
            if ( oResponse.responseText == "0" ) {
                $('saveBtn').disabled = false;
            } else {
                $('saveBtn').disabled = true;
            }
        }
        YAHOO.oxid.container2.onSuccessCalback = function( oResponse )
        {
            if ( oResponse.responseText == "0" ) {
                $('saveBtn').disabled = false;
            } else {
                $('saveBtn').disabled = true;
            }
        }
        YAHOO.oxid.container2.onOrder = function()
        {
            YAHOO.oxid.container1.getDataSource().flushCache();
            YAHOO.oxid.container1.getPage( 0 );
            YAHOO.oxid.container2.getDataSource().flushCache();
            YAHOO.oxid.container2.getPage( 0 );
        }
        YAHOO.oxid.container2.onFailure = function() { /* currently does nothing */}
        YAHOO.oxid.container2.saveOrder = function( oEvt )
        {
            var callback = {
                success: YAHOO.oxid.container2.onOrder,
                failure: YAHOO.oxid.container2.onFailure,
                scope:   YAHOO.oxid.container2
            };
            YAHOO.util.Connect.asyncRequest( 'GET', '[{ $oViewConf->getAjaxLink() }]&cmpid=container2&container=category_order&fnc=saveneworder&oxid=[{ $oxid }]&aoc=1', callback );
        };
        YAHOO.oxid.container2.deleteOrder = function( oEvt )
        {
            var callback = {
                success: YAHOO.oxid.container2.onOrder,
                failure: YAHOO.oxid.container2.onFailure,
                scope:   YAHOO.oxid.container2
            };

            YAHOO.util.Connect.asyncRequest( 'GET', '[{ $oViewConf->getAjaxLink() }]&cmpid=container2&container=category_order&fnc=remneworder&oxid=[{ $oxid }]', callback );
        };
        // subscribint event listeners on buttons
        $E.addListener( $('saveBtn'), "click", YAHOO.oxid.container2.saveOrder, $('saveBtn') );
        $E.addListener( $('deleteBtn'), "click", YAHOO.oxid.container2.deleteOrder, $('deleteBtn') );
    }
    $E.onDOMReady( initAoc );
</script>

    <table width="100%">
        <colgroup>
            <col span="2" width="50%" />
        </colgroup>
        <tr class="edittext">
            <td colspan="2">[{ oxmultilang ident="GENERAL_AJAX_DESCRIPTION" }]<br>[{ oxmultilang ident="GENERAL_FILTERING" }]<br /><br /></td>
        </tr>
        <tr class="edittext">
            <td align="center"><b>[{ oxmultilang ident="CATEGORY_ORDER_ACTSORT" }]</b></td>
            <td align="center"><b>[{ oxmultilang ident="CATEGORY_ORDER_NEWSORT" }]</b></td>
        </tr>
        <tr>
            <td valign="top" id="container1"></td>
            <td valign="top" id="container2"></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td align="right">
              <input type="button" id="saveBtn" class="edittext" disabled value="[{ oxmultilang ident="CATEGORY_ORDER_NEWSORTSAVE" }]" [{$readonly}]>
              <input type="button" id="deleteBtn" class="edittext" value="[{ oxmultilang ident="CATEGORY_ORDER_DELETESORT" }]" [{$readonly}]>
            </td>
        </tr>
    </table>

</body>
</html>