<?php

declare(strict_types=1);

use Vexor\ORM\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $this->db->statement("
            CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
                `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id`     BIGINT UNSIGNED NOT NULL,
                `name`        VARCHAR(100) NOT NULL,
                `token`       VARCHAR(255) NOT NULL UNIQUE,
                `abilities`   JSON NULL,
                `last_used_at` TIMESTAMP NULL,
                `expires_at`  TIMESTAMP NULL,
                `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `idx_user_id` (`user_id`),
                INDEX `idx_token`   (`token`),
                CONSTRAINT `fk_pat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        $this->db->statement("DROP TABLE IF EXISTS `personal_access_tokens`");
    }
};
