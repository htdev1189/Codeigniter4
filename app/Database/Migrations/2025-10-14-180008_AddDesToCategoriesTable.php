<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDesToCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories',[
            'seo_des' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'seo_title' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'seo_keyword' => [
                'type' => 'TEXT',
                'null' => true
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['seo_des', 'seo_title', 'seo_keyword']);
    }
}
