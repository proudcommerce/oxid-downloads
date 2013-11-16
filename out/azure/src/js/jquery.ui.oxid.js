/**
 * #PHPHEADER_OXID_LICENSE_INFORMATION#
 *
 * @link      http://www.oxid-esales.com
 * @package   views
 * @copyright (c) OXID eSales AG 2003-#OXID_VERSION_YEAR#
 * @version   SVN: $Id: account_newsletter.php 26071 2010-02-25 15:12:55Z sarunas $
 */
( function( $ ) {
    /**
     * Image selection handler
     */
    $.widget( "ui.oxPicSelect", {
        options: {
            sTarget    : "",
            sImgTarget : "",
            aPicArray  : ""
        },

        /**
         * Initializes image selector
         * @return null
         */
        _create: function() {
            var oSelf = this;

            // selecting first image
            this._select( $( "." + this.options.sTarget )[0], this.options.sTarget );

            // subscribing click handler..
            this.element.bind( { click: function ( event ) { return oSelf.catchClick( event ); }});
        },

        /**
         * Image click catcher
         * @return false
         */
        catchClick: function( event )
        {
            var oTarget = $( event.target ).closest("a");
            if ( $( oTarget ).is('a') ) {
                this._select( oTarget, this.options.sTarget );
                var iPicNr = $( oTarget ).attr( "class" ).match( /ox\-picnr\-\d/ );
                if ( iPicNr !== null ) {
                    iPicNr = iPicNr[0].replace( "ox-picnr-", "" );
                    $( "." + this.options.sImgTarget ).attr( "src", this.options.aPicArray[ iPicNr ] );
                }
            }

            return false;
        },

        /**
         * Adding selected image border
         * @return null
         */
        _select: function( oClickObject, sTarget )
        {
            var oBorder = $( "." + sTarget + "-border" );
            var oMarker = $( "." + sTarget + "-marker" );
            var oPic = $( oClickObject );


            // calculating width
            var iWidth  = $( oPic ).innerWidth();
            iWidth -= parseInt( oBorder.css( "border-left-width" ) );
            iWidth -= parseInt( oBorder.css( "border-right-width" ) );

            // calculating height
            var iHeight = $( oPic ).innerHeight();
            iHeight -= parseInt( oBorder.css( "border-top-width" ) );
            iHeight -= parseInt( oBorder.css( "border-bottom-width" ) );

            oBorder.css( "left", $( oPic ).position().left );
            oBorder.css( "top", $( oPic ).position().top );
            oBorder.css( "width", iWidth );
            oBorder.css( "height", iHeight );
            oBorder.css( "display", "block" );
            oMarker.css( "left", iWidth / 2 - oMarker.width() / 2 );
            $( "." + sTarget ).removeClass( "active" );
            $( oClickObject ).addClass( "active" );
        }
    }),

    /**
     * Standart popup widget
     */
    $.widget( "ui.oxPopup", {
        /**
         * Popup mask object
         */
        _oMask : false,

        /**
         * Returns popup mask object
         * @return object
         */
        _getMask: function() {
            if ( this._oMask == false ) {
                this._oMask = $( '<div class="ox-mask mask"></div>' );
                $( 'body' ).append( this._oMask );
            }
            return this._oMask;
        },

        /**
         * Returns popup mask object
         * @return object
         */
        _setMask: function( oMask ) {
            this._oMask = oMask;
        },

        /**
         * Hides (removes from DOM) popup mask
         * @return null
         */
        _hideMask: function() {
            $( this._getMask() ).fadeOut().remove();
            this._setMask( false );
        },

        /**
         * Shows (adds to DOM) popup mask
         */
        _showMask: function() {
            $( this._getMask() ).css( { 'filter' : 'alpha(opacity=80)' } ).fadeIn();
        },

        /**
         * Shows mask
         * @return null
         */
        open: function() {
            this._showMask();
        },

        /**
         * Hides mask
         * @return null
         */
        close: function() {
            this._hideMask();
        },

        /**
         * Returns next z-index, which may be used to add other items on top of mask
         * @return int
         */
        _getNextZIndex: function() {
            return this._getMask().css( "z-index" ) + 1;
        }
    } ),

    /**
     * Zoom popup handler
     */
    $.widget( "ui.oxZoomPopup", $.ui.oxPopup, {
        _blOpenedOnce : false,
        /**
         * Implements zoom popup and zoom image preview functionality
         * @return null
         */
        _create: function() {
            var oSelf = this;
            // subscribing click event handler on close button
            this.element.bind( { click: function( event ) { return oSelf._handleClick( event ); } } );

            // moving popup in front of mask
            this.element.css( "z-index", this._getNextZIndex() );

            // adding load event handler to zoom image
            $( ".ox-zoomimg" ).bind( { load: function() { return oSelf._resizeAndPosition( this ); } } );
        },

        /**
         * Displays zoom image popup
         * @return null
         */
        parent_open: $.ui.oxPopup.prototype.open,
        open: function() {
            var oSelf = this;

            // mask
            this.parent_open();

            // popup
            this.element.addClass( "on" );

            //
            this._resizeAndPosition();

            if ( this._blOpenedOnce == false ) {

                // image click listener
                $( ".ox-zoompic" ).oxPicSelect( { sTarget : "ox-zoompic", sImgTarget : "ox-zoomimg", aPicArray : typeof( aZoomPic ) == 'undefined' ? new Array() : aZoomPic } );

                this._blOpenedOnce = true;
            }
        },

        /**
         * Closes zoom popup
         * @return null
         */
        parent_close: $.ui.oxPopup.prototype.close,
        close: function() {
            this.parent_close();
            this.element.removeClass( "on" );;
        },

        /**
         * Handles various click events on popup
         * @return false
         */
        _handleClick: function( event ) {

            if ( $( event.target ).parent().hasClass( "ox-zoom-close" ) ) {
                // close button
                this.close();
            }

            return false;
        },

        /**
         * Resizes and positions zoom popup
         * @return null
         */
        _resizeAndPosition: function() {
            var oImage = $( ".ox-zoomimg" );
            this.element.css( { "width"       : oImage.innerWidth(),
                                "min-width"   : oImage.innerWidth(),
                                "height"      : oImage.innerHeight(),
                                "min-height"  : oImage.innerHeight() + 50,
                                "margin-left" : ( oImage.innerWidth() / 2 ) * -1,
                                "margin-top"  : ( oImage.innerHeight() / 2 ) * -1 } );
        }
    }),

    /**
     * Zoom button handler
     */
    $.widget( "ui.oxZoomButton", {
        /**
         * Adding click event handler on zoom button
         * @return null
         */
        _create: function() {
            this.element.click( function() { $( "#zoom" ).oxZoomPopup(); $( "#zoom" ).oxZoomPopup( "open" ); return false; } );
        }
    }),

    /**
     * Selector popup handler
     */
    $.widget( "ui.oxSelector", {
        /**
         * Subscribing click/hover events on selector
         * @return null
         */
        _create: function() {
            var oSelf = this;

            // subscribing hover
            this.element.bind( {
                // click function
                click: function () {
                    return false;
                },

                // hover function
                mouseover: function () {
                    return oSelf._showHoverBox( false );
                }
            });
        },

        /**
         * On hover displays related box
         *
         * @return null
         */
        _showHoverBox: function( oHoverBox ) {
            var iLeft = this.element.offset().left;
            var iTop  = this.element.offset().top;

            if ( oHoverBox == false ) {
                oHoverBox = $( this.element.attr( "href" ) );
            }

            // special case for suggest type boxes..
            $( ".ox-selectorbox-nose-shadow" ).remove();
            $( ".ox-selectorbox" ).css( "visibility", "hidden" );
            $( ".ox-selectorbox" ).removeClass( "suggest" );

            // ox-expand-toleft
            if ( oHoverBox.hasClass( "ox-expand-toleft" ) ) {
                var iMargin = Math.abs( parseInt( oHoverBox.css( "margin-left" ) ) );
                iLeft = iLeft - oHoverBox.outerWidth() + this.element.outerWidth() + iMargin * 2;
            }

            oHoverBox.css( { "left" : iLeft, "top" : iTop, "visibility" : "visible" } );
            oHoverBox.bind( { mouseleave: function() { $( this ).css( "visibility", "hidden" ); $( this ).removeClass( "suggest" ); $( ".ox-selectorbox-nose-shadow" ).remove(); return false; } } );

            return false;
        }
    }),


    /**
     * Selector popup handler
     */
    $.widget( "ui.oxSuggest", $.ui.oxSelector, {
        /**
         * Subscribing click/hover events on selector
         * @return null
         */
        _create: function() {
            var oSelf = this;

            // subscribing hover
            this.element.bind( {
                // hover function
                focus: function ( event ) {
                    return oSelf._showHoverBox( $( "#priceinfo" ) );
                },
                click: function ( event ) {
                    return oSelf._showHoverBox( $( "#priceinfo" ) );
                },
                keydown: function ( event ) {
                    return oSelf._showHoverBox( $( "#priceinfo" ) );
                }
            });
        },

        /**
         * On hover displays suggest box
         *
         * @return null
         */
        parent_showHoverBox: $.ui.oxSelector.prototype._showHoverBox,
        _showHoverBox: function( oHoverBox ) {
            if ( $( ".ox-selectorbox-nose-shadow" ).length == 0 ) {
                this.parent_showHoverBox( oHoverBox );

                oHoverBox.addClass( "suggest" );
                oHoverBox.unbind( "mouseleave" );

                var oNose = $( '<div class="ox-selectorbox-nose-shadow selectorbox-nose-shadow"></div>' );
                this.element.parent().append( oNose );
            }
        }
    }),



    /**
     * Form Items validator
     */
    oxInputValidator = {
            options: {
                classValid            : "oxValid",
                classInValid          : "oxInValid",
                errorParagraf         : "p.oxValidateError",
                errorMessageNotEmpty  : "oxError_notEmpty",
                errorMessageNotEmail  : "oxError_email",
                errorMessageShort     : "oxError_length",
                errorMessageNotEqual  : "oxError_match",
                metodValidate         : "oxValidate",
                metodValidateEmail    : "oxValidate_email",
                metodValidateNotEmpty : "oxValidate_notEmpty",
                metodValidateLength   : "oxValidate_length",
                metodValidateMatch    : "oxValidate_match",
                idPasswordLength      : "#passwordLength",
                listItem              : "li",
                list                  : "ul",
                paragraf              : "p",
                span                  : "span",
                form                  : "form",
                visible               : ":visible",

                //
                metodEnterPasswd      : "oxValidate_enterPass"
            },

            _create: function() {

                var self    = this,
                    options = self.options,
                    el      = self.element;

                el.delegate("."+options.metodValidate, "blur", function() {
                    if ( $( this ).is(options.visible) ) {
                        self.inputValidation(this, true);
                    }
                });

                self._bindSpecialListener( el );
                el.bind( "submit", function() {
                    return self.submitValidation(this);
                });
            },

            /**
             * Binds special listeners to given input field
             *
             * @return null
             */
            _bindSpecialListener: function( oInput )
            {
                var oOptions = this.options;
                var self     = this;
                var oInput   = $( oInput );

                if ( oInput.hasClass( oOptions.metodEnterPasswd ) ) {
                    oInput.bind ( "keyup", function() {
                        self.showInput( oInput, oInput.val() != oInput.attr( "defaultValue" ), oOptions.metodEnterPasswd );
                    });
                }
            },

            /**
             * Shows/hides given element
             */
            showInput: function( oSource, blShow, sClass )
            {
                var oRegexp  = new RegExp( sClass + "Target\\[(.+)\\]", "g" );
                var sClasses = oRegexp.exec( oSource.attr( "class" ) );
                if ( sClasses && sClasses.length ) {
                    var aClasses = sClasses[1].split(",");

                    for (var i = 0; i < aClasses.length; i++) {
                        if (blShow) {
                            $("." + aClasses[i]).show();
                        }
                        else {
                            $("." + aClasses[i]).hide();
                        }
                    }
                }
            },

            /**
             * Validate form element, return forms true - valid, false - not valid
             *
             * @return boolean
             */
            inputValidation: function(oInput, blCanSetDefaultState)
            {
                var oOptions = this.options;
                var self = this;
                var blValidInput = true;

                    if ( $( oInput ).hasClass( oOptions.metodValidateNotEmpty ) && blValidInput ) {
                        self.manageErrorMessage(oInput, $( oInput ).val() , oOptions.errorMessageNotEmpty);
                        blValidInput = $( oInput ).val() ? true : false;
                    }

                    if ( $( oInput ).hasClass( oOptions.metodValidateEmail ) && blValidInput ) {

                        if( $( oInput ).val() ) {
                            self.manageErrorMessage(oInput, self.isEmail( $( oInput ).val() ), oOptions.errorMessageNotEmail);
                            blValidInput = blValidInput && self.isEmail( $( oInput ).val() );
                        }
                    }

                    if ( $( oInput ).hasClass( oOptions.metodValidateLength ) && blValidInput ) {

                        var iLength = self.getLength( $( oInput ).parent(oOptions.listItem).parent(oOptions.list).parent(oOptions.form ));

                        if( $( oInput ).val() ) {
                            self.manageErrorMessage(oInput, self.hasLength( $( oInput ).val(), iLength), oOptions.errorMessageShort);
                            blValidInput = blValidInput && self.hasLength( $( oInput ).val(), iLength);
                        }
                    }

                    if ( $( oInput ).hasClass( oOptions.metodValidateMatch ) && blValidInput ) {

                        var inputs = new Array();

                        var oForm = $( oInput ).parent(oOptions.listItem).parent(oOptions.list).parent(oOptions.form);

                        $( "." + oOptions.metodValidateMatch, oForm).each( function(index) {
                            inputs[index] = this;
                        });

                        if( $(inputs[0]).val() && $(inputs[1]).val() ) {
                            self.manageErrorMessage(inputs[0], self.isEqual($(inputs[0]).val(), $(inputs[1]).val()), oOptions.errorMessageNotEqual);
                            self.manageErrorMessage(inputs[1], self.isEqual($(inputs[0]).val(), $(inputs[1]).val()), oOptions.errorMessageNotEqual);
                            blValidInput = blValidInput && self.isEqual($(inputs[0]).val(), $(inputs[1]).val());
                        }
                    }

                    if ( $( oInput ).hasClass( oOptions.metodValidate ) && blCanSetDefaultState) {

                        if( !$( oInput ).val()){
                            self.setDefaultState( oInput );
                            return true;
                        }
                    }

                return blValidInput;
            },

            /**
             * On submit validate requared form elements,
             * return true - if all filled correctly, false - if not
             *
             * @return boolean
             */
            submitValidation: function(oForm)
            {
                var blValid = true;
                var oFirstNotValidElement = null;
                var self = this;
                var oOptions = this.options;

                $( "." + oOptions.metodValidate, oForm).each(    function(index) {

                    if ( $( this ).is(oOptions.visible) ) {
                        if(! self.inputValidation(this, false)){
                            blValid = false;
                            if( oFirstNotValidElement == null ) {
                                oFirstNotValidElement = this;
                            }
                        }
                    }

                });

                if( oFirstNotValidElement != null ) {
                    $( oFirstNotValidElement ).focus();
                }

                return blValid;
            },


            /**
             * Manage error messages show / hide
             *
             * @return object
             */
            manageErrorMessage: function ( oObject, isValid, messageType )
            {
                if ( isValid ) {
                     return this.hideErrorMessage(oObject, messageType);
                } else {
                    return this.showErrorMessage(oObject, messageType);
                }
            },

            /**
             * Show error messages
             *
             * @return object
             */
            showErrorMessage: function ( oObject, messageType )
            {
                var oObject =  $( oObject).parent();

                oObject.removeClass(this.options.classValid);
                oObject.addClass(this.options.classInValid);
                oObject.children(this.options.errorParagraf).children( this.options.span + "." + messageType ).show();
                oObject.children(this.options.errorParagraf).show();

                return oObject;
            },

            /**
             * Hide error messages
             *
             * @return object
             */
            hideErrorMessage: function ( oObject, messageType )
            {
                var oObject = $( oObject).parent();

                oObject.removeClass(this.options.classInValid);
                oObject.addClass(this.options.classValid);
                oObject.children(this.options.errorParagraf).children( this.options.span + "." + messageType ).hide();
                oObject.children(this.options.errorParagraf).hide();

                return oObject;
            },

            /**
             * Set dafault look of form list element
             *
             * @return object
             */
            setDefaultState: function ( oObject )
            {
                var oObject = $( oObject ).parent();

                oObject.removeClass(this.options.classInValid);
                oObject.removeClass(this.options.classValid);
                oObject.children(this.options.errorParagraf).hide();

                oOptions = this.options;

                $( this.options.span, oObject.children( this.options.errorParagraf ) ).each( function(index) {
                    oObject.children( oOptions.errorParagraf ).children( oOptions.span ).hide();
                });

                return oObject;
            },

            /**
             * gets requared length from form
             *
             * @return boolean
             */
            getLength: function(oObject){

                oOptions = this.options;

                return $( oOptions.idPasswordLength , oObject).val();
            },

            /**
             * Checks length
             *
             * @return boolean
             */
            hasLength: function( stValue, length )
            {
                stValue = jQuery.trim( stValue );

                if( stValue.length >= length ) {
                    return true;
                }

                return false;
            },

            /**
             * Checks mails validation
             *
             * @return boolean
             */
            isEmail: function( email )
            {
                email = jQuery.trim(email);

                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                //var reg = /^([-!#\$%&'*+.\/0-9=?A-Z^_`a-z{|}~\177])+@([-!#\$%&'*+\/0-9=?A-Z^_`a-z{|}~\177]+\\.)+[a-zA-Z]{2,6}\$/i;

                if(reg.test(email) == false) {
                    return false;
                }

                return true;
            },

            /**
             * Checks is string equal
             *
             * @return boolean
             */
            isEqual: function( stValue1, stValue2 )
            {
                stValue1 = jQuery.trim(stValue1);
                stValue2 = jQuery.trim(stValue2);

                if (stValue1 == stValue2){
                    return true;
                }

                return false;
            }
        };

    /**
     * Form Items validator
     */
    $.widget("ui.oxInputValidator", oxInputValidator );


     oxValidateLoginForm = {

            _create: function() {
                var self    = this,
                    options = self.options,
                    el      = self.element;
                el.bind( "submit", function() {
                    if (! self._isEmail($('#loginEmail', el).val()) ){
                        $('#errorBadLogin').show();
                        return false;
                    }else{
                        return true;
                    }
                });
            },

              /**
             * Checks mails validation
             *
             * @return boolean
             */
            _isEmail: function( email )
            {
                email = jQuery.trim(email);

                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                //var reg = /^([-!#\$%&'*+.\/0-9=?A-Z^_`a-z{|}~\177])+@([-!#\$%&'*+\/0-9=?A-Z^_`a-z{|}~\177]+\\.)+[a-zA-Z]{2,6}\$/i;

                if(reg.test(email) == false) {
                    return false;
                }

                return true;
            }
    };

    $.widget( "ui.oxValidateLoginForm", oxValidateLoginForm );


    oxCountryStateSelect = {
        options: {
            listItem        : "li",
            select          : "select",
            span            : "span",
            selectedStateId : "selectedStateId"
        },

        _create: function() {
            var self = this,
            options = self.options,
            el         = self.element;

            var stateSelect = self.getStateSelect(el);

            self.manageStateSelect(stateSelect
                    , self.getStates(el.val(), allStates, allCountryIds)
                    , self.getStatesValues(el.val(), allStateIds, allCountryIds)
                    , options.selectedStateId);

            el.change(function() {
                self.manageStateSelect(stateSelect
                    , self.getStates(el.val(), allStates, allCountryIds)
                    , self.getStatesValues(el.val(), allStateIds, allCountryIds)
                    , options.selectedStateId);
            });
        },

        /**
         * show / hide select add/remove options
         *
         * @return object
         */
        manageStateSelect: function(oSelect, aStates, aStatesValues, selectedStateId)
        {
             this.removeSelectOptions(oSelect);

             var stateSelectSpan = this.getStateSelectSpan(oSelect);

             if (aStates != null && aStates.length > 0) {
                 this.addSelectOptions(oSelect, aStatesValues, aStates, selectedStateId);
                 stateSelectSpan.parent().show();
             } else {
                 stateSelectSpan.parent().hide();
                 this.removeSelectOptions(oSelect);
             }

             return oSelect;
        },

        /**
         * get state select
         *
         * @return object
         */
        getStateSelect: function(oCountrySelect)
        {
            oOptions = this.options;
            return     $( oCountrySelect ).parent(oOptions.listItem).next(oOptions.listItem).children(oOptions.span).children(oOptions.select);
        },

        /**
         * get state select span
         *
         * @return object
         */
        getStateSelectSpan: function(oStateSelect)
        {
            oOptions = this.options;
            return     $( oStateSelect ).parent(oOptions.span);
        },

        /**
         * add options
         *
         * @return object
         */
        addSelectOptions: function(oSelect, aValues, aLables, selectedStateId)
        {
            for(var x = 0; x < aValues.length; x++) {
                if (selectedStateId == aValues[x]) {
                    oSelect.
                    append($("<option></option>").
                    attr("value",aValues[x]).
                    attr('selected', x).
                    text(aLables[x]));
                } else {
                    oSelect.
                    append($("<option></option>").
                    attr("value",aValues[x]).
                    text(aLables[x]));
                }
            }
            return oSelect;
        },

        /**
         * remove all select options except first list promt string
         *
         * @return object
         */
        removeSelectOptions: function(oSelect)
        {
            oSelect.find('option[name!="promtString"]').remove().end();
            return oSelect;
        },

        /**
         * get Country state names
         *
         * @return array
         */
        getStates: function(sCountry, allStates, allCountryIds)
        {
            return allStates[allCountryIds[sCountry]];
        },

        /**
         * get Country state ids
         *
         * @return array
         */
        getStatesValues: function(sCountry, allStatesIds, allCountryIds)
        {
            return allStatesIds[allCountryIds[sCountry]];
        }

    };

    $.widget("ui.oxCountryStateSelect", oxCountryStateSelect );

    oxManufacturerSlider = {
            options: {
                classButtonNext    : '.nextItem',
                classButtonPrev    : '.prevItem'
            },

            _create: function() {

                var self = this,
                options = self.options,
                el         = self.element;

                 el.jCarouselLite({
                     btnNext: options.classButtonNext,
                     btnPrev: options.classButtonPrev,
                   visible: 6,
                   scroll: 1
                });
            }
    };

    $.widget("ui.oxManufacturerSlider", oxManufacturerSlider );

    oxModalPopup = {
            options: {
                width         : 687,
                height         : 'auto',
                modal         : true,
                resizable     : true,
                zIndex         : 10000,
                position     : 'center',
                draggable     : true,

                target         : '#popup',
                openDialog     : false,
                loadUrl        : false
            },

            _create: function() {

                var self = this,
                options = self.options,
                el      = self.element;

                if (options.openDialog) {

                    if (options.loadUrl){
                        $(options.target).load(options.loadUrl);
                    }

                    self.openDialog(options.target, options);

                    return false;
                }

                el.click(function(){

                    if (options.loadUrl){
                        $(options.target).load(options.loadUrl);
                    }

                    self.openDialog(options.target, options);

                    return false;
                });
            },

             openDialog: function (target, options) {

                $(target).dialog({

                        width         : options.width,
                        height         : options.height,
                        modal         : options.modal,
                        resizable     : options.resizable,
                        zIndex         : options.zIndex,
                        position     : options.position,
                        draggable     : options.draggable,

                        open: function(event, ui) {

                        $('div.ui-dialog-titlebar').css("visibility", "hidden");
                    }
                });
             }
    };

    $.widget("ui.oxModalPopup", oxModalPopup );



    oxLoadArticleVariant = {
            options: {
                selectClass : 'md_select_variant',
                blockClass     : 'variants'
            },

            _create: function() {

                var self     = this;
                var options  = self.options;
                var el       = self.element;
                var loadUrl;

                el.change( function(){

                    loadUrl =  mdRealVariantsLinks[ el.val() ];

                    if ( loadUrl == undefined ) {
                        var oLastSelect = self.getLastVariantSelect( $( '#' + options.blockClass ), options.selectClass );
                        loadUrl = mdRealVariantsLinks[self.getFirstValue( oLastSelect )]
                    }

                    if ( loadUrl != undefined ) {
                        window.location = loadUrl;
                    }

                });
            },

            getLastVariantSelect: function ( oVariantsBlock, sSelectClass){

                return $( '.' + sSelectClass + ':visible:last ', oVariantsBlock );
            },

            getFirstValue: function( oSelect ){

                return oSelect.children( 'option:first' ).val();

            }
    };

    $.widget( "ui.oxLoadArticleVariant", oxLoadArticleVariant );







    oxInfoPopup = {
            options: {
                width         : 300,
                resizable     : true,
                zIndex         : 10000,
                target         : '#popup'
            },

            _create: function() {

                var self = this,
                options = self.options,
                el      = self.element;

                var position = el.position();

                el.click(function(){

                    self.openDialog(options.target, options, position);

                    return false;
                });
            },

             openDialog: function (target, options, position) {

                $(target).dialog({

                        width         : options.width,
                        modal         : false,
                        resizable     : options.resizable,
                        zIndex         : options.zIndex,
                        position     : [position.left + 30, position.top - 30],

                        open: function(event, ui) {

                        $('div.ui-dialog-titlebar').css("visibility", "hidden");
                    }
                });
             }
    };

    $.widget("ui.oxInfoPopup", oxInfoPopup );

    oxSlider = {
            options: {
                width                  : 940,
                height               : 220,
                autoPlay             : true,
                classPanel             : '.panel',
                classStartStop         : '.start-stop',
                classPromotionText     : '.promoBox',
                classNavigation        : '.thumbNav',
                classForwardArrow    : '.forward',
                classBackArrow        : '.back',
                classAnythingSlider    : '.anythingSlider',
                classThumbNav        : '.thumbNav',
                classAnythingControls    : '.anythingControls',
                elementLi             : 'li',
                eventMouseover        : "mouseover",
                eventMouseout        : "mouseout",
                opacity70            : 0.7,
                opacity100            : 1,
                opacity0            : 0

            },

            _create: function() {

                var self = this,
                options = self.options,
                el         = self.element;
                var oAnythingSlider;

                var aNavigationTabs = new Array();

                aNavigationTabs = self.getNavigationTabsArray(el, options.elementLi);

                el.anythingSlider({
                        width               : options.width,
                        height              : options.height,
                        autoPlay            : options.autoPlay,
                        startStopped        : false,
                        delay               : 6700,
                        animationTime       : 2700,
                        navigationFormatter : function(i, panel){
                            return aNavigationTabs[i - 1];
                        }
                });

                oAnythingSlider = $(options.classAnythingSlider);

                $(options.classAnythingControls, oAnythingSlider).css("left", (options.width - $(options.classThumbNav, oAnythingSlider).innerWidth() ) / 2);

                self.hideControls(oAnythingSlider);

                $("a[class^='panel']", oAnythingSlider).attr("rel", 'nofolow');

                var blOnNav = false;

                $(options.classPromotionText, el).each(function(){
                    var targetObj = $(this).children(".promoPrice");
                    var targetObjHeight = targetObj.nextAll("strong").height();
                    targetObj.css({
                        "height" : targetObjHeight,
                        "line-height" : targetObjHeight + "px"
                    });
                });


                oAnythingSlider.mouseover( function() {
                    self.showTextSpan(el, options.classPromotionText);
                    if ( ! blOnNav ){
                        self.showControlsWithOpacity(oAnythingSlider, options.opacity70);
                    }

                });

                $(options.classNavigation, oAnythingSlider).mouseover(function() {

                    self.showControlsWithOpacity(oAnythingSlider, options.opacity70);
                    self.showControlWithOpacity(oAnythingSlider, options.classNavigation, options.opacity100);
                      blOnNav = true;

                });

                $(options.classBackArrow, oAnythingSlider).mouseover(function() {

                    self.showControlsWithOpacity(oAnythingSlider, options.opacity70);
                    self.showControlWithOpacity(oAnythingSlider, options.classBackArrow, options.opacity100);
                      blOnNav = true;

                });

                $(options.classForwardArrow, oAnythingSlider).mouseover(function() {

                    self.showControlsWithOpacity(oAnythingSlider, options.opacity70);
                    self.showControlWithOpacity(oAnythingSlider, options.classForwardArrow, options.opacity100);
                      blOnNav = true;

                });

                oAnythingSlider.mouseout( function() {

                   self.hideTextSpan(el, options.classPromotionText);
                   self.showControlWithOpacity(oAnythingSlider, options.classNavigation, options.opacity0);
                   self.hideControls(oAnythingSlider);
                   blOnNav = false;

                });

            },

            /**
             * generate slider navigation array
             *
             * @return array
             */
            getNavigationTabsArray: function(oElement, stElementType){

                var aTabs = new Array();

                $( stElementType, oElement ).each( function( index ) {
                    aTabs[index] = index + 1;
                });

                return aTabs;
            },

            /**
             * shows controls with opacity (navigation, start-stop button, etc.)
             *
             * @return object
             */
            showControlsWithOpacity: function(oElement, fOpacity){

                oOptions = this.options;

                this.showControlWithOpacity(oElement, oOptions.classForwardArrow, fOpacity);
                this.showControlWithOpacity(oElement, oOptions.classBackArrow, fOpacity);
                this.showControlWithOpacity(oElement, oOptions.classNavigation, fOpacity);

            },

            /**
             * shows control with opacity (navigation, start-stop button, etc.)
             *
             * @return object
             */
            showControlWithOpacity: function(oElement, stClass, fOpacity){

                oElement = $(stClass, oElement).fadeTo(0, fOpacity);
                return oElement;

            },

            /**
             * Show control (navigation, start-stop button, etc.)
             *
             * @return object
             */
            showControl: function(oElement, stClass){

                oElement = $(stClass, oElement).show();
                return oElement;

            },

            /**
             * hide control (navigation, start-stop button, etc.)
             *
             * @return object
             */
            hideControl: function(oElement, stClass ){

                oElement = $(stClass, oElement).hide();
                return oElement;

            },

            /**
             * hides controla (navigation, start-stop button, etc.)
             *
             * @return object
             */
            hideControls: function(oElement){

                oOptions = this.options;

                this.hideControl(oElement, oOptions.classStartStop);
                this.hideControl(oElement, oOptions.classForwardArrow);
                this.hideControl(oElement, oOptions.classBackArrow);
                this.hideControl(oElement, oOptions.classNavigation);
            },

            /**
             * hides texts spans
             *
             * @return object
             */
            hideTextSpan: function(oElement, stClass ){

                oElement = $(stClass, oElement).css("visibility", "hidden");

                return oElement;
            },

            /**
             * shows texts spans
             *
             * @return object
             */
            showTextSpan: function(oElement, stClass ){

                oElement = $( stClass, oElement );
                oElement.css("visibility", "visible");

                /*var targetObj = oElement.children(".promoPrice");
                var targetObjHeight = targetObj.nextAll("strong").height();

                targetObj.css({
                    "height" : targetObjHeight,
                    "line-height" : targetObjHeight + "px"
                });*/

                return oElement;
            }

    };

    $.widget("ui.oxSlider", oxSlider );

    /**
     * Compare list
     */
    oxCompareList = {
        options: {
            browserMozzila     : "mozilla",
            browserIE        : "msie",
            propertyHeight  : "height",
            classFirstCol     : ".firstCol",
            idDataTable     : "#compareDataDiv",
            elementTd         : "td",
            idFirstTr         : "#firstTr"
        },

        _create: function() {

            var self = this;
            var options = self.options;
            var iColumnCount = self.getColumnCount();
            var sBrowser = self.getBrowser();

            self.alignRows(sBrowser, iColumnCount);

        },

        /**
         * align first columns rows with data columns
         *
         * @return object
         */
        alignRows: function(sBrowser, iColumnCount)
        {
            var iNumberOfRow = 0;
            var self = this;

            $(self.options.classFirstCol).each(function(i){

                  var oFirstColumn = $(this);
                  var oOtherColumn = self.getOtherColumn(iColumnCount, iNumberOfRow);

                  var firstColumnHeight = self.getColumnHeight(sBrowser, oFirstColumn);
                  var otherColumnHeight = self.getColumnHeight(sBrowser, oOtherColumn);

                  if(firstColumnHeight >  otherColumnHeight){
                    self.setColumnHeight(oOtherColumn, firstColumnHeight);
                }else{
                    self.setColumnHeight(oFirstColumn, otherColumnHeight);
                }

                  iNumberOfRow++;
          });

        },

        /**
         * get colummns rows hight
         *
         * @return integer
         */
        getColumnHeight: function(sBrowser, oColumn)
        {
            if(sBrowser == this.options.browserMozzila){
                return oColumn.outerHeight();
            }
            else if(sBrowser == this.options.browserIE){

                return oColumn.innerHeight();
            }
            else {
                return oColumn.height();
            }
        },

        /**
         * set colummns rows hight
         *
         * @return object
         */
        setColumnHeight: function(oColumn, iHeight)
        {
            return $(oColumn).css(this.options.propertyHeight, iHeight);
        },

        /**
         * get colummns
         *
         * @return object
         */
        getOtherColumn: function(iColumnCount, iNumberOfRow)
        {
            return $( this.options.idDataTable + ' ' + this.options.elementTd + ':eq(' + iColumnCount * iNumberOfRow + ')');
        },

        /**
         * get browser
         *
         * @return object
         */
        getBrowser: function(){

            var sBrowser = this.options.browserMozzila;

            jQuery.each( jQuery.browser, function( i, val ) {
                if ( val == true ){
                   sBrowser = i.toString();
                 }
             });

            return sBrowser;
        },

        /**
         * get column Count
         *
         * @return object
         */
        getColumnCount: function()
        {
            return $( this.options.idFirstTr + '>' + this.options.elementTd).length;
        }
    };

    /**
     * Compare list widget
     */
    $.widget("ui.oxCompareList", oxCompareList );

    oxRating = {
        options: {
            reviewButton        : "writeNewReview",
            articleRatingValue    : "productRating",
            listManiaRatingValue: "recommListRating",
            currentRating        : "reviewCurrentRating",
            reviewForm             : "writeReview",
            reviewDiv             : "review",
            hideReviewButton    : true,
            openReviewForm         : true,
            ratingElement        : "a.ox-write-review"
        },

        _create: function() {

            var self     = this;
            var options = self.options;
            var el      = self.element;

            $( options.ratingElement, el ).each( function(i){

                $(this).click(function(){

                    self.setRatingValue( $('#' + options.articleRatingValue), i + 1 );

                    self.setRatingValue( $('#' + options.listManiaRatingValue), i + 1 );

                    self.setCurrentRating( $('#' + options.currentRating), ( ( i + 1 ) * 20) + '%' );

                    if ( options.openReviewForm ){
                        self.openReviewForm( $("#" + options.reviewForm) );
                    }

                    if ( options.hideReviewButton ){
                        self.hideReviewButton( $('#' + options.reviewButton) );
                    }
                    return false;
                });
            });
        },

        /**
         * set rating value on form element
         *
         * @return object
         */
        setRatingValue: function( oElement, value )
        {
            oElement.val(value);
            return oElement;
        },

        /**
         * set rating value on stars
         *
         * @return object
         */
        setCurrentRating: function( oElement, value )
        {
            oElement.width( value );
            return oElement;
        },


        /**
         * hide review button
         *
         * @return object
         */
        hideReviewButton: function( oButton )
        {
            oButton.hide();
            return oButton;
        },

        /**
         * open review form
         *
         * @return object
         */
        openReviewForm: function( oForm )
        {
            $( "html,body" ).animate( {
                scrollTop: $( "#" + this.options.reviewDiv ).offset().top
            }, 1000, function(){
                oForm.slideDown();
            } );

            return oForm;
        }

    };

    /**
     * Rating widget
     */
    $.widget("ui.oxRating", oxRating );


    oxArticleBox = {

        _create: function(){

            var self = this;
            var el   = self.element;

            //hide all
            $('.articleImage', el).hide();

            //open first
            $('.articleImage:first', el).show();
            $('.articleImage:first', el).addClass('showImage');

            $('.articleTitle', el).mouseover(function() {

                //if not opened
                if ($(this).prev().is(':hidden') == true) {

                    //closing opened
                    $('.articleTitle', el).removeClass('titleOn');
                    $('.showImage', el).slideUp(500);

                    //obening selected
                    $(this).addClass('titleOn');
                    $(this).prev().addClass('showImage')
                    $(this).prev().slideDown(500);
                }
            });
        }
    }

    $.widget( "ui.oxArticleBox", oxArticleBox );

    /**
     * Tree navigation
     */
    $.widget("ui.oxNavigation", {
        options: {
            expand: "ox-navigation-expand",
            active: "ox-navigation-active",
            toggle: "ox-navigation-toggle",
            branch: "li",
            link  : "a",
            block : "#content"
        },
        _create: function() {
            var self = this,
                options = self.options,
                el = self.element;

            self.element.css('min-height',$(options.block).height()+'px');

            self.element.addClass('ox-navigation');

            self.links = self.element.find( options.branch )
                .addClass('ox-navigation-branch');

            self.links = self.element.find( options.link )
                .addClass('ox-navigation-link')

                .bind( "mouseenter.navigation", function() {
                    $( this ).parents(options.branch).addClass( options.toggle);
                })
                .bind( "mouseleave.navigation", function() {
                    $( this ).parents(options.branch).removeClass( options.toggle );
                })

                .bind( "click.navigation", function() {
                    $('.ox-navigation-branch').removeClass(options.expand).removeClass(options.active);
                    $( this ).parents(options.branch).addClass(options.expand);
                    $( this ).parent(options.branch).addClass(options.active );
                });
        },
        destroy: function() {
            this.element.next().remove();
        }
    });

 // selectors..

    $( ".oxValidate" ).oxInputValidator();



} )( jQuery );