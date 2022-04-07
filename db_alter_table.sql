ALTER TABLE `proposals` ADD `offer_guarantee_type` VARCHAR (10) NULL AFTER `offer_guarantee`;
ALTER TABLE `proposals` ADD `same_pro` VARCHAR (100) NULL AFTER `offer_guarantee_type`;
ALTER TABLE `proposals` ADD `deal_value` decimal (18, 2) NULL AFTER `same_pro`; 
ALTER TABLE `proposals` ADD `financial_power` decimal (18, 2) NULL AFTER `deal_value`;
ALTER TABLE `proposals` ADD `receive_office` boolean NULL AFTER `financial_power`;
ALTER TABLE `proposals` ADD `bank_distribute` boolean NULL AFTER `receive_office`;
ALTER TABLE `pro_items` ADD `density` FLOAT (5, 5) NULL AFTER `equivalent`;
ALTER TABLE `pro_items` ADD `density` FLOAT(5, 5) NULL AFTER `equivalent`;
ALTER TABLE `stock_records` ADD `ex_rate_id` INT(10) NULL AFTER `remark`;
ALTER TABLE `pro_data` ADD `discount` INT(10) NULL DEFAULT '0' AFTER `others`;
ALTER TABLE `sales_threes` ADD `destination` VARCHAR(200) NULL AFTER `description`;
ALTER TABLE `sales_fours` ADD `destination` VARCHAR(200) NULL AFTER `description`;
ALTER TABLE `sales_fours` ADD `client_name` VARCHAR(20) NULL AFTER `description`;
ALTER TABLE `sales_fours` ADD `tax` INT(10) NULL AFTER `description`;
ALTER TABLE `notifications` ADD `notif_number` INT(5) NULL AFTER `user_id`;
ALTER TABLE `notifications` ADD `notif_source` VARCHAR(20) NULL AFTER `user_id`;
ALTER TABLE `notifications` ADD `notif_source_id` INT(5) NULL AFTER `user_id`;
ALTER TABLE `proposals_step` ADD `step` VARCHAR(10) NOT NULL AFTER `proposal_id`;

-- Changes
ALTER TABLE `pro_data` CHANGE `transit` `transit` BIGINT (20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `others` `others` BIGINT (20) NOT NULL;
ALTER TABLE `items` CHANGE `equivalent` `equivalent` BIGINT (20) NOT NULL;
ALTER TABLE `projects` CHANGE `project_guarantee` `project_guarantee` BIGINT(20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `total_price` `total_price` BIGINT(20) NOT NULL;
ALTER TABLE `pro_items` CHANGE `ammount` `ammount` BIGINT(20) NOT NULL;
ALTER TABLE `pro_items` CHANGE `unit_price` `unit_price` BIGINT(20) NOT NULL;
ALTER TABLE `pro_items` CHANGE `total_price` `total_price` BIGINT(20) NOT NULL;
ALTER TABLE `pro_items` CHANGE `equivalent` `equivalent` BIGINT(20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `pr_worth` `pr_worth` BIGINT(20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `deposit` `deposit` BIGINT(20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `tax` `tax` BIGINT(20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `transit` `transit` BIGINT(20) NOT NULL;
ALTER TABLE `pro_data` CHANGE `others` `others` BIGINT(20) NOT NULL;
ALTER TABLE `items` CHANGE `equivalent` `equivalent` BIGINT(20) NOT NULL;
ALTER TABLE `stock_records` CHANGE `decrement` `decrement` DECIMAL(18, 2) NULL DEFAULT '0.00';
ALTER TABLE `stock_records` CHANGE `increment` `increment` DECIMAL(18, 2) NULL DEFAULT '0.00';
ALTER TABLE `stock_records` CHANGE `decrement_equiv` `decrement_equiv` DECIMAL(18, 2) NULL DEFAULT '0.00';
ALTER TABLE `stock_records` CHANGE `increment_equiv` `increment_equiv` DECIMAL(18, 2) NULL DEFAULT '0.00';
ALTER TABLE `users` CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `items` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `purchases` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `financial_records` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `sales_fours` CHANGE `steps` `steps` DECIMAL(11) NULL DEFAULT NULL;
ALTER TABLE `sales_threes` CHANGE `steps` `steps` DECIMAL(11) NULL DEFAULT NULL;
ALTER TABLE `sales_twos` CHANGE `steps` `steps` DECIMAL(11) NULL DEFAULT NULL;
ALTER TABLE `sales_ones` CHANGE `steps` `steps` DECIMAL(11) NULL DEFAULT NULL;
ALTER TABLE `fuel_station_storages` CHANGE `capacity` `capacity` INT(20) NULL DEFAULT NULL;

ALTER TABLE `sales_fours` CHANGE `description` `description` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `sales_threes`  CHANGE `description` `description` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `sales_twos`  CHANGE `description` `description` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `sales_ones`  CHANGE `description` `description` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `expenses`  CHANGE `description` `description` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `stock_records`  CHANGE `remark` `remark` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `notifications` CHANGE `gen_date` `gen_date` DATETIME NOT NULL;
ALTER TABLE `notifications` CHANGE `exp_date` `exp_date` DATETIME NOT NULL;
ALTER TABLE `proposals_step` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pro_items` CHANGE `density` `density` FLOAT(20,5) NULL DEFAULT NULL;
ALTER TABLE `pro_data` CHANGE `pr_worth` `pr_worth` BIGINT(20) NULL;
ALTER TABLE `pro_data` CHANGE `reference_no` `reference_no` VARCHAR(20) NULL;
ALTER TABLE `proposals` CHANGE `bidding_address` `bidding_address` VARCHAR(191) NULL;
ALTER TABLE `proposals` CHANGE `publish_address` `publish_address` VARCHAR(191) NULL;

-- New
ALTER TABLE `financial_records` ADD `valid` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`;
ALTER TABLE `archives` CHANGE `note` `note` VARCHAR(191) NULL;
ALTER TABLE `transfers` ADD `destination_id` INT NULL AFTER `destination`;

-- New 2

ALTER TABLE `transactions` ADD `credit_desc` VARCHAR(200) NULL AFTER `description`, ADD `debit_desc` VARCHAR(200) NULL AFTER `credit_desc`;
ALTER TABLE `pro_data` CHANGE `reference_no` `reference_no` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `pro_data` CHANGE `res_person` `res_person` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `sales_fours` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sales_ones` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sales_threes` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `sales_twos` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `purchases` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `transactions` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `inventories` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `financial_records` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `expenses` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `accounts` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `transactions` CHANGE `credit_desc` `credit_desc` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `transactions` CHANGE `debit_desc` `debit_desc` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `permissions` ADD `display_name` VARCHAR(200) NOT NULL AFTER `name`;
ALTER TABLE `permissions` ADD `p_id` INT(11) NULL DEFAULT NULL AFTER `name`;


-- April

ALTER TABLE `proposals_step` CHANGE `res_person` `res_person` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `stock_records` CHANGE `remark` `remark` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;


-- May
ALTER TABLE `purchases` ADD `service_cost` DECIMAL(20) NOT NULL DEFAULT '0' AFTER `user_id`;
ALTER TABLE `expenses` ADD `step` INT(5) NOT NULL DEFAULT '0' AFTER `ammount`;
ALTER TABLE `transactions` ADD `step` INT(5) NOT NULL DEFAULT '0';