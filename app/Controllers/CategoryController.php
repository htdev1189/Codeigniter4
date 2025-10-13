<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{

    protected $helpers = ['url', 'form', 'CIMail', 'CIFunction'];

    public function index()
    {
        $data = [
            'pageTitle' => 'Category List'
        ];
        return view('backend/pages/category/list', $data);
    }

    // get data by datatable
    public function getData()
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


        // Tổng record (chưa filter)
        $totalRecords = $model->countAll();

        // Tạo query base
        $builder = $model;

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
            $edit = '<a href="' . route_to('admin.category.edit', $cat['id']) . '" class="btn btn-sm btn-warning">Edit</a>';
            $delete = '<form action="' . route_to('admin.category.delete', $cat['id']) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Xác nhận xóa danh mục này?\')">
        ' . csrf_field() . '
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>';
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


    public function create()
    {
        // Form thêm category
        $data = [
            'pageTitle' => 'Category add'
        ];
        return view('backend/pages/category/add', $data);
    }

    public function store()
    {
        // dd($this->request->getPost());
        // dd($this->request->getMethod());
        // Xử lý thêm mới
        if ($this->request->getMethod() === 'POST') { // POST chu khong phai post --- khiep

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

            // dd($this->request->getPost('name'));
            if (!$this->validate($rules)) {
                // redirect ve trang them, tra lai $POST
                return redirect()->back()->withInput()->with('validation', $this->validator);
            } else {
                // dd($this->request->getPost('name'));
                $name = $this->request->getPost('name');
                $slug = slugify($name);
                // dd($slug);

                // check issets
                $categoryModel = new Category();
                $check = $categoryModel->where('slug', $slug)->first();
                if (!$check) {
                    $categoryModel->insert([
                        'name' => $name,
                        'slug' => $slug
                    ]);
                    return redirect()->route('admin.category.list')->with('success', 'create category success');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Slug đã tồn tại, vui lòng nhập tên khác.');
                }
            }
        }
    }

    public function edit($id)
    {
        // Form sửa
        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'khong tim thay category');
        }
        $data = [
            'pageTitle' => 'Edit Category',
            'category'  => $category
        ];
        return view('backend/pages/category/edit', $data);
    }

    public function update($id)
    {
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

        $name = $this->request->getPost('name');
        $slug = slugify($name);

        $categoryModel = new Category();

        // Kiểm tra slug trùng (trừ chính nó)
        $check = $categoryModel->where('slug', $slug)->where('id !=', $id)->first();
        if ($check) {
            return redirect()->back()->withInput()->with('error', 'Slug đã tồn tại, vui lòng đổi tên khác.');
        }

        $update = $categoryModel->update($id, [
            'name' => $name,
            'slug' => $slug,
        ]);
        if ($update) {
            return redirect()->route('admin.category.list')->with('success', 'Cập nhật danh mục thành công.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra');
        }
    }

    public function delete($id)
    {
        // Xử lý xóa
        $categoryModel = new Category();
        $category = $categoryModel->find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'khong tim thay category');
        }

        $categoryModel->delete($id);
        return redirect()->route('admin.category.list')->with('success', 'remove category success');
    }
}
