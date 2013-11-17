[{assign var="template_title" value="ERR_MANDATES_EXCEEDED_TITLE"|oxmultilangassign}]
[{include file="_header_plain.tpl" title=$template_title location=$template_title}]

  <div class="errorbox">
      <div class="errhead">[{ oxmultilang ident="ERR_MANDATES_EXCEEDED_OXIDESHOPERROR" }]</div>
      <div class="errbody">[{ oxmultilang ident="ERR_MANDATES_EXCEEDED_VERSIONEXPIRED1" }] <a href="http://www.oxid-esales.com">[{ oxmultilang ident="ERR_MANDATES_EXCEEDED_VERSIONEXPIRED2" }]</a> [{ oxmultilang ident="ERR_MANDATES_EXCEEDED_VERSIONEXPIRED3" }]</div>
  </div>
  
[{include file="_footer_plain.tpl"}]

