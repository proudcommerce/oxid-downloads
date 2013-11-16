[{if $oView->isActive('FbLike') && $oViewConf->getFbAppId()}]
    <fb:like href="[{$oViewConf->getHomeLink()}]" layout="button_count" style="width:[{if $width}][{$width}][{else}]90[{/if}]px;" action="like" colorscheme="light"></fb:like>
[{/if}]