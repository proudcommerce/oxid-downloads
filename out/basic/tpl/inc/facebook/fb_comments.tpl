        [{if $oView->isActive('FbComments') && $oViewConf->getFbAppId()}]
        [{assign var="product" value=$oView->getProduct() }]

        <strong id="test_facebookCommentsHead" class="boxhead">[{ oxmultilang ident="FACEBOOK_COMMENTS" }]</strong>
        <div class="box">
            <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=[{$oViewConf->getFbAppId()}]&amp;xfbml=1"></script><fb:comments href="[{$oView->getCanonicalUrl()}]" num_posts="5" width="560"></fb:comments>
        </div>
        [{/if}]

