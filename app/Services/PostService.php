<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\PostRepository;

class PostService
{
    protected $PostRepo;

    public function __construct()
    {
        $this->PostRepo = new PostRepository();
    }
    public function create(array $data)
    {
        // get data
        $title = $data['title'];
        $slug = slugify($title);
        $content = $data['content'];
        $meta_keywords = $data['meta_keywords'];
        $meta_description = $data['meta_description'];
        $category_id = $data['category_id'];
        $tags = $data['tags'];
        $visibility = $data['visibility'];



        // su dung exception
        $errors = [];

        // check slug
        $exists = $this->PostRepo->findBySlug($slug);
        if ($exists) {
            // mac dinh minh se dung cai nay
            // throw new \Exception('Slug đã tồn tại, vui lòng nhập tên khác.');
            $errors['title'] = 'Slug đã tồn tại, vui lòng nhập tên khác.';
        }


        // xu ly upload hinh anh
        $file = $data['featured_image'];
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // dd($file->getClientExtension());
            // validate file --- 
            $allowed = ['jpg', 'jpeg', 'webp', 'png'];
            if (!in_array($file->getClientExtension(), $allowed)) {
                $errors['file'] = 'Định dạng file không hợp lệ, vui lòng nhập tên khác.';
            } else {

                // Lấy tên gốc của file (ví dụ: ảnh Việt Nam đẹp.png)
                $originalName = $file->getClientName();
                $ext = $file->getClientExtension();

                // Chuẩn hóa tên file: loại bỏ dấu và ký tự đặc biệt
                $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                $baseName = normalizeFilename($baseName); // helper

                // Gộp lại với tên ngẫu nhiên tránh trùng
                $newName = $baseName . '_' . $file->getRandomName();

                // $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/posts', $newName);
                $data['featured_image'] = $newName;
            }
        } else {
            $data['featured_image'] = null;
        }

        // dd($errors);

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }


        // Lưu dữ liệu
        // add slug vao da
        $data['slug'] = $slug;
        return $this->PostRepo->create($data);
    }
    public function getAll($perPage = 10)
    {

        $result = $this->PostRepo->getAllWithCategory($perPage);
        $posts = $result['posts'];

        // xử lý thêm nếu cần
        foreach ($posts as &$post) {
            if (empty($post['category_name'])) {
                $post['category_name'] = 'Chưa phân loại';
            }
        }
        return $result; // trả cả posts + pager
    }

    public function pager()
    {
        return $this->PostRepo->pager();
    }

    public function findByID($id)
    {
        return $this->PostRepo->find($id);
    }
    public function findBySlug($slug)
    {
        return $this->PostRepo->findBySlug($slug);
    }

    // update post
    public function update($id, $data)
    {
        $old = $this->PostRepo->find($id);


        $errors = [];

        // check slug
        $slug = slugify($data['title']);
        if (! empty($old) && $slug == $old['slug'] && $id != $old['id']) {
            $errors['title'] = 'Slug da ton tai, vui long thay doi title';
        }

        // check featured_image       
        $file = $data['featured_image'];
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg', 'jpeg', 'webp'];
            if (!in_array($file->getClientExtension(), $allowed)) {
                $errors['file'] = 'Định dạng file không hợp lệ, vui lòng nhập tên khác.';
            } else {

                // Lấy tên gốc của file (ví dụ: ảnh Việt Nam đẹp.png)
                $originalName = $file->getClientName();
                $ext = $file->getClientExtension();

                // Chuẩn hóa tên file: loại bỏ dấu và ký tự đặc biệt
                $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                $baseName = normalizeFilename($baseName); // helper

                // Gộp lại với tên ngẫu nhiên tránh trùng
                $newName = $baseName . '_' . $file->getRandomName();

                // $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/posts', $newName);
                $data['featured_image'] = $newName;
            }
        } else {
            $data['featured_image'] = $old['featured_image'];
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }


        // update data
        $data['slug'] = $slug;
        return $this->PostRepo->updatePost($id, $data);
    }

    public function deletePost($id)
    {
        $this->PostRepo->deletePost($id);
    }
    public function restorePost($id)
    {
        $this->PostRepo->restorePost($id);
    }

    // tags
    public function getByTag($tag){
        return $this->PostRepo->getByTag($tag);
    }
}
