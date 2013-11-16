[{ oxmultilang ident="PAGE_CHECKOUT_ORDER_EMAIL" }]&nbsp;[{ $oxcmp_user->oxuser__oxusername->value }]<br>
[{if $oxcmp_user->oxuser__oxcompany->value }] [{ $oxcmp_user->oxuser__oxcompany->value }]&nbsp;<br> [{/if}]
[{ $oxcmp_user->oxuser__oxsal->value|oxmultilangsal}]&nbsp;[{ $oxcmp_user->oxuser__oxfname->value }]&nbsp;[{ $oxcmp_user->oxuser__oxlname->value }]<br>
[{if $oxcmp_user->oxuser__oxaddinfo->value }] [{ $oxcmp_user->oxuser__oxaddinfo->value }]<br> [{/if}]
[{ $oxcmp_user->oxuser__oxstreet->value }]&nbsp;[{ $oxcmp_user->oxuser__oxstreetnr->value }]<br>
[{ $oxcmp_user->getState() }]
[{ $oxcmp_user->oxuser__oxzip->value }]&nbsp;[{ $oxcmp_user->oxuser__oxcity->value }]<br>
[{ $oxcmp_user->oxuser__oxcountry->value }]<br><br>
[{if $oxcmp_user->oxuser__oxfon->value }] [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_PHONE" }] [{ $oxcmp_user->oxuser__oxfon->value }]&nbsp;<br> [{/if}]
