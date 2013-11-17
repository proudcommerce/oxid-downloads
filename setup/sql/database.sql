SET @@session.sql_mode = '';

#
# Table structure for table `oxaccessoire2article`
#

DROP TABLE IF EXISTS `oxaccessoire2article`;

CREATE TABLE `oxaccessoire2article` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXARTICLENID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSORT` int(5) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`),
  KEY `OXARTICLENID` (`OXARTICLENID`)
) TYPE=MyISAM;

#
# Table structure for table `oxaddress`
#

DROP TABLE IF EXISTS `oxaddress`;

CREATE TABLE `oxaddress` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXADDRESSUSERID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXCOMPANY` varchar(255) NOT NULL default '',
  `OXFNAME` varchar(255) NOT NULL default '',
  `OXLNAME` varchar(255) NOT NULL default '',
  `OXSTREET` varchar(255) NOT NULL default '',
  `OXSTREETNR` varchar(16) NOT NULL default '',
  `OXADDINFO` varchar(255) NOT NULL default '',
  `OXCITY` varchar(255) NOT NULL default '',
  `OXCOUNTRY` varchar(255) NOT NULL default '',
  `OXCOUNTRYID` varchar( 32 ) character set latin1 collate latin1_general_ci NOT NULL,
  `OXZIP` varchar(50) NOT NULL default '',
  `OXFON` varchar(128) NOT NULL default '',
  `OXFAX` varchar(128) NOT NULL default '',
  `OXSAL` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXUSERID` (`OXUSERID`)
) TYPE=MyISAM;

#
# Table structure for table `oxadminlog`
#

DROP TABLE IF EXISTS `oxadminlog`;

CREATE TABLE `oxadminlog` (
  `OXDATE` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSQL` text NOT NULL
) TYPE=MyISAM;

#
# Table structure for table `oxarticles`
#

DROP TABLE IF EXISTS `oxarticles`;

CREATE TABLE `oxarticles` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXPARENTID` char(32) character set latin1 collate latin1_general_ci NOT NULL  default '',
  `OXACTIVE` tinyint(1) NOT NULL DEFAULT '1',
  `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXARTNUM` varchar(255) NOT NULL default '',
  `OXEAN` varchar(13)  NOT NULL default '',
  `OXDISTEAN` varchar(13)  NOT NULL default '',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXSHORTDESC` varchar(255) NOT NULL default '',
  `OXPRICE` double NOT NULL default '0',
  `OXBLFIXEDPRICE` tinyint(1) NOT NULL default '0',
  `OXPRICEA` double NOT NULL default '0',
  `OXPRICEB` double NOT NULL default '0',
  `OXPRICEC` double NOT NULL default '0',
  `OXBPRICE` double NOT NULL default '0',
  `OXTPRICE` double NOT NULL default '0',
  `OXUNITNAME` varchar(32) NOT NULL default '',
  `OXUNITQUANTITY` double NOT NULL default '0',
  `OXEXTURL` varchar(255) NOT NULL default '',
  `OXURLDESC` varchar(255) NOT NULL default '',
  `OXURLIMG` varchar(128) NOT NULL default '',
  `OXVAT` float default NULL,
  `OXTHUMB` varchar(128) NOT NULL default '',
  `OXICON` varchar(128) NOT NULL default '',
  `OXPIC1` varchar(128) NOT NULL default '',
  `OXPIC2` varchar(128) NOT NULL default '',
  `OXPIC3` varchar(128) NOT NULL default '',
  `OXPIC4` varchar(128) NOT NULL default '',
  `OXPIC5` varchar(128) NOT NULL default '',
  `OXPIC6` varchar(128) NOT NULL default '',
  `OXPIC7` varchar(128) NOT NULL default '',
  `OXPIC8` varchar(128) NOT NULL default '',
  `OXPIC9` varchar(128) NOT NULL default '',
  `OXPIC10` varchar(128) NOT NULL default '',
  `OXPIC11` varchar(128) NOT NULL default '',
  `OXPIC12` varchar(128) NOT NULL default '',
  `OXZOOM1` varchar(128) NOT NULL default '',
  `OXZOOM2` varchar(128) NOT NULL default '',
  `OXZOOM3` varchar(128) NOT NULL default '',
  `OXZOOM4` varchar(128) NOT NULL default '',
  `OXWEIGHT` double NOT NULL default '0',
  `OXSTOCK` double NOT NULL default '-1',
  `OXSTOCKFLAG` tinyint(1) NOT NULL default '1',
  `OXSTOCKTEXT` varchar(255) NOT NULL default '',
  `OXNOSTOCKTEXT` varchar(255) NOT NULL default '',
  `OXDELIVERY` date NOT NULL default '0000-00-00',
  `OXINSERT` date NOT NULL default '0000-00-00',
  `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `OXLENGTH` double NOT NULL default '0',
  `OXWIDTH` double NOT NULL default '0',
  `OXHEIGHT` double NOT NULL default '0',
  `OXFILE` varchar(128) NOT NULL default '',
  `OXSEARCHKEYS` varchar(255) NOT NULL default '',
  `OXTEMPLATE` varchar(128) NOT NULL default '',
  `OXQUESTIONEMAIL` varchar(255) NOT NULL default '',
  `OXISSEARCH` tinyint(1) NOT NULL default '1',
  `OXVARNAME` varchar(255) NOT NULL default '',
  `OXVARSTOCK` int(5) NOT NULL default '0',
  `OXVARCOUNT` int(1) NOT NULL default '0',
  `OXVARSELECT` varchar(255) NOT NULL default '',
  `OXVARMINPRICE` double NOT NULL default '0',
  `OXVARNAME_1` varchar(255) NOT NULL default '',
  `OXVARSELECT_1` varchar(255) NOT NULL default '',
  `OXVARNAME_2` varchar(255) NOT NULL default '',
  `OXVARSELECT_2` varchar(255) NOT NULL default '',
  `OXVARNAME_3` varchar(255) NOT NULL default '',
  `OXVARSELECT_3` varchar(255) NOT NULL default '',
  `OXTITLE_1` varchar(255) NOT NULL default '',
  `OXSHORTDESC_1` varchar(255) NOT NULL default '',
  `OXURLDESC_1` varchar(255) NOT NULL default '',
  `OXSEARCHKEYS_1` varchar(255) NOT NULL default '',
  `OXTITLE_2` varchar(255) NOT NULL default '',
  `OXSHORTDESC_2` varchar(255) NOT NULL default '',
  `OXURLDESC_2` varchar(255) NOT NULL default '',
  `OXSEARCHKEYS_2` varchar(255) NOT NULL default '',
  `OXTITLE_3` varchar(255) NOT NULL default '',
  `OXSHORTDESC_3` varchar(255) NOT NULL default '',
  `OXURLDESC_3` varchar(255) NOT NULL default '',
  `OXSEARCHKEYS_3` varchar(255) NOT NULL default '',
  `OXBUNDLEID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXFOLDER` varchar(32) NOT NULL default '',
  `OXSUBCLASS` varchar(32) NOT NULL default '',
  `OXSTOCKTEXT_1` varchar(255) NOT NULL default '',
  `OXSTOCKTEXT_2` varchar(255) NOT NULL default '',
  `OXSTOCKTEXT_3` varchar(255) NOT NULL default '',
  `OXNOSTOCKTEXT_1` varchar(255) NOT NULL default '',
  `OXNOSTOCKTEXT_2` varchar(255) NOT NULL default '',
  `OXNOSTOCKTEXT_3` varchar(255) NOT NULL default '',
  `OXSORT` int(5) NOT NULL default '0',
  `OXSOLDAMOUNT` double NOT NULL default '0',
  `OXNONMATERIAL` int(1) NOT NULL default '0',
  `OXFREESHIPPING` int(1) NOT NULL default '0',
  `OXREMINDACTIVE` int(1) NOT NULL default '0',
  `OXREMINDAMOUNT` double NOT NULL default '0',
  `OXAMITEMID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXAMTASKID` varchar(16) character set latin1 collate latin1_general_ci NOT NULL default '0',
  `OXVENDORID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXMANUFACTURERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSKIPDISCOUNTS` tinyint(1) NOT NULL default '0',
  `OXRATING` double NOT NULL default '0',
  `OXRATINGCNT` int(11) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXCOUNT` (`OXPARENTID`,`OXSHOPID`),
  KEY `OXSORT` (`OXSORT`),
  KEY `OXSHOPID` (`OXSHOPID`),
  KEY `OXISSEARCH` (`OXISSEARCH`),
  KEY `OXARTNUM` (`OXARTNUM`),
  KEY `OXSTOCK` (`OXSTOCK`),
  KEY `OXSTOCKFLAG` (`OXSTOCKFLAG`),
  KEY `OXINSERT` (`OXINSERT`),
  KEY `OXVARNAME` (`OXVARNAME`),
  KEY `OXACTIVE` (`OXACTIVE`),
  KEY `OXACTIVEFROM` (`OXACTIVEFROM`),
  KEY `OXACTIVETO` (`OXACTIVETO`),
  KEY `OXVENDORID` (`OXVENDORID`),
  KEY `OXMANUFACTURERID` (`OXMANUFACTURERID`)
)TYPE=InnoDB;

#
# Table structure for table `oxartextends`
# created on 2008-05-23
#

DROP TABLE IF EXISTS `oxartextends`;

CREATE TABLE `oxartextends` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXLONGDESC` text NOT NULL,
  `OXLONGDESC_1` text NOT NULL,
  `OXLONGDESC_2` text NOT NULL,
  `OXLONGDESC_3` text NOT NULL,
  `OXTAGS` varchar(255) NOT NULL,
  `OXTAGS_1` varchar(255) NOT NULL,
  `OXTAGS_2` varchar(255) NOT NULL,
  `OXTAGS_3` varchar(255) NOT NULL,
  PRIMARY KEY  (`OXID`),
  FULLTEXT KEY `OXTAGS`   (`OXTAGS`),
  FULLTEXT KEY `OXTAGS_1` (`OXTAGS_1`),
  FULLTEXT KEY `OXTAGS_2` (`OXTAGS_2`),
  FULLTEXT KEY `OXTAGS_3` (`OXTAGS_3`)
) ENGINE=MyISAM;


#
# Table structure for table `oxattribute`
#

DROP TABLE IF EXISTS `oxattribute`;

CREATE TABLE `oxattribute` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTITLE` char(128) NOT NULL default '',
  `OXTITLE_1` char(128) NOT NULL default '',
  `OXTITLE_2` char(128) NOT NULL default '',
  `OXTITLE_3` char(128) NOT NULL default '',
  `OXPOS` int(11) NOT NULL default '9999',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxuserbaskets`
#

DROP TABLE IF EXISTS `oxuserbaskets`;

CREATE TABLE `oxuserbaskets` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXCREATE` timestamp(14) NOT NULL,
  `OXPUBLIC` tinyint(1) DEFAULT '1' NOT NULL,
  PRIMARY KEY  (`OXID`)
) TYPE=InnoDB;

#
# Table structure for table `oxuserbasketitems`
#

DROP TABLE IF EXISTS `oxuserbasketitems`;

CREATE TABLE `oxuserbasketitems` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXBASKETID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXAMOUNT` char(32) NOT NULL default '',
  `OXSELLIST` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXBASKETID` (`OXBASKETID`),
  KEY `OXARTID` (`OXARTID`)
) TYPE=InnoDB;

#
# Table structure for table `oxcategories`
#

DROP TABLE IF EXISTS `oxcategories`;

CREATE TABLE `oxcategories` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXPARENTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default 'oxrootid',
  `OXLEFT` int(11) NOT NULL default '0',
  `OXRIGHT` int(11) NOT NULL default '0',
  `OXROOTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSORT` int(11) NOT NULL default '9999',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXHIDDEN` tinyint(1) NOT NULL default '0',
  `OXSHOPID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTITLE` varchar(254) NOT NULL default '',
  `OXDESC` varchar(255) NOT NULL default '',
  `OXLONGDESC` text NOT NULL,
  `OXTHUMB` varchar(128) NOT NULL default '',
  `OXEXTLINK` varchar(255) NOT NULL default '',
  `OXTEMPLATE` varchar(128) NOT NULL default '',
  `OXDEFSORT` varchar(64) NOT NULL default '',
  `OXDEFSORTMODE` tinyint(1) NOT NULL default '0',
  `OXPRICEFROM` double default NULL,
  `OXPRICETO` double default NULL,
  `OXACTIVE_1` tinyint(1) NOT NULL default '0',
  `OXTITLE_1` varchar(255) NOT NULL default '',
  `OXDESC_1` varchar(255) NOT NULL default '',
  `OXLONGDESC_1` text NOT NULL,
  `OXACTIVE_2` tinyint(1) NOT NULL default '0',
  `OXTITLE_2` varchar(255) NOT NULL default '',
  `OXDESC_2` varchar(255) NOT NULL default '',
  `OXLONGDESC_2` text NOT NULL,
  `OXACTIVE_3` tinyint(1) NOT NULL default '0',
  `OXTITLE_3` varchar(255) NOT NULL default '',
  `OXDESC_3` varchar(255) NOT NULL default '',
  `OXLONGDESC_3` text NOT NULL,
  `OXICON` varchar(128) NOT NULL default '',
  `OXVAT` FLOAT NULL DEFAULT NULL,
  `OXSKIPDISCOUNTS` tinyint(1) NOT NULL default '0',
  `OXSHOWSUFFIX` tinyint(1) NOT NULL default '1',
   PRIMARY KEY  (`OXID`),
   KEY `OXROOTID` (`OXROOTID`),
   KEY `OXPARENTID` (`OXPARENTID`),
   KEY `OXPRICEFROM` (`OXPRICEFROM`),
   KEY `OXPRICETO` (`OXPRICETO`),
   KEY `OXHIDDEN` (`OXHIDDEN`),
   KEY `OXSHOPID` (`OXSHOPID`),
   KEY `OXSORT` (`OXSORT`),
   KEY `OXVAT` (`OXVAT`)
) TYPE=MyISAM;

#
# Table structure for table `oxconfig`
#

DROP TABLE IF EXISTS `oxconfig`;

CREATE TABLE `oxconfig` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXVARNAME` char(32) NOT NULL default '',
  `OXVARTYPE` varchar(4) NOT NULL default '',
  `OXVARVALUE` blob NOT NULL,
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`),
  KEY `OXVARNAME` (`OXVARNAME`)
) TYPE=MyISAM;

#
# Data for table `oxconfig`
#

INSERT INTO `oxconfig` VALUES ('8563fba1965a11df3.34244997', 'oxbaseshop', 'blEnterNetPrice', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('8563fba1965a1cc34.52696792', 'oxbaseshop', 'blCalculateDelCostIfNotLoggedIn', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('8563fba1965a1f266.82484369', 'oxbaseshop', 'blAllowUnevenAmounts', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('8563fba1965a219c9.51133344', 'oxbaseshop', 'blUseStock', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('8563fba1965a23786.00479842', 'oxbaseshop', 'blStoreCreditCardInfo', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('8563fba1965a25500.87856483', 'oxbaseshop', 'dDefaultVAT', 'str', 0x07a1);
INSERT INTO `oxconfig` VALUES ('8563fba1965a27185.06428911', 'oxbaseshop', 'sDefaultLang', 'str', 0xde);
INSERT INTO `oxconfig` VALUES ('8563fba1965a2b330.65668120', 'oxbaseshop', 'sMerchantID', 'str', '');
INSERT INTO `oxconfig` VALUES ('8563fba1965a2d181.97927980', 'oxbaseshop', 'sHost', 'str', 0x00d0e1aeebd778fac282663570d1660f41dc61385dbcd5d5d6f6);
INSERT INTO `oxconfig` VALUES ('7fc4007ffb2639208.44268873', 'oxbaseshop', 'sGZSLogFile', 'str', '');
INSERT INTO `oxconfig` VALUES ('8563fba1965a2eee6.68137602', 'oxbaseshop', 'sPaymentUser', 'str', '');
INSERT INTO `oxconfig` VALUES ('8563fba1965a30cf7.41846088', 'oxbaseshop', 'sPaymentPwd', 'str', '');
INSERT INTO `oxconfig` VALUES ('8563fba1965a389e2.11728478', 'oxbaseshop', 'aLanguages', 'aarr', 0x4dba832f744c5786a371d9df3377ea87f0e2773dbaf685493e0b949a1c149111959424345b628f640a0d92ea6047ec118252e992);
INSERT INTO `oxconfig` VALUES ('39893a0ef6a6e11645d4beee4fd0cd51', 'oxbaseshop', 'aLanguageParams', 'aarr', 0x4dba832f744c5786a371d9df33778f9525f408b6efbc82de7c3c5ae3396caa6f8afb6864afa833b43597cad1fb8f9b8970c8e9098d10aae1be4637faa40a012a04e45a8a1cdd1b2ac3da558638600e58acf70fe8c192b668995bb533dac95be7af7d343b3a9c9b8daeaf4d637f065895346773476d667de331fe40d18765d4b98faf7375e1090587d8dd4bf98ad5005eb30666410920);
INSERT INTO `oxconfig` VALUES ('3985a8ab4dc5d26549d23856b5d84371', 'oxbaseshop', 'aLanguageSSLURLs', 'arr', 0x4dba832f74e74df4cdd5afca153f15e216aea908af01b8);
INSERT INTO `oxconfig` VALUES ('398609402285b0f3e629be51bce4d124', 'oxbaseshop', 'aLanguageURLs', 'arr', 0x4dba832f74e74df4cdd5afca153f15e216aea908af01b8);
INSERT INTO `oxconfig` VALUES ('60542e90eff80a4e9.05492701', 'oxbaseshop', 'aCurrencies', 'arr', 0x4dba852e75e64cf5ccd4aea3e152054127ec2d8c1077b7849319dbb81b0ebffb7d3f08b3fc61195546ae260a384a5c3c59e6dd25d7a2999758345e143d7cc8082cc82776aad1eb228e5b750241dd1b79a692326afe2c967dfcf5d44daf3dce9b79614bd82c3bbbf006b7aabc4b7e2f0fb6a79fdc0638389a4844);
INSERT INTO `oxconfig` VALUES ('8563fba1965a43873.40898997', 'oxbaseshop', 'aLexwareVAT', 'aarr', 0x4dba682873e04a2acbd3a9a4113b832e198a7e75fb770da528d4e916d042856bcaa4b6795b839a7c836f43faae6ef75d3e6f91e3a0384990c0b7fae81c46aeca010521bb89b5);
INSERT INTO `oxconfig` VALUES ('545423fe8ce213a06.20230295', 'oxbaseshop', 'aNrofCatArticles', 'arr', 0x4dbace2972e14bf2cbd3a9a4113b83ad1c8f7b704f710ba39fd1ecd29b438b41809712e316c6f4fdc92741f7876cc6fca127d78994e604dcc99519);
INSERT INTO `oxconfig` VALUES ('8563fba1baec4d3b7.61553539', 'oxbaseshop', 'iNrofSimilarArticles', 'str', 0x5d);
INSERT INTO `oxconfig` VALUES ('8563fba1baec4f6d3.38812651', 'oxbaseshop', 'iNrofCustomerWhoArticles', 'str', 0x5d);
INSERT INTO `oxconfig` VALUES ('8563fba1baec515d0.57265727', 'oxbaseshop', 'iNrofCrossellArticles', 'str', 0x5d);
INSERT INTO `oxconfig` VALUES ('8563fba1baec55dc8.04115259', 'oxbaseshop', 'iUseGDVersion', 'str', 0xb6);
INSERT INTO `oxconfig` VALUES ('8563fba1baec57c19.08644217', 'oxbaseshop', 'sThumbnailsize', 'str', 0x07c4b144c7b838);
INSERT INTO `oxconfig` VALUES ('8563fba1baec599d5.89404456', 'oxbaseshop', 'sCatThumbnailsize', 'str', 0x5d43334072bf3f);
INSERT INTO `oxconfig` VALUES ('8563fba1baec5b7d3.75515041', 'oxbaseshop', 'sCSVSign', 'str', 0x86);
INSERT INTO `oxconfig` VALUES ('8563fba1baec5d615.45874801', 'oxbaseshop', 'iExportNrofLines', 'str', 0xb644b7);
INSERT INTO `oxconfig` VALUES ('8563fba1baec678e2.44233324', 'oxbaseshop', 'iExportTickerRefresh', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('8563fba1baec6acc3.69139343', 'oxbaseshop', 'iImportNrofLines', 'str', 0x07c4b1);
INSERT INTO `oxconfig` VALUES ('8563fba1baec6cce8.28843189', 'oxbaseshop', 'iImportTickerRefresh', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('8563fba1baec6eaf2.01241384', 'oxbaseshop', 'iCntofMails', 'str', 0xb6c7);
INSERT INTO `oxconfig` VALUES ('8563fba1baec73b00.28734905', 'oxbaseshop', 'aOrderfolder', 'aarr', 0x4dba852e754d56360c19978b3f1481d799910f7f100e9ee73438ded0565e1a5edadd7c2846da44546f068ea2903bf5953068bc0cde9838848b7b31b27787c304bab9fe83bde678242f3645cb050632af58ea55b47cb51d45d03e8bd7cb984b2c2cd0fce8b09f09a2d796f5d3faa7f0ddb6b45d4554b6a7521f75503cd75b0c);
INSERT INTO `oxconfig` VALUES ('8563fba1c39367724.92308656', 'oxbaseshop', 'blCheckTemplates', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('8563fba1c3936a472.04220012', 'oxbaseshop', 'blTemplateCaching', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('8563fba1c3936e4e0.64567448', 'oxbaseshop', 'blLoadFullTree', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('8563fba1c39370d88.58444180', 'oxbaseshop', 'blLogChangesInAdmin', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('8563fba1c393750a0.46170041', 'oxbaseshop', 'sUtilModule', 'str', '');
INSERT INTO `oxconfig` VALUES ('8563fba1c3937ee60.91079898', 'oxbaseshop', 'iMallMode', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('8563fba1c39381962.39392958', 'oxbaseshop', 'aCacheViews', 'arr', 0x4dba852e75e64cf5ccd4ae48113baca2c2f96704c777824cbc13da72f905064aea430c1a75e7bb914d80360cf25cf5bd5ed9fcaf3d310ab4);
INSERT INTO `oxconfig` VALUES ('8563fba1c39386cf4.18302736', 'oxbaseshop', 'aSkipTags', 'arr', 0x4dba85c975d460d7927733e9525403bc01ae3616da4e6cdf0a9b83cf8359894abce65f2103ad7e83270c4eb019ecf2fc0a3dcde5325b2bb08143bb43ec2c868c29d48dc7bea7f3abf16f6ebd6b97c50114bb53f0f23f59568f0fe9da452cfab264b8aa17ba9e978e892fc6cdef47b7f495e487027dcd08f12ce35d7d997b031d80044d60ba090f1d82a01b62d201d77ef25ce01e68a94948b3d48c2f6c5d612c2dcd6e8af2f00dd435f5e4a4884431560fe092e46de90ebdea5199915de557220607bfc0f7c9c945192e7640e2fda7d7f36ff1215b22ea4b3569cb47763d13e81f0a2dcf9398a5ccdd093ffa578c3c505b13a91d85f0d839543b340a4407ff6ec7d0948b0e7794bc05b993636dd6ac010b7c315f671a5c9b734402efbe207473995291e3122d474f0a86d07d643df2910af62397b4dbfb27c2bc2485498d0ff6bd0eaadc6e63a0fbb596fb50f7dc04a26f6ea8fc1b36f3ea274de76375b6dc82b3924a048a7f8a6ea741e8325b280a8d8c8c33c9d044fae750ad46b80dccfd8ae0c8471bf20c4236ecc4f3220011f7318b51e8c4276141f29a88c248a7563e89decc6561ac568f444fc75b5721947e980a280cde376532a0c7af16d6ad3a7decf89a8c3f1fd923fb11f8dd3bdea9319c71ba0be02c7f1fa10c276240727b56aafa61cc48f5b4f4852d184b3cf12e879a7d96b3b3134de64d0a9f8582632d1d18e1e7c007e2fc5dc95fd460e9d02db3fd2958ca5600d1b66f0853a6cd1488133f0299e1f20f);
INSERT INTO `oxconfig` VALUES ('8563fba1c3938c994.40718405', 'oxbaseshop', 'aModules', 'aarr', 0x4dba322c774f5444a5777125d61918a96e9e65e1b8fba2e9f6f8ff4e240b745241e4b01edd9224c81f3020f58a2d);
INSERT INTO `oxconfig` VALUES ('8563fba1c3938ebe7.95075058', 'oxbaseshop', 'aLogSkipTags', 'arr', 0x4dbaeb2d768d);
INSERT INTO `oxconfig` VALUES ('79c3fbc9897c0d159.27469500', 'oxbaseshop', 'blLoadVariants', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('b2b400dd011bf6273.08965005', 'oxbaseshop', 'blVariantsSelection', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('b883fc1dd260a1494.25373720', 'oxbaseshop', 'iShopID_iPayment_Account', 'str', '');
INSERT INTO `oxconfig` VALUES ('b883fc1dd260a3f28.32999702', 'oxbaseshop', 'iShopID_iPayment_User', 'str', '');
INSERT INTO `oxconfig` VALUES ('b883fc1dd260a60c7.01993775', 'oxbaseshop', 'iShopID_iPayment_Passwort', 'str', '');
INSERT INTO `oxconfig` VALUES ('c083fc73e85b65906.75446129', 'oxbaseshop', 'iShopID_pointspereuro', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('c083fc74093591ea3.61037664', 'oxbaseshop', 'iShopID_pointspernews', 'str', 0xb0);
INSERT INTO `oxconfig` VALUES ('43040112c71dfb0f2.40367454', 'oxbaseshop', 'sDefaultImageQuality', 'str', 0x7741);
INSERT INTO `oxconfig` VALUES ('51240739e4bc61362.43751239', 'oxbaseshop', 'iMaxGBEntriesPerDay', 'str', 0xb0);
INSERT INTO `oxconfig` VALUES ('4994145b9e87481c5.69580772', 'oxbaseshop', 'aSortCols', 'arr', 0x4dba832f74e74df4cdd5af631238e7040fc97a18cf6cb28fd522f05ae28cf374f04ceeb7bd886eb10ac36ba86043beb02e);
INSERT INTO `oxconfig` VALUES ('4994145b9e8736eb6.03785000', 'oxbaseshop', 'iTop5Mode', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('4994145b9e8678993.26056670', 'oxbaseshop', 'blShowSorting', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('a104164f96fa51c41.58873414', 'oxbaseshop', 'aSearchCols', 'arr', 0x4dba682873e04af3cad2a864153fe00308ce7d1fc86bb588d225f75de58b4371f549ebf5f054a8aa5d72ff4f9b5bb590240b14921d5f21962f67c7bd29417e61149f025b96cdf815d975cc85278913ee4b505bdfea13af328807c5ddd68d655b20d74de1e812236ebd97ee);
INSERT INTO `oxconfig` VALUES ('d144175015dcd2a39.15131643', 'oxbaseshop', 'aHomeCountry', 'arr', 0x4dba322c77e44ef7ced6aca1f357003cad231d1d78fe80070841979cd58fd7eca88459d4cb9ce3b72a2804d5);
INSERT INTO `oxconfig` VALUES ('ce143201f7e03e110.09792514', 'oxbaseshop', 'aMustFillFields', 'arr', 0x4dba322775d460d7927733e9e5fb6bf2ef688abcc84baef2405f16b906eec019f3a63b927c45a833864604543fe611a86d4a9f4027235e1a3f8572bfe00be3f1f0efee2efcc915c759d77d9270c9fef10bc707cdf5bc1a299c3795b96e0b85d032c55ff31364daf0e7a37ec5362cfdfb60e2de223e8160c91b08887f22196bfa2abae5f5d862fb1d0a7e35b2ceaf862088ab34b7029b1d598e61c436d21111682cf3442e4f9f16b936b1cdc085ed0dbda4b996a2a573c0aa47d3fb73ab13d4193b4d32c87bf9994e175f864102872ef2535d5d3df359ca2b25d26640038bbe74de0c8e2ef4b4c4e887afc4d889da38c63bf1c13c57a5c8d3f66a0615e155e4c3ec6dc279693b96e5b04004171fca59cb133027c34a309d9393736914ba027d21fa8ef1b9c79ec170ffa1a2bbf4746175c0e04b9cff68ae4f2875973518b9b1abc64f8e940d42183ed4ec6e1d285b2503869374d82727fae6f33ef4dd71c52de6bf9e460837768460a9fe62570ba2f75e83fd21be7e0c8fb78106e713d0e2e79fd19f04304989166dda296361a897ad15cc9f11db0566c70e968282da76ebb76fef0409f0);
INSERT INTO `oxconfig` VALUES ('79e417a3916b910c8.31517473', 'oxbaseshop', 'bl_perfLoadAktion', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a4201010a12.85717286', 'oxbaseshop', 'bl_perfLoadReviews', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a420101f3e6.18536996', 'oxbaseshop', 'bl_perfLoadCrossselling', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a4201028c21.24163259', 'oxbaseshop', 'bl_perfLoadAccessoires', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a420103a598.95673089', 'oxbaseshop', 'bl_perfLoadCustomerWhoBoughtThis', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a4201044603.06076651', 'oxbaseshop', 'bl_perfLoadSimilar', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a420104dbd8.25267555', 'oxbaseshop', 'bl_perfLoadSelectLists', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a4201062a60.33852458', 'oxbaseshop', 'bl_perfLoadDiscounts', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a420106baa7.25594072', 'oxbaseshop', 'bl_perfLoadDelivery', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a420107ab46.59697382', 'oxbaseshop', 'bl_perfLoadPrice', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a442934fcb9.11733184', 'oxbaseshop', 'bl_perfLoadCatTree', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a45558d97f6.76133435', 'oxbaseshop', 'bl_perfLoadCurrency', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a45558e7851.36128674', 'oxbaseshop', 'bl_perfLoadLanguages', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a45558f1b86.05956285', 'oxbaseshop', 'bl_perfLoadNews', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a466086f390.29565974', 'oxbaseshop', 'bl_perfLoadNewsOnlyStart', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('c20424bf2f8e71271.42955545', 'oxbaseshop', 'bl_perfLoadTreeForSearch', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('36d42513de8cab671.54909813', 'oxbaseshop', 'bl_perfShowActionCatArticleCnt', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('7044252b61dc89982.15135968', 'oxbaseshop', 'bl_perfLoadCompare', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('7044252b61dcb8ac9.31672388', 'oxbaseshop', 'bl_perfLoadPriceForAddList', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('7044252b61dd44324.24181665', 'oxbaseshop', 'bl_perfParseLongDescinSmarty', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('77c425a29db68f0d9.00182375', 'oxbaseshop', 'bl_perfLoadManufacturerTree', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('669425a324684b6c0.57696393', 'oxbaseshop', 'bl_perfShowLeftBasket', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('669425a3246855199.09823559', 'oxbaseshop', 'bl_perfShowRightBasket', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('79e417a4eaad1a593.54850808', 'oxbaseshop', 'blStoreIPs', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('79e417a4eaad9dfa6.77588633', 'oxbaseshop', 'aDeniedDynGroups', 'arr', 0x4dba322c77e44ef7ced6acac1f35ea091294b94a7572f88ffe92);
INSERT INTO `oxconfig` VALUES ('33341949f476b65e8.17282442', 'oxbaseshop', 'iAttributesPercent', 'str', 0x77c2);
INSERT INTO `oxconfig` VALUES ('43141b9b252874600.34636487', 'oxbaseshop', 'sDecimalSeparator', 'str', 0xc9);
INSERT INTO `oxconfig` VALUES ('bf041bd98dacd9021.61732877', 'oxbaseshop', 'aInterfaceProfiles', 'aarr', 0x4dbace29724a51b6af7d09aac117301142e91c3c5b7eed9a850f85c1e3d58739aa9ea92523f05320a95060d60d57fbb027bad88efdaa0b928ebcd6aacf58084d31dd6ed5e718b833f1079b3805d28203f284492955c82cea3405879ea7588ec610ccde56acede495);
INSERT INTO `oxconfig` VALUES ('e8e41bda6fa7631d8.13775806', 'oxbaseshop', 'iSessionTimeout', 'str', 0x17c3);
INSERT INTO `oxconfig` VALUES ('6ec4235c5182c3620.11050422', 'oxbaseshop', 'iNrofNewcomerArticles', 'str', 0xfb);
INSERT INTO `oxconfig` VALUES ('6ec4235c2aaa45d77.87437919', 'oxbaseshop', 'sIconsize', 'str', 0x5d09ae6470);
INSERT INTO `oxconfig` VALUES ('6ec4235c2aaa8eec5.99966057', 'oxbaseshop', 'sMidlleCustPrice', 'str', 0xfbc1);
INSERT INTO `oxconfig` VALUES ('6ec4235c2aaa97585.69723730', 'oxbaseshop', 'sLargeCustPrice', 'str', 0x07c4b1);
INSERT INTO `oxconfig` VALUES ('6ec4235c2aa997942.70260123', 'oxbaseshop', 'blWarnOnSameArtNums', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('a7a425c02819f7253.64374401', 'oxbaseshop', 'blAutoIcons', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('7e9426025ff199d75.57820200', 'oxbaseshop', 'sStockWarningLimit', 'str', 0x07c4);
INSERT INTO `oxconfig` VALUES ('9a8426df9d36443e7.48701626', 'oxbaseshop', 'blSearchUseAND', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('a99427345bf85a602.27736147', 'oxbaseshop', 'blDontShowEmptyCategories', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('a99427345bf8fcff2.83464949', 'oxbaseshop', 'bl_perfUseSelectlistPrice', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('a99427345bf9a27a1.04791092', 'oxbaseshop', 'bl_perfCalcVatOnlyForBasketOrder', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('2ca4277aa49a5bd27.44511187', 'oxbaseshop', 'blStockOnDefaultMessage', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('2ca4277aa49a634f8.76432326', 'oxbaseshop', 'blStockOffDefaultMessage', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('bec42a95d05951091.98848474', 'oxbaseshop', 'iNewBasketItemMessage', 'str', 0xb6);
INSERT INTO `oxconfig` VALUES ('6da42abf915b5f290.70877375', 'oxbaseshop', 'sCntOfNewsLoaded', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('89e42b02704ce5589.91950338', 'oxbaseshop', 'iNewestArticlesMode', 'str', 0x07);
INSERT INTO `oxconfig` VALUES ('e1142ca231becd5c4.00590616', 'oxbaseshop', 'blConfirmAGB', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('62642dfaa1d88b1b2.94593071', 'oxbaseshop', 'aZoomImageSizes', 'aarr', 0x4dbace29724a5141a07274204edcbb15d2d328acf5c699c5c961b5a7a697d857bd436fb658ab3a493887f51ae808e94320b08af8f6a61adfa35c30b7e783bc1135dec501ad2044dbd7687502411a6f1ad6406499d626443d87a3d044b627b375703f1dfcc5cfc674c264fb3affbf8abb576c8e5d22fa);
INSERT INTO `oxconfig` VALUES ('62642dfaa1d87d064.50653921', 'oxbaseshop', 'aDetailImageSizes', 'aarr', 0x4dba326a73d2cdcb471b9533d7800b4b898873f7ae9dc29edf3ce8fab64f8609e31d318807f46516ea31aa94cb0b4edaaf32e7cb502403b480dd7cb1451f56975c3fd6159579cd2cab97104f17ae6a99af864bc1acb550c7e78b82f4618aeb8ba7fbec5409f277e0a2b84e66c24f96ba3fa76665f6a9294d8bf365bf7d3d0d56faf2355df799bc2892994db56203b0f5967ddbe8d403cead91988dfc82772557950eb1ba0d9468f3d8ca7170cde789d6c1282016056e51005091e7440fa453b1235c40010a71ff46f681c74515b4fda6da204abf3178561e271f8202652eabe106a93f9f4d1ed363f2f33c1e29716b95be88112373c48373148b134f2e0312bcfa2f2ba96f5cb15338dee7265d0efc66fe6526a6047b0e2bc4896143076e8dbc7dd8a7448ba2a5233814dd6abc39cb811a4d295c95cdaffde7cb8a5a3fddfe14f9a580973e9660a622f0d774bdb9);
INSERT INTO `oxconfig` VALUES ('85342e4cab0acb390.83838984', 'oxbaseshop', 'blShowBirthdayFields', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('2e4452b5763e03c74.88240349', 'oxbaseshop', 'blDisableDublArtOnCopy', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('fde4559837789b3c7.26965372', 'oxbaseshop', 'aCMSfolder', 'aarr', 0x4dbace29724a5131411d93d207fd82ee70b3e465e8c18e1b60a35eb597a1f3bad1e50ee52570c9ca486b4755b08cea9d0a17892b1e7628a152af0ab842c7c310547016f7c53a9ad0d62060ca7fc75f2bf6892a6f9987d014c0418d2b1e7a6defd8e0d2f5b189c89b886c5d130a72f1dcb7b55c4455b720ce73743f3ed559cda8621a523aa1021ed09f9a1f0177fc9e7ab5920621aa55a368bfeb28ae782c3456362aee);
INSERT INTO `oxconfig` VALUES ('6f8453f77d174e0a0.31854175', 'oxbaseshop', 'blOtherCountryOrder', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('0a5455450f97fdec9.37454802', 'oxbaseshop', 'blAllowNegativeStock', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('omc4555952125c3c2.98253113', 'oxbaseshop', 'blDisableNavBars', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('e73455b29d0db9b78.23162955', 'oxbaseshop', 'blShowFinalStep', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('b0b4d221756c80afdad8904c0b91b877', 'oxbaseshop', 'iRssItemsCount', 'str', 0xb6c7);
INSERT INTO `oxconfig` VALUES ('9fa5abe1bd51b6bbe97f95d8199660e5', 'oxbaseshop', 'aSeoReplaceChars', 'aarr', 0x4dba422a71495232a5777b11e101a218a65b6b8b54eb9139b44875f08d1f732c8b944cff73f2854633a67da52ac4258fecbd4331beae8950ab6d7a407e73fddcddc272e7bb6d190b8cb03718368f899425b48d2108358c2e40c08d0f808894c323ba6240f4d0b7fb5aa4bab1938cc98a9d2045789d6fc428547da6cb0d);
INSERT INTO `oxconfig` VALUES ('8b831f739c5d16cf4571b14a76006568', 'oxbaseshop', 'aSEOReservedWords', 'arr', 0x4dba422a71e248f1c8d0aa4c153fcde9eec56a0fcc7c8947b718d1dff30f2db6d7a60c59398fb5e1aa5999cfde45071ab225fba4d72b3ba9c23a4b0adb75314b1e7a2de97adee42d81197c0b48d4621740313f9df1ad63f693b7c47aa031ed88093c0e12eb85a75de769ede4f57823a56c6576106fb7);
INSERT INTO `oxconfig` VALUES ('cb6cdb441255938e1d311bb7104202b8', 'oxbaseshop', 'aRssSelected', 'arr', 0x4dbace2972e14bf2cbd3a91552540312fdb89dff9b147c0068096323a537f01e08d3c10e9db1838a83fe046c5136fbf8900f15f0c03307f5e788c7903ceca9e6a5341f11619d68ddd447f63664c6348ec0f55993b4d3923b7d4ce09603e84c4099a7505f62ab3810f0daa3);
INSERT INTO `oxconfig` VALUES ('46473ac9c01a70e0485f6e529a9d924b', 'oxbaseshop', 'bl_perfShowTopBasket', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('b1296159b7641d31b93423972af6150b', 'oxbaseshop', 'blTopNaviLayout', 'bool', 0x93ea1218);
INSERT INTO `oxconfig` VALUES ('fd770460540c32422b415a65fefb8f90', 'oxbaseshop', 'blLoadDynContents', 'bool', 0x7900fdf51e);
INSERT INTO `oxconfig` VALUES ('fd7a064bbb64466f8e6ba847902b2005', 'oxbaseshop', 'sShopCountry', 'str', '');
INSERT INTO `oxconfig` VALUES ('44bcd90bd1d059.053753111', 'oxbaseshop', 'sTagList', 'str', 0x071d33336bce8dbe0606);
INSERT INTO `oxconfig` VALUES ('603a1a28ff2a421b64c631ffaf97f324', 'oxbaseshop', 'sGiCsvFieldEncloser', 'str', 0x95);


#
# Table structure for table `oxcontents`
#

DROP TABLE IF EXISTS `oxcontents`;

CREATE TABLE `oxcontents` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXLOADID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSHOPID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSNIPPET` tinyint(1) NOT NULL default '1',
  `OXTYPE` tinyint(1) NOT NULL default '0',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXACTIVE_1` tinyint(1) NOT NULL default '1',
  `OXPOSITION` varchar(32) NOT NULL default '',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXCONTENT` text NOT NULL,
  `OXTITLE_1` varchar(255) NOT NULL default '',
  `OXCONTENT_1` text NOT NULL,
  `OXACTIVE_2` tinyint(1) NOT NULL default '1',
  `OXTITLE_2` varchar(255) NOT NULL default '',
  `OXCONTENT_2` text NOT NULL,
  `OXACTIVE_3` tinyint(1) NOT NULL default '1',
  `OXTITLE_3` varchar(255) NOT NULL default '',
  `OXCONTENT_3` text NOT NULL,
  `OXCATID` varchar(32) character set latin1 collate latin1_general_ci default NULL,
  `OXFOLDER` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY  (`OXID`),
  UNIQUE KEY `OXLOADID` (`OXLOADID`),
  INDEX `cat_search` ( `OXTYPE` , `OXSHOPID` , `OXSNIPPET` , `OXCATID` )
) TYPE=MyISAM;

#
# Table structure for table `oxcontents`
#

INSERT INTO `oxcontents` VALUES ('c4241316b2e5c1966.96997015', 'oxstartwelcome', 'oxbaseshop', '1', '0', '1', '1', '', 'start.tpl Begrüßungstext', '<h1><strong>Willkommen</strong> [{ if $oxcmp_user }]<strong>[{ $oxcmp_user->oxuser__oxfname->value }] [{ $oxcmp_user->oxuser__oxlname->value }] </strong>[{else}] [{/if}][{ if !$oxcmp_user }]<strong>im OXID <span style="color: #ff3301;">e</span>Shop 4</strong>[{/if}]\r\n</h1>\r\nDies ist eine Demo-Installation des <strong>OXID eShop 4</strong>. Also keine Sorge, wenn Sie bestellen: Die Ware wird weder ausgeliefert, noch in Rechnung gestellt. Die gezeigten Produkte (und Preise) dienen nur zur Veranschaulichung der umfangreichen Funktionalität des Systems.\r\n<div><strong>&nbsp;</strong></div>\r\n<div><strong>Wir wünschen viel Spa&szlig; beim Testen!</strong></div>\r\n<div><strong>Ihr OXID eSales Team</strong></div>', 'start.tpl welcome text', '<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> start.tpl welcome text</div>\r\n<div>&nbsp;</div>', '1', '', '', '1', '', '', '', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('1544167b4666ccdc1.28484600', 'oxblocked', 'oxbaseshop', '1', '0', '1', '1', '', 'Benutzer geblockt', '<div><span style="color: #ff0000;"><strong>\r\n<img title="" height="200" alt="" src="[{$oViewConf->getPictureDir()}]wysiwigpro/stopsign.jpg" width="200"></strong></span></div>\r\n<div><span style="color: #ff0000;"><strong>Der Zugang wurde Ihnen verweigert !</strong></span></div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>', 'user blocked', '<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> user blocked</div>\r\n<div>&nbsp;</div>', '1', '', '', '1', '', '', '', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('c4241316c6e7b9503.93160420', 'oxbargain', 'oxbaseshop', '1', '0', '1', '1', '', 'Schnäppchen', '<table>[{foreach from=$oView->getBargainArticleList() item=articlebargain_item}] <tbody><tr><td>\r\n<div class="product_image_s_container"><a href="[{$articlebargain_item->getLink()}]"><img border="0" alt="[{ $articlebargain_item->oxarticles__oxtitle->value }][{if $articlebargain_item->oxarticles__oxvarselect->value }] [{ $articlebargain_item->oxarticles__oxvarselect->value }][{/if}] [{$oxcmp_shop->oxshops__oxtitlesuffix->value}]" src="[{ $articlebargain_item->getDynImageDir()}]/[{$articlebargain_item->oxarticles__oxicon->value}]"></a></div> </td><td class="boxrightproduct-td"> <a href="[{$articlebargain_item->getLink()}]" class="boxrightproduct-td"><strong>[{ $articlebargain_item->oxarticles__oxtitle->value|cat:"\r\n"|cat:$articlebargain_item->oxarticles__oxvarselect->value|strip_tags|smartwordwrap:15:"<br>\r\n":2:1:"..." }]</strong></a><br>\r\n [{ if $articlebargain_item->isBuyable() }] <a href="[{$articlebargain_item->getToBasketLink()}]&am=1" class="details" onclick="showBasketWnd();" rel="nofollow"><img border="0" src="[{$oViewConf->getImageUrl()}]/arrow_details.gif" alt=""> Jetzt bestellen! </a> [{/if}] </td></tr>[{/foreach}]\r\n</tbody></table>', 'Bargain', '<table>[{foreach from=$oView->getBargainArticleList() item=articlebargain_item}] <tbody><tr><td>\r\n<div class="product_image_s_container"><a href="[{$articlebargain_item->getLink()}]"><img border="0" src="[{ $articlebargain_item->getDynImageDir()}]/[{$articlebargain_item->oxarticles__oxicon->value}]" alt="[{ $articlebargain_item->oxarticles__oxtitle->value }][{if $articlebargain_item->oxarticles__oxvarselect->value }] [{ $articlebargain_item->oxarticles__oxvarselect->value }][{/if}] [{$oxcmp_shop->oxshops__oxtitlesuffix->value}]"></a></div> </td><td class="boxrightproduct-td"> <a class="boxrightproduct-td" href="[{$articlebargain_item->getLink()}]"><strong>[{ $articlebargain_item->oxarticles__oxtitle->value|cat:"\r\n"|cat:$articlebargain_item->oxarticles__oxvarselect->value|strip_tags|smartwordwrap:15:"<br>\r\n ":2:1:"..." }]</strong></a><br>\r\n [{ if $articlebargain_item->isBuyable()}] <a onclick="showBasketWnd();" class="details" href="[{$articlebargain_item->getToBasketLink()}]&am=1" rel="nofollow"><img border="0" alt="" src="[{$oViewConf->getImageUrl()}]/arrow_details.gif"> Order now! </a> [{/if}] </td></tr>[{/foreach}] </tbody></table>', '1', '', '', '1', '', '', 'oxrootid', 'CMSFOLDER_PRODUCTINFO');
INSERT INTO `oxcontents` VALUES ('2eb46767947d21851.22681675', 'oximpressum', 'oxbaseshop', '1', '0', '1', '1', '', 'Impressum', '<p>Fügen Sie hier Ihre Anbieterkennzeichnung ein.</p>', 'About Us', '<p>Add provider identification here.</p>', '0', '', '', '0', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('1074279e67a85f5b1.96907412', 'oxorderinfo', 'oxbaseshop', '1', '0', '1', '1', '', 'Wie bestellen?', '<div>Beispieltext:</div>\r\n<div>&nbsp;</div>\r\n<div>OXID eShop, Ihr Online-Shop für ... <br>\r\n<br>\r\nBei uns haben Sie die Wahl aus mehr als ... Artikeln von bester Qualität und namhaften Herstellern. Schauen Sie sich um, stöbern Sie in unseren Angeboten! <br>\r\nOXID eShop steht Ihnen im Internet rund um die Uhr und 7 Tage die Woche offen.<br>\r\n<br>\r\nUnd wenn Sie eine Bestellung aufgeben möchten, können Sie das: \r\n<ul>\r\n<li class="font11">direkt im Internet über unseren Shop </li>\r\n<li class="font11">per Fax unter&nbsp;+49(0)761-36889-29 </li>\r\n<li class="font11">per Telefon unter +49(0)761-36889-0 </li>\r\n<li class="font11">oder per E-Mail unter <a href="mailto:demo@oxid-esales.com?subject=Bestellung"><strong>demo@oxid-esales.com</strong></a> </li></ul>Telefonisch sind wir für Sie <br>\r\nMontag bis Freitag von 10 bis 18 Uhr erreichbar. <br>\r\nWenn Sie auf der Suche nach einem Artikel sind, der zum Sortiment von OXID eShop passen könnte, ihn aber nirgends finden, lassen Sie''s uns wissen. Gern bemühen wir uns um eine Lösung für Sie. <br>\r\n<br>\r\nSchreiben Sie an <a href="mailto:demo@oxid-esales.com?subject=Produktidee"><strong>demo@oxid-esales.com</strong></a>.</div>', 'How to order ?', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> how to order</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('f41427a07519469f1.34718981', 'oxdeliveryinfo', 'oxbaseshop', '1', '0', '1', '1', '', 'Versand und Kosten', '<p>Fügen Sie hier Ihre Versandinformationen- und kosten ein.</p>', 'Shipping and Charges', '<p>Add your shipping information and costs here.</p>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('f41427a099a603773.44301043', 'oxsecurityinfo', 'oxbaseshop', '1', '0', '1', '1', '', 'Datenschutz', 'Fügen Sie hier Ihre Datenschutzbestimmungen ein.', 'Data Protection', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> security information</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('f41427a10afab8641.52768563', 'oxnewstlerinfo', 'oxbaseshop', '1', '0', '1', '1', '', 'Neuigkeiten bei uns', '<div>Mit dem [{ $oxcmp_shop->oxshops__oxname->value }]-Newsletter alle paar Wochen. <br>\r\nMit Tipps, Infos, Aktionen ... <br>\r\n<br>\r\nDas Abo kann jederzeit durch Austragen der E-Mail-Adresse beendet werden. <br>\r\nEine <span class="newsletter_title">Weitergabe Ihrer Daten an Dritte lehnen wir ab</span>. <br>\r\n<br>\r\nSie bekommen zur Bestätigung nach dem Abonnement eine E-Mail - so stellen wir sicher, dass kein Unbefugter Sie in unseren Newsletter eintragen kann (sog. "Double Opt-In").<br>\r\n<br>\r\n</div>', 'newsletter info', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> newsletter info</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('ad542e49975709a72.52261121', 'oxnewsletteremail', 'oxbaseshop', '1', '0', '1', '1', '', 'Newsletter eShop', 'Hallo, [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }],<br>\r\nvielen Dank für Ihre Anmeldung zu unserem Newsletter.<br>\r\n<br>\r\nUm den Newsletter freizuschalten klicken Sie bitte auf folgenden Link:<br>\r\n<br>\r\n<a href="[{$oViewConf->getSelfLink()}]cl=newsletter&fnc=addme&uid=[{ $user->oxuser__oxid->value}]">[{$oViewConf->getSelfLink()}]cl=newsletter&fnc=addme&uid=[{ $user->oxuser__oxid->value}]</a><br>\r\n<br>\r\nIhr [{ $shop->oxshops__oxname->value }] Team<br>', 'newsletter confirmation', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> newsletter confirmation</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e4999ec01dd3.07214049', 'oxnewsletterplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Newsletter eShop Plain', '[{ $shop->oxshops__oxname->value }] Newsletter Hallo, [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }], vielen Dank für Ihre Anmeldung zu unserem Newsletter. Um den Newsletter freizuschalten klicken Sie bitte auf folgenden Link: [{$oViewConf->getSelfLink()}]cl=newsletter&fnc=addme&uid=[{ $user->oxuser__oxid->value}] Ihr [{ $shop->oxshops__oxname->value }] Team', 'newsletter confirmation plain', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> newsletter confirmation plain\r\n', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49ae50c60f0.64307543', 'oxuserorderemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung', 'Vielen Dank für Ihre Bestellung!<br>\r\n<br>\r\nNachfolgend haben wir zur Kontrolle Ihre Bestellung noch einmal aufgelistet.<br>\r\nBei Fragen sind wir jederzeit für Sie da: Schreiben Sie einfach an [{ $shop->oxshops__oxorderemail->value }]!<br>\r\n<br>', 'your order', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> your order</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49bff479009.64538090', 'oxadminorderemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Admin', 'Folgende Artikel wurden soeben unter [{ $shop->oxshops__oxname->value }] bestellt:<br>\r\n<br>', 'your order admin', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> your order admin</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49b08c65017.19848749', 'oxuserorderplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Plain', 'Vielen Dank für Ihre Bestellung!\r\n\r\nNachfolgend haben wir zur Kontrolle Ihre Bestellung noch einmal aufgelistet.\r\nBei Fragen sind wir jederzeit für Sie da: Schreiben Sie einfach an [{ $shop->oxshops__oxorderemail->value }] !', 'your order plain', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> your order plain\r\n\r\n', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49c19109ad6.04198712', 'oxadminorderplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Admin Plain', 'Folgende Artikel wurden soeben unter [{ $shop->oxshops__oxname->value }] bestellt :', 'your order admin plain', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> your order admin plain\r\n', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('c8d45408c08bbaf79.09887022', 'oxuserordernpemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung (Fremdländer)', '<div>Vielen Dank für Ihre Bestellung!</div>\r\n<p><strong><span style="color: #ff0000">Hinweis:</span></strong> Derzeit ist uns keine Versandmethode für dieses Land bekannt. Wir werden versuchen, Versandmethoden zu finden und Sie über das Ergebnis unter Angabe der Versandkosten informieren. </p>Bei Fragen sind wir jederzeit für Sie da: Schreiben Sie einfach an [{ $shop->oxshops__oxorderemail->value }]! <br />\r\n<br />', 'your order (other country)', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> your order</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('c8d45408c4998f421.15746968', 'oxadminordernpemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Admin (Fremdländer)', '<div>\r\n<p> <span style="color: #ff0000;"><strong>Hinweis:</strong></span> Derzeit ist keine Liefermethode für dieses Land bekannt. Bitte Liefermöglichkeiten suchen und den Besteller unter Angabe der <strong>Lieferkosten</strong> informieren!\r\n&nbsp;</p> </div>\r\n<div>Folgende Artikel wurden soeben unter [{ $shop->oxshops__oxname->value }] bestellt:<br>\r\n<br>\r\n</div>', 'your order admin (other country)', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> your order admin</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('c8d45408c5c39ea22.75925645', 'oxuserordernpplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung (Fremdländer) Plain', 'Vielen Dank für Ihre Bestellung!\r\n\r\nHinweis: Derzeit ist uns keine Versandmethode für dieses Land bekannt. Wir werden versuchen, Versandmethoden zu finden und Sie über das Ergebnis unter Angabe der Versandkosten informieren.\r\n\r\nBei Fragen sind wir jederzeit für Sie da: Schreiben Sie einfach an [{ $shop->oxshops__oxorderemail->value }]!', 'your order plain (other country)', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> your order plain\r\n\r\n', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('c8d45408c718782f3.21298666', 'oxadminordernpplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Admin (Fremdländer) Plain', 'Hinweis: Derzeit ist keine Liefermethode für dieses Land bekannt. Bitte Liefermöglichkeiten suchen und den Besteller informieren!\r\n\r\nFolgende Artikel wurden soeben unter [{ $shop->oxshops__oxname->value }] bestellt:', 'your order admin plain (other country)', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> your order admin plain\r\n', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49c585394e4.36951640', 'oxpricealarmemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Preisalarm', 'Preisalarm im [{ $shop->oxshops__oxname->value }]!<br>\r\n<br>\r\n[{ $email }] bietet für Artikel [{ $product->oxarticles__oxtitle->value }], Artnum. [{ $product->oxarticles__oxartnum->value }]<br>\r\n<br>\r\nOriginalpreis: [{ $product->fprice }] [{ $currency->name}]<br>\r\nGEBOTEN: [{ $bidprice }] [{ $currency->name}]<br>\r\n<br>\r\n<br>\r\nIhr Shop.<br>', 'price alert', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> price alert</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49c8ec04201.39247735', 'oxregisteremail', 'oxbaseshop', '1', '0', '1', '1', '', 'Vielen Dank für Ihre Registrierung', 'Hallo, [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }], vielen Dank für Ihre Registrierung bei [{ $shop->oxshops__oxname->value }] !<br>\r\n<br>\r\nSie können sich ab sofort auch mit Ihrer Kundennummer <strong>[{ $user->oxuser__oxcustnr->value }]</strong> einloggen.<br>\r\n<br>\r\nIhr [{ $shop->oxshops__oxname->value }] Team<br>', 'thanks for your registration', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> thanks for your registration</div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49ca4750015.09588134', 'oxregisterplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Vielen Dank für Ihre Registrierung Plain', '[{ $shop->oxshops__oxregistersubject->value }] Hallo, [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }], vielen Dank für Ihre Registrierung bei [{ $shop->oxshops__oxname->value }] ! Sie koennnen sich ab sofort auch mit Ihrer Kundennummer ([{ $user->oxuser__oxcustnr->value }]) einloggen. Ihr [{ $shop->oxshops__oxname->value }] Team', 'thanks for your registration plain', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> thanks for your registration plain', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49d6de4a4f4.88594616', 'oxordersendemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung wurde versandt', 'Guten Tag, [{ $order->oxorder__oxbillsal->value }] [{ $order->oxorder__oxbillfname->value }] [{ $order->oxorder__oxbilllname->value }],<br>\r\n<br>\r\nunser Vertriebszentrum hat soeben folgende Artikel versandt.<br>\r\n<br>', 'your order has been shipped', '<div>\r\n<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> your order has been shipped</div></div></div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e49d856b5b68.98220446', 'oxordersendplainemail', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung wurde versandt Plain', 'Guten Tag [{ $order->oxorder__oxbillsal->value }] [{ $order->oxorder__oxbillfname->value }] [{ $order->oxorder__oxbilllname->value }],\r\n\r\nunser Vertriebszentrum hat soeben folgende Artikel versandt.', 'your order has been shipped plain', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> your order has been shipped plain', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('84a42e66105998a86.14045828', 'oxuserorderemailend', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Abschluss', '<div align="left">Fügen Sie hier Ihre Widerrufsbelehrung ein.</div>', 'your order terms', '<div>\r\n<div><strong>Notice for Shop Administrator:</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Update this text easily and comfortable in the Admin with a WYSYWYG-Editor.<strong>&nbsp;</strong></div>\r\n<div>&nbsp;</div>\r\n<div>Admin Menu: Customer Info -> CMS Pages -> your order terms</div></div>', '1', '', '', '1', '', '', '', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('84a42e66123887821.29772527', 'oxuserorderemailendplain', 'oxbaseshop', '1', '0', '1', '1', '', 'Ihre Bestellung Abschluss Plain', 'Fügen Sie hier Ihre Widerrufsbelehrung ein.', 'your order terms plain', 'Notice for Shop Administrator:\r\n \r\nUpdate this text easily and comfortable in the Admin with a WYSYWYG-Editor. \r\n \r\nAdmin Menu: Customer Info -> CMS Pages -> your order terms plain', '1', '', '', '1', '', '', '', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('29142e76dd32dd477.41262508', 'oxforgotpwd', 'oxbaseshop', '1', '0', '1', '1', '', 'Passwort vergessen', 'Sollten Sie innerhalb der nächsten Minuten KEINE E-Mail mit Ihren Zugangsdaten erhalten, so überprüfen Sie bitte: Haben Sie sich in unserem Shop bereits registriert? Wenn nicht, so tun Sie dies bitte einmalig im Rahmen des Bestellprozesses. Sie können dann selbst ein Passwort festlegen. Sobald Sie registriert sind, können Sie sich in Zukunft mit Ihrer E-Mail-Adresse und Ihrem Passwort einloggen.\r\n<ul>\r\n<li class="font11">Wenn Sie sich sicher sind, dass Sie sich in unserem Shop bereits registriert haben, dann überprüfen Sie bitte, ob Sie sich bei der Eingabe Ihrer E-Mail-Adresse evtl. vertippt haben.</li></ul>\r\n<p>Sollten Sie trotz korrekter E-Mail-Adresse und bereits bestehender Registrierung weiterhin Probleme mit dem Login haben und auch keine "Passwort vergessen"-E-Mail erhalten, so wenden Sie sich bitte per E-Mail an: <a href="mailto:demo@oxid-esales.com?subject=Passwort"><strong>demo@oxid-esales.com</strong></a></p>', '', '', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('e7a4518ce1e2c36a9.60268505', 'oxfirststart', 'oxbaseshop', '1', '0', '1', '1', '', 'UNSER SCHNÄPPCHEN!', '<div> Gültig solange Vorrat reicht. </div>', 'Our Bargain!', '<div>As long as supply lasts&nbsp;</div>', '1', '', '', '1', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_PRODUCTINFO');
INSERT INTO `oxcontents` VALUES ('1ea45574543b21636.29288751', 'oxrightofwithdrawal', 'oxbaseshop', '1', '0', '1', '1', '', 'Widerrufsrecht', 'Fügen Sie hier Ihre Widerrufsbelehrung ein.', 'Right of withdrawal', '<div>English version of rights of withdrawal&nbsp;</div>', '0', '', '', '0', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('42e4667ffcf844be0.22563656', 'oxemailfooter', 'oxbaseshop', '1', '0', '1', '1', '', 'E-Mail Fußtext', '<p align="left">--</p align="left">\r\n<p>Bitte fügen Sie hier Ihre vollständige Anbieterkennzeichnung ein.</p>', '', '', '0', '', '', '0', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('3194668fde854d711.73798992', 'oxemailfooterplain', 'oxbaseshop', '1', '0', '1', '1', '', 'E-Mail Fußtext Plain', '-- Bitte fügen Sie hier Ihre vollständige Anbieterkennzeichnung ein.', 'Email footer', '', '0', '', '', '0', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ce79015b6f6f07612270975889', 'oxstartmetadescription', 'oxbaseshop', '1', '0', '1', '1', '', 'META Description Startseite', 'Witzige und originelle Geschenke. Online Versand für Trend-Produkte, Lifestyle-Artikel und Accessoires. Geschenkideen für alle Anlässe günstig online kaufen.', 'META Description Startseite', 'Funny and original presents. Online store for trendy products, lifestyle articles and accessories. Gift ideas for all occasions.', '1', '', '', '1', '', '', '', '');
INSERT INTO `oxcontents` VALUES ('ce77743c334edf92b0cab924a7', 'oxstartmetakeywords', 'oxbaseshop', '1', '0', '1', '1', '', 'META Keywords Startseite', 'geschenk, geschenke, geschenkideen, geschenkeshop, trend-produkte, lifestyle-artikel, lifestyle, accessoires, geburtstagsgeschenke, hochzeitsgeschenke', 'META Keywords Startseite', 'gifts, gift, gift ideas, presents, birthday gifts, gift shop, wedding gifts, lifestyle products, accessories', '1', '', '', '1', '', '', '', '');
INSERT INTO `oxcontents` VALUES ('8709e45f31a86909e9f999222e80b1d0', 'oxstdfooter', 'oxbaseshop', 1, 0, 1, 1, '', 'Standard Footer', '<div>OXID Geschenke Shop - Geschenkideen für alle Anlässe günstig online kaufen</div>\r\n<div>Online Versand für Trend-Produkte, Lifestyle-Artikel und Accessoires</div>Witzige, originelle Geschenke bestellen<br>', 'Standard Footer', '<div>OXID Gift Shop - Buy gift ideas for all ocasions online</div>\r\n<div>Online store for trndy products, lifestyle articles and accessories</div>\r\n<div>Order funny and original presents online</div>', 1, '', '', 1, '', '', '8a142c3e4143562a5.46426637', '');
INSERT INTO `oxcontents` VALUES ('2eb4676806a3d2e87.06076523', 'oxagb', 'oxbaseshop', '1', '0', '1', '1', '', 'AGB', '<div><strong>AGB</strong></div>\r\n<div><strong>&nbsp;</strong></div>\r\n<div>Fügen Sie hier Ihre allgemeinen Geschäftsbedingungen ein:</div>\r\n<div>&nbsp;</div>\r\n<div><span style="font-weight: bold">Strukturvorschlag:</span><br>\r\n<br>\r\n<ol>\r\n<li>Geltungsbereich </li>\r\n<li>Vertragspartner </li>\r\n<li>Angebot und Vertragsschluss </li>\r\n<li>Widerrufsrecht, Widerrufsbelehrung, Widerrufsfolgen </li>\r\n<li>Preise und Versandkosten </li>\r\n<li>Lieferung </li>\r\n<li>Zahlung </li>\r\n<li>Eigentumsvorbehalt </li>\r\n<li>Gewährleistung </li>\r\n<li>Weitere Informationen</li></ol></div>', 'Terms', 'English AGB', '0', '', '', '0', '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_USERINFO');
INSERT INTO `oxcontents` VALUES ('ad542e49541c1add', 'oxupdatepassinfoemail', 'oxbaseshop', 1, 0, 1, 1, '', 'Ihr Passwort im eShop', 'Hallo [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }],\r\n<br /><br />\r\nöffnen Sie den folgenden Link, um ein neues Passwort für [{ $shop->oxshops__oxname->value }] einzurichten:\r\n<br /><br />\r\n<a href="[{ $oViewConf->getBaseDir() }]index.php?cl=forgotpwd&uid=[{ $user->getUpdateId() }]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]">[{ $oViewConf->getBaseDir() }]index.php?cl=forgotpwd&uid=[{ $user->getUpdateId()}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]</a>\r\n<br /><br />\r\nDiesen Link können Sie innerhalb der nächsten [{ $user->getUpdateLinkTerm()/3600 }] Stunden aufrufen.\r\n<br /><br />\r\nIhr [{ $shop->oxshops__oxname->value }] Team\r\n<br />', 'password update info', 'Hello [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }],<br />\r\n<br />\r\nfollow this link to generate a new password for [{ $shop->oxshops__oxname->value }]:<br />\r\n<br /><a href="[{ $oViewConf->getBaseDir() }]index.php?cl=forgotpwd&uid=[{ $user->getUpdateId() }]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]">[{ $oViewConf->getBaseDir() }]index.php?cl=forgotpwd&uid=[{ $user->getUpdateId()}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]</a><br />\r\n<br />\r\nYou can use this link within the next [{ $user->getUpdateLinkTerm()/3600 }] hours.<br />\r\n<br />\r\nYour [{ $shop->oxshops__oxname->value }] team<br />', 1, '', '', 1, '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');
INSERT INTO `oxcontents` VALUES ('ad542e495c392c6e', 'oxupdatepassinfoplainemail', 'oxbaseshop', 1, 0, 1, 1, '', 'Ihr Passwort im eShop Plain', 'Hallo [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }],\r\n\r\nöffnen Sie den folgenden Link, um ein neues Passwort für [{ $shop->oxshops__oxname->value }] einzurichten:\r\n\r\n[{ $oViewConf->getBaseDir() }]index.php?cl=forgotpwd&uid=[{ $user->getUpdateId()}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]\r\n\r\nDiesen Link können Sie innerhalb der nächsten [{ $user->getUpdateLinkTerm()/3600 }] Stunden aufrufen.\r\n\r\nIhr [{ $shop->oxshops__oxname->value }] Team', 'password update info plain', 'Hello [{ $user->oxuser__oxsal->value }] [{ $user->oxuser__oxfname->value }] [{ $user->oxuser__oxlname->value }],\r\n\r\nfollow this link to generate a new password for [{ $shop->oxshops__oxname->value }]:\r\n\r\n[{ $oViewConf->getBaseDir() }]index.php?cl=forgotpwd&uid=[{ $user->getUpdateId()}]&lang=[{ $oViewConf->getActLanguageId() }]&shp=[{ $shop->oxshops__oxid->value }]\r\n\r\nYou can use this link within the next [{ $user->getUpdateLinkTerm()/3600 }] hours.\r\n\r\nYour [{ $shop->oxshops__oxname->value }] team', 1, '', '', 1, '', '', '8a142c3e4143562a5.46426637', 'CMSFOLDER_EMAILS');

#
# Table structure for table `oxcountry`
#

DROP TABLE IF EXISTS `oxcountry`;

CREATE TABLE `oxcountry` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '0',
  `OXTITLE` char(128) NOT NULL default '',
  `OXISOALPHA2` char(2) NOT NULL default '',
  `OXISOALPHA3` char(3) NOT NULL default '',
  `OXUNNUM3` char(3) NOT NULL default '',
  `OXORDER` int(11) NOT NULL default '9999',
  `OXSHORTDESC` char(128) NOT NULL default '',
  `OXLONGDESC` char(255) NOT NULL default '',
  `OXTITLE_1` char(128) NOT NULL default '',
  `OXTITLE_2` char(128) NOT NULL default '',
  `OXTITLE_3` char(128) NOT NULL default '',
  `OXSHORTDESC_1` char(128) NOT NULL default '',
  `OXSHORTDESC_2` char(128) NOT NULL default '',
  `OXSHORTDESC_3` char(128) NOT NULL default '',
  `OXLONGDESC_1` char(255) NOT NULL,
  `OXLONGDESC_2` char(255) NOT NULL,
  `OXLONGDESC_3` char(255) NOT NULL,
  `OXVATSTATUS` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`,`OXACTIVE`)
) TYPE=MyISAM;

#
# Data for table `oxcountry`
#

INSERT INTO `oxcountry` VALUES ('2db455824e4a19cc7.14731328', 'oxbaseshop', 0, 'Anderes Land', '', '', '', 10000, '', 'Select this if you can not find your country.', 'Other country', '', '', '', '', '', 'Select this if you can not find your country.', '', '', 0);
INSERT INTO `oxcountry` VALUES ('a7c40f631fc920687.20179984', 'oxbaseshop', 1, 'Deutschland', 'DE', 'DEU', '276', 9999, 'EU1', '', 'Germany', '', '', 'EU1', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f6320aeb2ec2.72885259', 'oxbaseshop', 1, 'Österreich', 'AT', 'AUT', '40', 9999, 'EU1', '', 'Austria', '', '', 'EU1', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f6321c6f6109.43859248', 'oxbaseshop', 1, 'Schweiz', 'CH', 'CHE', '756', 9999, 'EU1', '', 'Switzerland', '', '', 'EU1', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('a7c40f6322d842ae3.83331920', 'oxbaseshop', 0, 'Liechtenstein', 'LI', 'LIE', '438', 9999, 'EU1', '', 'Liechtenstein', '', '', 'EU1', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('a7c40f6323c4bfb36.59919433', 'oxbaseshop', 0, 'Italien', 'IT', 'ITA', '380', 9999, 'EU1', '', 'Italy', '', '', 'EU1', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f63264309e05.58576680', 'oxbaseshop', 0, 'Luxemburg', 'LU', 'LUX', '442', 9999, 'EU1', '', 'Luxembourg', '', '', 'EU1', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f63272a57296.32117580', 'oxbaseshop', 0, 'Frankreich', 'FR', 'FRA', '250', 9999, 'EU1', '', 'France', '', '', 'EU1', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f632848c5217.53322339', 'oxbaseshop', 0, 'Schweden', 'SE', 'SWE', '752', 9999, 'EU2', '', 'Sweden', '', '', 'EU2', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f63293c19d65.37472814', 'oxbaseshop', 0, 'Finnland', 'FI', 'FIN', '246', 9999, 'EU2', '', 'Finland', '', '', 'EU2', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f632a0804ab5.18804076', 'oxbaseshop', 0, 'Grossbritannien', 'GB', 'GBR', '826', 9999, 'EU2', '', 'United Kingdom', '', '', 'EU2', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f632be4237c2.48517912', 'oxbaseshop', 0, 'Irland', 'IE', 'IRL', '372', 9999, 'EU2', '', 'Ireland', '', '', 'EU2', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f632cdd63c52.64272623', 'oxbaseshop', 0, 'Niederlande', 'NL', 'NLD', '528', 9999, 'EU2', '', 'Netherlands', '', '', 'EU2', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f632e04633c9.47194042', 'oxbaseshop', 0, 'Belgien', 'BE', 'BEL', '56', 9999, 'Rest Europäische Union', '', 'Belgium', '', '', 'Rest of EU', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f632f65bd8e2.84963272', 'oxbaseshop', 0, 'Portugal', 'PT', 'PRT', '620', 9999, 'Rest Europäische Union', '', 'Portugal', '', '', 'Rest of EU', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f633038cd578.22975442', 'oxbaseshop', 0, 'Spanien', 'ES', 'ESP', '724', 9999, 'Rest Europäische Union', '', 'Spain', '', '', 'Rest of EU', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('a7c40f633114e8fc6.25257477', 'oxbaseshop', 0, 'Griechenland', 'GR', 'GRC', '300', 9999, 'Rest Europäische Union', '', 'Greece', '', '', 'Rest of EU', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11095306451.36998225', 'oxbaseshop', 0, 'Afghanistan', 'AF', 'AFG', '4', 9999, 'Rest Welt', '', 'Afghanistan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110953265a5.25286134', 'oxbaseshop', 0, 'Albanien', 'AL', 'ALB', '8', 9999, 'Rest Europa', '', 'Albania', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109533b943.50287900', 'oxbaseshop', 0, 'Algerien', 'DZ', 'DZA', '12', 9999, 'Rest Welt', '', 'Algeria', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109534f8c7.80349931', 'oxbaseshop', 0, 'Amerikanische Samoa-inseln', 'AS', 'ASM', '16', 9999, 'Rest Welt', '', 'American Samoa', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095363464.89657222', 'oxbaseshop', 0, 'Andorra', 'AD', 'AND', '20', 9999, 'Europa', '', 'Andorra', '', '', 'Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095377d33.28678901', 'oxbaseshop', 0, 'Angola', 'AO', 'AGO', '24', 9999, 'Rest Welt', '', 'Angola', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095392e41.74397491', 'oxbaseshop', 0, 'Anguilla', 'AI', 'AIA', '660', 9999, 'Rest Welt', '', 'Anguilla', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110953a8d10.29474848', 'oxbaseshop', 0, 'Antarktis', 'AQ', 'ATA', '10', 9999, 'Rest Welt', '', 'Antarctica', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110953be8f2.56248134', 'oxbaseshop', 0, 'Antigua und Barbuda', 'AG', 'ATG', '28', 9999, 'Rest Welt', '', 'Antigua and Barbuda', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110953d2fb0.54260547', 'oxbaseshop', 0, 'Argentinien', 'AR', 'ARG', '32', 9999, 'Rest Welt', '', 'Argentina', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110953e7993.88180360', 'oxbaseshop', 0, 'Armenien', 'AM', 'ARM', '51', 9999, 'Rest Europa', '', 'Armenia', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110953facc6.31621036', 'oxbaseshop', 0, 'Aruba', 'AW', 'ABW', '533', 9999, 'Rest Welt', '', 'Aruba', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095410f38.37165361', 'oxbaseshop', 0, 'Australien', 'AU', 'AUS', '36', 9999, 'Rest Welt', '', 'Australia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109543cf47.17877015', 'oxbaseshop', 0, 'Aserbaidschan', 'AZ', 'AZE', '31', 9999, 'Rest Welt', '', 'Azerbaijan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095451379.72078871', 'oxbaseshop', 0, 'Bahamas', 'BS', 'BHS', '44', 9999, 'Rest Welt', '', 'Bahamas, The', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110954662e3.27051654', 'oxbaseshop', 0, 'Bahrain', 'BH', 'BHR', '48', 9999, 'Welt', '', 'Bahrain', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109547ae49.60154431', 'oxbaseshop', 0, 'Bangladesch', 'BD', 'BGD', '50', 9999, 'Rest Welt', '', 'Bangladesh', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095497083.21181725', 'oxbaseshop', 0, 'Barbados', 'BB', 'BRB', '52', 9999, 'Rest Welt', '', 'Barbados', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110954ac5b9.63105203', 'oxbaseshop', 0, 'Weißrussland', 'BY', 'BLR', '112', 9999, 'Rest Europa', '', 'Belarus', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110954d3621.45362515', 'oxbaseshop', 0, 'Belize', 'BZ', 'BLZ', '84', 9999, 'Rest Welt', '', 'Belize', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110954ea065.41455848', 'oxbaseshop', 0, 'Benin', 'BJ', 'BEN', '204', 9999, 'Rest Welt', '', 'Benin', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110954fee13.50011948', 'oxbaseshop', 0, 'Bermuda', 'BM', 'BMU', '60', 9999, 'Rest Welt', '', 'Bermuda', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095513ca0.75349731', 'oxbaseshop', 0, 'Bhutan', 'BT', 'BTN', '64', 9999, 'Rest Welt', '', 'Bhutan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109552aee2.91004965', 'oxbaseshop', 0, 'Bolivien', 'BO', 'BOL', '68', 9999, 'Rest Welt', '', 'Bolivia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109553f902.06960438', 'oxbaseshop', 0, 'Bosnien und Herzegowina', 'BA', 'BIH', '70', 9999, 'Rest Europa', '', 'Bosnia and Herzegovina', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095554834.54199483', 'oxbaseshop', 0, 'Botsuana', 'BW', 'BWA', '72', 9999, 'Rest Welt', '', 'Botswana', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109556dd57.84292282', 'oxbaseshop', 0, 'Bouvetinsel', 'BV', 'BVT', '74', 9999, 'Rest Welt', '', 'Bouvet Island', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095592407.89986143', 'oxbaseshop', 0, 'Brasilien', 'BR', 'BRA', '76', 9999, 'Rest Welt', '', 'Brazil', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110955a7644.68859180', 'oxbaseshop', 0, 'Britisches Territorium im Indischen Ozean', 'IO', 'IOT', '86', 9999, 'Rest Welt', '', 'British Indian Ocean Territory', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110955bde61.63256042', 'oxbaseshop', 0, 'Brunei', 'BN', 'BRN', '96', 9999, 'Rest Welt', '', 'Brunei', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110955d3260.55487539', 'oxbaseshop', 0, 'Bulgarien', 'BG', 'BGR', '100', 9999, 'Rest Europa', '', 'Bulgaria', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110955ea7c8.36762654', 'oxbaseshop', 0, 'Burkina Faso', 'BF', 'BFA', '854', 9999, 'Rest Welt', '', 'Burkina Faso', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110956004d5.11534182', 'oxbaseshop', 0, 'Burundi', 'BI', 'BDI', '108', 9999, 'Rest Welt', '', 'Burundi', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110956175f9.81682035', 'oxbaseshop', 0, 'Kambodscha', 'KH', 'KHM', '116', 9999, 'Rest Welt', '', 'Cambodia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095632828.20263574', 'oxbaseshop', 0, 'Cameroon', 'CM', 'CMR', '120', 9999, 'Rest Welt', '', 'Cameroon', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095649d18.02676059', 'oxbaseshop', 0, 'Kanada', 'CA', 'CAN', '124', 9999, 'Welt', '', 'Canada', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109565e671.48876354', 'oxbaseshop', 0, 'Cape Verde', 'CV', 'CPV', '132', 9999, 'Rest Welt', '', 'Cape Verde', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095673248.50405852', 'oxbaseshop', 0, 'Cayman-inseln', 'KY', 'CYM', '136', 9999, 'Rest Welt', '', 'Cayman Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109568a509.03566030', 'oxbaseshop', 0, 'Republik Des Zentralen Afrikaners', 'CF', 'CAF', '140', 9999, 'Rest Welt', '', 'Central African Republic', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109569d4c2.42800039', 'oxbaseshop', 0, 'Tschad', 'TD', 'TCD', '148', 9999, 'Rest Welt', '', 'Chad', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110956b3ea7.11168270', 'oxbaseshop', 0, 'Chile', 'CL', 'CHL', '152', 9999, 'Rest Welt', '', 'Chile', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110956c8860.37981845', 'oxbaseshop', 0, 'China', 'CN', 'CHN', '156', 9999, 'Rest Welt', '', 'China', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110956df6b2.52283428', 'oxbaseshop', 0, 'Weihnachtscinsel', 'CX', 'CXR', '162', 9999, 'Rest Welt', '', 'Christmas Island', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110956f54b4.26327849', 'oxbaseshop', 0, 'Inseln Cocos (keeling)', 'CC', 'CCK', '166', 9999, 'Rest Welt', '', 'Cocos (keeling) Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109570a1e3.69772638', 'oxbaseshop', 0, 'Kolumbien', 'CO', 'COL', '170', 9999, 'Rest Welt', '', 'Colombia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109571f018.46251535', 'oxbaseshop', 0, 'Comoren', 'KM', 'COM', '174', 9999, 'Rest Welt', '', 'Comoros', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095732184.72771986', 'oxbaseshop', 0, 'Der Kongo', 'CG', 'COG', '178', 9999, 'Rest Welt', '', 'Congo', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095746a92.94878441', 'oxbaseshop', 0, 'Kochcinseln', 'CK', 'COK', '184', 9999, 'Rest Welt', '', 'Cook Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109575d708.20084150', 'oxbaseshop', 0, 'Costa Rica', 'CR', 'CRI', '188', 9999, 'Rest Welt', '', 'Costa Rica', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095771f76.87904122', 'oxbaseshop', 0, 'Cote D''ivoire', 'CI', 'CIV', '384', 9999, 'Rest Welt', '', 'Cote D''ivoire', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095789a04.65154246', 'oxbaseshop', 0, 'Kroatien', 'HR', 'HRV', '191', 9999, 'Rest Europa', '', 'Croatia', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109579ef49.91803242', 'oxbaseshop', 0, 'Kuba', 'CU', 'CUB', '192', 9999, 'Rest Welt', '', 'Cuba', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110957b6896.52725150', 'oxbaseshop', 0, 'Zypern', 'CY', 'CYP', '196', 9999, 'Rest Europa', '', 'Cyprus', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110957cb457.97820918', 'oxbaseshop', 0, 'Tschechien', 'CZ', 'CZE', '203', 9999, 'Europa', '', 'Czech Republic', '', '', 'Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110957e6ef8.56458418', 'oxbaseshop', 0, 'Dänemark', 'DK', 'DNK', '208', 9999, 'Europa', '', 'Denmark', '', '', 'Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110957fd356.02918645', 'oxbaseshop', 0, 'Djibouti', 'DJ', 'DJI', '262', 9999, 'Rest Welt', '', 'Djibouti', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095811ea5.84717844', 'oxbaseshop', 0, 'Dominica', 'DM', 'DMA', '212', 9999, 'Rest Welt', '', 'Dominica', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095825bf2.61063355', 'oxbaseshop', 0, 'Dominikanische Republik', 'DO', 'DOM', '214', 9999, 'Rest Welt', '', 'Dominican Republic', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095839323.86755169', 'oxbaseshop', 0, 'Ostctimor', 'TL', 'TLS', '626', 9999, 'Rest Welt', '', 'East Timor', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109584d512.06663789', 'oxbaseshop', 0, 'Ecuador', 'EC', 'ECU', '218', 9999, 'Rest Welt', '', 'Ecuador', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095861fb7.55278256', 'oxbaseshop', 0, 'Ägypten', 'EG', 'EGY', '818', 9999, 'Welt', '', 'Egypt', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110958736a9.06061237', 'oxbaseshop', 0, 'Elcsalvador', 'SV', 'SLV', '222', 9999, 'Rest Welt', '', 'El Salvador', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109588d077.74284490', 'oxbaseshop', 0, 'Äquatorialguinea', 'GQ', 'GNQ', '226', 9999, 'Rest Welt', '', 'Equatorial Guinea', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110958a2216.38324531', 'oxbaseshop', 0, 'Eritrea', 'ER', 'ERI', '232', 9999, 'Rest Welt', '', 'Eritrea', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110958b69e4.93886171', 'oxbaseshop', 0, 'Estland', 'EE', 'EST', '233', 9999, 'Rest Europa', '', 'Estonia', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110958caf67.08982313', 'oxbaseshop', 0, 'Äthiopien', 'ET', 'ETH', '210', 9999, 'Rest Welt', '', 'Ethiopia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110958e2cc3.90770249', 'oxbaseshop', 0, 'Falklandinseln (malvinas)', 'FK', 'FLK', '238', 9999, 'Rest Welt', '', 'Falkland Islands (malvinas)', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110958f7ba4.96908065', 'oxbaseshop', 0, 'Faroe-inseln', 'FO', 'FRO', '234', 9999, 'Rest Welt', '', 'Faroe Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109590d226.07938729', 'oxbaseshop', 0, 'Fidschi', 'FJ', 'FJI', '242', 9999, 'Rest Welt', '', 'Fiji', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109594fcb1.79441780', 'oxbaseshop', 0, 'Französisch-guayana', 'GF', 'GUF', '254', 9999, 'Rest Welt', '', 'French Guiana', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110959636f5.71476354', 'oxbaseshop', 0, 'Französischer Polynesia', 'PF', 'PYF', '258', 9999, 'Rest Welt', '', 'French Polynesia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110959784a3.34264829', 'oxbaseshop', 0, 'Französische Südliche Gegenden', 'TF', 'ATF', '260', 9999, 'Rest Welt', '', 'French Southern Territories', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095994cb6.59353392', 'oxbaseshop', 0, 'Gabun', 'GA', 'GAB', '266', 9999, 'Rest Welt', '', 'Gabon', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110959ace77.17379319', 'oxbaseshop', 0, 'Gambia', 'GM', 'GMB', '270', 9999, 'Rest Welt', '', 'Gambia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110959c2341.01830199', 'oxbaseshop', 0, 'Georgien', 'GE', 'GEO', '268', 9999, 'Rest Europa', '', 'Georgia', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110959e96b3.05752152', 'oxbaseshop', 0, 'Ghana', 'GH', 'GHA', '288', 9999, 'Rest Welt', '', 'Ghana', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110959fdde0.68919405', 'oxbaseshop', 0, 'Gibraltar', 'GI', 'GIB', '292', 9999, 'Rest Welt', '', 'Gibraltar', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095a29f47.04102343', 'oxbaseshop', 0, 'Grönland', 'GL', 'GRL', '304', 9999, 'Europa', '', 'Greenland', '', '', 'Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095a3f195.88886789', 'oxbaseshop', 0, 'Grenada', 'GD', 'GRD', '308', 9999, 'Rest Welt', '', 'Grenada', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095a52578.45413493', 'oxbaseshop', 0, 'Guadeloupe', 'GP', 'GLP', '312', 9999, 'Rest Welt', '', 'Guadeloupe', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095a717b3.68126681', 'oxbaseshop', 0, 'Guam', 'GU', 'GUM', '316', 9999, 'Rest Welt', '', 'Guam', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095a870a5.42235635', 'oxbaseshop', 0, 'Guatemala', 'GT', 'GTM', '320', 9999, 'Rest Welt', '', 'Guatemala', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095a9bf82.19989557', 'oxbaseshop', 0, 'Guine', 'GN', 'GIN', '324', 9999, 'Rest Welt', '', 'Guinea', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095ab2b56.83049280', 'oxbaseshop', 0, 'Guinea-bissau', 'GW', 'GNB', '624', 9999, 'Rest Welt', '', 'Guinea-bissau', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095ac9d30.56640429', 'oxbaseshop', 0, 'Guyana', 'GY', 'GUY', '328', 9999, 'Rest Welt', '', 'Guyana', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095aebb06.34405179', 'oxbaseshop', 0, 'Haiti', 'HT', 'HTI', '332', 9999, 'Rest Welt', '', 'Haiti', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095aff2c3.98054755', 'oxbaseshop', 0, 'Gehörte Insel U. Mcdonald-inseln', 'HM', 'HMD', '334', 9999, 'Rest Welt', '', 'Heard Island & Mcdonald Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095b13f57.56022305', 'oxbaseshop', 0, 'Honduras', 'HN', 'HND', '340', 9999, 'Rest Welt', '', 'Honduras', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095b29021.49657118', 'oxbaseshop', 0, 'Hong Kong', 'HK', 'HKG', '344', 9999, 'Rest Welt', '', 'Hong Kong', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095b3e016.98213173', 'oxbaseshop', 0, 'Ungarn', 'HU', 'HUN', '348', 9999, 'Rest Europa', '', 'Hungary', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11095b55846.26192602', 'oxbaseshop', 0, 'Island', 'IS', 'ISL', '352', 9999, 'Rest Europa', '', 'Iceland', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095b6bb86.01364904', 'oxbaseshop', 0, 'Indien', 'IN', 'IND', '356', 9999, 'Rest Welt', '', 'India', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095b80526.59927631', 'oxbaseshop', 0, 'Indonesien', 'ID', 'IDN', '360', 9999, 'Rest Welt', '', 'Indonesia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095b94476.05195832', 'oxbaseshop', 0, 'Iran', 'IR', 'IRN', '364', 9999, 'Welt', '', 'Iran', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095bad5b2.42645724', 'oxbaseshop', 0, 'Irak', 'IQ', 'IRQ', '368', 9999, 'Welt', '', 'Iraq', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095bd65e1.59459683', 'oxbaseshop', 0, 'Israel', 'IL', 'ISR', '376', 9999, 'Rest Europa', '', 'Israel', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095bfe834.63390185', 'oxbaseshop', 0, 'Jamaika', 'JM', 'JAM', '388', 9999, 'Rest Welt', '', 'Jamaica', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c11d43.73419747', 'oxbaseshop', 0, 'Japan', 'JP', 'JPN', '392', 9999, 'Rest Welt', '', 'Japan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c2b304.75906962', 'oxbaseshop', 0, 'Jordanien', 'JO', 'JOR', '400', 9999, 'Rest Welt', '', 'Jordan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c3e2d1.36714463', 'oxbaseshop', 0, 'Kazakhstan', 'KZ', 'KAZ', '398', 9999, 'Rest Europa', '', 'Kazakhstan', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c5b8e8.66333679', 'oxbaseshop', 0, 'Kenia', 'KE', 'KEN', '404', 9999, 'Rest Welt', '', 'Kenya', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c6e184.21450618', 'oxbaseshop', 0, 'Kiribati', 'KI', 'KIR', '296', 9999, 'Rest Welt', '', 'Kiribati', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c87284.37982544', 'oxbaseshop', 0, 'Nordkorea', 'KP', 'PRK', '408', 9999, 'Rest Welt', '', 'North Korea', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095c9de64.01275726', 'oxbaseshop', 0, 'Südkorea', 'KR', 'KOR', '410', 9999, 'Rest Welt', '', 'South Korea', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095cb1546.46652174', 'oxbaseshop', 0, 'Kuwait', 'KW', 'KWT', '414', 9999, 'Welt', '', 'Kuwait', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095cc7ef5.28043767', 'oxbaseshop', 0, 'Kyrgyzstan', 'KG', 'KGZ', '417', 9999, 'Rest Welt', '', 'Kyrgyzstan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095cdccd5.96388808', 'oxbaseshop', 0, 'Demokratische Republik Der Lao-leute', 'LA', 'LAO', '418', 9999, 'Rest Welt', '', 'Lao People''s Democratic Republic', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095cf2ea6.73925511', 'oxbaseshop', 0, 'Latvia', 'LV', 'LVA', '428', 9999, 'Rest Europa', '', 'Latvia', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11095d07d87.58986129', 'oxbaseshop', 0, 'Der Libanon', 'LB', 'LBN', '422', 9999, 'Welt', '', 'Lebanon', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095d1c9b2.21548132', 'oxbaseshop', 0, 'Lesotho', 'LS', 'LSO', '426', 9999, 'Rest Welt', '', 'Lesotho', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095d2fd28.91858908', 'oxbaseshop', 0, 'Liberia', 'LR', 'LBR', '430', 9999, 'Welt', '', 'Liberia', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095d46188.64679605', 'oxbaseshop', 0, 'Libyscher Araber Jamahiriya', 'LY', 'LBY', '434', 9999, 'Rest Welt', '', 'Libyan Arab Jamahiriya', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095d6ffa8.86593236', 'oxbaseshop', 0, 'Litauen', 'LT', 'LTU', '440', 9999, 'Rest Europa', '', 'Lithuania', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11095d9c1b2.13577033', 'oxbaseshop', 0, 'Macau', 'MO', 'MAC', '446', 9999, 'Rest Welt', '', 'Macau', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095db2291.58912887', 'oxbaseshop', 0, 'Mazedonien', 'MK', 'MKD', '807', 9999, 'Rest Europa', '', 'Macedonia', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095dccf17.06266806', 'oxbaseshop', 0, 'Madagaskar', 'MG', 'MDG', '450', 9999, 'Rest Welt', '', 'Madagascar', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095de2119.60795833', 'oxbaseshop', 0, 'Malawi', 'MW', 'MWI', '454', 9999, 'Rest Welt', '', 'Malawi', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095df78a8.44559506', 'oxbaseshop', 0, 'Malaysia', 'MY', 'MYS', '458', 9999, 'Rest Welt', '', 'Malaysia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095e0c6c9.43746477', 'oxbaseshop', 0, 'Maldives', 'MV', 'MDV', '462', 9999, 'Rest Welt', '', 'Maldives', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095e24006.17141715', 'oxbaseshop', 0, 'Mali', 'ML', 'MLI', '466', 9999, 'Rest Welt', '', 'Mali', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095e36eb3.69050509', 'oxbaseshop', 0, 'Malta', 'MT', 'MLT', '470', 9999, 'Rest Welt', '', 'Malta', '', '', 'Rest World', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11095e4e338.26817244', 'oxbaseshop', 0, 'Marshall Inseln', 'MH', 'MHL', '584', 9999, 'Rest Welt', '', 'Marshall Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095e631e1.29476484', 'oxbaseshop', 0, 'Martinique', 'MQ', 'MTQ', '474', 9999, 'Rest Welt', '', 'Martinique', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095e7bff9.09518271', 'oxbaseshop', 0, 'Mauretanien', 'MR', 'MRT', '478', 9999, 'Rest Welt', '', 'Mauritania', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095e90a81.01156393', 'oxbaseshop', 0, 'Mauritius', 'MU', 'MUS', '480', 9999, 'Rest Welt', '', 'Mauritius', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095ea6249.81474246', 'oxbaseshop', 0, 'Mayotte', 'YT', 'MYT', '175', 9999, 'Rest Welt', '', 'Mayotte', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095ebf3a6.86388577', 'oxbaseshop', 0, 'Mexiko', 'MX', 'MEX', '484', 9999, 'Rest Welt', '', 'Mexico', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095ed4902.49276197', 'oxbaseshop', 0, 'Mikronesien', 'FM', 'FSM', '583', 9999, 'Rest Welt', '', 'Micronesia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095ee9923.85175653', 'oxbaseshop', 0, 'Moldova', 'MD', 'MDA', '498', 9999, 'Rest Europa', '', 'Moldova', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095f00d65.30318330', 'oxbaseshop', 0, 'Monaco', 'MC', 'MCO', '492', 9999, 'Europa', '', 'Monaco', '', '', 'Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095f160c9.41059441', 'oxbaseshop', 0, 'Mongolei', 'MN', 'MNG', '496', 9999, 'Rest Welt', '', 'Mongolia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11095f314f5.05830324', 'oxbaseshop', 0, 'Montserrat', 'MS', 'MSR', '500', 9999, 'Rest Welt', '', 'Montserrat', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096006828.49285591', 'oxbaseshop', 0, 'Marokko', 'MA', 'MAR', '504', 9999, 'Welt', '', 'Morocco', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109601b419.55269691', 'oxbaseshop', 0, 'Mosambik', 'MZ', 'MOZ', '508', 9999, 'Rest Welt', '', 'Mozambique', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096030af5.65449043', 'oxbaseshop', 0, 'Myanmar', 'MM', 'MMR', '104', 9999, 'Rest Welt', '', 'Myanmar', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096046575.31382060', 'oxbaseshop', 0, 'Namibia', 'NA', 'NAM', '516', 9999, 'Rest Welt', '', 'Namibia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109605b1f4.20574895', 'oxbaseshop', 0, 'Nauru', 'NR', 'NRU', '520', 9999, 'Rest Welt', '', 'Nauru', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109607a9e7.03486450', 'oxbaseshop', 0, 'Nepal', 'NP', 'NPL', '524', 9999, 'Rest Welt', '', 'Nepal', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110960aeb64.09757010', 'oxbaseshop', 0, 'Niederländische Antillen', 'AN', 'ANT', '530', 9999, 'Rest Welt', '', 'Netherlands Antilles', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110960c3e97.21901471', 'oxbaseshop', 0, 'Neukaledonien', 'NC', 'NCL', '540', 9999, 'Rest Welt', '', 'New Caledonia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110960d8e58.96466103', 'oxbaseshop', 0, 'Neuseeland', 'NZ', 'NZL', '554', 9999, 'Rest Welt', '', 'New Zealand', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110960ec345.71805056', 'oxbaseshop', 0, 'Nicaragua', 'NI', 'NIC', '558', 9999, 'Rest Welt', '', 'Nicaragua', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096101a79.70513227', 'oxbaseshop', 0, 'Niger', 'NE', 'NER', '562', 9999, 'Rest Welt', '', 'Niger', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096116744.92008092', 'oxbaseshop', 0, 'Nigeria', 'NG', 'NGA', '566', 9999, 'Rest Welt', '', 'Nigeria', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109612dc68.63806992', 'oxbaseshop', 0, 'Niue', 'NU', 'NIU', '570', 9999, 'Rest Welt', '', 'Niue', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110961442c2.82573898', 'oxbaseshop', 0, 'Norfolkcinsel', 'NF', 'NFK', '574', 9999, 'Rest Welt', '', 'Norfolk Island', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096162678.71164081', 'oxbaseshop', 0, 'Nordcmarianacinseln', 'MP', 'MNP', '580', 9999, 'Rest Welt', '', 'Northern Mariana Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096176795.61257067', 'oxbaseshop', 0, 'Norwegen', 'NO', 'NOR', '578', 9999, 'Rest Europa', '', 'Norway', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109618d825.87661926', 'oxbaseshop', 0, 'Oman', 'OM', 'OMN', '512', 9999, 'Rest Welt', '', 'Oman', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110961a2401.59039740', 'oxbaseshop', 0, 'Pakistan', 'PK', 'PAK', '586', 9999, 'Rest Welt', '', 'Pakistan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110961b7729.14290490', 'oxbaseshop', 0, 'Palau', 'PW', 'PLW', '585', 9999, 'Rest Welt', '', 'Palau', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110961cc384.18166560', 'oxbaseshop', 0, 'Panama', 'PA', 'PAN', '591', 9999, 'Rest Welt', '', 'Panama', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110961e3538.78435307', 'oxbaseshop', 0, 'Papua Neu-guinea', 'PG', 'PNG', '598', 9999, 'Rest Welt', '', 'Papua New Guinea', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110961f9d61.52794273', 'oxbaseshop', 0, 'Paraguay', 'PY', 'PRY', '600', 9999, 'Rest Welt', '', 'Paraguay', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109620b245.16261506', 'oxbaseshop', 0, 'Peru', 'PE', 'PER', '604', 9999, 'Rest Welt', '', 'Peru', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109621faf8.40135556', 'oxbaseshop', 0, 'Philippinen', 'PH', 'PHL', '608', 9999, 'Rest Welt', '', 'Philippines', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096234d62.44125992', 'oxbaseshop', 0, 'Pitcairn', 'PN', 'PCN', '612', 9999, 'Rest Welt', '', 'Pitcairn', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109624d3f8.50953605', 'oxbaseshop', 0, 'Polen', 'PL', 'POL', '616', 9999, 'Europa', '', 'Poland', '', '', 'Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11096279a22.50582479', 'oxbaseshop', 0, 'Puerto Rico', 'PR', 'PRI', '630', 9999, 'Rest Welt', '', 'Puerto Rico', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109628f903.51478291', 'oxbaseshop', 0, 'Qatar', 'QA', 'QAT', '634', 9999, 'Rest Welt', '', 'Qatar', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110962a3ec5.65857240', 'oxbaseshop', 0, 'Wiedervereinigung', 'RE', 'REU', '638', 9999, 'Rest Welt', '', 'Reunion', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110962c3007.60363573', 'oxbaseshop', 0, 'Rumänien', 'RO', 'ROU', '642', 9999, 'Rest Europa', '', 'Romania', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110962e40e6.75062153', 'oxbaseshop', 0, 'Russische Vereinigung', 'RU', 'RUS', '643', 9999, 'Rest Europa', '', 'Russian Federation', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110962f8615.93666560', 'oxbaseshop', 0, 'Ruanda', 'RW', 'RWA', '646', 9999, 'Rest Welt', '', 'Rwanda', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110963177a7.49289900', 'oxbaseshop', 0, 'Heiliger Kitts Und Nevis', 'KN', 'KNA', '659', 9999, 'Rest Welt', '', 'Saint Kitts And Nevis', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109632fab4.68646740', 'oxbaseshop', 0, 'St.Lucia', 'LC', 'LCA', '662', 9999, 'Rest Welt', '', 'Saint Lucia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110963443c3.29598809', 'oxbaseshop', 0, 'Heiliger Vincent Und Das Grenadines', 'VC', 'VCT', '670', 9999, 'Rest Welt', '', 'Saint Vincent And The Grenadines', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096359986.06476221', 'oxbaseshop', 0, 'Samoa-inseln', 'WS', 'WSM', '882', 9999, 'Rest Welt', '', 'Samoa', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096375757.44126946', 'oxbaseshop', 0, 'San Marino', 'SM', 'SMR', '674', 9999, 'Europa', '', 'San Marino', '', '', 'Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109639b8c4.57484984', 'oxbaseshop', 0, 'Saoctome Und Principe', 'ST', 'STP', '678', 9999, 'Rest Welt', '', 'Sao Tome And Principe', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110963b9b20.41500709', 'oxbaseshop', 0, 'Saudi Arabien', 'SA', 'SAU', '682', 9999, 'Welt', '', 'Saudi Arabia', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110963d9962.36307144', 'oxbaseshop', 0, 'Senegal', 'SN', 'SEN', '686', 9999, 'Rest Welt', '', 'Senegal', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110963f98d8.68428379', 'oxbaseshop', 0, 'Serbien', 'SR', 'RSB', '688', 9999, 'Rest Europa', '', 'Serbia', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096418496.77253079', 'oxbaseshop', 0, 'Seychellen', 'SC', 'SYC', '690', 9999, 'Rest Welt', '', 'Seychelles', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096436968.69551351', 'oxbaseshop', 0, 'Sierra Leone', 'SL', 'SLE', '694', 9999, 'Rest Welt', '', 'Sierra Leone', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096456a48.79608805', 'oxbaseshop', 0, 'Singapur', 'SG', 'SGP', '702', 9999, 'Rest Welt', '', 'Singapore', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109647a265.29938154', 'oxbaseshop', 0, 'Slowakei', 'SK', 'SVK', '703', 9999, 'Europa', '', 'Slovakia', '', '', 'Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f11096497149.85116254', 'oxbaseshop', 0, 'Slowenien', 'SI', 'SVN', '705', 9999, 'Rest Europa', '', 'Slovenia', '', '', 'Rest Europe', '', '', '', '', '', 1);
INSERT INTO `oxcountry` VALUES ('8f241f110964b7bf9.49501835', 'oxbaseshop', 0, 'Solomon-inseln', 'SB', 'SLB', '90', 9999, 'Rest Welt', '', 'Solomon Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110964d5f29.11398308', 'oxbaseshop', 0, 'Somalia', 'SO', 'SOM', '706', 9999, 'Rest Welt', '', 'Somalia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110964f2623.74976876', 'oxbaseshop', 0, 'Südafrika', 'ZA', 'ZAF', '710', 9999, 'Rest Welt', '', 'South Africa', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096531330.03198083', 'oxbaseshop', 0, 'Sri Lanka', 'LK', 'LKA', '144', 9999, 'Rest Welt', '', 'Sri Lanka', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109654dca4.99466434', 'oxbaseshop', 0, 'Heiliger Helena', 'SH', 'SHN', '654', 9999, 'Rest Welt', '', 'Saint Helena', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109656cde9.10816078', 'oxbaseshop', 0, 'Heiliger Pierre Und Miquelon', 'PM', 'SPM', '666', 9999, 'Rest Welt', '', 'Saint Pierre And Miquelon', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109658cbe5.08293991', 'oxbaseshop', 0, 'Sudan', 'SD', 'SDN', '736', 9999, 'Rest Welt', '', 'Sudan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110965c7347.75108681', 'oxbaseshop', 0, 'Suriname', 'SR', 'SUR', '740', 9999, 'Rest Welt', '', 'Suriname', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110965eb7b7.26149742', 'oxbaseshop', 0, 'Svalbard Und Inseln Jan.s Mayen', 'SJ', 'SJM', '744', 9999, 'Rest Welt', '', 'Svalbard And Jan Mayen Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109660c113.62780718', 'oxbaseshop', 0, 'Swasiland', 'SZ', 'SWZ', '748', 9999, 'Rest Welt', '', 'Swaziland', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109666b7f3.81435898', 'oxbaseshop', 0, 'Syrische Arabische Republik', 'SY', 'SYR', '760', 9999, 'Rest Welt', '', 'Syrian Arab Republic', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096687ec7.58824735', 'oxbaseshop', 0, 'Taiwan', 'TW', 'TWN', '158', 9999, 'Rest Welt', '', 'Taiwan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110966a54d1.43798997', 'oxbaseshop', 0, 'Tajikistan', 'TJ', 'TJK', '762', 9999, 'Rest Welt', '', 'Tajikistan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110966c3a75.68297960', 'oxbaseshop', 0, 'Tanzania', 'TZ', 'TZA', '834', 9999, 'Rest Welt', '', 'Tanzania', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110966e9c47.64685159', 'oxbaseshop', 0, 'Tatarstan', '', '', '', 9999, 'Rest Welt', '', 'Tatarstan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096707e08.60512709', 'oxbaseshop', 0, 'Thailand', 'TH', 'THA', '764', 9999, 'Rest Welt', '', 'Thailand', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110967241e1.34925220', 'oxbaseshop', 0, 'Togo', 'TG', 'TGO', '768', 9999, 'Rest Welt', '', 'Togo', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096742565.72138875', 'oxbaseshop', 0, 'Tokelau', 'TK', 'TKL', '772', 9999, 'Rest Welt', '', 'Tokelau', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096762b31.03069244', 'oxbaseshop', 0, 'Tonga', 'TO', 'TON', '776', 9999, 'Rest Welt', '', 'Tonga', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109677ed23.84886671', 'oxbaseshop', 0, 'Trinidad Und Tobago', 'TT', 'TTO', '780', 9999, 'Rest Welt', '', 'Trinidad And Tobago', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109679d988.46004322', 'oxbaseshop', 0, 'Tunesien', 'TN', 'TUN', '788', 9999, 'Welt', '', 'Tunisia', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110967bba40.88233204', 'oxbaseshop', 0, 'Türkei', 'TR', 'TUR', '792', 9999, 'Rest Europa', '', 'Turkey', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110967d8f65.52699796', 'oxbaseshop', 0, 'Turkmenistan', 'TM', 'TKM', '795', 9999, 'Rest Welt', '', 'Turkmenistan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110967f73f8.13141492', 'oxbaseshop', 0, 'Türken Und Caicoscinseln', 'TC', 'TCA', '796', 9999, 'Rest Welt', '', 'Turks And Caicos Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109680ec30.97426963', 'oxbaseshop', 0, 'Tuvalu', 'TV', 'TUV', '798', 9999, 'Rest Welt', '', 'Tuvalu', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096823019.47846368', 'oxbaseshop', 0, 'Uganda', 'UG', 'UGA', '800', 9999, 'Rest Welt', '', 'Uganda', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110968391d2.37199812', 'oxbaseshop', 0, 'Ukraine', 'UA', 'UKR', '804', 9999, 'Rest Europa', '', 'Ukraine', '', '', 'Rest Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109684bf15.63071279', 'oxbaseshop', 0, 'Vereinigte Arabische Emirates', 'AE', 'ARE', '784', 9999, 'Rest Welt', '', 'United Arab Emirates', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096877ac0.98748826', 'oxbaseshop', 0, 'Vereinigte Staaten', 'US', 'USA', '840', 9999, 'Welt', '', 'United States', '', '', 'World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096894977.41239553', 'oxbaseshop', 0, 'Kleine Nebensächliche Inseln Vereinigter Staaten', 'UM', 'UMI', '581', 9999, 'Rest Welt', '', 'United States Minor Outlying Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110968a7cc9.56710143', 'oxbaseshop', 0, 'Uruguay', 'UY', 'URY', '858', 9999, 'Rest Welt', '', 'Uruguay', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110968bec45.44161857', 'oxbaseshop', 0, 'Uzbekistan', 'UZ', 'UZB', '860', 9999, 'Rest Welt', '', 'Uzbekistan', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110968d3f03.13630334', 'oxbaseshop', 0, 'Vanuatu', 'VU', 'VUT', '548', 9999, 'Rest Welt', '', 'Vanuatu', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110968ebc30.63792746', 'oxbaseshop', 0, 'Vatican', 'VA', 'VAT', '336', 9999, 'Europa', '', 'Vatican', '', '', 'Europe', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096902d92.14742486', 'oxbaseshop', 0, 'Venezuela', 'VE', 'VEN', '862', 9999, 'Rest Welt', '', 'Venezuela', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096919d00.92534927', 'oxbaseshop', 0, 'Vietnam', 'VN', 'VNM', '704', 9999, 'Rest Welt', '', 'Viet Nam', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109692fc04.15216034', 'oxbaseshop', 0, 'Britische Jungferninseln', 'VS', 'VGB', '92', 9999, 'Rest Welt', '', 'British Virgin Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096944468.61956573', 'oxbaseshop', 0, 'Reine Inseln (us.)', 'VI', 'VIR', '850', 9999, 'Rest Welt', '', 'Virgin Islands (u.s.)', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110969598c8.76966113', 'oxbaseshop', 0, 'Wallis- Und Futuna-inseln', 'WF', 'WLF', '876', 9999, 'Rest Welt', '', 'Wallis And Futuna Islands', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f1109696e4e9.33006292', 'oxbaseshop', 0, 'Westcsahara', 'EH', 'ESH', '732', 9999, 'Rest Welt', '', 'Western Sahara', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f11096982354.73448958', 'oxbaseshop', 0, 'Yemen', 'YE', 'YEM', '887', 9999, 'Rest Welt', '', 'Yemen', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110969afa62.05474721', 'oxbaseshop', 0, 'Zaire', 'ZR', 'ZAR', '180', 9999, 'Rest Welt', '', 'Zaire', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110969c34a2.42564730', 'oxbaseshop', 0, 'Sambia', 'ZM', 'ZMB', '894', 9999, 'Rest Welt', '', 'Zambia', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('8f241f110969da699.04185888', 'oxbaseshop', 0, 'Zimbabwe', 'ZW', 'ZWE', '716', 9999, 'Rest Welt', '', 'Zimbabwe', '', '', 'Rest World', '', '', '', '', '', 0);
INSERT INTO `oxcountry` VALUES ('56d308a822c18e106.3ba59048', 'oxbaseshop', 0, 'Montenegro', 'ME', 'MNE', '499', 9999, 'Rest Europa', '', 'Montenegro', '', '', 'Rest Europe', '', '', '', '', '', 0);

#
# Table structure for table `oxdelivery`
#

DROP TABLE IF EXISTS `oxdelivery`;

CREATE TABLE `oxdelivery` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '0',
  `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXTITLE_1` varchar(255) NOT NULL default '',
  `OXTITLE_2` varchar(255) NOT NULL default '',
  `OXTITLE_3` varchar(255) NOT NULL default '',
  `OXADDSUMTYPE` enum('%','abs') NOT NULL default 'abs',
  `OXADDSUM` double NOT NULL default '0',
  `OXDELTYPE` enum('a','s','w','p') NOT NULL default 'a',
  `OXPARAM` double NOT NULL default '0',
  `OXPARAMEND` double NOT NULL default '0',
  `OXFIXED` tinyint(1) NOT NULL default '0',
  `OXSORT` int(11) NOT NULL default '9999',
  `OXFINALIZE` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`)
)  TYPE=MyISAM;

#
# Table structure for table `oxdiscount`
#

DROP TABLE IF EXISTS `oxdiscount`;

CREATE TABLE `oxdiscount` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '0',
  `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXTITLE` varchar(128) NOT NULL default '',
  `OXTITLE_1` varchar( 128 ) NOT NULL,
  `OXTITLE_2` varchar( 128 ) NOT NULL,
  `OXTITLE_3` varchar( 128 ) NOT NULL,
  `OXAMOUNT` double NOT NULL default '0',
  `OXAMOUNTTO` double NOT NULL default '999999',
  `OXPRICETO` double NOT NULL default '999999',
  `OXPRICE` double NOT NULL default '0',
  `OXADDSUMTYPE` enum('%','abs','itm') NOT NULL default '%',
  `OXADDSUM` double NOT NULL default '0',
  `OXITMARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXITMAMOUNT` double NOT NULL default '1',
  `OXITMMULTIPLE` int(1) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`),
  KEY `OXACTIVE` (`OXACTIVE`),
  KEY `OXACTIVEFROM` (`OXACTIVEFROM`),
  KEY `OXACTIVETO` (`OXACTIVETO`)
) TYPE=MyISAM;

#
# Table structure for table `oxgbentries`
#

DROP TABLE IF EXISTS `oxgbentries`;

CREATE TABLE `oxgbentries` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXCONTENT` text NOT NULL,
  `OXCREATE` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVE` tinyint(1) NOT NULL default '0' ,
  `OXVIEWED` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM COMMENT='Guestbook`s entries';

#
# Table structure for table `oxgroups`
#

DROP TABLE IF EXISTS `oxgroups`;

CREATE TABLE `oxgroups` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXTITLE` char(128) NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXACTIVE` (`OXACTIVE`)
) TYPE=MyISAM;

#
# Table structure for table `oxgroups`
#

INSERT INTO `oxgroups` VALUES ('oxidblacklist', '1', 'Blacklist');
INSERT INTO `oxgroups` VALUES ('oxidsmallcust', '1', 'Geringer Umsatz');
INSERT INTO `oxgroups` VALUES ('oxidmiddlecust', '1', 'Mittlerer Umsatz');
INSERT INTO `oxgroups` VALUES ('oxidgoodcust', '1', 'Grosser Umsatz');
INSERT INTO `oxgroups` VALUES ('oxidforeigncustomer', '1', 'Auslandskunde');
INSERT INTO `oxgroups` VALUES ('oxidnewcustomer', '1', 'Inlandskunde');
INSERT INTO `oxgroups` VALUES ('oxidpowershopper', '1', 'Powershopper');
INSERT INTO `oxgroups` VALUES ('oxiddealer', '1', 'Händler');
INSERT INTO `oxgroups` VALUES ('oxidnewsletter', '1', 'Newsletter-Abonnenten');
INSERT INTO `oxgroups` VALUES ('oxidadmin', '1', 'Shop-Admin');
INSERT INTO `oxgroups` VALUES ('oxidpriceb', '1', 'Preis B');
INSERT INTO `oxgroups` VALUES ('oxidpricea', '1', 'Preis A');
INSERT INTO `oxgroups` VALUES ('oxidpricec', '1', 'Preis C');
INSERT INTO `oxgroups` VALUES ('oxidblocked', '1', 'BLOCKED');
INSERT INTO `oxgroups` VALUES ('oxidcustomer', '1', 'Kunde');
INSERT INTO `oxgroups` VALUES ('oxidnotyetordered', '1', 'Noch nicht bestellt');

#
# Table structure for table `oxlinks`
#

DROP TABLE IF EXISTS `oxlinks`;

CREATE TABLE `oxlinks` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '0',
  `OXURL` varchar(255) NOT NULL default '',
  `OXURLDESC` text NOT NULL,
  `OXURLDESC_1` text NOT NULL,
  `OXURLDESC_2` text NOT NULL,
  `OXURLDESC_3` text NOT NULL,
  `OXINSERT` datetime default NULL,
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`),
  KEY `OXINSERT` (`OXINSERT`),
  KEY `OXACTIVE` (`OXACTIVE`)
) TYPE=MyISAM;

#
# Table structure for table `oxlogs`
#

DROP TABLE IF EXISTS `oxlogs`;

CREATE TABLE `oxlogs` (
  `OXTIME` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSESSID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXCLASS` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXFNC` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXCNID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXANID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXPARAMETER` varchar(64) NOT NULL default ''
) TYPE=InnoDB;

#
# Table structure for table `oxvouchers`
#

DROP TABLE IF EXISTS `oxvouchers` ;

CREATE  TABLE IF NOT EXISTS `oxvouchers` (
  `OXDATEUSED` DATE NULL DEFAULT NULL ,
  `OXORDERID` char(32) character set latin1 collate latin1_general_ci NOT NULL DEFAULT '' ,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL DEFAULT '' ,
  `OXRESERVED` INT(11) NOT NULL DEFAULT 0 ,
  `OXVOUCHERNR` varchar(255) NOT NULL DEFAULT '',
  `OXVOUCHERSERIEID` char(32) character set latin1 collate latin1_general_ci NOT NULL DEFAULT '' ,
  `OXDISCOUNT` FLOAT(9,2) NULL DEFAULT NULL ,
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL DEFAULT '' ,
  PRIMARY KEY  (`OXID`),
  INDEX OXVOUCHERSERIEID (`OXVOUCHERSERIEID` ASC) ,
  INDEX OXORDERID (`OXORDERID` ASC) ,
  INDEX OXUSERID (`OXUSERID` ASC) ,
  INDEX OXVOUCHERNR (`OXVOUCHERNR` ASC)
) ENGINE = InnoDB;

#
# Table structure for table `oxvoucherseries`
#

DROP TABLE IF EXISTS `oxvoucherseries` ;

CREATE  TABLE IF NOT EXISTS `oxvoucherseries` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL DEFAULT '' ,
  `OXSHOPID` CHAR(32) NOT NULL DEFAULT '' ,
  `OXSERIENR` varchar(255) NOT NULL DEFAULT '' ,
  `OXSERIEDESCRIPTION` varchar(255) NOT NULL DEFAULT '' ,
  `OXDISCOUNT` FLOAT(9,2) NOT NULL DEFAULT '0' ,
  `OXDISCOUNTTYPE` ENUM('percent','absolute') NOT NULL DEFAULT 'absolute' ,
  `OXSTARTDATE` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `OXRELEASEDATE` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `OXBEGINDATE` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `OXENDDATE` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `OXALLOWSAMESERIES` TINYINT(1) NOT NULL DEFAULT 0 ,
  `OXALLOWOTHERSERIES` TINYINT(1) NOT NULL DEFAULT 0 ,
  `OXALLOWUSEANOTHER` TINYINT(1) NOT NULL DEFAULT 0 ,
  `OXMINIMUMVALUE` FLOAT(9,2) NOT NULL DEFAULT '0.00' ,
  PRIMARY KEY  (`OXID`),
  INDEX OXSERIENR (`OXSERIENR` ASC) ,
  INDEX OXSHOPID (`OXSHOPID` ASC)
) ENGINE = InnoDB;

#
# Table structure for table `oxnews`
#

DROP TABLE IF EXISTS `oxnews`;

CREATE TABLE `oxnews` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXDATE` date NOT NULL default '0000-00-00',
  `OXSHORTDESC` varchar(255) NOT NULL default '',
  `OXLONGDESC` text NOT NULL,
  `OXACTIVE_1` tinyint(1) NOT NULL default '0',
  `OXSHORTDESC_1` varchar(255) NOT NULL default '',
  `OXLONGDESC_1` text NOT NULL,
  `OXACTIVE_2` tinyint(1) NOT NULL default '0',
  `OXSHORTDESC_2` varchar(255) NOT NULL default '',
  `OXLONGDESC_2` text NOT NULL,
  `OXACTIVE_3` tinyint(1) NOT NULL default '0',
  `OXSHORTDESC_3` varchar(255) NOT NULL default '',
  `OXLONGDESC_3` text NOT NULL,
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`),
  KEY `OXACTIVE` (`OXACTIVE`),
  KEY `OXACTIVEFROM` (`OXACTIVEFROM`),
  KEY `OXACTIVETO` (`OXACTIVETO`)
) TYPE=MyISAM;

#
# Table structure for table `oxnewsletter`
#

DROP TABLE IF EXISTS `oxnewsletter`;

CREATE TABLE `oxnewsletter` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXTEMPLATE` mediumtext NOT NULL,
  `OXPLAINTEMPLATE` mediumtext NOT NULL,
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxobject2article`
#

DROP TABLE IF EXISTS `oxobject2article`;

CREATE TABLE `oxobject2article` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXARTICLENID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSORT` int(5) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXARTICLENID` (`OXARTICLENID`),
  KEY `OXOBJECTID` (`OXOBJECTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxobject2attribute`
#

DROP TABLE IF EXISTS `oxobject2attribute`;

CREATE TABLE `oxobject2attribute` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXATTRID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXVALUE` char(255) NOT NULL default '',
  `OXPOS` int(11) NOT NULL default '9999',
  `OXVALUE_1` char(255) NOT NULL default '',
  `OXVALUE_2` char(255) NOT NULL default '',
  `OXVALUE_3` char(255) NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`),
  KEY `OXATTRID` (`OXATTRID`)
) TYPE=MyISAM;

#
# Table structure for table `oxobject2category`
#

DROP TABLE IF EXISTS `oxobject2category`;

CREATE TABLE `oxobject2category` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXCATNID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXPOS` int(11) NOT NULL default '0',
  `OXTIME` INT( 11 ) DEFAULT 0 NOT NULL,
  PRIMARY KEY  (`OXID`),
  KEY ( `OXOBJECTID` ),
  KEY (`OXPOS`),
  KEY `OXMAINIDX` (`OXCATNID`,`OXOBJECTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxobject2delivery`
#

DROP TABLE IF EXISTS `oxobject2delivery`;

CREATE TABLE `oxobject2delivery` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXDELIVERYID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`),
  KEY `OXDELIVERYID` ( `OXDELIVERYID` , `OXTYPE` )
) TYPE=MyISAM;

#
# Table structure for table `oxobject2discount`
#

DROP TABLE IF EXISTS `oxobject2discount`;

CREATE TABLE `oxobject2discount` (
   `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
   `OXDISCOUNTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
   `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
   `OXTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
   PRIMARY KEY  (`OXID`),
   KEY `oxobjectid` (`OXOBJECTID`),
   KEY `oxdiscidx` (`OXDISCOUNTID`,`OXTYPE`)
   ) TYPE=MyISAM;

#
# Table structure for table `oxobject2group`
#

DROP TABLE IF EXISTS `oxobject2group`;

CREATE TABLE `oxobject2group` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXGROUPSID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`),
  KEY `OXGROUPSID` (`OXGROUPSID`)
) TYPE=MyISAM;

INSERT INTO `oxobject2group` VALUES ('e913fdd8443ed43e1.51222316', 'oxbaseshop', 'oxdefaultadmin', 'oxidadmin');

#
# Table structure for table `oxobject2payment`
#

DROP TABLE IF EXISTS `oxobject2payment`;

CREATE TABLE `oxobject2payment` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXPAYMENTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxobject2selectlist`
#

DROP TABLE IF EXISTS `oxobject2selectlist`;

CREATE TABLE `oxobject2selectlist` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSELNID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSORT` int(5) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`),
  KEY `OXSELNID` (`OXSELNID`)
) TYPE=MyISAM;

#
# Table structure for table `oxorder`
#

DROP TABLE IF EXISTS `oxorder`;

CREATE TABLE `oxorder` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXORDERDATE` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXORDERNR` int(11) NOT NULL default '0',
  `OXBILLCOMPANY` varchar(255) NOT NULL default '',
  `OXBILLEMAIL` varchar(255) NOT NULL default '',
  `OXBILLFNAME` varchar(255) NOT NULL default '',
  `OXBILLLNAME` varchar(255) NOT NULL default '',
  `OXBILLSTREET` varchar(255) NOT NULL default '',
  `OXBILLSTREETNR` varchar(16) NOT NULL default '',
  `OXBILLADDINFO` varchar(255) NOT NULL default '',
  `OXBILLUSTID` varchar(255) NOT NULL default '',
  `OXBILLCITY` varchar(255) NOT NULL default '',
  `OXBILLCOUNTRYID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXBILLZIP` varchar(16) NOT NULL default '',
  `OXBILLFON` varchar(128) NOT NULL default '',
  `OXBILLFAX` varchar(128) NOT NULL default '',
  `OXBILLSAL` varchar(128) NOT NULL default '',
  `OXDELCOMPANY` varchar(255) NOT NULL default '',
  `OXDELFNAME` varchar(255) NOT NULL default '',
  `OXDELLNAME` varchar(255) NOT NULL default '',
  `OXDELSTREET` varchar(255) NOT NULL default '',
  `OXDELSTREETNR` varchar(16) NOT NULL default '',
  `OXDELADDINFO` varchar(255) NOT NULL default '',
  `OXDELCITY` varchar(255) NOT NULL default '',
  `OXDELCOUNTRYID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXDELZIP` varchar(16) NOT NULL default '',
  `OXDELFON` varchar(128) NOT NULL default '',
  `OXDELFAX` varchar(128) NOT NULL default '',
  `OXDELSAL` varchar(128) NOT NULL default '',
  `OXPAYMENTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXPAYMENTTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTOTALNETSUM` double NOT NULL default '0',
  `OXTOTALBRUTSUM` double NOT NULL default '0',
  `OXTOTALORDERSUM` double NOT NULL default '0',
  `OXDELCOST` double NOT NULL default '0',
  `OXDELVAT` double NOT NULL default '0',
  `OXPAYCOST` double NOT NULL default '0',
  `OXPAYVAT` double NOT NULL default '0',
  `OXWRAPCOST` DOUBLE NOT NULL default '0',
  `OXWRAPVAT` double NOT NULL default '0',
  `OXCARDID` varchar( 32 ) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXCARDTEXT` text NOT NULL,
  `OXDISCOUNT` double NOT NULL default '0',
  `OXEXPORT` tinyint(4) NOT NULL default '0',
  `OXBILLNR` varchar(128) NOT NULL default '',
  `OXTRACKCODE` varchar(128) NOT NULL default '',
  `OXSENDDATE` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXREMARK` text NOT NULL,
  `OXVOUCHERDISCOUNT` double NOT NULL default '0',
  `OXCURRENCY` char(32) NOT NULL default '',
  `OXCURRATE` double NOT NULL default '0',
  `OXFOLDER` char(32) NOT NULL default '',
  `OXPIDENT` varchar(128) NOT NULL default '',
  `OXTRANSID` varchar(64) NOT NULL default '',
  `OXPAYID` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXXID` varchar(64) NOT NULL default '',
  `OXPAID` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXSTORNO` tinyint(1) NOT NULL default '0',
  `OXIP` varchar(16) NOT NULL default '',
  `OXTRANSSTATUS` varchar(30) NOT NULL default '',
  `OXLANG` int(2) NOT NULL default '0',
  `OXINVOICENR` int(11) NOT NULL default '0',
  `OXDELTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `MAINIDX` (`OXSHOPID`,`OXSTORNO`,`OXORDERDATE`)
) TYPE=InnoDB;

#
# Table structure for table `oxorderarticles`
#

DROP TABLE IF EXISTS `oxorderarticles`;

CREATE TABLE `oxorderarticles` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXORDERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXAMOUNT` double NOT NULL default '0',
  `OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXARTNUM` varchar(255) NOT NULL default '',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXSHORTDESC` varchar(255) NOT NULL default '',
  `OXSELVARIANT` varchar(255) NOT NULL default '',
  `OXNETPRICE` double NOT NULL default '0',
  `OXBRUTPRICE` double NOT NULL default '0',
  `OXVATPRICE` double NOT NULL default '0',
  `OXVAT` double NOT NULL default '0',
  `OXPERSPARAM` text NOT NULL,
  `OXPRICE` double NOT NULL default '0',
  `OXBPRICE` double NOT NULL default '0',
  `OXNPRICE` double NOT NULL default '0',
  `OXWRAPID` varchar( 32 ) NOT NULL default '',
  `OXEXTURL` varchar(255) NOT NULL default '',
  `OXURLDESC` varchar(255) NOT NULL default '',
  `OXURLIMG` varchar(128) NOT NULL default '',
  `OXTHUMB` varchar(128) NOT NULL default '',
  `OXPIC1` varchar(128) NOT NULL default '',
  `OXPIC2` varchar(128) NOT NULL default '',
  `OXPIC3` varchar(128) NOT NULL default '',
  `OXPIC4` varchar(128) NOT NULL default '',
  `OXPIC5` varchar(128) NOT NULL default '',
  `OXWEIGHT` double NOT NULL default '0',
  `OXSTOCK` double NOT NULL default '-1',
  `OXDELIVERY` date NOT NULL default '0000-00-00',
  `OXINSERT` date NOT NULL default '0000-00-00',
  `OXTIMESTAMP` timestamp(14) NOT NULL,
  `OXLENGTH` double NOT NULL default '0',
  `OXWIDTH` double NOT NULL default '0',
  `OXHEIGHT` double NOT NULL default '0',
  `OXFILE` varchar(128) NOT NULL default '',
  `OXSEARCHKEYS` varchar(255) NOT NULL default '',
  `OXTEMPLATE` varchar(128) NOT NULL default '',
  `OXQUESTIONEMAIL` varchar(255) NOT NULL default '',
  `OXISSEARCH` tinyint(1) NOT NULL default '1',
  `OXFOLDER` char(32) NOT NULL default '',
  `OXSUBCLASS` char(32) NOT NULL default '',
  `OXSTORNO` tinyint(1) NOT NULL default '0',
  `OXORDERSHOPID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXORDERID` (`OXORDERID`),
  KEY `OXARTID` (`OXARTID`),
  KEY `OXARTNUM` (`OXARTNUM`)
) TYPE=InnoDB;

#
# Table structure for table `oxpayments`
#

DROP TABLE IF EXISTS `oxpayments`;

CREATE TABLE `oxpayments` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXDESC` varchar(128) NOT NULL default '',
  `OXADDSUM` double NOT NULL default '0',
  `OXADDSUMTYPE` enum('abs','%') NOT NULL default 'abs',
  `OXFROMBONI` int(11) NOT NULL default '0',
  `OXFROMAMOUNT` double NOT NULL default '0',
  `OXTOAMOUNT` double NOT NULL default '0',
  `OXVALDESC` text NOT NULL,
  `OXCHECKED` tinyint(1) NOT NULL default '0',
  `OXDESC_1` varchar(128) NOT NULL default '',
  `OXVALDESC_1` text NOT NULL,
  `OXDESC_2` varchar(128) NOT NULL default '',
  `OXVALDESC_2` text NOT NULL,
  `OXDESC_3` varchar(128) NOT NULL default '',
  `OXVALDESC_3` text NOT NULL,
  `OXLONGDESC` varchar(255) NOT NULL default '',
  `OXLONGDESC_1` varchar(255) NOT NULL default '',
  `OXLONGDESC_2` varchar(255) NOT NULL default '',
  `OXLONGDESC_3` varchar(255) NOT NULL default '',
  `OXSORT` int(5) NOT NULL default 0,
  PRIMARY KEY  (`OXID`),
  KEY `OXACTIVE` (`OXACTIVE`)
) TYPE=MyISAM;

#
# Data for table `oxpayments`
#
INSERT INTO `oxpayments` VALUES('oxidcashondel', 1, 'Nachnahme', 7.5, 'abs', 0, 0, 1000000, '', '1', 'COD cash on delivery', '', '', '', '', '', '', '', '', '', 0);
INSERT INTO `oxpayments` VALUES('oxidcreditcard', 1, 'Kreditkarte', 20.9, 'abs', 500, 0, 1000000, 'kktype__@@kknumber__@@kkmonth__@@kkyear__@@kkname__@@kkpruef__@@', '1', 'Credit Card', 'kktype__@@kknumber__@@kkmonth__@@kkyear__@@kkname__@@kkpruef__@@', '', '', '', '', 'Die Belastung Ihrer Kreditkarte erfolgt mit dem Abschluss der Bestellung.', 'Your Credit Card is charged when you submit the order.', '', '', 0);
INSERT INTO `oxpayments` VALUES('oxiddebitnote', 1, 'Bankeinzug/Lastschrift', 0, 'abs', 0, 0, 1000000, 'lsbankname__@@lsblz__@@lsktonr__@@lsktoinhaber__@@', '0', 'Direct Debit', 'lsbankname__@@lsblz__@@lsktonr__@@lsktoinhaber__@@', '', '', '', '', 'Die Belastung Ihres Kontos erfolgt mit dem Versand der Ware.', 'Your account is charged when the order is shipped.', '', '', 0);
INSERT INTO `oxpayments` VALUES('oxidpayadvance', 1, 'Vorauskasse 2% Skonto', -2, '%', 0, 0, 1000000, '', '1', 'Payment in advance 2% Skonto', '', '', '', '', '', '', '', '', '', 0);
INSERT INTO `oxpayments` VALUES('oxidinvoice', 1, 'Rechnung', 0, 'abs', 800, 0, 1000000, '', '0', 'Invoice', '', '', '', '', '', '', '', '', '', 0);
INSERT INTO `oxpayments` VALUES('oxempty', 1, 'Empty', 0, 'abs', 0, 0, 0, '', '0', 'Empty', '', '', '', '', '', 'for other countries', 'for other countries', '', '', 0);

#
# Table structure for table `oxprice2article`
#

DROP TABLE IF EXISTS `oxprice2article`;

CREATE TABLE `oxprice2article` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXADDABS` double NOT NULL default '0',
  `OXADDPERC` double NOT NULL default '0',
  `OXAMOUNT` double NOT NULL default '0',
  `OXAMOUNTTO` double NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`),
 KEY `OXARTID` (`OXARTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxpricealarm`
#

DROP TABLE IF EXISTS `oxpricealarm`;

CREATE TABLE `oxpricealarm` (
`OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
`OXSHOPID` char( 32 ) character set latin1 collate latin1_general_ci NOT NULL default '',
`OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
`OXEMAIL` varchar(128) NOT NULL default '',
`OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
`OXPRICE` double NOT NULL default '0',
`OXCURRENCY` varchar(32) NOT NULL default '',
`OXLANG` INT(2) NOT NULL default 0,
`OXINSERT` datetime NOT NULL default '0000-00-00 00:00:00',
`OXSENDED` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxrecommlists`
#

DROP TABLE IF EXISTS `oxrecommlists`;

CREATE TABLE `oxrecommlists` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXAUTHOR` varchar(255) NOT NULL default '',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXDESC` text NOT NULL,
  `OXRATINGCNT` int(11) NOT NULL default '0',
  `OXRATING` double NOT NULL default '0',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;



# --------------------------------------------------------

#
# Table structure for table `oxobject2list`
#

DROP TABLE IF EXISTS `oxobject2list`;

CREATE TABLE `oxobject2list` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXLISTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXDESC` text NOT NULL default '',
  `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`),
  KEY `OXLISTID` (`OXLISTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxremark`
#

DROP TABLE IF EXISTS `oxremark`;

CREATE TABLE `oxremark` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXPARENTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTYPE` enum('o','r','n','c') NOT NULL default 'r',
  `OXHEADER` varchar(255) NOT NULL default '',
  `OXTEXT` text NOT NULL,
  `OXCREATE` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`OXID`),
  KEY `OXPARENTID` (`OXPARENTID`),
  KEY `OXTYPE` (`OXTYPE`)
) TYPE=MyISAM;

#
# Table structure for table `oxratings`
#

DROP TABLE IF EXISTS `oxratings`;

CREATE TABLE `oxratings` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTYPE` enum('oxarticle','oxrecommlist') NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXRATING` int(1) NOT NULL default '0',
  `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`OXID`),
  KEY `oxobjectsearch` (`OXTYPE`,`OXOBJECTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxreviews`
#

DROP TABLE IF EXISTS `oxreviews`;

CREATE TABLE `oxreviews` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXACTIVE` tinyint(1) NOT NULL default '0',
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXTYPE` enum('oxarticle','oxrecommlist') NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTEXT` text NOT NULL,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXCREATE` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXLANG` tinyint( 3 ) NOT NULL DEFAULT '0',
  `OXRATING` int(1) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `oxobjectsearch` (`OXTYPE`,`OXOBJECTID`)
) ENGINE=MyISAM;

#
# Table structure for table `oxselectlist`
#

DROP TABLE IF EXISTS `oxselectlist`;

CREATE TABLE `oxselectlist` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTITLE` varchar(254) NOT NULL default '',
  `OXIDENT` varchar(255) NOT NULL default '',
  `OXVALDESC` text NOT NULL,
  `OXTITLE_1` varchar(255) NOT NULL default '',
  `OXVALDESC_1` text NOT NULL,
  `OXTITLE_2` varchar(255) NOT NULL default '',
  `OXVALDESC_2` text NOT NULL,
  `OXTITLE_3` varchar(255) NOT NULL default '',
  `OXVALDESC_3` text NOT NULL,
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxshops`
#

DROP TABLE IF EXISTS `oxshops`;

CREATE TABLE `oxshops` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXPRODUCTIVE` tinyint(1) NOT NULL default '0',
  `OXDEFCURRENCY` char(32) NOT NULL default '',
  `OXDEFLANGUAGE` int(11) NOT NULL default '0',
  `OXNAME` varchar(255) NOT NULL default '',
  `OXTITLEPREFIX` varchar(255) NOT NULL default '',
  `OXTITLEPREFIX_1` varchar(255) NOT NULL default '',
  `OXTITLEPREFIX_2` varchar(255) NOT NULL default '',
  `OXTITLEPREFIX_3` varchar(255) NOT NULL default '',
  `OXTITLESUFFIX` varchar(255) NOT NULL default '',
  `OXTITLESUFFIX_1` varchar(255) NOT NULL default '',
  `OXTITLESUFFIX_2` varchar(255) NOT NULL default '',
  `OXTITLESUFFIX_3` varchar(255) NOT NULL default '',
  `OXSTARTTITLE` varchar(255) NOT NULL default '',
  `OXSTARTTITLE_1` varchar(255) NOT NULL default '',
  `OXSTARTTITLE_2` varchar(255) NOT NULL default '',
  `OXSTARTTITLE_3` varchar(255) NOT NULL default '',
  `OXINFOEMAIL` varchar(255) NOT NULL default '',
  `OXORDEREMAIL` varchar(255) NOT NULL default '',
  `OXOWNEREMAIL` varchar(255) NOT NULL default '',
  `OXORDERSUBJECT` varchar(255) NOT NULL default '',
  `OXREGISTERSUBJECT` varchar(255) NOT NULL default '',
  `OXFORGOTPWDSUBJECT` varchar(255) NOT NULL default '',
  `OXSENDEDNOWSUBJECT` varchar(255) NOT NULL default '',
  `OXORDERSUBJECT_1` varchar(255) NOT NULL default '',
  `OXREGISTERSUBJECT_1` varchar(255) NOT NULL default '',
  `OXFORGOTPWDSUBJECT_1` varchar(255) NOT NULL default '',
  `OXSENDEDNOWSUBJECT_1` varchar(255) NOT NULL default '',
  `OXORDERSUBJECT_2` varchar(255) NOT NULL default '',
  `OXREGISTERSUBJECT_2` varchar(255) NOT NULL default '',
  `OXFORGOTPWDSUBJECT_2` varchar(255) NOT NULL default '',
  `OXSENDEDNOWSUBJECT_2` varchar(255) NOT NULL default '',
  `OXORDERSUBJECT_3` varchar(255) NOT NULL default '',
  `OXREGISTERSUBJECT_3` varchar(255) NOT NULL default '',
  `OXFORGOTPWDSUBJECT_3` varchar(255) NOT NULL default '',
  `OXSENDEDNOWSUBJECT_3` varchar(255) NOT NULL default '',
  `OXSMTP` varchar(255) NOT NULL default '',
  `OXSMTPUSER` varchar(128) NOT NULL default '',
  `OXSMTPPWD` varchar(128) NOT NULL default '',
  `OXCOMPANY` varchar(128) NOT NULL default '',
  `OXSTREET` varchar(255) NOT NULL default '',
  `OXZIP` varchar(255) NOT NULL default '',
  `OXCITY` varchar(255) NOT NULL default '',
  `OXCOUNTRY` varchar(255) NOT NULL default '',
  `OXBANKNAME` varchar(255) NOT NULL default '',
  `OXBANKNUMBER` varchar(255) NOT NULL default '',
  `OXBANKCODE` varchar(255) NOT NULL default '',
  `OXVATNUMBER` varchar(255) NOT NULL default '',
  `OXBICCODE` varchar(255) NOT NULL default '',
  `OXIBANNUMBER` varchar(255) NOT NULL default '',
  `OXFNAME` varchar(255) NOT NULL default '',
  `OXLNAME` varchar(255) NOT NULL default '',
  `OXTELEFON` varchar(255) NOT NULL default '',
  `OXTELEFAX` varchar(255) NOT NULL default '',
  `OXURL` varchar(255) NOT NULL default '',
  `OXDEFCAT` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXHRBNR` varchar(64) NOT NULL default '',
  `OXCOURT` varchar(128) NOT NULL default '',
  `OXADBUTLERID` varchar(64) NOT NULL default '',
  `OXAFFILINETID` varchar(64) NOT NULL default '',
  `OXSUPERCLICKSID` varchar(64) NOT NULL default '',
  `OXAFFILIWELTID` varchar(64) NOT NULL default '',
  `OXAFFILI24ID` varchar(64) NOT NULL default '',
  `OXEDITION` CHAR( 2 ) NOT NULL,
  `OXVERSION` CHAR( 16 ) NOT NULL,
  `OXSEOACTIVE` tinyint(1) NOT NULL DEFAULT '1',
  `OXSEOACTIVE_1` tinyint(1) NOT NULL DEFAULT '1',
  `OXSEOACTIVE_2` tinyint(1) NOT NULL DEFAULT '1',
  `OXSEOACTIVE_3` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`OXID`),
  KEY `OXACTIVE` (`OXACTIVE`)
) TYPE=MyISAM;

#
# Data for table `oxshops`
#

INSERT INTO `oxshops` VALUES ('oxbaseshop', 1, 0, '', 0, 'OXID eShop 4', 'OXID Geschenke Shop', 'OXID Gift Shop', '', '', 'online kaufen', 'buy online', '', '', 'Originelle, witzige Geschenkideen - Lifestyle, Trends, Accessoires', 'Gift Ideas - Original, Funny Presents - Lifestyle, Trends, Accessories', '', '', 'Ihre Info E-Mail Adresse', 'Ihre Bestell Reply E-Mail Adresse', 'Ihre Bestell E-Mail Adresse', 'Ihre Bestellung bei OXID eSales', 'Vielen Dank für Ihre Registrierung im OXID eShop', 'Ihr Passwort im OXID eShop', 'Ihre OXID eSales Bestellung wurde versandt', 'Your order from OXID eShop', 'Thank you for your registration in OXID eShop', 'Your OXID eShop password', 'Your OXID eSales Order has been shipped', '', '', '', '', '', '', '', '', 'Tragen Sie bitte hier Ihren SMTP Server ein', '', '', 'Ihr Firmenname', 'Musterstr. 10', '79098', 'Musterstadt', 'Deutschland', 'Volksbank Musterstadt', '1234567890', '900 1234567', 'DE651234567', '', '', 'Hans', 'Mustermann', '0800 1234567', '0800 1234567', 'www.meineshopurl.com', '8a142c3e60a535f16.78077188', '', '', '', '', '', '', '', 'CE', '4.2.0', 1, 1, 0, 0);

#
# Table structure for table `oxstatistics`
#

DROP TABLE IF EXISTS `oxstatistics`;

CREATE TABLE `oxstatistics` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXTITLE` char(32) NOT NULL default '',
  `OXVALUE` text NOT NULL,
  PRIMARY KEY  (`OXID`)
) TYPE=InnoDB;


#
# Table structure for table `oxuser`
#

DROP TABLE IF EXISTS `oxuser`;

CREATE TABLE `oxuser` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXRIGHTS` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSHOPID` char( 32 ) NOT NULL default '',
  `OXUSERNAME` varchar(255) NOT NULL default '',
  `OXPASSWORD` varchar(128) NOT NULL default '',
  `OXPASSSALT` char(128) character set latin1 collate latin1_general_ci NOT NULL,
  `OXCUSTNR` int(11) NOT NULL default '0',
  `OXUSTID` varchar(255) NOT NULL default '',
  `OXCOMPANY` varchar(255) NOT NULL default '',
  `OXFNAME` varchar(255) NOT NULL default '',
  `OXLNAME` varchar(255) NOT NULL default '',
  `OXSTREET` varchar(255) NOT NULL default '',
  `OXSTREETNR` varchar(16) NOT NULL default '',
  `OXADDINFO` varchar(255) NOT NULL default '',
  `OXCITY` varchar(255) NOT NULL default '',
  `OXCOUNTRYID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXZIP` varchar(16) NOT NULL default '',
  `OXFON` varchar(128) NOT NULL default '',
  `OXFAX` varchar(128) NOT NULL default '',
  `OXSAL` varchar(128) NOT NULL default '',
  `OXBONI` int(11) NOT NULL default '0',
  `OXCREATE` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXREGISTER` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXPRIVFON` varchar(64) NOT NULL default '',
  `OXMOBFON` varchar(64) NOT NULL default '',
  `OXBIRTHDATE` date NOT NULL default '0000-00-00',
  `OXURL` varchar(255) NOT NULL default '',
  `OXDISABLEAUTOGRP` tinyint(1) NOT NULL default '0',
  `OXUPDATEKEY` char( 32 ) NOT NULL default '',
  `OXUPDATEEXP` int(11) NOT NULL default '0',
  `OXISOPENID` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  UNIQUE `OXUSERNAME` (`OXUSERNAME`, `OXSHOPID`),
  KEY `OXPASSWORD` (`OXPASSWORD`),
  KEY `OXACTIVE` (`OXACTIVE`)
) TYPE=MyISAM;

#
# Data for table `oxuser`
#

INSERT INTO `oxuser` VALUES ('oxdefaultadmin', '1', 'malladmin', 'oxbaseshop', 'admin', 'f6fdffe48c908deb0f4c3bd36c032e72', '61646D696E', 1, '', 'Ihr Firmenname', 'Hans', 'Mustermann', 'Musterstr.', '10', '', 'Musterstadt', 'a7c40f631fc920687.20179984', '79098', '0800 1234567', '0800 1234567', 'Herr', 1000, '2003-01-01 00:00:00', '2003-01-01 00:00:00', '', '', '0000-00-00', '', '0', '', '0', '0');

#
# Table structure for table `oxuserpayments`
#

DROP TABLE IF EXISTS `oxuserpayments`;

CREATE TABLE `oxuserpayments` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXPAYMENTSID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXVALUE` blob NOT NULL,
  PRIMARY KEY  (`OXID`),
  KEY `OXUSERID` (`OXUSERID`)
) TYPE=InnoDB;

#
# Table structure for table `oxactions`
#

DROP TABLE IF EXISTS `oxactions`;

CREATE TABLE `oxactions` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXTITLE` char(128) NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Data for table `oxactions`
#

INSERT INTO `oxactions` VALUES ('oxstart', 'Startseite unten', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxtopstart', 'Topangebot Startseite', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxfirststart', 'Großes Angebot Startseite', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxbargain', 'Schnäppchen', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxtop5', 'Topseller', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxcatoffer', 'Kategorien-Topangebot', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxnewest', 'Frisch eingetroffen', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `oxactions` VALUES ('oxnewsletter', 'Newsletter', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

#
# Table structure for table `oxactions2article`
#

DROP TABLE IF EXISTS `oxactions2article`;

CREATE TABLE `oxactions2article` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIONID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSORT` int(11) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXMAINIDX` (`OXSHOPID`,`OXACTIONID`,`OXSORT`),
  KEY `OXARTID` (`OXARTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxwrapping`
#

DROP TABLE IF EXISTS `oxwrapping`;

CREATE TABLE `oxwrapping` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXACTIVE_1` tinyint(1) NOT NULL default '1',
  `OXACTIVE_2` tinyint(1) NOT NULL default '1',
  `OXACTIVE_3` tinyint(1) NOT NULL default '1',
  `OXTYPE` varchar(4) NOT NULL default 'WRAP',
  `OXNAME` varchar(128) NOT NULL default '',
  `OXNAME_1` varchar(128) NOT NULL default '',
  `OXNAME_2` varchar(128) NOT NULL default '',
  `OXNAME_3` varchar(128) NOT NULL default '',
  `OXPIC` varchar(128) NOT NULL default '',
  `OXPRICE` double NOT NULL default '0',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxdel2delset`
#

DROP TABLE IF EXISTS `oxdel2delset`;

CREATE TABLE `oxdel2delset` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXDELID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXDELSETID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`OXID`),
  KEY `OXDELID` (`OXDELID`)
) TYPE=MyISAM;

#
# Table structure for table `oxdeliveryset`
#

DROP TABLE IF EXISTS `oxdeliveryset`;

CREATE TABLE `oxdeliveryset` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '0',
  `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXTITLE` varchar(255) NOT NULL default '',
  `OXTITLE_1` varchar(255) NOT NULL default '',
  `OXTITLE_2` varchar(255) NOT NULL default '',
  `OXTITLE_3` varchar(255) NOT NULL default '',
  `OXPOS` int(11) NOT NULL default '0',
  PRIMARY KEY  (`OXID`),
  KEY `OXSHOPID` (`OXSHOPID`)
) TYPE=MyISAM;

#
# Data for table `oxdeliveryset`
#

INSERT INTO `oxdeliveryset` VALUES ('oxidstandard', 'oxbaseshop', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Standard', 'Standard', '', '', 10);

#
# Table structure for table `oxcategory2attribute`
#

DROP TABLE IF EXISTS `oxcategory2attribute`;

CREATE TABLE `oxcategory2attribute` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXATTRID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSORT` INT( 11 ) NOT NULL DEFAULT '9999',
  PRIMARY KEY  (`OXID`),
  KEY `OXOBJECTID` (`OXOBJECTID`)
) TYPE=MyISAM;

#
# Table structure for table `oxnewssubscribed`
#

DROP TABLE IF EXISTS `oxnewssubscribed`;

CREATE TABLE `oxnewssubscribed` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXUSERID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXSAL` char(64) NOT NULL default '',
  `OXFNAME` char(128) NOT NULL default '',
  `OXLNAME` char(128) NOT NULL default '',
  `OXEMAIL` char(128) NOT NULL default '',
  `OXDBOPTIN` tinyint(1) NOT NULL default '0',
  `OXEMAILFAILED` tinyint(1) NOT NULL default '0',
  `OXSUBSCRIBED` datetime NOT NULL default '0000-00-00 00:00:00',
  `OXUNSUBSCRIBED` datetime NOT NULL default '0000-00-00 00:00:00',
  UNIQUE KEY `OXEMAIL` (`OXEMAIL`),
  KEY `OXUSERID` (`OXUSERID`)
) TYPE=MyISAM;

#
# Data for table `oxnewssubscribed`
#

INSERT INTO `oxnewssubscribed` VALUES ('0b742e66fd94c88b8.61001136', 'oxdefaultadmin', 'Herr', 'Shop', 'Administrator', 'admin', 1, '0', '2005-07-26 19:16:09', '0000-00-00 00:00:00');

#
# Table structure for table `oxvendor`
#

DROP TABLE IF EXISTS `oxvendor`;

CREATE TABLE `oxvendor` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXICON` char(128) NOT NULL default '',
  `OXTITLE` char(255) NOT NULL default '',
  `OXSHORTDESC` char(255) NOT NULL default '',
  `OXTITLE_1` char(255) NOT NULL default '',
  `OXSHORTDESC_1` char(255) NOT NULL default '',
  `OXTITLE_2` char(255) NOT NULL default '',
  `OXSHORTDESC_2` char(255) NOT NULL default '',
  `OXTITLE_3` char(255) NOT NULL default '',
  `OXSHORTDESC_3` char(255) NOT NULL default '',
  `OXSHOWSUFFIX` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxmanufacturers`
#

DROP TABLE IF EXISTS `oxmanufacturers`;

CREATE TABLE `oxmanufacturers` (
  `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXACTIVE` tinyint(1) NOT NULL default '1',
  `OXICON` char(128) NOT NULL default '',
  `OXTITLE` char(255) NOT NULL default '',
  `OXSHORTDESC` char(255) NOT NULL default '',
  `OXTITLE_1` char(255) NOT NULL default '',
  `OXSHORTDESC_1` char(255) NOT NULL default '',
  `OXTITLE_2` char(255) NOT NULL default '',
  `OXSHORTDESC_2` char(255) NOT NULL default '',
  `OXTITLE_3` char(255) NOT NULL default '',
  `OXSHORTDESC_3` char(255) NOT NULL default '',
  `OXSHOWSUFFIX` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`OXID`)
) TYPE=MyISAM;

#
# Table structure for table `oxseo`# created 2008.04.16
#

DROP TABLE IF EXISTS `oxseo`;

CREATE TABLE `oxseo` (
`OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
`OXIDENT`    char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
`OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
`OXLANG`     int(2) NOT NULL default 0,
`OXSTDURL`   TEXT NOT NULL,
`OXSEOURL`   TEXT character set latin1 collate latin1_bin NOT NULL,
`OXTYPE`     enum('static', 'oxarticle', 'oxcategory', 'oxvendor', 'oxcontent', 'dynamic', 'oxmanufacturer') NOT NULL,
`OXFIXED`    TINYINT(1) NOT NULL default 0,
`OXKEYWORDS` TEXT NOT NULL,
`OXDESCRIPTION` TEXT NOT NULL,
`OXEXPIRED` tinyint(1) NOT NULL default '0',
`OXPARAMS` char(32) NOT NULL default '',
PRIMARY KEY (`OXIDENT`, `OXSHOPID`, `OXLANG`),
UNIQUE KEY search (`OXTYPE`, `OXOBJECTID`, `OXSHOPID`, `OXLANG`,`OXPARAMS`),
KEY `OXOBJECTID` (`OXLANG`,`OXOBJECTID`,`OXSHOPID`)
) TYPE=InnoDB;


#
# Data for table `oxseo`
#

INSERT INTO `oxseo` VALUES ('c855234180a3b4056b496120d229ea68', '023abc17c853f9bccc201c5afd549a92', 'oxbaseshop', 1, 'index.php?cl=account_wishlist', 'en/my-gift-registry/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('2e17757c0aaf8ed9ef2ba30317fa1faf', '0469752d03d80da379a679aaef4c0546', 'oxbaseshop', 1, 'index.php?cl=suggest', 'en/recommend/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('057ef382f23bdbb84991d049af2baba9', '063c82502d9dd528ce2cc40ceb76ade7', 'oxbaseshop', 1, 'index.php?cl=compare', 'en/my-product-comparison/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('23e2b46bdc9cd26023fd8020c5dff9a2', '0aaaa47d75da6581736b76eb4b4e62a3', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=review', 'hilfe/bewertungen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('41e797927a0b4318bc7fbc6c6702e194', '0f454924e8a9e54d99df911b3c8202ce', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_order', 'en/help/order-history/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('41e797927a0b4318bc7fbc6c6702e194', '116b37d6d0aa3bc10d40c0972e46dc17', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_order', 'hilfe/bestellhistorie/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('7bc8a506bbca225a2f95b6eac66020bf', '1368f5e45468ca1e1c7c84f174785c35', 'oxbaseshop', 1, 'index.php?cl=account_noticelist', 'en/my-wish-list/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('dd1a86d489a8b312737f59ab2cac0eb4', '1701ec08c0928b603e5290078f8ab724', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=details', 'en/help/product-details/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('1a583d63681ba48d989bdfd0bea8ade7', '192ce04536d8c1ea3d530825bc06bff9', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_wishlist', 'hilfe/mein-wunschzettel/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('c855234180a3b4056b496120d229ea68', '1f30ae9b1c78b7dc89d3e5fe9eb0de59', 'oxbaseshop', 0, 'index.php?cl=account_wishlist', 'mein-wunschzettel/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('6c3129355426de70051cc3c08a675bcd', '249ee5ebc00b1fe439072f87a103b99d', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=start', 'hilfe/startseite/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('9ba8be21e759c0fb18ed36d2d12b34ad', '281a927fd36ff57e9d5cd21a6ad83145', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=help', 'hilfe/hilfe/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('39e6808919a5c2cfea2c2733d9de60f8', '310a5b38352aecfde5a28d30ecaf2cb2', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=compare', 'hilfe/mein-produktvergleich/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('61c5d6965b480012aabb3a6701254b75', '347333f119c147545287d02ff8954b8e', 'oxbaseshop', 1, 'index.php?cl=recommlist', 'en/Recomendation-Lists/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('4e0a1c1634b39b25dc36fd17e72046f0', '352dd404dd24e284e60006ce1da9a3ae', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=vendorlist', 'hilfe/nach-hersteller/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('23e2b46bdc9cd26023fd8020c5dff9a2', '367a5b40fadd01331bb3a12e5cb0bef9', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=review', 'en/help/product-review/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('e56acdd347b0516f68a0afdd811e2382', '3a41965fb36fb45df7f4f9a4377f6364', 'oxbaseshop', 1, 'index.php?cl=newsletter', 'en/newsletter/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('ab459c0dc911137e9cc024d9fba5a68b', '3bdd64bd8073d044d8fd60334ed9b725', 'oxbaseshop', 1, 'index.php?cl=start', 'en/home/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('be0df35ba37af88c6c09527f1e2d7180', '3c8229b33f16cfe0fc5db6c8177c18bb', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_noticelist', 'hilfe/mein-merkzettel/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('d35db2901a428b971c0d7d53d64c4f77', '44fec8ed8396c63e0d958ae78996d1e4', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account', 'en/help/my-account/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('efaf9266cf7de3a8c84cea167285eb83', '4a70a4cd11d63fdce96fbe4ba8e714e6', 'oxbaseshop', 1, 'index.php?cnid=oxmore&amp;cl=alist', 'en/more/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('9ba8be21e759c0fb18ed36d2d12b34ad', '4ac8d6f8819076dd8fac958a264e04ff', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=help', 'en/help/help/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('7ae7ab3bc27e81b92a56a54a7e02fdec', '4baf9bd95ca982018c1ec6527669aef7', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=basket', 'en/help/cart/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('efaf9266cf7de3a8c84cea167285eb83', '4d3d4d70b09b5d2bd992991361374c67', 'oxbaseshop', 0, 'index.php?cnid=oxmore&amp;cl=alist', 'mehr/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('18f64cbbc296a32fb84b3bbe31dfed09', '510fef34e5d9b86f6a77af949d15950e', 'oxbaseshop', 1, 'index.php?cl=account', 'en/my-account/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('ace1e168a1e8183a2aa79c2243171a29', '5668048844927ca2767140c219e04efc', 'oxbaseshop', 1, 'index.php?cl=account_user', 'en/my-address/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('63914909be6a4f61f7744b87876c20ee', '585f263995b6a8216d1d49c10bdea22f', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=contact', 'hilfe/kontakt/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('0f8b225c4476bfb9f9f06072591caf0c', '5a0b0a570076f900c44f160a797832ef', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_newsletter', 'en/help/newsletter-settings/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('0563ce7f6a400737ce0e1c2b2c733e49', '5cc081514a72b0ce3d7eec4fb1e6f1dd', 'oxbaseshop', 1, 'index.php?cl=forgotpwd', 'en/forgot-password/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('f6bd7f77caae70afad276584caa6450a', '5d752e9e8302eeb21df24d1aee573234', 'oxbaseshop', 0, 'index.php?cl=wishlist', 'wunschzettel/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('002a90a450bc0eba234f80b0b27636ff', '5e82443daf55ddc38b24aefe8ec0daa5', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=newsletter', 'en/help/newsletter/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('6d9b5b3ee58bca1bd7be188f71e80ef2', '5eb126725ba500e6bbf1aecaa876dc48', 'oxbaseshop', 1, 'index.php?cl=review', 'en/product-review/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('8f44d31988a8962c87e3a0b7dda28ea2', '5f58b1965cb91c573ecd3d34c784c2e4', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_user', 'hilfe/rechnungs-und-liefereinstellungen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('7189adecf13cac2a3e0a085aa8c276d6', '6203915d115d00aacaa2a9ea3bc67cda', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=register', 'en/help/open-account/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('57cb6b2fafc870810cd48b8e1d28cf91', '63794023f46c56d329e2ee6a1462e8c2', 'oxbaseshop', 0, 'index.php?cl=tags', 'stichworte/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('8f44d31988a8962c87e3a0b7dda28ea2', '670524bc5a2b2334c83839396da5b10b', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_user', 'en/help/billings-and-shipping-settings/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('be0df35ba37af88c6c09527f1e2d7180', '6a1a92c6e19cb0923edc299fd7d0c19b', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_noticelist', 'en/help/my-wish-list/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('f560b18b547bca23752a154b45120980', '6b30b01fe01b80894efc0f29610e5215', 'oxbaseshop', 0, 'index.php?cl=account_password', 'mein-passwort/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('bde12a1bdd3b9c77bdc694a7de4c0dea', '6c3658516be12443e6778f253d9a6945', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=alist', 'hilfe/kategorien/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('f560b18b547bca23752a154b45120980', '6c890ac1255a99f8d1eb46f1193843d6', 'oxbaseshop', 1, 'index.php?cl=account_password', 'en/my-password/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('4e0a1c1634b39b25dc36fd17e72046f0', '6d01ef2701d240d4b80250d176cc6ffa', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=vendorlist', 'en/help/by-manufacturer/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('730781c5e392155012ef2f055eedce00', '74a7a5557c373f3a9b8268714abfd89c', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_password', 'en/help/my-password/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('04abcb465a8d3a4441df4c480838d23d', '7685924d3f3fb7e9bda421c57f665af4', 'oxbaseshop', 1, 'index.php?cl=contact', 'en/contact/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('d12b7badee1037e7c1a5a7a245a14e11', '7c8aa72aa16b7d4a859b22d8b8328412', 'oxbaseshop', 0, 'index.php?cl=guestbook', 'gaestebuch/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('0f8b225c4476bfb9f9f06072591caf0c', '7ea6f0334b42ae9efcf7272cc6c5d8bd', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_newsletter', 'hilfe/newslettereinstellungen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('2e17757c0aaf8ed9ef2ba30317fa1faf', '82dd672d68d8f6c943f98ccdaecda691', 'oxbaseshop', 0, 'index.php?cl=suggest', 'empfehlen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('6c3129355426de70051cc3c08a675bcd', '8480daf667f0c1fe8dd5c4dd66955e10', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=start', 'en/help/home/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('d35db2901a428b971c0d7d53d64c4f77', '878b29f193adb05133109d82eb4d9a88', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account', 'hilfe/mein-konto/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('dd1a86d489a8b312737f59ab2cac0eb4', '878fb0ccc48bca3194436cc19c3200e1', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=details', 'hilfe/produktdetails/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('18f64cbbc296a32fb84b3bbe31dfed09', '89c5e6bf1b5441599c4815281debe8df', 'oxbaseshop', 0, 'index.php?cl=account', 'mein-konto/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('7ae7ab3bc27e81b92a56a54a7e02fdec', '8db8366788784126550dfc537f794190', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=basket', 'hilfe/warenkorb/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('05c0f9a36dc4eaf3df528f0da18664d8', '8e7ebaebb0a810576453082e1f8f2fa3', 'oxbaseshop', 1, 'index.php?cl=account_recommlist', 'en/my-listmania-list/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('343c043546b3d653647e75d2e246ce94', '9508bb0d70121d49e4d4554c5b1af81d', 'oxbaseshop', 0, 'index.php?cl=links', 'links/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('a0ee4fb33f618ef2bef24e20d12d572f', '968c80a5b47daa4a4c7e5f1ac7c1925a', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_recommlist', 'hilfe/meine-lieblingslisten/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('39e6808919a5c2cfea2c2733d9de60f8', '9fc9811fd88eaf807b1036e07dbfa85c', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=compare', 'en/help/my-product-comparison/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('610f7fc243c7409cb5448b30029431fe', '9ff5c21cbc84dbfe815013236e132baf', 'oxbaseshop', 1, 'index.php?cl=account_order', 'en/order-history/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('7189adecf13cac2a3e0a085aa8c276d6', 'a1322f6c88d2e16960433bbeb1c6c3da', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=register', 'hilfe/konto-eroeffnen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('98964bf04c7edae2d658c5f3b3233ca1', 'a1b330b85c6f51fd9b50b1eb19707d84', 'oxbaseshop', 1, 'index.php?cl=register', 'en/open-account/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('7bc8a506bbca225a2f95b6eac66020bf', 'a24b03f1a3f73c325a7647e6039e2359', 'oxbaseshop', 0, 'index.php?cl=account_noticelist', 'mein-merkzettel/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('61c5d6965b480012aabb3a6701254b75', 'a4e5995182ade3cf039800c0ec2d512d', 'oxbaseshop', 0, 'index.php?cl=recommlist', 'Empfehlungslisten/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('71db5a32d74e4095f390ce401f158a14', 'a626f6f9942488da7ab0939c3585e58b', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=guestbook', 'hilfe/gaestebuch/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('ace1e168a1e8183a2aa79c2243171a29', 'a7d5ab5a4e87693998c5aec066dda6e6', 'oxbaseshop', 0, 'index.php?cl=account_user', 'meine-adressen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('0563ce7f6a400737ce0e1c2b2c733e49', 'a9afb500184c584fb5ab2ad9b4162e96', 'oxbaseshop', 0, 'index.php?cl=forgotpwd', 'passwort-vergessen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('98964bf04c7edae2d658c5f3b3233ca1', 'acddcfef9c25bcae3b96eb00669541ff', 'oxbaseshop', 0, 'index.php?cl=register', 'konto-eroeffnen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('6ba1f9c600305c7c92573cb6d1555797', 'af3d70b061ae02da3d6ce248c497dc32', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=links', 'hilfe/links/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('002a90a450bc0eba234f80b0b27636ff', 'b61bd555494657d24f309799e30827ec', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=newsletter', 'hilfe/newsletter/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('f6bd7f77caae70afad276584caa6450a', 'b93b703d313e89662773cffaab750f1d', 'oxbaseshop', 1, 'index.php?cl=wishlist', 'en/gift-registry/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('05c0f9a36dc4eaf3df528f0da18664d8', 'baa3b653a618696b06c70a6dda95ebde', 'oxbaseshop', 0, 'index.php?cl=account_recommlist', 'meine-lieblingslisten/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('9a545b8f4ebd5c1458b5aae08812b60f', 'c2d486a828d484a863b69e53078de31f', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=wishlist', 'en/help/gift-registry/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('6d9b5b3ee58bca1bd7be188f71e80ef2', 'cc28156a4f728c1b33e7e02fd846628e', 'oxbaseshop', 0, 'index.php?cl=review', 'bewertungen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('ab459c0dc911137e9cc024d9fba5a68b', 'ccca27059506a916fb64d673a5dd676b', 'oxbaseshop', 0, 'index.php?cl=start', 'startseite/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('bde12a1bdd3b9c77bdc694a7de4c0dea', 'd7abe1fb6fb1e9e6003b45844b0c0f09', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=alist', 'en/help/categories/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('c4345f215c2f7b50549ca896b5c17f13', 'da3c1a52ac30056f0e020469a5d35d99', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=forgotpwd', 'hilfe/passwort-vergessen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('57cb6b2fafc870810cd48b8e1d28cf91', 'da6c5523f7c91d108cadb0be7757c27f', 'oxbaseshop', 1, 'index.php?cl=tags', 'en/tags/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('a0ee4fb33f618ef2bef24e20d12d572f', 'dd78cb9b34d9cd30f8a848005c402ba6', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_recommlist', 'en/help/my-listmania-list/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('d12b7badee1037e7c1a5a7a245a14e11', 'ded4f3786c6f4d6d14e3034834ebdcaf', 'oxbaseshop', 1, 'index.php?cl=guestbook', 'en/guestbook/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('bc8df5696a42291e47f47478442ce2a8', 'e098f2c231bce2c60473c04f4cded5dd', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=suggest', 'en/help/recommend/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('057ef382f23bdbb84991d049af2baba9', 'e0dd29a75947539da6b1924d31c9849f', 'oxbaseshop', 0, 'index.php?cl=compare', 'mein-produktvergleich/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('e56acdd347b0516f68a0afdd811e2382', 'e604233c5a2804d21ec0252a445e93d3', 'oxbaseshop', 0, 'index.php?cl=newsletter', 'newsletter/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('38efc02f0f6b6a6d54cfef1fcdb99d70', 'e6331d115f5323610c639ef2f0369f8a', 'oxbaseshop', 0, 'index.php?cl=basket', 'warenkorb/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('730781c5e392155012ef2f055eedce00', 'e6c20bf0d1d929f570f919f35a25bff1', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=account_password', 'hilfe/mein-passwort/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('c4345f215c2f7b50549ca896b5c17f13', 'e7d3640dc365932ea39a5845017451f1', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=forgotpwd', 'en/help/forgot-password/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('1a583d63681ba48d989bdfd0bea8ade7', 'e9c2c9ccc91911acd7e4e399c2c8838d', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=account_wishlist', 'en/help/my-gift-registry/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('610f7fc243c7409cb5448b30029431fe', 'eb692d07ec8608d943db0a3bca29c4ce', 'oxbaseshop', 0, 'index.php?cl=account_order', 'bestellhistorie/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('38efc02f0f6b6a6d54cfef1fcdb99d70', 'ecaf0240f9f46bbee5ffc6dd73d0b7f0', 'oxbaseshop', 1, 'index.php?cl=basket', 'en/cart/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('bc8df5696a42291e47f47478442ce2a8', 'ed33aefc08d7a8b31ad3dcb61ba5d1b5', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=suggest', 'hilfe/empfehlen/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('63914909be6a4f61f7744b87876c20ee', 'efbdcce791ae8fecc0a45ff7e1c92ca6', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=contact', 'en/help/contact/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('9a545b8f4ebd5c1458b5aae08812b60f', 'f156d24d4a67d1a00e3423d7381ebfe8', 'oxbaseshop', 0, 'index.php?cl=help&amp;page=wishlist', 'hilfe/wunschzettel-oxid/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('343c043546b3d653647e75d2e246ce94', 'f80ace8f87e1d7f446ab1fa58f275f4c', 'oxbaseshop', 1, 'index.php?cl=links', 'en/links/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('71db5a32d74e4095f390ce401f158a14', 'f8e48035979bf62e5bbc15504f9d81fa', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=guestbook', 'en/help/guestbook/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('04abcb465a8d3a4441df4c480838d23d', 'f9d1a02ab749dc360c4e21f21de1beaf', 'oxbaseshop', 0, 'index.php?cl=contact', 'kontakt/', 'static', 0, '', '', 0, '');
INSERT INTO `oxseo` VALUES ('6ba1f9c600305c7c92573cb6d1555797', 'ffd0f3c469cdb59bb32a4e647152dca7', 'oxbaseshop', 1, 'index.php?cl=help&amp;page=links', 'en/help/links/', 'static', 0, '', '', 0, '');

#
# Table structure for table `oxseohistory`
# for tracking old SEO urls
# created 2008-05-21
#

DROP TABLE IF EXISTS `oxseohistory`;

CREATE TABLE `oxseohistory` (
  `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXIDENT` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXLANG` int(2) NOT NULL default '0',
  `OXHITS` bigint(20) NOT NULL default '0',
  `OXINSERT` timestamp NULL default NULL,
  `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`OXIDENT`,`OXSHOPID`,`OXLANG`),
  KEY `search` (`OXOBJECTID`,`OXSHOPID`,`OXLANG`)
) ENGINE=InnoDB;

#
# Table structure for table `oxseologs`
# for tracking untranslatable to SEO format non SEO urls
# created 2008-05-21
#

DROP TABLE IF EXISTS `oxseologs`;

CREATE TABLE IF NOT EXISTS `oxseologs` (
  `OXSTDURL` text NOT NULL,
  `OXIDENT` char(32) character set latin1 collate latin1_general_ci NOT NULL,
  `OXSHOPID` char(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  `OXLANG` int(11) NOT NULL,
  `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`OXIDENT`,`OXSHOPID`,`OXLANG`)
) ENGINE=InnoDB;

#
# Table structure for table `oxmediaurls`
# for storing extended file urls
# created 2008-06-25
#

DROP TABLE IF EXISTS `oxmediaurls`;

CREATE TABLE `oxmediaurls` (
 `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
 `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL,
 `OXURL` varchar(255) NOT NULL,
 `OXDESC` varchar(255) NOT NULL,
 `OXDESC_1` varchar(255) NOT NULL,
 `OXDESC_2` varchar(255) NOT NULL,
 `OXDESC_3` varchar(255) NOT NULL,
 `OXISUPLOADED` int(1) NOT NULL default '0',
 PRIMARY KEY ( `OXID` ) ,
 INDEX ( `OXOBJECTID` )
) ENGINE = MYISAM ;
