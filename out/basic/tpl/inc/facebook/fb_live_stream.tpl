        [{if $oView->isActive('FbLiveStream') && $oViewConf->getFbAppId()}]
        [{assign var="product" value=$oView->getProduct() }]

        <strong id="test_facebookInviteHead" class="boxhead">[{ oxmultilang ident="FACEBOOK_CHAT" }]</strong>
        <div class="box">
            <fb:live-stream app_id="[{$oViewConf->getFbAppId()}]" width="550" height="500"></fb:live-stream>
        </div>
        [{/if}]
