<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\ValidationException;
use App\Services\CategoryService;
use App\Services\PostService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class PostController extends BaseController
{
    /**
     * CIFunction : check user .. 
     */
    protected $helpers = ['CIFunction', 'form', 'url'];
    protected $PostService;
    protected $CategoryService;

    public function __construct()
    {
        $this->PostService = new PostService();
        $this->CategoryService = new CategoryService();
    }

    public function index()
    {
        $perPage = 1;
        $result = $this->PostService->getAll($perPage);
        $data = [
            'pageTitle' => 'Danh sách bài viết',
            'posts' => $result['posts'],
            'pager' => $result['pager']
        ];
        return view('backend/pages/post/list', $data);
    }
    public function create()
    {
        $data = [
            'pageTitle' => 'create new post',
            'categories' => $this->CategoryService->getAllCategories()
        ];
        return view('backend/pages/post/create', $data);
    }
    public function store()
    {
        $rules = [
            'title' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Vui lòng nhập tiêu đề'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }


        // goi service -- co the try catch noi day
        try {
            $data = $this->request->getPost(); // chỉ truyển qua định dạng text nên muốn truyền dạng fikle qua thi làm bước bên dưới
            /**
             * C:\wamp64\www\CodeIgniter4-4.6.3\system\HTTP\Files\UploadedFile.php
             * này nó sẽ trả về 1 instance của class UploadedFile
             * xem thêm để biết các propeties và methods
             */
            $data['featured_image'] = $this->request->getFile('featured_image');


            $this->PostService->create($data);
            return redirect()->route('admin.post.list')->with('success', 'created new post success');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('CustomException', $e->getErrors());
        } catch (\Exception $e) {
            // echo '<pre>';
            // echo "Exception: " . $e->getMessage() . "\n";
            // echo "File: " . $e->getFile() . "\n";
            // echo "Line: " . $e->getLine() . "\n";
            // echo "Trace:\n" . $e->getTraceAsString();
            // echo '</pre>';
            // exit; // dừng lại để xem lỗi
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
    }

    // edit form
    public function edit($id)
    {
        if ($this->PostService->findById($id)) {
            return view('backend/pages/post/edit', [
                'pageTitle' => 'edit post',
                'post' => $this->PostService->findById($id),
                'categories' => $this->CategoryService->getAllCategories()
            ]);
        } else {
            return view('backend/error/error-404');
        }
    }

    // update post
    public function update($id)
    {
        // validation
        $rules = [
            'title' => [
                'rules' => 'required|is_unique[posts.title,id,' . $id . ']',
                'errors' => [
                    'required' => 'Tiêu đề không được để trống',
                    'is_unique' => 'Tiêu đề đã tồn tại, vui lòng thay đổi lại',
                ]
            ]
        ];

        $file = $this->request->getFile('featured_image');
        // debug --- 
        // truong hop bị lỗi do warm server mac dinh upload duoi 2M 
        //dd($file->getError(), $file->getErrorString());
        //dd($file->getName(), $file->isValid());



        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // chuan bi data de truyen qua service
        $data = $this->request->getPost();
        $data['featured_image'] = $this->request->getFile('featured_image');


        try {
            // goi service
            $this->PostService->update($id, $data);
            return redirect()->route('admin.post.list')->with('success', 'updated post success');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('CustomException', $e->getErrors());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', $e->getMessage());
        }
    }

    // soft delete
    public function delete($id)
    {
        try {
            $this->PostService->deletePost($id);
            return redirect()
                ->route('admin.post.list')
                ->with('success', 'Xóa bài viết thành công!');
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
            $this->PostService->restorePost($id);
            return redirect()
                ->route('admin.post.list')
                ->with('success', 'Khôi phục bài viết thành công!');
        } catch (PageNotFoundException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (DatabaseException $e) {
            return redirect()->back()->with('error', 'Lỗi cơ sở dữ liệu: ' . $e->getMessage());
        } catch (\Throwable $e) {
            log_message('critical', $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi hệ thống khi khôi phục.');
        }
    }
}
