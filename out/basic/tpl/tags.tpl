[{assign var="template_title" value="TAGS"|oxmultilangassign }]
[{include file="_header.tpl" title=$template_title location=$template_title}]

[{include file="inc/tags.tpl"}]

[{insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl" }]