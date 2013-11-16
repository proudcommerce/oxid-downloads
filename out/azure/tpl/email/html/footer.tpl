<div id="footer">
    <div id="panel" class="corners">
        [{capture append="oxidBlock_footer"}]
        <div class="bar">
            [{if $oView->isActive('FbLike') && $oViewConf->getFbAppId()}]
            <div class="facebook">
                [{include file="widget/facebook/like.tpl"}]
            </div>
            [{/if}]
            [{include file="widget/footer/newsletter.tpl"}]
            <div class="deliveryinfo">
                [{oxifcontent ident="oxdeliveryinfo" object="oCont"}]<a href="[{ $oCont->getLink() }]" rel="nofollow">[{ oxmultilang ident="FOOTER_INCLTAXANDPLUSSHIPPING" }]</a>
                [{/oxifcontent}]
            </div>
        </div>
        [{/capture}]
        [{capture append="oxidBlock_footer"}]
        <dl class="services" id="footerServices">
            <dt>
                [{oxmultilang ident="FOOTER_SERVICES" }]
            </dt>
            <dd>
                [{include file="widget/footer/services.tpl"}]
            </dd>
        </dl>
        [{/capture}]
        [{capture append="oxidBlock_footer" if=$oView->getManufacturerlist()|count}]
        <dl class="manufacturers" id="footerManufacturers">
            <dt>
                [{oxmultilang ident="FOOTER_MANUFACTURERS" }]
            </dt>
            <dd>
                [{include file="widget/footer/manufacturers.tpl" manufacturers=$oView->getManufacturerlist()}]
            </dd>
        </dl>
        [{/capture}]
        [{capture append="oxidBlock_footer" if=$oView->getVendorlist()|count}]
        <dl class="vendors" id="footerVendors">
            <dt>
                [{oxmultilang ident="FOOTER_DISTRIBUTORS" }]
            </dt>
            <dd>
                [{include file="widget/footer/vendors.tpl" vendors=$oView->getVendorlist()}]
            </dd>
        </dl>
        [{/capture}]
        [{capture append="oxidBlock_footer" if=$oxcmp_categories|count}]
        <dl class="categories" id="footerCategories">
            <dt>
                [{oxmultilang ident="FOOTER_CATEGORIES" }]
            </dt>
            <dd>
                [{include file="widget/footer/categorieslist.tpl" categories=$oxcmp_categories}]
            </dd>
        </dl>
        [{/capture}]
        [{foreach from=$oxidBlock_footer item="_block"}]
        [{$_block}]
        [{/foreach}]
    </div>
    <div class="copyright">
        <img src="[{$oViewConf->getImageUrl()}]logo_small.png">
    </div>
    <div class="text">
        [{oxifcontent ident="oxstdfooter" object="oCont"}]
        [{$oCont->oxcontents__oxcontent->value}]
        [{/oxifcontent}]
    </div>
</div>
