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
        $category_id = $data['category'];
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
        if ($file && $file->isValid()) {
            // dd($file->getClientExtension());
            // validate file --- 
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
            $data['featured_image'] = null;
        }

        // dd($errors);

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }


        // Lưu dữ liệu
        // add slug vao da
        $data['slug'] = $slug;
        $data['category_id'] = $category_id;
        return $this->PostRepo->create($data);
    }
    public function getAll()
    {
        $posts = $this->PostRepo->getAllWithCategory();

        // Có thể xử lý thêm logic tại đây (nếu cần)
        foreach ($posts as &$post) {
            if (empty($post['category_name'])) {
                $post['category_name'] = 'Chưa phân loại';
            }
        }

        return $posts;
    }
}
