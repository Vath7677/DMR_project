<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class AdminUserSeeder extends AbstractSeed {
    public function run(): void {

        $data = [
            [
                'username' => 'admin',
                'email'    => 'admin@gmail.com',
                'password' => 'pass1234', 
                'role'     => 'superadmin'
            ]
        ];

        // បញ្ជាឲ្យ Phinx យកទិន្នន័យខាងលើ ទៅចាក់បញ្ចូលក្នុងតារាង users
        $users = $this->table('users');
        $users->insert($data)
              ->saveData();
    }
}