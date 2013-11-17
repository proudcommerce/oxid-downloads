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
 * @link http://www.oxid-esales.com
 * @package core
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxemail.php 18956 2009-05-12 08:55:26Z vilma $
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
class oxEmail extends phpmailer
{
    /**
     * Default Smtp server port
     *
     * @var int
     */
    public $SMTP_PORT = 25;

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
     * Current active shop
     *
     * @var oxShop
     */
    protected $_oShop = null;

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
     * Class constructor.
     */
    public function __construct()
    {
        $myConfig = $this->getConfig();

        $this->_setMailerPluginDir();
        $this->setSmtp();

        $this->setUseInlineImages( true );
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
     * Otputs email fields throught email output processor, includes images, and initiate email sending
     * If fails to send mail via smtp, tryes to send via mail(). On failing to send, sends mail to
     * shop administrator about failing mail sending
     *
     * @return bool
     */
    public function send()
    {
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

        if ( !$this->_isValidSmtpHost( $oShop->oxshops__oxsmtp->value ) ) {
            $this->setMailer( "mail" );
            return;
        }

        $this->setHost( $oShop->oxshops__oxsmtp->value );
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
            if ( $blIsSmtp = (bool) ( $rHandle = @fsockopen( $sSmtpHost, $this->SMTP_PORT, $iErrNo, $sErrStr, 30 )) ) {
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
     * @param oxOrder $oOrder Order object
     *
     * @return bool
     */
    public function sendOrderEmailToUser( $oOrder )
    {
        $myConfig = $this->getConfig();

        $sCustHTML  = $this->_sOrderUserTemplate;
        $sCustPLAIN = $this->_sOrderUserPlainTemplate;

        // add user defined stuff if there is any
        $oOrder = $this->_addUserInfoOrderEMail( $oOrder );

        //set mail params (from, fromName, smtp)
        $oShop = $this->_getShop();
        $this->_setMailParams( $oShop );

        // P
        // setting some deprecated variables
        $oOrder->oDelSet = $oOrder->getDelSet();

        $oUser = $oOrder->getUser();
        // create messages
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", oxLang::getInstance()->translateString("charset"));
        $smarty->assign( "order", $oOrder);
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "user", $oUser );
        $smarty->assign( "currency", $myConfig->getActShopCurrencyObject() );
        $smarty->assign( "basket", $oOrder->getBasket() );
        $smarty->assign( "payment", $oOrder->getPayment() );
        if ( $oUser ) {
            $smarty->assign( "reviewuser", $oUser->getReviewUserHash($oUser->getId()) );
        }
        $smarty->assign( "paymentinfo", $myConfig->getActiveShop() );

        //deprecated vars
        $smarty->assign( "iswishlist", true);
        $smarty->assign( "isreview", true);

        if ( $aVoucherList = $oOrder->getVoucherList() ) {
            $smarty->assign( "vouchers", $aVoucherList );
        }

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $smarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $smarty->assign( $key, $val );
        }

        $this->setBody( $smarty->fetch( $sCustHTML) );
        $this->setAltBody( $smarty->fetch( $sCustPLAIN) );

        // #586A
        if ( $smarty->template_exists( $this->_sOrderUserSubjectTemplate) ) {
            $this->setSubject( $smarty->fetch( $this->_sOrderUserSubjectTemplate) );
        } else {
            $this->setSubject( $oShop->oxshops__oxordersubject->value." (#".$oOrder->oxorder__oxordernr->value.")" );
        }

        $sFullName = $oUser->oxuser__oxfname->value . " " . $oUser->oxuser__oxlname->value;

        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );

        $blSuccess = $this->send();

        return $blSuccess;
    }

    /**
     * Sets mailer additional settings and sends ordering mail to shop owner.
     * Returns true on success.
     *
     * @param oxOrder $oOrder Order object
     *
     * @return bool
     */
    public function sendOrderEmailToOwner( $oOrder )
    {
        $myConfig = $this->getConfig();

        $sOwnerHTML  = $this->_sOrderOwnerTemplate;
        $sOwnerPLAIN = $this->_sOrderOwnerPlainTemplate;

        // cleanup
        $this->_clearMailer();

        // add user defined stuff if there is any
        $oOrder = $this->_addUserInfoOrderEMail( $oOrder );

        // send confirmation to shop owner
        $sFullName = $oOrder->getUser()->oxuser__oxfname->value . " " . $oOrder->getUser()->oxuser__oxlname->value;
        $this->setFrom( $oOrder->getUser()->oxuser__oxusername->value, $sFullName );

        $oLang = oxLang::getInstance();
        $iOrderLang = $oLang->getTplLanguage();

        $oShop = $this->_getShop();

        // if running shop language is different from admin lang. set in config
        // we have to load shop in config language
        if ( $oShop->getLanguage() != $iOrderLang ) {
            $oShop = $this->_getShop( $iOrderLang );
        }

        $this->setSmtp( $oShop );

        // create messages
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", $oLang->translateString("charset"));
        $smarty->assign( "order", $oOrder );
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "user", $oOrder->getUser() );
        $smarty->assign( "currency", $myConfig->getActShopCurrencyObject() );
        $smarty->assign( "basket", $oOrder->getBasket() );
        $smarty->assign( "payment", $oOrder->getPayment() );

        //deprecated var
        $smarty->assign( "iswishlist", true);

        if( $oOrder->getVoucherList() )
            $smarty->assign( "vouchers", $oOrder->getVoucherList() );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray($smarty->get_template_vars(), "oxemail");
        foreach ($aNewSmartyArray as $key => $val)
            $smarty->assign( $key, $val );

        //path to admin message template file
        $sPathToTemplate = $myConfig->getTemplateDir(false).'/';

        $this->setBody( $smarty->fetch( $sPathToTemplate.$sOwnerHTML ) );
        $this->setAltBody( $smarty->fetch( $sPathToTemplate.$sOwnerPLAIN ) );

        //Sets subject to email
        // #586A
        if ( $smarty->template_exists( $this->_sOrderOwnerSubjectTemplate) )
            $this->setSubject( $smarty->fetch( $this->_sOrderOwnerSubjectTemplate) );
        else
            $this->setSubject( $oShop->oxshops__oxordersubject->value." (#".$oOrder->oxorder__oxordernr->value.")" );

        $this->setRecipient( $oShop->oxshops__oxowneremail->value, $oLang->translateString("order") );

        if ( $oOrder->getUser()->oxuser__oxusername->value != "admin" )
            $this->setReplyTo( $oOrder->getUser()->oxuser__oxusername->value, $sFullName );

        $blSuccess = $this->send();

        // add user history
        $oRemark = oxNew( "oxremark" );
        $oRemark->oxremark__oxtext      = new oxField($this->getAltBody(), oxField::T_RAW);
        $oRemark->oxremark__oxparentid  = new oxField($oOrder->getUser()->getId(), oxField::T_RAW);
        $oRemark->oxremark__oxtype      = new oxField("o", oxField::T_RAW);
        $oRemark->save();


        if ( $myConfig->getConfigParam( 'iDebug' ) == 6) {
            exit();
        }

        return $blSuccess;
    }

    /**
     * Sets mailer additional settings and sends registration mail to user.
     * Returns true on success.
     *
     * @param oxUser $oUser User object
     *
     * @return bool
     */
    public function sendRegisterEmail( $oUser )
    {
        // add user defined stuff if there is any
        $oUser = $this->_addUserRegisterEmail( $oUser );

        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp )
        $this->_setMailParams( $oShop );

        // create messages
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "user", $oUser );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $smarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $smarty->assign( $key, $val );
        }

        $this->setBody( $smarty->fetch( "email_register_html.tpl") );
        $this->setAltBody( $smarty->fetch( "email_register_plain.tpl") );

        $this->setSubject( $oShop->oxshops__oxregistersubject->value );

        $sFullName = $oUser->oxuser__oxfname->value . " " . $oUser->oxuser__oxlname->value;

        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setReplyTo( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "forgot password" mail to user.
     * Returns true on success.
     *
     * @param string $sEmailAddress user email address
     *
     * @return bool
     */
    public function sendForgotPwdEmail( $sEmailAddress )
    {
        $myConfig = $this->getConfig();

        // shop info
        $oShop = $this->_getShop();

        // add user defined stuff if there is any
        $oShop = $this->_addForgotPwdEmail( $oShop);

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        // user
        $sSelect = "select oxid from oxuser where oxuser.oxactive = 1 and
                    oxuser.oxusername = '$sEmailAddress' and oxuser.oxpassword != ''
                    order by oxshopid = '".$oShop->getId()."' desc";

        if ( ( $sOxId = oxDb::getDb()->getOne( $sSelect ) ) ) {

            $oUser = oxNew( 'oxuser' );
            if ( $oUser->load($sOxId) ) {
                // create messages
                $smarty = oxUtilsView::getInstance()->getSmarty();
                $smarty->assign( "charset", oxLang::getInstance()->translateString("charset"));
                $smarty->assign( "shop", $oShop );
                $smarty->assign( "oViewConf", $oShop );
                $smarty->assign( "user", $oUser );

                $oOutputProcessor = oxNew( "oxoutput" );
                $aNewSmartyArray  = $oOutputProcessor->processViewArray( $smarty->get_template_vars(), "oxemail" );

                foreach ( $aNewSmartyArray as $key => $val ) {
                    $smarty->assign($key, $val);
                }

                $this->setBody( $smarty->fetch( "email_forgotpwd_html.tpl") );
                $this->setAltBody( $smarty->fetch( "email_forgotpwd_plain.tpl") );

                //sets subject of email
                $this->setSubject( $oShop->oxshops__oxforgotpwdsubject->value );

                $sFullName = $oUser->oxuser__oxfname->value . " " . $oUser->oxuser__oxlname->value;

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
     * @param oxUser $oUser User object
     *
     * @return bool
     */
    public function sendNewsletterDbOptInMail( $oUser )
    {

        // add user defined stuff if there is any
        $oUser = $this->_addNewsletterDbOptInMail( $oUser );

        // shop info
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        // create messages
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", oxLang::getInstance()->translateString("charset"));
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "user", $oUser );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $smarty->get_template_vars(), "oxemail" );
        foreach ( $aNewSmartyArray as $key => $val ) {
            $smarty->assign( $key, $val );
        }

        $this->setBody( $smarty->fetch("email_newsletteroptin_html.tpl") );
        $this->setAltBody( $smarty->fetch( "email_newsletteroptin_plain.tpl") );
        $this->setSubject( "Newsletter " . $oShop->oxshops__oxname->getRawValue() );

        $sFullName = $oUser->oxuser__oxfname->value . " " . $oUser->oxuser__oxlname->value;

        $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
        $this->setFrom( $oShop->oxshops__oxinfoemail->value, $oShop->oxshops__oxname->getRawValue() );
        $this->setReplyTo( $oShop->oxshops__oxinfoemail->value, $oShop->oxshops__oxname->getRawValue() );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "newsletter" mail to user.
     * Returns true on success.
     *
     * @param oxNewsletter $oNewsLetter Newsletter object
     * @param oxUser       $oUser       User object
     *
     * @return bool
     */
    public function sendNewsletterMail( $oNewsLetter, $oUser )
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

        $this->setSubject( $oNewsLetter->oxnewsletter__oxtitle->value );

        $sFullName = $oUser->oxuser__oxfname->value . " " . $oUser->oxuser__oxlname->value;
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
        $iCurrLang = 0;
        $iActShopLang = $myConfig->getActiveShop()->getLanguage();
        if ( isset($iActShopLang) && $iActShopLang != $iCurrLang ) {
            $iCurrLang = $iActShopLang;
        }

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
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "userinfo", $oParams );
        $smarty->assign( "product", $oProduct );

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $smarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $smarty->assign( $key, $val );
        }

        $this->setBody( $smarty->fetch( "email_suggest_html.tpl") );
        $this->setAltBody( $smarty->fetch( "email_suggest_plain.tpl") );
        $this->setSubject( $oParams->send_subject );

        $this->setRecipient( $oParams->rec_email, $oParams->rec_name );
        $this->setReplyTo( $oParams->send_email, $oParams->send_name );

        return $this->send();
    }

    /**
     * Sets mailer additional settings and sends "SendedNowMail" mail to user.
     * Returns true on success.
     *
     * @param oxOrder $oOrder Order object
     *
     * @return bool
     */
    public function sendSendedNowMail( $oOrder )
    {
        $myConfig = $this->getConfig();

        $iOrderLang = 0;
        if ( isset($oOrder->oxorder__oxlang->value) && $oOrder->oxorder__oxlang->value ) {
            $iOrderLang = $oOrder->oxorder__oxlang->value;
        }

        // shop info
        $oShop = $this->_getShop( $iOrderLang );

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        //override default wrap
        //$this->setMailWordWrap( 0 );

        //create messages
        $oLang = oxLang::getInstance();
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", $oLang->translateString("charset"));
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "order", $oOrder );
        $smarty->assign( "currency", $myConfig->getActShopCurrencyObject() );

        //deprecated var
        $smarty->assign( "isreview", true);

        $oOutputProcessor = oxNew( "oxoutput" );
        $aNewSmartyArray = $oOutputProcessor->processViewArray( $smarty->get_template_vars(), "oxemail" );

        foreach ( $aNewSmartyArray as $key => $val ) {
            $smarty->assign( $key, $val );
        }

        // dodger #1469 - we need to patch security here as we do not use standard template dir, so smarty stops working
        $aStore['INCLUDE_ANY'] = $smarty->security_settings['INCLUDE_ANY'];
        //V send email in order language
        $iOldTplLang = $oLang->getTplLanguage();
        $iOldBaseLang = $oLang->getTplLanguage();
        $oLang->setTplLanguage( $iOrderLang );
        $oLang->setBaseLanguage( $iOrderLang );

        $smarty->security_settings['INCLUDE_ANY'] = true;

        //Sets path to template file
        $sPathToTemplate = $myConfig->getTemplateDir(false)."/";

        $this->setBody( $smarty->fetch( $sPathToTemplate."email_sendednow_html.tpl") );
        $this->setAltBody( $smarty->fetch( $sPathToTemplate."email_sendednow_plain.tpl") );
        $oLang->setTplLanguage( $iOldTplLang );
        $oLang->setBaseLanguage( $iOldBaseLang );
        // set it back
        $smarty->security_settings['INCLUDE_ANY'] = $aStore['INCLUDE_ANY'] ;

        //Sets subject to email
        $this->setSubject( $oShop->oxshops__oxsendednowsubject->value );

        $sFullName = $oOrder->oxorder__oxbillfname->value . " " . $oOrder->oxorder__oxbilllname->value;

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

        if ( !$sEmailAddress ) {
            $sEmailAddress = $oShop->oxshops__oxowneremail->value;
        }

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
     * @param array $aBasketContents array of objects to pass to template
     *
     * @return bool
     */
    public function sendStockReminder( $aBasketContents )
    {
        $myConfig = $this->getConfig();

        $aRemindArticles = array();
        foreach ( $aBasketContents as $oBasketItem ) {
            $oArticle = $oBasketItem->getArticle();
             // reminder not set
            if ( !$oArticle->oxarticles__oxremindactive->value || $oArticle->oxarticles__oxremindactive->value > 1 ) {
                continue;
            }

            // number or articles available is more
            if ( $oArticle->oxarticles__oxstock->value > $oArticle->oxarticles__oxremindamount->value ) {
                continue;
            }

            $aRemindArticles[] = $oArticle;
            $oArticle->disableReminder();
        }

        // nothing to remind ...
        if ( !count( $aRemindArticles ) ) {
            return false;
        }
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp... )
        $this->_setMailParams( $oShop );
        $oLang = oxLang::getInstance();


        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", $oLang->translateString("charset"));
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "articles", $aRemindArticles );

        //path to admin message template file
        $sPathToTemplate = $myConfig->getTemplateDir(false).'/';

        $this->setRecipient( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
        $this->setFrom( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
        $this->setBody( $smarty->fetch($sPathToTemplate.$this->_sReminderMailTemplate) );
        $this->setAltBody( "" );
        $this->setSubject( $oLang->translateString('EMAIL_STOCKREMINDER_SUBJECT') );

        return $this->send();
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
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "charset", oxLang::getInstance()->translateString("charset") );
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "userinfo", $oParams );

        $this->setBody( $smarty->fetch( "email_wishlist_html.tpl") );
        $this->setAltBody( $smarty->fetch( "email_wishlist_plain.tpl") );
        $this->setSubject( $oParams->send_subject );

        $this->setRecipient( $oParams->rec_email, $oParams->rec_name );
        $this->setReplyTo( $oParams->send_email, $oParams->send_name );

        return $this->send();
    }

    /**
     * Sends a notification to the shop owner that pricealarm was subscribed.
     * Returns true on success.
     *
     * @param array        $aParams Parameters array
     * @param oxpricealarm $oAlarm  oxPriceAlarm object
     *
     * @return bool
     */
    public function sendPriceAlarmNotification( $aParams, $oAlarm )
    {
        $this->_clearMailer();
        $oShop = $this->_getShop();

        //set mail params (from, fromName, smtp)
        $this->_setMailParams( $oShop );

        $iAlarmLang = $oShop->getLanguage();

        $oArticle = oxNew( "oxarticle" );
        $oArticle->setSkipAbPrice( true );
        $oArticle->loadInLang( $iAlarmLang, $aParams['aid'] );

        $oCur  = $this->getConfig()->getActShopCurrencyObject();
        $oLang = oxLang::getInstance();

        // create messages
        $smarty = oxUtilsView::getInstance()->getSmarty();
        $smarty->assign( "shop", $oShop );
        $smarty->assign( "oViewConf", $oShop );
        $smarty->assign( "product", $oArticle );
        $smarty->assign( "email", $aParams['email']);
        $smarty->assign( "bidprice", $oLang->formatCurrency( $oAlarm->oxpricealarm__oxprice->value, $oCur ) );
        $smarty->assign( "currency", $oCur );

        $this->setRecipient( $oShop->oxshops__oxorderemail->value, $oShop->oxshops__oxname->getRawValue() );
        $sSubject = $oLang->translateString( 'EMAIL_PRICEALARM_OWNER_SUBJECT', $iAlarmLang ) . " " . $oArticle->oxarticles__oxtitle->value;
        $this->setSubject( $sSubject );
        $this->setBody( $smarty->fetch( $this->_sOwnerPricealarmTemplate ) );
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
        $this->Subject = $sSubject;
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
            $sBody = eregi_replace("sid=[A-Z0-9\.]+", "sid=x&amp;shp=" . $this->getConfig()->getShopId(), $sBody);
        }

        $this->Body = $sBody;
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
            $sAltBody = eregi_replace("sid=[A-Z0-9\.]+", "sid=x&amp;shp=" . $this->getConfig()->getShopId(), $sAltBody);
        }

        // A. alt body is used for plain text emails so we should eliminate HTML entities
        $sAltBody = str_replace(array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), array('&', '"', "'", '<', '>' ), $sAltBody);
        $this->AltBody = $sAltBody;
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
        // copying values as original class does not allow to access recipients array
        $this->_aRecipients[] = array( $sAddress, $sName );

        parent::AddAddress($sAddress, $sName );
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
            $sEmail = $this->_oShop->oxshops__oxorderemail->value;
        }

        $this->_aReplies[] = array( $sEmail, $sName );
        parent::AddReplyTo( $sEmail, $sName );
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
    public function setFrom( $sFromAdress = null, $sFromName = null )
    {
        // preventing possible email spam over php mail() exploit (http://www.securephpwiki.com/index.php/Email_Injection)
        // this is simple but must work
        // dodger Task #1532 field "From" in emails from shops
        $this->From     = substr($sFromAdress, 0, 150);
        $this->FromName = substr($sFromName, 0, 150);
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
        if ( !empty($sCharSet) ) {
            $this->CharSet = $sCharSet;
        } else {
            $this->CharSet = oxLang::getInstance()->translateString("charset");
        }
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
        $this->Mailer = $sMailer;
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
        $this->Host = $sHost;
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
        $this->WordWrap = $iWordWrap;
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
        return parent::addAttachment( $sFullPath, $sAttFile, $sEncoding, $sType );
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
        return parent::ClearAttachments();
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
     * Clears some mailer settings (AllRecipients, ReplyTos)
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
        if ( !isset($iLangId) ) {
            $iLangId = 0;
            $iActShopLang = $myConfig->getActiveShop()->getLanguage();
            if ( isset($iActShopLang) && $iActShopLang != $iLangId ) {
                $iLangId = $iActShopLang;
            }
        }
        if ( isset($this->_oShop) && $this->_oShop ) {
            // if oShop already setted and reqesting oShop with same language as current oShop,
            // or wihtout lang param, return oShop object
            if ( isset($iLangId) && $iLangId == $this->_oShop->getLanguage() ) {
                return $this->_oShop;
            }
        }

        $this->_oShop = oxNew( 'oxshop' );

        $iLangId = oxLang::getInstance()->validateLanguage( $iLangId );

        $this->_oShop->loadInLang( $iLangId, $myConfig->getShopId() );

        $oView = $myConfig->getActiveView();
        $this->_oShop = $oView->addGlobalParams( $this->_oShop );

        return $this->_oShop;
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
        $this->SMTPAuth = true;
        $this->Username = $sUserName;
        $this->Password = $sUserPassword;
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
        $this->SMTPDebug = $blDebug;
    }

    /**
     * Sets path to PHPMailer plugins
     *
     * @return null
     */
    protected function _setMailerPluginDir()
    {
        $this->PluginDir = getShopBasePath() . "core/phpmailer/";
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
        return parent::send();
    }
}
