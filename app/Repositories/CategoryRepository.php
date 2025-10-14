<?php

/**
 * Trung gian thông qua Model
 */

namespace App\Repositories;

use App\Models\Category;
use CodeIgniter\Database\Exceptions\DatabaseException;

class CategoryRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new Category();
    }
    public function all()
    {
        return $this->model->findAll();
    }
    public function find($id)
    {
        return $this->model->find($id);
    }
    // tim ca deleted
    public function find2($id)
    {
        return $this->model->withDeleted()->find($id);
    }

    public function create(array $data)
    {
        // xử lý lỗi
        try {
            $result = $this->model->insert($data);
            if ($result === false) {
                // lấy lỗi chi tiết từ Model
                $errors = $this->model->errors();
                throw new DatabaseException('Lỗi khi thêm dữ liệu: ' . json_encode($errors));
            }
        } catch (\Throwable $th) {
            // Có thể là lỗi kết nối DB, lỗi SQL syntax, ...
            throw new DatabaseException($th->getMessage(), $th->getCode(), $th);
        }
    }

    public function update($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
    public function findBySlug($slug)
    {
        return $this->model->where("slug", $slug)->first();
    }
    public function restore($id)
    {
        return $this->model->update($id, ['deleted_at' => null]);
    }

    public function existsSlug($slug, $excludeId = null)
    {
        $builder = $this->model;

        $builder->where('slug', $slug);

        // Nếu đang update thì bỏ qua chính nó
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        $query = $builder->get();
        return $query->getNumRows() > 0;
    }
}
