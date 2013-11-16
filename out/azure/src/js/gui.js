var oxid = {
    mdVariants: {
        // reloading page by selected value in select list
        getMdVariantUrl: function(selId){
            var _mdVar = document.getElementById(selId);

            if (_mdVar) {
                _newUrl = _mdVar.options[_mdVar.selectedIndex].value;
            }

            if (_newUrl) {
                document.location.href = _newUrl;
            }
        },

        mdAttachAll: function(){
            if (!mdVariantSelectIds) {
                mdVariantSelectIds = Array();
            }

            if (!mdRealVariants) {
                mdRealVariants = Array();
            }

            for (var i = 0; i < mdVariantSelectIds.length; i++) {
                if (mdVariantSelectIds[i]) {
                    for (var j = 0; j < mdVariantSelectIds[i].length; j++) {
                        //attach JS handlers
                        var mdSelect = document.getElementById(mdVariantSelectIds[i][j]);
                        if (mdSelect) {
                            mdSelect.onchange = oxid.mdVariants.resetMdVariantSelection;
                        }
                    }
                }
            }
        },

        resetMdVariantSelection: function(e){
            mdSelect = oxid.getEventTarget(e);
            //hide all
            selectedValue = mdSelect.options[mdSelect.selectedIndex].value;
            level = oxid.mdVariants.getSelectLevel(mdSelect.id);
            if (level !== null) {
                oxid.mdVariants.hideAllMdSelect(level + 1);
            }
            //show selection
            var showId = selectedValue;
            while (showId) {
                showSelectId = oxid.mdVariants.getMdSelectNameById(showId);
                oxid.mdVariants.showMdSelect(showSelectId);
                shownSelect = document.getElementById(showSelectId);
                if (shownSelect) {
                    showId = shownSelect.options[shownSelect.selectedIndex].value;
                }
                else {
                    showId = null;
                }
            }

            oxid.mdVariants.showMdRealVariant();
        },

        getMdSelectNameById: function(id){
            var name = 'mdVariantSelect_' + id;
            return name;
        },

        getSelectLevel: function(name){
            for (var i = 0; i < mdVariantSelectIds.length; i++) {
                for (var j = 0; j < mdVariantSelectIds[i].length; j++) {
                    if (mdVariantSelectIds[i][j] == name) {
                        return i;
                    }
                }
            }
            return null;
        },

        showMdSelect: function(id){
            if (document.getElementById(id)) {
                document.getElementById(id).style.display = 'inline';
            }
        },

        hideAllMdSelect: function(level){
            for (var i = level; i < mdVariantSelectIds.length; i++) {
                if (mdVariantSelectIds[i]) {
                    for (var j = 0; j < mdVariantSelectIds[i].length; j++) {
                        if (document.getElementById(mdVariantSelectIds[i][j])) {
                            document.getElementById(mdVariantSelectIds[i][j]).style.display = 'none';
                        }
                    }
                }
            }
        },

        getSelectedMdRealVariant: function(){
            for (var i = 0; i < mdVariantSelectIds.length; i++) {
                for (var j = 0; j < mdVariantSelectIds[i].length; j++) {
                    var mdSelectId = mdVariantSelectIds[i][j];
                    var mdSelect = document.getElementById(mdSelectId);
                    if (mdSelect && mdSelect.style.display == "inline") {
                        var selectedVal = mdSelect.options[mdSelect.selectedIndex].value;
                        if (mdRealVariants[selectedVal])
                            return mdRealVariants[selectedVal];
                    }
                }
            }
        },

        showMdRealVariant: function(){
            document.getElementById('mdVariantBox').innerHTML = '';
            var selectedId = oxid.mdVariants.getSelectedMdRealVariant();
            if (selectedId && document.getElementById('mdVariant_' + selectedId)) {
                document.getElementById('mdVariantBox').innerHTML = document.getElementById('mdVariant_' + selectedId).innerHTML;
            }

        }
    },

    loadingScreen: {
        start : function (target, iconPositionElement) {
            var loadingScreens = Array();
            $(target).each(function() {
                var overlayKeeper = document.createElement("div");
                overlayKeeper.innerHTML = '<div class="loadingfade"></div><div class="loadingicon"></div><div class="loadingiconbg"></div>';
                $('div', overlayKeeper).css({
                        'position' : 'absolute',
                        'left'     : $(this).offset().left-10,
                        'top'      : $(this).offset().top-10,
                        'width'    : $(this).width()+20,
                        'height'   : $(this).height()+20
                    });
                if (iconPositionElement && iconPositionElement.length) {
                    var x = Math.round(
                        iconPositionElement.offset().left // my left
                        - 10 - $(this).offset().left      // relativeness
                        + iconPositionElement.width()/2   // plus half of width to center
                    );
                    var offsetTop = iconPositionElement.offset().top;
                    var y = Math.round(
                        offsetTop                         //my top
                        - 10 - $(this).offset().top       // relativeness
                        + (                               // this requires, that last element in collection, would be the bottom one
                                                          // as it computes last element offset from the first one plus its height
                            iconPositionElement.last().offset().top - offsetTop + iconPositionElement.last().height()
                        )/2
                    );

                    $('div.loadingiconbg,div.loadingicon', overlayKeeper).css({
                        'background-position' : x + "px "+y+"px"
                    });
                }
                $('div.loadingfade', overlayKeeper)
                    .css({'opacity' : 0})
                    .animate({
                        opacity: 0.55
                    }, 200
                    );
                $("body").append(overlayKeeper);
                loadingScreens.push(overlayKeeper);
            });
            return loadingScreens;
        },
        stop : function (loadingScreens) {
          $.each(loadingScreens, function(i, el) {
              $('div', el).not('.loadingfade').remove();
              $('div.loadingfade', el)
                  .stop(true, true)
                  .animate({
                      opacity: 0
                  }, 100, function(){
                      $(el).remove();
                  });
          });
        }
    },

    updatePageErrors : function(errors) {
        if (errors.length) {
            var errlist = $("#content > .status.error");
            if (errlist.length == 0) {
                $("#content").prepend("<div class='status error corners'>");
                errlist = $("#content > .status.error");
            }
            if (errlist) {
                errlist.children().remove();
                var i;
                for (i=0; i<errors.length; i++) {
                    var p = document.createElement('p');
                    $(p).append(document.createTextNode(errors[i]));
                    errlist.append(p);
                }
            }
        } else {
            $("#content > .status.error").remove();
        }
    },

    ajax : function(activator, params) {
        // activator: form or link element
        // params: targetEl, iconPosEl, onSuccess, onError, additionalData
        var inputs = {};
        var action = "";
        var type   = "";
        if (activator[0].tagName == 'FORM') {
            $("input", activator).each(function() {
                inputs[this.name] = this.value;
            });
            action = activator.attr("action");
            type   = activator.attr("method");
        } else if (activator[0].tagName == 'A') {
            action = activator.attr("href");
        }

        if (params['additionalData']) {
            $.each(params['additionalData'], function(i, f) {inputs[i] = f;});
        }

        var loadingScreen = null;
        if (params['targetEl']) {
            loadingScreen = oxid.loadingScreen.start(params['targetEl'], params['iconPosEl']);
        }

        if (!type) {
            type = "get";
        }
        jQuery.ajax({
            data: inputs,
            url: action,
            type: type,
            timeout: 30000,
            error: function(jqXHR, textStatus, errorThrown) {
                if (loadingScreen) {
                    oxid.loadingScreen.stop(loadingScreen);
                }
                if (params['onError']) {
                    params['onError'](jqXHR, textStatus, errorThrown);
                }
            },
            success: function(r) {
                if (loadingScreen) {
                    oxid.loadingScreen.stop(loadingScreen);
                }
                if (r['debuginfo'] != undefined && r['debuginfo']) {
                    $("body").append(r['debuginfo']);
                }
                if   (r['errors'] != undefined
                   && r['errors']['default'] != undefined) {
                    oxid.updatePageErrors(r['errors']['default']);
                } else {
                    oxid.updatePageErrors([]);
                }
                if (params['onSuccess']) {
                    params['onSuccess'](r, inputs);
                }
            }
        });
    },

    evalScripts : function(container){
        try {
            $("script", container).each(function(){
                try {
                    eval(this.innerHTML);
                } catch (e) {
                    // console.error(e);
                }
                $(this).remove();
            });
        } catch (e) {
            // console.error(e);
        }
    },

    selectionDrop : {
        onClick : function ( obj ) {
            // setting new selection
            var oUl = obj.parent( "li" ).parent( "ul" );
            var oP  = oUl.prev( "input" ).prev( "p" );

            oP.addClass( "underlined" );
            $( "span", oP ).html( obj.html() );

            $( "a", oUl ).removeClass('selected');
            obj.addClass( "selected" );

            oUl.prev( "input" ).attr( "value", obj.attr( "rel" ) );
            oUl.hide();
            return false;
        }
    },

    initBasket : function () {
        /**
         * Selection dropdown
         */
        $('ul.seldrop a').unbind( "click" );
        $("ul.seldrop a").bind( "click", function() {
            return oxid.selectionDrop.onClick( $( this ) );
        });
    },

    initDetailsMain : function () {
        if ($("#productTitle").length > 0) {
            var targetWidth = $("#productTitle span").width();
            if (targetWidth > 220) {
                var linkboxWidth = $("#productTitle span").width();
            }
            else {
                var linkboxWidth = 220;
            }
            var targetHeight = $("#productTitle span").height();

             /* More pictures marker*/

             $(".otherPictures li a").click(function(){
                $(".otherPictures li a").removeClass("selected");
                $(this).addClass("selected");
                return false;
             });

             $("#zoomModal li a").click(function(){
                $("#zoomImg").attr("src", $(this).attr("href"));
                return false;
             });

             $(".cloud-zoom").click(function(){
                return false;
             });

            $("#zoomImg").click(function(){

                var oPaging     = $(".zoomPager");
                var iImgCount   = $(".zoomPager li").size();
                var iCurImgNo   = $(".selected", oPaging).text();

                if ( $(".selected", oPaging).length == 0 ) {
                    iCurImgNo = 1;
                    $(".zoomPager li:first").children("a").addClass('selected');
                }

                var sFirstImage = $(".zoomPager li:first").children("a").attr("href");
                var sNextImage  = $(".selected", oPaging).parent().next().children("a").attr("href");
                var oCurPage    = $(".selected", oPaging);
                var oNextPage   = $(".selected", oPaging).parent().next().children();
                var oLastPage   = $(".zoomPager li:last").children("a");
                var oFirstPage  = $(".zoomPager li:first").children("a");

                if( iCurImgNo == iImgCount ) {
                    $("#zoomImg").attr("src", sFirstImage);
                    oLastPage.removeClass('selected');
                    oFirstPage.addClass('selected');
                } else {
                    $("#zoomImg").attr("src", sNextImage);
                    oCurPage.removeClass('selected');
                    oNextPage.addClass('selected');
                }

                return false;
             });



            $(".actionLinks").css({
                "top": $("#productTitle").position().top - 7,
                "left": $("#productTitle").position().left - 10,
                "padding-top": targetHeight + 10,
                "width": linkboxWidth + 50
            });

            var arrowSrc = $(".selector img").attr("src");
            var arrow = $("#productLinks").children("img");
            function showLinks() {
                var arrowOnSrc = arrow.attr("longdesc");
                $(".actionLinks").slideDown("normal", function(){
                    arrow.attr("src", arrowOnSrc);
                });
            }
            function hideLinks() {
                $(".actionLinks").animate({
                    height: 0,
                    opacity: 0.1
                }, 300, function(){
                    $(".actionLinks").hide().css({
                        height: 'auto',
                        opacity: '1'
                    });
                    arrow.attr("src", arrowSrc);
                });
            }

            $("#productLinks").css({
                "top": $("#productTitle").position().top - 3,
                "left": targetWidth + $("#productTitle").position().left + 10
            }).click(function(){
                $(this).toggleClass("selected");
                if ($(this).hasClass("selected")) {
                    showLinks();
                }
                else {
                    hideLinks();
                }
                return false;
            });
            $("#productLinks").hover(function() {
                showLinks();
            });
            $(".actionLinks").mouseleave( function() {
                hideLinks();
            });
            if ($("#showLinksOnce").length > 0) {
                $(".actionLinks").slideDown('normal').delay(1000).slideUp('normal', function(){
                     setCookie('showlinksonce', 1);
                });
            }
        }

        if ($("#amountPrice").length > 0) {
            $(".pricePopup").css({
                "top": $("#amountPrice").position().top - 7,
                "left": $("#amountPrice").position().left - 10,
                "width": 220
            });

            var arrowSrc = $(".selector img").attr("src");

            $("#amountPrice").click(function(){
                var arrow = $(this).children("img");

                var arrowOnSrc = arrow.attr("longdesc");
                $(this).toggleClass("selected");
                if ($(this).hasClass("selected")) {
                    $("#priceinfo").slideDown("normal", function(){
                        arrow.attr("src", arrowOnSrc);
                    });
                    $(".tobasketFunction .selector").css({
                        "left": $("#amountPrice").position().left,
                        "position": "absolute"
                    });
                }
                else {
                    $("#priceinfo").animate({
                        height: 0,
                        opacity: 0.1
                    }, 300, function(){
                        $("#priceinfo").hide().css({
                            height: 'auto',
                            opacity: '1'
                        });
                        arrow.attr("src", arrowSrc);
                    });
                    $(".tobasketFunction .selector").css({
                        "position": "static"
                    });
                }
                return false;
            });
        }

        $(".priceAlarmLink").click(function() {
            var $tabs = $('.tabbedWidgetBox').tabs();
            $tabs.tabs('select', '#pricealarm');
            return false;
        });

        $('select[id^=sellist]').change (function() {
            var oSelf = $(this);
            var oNoticeList = $('#linkToNoticeList');
            if ( oNoticeList ) {
                oNoticeList.attr('href', oNoticeList.attr('href') + "&" + oSelf.attr('name') + "&" + oSelf.val());
            }
            var oWishList = $('#linkToWishList');
            if ( oWishList ) {
                oWishList.attr('href', oWishList.attr('href') + "&" + oSelf.attr('name') + "&" + oSelf.val());
            }
        });


        /**
         * Variant selection dropdown
         */
        $('ul.vardrop a').unbind( "click" );
        $("ul.vardrop a").bind( "click", function() {

            var obj = $( this );

            // resetting
            if ( obj.parents().hasClass("oxdisabled") ) {
                oxid.variantSelection.resetVariantSelections();
            } else {
                $( ".oxProductForm input[name=anid]" ).attr( "value", $( ".oxProductForm input[name=parentid]" ).attr( "value" ) );
            }

            // setting new selection
            if ( obj.parents().hasClass("fnSubmit") ){
                obj.parent('li').parent('ul').prev('input').attr( "value", obj.attr("rel") );

                var form = obj.closest("form");
                $('input[name=fnc]', form).val("");
                form.submit();
            }
            return false;
        });

        /**
         * selection dropdown
         */
        $('ul.seldrop a').unbind( "click" );
        $("ul.seldrop a").bind( "click", function() {
            return oxid.selectionDrop.onClick( $( this ) );
        });

        /**
         * variant reset link
         */
        $('div.variantReset a').click( function () {
            oxid.variantSelection.resetVariantSelections();
            var obj = $( this );
            var form = obj.closest("form");
            $('input[name=fnc]', form).val("");
            form.submit();
            return false;
        } );

        function reloadProductPartially(activator,renderPart,highlightTargets,contentTarget) {
            oxid.ajax(
                activator,
                {//targetEl, onSuccess, onError, additionalData
                    'targetEl'  : highlightTargets,
                    'iconPosEl' : $("#variants .dropDown"),
                    'additionalData' : {'renderPartial' : renderPart},
                    'onSuccess' : function(r) {
                        contentTarget.innerHTML = r['content'];
                        oxid.evalScripts(contentTarget);
                    }
                }
            );
            return false;
        }

        $(".oxProductForm").submit(function () {
            if (!$("input[name='fnc']", this).val()) {
                if (($( "input[name=aid]", this ).val() == $( "input[name=parentid]", this ).val() )) {
                    var aSelectionInputs = $("input[name^=varselid]", this);
                    if (aSelectionInputs.length) {
                        var hash = '';
                        aSelectionInputs.not("*[value='']").each(function(i){
                            hash = hash+i+':'+$(this).val()+"|";
                        });
                        if (oxVariantSelections.indexOf(hash) < 0) {
                            return reloadProductPartially($(".oxProductForm"),'detailsMain',$("#detailsMain"),$("#detailsMain")[0]);
                        }
                    }
                }
                return reloadProductPartially($(".oxProductForm"),'productInfo',$("#productinfo"),$("#productinfo")[0]);
            }
        });
    },

    initDetailsRelated : function () {

        $(".tabbedWidgetBox").tabs();

        $(".tagCloud .tagText").click(oxid.highTag);
        $("#saveTag").click(oxid.saveTag);
        $("#cancelTag").click(oxid.cancelTag);
        $("#editTag").click(oxid.editTag);

    },

    initNewReview : function () {

        $("#writeNewReview").click(function(){
            $("#writeReview").slideToggle();
            $("#writeNewReview").hide();
            return false;
        });

    },

    highTag : function() {
        var oSelf = $(this);
        $(".tagError").hide();

        oxid.ajax(
            $("#tagsForm"),
            {//targetEl, onSuccess, onError, additionalData
                'targetEl' : $("#tags"),
                'additionalData' : {'highTags' : oSelf.prev().text()},
                'onSuccess' : function(response, params) {
                    oSelf.prev().addClass('taggedText');
                    oSelf.hide();
                }
            }
        );
        return false;
    },

    saveTag : function() {
        $(".tagError").hide();

        oxid.ajax(
            $("#tagsForm"),
            {//targetEl, onSuccess, onError, additionalData
                'targetEl' : $("#tags"),
                'additionalData' : {'blAjax' : '1'},
                'onSuccess' : function(response, params) {
                    if ( response ) {
                        $(".tagCloud").append("<span class='taggedText'>" + params["newTags"] + "</span> ");
                    } else {
                        $(".tagError").show();
                    }
                }
            }
        );
        return false;
    },

    cancelTag : function () {
        oxid.ajax(
            $("#tagsForm"),
            {//targetEl, onSuccess, onError, additionalData
                'targetEl' : $("#tags"),
                'additionalData' : {'blAjax' : '1', 'fnc' : 'cancelTags'},
                'onSuccess' : function(response, params) {
                    if ( response ) {
                        $('#tags').html(response);
                        $("#tags #editTag").click(oxid.editTag);
                    }
                }
            }
        );
        return false;
    },

    editTag : function() {
        oxid.ajax(
            $("#tagsForm"),
            {//targetEl, onSuccess, onError, additionalData
                'targetEl' : $("#tags"),
                'additionalData' : {'blAjax' : '1'},
                'onSuccess' : function(response, params) {
                    if ( response ) {
                        $('#tags').html(response);
                        $("#tags .tagText").click(oxid.highTag);
                        $('#tags #saveTag').click(oxid.saveTag);
                        $('#tags #cancelTag').click(oxid.cancelTag);
                    }
                }
            }
        );
        return false;
    },

    initDetailsPagePartial : function () {
        if (window.fbAsyncInit) {
            window.fbAsyncInit();
        }
        $(".cloud-zoom, .cloud-zoom-gallery").CloudZoom();
        oxid.initDropDowns();
    },

    showDropdown : function () {
        oxid.hideDropdown();
        targetObj = $(this);

        targetObj.removeClass('underlined');
        sublist = targetObj.nextAll("ul.drop");

        sublist.prepend("<li class='value'></li>");
        targetObj.clone().appendTo($(".value", sublist));
        sublist.css("width", targetObj.parent().outerWidth());

        if (sublist.length) {
            sublist.slideToggle("fast");
            targetObj.toggleClass("selected");
        }

    },

    hideDropdown: function () {
        $("ul.drop").hide();
        $("ul.drop li.value").remove();
        $(".dropDown p").removeClass("selected");
        $(".dropDown p").addClass("underlined");
    },

    initDropDowns : function () {
        $(document).click( function(e){
            var clickTarget = e.target;
            if (!$(clickTarget).parents().hasClass("dropDown")) {
                $(".drop").hide();
                $(".dropDown p").addClass("underlined");
            }
        });

        $(".dropDown p:not(.oxdisabled)").click(oxid.showDropdown);

        $(".dropDown p").hover(function(){
            $(this).toggleClass("selected");
        });

        $("ul.drop a").click(function(){
            var obj = $(this);
            var objFnIdent = obj.parents().hasClass("fnSubmit");
            if ( objFnIdent ){
                obj.parent('li').parent('ul').prev('input').attr( "value", obj.attr("rel") );
                obj.closest("form").submit();
                return false;
            }
            return null;
        })
    },

    /**
     * Variant selection handler
     */
    variantSelection : {

        /**
         * Resets variant selections
         */
        resetVariantSelections : function()
        {
            var aVarSelections = $( ".oxProductForm input[name^=varselid]" );
            for (var i = 0; i < aVarSelections.length; i++) {
                $( aVarSelections[i] ).attr( "value", "" );
            }

            //
            $( ".oxProductForm input[name=anid]" ).attr( "value", $( ".oxProductForm input[name=parentid]" ).attr( "value" ) );
        }
    }

}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function deleteCookie(name) {
    setCookie(name,"",-1);
}

$(function(){
        if ($.browser.msie) {
            $("ul.sf-menu li:not(:has(ul))").hover(function(){
                $(this).addClass("sfHover");
            }, function(){
                $("ul.sf-menu li:not(:has(ul))").removeClass("sfHover");
            });
        }

        //Categories menu init
        $("ul.sf-menu").supersubs({
            minWidth:    12,   // minimum width of sub-menus in em units
            maxWidth:    35,   // maximum width of sub-menus in em units
            extraWidth:  1     // extra width can ensure lines don't sometimes turn over
                               // due to slight rounding differences and font-family
        }).superfish( {
             delay : 500,
             dropShadows : false,
             onBeforeShow : function() {
                //adding hover class for active <A> elements
                $('a:first', this.parent()).addClass($.fn.superfish.op.hoverClass);

                // horizontaly centering top navigation first level popup accoring its parent
                activeItem = this.parent()
                if ( activeItem.parent().hasClass('sf-menu') ) {
                    liWidth = activeItem.width();
                    ulWidth = $('ul:first', activeItem).width();
                    marginWidth = (liWidth - ulWidth) / 2;
                    $('ul:first', activeItem).css("margin-left", marginWidth);
                }
            },
            onHide : function() {
                $('a:first-child',this.parent()).removeClass($.fn.superfish.op.hoverClass);
            }
        } );


        $("#countdown").countdown(
            function(count, element, container) {
                if (count <= 1) {
                    $(element).parents("#basketFlyout").hide();
                    $("#countValue").replaceWith("0");
                    $("#miniBasket img.minibasketIcon").unbind('mouseenter mouseleave');
                    return container.not(element);
                }
                return null;
            }
        );
        $(".external").attr("target", "_blank");
        $('input.innerLabel').focus(function() {
            if (this.value == this.defaultValue){
                this.value = '';
            }
            if(this.value != this.defaultValue){
                this.select();
            }
        });

        $('input.innerLabel').blur(function() {
            if ($.trim($(this).val()).length==0) {
                this.value = (this.defaultValue);
            }
        });


        $(".flyout a.trigger").click(function(){
            $(".loginBox").show();
            return false;
        });

        $(document).click( function(e){
            if( $(e.target).parents("div").hasClass("popBox") || $(e.target).parents("div").hasClass("topPopList") ){
            }else{
                $(".popBox,.topPopList .flyoutBox").hide();
            }
        });

        $(document).keydown( function( e ) {
           if( e.which == 27) {
                $(".popBox,.topPopList .flyoutBox").hide();
           }
        });

        $(".selectedValue").click(function(){
            $(".flyoutBox").hide();
            $(this).nextAll(".flyoutBox").show();
            return false;
        });


        $("#checkAll").click(function(){
            toggleChecks(this);
        });


        function toggleChecks(){
            if ($("#checkAll").attr("checked")) {
                $(".basketitems .checkbox input").attr("checked", true);
                $("#checkAll").attr("checked", true);
                return;
            }
                $(".basketitems .checkbox input").attr("checked", false);
                $("#checkAll").attr("checked", false);
        };


        $("#basketRemoveAll").click(function(){
            $("#checkAll").click();
            toggleChecks();
            return false;
        });


        $("#paymentStep #orderStep").click(function(){
            $(".order").attr("submit", true);
        });


        /*
         * Toggling payment info on selecting payment
         */
        $("#payment dl dt input[type=radio]").click(function(){
            $("#payment dd").hide();
            $(this).parents("dl").children("dd").toggle();
        });


        /*
        * Minibasket flyout
        */
        var timeout;
        if ($("#miniBasket ul").length > 0) {
            $("#miniBasket img.minibasketIcon").hover(function(){
                timeout = setTimeout(function(){
                    $(".basketFlyout").show();
                    if ($(".scrollable ul").length > 0) {
                        $('.scrollable ul').jScrollPane({
                            showArrows: true,
                            verticalArrowPositions: 'split'
                        });
                    }
                }, 300);
            }, function(){
                clearTimeout(timeout);
            });
        }

        if ($("#compareDataDiv").length) {
            $("#compareDataDiv").jScrollPane({
                                showArrows: true,
                                horizontalGutter: 0
            });
        }

        $(".closePop").live("click", function(){
            $(".basketFlyout").hide();
            $(".popupBox").hide().dialog("close");
            clearTimeout(timeout);

            return false;
        });

        $(".altLoginBox .fb_button").live("click", function(){
            $("#loginBox").hide();
        });


        /*
         * Show/hide item details on grid list
         */

         $(".gridView li").hover(function (){
             $(".listDetails", this).show();
         }, function(){
             $(".listDetails", this).hide();
         });

        /*
         *  Overlay popup
         */

         function initOverlay(target, w, h) {
            $(target).dialog({
                    width: w,
                    modal: true,
                    resizable: true,
                    open: function(event, ui) {
                    $('div.ui-dialog-titlebar').css("visibility", "hidden");
                }
            });
         }

         $(".closeOverlay").click(function(){
            $(".overlayPop").dialog("close");
            return false;
         });


         oxid.initDropDowns();
         /*
          * Wraping selection, overlayPopup window
          */

         $("#wrapp li, #wrappCard li").click(function(){
            $(this).children("input[type=radio]").attr("checked", true);
         });


        /*$(".wrappingTrigger").click(function(){
            initOverlay(".wrapping", 687);
            return false;
        });*/

        /*
         * Remove item from list
         */
        $(".removeButton").click(function(){
            var targetForm = $(this).attr("triggerForm");
            $("#"+targetForm).submit();
            return false;
        });


        /*
         * Equalize columns
         */
        function equalHeight(group, target, getAddHeight) {
            var tallest = 0;
            if (target) {
                if (group.height() < target.height()){
                    group.css("height", target.height());
                }
            } else {

                group.each(function(){

                    var thisHeight = $(this).height();
                    if (thisHeight > tallest) {
                        tallest = thisHeight;
                    }
                });

                 group.each(function(){
                    if( $(this).hasClass('catPicOnly') && $(this).height() < tallest  ){
                        $(this).height(tallest+20);
                    }else{
                        $(this).height(tallest);

                    }
                 });
            }
        }

        equalHeight($("#panel dl"));
        equalHeight($(".sidebarMenu"), $("#content"));
        equalHeight($(".subcatList li .content"));
        equalHeight($(".checkoutOptions .option"));

        /*
         * Trim title and add ellipsis ...
         */
        function trimTitles(group) {
            group.each(function(){
                var thisWidth  = $(this).width();
                var thisText   = $.trim($(this).text());
                var parentWidth = $(this).parent().width();
                if (thisWidth > parentWidth) {
                    var thisLength  = thisText.length;
                    while (thisWidth > parentWidth)
                    {
                        thisLength--;
                        $(this).html(thisText.substr(0,thisLength)+'&hellip;');
                        var thisWidth = $(this).width();
                    }
                    $(this).attr('title',thisText);
                }
            });
        }

        trimTitles($(".box h3 a"));

       /* Show all items hover */
      $(".linkAll").hover(function(){
          var targetObj = $(this).children(".viewAllHover");
          targetObj.show();
          var targetObjMargin = targetObj.width() / 2;
          targetObj.css("margin-left", "-" + targetObjMargin + "px");
      }, function(){
          $(".viewAllHover").hide();
      });

     /* Vertical box positioning*/
    $(".specBoxInfo").hover(function(){
        var boxHeight = $(".hoverBox", $(this)).height();
        var boxTarget = $(".hoverInfo", $(this));
        var addHoverPadding = (boxHeight - boxTarget.height()) / 2;
        boxTarget.css("padding-top", addHoverPadding);
    });

    if($("#newItemMsg").length > 0){
        $("#countValue").hide();
        $("#newItemMsg").delay(3000).fadeTo("fast", 0, function(){
            $("#countValue").fadeTo("fast", 1);
            $("#newItemMsg").remove()
        });
    }

    $('#addressId').change(function() {
        $( ".oxValidate" ).unbind('submit');
        var reload = '2';
        var selectValue = $(this).val();
        if (selectValue === '-1') {
            reload = '1';
        }
        if ($("input[name=reloadaddress]")) {
            $("input[name=reloadaddress]").val(reload);
        }
        if (selectValue !== '-1') {
            $("form[name='order'] input[name=cl]").val($("input[name=changeClass]").val());
            $("form[name='order'] input[name=fnc]").val("");
            $("form[name='order']").submit();
        } else {
            $("input:text").filter(function() {
                return this.name.match(/address__/);
            }).val("");
            $('#shippingAddressForm').show();
            $('#shippingAddressText').hide();
            $("select[name='deladr[oxaddress__oxcountryid]']").children("option").attr("selected", null);
            $("select[name='deladr[oxaddress__oxstateid]']").children("option[name='promtString']").attr("selected", "selected");
        }
    });
});