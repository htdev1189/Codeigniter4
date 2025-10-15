<?php
namespace App\Repositories;

use App\Models\Post;

class PostRepository{
    protected $postModel;
    public function __construct() {
        $this->postModel = new Post();
    }

    // Lấy danh sách bài viết kèm tên danh mục
    public function getAllWithCategory()
    {
        return $this->postModel
            ->select('posts.*, categories.name AS category_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->findAll();
    }

    public function create(array $data)
    {
        return $this->postModel->insert($data);
    }

    public function getAll()
    {
        return $this->postModel->findAll();
    }

    public function find($id)
    {
        return $this->postModel->find($id);
    }
    public function findBySlug($slug){
        return $this->postModel->where("slug", $slug)->first();
    }

    public function updatePost($id, array $data)
    {
        return $this->postModel->update($id, $data);
    }

    public function deletePost($id)
    {
        return $this->postModel->delete($id);
    }
}