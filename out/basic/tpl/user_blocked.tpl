[{assign var="template_title" value="USER_BLOCHED_TITLE"|oxmultilangassign}]
[{include file="_header.tpl" title=$template_title location=$template_title}]

<strong class="boxhead">[{$template_title}]</strong>
<div class="box info">
  [{ oxcontent ident="oxblocked" }]
</div>

[{ insert name="oxid_tracker" title=$template_title }]
[{include file="_footer.tpl"}]