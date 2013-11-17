[{ assign var="blSep" value=""}]
[{foreach from=$oxcmp_lang item=lang}]
  [{ if $blSep == "y"}]|[{/if}]
    <a id="test_Lang_[{$lang->name}]" href="[{ oxgetseourl ident=$lang->link params="listtype="|cat:$oView->getListType() }]" class="[{if $lang->selected}]lang_active[{else}]lang[{/if}]">[{ $lang->name }]</a>
  [{ assign var="blSep" value="y"}]
[{/foreach}]
