-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_metamodel_attribute`
--

CREATE TABLE `tl_metamodel_attribute` (
  `get_geo` varchar(255) NOT NULL default '',
  `get_land` varchar(255) NOT NULL default '',
  `single_attr_id` varchar(255) NOT NULL default '',
  `first_attr_id` varchar(255) NOT NULL default '',
  `second_attr_id` varchar(255) NOT NULL default '',
  `datamode` varchar(255) NOT NULL default '',
  `lookupservice` text NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
