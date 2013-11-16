[{assign var="listType" value=$oView->getListDisplayType()}]
[{assign var="_additionalParams" value=$oView->getAdditionalParams()}]

[{if $oView->canSelectDisplayType()}]
    <div class="dropDown" id="viewOptions">
        <p>
            <label>[{oxmultilang ident="view"}]:</label>
            <span>[{oxmultilang ident=$listType}]</span>
        </p>
        <ul class="drop FXgradGreyLight shadow">
            <li><a href="[{$oView->getLink()|oxaddparams:"ldtype=infogrid&amp;pgNr=0&amp;$_additionalParams"}]" [{if $listType eq 'infogrid' }]class="selected" [{/if}]>[{oxmultilang ident="infogrid"}]</a></li>
            <li><a href="[{$oView->getLink()|oxaddparams:"ldtype=grid&amp;pgNr=0&amp;$_additionalParams"}]" [{if $listType eq 'grid' }]class="selected" [{/if}]>[{oxmultilang ident="grid"}]</a></li>
            <li><a href="[{$oView->getLink()|oxaddparams:"ldtype=line&amp;pgNr=0&amp;$_additionalParams"}]" [{if $listType eq 'line' }]class="selected" [{/if}]>[{oxmultilang ident="line"}]</a></li>
        </ul>
    </div>
[{/if}]