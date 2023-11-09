CREATE TABLE `teratomo_cebuana`.`client_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cardnumber` VARCHAR(255) NULL,
  `firstname` VARCHAR(255) NULL,
  `lastname` VARCHAR(255) NULL,
  `mobilenumber` VARCHAR(255) NULL,
  `birthday` DATE NULL,
  `connection` VARCHAR(45) NULL,
  `voucher_code` VARCHAR(45) NULL,
  `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`));

ALTER TABLE `teratomo_cebuana`.`client_logs` 
CHANGE COLUMN `birthday` `birthday` VARCHAR(255) NULL DEFAULT NULL ;

ALTER TABLE `teratomo_cebuana`.`client_logs` 
CHANGE COLUMN `connection` `is_successful` TINYINT(1) NULL DEFAULT '0' ;

ALTER TABLE `teratomo_cebuana`.`client_logs` 
ADD COLUMN `is_deleted` TINYINT(1) NULL DEFAULT '0' AFTER `created_at`;