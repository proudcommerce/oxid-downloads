[{if $delivadr }]
    [{if $delivadr->oxaddress__oxcompany->value }] [{ $delivadr->oxaddress__oxcompany->value }]&nbsp;<br> [{/if}]
    [{ $delivadr->oxaddress__oxsal->value|oxmultilangsal}]&nbsp;[{ $delivadr->oxaddress__oxfname->value }]&nbsp;[{ $delivadr->oxaddress__oxlname->value }]<br>
    [{if $delivadr->oxaddress__oxaddinfo->value }] [{ $delivadr->oxaddress__oxaddinfo->value }]<br> [{/if}]
    [{ $delivadr->oxaddress__oxstreet->value }]&nbsp;[{ $delivadr->oxaddress__oxstreetnr->value }]<br>
    [{ $delivadr->getState() }]
    [{ $delivadr->oxaddress__oxzip->value }]&nbsp;[{ $delivadr->oxaddress__oxcity->value }]<br>
    [{ $delivadr->oxaddress__oxcountry->value }]<br><br>
    [{if $delivadr->oxaddress__oxfon->value }] [{ oxmultilang ident="PAGE_CHECKOUT_ORDER_PHONE2" }] [{ $delivadr->oxaddress__oxfon->value }]&nbsp;<br>[{/if}]
[{/if}]
