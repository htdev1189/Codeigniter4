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
    // get all with delected
    public function getAll(){
        return $this->model->withDeleted()->findAll();
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

    public function getBySlug($slug){
        return $this->model->where('slug', $slug)->first();
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

    public function findNameById($id){
        return $this->model->select('name')->find($id)['name'] ?? null ;
    }

    // datatable
    public function countAll($showDeleted = true)
    {
        $builder = $this->model->builder();

        if (!$showDeleted) {
            $builder->where('deleted_at', null);
        }

        return $builder->countAllResults();
    }
    public function countFiltered($searchValue = '', $showDeleted = true)
    {
        $builder = $this->model->builder();

        if (!$showDeleted) {
            $builder->where('deleted_at', null);
        }

        if ($searchValue !== '') {
            $builder->groupStart()
                ->like('name', $searchValue)
                ->orLike('slug', $searchValue)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }
    /**
     * Lấy danh sách category có join với parent_name
     */
    public function getAllWithParent($start, $length, $searchValue = '', $orderColumn = 'id', $orderDir = 'asc', $showDeleted = true)
    {
        $builder = $this->model->builder();
        $builder->select('c.*, p.name AS parent_name')
            ->from('categories c')
            ->join('categories p', 'p.id = c.parent_id', 'left');

        if (!$showDeleted) {
            $builder->where('c.deleted_at', null);
        }

        if ($searchValue !== '') {
            $builder->groupStart()
                ->like('c.name', $searchValue)
                ->orLike('c.slug', $searchValue)
                ->groupEnd();
        }

        $builder->orderBy('c.' . $orderColumn, $orderDir);

        if ($length > 0) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }
}
