[{if $oView->showZoomPics()}]
    [{assign var="aZoomPics" value=$oView->getZoomPics()}]
    [{assign var="iZoomPic" value=$oView->getActZoomPic()}]
    <div id="zoomModal" class="popupBox corners FXgradGreyLight glowShadow overlayPop">
        <img src="[{$oViewConf->getImageUrl()}]x.png" alt="" class="closePop">
        <div class="zoomHead">
            [{oxmultilang ident="PAGE_DETAILS_ZOOMPOP"}]
            <a class="ox-zoom-close zoom-close" href="#zoom"><span></span></a>
        </div>
        <div class="zoomed">
            <img class="ox-zoomimg" src="[{$aZoomPics[$iZoomPic].file}]" alt="[{$oPictureProduct->oxarticles__oxtitle->value|strip_tags}] [{$oPictureProduct->oxarticles__oxvarselect->value|default:''}]" id="zoomImg">
        </div>
        [{if $aZoomPics|@count > 1}]
        <div class="otherPictures">
            <div class="shadowLine"></div>
            <ul class="zoomPager clear">
            [{oxscript add="var aZoomPic=new Array();"}]
            [{foreach from=$aZoomPics key=iPicNr item=_zoomPic}]
            [{assign var="_sZoomPic" value=$aZoomPics[$iPicNr].file}]
                <li>
                    <a class="ox-zoompic ox-picnr-[{$iPicNr}] [{if $iPicNr == 1}]selected[{/if}]" href="[{$_sZoomPic}]">
                        <span class="marker"><img src="[{$oViewConf->getImageUrl()}]marker.png" alt=""></span>
                        [{$_zoomPic.id}]
                    </a>
                </li>
                [{assign var="_sZoomPic" value=$aZoomPics[$iPicNr].file}]
                [{oxscript add="aZoomPic[`$iPicNr`]='`$_sZoomPic`';"}]
            [{/foreach}]
            </ul>
        </div>
        [{/if}]
    </div>
[{/if}]