
-- 
-- Table structure for table `cms_AuditTrail`
-- 

CREATE TABLE `cms_AuditTrail` (
  `nAuditTrailId` bigint(20) NOT NULL auto_increment,
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `nUserId` bigint(20) NOT NULL default '0',
  `cPage` varchar(255) NOT NULL default '',
  `cData` mediumtext NOT NULL,
  `cIPAddress` varchar(20) NOT NULL default '',
  `cSessionId` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`nAuditTrailId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Content`
-- 

CREATE TABLE `cms_Content` (
  `nContentId` bigint(20) NOT NULL auto_increment,
  `cContentKey` varchar(50) NOT NULL default '',
  `nTemplateId` bigint(20) NOT NULL default '0',
  `cFunction` varchar(50) NOT NULL default '',
  `nContentTypeId` bigint(20) NOT NULL default '0',
  `cTitle` varchar(80) NOT NULL default '',
  `cFile` varchar(255) NOT NULL default '',
  `cBody` mediumtext NOT NULL,
  `cApproved` char(1) NOT NULL default '',
  `dStart` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEnd` datetime NOT NULL default '0000-00-00 00:00:00',
  `nUserAdded` bigint(20) NOT NULL default '0',
  `nUserEdited` bigint(20) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nContentId`),
  KEY `cContentKey` (`cContentKey`),
  KEY `nTemplateId` (`nTemplateId`),
  KEY `nContentTypeId` (`nContentTypeId`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_ContentData`
-- 

CREATE TABLE `cms_ContentData` (
  `nContentDataId` bigint(20) NOT NULL auto_increment,
  `nContentId` bigint(20) NOT NULL default '0',
  `nPropertyId` bigint(20) NOT NULL default '0',
  `nDataInt` int(11) NOT NULL default '0',
  `nDataBigInt` bigint(20) NOT NULL default '0',
  `dDataDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `bDataBoolean` int(11) NOT NULL default '0',
  `nDataFloat` float NOT NULL default '0',
  `cDataVarchar` varchar(255) NOT NULL default '',
  `cDataMediumText` mediumtext NOT NULL,
  `bDataBlob` blob NOT NULL,
  PRIMARY KEY  (`nContentDataId`),
  KEY `nContentId` (`nContentId`),
  KEY `nPropertyId` (`nPropertyId`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_ContentSecurity`
-- 

CREATE TABLE `cms_ContentSecurity` (
  `nContentSecurityId` bigint(20) NOT NULL auto_increment,
  `nUserTypeId` bigint(20) NOT NULL default '0',
  `nContentTypeId` bigint(20) NOT NULL default '0',
  `cView` char(1) NOT NULL default '',
  `cAdd` char(1) NOT NULL default '',
  `cEdit` char(1) NOT NULL default '',
  `cDelete` char(1) NOT NULL default '',
  `cApprove` char(1) NOT NULL default '',
  PRIMARY KEY  (`nContentSecurityId`),
  KEY `nUserTypeId` (`nUserTypeId`),
  KEY `nContentTypeId` (`nContentTypeId`),
  KEY `cView` (`cView`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_ContentType`
-- 

CREATE TABLE `cms_ContentType` (
  `nContentTypeId` bigint(20) NOT NULL auto_increment,
  `cContentTypeName` varchar(50) NOT NULL default '',
  `cFunction` varchar(50) NOT NULL default '',
  `cFile` varchar(255) NOT NULL default '',
  `cApprovalRequired` char(1) NOT NULL default '',
  PRIMARY KEY  (`nContentTypeId`),
  KEY `cApprovalRequired` (`cApprovalRequired`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_ContentTypeProperties`
-- 

CREATE TABLE `cms_ContentTypeProperties` (
  `nContentTypePropertyId` bigint(20) NOT NULL auto_increment,
  `nContentTypeId` bigint(20) NOT NULL default '0',
  `cPropertyName` varchar(255) NOT NULL default '',
  `cPropertyDescription` varchar(255) NOT NULL default '',
  `cDataType` varchar(20) NOT NULL default '',
  `nSortIndex` int(11) NOT NULL default '0',
  `bMandatory` int(11) NOT NULL default '0',
  `bHidden` int(11) NOT NULL default '0',
  `cInputMask` varchar(255) NOT NULL default '',
  `bUnique` int(11) NOT NULL default '0',
  PRIMARY KEY  (`nContentTypePropertyId`),
  KEY `nContentTypeId` (`nContentTypeId`),
  KEY `cDataType` (`cDataType`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Document`
-- 

CREATE TABLE `cms_Document` (
  `nDocumentId` bigint(20) NOT NULL auto_increment,
  `nDocumentTypeId` bigint(20) NOT NULL default '0',
  `nParentId` bigint(20) NOT NULL default '0',
  `nVersion` int(11) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  `nAddedBy` bigint(20) NOT NULL default '0',
  `nEditedBy` bigint(20) NOT NULL default '0',
  `nRepositoryId` bigint(20) NOT NULL default '0',
  `cFilename` varchar(255) NOT NULL default '',
  `cOriginalFilename` varchar(255) NOT NULL default '',
  `nFilesize` bigint(20) NOT NULL default '0',
  `cFullText` longtext NOT NULL,
  PRIMARY KEY  (`nDocumentId`),
  KEY `nDocumentTypeId` (`nDocumentTypeId`),
  FULLTEXT KEY `cFullText` (`cFullText`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_DocumentData`
-- 

CREATE TABLE `cms_DocumentData` (
  `nDocumentDataId` bigint(20) NOT NULL auto_increment,
  `nDocumentId` bigint(20) NOT NULL default '0',
  `nPropertyId` bigint(20) NOT NULL default '0',
  `nDataInt` int(11) NOT NULL default '0',
  `nDataBigInt` bigint(20) NOT NULL default '0',
  `dDataDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `bDataBoolean` int(11) NOT NULL default '0',
  `nDataFloat` float NOT NULL default '0',
  `cDataVarchar` varchar(255) NOT NULL default '',
  `cDataMediumText` mediumtext NOT NULL,
  `bDataBlob` blob NOT NULL,
  PRIMARY KEY  (`nDocumentDataId`),
  KEY `nDocumentId` (`nDocumentId`),
  KEY `nPropertyId` (`nPropertyId`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_DocumentType`
-- 

CREATE TABLE `cms_DocumentType` (
  `nDocumentTypeId` bigint(20) NOT NULL auto_increment,
  `cName` varchar(255) NOT NULL default '',
  `cDescription` mediumtext NOT NULL,
  `nRepositoryId` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`nDocumentTypeId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_DocumentTypeProperties`
-- 

CREATE TABLE `cms_DocumentTypeProperties` (
  `nDocumentTypePropertyId` bigint(20) NOT NULL auto_increment,
  `nDocumentTypeId` bigint(20) NOT NULL default '0',
  `cPropertyName` varchar(255) NOT NULL default '',
  `cPropertyDescription` varchar(255) NOT NULL default '',
  `cDataType` varchar(20) NOT NULL default '',
  `nSortIndex` int(11) NOT NULL default '0',
  `bMandatory` int(11) NOT NULL default '0',
  `bHidden` int(11) NOT NULL default '0',
  `cInputMask` varchar(255) NOT NULL default '',
  `bUnique` int(11) NOT NULL default '0',
  PRIMARY KEY  (`nDocumentTypePropertyId`),
  KEY `nDocumentTypeId` (`nDocumentTypeId`),
  KEY `cDataType` (`cDataType`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_DocumentTypeSecurity`
-- 

CREATE TABLE `cms_DocumentSecurity` (
  `nDocumentSecurityId` bigint(20) NOT NULL auto_increment,
  `nDocumentTypeId` bigint(20) NOT NULL default '0',
  `nUserTypeId` bigint(20) NOT NULL default '0',
  `cView` char(1) NOT NULL default '0',
  `cAdd` char(1) NOT NULL default '0',
  `cEdit` char(1) NOT NULL default '0',
  `cDelete` char(1) NOT NULL default '0',
  `cReplace` char(1) NOT NULL default '0',
  PRIMARY KEY  (`nDocumentTypeSecurityId`),
  KEY `nDocumentTypeId` (`nDocumentTypeId`),
  KEY `nUserTypeId` (`nUserTypeId`),
  KEY `cView` (`cView`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_MenuItems`
-- 

CREATE TABLE `cms_MenuItems` (
  `nMenuItemId` bigint(20) NOT NULL auto_increment,
  `nPageId` bigint(20) NOT NULL default '0',
  `nIndex` bigint(20) NOT NULL default '0',
  `nLinkedPageId` bigint(20) NOT NULL default '0',
  `cSection` varchar(50) NOT NULL default '',
  `cTitle` varchar(80) NOT NULL default '',
  `cCaption` mediumtext NOT NULL,
  `nUserAdded` bigint(20) NOT NULL default '0',
  `nUserEdited` bigint(20) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nMenuItemId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_PageContent`
-- 

CREATE TABLE `cms_PageContent` (
  `nPageContentId` bigint(20) NOT NULL auto_increment,
  `nPageId` bigint(20) NOT NULL default '0',
  `cContentKey` varchar(50) NOT NULL default '0',
  `nTemplateId` bigint(20) NOT NULL default '0',
  `nTemplateElementId` tinyint(4) NOT NULL default '0',
  `nIndex` bigint(20) NOT NULL default '0',
  `nUserAdded` bigint(20) NOT NULL default '0',
  `nUserEdited` bigint(20) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nPageContentId`),
  KEY `nPageId` (`nPageId`),
  KEY `cContentKey` (`cContentKey`),
  KEY `nTemplateId` (`nTemplateId`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_PageData`
-- 

CREATE TABLE `cms_PageData` (
  `nPageDataId` bigint(20) NOT NULL auto_increment,
  `nPageId` bigint(20) NOT NULL default '0',
  `nPropertyId` bigint(20) NOT NULL default '0',
  `nDataInt` int(11) NOT NULL default '0',
  `nDataBigInt` bigint(20) NOT NULL default '0',
  `dDataDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `bDataBoolean` int(11) NOT NULL default '0',
  `nDataFloat` float NOT NULL default '0',
  `cDataVarchar` varchar(255) NOT NULL default '',
  `cDataMediumText` mediumtext NOT NULL,
  `bDataBlob` blob NOT NULL,
  PRIMARY KEY  (`nPageDataId`),
  KEY `nPageId` (`nPageId`),
  KEY `nPropertyId` (`nPropertyId`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_PageSecurity`
-- 

CREATE TABLE `cms_PageSecurity` (
  `nPageSecurityId` bigint(20) NOT NULL auto_increment,
  `nUserTypeId` bigint(20) NOT NULL default '0',
  `nPageTypeId` bigint(20) NOT NULL default '0',
  `cView` char(1) NOT NULL default '',
  `cAdd` char(1) NOT NULL default '',
  `cEdit` char(1) NOT NULL default '',
  `cDelete` char(1) NOT NULL default '',
  `cApprove` char(1) NOT NULL default '',
  PRIMARY KEY  (`nPageSecurityId`),
  KEY `nUserTypeId` (`nUserTypeId`),
  KEY `nPageTypeId` (`nPageTypeId`),
  KEY `cView` (`cView`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_PageType`
-- 

CREATE TABLE `cms_PageType` (
  `nPageTypeId` bigint(20) NOT NULL auto_increment,
  `cPageTypeName` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`nPageTypeId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_PageTypeProperties`
-- 

CREATE TABLE `cms_PageTypeProperties` (
  `nPageTypePropertyId` bigint(20) NOT NULL auto_increment,
  `nPageTypeId` bigint(20) NOT NULL default '0',
  `cPropertyName` varchar(255) NOT NULL default '',
  `cPropertyDescription` varchar(255) NOT NULL default '',
  `cDataType` varchar(20) NOT NULL default '',
  `nSortIndex` int(11) NOT NULL default '0',
  `bMandatory` int(11) NOT NULL default '0',
  `bHidden` int(11) NOT NULL default '0',
  `cInputMask` varchar(255) NOT NULL default '',
  `bUnique` int(11) NOT NULL default '0',
  PRIMARY KEY  (`nPageTypePropertyId`),
  KEY `nPageTypeId` (`nPageTypeId`),
  KEY `cDataType` (`cDataType`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Pages`
-- 

CREATE TABLE `cms_Pages` (
  `nPageId` bigint(20) NOT NULL auto_increment,
  `nPageTypeId` bigint(20) NOT NULL default '0',
  `cPageKey` varchar(64) NOT NULL default '',
  `cTitle` varchar(80) NOT NULL default '',
  `cNotes` mediumtext NOT NULL,
  `nTemplateId` bigint(20) NOT NULL default '0',
  `nUserAdded` bigint(20) NOT NULL default '0',
  `nUserEdited` bigint(20) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  `cCache` char(1) NOT NULL default '',
  `cApproved` char(1) NOT NULL default '',
  PRIMARY KEY  (`nPageId`),
  KEY `nPageTypeId` (`nPageTypeId`),
  KEY `cPageKey` (`cPageKey`),
  KEY `nTemplateId` (`nTemplateId`),
  KEY `cApproved` (`cApproved`)
) TYPE=MyISAM PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Repository`
-- 

CREATE TABLE `cms_Repository` (
  `nRepositoryId` bigint(20) NOT NULL auto_increment,
  `cPath` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`nRepositoryId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Statistics`
-- 

CREATE TABLE `cms_Statistics` (
  `nStatisticId` bigint(20) NOT NULL auto_increment,
  `nUserId` bigint(20) NOT NULL default '0',
  `cPageKey` tinytext NOT NULL,
  `dView` datetime NOT NULL default '0000-00-00 00:00:00',
  `cIPAddress` tinytext NOT NULL,
  `cSessionId` tinytext NOT NULL,
  PRIMARY KEY  (`nStatisticId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Templates`
-- 

CREATE TABLE `cms_Templates` (
  `nTemplateId` bigint(20) NOT NULL auto_increment,
  `cType` varchar(16) NOT NULL default '',
  `cTitle` varchar(30) NOT NULL default '',
  `cTemplate` mediumtext NOT NULL,
  `nVersion` bigint(20) NOT NULL default '0',
  `nUserAdded` bigint(20) NOT NULL default '0',
  `nUserEdited` bigint(20) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nTemplateId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_UserType`
-- 

CREATE TABLE `cms_UserType` (
  `nUserTypeId` bigint(20) NOT NULL auto_increment,
  `cUserTypeName` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`nUserTypeId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_UserTypeMember`
-- 

CREATE TABLE `cms_UserTypeMember` (
  `nUserTypeMemberId` bigint(20) NOT NULL auto_increment,
  `nUserId` bigint(20) NOT NULL default '0',
  `nUserTypeId` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`nUserTypeMemberId`),
  KEY `nUserId` (`nUserId`),
  KEY `nUserTypeId` (`nUserTypeId`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `cms_Users`
-- 

CREATE TABLE `cms_Users` (
  `nUserId` bigint(20) NOT NULL auto_increment,
  `cUsername` varchar(20) NOT NULL default '',
  `cPassword` varchar(255) NOT NULL default '',
  `cEMailAddress` varchar(50) NOT NULL default '',
  `cAdmin` char(1) NOT NULL default '',
  `nUserAdded` bigint(20) NOT NULL default '0',
  `dAdded` datetime NOT NULL default '0000-00-00 00:00:00',
  `nUserEdited` bigint(20) NOT NULL default '0',
  `dEdited` datetime NOT NULL default '0000-00-00 00:00:00',
  `dLastLogon` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`nUserId`)
) TYPE=MyISAM;


INSERT INTO `cms_Content` VALUES (1, 'home_content', 4, '', 1, 'Home Page Content', '', '<h1>Sample CMS Content</h1>\r\n\r\n<p>This is some sample content for the CMS Content Management System to help you along the way with looking around the software.</p>\r\n\r\nThe two pieces of content below have been included by filling metadata fields on the content...\r\n<ul>\r\n<li><!--comd:Lastname--></li>\r\n<li><!--comd:Firstname--></li>\r\n</ul>\r\n\r\nThe next two pieces of content have been filled from the metadata associated with the page...\r\n<ul>\r\n<li><!--pgmd:Colour--></li>\r\n<li><!--pgmd:Size--></li>\r\n</ul>\r\n\r\nIf you look at the foot of the page, you\\''ll also notice my name and age - they are place-holders in the content template using \\"ad-hoc\\" metadata... if you look at the content you\\''ll realise where they came from.\r\n\r\n[metadata]\r\nmyname=Jonathan\r\nmyage=31\r\n[/metadata]\r\n\r\nAnd the link to the PluggedOut webspace below here isn\\''t even in the content - it\\''s a part of the content template...', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 2, '0000-00-00 00:00:00', '2005-01-29 17:37:05');
INSERT INTO `cms_ContentData` VALUES (2, 1, 2, 0, 0, '0000-00-00 00:00:00', 0, 0, 'Beckett', '', '');
INSERT INTO `cms_ContentData` VALUES (3, 1, 3, 0, 0, '0000-00-00 00:00:00', 0, 0, 'Jonathan', '', '');
INSERT INTO `cms_ContentSecurity` VALUES (1, 1, 1, 'x', '', '', '', '');
INSERT INTO `cms_ContentSecurity` VALUES (2, 2, 1, 'x', 'x', 'x', 'x', 'x');
INSERT INTO `cms_ContentType` VALUES (1, 'Normal', '', '', '');
INSERT INTO `cms_ContentTypeProperties` VALUES (2, 1, 'Lastname', 'Last name or surname', 'cDataVarchar', 0, 0, 0, '', 0);
INSERT INTO `cms_ContentTypeProperties` VALUES (3, 1, 'Firstname', 'First name or christian name', 'cDataVarchar', 0, 0, 0, '', 0);
INSERT INTO `cms_DocumentSecurity` VALUES (3, 1, 2, 'x', 'x', 'x', 'x', 'x');
INSERT INTO `cms_DocumentSecurity` VALUES (4, 1, 1, 'x', '', '', '', '');
INSERT INTO `cms_DocumentType` VALUES (1, 'Normal', 'Normal Document Type', 1);
INSERT INTO `cms_DocumentTypeProperties` VALUES (5, 1, 'Test Field', 'Test field', 'cDataVarchar', 0, 0, 0, '', 0);
INSERT INTO `cms_PageContent` VALUES (1, 1, 'home_content', 0, 1, 1, 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_PageContent` VALUES (16, 8, 'home_content', 0, 1, 1, 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_PageData` VALUES (2, 1, 3, 0, 0, '0000-00-00 00:00:00', 0, 0, 'Blue', '', '');
INSERT INTO `cms_PageData` VALUES (3, 1, 4, 0, 0, '0000-00-00 00:00:00', 0, 0, 'Small', '', '');
INSERT INTO `cms_PageSecurity` VALUES (1, 1, 1, 'x', '', '', '', '');
INSERT INTO `cms_PageSecurity` VALUES (2, 2, 1, 'x', 'x', 'x', 'x', 'x');
INSERT INTO `cms_PageType` VALUES (1, 'Normal');
INSERT INTO `cms_PageTypeProperties` VALUES (3, 1, 'Colour', 'A test field', 'cDataVarchar', 0, 0, 0, '', 0);
INSERT INTO `cms_PageTypeProperties` VALUES (4, 1, 'Size', 'Another test field', 'cDataVarchar', 0, 0, 0, '', 0);
INSERT INTO `cms_Pages` VALUES (1, 1, 'home', 'Home Page', 'Default Home Page', 1, 2, 2, '0000-00-00 00:00:00', '2005-01-29 19:22:47', '','');
INSERT INTO `cms_Repository` VALUES (1, '/var/www/html/cms/repository');
INSERT INTO `cms_Templates` VALUES (1, 'page', 'Normal', '<html>\r\n<head>\r\n<title><!--PAGETITLE--></title>\r\n<link rel=\\''stylesheet\\'' href=\\''lib/cms_style.css\\'' type=\\''text/css\\''>\r\n</head>\r\n<body>\r\n<!--PAGECONTENT1-->\r\n</body>\r\n</html>', 0, 2, 2, '2005-01-22 22:24:11', '2005-01-29 11:07:08');
INSERT INTO `cms_Templates` VALUES (4, 'content', 'Test Content Template', '<!--BODY-->\r\n\r\n<p>Visit <a href=\\"http://www.pluggedout.com\\">PluggedOut</a> for updates to the CMS script!</p>\r\n\r\nPage by <!--ahmd:myname-->,<!--ahmd:myage-->', 0, 2, 2, '2005-01-27 19:22:13', '2005-01-27 19:27:42');
INSERT INTO `cms_UserType` VALUES (1, 'Guest');
INSERT INTO `cms_UserType` VALUES (2, 'Administrator');
INSERT INTO `cms_UserTypeMember` VALUES (1, 1, 1);
INSERT INTO `cms_UserTypeMember` VALUES (2, 2, 2);
INSERT INTO `cms_Users` VALUES (1, 'guest', '', '', '', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `cms_Users` VALUES (2, 'admin', '', '', 'x', 0, '0000-00-00 00:00:00', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
       
        