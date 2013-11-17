<?php
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
 * @package   core
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxemail.php 29935 2010-09-22 22:34:57Z alfonsas $
 */
/**
 * Includes PHP mailer class.
 */
require oxConfig::getInstance()->getConfigParam( 'sCoreDir' ) . "/phpmailer/class.phpmailer.php";


/**
 * Mailing manager.
 * Collects mailing configuration, other parameters, performs mailing functions
 * (newsletters, ordering, registration emails, etc.).
 * @package core
 */
class oxEmail extends PHPMailer
{
    /**
     * Default Smtp server port
     *
     * @var int
     */
    public $SMTP_PORT = 25;

    /**
     * Password reminder mail template
     *
     * @var string
     */
    protected $_sForgotPwdTemplate = "email_forgotpwd_html.tpl";

    /**
     * Password reminder plain mail template
     *
     * @var string
     */
    protected $_sForgotPwdTemplatePlain = "email_forgotpwd_plain.tpl";

    /**
     * Newsletter registration mail template
     *
     * @var string
     */
    protected $_sNewsletterOptInTemplate = "email_newsletteroptin_html.tpl";

    /**
     * Newsletter registration plain mail template
     *
     * @var string
     */
    protected $_sNewsletterOptInTemplatePlain = "email_newsletteroptin_plain.tpl";

    /**
     * Product suggest mail template
     *
     * @var string
     */
    protected $_sSuggestTemplate = "email_suggest_html.tpl";

    /**
     * Product suggest plain mail template
     *
     * @var string
     */
    protected $_sSuggestTemplatePlain = "email_suggest_plain.tpl";

    /**
     * Product suggest mail template
     *
     * @var string
     */
    protected $_sInviteTemplate = "email_invite_html.tpl";

    /**
     * Product suggest plain mail template
     *
     * @var string
     */
    protected $_sInviteTemplatePlain = "email_invite_plain.tpl";

    /**
     * Send order notification mail template
     *
     * @var string
     */
    protected $_sSenedNowTemplate = "email_sendednow_html.tpl";

    /**
     * Send order notification plain mail template
     *
     * @var string
     */
    protected $_sSenedNowTemplatePlain = "email_sendednow_plain.tpl";

    /**
     * Wishlist mail template
     *
     * @var string
     */
    protected $_sWishListTemplate = "email_wishlist_html.tpl";

    /**
     * Wishlist plain mail template
     *
     * @var string
     */
    protected $_sWishListTemplatePlain = "email_wishlist_plain.tpl";

    /**
     * Name of template used during registration
     *
     * @var string
     */
    protected $_sRegisterTemplate = "email_register_html.tpl";

    /**
     * Name of plain template used during registration
     *
     * @var string
     */
    protected $_sRegisterTemplatePlain = "email_register_plain.tpl";

    /**
     * Name of template used by reminder function (article).
     *
     * @var string
     */
    protected $_sReminderMailTemplate = "email_owner_reminder_html.tpl";

    /**
     * Order e-mail for customer HTML template
     *
     * @var string
     */
    protected $_sOrderUserTemplate          = "email_order_cust_html.tpl";

    /**
     * Order e-mail for customer plain text template
     *
     * @var string
     */
    protected $_sOrderUserPlainTemplate     = "email_order_cust_plain.tpl";

    /**
     * Order e-mail for shop owner HTML template
     *
     * @var string
     */
    protected $_sOrderOwnerTemplate         = "email_order_owner_html.tpl";

    /**
     * Order e-mail for shop owner plain text template
     *
     * @var string
     */
    protected $_sOrderOwnerPlainTemplate    = "email_order_owner_plain.tpl";

    // #586A - additional templates for more customizable subjects

    /**
     * Order e-mail subject for customer template
     *
     * @var string
     */
    protected $_sOrderUserSubjectTemplate   = "email_order_cust_subj.tpl";

    /**
     * Order e-mail subject for shop owner template
     *
     * @var string
     */
    protected $_sOrderOwnerSubjectTemplate  = "email_order_owner_subj.tpl";

    /**
     * Price alarm e-mail for shop owner template
     *
     * @var string
     */
    protected $_sOwnerPricealarmTemplate    = "email_pricealarm_owner.tpl";

    /**
     * Language specific viewconfig object array containing view data, view confic and shop object
     *
     * @var array
     */
    protected $_aShops = array();

    /**
     * Add inline images to mail
     *
     * @var bool
     */
    protected $_blInlineImgEmail = null;

    /**
     * Array of recipient email addresses
     *
     * @var array
     */
    protected $_aRecipients = array();

    /**
     * Array of reply addresses used
     *
     * @var array
     */
    protected $_aReplies = array();

    /**
     * Attachment info array
     *
     * @var array
     */
    protected $_aAttachments = array();

    /**
     * Smarty instance
     *
     * @var smarty
     */
    protected $_oSmarty = null;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        //enabling exception handling in phpmailer class
        parent::__construct( true );

        $myConfig = $this->getConfig();

        $this->_setMailerPluginDir();
        $this->setSmtp();

        $this->setUseInlineImages( $myConfig->getConfigParam('blInlineImgEmail') );
        $this->setMailWordWrap( 100 );

        $this->isHtml( true );
        $this->setLanguage( "en", $myConfig->getConfigParam( 'sShopDir' )."/core/phpmailer/language/");
    }

    /**
     * Only used for convenience in UNIT tests by doing so we avoid
     * writing extended classes for testing protected or private methods
     *
     * @param string $sMethod Methods name
     * @param array  $aArgs   Argument array
     *
     * @throws oxSystemComponentException Throws an exception if the called method does not exist or is not accessable in current class
     *
     * @return string
     */
    public function __call( $sMethod, $aArgs )
    {
        if ( defined( 'OXID_PHP_UNIT' ) ) {
            if ( substr( $sMethod, 0, 4) == "UNIT" ) {
                $sMethod = str_replace( "UNIT", "_", $sMethod );
            }
            if ( method_exists( $this, $sMethod)) {
                return call_user_func_array( array( & $this, $sMethod ), $aArgs );
            }
        }

        throw new oxSystemComponentException( "Function '$sMethod' does not exist or is not accessible! (" . get_class($this) . ")".PHP_EOL);
    }

    /**
     * oxConfig instance getter
     *
     * @return oxconfig
     */
    public function getConfig()
    {
        if ( $this->_oConfig == null ) {
            $this->_oConfig = oxConfig::getInstance();
        }

        return $this->_oConfig;
    }

    /**
     * oxConfig instance setter
     *
     * @param oxConfig $oConfig config object
     *
     * @return null
     */
    public function setConfig( $oConfig )
    {
        $this->_oConfig = $oConfig;
    }


    /**
     * Smarty instance getter
     *
     * @return smarty
     */
    protected function _getSmarty()
    {
        if ( $this->_oSmarty === null ) {
            $this->_oSmarty = oxUtilsView::getInstance()->getSmarty();
        }
        return $this->_oSmarty;
    }

    /**
     * Otputs email fields throught email output processor, includes images, and initiate email sending
     * If fails to send mail via smtp, tryes to send via mail(). On failing to send, sends mail to
     * shop administrator about failing mail sending
     *
     * @return bool
     */
    public function send()
    {
        // if no recipients found, skipping sending
        if ( count( $this->getRecipient() ) < 1 ) {
            return false;
        }

        $myConfig = $this->getConfig();
        $this->setCharSet();

        if ( $this->_getUseInlineImages() ) {
            $this->_includeImages( $myConfig->getImageDir(), $myConfig->getNoSSLImageDir( isAdmin() ), $myConfig->getDynImageDir(),
                                   $myConfig->getAbsImageDir(), $myConfig->getAbsDynImageDir());
        }

        $this->_makeOutputProcessing();

        // try to send mail via SMTP
        if ( $this->getMailer() == 'smtp' ) {
            $blRet = $this->_sendMail();

            // if sending failed, try to send via mail()
            if ( !$blRet ) {
                $this->setMailer( 'mail' );
                $blRet = $this->_sendMail();
            }
        } else {
            // sending mail via mail()
            $this->setMailer( 'mail' );
            $blRet = $this->_sendMail();
        }

        if ( !$blRet ) {
            // failed sending, giving up, trying to send notification to shop owner
            $this->_sendMailErrorMsg();
        }

        return $blRet;
    }

    /**
     * Sets smtp parameters depending on the protocol used
     * returns smtp url which should be used for fsockopen
     *
     * @param string $sUrl initial smtp
     *
     * @return string
     */
    protected function _setSmtpProtocol($sUrl)
    {
        $sProtocol = '';
        $sSmtpHost = $sUrl;
        $aMatch = array();
        if ( getStr()->preg_match('@^([0-9a-z]+://)?(.*)$@i', $sUrl, $aMatch ) ) {
            if ($aMatch[1]) {
                if (($aMatch[1] == 'ssl://') || ($aMatch[1] == 'tls://')) {
                    $this->set( "SMTPSecure", substr($aMatch[1], 0, 3) );
                } else {
                    $sProtocol = $aMatch[1];
                }
            }
            $sSmtpHost = $aMatch[2];
        }

        return $sProtocol.$sSmtpHost;
    }

    /**
     * Sets SMTP mailer parameters, such as user name, password, location.
     *
     * @param oxShop $oShop Object, that keeps base shop info
     *
     * @return null
     */
    public function setSmtp( $oShop = null )
    {
        $myConfig = $this->getConfig();
        $oShop = ( $oShop ) ? $oShop : $this->_getShop();

        $sSmtpUrl = $this->_setSmtpProtocol($oShop->oxshops__oxsmtp->value);

        if ( !$this->_isValidSmtpHost( $sSmtpUrl ) ) {
            $this->setMailer( "mail" );
            return;
        }

        $this->setHost( $sSmtpUrl );
        $this->setMailer( "smtp" );

        if ( $oShop->oxshops__oxsmtpuser->value ) {
            $this->_setSmtpAuthInfo( $oShop->oxshops__oxsmtpuser->value, $oShop->oxshops__oxsmtppwd->value );
        }

        if ( $myConfig->getConfigParam( 'iDebug' ) == 6 ) {
            $this->_setSmtpDebug( true );
        }
    }

    /**
     * Checks if smtp host is valid (tries to connect to it)
     *
     * @param string $sSmtpHost currently used smtp server host name
     *
     * @return bool
     */
    protected function _isValidSmtpHost( $sSmtpHost )
    {
        $blIsSmtp = false;
        if ( $sSmtpHost ) {
            $sSmtpPort = $this->SMTP_PORT;
            $aMatch = array();
            if ( getStr()->preg_match('@^(.*?)(:([0-9]+))?$@i', $sSmtpHost, $aMatch)) {
                $sSmtpHost = $aMatch[1];
                $sSmtpPort = (int)$aMatch[3];
                if (!$sSmtpPort) {
                    $sSmtpPort = $this->SMTP_PORT;
                }
            }
            if ( $blIsSmtp = (bool) ( $rHandle = @fsockopen( $sSmtpHost, $sSmtpPort, $iErrNo, $sErrStr, 30 )) ) {
                // closing connection ..
                fclose( $rHandle );
            }
        }

        return $blIsSmtp;
    }

    /**
     * Sets mailer additional settings and sends ordering mail to user.
     * Returns true on success.
     *
     * @param oxOrder $oOrder   Order object
     * @param string  $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendOrderEmailToUser( $oOrder, $sSubject = null )
    {
        $myConfig = $this->getConfig();

        // add user defined stuff if there is any
        $oOrder = $this->_addUserInfoOrderEMail( $oOrder );

        //set mail params (from, fromName, smtp)
        $oShop = $this->_getShop();
        $this->_setMailParams( $oShop );

        // P
        // setting some deprecated variables
        $oOrder->oDelSet = $oOrder->getDelSet();

        $oUser = $oOrder->getOrderUser();
        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", oxLang::getInstance()->translateString("charset"));
        $oSmarty->assign( "order", $oOrder);
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $myConfig->getActiveView() );
        $oSmarty->assign( "user", $oUser );
        $oSmarty->assign( "currency", $myConfig->getActShopCurrencyObject() );
        $oSmarty->assign( "basket", $oOrder->getBasket() );
        $oSmarty->assign( "payment", $oOrder->getPayment() );
        if ( $oUser ) {
            $oSmarty->assign( "reviewuserhash", $oUser->getReviewUserHash( $oUser->getId() ) );
        }
        $oSmarty->assign( "paymentinfo", $myConfig->getActiveShop() );

        //deprecated vars
        $oSmarty->assign( "iswishlist", true );
        $oSmarty->assign( "isreview", true );

        if ( $aVoucherList = $oOrder->getVoucherList() ) {
            $oSmarty->assign( "vouchers", $aVoucherList );
        }

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $oSmarty->assign( $key, $val );
        }

        $this->setBody( $oSmarty->fetch( $this->_sOrderUserTemplate ) );
        $this->setAltBody( $oSmarty->fetch( $this->_sOrderUserPlainTemplate ) );

        // #586A
        if ( $sSubject === null ) {
            if ( $oSmarty->template_exists( $this->_sOrderUserSubjectTemplate) ) {
                $sSubject = $oSmarty->fetch( $this->_sOrderUserSubjectTemplate );
            } else {
                $sSubject = $oShop->oxshops__oxordersubject->getRawValue()." (#".$oOrder->oxorder__oxordernr->value.")";
            }
        }

        $this->setSubject( $sSubject );

        $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );

        $blSuccess = $this->send();

        return $blSuccess;
    }

    /**
     * Sets mailer additional settings and sends ordering mail to shop owner.
     * Returns true on success.
     *
     * @param oxOrder $oOrder   Order object
     * @param string  $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendOrderEmailToOwner( $oOrder, $sSubject = null )
    {
        $myConfig = $this->getConfig();

        $oShop = $this->_getShop();

        // cleanup
        $this->_clearMailer();

        // add user defined stuff if there is any
        $oOrder = $this->_addUserInfoOrderEMail( $oOrder );

        // send confirmation to shop owner
        $sFullName = $oOrder->getOrderUser()->oxuser__oxfname->getRawValue() . " " . $oOrder->getOrderUser()->oxuser__oxlname->getRawValue();
        $this->setFrom( $oOrder->getOrderUser()->oxuser__oxusername->value, $sFullName );

        $oLang = oxLang::getInstance();
        $iOrderLang = $oLang->getObjectTplLanguage();

        // if running shop language is different from admin lang. set in config
        // we have to load shop in config language
        if ( $oShop->getLanguage() != $iOrderLang ) {
            $oShop = $this->_getShop( $iOrderLang );
        }

        $this->setSmtp( $oShop );

        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", $oLang->translateString("charset"));
        $oSmarty->assign( "order", $oOrder );
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $myConfig->getActiveView() );
        $oSmarty->assign( "user", $oOrder->getOrderUser() );
        $oSmarty->assign( "currency", $myConfig->getActShopCurrencyObject() );
        $oSmarty->assign( "basket", $oOrder->getBasket() );
        $oSmarty->assign( "payment", $oOrder->getPayment() );

        //deprecated var
        $oSmarty->assign( "iswishlist", true);

        if( $oOrder->getVoucherList() )
            $oSmarty->assign( "vouchers", $oOrder->getVoucherList() );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray($oSmarty->get_template_vars(), "oxemail");
        foreach ($aNewSmartyArray as $key => $val)
            $oSmarty->assign( $key, $val );

        $this->setBody( $oSmarty->fetch( $myConfig->getTemplatePath( $this->_sOrderOwnerTemplate, false ) ) );
        $this->setAltBody( $oSmarty->fetch( $myConfig->getTemplatePath( $this->_sOrderOwnerPlainTemplate, false ) ) );

        //Sets subject to email
        // #586A
        if ( $sSubject === null ) {
            if ( $oSmarty->template_exists( $this->_sOrderOwnerSubjectTemplate) ) {
                $sSubject = $oSmarty->fetch( $this->_sOrderOwnerSubjectTemplate );
            } else {
                 $sSubject = $oShop->oxshops__oxordersubject->getRawValue()." (#".$oOrder->oxorder__oxordernr->value.")";
            }
        }

        $this->setSubject( $sSubject );
        $this->setRecipient( $oShop->oxshops__oxowneremail->value, $oLang->translateString("order") );

        if ( $oOrder->getOrderUser()->oxuser__oxusername->value != "admin" )
            $this->setReplyTo( $oOrder->getOrderUser()->oxuser__oxusername->value, $sFullName );

        $blSuccess = $this->send();

        // add user history
        $oRemark = oxNew( "oxremark" );
        $oRemark->oxremark__oxtext      = new oxField($this->getAltBody(), oxField::T_RAW);
        $oRemark->oxremark__oxparentid  = new oxField($oOrder->getOrderUser()->getId(), oxField::T_RAW);
        $oRemark->oxremark__oxtype      = new oxField("o", oxField::T_RAW);
        $oRemark->save();


        if ( $myConfig->getConfigParam( 'iDebug' ) == 6) {
            oxUtils::getInstance()->showMessageAndExit( "" );
        }

        return $blSuccess;
    }

    /**
     * Sets mailer additional settings and sends registration mail to user.
     * Returns true on success.
     *
     * @param oxUser $oUser    user object
     * @param string $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendRegisterConfirmEmail( $oUser, $sSubject = null )
    {
        // setting content ident
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "contentident", "oxregisteraltemail" );
        $oSmarty->assign( "contentplainident", "oxregisterplainaltemail" );

        // sending email
        return $this->sendRegisterEmail( $oUser, $sSubject );
    }

    /**
     * Sets mailer additional settings and sends registration mail to user.
     * Returns true on success.
     *
     * @param oxUser $oUser    user object
     * @param string $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendRegisterEmail( $oUser, $sSubject = null )
    {
        // add user defined stuff if there is any
        $oUser = $this->_addUserRegisterEmail( $oUser );

        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp )
        $this->_setMailParams( $oShop );

        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $this->getConfig()->getActiveView() );
        $oSmarty->assign( "user", $oUser );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $oSmarty->assign( $key, $val );
        }

        $this->setBody( $oSmarty->fetch( $this->_sRegisterTemplate ) );
        $this->setAltBody( $oSmarty->fetch( $this->_sRegisterTemplatePlain ) );

        $this->setSubject( ( $sSubject !== null ) ? $sSubject : $oShop->oxshops__oxregistersubject->getRawValue() );

        $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "forgot password" mail to user.
     * Returns true on success.
     *
     * @param string $sEmailAddress user email address
     * @param string $sSubject      user defined subject [optional]
     *
     * @return bool
     */
    public function sendForgotPwdEmail( $sEmailAddress, $sSubject = null )
    {
        $myConfig = $this->getConfig();
        $oDb = oxDb::getDb();

        // shop info
        $oShop = $this->_getShop();

        // add user defined stuff if there is any
        $oShop = $this->_addForgotPwdEmail( $oShop);

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        // user
        $sWhere = "oxuser.oxactive = 1 and oxuser.oxusername = ".$oDb->quote( $sEmailAddress )." and oxuser.oxpassword != ''";
        $sOrder = "";
        if ( $myConfig->getConfigParam( 'blMallUsers' )) {
            $sOrder = "order by oxshopid = '".$oShop->getId()."' desc";
        } else {
            $sWhere .= " and oxshopid = '".$oShop->getId()."'";
        }

        $sSelect = "select oxid from oxuser where $sWhere $sOrder";
        if ( ( $sOxId = $oDb->getOne( $sSelect ) ) ) {

            $oUser = oxNew( 'oxuser' );
            if ( $oUser->load($sOxId) ) {
                // create messages
                $oSmarty = $this->_getSmarty();
                $oSmarty->assign( "charset", oxLang::getInstance()->translateString("charset"));
                $oSmarty->assign( "shop", $oShop );
                $oSmarty->assign( "oViewConf", $oShop );
                $oSmarty->assign( "oView", $myConfig->getActiveView() );
                $oSmarty->assign( "user", $oUser );

                $oOutputProcessor = oxNew( "oxoutput" );
                $aNewSmartyArray  = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );

                foreach ( $aNewSmartyArray as $key => $val ) {
                    $oSmarty->assign($key, $val);
                }

                $this->setBody( $oSmarty->fetch( $this->_sForgotPwdTemplate ) );
                $this->setAltBody( $oSmarty->fetch( $this->_sForgotPwdTemplatePlain ) );

                //sets subject of email
                $this->setSubject( ( $sSubject !== null ) ? $sSubject : $oShop->oxshops__oxforgotpwdsubject->getRawValue() );

                $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

                $this->setRecipient( $sEmailAddress, $sFullName );
                $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );

                return $this->send();
            }
        }

        return false;
    }

    /**
     * Sets mailer additional settings and sends contact info mail to user.
     * Returns true on success.
     *
     * @param string $sEmailAddress Email address
     * @param string $sSubject      Email subject
     * @param string $sMessage      Email message text
     *
     * @return bool
     */
    public function sendContactMail( $sEmailAddress = null, $sSubject = null, $sMessage = null )
    {

        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        $this->setBody( $sMessage );
        $this->setSubject( $sSubject );

        $this->setRecipient( $oShop->oxshops__oxinfoemail->value, "" );
        $this->setFrom( $sEmailAddress, "" );
        $this->setReplyTo( $sEmailAddress, "" );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "NewsletterDBOptInMail" mail to user.
     * Returns true on success.
     *
     * @param oxUser $oUser    user object
     * @param string $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendNewsletterDbOptInMail( $oUser, $sSubject = null )
    {
        $oLang = oxLang::getInstance();

        // add user defined stuff if there is any
        $oUser = $this->_addNewsletterDbOptInMail( $oUser );

        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", $oLang->translateString("charset"));
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $this->getConfig()->getActiveView() );
        $oSmarty->assign( "subscribeLink", $this->_getNewsSubsLink($oUser->oxuser__oxid->value) );
        $oSmarty->assign( "user", $oUser );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );
        foreach ( $aNewSmartyArray as $key => $val ) {
            $oSmarty->assign( $key, $val );
        }

        $this->setBody( $oSmarty->fetch( $this->_sNewsletterOptInTemplate ) );
        $this->setAltBody( $oSmarty->fetch( $this->_sNewsletterOptInTemplatePlain ) );
        $this->setSubject( ( $sSubject !== null ) ? $sSubject : oxLang::getInstance()->translateString("EMAIL_NEWSLETTERDBOPTINMAIL_SUBJECT") . " " . $oShop->oxshops__oxname->getRawValue() );

        $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setFrom( $oShop->oxshops__oxinfoemail->value, $oShop->oxshops__oxname->getRawValue() );
        $this->setReplyTo( $oShop->oxshops__oxinfoemail->value, $oShop->oxshops__oxname->getRawValue() );

        return $this->send();
    }

    /**
     * Returns newsletter subscription link
     *
     * @param string $sId user id
     *
     * @return string $sUrl
     */
    protected function _getNewsSubsLink( $sId )
    {
        $myConfig = $this->getConfig();
        $iActShopLang = $myConfig->getActiveShop()->getLanguage();

        $sUrl = $myConfig->getShopHomeURL().'cl=newsletter&amp;fnc=addme&amp;uid='.$sId;
        $sUrl.= ( $iActShopLang ) ? '&amp;lang='.$iActShopLang : "";
        return $sUrl;
    }

    /**
     * Sets mailer additional settings and sends "newsletter" mail to user.
     * Returns true on success.
     *
     * @param oxNewsletter $oNewsLetter newsletter object
     * @param oxUser       $oUser       user object
     * @param string       $sSubject    user defined subject [optional]
     *
     * @return bool
     */
    public function sendNewsletterMail( $oNewsLetter, $oUser, $sSubject = null )
    {
        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        $sBody = $oNewsLetter->getHtmlText();

        if ( !empty($sBody) ) {
            $this->setBody( $sBody );
            $this->setAltBody( $oNewsLetter->getPlainText() );
        } else {
            $this->isHtml( false );
            $this->setBody( $oNewsLetter->getPlainText() );
        }

        $this->setSubject( ( $sSubject !== null ) ? $sSubject : $oNewsLetter->oxnewsletter__oxtitle->getRawValue() );

        $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();
        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "SuggestMail" mail to user.
     * Returns true on success.
     *
     * @param object $oParams  Mailing parameters object
     * @param object $oProduct Product object
     *
     * @return bool
     */
    public function sendSuggestMail( $oParams, $oProduct )
    {
        $myConfig = $this->getConfig();

        //sets language of shop
        $iCurrLang = $myConfig->getActiveShop()->getLanguage();

        // shop info
        $oShop = $this->_getShop( $iCurrLang );

        //sets language to article
        if ( $oProduct->getLanguage() != $iCurrLang ) {
            $oProduct->setLanguage( $iCurrLang );
            $oProduct->load( $oProduct->getId() );
        }

        // mailer stuff
        $this->setFrom( $oParams->send_email, $oParams->send_name );
        $this->setSMTP();

        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $myConfig->getActiveView() );
        $oSmarty->assign( "userinfo", $oParams );
        $oSmarty->assign( "product", $oProduct );

        $sArticleUrl = $oProduct->getLink();

        //setting recommended user id
        if ( $myConfig->getActiveView()->isActive('Invitations') && $oActiveUser = $oShop->getUser() ) {
            $sArticleUrl  = oxUtilsUrl::getInstance()->appendParamSeparator( $sArticleUrl );
            $sArticleUrl .= "su=" . $oActiveUser->getId();
        }

        $oSmarty->assign( "sArticleUrl", $sArticleUrl );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $oSmarty->assign( $key, $val );
        }

        $this->setBody( $oSmarty->fetch( $this->_sSuggestTemplate ) );
        $this->setAltBody( $oSmarty->fetch( $this->_sSuggestTemplatePlain ) );
        $this->setSubject( $oParams->send_subject );

        $this->setRecipient( $oParams->rec_email, $oParams->rec_name );
        $this->setReplyTo( $oParams->send_email, $oParams->send_name );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "InviteMail" mail to user.
     * Returns true on success.
     *
     * @param object $oParams Mailing parameters object
     *
     * @return bool
     */
    public function sendInviteMail( $oParams )
    {
        $myConfig = $this->getConfig();

        //sets language of shop
        $iCurrLang = $myConfig->getActiveShop()->getLanguage();

        // shop info
        $oShop = $this->_getShop( $iCurrLang );

        // mailer stuff
        $this->setFrom( $oParams->send_email, $oParams->send_name );
        $this->setSMTP();

        // create messages
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $myConfig->getActiveView() );
        $oSmarty->assign( "userinfo", $oParams );
        $oSmarty->assign( "sShopUrl", $myConfig->getShopCurrentUrl() );

        $sHomeUrl = $oShop->getHomeLink();

        //setting recommended user id
        if ( $myConfig->getActiveView()->isActive('Invitations') && $oActiveUser = $oShop->getUser() ) {
            $sHomeUrl  = oxUtilsUrl::getInstance()->appendParamSeparator( $sHomeUrl );
            $sHomeUrl .= "su=" . $oActiveUser->getId();
        }

        $oSmarty->assign( "sHomeUrl", $sHomeUrl );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $oSmarty->assign( $key, $val );
        }

        $this->setBody( $oSmarty->fetch( $this->_sInviteTemplate ) );

        $this->setAltBody( $oSmarty->fetch( $this->_sInviteTemplatePlain ) );
        $this->setSubject( $oParams->send_subject );

        if ( is_array($oParams->rec_email) && count($oParams->rec_email) > 0  ) {
            foreach ( $oParams->rec_email as $sEmail ) {
                if ( !empty( $sEmail ) ) {
                    $this->setRecipient( $sEmail );
                    $this->setReplyTo( $oParams->send_email, $oParams->send_name );
                    $this->send();
                    $this->clearAllRecipients();
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Sets mailer additional settings and sends "SendedNowMail" mail to user.
     * Returns true on success.
     *
     * @param oxOrder $oOrder   order object
     * @param string  $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendSendedNowMail( $oOrder, $sSubject = null )
    {
        $myConfig = $this->getConfig();

        $iOrderLang = (int) ( isset( $oOrder->oxorder__oxlang->value ) ? $oOrder->oxorder__oxlang->value : 0 );

        // shop info
        $oShop = $this->_getShop( $iOrderLang );

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        //override default wrap
        //$this->setMailWordWrap( 0 );

        //create messages
        $oLang = oxLang::getInstance();
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", $oLang->translateString("charset"));
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $myConfig->getActiveView() );
        $oSmarty->assign( "order", $oOrder );
        $oSmarty->assign( "currency", $myConfig->getActShopCurrencyObject() );

        //deprecated var
        $oSmarty->assign( "isreview", true);
        $oUser = oxNew( 'oxuser' );
        $oSmarty->assign( "reviewuserhash", $oUser->getReviewUserHash($oOrder->oxorder__oxuserid->value) );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $oSmarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $oSmarty->assign( $key, $val );
        }

        // dodger #1469 - we need to patch security here as we do not use standard template dir, so smarty stops working
        $aStore['INCLUDE_ANY'] = $oSmarty->security_settings['INCLUDE_ANY'];
        //V send email in order language
        $iOldTplLang = $oLang->getTplLanguage();
        $iOldBaseLang = $oLang->getTplLanguage();
        $oLang->setTplLanguage( $iOrderLang );
        $oLang->setBaseLanguage( $iOrderLang );

        $oSmarty->security_settings['INCLUDE_ANY'] = true;

        $this->setBody( $oSmarty->fetch( $myConfig->getTemplatePath( $this->_sSenedNowTemplate, false ) ) );
        $this->setAltBody( $oSmarty->fetch( $myConfig->getTemplatePath( $this->_sSenedNowTemplatePlain, false ) ) );
        $oLang->setTplLanguage( $iOldTplLang );
        $oLang->setBaseLanguage( $iOldBaseLang );
        // set it back
        $oSmarty->security_settings['INCLUDE_ANY'] = $aStore['INCLUDE_ANY'] ;

        //Sets subject to email
        $this->setSubject( ( $sSubject !== null ) ? $sSubject : $oShop->oxshops__oxsendednowsubject->getRawValue() );

        $sFullName = $oOrder->oxorder__oxbillfname->getRawValue() . " " . $oOrder->oxorder__oxbilllname->getRawValue();

        $this->setRecipient( $oOrder->oxorder__oxbillemail->value, $sFullName );
        $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );
        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends backuped data to user.
     * Returns true on success.
     *
     * @param array  $aAttFiles     Array of file names to attach
     * @param string $sAttPath      Path to files to attach
     * @param string $sEmailAddress Email address
     * @param string $sSubject      Email subject
     * @param string $sMessage      Email body message
     * @param array  &$aStatus      Pointer to mailing status array
     * @param array  &$aError       Pointer to error status array
     *
     * @return bool
     */
    public function sendBackupMail( $aAttFiles, $sAttPath, $sEmailAddress, $sSubject, $sMessage, &$aStatus, &$aError )
    {

        /* P
        $sMailMessage = $myConfig->getConfigParam( 'sMailMessage' );
        $sMessage = ( !empty($sMailMessage) ) ? $sMailMessage : "" ;
        */

        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        $this->setBody( $sMessage );
        $this->setSubject( $sSubject );

        $this->setRecipient( $oShop->oxshops__oxinfoemail->value, "" );
        $sEmailAddress = $sEmailAddress ? $sEmailAddress : $oShop->oxshops__oxowneremail->value;

        $this->setFrom( $sEmailAddress, "" );
        $this->setReplyTo( $sEmailAddress, "" );

        //attaching files
        $blAttashSucc = true;
        $sAttPath = oxUtilsFile::getInstance()->normalizeDir($sAttPath);
        foreach ( $aAttFiles as $iNum => $sAttFile ) {
            if ( file_exists($sAttPath . $sAttFile) && is_file($sAttPath . $sAttFile) ) {
                $blAttashSucc = $this->addAttachment( $sAttPath, $sAttFile );
            } else {
                $blAttashSucc = false;
                $aError[] = array( 5, $sAttFile );   //"Error: backup file $sAttFile not found";
            }
        }

        if ( !$blAttashSucc ) {
            $aError[] = array( 4, "" );   //"Error: backup files was not sent to email ...";
            $this->clearAttachments();
            return false;
        }

        $aStatus[] = 3;     //"Mailing backup files ...";
        $blSend = $this->send();
        $this->clearAttachments();

        return $blSend;
    }

    /**
     * Basic wrapper for email message sending with default parameters from the oxbaseshop.
     * Returns true on success.
     *
     * @param mixed  $sTo      Recipient or an array of the recipients
     * @param string $sSubject Mail subject
     * @param string $sBody    Mmail body
     *
     * @return bool
     */
    public function sendEmail( $sTo, $sSubject, $sBody )
    {
        //set mail params (from, fromName, smtp)
        $this->_setMailParams();

        if ( is_array($sTo) ) {
            foreach ($sTo as $sAddress) {
                $this->setRecipient( $sAddress, "" );
                $this->setReplyTo( $sAddress, "" );
            }
        } else {
            $this->setRecipient( $sTo, "" );
            $this->setReplyTo( $sTo, "" );
        }

        //may be changed later
        $this->isHtml( false );

        $this->setSubject( $sSubject );
        $this->setBody( $sBody );

        return $this->send();
    }

    /**
     * Sends reminder email to shop owner.
     *
     * @param array  $aBasketContents array of objects to pass to template
     * @param string $sSubject        user defined subject [optional]
     *
     * @return bool
     */
    public function sendStockReminder( $aBasketContents, $sSubject = null )
    {
        $blSend = false;

        $oArticleList = oxNew( "oxarticlelist" );
        $oArticleList->loadStockRemindProducts( $aBasketContents );

        // nothing to remind?
        if ( $oArticleList->count() ) {
            $oShop = $this->_getShop();

            //set mail params (from, fromName, smtp... )
            $this->_setMailParams( $oShop );
            $oLang = oxLang::getInstance();

            $oSmarty = $this->_getSmarty();
            $oSmarty->assign( "charset", $oLang->translateString( "charset" ) );
            $oSmarty->assign( "shop", $oShop );
            $oSmarty->assign( "oViewConf", $oShop );
            $oSmarty->assign( "oView", $this->getConfig()->getActiveView() );
            $oSmarty->assign( "articles", $oArticleList );

            $this->setRecipient( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
            $this->setFrom( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
            $this->setBody( $oSmarty->fetch( $this->getConfig()->getTemplatePath( $this->_sReminderMailTemplate, false ) ) );
            $this->setAltBody( "" );
            $this->setSubject( ( $sSubject !== null ) ? $sSubject : $oLang->translateString( 'EMAIL_STOCKREMINDER_SUBJECT' ) );

            $blSend = $this->send();
        }

        return $blSend;
    }

    /**
     * Sets mailer additional settings and sends "WishlistMail" mail to user.
     * Returns true on success.
     *
     * @param object $oParams Mailing parameters object
     *
     * @return bool
     */
    public function sendWishlistMail( $oParams )
    {
        $myConfig = $this->getConfig();

        $this->_clearMailer();

        // shop info
        $oShop = $this->_getShop();

        // mailer stuff
        $this->setFrom( $oParams->send_email, $oParams->send_name );
        $this->setSMTP();

        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $myConfig->getActiveView() );
        $oSmarty->assign( "userinfo", $oParams );

        $this->setBody( $oSmarty->fetch( $this->_sWishListTemplate ) );
        $this->setAltBody( $oSmarty->fetch( $this->_sWishListTemplatePlain ) );
        $this->setSubject( $oParams->send_subject );

        $this->setRecipient( $oParams->rec_email, $oParams->rec_name );
        $this->setReplyTo( $oParams->send_email, $oParams->send_name );

        return $this->send();
    }

    /**
     * Sends a notification to the shop owner that pricealarm was subscribed.
     * Returns true on success.
     *
     * @param array        $aParams  Parameters array
     * @param oxpricealarm $oAlarm   oxPriceAlarm object
     * @param string       $sSubject user defined subject [optional]
     *
     * @return bool
     */
    public function sendPriceAlarmNotification( $aParams, $oAlarm, $sSubject = null )
    {
        $this->_clearMailer();
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        $iAlarmLang = $oAlarm->oxpricealarm__oxlang->value;

        $oArticle = oxNew( "oxarticle" );
        $oArticle->setSkipAbPrice( true );
        $oArticle->loadInLang( $iAlarmLang, $aParams['aid'] );

        $oCur  = $this->getConfig()->getActShopCurrencyObject();
        $oLang = oxLang::getInstance();

        // create messages
        $oSmarty = $this->_getSmarty();
        $oSmarty->assign( "shop", $oShop );
        $oSmarty->assign( "oViewConf", $oShop );
        $oSmarty->assign( "oView", $this->getConfig()->getActiveView() );
        $oSmarty->assign( "product", $oArticle );
        $oSmarty->assign( "email", $aParams['email']);
        $oSmarty->assign( "bidprice", $oLang->formatCurrency( $oAlarm->oxpricealarm__oxprice->value, $oCur ) );
        $oSmarty->assign( "currency", $oCur );

        $this->setRecipient( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );
        $this->setSubject( ( $sSubject !== null ) ? $sSubject : $oLang->translateString( 'EMAIL_PRICEALARM_OWNER_SUBJECT', $iAlarmLang ) . " " . $oArticle->oxarticles__oxtitle->getRawValue() );
        $this->setBody( $oSmarty->fetch( $this->_sOwnerPricealarmTemplate ) );
        $this->setFrom( $aParams['email'], "" );
        $this->setReplyTo( $aParams['email'], "" );

        return $this->send();
    }

    /**
     * Checks for external images and embeds them to email message if possible
     *
     * @param string $sImageDir       Images directory url
     * @param string $sImageDirNoSSL  Images directory url (no SSL)
     * @param string $sDynImageDir    Path to Dyn images
     * @param string $sAbsImageDir    Absolute path to images
     * @param string $sAbsDynImageDir Absolute path to Dyn images
     *
     * @return null
     */
    protected function _includeImages($sImageDir = null, $sImageDirNoSSL = null, $sDynImageDir = null, $sAbsImageDir = null, $sAbsDynImageDir = null)
    {
        $sBody = $this->getBody();
        if (preg_match_all('/<\s*img\s+[^>]*?src[\s]*=[\s]*[\'"]?([^[\'">]]+|.*?)?[\'">]/i', $sBody, $matches, PREG_SET_ORDER)) {

            $oFileUtils = oxUtilsFile::getInstance();
            $blReSetBody = false;

            // preparing imput
            $sDynImageDir = $oFileUtils->normalizeDir( $sDynImageDir );
            $sImageDir = $oFileUtils->normalizeDir( $sImageDir );
            $sImageDirNoSSL = $oFileUtils->normalizeDir( $sImageDirNoSSL );

            if (is_array($matches) && count($matches)) {
                $aImageCache = array();
                $myUtils = oxUtils::getInstance();
                $myUtilsObject = oxUtilsObject::getInstance();

                foreach ($matches as $aImage) {

                    $image = $aImage[1];
                    $sFileName = '';
                    if ( strpos( $image, $sDynImageDir ) === 0 ) {
                        $sFileName = $oFileUtils->normalizeDir( $sAbsDynImageDir ) . str_replace( $sDynImageDir, '', $image );
                    } elseif ( strpos( $image, $sImageDir ) === 0 ) {
                        $sFileName = $oFileUtils->normalizeDir( $sAbsImageDir ) . str_replace( $sImageDir, '', $image );
                    } elseif ( strpos( $image, $sImageDirNoSSL ) === 0 ) {
                        $sFileName = $oFileUtils->normalizeDir( $sAbsImageDir ) . str_replace( $sImageDirNoSSL, '', $image );
                    }

                    if ($sFileName && @is_file($sFileName)) {
                        $sCId = '';
                        if ( isset( $aImageCache[$sFileName] ) && $aImageCache[$sFileName] ) {
                            $sCId = $aImageCache[$sFileName];
                        } else {
                            $sCId = $myUtilsObject->generateUID();
                            $sMIME = $myUtils->oxMimeContentType($sFileName);
                            if ($sMIME == 'image/jpeg' || $sMIME == 'image/gif' || $sMIME == 'image/png') {
                                if ( $this->addEmbeddedImage( $sFileName, $sCId, "image", "base64", $sMIME ) ) {
                                    $aImageCache[$sFileName] = $sCId;
                                } else {
                                    $sCId = '';
                                }
                            }
                        }
                        if ( $sCId && $sCId == $aImageCache[$sFileName] ) {
                            if ( $sReplTag = str_replace( $image, 'cid:'.$sCId, $aImage[0] ) ) {
                                $sBody = str_replace($aImage[0], $sReplTag, $sBody );
                                $blReSetBody = true;
                            }
                        }
                    }
                }
            }

            if ( $blReSetBody ) {
                $this->setBody( $sBody );
            }
        }
    }

    /**
     * Sets mail subject
     *
     * @param string $sSubject mail subject
     *
     * @return null
     */
    public function setSubject( $sSubject = null )
    {
        // A. HTML entites in subjects must be replaced
        $sSubject = str_replace(array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), array('&', '"', "'", '<', '>' ), $sSubject);

        $this->set( "Subject", $sSubject );
    }

    /**
     * Gets mail subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->Subject;
    }

    /**
     * Set mail body. If second parameter (default value is true) is set to true,
     * performs searche for "sid", replaces sid by sid=x and adds shop id to string
     *
     * @param string $sBody      mail body
     * @param bool   $blClearSid clear sid in mail body
     *
     * @return null
     */
    public function setBody( $sBody = null, $blClearSid = true )
    {
        if ( $blClearSid ) {
            $sBody = getStr()->preg_replace('/((\?|&(amp;)?)(force_)?(admin_)?)sid=[A-Z0-9\.]+/i', '\1sid=x&amp;shp=' . $this->getConfig()->getShopId(), $sBody);
        }

        $this->set( "Body", $sBody );
    }

    /**
     * Gets mail body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->Body;
    }

    /**
     * Sets text-only body of the message. If second parameter is set to true,
     * performs searche for "sid", replaces sid by sid=x and adds shop id to string
     *
     * @param string $sAltBody   mail subject
     * @param bool   $blClearSid clear sid in mail body (default value is true)
     *
     * @return null
     */
    public function setAltBody( $sAltBody = null, $blClearSid = true )
    {
        if ( $blClearSid ) {
            $sAltBody = getStr()->preg_replace('/((\?|&(amp;)?)(force_)?(admin_)?)sid=[A-Z0-9\.]+/i', '\1sid=x&amp;shp=' . $this->getConfig()->getShopId(), $sAltBody);
        }

        // A. alt body is used for plain text emails so we should eliminate HTML entities
        $sAltBody = str_replace(array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), array('&', '"', "'", '<', '>' ), $sAltBody);

        $this->set( "AltBody", $sAltBody );
    }

    /**
     * Gets mail text-only body
     *
     * @return string
     */
    public function getAltBody()
    {
        return $this->AltBody;
    }

    /**
     * Sets mail recipient to recipients array
     *
     * @param string $sAddress recipient email address
     * @param string $sName    recipient name
     *
     * @return null
     */
    public function setRecipient( $sAddress = null, $sName = null )
    {
        try {
            parent::AddAddress( $sAddress, $sName );

            // copying values as original class does not allow to access recipients array
            $this->_aRecipients[] = array( $sAddress, $sName );
        } catch( Exception $oEx ) {
        }
    }

    /**
     * Gets recipients array.
     * Returns array of recipients
     * f.e. array( array('mail1@mail1.com', 'user1Name'), array('mail2@mail2.com', 'user2Name') )
     *
     * @return array
     */
    public function getRecipient()
    {
        return $this->_aRecipients;
    }

    /**
     * Clears all recipients assigned in the TO, CC and BCC
     * array.  Returns void.
     *
     * @return void
     */
    public function clearAllRecipients()
    {
        $this->_aRecipients = array();
        parent::clearAllRecipients();
    }

    /**
     * Sets user address and name to "reply to" array.
     * On error (wrong email) default shop email is added as a reply address.
     * Returns array of recipients
     * f.e. array( array('mail1@mail1.com', 'user1Name'), array('mail2@mail2.com', 'user2Name') )
     *
     * @param string $sEmail email address
     * @param string $sName  user name
     *
     * @return null
     */
    public function setReplyTo( $sEmail = null, $sName = null )
    {
        if ( !oxUtils::getInstance()->isValidEmail( $sEmail ) ) {
            $sEmail = $this->_getShop()->oxshops__oxorderemail->value;
        }

        $this->_aReplies[] = array( $sEmail, $sName );

        try {
            parent::addReplyTo( $sEmail, $sName );
        } catch( Exception $oEx ) {
        }
    }

    /**
     * Gets array of users for which reply is used.
     *
     * @return array
     */
    public function getReplyTo()
    {
        return $this->_aReplies;
    }

    /**
    * Clears all recipients assigned in the ReplyTo array.  Returns void.
    *
    * @return null
    */
    public function clearReplyTos()
    {
        $this->_aReplies = array();
        parent::clearReplyTos();
    }

    /**
     * Sets mail from address and name.
     *
     * @param string $sFromAdress email address
     * @param string $sFromName   user name
     *
     * @return null
     */
    public function setFrom( $sFromAdress, $sFromName = null )
    {
        // preventing possible email spam over php mail() exploit (http://www.securephpwiki.com/index.php/Email_Injection)
        // this is simple but must work
        // dodger Task #1532 field "From" in emails from shops
        $sFromAdress = substr($sFromAdress, 0, 150);
        $sFromName   = substr($sFromName, 0, 150);

        try {
            parent::setFrom( $sFromAdress, $sFromName );
        } catch( Exception $oEx ) {
        }
    }

    /**
     * Gets mail "from address" field.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->From;
    }

    /**
     * Gets mail "from name" field.
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->FromName;
    }

    /**
     * Sets mail charset.
     * If $sCharSet is not defined, sets charset from translation file.
     *
     * @param string $sCharSet email charset
     *
     * @return null
     */
    public function setCharSet( $sCharSet = null )
    {
        $this->set( "CharSet", $sCharSet ? $sCharSet : oxLang::getInstance()->translateString( "charset" ) );
    }

    /**
     * Gets mail charset.
     *
     * @return string
     */
    public function getCharSet()
    {
        return $this->CharSet;
    }

    /**
     * Sets mail mailer. Set to send mail via smtp, mail() or sendmail.
     *
     * @param string $sMailer email mailer
     *
     * @return null
     */
    public function setMailer( $sMailer = null )
    {
        $this->set( "Mailer", $sMailer );
    }

    /**
     * Gets mail mailer.
     *
     * @return string
     */
    public function getMailer()
    {
        return $this->Mailer;
    }

    /**
     * Sets stmp host.
     *
     * @param string $sHost smtp host
     *
     * @return null
     */
    public function setHost( $sHost = null )
    {
        $this->set( "Host", $sHost );
    }

    /**
     * Gets mailing error info.
     *
     * @return string
     */
    public function getErrorInfo()
    {
        return $this->ErrorInfo;
    }

    /**
     * Sets word wrapping on the body of the message to a given number of
     * characters
     *
     * @param int $iWordWrap word wrap
     *
     * @return string
     */
    public function setMailWordWrap( $iWordWrap = null )
    {
        $this->set( "WordWrap", $iWordWrap );
    }

    /**
     * Sets use inline images. If setted to true, images will be embeded into mail.
     *
     * @param bool $blUseImages embed or not images into mail
     *
     * @return null
     */
    public function setUseInlineImages( $blUseImages = null )
    {
        $this->_blInlineImgEmail = $blUseImages;
    }

    /**
     * Adds an attachment to mail from a path on the filesystem
     *
     * @param string $sAttPath  path to the attachment
     * @param string $sAttFile  attachment name
     * @param string $sEncoding attachment encoding
     * @param string $sType     attachment type
     *
     * @return bool
     */
    public function addAttachment( $sAttPath, $sAttFile = '', $sEncoding = 'base64', $sType = 'application/octet-stream' )
    {
        $sFullPath = $sAttPath . $sAttFile;

        $this->_aAttachments[] = array( $sFullPath, $sAttFile, $sEncoding, $sType );
        $blResult = false;

        try {
             $blResult = parent::addAttachment( $sFullPath, $sAttFile, $sEncoding, $sType );
        } catch( Exception $oEx ) {
        }

        return $blResult;
    }

    /**
     * Adds an embedded attachment (check phpmail documentation for more details)
     *
     * @param string $sFullPath Path to the attachment.
     * @param string $sCid      Content ID of the attachment. Use this to identify the Id for accessing the image in an HTML form.
     * @param string $sAttFile  Overrides the attachment name.
     * @param string $sEncoding File encoding (see $Encoding).
     * @param string $sType     File extension (MIME) type.
     *
     * @return bool
     */
    public function addEmbeddedImage( $sFullPath, $sCid, $sAttFile = '', $sEncoding = 'base64', $sType = 'application/octet-stream' )
    {
        $this->_aAttachments[] = array( $sFullPath, basename($sFullPath), $sAttFile, $sEncoding, $sType, false, 'inline', $sCid );
        return parent::addEmbeddedImage( $sFullPath, $sCid, $sAttFile, $sEncoding, $sType );
    }

    /**
     * Gets mail attachment.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->_aAttachments;
    }

    /**
     * Clears all attachments from mail.
     *
     * @return null
     */
    public function clearAttachments()
    {
        $this->_aAttachments = array();
        return parent::clearAttachments();
    }

    /**
     * Inherited phpMailer function adding a header to email message.
     * We override it to skip X-Mailer header.
     *
     * @param string $sName  header name
     * @param string $sValue header value
     *
     * @return null
     */
    public function headerLine($sName, $sValue)
    {
        if (stripos($sName, 'X-') !== false) {
            return;
        }
        return parent::headerLine($sName, $sValue);
    }

    /**
     * Gets use inline images.
     *
     * @return bool
     */
    protected function _getUseInlineImages()
    {
        return $this->_blInlineImgEmail;
    }

    /**
     * Try to send error message when original mailing by smtp and via mail() fails
     *
     * @return bool
     */
    protected function _sendMailErrorMsg()
    {
        // build addresses
        $sToAdress  = "";
        $sToName    = "";

        $aRecipients = $this->getRecipient();

        $sOwnerMessage  = "Error sending eMail(". $this->getSubject().") to: \n\n";

        foreach ( $aRecipients as $aEMail ) {
            $sOwnerMessage .= $aEMail[0];
            $sOwnerMessage .= ( !empty($aEMail[1]) ) ? ' (' . $aEMail[1] . ')' : '';
            $sOwnerMessage .= " \n ";
        }
        $sOwnerMessage .= "\n\nError : " . $this->getErrorInfo();

        // shop info
        $oShop = $this->_getShop();

        $blRet = @mail( $oShop->oxshops__oxorderemail->value, "eMail problem in shop !", $sOwnerMessage);

        return $blRet;
    }

    /**
     * Does nothing, returns same object as passed to method.
     * This method is called from oxemail::sendOrderEMailToUser() to do
     * additional operation with order object before sending email
     *
     * @param oxOrder $oOrder Ordering object
     *
     * @return oxOrder
     */
    protected function _addUserInfoOrderEMail( $oOrder )
    {
        return $oOrder;
    }

    /**
     * Does nothing, returns same object as passed to method.
     * This method is called from oxemail::SendRegisterEMail() to do
     * additional operation with user object before sending email
     *
     * @param oxUser $oUser User object
     *
     * @return oxUser
     */
    protected function _addUserRegisterEmail( $oUser )
    {
        return $oUser;
    }

    /**
     * Does nothing, returns same object as passed to method.
     * This method is called from oxemail::SendForgotPWDEMail() to do
     * additional operation with shop object before sending email
     *
     * @param oxShop $oShop Shop object
     *
     * @return oxShop
     */
    protected function _addForgotPwdEmail( $oShop )
    {
        return $oShop;
    }

    /**
     * Does nothing, returns same object as passed to method.
     * This method is called from oxemail::SendNewsletterDBOptInMail() to do
     * additional operation with user object before sending email
     *
     * @param oxUser $oUser User object
     *
     * @return oxUser
     */
    protected function _addNewsletterDbOptInMail( $oUser )
    {
        return $oUser;
    }

    /**
     * Clears mailer settings (AllRecipients, ReplyTos, Attachments, Errors)
     *
     * @return null
     */
    protected function _clearMailer()
    {
        $this->clearAllRecipients();
        $this->clearReplyTos();
        $this->clearAttachments();

        //workaround for phpmailer as it doesn't cleanup as it should
        $this->error_count = 0;
        $this->ErrorInfo   = '';
    }

    /**
     * Set mail From, FromName, SMTP values
     *
     * @param oxShop $oShop Shop object
     *
     * @return void
     */
    protected function _setMailParams( $oShop = null )
    {
        $this->_clearMailer();

        if ( !$oShop ) {
            $oShop = $this->_getShop();
        }

        $this->setFrom( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );
        $this->setSmtp( $oShop );
    }

    /**
     * Get active shop and set global params for it
     * If is set language parameter, load shop in given language
     *
     * @param int $iLangId language id
     *
     * @return oxShop
     */
    protected function _getShop( $iLangId = null )
    {
        $myConfig = $this->getConfig();
        if ( $iLangId === null ) {
            $iLangId = $myConfig->getActiveShop()->getLanguage();
        }
        $iLangId = oxLang::getInstance()->validateLanguage( $iLangId );

        if ( !isset( $this->_aShops[$iLangId] ) ) {
            $oShop = oxNew( 'oxshop' );
            $oShop->loadInLang( $iLangId, $myConfig->getShopId() );
            $this->_aShops[$iLangId] = $myConfig->getActiveView()->addGlobalParams( $oShop );
        }

        return $this->_aShops[$iLangId];
    }

    /**
     * Sets smtp authentification parameters.
     *
     * @param string $sUserName     smtp user
     * @param oxShop $sUserPassword smtp password
     *
     * @return null
     */
    protected function _setSmtpAuthInfo( $sUserName = null, $sUserPassword = null )
    {
        $this->set( "SMTPAuth", true );
        $this->set( "Username", $sUserName );
        $this->set( "Password", $sUserPassword );
    }

    /**
     * Sets SMTP class debugging on or off
     *
     * @param bool $blDebug show debug info or not
     *
     * @return null
     */
    protected function _setSmtpDebug( $blDebug = null )
    {
        $this->set( "SMTPDebug", $blDebug );
    }

    /**
     * Sets path to PHPMailer plugins
     *
     * @return null
     */
    protected function _setMailerPluginDir()
    {
        $this->set( "PluginDir", getShopBasePath() . "core/phpmailer/" );
    }

    /**
     * Process email body and alt body throught oxoutput.
     * Calls oxoutput::processEmail() on class instance.
     *
     * @return null
     */
    protected function _makeOutputProcessing()
    {
        $oOutput = oxNew( "oxoutput" );
        $this->setBody( $oOutput->process($this->getBody(), "oxemail") );
        $this->setAltBody( $oOutput->process($this->getAltBody(), "oxemail") );
        $oOutput->processEmail( $this );
    }

    /**
     * Sends email via phpmailer.
     *
     * @return bool
     */
    protected function _sendMail()
    {
        $blResult = false;
        try {
             $blResult = parent::send();
        } catch( Exception $oEx ) {
        }

        return $blResult;
    }
}
