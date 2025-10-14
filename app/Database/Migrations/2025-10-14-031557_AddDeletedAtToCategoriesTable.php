<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories',[
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('settings','deleted_at');
    }
}
