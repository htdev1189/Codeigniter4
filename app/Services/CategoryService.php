<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;

class CategoryService
{
    protected $CategoryRepository;
    public function __construct()
    {
        $this->CategoryRepository = new CategoryRepository();
    }
    public function getAllCategories()
    {
        return $this->CategoryRepository->all();
    }
    public function getById($id)
    {
        return $this->CategoryRepository->find2($id);
    }

    public function create($data)
    {
        $name = trim($data['name']);
        $slug = slugify($name);

        // Business logic: ví dụ kiểm tra trùng slug
        $exists = $this->CategoryRepository->findBySlug($slug);

        if ($exists) {
            throw new \Exception('Slug đã tồn tại, vui lòng nhập tên khác.');
        }


        try {
            return $this->CategoryRepository->create([
                'name' => $name,
                'slug' => $slug,
            ]);
        } catch (\Exception $e) {
            throw $e; // Ném lên controller
        }
    }

    public function delete($id)
    {
        // Kiểm tra có tồn tại hay không.
        $category = $this->CategoryRepository->find($id);

        if (!$category) {
            throw new PageNotFoundException('Không tìm thấy category có ID: ' . $id);
            // throw new PageNotFoundException('Không tìm thấy category có ID: ' . $id);
        }

        try {
            // Gọi repository để xóa.
            $this->CategoryRepository->delete($id);
        } catch (DatabaseException $e) {
            // Có thể ghi log hoặc rollback nếu dùng transaction
            log_message('error', '[CategoryService] Lỗi khi xóa: ' . $e->getMessage());
            throw $e;
        }
    }
    public function restore($id)
    {
        // Kiểm tra có tồn tại hay không.
        $category = $this->CategoryRepository->find2($id);

        if (!$category) {
            throw new PageNotFoundException('Không tìm thấy category có ID: ' . $id);
            // throw new PageNotFoundException('Không tìm thấy category có ID: ' . $id);
        }

        try {
            // Gọi repository để xóa.
            $this->CategoryRepository->restore($id);
        } catch (DatabaseException $e) {
            // Có thể ghi log hoặc rollback nếu dùng transaction
            log_message('error', '[CategoryService] Lỗi khi restore: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, $data)
    {
        $category = $this->CategoryRepository->find2($id);
        if (!$category) {
            throw new PageNotFoundException('Không tìm thấy category có ID: ' . $id);
        }

        // Kiểm tra slug trùng
        if ($this->CategoryRepository->existsSlug($data['slug'], $id)) {
            throw new \Exception('Slug đã tồn tại, vui lòng chọn tên khác.');
        }

        try {
            return $this->CategoryRepository->update($id, $data);
        } catch (DatabaseException $e) {
            throw $e;
        }
    }
}
