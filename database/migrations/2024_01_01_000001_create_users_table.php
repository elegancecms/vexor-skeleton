<?php

declare(strict_types=1);

use Vexor\Core\ORM\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $this->db->statement("
            CREATE TABLE IF NOT EXISTS `users` (
                `id`                     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`                   VARCHAR(100) NOT NULL,
                `email`                  VARCHAR(255) NOT NULL UNIQUE,
                `password`               VARCHAR(255) NOT NULL,
                `role`                   ENUM('user','admin','moderator') NOT NULL DEFAULT 'user',
                `api_key`                VARCHAR(255) NULL UNIQUE,
                `remember_token`         VARCHAR(100) NULL,
                `two_factor_secret`      VARCHAR(255) NULL,
                `two_factor_enabled`     TINYINT(1) NOT NULL DEFAULT 0,
                `password_reset_token`   VARCHAR(255) NULL,
                `password_reset_expiry`  INT UNSIGNED NULL,
                `email_verified_at`      TIMESTAMP NULL,
                `last_login_at`          TIMESTAMP NULL,
                `last_login_ip`          VARCHAR(45) NULL,
                `is_active`              TINYINT(1) NOT NULL DEFAULT 1,
                `created_at`             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `idx_email` (`email`),
                INDEX `idx_role`  (`role`),
                INDEX `idx_api_key` (`api_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        $this->db->statement("DROP TABLE IF EXISTS `users`");
    }
};
