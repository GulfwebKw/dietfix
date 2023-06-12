ALTER TABLE `users` ADD `building_number` VARCHAR(255) NULL DEFAULT NULL AFTER `floor_work`, ADD `building_number_work` VARCHAR(255) NULL DEFAULT NULL AFTER `building_number`;
INSERT INTO `admin_menu` (`id`, `menuTitleEn`, `menuTitleAr`, `menuLink`, `menuIco`, `menu_id`, `ordering`, `visible`) VALUES (NULL, 'Delivery', 'Delivery', 'delivery_type', 'location-arrow', '64', '91', '1');
CREATE TABLE `delivery_type` ( `id` INT NOT NULL AUTO_INCREMENT , `type_en` VARCHAR(255) NULL DEFAULT NULL , `type_ar` VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `users` ADD `delivery_type` INT NULL DEFAULT NULL AFTER `sex`;
ALTER TABLE `users` ADD FOREIGN KEY (`delivery_type`) REFERENCES `delivery_type`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
INSERT INTO `admin_menu` (`id`, `menuTitleEn`, `menuTitleAr`, `menuLink`, `menuIco`, `menu_id`, `ordering`, `visible`) VALUES (NULL, 'Upcoming Birthdays', 'أعياد الميلاد القادمة', 'users/birthdays-upcoming', 'birthday-cake', '0', '150', '1');

INSERT INTO `settings` (`id`, `key`, `value`, `help`) VALUES (NULL, 'printLabelProduction', '1', ''), (NULL, 'printLabelExpiry', '15', '');

INSERT INTO `admin_menu` (`id`, `menuTitleEn`, `menuTitleAr`, `menuLink`, `menuIco`, `menu_id`, `ordering`, `visible`) VALUES (NULL, 'Not Active Users', 'العملاء', 'users/notActive', 'user', '0', '150', '1');

ALTER TABLE `discounts` ADD `package` INT NULL DEFAULT NULL AFTER `count_limit_user`, ADD `package_duration` INT NULL DEFAULT NULL AFTER `package`;
CREATE TABLE `cancel_freeze_day` ( `user_id` INT NOT NULL , `resume_at` TIMESTAMP NULL DEFAULT NULL , UNIQUE (`user_id`)) ENGINE = InnoDB;
ALTER TABLE `cancel_freeze_day` ADD `isFreezed` BOOLEAN NOT NULL DEFAULT FALSE AFTER `resume_at`, ADD `isAutoUnFreezed` BOOLEAN NOT NULL DEFAULT FALSE AFTER `isFreezed`, ADD `freezed_starting_date` TIMESTAMP NULL DEFAULT NULL AFTER `isAutoUnFreezed`;
ALTER TABLE `cancel_freeze_day` CHANGE `resume_at` `freezed_ending_date` TIMESTAMP NULL DEFAULT NULL;


--  ===================================================================
ALTER TABLE `users` ADD `lastDeviceCode` VARCHAR(65) NULL DEFAULT NULL AFTER `dob`;
