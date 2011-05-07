-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Host: 10.8.11.212
-- Generation Time: Apr 07, 2009 at 12:03 PM
-- Server version: 5.0.67
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `supersquirrel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL default '1',
  `first_name` varchar(75) NOT NULL,
  `middle_name` varchar(75) NOT NULL,
  `last_name` varchar(75) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` int(1) NOT NULL,
  `computer_id` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `created`, `modified`, `username`, `password`, `enabled`, `first_name`, `middle_name`, `last_name`, `email`, `type`, `computer_id`) VALUES(44, '2009-04-01 19:50:13', '2009-04-01 19:50:13', 'admin', '7c5fcbdf73b5b763f9e7c1dcf1769253', 1, 'admin', '', 'admin', 'admin@admin.com', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `admin_id` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `created`, `modified`, `title`, `content`, `admin_id`) VALUES(10, '2009-03-30 09:40:23', '2009-03-30 09:40:23', 'Welcome to the Voyager Incident Management System!', '<p>Welcome to the Voyager Incident Management System!</p>\r\n<p>If you are having a hard time finding your way around check out the user''s guide by clicking on the blue question mark in the upper right corner of every page.</p>\r\n<p>If you find any bugs please let us know so that we can immediately address the problem!</p>', 1);
INSERT INTO `articles` (`id`, `created`, `modified`, `title`, `content`, `admin_id`) VALUES(11, '2009-03-30 09:41:43', '2009-03-30 09:41:43', '3 new Knowledge Base articles have been added', '<p>Today, we have added 3 new knowledge base articles. We thought that these might help you with frequent computer problems you may be experiencing.</p>\r\n<p>If you have any suggestions or ideas for new KB articles let us know!</p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(8) NOT NULL auto_increment,
  `parent_id` int(8) NOT NULL,
  `enabled` int(1) NOT NULL default '1',
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(1, 0, 1, 'General', 'This category is used when an incident doesn''t belong in any other category.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(2, 0, 1, 'Hardware', 'This category is used for incidents related to hardware.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(3, 0, 1, 'Software', 'This category is used for incidents related to software.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(4, 2, 1, 'Printer', 'Printer and printing related tasks');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(5, 2, 1, 'Network', 'File access, network access, internet, etc.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(6, 2, 1, 'Video', 'display, video, and monitor issues');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(7, 2, 1, 'Peripheral', 'mouse, keyboard, webcam, etc.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(8, 3, 1, 'Microsoft Windows', 'operating system problems, such as starting up, shutting down, general Windows error messages, etc.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(9, 3, 1, 'Microsoft Office', 'Microsoft Office Excel, Powerpoint, Word, Visio, Outlook, etc.');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(10, 2, 1, 'Other', 'Other hardware related issues');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(11, 3, 1, 'Other', 'Other software related issues');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(13, 7, 1, 'Mouse', 'mouse related issues');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(14, 7, 1, 'Keyboard', 'keyboard related issues');
INSERT INTO `categories` (`id`, `parent_id`, `enabled`, `name`, `description`) VALUES(21, 1, 0, 'ttgtt', 'ggggt');

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

DROP TABLE IF EXISTS `computers`;
CREATE TABLE IF NOT EXISTS `computers` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `name` varchar(75) NOT NULL,
  `type` varchar(100) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `operating_system` varchar(50) NOT NULL,
  `memory` float NOT NULL,
  `hdd_space` float NOT NULL,
  `processor` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(1, '2009-02-28 13:17:53', '2009-03-28 11:42:54', 'dx456', 'Laptop', '514887578', 'Windows XP Pro', 512, 40, 'Intel Core 2 Duo');
INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(2, '2009-02-28 13:17:53', '2009-03-04 13:06:18', 'dx457', 'Desktop', '579863218', 'Windows XP Home', 1024, 80, 'Intel Celeron');
INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(3, '2009-02-28 13:17:53', '2009-02-28 13:17:53', 'dx458', 'Laptop', '698759632', 'Windows XP Pro', 512, 40, 'Intel Core 2 Duo');
INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(15, '2009-03-30 09:49:37', '2009-03-30 09:49:37', 'dx451', 'Desktop', '987314568', 'Windows Vista Ultimate 32-Bit', 3, 320, 'Intel Core 2 Duo');
INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(14, '2009-03-30 09:49:03', '2009-03-30 09:49:03', 'dx450', 'Desktop', '879852365', 'Windows Vista Ultimate 64-Bit', 16, 500, 'Intel Nehalem');
INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(13, '2009-03-04 12:52:59', '2009-03-04 12:52:59', 'dx459', 'laptop', '458796321', 'windows xp', 4, 60, 'Intel Core i7 920');
INSERT INTO `computers` (`id`, `created`, `modified`, `name`, `type`, `serial_number`, `operating_system`, `memory`, `hdd_space`, `processor`) VALUES(12, '2009-03-04 12:32:40', '2009-03-04 12:32:40', 'dx460', 'Netbook', '346587921', 'Ubuntu Linux 8.10', 1.5, 40, 'Intel Core 2 Quad');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

DROP TABLE IF EXISTS `incidents`;
CREATE TABLE IF NOT EXISTS `incidents` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `description` varchar(250) NOT NULL,
  `status` int(1) NOT NULL,
  `content` text NOT NULL,
  `category_id` int(8) NOT NULL,
  `priority` int(1) NOT NULL,
  `user_id` int(8) NOT NULL,
  `admin_id` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, `user_id`, `admin_id`) VALUES(26, '2009-04-04 08:57:17', '2009-04-04 09:08:17', 'Cannot access shared documents drive.', 2, '<p>I cannot access the shared documents drive located on //enterprise/accounting/sharedocs/.&nbsp; I have a mapped drive to it, but it is a error stating the network drive is not available.</p>', 5, 1, 7, 4);
INSERT INTO `incidents` (`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, `user_id`, `admin_id`) VALUES(27, '2009-04-04 08:58:12', '2009-04-04 08:58:12', 'Scroll wheel is not functional.', 0, '<p>The scroll wheel on my mouse no longer works.&nbsp; Can I get a replacement?</p>', 13, 0, 7, 0);
INSERT INTO `incidents` (`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, `user_id`, `admin_id`) VALUES(28, '2009-04-05 21:13:04', '2009-04-05 21:13:04', 'Quote for a new monitor', 1, '<p>How much woudl a new monitor cost?</p>', 1, 0, 7, 4);
INSERT INTO `incidents` (`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, `user_id`, `admin_id`) VALUES(29, '2009-04-06 14:05:11', '2009-04-06 14:08:32', 'Monitor isnt working right ', 2, '<p>akjglkajg;lajflkgjalkjf;la</p>', 1, 0, 8, 9);
INSERT INTO `incidents` (`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, `user_id`, `admin_id`) VALUES(30, '2009-04-06 15:28:36', '2009-04-06 15:33:33', 'Monitor has funny colors', 2, '<p>My monitor has funny colors. Please help.</p>', 1, 0, 8, 43);
INSERT INTO `incidents` (`id`, `created`, `modified`, `description`, `status`, `content`, `category_id`, `priority`, `user_id`, `admin_id`) VALUES(25, '2009-03-30 09:45:33', '2009-03-31 20:01:56', 'I can''t print PDF files anymore', 1, '<p>For some reason I can''t print PDF files anymore. It started after I upgraded to Adobe 7.9. There are some reports that need completed by Friday and I am unable to complete them.</p>\r\n<p>&nbsp;</p>\r\n<p>Test</p>', 4, 2, 7, 43);

-- --------------------------------------------------------

--
-- Table structure for table `kbentries`
--

DROP TABLE IF EXISTS `kbentries`;
CREATE TABLE IF NOT EXISTS `kbentries` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `admin_id` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `kbentries`
--

INSERT INTO `kbentries` (`id`, `created`, `modified`, `title`, `content`, `admin_id`) VALUES(6, '2009-03-30 09:36:44', '2009-03-30 09:36:44', 'How to calibrate your monitor.', '<ol>\r\n<li>Check the screen resolution. Choose the highest resolution available unless the text is too small. If you are using an LCD monitor, check the manual or box for the "native" resolution. Set your computer to this resolution.</li>\r\n<li>Verify that your <a title="Fix a Stuck Pixel on an LCD Monitor" href="http://www.wikihow.com/Fix-a-Stuck-Pixel-on-an-LCD-Monitor">computer monitor</a> is in high color or 24-bit mode. In Windows, check this by right clicking on your desktop and choosing <em>Graphic Properties.</em> On Mac, go to <em>Preferences</em>, then click on <em>Displays</em> and then choose <em>Colors:Millions</em>. If your display is in 16-bit color, there won''t be enough color depth for the calibration process.</li>\r\n<li>Let your monitor warm up for at least 15 (preferably 30) minutes before beginning the calibration.</li>\r\n<li>Make sure that no reflections, glare or strong, direct light reaches your screen. The room doesn''t have to be dark, but ambient light shouldn''t interfere with how you see what''s on the screen.</li>\r\n<li><a title="Print a Document" href="http://www.wikihow.com/Print-a-Document">Print</a> a test <a title="Add People to a Photo" href="http://www.wikihow.com/Add-People-to-a-Photo">photo</a> on a professional quality printer. Choose a daylight photo with a person who has natural skin tone and print it using the highest quality settings and top-quality glossy photo paper. Let it dry away from direct sunlight for a few hours so that the colors can set permanently.</li>\r\n<li>\r\n<div class="thumb tright">\r\n<div class="thumbinner" style="width: 182px;">\r\n<div class="thumbcaption"></div>\r\n</div>\r\n</div>\r\n</li>\r\n<li>Open the image file that you just printed.</li>\r\n<li>Place the printed photo right next to the original image on the screen and compare.</li>\r\n<li>Adjust the brightness, contrast, and color levels (red, green, blue) on your monitor until the image on the screen resembles the printed photo as closely as possible. This takes time and a good eye for color. Continue to the next step if you''d like to use software to calibrate your monitor.</li>\r\n<li>Use basic software such as Adobe Gamma (if you have <a title="Replace Text in Adobe Photoshop" href="http://www.wikihow.com/Replace-Text-in-Adobe-Photoshop">Adobe Photoshop 7</a> or below installed), <a class="external text" title="http://www.quickgamma.de/indexen.html" rel="nofollow" href="http://www.quickgamma.de/indexen.html">QuickGamma</a> (which is free), <a class="external text" title="http://www.apple.com/macosx/features/colorsync/" rel="nofollow" href="http://www.apple.com/macosx/features/colorsync/">Apple ColorSync</a>, or <a class="external text" title="http://www.pcbypaul.com/software/monica.html" rel="nofollow" href="http://www.pcbypaul.com/software/monica.html">Monica</a> for Linux to calibrate your monitor. To access Adobe Gamma, click "Start," "Settings" and "Control Panel." For all the software, follow the step by step instructions to perform the calibration. These will provide a basic calibration for, say, casual Photoshop users who don''t print a lot of photographs.</li>\r\n<li>Purchase specialized software used in conjunction with a colorimeter (a device that reads the actual color values produced by your monitor) if color accuracy is vital to your profession. Some calibration systems worth looking into are ColorVision Spyder2, the ColorVision Color Plus (great for home systems), Monaco Systems MonacoOPTIX, and Gretag Macbeth Eye-One Display.</li>\r\n<li>Calibrate your monitor every 2 to 4 weeks for optimum visual accuracy.</li>\r\n</ol>', 1);
INSERT INTO `kbentries` (`id`, `created`, `modified`, `title`, `content`, `admin_id`) VALUES(7, '2009-03-30 09:37:46', '2009-03-30 09:37:46', 'Fix a stuck pixel on your LCD monitor', '<h3><span>Software Method</span></h3>\r\n<ol>\r\n<li>Try running pixel fixing software (see Sources and Citations). Stuck pixels can often be re-energized by rapidly turning them on and off. If this fails, complete the following steps.</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<div><a name="Pressure_Method"></a>\r\n<h3><span>Pressure Method</span></h3>\r\n<ol>\r\n<li>Turn off your computer''s monitor.</li>\r\n<li>Get yourself a damp washcloth, so that you don''t scratch your screen.</li>\r\n<li>Take a household pen, pencil, screwdriver, or some other sort of instrument with a focused, but relatively dull, point. A very good tool would be a PDA stylus.</li>\r\n<li>Fold the washcloth to make sure you don''t accidentally puncture it and scratch the screen.</li>\r\n<li>Apply pressure through the folded washcloth with the instrument to exactly where the stuck pixel is. Try not to put pressure anywhere else, as this may make more stuck pixels.</li>\r\n<li>While applying pressure, turn on your computer and screen.</li>\r\n<li>Remove pressure and the stuck pixel should be gone. This works as the liquid in the liquid crystal has not spread into each little pixel. This liquid is used with the backlight on your monitor, allowing different amounts of light through, which creates the different colors.</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n</div>\r\n<p><a name="Tapping_Method"></a></p>\r\n<h3><span>Tapping Method</span></h3>\r\n<ol>\r\n<li>Turn on the computer and LCD screen.</li>\r\n<li>Display a black image, which will show the stuck pixel very clearly against the background. (It is very important that you are showing a black image and not just a blank signal, as you need the backlighting of the LCD to be illuminating the back of the panel).</li>\r\n<li>Find a pen with a rounded end. A Sharpie marker with the cap on should be fine for this.</li>\r\n<li>Use the rounded end of the pen to gently tap where the stuck pixel is - not too hard to start with, just enough to see a quick white glow under the point of contact. If you didn''t see a white glow, then you didn''t tap hard enough, so use just slightly more pressure this time.</li>\r\n<li>Start tapping gently. Increase the pressure on the taps gradually for 5-10 taps until the pixel rights itself.</li>\r\n<li>Display a white image (an empty text document is good for this) to verify that you haven''t accidentally caused more damage than you fixed.</li>\r\n</ol>', 1);
INSERT INTO `kbentries` (`id`, `created`, `modified`, `title`, `content`, `admin_id`) VALUES(8, '2009-03-30 09:38:43', '2009-03-30 09:38:43', 'Clean your keyboard', '<ol>\r\n<li>Shut down the computer and detach the keyboard connector before any cleaning procedure. Do not remove or connect a keyboard while the computer is running. Doing so may damage the machine, especially a non-USB based keyboard.</li>\r\n<li>\r\n<div class="floatright">For a quick cleaning, turn the keyboard upside down and use a can of compressed air to blow out any foreign matter. Be sure to do this in a location where falling debris can be cleaned up easily. Turn the keyboard upside down and tap on it a few times. You should see a bit of dirt fall out. Change the angle and tap harder to make most of it get out.</div>\r\n</li>\r\n<li>Clean the sides of the keys with a cotton swab dipped in isopropyl alcohol.</li>\r\n<li>\r\n<div class="floatright">For a more thorough cleaning, remove all of the keys. Do this by gently prying up each key with a small screwdriver or a similar lever. When the keys have been removed, blow out any debris with compressed air. With a moist (but not wet) cloth, lightly swab all surfaces. Do not allow any type of fluid to enter the keyboard.</div>\r\n</li>\r\n<li>Clean the individual keys and place them back in the keyboard.</li>\r\n</ol>\r\n<p><a id="Alternate_Method" name="Alternate_Method"></a></p>\r\n<h2><span>Alternate Method</span></h2>\r\n<ol>\r\n<li>Switch off the machine and disconnect the keyboard.</li>\r\n<li>Turn the keyboard upside down and remove all the screws.</li>\r\n<li>Lift the top half of the keyboard (The one with the keys on it) off and put the bottom half to one side. \r\n<ul>\r\n<li>There may be some clips on the keyboard, also, check for screws hidden under labels.</li>\r\n</ul>\r\n</li>\r\n<li>Turn the top half around so that you can see the backs of the keys, squeeze the tabs on each key to remove it, the space-bar will have a metal rod, this may be a pain to refit, but is not impossible.</li>\r\n<li>Fill a bowl with hot soapy water (Fairy Liquid works well)</li>\r\n<li>Dump the keys into the bowl and scrub with a brush.</li>\r\n<li>Remove the keys and leave to dry or dry with a hair dryer.</li>\r\n<li>Place the empty top half of the keyboard into the bowl and scrub until clean.</li>\r\n<li>Once everything is dry, reassemble the keyboard.</li>\r\n<li>Press both halves of the keyboard firmly together, if you miss the clips in the middle your keys wont reach the circuits and wont spring up.</li>\r\n<li>Plug in your keyboard, switch on your PC, enjoy.</li>\r\n</ol>', 1);
INSERT INTO `kbentries` (`id`, `created`, `modified`, `title`, `content`, `admin_id`) VALUES(9, '2009-04-06 15:35:47', '2009-04-06 15:35:47', 'Monitor with funny colors', '<p>IT cannot do anything.&nbsp; you will need to bring in your own monitor.</p>', 43);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `created`, `modified`, `name`) VALUES(1, '2009-02-28 13:17:53', '2009-02-28 13:17:53', 'NONE');
INSERT INTO `locations` (`id`, `created`, `modified`, `name`) VALUES(3, '2009-02-28 13:17:53', '2009-02-28 13:17:53', 'Northeast');
INSERT INTO `locations` (`id`, `created`, `modified`, `name`) VALUES(6, '2009-03-05 09:44:31', '2009-03-05 09:55:30', 'Southwest');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL default '1',
  `first_name` varchar(75) NOT NULL,
  `middle_name` varchar(75) default NULL,
  `last_name` varchar(75) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `computer_id` int(8) NOT NULL,
  `network_port` varchar(30) NOT NULL,
  `location_id` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `enabled`, `first_name`, `middle_name`, `last_name`, `email`, `created`, `modified`, `computer_id`, `network_port`, `location_id`) VALUES(1, 'billyuser', 'b5f6da2dbb28a405140b8bce7939298a', 1, 'George', '', 'Jungle', 'george@jungle.com', '2009-02-28 13:17:53', '2009-03-30 09:47:29', 1, '984', 3);
INSERT INTO `users` (`id`, `username`, `password`, `enabled`, `first_name`, `middle_name`, `last_name`, `email`, `created`, `modified`, `computer_id`, `network_port`, `location_id`) VALUES(7, 'telunuser', '50783287e9a000d5cd1355ace38cd7af', 1, 'Jack', '', 'Beanstalk', 'Jack@stalk.com', '2009-02-28 13:17:53', '2009-03-30 09:47:40', 3, '8766', 6);
INSERT INTO `users` (`id`, `username`, `password`, `enabled`, `first_name`, `middle_name`, `last_name`, `email`, `created`, `modified`, `computer_id`, `network_port`, `location_id`) VALUES(8, 'ursulauser', '59443febed05a9633a6cea1c6b4c8800', 1, 'Wonder', '', 'Woman', 'wonder@woman.com', '2009-02-28 13:17:53', '2009-04-05 09:39:41', 2, '66788', 3);

-- --------------------------------------------------------

--
-- Table structure for table `worklogs`
--

DROP TABLE IF EXISTS `worklogs`;
CREATE TABLE IF NOT EXISTS `worklogs` (
  `id` int(8) NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `content` text NOT NULL,
  `incident_id` int(8) NOT NULL,
  `user_id` int(8) NOT NULL,
  `admin_id` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `worklogs`
--

INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(56, '2009-03-30 16:12:23', '2009-03-30 16:12:23', 'SYSTEM MESSAGE: Incident Updated by: billy | Status changed to: accepted | \n					Incident assigned to: joe', 25, 7, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(57, '2009-03-31 20:01:41', '2009-03-31 20:01:41', '<p>Thank you Mr. Tech Sir.</p>', 25, 0, 43);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(58, '2009-03-31 20:01:56', '2009-03-31 20:01:56', 'SYSTEM MESSAGE: Incident Updated by: joe | Status changed to: accepted | \n					Incident assigned to: joe', 25, 7, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(59, '2009-03-31 20:02:31', '2009-03-31 20:02:31', '<p>You are welcome.&nbsp; It might be about a hour or two.&nbsp; Something urgent came up.</p>', 25, 0, 43);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(60, '2009-04-04 09:03:56', '2009-04-04 09:03:56', '<p>Jack,</p>\r\n<p>When was the last time you tried to access this folder?</p>\r\n<p>&nbsp;</p>', 26, 0, 4);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(61, '2009-04-04 09:06:13', '2009-04-04 09:06:13', '<p>To tell you the truth, I do not remember.</p>', 26, 7, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(62, '2009-04-04 09:07:31', '2009-04-04 09:07:31', '<p>The network drive was renamed about 3 months ago.&nbsp; Please try remapping your drive to  //sharedstorage/accounting/sharedocs/ and let me know if it works.</p>', 26, 0, 4);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(63, '2009-04-04 09:07:52', '2009-04-04 09:07:52', '<p>It works.&nbsp; Thanks.</p>', 26, 7, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(64, '2009-04-04 09:08:17', '2009-04-04 09:08:17', 'SYSTEM MESSAGE: Incident Updated by: telun | Status changed to: resolved | \n					Incident assigned to: telun', 26, 7, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(65, '2009-04-05 09:19:17', '2009-04-05 09:19:17', '<p>klkklkllk;kl</p>', 27, 0, 9);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(66, '2009-04-06 14:06:40', '2009-04-06 14:06:40', 'SYSTEM MESSAGE: Incident Updated by: ursula | Status changed to: pending. | \n					Incident is assigned to: UNASSIGNED', 29, 8, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(67, '2009-04-06 14:07:22', '2009-04-06 14:07:22', 'SYSTEM MESSAGE: Incident Updated by: ursula | Status changed to: accepted | \n					Incident assigned to: ursula', 29, 8, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(68, '2009-04-06 14:08:32', '2009-04-06 14:08:32', 'SYSTEM MESSAGE: Incident Updated by: ursula | Status changed to: resolved | \n					Incident assigned to: ursula', 29, 8, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(69, '2009-04-06 15:29:02', '2009-04-06 15:29:02', '<p>Did you see my order yet?</p>', 30, 8, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(70, '2009-04-06 15:31:43', '2009-04-06 15:31:43', 'SYSTEM MESSAGE: Incident Updated by: joe | Status changed to: accepted | \n					Incident assigned to: joe', 30, 8, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(71, '2009-04-06 15:32:37', '2009-04-06 15:32:37', '<p>Hello, I am looking at your issue.&nbsp; It will be a minute.</p>', 30, 0, 43);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(72, '2009-04-06 15:33:15', '2009-04-06 15:33:15', '<p>The monitor has been replaced.</p>', 30, 0, 43);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(73, '2009-04-06 15:33:33', '2009-04-06 15:33:33', 'SYSTEM MESSAGE: Incident Updated by: joe | Status changed to: resolved | \n					Incident assigned to: joe', 30, 8, 0);
INSERT INTO `worklogs` (`id`, `created`, `modified`, `content`, `incident_id`, `user_id`, `admin_id`) VALUES(55, '2009-03-30 09:46:37', '2009-03-30 09:46:37', '<p>I am currently looking into the problem.&nbsp; I will update the worklog when I find an answer.</p>', 25, 0, 43);
