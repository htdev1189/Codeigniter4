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

        // Tổng record (chưa filter)
        $totalRecords = $model->countAll();

        // Tạo query base
        $builder = $model;

        // Nếu có từ khóa tìm kiếm
        if ($searchValue) {
            $builder = $builder->like('name', $searchValue);
        }

        // Tổng record sau filter
        $filteredRecords = $builder->countAllResults(false);

        // Lấy dữ liệu thực tế
        $categories = $builder
            ->orderBy('id', 'DESC')
            ->findAll($length, $start);

        // Chuẩn bị dữ liệu phản hồi
        $data = [];
        foreach ($categories as $cat) {
            $data[] = [
                $cat['id'],
                esc($cat['name']),
                esc($cat['slug']),
                date('d/m/Y', strtotime($cat['created_at'])),
                date('d/m/Y', strtotime($cat['updated_at'])),
                '<a href="' . route_to('admin.category.edit', $cat['id']) . '" class="btn btn-sm btn-warning">Edit</a>
             <a href="' . route_to('admin.category.delete', $cat['id']) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Xác nhận xóa?\')">Delete</a>'
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
    }

    public function update($id)
    {
        // Xử lý cập nhật
    }

    public function delete($id)
    {
        // Xử lý xóa
    }
}
