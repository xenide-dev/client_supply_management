-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 03, 2020 at 02:46 PM
-- Server version: 5.7.14
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supply_db`
--
CREATE DATABASE IF NOT EXISTS `supply_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `supply_db`;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `depart_code` varchar(100) DEFAULT NULL,
  `depart_name` varchar(500) DEFAULT NULL,
  `depart_abbr` varchar(100) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  PRIMARY KEY (`did`) USING BTREE,
  KEY `fid` (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`did`, `depart_code`, `depart_name`, `depart_abbr`, `fid`) VALUES
(3, '1001', 'ICT', '', 1),
(4, '1002', 'ACCOUNTING', 'ACCT', 1),
(5, '1003', 'BUDGET', NULL, 1),
(6, '1004', 'TREASURER', NULL, 1),
(7, '1005', 'ENGINEERING', NULL, 2),
(8, '1006', 'SANGGUNIANG BAYAN', NULL, 1),
(32, '1081', 'OFFICE OF THE MUNICIPAL ACCOUNTANT', 'ACCTG', NULL),
(33, '1031', 'OFFICE OF THE MUNICIPAL ADMINISTRATOR', 'ADMIN', NULL),
(34, '3323', 'AROROY MUNICIPAL COLLEGE', 'AMC', NULL),
(35, '1101', 'OFFICE OF THE MUNICIPAL ASSESSORS', 'ASSESSORS', NULL),
(36, '8751', 'OFFICE OF THE MUNICIPAL ENGINEER', 'ENGINEERING', NULL),
(37, '1061', 'GENERAL SERVICES OFFICE', 'GSO', NULL),
(38, '8711', 'MUNICIPAL AGRICULTURE OFFICE', 'MAO', NULL),
(39, '8811', 'ECONOMIC ENTERPRISE', 'MARKET', NULL),
(40, '1071', 'MUNICIPAL BUDGET OFFICE', 'MBO', NULL),
(41, '1051', 'MUNICIPAL CIVIL REGISTRAR\'S OFFICE', 'MCR', NULL),
(42, '9919', 'MUNICIPAL DISASTER RISK REDUCTION COORDINATOR OFFICE', 'MDRRMC', NULL),
(43, '8731', 'MUNICIPAL ENVIRONMENT & NATURAL RESOURCES OFFICE', 'MENRO', NULL),
(44, '4411', 'MUNICIPAL HEALTH OFFICE', 'MHO', NULL),
(45, '1011', 'MAYOR\'S OFFICE', 'MO', NULL),
(46, '1041', 'MUNICIPAL PLANNING & DEVELOPMENT OFFICE', 'MPDO', NULL),
(47, '7611', 'MUNICIPAL SOCIAL WELFARE & DEVELOPMENT OFFICE', 'MSWDO', NULL),
(48, '1091', 'MUNICIPAL TREASURER\'S OFFICE', 'MTO', NULL),
(49, '1021', 'SANGGUNNIANG BAYAN', 'SB', NULL),
(50, '1021-A', 'SANGGUNNIANG BAYAN SUPPORT STAFF', 'SS', NULL),
(51, '8721', 'OFFICE OF THE MUNICIPAL VETERINARIAN', 'VET', NULL),
(52, '1016', 'VICE MAYOR\'S OFFICE', 'VMO', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fund_type`
--

DROP TABLE IF EXISTS `fund_type`;
CREATE TABLE IF NOT EXISTS `fund_type` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `fund_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fund_type`
--

INSERT INTO `fund_type` (`fid`, `fund_name`) VALUES
(1, 'GENERAL FUND'),
(2, 'ECONOMIC ENTERPRISE'),
(4, '20% EDF'),
(5, 'SEF');

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

DROP TABLE IF EXISTS `item_category`;
CREATE TABLE IF NOT EXISTS `item_category` (
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `cat_code` varchar(100) DEFAULT NULL,
  `cat_name` varchar(500) DEFAULT NULL,
  `cat_descrip` text,
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_category`
--

INSERT INTO `item_category` (`catid`, `cat_code`, `cat_name`, `cat_descrip`) VALUES
(1, NULL, 'PESTICIDES OR PEST REPELLENTS', NULL),
(2, NULL, 'SOLVENTS', NULL),
(3, NULL, 'COLOR COMPOUNDS AND DISPERSIONS', NULL),
(4, NULL, 'FILMS', NULL),
(5, NULL, 'PAPER MATERIALS AND PRODUCTS', NULL),
(6, NULL, 'BATTERIES AND CELLS AND ACCESSORIES', NULL),
(7, NULL, 'MANUFACTURING COMPONENTS AND SUPPLIES', NULL),
(8, NULL, 'HEATING AND VENTILATION AND AIR CIRCULATION', NULL),
(9, NULL, 'LIGHTING AND FIXTURES AND ACCESSORIES', NULL),
(10, NULL, 'MEASURING AND OBSERVING AND TESTING EQUIPMENT', NULL),
(11, NULL, 'CLEANING EQUIPMENT AND SUPPLIES', NULL),
(12, NULL, 'INFORMATION AND COMMUNICATION TECHNOLOGY (ICT) EQUIPMENT AND DEVICES AND ACCESSORIES', NULL),
(13, NULL, 'OFFICE EQUIPMENT AND ACCESSORIES AND SUPPLIES', NULL),
(14, NULL, 'PRINTER OR FACSIMILE OR PHOTOCOPIER SUPPLIES', NULL),
(15, NULL, 'AUDIO AND VISUAL EQUIPMENT AND SUPPLIES', NULL),
(16, NULL, 'FLAG OR ACCESSORIES', NULL),
(17, NULL, 'PRINTED PUBLICATIONS', NULL),
(18, NULL, 'FIRE FIGHTING EQUIPMENT', NULL),
(19, NULL, 'CONSUMER ELECTRONICS', NULL),
(20, NULL, 'FURNITURE AND FURNISHINGS', NULL),
(21, NULL, 'ARTS AND CRAFTS EQUIPMENT AND ACCESSORIES AND SUPPLIES', NULL),
(22, NULL, 'SOFTWARE', NULL),
(23, NULL, 'PASSENGER AIR TRANSPORTATION', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_dictionary`
--

DROP TABLE IF EXISTS `item_dictionary`;
CREATE TABLE IF NOT EXISTS `item_dictionary` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) DEFAULT NULL,
  `item_code` varchar(100) DEFAULT NULL,
  `item_name` varchar(500) DEFAULT NULL,
  `item_description` text,
  `item_default_unit` varchar(50) DEFAULT NULL,
  `item_type` varchar(20) DEFAULT NULL COMMENT 'Consumable or Non-Consumable',
  `qr_code_path` text,
  PRIMARY KEY (`itemid`),
  KEY `catid` (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_dictionary`
--

INSERT INTO `item_dictionary` (`itemid`, `catid`, `item_code`, `item_name`, `item_description`, `item_default_unit`, `item_type`, `qr_code_path`) VALUES
(1, 1, '10191509-IN-A01', 'INSECTICIDE', 'aerosol type,net content: 600ml min', 'Can', 'Consumable', NULL),
(2, 2, '12191601-AL-E01', 'ALCOHOL', 'ethyl,68%-70%,scented,500ml (-5ml)', 'Bottle', 'Consumable', NULL),
(3, 3, '12171703-SI-P01', 'STAMP PAD INK', 'purple or violet', 'Bottle', 'Consumable', NULL),
(4, 4, '13111203-AC-F01', 'ACETATE', 'thickness: 0.075mm min (gauge #3)', 'Roll', 'Consumable', NULL),
(5, 4, '13111201-CF-P01', 'CARBON FILM,PE', 'black,size 210mm x 297mm', 'Box', 'Consumable', NULL),
(6, 4, '13111201-CF-P02', 'CARBON FILM,PE', 'black,size 216mm x 330mm', 'Box', 'Consumable', NULL),
(7, 5, '14111525-CA-A01', 'CARTOLINA', 'assorted colors', 'Pack', 'Consumable', NULL),
(8, 5, '14111506-CF-L11', 'CONTINUOUS FORM', '1 PLY,280 x 241mm', 'Box', 'Consumable', NULL),
(9, 5, '14111506-CF-L12', 'CONTINUOUS FORM', '1 PLY,280 x 378mm', 'Box', 'Consumable', NULL),
(10, 5, '14111506-CF-L22', 'CONTINUOUS FORM', '2 ply,280 x 378mm,carbonless', 'Box', 'Consumable', NULL),
(11, 5, '14111506-CF-L21', 'CONTINUOUS FORM', '2 ply,280mm x 241mm,carbonless', 'Box', 'Consumable', NULL),
(12, 5, '14111506-CF-L31', 'CONTINUOUS FORM', '3 PLY,280 x 241mm,carbonless', 'Box', 'Consumable', NULL),
(13, 5, '14111506-CF-L32', 'CONTINUOUS FORM', '3 PLY,280 x 378mm,carbonless', 'Box', 'Consumable', NULL),
(14, 5, '14111609-LL-C01', 'LOOSELEAF COVER', 'made of chipboard,for legal', 'Bundle', 'Consumable', NULL),
(15, 5, '14111514-NP-S02', 'NOTE PAD', 'stick on,50mm x 76mm (2', 'Pad', 'Consumable', NULL),
(16, 5, '14111514-NP-S04', 'NOTE PAD', 'stick on,76mm x 100mm (3', 'Pad', 'Consumable', NULL),
(17, 5, '14111514-NP-S03', 'NOTE PAD', 'stick on,76mm x 76mm (3', 'Pad', 'Consumable', NULL),
(18, 5, '14111514-NB-S01', 'NOTEBOOK,STENOGRAPHER', 'spiral,40 leaves', 'Piece', 'Consumable', NULL),
(19, 5, '14111507-PP-M01', 'PAPER,MULTICOPY', '80gsm,size: 210mm x 297mm', 'Ream', 'Consumable', NULL),
(20, 5, '14111507-PP-M02', 'PAPER,MULTICOPY', '80gsm,size: 216mm x 330mm', 'Ream', 'Consumable', NULL),
(21, 5, '14111507-PP-C01', 'PAPER,Multi-Purpose (COPY) A4', '70 gsm', 'Ream', 'Consumable', NULL),
(22, 5, '14111507-PP-C02', 'PAPER,Multi-Purpose (COPY) Legal', '70 gsm', 'Ream', 'Consumable', NULL),
(23, 5, '14111531-PP-R01', 'PAPER,PAD', 'ruled,size: 216mm x 330mm (± 2mm)', 'Pad', 'Consumable', NULL),
(24, 5, '14111503-PA-P01', 'PAPER,PARCHMENT', 'size: 210 x 297mm,multi-purpose', 'Ream', 'Consumable', NULL),
(25, 5, '14111818-TH-P02', 'PAPER,THERMAL', '55gsm,size: 216mm±1mm x 30m-0.3m', 'Roll', 'Consumable', NULL),
(26, 5, '14111531-RE-B01', 'RECORD BOOK', '300 PAGES,size: 214mm x 278mm min', 'Book', 'Consumable', NULL),
(27, 5, '14111531-RE-B02', 'RECORD BOOK', '500 PAGES,size: 214mm x 278mm min', 'Book', 'Consumable', NULL),
(28, 5, '14111704-TT-P02', 'TOILET TISSUE PAPER 2-plys sheets', '150 pulls', 'Pack', 'Consumable', NULL),
(29, 6, '26111702-BT-A02', 'BATTERY,AA', 'dry cell,2 pieces per blister pack', 'Pack', 'Consumable', NULL),
(30, 6, '26111702-BT-A01', 'BATTERY,AAA', 'dry cell,2 pieces per blister pack', 'Pack', 'Consumable', NULL),
(31, 6, '26111702-BT-A03', 'BATTERY,D', 'dry cell,1.5 volts,alkaline', 'Pack', 'Consumable', NULL),
(32, 7, '31201610-GL-J01', 'GLUE', 'all purpose,gross weight: 200 grams min', 'Jar', 'Consumable', NULL),
(33, 7, '31151804-SW-H01', 'STAPLE WIRE', 'for heavy duty staplers,(23/13)', 'Box', 'Consumable', NULL),
(34, 7, '31151804-SW-S01', 'STAPLE WIRE,STANDARD', '(26/6)', 'Box', 'Consumable', NULL),
(35, 7, '31201502-TA-E01', 'TAPE,ELECTRICAL', '18mm x 16M min', 'Roll', 'Consumable', NULL),
(36, 7, '31201503-TA-M01', 'TAPE,MASKING', 'width: 24mm (±1mm)', 'Roll', 'Consumable', NULL),
(37, 7, '31201503-TA-M02', 'TAPE,MASKING', 'width: 48mm (±1mm)', 'Roll', 'Consumable', NULL),
(38, 7, '31201517-TA-P01', 'TAPE,PACKAGING', 'width: 48mm (±1mm)', 'Roll', 'Consumable', NULL),
(39, 7, '31201512-TA-T01', 'TAPE,TRANSPARENT', 'width: 24mm (±1mm)', 'Roll', 'Consumable', NULL),
(40, 7, '31201512-TA-T02', 'TAPE,TRANSPARENT', 'width: 48mm (±1mm)', 'Roll', 'Consumable', NULL),
(41, 7, '31151507-TW-P01', 'TWINE', 'plastic,one (1) kilo per roll', 'Roll', 'Consumable', NULL),
(42, 8, '40101604-EF-G01', 'ELECTRIC FAN,INDUSTRIAL', 'ground type,metal blade', 'Unit', 'Non-Consumable', NULL),
(43, 8, '40101604-EF-C01', 'ELECTRIC FAN,ORBIT type', 'ceiling,metal blade', 'Unit', 'Non-Consumable', NULL),
(44, 8, '40101604-EF-S01', 'ELECTRIC FAN,STAND type', 'plastic blade', 'Unit', 'Non-Consumable', NULL),
(45, 8, '40101604-EF-W01', 'ELECTRIC FAN,WALL type', 'plastic blade', 'Unit', 'Non-Consumable', NULL),
(46, 9, '39101605-FL-T01', 'FLUORESCENT LAMP', '18 WATTS,linear tubular (T8)', 'Piece', 'Non-Consumable', NULL),
(47, 9, '39101628-LB-L01', 'Ligth Bulb,LED', '7 watts 1 pc in individual box', 'Piece', 'Non-Consumable', NULL),
(48, 10, '41111604-RU-P02', 'RULER', 'plastic,450mm (18', 'Piece', 'Consumable', NULL),
(49, 11, '47131812-AF-A01', 'AIR FRESHENER', 'aerosol,280ml/150g min', 'Can', 'Consumable', NULL),
(50, 11, '47131604-BR-S01', 'BROOM', 'soft (tambo)', 'Piece', 'Consumable', NULL),
(51, 11, '47131604-BR-T01', 'BROOM,STICK (TING-TING)', 'usable length: 760mm min', 'Piece', 'Consumable', NULL),
(52, 11, '47131829-TB-C01', 'CLEANER,TOILET BOWL AND URINAL', '900ml-1000ml cap', 'Bottle', 'Consumable', NULL),
(53, 11, '47131805-CL-P01', 'CLEANSER,SCOURING POWDER', '350g min./can', 'Can', 'Consumable', NULL),
(54, 11, '47131811-DE-B02', 'DETERGENT BAR', '140 grams as packed', 'Bar', 'Consumable', NULL),
(55, 11, '47131811-DE-P02', 'DETERGENT POWDER', 'all purpose,1kg', 'Pack', 'Consumable', NULL),
(56, 11, '47131803-DS-A01', 'DISINFECTANT SPRAY', 'aerosol type,400-550 grams', 'Can', 'Consumable', NULL),
(57, 11, '47131601-DU-P01', 'DUST PAN', 'non-rigid plastic,w/ detachable handle', 'Piece', 'Non-Consumable', NULL),
(58, 11, '47131802-FW-P02', 'FLOOR WAX,PASTE,RED', '', 'Can', 'Consumable', NULL),
(59, 11, '47131830-FC-A01', 'FURNITURE CLEANER', 'aerosol type,300ml min per can', 'Can', 'Consumable', NULL),
(60, 11, '47121804-MP-B01', 'MOP BUCKET', 'heavy duty,hard plastic', 'Unit', 'Non-Consumable', NULL),
(61, 11, '47131613-MP-H02', 'MOPHANDLE', 'heavy duty,aluminum,screw type', 'Piece', 'Non-Consumable', NULL),
(62, 11, '47131619-MP-R01', 'MOPHEAD', 'made of rayon,weight: 400 grams min', 'Piece', 'Non-Consumable', NULL),
(63, 11, '47131501-RG-C01', 'RAGS', 'all cotton,32 pieces per kilogram min', 'Bundle', 'Non-Consumable', NULL),
(64, 11, '47131602-SC-N01', 'SCOURING PAD', 'made of synthetic nylon,140 x 220mm', 'Pack', 'Consumable', NULL),
(65, 11, '47121701-TB-P04', 'TRASHBAG', 'plastic,transparent', 'Roll', 'Consumable', NULL),
(66, 11, '47121702-WB-P01', 'WASTEBASKET', 'non-rigid plastic', 'Piece', 'Consumable', NULL),
(67, 12, '43211507-DCT-03', 'Desktop Computer', 'branded', 'Unit', 'Non-Consumable', NULL),
(68, 12, '43201827-HD-X02', 'EXTERNAL HARD DRIVE', '1TB,2.5', 'Piece', 'Non-Consumable', NULL),
(69, 12, '43202010-FD-U01', 'FLASH DRIVE', '16 GB capacity', 'Piece', 'Non-Consumable', NULL),
(70, 12, '43211503-LCT-03', 'Laptop Computer', 'branded', 'Unit', 'Non-Consumable', NULL),
(71, 12, '43211708-MO-O01', 'MOUSE,USB connection type', 'optical', 'Unit', 'Non-Consumable', NULL),
(72, 12, '43212102-PR-D02', 'PRINTER,IMPACT DOT MATRIX', '24 pins,136 column', 'Unit', 'Non-Consumable', NULL),
(73, 12, '43212102-PR-D01', 'PRINTER,IMPACT DOT MATRIX', '9 pins,80 columns', 'Unit', 'Non-Consumable', NULL),
(74, 12, '43212105-PR-L01', 'PRINTER,LASER', 'monochrome,network-ready', 'Unit', 'Non-Consumable', NULL),
(75, 13, '44121710-CH-W01', 'CHALK', 'molded,white,dustless,length: 78mm min', 'Box', 'Consumable', NULL),
(76, 13, '44122105-BF-C01', 'CLIP,BACKFOLD', 'all metal,clamping: 19mm (-1mm)', 'Box', 'Consumable', NULL),
(77, 13, '44122105-BF-C02', 'CLIP,BACKFOLD', 'all metal,clamping: 25mm (-1mm)', 'Box', 'Consumable', NULL),
(78, 13, '44122105-BF-C03', 'CLIP,BACKFOLD', 'all metal,clamping: 32mm (-1mm)', 'Box', 'Consumable', NULL),
(79, 13, '44122105-BF-C04', 'CLIP,BACKFOLD', 'all metal,clamping: 50mm (-1mm)', 'Box', 'Consumable', NULL),
(80, 13, '44121801-CT-R01', 'CORRECTION TAPE,UL 6m min', 'film base type', 'Piece', 'Consumable', NULL),
(81, 13, '44111515-DF-B01', 'DATA FILE BOX', 'made of chipboard,with closed ends', 'Piece', 'Consumable', NULL),
(82, 13, '44122011-DF-F01', 'DATA FOLDER', 'made of chipboard,taglia lock', 'Piece', 'Consumable', NULL),
(83, 13, '44121506-EN-D01', 'ENVELOPE,DOCUMENTARY', 'for A4 size document', 'Box', 'Consumable', NULL),
(84, 13, '44121506-EN-D02', 'ENVELOPE,DOCUMENTARY', 'for legal size document', 'Box', 'Consumable', NULL),
(85, 13, '44121506-EN-X01', 'ENVELOPE,EXPANDING,KRAFTBOARD', 'for legal size doc', 'Box', 'Consumable', NULL),
(86, 13, '44121506-EN-X02', 'ENVELOPE,EXPANDING,PLASTIC', '0.50mm thickness min', 'Piece', 'Consumable', NULL),
(87, 13, '44121506-EN-M02', 'ENVELOPE,MAILING', 'white,80gsm (-5%)', 'Box', 'Consumable', NULL),
(88, 13, '44121504-EN-W02', 'ENVELOPE', 'mailing,white,with window', 'Box', 'Consumable', NULL),
(89, 13, '44111912-ER-B01', 'ERASER,FELT', 'for blackboard/whiteboard', 'Piece', 'Consumable', NULL),
(90, 13, '44122118-FA-P01', 'FASTENER,METAL', '70mm between prongs', 'Box', 'Consumable', NULL),
(91, 13, '44111515-FO-X01', 'FILE ORGANIZER', 'expanding,plastic,12 pockets', 'Piece', 'Consumable', NULL),
(92, 13, '44122018-FT-D01', 'FILE TAB DIVIDER', 'bristol board,for A4', 'Set', 'Consumable', NULL),
(93, 13, '44122018-FT-D02', 'FILE TAB DIVIDER', 'bristol board,for legal', 'Set', 'Consumable', NULL),
(94, 13, '44122011-FO-F01', 'FOLDER,FANCY', 'for A4 size documents', 'Bundle', 'Consumable', NULL),
(95, 13, '44122011-FO-F02', 'FOLDER,FANCY', 'for legal size documents', 'Bundle', 'Consumable', NULL),
(96, 13, '44122011-FO-L01', 'FOLDER,L-TYPE,PLASTIC', 'for A4 size documents', 'Pack', 'Consumable', NULL),
(97, 13, '44122011-FO-L02', 'FOLDER,L-TYPE,PLASTIC', 'for legal size documents', 'Pack', 'Consumable', NULL),
(98, 13, '44122027-FO-P01', 'FOLDER,PRESSBOARD', 'size: 240mm x 370mm (-5mm)', 'Box', 'Consumable', NULL),
(99, 13, '44122011-FO-T03', 'FOLDER,TAGBOARD', 'for A4 size documents', 'Pack', 'Consumable', NULL),
(100, 13, '44122011-FO-T04', 'FOLDER,TAGBOARD', 'for legal size documents', 'Pack', 'Consumable', NULL),
(101, 13, '44122008-IT-T01', 'INDEX TAB', 'self-adhesive,transparent', 'Box', NULL, NULL),
(102, 13, '44111515-MF-B02', 'MAGAZINE FILE BOX,LARGE size', 'made of chipboard', 'Piece', NULL, NULL),
(103, 13, '44121716-MA-F01', 'MARKER,FLUORESCENT', '3 assorted colors per set', 'Set', NULL, NULL),
(104, 13, '44121708-MW-B01', 'MARKER', 'whiteboard,black,felt tip,bullet type', 'Piece', NULL, NULL),
(105, 13, '44121708-MW-B02', 'MARKER', 'whiteboard,blue,felt tip,bullet type', 'Piece', NULL, NULL),
(106, 13, '44121708-MW-B03', 'MARKER', 'whiteboard,red,felt tip,bullet type', 'Piece', NULL, NULL),
(107, 13, '44121708-MP-B01', 'MARKER,PERMANENT', 'bullet type,black', 'Piece', NULL, NULL),
(108, 13, '44121708-MP-B02', 'MARKER,PERMANENT', 'bullet type,blue', 'Piece', NULL, NULL),
(109, 13, '44121708-MP-B03', 'MARKER,PERMANENT', 'bullet type,red', 'Piece', NULL, NULL),
(110, 13, '44122104-PC-G01', 'PAPER CLIP', 'vinyl/plastic coat,length: 32mm min', 'Box', NULL, NULL),
(111, 13, '44122104-PC-J02', 'PAPER CLIP', 'vinyl/plastic coat,length: 48mm min', 'Box', NULL, NULL),
(112, 13, '44121706-PE-L01', 'PENCIL', 'lead,w/ eraser,wood cased,hardness: HB', 'Box', NULL, NULL),
(113, 13, '44122037-RB-P10', 'RING BINDER', '80 rings,plastic,32mm x 1.12m', 'Bundle', NULL, NULL),
(114, 13, '44122101-RU-B01', 'RUBBER BAND', '70mm min lay flat length (#18)', 'Box', NULL, NULL),
(115, 13, '44121905-SP-F01', 'STAMP PAD,FELT', 'bed dimension: 60mm x 100mm min', 'Piece', NULL, NULL),
(116, 13, '44121612-BL-H01', 'CUTTER BLADE', 'for heavy duty cutter', 'Piece', NULL, NULL),
(117, 13, '44121612-CU-H01', 'CUTTER KNIFE', 'for general purpose', 'Piece', NULL, NULL),
(118, 13, '44103202-DS-M01', 'DATING AND STAMPING MACHINE', 'heavy duty', 'Piece', NULL, NULL),
(119, 13, '44121619-PS-M01', 'PENCIL SHARPENER', 'manual,single cutter head', 'Piece', NULL, NULL),
(120, 13, '44101602-PU-P01', 'PUNCHER', 'paper,heavy duty,with two hole guide', 'Piece', NULL, NULL),
(121, 13, '44121618-SS-S01', 'SCISSORS', 'symmetrical,blade length: 65mm min', 'Pair', NULL, NULL),
(122, 13, '44121615-ST-S01', 'STAPLER,STANDARD TYPE', 'load cap: 200 staples min', 'Piece', NULL, NULL),
(123, 13, '44121615-ST-B01', 'STAPLER,BINDER TYPE', 'heavy duty,desktop', 'Unit', NULL, NULL),
(124, 13, '44121613-SR-P01', 'STAPLE REMOVER,PLIER-TYPE', '', 'Piece', NULL, NULL),
(125, 13, '44121605-TD-T01', 'TAPE DISPENSER,TABLE TOP', 'for 24mm width tape', 'Piece', NULL, NULL),
(126, 13, '44101602-PB-M01', 'BINDING AND PUNCHING MACHINE', 'binding cap: 50mm', 'Unit', NULL, NULL),
(127, 13, '44101807-CA-C01', 'CALCULATOR', 'compact,12 digits', 'Unit', NULL, NULL),
(128, 13, '44101714-FX-M01', 'FACSIMILE MACHINE', 'uses thermal paper', 'Unit', NULL, NULL),
(129, 13, '44101601-PT-M01', 'PAPER TRIMMER/CUTTING MACHINE', 'max paper size: B4', 'Unit', NULL, NULL),
(130, 13, '44101603-PS-M01', 'PAPER SHREDDER', 'cutting width: 3mm-4mm (Entry Level)', 'Unit', NULL, NULL),
(131, 13, '44101603-PS-M02', 'PAPER SHREDDER', 'cutting width: 3mm-4mm (Mid-Level)', 'Unit', NULL, NULL),
(132, 14, '44103109-BR-D05', 'DRUM CART,BROTHER DR-3455', '', 'Cart', NULL, NULL),
(133, 14, '44103105-CA-C04', 'INK CART,CANON CL-741,Col.', '', 'Cart', NULL, NULL),
(134, 14, '44103105-CA-C02', 'INK CART,CANON CL-811,Colored', '', 'Cart', NULL, NULL),
(135, 14, '44103105-CA-B04', 'INK CART,CANON PG-740,Black', '', 'Cart', NULL, NULL),
(136, 14, '44103105-CA-B02', 'INK CART,CANON PG-810,Black', '', 'Cart', NULL, NULL),
(137, 14, '44103105-EP-B17', 'INK CART,EPSON C13T664100 (T6641),Black', '', 'Cart', NULL, NULL),
(138, 14, '44103105-EP-C17', 'INK CART,EPSON C13T664200 (T6642),Cyan', '', 'Cart', NULL, NULL),
(139, 14, '44103105-EP-M17', 'INK CART,EPSON C13T664300 (T6643),Magenta', '', 'Cart', NULL, NULL),
(140, 14, '44103105-EP-Y17', 'INK CART,EPSON C13T664400 (T6644),Yellow', '', 'Cart', NULL, NULL),
(141, 14, '44103105-HP-B40', 'INK CART,HP C2P04AA (HP62) Black', '', 'Cart', NULL, NULL),
(142, 14, '44103105-HP-T40', 'INK CART,HP C2P06AA (HP62) Tri-color', '', 'Cart', NULL, NULL),
(143, 14, '44103105-HP-B09', 'INK CART,HP C9351AA,Black', '(HP21)', 'Cart', NULL, NULL),
(144, 14, '44103105-HP-T10', 'INK CART,HP C9352AA,Tri-color', '(HP22)', 'Cart', NULL, NULL),
(145, 14, '44103105-HP-T30', 'INK CART,HP C9363WA,Tri-color', '(HP97)', 'Cart', NULL, NULL),
(146, 14, '44103105-HP-P48', 'INK CART,HP C9397A (HP72) 69ml Photo Black', '', 'Cart', NULL, NULL),
(147, 14, '44103105-HP-C48', 'INK CART,HP C9398A (HP72) 69ml Cyan', '', 'Cart', NULL, NULL),
(148, 14, '44103105-HP-M48', 'INK CART,HP C9399A (HP72) 69ml Magenta', '', 'Cart', NULL, NULL),
(149, 14, '44103105-HP-Y48', 'INK CART,HP C9400A (HP72) 69ml Yellow', '', 'Cart', NULL, NULL),
(150, 14, '44103105-HP-G48', 'INK CART,HP C9401A (HP72) 69ml Gray', '', 'Cart', NULL, NULL),
(151, 14, '44103105-HP-B48', 'INK CART,HP C9403A (HP72) 130ml Matte Black', '', 'Cart', NULL, NULL),
(152, 14, '44103105-HP-B17', 'INK CART,HP CC640WA,Black', '(HP60)', 'Cart', NULL, NULL),
(153, 14, '44103105-HP-T17', 'INK CART,HP CC643WA,Tri-color', '(HP60)', 'Cart', NULL, NULL),
(154, 14, '44103105-HP-B35', 'INK CART,HP CD887AA,Black', '(HP703)', 'Cart', NULL, NULL),
(155, 14, '44103105-HP-T35', 'INK CART,HP CD888AA,Tri-color', '(HP703)', 'Cart', NULL, NULL),
(156, 14, '44103105-HX-C40', 'INK CART,HP CD972AA,Cyan', '(HP 920XL)', 'Cart', NULL, NULL),
(157, 14, '44103105-HX-M40', 'INK CART,HP CD973AA,Magenta', '(HP 920XL)', 'Cart', NULL, NULL),
(158, 14, '44103105-HX-Y40', 'INK CART,HP CD974AA,Yellow', '(HP 920XL)', 'Cart', NULL, NULL),
(159, 14, '44103105-HX-B40', 'INK CART,HP CD975AA,Black', '(HP 920XL)', 'Cart', NULL, NULL),
(160, 14, '44103105-HP-B20', 'INK CART,HP CH561WA,Black', '(HP61)', 'Cart', NULL, NULL),
(161, 14, '44103105-HP-T20', 'INK CART,HP CH562WA,Tricolor', '(HP61)', 'Cart', NULL, NULL),
(162, 14, '44103105-HP-B49', 'INK CART,HP CH565A (HP82) Black', '', 'Cart', NULL, NULL),
(163, 14, '44103105-HP-C49', 'INK CART,HP CH566A (HP82) Cyan', '', 'Cart', NULL, NULL),
(164, 14, '44103105-HP-M49', 'INK CART,HP CH567A (HP82) Magenta', '', 'Cart', NULL, NULL),
(165, 14, '44103105-HP-Y49', 'INK CART,HP CH568A (HP82) Yellow', '', 'Cart', NULL, NULL),
(166, 14, '44103105-HX-B43', 'INK CART,HP CN045AA,Black', '(HP950XL)', 'Cart', NULL, NULL),
(167, 14, '44103105-HX-C43', 'INK CART,HP CN046AA,Cyan', '(HP951XL)', 'Cart', NULL, NULL),
(168, 14, '44103105-HX-M43', 'INK CART,HP CN047AA,Magenta', '(HP951XL)', 'Cart', NULL, NULL),
(169, 14, '44103105-HX-Y43', 'INK CART,HP CN048AA', '(HP951XL). Yellow', 'Cart', NULL, NULL),
(170, 14, '44103105-HP-B36', 'INK CART,HP CN692AA,Black', '(HP704)', 'Cart', NULL, NULL),
(171, 14, '44103105-HP-T36', 'INK CART,HP CN693AA,Tri-color', '(HP704)', 'Cart', NULL, NULL),
(172, 14, '44103105-HP-B33', 'INK CART,HP CZ107AA,Black', '(HP678)', 'Cart', NULL, NULL),
(173, 14, '44103105-HP-T33', 'INK CART,HP CZ108AA,Tricolor', '(HP678)', 'Cart', NULL, NULL),
(174, 14, '44103105-HP-B42', 'INK CART,HP CZ121A (HP685A),Black', '', 'Cart', NULL, NULL),
(175, 14, '44103105-HP-C33', 'INK CART,HP CZ122A (HP685A),Cyan', '', 'Cart', NULL, NULL),
(176, 14, '44103105-HP-M33', 'INK CART,HP CZ123A (HP685A),Magenta', '', 'Cart', NULL, NULL),
(177, 14, '44103105-HP-Y33', 'INK CART,HP CZ124A (HP685A),Yellow', '', 'Cart', NULL, NULL),
(178, 14, '44103105-HP-T43', 'INK CART,HP F6V26AA (HP680) Tri-color', '', 'Cart', NULL, NULL),
(179, 14, '44103105-HP-B43', 'INK CART,HP F6V27AA (HP680) Black', '', 'Cart', NULL, NULL),
(180, 14, '44103105-HP-C50', 'INK CART,HP L0S51AA (HP955) Cyan Original', '', 'Cart', NULL, NULL),
(181, 14, '44103105-HP-M50', 'INK CART,HP L0S54AA (HP955) Magenta Original', '', 'Cart', NULL, NULL),
(182, 14, '44103105-HP-Y50', 'INK CART,HP L0S57AA (HP955) Yellow Original', '', 'Cart', NULL, NULL),
(183, 14, '44103105-HP-B50', 'INK CART,HP L0S60AA (HP955) Black Original', '', 'Cart', NULL, NULL),
(184, 14, '44103105-HX-C48', 'INK CART,HP L0S63AA (HP955XL) Cyan Original', '', 'Cart', NULL, NULL),
(185, 14, '44103105-HX-M48', 'INK CART,HP L0S66AA (HP955XL) Magenta Original', '', 'Cart', NULL, NULL),
(186, 14, '44103105-HX-Y48', 'INK CART,HP L0S69AA (HP955XL) Yellow Original', '', 'Cart', NULL, NULL),
(187, 14, '44103105-HX-B48', 'INK CART,HP L0S72AA (HP955XL) Black Original', '', 'Cart', NULL, NULL),
(188, 14, '44103105-HP-C51', 'INK CART,HP T6L89AA (HP905) Cyan Original', '', 'Cart', NULL, NULL),
(189, 14, '44103105-HP-M51', 'INK CART,HP T6L93AA (HP905) Magenta Original', '', 'Cart', NULL, NULL),
(190, 14, '44103105-HP-Y51', 'INK CART,HP T6L97AA (HP905) Yellow Original', '', 'Cart', NULL, NULL),
(191, 14, '44103105-HP-B51', 'INK CART,HP T6M01AA (HP905) Black Original', '', 'Cart', NULL, NULL),
(192, 14, '44103105-HX-C49', 'INK CART,HP T6M05AA (HP905XL) Cyan Original', '', 'Cart', NULL, NULL),
(193, 14, '44103105-HX-M49', 'INK CART,HP T6M09AA (HP905XL) Magenta Original', '', 'Cart', NULL, NULL),
(194, 14, '44103105-HX-Y49', 'INK CART,HP T6M13AA (HP905XL) Yellow Original', '', 'Cart', NULL, NULL),
(195, 14, '44103105-HX-B49', 'INK CART,HP T6M17AA (HP905XL) Black Original', '', 'Cart', NULL, NULL),
(196, 14, '44103112-EP-R05', 'RIBBON CART,EPSON C13S015516 (#8750),Black', '', 'Cart', NULL, NULL),
(197, 14, '44103112-EP-R07', 'RIBBON CART,EPSON C13S015531 (S015086),Black', '', 'Cart', NULL, NULL),
(198, 14, '44103112-EP-R13', 'RIBBON CART,EPSON C13S015632,Black', 'forLX-310', 'Cart', NULL, NULL),
(199, 14, '44103103-BR-B03', 'TONER CART,BROTHER TN-2025,Black', '', 'Cart', NULL, NULL),
(200, 14, '44103103-BR-B04', 'TONER CART,BROTHER TN-2130,Black', '', 'Cart', NULL, NULL),
(201, 14, '44103103-BR-B05', 'TONER CART,BROTHER TN-2150,Black', '', 'Cart', NULL, NULL),
(202, 14, '44103103-BR-B09', 'TONER CART,BROTHER TN-3320,Black', '', 'Cart', NULL, NULL),
(203, 14, '44103103-BR-B11', 'TONER CART,BROTHER TN-3350,Black', 'for HL5450DN (CU Printer)', 'Cart', NULL, NULL),
(204, 14, '44103103-HP-B12', 'TONER CART,HP CB435A,Black', '', 'Cart', NULL, NULL),
(205, 14, '44103103-HP-B14', 'TONER CART,HP CB540A,Black', '', 'Cart', NULL, NULL),
(206, 14, '44103103-HP-B18', 'TONER CART,HP CE255A,Black', '', 'Cart', NULL, NULL),
(207, 14, '44103103-HP-B21', 'TONER CART,HP CE278A,Black', '', 'Cart', NULL, NULL),
(208, 14, '44103103-HP-B22', 'TONER CART,HP CE285A (HP85A),Black', '', 'Cart', NULL, NULL),
(209, 14, '44103103-HP-B23', 'TONER CART,HP CE310A,Black', '', 'Cart', NULL, NULL),
(210, 14, '44103103-HP-C23', 'TONER CART,HP CE311A,Cyan', '', 'Cart', NULL, NULL),
(211, 14, '44103103-HP-Y23', 'TONER CART,HP CE312A,Yellow', '', 'Cart', NULL, NULL),
(212, 14, '44103103-HP-M23', 'TONER CART,HP CE313A,Magenta', '', 'Cart', NULL, NULL),
(213, 14, '44103103-HP-B24', 'TONER CART,HP CE320A,Black', '', 'Cart', NULL, NULL),
(214, 14, '44103103-HP-C24', 'TONER CART,HP CE321A,Cyan', '', 'Cart', NULL, NULL),
(215, 14, '44103103-HP-Y24', 'TONER CART,HP CE322A,Yellow', '', 'Cart', NULL, NULL),
(216, 14, '44103103-HP-M24', 'TONER CART,HP CE323A,Magenta', '', 'Cart', NULL, NULL),
(217, 14, '44103103-HP-B25', 'TONER CART,HP CE390A,Black', '', 'Cart', NULL, NULL),
(218, 14, '44103103-HP-B26', 'TONER CART,HP CE400A,Black', '', 'Cart', NULL, NULL),
(219, 14, '44103103-HP-C26', 'TONER CART,HP CE401A,Cyan', '', 'Cart', NULL, NULL),
(220, 14, '44103103-HP-Y26', 'TONER CART,HP CE402A,Yellow', '', 'Cart', NULL, NULL),
(221, 14, '44103103-HP-M26', 'TONER CART,HP CE403A,Magenta', '', 'Cart', NULL, NULL),
(222, 14, '44103103-HP-B27', 'TONER CART,HP CE410A,Black', '(HP305)', 'Cart', NULL, NULL),
(223, 14, '44103103-HP-C27', 'TONER CART,HP CE411A,Cyan', '(HP305)', 'Cart', NULL, NULL),
(224, 14, '44103103-HP-Y27', 'TONER CART,HP CE412A,Yellow', '(HP305)', 'Cart', NULL, NULL),
(225, 14, '44103103-HP-M27', 'TONER CART,HP CE413A,Magenta', '(HP305)', 'Cart', NULL, NULL),
(226, 14, '44103103-HP-B28', 'TONER CART,HP CE505A,Black', '', 'Cart', NULL, NULL),
(227, 14, '44103103-HX-B28', 'TONER CART,HP CE505X,Black', 'high cap', 'Cart', NULL, NULL),
(228, 14, '44103103-HP-B52', 'TONER CART,HP CF217A (HP17A) Black LaserJet', '', 'Cart', NULL, NULL),
(229, 14, '44103103-HP-B53', 'TONER CART,HP CF226A (HP26A) Black LaserJet', '', 'Cart', NULL, NULL),
(230, 14, '44103103-HX-B50', 'TONER CART,HP CF226XC (HP26XC) Black LaserJet', '', 'Cart', NULL, NULL),
(231, 14, '44103103-HP-B55', 'TONER CART,HP CF280A,LaserJet Pro M401/M425 2.7K Black', '', 'Cart', NULL, NULL),
(232, 14, '44103103-HP-B51', 'TONER CART,HP CF280XC', '', 'Cart', NULL, NULL),
(233, 14, '44103103-HP-B56', 'TONER CART,HP CF281A (HP81A) Black LaserJet', '', 'Cart', NULL, NULL),
(234, 14, '44103103-HP-B57', 'TONER CART,HP CF283A (HP83A) LaserJet  Black', '', 'Cart', NULL, NULL),
(235, 14, '44103103-HX-B51', 'TONER CART,HP CF283XC (HP83X) Blk Contract LJ', '', 'Cart', NULL, NULL),
(236, 14, '44103103-HP-B58', 'TONER CART,HP CF287A (HP87) black', '', 'Cart', NULL, NULL),
(237, 14, '44103103-HP-B59', 'TONER CART,HP CF310AC (HP826) black', '', 'Cart', NULL, NULL),
(238, 14, '44103103-HP-C59', 'TONER CART,HP CF311AC (HP826) cyan', '', 'Cart', NULL, NULL),
(239, 14, '44103103-HP-Y59', 'TONER CART,HP CF312AC (HP826) yellow', '', 'Cart', NULL, NULL),
(240, 14, '44103103-HP-M59', 'TONER CART,HP CF313AC (HP826) magenta', '', 'Cart', NULL, NULL),
(241, 14, '44103103-HX-B52', 'TONER CART,HP CF325XC (HP25X) Black LaserJet', '', 'Cart', NULL, NULL),
(242, 14, '44103103-HP-B60', 'TONER CART,HP CF350A Black LJ', '', 'Cart', NULL, NULL),
(243, 14, '44103103-HP-C60', 'TONER CART,HP CF351A Cyan LJ', '', 'Cart', NULL, NULL),
(244, 14, '44103103-HP-Y60', 'TONER CART,HP CF352A Yellow LJ', '', 'Cart', NULL, NULL),
(245, 14, '44103103-HP-M60', 'TONER CART,HP CF353A Magenta LJ', '', 'Cart', NULL, NULL),
(246, 14, '44103103-HP-B61', 'TONER CART,HP CF360A (HP508A) Black LaserJet', '', 'Cart', NULL, NULL),
(247, 14, '44103103-HX-B53', 'TONER CART,HP CF360XC (HP508X) Black Contract LJ', '', 'Cart', NULL, NULL),
(248, 14, '44103103-HP-C61', 'TONER CART,HP CF361A (HP508A) Cyan LaserJet', '', 'Cart', NULL, NULL),
(249, 14, '44103103-HX-C53', 'TONER CART,HP CF361XC (HP508X) Cyan Contract LJ', '', 'Cart', NULL, NULL),
(250, 14, '44103103-HP-Y61', 'TONER CART,HP CF362A (HP508A) Yellow LaserJet', '', 'Cart', NULL, NULL),
(251, 14, '44103103-HX-Y53', 'TONER CART,HP CF362XC (HP508X) Yellow Contract LJ', '', 'Cart', NULL, NULL),
(252, 14, '44103103-HP-M61', 'TONER CART,HP CF363A (HP508A) Magenta LaserJet', '', 'Cart', NULL, NULL),
(253, 14, '44103103-HX-M53', 'TONER CART,HP CF363XC (HP508X) Magenta Contract LJ', '', 'Cart', NULL, NULL),
(254, 14, '44103103-HP-B62', 'TONER CART,HP CF400A (HP201A) Black LaserJet', '', 'Cart', NULL, NULL),
(255, 14, '44103103-HP-C62', 'TONER CART,HP CF401A (HP201A) Cyan LaserJet', '', 'Cart', NULL, NULL),
(256, 14, '44103103-HP-Y62', 'TONER CART,HP CF402A (HP201A) Yellow LaserJet', '', 'Cart', NULL, NULL),
(257, 14, '44103103-HP-M62', 'TONER CART,HP CF403A (HP201A) Magenta LaserJet', '', 'Cart', NULL, NULL),
(258, 14, '44103103-HP-B63', 'TONER CART,HP CF410A (HP410A) black', '', 'Cart', NULL, NULL),
(259, 14, '44103103-HX-B54', 'TONER CART,HP CF410XC (HP410XC) black', '', 'Cart', NULL, NULL),
(260, 14, '44103103-HP-C63', 'TONER CART,HP CF411A (HP410A) cyan', '', 'Cart', NULL, NULL),
(261, 14, '44103103-HX-C54', 'TONER CART,HP CF411XC (HP410XC) cyan', '', 'Cart', NULL, NULL),
(262, 14, '44103103-HP-Y63', 'TONER CART,HP CF412A (HP410A) yellow', '', 'Cart', NULL, NULL),
(263, 14, '44103103-HX-Y54', 'TONER CART,HP CF412XC (HP410XC) yellow', '', 'Cart', NULL, NULL),
(264, 14, '44103103-HP-M63', 'TONER CART,HP CF413A (HP410A) magenta', '', 'Cart', NULL, NULL),
(265, 14, '44103103-HX-M54', 'TONER CART,HP CF413XC (HP410XC) magenta', '', 'Cart', NULL, NULL),
(266, 14, '44103103-HP-B34', 'TONER CART,HP Q2612A,Black', '', 'Cart', NULL, NULL),
(267, 14, '44103103-HP-B39', 'TONER CART,HP Q5942A,Black', '', 'Cart', NULL, NULL),
(268, 14, '44103103-HP-B48', 'TONER CART,HP Q7553A,Black', '', 'Cart', NULL, NULL),
(269, 14, '44103103-LX-B03', 'TONER CART,LEXMARK E360H11P,Black', '', 'Cart', NULL, NULL),
(270, 14, '44103103-LX-B05', 'TONER CART,LEXMARK T650A11P,Black', '', 'Cart', NULL, NULL),
(271, 14, '44103103-SA-B06', 'TONER CART,SAMSUNG MLT-D101S,Black', '', 'Cart', NULL, NULL),
(272, 14, '44103103-SA-B07', 'TONER CART,SAMSUNG MLT-D103S,Black', '', 'Cart', NULL, NULL),
(273, 14, '44103103-SA-B08', 'TONER CART,SAMSUNG MLT-D104S,Black', '', 'Cart', NULL, NULL),
(274, 14, '44103103-SA-B09', 'TONER CART,SAMSUNG MLT-D105L,Black', '', 'Cart', NULL, NULL),
(275, 14, '44103103-SA-B14', 'TONER CART,SAMSUNG MLT-D108S,Black', '', 'Cart', NULL, NULL),
(276, 14, '44103103-SA-B21', 'TONER CART,SAMSUNG MLT-D203E,Black', '', 'Cart', NULL, NULL),
(277, 14, '44103103-SA-B18', 'TONER CART,SAMSUNG MLT-D203L,Black', '', 'Cart', NULL, NULL),
(278, 14, '44103103-SA-B20', 'TONER CART,SAMSUNG MLT-D203U', 'black', 'Cart', NULL, NULL),
(279, 14, '44103103-SA-B12', 'TONER CART,SAMSUNG MLT-D205E,Black', '', 'Cart', NULL, NULL),
(280, 14, '44103103-SA-B05', 'TONER CART,SAMSUNG MLT-D205L,Black', '', 'Cart', NULL, NULL),
(281, 14, '44103103-SA-B10', 'TONER CART,SAMSUNG SCX-D6555A,Black', '', 'Cart', NULL, NULL),
(282, 14, '44103103-BR-B15', 'TONER CARTRIDGE,BROTHER TN-3478,Blackf', 'for printer HL-6400DW (12,000 pages)', 'Cart', NULL, NULL),
(283, 14, '44103103-CA-B00', 'TONER CARTRIDGE,CANON 324 II', 'for  printer LBP6780x', 'Cart', NULL, NULL),
(284, 15, '45121517-DO-C01', 'DOCUMENT CAMERA', '3.2m pixels', 'Unit', 'Non-Consumable', NULL),
(285, 15, '45111609-MM-P01', 'MULTIMEDIA PROJECTOR', '4000 min ansi lumens', 'Unit', 'Non-Consumable', NULL),
(286, 16, '55121905-PH-F01', 'PHILIPPINE NATIONAL FLAG', '100% polyester', 'Piece', NULL, NULL),
(287, 17, '55101524-RA-H01', 'HANDBOOK (RA 9184)', '7th Edition', 'Book', NULL, NULL),
(288, 18, '46191601-FE-M01', 'FIRE EXTINGUISHER,DRY CHEMICAL', '4.5kgs', 'Unit', NULL, NULL),
(289, 18, '46191601-FE-H01', 'FIRE EXTINGUISHER,PURE HCFC 123', '4.5kgs', 'Unit', NULL, NULL),
(290, 19, '52161535-DV-R01', 'DIGITAL VOICE RECORDER', 'memory: 4GB (expandable)', 'Unit', NULL, NULL),
(291, 20, '56101504-CM-B01', 'CHAIR', 'monobloc,beige,with backrest,w/o armrest', 'Piece', NULL, NULL),
(292, 20, '56101504-CM-W01', 'CHAIR', 'monobloc,white,with backrest,w/o armrest', 'Piece', NULL, NULL),
(293, 20, '56101519-TM-S01', 'TABLE,MONOBLOC,WHITE', '889 x 889mm (35', 'Unit', NULL, NULL),
(294, 20, '56101519-TM-S02', 'TABLE,MONOBLOC,BEIGE', '889 x 889mm (35', 'Unit', NULL, NULL),
(295, 21, '60121413-CB-P01', 'CLEARBOOK', '20 transparent pockets,for a4 size', 'Piece', 'Consumable', NULL),
(296, 21, '60121413-CB-P02', 'CLEARBOOK', '20 transparent pockets,for legal size', 'Piece', 'Consumable', NULL),
(297, 21, '60121534-ER-P01', 'ERASER,PLASTIC/RUBBER', 'for pencil draft/writing', 'Piece', 'Consumable', NULL),
(298, 21, '60121524-SP-G01', 'SIGN PEN,BLACK', 'liquid/gel ink,0.5mm needle tip', 'Piece', 'Consumable', NULL),
(299, 21, '60121524-SP-G02', 'SIGN PEN,BLUE', 'liquid/gel ink,0.5mm needle tip', 'Piece', 'Consumable', NULL),
(300, 21, '60121524-SP-G03', 'SIGN PEN,RED', 'liquid/gel ink,0.5mm needle tip', 'Piece', 'Consumable', NULL),
(301, 21, '60121124-WR-P01', 'WRAPPING PAPER', 'kraft,65gsm (-5%)', 'Pack', 'Consumable', NULL),
(302, 22, '43231513-SFT-001', 'Business function specific software', '', 'License', NULL, NULL),
(303, 22, '43231602-SFT-002', 'Finance accounting and enterprise resource planning ERP software', '', 'License', NULL, NULL),
(304, 22, '43232004-SFT-003', 'Computer game or entertainment software', '', 'License', NULL, NULL),
(305, 22, '43232107-SFT-004', 'Content authoring and editing software', '', 'License', NULL, NULL),
(306, 22, '43232202-SFT-005', 'Content management software', '', 'License', NULL, NULL),
(307, 22, '43232304-SFT-006', 'Data management and query software', '', 'License', NULL, NULL),
(308, 22, '43232402-SFT-007', 'Development software', '', 'License', NULL, NULL),
(309, 22, '43232505-SFT-008', 'Educational or reference software', '', 'License', NULL, NULL),
(310, 22, '43232603-SFT-009', 'Industry specific software', '', 'License', NULL, NULL),
(311, 22, '43232701-SFT-010', 'Network applications software', '', 'License', NULL, NULL),
(312, 22, '43232802-SFT-011', 'Network management software', '', 'License', NULL, NULL),
(313, 22, '43232905-SFT-012', 'Networking software', '', 'License', NULL, NULL),
(314, 22, '43233004-SFT-013', 'Operating environment software', '', 'License', NULL, NULL),
(315, 22, '43233205-SFT-014', 'Security and protection software', '', 'License', NULL, NULL),
(316, 22, '43233405-SFT-015', 'Utility and device driver software', '', 'License', NULL, NULL),
(317, 22, '43233501-SFT-016', 'Information exchange software', '', 'License', NULL, NULL),
(318, 23, '43212111-GFA-001', '', '* Airline Ticket', 'Ticket', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ppmp`
--

DROP TABLE IF EXISTS `ppmp`;
CREATE TABLE IF NOT EXISTS `ppmp` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `ppmp_year` int(11) DEFAULT NULL,
  `total_supplies` int(11) DEFAULT NULL,
  `total_equipments` int(11) DEFAULT NULL,
  `isSeen` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ppmp_items`
--

DROP TABLE IF EXISTS `ppmp_items`;
CREATE TABLE IF NOT EXISTS `ppmp_items` (
  `piid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `itemid` int(11) DEFAULT NULL,
  `requested_qty` int(11) DEFAULT NULL,
  `requested_unit` varchar(20) DEFAULT NULL,
  `mon_jan` int(11) NOT NULL DEFAULT '0',
  `mon_feb` int(11) NOT NULL DEFAULT '0',
  `mon_mar` int(11) NOT NULL DEFAULT '0',
  `mon_apr` int(11) NOT NULL DEFAULT '0',
  `mon_may` int(11) NOT NULL DEFAULT '0',
  `mon_jun` int(11) NOT NULL DEFAULT '0',
  `mon_jul` int(11) NOT NULL DEFAULT '0',
  `mon_aug` int(11) NOT NULL DEFAULT '0',
  `mon_sep` int(11) NOT NULL DEFAULT '0',
  `mon_oct` int(11) NOT NULL DEFAULT '0',
  `mon_nov` int(11) NOT NULL DEFAULT '0',
  `mon_dec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`piid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

DROP TABLE IF EXISTS `purchase_order`;
CREATE TABLE IF NOT EXISTS `purchase_order` (
  `poid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  `supplier_name` varchar(500) DEFAULT NULL,
  `supplier_address` text,
  `total_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending' COMMENT 'Pending (Default), Approved',
  PRIMARY KEY (`poid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE IF NOT EXISTS `purchase_order_items` (
  `poiid` int(11) NOT NULL AUTO_INCREMENT,
  `poid` int(11) DEFAULT NULL,
  `riid` int(11) DEFAULT NULL,
  `unit_cost` double DEFAULT NULL,
  `total_cost` double DEFAULT NULL,
  `isDelivered` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'inspector side | mark the items if already delivered or inspected',
  PRIMARY KEY (`poiid`),
  KEY `riid` (`riid`),
  KEY `poid` (`poid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `request_no` varchar(100) DEFAULT NULL,
  `request_type` varchar(50) DEFAULT 'Requisition' COMMENT 'Requisition - for request that are available\r\nPurchase Request - for request that are not available',
  `request_purpose` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `total_supplies_requested` int(11) DEFAULT NULL,
  `total_equipments_requested` int(11) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'Pending',
  `remarks` text COMMENT 'In case of disapproved status, you can add reason here ',
  PRIMARY KEY (`rid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_items`
--

DROP TABLE IF EXISTS `request_items`;
CREATE TABLE IF NOT EXISTS `request_items` (
  `riid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `itemid` int(11) DEFAULT NULL,
  `requested_qty` int(11) DEFAULT NULL,
  `requested_unit` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`riid`),
  KEY `itemid` (`itemid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_tracer`
--

DROP TABLE IF EXISTS `request_tracer`;
CREATE TABLE IF NOT EXISTS `request_tracer` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `tracer_no` varchar(100) DEFAULT NULL COMMENT 'Ex. 1, 2, 3, ...',
  `rid` int(11) DEFAULT NULL,
  `source_uid` int(11) DEFAULT NULL,
  `destination_uid_type` varchar(50) DEFAULT NULL COMMENT 'If regional director, admin, inspector, user',
  `destination_uid` int(11) DEFAULT NULL COMMENT 'id of the user who take an action',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) DEFAULT NULL,
  `remarks` text,
  `isSeen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `source_uid` (`source_uid`),
  KEY `destination_uid` (`destination_uid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplies_equipment`
--

DROP TABLE IF EXISTS `supplies_equipment`;
CREATE TABLE IF NOT EXISTS `supplies_equipment` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) DEFAULT NULL,
  `item_qty` int(11) DEFAULT NULL,
  `available_qty` int(11) NOT NULL DEFAULT '0' COMMENT 'this is used when retrieving available qty for the user',
  `item_unit` varchar(50) DEFAULT NULL COMMENT 'Can be adopted from item default unit or override it',
  `reorder_point` double DEFAULT NULL COMMENT 'Percentage Amount',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sid`),
  KEY `itemid` (`itemid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplies_equipment_transaction`
--

DROP TABLE IF EXISTS `supplies_equipment_transaction`;
CREATE TABLE IF NOT EXISTS `supplies_equipment_transaction` (
  `stid` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `transaction_type` varchar(10) DEFAULT NULL COMMENT 'In or Out',
  `sid` int(11) DEFAULT NULL,
  `poid` int(11) DEFAULT NULL,
  `riid` int(11) DEFAULT NULL COMMENT 'for out',
  `destination_uid` int(11) DEFAULT NULL COMMENT 'for out',
  `item_qty` int(11) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL COMMENT 'transfer, return, purchase order, request (requisition/issue from purchase order), lost (basis for inventory)',
  `item_status` text COMMENT 'user input (especially when transferring of items, i.e. slightly damage etc.) maybe optional',
  `report_type` varchar(30) DEFAULT NULL COMMENT 'ICS or PAR',
  `report_item_no` varchar(100) DEFAULT NULL COMMENT 'for property number when releasing an item',
  `report_overall_no` varchar(50) DEFAULT NULL,
  `transaction_status` varchar(100) DEFAULT NULL COMMENT 'pending, approved, disapproved, for special cases like transferring.',
  `transaction_reason` text COMMENT 'can be use for reasoning like why the user needs to transfer the equipment and etc',
  `requested_by_uid` int(11) DEFAULT NULL COMMENT 'User id of the requestor, can be the user himself or the admin',
  `target_uid` int(11) DEFAULT NULL COMMENT 'the proposed user',
  `isSeen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stid`),
  KEY `sid` (`sid`),
  KEY `destination_uid` (`destination_uid`),
  KEY `riid` (`riid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplies_equipment_transaction_qr_collection`
--

DROP TABLE IF EXISTS `supplies_equipment_transaction_qr_collection`;
CREATE TABLE IF NOT EXISTS `supplies_equipment_transaction_qr_collection` (
  `qrid` int(11) NOT NULL AUTO_INCREMENT,
  `stid` int(11) DEFAULT NULL COMMENT 'id from supplies equipment transaction',
  `item_number` varchar(100) DEFAULT NULL COMMENT 'the report_item_no under supplies equipment transaction which has a suffix of -01 and soon depending on the total outgoing quantity',
  `qr_path` text COMMENT 'the location of qr code (image)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`qrid`),
  KEY `stid` (`stid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `units_measure`
--

DROP TABLE IF EXISTS `units_measure`;
CREATE TABLE IF NOT EXISTS `units_measure` (
  `umid` int(11) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`umid`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `units_measure`
--

INSERT INTO `units_measure` (`umid`, `unit_name`) VALUES
(1, 'Can'),
(2, 'Bottle'),
(3, 'Roll'),
(4, 'Box'),
(5, 'Pack'),
(6, 'Bundle'),
(7, 'Pad'),
(8, 'Piece'),
(9, 'Ream'),
(10, 'Book'),
(11, 'Jar'),
(12, 'Unit'),
(13, 'Bar'),
(14, 'Set'),
(15, 'Pair'),
(16, 'Cart'),
(17, 'License'),
(18, 'Ticket'),
(19, 'Meter'),
(20, 'Centimeter'),
(21, 'Yard'),
(22, 'Dozen'),
(23, 'Sack'),
(24, 'Glass');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
CREATE TABLE IF NOT EXISTS `user_accounts` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `temp_pass` varchar(100) DEFAULT NULL,
  `user_type` varchar(100) DEFAULT NULL,
  `gmt_created` datetime DEFAULT NULL,
  `priviledges` text,
  `gmt_last_access` datetime DEFAULT NULL,
  `isOnline` tinyint(1) NOT NULL DEFAULT '0',
  `isActive` tinyint(1) NOT NULL DEFAULT '1',
  `employeeid` varchar(50) DEFAULT NULL,
  `fname` varchar(200) DEFAULT NULL,
  `mname` varchar(200) DEFAULT NULL,
  `lname` varchar(200) DEFAULT NULL,
  `midinit` varchar(5) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `citizenship` varchar(100) NOT NULL DEFAULT 'FILIPINO',
  `religion` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `contact_mobile` varchar(20) DEFAULT NULL,
  `contact_email` text,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`uid`, `username`, `password`, `temp_pass`, `user_type`, `gmt_created`, `priviledges`, `gmt_last_access`, `isOnline`, `isActive`, `employeeid`, `fname`, `mname`, `lname`, `midinit`, `birthdate`, `gender`, `citizenship`, `religion`, `address`, `contact_mobile`, `contact_email`) VALUES
(9, '12-7500', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'User', '2020-02-19 10:03:31', 'list_supplies,list_equipment,list_request,issuance_supplies,issuance_equipments,issuance_records', '2020-03-03 22:30:37', 0, 1, '12-7500', 'F', 'M', 'L', 'M', '1995-09-01', 'Male', 'FILIPINO', 'ROMAN CATHOLIC', 'ZONE 8, BULAN, SORSOGON', '0905 461 2597', 'anthony.gacis@yahoo.com'),
(10, '123456', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'Regional Director', '2020-02-19 12:48:45', 'list_supplies,list_equipment,doc_approval', '2020-03-03 22:29:33', 0, 1, '123456', 'JUAN', 'DELA', 'CRUZ', 'D', '1995-12-01', 'Male', 'FILIPINO', 'ROMAN CATHOLIC', 'ZONE 8, BULAN, SORSOGON', '0912 345 6789', 'anthony.gacis@yahoo.com'),
(11, 'admin123', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'Administrator', '2020-02-19 13:08:07', 'item_equipment,purchase_order,list_ppmp,list_supplies,list_equipment,list_request,issuance_supplies,issuance_equipments,issuance_records,reports,manage_account,user_activities', '2020-03-03 22:29:23', 1, 1, 'admin123', 'ADMIN', 'ADMIN', 'ADMIN', 'A', '1995-09-01', 'Male', 'FILIPINO', 'ROMAN CATHOLIC', 'ZONE 8, BULAN, SORSOGON', '0054 612 597_', 'anthony.gacis@yahoo.com'),
(12, 'ins123', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'Inspector', '2020-02-22 22:10:32', 'inspection_supplies_equipments', '2020-03-03 22:22:02', 0, 1, 'ins123', 'INSPECTOR', 'INSPECTOR', 'INSPECTOR', 'I', '1995-09-01', 'Male', 'FILIPINO', 'ROMAN CATHOLIC', 'ZONE 8, BULAN, SORSOGON', '0905 461 2597', 'anthony.gacis@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
CREATE TABLE IF NOT EXISTS `user_activities` (
  `act_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `act_name` varchar(100) DEFAULT NULL,
  `act_descrip` text,
  `event_type` varchar(100) DEFAULT NULL,
  `gmt_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=573 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`act_id`, `uid`, `act_name`, `act_descrip`, `event_type`, `gmt_datetime`) VALUES
(337, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:48:13'),
(338, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:48:17'),
(339, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:48:21'),
(340, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:48:28'),
(341, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:48:45'),
(342, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:48:49'),
(343, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:48:54'),
(344, 12, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:48:59'),
(345, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:49:08'),
(346, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:49:12'),
(347, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:50:57'),
(348, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:51:02'),
(349, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:51:06'),
(350, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:51:09'),
(351, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:51:25'),
(352, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:51:31'),
(353, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:51:35'),
(354, 12, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:51:45'),
(355, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:51:53'),
(356, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:51:58'),
(357, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:54:28'),
(358, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:54:31'),
(359, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:54:37'),
(360, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:54:41'),
(361, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:54:55'),
(362, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:55:01'),
(363, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:55:06'),
(364, 12, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:55:10'),
(365, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 13:55:18'),
(366, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 13:55:21'),
(367, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:09:43'),
(368, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 16:09:49'),
(369, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:09:54'),
(370, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 16:10:01'),
(371, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:10:17'),
(372, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 16:10:20'),
(373, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:10:25'),
(374, 12, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 16:10:29'),
(375, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:10:38'),
(376, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 16:10:41'),
(377, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:46:27'),
(378, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 16:46:30'),
(379, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 20:29:05'),
(380, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 20:30:43'),
(381, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 21:12:52'),
(382, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 21:35:47'),
(383, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 21:35:59'),
(384, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 21:55:13'),
(385, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 22:15:47'),
(386, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 22:15:54'),
(387, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-26 22:15:58'),
(388, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-26 23:52:55'),
(389, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-27 07:40:53'),
(390, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-27 15:00:45'),
(391, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-27 16:44:30'),
(392, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-27 19:49:48'),
(393, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-27 19:59:10'),
(394, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-27 20:58:57'),
(395, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-27 20:59:01'),
(396, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-27 21:00:17'),
(397, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-27 21:05:13'),
(398, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-27 21:05:29'),
(399, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-27 21:11:52'),
(400, 9, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:08:19'),
(401, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 09:10:56'),
(402, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:11:01'),
(403, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 09:11:09'),
(404, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:11:15'),
(405, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 09:12:13'),
(406, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:12:17'),
(407, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 09:12:24'),
(408, 12, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:12:28'),
(409, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:14:14'),
(410, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 09:21:28'),
(411, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 09:21:31'),
(412, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 09:21:36'),
(413, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 10:12:10'),
(414, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 10:16:33'),
(415, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 14:22:14'),
(416, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 14:22:18'),
(417, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 19:06:06'),
(418, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 19:06:10'),
(419, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 19:07:22'),
(420, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 19:07:30'),
(421, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 19:13:33'),
(422, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 19:13:36'),
(423, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 21:47:28'),
(424, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 21:47:32'),
(425, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 22:11:33'),
(426, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 22:11:41'),
(427, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 23:05:48'),
(428, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 23:30:29'),
(429, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 23:30:34'),
(430, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 23:30:37'),
(431, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 23:30:41'),
(432, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 23:30:50'),
(433, 10, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 23:31:15'),
(434, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-02-29 23:32:09'),
(435, 11, 'User Accessed', 'None', 'LOG-IN', '2020-02-29 23:32:13'),
(436, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 01:09:40'),
(437, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 01:09:45'),
(438, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 01:10:39'),
(439, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 01:21:53'),
(440, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 10:51:30'),
(441, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 10:55:59'),
(442, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 12:22:53'),
(443, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 14:33:24'),
(444, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 14:36:15'),
(445, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 14:36:20'),
(446, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 14:41:13'),
(447, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 14:41:17'),
(448, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-01 16:26:15'),
(449, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-01 21:50:59'),
(450, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-02 08:02:15'),
(451, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-02 08:23:41'),
(452, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-02 08:24:23'),
(453, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-02 08:26:46'),
(454, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-02 11:01:59'),
(455, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-02 15:27:03'),
(456, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-02 15:27:07'),
(457, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-02 16:50:42'),
(458, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 08:03:14'),
(459, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 12:21:12'),
(460, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 12:21:16'),
(461, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 12:25:01'),
(462, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 12:25:06'),
(463, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 16:37:04'),
(464, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 19:06:43'),
(465, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 19:06:56'),
(466, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 19:07:00'),
(467, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 19:07:51'),
(468, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 19:07:57'),
(469, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 19:08:10'),
(470, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 19:08:14'),
(471, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:00:21'),
(472, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:00:25'),
(473, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:02:19'),
(474, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:02:26'),
(475, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:03:30'),
(476, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:03:35'),
(477, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:03:54'),
(478, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:03:58'),
(479, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:04:27'),
(480, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:04:31'),
(481, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:07:16'),
(482, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:07:23'),
(483, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:07:43'),
(484, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:07:47'),
(485, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:07:49'),
(486, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:08:08'),
(487, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:09:10'),
(488, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:09:19'),
(489, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:09:45'),
(490, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:09:49'),
(491, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:09:52'),
(492, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:09:56'),
(493, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:10:01'),
(494, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:10:05'),
(495, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:10:18'),
(496, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:10:26'),
(497, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:10:58'),
(498, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:11:02'),
(499, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:11:10'),
(500, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:11:15'),
(501, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:12:12'),
(502, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:12:20'),
(503, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:12:56'),
(504, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:13:00'),
(505, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:15:06'),
(506, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:35:39'),
(507, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:35:57'),
(508, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:38:19'),
(509, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:38:22'),
(510, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:38:26'),
(511, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:51:12'),
(512, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:51:16'),
(513, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:52:10'),
(514, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:52:20'),
(515, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 20:52:32'),
(516, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 20:52:36'),
(517, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:13:02'),
(518, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:13:06'),
(519, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:14:40'),
(520, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:14:43'),
(521, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:19:32'),
(522, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:19:39'),
(523, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:20:09'),
(524, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:36:17'),
(525, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:36:20'),
(526, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:39:56'),
(527, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:40:00'),
(528, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:52:30'),
(529, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:52:33'),
(530, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:52:39'),
(531, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:52:44'),
(532, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:52:59'),
(533, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:53:02'),
(534, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:53:08'),
(535, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:53:11'),
(536, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:54:00'),
(537, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:54:20'),
(538, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:54:51'),
(539, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:55:00'),
(540, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:58:50'),
(541, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:58:55'),
(542, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:59:06'),
(543, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 21:59:11'),
(544, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 21:59:19'),
(545, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:12:33'),
(546, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:12:35'),
(547, 9, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:12:39'),
(548, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:12:59'),
(549, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:13:10'),
(550, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:13:17'),
(551, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:13:21'),
(552, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:16:04'),
(553, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:16:09'),
(554, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:16:22'),
(555, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:16:25'),
(556, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:16:31'),
(557, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:16:46'),
(558, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:20:52'),
(559, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:20:56'),
(560, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:21:01'),
(561, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:21:06'),
(562, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:21:24'),
(563, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:21:27'),
(564, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:21:33'),
(565, 12, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:21:46'),
(566, 12, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:22:02'),
(567, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:22:11'),
(568, 11, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:29:23'),
(569, 10, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:29:26'),
(570, 10, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:29:33'),
(571, 11, 'User Accessed', 'None', 'LOG-IN', '2020-03-03 22:29:38'),
(572, 9, 'Session Ended', 'None', 'LOG-OUT', '2020-03-03 22:30:37');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `fund_type` (`fid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `item_dictionary`
--
ALTER TABLE `item_dictionary`
  ADD CONSTRAINT `item_dictionary_ibfk_1` FOREIGN KEY (`catid`) REFERENCES `item_category` (`catid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ppmp`
--
ALTER TABLE `ppmp`
  ADD CONSTRAINT `ppmp_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user_accounts` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ppmp_items`
--
ALTER TABLE `ppmp_items`
  ADD CONSTRAINT `ppmp_items_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `ppmp` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `request` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_ibfk_1` FOREIGN KEY (`riid`) REFERENCES `request_items` (`riid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_items_ibfk_2` FOREIGN KEY (`poid`) REFERENCES `purchase_order` (`poid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user_accounts` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_items`
--
ALTER TABLE `request_items`
  ADD CONSTRAINT `request_items_ibfk_1` FOREIGN KEY (`itemid`) REFERENCES `item_dictionary` (`itemid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_items_ibfk_2` FOREIGN KEY (`rid`) REFERENCES `request` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request_tracer`
--
ALTER TABLE `request_tracer`
  ADD CONSTRAINT `request_tracer_ibfk_1` FOREIGN KEY (`source_uid`) REFERENCES `user_accounts` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_tracer_ibfk_2` FOREIGN KEY (`destination_uid`) REFERENCES `user_accounts` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_tracer_ibfk_3` FOREIGN KEY (`rid`) REFERENCES `request` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplies_equipment`
--
ALTER TABLE `supplies_equipment`
  ADD CONSTRAINT `supplies_equipment_ibfk_1` FOREIGN KEY (`itemid`) REFERENCES `item_dictionary` (`itemid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplies_equipment_transaction`
--
ALTER TABLE `supplies_equipment_transaction`
  ADD CONSTRAINT `supplies_equipment_transaction_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `supplies_equipment` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supplies_equipment_transaction_ibfk_2` FOREIGN KEY (`destination_uid`) REFERENCES `user_accounts` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `supplies_equipment_transaction_ibfk_3` FOREIGN KEY (`riid`) REFERENCES `request_items` (`riid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplies_equipment_transaction_qr_collection`
--
ALTER TABLE `supplies_equipment_transaction_qr_collection`
  ADD CONSTRAINT `supplies_equipment_transaction_qr_collection_ibfk_1` FOREIGN KEY (`stid`) REFERENCES `supplies_equipment_transaction` (`stid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
