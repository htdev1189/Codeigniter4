<?php

namespace App\Models;

use CodeIgniter\Model;

class Setting extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = [
        'blog_title',
        'blog_email',
        'blog_phone',
        'blog_keywords',
        'blog_description',
        'blog_logo',
        'blog_favicon'
    ];

    protected $useTimestamps = true; // :) turn on
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
