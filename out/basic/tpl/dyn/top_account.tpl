[{if $oxcmp_user->oxuser__oxpassword->value}]
<dl class="box account">
    <dt id="tm.account.dt">
        <a id="test_TopAccMyAccount" rel="nofollow" href="[{ oxgetseourl ident=$oViewConf->getSslSelfLink()|cat:"cl=account" }]">[{ oxmultilang ident="INC_HEADER_MYACCOUNT" }]</a>
    </dt>
    <dd id="tm.account.dd" class="dropdown">
        [{strip}]
        <ul id="account_menu" class="menue verticall">
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSslSelfLink()|cat:"cl=account_password" }]" rel="nofollow">[{ oxmultilang ident="INC_ACCOUNT_HEADER_PASSWORD" }]</a></li>
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSslSelfLink()|cat:"cl=account_user" }]" rel="nofollow">[{ oxmultilang ident="INC_ACCOUNT_HEADER_ADDRESS" }]</a></li>
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_order" }]" rel="nofollow">[{ oxmultilang ident="INC_ACCOUNT_HEADER_ORDERHISTORY" }]</a></li>
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_noticelist" }]" rel="nofollow">[{ oxmultilang ident="INC_HEADER_NOTICELIST" }]</a></li>
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_wishlist" }]" rel="nofollow">[{ oxmultilang ident="INC_HEADER_WISHLIST" }]</a></li>
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=compare" }]" rel="nofollow">[{ oxmultilang ident="INC_ACCOUNT_HEADER_MYPRODUCTCOMPARISON" }]</a></li>
            <li><a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=account_recommlist" }]" rel="nofollow">[{ oxmultilang ident="INC_ACCOUNT_HEADER_MYRECOMMLIST" }]</a></li>

            <li><a href="[{ $oViewConf->getLogoutLink() }]" rel="nofollow">[{ oxmultilang ident="INC_ACCOUNT_HEADER_LOGOUT" }]</a></li>
        </ul>
        [{/strip}]
    </dd>
    <dd>
        [{ oxmultilang ident="INC_CMP_LOGIN_RIGHT_LOGGEDINAS" }]<br>
        <b>[{ $oxcmp_user->oxuser__oxfname->value}] [{$oxcmp_user->oxuser__oxlname->value}]</b><br>
        <span class="btn"><a id="test_TopAccLogout" href="[{ $oViewConf->getLogoutLink() }]" rel="nofollow">[{ oxmultilang ident="INC_HEADER_LOGOUT" }]</a></span>
    </dd>
</dl>
[{oxscript add="oxid.topnav('tm.account.dt','tm.account.dd');" }]
[{/if}]