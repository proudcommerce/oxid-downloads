[{assign var="template_title" value="ERR_EXPIRED_DAYS_TITLE"|oxmultilangassign}]
[{include file="_header_plain.tpl" title=$template_title location=$template_title}]

  <div class="errorbox">
      <div class="errhead">[{ oxmultilang ident="ERR_EXPIRED_DAYS_OXIDESHOPERROR" }]</div>
      <div class="errbody">[{ oxmultilang ident="ERR_EXPIRED_DAYS_VERSIONEXPIRED1" }] <a href="[{ oxmultilang ident="OXID_ESALES_URL" }]" title="[{ oxmultilang ident="OXID_ESALES_URL_TITLE" }]">[{ oxmultilang ident="ERR_EXPIRED_DAYS_VERSIONEXPIRED2" }]</a> [{ oxmultilang ident="ERR_EXPIRED_DAYS_VERSIONEXPIRED3" }]</div>
  </div>
  
[{include file="_footer_plain.tpl"}]

