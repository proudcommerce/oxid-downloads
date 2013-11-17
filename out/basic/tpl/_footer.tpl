        <div class="clear"></div>
        </div>

    <div id="footer">
        <div class="bar copy">
            <div class="left" id="delivery_link">
                [{assign var="oCont" value=$oView->getContentByIdent("oxdeliveryinfo") }]
                <a href="[{ $oCont->getLink() }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_INCLTAXANDPLUSSHIPPING" }]</a>
            </div>
            <div class="right">
                &copy; <a href="http://www.oxid-esales.com">[{ oxmultilang ident="INC_FOOTER_SOFTWAREFROMOXIDESALES" }]</a>
            </div>
            <div class="clear"></div>
        </div>
        <div class="bar shop">
            <a id="test_link_footer_home" href="[{ oxgetseourl ident=$oViewConf->getHomeLink() }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_HOME" }]</a> |
            <a id="test_link_footer_contact" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=contact" }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_CONTACT" }]</a> |
            <a id="test_link_footer_help" href="[{ oxgetseourl ident=$oViewConf->getHelpLink() }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_HELP" }]</a> |
            <a id="test_link_footer_guestbook" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=guestbook" }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_GUESTBOOK" }]</a> |
            <a id="test_link_footer_links" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=links" }]">[{ oxmultilang ident="INC_FOOTER_LINKS" }]</a> |
            [{assign var="oCont" value=$oView->getContentByIdent("oximpressum") }]
            <a id="test_link_footer_impressum" href="[{ $oCont->getLink() }]" rel="nofollow">[{ $oCont->oxcontents__oxtitle->value }]</a> |
            [{assign var="oCont" value=$oView->getContentByIdent("oxagb") }]
            <a id="test_link_footer_terms" href="[{ $oCont->getLink() }]" rel="nofollow">[{ $oCont->oxcontents__oxtitle->value }]</a> |
            <br>
            [{oxhasrights ident="TOBASKET"}]
            <a id="test_link_footer_basket" href="[{ oxgetseourl ident=$oViewConf->getBasketLink() }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_CART" }]</a> |
            [{/oxhasrights}]
            <a id="test_link_footer_account" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account" }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_MYACCOUNT" }]</a> |
            <a id="test_link_footer_noticelist" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_noticelist" }]" rel="nofollow"> [{ oxmultilang ident="INC_FOOTER_MYNOTICELIST" }]</a>
              | <a id="test_link_footer_wishlist" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_wishlist" }]" rel="nofollow"> [{ oxmultilang ident="INC_FOOTER_MYWISHLIST" }]</a>
              | <a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=wishlist" params="wishid="|cat:$wishid }]" rel="nofollow">[{ oxmultilang ident="INC_FOOTER_PUBLICWISHLIST" }]</a>
        </div>
        <div class="bar icons">
            [{*
            <a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Strict" height="31" width="88"></a>
            <a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-css2" alt="Valid CSS!" /></a>
            *}]
        </div>

        <div class="shopicons">
            <div class="left"><img src="[{$oViewConf->getImageUrl()}]cc.jpg" alt=""></div>
            <div class="right"><a href="http://www.oxid-esales.com"><img src="[{$oViewConf->getImageUrl()}]oxid_powered.jpg" alt="[{ oxmultilang ident="INC_FOOTER_SOFTWAREANDSYSTEMBYOXID" }]" height="30" width="80"></a></div>
        </div>

        <div class="footertext">[{oxcontent ident="oxstdfooter"}]</div>

    </div>
</div>
</div>
<div id="mask"></div>
[{if $popup}][{include file=$popup}][{/if}]
<script type="text/javascript" src="[{ $oViewConf->getResourceUrl() }]oxid.js"></script>
[{oxid_include_dynamic file="dyn/newbasketitem_popup.tpl"}]
<script type="text/javascript">[{oxscript}][{oxid_include_dynamic file="dyn/oxscript.tpl" }]</script>
<!--[if lt IE 7]><script type="text/javascript">oxid.popup.addShim();</script><![endif]-->
</body>
</html>