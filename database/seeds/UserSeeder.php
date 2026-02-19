<?php

declare(strict_types=1);

use Vexor\ORM\Seeder;

/**
 * UserSeeder — Varsayılan admin kullanıcısı oluşturur.
 * 
 * Çalıştır: php vexor db:seed
 */
return new class extends Seeder
{
    public function run(): void
    {
        $exists = $this->db->table('users')->where('email', 'admin@example.com')->exists();

        if ($exists) {
            $this->info('Admin user already exists, skipping.');
            return;
        }

        $this->db->table('users')->insert([
            'name'              => 'Admin',
            'email'             => 'admin@example.com',
            'password'          => password_hash('password', PASSWORD_ARGON2ID),
            'role'              => 'admin',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_active'         => 1,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        $this->success('Admin user created → admin@example.com / password');
    }
};
