<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'slug' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'content' => ['type' => 'TEXT', 'null' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'meta_keywords' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'meta_description' => ['type' => 'TEXT', 'null' => true],
            'tags' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'featured_image' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'visibility' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('posts');
    }

    public function down()
    {
        $this->forge->dropTable('posts');
    }
}
