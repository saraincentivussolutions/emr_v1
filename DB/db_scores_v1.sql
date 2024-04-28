-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table db_emr_1.srt_approved_details
DROP TABLE IF EXISTS `srt_approved_details`;
CREATE TABLE IF NOT EXISTS `srt_approved_details` (
  `approved_details_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_transaction_id` int(10) unsigned DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `retail_status` tinyint(4) DEFAULT NULL,
  `remark_desc` text DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`approved_details_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_bakup_booking_transaction_offer
DROP TABLE IF EXISTS `srt_bakup_booking_transaction_offer`;
CREATE TABLE IF NOT EXISTS `srt_bakup_booking_transaction_offer` (
  `booking_transaction_id` int(10) unsigned DEFAULT NULL,
  `order_no` varchar(50) NOT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` int(11) DEFAULT 0,
  `sales_team` int(11) DEFAULT 0,
  `customer_advisor` int(11) DEFAULT NULL,
  `source_contact` int(11) DEFAULT 0,
  `customer_name` varchar(100) NOT NULL,
  `customer_mobile` varchar(50) NOT NULL,
  `customer_pan` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `nominee_name` varchar(100) NOT NULL,
  `nominee_dob` date DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  `pincode` varchar(100) NOT NULL,
  `corporate_name` varchar(100) NOT NULL,
  `ex_vechicle` tinyint(4) DEFAULT 0,
  `customer_address` varchar(100) NOT NULL,
  `parent_product_line` int(11) DEFAULT NULL,
  `product_line` int(11) DEFAULT NULL,
  `vehicle_type` tinyint(4) DEFAULT 0,
  `product_color_primary` int(11) DEFAULT NULL,
  `product_color_secondary` int(11) DEFAULT NULL,
  `finance` tinyint(4) DEFAULT 0,
  `product_color_additional` int(11) DEFAULT NULL,
  `opportunity_id` varchar(100) NOT NULL,
  `insurance_type` tinyint(4) DEFAULT 0,
  `edd` date NOT NULL,
  `revised_edd` date NOT NULL,
  `insurance_detail` tinyint(4) DEFAULT 0,
  `remarks` varchar(200) NOT NULL,
  `registration_type` tinyint(4) DEFAULT 0,
  `ex_showroom_price` decimal(12,2) DEFAULT NULL,
  `insurance_method` decimal(12,2) DEFAULT NULL,
  `rto_fee` decimal(12,2) DEFAULT NULL,
  `taxi_charges` decimal(12,2) DEFAULT NULL,
  `accessories` decimal(12,2) DEFAULT NULL,
  `amc` decimal(12,2) DEFAULT NULL,
  `ex_price` decimal(12,2) DEFAULT NULL,
  `onroad_price` decimal(12,2) DEFAULT NULL,
  `cosumer_offer` decimal(12,2) DEFAULT NULL,
  `cosumer_offer_srt` decimal(12,2) DEFAULT NULL,
  `corporate_offer` decimal(12,2) DEFAULT NULL,
  `corporate_offer_srt` decimal(12,2) DEFAULT NULL,
  `exchange_offer` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt` decimal(12,2) DEFAULT NULL,
  `access_offer` decimal(12,2) DEFAULT NULL,
  `access_offer_srt` decimal(12,2) DEFAULT NULL,
  `insurance_offer` decimal(12,2) DEFAULT NULL,
  `insurance_offer_srt` decimal(12,2) DEFAULT NULL,
  `add_discount` decimal(12,2) DEFAULT NULL,
  `add_discount_srt` decimal(12,2) DEFAULT NULL,
  `edr` decimal(12,2) DEFAULT NULL,
  `edr_srt` decimal(12,2) DEFAULT NULL,
  `other_offer_desc` varchar(50) DEFAULT NULL,
  `other_contribution` decimal(12,2) DEFAULT NULL,
  `other_contribution_srt` decimal(12,2) DEFAULT NULL,
  `total_tata` decimal(12,2) DEFAULT NULL,
  `total_srt` decimal(12,2) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `off_acc_approved_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No, 3-Send to admin',
  `off_acc_approved_by` varchar(50) DEFAULT NULL,
  `off_acc_approved_date` date DEFAULT NULL,
  `off_acc_approved_logdate` datetime DEFAULT NULL,
  `off_admin_approved_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No',
  `off_admin_approved_by` varchar(50) DEFAULT NULL,
  `off_admin_approved_date` date DEFAULT NULL,
  `off_admin_approved_logdate` datetime DEFAULT NULL,
  `customer_alternate_no` varchar(20) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `nominee_age` tinyint(3) unsigned DEFAULT NULL,
  `corporate_type` tinyint(4) DEFAULT NULL,
  `corporate_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `access_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `insurance_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `add_discount_srt_addition` decimal(12,2) DEFAULT NULL,
  `edr_srt_addition` decimal(12,2) DEFAULT NULL,
  `other_contribution_srt_addition` decimal(12,2) DEFAULT NULL,
  `total_srt_addition` decimal(12,2) DEFAULT NULL,
  `offer_remarks` text DEFAULT NULL,
  `cosumer_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `off_acc_send_to_md` tinyint(4) DEFAULT NULL,
  `off_acc_approved_desc` text DEFAULT NULL,
  `bakup_bk_offer_created_on` datetime DEFAULT NULL,
  `bakup_bk_offer_created_user` int(10) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_bakup_booking_transaction_price
DROP TABLE IF EXISTS `srt_bakup_booking_transaction_price`;
CREATE TABLE IF NOT EXISTS `srt_bakup_booking_transaction_price` (
  `booking_transaction_id` int(10) unsigned DEFAULT NULL,
  `order_no` varchar(50) NOT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` int(11) DEFAULT 0,
  `sales_team` int(11) DEFAULT 0,
  `customer_advisor` int(11) DEFAULT NULL,
  `source_contact` int(11) DEFAULT 0,
  `customer_name` varchar(100) NOT NULL,
  `customer_mobile` varchar(50) NOT NULL,
  `customer_pan` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `nominee_name` varchar(100) NOT NULL,
  `nominee_dob` date DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  `pincode` varchar(100) NOT NULL,
  `corporate_name` varchar(100) NOT NULL,
  `ex_vechicle` tinyint(4) DEFAULT 0,
  `customer_address` varchar(100) NOT NULL,
  `parent_product_line` int(11) DEFAULT NULL,
  `product_line` int(11) DEFAULT NULL,
  `vehicle_type` tinyint(4) DEFAULT 0,
  `product_color_primary` int(11) DEFAULT NULL,
  `product_color_secondary` int(11) DEFAULT NULL,
  `finance` tinyint(4) DEFAULT 0,
  `product_color_additional` int(11) DEFAULT NULL,
  `opportunity_id` varchar(100) NOT NULL,
  `insurance_type` tinyint(4) DEFAULT 0,
  `edd` date NOT NULL,
  `revised_edd` date NOT NULL,
  `insurance_detail` tinyint(4) DEFAULT 0,
  `remarks` varchar(200) NOT NULL,
  `registration_type` tinyint(4) DEFAULT 0,
  `ex_showroom_price` decimal(12,2) DEFAULT NULL,
  `insurance_method` decimal(12,2) DEFAULT NULL,
  `rto_fee` decimal(12,2) DEFAULT NULL,
  `taxi_charges` decimal(12,2) DEFAULT NULL,
  `accessories` decimal(12,2) DEFAULT NULL,
  `amc` decimal(12,2) DEFAULT NULL,
  `ex_price` decimal(12,2) DEFAULT NULL,
  `onroad_price` decimal(12,2) DEFAULT NULL,
  `cosumer_offer` decimal(12,2) DEFAULT NULL,
  `cosumer_offer_srt` decimal(12,2) DEFAULT NULL,
  `corporate_offer` decimal(12,2) DEFAULT NULL,
  `corporate_offer_srt` decimal(12,2) DEFAULT NULL,
  `exchange_offer` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt` decimal(12,2) DEFAULT NULL,
  `access_offer` decimal(12,2) DEFAULT NULL,
  `access_offer_srt` decimal(12,2) DEFAULT NULL,
  `insurance_offer` decimal(12,2) DEFAULT NULL,
  `insurance_offer_srt` decimal(12,2) DEFAULT NULL,
  `add_discount` decimal(12,2) DEFAULT NULL,
  `add_discount_srt` decimal(12,2) DEFAULT NULL,
  `edr` decimal(12,2) DEFAULT NULL,
  `edr_srt` decimal(12,2) DEFAULT NULL,
  `other_offer_desc` varchar(50) DEFAULT NULL,
  `other_contribution` decimal(12,2) DEFAULT NULL,
  `other_contribution_srt` decimal(12,2) DEFAULT NULL,
  `total_tata` decimal(12,2) DEFAULT NULL,
  `total_srt` decimal(12,2) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `off_acc_approved_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No, 3-Send to admin',
  `off_acc_approved_by` varchar(50) DEFAULT NULL,
  `off_acc_approved_date` date DEFAULT NULL,
  `off_acc_approved_logdate` datetime DEFAULT NULL,
  `off_admin_approved_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No',
  `off_admin_approved_by` varchar(50) DEFAULT NULL,
  `off_admin_approved_date` date DEFAULT NULL,
  `off_admin_approved_logdate` datetime DEFAULT NULL,
  `customer_alternate_no` varchar(20) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `nominee_age` tinyint(3) unsigned DEFAULT NULL,
  `corporate_type` tinyint(4) DEFAULT NULL,
  `corporate_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `access_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `insurance_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `add_discount_srt_addition` decimal(12,2) DEFAULT NULL,
  `edr_srt_addition` decimal(12,2) DEFAULT NULL,
  `other_contribution_srt_addition` decimal(12,2) DEFAULT NULL,
  `total_srt_addition` decimal(12,2) DEFAULT NULL,
  `offer_remarks` text DEFAULT NULL,
  `cosumer_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `off_acc_send_to_md` tinyint(4) DEFAULT NULL,
  `off_acc_approved_desc` text DEFAULT NULL,
  `bakup_bk_price_created_on` datetime DEFAULT NULL,
  `bakup_bk_price_created_user` int(10) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_booking_transaction
DROP TABLE IF EXISTS `srt_booking_transaction`;
CREATE TABLE IF NOT EXISTS `srt_booking_transaction` (
  `booking_transaction_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(50) NOT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` int(11) DEFAULT 0,
  `sales_team` int(11) DEFAULT 0,
  `customer_advisor` int(11) DEFAULT NULL,
  `source_contact` int(11) DEFAULT 0,
  `customer_name` varchar(100) NOT NULL,
  `customer_mobile` varchar(50) NOT NULL,
  `customer_pan` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `nominee_name` varchar(100) NOT NULL,
  `nominee_dob` date DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  `pincode` varchar(100) NOT NULL,
  `corporate_name` varchar(100) NOT NULL,
  `ex_vechicle` tinyint(4) DEFAULT 0,
  `customer_address` varchar(100) NOT NULL,
  `parent_product_line` int(11) DEFAULT NULL,
  `product_line` int(11) DEFAULT NULL,
  `vehicle_type` tinyint(4) DEFAULT 0,
  `product_color_primary` int(11) DEFAULT NULL,
  `product_color_secondary` int(11) DEFAULT NULL,
  `finance` tinyint(4) DEFAULT 0,
  `product_color_additional` int(11) DEFAULT NULL,
  `opportunity_id` varchar(100) NOT NULL,
  `insurance_type` tinyint(4) DEFAULT 0,
  `edd` date NOT NULL,
  `revised_edd` date NOT NULL,
  `insurance_detail` tinyint(4) DEFAULT 0,
  `remarks` varchar(200) NOT NULL,
  `registration_type` tinyint(4) DEFAULT 0,
  `ex_showroom_price` decimal(12,2) DEFAULT NULL,
  `insurance_method` decimal(12,2) DEFAULT NULL,
  `rto_fee` decimal(12,2) DEFAULT NULL,
  `taxi_charges` decimal(12,2) DEFAULT NULL,
  `accessories` decimal(12,2) DEFAULT NULL,
  `amc` decimal(12,2) DEFAULT NULL,
  `ex_price` decimal(12,2) DEFAULT NULL,
  `onroad_price` decimal(12,2) DEFAULT NULL,
  `cosumer_offer` decimal(12,2) DEFAULT NULL,
  `cosumer_offer_srt` decimal(12,2) DEFAULT NULL,
  `corporate_offer` decimal(12,2) DEFAULT NULL,
  `corporate_offer_srt` decimal(12,2) DEFAULT NULL,
  `exchange_offer` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt` decimal(12,2) DEFAULT NULL,
  `access_offer` decimal(12,2) DEFAULT NULL,
  `access_offer_srt` decimal(12,2) DEFAULT NULL,
  `insurance_offer` decimal(12,2) DEFAULT NULL,
  `insurance_offer_srt` decimal(12,2) DEFAULT NULL,
  `add_discount` decimal(12,2) DEFAULT NULL,
  `add_discount_srt` decimal(12,2) DEFAULT NULL,
  `edr` decimal(12,2) DEFAULT NULL,
  `edr_srt` decimal(12,2) DEFAULT NULL,
  `other_offer_desc` varchar(50) DEFAULT NULL,
  `other_contribution` decimal(12,2) DEFAULT NULL,
  `other_contribution_srt` decimal(12,2) DEFAULT NULL,
  `total_tata` decimal(12,2) DEFAULT NULL,
  `total_srt` decimal(12,2) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `off_acc_approved_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No, 3-Send to admin',
  `off_acc_approved_by` varchar(50) DEFAULT NULL,
  `off_acc_approved_date` date DEFAULT NULL,
  `off_acc_approved_logdate` datetime DEFAULT NULL,
  `off_admin_approved_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No',
  `off_admin_approved_by` varchar(50) DEFAULT NULL,
  `off_admin_approved_date` date DEFAULT NULL,
  `off_admin_approved_logdate` datetime DEFAULT NULL,
  `customer_alternate_no` varchar(20) DEFAULT NULL,
  `customer_email` varchar(50) DEFAULT NULL,
  `nominee_age` tinyint(3) unsigned DEFAULT NULL,
  `corporate_type` tinyint(4) DEFAULT NULL,
  `cosumer_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `corporate_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `access_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `insurance_offer_srt_addition` decimal(12,2) DEFAULT NULL,
  `add_discount_srt_addition` decimal(12,2) DEFAULT NULL,
  `edr_srt_addition` decimal(12,2) DEFAULT NULL,
  `other_contribution_srt_addition` decimal(12,2) DEFAULT NULL,
  `total_srt_addition` decimal(12,2) DEFAULT NULL,
  `offer_remarks` text DEFAULT NULL,
  `off_acc_send_to_md` tinyint(4) DEFAULT NULL,
  `off_acc_approved_desc` text DEFAULT NULL,
  PRIMARY KEY (`booking_transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=180 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_employee_master
DROP TABLE IF EXISTS `srt_employee_master`;
CREATE TABLE IF NOT EXISTS `srt_employee_master` (
  `employee_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(20) NOT NULL,
  `employee_name` varchar(50) DEFAULT NULL,
  `employee_mobile` varchar(20) DEFAULT NULL,
  `sales_team_id` int(10) unsigned DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `employee_address` text DEFAULT NULL,
  `employee_email` varchar(50) DEFAULT NULL,
  `user_access` text DEFAULT NULL COMMENT 'to be stored in json',
  `super_user` tinyint(4) DEFAULT 0,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `sales_team_access_ids` text DEFAULT NULL,
  `user_role_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_finance_transaction
DROP TABLE IF EXISTS `srt_finance_transaction`;
CREATE TABLE IF NOT EXISTS `srt_finance_transaction` (
  `finance_transaction_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_transaction_id` int(10) unsigned DEFAULT NULL,
  `financier_id` int(10) unsigned DEFAULT NULL,
  `finance_amount` decimal(12,2) DEFAULT NULL,
  `followed_by` varchar(30) DEFAULT NULL,
  `kyc_date` date DEFAULT NULL,
  `expected_do_date` date DEFAULT NULL,
  `login_date` date DEFAULT NULL,
  `approval_status` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-no',
  `document_date` date DEFAULT NULL,
  `mmr_status` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-no',
  `do_date` date DEFAULT NULL,
  `do_approved` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-no',
  `remark_desc` text DEFAULT NULL,
  `finance_process_status` tinyint(4) DEFAULT 0 COMMENT '1-KYC, 2-Expected DO, 3-Login, 4-Approve, 5-Document, 6-MMR, 7-DO, 8-DO approved',
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `kyc_notes` text DEFAULT NULL,
  `login_notes` text DEFAULT NULL,
  `document_notes` text DEFAULT NULL,
  `do_notes` text DEFAULT NULL,
  `first_followup_date` date DEFAULT NULL,
  `second_followup_date` date DEFAULT NULL,
  `third_followup_date` date DEFAULT NULL,
  `fourth_followup_date` date DEFAULT NULL,
  `next_followup_date1` date DEFAULT NULL,
  `next_followup_date2` date DEFAULT NULL,
  `next_followup_date3` date DEFAULT NULL,
  `stage_of_comments` text DEFAULT NULL,
  PRIMARY KEY (`finance_transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_financier_master
DROP TABLE IF EXISTS `srt_financier_master`;
CREATE TABLE IF NOT EXISTS `srt_financier_master` (
  `financier_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `financier_name` varchar(50) DEFAULT NULL,
  `financier_contact_name` varchar(50) DEFAULT NULL,
  `financier_contact_mobile` varchar(20) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`financier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_login_master
DROP TABLE IF EXISTS `srt_login_master`;
CREATE TABLE IF NOT EXISTS `srt_login_master` (
  `login_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login_user_name` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_access` text DEFAULT NULL COMMENT 'to be stored in json',
  `super_user` tinyint(4) DEFAULT 0,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `sales_team_access_ids` text DEFAULT NULL,
  `user_role_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`login_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_messages_master
DROP TABLE IF EXISTS `srt_messages_master`;
CREATE TABLE IF NOT EXISTS `srt_messages_master` (
  `messages_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `messages_text` text DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`messages_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_modules
DROP TABLE IF EXISTS `srt_modules`;
CREATE TABLE IF NOT EXISTS `srt_modules` (
  `module_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `module_status` tinyint(1) DEFAULT 0,
  `module_order` int(11) DEFAULT 999,
  `module_actions` varchar(20) DEFAULT '1',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_offer_list_master
DROP TABLE IF EXISTS `srt_offer_list_master`;
CREATE TABLE IF NOT EXISTS `srt_offer_list_master` (
  `offer_list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_productline_id` int(10) unsigned DEFAULT NULL,
  `productline_id` text DEFAULT NULL,
  `vechile_type` tinyint(4) DEFAULT NULL COMMENT '1-Own board, 2- Taxi',
  `offer_date` date DEFAULT NULL,
  `cash_offer_tata` decimal(12,2) DEFAULT NULL,
  `cash_offer_srt` decimal(12,2) DEFAULT NULL,
  `exchange_offer_tata` decimal(12,2) DEFAULT NULL,
  `exchange_offer_srt` decimal(12,2) DEFAULT NULL,
  `corporate_offer_tata` decimal(12,2) DEFAULT NULL,
  `corporate_offer_srt` decimal(12,2) DEFAULT NULL,
  `edr_offer_tata` decimal(12,2) DEFAULT NULL,
  `edr_offer_srt` decimal(12,2) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `product_colour_ids` text DEFAULT NULL,
  `registration_type` tinyint(1) DEFAULT NULL COMMENT '1-permonent, 2-temporary',
  PRIMARY KEY (`offer_list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_orderstatus_master
DROP TABLE IF EXISTS `srt_orderstatus_master`;
CREATE TABLE IF NOT EXISTS `srt_orderstatus_master` (
  `orderstatus_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderstatus_name` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`orderstatus_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_parent_productline_master
DROP TABLE IF EXISTS `srt_parent_productline_master`;
CREATE TABLE IF NOT EXISTS `srt_parent_productline_master` (
  `parent_productline_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_productline_name` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`parent_productline_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_price_list_master
DROP TABLE IF EXISTS `srt_price_list_master`;
CREATE TABLE IF NOT EXISTS `srt_price_list_master` (
  `price_list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_productline_id` int(10) unsigned DEFAULT NULL,
  `productline_id` int(10) unsigned DEFAULT NULL,
  `vechile_type` tinyint(4) DEFAULT NULL COMMENT '1-Own board, 2- Taxi',
  `price_date` date DEFAULT NULL,
  `ex_showroom_amount` decimal(12,2) DEFAULT NULL,
  `insurance_amount` decimal(12,2) DEFAULT NULL,
  `taxi_chg_amount` decimal(12,2) DEFAULT NULL,
  `accessories_amount` decimal(12,2) DEFAULT NULL,
  `tax_amount` decimal(12,2) DEFAULT NULL,
  `ew_amount` decimal(12,2) DEFAULT NULL,
  `nill_depriciation_amount` decimal(12,2) DEFAULT NULL,
  `cc_amount` decimal(12,2) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `onroad_amount` decimal(12,2) DEFAULT NULL,
  `onroad_nill_amount` decimal(12,2) DEFAULT NULL,
  `product_colour_ids` text DEFAULT NULL,
  `registration_type` tinyint(1) DEFAULT NULL COMMENT '1-permonent, 2-temporary',
  PRIMARY KEY (`price_list_id`)
) ENGINE=MyISAM AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_productcolour_master
DROP TABLE IF EXISTS `srt_productcolour_master`;
CREATE TABLE IF NOT EXISTS `srt_productcolour_master` (
  `productcolour_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_productline_ids` text NOT NULL,
  `productcolour_name` varchar(50) DEFAULT NULL,
  `productcolour_vc` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`productcolour_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_productline_master
DROP TABLE IF EXISTS `srt_productline_master`;
CREATE TABLE IF NOT EXISTS `srt_productline_master` (
  `productline_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_productline_id` int(10) unsigned NOT NULL,
  `productline_name` varchar(50) DEFAULT NULL,
  `productline_vc` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`productline_id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_receipts_transaction
DROP TABLE IF EXISTS `srt_receipts_transaction`;
CREATE TABLE IF NOT EXISTS `srt_receipts_transaction` (
  `receipt_transaction_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_transaction_id` int(11) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_by` varchar(50) DEFAULT NULL,
  `receipt_no` varchar(100) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `payment_mode` tinyint(1) DEFAULT NULL COMMENT '1-Cash, 2-Bank',
  `receipt_amount` decimal(12,2) DEFAULT NULL,
  `receipt_remarks` varchar(150) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `amount_reveived_status` tinyint(4) DEFAULT 0,
  `chque_dd_type` tinyint(4) DEFAULT 0 COMMENT '1-Cheque,2-DD',
  `bank_name` varchar(50) DEFAULT NULL,
  `cheque_no` varchar(20) DEFAULT NULL,
  `bank_recons_entry_status` tinyint(4) DEFAULT 0 COMMENT '1-Yes, 2-No',
  `bank_recons_entry_date` date DEFAULT NULL,
  `bank_recons_entry_by` varchar(50) DEFAULT NULL,
  `veh_ex_bookid` int(10) unsigned DEFAULT 0,
  `bank_recons_reason_type` tinyint(4) DEFAULT NULL,
  `bank_recons_remarks` text DEFAULT NULL,
  `finance_transaction_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`receipt_transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=238 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_retail
DROP TABLE IF EXISTS `srt_retail`;
CREATE TABLE IF NOT EXISTS `srt_retail` (
  `retail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_transaction_id` int(10) unsigned DEFAULT NULL,
  `payment_received` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-No',
  `vehicle_allotted` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-No',
  `stock_type` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-No',
  `invoice_no` varchar(30) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `rto_approved` tinyint(4) DEFAULT NULL COMMENT '1-Yes, 2-No',
  `rto_date` date DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `stock_status` tinyint(4) DEFAULT 0,
  `stock_chasis_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`retail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_sales_team_master
DROP TABLE IF EXISTS `srt_sales_team_master`;
CREATE TABLE IF NOT EXISTS `srt_sales_team_master` (
  `sales_team_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sales_team_name` varchar(50) DEFAULT NULL,
  `sales_team_description` text DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`sales_team_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_source_of_contact_master
DROP TABLE IF EXISTS `srt_source_of_contact_master`;
CREATE TABLE IF NOT EXISTS `srt_source_of_contact_master` (
  `source_of_contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_of_contact_name` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`source_of_contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_stock_master_entry
DROP TABLE IF EXISTS `srt_stock_master_entry`;
CREATE TABLE IF NOT EXISTS `srt_stock_master_entry` (
  `stock_master_entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stock_master_entry_date` date DEFAULT NULL,
  `parent_productline_id` int(10) unsigned DEFAULT NULL,
  `productline_id` int(10) unsigned DEFAULT NULL,
  `productcolour_id` int(10) unsigned DEFAULT NULL,
  `chasis_no` varchar(50) DEFAULT NULL,
  `purchase_cost` decimal(12,2) DEFAULT NULL,
  `stock_type` tinyint(4) DEFAULT NULL COMMENT '1-Open stock, 2- G Stock',
  `is_imported` tinyint(4) DEFAULT 0 COMMENT '1-Imported',
  `stock_imported_id` int(10) unsigned DEFAULT NULL,
  `insert_unique_ref` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `stock_chasis_used` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`stock_master_entry_id`)
) ENGINE=MyISAM AUTO_INCREMENT=209 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_stock_master_importlog
DROP TABLE IF EXISTS `srt_stock_master_importlog`;
CREATE TABLE IF NOT EXISTS `srt_stock_master_importlog` (
  `stock_imported_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `import_date` datetime DEFAULT NULL,
  `import_file` varchar(100) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`stock_imported_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_sub_modules
DROP TABLE IF EXISTS `srt_sub_modules`;
CREATE TABLE IF NOT EXISTS `srt_sub_modules` (
  `sub_module_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `sub_module_name` varchar(100) DEFAULT NULL,
  `sub_module_status` tinyint(1) DEFAULT 0,
  `sub_module_order` int(11) DEFAULT 999,
  `sub_module_actions` varchar(20) DEFAULT NULL COMMENT '1-view, 2-add, 3-edit, 4-delete, 5-print',
  `sub_module_call_js` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sub_module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_user_role_master
DROP TABLE IF EXISTS `srt_user_role_master`;
CREATE TABLE IF NOT EXISTS `srt_user_role_master` (
  `user_role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_role_name` varchar(50) DEFAULT NULL,
  `active_status` tinyint(4) DEFAULT 1,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `extra_json_elements` text DEFAULT NULL,
  PRIMARY KEY (`user_role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_user_role_modules
DROP TABLE IF EXISTS `srt_user_role_modules`;
CREATE TABLE IF NOT EXISTS `srt_user_role_modules` (
  `user_role_module_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sub_module_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `module_type` tinyint(1) DEFAULT NULL COMMENT '1-parent, 2-child',
  `module_actions` varchar(20) DEFAULT NULL COMMENT '1-view, 2-addcomment , 3-edit, 4-delete, 5-print',
  `user_role_id` int(11) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_role_module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_emr_1.srt_vehicle_exchange
DROP TABLE IF EXISTS `srt_vehicle_exchange`;
CREATE TABLE IF NOT EXISTS `srt_vehicle_exchange` (
  `vehicle_exchange_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `booking_transaction_id` int(10) unsigned DEFAULT NULL,
  `exchange_model` varchar(30) DEFAULT NULL,
  `manufacture_year` smallint(6) DEFAULT NULL,
  `numberof_owners` tinyint(4) DEFAULT NULL,
  `running_km` double DEFAULT NULL,
  `registration_number` varchar(20) DEFAULT NULL,
  `exchange_price` decimal(12,2) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `createdby` int(10) unsigned DEFAULT NULL,
  `lastmodifiedon` datetime DEFAULT NULL,
  `lastmodifiedby` int(10) unsigned DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `deleted_date` datetime DEFAULT NULL,
  `deleted_user` int(10) unsigned DEFAULT NULL,
  `finance_previous_status` tinyint(4) DEFAULT 0 COMMENT '1-yes, 2-No',
  `finance_previous_financier` int(10) unsigned DEFAULT NULL,
  `finance_previous_loanamnt` decimal(12,2) DEFAULT NULL,
  `chklist_available` text DEFAULT NULL,
  `exchange_type` tinyint(1) DEFAULT NULL COMMENT '1-claim,2-actual',
  `scheme_bonus_tata` decimal(12,2) DEFAULT NULL,
  `scheme_bonus_srt` decimal(12,2) DEFAULT NULL,
  `actual_paid_tata` decimal(12,2) DEFAULT NULL,
  `actual_paid_srt` decimal(12,2) DEFAULT NULL,
  `actual_value` decimal(12,2) DEFAULT NULL,
  `owner_different` tinyint(1) DEFAULT NULL COMMENT '1-yes,2-no',
  `owner_name` varchar(30) DEFAULT NULL,
  `owner_relationship` tinyint(2) DEFAULT NULL,
  `proff_collected` tinyint(1) DEFAULT 0,
  `entered_exchange_price` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`vehicle_exchange_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
