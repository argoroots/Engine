-- phpMyAdmin SQL Dump
-- version 3.3.0
-- http://www.phpmyadmin.net
--
-- Host: d10160.mysql.zone.ee
-- Generation Time: Apr 06, 2010 at 03:17 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'd10160sd7727'
--

-- --------------------------------------------------------

--
-- Table structure for table 'e_contents'
--

CREATE TABLE e_contents (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  create_time int(10) NOT NULL,
  delete_time int(10) NOT NULL,
  topic_id int(10) unsigned NOT NULL,
  user_id int(10) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8_estonian_ci NOT NULL,
  content text COLLATE utf8_estonian_ci NOT NULL,
  ip varchar(15) COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (id),
  KEY topic_id (topic_id),
  KEY user_id (user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_groups'
--

CREATE TABLE e_groups (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  create_time int(10) NOT NULL,
  edit_time int(10) NOT NULL,
  `name` varchar(25) COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_migration'
--

CREATE TABLE e_migration (
  e_id int(10) unsigned NOT NULL,
  pun_id int(10) unsigned NOT NULL,
  `type` varchar(10) COLLATE utf8_estonian_ci NOT NULL,
  KEY e_id (e_id),
  KEY pun_id (pun_id),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_openids'
--

CREATE TABLE e_openids (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(11) unsigned NOT NULL,
  openid varchar(256) COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY openid (openid(255)),
  KEY user_id (user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_permissions'
--

CREATE TABLE e_permissions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  topic_id int(10) unsigned NOT NULL,
  group_id int(10) unsigned NOT NULL,
  `view` tinyint(4) unsigned NOT NULL,
  add_child tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY topic_group (topic_id,group_id),
  KEY group_id (group_id),
  KEY topic_id (topic_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_templates'
--

CREATE TABLE e_templates (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  create_time int(10) NOT NULL,
  edit_time int(10) NOT NULL,
  `name` varchar(25) COLLATE utf8_estonian_ci NOT NULL,
  template_file varchar(25) COLLATE utf8_estonian_ci NOT NULL,
  menu_selected varchar(20) COLLATE utf8_estonian_ci NOT NULL,
  child_sort_order enum('DATE','DATE_DESC','ORDINAR','ORDINAR_DESC') COLLATE utf8_estonian_ci NOT NULL,
  child_count int(10) unsigned NOT NULL,
  child_level int(10) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_topics'
--

CREATE TABLE e_topics (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  create_time int(10) NOT NULL,
  edit_time int(10) NOT NULL,
  parent_topic_id int(10) unsigned NOT NULL,
  template_id int(10) unsigned NOT NULL,
  ordinar int(10) unsigned NOT NULL,
  url varchar(100) COLLATE utf8_estonian_ci NOT NULL,
  path varchar(50) COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY url (url),
  KEY parent_topic_id (parent_topic_id),
  KEY template_id (template_id),
  KEY path (path)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'e_users'
--

CREATE TABLE e_users (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  create_time int(10) NOT NULL,
  edit_time int(10) NOT NULL,
  group_id int(10) unsigned NOT NULL,
  username varchar(200) COLLATE utf8_estonian_ci NOT NULL,
  email varchar(100) COLLATE utf8_estonian_ci NOT NULL,
  last_visit_time int(10) NOT NULL,
  activation_key varchar(32) COLLATE utf8_estonian_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email),
  KEY group_id (group_id),
  KEY activation_key (activation_key)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `e_contents`
--
ALTER TABLE `e_contents`
  ADD CONSTRAINT e_contents_ibfk_1 FOREIGN KEY (topic_id) REFERENCES e_topics (id),
  ADD CONSTRAINT e_contents_ibfk_2 FOREIGN KEY (user_id) REFERENCES e_users (id);

--
-- Constraints for table `e_openids`
--
ALTER TABLE `e_openids`
  ADD CONSTRAINT e_openids_ibfk_1 FOREIGN KEY (user_id) REFERENCES e_users (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `e_permissions`
--
ALTER TABLE `e_permissions`
  ADD CONSTRAINT e_permissions_ibfk_4 FOREIGN KEY (group_id) REFERENCES e_groups (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT e_permissions_ibfk_3 FOREIGN KEY (topic_id) REFERENCES e_topics (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `e_topics`
--
ALTER TABLE `e_topics`
  ADD CONSTRAINT e_topics_ibfk_2 FOREIGN KEY (template_id) REFERENCES e_templates (id);

--
-- Constraints for table `e_users`
--
ALTER TABLE `e_users`
  ADD CONSTRAINT e_users_ibfk_1 FOREIGN KEY (group_id) REFERENCES e_groups (id);
