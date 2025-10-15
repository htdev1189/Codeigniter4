<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\ValidationException;
use App\Services\CategoryService;
use App\Services\PostService;
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
        $data = [
            'pageTitle' => 'list all posts',
            'posts' => $this->PostService->getAll()
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
            $data = $this->request->getPost();
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
}
