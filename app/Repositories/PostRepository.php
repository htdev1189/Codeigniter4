<?php
namespace App\Repositories;

use App\Models\Post;

class PostRepository{
    protected $model;
    public function __construct() {
        $this->model = new Post();
    }
}