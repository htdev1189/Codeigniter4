<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => 'hoang tuan',
            'email' => 'htuan1189@gmail.com',
            'username' => 'htuan1189',
            'password' => password_hash('123456',PASSWORD_BCRYPT)
        ];

        // insert
        $this->db->table('users')->insert($data);
    }
}
