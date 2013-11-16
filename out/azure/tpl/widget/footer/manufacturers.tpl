<ul class="list manufacturers">
  [{foreach from=$manufacturers item=_mnf}]
  <li><a href="[{$_mnf->getLink()}]" [{if $_mnf->expanded}]class="exp"[{/if}]>[{$_mnf->oxmanufacturers__oxtitle->value}]</a></li>
  [{/foreach}]
</ul>