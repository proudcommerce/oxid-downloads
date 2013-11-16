[{capture name="slides"}]
    [{foreach from=$oView->getManufacturerForSlider() item=oManufacturer}]
        [{if $oManufacturer->oxmanufacturers__oxicon->value }]
        [{counter assign="slideCount"}]
            <li>
                <a href="[{ $oManufacturer->getLink() }]" class="viewAllHover">
                    <span>[{ oxmultilang ident="WIDGET_MANUFACTURERS_SLIDER_VIEWALL" }]</span>
                </a>
                <a class="sliderHover" href="[{ $oManufacturer->getLink() }]"></a>
                <img src="[{ $oManufacturer->getIconUrl() }]" alt="[{ $oManufacturer->oxmanufacturers__oxtitle->value }]">
            </li>
        [{/if}]
    [{/foreach}]
[{/capture}]
[{if $slideCount > 6 }]
    [{oxscript include="js/jquery.jcarousellite.js"}]
    [{oxscript add="$( '#manufacturerSlider' ).oxManufacturerSlider();"}]
    <div class="itemSlider">
        <a class="prevItem slideNav" href="#" rel="nofollow"><strong>[{ oxmultilang ident="WIDGET_MANUFACTURERS_SLIDER_OURBRANDS" }]</strong><span>&laquo;</span></a>
        <a class="nextItem slideNav" href="#" rel="nofollow"><span>&raquo;</span></a>
        <div id="manufacturerSlider">
            <ul>
                [{$smarty.capture.slides}]
            </ul>
        </div>
    </div>
[{/if}]