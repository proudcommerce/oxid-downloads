        [{if $oView->isActive('FbInvite') && $oViewConf->getFbAppId()}]
        <strong id="test_facebookInviteHead" class="boxhead">[{ oxmultilang ident="FACEBOOK_INVITE" }]</strong>
        <div class="box">
            <fb:serverfbml width="560px">
                <script type="text/fbml">
                    <fb:fbml>
                      <fb:request-form action="[{$oView->getCanonicalUrl()}]"
                                method="GET"
                                invite="true"
                                type="Facebook"
                                content="[{ oxmultilang ident="FACEBOOK_INVITETEXT" }]<fb:req-choice url='[{$oView->getCanonicalUrl()}]' label='[{ oxmultilang ident="FACEBOOK_INVITEBUTTONTEXT" }]'></fb:req-choice>">
                                <fb:multi-friend-selector
                                    showborder="false"
                                    rows="3"
                                    cols="3"
                                    max="20"
                                    width="560"
                                    actiontext="[{ oxmultilang ident="FACEBOOK_INVITEHEADER" }]">
                                </fb:multi-friend-selector>
                      </fb:request-form>
                    </fb:fbml>
                </script>
            </fb:serverfbml>
        </div>
        [{/if}]
