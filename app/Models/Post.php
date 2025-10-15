<?php

namespace App\Models;

use CodeIgniter\Model;

class Post extends Model
{
    protected $table            = 'posts';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['title','slug','content','meta_keywords','meta_description','category_id','featured_image','tags','visibility','created_at', 'updated_at'];
    
    protected $useTimestamps = true;
    // protected $useSoftDeletes   = true;
    
}
