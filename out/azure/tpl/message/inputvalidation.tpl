[{foreach from=$aErrors item=oError }]
  <span class="oxError_notEmpty req">[{oxmultilang ident=$oError->getMessage()}]</span>
[{/foreach }]