-- phpMyAdmin SQL Dump
-- version 4.6.6deb1+deb.cihar.com~xenial.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 23, 2019 at 11:44 AM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-11+ubuntu16.04.1+deb.sury.org+1

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
(1, 'Dashboard', 1, NULL, 1, 'fas fa-tachometer-alt'),
(2, 'Admin', NULL, NULL, 2, 'fas fa-user-shield'),
(3, 'Staff Members', 2, 2, 1, 'flaticon-user'),
(5, 'User', NULL, NULL, 3, 'fas fa-users'),
(6, 'Users', 3, 5, 1, 'flaticon-user'),
(7, 'Social', NULL, NULL, 4, 'flaticon-user'),
(9, 'Configuration', NULL, NULL, 5, 'fas fa-cogs'),
(12, 'Store', NULL, NULL, 4, 'fas fa-store'),
(15, 'Coupon', 9, 12, 4, 'flaticon-user'),
(17, 'Broadcast', 11, 5, 2, 'flaticon-user'),
(36, 'Cricket', NULL, NULL, 4, 'flaticon-cricket'),
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
(52, 'Pre Draft Contest', 35, 36, 7, 'flaticon-user'),
(54, 'Roles/Permissions', 39, 55, 1, 'flaticon-user'),
(55, 'Setup', NULL, NULL, 1, 'fas fa-user-tag'),
(56, 'Private Cricket Sport', NULL, NULL, 6, 'flaticon-user'),
(57, 'Series', 41, 56, 1, 'flaticon-user'),
(58, 'Teams', 42, 56, 2, 'flaticon-user'),
(59, 'Players', 43, 56, 3, 'flaticon-user'),
(60, 'Matches', 44, 56, 4, 'flaticon-user'),
(61, 'Assign Team Players', 45, 56, 5, 'flaticon-user'),
(62, 'API Logs', 50, 9, 2, 'flaticon-user');

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
(9, 'Manage Coupons', 'coupon'),
(11, 'Broadcast Message', 'broadcast'),
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
(34, 'Deposit History', 'depositHistory'),
(35, 'Pre Draft Contest', 'predraft'),
(36, 'Referral History', 'referral'),
(37, 'Joined Users', 'joinedusers'),
(39, 'Roles/Permissions', 'setup/group'),
(40, 'Coupon History', 'couponhistory'),
(41, 'Private Series', 'private/cricket/series'),
(42, 'Private Teams', 'private/cricket/teams'),
(43, 'Private Players', 'private/cricket/players'),
(44, 'Private Matches', 'private/cricket/matches'),
(45, 'Assign Team Players', 'private/cricket/assignTeamPlayers'),
(46, 'Add Private Match', 'private/cricket/matches/addmatch'),
(47, 'Edit Private Match', 'private/cricket/matches/editmatch'),
(48, 'Private Players', 'private/cricket/matches/privateplayers'),
(49, 'Manage Live Score', 'private/cricket/matches/managelivescore'),
(50, 'API Logs', 'apilogs');

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_type_permission`
--

CREATE TABLE `admin_user_type_permission` (
  `UserTypeID` int(11) NOT NULL,
  `ModuleID` tinyint(2) UNSIGNED NOT NULL,
  `IsDefault` enum('Yes') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_user_type_permission`
--

INSERT INTO `admin_user_type_permission` (`UserTypeID`, `ModuleID`, `IsDefault`) VALUES
(1, 1, NULL),
(1, 2, NULL),
(1, 3, NULL),
(1, 9, NULL),
(1, 11, NULL),
(1, 15, NULL),
(1, 16, NULL),
(1, 17, NULL),
(1, 18, NULL),
(1, 19, NULL),
(1, 20, NULL),
(1, 22, NULL),
(1, 23, NULL),
(1, 24, NULL),
(1, 25, NULL),
(1, 26, NULL),
(1, 27, NULL),
(1, 28, NULL),
(1, 29, NULL),
(1, 30, NULL),
(1, 31, NULL),
(1, 34, NULL),
(1, 35, NULL),
(1, 36, NULL),
(1, 37, NULL),
(1, 39, NULL),
(1, 40, NULL),
(1, 41, NULL),
(1, 42, NULL),
(1, 43, NULL),
(1, 44, NULL),
(1, 45, NULL),
(1, 46, NULL),
(1, 47, NULL),
(1, 50, NULL),
(2, 1, NULL),
(2, 3, NULL),
(2, 11, NULL),
(2, 15, NULL),
(2, 16, NULL),
(2, 18, NULL),
(2, 20, NULL),
(2, 28, NULL),
(2, 35, NULL),
(2, 39, NULL),
(4, 25, NULL),
(4, 30, NULL),
(4, 36, NULL),
(4, 40, NULL),
(5, 11, NULL),
(5, 34, NULL),
(5, 40, NULL),
(6, 19, NULL),
(6, 25, NULL),
(6, 36, NULL),
(31, 9, NULL),
(31, 15, NULL),
(31, 16, NULL),
(31, 17, NULL),
(31, 19, NULL),
(31, 28, NULL),
(31, 35, NULL),
(31, 37, NULL),
(31, 40, NULL),
(33, 1, NULL),
(33, 11, NULL),
(33, 17, NULL),
(33, 30, 'Yes'),
(33, 37, NULL),
(36, 1, NULL),
(36, 2, NULL),
(36, 9, NULL),
(36, 11, NULL),
(36, 16, NULL),
(36, 19, NULL),
(36, 25, NULL),
(36, 27, NULL),
(36, 28, NULL),
(36, 29, NULL),
(36, 30, NULL),
(36, 34, 'Yes'),
(37, 3, 'Yes'),
(37, 9, NULL),
(37, 11, NULL),
(37, 19, NULL),
(37, 20, NULL),
(37, 34, NULL),
(37, 36, NULL);

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
  `CouponBanner` text,
  `CouponCode` varchar(12) NOT NULL,
  `CouponType` enum('Flat','Percentage') NOT NULL,
  `CouponValue` int(11) NOT NULL,
  `CouponValueLimit` int(11) DEFAULT '0',
  `MiniumAmount` mediumint(8) DEFAULT NULL,
  `MaximumAmount` mediumint(8) DEFAULT NULL,
  `NumberOfUses` smallint(6) DEFAULT NULL,
  `CouponValidTillDate` date DEFAULT NULL,
  `StatusID` int(11) NOT NULL
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
(10, 'Reviewing');

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
-- Table structure for table `sports_assign_team_players`
--

CREATE TABLE `sports_assign_team_players` (
  `SeriesID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `IsRefund` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsVirtualUserJoined` enum('Yes','No','Completed') NOT NULL DEFAULT 'No',
  `IsDummyJoined` int(11) NOT NULL DEFAULT '0',
  `VirtualUserJoinedPercentage` int(6) DEFAULT NULL,
  `IsPrivacyNameDisplay` enum('Yes','No') DEFAULT 'No',
  `CancelledBy` enum('Automated','Manually') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `IsRefund` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsWinningAssigned` enum('No','Yes') NOT NULL DEFAULT 'No' COMMENT '(From MongoDB To MySQL)',
  `IsWinningDistributed` enum('No','Yes') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `PointsLastUpdatedOn` datetime DEFAULT NULL COMMENT '(For Joined Contest Player Points Cron)',
  `IsPreSquad` enum('Yes','No') NOT NULL DEFAULT 'No' COMMENT 'if yes matches going to user crate team ',
  `PlayerStatsUpdate` enum('Yes','No') NOT NULL DEFAULT 'No',
  `MatchScoreDetails` text,
  `LastUpdatedOn` datetime DEFAULT NULL,
  `IsPlayingXINotificationSent` enum('No','Yes') NOT NULL DEFAULT 'No',
  `IsPlayerPointsUpdated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsUserPointsUpdated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsCustom` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `InitialPlayerRole` varchar(50) DEFAULT NULL,
  `IsAdminSalaryUpdated` enum('Yes','No') NOT NULL DEFAULT 'No',
  `IsCustom` enum('Yes','No') NOT NULL DEFAULT 'No',
  `LastUpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `ModifiedDate` datetime DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `IsCustom` enum('Yes','No') NOT NULL DEFAULT 'No',
  `StatusID` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `SportsType` enum('Cricket','Football','Kabaddi') NOT NULL DEFAULT 'Cricket',
  `IsCustom` enum('Yes','No') NOT NULL DEFAULT 'No',
  `StatusID` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sports_team_players`
--

CREATE TABLE `sports_team_players` (
  `PlayerID` int(11) NOT NULL,
  `SeriesID` int(11) NOT NULL,
  `MatchID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `PlayerRole` enum('AllRounder','Batsman','Bowler','WicketKeeper','Other') NOT NULL,
  `PlayerSalary` float(8,2) NOT NULL DEFAULT '0.00',
  `IsPlaying` enum('Yes','No') NOT NULL DEFAULT 'No',
  `SelectionPercent` float(4,2) NOT NULL DEFAULT '0.00',
  `TotalPoints` float(8,2) DEFAULT '0.00',
  `PointsData` text,
  `IsAdminUpdate` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(125, 'abcd', 1, NULL, '0', 0, 0, 0, 0, 0, 0, '2017-12-30 12:19:27', '2019-10-05 09:06:00', NULL, 2);

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
(1, '27892f73-2e3a-d208-9ad8-e4062fbffa3a', 1, 125, 'Coupon', NULL, 'player.png', 'player_1567410172.png', 5.15, '.png', NULL, '2019-09-02 07:42:52'),
(2, '09b6fb44-d4d8-80ac-da83-c221c8bf64fd', 1, 125, 'Coupon', NULL, 'Aparna Pandey PAN.JPEG', 'aparna_pandey_pan_1568020452.jpeg', 64.38, '.jpeg', NULL, '2019-09-09 09:14:12'),
(3, '2e76bcc4-3644-390e-b6d1-bf31300930f4', 1, 125, 'Coupon', NULL, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1568020454.png', 58.85, '.png', NULL, '2019-09-09 09:14:14'),
(8, 'dcce60a3-6ac3-3263-baba-ecc554625b4f', 1, 125, 'Coupon', NULL, 'Aparna Pandey PAN.JPEG', 'aparna_pandey_pan_1568021802.jpeg', 64.38, '.jpeg', NULL, '2019-09-09 09:36:42'),
(9, '9e668cca-92c8-ba5c-8c9c-bb1668403eef', 1, 125, 'ProfilePic', 125, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1570265473.png', 58.85, '.png', NULL, '2019-10-05 08:51:13'),
(10, 'ed0ecb99-06da-6ccd-e076-7d9ba5ed4a44', 1, 125, 'ProfilePic', 125, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1570265693.png', 58.85, '.png', NULL, '2019-10-05 08:54:53'),
(11, '24c2a558-364e-d581-55f7-be71d2aeaff9', 1, 125, 'ProfilePic', 125, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1570265725.png', 58.85, '.png', NULL, '2019-10-05 08:55:25'),
(12, '686e12ec-5f56-5585-847f-b579decb07c8', 1, 125, 'ProfilePic', 125, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1570265729.png', 58.85, '.png', NULL, '2019-10-05 08:55:29'),
(13, '05eaccb1-fe6e-9ae2-e8bf-b39cebf15939', 1, 125, 'ProfilePic', 125, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1570265740.png', 58.85, '.png', NULL, '2019-10-05 08:55:40'),
(14, '9d71b500-0b3f-f440-f92b-389799c49c92', 1, 125, 'ProfilePic', 125, '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19.png', '1564064568-ad4fcf3d-7f54-c1b4-f841-14f229fcfc19_1570265747.png', 58.85, '.png', NULL, '2019-10-05 08:55:47'),
(15, 'be858feb-3d49-eaca-b151-f9d727410865', 1, 125, 'ProfilePic', 125, 'icons8-instagram-16.png', 'icons8-instagram-16_1570265762.png', 0.97, '.png', NULL, '2019-10-05 08:56:02'),
(16, '6ebc98d1-1be5-d94c-addc-289e79ff9866', 1, 125, 'ProfilePic', 125, 'lancher 1024.png', 'lancher_1024_1570266207.png', 24.03, '.png', NULL, '2019-10-05 09:03:27'),
(17, '4f6cc7c8-01e4-dc08-257b-4bb8e8bc30a7', 1, 125, 'ProfilePic', 125, '1.png', '1_1570266226.png', 26.26, '.png', NULL, '2019-10-05 09:03:46'),
(18, '6c455441-3105-54cf-3b63-1b957375bf84', 1, 125, 'ProfilePic', 125, '1.png', '1_1570266360.png', 26.26, '.png', NULL, '2019-10-05 09:06:00'),
(19, '18ab13b1-6442-0a91-82df-6c75ae89ff2a', 1, 125, 'TeamFlag', NULL, 'download.jpeg', 'download_1571116690.jpeg', 12.72, '.jpeg', NULL, '2019-10-15 05:18:10'),
(20, 'bf4825f1-5058-a89c-4088-20b32a10dc60', 1, 125, 'TeamFlag', NULL, 'delhicapitals.jpg', 'delhicapitals_1571116832.jpg', 14.41, '.jpg', NULL, '2019-10-15 05:20:32'),
(21, 'e8349913-ac0d-f575-1bed-200da9024614', 1, 125, 'TeamFlag', NULL, 'delhicapitals.jpg', 'delhicapitals_1571117136.jpg', 14.41, '.jpg', NULL, '2019-10-15 05:25:36'),
(22, 'f11b1161-6f14-d5e9-f278-2b707aa61b1c', 1, 125, 'PlayerPic', NULL, 'A Rajpoot.jpg', 'a_rajpoot_1571117164.jpg', 9.2, '.jpg', NULL, '2019-10-15 05:26:04'),
(23, 'afc79498-b661-9c3f-b7d3-fb5acfe031e0', 1, 125, 'Post', 1744, 'download.jpeg', 'download_1571721880.jpeg', 12.72, '.jpeg', NULL, '2019-10-22 05:24:40'),
(24, 'b3f228d2-afb8-c38e-93d7-6c7b2731115c', 1, 125, 'Post', 1745, 'download.png', 'download_1571721903.png', 6.8, '.png', NULL, '2019-10-22 05:25:03'),
(26, 'dddf9aff-ae54-3bdc-fc47-755cd644e5f4', 1, 125, 'Post', 1747, 'A Tye.jpg', 'a_tye_1571737059.jpg', 10.23, '.jpg', NULL, '2019-10-22 09:37:39');

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
(4, NULL, 'cnvZEX', 1),
(5, NULL, 'ksB2Uh', 1);

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
(125, 'daac5fd7-adcb-b881-40e7-b4edaeef9311', 1, 'Admin', NULL, NULL, NULL, NULL, NULL, '1_1570266360.png', NULL, 'admin@mailinator.com', 'alexm@mailinator.com', NULL, 'No', 'Male', '1984-05-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, 76, '9898989899', '9981823755', NULL, NULL, 'native', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 9, 9, 'No');

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
(125, 'e10adc3949ba59abbe56e057f20f883e', 1, '2018-01-03 06:31:01', '2019-10-23 04:32:18', '2019-06-07 10:42:15');

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
(125, '2ab92b81-cbb4-2499-30d3-0ae6036ff75c', NULL, 1, 1, NULL, NULL, '2019-08-29 14:22:23'),
(125, 'd4e820a4-6363-d951-8430-6c81d302bbbd', NULL, 1, 1, NULL, NULL, '2019-08-30 05:54:58'),
(125, '3de08242-661d-1eaa-87de-bc3f45cd1a76', NULL, 1, 1, NULL, NULL, '2019-08-30 06:44:15'),
(125, '7c8d3ae7-3220-ca19-6b70-2054a18474e6', NULL, 1, 1, NULL, NULL, '2019-08-30 06:51:43'),
(125, 'fd68ac59-d68d-2d44-74d2-9ebfa833b663', NULL, 1, 1, NULL, NULL, '2019-09-02 08:41:29'),
(125, 'b6875c3c-bc57-da98-7483-8a2193cb7efd', NULL, 1, 1, NULL, NULL, '2019-09-09 06:33:07'),
(125, 'd940b17c-f3d3-2051-8727-51dc47135b4f', NULL, 1, 1, NULL, NULL, '2019-09-09 09:13:56'),
(125, '2b509700-dcad-254a-df37-e3f705adc058', NULL, 1, 1, NULL, NULL, '2019-09-10 06:00:16'),
(125, '330f175d-be17-c2d6-04b6-c61ac194ff55', NULL, 1, 1, NULL, NULL, '2019-09-17 10:15:42'),
(125, '7f8a8bb3-4215-8f55-99dc-40810d534ef4', NULL, 1, 1, NULL, NULL, '2019-09-17 10:15:50'),
(125, 'c4dbc297-c57d-f0bb-afbd-0c79a1fe147d', NULL, 1, 1, NULL, NULL, '2019-09-17 10:16:01'),
(125, '107c7e1e-4a0f-3d25-e3e0-76a7e8ca078e', NULL, 1, 1, NULL, NULL, '2019-09-17 10:17:41'),
(125, '50cc7360-4be0-eda3-25d0-ad9be9d6dc80', NULL, 1, 1, NULL, NULL, '2019-09-17 10:18:29'),
(125, '2bfe9592-4d91-fcc5-e2d5-7fb93289e38a', NULL, 1, 1, NULL, NULL, '2019-09-26 06:52:22'),
(125, '9509b033-3a1e-36bb-979c-876cfc74efac', NULL, 1, 1, NULL, NULL, '2019-09-26 06:52:22'),
(125, '8a5eedb8-e5aa-50be-a425-64391258d9ec', NULL, 1, 1, NULL, NULL, '2019-10-05 09:53:25'),
(125, 'd0459a11-b9c6-75fc-b165-1e8de79e3d3a', NULL, 1, 1, NULL, NULL, '2019-10-09 05:23:05'),
(125, 'ec65ab8f-9b9b-96c0-82f1-88a8471cd142', NULL, 1, 1, NULL, NULL, '2019-10-09 12:49:43'),
(125, '248cea3a-5a23-0d53-dd16-0a08c2aef69f', NULL, 1, 1, NULL, NULL, '2019-10-10 05:05:36'),
(125, '91374189-f8bd-31ba-95a7-4dcf7ee391d4', NULL, 1, 1, NULL, NULL, '2019-10-11 06:17:54'),
(125, '0db0021d-f017-a4fb-64f5-9dae50fa80d8', NULL, 1, 1, NULL, NULL, '2019-10-14 10:33:26'),
(125, 'a71efc5d-0e8b-835a-4fb2-2ee3a201707f', NULL, 1, 1, NULL, NULL, '2019-10-15 04:35:34'),
(125, '7244e06b-8434-5993-d200-b3a78df4973e', NULL, 1, 1, NULL, NULL, '2019-10-15 05:27:01'),
(125, 'ea545b1b-6b18-97bf-729b-abe6f04a469f', NULL, 1, 1, NULL, NULL, '2019-10-15 05:42:56'),
(125, 'de570e75-255d-f18f-196b-5de54e9e66ea', NULL, 1, 1, NULL, NULL, '2019-10-22 04:19:44'),
(125, '33aa33e0-11a8-7caf-6fd4-8640e9e9fdb8', NULL, 1, 1, NULL, NULL, '2019-10-22 05:21:24'),
(125, '3418f752-2265-a8c6-e297-e4aa7eec0c34', NULL, 1, 1, NULL, NULL, '2019-10-22 10:17:42'),
(125, 'b3f1d0e4-296f-49e6-a2b1-29a23cbcc81a', NULL, 1, 1, NULL, NULL, '2019-10-23 04:32:18');

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_type`
--

CREATE TABLE `tbl_users_type` (
  `UserTypeID` int(11) NOT NULL,
  `UserTypeGUID` varchar(36) NOT NULL,
  `UserTypeName` varchar(100) NOT NULL,
  `IsAdmin` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_users_type`
--

INSERT INTO `tbl_users_type` (`UserTypeID`, `UserTypeGUID`, `UserTypeName`, `IsAdmin`) VALUES
(1, 'SADMN', 'Administrator', 'Yes'),
(2, '059fae09-8245-dbae-4aeb-5876eaaf53e5', 'User', 'No'),
(4, '059fae09-8245-dbae-4aeb-5876eaaf53f5', 'Staff', 'No'),
(5, '748a1f53-cb66-976a-53a0-46bcf0b385a5', 'Manager', 'No'),
(6, '2a0f3df5-95f3-995b-d892-33f1016440a4', 'Employee', 'No'),
(31, '286d5183-4398-7161-222b-787041636c11', 'developer', 'No'),
(33, '3d9edf8b-5156-5812-27f1-4787e579ab49', 'Emp', 'No'),
(34, '5f3840ad-a41e-a5e8-eeae-4707ecf945a7', 'test', 'No'),
(35, '7eb7d8d8-c4e7-0e86-4006-62d33ff8a512', 'User123', 'No'),
(36, '5b1d38d1-8236-e4b6-f453-3ed400f660f7', 'Administrator 2', 'Yes'),
(37, '5ce9894d-c05e-0d24-9b74-5b84a6a26d86', 'Admin 3', 'Yes');

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
  ADD PRIMARY KEY (`CouponID`),
  ADD KEY `StatusID` (`StatusID`);

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
-- Indexes for table `sports_assign_team_players`
--
ALTER TABLE `sports_assign_team_players`
  ADD KEY `SeriesID` (`SeriesID`),
  ADD KEY `TeamID` (`TeamID`),
  ADD KEY `PlayerID` (`PlayerID`);

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
  ADD KEY `LeagueType` (`LeagueType`),
  ADD KEY `GameType` (`GameType`),
  ADD KEY `ContestID` (`ContestID`,`LeagueType`,`MatchID`);

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
  ADD PRIMARY KEY (`PredraftContestID`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `sports_series`
--
ALTER TABLE `sports_series`
  ADD PRIMARY KEY (`SeriesID`),
  ADD UNIQUE KEY `SeriesIDLive_2` (`SeriesIDLive`),
  ADD KEY `SeriesIDLive` (`SeriesIDLive`),
  ADD KEY `SeriesGUID` (`SeriesGUID`),
  ADD KEY `StatusID` (`StatusID`);

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
  ADD UNIQUE KEY `TeamIDLive` (`TeamIDLive`),
  ADD KEY `StatusID` (`StatusID`);

--
-- Indexes for table `sports_team_players`
--
ALTER TABLE `sports_team_players`
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
  ADD KEY `UserTeamID_3` (`UserTeamID`,`PlayerID`),
  ADD KEY `UserTeamID_4` (`UserTeamID`),
  ADD KEY `MatchID_2` (`MatchID`),
  ADD KEY `PlayerID_2` (`PlayerID`),
  ADD KEY `SeriesID_2` (`SeriesID`);

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
  MODIFY `ControlID` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `admin_modules`
--
ALTER TABLE `admin_modules`
  MODIFY `ModuleID` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `dummy_names`
--
ALTER TABLE `dummy_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_api`
--
ALTER TABLE `log_api`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `PredraftContestID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sports_set_match_types`
--
ALTER TABLE `sports_set_match_types`
  MODIFY `MatchTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tbl_entity`
--
ALTER TABLE `tbl_entity`
  MODIFY `EntityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1747;
--
-- AUTO_INCREMENT for table `tbl_entity_type`
--
ALTER TABLE `tbl_entity_type`
  MODIFY `EntityTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tbl_media`
--
ALTER TABLE `tbl_media`
  MODIFY `MediaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `tbl_notification_pattern`
--
ALTER TABLE `tbl_notification_pattern`
  MODIFY `NotificationPatternID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_referral_codes`
--
ALTER TABLE `tbl_referral_codes`
  MODIFY `ReferralCodeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_users_type`
--
ALTER TABLE `tbl_users_type`
  MODIFY `UserTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `tbl_users_wallet`
--
ALTER TABLE `tbl_users_wallet`
  MODIFY `WalletID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
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
  ADD CONSTRAINT `ecom_coupon_ibfk_3` FOREIGN KEY (`CouponID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ecom_coupon_ibfk_4` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `sports_contest_ibfk_7` FOREIGN KEY (`MatchID`) REFERENCES `sports_matches` (`MatchID`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `sports_contest_join`
--
ALTER TABLE `sports_contest_join`
  ADD CONSTRAINT `sports_contest_join_ibfk_1` FOREIGN KEY (`ContestID`) REFERENCES `sports_contest` (`ContestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_join_ibfk_2` FOREIGN KEY (`ContestID`) REFERENCES `sports_contest` (`ContestID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_join_ibfk_3` FOREIGN KEY (`UserID`) REFERENCES `tbl_users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_join_ibfk_4` FOREIGN KEY (`MatchID`) REFERENCES `sports_matches` (`MatchID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_join_ibfk_5` FOREIGN KEY (`SeriesID`) REFERENCES `sports_series` (`SeriesID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_contest_join_ibfk_6` FOREIGN KEY (`UserTeamID`) REFERENCES `sports_users_teams` (`UserTeamID`) ON DELETE CASCADE;

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
-- Constraints for table `sports_predraft_contest`
--
ALTER TABLE `sports_predraft_contest`
  ADD CONSTRAINT `sports_predraft_contest_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`);

--
-- Constraints for table `sports_series`
--
ALTER TABLE `sports_series`
  ADD CONSTRAINT `sports_series_ibfk_1` FOREIGN KEY (`SeriesID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_series_ibfk_3` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_setting_points`
--
ALTER TABLE `sports_setting_points`
  ADD CONSTRAINT `sports_setting_points_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

--
-- Constraints for table `sports_teams`
--
ALTER TABLE `sports_teams`
  ADD CONSTRAINT `sports_teams_ibfk_1` FOREIGN KEY (`TeamID`) REFERENCES `tbl_entity` (`EntityID`) ON DELETE CASCADE,
  ADD CONSTRAINT `sports_teams_ibfk_2` FOREIGN KEY (`StatusID`) REFERENCES `set_status` (`StatusID`) ON DELETE CASCADE;

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
