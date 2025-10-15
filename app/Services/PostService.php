<?php
namespace App\Services;

use App\Repositories\PostRepository;

class PostService{
    protected $PostRepo;

    public function __construct() {
        $this->PostRepo = new PostRepository();
    }
}