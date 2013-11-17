[{include file="headitem.tpl" title="SYSREQ_MAIN_TITLE"|oxmultilangassign}]

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<ul class="req">
    <h3>[{ oxmultilang ident="SYSREQ_DESCRIPTION_REQ" }]:</h3>
    [{foreach from=$aInfo item=aModules key=sGroupName}]
    <li class='group'>[{ oxmultilang ident="SYSREQ_"|cat:$sGroupName|oxupper }]
        [{foreach from=$aModules item=iModuleState key=sModule}]
            <ul>
                [{assign var="class" value=$oView->getModuleClass($iModuleState)}]
                <li id="[{$sModule}]" class="[{ $class }]">[{ oxmultilang ident="SYSREQ_"|cat:$sModule|oxupper }]</li>
            </ul>
        [{/foreach}]
    </li>
    [{/foreach}]
    <li class="clear"></li>
</ul>

[{if $aCollations}]
    <ul class="req">
        <h3>[{ oxmultilang ident="SYSREQ_DESCRIPTION_COLL" }]:</h3>
        [{foreach from=$aCollations item=aColumns key=sTable}]
        <li class="coll">[{ $sTable }]
            [{foreach from=$aColumns item=sCollation key=sColumn}]
                <ul>
                    <li id="[{$sColumn}]" class="fail">[{ $sColumn }] - [{ $sCollation }]</li>
                </ul>
            [{/foreach}]
        </li>
        [{/foreach}]
        <li class="clear"></li>
    </ul>
[{/if}]

<ul class="req">
    <li class="pass"> - [{ oxmultilang ident="SYSREQ_DESCRIPTION_PASS" }]</li>
    <li class="pmin"> - [{ oxmultilang ident="SYSREQ_DESCRIPTION_PMIN" }]</li>
    <li class="fail"> - [{ oxmultilang ident="SYSREQ_DESCRIPTION_FAIL" }]</li>
    <li class="null"> - [{ oxmultilang ident="SYSREQ_DESCRIPTION_NULL" }]</li>
</ul>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]