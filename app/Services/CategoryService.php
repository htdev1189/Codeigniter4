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
    public function getAll()
    {
        return $this->CategoryRepository->getAll();
    }
    public function getById($id)
    {
        $category =  $this->CategoryRepository->find2($id);
        if (!$category) {
            // Nếu không có kết quả, ném exception
            throw new PageNotFoundException("Không tìm thấy danh mục có ID = {$id}");
        }

        // Nếu có parent_id => lấy parent name
        if (!empty($category['parent_id'])) {
            $category['parent_name'] = $this->CategoryRepository->findNameById($category['parent_id']);
        } else {
            $category['parent_name'] = null;
        }

        return $category;
    }

    public function getGroupedCategories()
    {
        $categories = $this->CategoryRepository->getAll();
        $grouped = [];

        foreach ($categories as $cat) {
            if ($cat['parent_id'] == 0) {
                $grouped[$cat['id']] = [
                    'name' => $cat['name'],
                    'children' => []
                ];
            }
        }

        foreach ($categories as $cat) {
            if ($cat['parent_id'] != 0 && isset($grouped[$cat['parent_id']])) {
                $grouped[$cat['parent_id']]['children'][] = $cat;
            }
        }

        return $grouped;
    }

    public function create($data)
    {
        $name = trim($data['name']);
        $slug = slugify($name);
        $parent_id = $data['parent'];
        $seo_title = $data['seo_title'];
        $seo_keyword = $data['seo_keyword'];
        $seo_des = $data['seo_des'];

        // Business logic: ví dụ kiểm tra trùng slug
        $exists = $this->CategoryRepository->findBySlug($slug);

        if ($exists) {
            throw new \Exception('Slug đã tồn tại, vui lòng nhập tên khác.');
        }


        try {
            return $this->CategoryRepository->create([
                'name' => $name,
                'slug' => $slug,
                'parent_id' => $parent_id,
                'seo_title' => $seo_title,
                'seo_keyword' => $seo_keyword,
                'seo_des' => $seo_des,
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

    public function getDataForDataTable($params)
    {
        $start       = (int) ($params['start'] ?? 0);
        $length      = (int) ($params['length'] ?? 10);
        $searchValue = trim($params['searchValue'] ?? '');
        $orderColumn = $params['orderColumn'] ?? 'id';
        $orderDir    = $params['orderDir'] ?? 'asc';
        $showDeleted = $params['showDeleted'] ?? true;

        $totalRecords = $this->CategoryRepository->countAll($showDeleted);
        $filteredRecords = $this->CategoryRepository->countFiltered($searchValue, $showDeleted);
        $categories = $this->CategoryRepository->getAllWithParent($start, $length, $searchValue, $orderColumn, $orderDir, $showDeleted);

        return [
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $categories,
        ];
    }
}
