<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => password_hash('123456',PASSWORD_BCRYPT)
        ];

        // insert
        $this->db->table('users')->insert($data);
    }
}
