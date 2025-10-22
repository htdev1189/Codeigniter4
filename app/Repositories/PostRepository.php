<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    protected $postModel;
    public function __construct()
    {
        $this->postModel = new Post();
    }

    // Lấy danh sách bài viết kèm tên danh mục
    public function getAllWithCategory($perPage = 10)
    {
        $posts = $this->postModel
            ->select('posts.*, categories.name AS category_name')
            ->join('categories', 'categories.id = posts.category_id', 'left')
            ->withDeleted() // find all
            ->paginate($perPage, 'posts'); // 👈 thêm group 'posts'
        return [
            'posts' => $posts,
            'pager' => $this->postModel->pager
        ];
    }

    public function pager()
    {
        return $this->postModel->pager;
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
        return $this->postModel->withDeleted()->find($id);
    }
    public function findBySlug($slug)
    {
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
    public function restorePost($id)
    {
        $data = [
            'deleted_at' => null,
        ];

        return $this->postModel->update($id, $data);
    }
}
