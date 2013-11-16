        [{if $oView->isActive('FbFacepile') && $oViewConf->getFbAppId()}]
        <strong id="test_facebookFacepileHead" strong class="h2">[{ oxmultilang ident="FACEBOOK_FACEPILE" }]</strong>
        <div class="box">
            <fb:facepile max-rows="5" width="180" ></fb:facepile>
        </div>
        [{/if}]
