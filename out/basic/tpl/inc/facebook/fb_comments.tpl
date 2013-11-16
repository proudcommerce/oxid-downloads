        [{if $oView->isActive('FbComments') && $oViewConf->getFbAppId()}]
        [{assign var="product" value=$oView->getProduct() }]

        <strong id="test_facebookCommentsHead" class="boxhead">[{ oxmultilang ident="FACEBOOK_COMMENTS" }]</strong>
        <div class="box">
            <fb:comments publish_feed=0 width="560"></fb:comments>
        </div>
        [{/if}]

