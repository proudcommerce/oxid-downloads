/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @package   out
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: oxinputvalidator.js 35529 2011-05-23 07:31:20Z vilma $
 */
( function( $ ) {

    oxInputValidator = {
            options: {
                classValid            : "oxValid",
                classInValid          : "oxInValid",
                errorParagraf         : "p.oxValidateError",
                errorMessageNotEmpty  : "js-oxError_notEmpty",
                errorMessageNotEmail  : "js-oxError_email",
                errorMessageShort     : "js-oxError_length",
                errorMessageNotEqual  : "js-oxError_match",
                metodValidate         : "js-oxValidate",
                metodValidateEmail    : "js-oxValidate_email",
                metodValidateNotEmpty : "js-oxValidate_notEmpty",
                metodValidateLength   : "js-oxValidate_length",
                metodValidateMatch    : "js-oxValidate_match",
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

                el.bind( "submit", function() {
                    return self.submitValidation(this);
                });
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
                        var sValue = $.trim( $( oInput ).val());
                        self.manageErrorMessage(oInput, sValue , oOptions.errorMessageNotEmpty);
                        blValidInput = sValue ? true : false;
                    }

                    if ( $( oInput ).hasClass( oOptions.metodValidateEmail ) && blValidInput ) {

                        if( $( oInput ).val() ) {
                            self.manageErrorMessage(oInput, self.isEmail( $( oInput ).val() ), oOptions.errorMessageNotEmail);
                            blValidInput = blValidInput && self.isEmail( $( oInput ).val() );
                        }
                    }

                    if ( $( oInput ).hasClass( oOptions.metodValidateLength ) && blValidInput ) {

                        var iLength = self.getLength( $( oInput ).closest(oOptions.form ));

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


} )( jQuery );