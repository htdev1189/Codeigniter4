<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'slug', 'deleted_at' ]; // them deleted_at thi moi update dc

    protected $useTimestamps = true; // tu dong them created_at và updated_at khi sử dụng Model insert hoặc update

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
}
