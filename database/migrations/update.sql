ALTER TABLE `users` ADD `building_number` VARCHAR(255) NULL DEFAULT NULL AFTER `floor_work`, ADD `building_number_work` VARCHAR(255) NULL DEFAULT NULL AFTER `building_number`;
INSERT INTO `admin_menu` (`id`, `menuTitleEn`, `menuTitleAr`, `menuLink`, `menuIco`, `menu_id`, `ordering`, `visible`) VALUES (NULL, 'Delivery', 'Delivery', 'delivery_type', 'location-arrow', '64', '91', '1');
CREATE TABLE `delivery_type` ( `id` INT NOT NULL AUTO_INCREMENT , `type_en` VARCHAR(255) NULL DEFAULT NULL , `type_ar` VARCHAR(255) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `users` ADD `delivery_type` INT NULL DEFAULT NULL AFTER `sex`;
ALTER TABLE `users` ADD FOREIGN KEY (`delivery_type`) REFERENCES `delivery_type`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
INSERT INTO `admin_menu` (`id`, `menuTitleEn`, `menuTitleAr`, `menuLink`, `menuIco`, `menu_id`, `ordering`, `visible`) VALUES (NULL, 'Upcoming Birthdays', 'أعياد الميلاد القادمة', 'users/birthdays-upcoming', 'birthday-cake', '0', '150', '1');

