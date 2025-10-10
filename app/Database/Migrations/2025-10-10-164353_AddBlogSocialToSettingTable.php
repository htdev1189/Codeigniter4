<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBlogSocialToSettingTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('settings',[
            'blog_social' => [
                'type' => 'TEXT',
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('settings','blog_social');
    }
}
