<div class="dropDown" id="selectlist_[{$iKey}]">

    <p class="selectorLabel underlined[{if $editable === false}] oxdisabled[{/if}]">
        <label>[{$oSelectionList->getLabel()}]:</label>
        [{assign var="oActiveSelection" value=$oSelectionList->getActiveSelection()}]
        [{if $oActiveSelection}]
            <span>[{$oActiveSelection->getName()}]</span>
        [{elseif !$blHideDefault}]
            <span>[{ oxmultilang ident="WIDGET_PRODUCT_ATTRIBUTES_PLEASECHOOSE"}]</span>
        [{/if}]
    </p>

    [{if $editable !== false}]
        <input type="hidden" name="[{$sFieldName|default:"varselid"}][[{$iKey}]]" value="[{if $oActiveSelection }][{$oActiveSelection->getValue()}][{/if}]">
        <ul class="drop [{$sSelType|default:"vardrop"}] FXgradGreyLight shadow">
            [{if $oActiveSelection && !$blHideDefault}]
                <li><a rel="" href="#">[{ oxmultilang ident="WIDGET_PRODUCT_ATTRIBUTES_PLEASECHOOSE" }]</a></li>
            [{/if}]
            [{foreach from=$oSelectionList->getSelections() item=oSelection}]
                <li class="[{if $oSelection->isDisabled()}]oxdisabled disabled[{/if}]">
                    <a rel="[{$oSelection->getValue()}]" href="[{$oSelection->getLink()}]" class="[{if $oSelection->isActive()}]selected[{/if}]">[{$oSelection->getName()}]</a>
                </li>
            [{/foreach}]
        </ul>
    [{/if}]

</div>