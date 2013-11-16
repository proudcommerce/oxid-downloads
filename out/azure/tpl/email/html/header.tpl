<div id="header" class="clear">
    [{include file="widget/header/languages.tpl"}]
    [{include file="widget/header/currencies.tpl"}]
    <ul id="topMenu">
        <li class="login flyout[{if $oxcmp_user->oxuser__oxpassword->value}] logged[{/if}]">
            [{include file="widget/header/loginbox.tpl"}] 
        </li>
        <li>
            <a href="#" title="Registrieren">Registrieren</a>
        </li>
        <li>
            <a href="#" title="Service">Service</a>
        </li>
    </ul>
    <a id="logo" href="[{$oViewConf->getHomeLink()}]" title="[{$oxcmp_shop->oxshops__oxtitleprefix->value}]"><img src="[{$oViewConf->getImageUrl()}]logo.png" alt="[{$oxcmp_shop->oxshops__oxtitleprefix->value}]"></a>[{include file="widget/header/topcategories.tpl"}]
    [{include file="widget/header/minibasket.tpl"}]
    [{include file="widget/header/search.tpl"}]
</div>[{if $oView->getClassName()=='start'}]
<div id="promotions">
    <img src="[{$oViewConf->getImageUrl()}]promo-demo.jpg" height="220" width="940" alt="Promotions demo">
</div>[{/if}]