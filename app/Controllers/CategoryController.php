<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Services\CategoryService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{

    protected $helpers = ['url', 'form', 'CIMail', 'CIFunction'];

    protected $CategoryService;

    public function __construct()
    {
        $this->CategoryService = new CategoryService();
    }

    // sử dụng datatable
    public function index()
    {
        return view('backend/pages/category/list', [
            'pageTitle' => 'Category List'
        ]);
    }

    // không sử dụng datatable
    public function index2()
    {
        $categories = $this->CategoryService->getAllCategories();
        return view(
            'backend/pages/category/list',
            [
                'pageTitle' => 'Category List',
                'categories' => $categories
            ]
        );
    }


    // get data by datatable
    public function getDataOld()
    {
        $request = service('request');
        $model = new Category();

        $draw   = $request->getGet('draw');
        $start  = $request->getGet('start');
        $length = $request->getGet('length');
        $searchValue = $request->getGet('search')['value'] ?? '';

        // --- sort ---
        $orderColumnIndex = $request->getGet('order')[0]['column'] ?? 0;
        $orderDir         = $request->getGet('order')[0]['dir'] ?? 'asc';
        $columns = ['id', 'name', 'slug', 'created_at', 'updated_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // --- filter deleted ---
        // Nếu có query ?showDeleted=true thì hiện cả soft deleted
        $showDeleted = $request->getGet('showDeleted') ?? false;




        // Tổng record (chưa filter)
        $totalRecords = $model->countAll();

        // Tạo query base
        // $builder = $model;

        if ($showDeleted) {
            $builder = $model->withDeleted();
        } else {
            $builder = $model->where('deleted_at', null);
        }

        // Nếu có từ khóa tìm kiếm
        $builder = $builder->like('name', $searchValue)
            ->orLike('slug', $searchValue);

        // Tổng record sau filter
        $filteredRecords = $builder->countAllResults(false);

        // Lấy dữ liệu thực tế
        // $categories = $builder
        //     ->orderBy('id', 'DESC')
        //     ->findAll($length, $start);

        // --- query with sort + limit ---
        $categories = $builder
            ->orderBy($orderColumn, $orderDir)
            ->findAll($length, $start);

        // Chuẩn bị dữ liệu phản hồi
        $data = [];
        foreach ($categories as $cat) {
            $isDeleted = !empty($cat['deleted_at']);
            $edit = '<a href="' . route_to('admin.category.edit', $cat['id']) . '" class="btn btn-sm btn-warning">Edit</a>';
            $delete = '<form action="' . route_to('admin.category.delete', $cat['id']) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Xác nhận xóa danh mục này?\')">
        ' . csrf_field() . '
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>';

            // Nếu là soft deleted thì hiển thị khác
            if ($isDeleted) {
                $delete = '<form action="' . route_to('admin.category.restore', $cat['id']) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Khôi phục danh mục này?\')">
                ' . csrf_field() . '
                <button type="submit" class="btn btn-sm btn-success">Restore</button>
            </form>';
            }

            $data[] = [
                $cat['id'],
                esc($cat['name']),
                esc($cat['slug']),
                date('d/m/Y H:i:s', strtotime($cat['created_at'])),
                date('d/m/Y H:i:s', strtotime($cat['updated_at'])),
                $edit . $delete
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function getData()
    {
        $request = service('request');
        $model   = new Category();

        $draw        = $request->getGet('draw');
        $start       = (int) $request->getGet('start');
        $length      = (int) $request->getGet('length');
        $searchValue = trim($request->getGet('search')['value'] ?? '');
        $showDeleted = $request->getGet('showDeleted') === 'true'; // true nếu query string có ?showDeleted=true

        // --- sort ---
        $columns = ['id', 'name', 'slug', 'created_at', 'updated_at'];
        $orderColumnIndex = (int) ($request->getGet('order')[0]['column'] ?? 0);
        $orderDir         = $request->getGet('order')[0]['dir'] ?? 'asc';
        $orderColumn      = $columns[$orderColumnIndex] ?? 'id';

        // --- query base ---
        $builder = $model;

        $builder = $builder->withDeleted(); // hien tat ca
        // if ($showDeleted) {
        //     $builder = $builder->withDeleted(); // hien tat ca
        // } else {
        //     $builder = $builder->where('deleted_at', null);
        // }

        // --- total records ---
        $totalRecords = $builder->countAllResults(false);

        // --- filter search ---
        if ($searchValue !== '') {
            $builder->groupStart()
                ->like('name', $searchValue)
                ->orLike('slug', $searchValue)
                ->groupEnd();
        }

        // --- filtered records ---
        $filteredRecords = $builder->countAllResults(false);

        // --- pagination + sorting ---
        $categories = $builder
            ->orderBy($orderColumn, $orderDir)
            ->findAll($length, $start);

        // --- format output ---
        $data = [];
        foreach ($categories as $cat) {
            $isDeleted = !empty($cat['deleted_at']);

            $editBtn = '<a href="' . route_to('admin.category.edit', $cat['id']) . '" class="btn btn-sm btn-warning">Edit</a>';
            $deleteBtn = '<form action="' . route_to('admin.category.delete', $cat['id']) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Xác nhận xóa danh mục này?\')">'
                . csrf_field() .
                '<button type="submit" class="btn btn-sm btn-danger">Delete</button></form>';

            // Nếu là soft deleted → hiển thị nút Restore
            if ($isDeleted) {
                $deleteBtn = '<form action="' . route_to('admin.category.restore', $cat['id']) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Khôi phục danh mục này?\')">'
                    . csrf_field() .
                    '<button type="submit" class="btn btn-sm btn-success">Restore</button></form>';
            }

            $data[] = [
                $cat['id'],
                esc($cat['name']),
                esc($cat['slug']),
                $this->CategoryService->getById($cat['id'])['parent_name'],
                date('d/m/Y H:i:s', strtotime($cat['created_at'])),
                $cat['updated_at'] ? date('d/m/Y H:i:s', strtotime($cat['updated_at'])) : '',
                $editBtn . ' ' . $deleteBtn
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }



    public function create()
    {
        // Form thêm category
        return view('backend/pages/category/add', [
            'pageTitle' => 'Create New Category',
            'categories' => $this->CategoryService->getAll()
        ]);
    }

    public function store()
    {
        // Nhận request
        if ($this->request->getMethod() !== 'POST') { // POST chứ không phải post
            return redirect()->back()->with('error', 'Phương thức không hợp lệ');
        }

        // validate
        $rules = [
            'name' => [
                'rules' => 'required|min_length[2]|max_length[255]',
                'errors' => [
                    'required' => 'Vui lòng nhập tên danh mục.',
                    'min_length' => 'Tên danh mục phải có ít nhất 2 ký tự.',
                    'max_length' => 'Tên danh mục tối đa 255 ký tự.'
                ]
            ]
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // gọi service -- bao gom exception
        try {
            $this->CategoryService->create($this->request->getPost());
            return redirect()->route('admin.category.list')->with('success', 'Tạo danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (DatabaseException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Form sửa
        try {
            $category = $this->CategoryService->getById($id);
            return view('backend/pages/category/edit', [
                'pageTitle' => "edit category",
                'category' => $category,
                'categories' => $this->CategoryService->getAll()
            ]);
        } catch (PageNotFoundException $e) {
            // Hiển thị trang 404 mặc định của CI4
            // return view('errors/html/error_404', [
            //     'message' => $e->getMessage()
            // ]);
            // hiển thị theo định dạng khác
            return view('backend/error/error-404');
            // return redirect()->route('admin.category.list')->with('error', $e->getMessage());
        }
    }

    public function update($id)
    {
        // validate
        $rules = [
            'name' => [
                'rules' => 'required|min_length[2]|max_length[255]',
                'errors' => [
                    'required' => 'Vui lòng nhập tên danh mục.',
                    'min_length' => 'Tên danh mục phải có ít nhất 2 ký tự.',
                    'max_length' => 'Tên danh mục tối đa 255 ký tự.'
                ]
            ]
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'slug' => slugify($this->request->getPost('name')),
                'parent_id' => $this->request->getPost('parent'),
                'seo_title' => $this->request->getPost('seo_title'),
                'seo_des' => $this->request->getPost('seo_des'),
                'seo_keyword' => $this->request->getPost('seo_keyword'),
            ];

            $this->CategoryService->update($id, $data);

            return redirect()
                ->route('admin.category.list')
                ->with('success', 'Cập nhật danh mục thành công!');
        } catch (PageNotFoundException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (DatabaseException $e) {
            return redirect()->back()->with('error', 'Lỗi cơ sở dữ liệu: ' . $e->getMessage());
        } catch (\Exception $e) { // 👈 Thêm catch này
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('critical', $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống khi cập nhật.');
        }
    }

    public function delete($id)
    {
        // Gọi service.
        // Bắt lỗi và phản hồi tương ứng cho người dùng.
        try {
            $this->CategoryService->delete($id);
            return redirect()
                ->route('admin.category.list')
                ->with('success', 'Xóa danh mục thành công!');
        } catch (PageNotFoundException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (DatabaseException $e) {
            return redirect()->back()->with('error', 'Lỗi cơ sở dữ liệu: ' . $e->getMessage());
        } catch (\Throwable $e) {
            log_message('critical', $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống khi xóa.');
        }
    }

    public function restore($id)
    {
        try {
            $this->CategoryService->restore($id);
            return redirect()
                ->route('admin.category.list')
                ->with('success', 'Restore danh mục thành công!');
        } catch (PageNotFoundException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (DatabaseException $e) {
            return redirect()->back()->with('error', 'Lỗi cơ sở dữ liệu: ' . $e->getMessage());
        } catch (\Throwable $e) {
            log_message('critical', $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống khi restore.');
        }
    }
}
