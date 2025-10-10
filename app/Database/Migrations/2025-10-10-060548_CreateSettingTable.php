<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', // kiểu
                'auto_increment' => true, // tự động tăng
                'constraint' => 5, // chiều dài
            ],
            'blog_title' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'blog_email' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'blog_phone' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'blog_keywords' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'blog_description' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'blog_logo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'blog_favicon' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            // 👇 Add these fields for automatic timestamps
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // add primary key
        $this->forge->addKey('id', true);
        // create table
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
