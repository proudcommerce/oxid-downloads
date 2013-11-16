        [{if $oView->isActive('FbLike') && $oViewConf->getFbAppId()}]
        [{assign var="oProduct" value=$oView->getProduct() }]
        <br><br>
         <fb:like href="[{$oProduct->getLink()}]" layout="standard" show_faces="false" width="270" action="like" colorscheme="light"></fb:like>
        [{/if}]