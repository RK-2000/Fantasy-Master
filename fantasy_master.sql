-- phpMyAdmin SQL Dump
-- version 4.6.6deb1+deb.cihar.com~xenial.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 08, 2019 at 05:29 PM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-8+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fantasy_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_control`
--

CREATE TABLE `admin_control` (
  `ControlID` tinyint(2) UNSIGNED NOT NULL,
  `ControlName` varchar(50) NOT NULL,
  `ModuleID` tinyint(2) UNSIGNED DEFAULT NULL,
  `ParentControlID` tinyint(2) UNSIGNED DEFAULT NULL,
  `Sort` tinyint(1) UNSIGNED NOT NULL,
  `ModuleIcon` varchar(25) NOT NULL DEFAULT 'flaticon-user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_control`
--

INSERT INTO `admin_control` (`ControlID`, `ControlName`, `ModuleID`, `ParentControlID`, `Sort`, `ModuleIcon`) VALUES
(1, 'Dashboard', 1, NULL, 1, 'flaticon-user'),
(2, 'Admin', NULL, NULL, 2, 'flaticon-user'),
(3, 'Staff Members', 2, 2, 1, 'flaticon-user'),
(5, 'User', NULL, NULL, 3, 'flaticon-user'),
(6, 'Users', 3, 5, 1, 'flaticon-user'),
(7, 'Social', NULL, NULL, 4, 'flaticon-user'),
(8, 'Reported Contents', 4, 7, 1, 'flaticon-user'),
(9, 'Configuration', NULL, NULL, 5, 'flaticon-user'),
(10, 'Categories', 5, 9, 1, 'flaticon-user'),
(11, 'Products', 6, 12, 2, 'flaticon-user'),
(12, 'Store', NULL, NULL, 4, 'flaticon-user'),
(13, 'Stores', 7, 12, 1, 'flaticon-user'),
(14, 'Orders', 8, 12, 3, 'flaticon-user'),
(15, 'Coupon', 9, 12, 4, 'flaticon-user'),
(16, 'Bookings', 10, 12, 3, 'flaticon-user'),
(17, 'Broadcast', 11, 5, 2, 'flaticon-user'),
(18, 'Manage Pages', 12, 9, 2, 'flaticon-user'),
(19, 'Sales Report', 13, 12, 4, 'flaticon-user'),
(36, 'Cricket', NULL, NULL, 4, 'flaticon-user'),
(37, 'Series', 15, 36, 1, 'flaticon-user'),
(38, 'Matches', 16, 36, 2, 'flaticon-user'),
(39, 'Contests', 17, 36, 4, 'flaticon-user'),
(40, 'Teams', 18, 36, 3, 'flaticon-user'),
(41, 'Point System', 19, 36, 9, 'flaticon-user'),
(44, 'Withdrawals', 23, 5, 3, 'flaticon-user'),
(45, 'Winnings', 26, 36, 8, 'flaticon-user'),
(46, 'Verifications', 27, 5, 4, 'flaticon-user'),
(47, 'Settings', 28, 9, 1, 'flaticon-user'),
(48, 'Manage Testimonial', 29, 5, 5, 'flaticon-user'),
(49, 'Banner', 30, 9, 1, 'flaticon-user'),
(52, 'Pre Draft Contest', 35, 36, 7, 'flaticon-user');

-- --------------------------------------------------------

--
-- Table structure for table `admin_modules`
--

CREATE TABLE `admin_modules` (
  `ModuleID` tinyint(2) UNSIGNED NOT NULL,
  `ModuleTitle` varchar(50) NOT NULL,
  `ModuleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_modules`
--

INSERT INTO `admin_modules` (`ModuleID`, `ModuleTitle`, `ModuleName`) VALUES
(1, 'Dashboard', 'dashboard'),
(2, 'Manage Staff', 'staff'),
(3, 'Manage Users', 'user'),
(4, 'Reported Content', 'flagged'),
(5, 'Manage Categories', 'category'),
(6, 'Manage Products', 'product'),
(7, 'Manage Stores', 'store'),
(8, 'Manage Orders', 'order'),
(9, 'Manage Coupons', 'coupon'),
(10, 'Manage Bookings', 'booking'),
(11, 'Broadcast Message', 'broadcast'),
(12, 'Manage Page', 'page'),
(13, 'Sales Report', 'storesalesreport'),
(15, 'Series', 'series'),
(16, 'Matches', 'matches'),
(17, 'Contests', 'contests'),
(18, 'Teams', 'teams'),
(19, 'Point System', 'pointsystem'),
(20, 'Players', 'players'),
(22, 'Transactions', 'transactions'),
(23, 'Withdrawals', 'withdrawals'),
(24, 'Joined Contests', 'joinedcontests'),
(25, 'Private Contests', 'privatecontests'),
(26, 'Winnings', 'winnings'),
(27, 'Verifications', 'verifications'),
(28, 'Settings', 'bonus'),
(29, 'Manage Testimonial', 'post'),
(30, 'Banner', 'banner'),
(31, 'UserDetails', 'userdetails'),
(32, 'AuctionDrafts', 'auctionDrafts'),
(33, 'Private Contests', 'Privatecontests'),
(34, 'Deposit History', 'depositHistory'),
(35, 'Pre Draft Contest', 'predraft'),
(36, 'Referral History', 'referral'),
(37, 'Joined Users', 'joinedusers');

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_type_permission`
--

CREATE TABLE `admin_user_type_permission` (
  `UserTypeID` int(11) NOT NULL,
  `ModuleID` tinyint(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_user_type_permission`
--

INSERT INTO `admin_user_type_permission` (`UserTypeID`, `ModuleID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 9),
(1, 11),
(2, 12),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37);

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `BannerID` int(11) NOT NULL,
  `BannerTitle` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`BannerID`, `BannerTitle`) VALUES
(341492, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `dummy_names`
--

CREATE TABLE `dummy_names` (
  `id` int(11) NOT NULL,
  `names` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ecom_coupon`
--

CREATE TABLE `ecom_coupon` (
  `CouponID` int(11) NOT NULL,
  `CouponTitle` varchar(255) DEFAULT NULL,
  `CouponDescription` mediumtext,
  `CouponCode` varchar(12) NOT NULL,
  `CouponType` enum('Flat','Percentage') NOT NULL,
  `CouponValue` int(11) NOT NULL,
  `CouponValueLimit` int(11) DEFAULT '0',
  `MiniumAmount` mediumint(8) DEFAULT NULL,
  `MaximumAmount` mediumint(8) DEFAULT NULL,
  `NumberOfUses` smallint(6) DEFAULT NULL,
  `CouponValidTillDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log_api`
--

CREATE TABLE `log_api` (
  `LogID` int(11) NOT NULL,
  `URL` varchar(255) NOT NULL,
  `RawData` mediumtext NOT NULL,
  `DataJ` mediumtext NOT NULL,
  `Response` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `log_api`
--

INSERT INTO `log_api` (`LogID`, `URL`, `RawData`, `DataJ`, `Response`) VALUES
(1, 'http://localhost/fantasy-master/api/admin/signin', '{\"Username\":\"admin@mailinator.com\",\"Password\":\"123456\",\"Source\":\"Direct\",\"DeviceType\":\"Native\"}', '{\"API\":\"signin\",\"Username\":\"admin@mailinator.com\",\"Password\":\"123456\",\"Source\":\"Direct\",\"DeviceType\":\"Native\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{\"UserGUID\":\"abcd\",\"UserID\":125,\"FullName\":\"Admin\",\"UserTypeID\":1,\"IsAdmin\":\"Yes\",\"FirstName\":\"Admin\",\"LastName\":null,\"Email\":\"admin@mailinator.com\",\"StatusID\":2,\"ProfilePic\":\"http:\\/\\/localhost\\/fantasy-master\\/api\\/uploads\\/profile\\/picture\\/user-img.svg\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"PermittedModules\":[{\"ModuleID\":1,\"ModuleTitle\":\"Dashboard\",\"ModuleName\":\"dashboard\"},{\"ModuleID\":2,\"ModuleTitle\":\"Manage Staff\",\"ModuleName\":\"staff\"},{\"ModuleID\":3,\"ModuleTitle\":\"Manage Users\",\"ModuleName\":\"user\"},{\"ModuleID\":9,\"ModuleTitle\":\"Manage Coupons\",\"ModuleName\":\"coupon\"},{\"ModuleID\":11,\"ModuleTitle\":\"Broadcast Message\",\"ModuleName\":\"broadcast\"},{\"ModuleID\":15,\"ModuleTitle\":\"Series\",\"ModuleName\":\"series\"},{\"ModuleID\":16,\"ModuleTitle\":\"Matches\",\"ModuleName\":\"matches\"},{\"ModuleID\":17,\"ModuleTitle\":\"Contests\",\"ModuleName\":\"contests\"},{\"ModuleID\":18,\"ModuleTitle\":\"Teams\",\"ModuleName\":\"teams\"},{\"ModuleID\":19,\"ModuleTitle\":\"Point System\",\"ModuleName\":\"pointsystem\"},{\"ModuleID\":20,\"ModuleTitle\":\"Players\",\"ModuleName\":\"players\"},{\"ModuleID\":22,\"ModuleTitle\":\"Transactions\",\"ModuleName\":\"transactions\"},{\"ModuleID\":23,\"ModuleTitle\":\"Withdrawals\",\"ModuleName\":\"withdrawals\"},{\"ModuleID\":24,\"ModuleTitle\":\"Joined Contests\",\"ModuleName\":\"joinedcontests\"},{\"ModuleID\":25,\"ModuleTitle\":\"Private Contests\",\"ModuleName\":\"privatecontests\"},{\"ModuleID\":26,\"ModuleTitle\":\"Winnings\",\"ModuleName\":\"winnings\"},{\"ModuleID\":27,\"ModuleTitle\":\"Verifications\",\"ModuleName\":\"verifications\"},{\"ModuleID\":28,\"ModuleTitle\":\"Settings\",\"ModuleName\":\"bonus\"},{\"ModuleID\":29,\"ModuleTitle\":\"Manage Testimonial\",\"ModuleName\":\"post\"},{\"ModuleID\":30,\"ModuleTitle\":\"Banner\",\"ModuleName\":\"banner\"},{\"ModuleID\":31,\"ModuleTitle\":\"UserDetails\",\"ModuleName\":\"userdetails\"},{\"ModuleID\":32,\"ModuleTitle\":\"AuctionDrafts\",\"ModuleName\":\"auctionDrafts\"},{\"ModuleID\":33,\"ModuleTitle\":\"Private Contests\",\"ModuleName\":\"Privatecontests\"},{\"ModuleID\":34,\"ModuleTitle\":\"Deposit History\",\"ModuleName\":\"depositHistory\"},{\"ModuleID\":35,\"ModuleTitle\":\"Pre Draft Contest\",\"ModuleName\":\"predraft\"},{\"ModuleID\":36,\"ModuleTitle\":\"Referral History\",\"ModuleName\":\"referral\"}],\"Menu\":[{\"ControlID\":1,\"ControlName\":\"Dashboard\",\"ModuleName\":\"dashboard\"},{\"ControlID\":2,\"ControlName\":\"Admin\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":3,\"ControlName\":\"Staff Members\",\"ModuleName\":\"staff\"}]},{\"ControlID\":5,\"ControlName\":\"User\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":6,\"ControlName\":\"Users\",\"ModuleName\":\"user\"},{\"ControlID\":17,\"ControlName\":\"Broadcast\",\"ModuleName\":\"broadcast\"},{\"ControlID\":44,\"ControlName\":\"Withdrawals\",\"ModuleName\":\"withdrawals\"},{\"ControlID\":46,\"ControlName\":\"Verifications\",\"ModuleName\":\"verifications\"},{\"ControlID\":48,\"ControlName\":\"Manage Testimonial\",\"ModuleName\":\"post\"}]},{\"ControlID\":12,\"ControlName\":\"Store\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":15,\"ControlName\":\"Coupon\",\"ModuleName\":\"coupon\"}]},{\"ControlID\":36,\"ControlName\":\"Cricket\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":37,\"ControlName\":\"Series\",\"ModuleName\":\"series\"},{\"ControlID\":38,\"ControlName\":\"Matches\",\"ModuleName\":\"matches\"},{\"ControlID\":40,\"ControlName\":\"Teams\",\"ModuleName\":\"teams\"},{\"ControlID\":39,\"ControlName\":\"Contests\",\"ModuleName\":\"contests\"},{\"ControlID\":51,\"ControlName\":\"Private Contests\",\"ModuleName\":\"Privatecontests\"},{\"ControlID\":50,\"ControlName\":\"Auction & Snake Drafts\",\"ModuleName\":\"auctionDrafts\"},{\"ControlID\":52,\"ControlName\":\"Pre Draft Contest\",\"ModuleName\":\"predraft\"},{\"ControlID\":45,\"ControlName\":\"Winnings\",\"ModuleName\":\"winnings\"},{\"ControlID\":41,\"ControlName\":\"Point System\",\"ModuleName\":\"pointsystem\"}]},{\"ControlID\":9,\"ControlName\":\"Configuration\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":47,\"ControlName\":\"Settings\",\"ModuleName\":\"bonus\"},{\"ControlID\":49,\"ControlName\":\"Banner\",\"ModuleName\":\"banner\"}]}]}}'),
(2, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(3, 'http://localhost/fantasy-master/api/utilities/dashboardStatics', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d', '{\"API\":\"dashboardStatics\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{\"TotalUsers\":0,\"TotalContest\":0,\"TodayContests\":0,\"TotalDeposits\":0,\"TotalWithdraw\":0,\"TodayDeposit\":0,\"NewUsers\":0,\"TotalEarning\":0,\"PendingWithdraw\":0}}'),
(4, 'http://localhost/fantasy-master/api/sports/getMatches', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&OrderBy=MatchStartDateTime&Sequence=ASC&existingContests=2&Params=SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status&PageNo=1&PageSize=5&Status=Running', '{\"API\":\"getMatches\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"OrderBy\":\"MatchStartDateTime\",\"Sequence\":\"ASC\",\"existingContests\":\"2\",\"Params\":\"SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status\",\"PageNo\":\"1\",\"PageSize\":\"5\",\"Status\":\"Running\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{\"TotalRecords\":0}}'),
(5, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(6, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(7, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(8, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(9, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(10, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(11, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(12, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(13, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(14, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(15, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(16, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(17, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(18, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(19, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(20, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(21, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(22, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=ad804dd5-b0b1-9701-8539-8e43a37ca29d&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(23, 'http://localhost/fantasy-master/api/signin/signout', '{\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\"}', '{\"API\":\"signout\",\"SessionKey\":\"ad804dd5-b0b1-9701-8539-8e43a37ca29d\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(24, 'http://localhost/fantasy-master/api/admin/signin', '{\"Username\":\"admin@mailinator.com\",\"Password\":\"123456\",\"Source\":\"Direct\",\"DeviceType\":\"Native\"}', '{\"API\":\"signin\",\"Username\":\"admin@mailinator.com\",\"Password\":\"123456\",\"Source\":\"Direct\",\"DeviceType\":\"Native\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{\"UserGUID\":\"abcd\",\"UserID\":125,\"FullName\":\"Admin\",\"UserTypeID\":1,\"IsAdmin\":\"Yes\",\"FirstName\":\"Admin\",\"LastName\":null,\"Email\":\"admin@mailinator.com\",\"StatusID\":2,\"ProfilePic\":\"http:\\/\\/localhost\\/fantasy-master\\/api\\/uploads\\/profile\\/picture\\/user-img.svg\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"PermittedModules\":[{\"ModuleID\":1,\"ModuleTitle\":\"Dashboard\",\"ModuleName\":\"dashboard\"},{\"ModuleID\":2,\"ModuleTitle\":\"Manage Staff\",\"ModuleName\":\"staff\"},{\"ModuleID\":3,\"ModuleTitle\":\"Manage Users\",\"ModuleName\":\"user\"},{\"ModuleID\":9,\"ModuleTitle\":\"Manage Coupons\",\"ModuleName\":\"coupon\"},{\"ModuleID\":11,\"ModuleTitle\":\"Broadcast Message\",\"ModuleName\":\"broadcast\"},{\"ModuleID\":15,\"ModuleTitle\":\"Series\",\"ModuleName\":\"series\"},{\"ModuleID\":16,\"ModuleTitle\":\"Matches\",\"ModuleName\":\"matches\"},{\"ModuleID\":17,\"ModuleTitle\":\"Contests\",\"ModuleName\":\"contests\"},{\"ModuleID\":18,\"ModuleTitle\":\"Teams\",\"ModuleName\":\"teams\"},{\"ModuleID\":19,\"ModuleTitle\":\"Point System\",\"ModuleName\":\"pointsystem\"},{\"ModuleID\":20,\"ModuleTitle\":\"Players\",\"ModuleName\":\"players\"},{\"ModuleID\":22,\"ModuleTitle\":\"Transactions\",\"ModuleName\":\"transactions\"},{\"ModuleID\":23,\"ModuleTitle\":\"Withdrawals\",\"ModuleName\":\"withdrawals\"},{\"ModuleID\":24,\"ModuleTitle\":\"Joined Contests\",\"ModuleName\":\"joinedcontests\"},{\"ModuleID\":25,\"ModuleTitle\":\"Private Contests\",\"ModuleName\":\"privatecontests\"},{\"ModuleID\":26,\"ModuleTitle\":\"Winnings\",\"ModuleName\":\"winnings\"},{\"ModuleID\":27,\"ModuleTitle\":\"Verifications\",\"ModuleName\":\"verifications\"},{\"ModuleID\":28,\"ModuleTitle\":\"Settings\",\"ModuleName\":\"bonus\"},{\"ModuleID\":29,\"ModuleTitle\":\"Manage Testimonial\",\"ModuleName\":\"post\"},{\"ModuleID\":30,\"ModuleTitle\":\"Banner\",\"ModuleName\":\"banner\"},{\"ModuleID\":31,\"ModuleTitle\":\"UserDetails\",\"ModuleName\":\"userdetails\"},{\"ModuleID\":32,\"ModuleTitle\":\"AuctionDrafts\",\"ModuleName\":\"auctionDrafts\"},{\"ModuleID\":33,\"ModuleTitle\":\"Private Contests\",\"ModuleName\":\"Privatecontests\"},{\"ModuleID\":34,\"ModuleTitle\":\"Deposit History\",\"ModuleName\":\"depositHistory\"},{\"ModuleID\":35,\"ModuleTitle\":\"Pre Draft Contest\",\"ModuleName\":\"predraft\"},{\"ModuleID\":36,\"ModuleTitle\":\"Referral History\",\"ModuleName\":\"referral\"}],\"Menu\":[{\"ControlID\":1,\"ControlName\":\"Dashboard\",\"ModuleName\":\"dashboard\"},{\"ControlID\":2,\"ControlName\":\"Admin\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":3,\"ControlName\":\"Staff Members\",\"ModuleName\":\"staff\"}]},{\"ControlID\":5,\"ControlName\":\"User\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":6,\"ControlName\":\"Users\",\"ModuleName\":\"user\"},{\"ControlID\":17,\"ControlName\":\"Broadcast\",\"ModuleName\":\"broadcast\"},{\"ControlID\":44,\"ControlName\":\"Withdrawals\",\"ModuleName\":\"withdrawals\"},{\"ControlID\":46,\"ControlName\":\"Verifications\",\"ModuleName\":\"verifications\"},{\"ControlID\":48,\"ControlName\":\"Manage Testimonial\",\"ModuleName\":\"post\"}]},{\"ControlID\":12,\"ControlName\":\"Store\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":15,\"ControlName\":\"Coupon\",\"ModuleName\":\"coupon\"}]},{\"ControlID\":36,\"ControlName\":\"Cricket\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":37,\"ControlName\":\"Series\",\"ModuleName\":\"series\"},{\"ControlID\":38,\"ControlName\":\"Matches\",\"ModuleName\":\"matches\"},{\"ControlID\":40,\"ControlName\":\"Teams\",\"ModuleName\":\"teams\"},{\"ControlID\":39,\"ControlName\":\"Contests\",\"ModuleName\":\"contests\"},{\"ControlID\":51,\"ControlName\":\"Private Contests\",\"ModuleName\":\"Privatecontests\"},{\"ControlID\":50,\"ControlName\":\"Auction & Snake Drafts\",\"ModuleName\":\"auctionDrafts\"},{\"ControlID\":52,\"ControlName\":\"Pre Draft Contest\",\"ModuleName\":\"predraft\"},{\"ControlID\":45,\"ControlName\":\"Winnings\",\"ModuleName\":\"winnings\"},{\"ControlID\":41,\"ControlName\":\"Point System\",\"ModuleName\":\"pointsystem\"}]},{\"ControlID\":9,\"ControlName\":\"Configuration\",\"ModuleName\":null,\"ChildMenu\":[{\"ControlID\":47,\"ControlName\":\"Settings\",\"ModuleName\":\"bonus\"},{\"ControlID\":49,\"ControlName\":\"Banner\",\"ModuleName\":\"banner\"}]}]}}'),
(25, 'http://localhost/fantasy-master/api/utilities/dashboardStatics', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755', '{\"API\":\"dashboardStatics\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{\"TotalUsers\":0,\"TotalContest\":0,\"TodayContests\":0,\"TotalDeposits\":0,\"TotalWithdraw\":0,\"TodayDeposit\":0,\"NewUsers\":0,\"TotalEarning\":0,\"PendingWithdraw\":0}}'),
(26, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(27, 'http://localhost/fantasy-master/api/sports/getMatches', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&OrderBy=MatchStartDateTime&Sequence=ASC&existingContests=2&Params=SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status&PageNo=1&PageSize=5&Status=Running', '{\"API\":\"getMatches\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"OrderBy\":\"MatchStartDateTime\",\"Sequence\":\"ASC\",\"existingContests\":\"2\",\"Params\":\"SeriesName,MatchType,MatchNo,MatchStartDateTime,TeamNameLocal,TeamNameVisitor,TeamNameShortLocal,TeamNameShortVisitor,TeamFlagLocal,TeamFlagVisitor,MatchLocation,Status\",\"PageNo\":\"1\",\"PageSize\":\"5\",\"Status\":\"Running\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{\"TotalRecords\":0}}'),
(28, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(29, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(30, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(31, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(32, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(33, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(34, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(35, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(36, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(37, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(38, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(39, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(40, 'http://localhost/fantasy-master/api/admin/users', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&IsAdmin=No&EmailStatus=Verified&OrderBy=FirstName&Sequence=ASC&Params=Email,EmailStatus', '{\"API\":\"users\",\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"IsAdmin\":\"No\",\"EmailStatus\":\"Verified\",\"OrderBy\":\"FirstName\",\"Sequence\":\"ASC\",\"Params\":\"Email,EmailStatus\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}'),
(41, 'http://localhost/fantasy-master/api/notifications', 'SessionKey=08e87c96-d070-589b-93f9-93c308935755&Status=1&PageNo=1&PageSize=15', '{\"API\":null,\"SessionKey\":\"08e87c96-d070-589b-93f9-93c308935755\",\"Status\":\"1\",\"PageNo\":\"1\",\"PageSize\":\"15\"}', '{\"ResponseCode\":200,\"Message\":\"Success\",\"Data\":{}}');

-- --------------------------------------------------------

--
-- Table structure for table `log_cron`
--

CREATE TABLE `log_cron` (
  `CronID` int(11) NOT NULL,
  `CronType` varchar(100) NOT NULL,
  `EntryDate` datetime NOT NULL,
  `CronStatus` enum('Completed','Exit') DEFAULT NULL,
  `CompletionDate` datetime DEFAULT NULL,
  `CronResponse` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_pushdata`
--

CREATE TABLE `log_pushdata` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Body` text,
  `DeviceTypeID` int(11) DEFAULT NULL,
  `Return` varchar(255) DEFAULT NULL,
  `EntryDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `set_categories`
--

CREATE TABLE `set_categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryGUID` char(36) NOT NULL,
  `CategoryTypeID` int(11) NOT NULL,
  `ParentCategoryID` int(11) DEFAULT NULL,
  `CategoryName` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `set_categories_type`
--

CREATE TABLE `set_categories_type` (
  `CategoryTypeID` int(11) NOT NULL,
  `CategoryTypeName` varchar(100) DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `set_categories_type`
--

INSERT INTO `set_categories_type` (`CategoryTypeID`, `CategoryTypeName`, `StatusID`) VALUES
(1, 'Category Type1', 2);

-- --------------------------------------------------------

--
-- Table structure for table `set_device_type`
--

CREATE TABLE `set_device_type` (
  `DeviceTypeID` int(11) NOT NULL,
  `DeviceTypeName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `set_device_type`
--

INSERT INTO `set_device_type` (`DeviceTypeID`, `DeviceTypeName`) VALUES
(1, 'Native'),
(2, 'iPhone'),
(3, 'AndroidPhone'),
(4, 'iPad'),
(5, 'AndroidTablet'),
(6, 'WindowsPhone'),
(7, 'WindowsTablet'),
(8, 'OtherMobileDevice');

-- --------------------------------------------------------

--
-- Table structure for table `set_location_country`
--

CREATE TABLE `set_location_country` (
  `CountryCode` char(2) NOT NULL,
  `CountryName` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `phonecode` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `set_location_country`
--

INSERT INTO `set_location_country` (`CountryCode`, `CountryName`, `iso3`, `phonecode`) VALUES
('AF', 'Afghanistan', 'AFG', 93),
('AL', 'Albania', 'ALB', 355),
('DZ', 'Algeria', 'DZA', 213),
('AS', 'American Samoa', 'ASM', 1684),
('AD', 'Andorra', 'AND', 376),
('AO', 'Angola', 'AGO', 244),
('AI', 'Anguilla', 'AIA', 1264),
('AG', 'Antigua and Barbuda', 'ATG', 1268),
('AR', 'Argentina', 'ARG', 54),
('AM', 'Armenia', 'ARM', 374),
('AW', 'Aruba', 'ABW', 297),
('AU', 'Australia', 'AUS', 61),
('AT', 'Austria', 'AUT', 43),
('AZ', 'Azerbaijan', 'AZE', 994),
('BS', 'Bahamas', 'BHS', 1242),
('BH', 'Bahrain', 'BHR', 973),
('BD', 'Bangladesh', 'BGD', 880),
('BB', 'Barbados', 'BRB', 1246),
('BY', 'Belarus', 'BLR', 375),
('BE', 'Belgium', 'BEL', 32),
('BZ', 'Belize', 'BLZ', 501),
('BJ', 'Benin', 'BEN', 229),
('BM', 'Bermuda', 'BMU', 1441),
('BT', 'Bhutan', 'BTN', 975),
('BO', 'Bolivia', 'BOL', 591),
('BA', 'Bosnia and Herzegovina', 'BIH', 387),
('BW', 'Botswana', 'BWA', 267),
('BR', 'Brazil', 'BRA', 55),
('IO', 'British Indian Ocean Territory', NULL, 246),
('BN', 'Brunei Darussalam', 'BRN', 673),
('BG', 'Bulgaria', 'BGR', 359),
('BF', 'Burkina Faso', 'BFA', 226),
('BI', 'Burundi', 'BDI', 257),
('KH', 'Cambodia', 'KHM', 855),
('CM', 'Cameroon', 'CMR', 237),
('CA', 'Canada', 'CAN', 1),
('CV', 'Cape Verde', 'CPV', 238),
('KY', 'Cayman Islands', 'CYM', 1345),
('CF', 'Central African Republic', 'CAF', 236),
('TD', 'Chad', 'TCD', 235),
('CL', 'Chile', 'CHL', 56),
('CN', 'China', 'CHN', 86),
('CX', 'Christmas Island', NULL, 61),
('CC', 'Cocos (Keeling) Islands', NULL, 672),
('CO', 'Colombia', 'COL', 57),
('KM', 'Comoros', 'COM', 269),
('CG', 'Congo', 'COG', 242),
('CD', 'Congo, the Democratic Republic of the', 'COD', 242),
('CK', 'Cook Islands', 'COK', 682),
('CR', 'Costa Rica', 'CRI', 506),
('CI', 'Cote D\'Ivoire', 'CIV', 225),
('HR', 'Croatia', 'HRV', 385),
('CU', 'Cuba', 'CUB', 53),
('CY', 'Cyprus', 'CYP', 357),
('CZ', 'Czech Republic', 'CZE', 420),
('DK', 'Denmark', 'DNK', 45),
('DJ', 'Djibouti', 'DJI', 253),
('DM', 'Dominica', 'DMA', 1767),
('DO', 'Dominican Republic', 'DOM', 1809),
('EC', 'Ecuador', 'ECU', 593),
('EG', 'Egypt', 'EGY', 20),
('SV', 'El Salvador', 'SLV', 503),
('GQ', 'Equatorial Guinea', 'GNQ', 240),
('ER', 'Eritrea', 'ERI', 291),
('EE', 'Estonia', 'EST', 372),
('ET', 'Ethiopia', 'ETH', 251),
('FK', 'Falkland Islands (Malvinas)', 'FLK', 500),
('FO', 'Faroe Islands', 'FRO', 298),
('FJ', 'Fiji', 'FJI', 679),
('FI', 'Finland', 'FIN', 358),
('FR', 'France', 'FRA', 33),
('GF', 'French Guiana', 'GUF', 594),
('PF', 'French Polynesia', 'PYF', 689),
('GA', 'Gabon', 'GAB', 241),
('GM', 'Gambia', 'GMB', 220),
('GE', 'Georgia', 'GEO', 995),
('DE', 'Germany', 'DEU', 49),
('GH', 'Ghana', 'GHA', 233),
('GI', 'Gibraltar', 'GIB', 350),
('GR', 'Greece', 'GRC', 30),
('GL', 'Greenland', 'GRL', 299),
('GD', 'Grenada', 'GRD', 1473),
('GP', 'Guadeloupe', 'GLP', 590),
('GU', 'Guam', 'GUM', 1671),
('GT', 'Guatemala', 'GTM', 502),
('GN', 'Guinea', 'GIN', 224),
('GW', 'Guinea-Bissau', 'GNB', 245),
('GY', 'Guyana', 'GUY', 592),
('HT', 'Haiti', 'HTI', 509),
('VA', 'Holy See (Vatican City State)', 'VAT', 39),
('HN', 'Honduras', 'HND', 504),
('HK', 'Hong Kong', 'HKG', 852),
('HU', 'Hungary', 'HUN', 36),
('IS', 'Iceland', 'ISL', 354),
('IN', 'India', 'IND', 91),
('ID', 'Indonesia', 'IDN', 62),
('IR', 'Iran, Islamic Republic of', 'IRN', 98),
('IQ', 'Iraq', 'IRQ', 964),
('IE', 'Ireland', 'IRL', 353),
('IL', 'Israel', 'ISR', 972),
('IT', 'Italy', 'ITA', 39),
('JM', 'Jamaica', 'JAM', 1876),
('JP', 'Japan', 'JPN', 81),
('JO', 'Jordan', 'JOR', 962),
('KZ', 'Kazakhstan', 'KAZ', 7),
('KE', 'Kenya', 'KEN', 254),
('KI', 'Kiribati', 'KIR', 686),
('KP', 'Korea, Democratic People\'s Republic of', 'PRK', 850),
('KR', 'Korea, Republic of', 'KOR', 82),
('KW', 'Kuwait', 'KWT', 965),
('KG', 'Kyrgyzstan', 'KGZ', 996),
('LA', 'Lao People\'s Democratic Republic', 'LAO', 856),
('LV', 'Latvia', 'LVA', 371),
('LB', 'Lebanon', 'LBN', 961),
('LS', 'Lesotho', 'LSO', 266),
('LR', 'Liberia', 'LBR', 231),
('LY', 'Libyan Arab Jamahiriya', 'LBY', 218),
('LI', 'Liechtenstein', 'LIE', 423),
('LT', 'Lithuania', 'LTU', 370),
('LU', 'Luxembourg', 'LUX', 352),
('MO', 'Macao', 'MAC', 853),
('MK', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 389),
('MG', 'Madagascar', 'MDG', 261),
('MW', 'Malawi', 'MWI', 265),
('MY', 'Malaysia', 'MYS', 60),
('MV', 'Maldives', 'MDV', 960),
('ML', 'Mali', 'MLI', 223),
('MT', 'Malta', 'MLT', 356),
('MH', 'Marshall Islands', 'MHL', 692),
('MQ', 'Martinique', 'MTQ', 596),
('MR', 'Mauritania', 'MRT', 222),
('MU', 'Mauritius', 'MUS', 230),
('YT', 'Mayotte', NULL, 269),
('MX', 'Mexico', 'MEX', 52),
('FM', 'Micronesia, Federated States of', 'FSM', 691),
('MD', 'Moldova, Republic of', 'MDA', 373),
('MC', 'Monaco', 'MCO', 377),
('MN', 'Mongolia', 'MNG', 976),
('MS', 'Montserrat', 'MSR', 1664),
('MA', 'Morocco', 'MAR', 212),
('MZ', 'Mozambique', 'MOZ', 258),
('MM', 'Myanmar', 'MMR', 95),
('NA', 'Namibia', 'NAM', 264),
('NR', 'Nauru', 'NRU', 674),
('NP', 'Nepal', 'NPL', 977),
('NL', 'Netherlands', 'NLD', 31),
('AN', 'Netherlands Antilles', 'ANT', 599),
('NC', 'New Caledonia', 'NCL', 687),
('NZ', 'New Zealand', 'NZL', 64),
('NI', 'Nicaragua', 'NIC', 505),
('NE', 'Niger', 'NER', 227),
('NG', 'Nigeria', 'NGA', 234),
('NU', 'Niue', 'NIU', 683),
('NF', 'Norfolk Island', 'NFK', 672),
('MP', 'Northern Mariana Islands', 'MNP', 1670),
('NO', 'Norway', 'NOR', 47),
('OM', 'Oman', 'OMN', 968),
('PK', 'Pakistan', 'PAK', 92),
('PW', 'Palau', 'PLW', 680),
('PS', 'Palestinian Territory, Occupied', NULL, 970),
('PA', 'Panama', 'PAN', 507),
('PG', 'Papua New Guinea', 'PNG', 675),
('PY', 'Paraguay', 'PRY', 595),
('PE', 'Peru', 'PER', 51),
('PH', 'Philippines', 'PHL', 63),
('PL', 'Poland', 'POL', 48),
('PT', 'Portugal', 'PRT', 351),
('PR', 'Puerto Rico', 'PRI', 1787),
('QA', 'Qatar', 'QAT', 974),
('RE', 'Reunion', 'REU', 262),
('RO', 'Romania', 'ROM', 40),
('RU', 'Russian Federation', 'RUS', 70),
('RW', 'Rwanda', 'RWA', 250),
('SH', 'Saint Helena', 'SHN', 290),
('KN', 'Saint Kitts and Nevis', 'KNA', 1869),
('LC', 'Saint Lucia', 'LCA', 1758),
('PM', 'Saint Pierre and Miquelon', 'SPM', 508),
('VC', 'Saint Vincent and the Grenadines', 'VCT', 1784),
('WS', 'Samoa', 'WSM', 684),
('SM', 'San Marino', 'SMR', 378),
('ST', 'Sao Tome and Principe', 'STP', 239),
('SA', 'Saudi Arabia', 'SAU', 966),
('SN', 'Senegal', 'SEN', 221),
('CS', 'Serbia and Montenegro', NULL, 381),
('SC', 'Seychelles', 'SYC', 248),
('SL', 'Sierra Leone', 'SLE', 232),
('SG', 'Singapore', 'SGP', 65),
('SK', 'Slovakia', 'SVK', 421),
('SI', 'Slovenia', 'SVN', 386),
('SB', 'Solomon Islands', 'SLB', 677),
('SO', 'Somalia', 'SOM', 252),
('ZA', 'South Africa', 'ZAF', 27),
('GS', 'South Georgia and the South Sandwich Islands', NULL, 0),
('ES', 'Spain', 'ESP', 34),
('LK', 'Sri Lanka', 'LKA', 94),
('SD', 'Sudan', 'SDN', 249),
('SR', 'Suriname', 'SUR', 597),
('SJ', 'Svalbard and Jan Mayen', 'SJM', 47),
('SZ', 'Swaziland', 'SWZ', 268),
('SE', 'Sweden', 'SWE', 46),
('CH', 'Switzerland', 'CHE', 41),
('SY', 'Syrian Arab Republic', 'SYR', 963),
('TW', 'Taiwan, Province of China', 'TWN', 886),
('TJ', 'Tajikistan', 'TJK', 992),
('TZ', 'Tanzania, United Republic of', 'TZA', 255),
('TH', 'Thailand', 'THA', 66),
('TL', 'Timor-Leste', NULL, 670),
('TG', 'Togo', 'TGO', 228),
('TK', 'Tokelau', 'TKL', 690),
('TO', 'Tonga', 'TON', 676),
('TT', 'Trinidad and Tobago', 'TTO', 1868),
('TN', 'Tunisia', 'TUN', 216),
('TR', 'Turkey', 'TUR', 90),
('TM', 'Turkmenistan', 'TKM', 7370),
('TC', 'Turks and Caicos Islands', 'TCA', 1649),
('TV', 'Tuvalu', 'TUV', 688),
('UG', 'Uganda', 'UGA', 256),
('UA', 'Ukraine', 'UKR', 380),
('AE', 'United Arab Emirates', 'ARE', 971),
('GB', 'United Kingdom', 'GBR', 44),
('US', 'United States', 'USA', 1),
('UM', 'United States Minor Outlying Islands', NULL, 1),
('UY', 'Uruguay', 'URY', 598),
('UZ', 'Uzbekistan', 'UZB', 998),
('VU', 'Vanuatu', 'VUT', 678),
('VE', 'Venezuela', 'VEN', 58),
('VN', 'Viet Nam', 'VNM', 84),
('VG', 'Virgin Islands, British', 'VGB', 1284),
('VI', 'Virgin Islands, U.s.', 'VIR', 1340),
('WF', 'Wallis and Futuna', 'WLF', 681),
('EH', 'Western Sahara', 'ESH', 212),
('YE', 'Yemen', 'YEM', 967),
('ZM', 'Zambia', 'ZMB', 260),
('ZW', 'Zimbabwe', 'ZWE', 263);

-- --------------------------------------------------------

--
-- Table structure for table `set_location_state`
--

CREATE TABLE `set_location_state` (
  `StateName` varchar(255) NOT NULL,
  `CountryCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `set_location_state`
--

INSERT INTO `set_location_state` (`StateName`, `CountryCode`) VALUES
('Andaman and Nicobar Islands', 'IN'),
('Andhra Pradesh', 'IN'),
('Arunachal Pradesh', 'IN'),
('Bihar', 'IN'),
('Chandigarh', 'IN'),
('Chhattisgarh', 'IN'),
('Dadra and Nagar Haveli', 'IN'),
('Daman and Diu', 'IN'),
('Delhi', 'IN'),
('Goa', 'IN'),
('Gujarat', 'IN'),
('Haryana', 'IN'),
('Himachal Pradesh', 'IN'),
('Jammu and Kashmir', 'IN'),
('Jharkhand', 'IN'),
('Karnataka', 'IN'),
('Kenmore', 'IN'),
('Kerala', 'IN'),
('Lakshadweep', 'IN'),
('Madhya Pradesh', 'IN'),
('Maharashtra', 'IN'),
('Manipur', 'IN'),
('Meghalaya', 'IN'),
('Mizoram', 'IN'),
('Nagaland', 'IN'),
('Narora', 'IN'),
('Natwar', 'IN'),
('Paschim Medinipur', 'IN'),
('Pondicherry', 'IN'),
('Punjab', 'IN'),
('Rajasthan', 'IN'),
('Sikkim', 'IN'),
('Tamil Nadu', 'IN'),
('Tripura', 'IN'),
('Uttar Pradesh', 'IN'),
('Uttarakhand', 'IN'),
('Vaishali', 'IN'),
('West Bengal', 'IN');

-- --------------------------------------------------------

--
-- Table structure for table `set_pages`
--

CREATE TABLE `set_pages` (
  `PageID` int(11) NOT NULL,
  `PageGUID` varchar(36) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Content` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `set_pages`
--

INSERT INTO `set_pages` (`PageID`, `PageGUID`, `Title`, `Content`) VALUES
(1, 'about', 'Hello', '&lt;p style=&quot;margin-left:0cm; margin-right:0cm&quot;&gt;&lt;span style=&quot;font-size:12pt&quot;&gt;&lt;span style=&quot;font-family:&amp;quot;Times New Roman&amp;quot;,serif&quot;&gt;&lt;span style=&quot;font-size:10.5pt&quot;&gt;&lt;span style=&quot;font-family:&amp;quot;Arial&amp;quot;,&amp;quot;sans-serif&amp;quot;&quot;&gt;&lt;span style=&quot;color:#212529&quot;&gt;Test&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;\r\n'),
(2, 'terms', 'About Terms and Condition', '&lt;p&gt;About terms and Condition&lt;/p&gt;\r\n'),
(3, 'privacy', 'About Privacy Policy', '&lt;p&gt;About privacy policy details&lt;/p&gt;\r\n'),
(4, 'help', 'Help', '&lt;p&gt;Help Detail&lt;/p&gt;\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `set_site_config`
--

CREATE TABLE `set_site_config` (
  `ConfigTypeGUID` varchar(50) NOT NULL,
  `ConfigTypeDescprition` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `ConfigTypeValue` varchar(100) NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  `Sort` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `set_site_config`
--

INSERT INTO `set_site_config` (`ConfigTypeGUID`, `ConfigTypeDescprition`, `ConfigTypeValue`, `StatusID`, `Sort`) VALUES
('AndridAppUrl', 'Andrid App Url', 'https://fsl11.com/android/FSL11.apk', 2, 0),
('AndroidAppVersion', 'Android App Version', '10', 2, 0),
('FirstDepositBonus', 'First Deposit Bonus', '1', 6, 2),
('IsAndroidAppUpdateMandatory', 'Is Android App Update Mandatory', 'Yes', 2, 0),
('MatchLiveTime', 'Match On Going Live In Minutes', '0', 2, 8),
('MinimumDepositLimit', 'Minimum Deposit Limit', '1', 6, 3),
('MinimumWithdrawalLimitBank', 'Minimum Withdrawal Limit Bank', '200', 2, 0),
('MinimumWithdrawalLimitPaytm', 'Minimum Withdrawal Limit Paytm', '200', 2, 0),
('ReferByDepositBonus', 'Refer By Deposit Bonus', '50', 2, 4),
('ReferToDepositBonus', 'Refer To Deposit Bonus', '50', 2, 5),
('SignupBonus', 'Signup Bonus', '50', 2, 6),
('VerificationBonus', 'Verification Bonus', '100', 2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `set_source`
--

CREATE TABLE `set_source` (
  `SourceID` int(11) NOT NULL,
  `SourceName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `set_source`
--

INSERT INTO `set_source` (`SourceID`, `SourceName`) VALUES
(1, 'Direct'),
(2, 'Facebook'),
(3, 'Twitter'),
(4, 'Google'),
(5, 'LinkedIn'),
(6, 'Otp');

-- --------------------------------------------------------

--
-- Table structure for table `set_status`
--

CREATE TABLE `set_status` (
  `StatusID` int(11) NOT NULL,
  `StatusName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `set_status`
--

INSERT INTO `set_status` (`StatusID`, `StatusName`) VALUES
(1, 'Pending,Upcoming'),
(2, 'Verified,Active,Running'),
(3, 'Deleted,Rejected,Cancelled,Failed'),
(4, 'Blocked,Closed'),
(5, 'Delivered,Completed'),
(6, 'Deactive,Inactive'),
(7, 'Discontinued'),
(8, 'Abandoned'),
(9, 'No Result, Not Submitted'),
(10, 'Match Review');

-- --------------------------------------------------------

--
-- Table structure for table `social_post`
--

CREATE TABLE `social_post` (
  `PostID` int(11) NOT NULL,
  `PostGUID` varchar(36) NOT NULL,
  `ParentPostID` int(11) DEFAULT NULL,
  `PostType` enum('Activity','Comment','Review','Question','Testimonial') DEFAULT NULL,
  `EntityID` int(11) NOT NULL,
  `ToEntityID` int(11) NOT NULL,
  `PostCaption` mediumtext COMMENT 'Title',
  `PostContent` mediumtext,
  `Privacy` enum('Public','Private','Friends') NOT NULL DEFAULT 'Public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `social_subscribers`
--

CREATE TABLE `social_subscribers` (
  `UserID` int(11) NOT NULL COMMENT 'Requested by User',
  `ToEntityID` int(11) NOT NULL COMMENT 'Can be ID of User, Page, Group etc.',
  `Action` enum('Friend','Follow','Subscribe') NOT NULL DEFAULT 'Follow',
  `EntryDate` datetime NOT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '1',
  `IsAdmin` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sports_auction_draft_player_point`
--

CREATE TABLE `sports_auction_draft_player_point` (
  `SeriesID` int(11) NOT NULL,
  `ContestID` int(11) DEFAULT NULL,
  `PlayerID` int(11) NOT NULL,
  `PlayerRole` enum('AllRounder','Batsman','Bowler','WicketKeeper','Other') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Other',
  `TotalPoints` decimal(10,0) NOT NULL DEFAULT '0',
  `UpdateDateTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sports_contest`
--

CREATE TABLE `sports_contest` (
  `ContestID` int(11) NOT NULL,
  `ContestGUID` varchar(36) NOT NULL,
  `UserID` int(11) NOT NULL,
  `LeagueType` enum('Dfs','Draft','Auction','') NOT NULL DEFAULT 'Dfs',
  `GameType` enum('Advance','Safe') NOT NULL DEFAULT 'Advance',
  `GameTimeLive` smallint(6) DEFAULT '0',
  `LeagueJoinDateTime` datetime DEFAULT NULL,
  `AdminPercent` smallint(3) DEFAULT NULL,
  `ContestFormat` enum('Head to Head','League') NOT NULL,
  `ContestType` enum('Normal','Reverse','InPlay','Hot','Champion','Practice','More','Mega','Winner Takes All','Only For Beginners','Head to Head') NOT NULL,
  `IsAutoCreate` enum('No','Yes') NOT NULL DEFAULT 'No',
  `PredraftContestID` int(11) DEFAULT NULL,
  `SeriesID` int(11) NOT NULL,
  `MatchID` int(11) DEFAULT NULL,
  `ContestName` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `Privacy` enum('Yes','No') NOT NULL,
  `IsPaid` enum('Yes','No') NOT NULL,
  `IsConfirm` enum('Yes','No') NOT NULL DEFAULT 'No',
  `ShowJoinedContest` enum('Yes','No') NOT NULL,
  `UnfilledWinningPercent` enum('No','Yes') NOT NULL DEFAULT 'No',
  `WinningAmount` int(11) NOT NULL DEFAULT '0',
  `ContestSize` int(11) NOT NULL,
  `TotalJoinedTeams` int(11) NOT NULL DEFAULT '0',
  `CashBonusContribution` float(6,2) NOT NULL DEFAULT '0.00',
  `UserJoinLimit` int(11) NOT NULL DEFAULT '1',
  `EntryType` enum('Single','Multiple') NOT NULL,
  `EntryFee` int(11) NOT NULL DEFAULT '0',
  `NoOfWinners` int(11) NOT NULL DEFAULT '0',
  `CustomizeWinning` text,
  `UserInvitationCode` varchar(50) DEFAULT NULL,
  `IsWinningAssigned` enum('No','Yes','Moved') NOT NULL DEFAULT 'No',
  `IsWinningDistributed` enum('No','Yes') NOT NULL DEFAULT 'No',
  `MinimumUserJoined` int(11) DEFAULT NULL,
  `AuctionStatusID` int(11) NOT NULL DEFAULT '1',
  `AuctionUpdateTime` datetime DEFAULT NULL,
  `AuctionTimeBreakAvailable` enum('Yes','No') NOT NULL DEFAULT 'No',
  `AuctionIsBreakTimeStatus` enum('Yes','No') NOT NULL DEFAULT 'No',
  `AuctionBreakDateTime` datetime DEFAULT NULL,
  `DraftTotalRounds` smallint(6) DEFAULT NULL,
  `DraftLiveRound` smallint(6) DEFAULT NULL,
  `DraftUserTeamSubmitted` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsRefund` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsVirtualUserJoined` enum('Yes','No','Completed') NOT NULL DEFAULT 'No',
  `IsDummyJoined` tinyint(4) NOT NULL DEFAULT '0',
  `VirtualUserJoinedPercentage` int(6) DEFAULT NULL,
  `IsPrivacyNameDisplay` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_contest`
--

INSERT INTO `sports_contest` (`ContestID`, `ContestGUID`, `UserID`, `LeagueType`, `GameType`, `GameTimeLive`, `LeagueJoinDateTime`, `AdminPercent`, `ContestFormat`, `ContestType`, `IsAutoCreate`, `PredraftContestID`, `SeriesID`, `MatchID`, `ContestName`, `Privacy`, `IsPaid`, `IsConfirm`, `ShowJoinedContest`, `UnfilledWinningPercent`, `WinningAmount`, `ContestSize`, `TotalJoinedTeams`, `CashBonusContribution`, `UserJoinLimit`, `EntryType`, `EntryFee`, `NoOfWinners`, `CustomizeWinning`, `UserInvitationCode`, `IsWinningAssigned`, `IsWinningDistributed`, `MinimumUserJoined`, `AuctionStatusID`, `AuctionUpdateTime`, `AuctionTimeBreakAvailable`, `AuctionIsBreakTimeStatus`, `AuctionBreakDateTime`, `DraftTotalRounds`, `DraftLiveRound`, `DraftUserTeamSubmitted`, `IsRefund`, `IsVirtualUserJoined`, `IsDummyJoined`, `VirtualUserJoinedPercentage`, `IsPrivacyNameDisplay`) VALUES
(926, '6472acea-eb2b-87ca-8dd2-1b65e706d489', 125, 'Dfs', 'Advance', 0, NULL, 10, 'League', 'Head to Head', 'Yes', NULL, 149, 167, 'sad', 'No', 'Yes', 'No', 'No', 'No', 100, 2, 0, 10.00, 1, 'Single', 55, 1, '[{\"From\":1,\"To\":1,\"Percent\":100,\"WinningAmount\":\"100\"}]', 'CPLepY', 'No', 'No', NULL, 1, NULL, 'No', 'No', NULL, NULL, NULL, 'No', 'No', 'No', 0, NULL, 'No'),
(929, 'dae1ec28-3046-1d2b-6d0c-217e086e0b59', 125, 'Dfs', 'Advance', 0, NULL, 10, 'Head to Head', 'Head to Head', 'No', NULL, 126, 129, 'new 11', 'No', 'Yes', 'No', 'No', 'No', 100, 2, 0, 10.00, 1, 'Single', 55, 1, '[{\"From\":1,\"To\":1,\"Percent\":100,\"WinningAmount\":\"100\"}]', 'YbjM5U', 'No', 'No', NULL, 1, NULL, 'No', 'No', NULL, NULL, NULL, 'No', 'No', 'No', 0, NULL, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `sports_contest_join`
--

CREATE TABLE `sports_contest_join` (
  `ContestID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `MatchID` int(11) DEFAULT NULL,
  `SeriesID` int(11) DEFAULT NULL,
  `JoinInning` enum('First','Second','Third','Fourth') DEFAULT NULL,
  `UserTeamID` int(11) DEFAULT NULL,
  `TotalPoints` float(8,2) NOT NULL DEFAULT '0.00',
  `UserRank` int(11) DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `UserWinningAmount` float(8,2) NOT NULL DEFAULT '0.00',
  `TaxAmount` float(8,2) NOT NULL DEFAULT '0.00',
  `EntryDate` datetime NOT NULL,
  `AuctionTimeBank` smallint(6) NOT NULL DEFAULT '180',
  `AuctionBudget` bigint(20) NOT NULL DEFAULT '1000000000',
  `AuctionUserStatus` enum('Online','Offline') NOT NULL DEFAULT 'Offline',
  `IsHold` enum('Yes','No') NOT NULL DEFAULT 'No',
  `AuctionHoldDateTime` datetime DEFAULT NULL,
  `DraftUserPosition` smallint(6) DEFAULT NULL,
  `DraftUserLive` enum('Yes','No') NOT NULL DEFAULT 'No',
  `DraftUserLiveTime` datetime DEFAULT NULL,
  `IsRefund` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsWinningAssigned` enum('No','Yes') NOT NULL DEFAULT 'No' COMMENT '(From MongoDB To MySQL)',
  `IsWinningDistributed` enum('No','Yes') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_contest_join`
--

INSERT INTO `sports_contest_join` (`ContestID`, `UserID`, `MatchID`, `SeriesID`, `JoinInning`, `UserTeamID`, `TotalPoints`, `UserRank`, `ModifiedDate`, `UserWinningAmount`, `TaxAmount`, `EntryDate`, `AuctionTimeBank`, `AuctionBudget`, `AuctionUserStatus`, `IsHold`, `AuctionHoldDateTime`, `DraftUserPosition`, `DraftUserLive`, `DraftUserLiveTime`, `IsRefund`, `IsWinningAssigned`, `IsWinningDistributed`) VALUES
(929, 927, 129, NULL, NULL, 928, 0.00, NULL, NULL, 0.00, 0.00, '2019-08-05 12:44:37', 180, 1000000000, 'Offline', 'No', NULL, NULL, 'No', NULL, 'No', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `sports_matches`
--

CREATE TABLE `sports_matches` (
  `MatchID` int(11) NOT NULL,
  `MatchGUID` char(36) NOT NULL,
  `MatchIDLive` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `SeriesID` int(11) NOT NULL,
  `MatchTypeID` int(11) NOT NULL,
  `MatchNo` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `SportsType` enum('Cricket','Football','Kabaddi') NOT NULL DEFAULT 'Cricket',
  `MatchLocation` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `TeamIDLocal` int(11) NOT NULL,
  `TeamIDVisitor` int(11) NOT NULL,
  `MatchStartDateTime` datetime DEFAULT NULL,
  `MatchClosedInMinutes` smallint(6) DEFAULT NULL,
  `MatchCompleteDateTime` datetime DEFAULT NULL,
  `IsPreSquad` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT 'if yes matches going to user crate team ',
  `PlayerStatsUpdate` enum('Yes','No') NOT NULL DEFAULT 'No',
  `MatchScoreDetails` text,
  `LastUpdatedOn` datetime DEFAULT NULL,
  `IsPlayingXINotificationSent` enum('No','Yes') NOT NULL DEFAULT 'No',
  `IsPlayerPointsUpdated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsUserPointsUpdated` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_matches`
--

INSERT INTO `sports_matches` (`MatchID`, `MatchGUID`, `MatchIDLive`, `SeriesID`, `MatchTypeID`, `MatchNo`, `SportsType`, `MatchLocation`, `TeamIDLocal`, `TeamIDVisitor`, `MatchStartDateTime`, `MatchClosedInMinutes`, `MatchCompleteDateTime`, `IsPreSquad`, `PlayerStatsUpdate`, `MatchScoreDetails`, `LastUpdatedOn`, `IsPlayingXINotificationSent`, `IsPlayerPointsUpdated`, `IsUserPointsUpdated`) VALUES
(129, '24ed79c7-13eb-c05b-c206-7493ca9d57f4', 'tnplt20_2019_g23', 126, 3, '23rd Match', 'Cricket', 'NPR College Ground, Dindigul, India', 127, 128, '2019-08-05 13:45:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:33:32', 'No', 'No', 'No'),
(133, '06d2737a-2531-30cc-1572-4e29d3f0837d', 'nluae_2019_t20_02', 130, 3, '2nd Match', 'Cricket', 'VRA Cricket Ground, Amstelveen, Netherlands', 131, 132, '2019-08-05 14:00:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:33:40', 'No', 'No', 'No'),
(137, '618f29b7-e330-d76a-3446-dcb2d885b0e2', 'wcslt20_2019_g1', 134, 3, '1st Match', 'Cricket', 'Aigburth, Liverpool, England', 135, 136, '2019-08-06 13:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:33:48', 'No', 'No', 'No'),
(140, '43a4684a-6dc2-c021-7535-40d1c331ac53', 'tnplt20_2019_g24', 126, 3, '24th Match', 'Cricket', 'NPR College Ground, Dindigul, India', 138, 139, '2019-08-06 13:45:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:33:55', 'No', 'No', 'No'),
(141, 'ed217f71-09fa-5fc7-d3bf-9b98c560c656', 'nluae_2019_t20_03', 130, 3, '3rd Match', 'Cricket', 'Sportpark Westvliet, The Hague, Netherlands', 131, 132, '2019-08-06 14:00:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:02', 'No', 'No', 'No'),
(145, '5c5a6359-043c-feb7-8af6-da8a58d7e485', 'wiind_2019_t20_03', 142, 3, '3rd T20 Match', 'Cricket', 'Providence Stadium, Guyana, West Indies', 143, 144, '2019-08-06 14:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:09', 'No', 'No', 'No'),
(148, '68aa899d-b082-601b-bd6f-292e54ab3374', 'wcslt20_2019_g2', 134, 3, '2nd Match', 'Cricket', 'Haslegrave Ground, Loughborough, England', 146, 147, '2019-08-06 15:00:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:16', 'No', 'No', 'No'),
(152, 'de36e99c-a8ee-8c8d-bf21-8a2fafa04475', 'gblt20_2019_can_g16', 149, 3, '16th Match - Round 2', 'Cricket', 'CAA Centre, Brampton, Canada', 150, 151, '2019-08-06 16:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:23', 'No', 'No', 'No'),
(156, '386d2518-ee59-d45c-c63c-653633f8146c', 't20blast_2019_g58', 153, 3, '58th Match - South Group', 'Cricket', 'The 1st Central County Ground, Hove, England', 154, 155, '2019-08-06 18:00:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:38', 'No', 'No', 'No'),
(159, 'c82b440d-dc50-e3c0-e890-d602ec998d32', 'wcslt20_2019_g3', 134, 3, '3rd Match', 'Cricket', 'Emerald Headingley, Leeds, England', 157, 158, '2019-08-06 18:00:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:31', 'No', 'No', 'No'),
(162, 'cd1e128a-b7ad-6ff8-31ed-577858a8c915', 'gblt20_2019_can_g17', 149, 3, '17th Match - Round 2', 'Cricket', 'CAA Centre, Brampton, Canada', 160, 161, '2019-08-06 20:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:46', 'No', 'No', 'No'),
(164, '7ad6eb47-32c5-8718-514f-00fbb6f26408', 'tnplt20_2019_g25', 126, 3, '25th Match', 'Cricket', 'ICL - Sankar Nagar Ground, Tirunelveli, India', 128, 163, '2019-08-07 13:45:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:34:53', 'No', 'No', 'No'),
(167, 'f03c8560-6cee-f3d0-2ace-a4c8f5a50f94', 'gblt20_2019_can_g18', 149, 3, '18th Match - Round 2', 'Cricket', 'CAA Centre, Brampton, Canada', 165, 166, '2019-08-07 16:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:35:02', 'No', 'No', 'No'),
(170, '935f908d-7dc8-eeae-0d1a-c2e00869d612', 't20blast_2019_g61', 153, 3, '61st Match - North Group', 'Cricket', 'The County Ground, Northampton, England', 168, 169, '2019-08-07 17:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:35:10', 'No', 'No', 'No'),
(173, 'b3a18702-0e6c-e62e-3927-472feacab60e', 't20blast_2019_g62', 153, 3, '62nd Match - North Group', 'Cricket', 'The Fischer County Ground Grace Road, Leicester, England', 171, 172, '2019-08-07 17:30:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No'),
(176, 'c51361df-fc2b-94c6-5eca-50623271cc42', 't20blast_2019_g59', 153, 3, '59th Match - South Group', 'Cricket', 'Bristol County Ground, Bristol, England', 174, 175, '2019-08-07 17:30:00', NULL, NULL, 'No', 'No', NULL, '2019-08-05 10:35:18', 'No', 'No', 'No'),
(179, '9aaabe93-3fe2-3a60-f718-4b80eef506ea', 't20blast_2019_g60', 153, 3, '60th Match - South Group', 'Cricket', 'The CloudFM County Ground, Chelmsford, England', 177, 178, '2019-08-07 18:00:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No'),
(180, '7435b101-f755-48a0-ad55-a4cdf6d848f8', 'wiind_2019_one-day_01', 142, 1, '1st ODI Match', 'Cricket', 'Providence Stadium, Guyana, West Indies', 143, 144, '2019-08-08 13:30:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No'),
(181, 'edabdc05-a30b-9ff8-92e4-aa6fb3b1e0dd', 'wcslt20_2019_g5', 134, 3, '5th Match', 'Cricket', 'Woodbridge Road, Guildford, England', 158, 135, '2019-08-08 13:30:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No'),
(184, '65aa9ea6-f042-a69d-dd01-b04c8a949014', 'tnplt20_2019_g26', 126, 3, '26th Match', 'Cricket', 'ICL - Sankar Nagar Ground, Tirunelveli, India', 182, 183, '2019-08-08 13:45:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No'),
(185, '541342ca-11bb-b24f-360b-c7730b13132f', 'nluae_2019_t20_04', 130, 3, '4th Match', 'Cricket', 'Sportpark Westvliet, The Hague, Netherlands', 131, 132, '2019-08-08 14:00:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No'),
(186, '4bc393ca-9704-c839-0d85-5047a83f082b', 'wcslt20_2019_g4', 134, 3, '4th Match', 'Cricket', 'Haslegrave Ground, Loughborough, England', 146, 136, '2019-08-08 15:00:00', NULL, NULL, 'No', 'No', NULL, NULL, 'No', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `sports_players`
--

CREATE TABLE `sports_players` (
  `PlayerID` int(11) NOT NULL,
  `PlayerGUID` varchar(36) NOT NULL,
  `PlayerIDLive` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `PlayerName` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `PlayerPic` varchar(50) DEFAULT NULL,
  `PlayerCountry` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `PlayerBattingStyle` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `PlayerBowlingStyle` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `PlayerBattingStats` text,
  `PlayerBowlingStats` text,
  `PlayerSalary` float(4,2) DEFAULT NULL,
  `IsAdminSalaryUpdated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `LastUpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_players`
--

INSERT INTO `sports_players` (`PlayerID`, `PlayerGUID`, `PlayerIDLive`, `PlayerName`, `PlayerPic`, `PlayerCountry`, `PlayerBattingStyle`, `PlayerBowlingStyle`, `PlayerBattingStats`, `PlayerBowlingStats`, `PlayerSalary`, `IsAdminSalaryUpdated`, `LastUpdatedOn`) VALUES
(396, 'c6a57fe4-ad7b-c03a-6ce7-122d1032bf58', 'kt_kannan', 'KG Thamarai Kannan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(397, '57cf4532-516c-2f2c-844d-ca9b7d9767fd', 'c_shriram', 'C Shriram', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(398, '6002d0c0-c821-7bee-fd0d-93532ccd5703', 'kv_vaidhya', 'K Vishal Vaidhya', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(399, '57ae464c-7b47-41ea-0626-85ac2583fc4e', 'rs_yadav', 'R Sanjay Yadav', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(400, '2095d355-b1cf-445b-787f-eb9284cdf16c', 'b_aparajith', 'B Aparajith', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(401, '19bff90a-15d5-a4ea-6ea5-8f1214913038', 's_siddharth', 'S Siddharth', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(402, '1f4b955b-8668-5d08-ee0a-ee5f25352566', 'r_sathish', 'Rajagopal Sathish', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(403, '6042d3ff-cdf0-7acc-7d6b-254232390843', 'r_silambarasan', 'R Silambarasan', NULL, NULL, 'Right-hand bat', 'Right-arm Fast medium', NULL, NULL, NULL, 'No', NULL),
(404, 'f0a9d2e8-0b2b-559c-5c89-0efdfbbb96da', 'u_mukilesh', 'U Mukilesh', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(405, 'b5859cc2-ce62-9526-62e7-74b831c6c90e', 'ra_suthesh', 'Rangaraj Suthesh', NULL, NULL, 'Left-Hand Bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(406, '470599b7-4d6c-fd97-014c-037ef43f07a2', 'r_divakar', 'R Divakar', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(407, '037071b2-4e65-a8ab-a98a-813f3cb3b8c6', 's_arun', 'S Arun', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(408, '72ed1970-1d33-94b4-d2c8-c7488e8d6b2b', 'u_vishal', 'U Vishal', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(409, '2e621b7a-8405-32b0-22ea-a032d09e1997', 'pf_rokins', 'P Francis Rokins', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(410, '4f705fed-3bbe-9452-6c5f-a2d242b7445a', 'p_sugendhran', 'P Sugendhran', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(411, 'f1aae7e2-618f-0a9f-a64e-21dd661cf28c', 'rs_hariharan', 'RS Mokit Hariharan', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(412, '57b0aa0e-03d9-795c-f4ac-4fd1ca5b7845', 'ns_harish', 'NS Harish', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(413, '89a1a608-8894-33e5-d691-317c6bdb9889', 'dee_lingesh', 'Deeban Lingesh', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(414, '0036ce0a-42f1-e090-80bb-7ee557e88e5e', 'au_srinivas', 'Aushik Srinivas', NULL, NULL, 'Right-hand bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(415, 'a11b5c12-2b4c-e19a-c753-96c7b4c02610', 's_lokeshwar', 'S Lokeshwar', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(416, 'b3e178cf-b274-4509-c7e7-7d5f20061664', 'r_ashwin', 'Ravichandran Ashwin', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(417, 'f60e82a6-5f3a-0017-5da1-ddabd627e876', 'm_anbu', 'M Anbu', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(418, 'b762ea7b-e85a-0023-7ddb-c12508527f4a', 'b_praanesh', 'B Praanesh', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(419, '536f637d-99cd-67ba-8602-2f87a0720b0b', 'm_silambarasan', 'M Silambarasan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(420, '603d3005-c761-0f5c-d141-1b49728dbc7a', 'ka_saran', 'Karthik Saran M', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(421, 'b70061e1-91c3-bef8-3b81-764b3493eac6', 'n_jagadeesan', 'Narayan Jagadeesan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(422, 'b13f4fe2-e707-bea2-9000-77c3e8d22079', 'ram_rohit', 'Ramalingam Rohit', NULL, NULL, 'Right-hand bat', 'Right-arm Fast medium', NULL, NULL, NULL, 'No', NULL),
(423, 'c9c76582-9dfe-fb76-f252-a7cac9519cbd', 'ch_nishanth', 'C Hari Nishanth', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(424, '1749192d-bac2-508e-9a87-65c085e02e9c', 'v_totadri', 'Varun M Totadri', NULL, NULL, 'Left-Hand Bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(425, 'c9b219a1-c0ab-2bf4-3eba-29040dd5bc73', 's_sujay', 'S Sujay', NULL, NULL, 'Left-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(426, 'e9ed0e7d-2598-5f81-df32-8b19e04c52b4', 'mm_mohammed', 'M Mohammed', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(427, '03cebbdb-5a2f-0480-b857-5b2c1a75b21a', 'm_arunmozhi', 'MEY Arun Mozhi', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(428, 'b85d2b98-81c6-3716-e03e-467d270b6775', 'ht_nag', 'H Trilok Nag', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(429, '01c1c3e6-30a2-4c6a-da73-03f9cd31f077', 'sum_jain', 'Sumant Jain', NULL, NULL, 'Right-Hand Bat', 'Legbreak', NULL, NULL, NULL, 'No', NULL),
(430, '8e527ded-f97c-823c-01aa-3f96fe3785aa', 'm_abhinav', 'M Abhinav', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(431, '3794ed9d-b74f-793a-68fe-fb33bb3d0cd7', 'ja_kaushik', 'Jagannathan Kaushik', NULL, NULL, 'Right-hand bat', 'Right arm medium', NULL, NULL, NULL, 'No', NULL),
(432, '52625c5f-821c-2ff9-54c8-0003ad3385e6', 'ns_chaturved', 'NS Chaturved', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(433, '7af6209f-f5d3-ad04-d9ff-8d28b98a1e48', 'a_arun', 'Adithya Arun', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(434, '944b88ae-3510-de54-9a61-c74cb0614912', 'r_vivek', 'R Vivek', NULL, NULL, 'Right-hand bat', 'Right-arm medium fast', NULL, NULL, NULL, 'No', NULL),
(435, 'fc2d0aba-07e7-0590-ed77-fd0c45a5821e', 'seb_braat', 'Sebastiaan Braat', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(436, '6212e663-4575-8659-7ca8-df330ec66ac4', 'h_overdijk', 'Hidde C Overdijk', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(437, '85bc02f0-f9ba-da9a-cc0a-87bf239d3a99', 'p_seelaar', 'Pieter Seelaar', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(438, '3badbf6a-2d3e-ad59-3a10-fb9b99e2ba58', 'v_kingma', 'VJ Kingma', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(439, 'edfd0536-7186-d31d-f90f-e2eeedcd73c8', 'ton_staal', 'Tonny Staal', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(440, '8e645a68-e86f-2a96-6a01-2ab2ca6f3126', 'bd_glover', ' Brandon Glover', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(441, 'cfaf6a90-58f3-b95f-aeb7-d120bd638e1d', 'ms_zulfiqar', 'Saqib Zulfiqar', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(442, '2e9a417d-be31-60f8-c840-7660f064f685', 'b_cooper', 'Ben Cooper', NULL, NULL, 'Left-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(443, 'e3b7b3d9-12ee-8de9-a3b2-4c5ae1e3087c', 's_edwards', 'Scott Edwards', NULL, NULL, 'Right-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(444, 'e28e04c6-4a57-8705-d91b-a491e3d6b6e4', 'sik_zulfiqar', 'Sikander Zulfiqar', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(445, 'cd4b767c-b09e-bc74-7209-924e8ef30433', 'phi_boissevain', 'Philippe Boissevain', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(446, '05a799fa-dffa-40bb-14a8-a49c7f0ae66d', 'ma_odowd', 'Max O\'Dowd', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(447, '1914c427-3f30-6364-84af-7c3c85fa053c', 's_myburgh', 'Stephan Myburgh', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(448, 'b43d57b7-8ca6-b265-7caa-738e796fbaa5', 'ab_shakoor', 'Abdul Shakoor', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(449, '1feb415f-0a98-7b95-7635-8cb846c3fc4e', 'rame_shahzad', 'Rameez Shahzad', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(450, '4f5347f9-2aad-1a6e-9338-f73c853e2d27', 'ghul_shabber', 'Ghulam Shabber', NULL, NULL, 'Left-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(451, '51639536-7b1b-a1fd-334c-2a747c0ac3a7', 'r_mustafa', 'Rohan Mustafa', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(452, 'bee30cad-065b-ff59-6a29-70a39a5275eb', 'muh_usman', 'Muhammad Usman', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(453, '8c2e44dc-f258-b512-8d81-aaae6a33aa46', 'su_ahmed', 'Sultan Ahmed', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(454, 'eae84d1c-0e94-fa8f-27da-3a7a85dfa1c1', 'a_raza', 'Ahmed Raza', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(455, '2af8f31b-6e09-261e-2dc5-85cc7a3cba26', 'c_suri', 'Chirag Suri', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(456, '861ef76f-3d92-31fb-2f4a-cc6c212bb437', 'zahoo_khan', 'Zahoor Khan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(457, '9b416180-167c-1245-9dce-8cc4d871fcf9', 'ashf_ahmed', 'Ashfaq Ahmed', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(458, '922631a1-ad4f-505b-6e92-65c48770e577', 'w_ahmad', 'Waheed Ahmed', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(459, '8952e148-5086-d171-442b-fb96e9b2d7ec', 'dar_dsilva', 'Darius D\'Silva', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(460, '0d25fadf-54d8-0061-2944-dca69efd3366', 'qa_ahmed', 'Qadeer Ahmed', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(461, 'c884ef1a-b8d1-61fa-b924-8e9c7458f9e2', 'm_naveed', 'Mohammad Naveed', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(462, 'addbc398-7b53-5943-f838-0e4f17402f39', 'zaw_farid', 'Zawar Farid', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(463, '866a8e2b-feb6-e3ce-48a4-6e83bad52af6', 'moha_boota', 'Mohammad Boota', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(464, 'f94f1934-a72c-ed36-3555-2c98880d4d1f', 'w_ria_fackrell', 'Ria Fackrell', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(465, 'f8aefd44-e970-8e98-6df5-acfa50b677e6', 'w_su_luus', 'Sune Luus', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(466, 'e1eb4cee-eaba-7e7c-1c82-766467ea174f', 'w_eve_jones', 'Eve Jones', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(467, '04997c59-08fc-86d1-deb5-d55b677fd15f', 'w_ae_dyson', 'Alice Dyson', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(468, 'e1887317-d100-b622-c0b6-981a9290412e', 'w_s_ecclestone', 'Sophie Ecclestone', NULL, NULL, 'Right-Hand Bat', 'Left-Arm ordhodox', NULL, NULL, NULL, 'No', NULL),
(469, 'c1ae2659-f95c-f449-257d-7162357e5fae', 'w_db_Sophia', 'Sophia Dunkley Brown', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(470, '0f07f25e-4efa-c959-ca79-16c1eab70117', 'w_ele_threlkeld', 'Eleanor Threlkeld', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(471, 'c5e1180e-40aa-74ac-70a1-ea9bd160f151', 'w_tm_mcgrath', 'Tahlia  McGrath', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(472, '8f61de65-9d17-45cd-3e89-82dd791258c8', 'w_k_cross', 'Kate Cross', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(473, 'de39db0c-7b4c-599b-2148-fb42058e8050', 'w_el_lamb', 'Emma Lamb', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(474, 'f9f9576d-bbfb-9334-c695-3cfcf6a9b424', 'w_ha_kaur', 'Harmanpreet Kaur', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(475, '478cb4e4-507c-ccd1-313c-23d30174d419', 'w_al_hartley', 'Alex Hartley', NULL, NULL, 'Right-Hand Bat', 'Left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(476, 'dc8091f4-883e-c1d6-4741-e531a8bd31a7', 'w_geb_boyce', 'Georgie Boyce', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(477, 'c85fdc26-c663-c57d-ba16-68d467714086', 'w_dan_collins', 'Danielle Collins', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(478, 'b8e86654-0ac2-aca2-92b2-ca664f2470fd', 'w_nat_brown', 'Natalie Brown', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(479, '08b0fee5-fd15-522f-ae42-779cc801c3dc', 'w_mar_kelly', 'Marie Kelly', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(480, '7bdf3287-7f51-00c7-22d4-42b3f6f3bc0c', 'w_t_farrant', 'Tash Farrant', NULL, NULL, 'Left-hand bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(481, '0faa8581-8bf2-4171-8753-3b263665ad31', 'w_s_molineux', 'Sophie Molineux', NULL, NULL, 'Left-hand bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(482, '9beb8620-98d9-b4f1-b917-53fa7c688fbc', 'w_the_brookes', ' Thea Brookes', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(483, '788435bb-2ac8-f253-0b46-81c32aa2c2ca', 'w_ce_dean', 'charlie Dean', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(484, '986a571a-115c-409a-fec7-5d7b41ce04ba', 'w_pai_scholfield', ' Paige Scholfield', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(485, 'c5daf170-6c6e-080d-4990-72882befb48d', 'w_me_bouchier', 'Maia Bouchier', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(486, '6eb0e197-523e-6bb5-27db-d0e4b3a2b8b9', 'w_lk_bell', 'Lauren Bell', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(487, 'c48b6f83-b37d-5aa3-a135-97e58e9e54b4', 'w_ta_beaumont', 'Tammy Beaumont', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(488, '6c35e59c-efed-09e7-5480-265585866040', 'w_sw_bates', 'Suzie Bates', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(489, '4cf80ea1-a8e5-5c20-95fc-94b49dec2c06', 'w_ce_rudd', 'Carla Rudd', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(490, 'eb5b302a-f76b-d261-3268-8b58a0c78e35', 'w_iss_wong', 'Issy Wong', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(491, '00ca033e-e754-f31a-0c04-443fde37d12c', 'w_fmk_morris', 'Fritha Morris', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(492, '7e799750-4ff5-c034-a6a6-a9580e6e9c67', 'w_ad_wellington', 'Amanda Jade Wellington', NULL, NULL, 'Right-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(493, 'bd7f4538-a7a5-fbac-4079-8c98e8f5362e', 'w_sr_taylor', 'Stafanie Taylor', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(494, 'e7f4a2e0-ddef-9b74-866c-f5c5f10bfeef', 'w_da_wyatt', 'Danielle Wyatt', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(495, 'bd8f1cdb-8cfa-588f-5b68-792402928489', 'rr_karthikeyan', 'R Karthikeyan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(496, '7d21931f-555e-9378-3395-3ace114f537f', 'mg_moorthi', 'M Ganesh Moorthi', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(497, 'af0a5ea2-43d4-909d-bfdf-85c9ef759a3e', 'sp_nathan', 'SP Nathan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(498, 'aa25878a-42ac-c0d2-a2f2-5ce3121780dc', 's_saravanan', 'Sathiamoorty Vasanth Saravanan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(499, 'f79ca0b8-410f-5a1a-a78c-d52cbcd66f62', 'va_davidson', 'V Athisayaraj Davidson', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium-Fast', NULL, NULL, NULL, 'No', NULL),
(500, '352cdab8-9164-3623-7882-7cec1c25b781', 'su_anand', 'Subramanian Anand', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(501, '98da6036-0140-bc08-e764-780964fb1dbb', 'dak_kumaran', 'Dakshinamoorthy Kumaran', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(502, 'f189a3a7-7901-cbdc-8704-036bbe9a78d7', 'ak_sivan', 'Akash Sivan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(503, '1e3680e4-2815-8af3-6f34-45ba5f97d208', 's_boopalan', 'S Boopalan', NULL, NULL, 'Right-hand bat', 'Slow right-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(504, '268adc03-c039-e41e-942a-6d19f9845591', 's_dinesh', 'S Dinesh', NULL, NULL, 'Left-hand bat', 'Left-arm medium fast', NULL, NULL, NULL, 'No', NULL),
(505, 'bf86d8bf-f9bd-1b51-9269-c9b84171564b', 'nid_rajagopal', 'Nidhish Rajagopal', NULL, NULL, 'Left-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(506, 'f00900b1-b126-6cea-8430-7c5dff520980', 'm_kamlesh', 'Murugesan Kamlesh', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(507, '20072032-254e-5ded-e3e3-c9b43276a461', 'wj_victor', 'Wilkins Victor', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(508, '33f2d124-58cf-68b6-ff7d-00dcf7b4555d', 'bs_nathan', 'B S Nathan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(509, '256a3519-6760-f735-c4ab-a2d98208799e', 'was_sundar', 'Washington Sundar', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(510, '31c6645d-9c86-0975-4fc3-00dfda78c4d9', 'vs_siva', 'Subramania Siva', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(511, '537be1f2-66a0-1d86-5b46-c708af34341d', 'av_srinivasan', 'Akshay V Srinivasan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(512, 'd3485bb0-4340-9691-dd92-3c6fd53918c5', 'ms_raj', 'M Sathya Raj', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(513, '5ec41172-6eb6-7011-b66b-09acac1a546c', 'siv_senthilnathan', 'Sivagnanan Senthilnathan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(514, '6314a690-6f01-fc1b-47b5-acdf73e05b70', 'rah_raj', 'Rahul Raj', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(515, '7ec005cb-eade-193f-c7e5-6d337c732c19', 'ash_sanganakal', 'Ashith Sanganakal', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(516, 'bcde96ab-a7c8-a263-ea41-7f490cedf750', 'a_venkatesh', 'A Venkatesh', NULL, NULL, 'Right-Hand Bat', 'Legbreak', NULL, NULL, NULL, 'No', NULL),
(517, '48ab5be0-e37a-c4ad-c0d6-1e41c6847cfc', 's_abishiek', 'S Abishiek', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(518, '2a424450-5ce1-6418-3d94-a58edb20562f', 'shu_mehta', 'Shubham Mehta', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(519, '3822bf56-e375-0a28-f15a-b465e8d95f7a', 'km_bharathi', 'K Mani Bharathi', NULL, NULL, 'RIGHT HAND BATSMAN', NULL, NULL, NULL, NULL, 'No', NULL),
(520, '3aa2e711-bbbe-9a8a-ab7b-8e1aafb17e8e', 'rs_thilak', 'Thillak R S', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(521, '5db2ca4f-af2a-9b1a-51ed-ae4cdb7248f9', 'maa_raghav', 'Maaruthi Raghav', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(522, '2da48d52-c8ec-fe1e-e30a-3910131e91ca', 'ad_ganesh', 'Adithya Ganesh', NULL, NULL, 'Left-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(523, '37253a58-3b64-3d4f-72a3-8bb8d873383f', 'r_sathyanarayan', 'R Sathyanarayan', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(524, '2c1638a4-c2a5-174e-1c88-22c18e8bfd29', 'roo_raj', 'Rooban Raj M', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(525, '57736f13-a7eb-d1a2-4d37-a636cd6c907d', 'ad_barooah', 'Aditya Barooah', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(526, 'cca3f9a3-c9f7-9e68-393b-721e53eb8460', 'b_indrajith', 'Baba Indrajith', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(527, '685d60e5-ebb2-e398-7ee6-0a146d780ba8', 'bh_shankar', 'Bharath Shankar', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(528, 'c8fb4737-3275-93a4-5273-2c0e1e825f76', 'm_vijay', 'Murali Vijay', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(529, '530cf3f1-f1aa-401a-49a1-8193ce5c0073', 'p_saravana_kumar', 'Saravana Kumar P', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(530, '83739947-9cef-1639-0b5e-0ffb1551a0b7', 'm_poiyamozhi', 'M Poiyamozhi', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(531, '9dd43625-ee2f-fa93-9b88-780a93130987', 'lak_vignesh', 'Lakshminarayanan Vignesh', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(532, 'ae6893c6-4e54-2793-6edd-f9cc381843da', 's_aravind_d', 'S Aravind', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(533, '10de400b-10e7-28a3-95a5-954ec18d6b8b', 'rso_yadav', 'R Sonu Yadav', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(534, '2f856703-ab2d-47e7-1545-7fb151bb4a5f', 'ney_kangayan', 'Neyan Kangayan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(535, '05e2babd-8352-4c6f-3876-3b5590697ddf', 'ka_vignesh', 'Kannan Vignesh', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(536, '00f244a2-ebb0-fe15-dc07-1ad920ea401e', 'sur_kumar', 'Suresh Kumar', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(537, '862c82bb-24a0-457d-1b1a-993c27294838', 'ms_sanjay', 'MS Sanjay', NULL, NULL, 'Left-hand bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(538, 'bab1fca5-e01c-8155-a386-ce951552fd66', 'cha_ganapathy', 'Chandrasekar Ganapathy', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(539, 'c42316e3-1e5e-94bb-8933-bd6c0bbf35db', 'dt_chandrasekar', 'DT Chandrasekar', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(540, '583bbdf6-94f9-116c-32de-66752633af38', 'rs_kishore', 'Ravisrinivasan S Kishore', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(541, 'b0344a18-e3e6-b3de-74f1-543e836315c6', 'k_mukunth', 'K Mukunth', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(542, 'ddc576a6-54dc-b1de-6dcf-b078e3230ad8', 'rat_avr', 'Rathnam AVR', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(543, '118b12e7-fb8a-3cea-ba3f-30df000f7975', 'k_pollard', 'Kieron Pollard', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(544, '1d19456d-7735-c522-cf4b-da4a66774f76', 'k_roach', 'Kemar Roach', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(545, '6eec9152-e5b6-75f0-e7d6-68986d6b3c87', 's_hetmyer', 'Shimron Hetmyer', NULL, NULL, 'Left-hand bat', 'legbreak', NULL, NULL, NULL, 'No', NULL),
(546, 'c581f9d6-200b-c614-52fe-88de26a1734f', 'c_brathwaite', 'Carlos Brathwaite', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(547, 'db50d40f-d42e-fca0-4035-0acdf35747c3', 's_narine', 'Sunil Narine', NULL, NULL, 'Left-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(548, 'd501bcc3-64ca-4cbb-460f-d04024b239ef', 'n_pooran', 'Nicholas Pooran', NULL, NULL, 'Left-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(549, '7cc682ca-bd66-c38c-c464-3f82817d5c79', 'f_allen', 'Fabian Allen', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(550, '736ea445-8e7f-5096-b9b2-143d6deaf60b', 'k_pierre', 'Khary Pierre', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(551, 'f799ae74-a8b5-394d-9838-4c1d040979ba', 'ro_powell', 'Rovman Powell', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(552, 'bdbdb3f5-da55-c199-3fb7-e13b5f3f613b', 'an_bramble', 'Anthony Bramble', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(553, 'b458b953-9ea2-2b79-dbb6-0109e01ba7fa', 'rj_campbell', 'John Campbell', NULL, NULL, 'Right-hand bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(554, '6c1a5c74-182f-58b2-07a4-94d440410754', 's_hope', 'Shai Hope', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(555, 'c2feacd5-6a05-dc2c-3f54-0f78c498d5f0', 's_cotterrell', 'Sheldon Cottrell', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(556, 'b43bfd2a-d9c5-63f2-86e9-f97c0de7be29', 'j_holder', 'Jason Holder', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(557, '8685a9a2-e19d-325e-6304-d2832a8338cf', 'ro_chase', 'Roston Chase', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(558, '728a2406-e097-9d6a-17fb-e7cb7d571cd2', 'j_mohammed', 'Jason Mohammed', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(559, '39dc1b3a-0868-93e1-155a-759ea1740bb0', 'kee_paul', 'Keemo Paul', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(560, 'b7e3eb7a-e9ba-4252-4c8e-fb268b1c1829', 'e_lewis', 'Evin Lewis', NULL, NULL, 'Left-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(561, 'a937f966-dd61-0ee2-352d-b80454c8f909', 'os_thomas', 'Oshane Thomas', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(562, 'd9fe33c2-e72a-2a8d-37c5-bc715632f2cc', 'c_gayle', 'Chris Gayle', NULL, NULL, 'Left-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(563, '84909906-cdd2-d2a2-39a7-e772a8bc0f2c', 'a_russell', 'Andre Russell', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(564, '361aa1ae-4022-a4a3-ad92-d61ac3fe2765', 'kh_ahmed', 'Khaleel Ahmed', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(565, 'bca78589-fa0b-0ff7-67f1-f9f1da6e184c', 'a_rahane', 'Ajinkya Rahane', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(566, 'f23c3faa-35aa-3938-14ae-3a46df41f08d', 'k_yadav', 'Kuldeep Yadav', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(567, '0150a88b-efe7-4317-126a-917a213feaee', 'k_pandya', 'Krunal Pandya', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(568, 'a4b0b464-9e3d-de29-d38b-855d794a9358', 'c_pujara', 'Cheteshwar Pujara', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(569, '673d38ff-5c50-445c-154d-dbd42132869e', 'v_kohli', 'Virat Kohli', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(570, '8317d4ee-0de7-e8c6-0b31-26c43f719d17', 'u_yadav', 'Umesh Yadav', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(571, '4a9fe71d-8e9c-2a7c-101b-b2bfc5ee0804', 'h_vihari', 'H Vihari', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(572, '6fbdad8d-1ae0-15e4-5aa4-ce42d2bcb7c8', 'd_chahar', 'Deepak Chahar', NULL, NULL, 'Right-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(573, '120696f3-0c08-a3f8-58dd-0ba17825eea1', 'r_jadeja', 'Ravindra Jadeja', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(574, '9bbfa98d-7a98-9f3e-6a5d-62fe3384eb58', 's_ahmed', 'Mohammed Shami', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(575, '129cb43b-74b0-50c4-1dab-495b3bdf958e', 'y_chahal', 'YS Chahal', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(576, 'bf9bd63c-6e0f-7a60-87e9-e86adb5d39da', 's_iyer', 'Shreyas Iyer', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(577, '866c94a1-1f31-06d4-cd11-e332f59d0d32', 'l_rahul', 'Lokesh Rahul', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(578, '507d7847-06cf-0f77-0033-db67968919ac', 'i_sharma', 'Ishant Sharma', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(579, '4a53e1c1-81b1-fd27-1ef6-f8f7711ccbc4', 'm_pandey', 'Manish Pandey', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(580, '499ea3a6-e638-df98-1962-20362b337445', 'b_kumar', 'Bhuvneshwar Kumar', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(581, '0f3c7394-4f2e-f3ff-69d5-3cf7f02f0b0f', 'j_bumrah', 'JJ Bumrah', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(582, '078cd9ca-583b-2731-d4c2-ee6dfddc979b', 'r_pant', 'Rishabh Pant', NULL, NULL, 'Left-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(583, 'ce50f95c-431a-9358-5683-170426bc5acb', 's_dhawan', 'Shikhar Dhawan', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(584, '357e1a18-2a6e-aca2-c3c6-69ed5adaed54', 'rah_chahar', 'Rahul Chahar', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(585, 'deaac70c-45fc-611d-ff36-fa15b7301afc', 'k_jadhav', 'Kedar Jadhav', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(586, '0d15c436-9a07-0828-9b35-b0a9a593a545', 'rg_sharma', 'Rohit Sharma', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(587, 'b995c6ec-5253-c3db-9253-ee56150d52f4', 'na_saini', 'NA Saini', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(588, 'eebe8ed8-5e6b-6ddd-ec7b-734909232c2c', 'm_agarwal', 'Mayank Agarwal', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(589, '47d328df-8458-4f28-8dc1-a76e443a53a2', 'w_saha', 'WP Saha', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(590, 'e0b1a597-7001-8d28-c8f2-9f7927615ee5', 'w_sar_glenn', 'Sarah Glenn', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(591, '5af6004a-5835-f743-d140-a0fb91bd9944', 'w_az_monaghan', 'Alice Monaghan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(592, '3054735a-9d09-2447-ca0e-64f90b3a35c2', 'w_d_Kirstie', 'Kirstie Gordon', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(593, 'd5370ed5-a1e9-7993-a67e-77392cbe602c', 'w_ha_matthews', 'Hayley Matthews', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(594, '4ac483a1-0c4c-f4c1-8655-cfde390a949a', 'w_ac_atapattu', 'Chamari Atapattu', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(595, 'ef620b81-27c7-741e-46b9-c1b181dbffae', 'w_gl_adams', 'Georgia Adams', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(596, '21bd5d71-563f-0fb7-0c68-1d2a1e88b238', 'w_je_gunn', 'Jenny Gunn', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(597, 'addc6e53-b7e7-9661-50c6-b7b746a080f3', 'w_a_jones', 'Amy Ellen Jones', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(598, '3ddd0774-5bc3-d18e-b1a0-9b92a2249604', 'w_aj_freeborn', 'Abigail Freeborn', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(599, 'fb04c55d-83a5-acd5-262b-0c03ced13136', 'w_ke_bryce', 'Kathryn Bryce', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(600, 'ab4db264-d287-26e5-fec6-afa5b66827f4', 'w_joa_gardner', 'Joanne Gardner', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(601, '19f785be-d23a-2771-65b8-70f35523d01b', 'w_lf_higham', 'Lucy Higham', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(602, '5e416640-ebf1-353c-7927-2e9a2ec97147', 'w_tar_norris', 'Tara Norris', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(603, '5c3931f8-9481-aac0-b540-a767793a4b24', 'w_md_preez', 'Mignon du Preez', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(604, '30899efb-ac71-09b8-7206-5379afe430f7', 'w_ge_elwiss', 'Georgia Elwiss', NULL, NULL, 'Right-hand bat', 'Right-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(605, 'c3017f60-83ea-1c99-382f-3a134797b663', 'w_ss_mandhana', 'Smriti Mandhana', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(606, 'b22e6788-cdf2-a034-7c02-64fd363dac93', 'w_dan_gibson', 'Danielle Gibson', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(607, '0fed96ed-75fe-2f34-35ce-3465f0e6511c', 'w_ama_carr', 'Amara Carr', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(608, '92d3c5cd-bfed-6aed-ffc0-402fb5d12ba6', 'w_fr_wilson', 'Fran Wilson', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(609, 'e06d21df-1c0c-63fa-609c-8a3ca47c82c9', 'w_cla_nicholas', 'Claire Nicholas', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(610, '1c7a13c9-09be-323f-dfa0-88b2085091eb', 'w_db_deepti', 'Deepti Sharma', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(611, 'e2ab8d8f-8379-0345-e29b-af13a1501536', 'w_ell_mitchell', 'Ellie Mitchell', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(612, '4bbdc541-489d-2a70-7afe-541dbf35bf8c', 'w_fre_davies', 'Freya Davies', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(613, '3212d189-8533-206a-0662-0238829d102e', 'w_sop_luff', 'Sophie Luff', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(614, '8f636ebb-cdbf-646a-0704-48e717d5abff', 'w_son_odedra', 'Sonia Odedra', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(615, 'c7c65956-4393-c434-1d40-a9479f8e4cde', 'w_ale_griffiths', 'Alex Griffiths', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(616, '73ba0e3e-ecae-4648-23f3-887091f7f916', 'w_he_knight', 'Heather Knight', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(617, 'f3347aef-53ee-a8f0-6d46-10facfc17a44', 'w_nao_dattani', 'Naomi Dattani', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(618, '0aceda82-3455-c16d-0719-495f1a965116', 'w_rh_priest', 'Rachel Priest', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(619, 'f27d4430-3657-e28c-f0cf-3194a82a6c9d', 'w_a_shrubsole', 'Anya Shrubsole', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(620, '22a1cf0a-524f-bbeb-d82b-9e27f43a7677', 'i_sodhi', 'Ish Sodhi', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(621, '0b7431e3-eeeb-d9b7-1666-8e81c66b399f', 'arm_kapoor', 'Armaan Kapoor', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(622, '2b2da2e2-e00c-f755-1545-27a594683c2e', 'ni_kumar', 'Nitish Kumar', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(623, '491bb97a-2a0b-e3f0-7aa2-339dfb364f24', 'c_munro', 'Colin Munro', NULL, NULL, 'Left-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(624, '6418f1df-444b-b407-e05b-9a15ef83ff16', 'd_sammy', 'DJG Sammy', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(625, '550254b0-c16d-4e4a-ad4f-4a515ae36f80', 'a_fletcher', 'ADS Fletcher ', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium-Fast', NULL, NULL, NULL, 'No', NULL),
(626, 'f1c56725-1976-0677-9b27-3774ca6e6149', 'abr_khan', 'Abraash Khan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(627, '7b78caac-613e-73f9-705f-cd71464fe74f', 'fai_jamkhandi', 'Faisal Jamkhandi', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(628, 'cd6e5dce-87b2-2a9c-363d-cd73ed1fed97', 'ai_hasan', 'Shakib Al Hasan', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(629, '9b33c265-48ee-69ac-348b-accf41d189ce', 'c_pervez', 'Cecil Pervez', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(631, 'fe4c8a1d-d6e7-4fc1-4088-17635c83ef01', 'j_siddiqui', 'Junaid Siddiqui', NULL, NULL, 'Right-hand bat', 'Legbreak', NULL, NULL, NULL, 'No', NULL),
(632, '9805e6ec-7c23-c3d5-a613-5f85c8cf16bf', 'nd_singh', 'Newab Singh', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(633, '3f007bb8-d0b4-d706-7a77-21e09c36733d', 'b_hayat', 'B Hayat', NULL, NULL, 'Right Handed Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(634, '3e5c3739-dd8c-f7a3-ea7c-2352865f8165', 'g_munsey', 'George Munsey', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(635, '055e5b87-ad0a-9e2f-8b70-57f69cf681ab', 'l_simmons', 'LMP Simmons', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(636, '540aad1d-7888-4466-b1cf-5378002b932f', 's_afridi', 'SMSK Afridi', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(637, '3ff07373-b3f9-0d8d-4f28-d23b140ca0cf', 'tk_patel', 'Timil Patel', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(638, '419fa348-91a8-1623-d169-9d44e9fbb96d', 'w_riaz', 'Wahab Riaz', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(639, 'ffa1acc6-cb23-7e2f-242b-42cf2a83af64', 'b_cutting', 'Ben Cutting', NULL, NULL, 'Right-hand bat', 'right-arm fast-medium', NULL, NULL, NULL, 'No', NULL),
(640, 'b95a2ec7-a433-589d-ffa0-51b0e92107c4', 'd_jacobs', 'Davy Jacobs', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(641, '491e184c-06e0-b308-42e6-87cbbe452b2a', 'an_rath', 'Anshuman Rath', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(642, '60aab696-6964-2d8a-d686-2ee66132de8c', 'du_plessis', 'Faf du Plessis', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(643, '6bbe574a-2bbb-8b46-342e-96331f4ff1ba', 'kf_phill', 'Kyle Fitzroy Phill', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(644, '789b85a7-b0df-9a95-c103-131eb75df81a', 's_dhindsa', 'Satsimranjit Dhindsa', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(645, 'a9c2e61a-350c-339f-aa2a-348362f5eab3', 's_sharif', 'Safyaan Sharif', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(646, '7a973642-2e57-0d37-5d9f-0b46b53a9132', 'm_hafeez', 'Mohammad Hafeez', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(647, 'f5802d69-1911-f373-b41a-db5de1b55af4', 's_rutherford', 'Sherfane Rutherford', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium fast', NULL, NULL, NULL, 'No', NULL),
(648, '7677959d-37c2-2bb8-3eda-f2388f0be737', 'as_gill', 'Akash Gill', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(649, '7434a098-c998-3610-a2fb-45e42e5874bc', 'k_williamson', 'KS Williamson', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(650, '18a6e440-e55e-8464-24e3-48e6b33c93ff', 'moh_nawaz', 'Mohammad Nawaz', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(651, '97844664-3bd2-5511-fed4-f4000f035249', 'sha_ahmadzai', 'Shahid Ahmadzai', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(652, '23b0479b-5563-0dbf-d662-ae2cb7d88199', 'e_nawaz', 'E Nawaz', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(653, '89ede5d1-1f4b-4e1c-8d4b-ca02780054c3', 'ja_neesham', 'James Neesham', NULL, NULL, 'Left-Hand Bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(654, 'ea85d836-f911-8dfe-c22e-7f3637fa0ca3', 'nav_dhaliwal', 'Navneet Dhaliwal', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(655, 'cb71d47f-5aac-9e5b-f593-24007fd98300', 'sha_khan', 'Shadab Khan', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(656, '1e9f5c65-b949-f1e5-3fff-714b8de95bad', 'r_berrington', 'Richie Berrington', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(657, '670f38c7-f309-58fd-5861-56a50917affb', 'w_k_george', 'Katie George', NULL, NULL, 'Left-hand bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(658, '903a691b-34b6-a9c1-fa1a-3bd5e09644fb', 'w_be_langston', 'Beth Langston', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(659, 'cfa9c8e8-dcab-7b31-2653-415d54ef0d12', 'w_kat_levick', 'Katie Levick', NULL, NULL, 'Right-Hand Bat', 'Right-arm Legbreak', NULL, NULL, NULL, 'No', NULL),
(660, 'b1108996-cccc-f27f-e06d-61a2bad2106d', 'w_lm_kasperek', 'Leigh Kasperek', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(661, 'fd238534-bdf0-4108-5a21-98f0397a68cc', 'w_d_richards', 'Alice Richards', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium fast', NULL, NULL, NULL, 'No', NULL),
(662, '05e3c62a-4453-a278-979e-3d94c3630a5c', 'w_hel_fenby', 'Helen fenby', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(663, '45898a6e-3df5-eedb-1390-a8e0b91ebe67', 'w_cl_tryon', 'Chloe Tryon', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(664, '30d358a4-6002-f23d-8005-02ce0f57a4a8', 'w_la_winfield', 'Lauren Winfield', NULL, NULL, 'Right-hand bat', NULL, NULL, NULL, NULL, 'No', NULL),
(665, '2ea77798-65c8-2d76-7830-b9b6178db4e5', 'w_cor_griffith', 'Cordelia Griffith', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(666, 'fb842b4b-18bb-2110-b2b3-b33c31bc28d8', 'w_j_rodrigues', 'Jemimah Rodrigues', NULL, NULL, 'Right-hand batsman', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(667, '7ca444d5-5b3b-1515-5c50-523cc7b0546a', 'w_hol_armitage', 'Hollie Armitage', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(668, '8939a063-a1b5-b4df-ab6e-dd78bd44b768', 'w_geo_davis', 'Georgia davis', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(669, '2411e8cd-c383-a25c-f5c2-d21369427cdb', 'w_bes_heath', 'Bess Heath', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(670, 'c0d775e3-50c1-1450-afb0-7d032a996f2b', 'w_s_Linsey', 'Linsey Smith', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(671, '9cc8c045-e845-d460-623f-d9fa99a4112c', 'w_al_healy', 'Alyssa Healy', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(672, 'c2dc8c04-f3c7-d1fe-b5a7-c54f7904069b', 'w_ka_brunt', 'Katherine Brunt', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Mem Fast', NULL, NULL, NULL, 'No', NULL),
(673, '58cbef86-3b32-5661-59f2-53d7bcb0e48e', 'w_ayl_cranstone', 'Aylish Cranstone', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(674, '3a921685-3509-69e9-1308-0bb2e2a0201c', 'w_b_smith', 'Bryony Smith', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(675, '5838cb44-6f56-ea4e-ee07-82cde02418ab', 'w_gra_gibbs', 'Grace Gibbs', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(676, 'd3903344-c52b-67cd-4771-43a560c3fea7', 'w_han_jones', 'Hannah Jones', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(677, '8e9b9ee7-e4f8-c0c3-8f83-2c188c326c2d', 'w_mad_villiers', 'Mady Villiers', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(678, '9ecfcda6-933b-30fe-67a4-d697c0f9f561', 'w_li_lee', 'Lizelle Lee', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(679, '52887ec3-9fe5-20e7-d131-55866bb8316c', 'w_amy_gordon', 'Amy Gordon', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(680, '3e0fce44-9c8e-9fa2-75da-47ac635c7b80', 'w_dv_niekerk', 'Dane van Niekerk', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(681, '77690a0e-f12f-c156-83a4-9699ccc31dfe', 'w_la_marsh', 'Laura Marsh', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(682, 'e146143d-3e05-553e-8b25-587baadd4560', 'W_eva_gray', 'Eva Gray', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(683, '7dfe4ca6-38e0-a0b7-731d-5cebb2f8b9f1', 'w_na_sciver', 'Natalie Sciver', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(684, 'f1522466-a34c-1733-f389-8ea6cbc495e2', 'w_ma_kapp', 'Marizanne Kapp', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(685, '898ebfed-08f1-9dbf-5307-2cfdcbf090b0', 'w_rhi_southby', 'Rhianna Southby', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(686, 'b9526dd0-d1f8-795b-847d-9dc24e2e93be', 'w_gwe_davies', ' Gwenan Davies', NULL, NULL, 'Left-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(687, '7b8f4ff5-d2e6-7f35-ca7e-8b4c374415cb', 'w_sa_taylor', 'Sarah Taylor', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(688, '568bff0a-d02c-7f71-6daf-52199316bb3f', 'l_wright', 'Luke Wright', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(689, 'ad1f6934-6264-ad1b-15da-82e2bd2944ed', 'ty_mills', 'TS Mills', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(690, '99ac6f04-e8c2-032a-876f-dec5db1ac73c', 'jc_archer', 'Jofra Chioke Archer', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(691, 'df785e81-ef6b-7099-a702-3ba7c9cc5d67', 't_haines', 'Tom Haines', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(692, '39bbd832-23ba-fceb-808f-f2daf5abf62c', 'a_sakande', 'Abidine Sakande', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(693, 'ccfef74b-7d81-9330-2187-c84c2edf1739', 'd_briggs', 'Danny Briggs', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(694, '9bd4d1df-ed95-34e5-f6a7-318f24b19141', 'o_robinson', 'Ollie Robinson', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(695, '02b7295a-d7d2-63cc-cb82-bed5c656dee6', 'l_wells', 'Luke Wells', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(696, 'e6830a43-f7fe-2102-c3a6-7e0d2f02df35', 'd_rawlins', 'Delray Rawlins', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(697, '992737ad-2adc-fe43-b587-be4a36588838', 'g_garton', 'George Garton', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(698, 'ae3e3134-035c-6ecf-6cd7-60e4b5e4e3f4', 'sv_zyl', ' Stiaan van Zyl', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(699, '1a73134a-c027-e4f0-3a6a-de24408fe0b6', 'h_finch', 'Harry Finch', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(700, 'dedac522-7c97-ceee-a3e9-a359ddc0553d', 'w_beer', 'Will Beer', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(701, 'bad68f43-5194-9ac2-f840-bce92ad15954', 'b_brown', 'Ben Brown', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(702, '97a202ee-e7ba-9457-3d5b-4792c5c03660', 'ale_carey', 'Alex Carey', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium-Fast', NULL, NULL, NULL, 'No', NULL),
(703, '783f048f-0841-ee90-7f2b-4a2060838a69', 'wil_sheffield', 'Will Sheffield', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(704, '99c67073-84be-3004-8cba-06b3a3d24574', 'ra_khan', 'Rashid Khan', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(705, 'b70228b4-c5ca-9c88-c8eb-add44e307eba', 'c_jordan', 'Chris Jordan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(706, '93e9ea3c-70bd-f4f8-a2fe-4e6c48ac40df', 'p_salt', 'Philip Salt', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(707, '9e6fa218-e2db-9c03-dde4-a6d4a731bc2c', 'd_wiese', 'D Wiese', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(708, '0163f5bb-c940-dc83-f076-9fed4ae3d930', 'l_evans', 'Laurie Evans', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(709, 'df5717d4-03fc-a614-8950-da494d93b40c', 'aa_thomason', 'Aaron Thomason', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(710, 'bc9e60e2-bb67-250c-b70d-a7794a29241d', 'ane_kapil', 'Aneesh Kapil', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(711, '52e95958-3862-fdec-f0e4-e5af777e6527', 'r_topley', 'R Topley', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(712, '3de62ff7-9387-4dc1-c0d1-7a645627596f', 'cm_taylor', 'Callum Taylor', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(713, 'a9bd43b2-5c39-366f-6913-6bb014691bce', 'mar_labuschagne', 'Marnus Labuschagne', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(714, '6045ea0d-771d-2e79-5f87-49fff3f64957', 't_gugten', 'Timm van der Gugten', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(715, 'd967df8d-2e20-934a-1db4-5f503e01dee8', 'c_ingram', 'Colin Ingram', NULL, NULL, 'Left-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(716, '107058fc-18b9-ae1d-8db8-ffd82536c117', 'gra_wagg', 'Graham Wagg', NULL, NULL, 'Right-Hand Bat', 'Left-arm medium', NULL, NULL, NULL, 'No', NULL);
INSERT INTO `sports_players` (`PlayerID`, `PlayerGUID`, `PlayerIDLive`, `PlayerName`, `PlayerPic`, `PlayerCountry`, `PlayerBattingStyle`, `PlayerBowlingStyle`, `PlayerBattingStats`, `PlayerBowlingStats`, `PlayerSalary`, `IsAdminSalaryUpdated`, `LastUpdatedOn`) VALUES
(717, 'a601fec6-5d63-6e9b-de59-49c918249168', 'rom_walker', 'Roman Walker', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(718, 'c548f18d-1b12-aba4-287f-011306a4a94a', 'nic_selman', 'Nicholas Selman', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(719, '8d50e57d-e69f-d074-d49a-589c0795e4da', 'm_hogan', 'Michael Hogan', NULL, NULL, 'Right-hand bat', 'Right-arm fast-medium', NULL, NULL, NULL, 'No', NULL),
(720, '8a72a85a-6cbf-0496-df9d-ef7c824c89d7', 'm_lange', 'Marchant de Lange', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(721, '529cd381-5d1c-72cc-792b-b4975e8f244f', 'kaz_szymanski', 'Kazi Szymanski', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(722, 'a1ee6970-f0f7-2da7-b51f-15facd979df5', 'luk_carey', 'Lukas Carey', NULL, NULL, 'Right-Hand Bat', 'Right-arm fast-medium', NULL, NULL, NULL, 'No', NULL),
(723, '39402425-177e-de98-fe30-2cb6584c97b9', 'billy_root', 'William Thomas Root', NULL, NULL, 'left-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(724, 'ef3619e5-d131-bdef-ad79-882be8e04955', 'ru_smith', 'Ruaidhri Smith', NULL, NULL, 'Right-hand bat', 'Right-arm fast-medium', NULL, NULL, NULL, 'No', NULL),
(725, '7191ad87-ca47-d478-34ed-ae70c25c3974', 'tom_cullen', 'Tom Cullen', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(726, '2adfcb66-e589-c5fc-6dac-cc874e4b1132', 'dav_lloyd', 'David Lloyd', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(727, 'e40f1a4b-590b-244b-11f2-7d7fbba4b5d6', 'jam_mcIlroy', 'Jamie McIlroy', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(728, 'e97940c8-9d1d-b059-eefa-139c1d567394', 'pre_sisodiya', 'Prem Sisodiya', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(729, 'f11dddb6-9bb0-5d48-2894-fd16fbbc95a9', 'and_salter', 'Andrew Salter', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(730, '439378a1-dc36-5102-06ac-3b045d430705', 'con_brown', 'Connor Brown', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(731, 'ca1728bc-ecb5-12b2-9b4e-8b96824ec698', 'cra_meschede', 'Craig Meschede', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(732, '3d496e43-5f91-481b-5051-39d410215e35', 'kie_bull', 'Kieran Bull', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(733, '8c8dfb35-41a3-bd5d-7409-d7d8851c539b', 'dan_douthwaite', 'Daniel Douthwaite', NULL, NULL, 'Right-Hand Bat', 'Right Arm Medium', NULL, NULL, NULL, 'No', NULL),
(734, 'e852eb5e-2c93-0e89-3427-c65d8a739c38', 'jer_lawlor', 'Jeremy Lawlor', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(735, 'f73b0ccb-22d6-0197-92ed-7ac2776e6f02', 'owe_morgan', 'Owen Morgan', NULL, NULL, 'Right-Hand Bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(736, '5a4682c3-9c01-b75d-3838-bae8b41ee710', 'f_zaman', ' F Zaman', NULL, NULL, 'Left-hand bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(737, 'ee7f5bc5-0e68-08c6-74d0-f4414d5f5abe', 'kir_carlson', 'Kiran Carlson', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(738, '642a8cc6-86ee-3906-99b8-b5212261e0c5', 'cha_hemphrey', 'Charli Hemphrey', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(739, 'a3162e3c-0bfa-1546-cb94-4481a6fd5037', 'chr_cooke', 'Chris Cooke', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(740, 'ab8eed4a-2029-0758-5f86-5a0a59da1be4', 'sb_zafar', 'Saad Bin Zafar', NULL, NULL, 'Left-hand bat', 'Left-Arm ordhodox', NULL, NULL, NULL, 'No', NULL),
(741, 'd56aff65-3298-d2b7-f817-bab9a2cabedb', 'c_walton', 'Chadwick Walton', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(742, '60878c5d-98dc-e548-b2f5-7c8d866b7a9a', 'ry_pathan', 'Rayyan Pathan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(743, 'f4920f62-c92a-605d-eaef-341ae2677d82', 'd_sams', 'Daniel Sams', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(744, 'd6c1b502-5450-a6e5-1f92-ca69741d4205', 'rvd_dussen', 'Rassie van der Dussen', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(745, '237928c5-9962-56af-15ea-44446493c84a', 'as_ali', 'Asif Ali', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(746, '84ddc101-272e-e743-a9e0-7b27ed62c181', 'hayd_walsh', 'Hayden Walsh', NULL, NULL, 'Left-hand bat', 'Legbreak googly', NULL, NULL, NULL, 'No', NULL),
(747, 'cd1231f6-8513-4d15-2d28-50ed6768d93c', 'm_rippon', 'Michael Rippon', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Chinaman', NULL, NULL, NULL, 'No', NULL),
(748, '119e504a-c330-a603-d168-30f2af15635e', 'har_thaker', 'Harsh Thaker', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(749, '881eafc9-6a88-4b38-5e9f-415b75b501aa', 'jj_smit', 'JJ Smit', NULL, NULL, 'Right-hand bat', 'left-arm medium fast', NULL, NULL, NULL, 'No', NULL),
(750, 'a90feac5-5baf-c591-39c0-a68d49fd28c7', 'a_phehlukwayo', 'Andile Phehlukwayo', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(751, '0e5a0da4-b078-e3ec-1c9a-6f6ef0185bc5', 'mat_nandu', 'Matthew Nandu', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(752, '6401fd1c-b5aa-80b8-abb2-307fdf8ed978', 'r_cheema', 'Rizwan Cheema', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(753, '80a0a4e1-76a6-4b6a-0788-53c251c41783', 'a_summers', 'Aaron Summers', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(754, '06c5956b-fb02-8bdb-2813-b20a01dce7e1', 't_visee', 'Tobias Visee', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(755, 'a15ee45a-9db6-c09c-d691-610a5ce7aebd', 's_malik', 'Shoaib Malik', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(756, '49e45108-0474-8c1f-cd9a-012c23d7719f', 't_southee', 'TG Southee', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(757, '21860506-7ebd-f88b-edd5-8e82baf7a449', 'maa_khan', 'Ali Khan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(758, 'e0f822f0-87aa-ea5d-429c-d32f18c75617', 'jp_duminy', 'Jean-Paul Duminy', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(759, '5bc16d7c-7cd5-08d8-ed0b-4b4592c4b528', 'd_bravo', 'Dwayne Bravo', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(760, '2dac335e-4d4b-6035-00eb-4b6423f074b5', 's_sohal', 'Sunny Sohal', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(761, '8cd09430-91b2-b703-3b4e-1d2ec3d306a4', 'gdr_eranga', 'Romesh Eranga', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(762, '987a0efd-8f4e-ea61-0be3-e214490db163', 'h_tariq', 'Hamza Tariq', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(763, '5a489434-e353-3dd7-8c25-d74b1f55dfc7', 'p_meekeren', 'Paul Van Meekeren', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(764, 'a2b7619f-545d-9429-7f5b-f269c8701dbe', 'c_lynn', 'Chris Lynn', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(765, '7222f038-53fd-532e-8342-488ded636a89', 'ks_rehman', 'Kaleem Sana', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(766, '10a21e92-2687-46b9-e7c8-2299db289b2e', 's_anwar', 'Shaiman Anwar', NULL, NULL, 'Right-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(767, '88c320b9-6be6-f5ad-9e52-d93f7209df45', 'u_akmal', 'Umar Akmal', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(768, '4f9da189-7297-63f3-4eec-33e916015081', 'uma_Ghani', 'Umair Ghani', NULL, NULL, 'Mohammad Umair Ghani', 'Leg-Break', NULL, NULL, NULL, 'No', NULL),
(769, 'd2841c3e-b4f0-5abd-ca62-dc0a0bfdebcf', 'd_smith', 'Dwayne Smith', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(770, 'f261625d-38c6-9a32-0ff8-bf08f960f797', 'm_irfan', 'Mohammad Irfan', NULL, NULL, 'Right-Hand Bat', 'Left-handed', NULL, NULL, NULL, 'No', NULL),
(771, '3a55538a-9723-e213-966c-50b9a51904fa', 'var_sehdev', 'Varun Sehdev', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(772, '4deea924-9a2c-bc94-c638-e032ad952cd4', 's_kami', 'Sompal Kami', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(773, '2ab10416-f754-b1e1-e3ea-4ca320458cc7', 'r_emrit', 'RR Emrit', NULL, NULL, 'Right-hand bat', 'Right-arm medium fast', NULL, NULL, NULL, 'No', NULL),
(774, '108c739a-1e17-f46f-969f-50b65bd32882', 'n_zadran', 'N Zadran', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(775, 'c66fc397-25d2-b811-62a9-a63f0ec87818', 'shah_khan', 'Shahrukh Khan', NULL, NULL, 'Right-hand bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(776, 'eb6e2625-5625-fcad-47d9-62ca1cb203a5', 'rsj_sinivas', 'RS Jaganath Sinivas', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(777, 'f22b0a5d-220f-fc47-a4fb-457efa85de4c', 'k_vignesh', 'Krishnamoorthy Vignesh', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(778, '713f6023-f03f-b070-6cf4-eb078aa4bb3f', 'js_kumar', 'J Suresh Kumar', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(779, 'c39f9dc7-50fd-900e-a559-174ba228d65c', 's_ajithram', 'S Ajith Ram', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(780, '7e245dae-11cf-72d8-7a50-5993001afd1e', 's_manigandan', 'S Manigandan', NULL, NULL, 'Right-Hand Bat', 'Legbreak', NULL, NULL, NULL, 'No', NULL),
(781, '032d1dfe-b040-42eb-8f98-87de4c807967', 'a_venkataraman', 'Ashwin Venkataraman', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(782, 'de850d07-97e5-132d-64b8-c21508bcb03b', 'su_babu', 'Suresh Babu', NULL, NULL, 'Left-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(783, '8ff67835-6ca8-cec9-08f3-68b8183f3b0e', 't_natarajan', 'T Natarajan', NULL, NULL, 'Left-Hand Bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(784, '8b851e4c-3f14-aeba-7717-edf5128b7da9', 'nm_ashik', 'N Mohammed Ashik', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(785, '35936d1b-dfad-bdff-abe9-37f23bb3f8ed', 'pdrj_paul', 'Pradosh Ranjan Paul', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(786, '075d48d3-d8b1-131c-e663-bfeea602f16b', 'm_raja', 'M Raja', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(787, '4c1d48a9-cfe4-726b-8df8-0fa4d7999f1f', 'mal_rangarajan', 'Malolan Rangarajan', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(788, 'ee15cb36-4c59-2bc1-e193-2cc529f0ffa9', 'b_sitaram', 'B Anirudh Sita Ram', NULL, NULL, 'Left-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(789, '1ccc67ca-4014-f777-a1c0-d9ac3dd2b6ca', 'mp_rajesh', 'M Prasanth Rajesh', NULL, NULL, 'Right-Hand Bat', 'Legbreak', NULL, NULL, NULL, 'No', NULL),
(790, '51a0e44c-60d6-8441-4153-d6a730f414fc', 'a_mukund', 'Abhinav Mukund', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(791, 'f37a592d-0e86-31ce-6c1c-4b6f30c75f79', 'ak_srinaath', 'Akkil Srinaath', NULL, NULL, 'Left-hand bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(792, '798307f6-25c8-42f2-aa21-23450d6bf508', 'an_dhas', 'Antony Dhas', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(793, 'f94ac86d-ae8b-67c7-2308-bbea66c089b4', 'm_adnankhan', 'Muhammed Adnan Khan', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(794, 'bc5d1bda-496d-cf74-502b-99591d6dfbcc', 'n_dickwella', 'Niroshan Dickwella', NULL, NULL, 'Left-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(795, '26c7dc94-4033-641d-9b98-6a029fa484d8', 'yax_patel', 'Yax Patel', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(796, '92e5e57a-cd97-fa89-0ca9-68579360f4c1', 'r_gunasekera', 'Ruvindu Gunasekera', NULL, NULL, 'Left-Hand Bat', 'LegBreak Googly', NULL, NULL, NULL, 'No', NULL),
(797, 'dee3a1f6-f2ba-6597-69cc-83251880c8f9', 'm_cross', 'Matthew Cross', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(798, '7d0b1bf7-2378-e996-33b8-1fb7d405dfbb', 'd_heyliger', 'Dillon Heyliger', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(799, '914b7c7d-1e0d-1e28-7b21-6e7151cc6203', 'a_evans', 'Alasdair Evans', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium-Fast', NULL, NULL, NULL, 'No', NULL),
(800, '9a31d634-2740-c2fe-d849-db8ac4f07a44', 'bup_singh', 'Bupinder Singh', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(801, '5fdb8f25-0240-c796-d9e0-071beac91a28', 'i_udana', 'Isuru Udana', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(802, '85ea22c4-89f7-c4ac-18bf-74cef3316b90', 'k_coetzer', 'Kyle Coetzer', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(803, '19c043aa-b21b-b9a3-0285-37e09722ac55', 'g_bailey', 'George Bailey', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(804, '7712aade-8b62-704e-2765-c952fc6d0268', 's_kuggeleijn', 'Scott Kuggeleijn', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(805, '7bab078d-964e-c4c9-f57f-2f42af208e28', 's_taylor', 'SR Taylor', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(806, 'cab6f397-5f0a-c45f-80a1-809a71db27cc', 'niz_khan', 'Nizakat Khan', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(807, 'fb56d9af-790d-95d0-864f-767c6b8fb954', 'a_devcich', 'Anton Devcich', NULL, NULL, 'Left-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(808, '1e9c1fef-bb30-dd21-2a82-f80ac8e96956', 'ash_deosammy', 'Ashtan Deosammy', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(809, '52d0d783-a6ca-224f-41e2-63c5c06d9527', 'fa_ahmed', 'Fawad Ahmed', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(810, 'd99b8823-24ed-9250-3941-a54a58697da9', 's_abbott', 'Sean Abbott', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(811, '5a7c98a2-ec4d-3078-c994-d1736bb9aae5', 'as_khan', 'Arslan Khan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(812, '617310b3-c6cd-ebf5-5989-bb0134a77bd2', 'n_dutta', 'Nikhil Dutta', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(813, '62c46e02-a19a-22cf-7c50-e356607eb9e3', 'd_chandimal', 'Dinesh Chandimal', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(814, '6b4d26a3-042f-4f68-5cef-4654a271e457', 't_perera', 'Thisara Perera', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(815, '89e17168-5168-4577-8d9a-31f441cc714c', 't_boult', 'Trent Boult', NULL, NULL, 'Right-Hand Bat', 'Left-handed', NULL, NULL, NULL, 'No', NULL),
(816, '1309daae-b23c-de21-d20c-2e4a45e97125', 'c_macLeod', 'Calum MacLeod', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(817, '5fa15803-e93b-e767-39a7-82bce6874d3d', 'm_gony', 'Manpreet Gony', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(818, '0a85017e-e16b-a27d-b311-13501d185068', 'm_mcclenaghan', 'Mitchell McClenaghan', NULL, NULL, 'Left-Hand Bat', 'Left-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(819, '48bc342f-6729-00b7-5725-4d4bee48b6b1', 'b_mccullum', 'Brendon McCullum', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(820, 'b2420f9b-fa82-351d-d2cb-c8eaf492ae07', 'h_klaasen', 'Heinrich Klaasen', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(821, '4eb03375-dc00-57d4-7701-af0bec44a658', 'y_singh', 'Yuvraj Singh', NULL, NULL, 'Left-Hand Bat', 'Left-handed', NULL, NULL, NULL, 'No', NULL),
(822, '9f0f587d-3681-31b1-0bed-60dec570ec8e', 'mr_montfort', 'Mark Montfort', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(823, '88f065bd-5eab-79f8-a300-f9342d8b5c81', 'c_green', 'Christopher Green', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(824, '5b57b92d-1452-b3d0-d050-73ad48702c67', 'jas_singh', 'Jasdeep Singh', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(825, '9f62d299-c6f2-98b4-c224-3519b8d13e45', 'j_gordon', 'Jeremy Gordon', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(826, '8e26e10b-dff1-7e6c-0aae-19ed0522e604', 'ra_thomas', 'Rodrigo Thomas', NULL, NULL, 'left-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(827, 'bb7d067f-bd99-b6b0-4831-649487ed5d82', 'rav_singh', 'Ravinderpal Singh', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(828, '92160766-c2a2-d640-a5a8-f1db18aa8297', 'm_henriques', 'Moises Henriques', NULL, NULL, 'Right-Hand Bat', 'Right-handed', NULL, NULL, NULL, 'No', NULL),
(829, 'ec16a483-cfc9-bbf7-a451-b824587bb01b', 'sd_lamichhane', 'Sandeep Lamichhane', NULL, NULL, 'Right-hand batsman', 'Legbreak googly', NULL, NULL, NULL, 'No', NULL),
(830, '04a20ad0-e051-bbc0-2d73-ba6aba5eddaa', 's_nazar', 'Salman Nazar', NULL, NULL, 'Left-hand bat', 'Left-Arm ordhodox', NULL, NULL, NULL, 'No', NULL),
(831, '5c0c765d-2bfa-f1db-361b-5983c282f38e', 'r_keogh', 'Rob Keogh', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(832, '91e97233-fc2a-6c68-2d4b-e4795fc7bcf0', 'a_rossington', 'Adam Rossington', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(833, '7ad9e549-a534-7911-854c-1b68fc88571a', 'r_levi', 'Richard Levi', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(834, '459ce691-e8bb-6294-00f9-1bba3f431dec', 'ric_vasconcelos', 'Ricardo Vasconcelos', NULL, NULL, 'Left-Hand Bat', 'Right Handed', NULL, NULL, NULL, 'No', NULL),
(835, 'bf54d67d-ca98-7564-1393-18dffa7934ec', 'c_thurston', 'Charlie Thurston', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(836, 'e4350d99-a177-6fab-88af-34bc0326a4ad', 'a_wakely', 'Alex Wakely', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(837, '11518401-dbe4-8857-4fad-820b52bfcc9e', 'fa_ashraf', 'Faheem Ashraf', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(838, '4d3574e1-8bdd-08ce-a952-e3ca1889e8ff', 'tom_sole', 'Tom Sole', NULL, NULL, 'Right-hand bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(839, 'b7836e70-f4b0-a0e6-7c2c-2795642577c7', 'jo_cobb', 'Josh Cobb', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(840, '5fb4ea4e-7fb9-28e7-0b8a-660ecf589cd0', 'b_sanderson', 'Ben Sanderson', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(841, '990c64a3-70d2-dd0b-3187-6189d5e9174c', 'd_pretorius', 'Dwaine Pretorius', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(842, '264552ab-1382-b683-a9a5-a4a04fe47fef', 'l_procter', 'Luke Procter', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(843, 'd5f0ea5b-2f62-c06c-1944-2ecba053eabd', 'b_hutton', 'Brett Hutton', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(844, 'f131818e-a69d-7506-5f9a-05b8372a5b70', 's_zaib', 'Saif Zaib', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(845, 'e02a07a3-4865-829a-46c0-a29e1eeab991', 'jac_white', 'Jack White', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(846, 'e933dc51-5a27-cead-dea4-2103917f8475', 'g_white', 'Graeme White', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(847, '72a42570-befd-4657-f68f-a8e5531375ba', 'r_newton', 'Rob Newton', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(848, '851b74ad-574a-1bfa-0093-82cfff2da92d', 'n_buck', 'Nathan Buck', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(849, '049e8f69-5e26-ef71-e8b7-62fb55d1109d', 'b_muzarabani', 'Blessing Muzarabani', NULL, NULL, 'Right-hand bat', 'Right-arm fast-medium', NULL, NULL, NULL, 'No', NULL),
(850, 'a1ae4188-8ca5-8f24-32df-775cde07807d', 'm_coles', 'Matt Coles', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(851, '1ed1d1cc-3da7-191b-cf40-41b8dbe698fb', 'be_curran', 'Ben Curran', NULL, NULL, 'Left-hand bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(852, '687cdd88-eccd-b416-a60a-e94ca22dce20', 'mat_potts', 'Matthew Potts', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(853, '8b75753c-30b7-6758-31f9-bf1ad4d13ab6', 'jac_campbell', 'Jack Campbell', NULL, NULL, 'Right-Hand Bat', ' Left-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(854, '472650a9-30d3-748e-8c02-23d850541342', 'g_harding', 'George Harding', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(855, '34a3377d-a10e-62b5-30c5-9d75c7675917', 'ben_whitehead', 'Ben whitehead', NULL, NULL, 'Right-Hand Bat', 'Legbreak googly', NULL, NULL, NULL, 'No', NULL),
(856, 'a893bfbd-798f-3e5c-b9c2-b17e7878ec0c', 'b_stokes', 'Ben Stokes', NULL, NULL, 'Left-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(857, 'ab0d2c82-9ec6-4338-896a-ac63ac5f3387', 'c_rushworth', 'Chris Rushworth', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(858, '37ffb2a0-d4f9-5564-e75b-7b18d3b4ca6c', 'sco_steel', 'Scott Steel', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(859, '8253a98d-9ba2-d6b4-ee46-49976758f1b3', 'j_coughlin', 'Josh Coughlin', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(860, 'f982691a-0d85-c761-72ae-22813a5b6ab8', 'c_steel', 'Cameron Steel', NULL, NULL, 'Right-hand batsman', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(861, '0dd568c3-42fd-738d-55a1-e21c60889ec6', 'c_bancroft', 'CT Bancroft', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(862, 'fc1322cb-3ab5-6341-3eb7-4f4584e02b73', 'darc_short', 'D\'Arcy Short', NULL, NULL, 'Left-Hand Bat', 'Slow left-arm chinaman', NULL, NULL, NULL, 'No', NULL),
(863, 'c5a3f026-3c7e-8e80-3552-84e5bd6574f1', 'b_carse', 'Brydon Carse', NULL, NULL, 'Right-hand batsman', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(864, '639e53c6-b972-319d-508e-9cf08d34f615', 'j_burnham', 'Jack Burnham', NULL, NULL, 'Left-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(865, '5bb2f06d-44b5-4662-8a01-7b48bf44e841', 'p_handscomb', 'Peter Handscomb', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(866, '681becd7-095c-bff4-c036-2f728a512561', 'gar_harte', 'Gareth Harte', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(867, '8b2a399c-7ffa-7937-3c15-1bac0d8611f3', 'g_main', 'Gavin Main', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(868, '3794ba24-a831-31fd-1e83-32ee353f9464', 'w_smith', 'Will Smith', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(869, '296566e6-9f07-790c-2703-91c40892c868', 'n_eckersley', 'Ned Eckersley', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(870, '786aba31-ef1d-0acd-9cf5-58968df4f83b', 'a_lees', 'Alex Lees', NULL, NULL, 'Right-hand batsman', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(871, 'dc695f26-ac54-05cb-ea49-e7b5a670d6a4', 's_poynter', 'Stuart Poynter', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(872, '22e4eb91-b3cf-849c-9e49-3978082bb176', 'n_rimmington', 'Nathan Rimmington', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(873, '66bfaf68-8602-4bd6-18ee-0b9d9d157624', 'r_pringle', 'Ryan Pringle', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(874, 'f37cd10a-1553-0829-1e5a-ac21f2e5ecef', 'b_raine', 'Ben Raine', NULL, NULL, 'Right-hand batsman', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(875, 'a7e915c2-340b-f143-d162-7b50b43a7fca', 'j_weighell', 'James Weighell', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(876, 'de765df5-4fe4-3b23-fc5f-1a32d4368b47', 'm_wood', 'Mark Wood', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(877, 'f2c75f00-274d-0319-0ab3-6fd3903ebbc0', 'g_clark', 'Graham Clark', NULL, NULL, 'Right-hand batsman', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(878, 'abb35462-e745-64ea-4d79-1296a3877712', 'l_trevaskis', 'Liam Trevaskis', NULL, NULL, 'Right-hand batsman', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(879, 'd5aec555-cbaa-5e29-ebf5-400ab93d34d2', 'mat_salisbury', 'Matt Salisbury', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(880, '125e4dea-fb12-8cb7-be68-07f5d958fb80', 'd_payne', 'David Payne', NULL, NULL, 'Right-Hand Bat', 'Left-arm fast medium', NULL, NULL, NULL, 'No', NULL),
(881, '6188482d-45b7-69c8-3832-0071a0d62cf9', 'm_taylor', 'Matthew David Taylor', NULL, NULL, 'Right-Hand Bat', 'Left-arm fast', NULL, NULL, NULL, 'No', NULL),
(882, '544cca6e-cfa7-da10-d8d7-9d7454c1c244', 'tj_price', 'Tom Price', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(883, 'e6f4e8f3-5df5-4c56-e4d5-b0d913eec959', 'a_tye', 'AJ Tye', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(884, 'b700ef19-44fd-f4f2-c5c1-127055bba677', 'eth_bamber', 'Ethan Bamber', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(885, '45f06bba-38ff-6ffe-7634-47dc5cbc58c4', 'sg_whittingham', 'Stuart Whittingham', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast Medium', NULL, NULL, NULL, 'No', NULL),
(886, '21a24ba8-aa51-f5f7-0fa1-07fbb1b5430a', 'gh_roderick', 'Gareth Roderick', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(887, '92fbe39d-0c57-78d0-714b-58df91f32e73', 'i_cockbain', 'Ian Cockbain', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(888, 'fd6be19f-f02a-1125-7141-c3c99bc28e8b', 'gt_hankins', 'George Thomas Hankins', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(889, '864bbad2-7151-c6ee-9a50-013b7176dc0c', 'j_bracey', 'James Robert Bracey', NULL, NULL, 'Left-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(890, 'e09693ee-f655-d589-8ddc-a1c9b14efc8d', 'be_howell', 'Benny Howell', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(891, '50eb597c-fe6d-9d72-d48e-675cb36988b8', 'jw_taylor', 'Jack William Taylor', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(892, '395829b1-f3aa-061a-89d7-652a6b34dbc8', 'gv_buuren', 'GLV Buuren', NULL, NULL, 'Right-Hand Bat', 'Slow Left-Arm Orthodox', NULL, NULL, NULL, 'No', NULL),
(893, 'aaf62aa1-f74e-5258-565c-d8179afebd3e', 'c_sayers', 'Chadd Sayers', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(894, '444263ce-4194-ad3b-7f93-96424f33062f', 'm_klinger', 'M Klinger', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(895, 'a8f6be53-fe69-0b0f-fb91-9682b5b1ad06', 'wa_tavare', 'William Andrew Tavare', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(896, '323dc284-800a-9fa6-7430-46bc040f3adf', 'to_smith', 'Tom Smith', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(897, '369ab17a-2be2-e91d-6a64-a626fe146c56', 'ch_dent', 'Chris Dent', NULL, NULL, 'Left-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(898, 'b1614633-63ac-d127-b691-63477952d903', 'c_liddle', 'Chris Liddle', NULL, NULL, 'Right-Hand Bat', 'Left-arm fast medium', NULL, NULL, NULL, 'No', NULL),
(899, '1770d5b5-f5e1-44ee-5b07-2c8b27f1e152', 'g_drissell', 'George Drissell', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(900, '1e77c467-0887-6777-9b1b-c5e1bee74e68', 'mi_hammond', 'Miles Hammond', NULL, NULL, 'Left-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(901, '6491a773-2761-c9c9-0c49-f64c29635cd7', 'r_higgins', 'Ryan Higgins', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(902, 'c08d80b7-634a-af49-58ae-c928937c7312', 'bc_charlesworth', 'Ben Charlesworth', NULL, NULL, 'Left-Hand Bat', 'Right Arm Medium- fast', NULL, NULL, NULL, 'No', NULL),
(903, 'd3c64d9a-03f9-5912-8aa3-6726dc56ba8e', 'zak_crawley', 'Zak Crawley', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(904, 'ac9caa14-1700-cc04-0a63-0fed64da2b0d', 'ivan_thomas', 'Ivan Thomas', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(905, '0493751c-a41c-7e23-97f9-689e5ae8fb6c', 'ada_rouse', 'Adam Rouse', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(906, 'fa84e96c-d65f-1736-d9dd-c583567df395', 'h_viljoen', 'Hardus Viljoen', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(907, 'd837f34b-671b-bc95-f5f3-bc8b01577482', 'imr_qayyum', 'Imran Qayyum', NULL, NULL, 'Right-Hand Bat', 'Slow left-arm orthodox', NULL, NULL, NULL, 'No', NULL),
(908, 'b50a5c36-cca9-b288-011b-f73c9f7e17d6', 'grant_stewart', 'Grant Stewart', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(909, '708c84bc-b6a0-d252-dcdd-90f83b757840', 'mar_odiordan', 'Marcus O\'Riordan', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(910, 'e22b6cb8-ded3-5902-3631-39df0ad7a234', 'ollie_rayner', 'Ollie Rayner', NULL, NULL, 'Right-Hand Bat', 'Right-arm offbreak', NULL, NULL, NULL, 'No', NULL),
(911, 'ada606f9-9973-21d2-a94b-637ebb86adc0', 'a_milne', 'Adam Milne', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Fast', NULL, NULL, NULL, 'No', NULL),
(912, '2e4d340f-8eb4-0010-dbf6-21dc22c5a7a6', 'matt_milnes', 'Matt Milnes', NULL, NULL, 'Right-Hand Bat', 'Right-Arm Medium Fast', NULL, NULL, NULL, 'No', NULL),
(913, '9159b3e1-c352-6665-61bc-20c13833c7c4', 'j_denly', 'Joe Denly', NULL, NULL, 'Right-Hand Bat', 'LegBreak', NULL, NULL, NULL, 'No', NULL),
(914, '446a110f-c9af-ddeb-867b-98433ca91ef2', 'm_nabi', 'Mohammad Nabi', NULL, NULL, 'Right-Hand Bat', 'Right-Arm OffBreak', NULL, NULL, NULL, 'No', NULL),
(915, 'e4b1b6af-f565-9cd6-54fc-dfee7c029fb3', 'h_kuhn', 'Heino Kuhn', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(916, 'e1f5ec21-8243-ef4b-f5c7-356f6b5da428', 'calu_haggett', 'Calum Haggett', NULL, NULL, 'Left-hand bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(917, '2e1cdec4-f8d7-969d-68cc-3a5733bf15c3', 'alex_blake', 'Alex Blake', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(918, '0ab56a17-604a-f436-489b-60d20da56d84', 'mitc_claydon', 'Mitchell Claydon', NULL, NULL, 'Left-hand bat', 'Right-arm medium-fast', NULL, NULL, NULL, 'No', NULL),
(919, '97a8a414-d4f2-94e0-2998-07d2d18f1a05', 'dan_b_drummond', 'Daniel Bell-Drummond', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No', NULL),
(920, '4d3d39b2-4b8b-8f98-01c2-ec7ecfebcd71', 'olive_robinson', 'Oliver Robinson', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(921, 'eeaf1b31-b7ae-a2e3-ebb0-5292a930a271', 's_billings', 'Sam Billings', NULL, NULL, 'Right-Hand Bat', NULL, NULL, NULL, NULL, 'No', NULL),
(922, '1b07eb89-b0a7-fc59-d913-fff020f451fe', 'jm_cox', 'Jordan Cox', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(923, '3f74d8fb-2e3c-8779-9831-721c19291304', 'f_klaassen', 'Fred Klaassen', NULL, NULL, 'Right-Hand Bat', 'Left-Arm Medium', NULL, NULL, NULL, 'No', NULL),
(924, 'b563a29a-a658-da6c-9cec-6924a0058b27', 'har_podmore', 'Harry Podmore', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL),
(925, 'e158b1d5-9a1b-8320-3704-d98cf3521e88', 'sea_dickson', 'Sean Dickson', NULL, NULL, 'Right-Hand Bat', 'Right-arm medium', NULL, NULL, NULL, 'No', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sports_predraft_contest`
--

CREATE TABLE `sports_predraft_contest` (
  `PredraftContestID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DraftFormat` enum('Head to Head','League') COLLATE utf8_unicode_ci NOT NULL,
  `DraftType` enum('Normal','Reverse','InPlay','Hot','Champion','Practice','More','Mega','Winner Takes All','Only For Beginners','Head to Head') COLLATE utf8_unicode_ci NOT NULL,
  `DraftName` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `Privacy` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL,
  `IsPaid` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL,
  `IsConfirm` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `IsAutoCreate` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `ShowJoinedDraft` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL,
  `WinningAmount` int(11) NOT NULL DEFAULT '0',
  `DraftSize` int(11) NOT NULL,
  `UnfilledWinningPercent` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `CashBonusContribution` float(6,2) NOT NULL,
  `UserJoinLimit` int(11) NOT NULL DEFAULT '6',
  `EntryType` enum('Single','Multiple') COLLATE utf8_unicode_ci NOT NULL,
  `EntryFee` int(11) NOT NULL,
  `NoOfWinners` int(11) NOT NULL,
  `AdminPercent` float(6,2) DEFAULT NULL,
  `CustomizeWinning` text COLLATE utf8_unicode_ci,
  `EntryDate` datetime NOT NULL,
  `ModifiedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sports_predraft_contest`
--

INSERT INTO `sports_predraft_contest` (`PredraftContestID`, `UserID`, `DraftFormat`, `DraftType`, `DraftName`, `Privacy`, `IsPaid`, `IsConfirm`, `IsAutoCreate`, `ShowJoinedDraft`, `WinningAmount`, `DraftSize`, `UnfilledWinningPercent`, `CashBonusContribution`, `UserJoinLimit`, `EntryType`, `EntryFee`, `NoOfWinners`, `AdminPercent`, `CustomizeWinning`, `EntryDate`, `ModifiedDate`) VALUES
(3, 125, 'League', 'Hot', 'test draft 11', 'No', 'No', 'Yes', 'Yes', 'Yes', 0, 2, 'No', 8.00, 1, 'Single', 0, 1, 10.00, NULL, '2019-07-08 11:33:09', '2019-07-08 12:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `sports_series`
--

CREATE TABLE `sports_series` (
  `SeriesID` int(11) NOT NULL,
  `SeriesGUID` char(36) NOT NULL,
  `SeriesIDLive` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `SeriesName` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `SportsType` enum('Cricket','Football','Kabaddi') NOT NULL DEFAULT 'Cricket',
  `SeriesStartDate` date DEFAULT NULL,
  `SeriesEndDate` date DEFAULT NULL,
  `AuctionDraftIsPlayed` enum('Yes','No') NOT NULL DEFAULT 'No',
  `DraftUserLimit` int(11) NOT NULL DEFAULT '0' COMMENT 'Snake & Auction Draft',
  `DraftTeamPlayerLimit` smallint(6) DEFAULT NULL,
  `DraftPlayerSelectionCriteria` varchar(255) DEFAULT NULL,
  `AuctionDraftStatusID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_series`
--

INSERT INTO `sports_series` (`SeriesID`, `SeriesGUID`, `SeriesIDLive`, `SeriesName`, `SportsType`, `SeriesStartDate`, `SeriesEndDate`, `AuctionDraftIsPlayed`, `DraftUserLimit`, `DraftTeamPlayerLimit`, `DraftPlayerSelectionCriteria`, `AuctionDraftStatusID`) VALUES
(126, '3c03b117-36bf-a4eb-5c53-b49876793bf5', 'tnplt20_2019', 'Tamil Nadu Premier League 2019', 'Cricket', NULL, NULL, 'No', 0, NULL, NULL, 1),
(130, '55a36d55-a095-eb3b-258e-dbc8500e01c8', 'nluae_2019', 'United Arab Emirates in Netherlands T20I Series 2019', 'Cricket', NULL, NULL, 'No', 0, NULL, NULL, 1),
(134, '38f7d00d-9902-034f-7e96-92354c797f1a', 'wcslt20_2019', 'Womens Cricket Super League 2019', 'Cricket', NULL, NULL, 'No', 0, NULL, NULL, 1),
(142, '58cedb7d-bd6d-4b4e-d348-03b4f2b9448e', 'wiind_2019', 'West Indies vs India 2019', 'Cricket', NULL, NULL, 'No', 0, NULL, NULL, 1),
(149, '6257971c-5937-a2e7-acfc-bb5e2365e480', 'gblt20_2019_can', 'Global T20 League 2019 Canada', 'Cricket', NULL, NULL, 'No', 0, NULL, NULL, 1),
(153, 'b60dad9f-24dd-90e9-ddec-c6c70dd27d88', 't20blast_2019', 'VITALITY T20 BLAST 2019', 'Cricket', NULL, NULL, 'No', 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sports_setting_points`
--

CREATE TABLE `sports_setting_points` (
  `PointsTypeGUID` varchar(36) NOT NULL,
  `PointsTypeDescprition` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `PointsTypeShortDescription` char(22) NOT NULL,
  `PointsType` enum('General Point','Bonus Point','Economy Rate','Strike Rate') NOT NULL,
  `PointsInningType` enum('Batting','Bowling','Fielding') NOT NULL,
  `PointsScoringField` varchar(50) DEFAULT NULL,
  `PointsT20` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsODI` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsTEST` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsT20InPlay` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsODIInPlay` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsTESTInPlay` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsT20Reverse` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsODIReverse` float(4,1) NOT NULL DEFAULT '0.0',
  `PointsTESTReverse` float(4,1) NOT NULL DEFAULT '0.0',
  `StatusID` int(11) NOT NULL DEFAULT '6',
  `Sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_setting_points`
--

INSERT INTO `sports_setting_points` (`PointsTypeGUID`, `PointsTypeDescprition`, `PointsTypeShortDescription`, `PointsType`, `PointsInningType`, `PointsScoringField`, `PointsT20`, `PointsODI`, `PointsTEST`, `PointsT20InPlay`, `PointsODIInPlay`, `PointsTESTInPlay`, `PointsT20Reverse`, `PointsODIReverse`, `PointsTESTReverse`, `StatusID`, `Sort`) VALUES
('BattingMinimumRuns', 'Applicable for players batting minimum runs', '', 'Strike Rate', 'Batting', NULL, 15.0, 15.0, 15.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 6, 17),
('CaptainPointMP', 'Captain points Multiplier', '', 'General Point', 'Batting', NULL, 2.0, 2.0, 2.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 6, 18),
('Catch', 'Catch', 'CT', 'General Point', 'Fielding', 'Catches', 4.0, 4.0, 4.0, 0.0, 0.0, 0.0, 10.0, 10.0, 10.0, 1, 1),
('Duck', 'Duck (EXCEPT BOWLER)', 'DUCK', 'General Point', 'Batting', 'Runs', -5.0, -5.0, -5.0, 0.0, 0.0, 0.0, -5.0, -5.0, -5.0, 1, 10),
('EconomyRate0N5Balls', 'Economy rate 0 to 5.00', 'EB', 'Economy Rate', 'Bowling', 'Economy', 4.0, 4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 9),
('EconomyRate10.01N12.00Balls', 'Economy rate 10.01 to 12.00', 'EB', 'Economy Rate', 'Bowling', 'Economy', -6.0, -6.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 14),
('EconomyRate5.01N7.00Balls', 'Economy rate 5.01 to 7.00', 'EB', 'Economy Rate', 'Bowling', 'Economy', 0.0, -4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 10),
('EconomyRate5.01N8.00Balls', 'Economy rate 5.01 to 8.00', 'EB', 'Economy Rate', 'Bowling', 'Economy', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 11),
('EconomyRate7.01N10.00Balls', 'Economy rate 7.01 to 10.00', 'EB', 'Economy Rate', 'Bowling', 'Economy', 0.0, -5.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 12),
('EconomyRate8.01N10.00Balls', 'Economy rate 8.01 to 10.00', 'EB', 'Economy Rate', 'Bowling', 'Economy', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 13),
('EconomyRateAbove12.1Balls', 'Economy rate 12.01 or more', 'EB', 'Economy Rate', 'Bowling', 'Economy', -8.0, -8.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 15),
('EightWicketsMore', 'For 8 wickets or more', 'BWB', 'Bonus Point', 'Bowling', 'Wickets', 0.0, 0.0, 40.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 7),
('EveryRunScored', 'For every run scored', 'RUNS', 'General Point', 'Batting', 'Runs', 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 1.0, 1.0, 1.0, 1, 1),
('FiveWickets', 'For 5-wicket', 'BWB', 'Bonus Point', 'Bowling', 'Wickets', 8.0, 8.0, 8.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 4),
('For100runs', 'For 100 runs', 'BTB', 'General Point', 'Batting', 'Runs', 8.0, 8.0, 8.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 6),
('For150runs', 'For 150 runs', 'BTB', 'General Point', 'Batting', 'Runs', 0.0, 30.0, 25.0, 0.0, 0.0, 0.0, 10.0, 30.0, 25.0, 1, 7),
('For200runs', 'For 200 runs', 'BTB', 'General Point', 'Batting', 'Runs', 0.0, 40.0, 30.0, 0.0, 0.0, 0.0, 0.0, 40.0, 30.0, 1, 8),
('For300runs', 'For 300 runs or more', 'BTB', 'General Point', 'Batting', 'Runs', 0.0, 0.0, 40.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 9),
('For30runs', 'For 30 runs', 'BTB', 'General Point', 'Batting', 'Runs', 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, -5.0, 0.0, 0.0, 1, 5),
('For50runs', 'For 50 runs', 'BTB', 'General Point', 'Batting', 'Runs', 4.0, 4.0, 4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 5),
('Four', 'For every 4 hit', '4s', 'General Point', 'Batting', 'Fours', 1.0, 1.0, 1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 2),
('FourWickets', 'For 4-wicket', 'BWB', 'Bonus Point', 'Bowling', 'Wickets', 4.0, 4.0, 4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 3),
('Maiden', 'For every maiden', 'MD', 'Bonus Point', 'Bowling', 'Maidens', 4.0, 4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 8),
('MinimumBallsScoreStrikeRate', 'A player must score a minimum balls to be awarded the Strike Rate\nbonus (EXCEPT BOWLER)', '', 'Strike Rate', 'Batting', NULL, 15.0, 15.0, 15.0, 0.0, 0.0, 0.0, 2.0, 0.0, 2.0, 1, 19),
('MinimumOverEconomyRate', 'A player must bowl a minimum over to be awarded the Economy Rate bonus', '', 'Economy Rate', 'Bowling', NULL, 10.0, 20.0, 0.0, 0.0, 0.0, 0.0, 2.0, 0.0, 2.0, 1, 20),
('MinimumRunScoreStrikeRate', 'A player must score a minimum runs to be awarded the Strike Rate\r\nbonus', '', 'Strike Rate', 'Batting', NULL, 15.0, 15.0, 15.0, 0.0, 0.0, 0.0, 2.0, 0.0, 2.0, 6, 20),
('RunOUT', 'Run-out', 'RO', 'General Point', 'Fielding', 'RunOutDirectHit', 6.0, 6.0, 6.0, 0.0, 0.0, 0.0, 0.0, 10.0, 10.0, 1, 3),
('SevenWicketsMore', 'For 7 wickets', 'BWB', 'Bonus Point', 'Bowling', 'Wickets', 0.0, 40.0, 30.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 6),
('Six', 'For every 6 hit', '6s', 'General Point', 'Batting', 'Sixes', 2.0, 2.0, 3.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 3),
('SixWickets', 'For 6-wicket', 'BWB', 'Bonus Point', 'Bowling', 'Wickets', 0.0, 30.0, 25.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 5),
('StatringXI', 'For being part of the starting XI', 'SB', 'General Point', 'Batting', NULL, 2.0, 2.0, 2.0, 0.0, 0.0, 0.0, 2.0, 0.0, 2.0, 1, 20),
('StrikeRate0N49.99', 'Strike rate 0 to 49.99', 'STB', 'Strike Rate', 'Batting', 'StrikeRate', -4.0, -4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 11),
('StrikeRate100N149.99', 'Strike rate 100 to 149.99', 'STB', 'Strike Rate', 'Batting', 'StrikeRate', 5.0, 5.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 14),
('StrikeRate150N199.99', 'Strike rate 150.00 to 199.99', 'STB', 'Strike Rate', 'Batting', 'StrikeRate', 10.0, 10.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 15),
('StrikeRate200NMore', 'Strike rate 200.00 or more', 'STB', 'Strike Rate', 'Batting', 'StrikeRate', 15.0, 15.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 16),
('StrikeRate50N74.99', 'Strike rate 50 to 74.99', 'STB', 'Strike Rate', 'Batting', 'StrikeRate', -5.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 12),
('StrikeRate75N99.99', 'Strike rate 75.00 to 99.99', 'STB', 'Strike Rate', 'Batting', 'StrikeRate', -6.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 13),
('Stumping', 'Stumping', 'ST', 'General Point', 'Fielding', 'Stumping', 6.0, 6.0, 6.0, 0.0, 0.0, 0.0, 15.0, 15.0, 15.0, 1, 2),
('ThreeWickets', 'For 3-wicket', 'BWB', 'Bonus Point', 'Bowling', 'Wickets', 4.0, 4.0, 4.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 1, 2),
('ViceCaptainPointMP', 'Vice captain points multiplier', '', 'General Point', 'Batting', NULL, 1.5, 2.4, 1.5, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 6, 19),
('Wicket', 'For every wicket taken', 'WK', 'General Point', 'Bowling', 'Wickets', 10.0, 10.0, 10.0, 0.0, 0.0, 0.0, 10.0, 15.1, 20.0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sports_set_match_types`
--

CREATE TABLE `sports_set_match_types` (
  `MatchTypeID` int(11) NOT NULL,
  `MatchTypeName` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT '(Entity API)',
  `MatchTypeNameCricketAPI` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_set_match_types`
--

INSERT INTO `sports_set_match_types` (`MatchTypeID`, `MatchTypeName`, `MatchTypeNameCricketAPI`) VALUES
(1, 'ODI', 'one-day'),
(2, 'First Class', NULL),
(3, 'T20', 't20'),
(4, 'T20I', NULL),
(5, 'Test', 'test'),
(6, 'Others', NULL),
(7, 'Woman T20', NULL),
(8, 'List A', NULL),
(9, 'Woman ODI', NULL),
(10, 'Youth ODI', NULL),
(11, 'Youth T20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sports_teams`
--

CREATE TABLE `sports_teams` (
  `TeamID` int(11) NOT NULL,
  `TeamGUID` char(36) NOT NULL,
  `TeamIDLive` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `TeamName` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `TeamNameShort` varchar(10) CHARACTER SET utf8mb4 DEFAULT NULL,
  `TeamFlag` text,
  `SportsType` enum('Cricket','Football','Kabaddi') NOT NULL DEFAULT 'Cricket'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_teams`
--

INSERT INTO `sports_teams` (`TeamID`, `TeamGUID`, `TeamIDLive`, `TeamName`, `TeamNameShort`, `TeamFlag`, `SportsType`) VALUES
(127, '289f50c6-e8e4-84ac-67a5-9f845d08dff5', 'vkv', 'VB Kanchi Veerans', 'VKV', NULL, 'Cricket'),
(128, '6643cc17-5eb6-6b79-dfb3-6d032d13ae77', 'did', 'Dindigul Dragons', 'DID', NULL, 'Cricket'),
(131, 'ca8501ea-6ec2-803c-fbe6-264d6a36dcc5', 'nl', 'Netherlands', 'NL', NULL, 'Cricket'),
(132, 'b4db1d1a-383c-a458-c91b-2836f08169b3', 'uae', 'United Arab Emirates', 'UAE', NULL, 'Cricket'),
(135, '41e72b32-26be-6e2f-46d0-2eccf8bb0a8a', 'lt', 'Lancashire Thunder', 'LT', NULL, 'Cricket'),
(136, 'e9e31e86-fc68-9c34-713f-eac420c088e2', 'sv', 'Southern Vipers', 'SV', NULL, 'Cricket'),
(138, 'b79476f5-8f38-4c56-549d-d32cff552c6a', 'ttp', 'TUTI Patriots', 'TTP', NULL, 'Cricket'),
(139, '5f49ab11-ec1e-0e00-fc04-0696a8560beb', 'rtw', 'Ruby Trichy Warriors', 'RTW', NULL, 'Cricket'),
(143, 'eb9b8979-7124-4918-acef-5eec2651014d', 'wi', 'West Indies', 'WI', NULL, 'Cricket'),
(144, '3fe3d8fc-bb13-ca73-a27f-678cd6ae3697', 'ind', 'India', 'IND', NULL, 'Cricket'),
(146, 'e986c274-c2d8-7ca7-a564-62f48890465f', 'll', 'Loughborough Lightning', 'LL', NULL, 'Cricket'),
(147, 'e14e918b-f6e5-376b-b23b-f5a092efa731', 'ws', 'Western Storm', 'WS', NULL, 'Cricket'),
(150, '0b063f93-1015-0ac9-cae0-d794b2363e32', 'bmw', 'Brampton Wolves', 'BMW', 'bgnew_1565003299.png', 'Cricket'),
(151, 'da59e6c3-e5db-30fa-150d-511a127ea355', 'edr', 'Edmonton Royals', 'EDR', NULL, 'Cricket'),
(154, 'b162e897-d17b-9335-3919-2366afc7a7aa', 'sus', 'Sussex', 'SUS', NULL, 'Cricket'),
(155, 'e3517c1a-d28c-67e9-629e-3feafae2fce7', 'gla', 'Glamorgan', 'GLA', NULL, 'Cricket'),
(157, '7185b9b3-fda6-52b0-28f7-aca89a9a17ae', 'yd', 'Yorkshire Diamonds', 'YD', NULL, 'Cricket'),
(158, 'b4a5de29-4591-c7f7-cca2-3edf755050d3', 'ss', 'Surrey Stars', 'SS', NULL, 'Cricket'),
(160, '2a1a40d3-e069-afd2-b562-81b820672e97', 'vak', 'Vancouver Knights', 'VAK', NULL, 'Cricket'),
(161, '9eeb2e28-9eea-9bab-b641-a04d1c9a38e4', 'wik', 'Winnipeg Hawks', 'WIK', NULL, 'Cricket'),
(163, 'bcf0b59d-dd5a-4f08-6911-5fc4f44f2880', 'lkk', 'Lyca Kovai Kings', 'LKK', NULL, 'Cricket'),
(165, 'ba635aff-4b5b-807f-fafc-e0e5ad9c6080', 'mot', 'Montreal Tigers', 'MOT', NULL, 'Cricket'),
(166, 'e5fab056-f70d-f93b-d85a-b03b61005e32', 'ton', 'Toronto Nationals', 'TON', NULL, 'Cricket'),
(168, '4df9aa26-2e8a-a20a-c7b4-5218f1783301', 'nor', 'Northamptonshire', 'NOR', NULL, 'Cricket'),
(169, '72a34012-1564-6f8e-4506-87b9e2ba04b8', 'dur', 'Durham', 'DUR', NULL, 'Cricket'),
(171, '14c1a7b1-01ee-9f2c-b45f-256302487884', 'lei', 'Leicestershire', 'LEI', NULL, 'Cricket'),
(172, 'b6b040ef-c7b0-d3d0-8418-c9fc3649d95a', 'wrw', 'Warwickshire', 'WRW', NULL, 'Cricket'),
(174, '2f104fad-bde0-6eb7-2b81-d6c81cb73011', 'glo', 'Gloucestershire', 'GLO', NULL, 'Cricket'),
(175, '2bcd011e-f58c-1a01-20b3-f0d2cd904ac0', 'kent', 'Kent', 'KENT', NULL, 'Cricket'),
(177, 'e32ec6d5-c86a-41f3-7e14-fb3e9e28596a', 'ess', 'Essex', 'ESS', NULL, 'Cricket'),
(178, '8a6b3da3-af2c-2193-72ea-61dcc7c4f67b', 'som', 'Somerset', 'SOM', NULL, 'Cricket'),
(182, '735e6738-801a-fb41-8037-afb4ac92adac', 'kak', 'Karaikudi Kaalai', 'KAK', NULL, 'Cricket'),
(183, 'bd43664d-32a1-dc47-c218-0b17ef6e5d84', 'mps', 'Madurai Panthers', 'MPS', NULL, 'Cricket');

-- --------------------------------------------------------

--
-- Table structure for table `sports_team_players`
--

CREATE TABLE `sports_team_players` (
  `id` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `SeriesID` int(11) NOT NULL,
  `MatchID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `PlayerRole` enum('AllRounder','Batsman','Bowler','WicketKeeper','Other') NOT NULL,
  `PlayerSalary` float(8,2) NOT NULL DEFAULT '0.00',
  `IsPlaying` enum('Yes','No') NOT NULL DEFAULT 'No',
  `TotalPoints` float(8,2) DEFAULT '0.00',
  `PointsData` text,
  `IsAdminUpdate` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_team_players`
--

INSERT INTO `sports_team_players` (`id`, `PlayerID`, `SeriesID`, `MatchID`, `TeamID`, `PlayerRole`, `PlayerSalary`, `IsPlaying`, `TotalPoints`, `PointsData`, `IsAdminUpdate`) VALUES
(1, 396, 126, 129, 127, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(2, 397, 126, 129, 127, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(3, 398, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(4, 399, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(5, 400, 126, 129, 127, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(6, 401, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(7, 402, 126, 129, 127, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(8, 403, 126, 129, 127, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(9, 404, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(10, 405, 126, 129, 127, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(11, 406, 126, 129, 127, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(12, 407, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(13, 408, 126, 129, 127, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(14, 409, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(15, 410, 126, 129, 127, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(16, 411, 126, 129, 127, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(17, 412, 126, 129, 127, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(18, 413, 126, 129, 127, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(19, 414, 126, 129, 127, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(20, 415, 126, 129, 127, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(21, 416, 126, 129, 128, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(22, 416, 126, 164, 128, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(23, 417, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(24, 417, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(25, 418, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(26, 418, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(27, 419, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(28, 419, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(29, 420, 126, 129, 128, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(30, 420, 126, 164, 128, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(31, 421, 126, 129, 128, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(32, 421, 126, 164, 128, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(33, 422, 126, 129, 128, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(34, 422, 126, 164, 128, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(35, 423, 126, 129, 128, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(36, 423, 126, 164, 128, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(37, 424, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(38, 424, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(39, 425, 126, 129, 128, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(40, 425, 126, 164, 128, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(41, 426, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(42, 426, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(43, 427, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(44, 427, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(45, 428, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(46, 428, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(47, 429, 126, 129, 128, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(48, 429, 126, 164, 128, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(49, 430, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(50, 430, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(51, 431, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(52, 431, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(53, 432, 126, 129, 128, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(54, 432, 126, 164, 128, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(55, 433, 126, 129, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(56, 433, 126, 164, 128, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(57, 434, 126, 129, 128, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(58, 434, 126, 164, 128, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(59, 435, 130, 133, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(60, 435, 130, 141, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(61, 435, 130, 185, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(62, 436, 130, 133, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(63, 436, 130, 141, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(64, 436, 130, 185, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(65, 437, 130, 133, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(66, 437, 130, 141, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(67, 437, 130, 185, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(68, 438, 130, 133, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(69, 438, 130, 141, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(70, 438, 130, 185, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(71, 439, 130, 133, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(72, 439, 130, 141, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(73, 439, 130, 185, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(74, 440, 130, 133, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(75, 440, 130, 141, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(76, 440, 130, 185, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(77, 441, 130, 133, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(78, 441, 130, 141, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(79, 441, 130, 185, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(80, 442, 130, 133, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(81, 442, 130, 141, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(82, 442, 130, 185, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(83, 443, 130, 133, 131, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(84, 443, 130, 141, 131, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(85, 443, 130, 185, 131, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(86, 444, 130, 133, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(87, 444, 130, 141, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(88, 444, 130, 185, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(89, 445, 130, 133, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(90, 445, 130, 141, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(91, 445, 130, 185, 131, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(92, 446, 130, 133, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(93, 446, 130, 141, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(94, 446, 130, 185, 131, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(95, 447, 130, 133, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(96, 447, 130, 141, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(97, 447, 130, 185, 131, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(98, 448, 130, 133, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(99, 448, 130, 141, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(100, 448, 130, 185, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(101, 449, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(102, 449, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(103, 449, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(104, 450, 130, 133, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(105, 450, 130, 141, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(106, 450, 130, 185, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(107, 451, 130, 133, 132, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(108, 451, 130, 141, 132, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(109, 451, 130, 185, 132, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(110, 452, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(111, 452, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(112, 452, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(113, 453, 130, 133, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(114, 453, 130, 141, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(115, 453, 130, 185, 132, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(116, 454, 130, 133, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(117, 454, 130, 141, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(118, 454, 130, 185, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(119, 455, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(120, 455, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(121, 455, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(122, 456, 130, 133, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(123, 456, 130, 141, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(124, 456, 130, 185, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(125, 457, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(126, 457, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(127, 457, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(128, 458, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(129, 458, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(130, 458, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(131, 459, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(132, 459, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(133, 459, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(134, 460, 130, 133, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(135, 460, 130, 141, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(136, 460, 130, 185, 132, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(137, 461, 130, 133, 132, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(138, 461, 130, 141, 132, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(139, 461, 130, 185, 132, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(140, 462, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(141, 462, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(142, 462, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(143, 463, 130, 133, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(144, 463, 130, 141, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(145, 463, 130, 185, 132, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(146, 464, 134, 137, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(147, 464, 134, 181, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(148, 465, 134, 137, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(149, 465, 134, 181, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(150, 466, 134, 137, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(151, 466, 134, 181, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(152, 467, 134, 137, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(153, 467, 134, 181, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(154, 468, 134, 137, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(155, 468, 134, 181, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(156, 469, 134, 137, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(157, 469, 134, 181, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(158, 470, 134, 137, 135, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(159, 470, 134, 181, 135, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(160, 471, 134, 137, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(161, 471, 134, 181, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(162, 472, 134, 137, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(163, 472, 134, 181, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(164, 473, 134, 137, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(165, 473, 134, 181, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(166, 474, 134, 137, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(167, 474, 134, 181, 135, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(168, 475, 134, 137, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(169, 475, 134, 181, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(170, 476, 134, 137, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(171, 476, 134, 181, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(172, 477, 134, 137, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(173, 477, 134, 181, 135, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(174, 478, 134, 137, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(175, 478, 134, 181, 135, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(176, 479, 134, 137, 136, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(177, 479, 134, 186, 136, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(178, 480, 134, 137, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(179, 480, 134, 186, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(180, 481, 134, 137, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(181, 481, 134, 186, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(182, 482, 134, 137, 136, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(183, 482, 134, 186, 136, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(184, 483, 134, 137, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(185, 483, 134, 186, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(186, 484, 134, 137, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(187, 484, 134, 186, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(188, 485, 134, 137, 136, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(189, 485, 134, 186, 136, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(190, 486, 134, 137, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(191, 486, 134, 186, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(192, 487, 134, 137, 136, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(193, 487, 134, 186, 136, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(194, 488, 134, 137, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(195, 488, 134, 186, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(196, 489, 134, 137, 136, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(197, 489, 134, 186, 136, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(198, 490, 134, 137, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(199, 490, 134, 186, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(200, 491, 134, 137, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(201, 491, 134, 186, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(202, 492, 134, 137, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(203, 492, 134, 186, 136, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(204, 493, 134, 137, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(205, 493, 134, 186, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(206, 494, 134, 137, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(207, 494, 134, 186, 136, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(208, 495, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(209, 496, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(210, 497, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(211, 498, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(212, 499, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(213, 500, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(214, 501, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(215, 502, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(216, 503, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(217, 504, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(218, 505, 126, 140, 138, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(219, 506, 126, 140, 138, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(220, 507, 126, 140, 138, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(221, 508, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(222, 509, 126, 140, 138, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(223, 510, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(224, 511, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(225, 512, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(226, 513, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(227, 514, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(228, 515, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(229, 516, 126, 140, 138, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(230, 517, 126, 140, 138, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(231, 518, 126, 140, 138, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(232, 519, 126, 140, 139, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(233, 520, 126, 140, 139, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(234, 521, 126, 140, 139, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(235, 522, 126, 140, 139, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(236, 523, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(237, 524, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(238, 525, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(239, 526, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(240, 527, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(241, 528, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(242, 529, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(243, 530, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(244, 531, 126, 140, 139, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(245, 532, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(246, 533, 126, 140, 139, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(247, 534, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(248, 535, 126, 140, 139, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(249, 536, 126, 140, 139, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(250, 537, 126, 140, 139, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(251, 538, 126, 140, 139, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(252, 539, 126, 140, 139, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(253, 540, 126, 140, 139, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(254, 541, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(255, 542, 126, 140, 139, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(256, 543, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(257, 543, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(258, 544, 142, 145, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(259, 544, 142, 180, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(260, 545, 142, 145, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(261, 545, 142, 180, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(262, 546, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(263, 546, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(264, 547, 142, 145, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(265, 547, 142, 180, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(266, 548, 142, 145, 143, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(267, 548, 142, 180, 143, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(268, 549, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(269, 549, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(270, 550, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(271, 550, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(272, 551, 142, 145, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(273, 551, 142, 180, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(274, 552, 142, 145, 143, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(275, 552, 142, 180, 143, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(276, 553, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(277, 553, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(278, 554, 142, 145, 143, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(279, 554, 142, 180, 143, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(280, 555, 142, 145, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(281, 555, 142, 180, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(282, 556, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(283, 556, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(284, 557, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(285, 557, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(286, 558, 142, 145, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(287, 558, 142, 180, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(288, 559, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(289, 559, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(290, 560, 142, 145, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(291, 560, 142, 180, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(292, 561, 142, 145, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(293, 561, 142, 180, 143, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(294, 562, 142, 145, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(295, 562, 142, 180, 143, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(296, 563, 142, 145, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(297, 563, 142, 180, 143, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(298, 564, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(299, 564, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(300, 565, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(301, 565, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(302, 566, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(303, 566, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(304, 567, 142, 145, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(305, 567, 142, 180, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(306, 568, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(307, 568, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(308, 569, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(309, 569, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(310, 570, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(311, 570, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(312, 571, 142, 145, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(313, 571, 142, 180, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(314, 572, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(315, 572, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(316, 573, 142, 145, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(317, 573, 142, 180, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(318, 574, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(319, 574, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(320, 575, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(321, 575, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(322, 576, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(323, 576, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(324, 577, 142, 145, 144, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(325, 577, 142, 180, 144, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(326, 578, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(327, 578, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(328, 579, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(329, 579, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(330, 509, 142, 145, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(331, 509, 142, 180, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(332, 580, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(333, 580, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(334, 581, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(335, 581, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(336, 582, 142, 145, 144, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(337, 582, 142, 180, 144, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(338, 583, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(339, 583, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(340, 584, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(341, 584, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(342, 585, 142, 145, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(343, 585, 142, 180, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(344, 586, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(345, 586, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(346, 587, 142, 145, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(347, 587, 142, 180, 144, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(348, 416, 142, 145, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(349, 416, 142, 180, 144, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(350, 588, 142, 145, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(351, 588, 142, 180, 144, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(352, 589, 142, 145, 144, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(353, 589, 142, 180, 144, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(354, 590, 134, 148, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(355, 590, 134, 186, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(356, 591, 134, 148, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(357, 591, 134, 186, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(358, 592, 134, 148, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(359, 592, 134, 186, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(360, 593, 134, 148, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(361, 593, 134, 186, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(362, 594, 134, 148, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(363, 594, 134, 186, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(364, 595, 134, 148, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(365, 595, 134, 186, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(366, 596, 134, 148, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(367, 596, 134, 186, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(368, 597, 134, 148, 146, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(369, 597, 134, 186, 146, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(370, 598, 134, 148, 146, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(371, 598, 134, 186, 146, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(372, 599, 134, 148, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(373, 599, 134, 186, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(374, 600, 134, 148, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(375, 600, 134, 186, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(376, 601, 134, 148, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(377, 601, 134, 186, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(378, 602, 134, 148, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(379, 602, 134, 186, 146, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(380, 603, 134, 148, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(381, 603, 134, 186, 146, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(382, 604, 134, 148, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(383, 604, 134, 186, 146, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(384, 605, 134, 148, 147, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(385, 606, 134, 148, 147, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(386, 607, 134, 148, 147, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(387, 608, 134, 148, 147, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(388, 609, 134, 148, 147, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(389, 610, 134, 148, 147, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(390, 611, 134, 148, 147, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(391, 612, 134, 148, 147, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(392, 613, 134, 148, 147, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(393, 614, 134, 148, 147, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(394, 615, 134, 148, 147, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(395, 616, 134, 148, 147, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(396, 617, 134, 148, 147, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(397, 618, 134, 148, 147, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(398, 619, 134, 148, 147, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(399, 620, 149, 152, 150, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(400, 621, 149, 152, 150, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(401, 451, 149, 152, 150, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(402, 622, 149, 152, 150, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(403, 623, 149, 152, 150, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(404, 624, 149, 152, 150, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(405, 625, 149, 152, 150, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(406, 626, 149, 152, 150, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(407, 627, 149, 152, 150, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(408, 628, 149, 152, 150, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(409, 456, 149, 152, 150, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(410, 629, 149, 152, 150, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(411, 631, 149, 152, 150, '', 0.00, 'No', 0.00, NULL, 'No'),
(412, 632, 149, 152, 150, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(413, 633, 149, 152, 150, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(414, 634, 149, 152, 150, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(415, 635, 149, 152, 150, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(416, 636, 149, 152, 150, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(417, 637, 149, 152, 150, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(418, 638, 149, 152, 150, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(419, 639, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(420, 640, 149, 152, 151, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(421, 641, 149, 152, 151, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(422, 642, 149, 152, 151, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(423, 643, 149, 152, 151, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(424, 644, 149, 152, 151, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(425, 645, 149, 152, 151, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(426, 646, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(427, 647, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(428, 648, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(429, 649, 149, 152, 151, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(430, 650, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(431, 651, 149, 152, 151, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(432, 652, 149, 152, 151, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(433, 653, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(434, 654, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(435, 655, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(436, 656, 149, 152, 151, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(437, 657, 134, 159, 157, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(438, 658, 134, 159, 157, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(439, 659, 134, 159, 157, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(440, 660, 134, 159, 157, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(441, 661, 134, 159, 157, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(442, 662, 134, 159, 157, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(443, 663, 134, 159, 157, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(444, 664, 134, 159, 157, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(445, 665, 134, 159, 157, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(446, 666, 134, 159, 157, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(447, 667, 134, 159, 157, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(448, 668, 134, 159, 157, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(449, 669, 134, 159, 157, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(450, 670, 134, 159, 157, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(451, 671, 134, 159, 157, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(452, 672, 134, 159, 157, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(453, 673, 134, 159, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(454, 673, 134, 181, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(455, 674, 134, 159, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(456, 674, 134, 181, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(457, 675, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(458, 675, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(459, 676, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(460, 676, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(461, 677, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(462, 677, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(463, 678, 134, 159, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(464, 678, 134, 181, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(465, 679, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(466, 679, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(467, 680, 134, 159, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(468, 680, 134, 181, 158, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(469, 681, 134, 159, 158, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(470, 681, 134, 181, 158, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(471, 682, 134, 159, 158, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(472, 682, 134, 181, 158, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(473, 683, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(474, 683, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(475, 684, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(476, 684, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(477, 685, 134, 159, 158, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(478, 685, 134, 181, 158, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(479, 686, 134, 159, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(480, 686, 134, 181, 158, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(481, 687, 134, 159, 158, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(482, 687, 134, 181, 158, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(483, 688, 153, 156, 154, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(484, 689, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(485, 690, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(486, 691, 153, 156, 154, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(487, 692, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(488, 693, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(489, 694, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(490, 695, 153, 156, 154, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(491, 696, 153, 156, 154, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(492, 697, 153, 156, 154, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(493, 698, 153, 156, 154, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(494, 699, 153, 156, 154, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(495, 700, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(496, 701, 153, 156, 154, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(497, 702, 153, 156, 154, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(498, 703, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(499, 704, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(500, 705, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(501, 706, 153, 156, 154, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(502, 707, 153, 156, 154, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(503, 708, 153, 156, 154, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(504, 709, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(505, 710, 153, 156, 154, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(506, 711, 153, 156, 154, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(507, 712, 153, 156, 155, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(508, 713, 153, 156, 155, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(509, 714, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(510, 715, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(511, 716, 153, 156, 155, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(512, 717, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(513, 718, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(514, 719, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(515, 720, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(516, 721, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(517, 722, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(518, 723, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(519, 724, 153, 156, 155, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(520, 725, 153, 156, 155, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(521, 726, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(522, 727, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(523, 728, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(524, 729, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(525, 730, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(526, 731, 153, 156, 155, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(527, 732, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(528, 733, 153, 156, 155, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(529, 734, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(530, 735, 153, 156, 155, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(531, 736, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(532, 737, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(533, 738, 153, 156, 155, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(534, 739, 153, 156, 155, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(535, 740, 149, 162, 160, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(536, 741, 149, 162, 160, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(537, 742, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(538, 743, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(539, 744, 149, 162, 160, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(540, 745, 149, 162, 160, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(541, 746, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(542, 562, 149, 162, 160, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(543, 747, 149, 162, 160, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(544, 748, 149, 162, 160, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(545, 749, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(546, 750, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(547, 751, 149, 162, 160, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(548, 752, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(549, 753, 149, 162, 160, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(550, 754, 149, 162, 160, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(551, 755, 149, 162, 160, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(552, 756, 149, 162, 160, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(553, 757, 149, 162, 160, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(554, 563, 149, 162, 160, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(555, 758, 149, 162, 161, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(556, 759, 149, 162, 161, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(557, 449, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(558, 760, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(559, 761, 149, 162, 161, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(560, 762, 149, 162, 161, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(561, 763, 149, 162, 161, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(562, 764, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(563, 765, 149, 162, 161, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(564, 766, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(565, 767, 149, 162, 161, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(566, 768, 149, 162, 161, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(567, 769, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(568, 770, 149, 162, 161, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(569, 771, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(570, 772, 149, 162, 161, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(571, 773, 149, 162, 161, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(572, 774, 149, 162, 161, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(573, 775, 126, 164, 163, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(574, 776, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(575, 777, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(576, 778, 126, 164, 163, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(577, 779, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(578, 780, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(579, 781, 126, 164, 163, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(580, 782, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(581, 783, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(582, 784, 126, 164, 163, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(583, 785, 126, 164, 163, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(584, 786, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(585, 787, 126, 164, 163, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(586, 788, 126, 164, 163, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(587, 789, 126, 164, 163, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(588, 790, 126, 164, 163, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(589, 791, 126, 164, 163, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(590, 792, 126, 164, 163, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(591, 793, 126, 164, 163, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(592, 794, 149, 167, 165, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(593, 795, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(594, 796, 149, 167, 165, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(595, 797, 149, 167, 165, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(596, 798, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(597, 799, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(598, 800, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(599, 801, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(600, 802, 149, 167, 165, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(601, 803, 149, 167, 165, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(602, 461, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(603, 804, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(604, 559, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(605, 805, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(606, 806, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(607, 807, 149, 167, 165, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(608, 808, 149, 167, 165, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(609, 809, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(610, 810, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(611, 547, 149, 167, 165, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(612, 811, 149, 167, 165, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(613, 812, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(614, 813, 149, 167, 165, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(615, 814, 149, 167, 165, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(616, 815, 149, 167, 166, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(617, 543, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(618, 816, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(619, 450, 149, 167, 166, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(620, 817, 149, 167, 166, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(621, 818, 149, 167, 166, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(622, 819, 149, 167, 166, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(623, 820, 149, 167, 166, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(624, 821, 149, 167, 166, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(625, 822, 149, 167, 166, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(626, 455, 149, 167, 166, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(627, 823, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(628, 824, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(629, 825, 149, 167, 166, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(630, 826, 149, 167, 166, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(631, 827, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(632, 828, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(633, 829, 149, 167, 166, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(634, 830, 149, 167, 166, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(635, 831, 153, 170, 168, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(636, 832, 153, 170, 168, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(637, 833, 153, 170, 168, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(638, 834, 153, 170, 168, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(639, 835, 153, 170, 168, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(640, 836, 153, 170, 168, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(641, 556, 153, 170, 168, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(642, 837, 153, 170, 168, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(643, 838, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(644, 839, 153, 170, 168, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(645, 840, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(646, 841, 153, 170, 168, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(647, 842, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(648, 843, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(649, 844, 153, 170, 168, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(650, 845, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(651, 846, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(652, 847, 153, 170, 168, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(653, 848, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(654, 849, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(655, 850, 153, 170, 168, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(656, 851, 153, 170, 168, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(657, 852, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(658, 853, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(659, 854, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(660, 855, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(661, 856, 153, 170, 169, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(662, 857, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(663, 858, 153, 170, 169, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(664, 859, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(665, 860, 153, 170, 169, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(666, 861, 153, 170, 169, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(667, 862, 153, 170, 169, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(668, 863, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(669, 864, 153, 170, 169, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(670, 865, 153, 170, 169, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(671, 866, 153, 170, 169, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(672, 867, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(673, 868, 153, 170, 169, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(674, 869, 153, 170, 169, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(675, 870, 153, 170, 169, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(676, 871, 153, 170, 169, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(677, 872, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(678, 873, 153, 170, 169, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(679, 874, 153, 170, 169, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(680, 875, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(681, 876, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(682, 877, 153, 170, 169, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(683, 878, 153, 170, 169, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(684, 879, 153, 170, 169, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(685, 880, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(686, 881, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(687, 882, 153, 176, 174, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(688, 883, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(689, 884, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(690, 885, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(691, 886, 153, 176, 174, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(692, 887, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(693, 888, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(694, 889, 153, 176, 174, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(695, 890, 153, 176, 174, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(696, 891, 153, 176, 174, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(697, 892, 153, 176, 174, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(698, 893, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(699, 894, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(700, 895, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(701, 896, 153, 176, 174, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(702, 897, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(703, 898, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(704, 899, 153, 176, 174, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(705, 900, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(706, 901, 153, 176, 174, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(707, 902, 153, 176, 174, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(708, 903, 153, 176, 175, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(709, 904, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(710, 905, 153, 176, 175, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(711, 906, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(712, 907, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(713, 908, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(714, 909, 153, 176, 175, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(715, 910, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(716, 911, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(717, 912, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(718, 913, 153, 176, 175, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(719, 914, 153, 176, 175, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(720, 915, 153, 176, 175, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(721, 916, 153, 176, 175, 'AllRounder', 0.00, 'No', 0.00, NULL, 'No'),
(722, 917, 153, 176, 175, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(723, 918, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(724, 919, 153, 176, 175, 'Batsman', 0.00, 'No', 0.00, NULL, 'No'),
(725, 920, 153, 176, 175, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(726, 921, 153, 176, 175, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(727, 922, 153, 176, 175, 'WicketKeeper', 0.00, 'No', 0.00, NULL, 'No'),
(728, 923, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(729, 924, 153, 176, 175, 'Bowler', 0.00, 'No', 0.00, NULL, 'No'),
(730, 925, 153, 176, 175, 'Batsman', 0.00, 'No', 0.00, NULL, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `sports_users_teams`
--

CREATE TABLE `sports_users_teams` (
  `UserTeamID` int(11) NOT NULL,
  `UserTeamGUID` varchar(36) NOT NULL,
  `UserID` int(11) NOT NULL,
  `UserTeamName` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `UserTeamType` enum('Normal','Auction','Draft') NOT NULL DEFAULT 'Normal',
  `MatchID` int(11) DEFAULT NULL,
  `SeriesID` int(11) DEFAULT NULL,
  `ContestID` int(11) DEFAULT NULL,
  `IsPreTeam` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsAssistant` enum('Yes','No') NOT NULL DEFAULT 'No',
  `AuctionTopPlayerSubmitted` enum('Yes','No') NOT NULL DEFAULT 'No',
  `MatchInning` enum('First','Second','Third','Fourth') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_users_teams`
--

INSERT INTO `sports_users_teams` (`UserTeamID`, `UserTeamGUID`, `UserID`, `UserTeamName`, `UserTeamType`, `MatchID`, `SeriesID`, `ContestID`, `IsPreTeam`, `IsAssistant`, `AuctionTopPlayerSubmitted`, `MatchInning`) VALUES
(928, '7930bd6f-b573-9871-0e6d-615f3e1aef49', 927, 'Team 1', 'Normal', 129, NULL, NULL, 'No', 'No', 'No', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sports_users_team_players`
--

CREATE TABLE `sports_users_team_players` (
  `UserTeamID` int(11) NOT NULL,
  `MatchID` int(11) DEFAULT NULL,
  `PlayerID` int(11) NOT NULL,
  `PlayerPosition` enum('Player','Captain','ViceCaptain') NOT NULL,
  `Points` float(8,2) NOT NULL DEFAULT '0.00',
  `BidCredit` decimal(10,0) DEFAULT NULL,
  `SeriesID` int(11) DEFAULT NULL,
  `DateTime` datetime DEFAULT NULL,
  `AuctionDraftAssistantPriority` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sports_users_team_players`
--

INSERT INTO `sports_users_team_players` (`UserTeamID`, `MatchID`, `PlayerID`, `PlayerPosition`, `Points`, `BidCredit`, `SeriesID`, `DateTime`, `AuctionDraftAssistantPriority`) VALUES
(928, 129, 432, 'Captain', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 434, 'ViceCaptain', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 429, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 412, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 409, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 416, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 411, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 427, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 428, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 431, 'Player', 0.00, NULL, NULL, NULL, NULL),
(928, 129, 414, 'Player', 0.00, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_action`
--

CREATE TABLE `tbl_action` (
  `EntityID` int(11) NOT NULL,
  `ToEntityID` int(11) NOT NULL,
  `Action` enum('Liked','Flagged','Saved','Blocked') NOT NULL,
  `Caption` varchar(255) DEFAULT NULL,
  `EntryDate` datetime NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auction_player_bid`
--

CREATE TABLE `tbl_auction_player_bid` (
  `SeriesID` int(11) NOT NULL,
  `ContestID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `BidCredit` bigint(20) NOT NULL,
  `IsSold` enum('Yes','No') NOT NULL DEFAULT 'No',
  `DateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auction_player_bid_status`
--

CREATE TABLE `tbl_auction_player_bid_status` (
  `SeriesID` int(11) NOT NULL,
  `ContestID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `PlayerRole` enum('AllRounder','Batsman','Bowler','WicketKeeper','Other') NOT NULL DEFAULT 'Other',
  `BidCredit` decimal(10,0) NOT NULL,
  `PlayerStatus` enum('Upcoming','Live','Sold','Unsold') NOT NULL DEFAULT 'Upcoming',
  `DateTime` datetime DEFAULT NULL,
  `CreateDateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entity`
--

CREATE TABLE `tbl_entity` (
  `EntityID` int(11) NOT NULL,
  `EntityGUID` char(36) NOT NULL,
  `EntityTypeID` int(11) NOT NULL,
  `CreatedByUserID` int(11) DEFAULT NULL,
  `Rating` decimal(10,0) NOT NULL DEFAULT '0',
  `LikedCount` int(11) NOT NULL DEFAULT '0',
  `ViewCount` int(11) NOT NULL DEFAULT '0',
  `SharedCount` int(11) NOT NULL DEFAULT '0',
  `FlaggedCount` int(11) NOT NULL DEFAULT '0' COMMENT 'inappropriate content',
  `SavedCount` int(11) NOT NULL DEFAULT '0',
  `BlockedCount` int(11) NOT NULL DEFAULT '0',
  `EntryDate` datetime NOT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `MenuOrder` int(11) DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_entity`
--

INSERT INTO `tbl_entity` (`EntityID`, `EntityGUID`, `EntityTypeID`, `CreatedByUserID`, `Rating`, `LikedCount`, `ViewCount`, `SharedCount`, `FlaggedCount`, `SavedCount`, `BlockedCount`, `EntryDate`, `ModifiedDate`, `MenuOrder`, `StatusID`) VALUES
(125, 'abcd', 1, NULL, '0', 0, 0, 0, 0, 0, 0, '2017-12-30 12:19:27', '2019-04-25 12:08:29', NULL, 2),
(126, '3c03b117-36bf-a4eb-5c53-b49876793bf5', 7, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(127, '289f50c6-e8e4-84ac-67a5-9f845d08dff5', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(128, '6643cc17-5eb6-6b79-dfb3-6d032d13ae77', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(129, '24ed79c7-13eb-c05b-c206-7493ca9d57f4', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(130, '55a36d55-a095-eb3b-258e-dbc8500e01c8', 7, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(131, 'ca8501ea-6ec2-803c-fbe6-264d6a36dcc5', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(132, 'b4db1d1a-383c-a458-c91b-2836f08169b3', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(133, '06d2737a-2531-30cc-1572-4e29d3f0837d', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(134, '38f7d00d-9902-034f-7e96-92354c797f1a', 7, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(135, '41e72b32-26be-6e2f-46d0-2eccf8bb0a8a', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(136, 'e9e31e86-fc68-9c34-713f-eac420c088e2', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(137, '618f29b7-e330-d76a-3446-dcb2d885b0e2', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(138, 'b79476f5-8f38-4c56-549d-d32cff552c6a', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(139, '5f49ab11-ec1e-0e00-fc04-0696a8560beb', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(140, '43a4684a-6dc2-c021-7535-40d1c331ac53', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(141, 'ed217f71-09fa-5fc7-d3bf-9b98c560c656', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(142, '58cedb7d-bd6d-4b4e-d348-03b4f2b9448e', 7, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(143, 'eb9b8979-7124-4918-acef-5eec2651014d', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(144, '3fe3d8fc-bb13-ca73-a27f-678cd6ae3697', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(145, '5c5a6359-043c-feb7-8af6-da8a58d7e485', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(146, 'e986c274-c2d8-7ca7-a564-62f48890465f', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(147, 'e14e918b-f6e5-376b-b23b-f5a092efa731', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(148, '68aa899d-b082-601b-bd6f-292e54ab3374', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(149, '6257971c-5937-a2e7-acfc-bb5e2365e480', 7, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(150, '0b063f93-1015-0ac9-cae0-d794b2363e32', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(151, 'da59e6c3-e5db-30fa-150d-511a127ea355', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(152, 'de36e99c-a8ee-8c8d-bf21-8a2fafa04475', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(153, 'b60dad9f-24dd-90e9-ddec-c6c70dd27d88', 7, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(154, 'b162e897-d17b-9335-3919-2366afc7a7aa', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(155, 'e3517c1a-d28c-67e9-629e-3feafae2fce7', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(156, '386d2518-ee59-d45c-c63c-653633f8146c', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(157, '7185b9b3-fda6-52b0-28f7-aca89a9a17ae', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(158, 'b4a5de29-4591-c7f7-cca2-3edf755050d3', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(159, 'c82b440d-dc50-e3c0-e890-d602ec998d32', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(160, '2a1a40d3-e069-afd2-b562-81b820672e97', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(161, '9eeb2e28-9eea-9bab-b641-a04d1c9a38e4', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(162, 'cd1e128a-b7ad-6ff8-31ed-577858a8c915', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(163, 'bcf0b59d-dd5a-4f08-6911-5fc4f44f2880', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(164, '7ad6eb47-32c5-8718-514f-00fbb6f26408', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(165, 'ba635aff-4b5b-807f-fafc-e0e5ad9c6080', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(166, 'e5fab056-f70d-f93b-d85a-b03b61005e32', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(167, 'f03c8560-6cee-f3d0-2ace-a4c8f5a50f94', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(168, '4df9aa26-2e8a-a20a-c7b4-5218f1783301', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(169, '72a34012-1564-6f8e-4506-87b9e2ba04b8', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(170, '935f908d-7dc8-eeae-0d1a-c2e00869d612', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(171, '14c1a7b1-01ee-9f2c-b45f-256302487884', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(172, 'b6b040ef-c7b0-d3d0-8418-c9fc3649d95a', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(173, 'b3a18702-0e6c-e62e-3927-472feacab60e', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(174, '2f104fad-bde0-6eb7-2b81-d6c81cb73011', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(175, '2bcd011e-f58c-1a01-20b3-f0d2cd904ac0', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(176, 'c51361df-fc2b-94c6-5eca-50623271cc42', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(177, 'e32ec6d5-c86a-41f3-7e14-fb3e9e28596a', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(178, '8a6b3da3-af2c-2193-72ea-61dcc7c4f67b', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(179, '9aaabe93-3fe2-3a60-f718-4b80eef506ea', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(180, '7435b101-f755-48a0-ad55-a4cdf6d848f8', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(181, 'edabdc05-a30b-9ff8-92e4-aa6fb3b1e0dd', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(182, '735e6738-801a-fb41-8037-afb4ac92adac', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(183, 'bd43664d-32a1-dc47-c218-0b17ef6e5d84', 9, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 2),
(184, '65aa9ea6-f042-a69d-dd01-b04c8a949014', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(185, '541342ca-11bb-b24f-360b-c7730b13132f', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(186, '4bc393ca-9704-c839-0d85-5047a83f082b', 8, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:41', NULL, NULL, 1),
(396, 'c6a57fe4-ad7b-c03a-6ce7-122d1032bf58', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(397, '57cf4532-516c-2f2c-844d-ca9b7d9767fd', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(398, '6002d0c0-c821-7bee-fd0d-93532ccd5703', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(399, '57ae464c-7b47-41ea-0626-85ac2583fc4e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(400, '2095d355-b1cf-445b-787f-eb9284cdf16c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(401, '19bff90a-15d5-a4ea-6ea5-8f1214913038', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(402, '1f4b955b-8668-5d08-ee0a-ee5f25352566', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(403, '6042d3ff-cdf0-7acc-7d6b-254232390843', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(404, 'f0a9d2e8-0b2b-559c-5c89-0efdfbbb96da', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(405, 'b5859cc2-ce62-9526-62e7-74b831c6c90e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(406, '470599b7-4d6c-fd97-014c-037ef43f07a2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(407, '037071b2-4e65-a8ab-a98a-813f3cb3b8c6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(408, '72ed1970-1d33-94b4-d2c8-c7488e8d6b2b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(409, '2e621b7a-8405-32b0-22ea-a032d09e1997', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(410, '4f705fed-3bbe-9452-6c5f-a2d242b7445a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(411, 'f1aae7e2-618f-0a9f-a64e-21dd661cf28c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(412, '57b0aa0e-03d9-795c-f4ac-4fd1ca5b7845', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(413, '89a1a608-8894-33e5-d691-317c6bdb9889', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(414, '0036ce0a-42f1-e090-80bb-7ee557e88e5e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(415, 'a11b5c12-2b4c-e19a-c753-96c7b4c02610', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:49', NULL, NULL, 2),
(416, 'b3e178cf-b274-4509-c7e7-7d5f20061664', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(417, 'f60e82a6-5f3a-0017-5da1-ddabd627e876', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(418, 'b762ea7b-e85a-0023-7ddb-c12508527f4a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(419, '536f637d-99cd-67ba-8602-2f87a0720b0b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(420, '603d3005-c761-0f5c-d141-1b49728dbc7a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(421, 'b70061e1-91c3-bef8-3b81-764b3493eac6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(422, 'b13f4fe2-e707-bea2-9000-77c3e8d22079', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(423, 'c9c76582-9dfe-fb76-f252-a7cac9519cbd', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(424, '1749192d-bac2-508e-9a87-65c085e02e9c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(425, 'c9b219a1-c0ab-2bf4-3eba-29040dd5bc73', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(426, 'e9ed0e7d-2598-5f81-df32-8b19e04c52b4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(427, '03cebbdb-5a2f-0480-b857-5b2c1a75b21a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(428, 'b85d2b98-81c6-3716-e03e-467d270b6775', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(429, '01c1c3e6-30a2-4c6a-da73-03f9cd31f077', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(430, '8e527ded-f97c-823c-01aa-3f96fe3785aa', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(431, '3794ed9d-b74f-793a-68fe-fb33bb3d0cd7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(432, '52625c5f-821c-2ff9-54c8-0003ad3385e6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(433, '7af6209f-f5d3-ad04-d9ff-8d28b98a1e48', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(434, '944b88ae-3510-de54-9a61-c74cb0614912', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:31:51', NULL, NULL, 2),
(435, 'fc2d0aba-07e7-0590-ed77-fd0c45a5821e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(436, '6212e663-4575-8659-7ca8-df330ec66ac4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(437, '85bc02f0-f9ba-da9a-cc0a-87bf239d3a99', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(438, '3badbf6a-2d3e-ad59-3a10-fb9b99e2ba58', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(439, 'edfd0536-7186-d31d-f90f-e2eeedcd73c8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(440, '8e645a68-e86f-2a96-6a01-2ab2ca6f3126', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(441, 'cfaf6a90-58f3-b95f-aeb7-d120bd638e1d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(442, '2e9a417d-be31-60f8-c840-7660f064f685', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(443, 'e3b7b3d9-12ee-8de9-a3b2-4c5ae1e3087c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(444, 'e28e04c6-4a57-8705-d91b-a491e3d6b6e4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(445, 'cd4b767c-b09e-bc74-7209-924e8ef30433', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(446, '05a799fa-dffa-40bb-14a8-a49c7f0ae66d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(447, '1914c427-3f30-6364-84af-7c3c85fa053c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:00', NULL, NULL, 2),
(448, 'b43d57b7-8ca6-b265-7caa-738e796fbaa5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(449, '1feb415f-0a98-7b95-7635-8cb846c3fc4e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(450, '4f5347f9-2aad-1a6e-9338-f73c853e2d27', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(451, '51639536-7b1b-a1fd-334c-2a747c0ac3a7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(452, 'bee30cad-065b-ff59-6a29-70a39a5275eb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(453, '8c2e44dc-f258-b512-8d81-aaae6a33aa46', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(454, 'eae84d1c-0e94-fa8f-27da-3a7a85dfa1c1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(455, '2af8f31b-6e09-261e-2dc5-85cc7a3cba26', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(456, '861ef76f-3d92-31fb-2f4a-cc6c212bb437', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(457, '9b416180-167c-1245-9dce-8cc4d871fcf9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(458, '922631a1-ad4f-505b-6e92-65c48770e577', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(459, '8952e148-5086-d171-442b-fb96e9b2d7ec', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(460, '0d25fadf-54d8-0061-2944-dca69efd3366', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(461, 'c884ef1a-b8d1-61fa-b924-8e9c7458f9e2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(462, 'addbc398-7b53-5943-f838-0e4f17402f39', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(463, '866a8e2b-feb6-e3ce-48a4-6e83bad52af6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:02', NULL, NULL, 2),
(464, 'f94f1934-a72c-ed36-3555-2c98880d4d1f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(465, 'f8aefd44-e970-8e98-6df5-acfa50b677e6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(466, 'e1eb4cee-eaba-7e7c-1c82-766467ea174f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(467, '04997c59-08fc-86d1-deb5-d55b677fd15f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(468, 'e1887317-d100-b622-c0b6-981a9290412e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(469, 'c1ae2659-f95c-f449-257d-7162357e5fae', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(470, '0f07f25e-4efa-c959-ca79-16c1eab70117', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(471, 'c5e1180e-40aa-74ac-70a1-ea9bd160f151', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(472, '8f61de65-9d17-45cd-3e89-82dd791258c8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(473, 'de39db0c-7b4c-599b-2148-fb42058e8050', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(474, 'f9f9576d-bbfb-9334-c695-3cfcf6a9b424', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(475, '478cb4e4-507c-ccd1-313c-23d30174d419', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(476, 'dc8091f4-883e-c1d6-4741-e531a8bd31a7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(477, 'c85fdc26-c663-c57d-ba16-68d467714086', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(478, 'b8e86654-0ac2-aca2-92b2-ca664f2470fd', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:08', NULL, NULL, 2),
(479, '08b0fee5-fd15-522f-ae42-779cc801c3dc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(480, '7bdf3287-7f51-00c7-22d4-42b3f6f3bc0c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(481, '0faa8581-8bf2-4171-8753-3b263665ad31', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(482, '9beb8620-98d9-b4f1-b917-53fa7c688fbc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(483, '788435bb-2ac8-f253-0b46-81c32aa2c2ca', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(484, '986a571a-115c-409a-fec7-5d7b41ce04ba', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(485, 'c5daf170-6c6e-080d-4990-72882befb48d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(486, '6eb0e197-523e-6bb5-27db-d0e4b3a2b8b9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(487, 'c48b6f83-b37d-5aa3-a135-97e58e9e54b4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(488, '6c35e59c-efed-09e7-5480-265585866040', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(489, '4cf80ea1-a8e5-5c20-95fc-94b49dec2c06', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(490, 'eb5b302a-f76b-d261-3268-8b58a0c78e35', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(491, '00ca033e-e754-f31a-0c04-443fde37d12c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(492, '7e799750-4ff5-c034-a6a6-a9580e6e9c67', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(493, 'bd7f4538-a7a5-fbac-4079-8c98e8f5362e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(494, 'e7f4a2e0-ddef-9b74-866c-f5c5f10bfeef', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:10', NULL, NULL, 2),
(495, 'bd8f1cdb-8cfa-588f-5b68-792402928489', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(496, '7d21931f-555e-9378-3395-3ace114f537f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(497, 'af0a5ea2-43d4-909d-bfdf-85c9ef759a3e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(498, 'aa25878a-42ac-c0d2-a2f2-5ce3121780dc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(499, 'f79ca0b8-410f-5a1a-a78c-d52cbcd66f62', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(500, '352cdab8-9164-3623-7882-7cec1c25b781', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(501, '98da6036-0140-bc08-e764-780964fb1dbb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(502, 'f189a3a7-7901-cbdc-8704-036bbe9a78d7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(503, '1e3680e4-2815-8af3-6f34-45ba5f97d208', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(504, '268adc03-c039-e41e-942a-6d19f9845591', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(505, 'bf86d8bf-f9bd-1b51-9269-c9b84171564b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(506, 'f00900b1-b126-6cea-8430-7c5dff520980', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(507, '20072032-254e-5ded-e3e3-c9b43276a461', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(508, '33f2d124-58cf-68b6-ff7d-00dcf7b4555d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:15', NULL, NULL, 2),
(509, '256a3519-6760-f735-c4ab-a2d98208799e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(510, '31c6645d-9c86-0975-4fc3-00dfda78c4d9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(511, '537be1f2-66a0-1d86-5b46-c708af34341d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(512, 'd3485bb0-4340-9691-dd92-3c6fd53918c5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(513, '5ec41172-6eb6-7011-b66b-09acac1a546c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(514, '6314a690-6f01-fc1b-47b5-acdf73e05b70', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(515, '7ec005cb-eade-193f-c7e5-6d337c732c19', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(516, 'bcde96ab-a7c8-a263-ea41-7f490cedf750', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(517, '48ab5be0-e37a-c4ad-c0d6-1e41c6847cfc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(518, '2a424450-5ce1-6418-3d94-a58edb20562f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:16', NULL, NULL, 2),
(519, '3822bf56-e375-0a28-f15a-b465e8d95f7a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(520, '3aa2e711-bbbe-9a8a-ab7b-8e1aafb17e8e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(521, '5db2ca4f-af2a-9b1a-51ed-ae4cdb7248f9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(522, '2da48d52-c8ec-fe1e-e30a-3910131e91ca', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(523, '37253a58-3b64-3d4f-72a3-8bb8d873383f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(524, '2c1638a4-c2a5-174e-1c88-22c18e8bfd29', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(525, '57736f13-a7eb-d1a2-4d37-a636cd6c907d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(526, 'cca3f9a3-c9f7-9e68-393b-721e53eb8460', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(527, '685d60e5-ebb2-e398-7ee6-0a146d780ba8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(528, 'c8fb4737-3275-93a4-5273-2c0e1e825f76', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(529, '530cf3f1-f1aa-401a-49a1-8193ce5c0073', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(530, '83739947-9cef-1639-0b5e-0ffb1551a0b7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(531, '9dd43625-ee2f-fa93-9b88-780a93130987', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(532, 'ae6893c6-4e54-2793-6edd-f9cc381843da', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(533, '10de400b-10e7-28a3-95a5-954ec18d6b8b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(534, '2f856703-ab2d-47e7-1545-7fb151bb4a5f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(535, '05e2babd-8352-4c6f-3876-3b5590697ddf', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(536, '00f244a2-ebb0-fe15-dc07-1ad920ea401e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(537, '862c82bb-24a0-457d-1b1a-993c27294838', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(538, 'bab1fca5-e01c-8155-a386-ce951552fd66', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(539, 'c42316e3-1e5e-94bb-8933-bd6c0bbf35db', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(540, '583bbdf6-94f9-116c-32de-66752633af38', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(541, 'b0344a18-e3e6-b3de-74f1-543e836315c6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(542, 'ddc576a6-54dc-b1de-6dcf-b078e3230ad8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:18', NULL, NULL, 2),
(543, '118b12e7-fb8a-3cea-ba3f-30df000f7975', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(544, '1d19456d-7735-c522-cf4b-da4a66774f76', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(545, '6eec9152-e5b6-75f0-e7d6-68986d6b3c87', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(546, 'c581f9d6-200b-c614-52fe-88de26a1734f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(547, 'db50d40f-d42e-fca0-4035-0acdf35747c3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(548, 'd501bcc3-64ca-4cbb-460f-d04024b239ef', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(549, '7cc682ca-bd66-c38c-c464-3f82817d5c79', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(550, '736ea445-8e7f-5096-b9b2-143d6deaf60b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(551, 'f799ae74-a8b5-394d-9838-4c1d040979ba', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(552, 'bdbdb3f5-da55-c199-3fb7-e13b5f3f613b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(553, 'b458b953-9ea2-2b79-dbb6-0109e01ba7fa', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(554, '6c1a5c74-182f-58b2-07a4-94d440410754', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(555, 'c2feacd5-6a05-dc2c-3f54-0f78c498d5f0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(556, 'b43bfd2a-d9c5-63f2-86e9-f97c0de7be29', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(557, '8685a9a2-e19d-325e-6304-d2832a8338cf', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(558, '728a2406-e097-9d6a-17fb-e7cb7d571cd2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(559, '39dc1b3a-0868-93e1-155a-759ea1740bb0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(560, 'b7e3eb7a-e9ba-4252-4c8e-fb268b1c1829', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(561, 'a937f966-dd61-0ee2-352d-b80454c8f909', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(562, 'd9fe33c2-e72a-2a8d-37c5-bc715632f2cc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(563, '84909906-cdd2-d2a2-39a7-e772a8bc0f2c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:30', NULL, NULL, 2),
(564, '361aa1ae-4022-a4a3-ad92-d61ac3fe2765', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(565, 'bca78589-fa0b-0ff7-67f1-f9f1da6e184c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(566, 'f23c3faa-35aa-3938-14ae-3a46df41f08d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(567, '0150a88b-efe7-4317-126a-917a213feaee', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(568, 'a4b0b464-9e3d-de29-d38b-855d794a9358', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(569, '673d38ff-5c50-445c-154d-dbd42132869e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(570, '8317d4ee-0de7-e8c6-0b31-26c43f719d17', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(571, '4a9fe71d-8e9c-2a7c-101b-b2bfc5ee0804', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(572, '6fbdad8d-1ae0-15e4-5aa4-ce42d2bcb7c8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(573, '120696f3-0c08-a3f8-58dd-0ba17825eea1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(574, '9bbfa98d-7a98-9f3e-6a5d-62fe3384eb58', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(575, '129cb43b-74b0-50c4-1dab-495b3bdf958e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(576, 'bf9bd63c-6e0f-7a60-87e9-e86adb5d39da', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(577, '866c94a1-1f31-06d4-cd11-e332f59d0d32', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(578, '507d7847-06cf-0f77-0033-db67968919ac', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(579, '4a53e1c1-81b1-fd27-1ef6-f8f7711ccbc4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(580, '499ea3a6-e638-df98-1962-20362b337445', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(581, '0f3c7394-4f2e-f3ff-69d5-3cf7f02f0b0f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(582, '078cd9ca-583b-2731-d4c2-ee6dfddc979b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(583, 'ce50f95c-431a-9358-5683-170426bc5acb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(584, '357e1a18-2a6e-aca2-c3c6-69ed5adaed54', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(585, 'deaac70c-45fc-611d-ff36-fa15b7301afc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(586, '0d15c436-9a07-0828-9b35-b0a9a593a545', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(587, 'b995c6ec-5253-c3db-9253-ee56150d52f4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(588, 'eebe8ed8-5e6b-6ddd-ec7b-734909232c2c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(589, '47d328df-8458-4f28-8dc1-a76e443a53a2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:33', NULL, NULL, 2),
(590, 'e0b1a597-7001-8d28-c8f2-9f7927615ee5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(591, '5af6004a-5835-f743-d140-a0fb91bd9944', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(592, '3054735a-9d09-2447-ca0e-64f90b3a35c2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(593, 'd5370ed5-a1e9-7993-a67e-77392cbe602c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(594, '4ac483a1-0c4c-f4c1-8655-cfde390a949a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(595, 'ef620b81-27c7-741e-46b9-c1b181dbffae', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(596, '21bd5d71-563f-0fb7-0c68-1d2a1e88b238', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(597, 'addc6e53-b7e7-9661-50c6-b7b746a080f3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(598, '3ddd0774-5bc3-d18e-b1a0-9b92a2249604', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(599, 'fb04c55d-83a5-acd5-262b-0c03ced13136', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(600, 'ab4db264-d287-26e5-fec6-afa5b66827f4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(601, '19f785be-d23a-2771-65b8-70f35523d01b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(602, '5e416640-ebf1-353c-7927-2e9a2ec97147', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(603, '5c3931f8-9481-aac0-b540-a767793a4b24', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(604, '30899efb-ac71-09b8-7206-5379afe430f7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:39', NULL, NULL, 2),
(605, 'c3017f60-83ea-1c99-382f-3a134797b663', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(606, 'b22e6788-cdf2-a034-7c02-64fd363dac93', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(607, '0fed96ed-75fe-2f34-35ce-3465f0e6511c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(608, '92d3c5cd-bfed-6aed-ffc0-402fb5d12ba6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(609, 'e06d21df-1c0c-63fa-609c-8a3ca47c82c9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(610, '1c7a13c9-09be-323f-dfa0-88b2085091eb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(611, 'e2ab8d8f-8379-0345-e29b-af13a1501536', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(612, '4bbdc541-489d-2a70-7afe-541dbf35bf8c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(613, '3212d189-8533-206a-0662-0238829d102e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(614, '8f636ebb-cdbf-646a-0704-48e717d5abff', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(615, 'c7c65956-4393-c434-1d40-a9479f8e4cde', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(616, '73ba0e3e-ecae-4648-23f3-887091f7f916', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(617, 'f3347aef-53ee-a8f0-6d46-10facfc17a44', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(618, '0aceda82-3455-c16d-0719-495f1a965116', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(619, 'f27d4430-3657-e28c-f0cf-3194a82a6c9d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:41', NULL, NULL, 2),
(620, '22a1cf0a-524f-bbeb-d82b-9e27f43a7677', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(621, '0b7431e3-eeeb-d9b7-1666-8e81c66b399f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(622, '2b2da2e2-e00c-f755-1545-27a594683c2e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(623, '491bb97a-2a0b-e3f0-7aa2-339dfb364f24', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(624, '6418f1df-444b-b407-e05b-9a15ef83ff16', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(625, '550254b0-c16d-4e4a-ad4f-4a515ae36f80', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(626, 'f1c56725-1976-0677-9b27-3774ca6e6149', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(627, '7b78caac-613e-73f9-705f-cd71464fe74f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(628, 'cd6e5dce-87b2-2a9c-363d-cd73ed1fed97', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(629, '9b33c265-48ee-69ac-348b-accf41d189ce', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:32:46', NULL, NULL, 2),
(631, 'fe4c8a1d-d6e7-4fc1-4088-17635c83ef01', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:18', NULL, NULL, 2),
(632, '9805e6ec-7c23-c3d5-a613-5f85c8cf16bf', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(633, '3f007bb8-d0b4-d706-7a77-21e09c36733d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(634, '3e5c3739-dd8c-f7a3-ea7c-2352865f8165', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(635, '055e5b87-ad0a-9e2f-8b70-57f69cf681ab', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(636, '540aad1d-7888-4466-b1cf-5378002b932f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(637, '3ff07373-b3f9-0d8d-4f28-d23b140ca0cf', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(638, '419fa348-91a8-1623-d169-9d44e9fbb96d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:19', NULL, NULL, 2),
(639, 'ffa1acc6-cb23-7e2f-242b-42cf2a83af64', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(640, 'b95a2ec7-a433-589d-ffa0-51b0e92107c4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(641, '491e184c-06e0-b308-42e6-87cbbe452b2a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(642, '60aab696-6964-2d8a-d686-2ee66132de8c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(643, '6bbe574a-2bbb-8b46-342e-96331f4ff1ba', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(644, '789b85a7-b0df-9a95-c103-131eb75df81a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(645, 'a9c2e61a-350c-339f-aa2a-348362f5eab3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(646, '7a973642-2e57-0d37-5d9f-0b46b53a9132', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(647, 'f5802d69-1911-f373-b41a-db5de1b55af4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(648, '7677959d-37c2-2bb8-3eda-f2388f0be737', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(649, '7434a098-c998-3610-a2fb-45e42e5874bc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(650, '18a6e440-e55e-8464-24e3-48e6b33c93ff', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(651, '97844664-3bd2-5511-fed4-f4000f035249', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(652, '23b0479b-5563-0dbf-d662-ae2cb7d88199', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(653, '89ede5d1-1f4b-4e1c-8d4b-ca02780054c3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(654, 'ea85d836-f911-8dfe-c22e-7f3637fa0ca3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(655, 'cb71d47f-5aac-9e5b-f593-24007fd98300', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(656, '1e9f5c65-b949-f1e5-3fff-714b8de95bad', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:21', NULL, NULL, 2),
(657, '670f38c7-f309-58fd-5861-56a50917affb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(658, '903a691b-34b6-a9c1-fa1a-3bd5e09644fb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(659, 'cfa9c8e8-dcab-7b31-2653-415d54ef0d12', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(660, 'b1108996-cccc-f27f-e06d-61a2bad2106d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(661, 'fd238534-bdf0-4108-5a21-98f0397a68cc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(662, '05e3c62a-4453-a278-979e-3d94c3630a5c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(663, '45898a6e-3df5-eedb-1390-a8e0b91ebe67', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(664, '30d358a4-6002-f23d-8005-02ce0f57a4a8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(665, '2ea77798-65c8-2d76-7830-b9b6178db4e5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(666, 'fb842b4b-18bb-2110-b2b3-b33c31bc28d8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(667, '7ca444d5-5b3b-1515-5c50-523cc7b0546a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(668, '8939a063-a1b5-b4df-ab6e-dd78bd44b768', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(669, '2411e8cd-c383-a25c-f5c2-d21369427cdb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(670, 'c0d775e3-50c1-1450-afb0-7d032a996f2b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(671, '9cc8c045-e845-d460-623f-d9fa99a4112c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(672, 'c2dc8c04-f3c7-d1fe-b5a7-c54f7904069b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:26', NULL, NULL, 2),
(673, '58cbef86-3b32-5661-59f2-53d7bcb0e48e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(674, '3a921685-3509-69e9-1308-0bb2e2a0201c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(675, '5838cb44-6f56-ea4e-ee07-82cde02418ab', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(676, 'd3903344-c52b-67cd-4771-43a560c3fea7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(677, '8e9b9ee7-e4f8-c0c3-8f83-2c188c326c2d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(678, '9ecfcda6-933b-30fe-67a4-d697c0f9f561', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(679, '52887ec3-9fe5-20e7-d131-55866bb8316c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(680, '3e0fce44-9c8e-9fa2-75da-47ac635c7b80', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(681, '77690a0e-f12f-c156-83a4-9699ccc31dfe', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(682, 'e146143d-3e05-553e-8b25-587baadd4560', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(683, '7dfe4ca6-38e0-a0b7-731d-5cebb2f8b9f1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(684, 'f1522466-a34c-1733-f389-8ea6cbc495e2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(685, '898ebfed-08f1-9dbf-5307-2cfdcbf090b0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(686, 'b9526dd0-d1f8-795b-847d-9dc24e2e93be', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(687, '7b8f4ff5-d2e6-7f35-ca7e-8b4c374415cb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:28', NULL, NULL, 2),
(688, '568bff0a-d02c-7f71-6daf-52199316bb3f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(689, 'ad1f6934-6264-ad1b-15da-82e2bd2944ed', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(690, '99ac6f04-e8c2-032a-876f-dec5db1ac73c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(691, 'df785e81-ef6b-7099-a702-3ba7c9cc5d67', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(692, '39bbd832-23ba-fceb-808f-f2daf5abf62c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(693, 'ccfef74b-7d81-9330-2187-c84c2edf1739', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(694, '9bd4d1df-ed95-34e5-f6a7-318f24b19141', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(695, '02b7295a-d7d2-63cc-cb82-bed5c656dee6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(696, 'e6830a43-f7fe-2102-c3a6-7e0d2f02df35', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(697, '992737ad-2adc-fe43-b587-be4a36588838', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(698, 'ae3e3134-035c-6ecf-6cd7-60e4b5e4e3f4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(699, '1a73134a-c027-e4f0-3a6a-de24408fe0b6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(700, 'dedac522-7c97-ceee-a3e9-a359ddc0553d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(701, 'bad68f43-5194-9ac2-f840-bce92ad15954', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(702, '97a202ee-e7ba-9457-3d5b-4792c5c03660', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(703, '783f048f-0841-ee90-7f2b-4a2060838a69', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(704, '99c67073-84be-3004-8cba-06b3a3d24574', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(705, 'b70228b4-c5ca-9c88-c8eb-add44e307eba', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(706, '93e9ea3c-70bd-f4f8-a2fe-4e6c48ac40df', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(707, '9e6fa218-e2db-9c03-dde4-a6d4a731bc2c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(708, '0163f5bb-c940-dc83-f076-9fed4ae3d930', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(709, 'df5717d4-03fc-a614-8950-da494d93b40c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(710, 'bc9e60e2-bb67-250c-b70d-a7794a29241d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(711, '52e95958-3862-fdec-f0e4-e5af777e6527', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:33', NULL, NULL, 2),
(712, '3de62ff7-9387-4dc1-c0d1-7a645627596f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(713, 'a9bd43b2-5c39-366f-6913-6bb014691bce', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(714, '6045ea0d-771d-2e79-5f87-49fff3f64957', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(715, 'd967df8d-2e20-934a-1db4-5f503e01dee8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(716, '107058fc-18b9-ae1d-8db8-ffd82536c117', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(717, 'a601fec6-5d63-6e9b-de59-49c918249168', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(718, 'c548f18d-1b12-aba4-287f-011306a4a94a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(719, '8d50e57d-e69f-d074-d49a-589c0795e4da', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(720, '8a72a85a-6cbf-0496-df9d-ef7c824c89d7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(721, '529cd381-5d1c-72cc-792b-b4975e8f244f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(722, 'a1ee6970-f0f7-2da7-b51f-15facd979df5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(723, '39402425-177e-de98-fe30-2cb6584c97b9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(724, 'ef3619e5-d131-bdef-ad79-882be8e04955', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(725, '7191ad87-ca47-d478-34ed-ae70c25c3974', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(726, '2adfcb66-e589-c5fc-6dac-cc874e4b1132', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(727, 'e40f1a4b-590b-244b-11f2-7d7fbba4b5d6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(728, 'e97940c8-9d1d-b059-eefa-139c1d567394', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(729, 'f11dddb6-9bb0-5d48-2894-fd16fbbc95a9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(730, '439378a1-dc36-5102-06ac-3b045d430705', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(731, 'ca1728bc-ecb5-12b2-9b4e-8b96824ec698', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(732, '3d496e43-5f91-481b-5051-39d410215e35', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(733, '8c8dfb35-41a3-bd5d-7409-d7d8851c539b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(734, 'e852eb5e-2c93-0e89-3427-c65d8a739c38', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(735, 'f73b0ccb-22d6-0197-92ed-7ac2776e6f02', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(736, '5a4682c3-9c01-b75d-3838-bae8b41ee710', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(737, 'ee7f5bc5-0e68-08c6-74d0-f4414d5f5abe', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(738, '642a8cc6-86ee-3906-99b8-b5212261e0c5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(739, 'a3162e3c-0bfa-1546-cb94-4481a6fd5037', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:36', NULL, NULL, 2),
(740, 'ab8eed4a-2029-0758-5f86-5a0a59da1be4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(741, 'd56aff65-3298-d2b7-f817-bab9a2cabedb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(742, '60878c5d-98dc-e548-b2f5-7c8d866b7a9a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(743, 'f4920f62-c92a-605d-eaef-341ae2677d82', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(744, 'd6c1b502-5450-a6e5-1f92-ca69741d4205', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(745, '237928c5-9962-56af-15ea-44446493c84a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(746, '84ddc101-272e-e743-a9e0-7b27ed62c181', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(747, 'cd1231f6-8513-4d15-2d28-50ed6768d93c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(748, '119e504a-c330-a603-d168-30f2af15635e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(749, '881eafc9-6a88-4b38-5e9f-415b75b501aa', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(750, 'a90feac5-5baf-c591-39c0-a68d49fd28c7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(751, '0e5a0da4-b078-e3ec-1c9a-6f6ef0185bc5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(752, '6401fd1c-b5aa-80b8-abb2-307fdf8ed978', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(753, '80a0a4e1-76a6-4b6a-0788-53c251c41783', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(754, '06c5956b-fb02-8bdb-2813-b20a01dce7e1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(755, 'a15ee45a-9db6-c09c-d691-610a5ce7aebd', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(756, '49e45108-0474-8c1f-cd9a-012c23d7719f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(757, '21860506-7ebd-f88b-edd5-8e82baf7a449', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:41', NULL, NULL, 2),
(758, 'e0f822f0-87aa-ea5d-429c-d32f18c75617', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(759, '5bc16d7c-7cd5-08d8-ed0b-4b4592c4b528', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(760, '2dac335e-4d4b-6035-00eb-4b6423f074b5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(761, '8cd09430-91b2-b703-3b4e-1d2ec3d306a4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(762, '987a0efd-8f4e-ea61-0be3-e214490db163', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(763, '5a489434-e353-3dd7-8c25-d74b1f55dfc7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2);
INSERT INTO `tbl_entity` (`EntityID`, `EntityGUID`, `EntityTypeID`, `CreatedByUserID`, `Rating`, `LikedCount`, `ViewCount`, `SharedCount`, `FlaggedCount`, `SavedCount`, `BlockedCount`, `EntryDate`, `ModifiedDate`, `MenuOrder`, `StatusID`) VALUES
(764, 'a2b7619f-545d-9429-7f5b-f269c8701dbe', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(765, '7222f038-53fd-532e-8342-488ded636a89', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(766, '10a21e92-2687-46b9-e7c8-2299db289b2e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(767, '88c320b9-6be6-f5ad-9e52-d93f7209df45', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(768, '4f9da189-7297-63f3-4eec-33e916015081', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(769, 'd2841c3e-b4f0-5abd-ca62-dc0a0bfdebcf', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(770, 'f261625d-38c6-9a32-0ff8-bf08f960f797', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(771, '3a55538a-9723-e213-966c-50b9a51904fa', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(772, '4deea924-9a2c-bc94-c638-e032ad952cd4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(773, '2ab10416-f754-b1e1-e3ea-4ca320458cc7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(774, '108c739a-1e17-f46f-969f-50b65bd32882', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:43', NULL, NULL, 2),
(775, 'c66fc397-25d2-b811-62a9-a63f0ec87818', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(776, 'eb6e2625-5625-fcad-47d9-62ca1cb203a5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(777, 'f22b0a5d-220f-fc47-a4fb-457efa85de4c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(778, '713f6023-f03f-b070-6cf4-eb078aa4bb3f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(779, 'c39f9dc7-50fd-900e-a559-174ba228d65c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(780, '7e245dae-11cf-72d8-7a50-5993001afd1e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(781, '032d1dfe-b040-42eb-8f98-87de4c807967', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:50', NULL, NULL, 2),
(782, 'de850d07-97e5-132d-64b8-c21508bcb03b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(783, '8ff67835-6ca8-cec9-08f3-68b8183f3b0e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(784, '8b851e4c-3f14-aeba-7717-edf5128b7da9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(785, '35936d1b-dfad-bdff-abe9-37f23bb3f8ed', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(786, '075d48d3-d8b1-131c-e663-bfeea602f16b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(787, '4c1d48a9-cfe4-726b-8df8-0fa4d7999f1f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(788, 'ee15cb36-4c59-2bc1-e193-2cc529f0ffa9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(789, '1ccc67ca-4014-f777-a1c0-d9ac3dd2b6ca', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(790, '51a0e44c-60d6-8441-4153-d6a730f414fc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(791, 'f37a592d-0e86-31ce-6c1c-4b6f30c75f79', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(792, '798307f6-25c8-42f2-aa21-23450d6bf508', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(793, 'f94ac86d-ae8b-67c7-2308-bbea66c089b4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:51', NULL, NULL, 2),
(794, 'bc5d1bda-496d-cf74-502b-99591d6dfbcc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(795, '26c7dc94-4033-641d-9b98-6a029fa484d8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(796, '92e5e57a-cd97-fa89-0ca9-68579360f4c1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(797, 'dee3a1f6-f2ba-6597-69cc-83251880c8f9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(798, '7d0b1bf7-2378-e996-33b8-1fb7d405dfbb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(799, '914b7c7d-1e0d-1e28-7b21-6e7151cc6203', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(800, '9a31d634-2740-c2fe-d849-db8ac4f07a44', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(801, '5fdb8f25-0240-c796-d9e0-071beac91a28', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(802, '85ea22c4-89f7-c4ac-18bf-74cef3316b90', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(803, '19c043aa-b21b-b9a3-0285-37e09722ac55', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(804, '7712aade-8b62-704e-2765-c952fc6d0268', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(805, '7bab078d-964e-c4c9-f57f-2f42af208e28', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(806, 'cab6f397-5f0a-c45f-80a1-809a71db27cc', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(807, 'fb56d9af-790d-95d0-864f-767c6b8fb954', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(808, '1e9c1fef-bb30-dd21-2a82-f80ac8e96956', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(809, '52d0d783-a6ca-224f-41e2-63c5c06d9527', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(810, 'd99b8823-24ed-9250-3941-a54a58697da9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(811, '5a7c98a2-ec4d-3078-c994-d1736bb9aae5', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(812, '617310b3-c6cd-ebf5-5989-bb0134a77bd2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(813, '62c46e02-a19a-22cf-7c50-e356607eb9e3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(814, '6b4d26a3-042f-4f68-5cef-4654a271e457', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:56', NULL, NULL, 2),
(815, '89e17168-5168-4577-8d9a-31f441cc714c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(816, '1309daae-b23c-de21-d20c-2e4a45e97125', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(817, '5fa15803-e93b-e767-39a7-82bce6874d3d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(818, '0a85017e-e16b-a27d-b311-13501d185068', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(819, '48bc342f-6729-00b7-5725-4d4bee48b6b1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(820, 'b2420f9b-fa82-351d-d2cb-c8eaf492ae07', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(821, '4eb03375-dc00-57d4-7701-af0bec44a658', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(822, '9f0f587d-3681-31b1-0bed-60dec570ec8e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(823, '88f065bd-5eab-79f8-a300-f9342d8b5c81', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(824, '5b57b92d-1452-b3d0-d050-73ad48702c67', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(825, '9f62d299-c6f2-98b4-c224-3519b8d13e45', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(826, '8e26e10b-dff1-7e6c-0aae-19ed0522e604', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(827, 'bb7d067f-bd99-b6b0-4831-649487ed5d82', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(828, '92160766-c2a2-d640-a5a8-f1db18aa8297', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(829, 'ec16a483-cfc9-bbf7-a451-b824587bb01b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(830, '04a20ad0-e051-bbc0-2d73-ba6aba5eddaa', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:34:59', NULL, NULL, 2),
(831, '5c0c765d-2bfa-f1db-361b-5983c282f38e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(832, '91e97233-fc2a-6c68-2d4b-e4795fc7bcf0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(833, '7ad9e549-a534-7911-854c-1b68fc88571a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(834, '459ce691-e8bb-6294-00f9-1bba3f431dec', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(835, 'bf54d67d-ca98-7564-1393-18dffa7934ec', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(836, 'e4350d99-a177-6fab-88af-34bc0326a4ad', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(837, '11518401-dbe4-8857-4fad-820b52bfcc9e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(838, '4d3574e1-8bdd-08ce-a952-e3ca1889e8ff', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(839, 'b7836e70-f4b0-a0e6-7c2c-2795642577c7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(840, '5fb4ea4e-7fb9-28e7-0b8a-660ecf589cd0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(841, '990c64a3-70d2-dd0b-3187-6189d5e9174c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(842, '264552ab-1382-b683-a9a5-a4a04fe47fef', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(843, 'd5f0ea5b-2f62-c06c-1944-2ecba053eabd', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(844, 'f131818e-a69d-7506-5f9a-05b8372a5b70', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(845, 'e02a07a3-4865-829a-46c0-a29e1eeab991', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(846, 'e933dc51-5a27-cead-dea4-2103917f8475', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(847, '72a42570-befd-4657-f68f-a8e5531375ba', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(848, '851b74ad-574a-1bfa-0093-82cfff2da92d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(849, '049e8f69-5e26-ef71-e8b7-62fb55d1109d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(850, 'a1ae4188-8ca5-8f24-32df-775cde07807d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(851, '1ed1d1cc-3da7-191b-cf40-41b8dbe698fb', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:05', NULL, NULL, 2),
(852, '687cdd88-eccd-b416-a60a-e94ca22dce20', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(853, '8b75753c-30b7-6758-31f9-bf1ad4d13ab6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(854, '472650a9-30d3-748e-8c02-23d850541342', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(855, '34a3377d-a10e-62b5-30c5-9d75c7675917', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(856, 'a893bfbd-798f-3e5c-b9c2-b17e7878ec0c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(857, 'ab0d2c82-9ec6-4338-896a-ac63ac5f3387', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(858, '37ffb2a0-d4f9-5564-e75b-7b18d3b4ca6c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(859, '8253a98d-9ba2-d6b4-ee46-49976758f1b3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(860, 'f982691a-0d85-c761-72ae-22813a5b6ab8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(861, '0dd568c3-42fd-738d-55a1-e21c60889ec6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(862, 'fc1322cb-3ab5-6341-3eb7-4f4584e02b73', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(863, 'c5a3f026-3c7e-8e80-3552-84e5bd6574f1', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(864, '639e53c6-b972-319d-508e-9cf08d34f615', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(865, '5bb2f06d-44b5-4662-8a01-7b48bf44e841', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(866, '681becd7-095c-bff4-c036-2f728a512561', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(867, '8b2a399c-7ffa-7937-3c15-1bac0d8611f3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(868, '3794ba24-a831-31fd-1e83-32ee353f9464', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(869, '296566e6-9f07-790c-2703-91c40892c868', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(870, '786aba31-ef1d-0acd-9cf5-58968df4f83b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(871, 'dc695f26-ac54-05cb-ea49-e7b5a670d6a4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(872, '22e4eb91-b3cf-849c-9e49-3978082bb176', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(873, '66bfaf68-8602-4bd6-18ee-0b9d9d157624', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(874, 'f37cd10a-1553-0829-1e5a-ac21f2e5ecef', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(875, 'a7e915c2-340b-f143-d162-7b50b43a7fca', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(876, 'de765df5-4fe4-3b23-fc5f-1a32d4368b47', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(877, 'f2c75f00-274d-0319-0ab3-6fd3903ebbc0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(878, 'abb35462-e745-64ea-4d79-1296a3877712', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(879, 'd5aec555-cbaa-5e29-ebf5-400ab93d34d2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:08', NULL, NULL, 2),
(880, '125e4dea-fb12-8cb7-be68-07f5d958fb80', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:12', NULL, NULL, 2),
(881, '6188482d-45b7-69c8-3832-0071a0d62cf9', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(882, '544cca6e-cfa7-da10-d8d7-9d7454c1c244', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(883, 'e6f4e8f3-5df5-4c56-e4d5-b0d913eec959', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(884, 'b700ef19-44fd-f4f2-c5c1-127055bba677', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(885, '45f06bba-38ff-6ffe-7634-47dc5cbc58c4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(886, '21a24ba8-aa51-f5f7-0fa1-07fbb1b5430a', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(887, '92fbe39d-0c57-78d0-714b-58df91f32e73', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(888, 'fd6be19f-f02a-1125-7141-c3c99bc28e8b', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(889, '864bbad2-7151-c6ee-9a50-013b7176dc0c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(890, 'e09693ee-f655-d589-8ddc-a1c9b14efc8d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(891, '50eb597c-fe6d-9d72-d48e-675cb36988b8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(892, '395829b1-f3aa-061a-89d7-652a6b34dbc8', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(893, 'aaf62aa1-f74e-5258-565c-d8179afebd3e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(894, '444263ce-4194-ad3b-7f93-96424f33062f', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(895, 'a8f6be53-fe69-0b0f-fb91-9682b5b1ad06', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(896, '323dc284-800a-9fa6-7430-46bc040f3adf', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(897, '369ab17a-2be2-e91d-6a64-a626fe146c56', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(898, 'b1614633-63ac-d127-b691-63477952d903', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(899, '1770d5b5-f5e1-44ee-5b07-2c8b27f1e152', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(900, '1e77c467-0887-6777-9b1b-c5e1bee74e68', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(901, '6491a773-2761-c9c9-0c49-f64c29635cd7', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(902, 'c08d80b7-634a-af49-58ae-c928937c7312', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:13', NULL, NULL, 2),
(903, 'd3c64d9a-03f9-5912-8aa3-6726dc56ba8e', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(904, 'ac9caa14-1700-cc04-0a63-0fed64da2b0d', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(905, '0493751c-a41c-7e23-97f9-689e5ae8fb6c', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(906, 'fa84e96c-d65f-1736-d9dd-c583567df395', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(907, 'd837f34b-671b-bc95-f5f3-bc8b01577482', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(908, 'b50a5c36-cca9-b288-011b-f73c9f7e17d6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(909, '708c84bc-b6a0-d252-dcdd-90f83b757840', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(910, 'e22b6cb8-ded3-5902-3631-39df0ad7a234', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(911, 'ada606f9-9973-21d2-a94b-637ebb86adc0', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(912, '2e4d340f-8eb4-0010-dbf6-21dc22c5a7a6', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(913, '9159b3e1-c352-6665-61bc-20c13833c7c4', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(914, '446a110f-c9af-ddeb-867b-98433ca91ef2', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(915, 'e4b1b6af-f565-9cd6-54fc-dfee7c029fb3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(916, 'e1f5ec21-8243-ef4b-f5c7-356f6b5da428', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(917, '2e1cdec4-f8d7-969d-68cc-3a5733bf15c3', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(918, '0ab56a17-604a-f436-489b-60d20da56d84', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(919, '97a8a414-d4f2-94e0-2998-07d2d18f1a05', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(920, '4d3d39b2-4b8b-8f98-01c2-ec7ecfebcd71', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(921, 'eeaf1b31-b7ae-a2e3-ebb0-5292a930a271', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(922, '1b07eb89-b0a7-fc59-d913-fff020f451fe', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(923, '3f74d8fb-2e3c-8779-9831-721c19291304', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(924, 'b563a29a-a658-da6c-9cec-6924a0058b27', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(925, 'e158b1d5-9a1b-8320-3704-d98cf3521e88', 10, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 10:35:15', NULL, NULL, 2),
(926, '6472acea-eb2b-87ca-8dd2-1b65e706d489', 11, 125, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 12:04:58', NULL, NULL, 1),
(927, '6371a8f8-9852-8447-cfef-1f6e66b2c540', 1, NULL, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 12:34:56', NULL, NULL, 2),
(928, '7930bd6f-b573-9871-0e6d-615f3e1aef49', 12, 927, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 12:41:03', NULL, NULL, 2),
(929, 'dae1ec28-3046-1d2b-6d0c-217e086e0b59', 11, 125, '0', 0, 0, 0, 0, 0, 0, '2019-08-05 12:43:23', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entity_categories`
--

CREATE TABLE `tbl_entity_categories` (
  `EntityID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entity_type`
--

CREATE TABLE `tbl_entity_type` (
  `EntityTypeID` int(11) NOT NULL,
  `EntityTypeName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_entity_type`
--

INSERT INTO `tbl_entity_type` (`EntityTypeID`, `EntityTypeName`) VALUES
(1, 'User'),
(3, 'Category'),
(4, 'Group'),
(5, 'Event'),
(6, 'Post'),
(7, 'Series'),
(8, 'Matches'),
(9, 'Teams'),
(10, 'Players'),
(11, 'Contest'),
(12, 'User Teams'),
(13, 'Coupon'),
(14, 'Banner');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entity_views`
--

CREATE TABLE `tbl_entity_views` (
  `UserID` int(11) NOT NULL,
  `EntityID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_media`
--

CREATE TABLE `tbl_media` (
  `MediaID` int(11) NOT NULL,
  `MediaGUID` char(36) NOT NULL,
  `IsImage` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL COMMENT 'Uploaded by User',
  `SectionID` char(20) NOT NULL,
  `EntityID` int(11) DEFAULT NULL,
  `MediaRealName` varchar(255) DEFAULT NULL,
  `MediaName` varchar(100) DEFAULT NULL,
  `MediaSize` float DEFAULT NULL,
  `MediaExt` varchar(15) DEFAULT NULL,
  `MediaCaption` text,
  `EntryDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_media`
--

INSERT INTO `tbl_media` (`MediaID`, `MediaGUID`, `IsImage`, `UserID`, `SectionID`, `EntityID`, `MediaRealName`, `MediaName`, `MediaSize`, `MediaExt`, `MediaCaption`, `EntryDate`) VALUES
(1, '38ead012-f94c-5682-b141-6519b80919f3', 1, 125, 'TeamFlag', NULL, 'bigScre.jpg', 'bigscre_1565003168.jpg', 54.14, '.jpg', NULL, '2019-08-05 11:06:08'),
(2, 'ad9bf59f-e310-71ba-8c45-e54e784c38e6', 1, 125, 'TeamFlag', NULL, 'bgNew.png', 'bgnew_1565003299.png', 528.87, '.png', NULL, '2019-08-05 11:08:19'),
(3, '123b9c15-c501-1d30-eac3-4a1cda10b885', 1, 125, 'PlayerPic', NULL, 'bg_1.jpg', 'bg_1_1565003437.jpg', 35.54, '.jpg', NULL, '2019-08-05 11:10:37'),
(4, 'f937e3f4-c753-acad-850a-fbc71fa00f96', 1, 125, 'PlayerPic', NULL, 'app.png', 'app_1565003441.png', 63.82, '.png', NULL, '2019-08-05 11:10:41'),
(5, '7b5e86d3-b887-ae62-a3a9-0695d16b7739', 1, 125, 'PlayerPic', NULL, 'bg1.jpg', 'bg1_1565003470.jpg', 73.98, '.jpg', NULL, '2019-08-05 11:11:10'),
(6, 'd637a932-0dad-b01c-5c93-ca335d5dd711', 1, 125, 'PlayerPic', NULL, 'bg.png', 'bg_1565003498.png', 82.96, '.png', NULL, '2019-08-05 11:11:38'),
(7, '15ca199b-8b23-f767-b023-72697ae2a626', 1, 125, 'PlayerPic', NULL, 'bg1.jpg', 'bg1_1565003545.jpg', 73.98, '.jpg', NULL, '2019-08-05 11:12:25'),
(8, '59e04a5a-b282-7ef4-5e99-763ac50982a2', 1, 125, 'PlayerPic', NULL, 'bg.png', 'bg_1565003596.png', 82.96, '.png', NULL, '2019-08-05 11:13:16'),
(9, '74f80c5a-aaa5-2109-0a9c-171c2498b19b', 1, 125, 'PlayerPic', NULL, 'bg1.jpg', 'bg1_1565003758.jpg', 73.98, '.jpg', NULL, '2019-08-05 11:15:58'),
(10, '79707467-587e-c33f-ca22-d0e45cd12bff', 1, 125, 'PlayerPic', NULL, 'bg_1.jpg', 'bg_1_1565003835.jpg', 35.54, '.jpg', NULL, '2019-08-05 11:17:15'),
(11, '7a8973e6-73f6-fc85-6932-50fb244d515a', 1, 125, 'PlayerPic', NULL, 'bg1.jpg', 'bg1_1565004478.jpg', 73.98, '.jpg', NULL, '2019-08-05 11:27:58'),
(12, '0925bc57-8161-dcdd-bd84-ed151a195e4b', 1, 125, 'PlayerPic', NULL, 'bg_21.png', 'bg_21_1565004506.png', 87.86, '.png', NULL, '2019-08-05 11:28:26'),
(13, '17c9e097-cac8-c3c9-7e0d-a0762b8dede2', 1, 125, 'PlayerPic', NULL, 'bg.jpg', 'bg_1565004593.jpg', 25.51, '.jpg', NULL, '2019-08-05 11:29:53');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_media_sections`
--

CREATE TABLE `tbl_media_sections` (
  `SectionID` char(20) NOT NULL,
  `SectionFolderPath` varchar(255) NOT NULL,
  `SectionThumbSize` varchar(25) DEFAULT NULL,
  `SectionMaintainRatio` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_media_sections`
--

INSERT INTO `tbl_media_sections` (`SectionID`, `SectionFolderPath`, `SectionThumbSize`, `SectionMaintainRatio`) VALUES
('BankDetail', 'uploads/BankDetail/', '1100', 'No'),
('Banner', 'uploads/Banner/', '323230', 'No'),
('Category', 'uploads/category/', '220', 'No'),
('Coupon', 'uploads/Coupon/', '220', 'No'),
('File', 'uploads/file/', NULL, NULL),
('PAN', 'uploads/PAN/', '1100', 'No'),
('PlayerPic', 'uploads/PlayerPic/', '220', 'Yes'),
('Post', 'uploads/Post/', '220', 'No'),
('ProfileCoverPic', 'uploads/profile/cover/', '1100', 'Yes'),
('ProfilePic', 'uploads/profile/picture/', '220', 'Yes'),
('TeamFlag', 'uploads/TeamFlag/', '220', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `NotificationID` int(11) NOT NULL,
  `NotificationPatternID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ToUserID` int(11) NOT NULL,
  `RefrenceID` varchar(36) DEFAULT NULL,
  `NotificationText` varchar(255) DEFAULT NULL,
  `NotificationMessage` text,
  `EntryDate` datetime NOT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`NotificationID`, `NotificationPatternID`, `UserID`, `ToUserID`, `RefrenceID`, `NotificationText`, `NotificationMessage`, `EntryDate`, `ModifiedDate`, `StatusID`) VALUES
(1, 7, 927, 927, NULL, 'Signup Bonus', 'Rs.50has been credited in your Wallet', '2019-08-05 12:34:56', NULL, 1),
(2, 1, 927, 927, NULL, 'Welcome to Fantasy Master!', 'Hi vishakha, Verify your Email and PAN Details and Earn more Cash Bonus.', '2019-08-05 12:35:02', NULL, 1),
(3, 1, 927, 125, NULL, 'vishakha, got Registered', NULL, '2019-08-05 12:35:02', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_pattern`
--

CREATE TABLE `tbl_notification_pattern` (
  `NotificationPatternID` int(11) NOT NULL,
  `NotificationPatternGUID` varchar(36) NOT NULL,
  `NotificationSampleText` varchar(255) DEFAULT NULL,
  `SendPushMessage` enum('Yes','No') NOT NULL DEFAULT 'No',
  `StatusID` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_notification_pattern`
--

INSERT INTO `tbl_notification_pattern` (`NotificationPatternID`, `NotificationPatternGUID`, `NotificationSampleText`, `SendPushMessage`, `StatusID`) VALUES
(1, 'welcome', 'Hi and welcome to SITE_NAME', 'No', 2),
(2, 'broadcast', 'broadcast message', 'Yes', 2),
(3, 'playingxi', 'PlayingXI message', 'No', 2),
(4, 'AddCash', 'Add Deposit message', 'Yes', 2),
(5, 'ReferralBonus', 'Referral Bonus message', 'Yes', 2),
(6, 'Withdrawal', 'Withdrawal message', 'Yes', 2),
(7, 'bonus', 'Signup bonus message', 'Yes', 2),
(8, 'refund', 'Contest Cancel Refund message', 'Yes', 2),
(9, 'verify', 'Verify Message', 'Yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_referral_codes`
--

CREATE TABLE `tbl_referral_codes` (
  `ReferralCodeID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ReferralCode` varchar(6) NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_referral_codes`
--

INSERT INTO `tbl_referral_codes` (`ReferralCodeID`, `UserID`, `ReferralCode`, `StatusID`) VALUES
(1, 927, 't9g4s1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tokens`
--

CREATE TABLE `tbl_tokens` (
  `Type` enum('1','2','3') DEFAULT NULL COMMENT '1=Forgot Password, 2=Email Verification, 3=Phone No. Verification',
  `Token` varchar(100) NOT NULL,
  `UserID` int(11) NOT NULL,
  `EntryDate` datetime NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_tokens`
--

INSERT INTO `tbl_tokens` (`Type`, `Token`, `UserID`, `EntryDate`, `StatusID`) VALUES
('2', '917854', 927, '2019-08-05 12:34:56', 1),
('3', '879325', 927, '2019-08-05 12:35:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `UserID` int(11) NOT NULL,
  `UserGUID` char(36) NOT NULL,
  `UserTypeID` int(11) NOT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `About` mediumtext,
  `About1` mediumtext,
  `About2` mediumtext,
  `ProfilePic` varchar(50) DEFAULT NULL,
  `ProfileCoverPic` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `EmailForChange` varchar(50) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL COMMENT 'Profile Slug (Team Code)',
  `IsUsernameUpdateded` enum('No','Yes') NOT NULL DEFAULT 'No',
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `BirthDate` date DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Height` varchar(100) DEFAULT NULL,
  `Weight` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Postal` varchar(12) DEFAULT NULL,
  `CountryCode` char(2) DEFAULT NULL,
  `CityName` varchar(255) DEFAULT NULL,
  `StateName` varchar(255) DEFAULT NULL,
  `Latitude` float DEFAULT NULL,
  `Longitude` float DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `PhoneNumberForChange` varchar(15) DEFAULT NULL,
  `Website` varchar(1000) DEFAULT NULL,
  `FacebookURL` varchar(255) DEFAULT NULL COMMENT 'FB Profile URL',
  `TwitterURL` varchar(255) DEFAULT NULL COMMENT 'Twitter Profile URL',
  `GoogleURL` varchar(255) DEFAULT NULL,
  `InstagramURL` varchar(255) DEFAULT NULL,
  `LinkedInURL` varchar(255) DEFAULT NULL,
  `WhatsApp` varchar(255) DEFAULT NULL,
  `ReferralCodeID` int(11) DEFAULT NULL,
  `ReferredByUserID` int(11) DEFAULT NULL,
  `WalletAmount` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '(Deposit Amount)',
  `WinningAmount` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '(Contest Winning Amount)',
  `CashBonus` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '(Referral Bonus)',
  `WithdrawalHoldAmount` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '(Total Of Withdrawal Pending Request)',
  `PanStatus` int(2) DEFAULT '9',
  `BankStatus` int(2) NOT NULL DEFAULT '9',
  `IsPrivacyNameDisplay` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`UserID`, `UserGUID`, `UserTypeID`, `FirstName`, `MiddleName`, `LastName`, `About`, `About1`, `About2`, `ProfilePic`, `ProfileCoverPic`, `Email`, `EmailForChange`, `Username`, `IsUsernameUpdateded`, `Gender`, `BirthDate`, `Age`, `Height`, `Weight`, `Address`, `Address1`, `Postal`, `CountryCode`, `CityName`, `StateName`, `Latitude`, `Longitude`, `PhoneNumber`, `PhoneNumberForChange`, `Website`, `FacebookURL`, `TwitterURL`, `GoogleURL`, `InstagramURL`, `LinkedInURL`, `WhatsApp`, `ReferralCodeID`, `ReferredByUserID`, `WalletAmount`, `WinningAmount`, `CashBonus`, `WithdrawalHoldAmount`, `PanStatus`, `BankStatus`, `IsPrivacyNameDisplay`) VALUES
(125, 'abcd', 1, 'Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@mailinator.com', 'alexm@mailinator.com', NULL, 'No', 'Male', '1984-05-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, 76, '9898989899', '9981823755', NULL, NULL, 'native', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 9, 9, 'No'),
(927, '6371a8f8-9852-8447-cfef-1f6e66b2c540', 2, 'Vishakha', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vishakha.mobiwebtech@gmail.com', NULL, 'pjjozk', 'No', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9669422162', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4950.50, 0.00, 44.50, 0.00, 9, 9, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_login`
--

CREATE TABLE `tbl_users_login` (
  `UserID` int(11) NOT NULL,
  `Password` char(32) DEFAULT NULL,
  `SourceID` int(11) DEFAULT NULL,
  `EntryDate` datetime NOT NULL,
  `LastLoginDate` datetime DEFAULT NULL,
  `ModifiedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_login`
--

INSERT INTO `tbl_users_login` (`UserID`, `Password`, `SourceID`, `EntryDate`, `LastLoginDate`, `ModifiedDate`) VALUES
(125, 'e10adc3949ba59abbe56e057f20f883e', 1, '2018-01-03 06:31:01', '2019-08-06 12:39:46', '2019-06-07 10:42:15'),
(927, 'e10adc3949ba59abbe56e057f20f883e', 1, '2019-08-05 12:34:56', '2019-08-05 12:39:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_session`
--

CREATE TABLE `tbl_users_session` (
  `UserID` int(11) NOT NULL,
  `SessionKey` char(36) NOT NULL,
  `IPAddress` varchar(15) DEFAULT NULL,
  `SourceID` int(11) DEFAULT NULL,
  `DeviceTypeID` int(11) DEFAULT NULL,
  `DeviceGUID` varchar(255) DEFAULT NULL,
  `DeviceToken` varchar(255) DEFAULT NULL,
  `EntryDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tbl_users_session`
--

INSERT INTO `tbl_users_session` (`UserID`, `SessionKey`, `IPAddress`, `SourceID`, `DeviceTypeID`, `DeviceGUID`, `DeviceToken`, `EntryDate`) VALUES
(125, '36bdb342-8f80-9a2e-5574-3a5a9558cba2', NULL, 1, 1, NULL, NULL, '2019-08-05 07:28:06'),
(125, 'ac6cf2fb-9112-88b3-f149-c7a8d091d724', NULL, 1, 1, NULL, NULL, '2019-08-05 10:30:31'),
(125, '69033033-3464-6391-018a-2adbf0b4b0fd', NULL, 1, 1, NULL, NULL, '2019-08-05 10:38:49'),
(125, '31e85934-1383-678c-6bfe-ffd6b12ad85b', NULL, 1, 1, NULL, NULL, '2019-08-05 10:57:24'),
(927, 'ddce9067-b872-3c7d-12e6-a49159590555', NULL, 1, 1, NULL, NULL, '2019-08-05 12:35:02'),
(927, '5826aaed-d80f-24e6-ec31-3b32d1fa94fe', NULL, 1, 1, NULL, NULL, '2019-08-05 12:39:28'),
(125, '649e75a8-f101-90e4-f9a8-3fb08eddf7a6', NULL, 1, 1, NULL, NULL, '2019-08-05 12:42:17'),
(125, '0785840d-7f86-2646-9711-89851a916c8c', NULL, 1, 1, NULL, NULL, '2019-08-05 14:15:26'),
(125, '22725d86-bdeb-826d-829d-309bcbf1558f', NULL, 1, 1, NULL, NULL, '2019-08-06 06:02:44'),
(125, '5995581a-70e9-6374-6912-0b3a3de887b3', NULL, 1, 1, NULL, NULL, '2019-08-06 12:35:16'),
(125, '06d8717b-c9e5-e0c7-8cd7-9d51e7401351', NULL, 1, 1, NULL, NULL, '2019-08-06 12:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_settings`
--

CREATE TABLE `tbl_users_settings` (
  `UserID` int(11) DEFAULT NULL,
  `PushNotification` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `PrivacyPhone` enum('Public','Private') NOT NULL DEFAULT 'Public',
  `PrivacyLocation` enum('Public','Private') NOT NULL DEFAULT 'Public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_settings`
--

INSERT INTO `tbl_users_settings` (`UserID`, `PushNotification`, `PrivacyPhone`, `PrivacyLocation`) VALUES
(927, 'Yes', 'Public', 'Public');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_type`
--

CREATE TABLE `tbl_users_type` (
  `UserTypeID` int(11) NOT NULL,
  `UserTypeName` varchar(100) NOT NULL,
  `IsAdmin` enum('Yes','No') NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_type`
--

INSERT INTO `tbl_users_type` (`UserTypeID`, `UserTypeName`, `IsAdmin`) VALUES
(1, 'Administrator', 'Yes'),
(2, 'User', 'No'),
(3, 'User2', 'No'),
(4, 'Staff', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_wallet`
--

CREATE TABLE `tbl_users_wallet` (
  `WalletID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Amount` float(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Total Amount (WalletAmount+WinningAmount+CashBonus)',
  `OpeningWalletAmount` float(8,2) NOT NULL DEFAULT '0.00',
  `OpeningWinningAmount` float(8,2) DEFAULT '0.00',
  `OpeningCashBonus` float(8,2) NOT NULL DEFAULT '0.00',
  `WalletAmount` float(8,2) NOT NULL DEFAULT '0.00',
  `WinningAmount` float(8,2) NOT NULL DEFAULT '0.00',
  `CashBonus` float(8,2) NOT NULL DEFAULT '0.00',
  `ClosingWalletAmount` float(8,2) NOT NULL DEFAULT '0.00',
  `ClosingWinningAmount` float(8,2) DEFAULT '0.00',
  `ClosingCashBonus` float(8,2) NOT NULL DEFAULT '0.00',
  `Currency` enum('INR','USD') NOT NULL DEFAULT 'INR',
  `PaymentGateway` enum('PayUmoney','Paytm','Razorpay','CashFree') DEFAULT NULL,
  `TransactionType` enum('Cr','Dr') NOT NULL,
  `TransactionID` varchar(255) DEFAULT NULL,
  `Narration` enum('Deposit Money','Join Contest','Cancel Contest','Signup Bonus','Admin Cash Bonus','Join Contest Winning','First Deposit Bonus','Verification Bonus','Referral Bonus','Coupon Discount','Withdrawal Request','Withdrawal Reject','Wrong Winning Distribution','Admin Deposit Money','Cash Bonus Expire') NOT NULL,
  `EntityID` int(11) DEFAULT NULL,
  `UserTeamID` int(11) DEFAULT NULL,
  `PaymentGatewayResponse` text,
  `CouponDetails` text COMMENT '(Applied Coupon Details)',
  `CouponCode` varchar(20) DEFAULT NULL,
  `EntryDate` datetime NOT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `StatusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users_wallet`
--

INSERT INTO `tbl_users_wallet` (`WalletID`, `UserID`, `Amount`, `OpeningWalletAmount`, `OpeningWinningAmount`, `OpeningCashBonus`, `WalletAmount`, `WinningAmount`, `CashBonus`, `ClosingWalletAmount`, `ClosingWinningAmount`, `ClosingCashBonus`, `Currency`, `PaymentGateway`, `TransactionType`, `TransactionID`, `Narration`, `EntityID`, `UserTeamID`, `PaymentGatewayResponse`, `CouponDetails`, `CouponCode`, `EntryDate`, `ModifiedDate`, `StatusID`) VALUES
(1, 927, 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 50.00, 0.00, 0.00, 50.00, 'INR', NULL, 'Cr', 'edceeaee722751fee744', 'Signup Bonus', NULL, NULL, NULL, NULL, NULL, '2019-08-05 12:34:56', NULL, 5),
(2, 927, 5000.00, 0.00, 0.00, 50.00, 5000.00, 0.00, 0.00, 5000.00, 0.00, 50.00, 'INR', NULL, 'Cr', '8ab533473f26f8de55d8', 'Admin Deposit Money', NULL, NULL, NULL, NULL, NULL, '2019-08-05 12:44:23', NULL, 5),
(3, 927, 55.00, 5000.00, 0.00, 50.00, 49.50, 0.00, 5.50, 4950.50, 0.00, 44.50, 'INR', NULL, 'Dr', '49e198e01892b224b917', 'Join Contest', 929, 928, NULL, NULL, NULL, '2019-08-05 12:44:37', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_withdrawal`
--

CREATE TABLE `tbl_users_withdrawal` (
  `WithdrawalID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PaytmPhoneNumber` varchar(100) DEFAULT NULL,
  `OTP` varchar(10) DEFAULT NULL,
  `IsOTPVerified` enum('No','Yes') DEFAULT NULL,
  `Amount` float(8,2) NOT NULL,
  `PaymentGateway` enum('Paytm','Bank') NOT NULL,
  `PaymentGatewayResponse` text,
  `EntryDate` datetime NOT NULL,
  `ModifiedDate` datetime DEFAULT NULL,
  `Comments` varchar(250) DEFAULT NULL,
  `StatusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_control`
--
ALTER TABLE `admin_control`
  ADD PRIMARY KEY (`ControlID`),
  ADD KEY `moduleID` (`ModuleID`),
  ADD KEY `parentControlID` (`ParentControlID`);

--
-- Indexes for table `admin_modules`
--
ALTER TABLE `admin_modules`
  ADD PRIMARY KEY (`ModuleID`);

--
-- Indexes for table `admin_user_type_permission`
--
ALTER TABLE `admin_user_type_permission`
  ADD UNIQUE KEY `groupID_2` (`UserTypeID`,`ModuleID`),
  ADD KEY `groupID` (`UserTypeID`),
  ADD KEY `moduleID` (`ModuleID`);

--
-- Indexes for table `dummy_names`
--
ALTER TABLE `dummy_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ecom_coupon`
--
ALTER TABLE `ecom_coupon`
  ADD PRIMARY KEY (`CouponID`);

--
-- Indexes for table `log_api`
--
ALTER TABLE `log_api`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `log_cron`
--
ALTER TABLE `log_cron`
  ADD PRIMARY KEY (`CronID`);

--
-- Indexes for table `log_pushdata`
--
ALTER TABLE `log_pushdata`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `set_categories`
--
ALTER TABLE `set_categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `CategoryTypeID` (`CategoryTypeID`);

--
-- Indexes for table `set_categories_type`
--
ALTER TABLE `set_categories_type`
  ADD PRIMARY KEY (`CategoryTypeID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `set_device_type`
--
ALTER TABLE `set_device_type`
  ADD PRIMARY KEY (`DeviceTypeID`);

--
-- Indexes for table `set_pages`
--
ALTER TABLE `set_pages`
  ADD PRIMARY KEY (`PageID`);

--
-- Indexes for table `set_site_config`
--
ALTER TABLE `set_site_config`
  ADD UNIQUE KEY `ConfigurationTypeGUID` (`ConfigTypeGUID`),
  ADD KEY `ConfigTypeStatus` (`StatusID`);

--
-- Indexes for table `set_source`
--
ALTER TABLE `set_source`
  ADD PRIMARY KEY (`SourceID`);

--
-- Indexes for table `set_status`
--
ALTER TABLE `set_status`
  ADD PRIMARY KEY (`StatusID`);

--
-- Indexes for table `social_post`
--
ALTER TABLE `social_post`
  ADD UNIQUE KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`EntityID`),
  ADD KEY `EntityID` (`ToEntityID`);
ALTER TABLE `social_post` ADD FULLTEXT KEY `PostContent` (`PostContent`);
ALTER TABLE `social_post` ADD FULLTEXT KEY `Caption` (`PostCaption`);

--
-- Indexes for table `social_subscribers`
--
ALTER TABLE `social_subscribers`
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `EntityID` (`ToEntityID`),
  ADD KEY `social_subscribers_ibfk_5` (`UserID`);

--
-- Indexes for table `sports_auction_draft_player_point`
--
ALTER TABLE `sports_auction_draft_player_point`
  ADD KEY `sports_auction_draft_player_point_ibfk1` (`SeriesID`),
  ADD KEY `sports_auction_draft_player_point_ibfk2` (`ContestID`),
  ADD KEY `sports_auction_draft_player_point_ibfk3` (`PlayerID`);

--
-- Indexes for table `sports_contest`
--
ALTER TABLE `sports_contest`
  ADD PRIMARY KEY (`ContestID`),
  ADD UNIQUE KEY `UserInvitationCode` (`UserInvitationCode`),
  ADD KEY `MatchID` (`MatchID`),
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `ContestGUID` (`ContestGUID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ContestType` (`ContestType`),
  ADD KEY `Privacy` (`Privacy`),
  ADD KEY `IsPaid` (`IsPaid`),
  ADD KEY `sports_contest_ibfk_8` (`AuctionStatusID`);

--
-- Indexes for table `sports_contest_join`
--
ALTER TABLE `sports_contest_join`
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ContestID` (`ContestID`),
  ADD KEY `UserTeamID` (`UserTeamID`),
  ADD KEY `MatchID` (`MatchID`),
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `ContestID_2` (`ContestID`,`UserTeamID`);

--
-- Indexes for table `sports_matches`
--
ALTER TABLE `sports_matches`
  ADD PRIMARY KEY (`MatchID`),
  ADD KEY `MatchIDLive` (`MatchIDLive`),
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `TeamIDLocal` (`TeamIDLocal`),
  ADD KEY `TeamIDVisitor` (`TeamIDVisitor`),
  ADD KEY `MatchTypeID` (`MatchTypeID`),
  ADD KEY `MatchGUID` (`MatchGUID`),
  ADD KEY `MatchStartDateTime` (`MatchStartDateTime`);

--
-- Indexes for table `sports_players`
--
ALTER TABLE `sports_players`
  ADD PRIMARY KEY (`PlayerID`),
  ADD UNIQUE KEY `PlayerIDLive` (`PlayerIDLive`) USING BTREE,
  ADD KEY `PlayerGUID` (`PlayerGUID`);

--
-- Indexes for table `sports_predraft_contest`
--
ALTER TABLE `sports_predraft_contest`
  ADD PRIMARY KEY (`PredraftContestID`);

--
-- Indexes for table `sports_series`
--
ALTER TABLE `sports_series`
  ADD PRIMARY KEY (`SeriesID`),
  ADD UNIQUE KEY `SeriesIDLive_2` (`SeriesIDLive`),
  ADD KEY `SeriesIDLive` (`SeriesIDLive`),
  ADD KEY `SeriesGUID` (`SeriesGUID`),
  ADD KEY `sports_series_ibfk_2` (`AuctionDraftStatusID`);

--
-- Indexes for table `sports_setting_points`
--
ALTER TABLE `sports_setting_points`
  ADD UNIQUE KEY `PointsTypeGUID` (`PointsTypeGUID`),
  ADD KEY `PointsT20` (`PointsT20`),
  ADD KEY `PointsODI` (`PointsODI`),
  ADD KEY `PointsTEST` (`PointsTEST`),
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `PointsInningType` (`PointsInningType`);

--
-- Indexes for table `sports_set_match_types`
--
ALTER TABLE `sports_set_match_types`
  ADD PRIMARY KEY (`MatchTypeID`);

--
-- Indexes for table `sports_teams`
--
ALTER TABLE `sports_teams`
  ADD PRIMARY KEY (`TeamID`),
  ADD UNIQUE KEY `TeamIDLive` (`TeamIDLive`);

--
-- Indexes for table `sports_team_players`
--
ALTER TABLE `sports_team_players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_index` (`PlayerID`,`SeriesID`,`MatchID`,`TeamID`),
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `TeamID` (`TeamID`),
  ADD KEY `PlayerID` (`PlayerID`),
  ADD KEY `MatchID` (`MatchID`),
  ADD KEY `IsPlaying` (`IsPlaying`),
  ADD KEY `PlayerRole` (`PlayerRole`);

--
-- Indexes for table `sports_users_teams`
--
ALTER TABLE `sports_users_teams`
  ADD PRIMARY KEY (`UserTeamID`),
  ADD KEY `MatchID` (`MatchID`),
  ADD KEY `UserTeamGUID` (`UserTeamGUID`),
  ADD KEY `UserTeamID` (`UserTeamID`,`MatchID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `ContestID` (`ContestID`);

--
-- Indexes for table `sports_users_team_players`
--
ALTER TABLE `sports_users_team_players`
  ADD UNIQUE KEY `UserTeamID_2` (`UserTeamID`,`MatchID`,`PlayerID`),
  ADD KEY `UserTeamID` (`UserTeamID`),
  ADD KEY `PlayerID` (`PlayerID`),
  ADD KEY `MatchID` (`MatchID`),
  ADD KEY `PlayerPosition` (`PlayerPosition`),
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `UserTeamID_3` (`UserTeamID`,`PlayerID`);

--
-- Indexes for table `tbl_action`
--
ALTER TABLE `tbl_action`
  ADD KEY `EntityID` (`EntityID`),
  ADD KEY `ToEntityID` (`ToEntityID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `tbl_auction_player_bid`
--
ALTER TABLE `tbl_auction_player_bid`
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `ContestID` (`ContestID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PlayerID` (`PlayerID`);

--
-- Indexes for table `tbl_auction_player_bid_status`
--
ALTER TABLE `tbl_auction_player_bid_status`
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `ContestID` (`ContestID`),
  ADD KEY `PlayerID` (`PlayerID`);

--
-- Indexes for table `tbl_entity`
--
ALTER TABLE `tbl_entity`
  ADD PRIMARY KEY (`EntityID`),
  ADD KEY `ModuleID` (`EntityTypeID`),
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `CreatedByUserID` (`CreatedByUserID`),
  ADD KEY `EntityGUID` (`EntityGUID`);

--
-- Indexes for table `tbl_entity_categories`
--
ALTER TABLE `tbl_entity_categories`
  ADD KEY `ProductID` (`EntityID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `tbl_entity_type`
--
ALTER TABLE `tbl_entity_type`
  ADD PRIMARY KEY (`EntityTypeID`);

--
-- Indexes for table `tbl_entity_views`
--
ALTER TABLE `tbl_entity_views`
  ADD UNIQUE KEY `UserID` (`UserID`,`EntityID`),
  ADD KEY `EntityID` (`EntityID`);

--
-- Indexes for table `tbl_media`
--
ALTER TABLE `tbl_media`
  ADD PRIMARY KEY (`MediaID`),
  ADD KEY `MediaID` (`MediaID`),
  ADD KEY `SectionID` (`SectionID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tbl_media_sections`
--
ALTER TABLE `tbl_media_sections`
  ADD PRIMARY KEY (`SectionID`),
  ADD KEY `SectionName` (`SectionID`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`NotificationID`),
  ADD KEY `NotificationTypeID` (`NotificationPatternID`),
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ToUserID` (`ToUserID`);

--
-- Indexes for table `tbl_notification_pattern`
--
ALTER TABLE `tbl_notification_pattern`
  ADD PRIMARY KEY (`NotificationPatternID`),
  ADD UNIQUE KEY `NotificationTypeGUID` (`NotificationPatternGUID`);

--
-- Indexes for table `tbl_referral_codes`
--
ALTER TABLE `tbl_referral_codes`
  ADD PRIMARY KEY (`ReferralCodeID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ReferralCode` (`ReferralCode`,`StatusID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `tbl_tokens`
--
ALTER TABLE `tbl_tokens`
  ADD KEY `userID` (`UserID`),
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `Type` (`Type`,`Token`,`StatusID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD UNIQUE KEY `UserID` (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `PhoneNumber` (`PhoneNumber`),
  ADD KEY `UserTypeID` (`UserTypeID`),
  ADD KEY `ReferralCodeID` (`ReferralCodeID`),
  ADD KEY `ReferredByUserID` (`ReferredByUserID`),
  ADD KEY `UserGUID` (`UserGUID`);

--
-- Indexes for table `tbl_users_login`
--
ALTER TABLE `tbl_users_login`
  ADD UNIQUE KEY `UserID` (`UserID`,`Password`,`SourceID`),
  ADD KEY `fk_UserLogins_2` (`SourceID`),
  ADD KEY `Password` (`Password`,`SourceID`);

--
-- Indexes for table `tbl_users_session`
--
ALTER TABLE `tbl_users_session`
  ADD KEY `fk_DeviceTypeID` (`DeviceTypeID`),
  ADD KEY `fk_LoginSourceID` (`SourceID`),
  ADD KEY `fk_UserID` (`UserID`),
  ADD KEY `SessionKey` (`SessionKey`);

--
-- Indexes for table `tbl_users_settings`
--
ALTER TABLE `tbl_users_settings`
  ADD UNIQUE KEY `UserID` (`UserID`);

--
-- Indexes for table `tbl_users_type`
--
ALTER TABLE `tbl_users_type`
  ADD PRIMARY KEY (`UserTypeID`),
  ADD KEY `UserTypeName` (`UserTypeName`);

--
-- Indexes for table `tbl_users_wallet`
--
ALTER TABLE `tbl_users_wallet`
  ADD PRIMARY KEY (`WalletID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `EntityID` (`EntityID`),
  ADD KEY `UserTeamID` (`UserTeamID`);

--
-- Indexes for table `tbl_users_withdrawal`
--
ALTER TABLE `tbl_users_withdrawal`
  ADD PRIMARY KEY (`WithdrawalID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_control`
--
ALTER TABLE `admin_control`
  MODIFY `ControlID` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `admin_modules`
--
ALTER TABLE `admin_modules`
  MODIFY `ModuleID` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `dummy_names`
--
ALTER TABLE `dummy_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_api`
--
ALTER TABLE `log_api`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `log_cron`
--
ALTER TABLE `log_cron`
  MODIFY `CronID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_pushdata`
--
ALTER TABLE `log_pushdata`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `set_categories_type`
--
ALTER TABLE `set_categories_type`
  MODIFY `CategoryTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `set_device_type`
--
ALTER TABLE `set_device_type`
  MODIFY `DeviceTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `set_pages`
--
ALTER TABLE `set_pages`
  MODIFY `PageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `set_source`
--
ALTER TABLE `set_source`
  MODIFY `SourceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `set_status`
--
ALTER TABLE `set_status`
  MODIFY `StatusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `sports_predraft_contest`
--
ALTER TABLE `sports_predraft_contest`
  MODIFY `PredraftContestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sports_set_match_types`
--
ALTER TABLE `sports_set_match_types`
  MODIFY `MatchTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sports_team_players`
--
ALTER TABLE `sports_team_players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=731;
--
-- AUTO_INCREMENT for table `tbl_entity`
--
ALTER TABLE `tbl_entity`
  MODIFY `EntityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=930;
--
-- AUTO_INCREMENT for table `tbl_entity_type`
--
ALTER TABLE `tbl_entity_type`
  MODIFY `EntityTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tbl_media`
--
ALTER TABLE `tbl_media`
  MODIFY `MediaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_notification_pattern`
--
ALTER TABLE `tbl_notification_pattern`
  MODIFY `NotificationPatternID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_referral_codes`
--
ALTER TABLE `tbl_referral_codes`
  MODIFY `ReferralCodeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_users_type`
--
ALTER TABLE `tbl_users_type`
  MODIFY `UserTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_users_wallet`
--
ALTER TABLE `tbl_users_wallet`
  MODIFY `WalletID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_users_withdrawal`
--
ALTER TABLE `tbl_users_withdrawal`
  MODIFY `WithdrawalID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_control`
--
ALTER TABLE `admin_control`
  ADD CONSTRAINT `admin_control_ibfk_1` FOREIGN KEY (`ModuleID`) REFERENCES `admin_modules` (`ModuleID`) ON DELETE CASCADE;

--
-- Constraints for table `admin_user_type_permission`
--
ALTER TABLE `admin_user_type_permission`
  ADD CONSTRAINT `admin_user_type_permission_ibfk_1` FOREIGN KEY (`UserTypeID`) REFERENCES `tbl_users_type` (`UserTypeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_user_type_permission_ibfk_2` FOREIGN KEY (`ModuleID`) REFERENCES `admin_modules` (`ModuleID`) ON DELETE CASCADE;

--
-- Constraints for table `ecom_coupon`
--
ALTER TABLE `ecom_coupon`
  ADD CONSTRAINT `ecom_coupon_ibfk_3` FOREIGN KEY (`CouponID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `set_categories`
--
ALTER TABLE `set_categories`
  ADD CONSTRAINT `set_categories_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `set_categories_ibfk_2` FOREIGN KEY (`CategoryTypeID`) REFERENCES `set_categories_type` (`CategoryTypeID`) ON DELETE CASCADE;

--
-- Constraints for table `set_categories_type`
--
ALTER TABLE `set_categories_type`
  ADD CONSTRAINT `set_categories_type_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `set_site_config`
--
ALTER TABLE `set_site_config`
  ADD CONSTRAINT `set_site_config_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

--
-- Constraints for table `social_post`
--
ALTER TABLE `social_post`
  ADD CONSTRAINT `social_post_ibfk_5` FOREIGN KEY (`ToEntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `social_post_ibfk_6` FOREIGN KEY (`PostID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `social_post_ibfk_7` FOREIGN KEY (`EntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `social_subscribers`
--
ALTER TABLE `social_subscribers`
  ADD CONSTRAINT `social_subscribers_ibfk_4` FOREIGN KEY (`ToEntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `social_subscribers_ibfk_5` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `social_subscribers_ibfk_6` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `sports_auction_draft_player_point`
--
ALTER TABLE `sports_auction_draft_player_point`
  ADD CONSTRAINT `sports_auction_draft_player_point_ibfk1` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_auction_draft_player_point_ibfk3` FOREIGN KEY (`PlayerID`) REFERENCES `sports_players` (`PlayerID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_contest`
--
ALTER TABLE `sports_contest`
  ADD CONSTRAINT `sports_contest_ibfk_3` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_ibfk_5` FOREIGN KEY (`ContestID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_ibfk_6` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_ibfk_7` FOREIGN KEY (`MatchID`) REFERENCES `sports_matches` (`MatchID`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `sports_contest_ibfk_8` FOREIGN KEY (`AuctionStatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_matches`
--
ALTER TABLE `sports_matches`
  ADD CONSTRAINT `sports_matches_ibfk_4` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_matches_ibfk_5` FOREIGN KEY (`TeamIDLocal`) REFERENCES `sports_teams` (`TeamID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_matches_ibfk_6` FOREIGN KEY (`TeamIDVisitor`) REFERENCES `sports_teams` (`TeamID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_matches_ibfk_7` FOREIGN KEY (`MatchTypeID`) REFERENCES `sports_set_match_types` (`MatchTypeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_matches_ibfk_8` FOREIGN KEY (`MatchID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_players`
--
ALTER TABLE `sports_players`
  ADD CONSTRAINT `sports_players_ibfk_1` FOREIGN KEY (`PlayerID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_series`
--
ALTER TABLE `sports_series`
  ADD CONSTRAINT `sports_series_ibfk_1` FOREIGN KEY (`SeriesID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_series_ibfk_2` FOREIGN KEY (`AuctionDraftStatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_setting_points`
--
ALTER TABLE `sports_setting_points`
  ADD CONSTRAINT `sports_setting_points_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_teams`
--
ALTER TABLE `sports_teams`
  ADD CONSTRAINT `sports_teams_ibfk_1` FOREIGN KEY (`TeamID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_team_players`
--
ALTER TABLE `sports_team_players`
  ADD CONSTRAINT `sports_team_players_ibfk_2` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_team_players_ibfk_3` FOREIGN KEY (`TeamID`) REFERENCES `sports_teams` (`TeamID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_team_players_ibfk_4` FOREIGN KEY (`PlayerID`) REFERENCES `sports_players` (`PlayerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_team_players_ibfk_5` FOREIGN KEY (`MatchID`) REFERENCES `sports_matches` (`MatchID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_users_teams`
--
ALTER TABLE `sports_users_teams`
  ADD CONSTRAINT `sports_users_teams_contest_ibfk_5` FOREIGN KEY (`ContestID`) REFERENCES `sports_contest` (`ContestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_users_teams_ibfk_2` FOREIGN KEY (`MatchID`) REFERENCES `sports_matches` (`MatchID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_users_teams_ibfk_3` FOREIGN KEY (`UserTeamID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_users_teams_ibfk_4` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_action`
--
ALTER TABLE `tbl_action`
  ADD CONSTRAINT `tbl_action_ibfk_1` FOREIGN KEY (`EntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_action_ibfk_2` FOREIGN KEY (`ToEntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_action_ibfk_3` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `tbl_auction_player_bid`
--
ALTER TABLE `tbl_auction_player_bid`
  ADD CONSTRAINT `tbl_auction_player_bid_ibfk1` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_auction_player_bid_ibfk2` FOREIGN KEY (`ContestID`) REFERENCES `sports_contest` (`ContestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_auction_player_bid_ibfk3` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_auction_player_bid_ibfk4` FOREIGN KEY (`PlayerID`) REFERENCES `sports_players` (`PlayerID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_auction_player_bid_status`
--
ALTER TABLE `tbl_auction_player_bid_status`
  ADD CONSTRAINT `tbl_auction_player_bid_status_ibfk1` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_auction_player_bid_status_ibfk2` FOREIGN KEY (`ContestID`) REFERENCES `sports_contest` (`ContestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_auction_player_bid_status_ibfk3` FOREIGN KEY (`PlayerID`) REFERENCES `sports_players` (`PlayerID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_entity`
--
ALTER TABLE `tbl_entity`
  ADD CONSTRAINT `tbl_entity_ibfk_3` FOREIGN KEY (`EntityTypeID`) REFERENCES `tbl_entity_type` (`EntityTypeID`),
  ADD CONSTRAINT `tbl_entity_ibfk_4` FOREIGN KEY (`CreatedByUserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_entity_ibfk_5` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `tbl_entity_categories`
--
ALTER TABLE `tbl_entity_categories`
  ADD CONSTRAINT `tbl_entity_categories_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `set_categories` (`CategoryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_entity_categories_ibfk_3` FOREIGN KEY (`EntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_entity_views`
--
ALTER TABLE `tbl_entity_views`
  ADD CONSTRAINT `tbl_entity_views_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_entity_views_ibfk_2` FOREIGN KEY (`EntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_media`
--
ALTER TABLE `tbl_media`
  ADD CONSTRAINT `tbl_media_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE SET NULL,
  ADD CONSTRAINT `tbl_media_ibfk_5` FOREIGN KEY (`SectionID`) REFERENCES `tbl_media_sections` (`SectionID`);

--
-- Constraints for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `tbl_notifications_ibfk_3` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_notifications_ibfk_4` FOREIGN KEY (`ToUserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_notifications_ibfk_5` FOREIGN KEY (`NotificationPatternID`) REFERENCES `tbl_notification_pattern` (`NotificationPatternID`),
  ADD CONSTRAINT `tbl_notifications_ibfk_6` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `tbl_referral_codes`
--
ALTER TABLE `tbl_referral_codes`
  ADD CONSTRAINT `tbl_referral_codes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE SET NULL,
  ADD CONSTRAINT `tbl_referral_codes_ibfk_3` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `tbl_tokens`
--
ALTER TABLE `tbl_tokens`
  ADD CONSTRAINT `tbl_tokens_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_tokens_ibfk_3` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_3` FOREIGN KEY (`UserTypeID`) REFERENCES `tbl_users_type` (`UserTypeID`),
  ADD CONSTRAINT `tbl_users_ibfk_4` FOREIGN KEY (`UserID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_users_ibfk_5` FOREIGN KEY (`ReferralCodeID`) REFERENCES `tbl_referral_codes` (`ReferralCodeID`),
  ADD CONSTRAINT `tbl_users_ibfk_6` FOREIGN KEY (`ReferredByUserID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_users_login`
--
ALTER TABLE `tbl_users_login`
  ADD CONSTRAINT `tbl_users_login_ibfk_2` FOREIGN KEY (`SourceID`) REFERENCES `set_source` (`SourceID`),
  ADD CONSTRAINT `tbl_users_login_ibfk_3` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users_session`
--
ALTER TABLE `tbl_users_session`
  ADD CONSTRAINT `tbl_users_session_ibfk_1` FOREIGN KEY (`DeviceTypeID`) REFERENCES `set_device_type` (`DeviceTypeID`),
  ADD CONSTRAINT `tbl_users_session_ibfk_2` FOREIGN KEY (`SourceID`) REFERENCES `set_source` (`SourceID`),
  ADD CONSTRAINT `tbl_users_session_ibfk_3` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users_settings`
--
ALTER TABLE `tbl_users_settings`
  ADD CONSTRAINT `tbl_users_settings_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users_wallet`
--
ALTER TABLE `tbl_users_wallet`
  ADD CONSTRAINT `tbl_users_wallet_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_users_wallet_ibfk_2` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`),
  ADD CONSTRAINT `tbl_users_wallet_ibfk_3` FOREIGN KEY (`EntityID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_users_wallet_ibfk_4` FOREIGN KEY (`UserTeamID`) REFERENCES `sports_users_teams` (`UserTeamID`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users_withdrawal`
--
ALTER TABLE `tbl_users_withdrawal`
  ADD CONSTRAINT `tbl_users_withdrawal_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_users_withdrawal_ibfk_2` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
