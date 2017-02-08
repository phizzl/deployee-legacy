CREATE TABLE `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`password` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`remember_token` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `users_email_unique` (`email`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
;
CREATE TABLE `password_resets` (
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`token` VARCHAR(255) NOT NULL COLLATE 'utf8_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	INDEX `password_resets_email_index` (`email`),
	INDEX `password_resets_token_index` (`token`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB
;
