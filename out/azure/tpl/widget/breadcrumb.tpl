[{strip}]
<span>[{ oxmultilang ident="WIDGET_BREADCRUMB_YOUAREHERE" }]:</span>
[{foreach from=$oView->getBreadCrumb() item=sCrum }]
    &nbsp;/&nbsp;[{if $sCrum.link }]<a href="[{ $sCrum.link }]" title="[{ $sCrum.title}]">[{/if}][{$sCrum.title}][{if $sCrum.link }]</a>[{/if}]
[{/foreach}]
[{/strip}]
