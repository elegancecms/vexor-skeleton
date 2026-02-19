<?php

declare(strict_types=1);

use Vexor\ORM\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $this->db->statement("
            CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
                `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `email`      VARCHAR(255) NOT NULL,
                `token`      VARCHAR(255) NOT NULL,
                `expires_at` TIMESTAMP NOT NULL,
                `used_at`    TIMESTAMP NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `idx_email` (`email`),
                INDEX `idx_token` (`token`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(): void
    {
        $this->db->statement("DROP TABLE IF EXISTS `password_reset_tokens`");
    }
};
