<form class="search" action="[{ $oViewConf->getSelfActionLink() }]" method="get">
  <div>
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="search">
    <input type="text" name="searchparam" value="[{$oView->getSearchParamForHtml()}]">
    <input type="submit" value="search">
  </div>
</form>