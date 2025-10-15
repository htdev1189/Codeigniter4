<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParentIdToCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories', [
            'parent_id' => [
                'type'       => 'INT', // kieu interger
                'constraint' => 11, // do dai toi da
                'null'       => true, // co the null
                'unsigned'   => true,
                'after'      => 'id' // tuỳ chọn: thêm sau cột id
            ]
        ]);
        // rang buoc voi bang 
        $this->forge->addForeignKey('parent_id', 'categories', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropColumn('categories', 'parent_id');
    }
}
