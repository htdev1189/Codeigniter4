<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\CategoryService;
use App\Services\PostService;
use CodeIgniter\HTTP\ResponseInterface;

class PostController extends BaseController
{
    /**
     * CIFunction : check user .. 
     */
    protected $helpers = ['CIFunction'];
    protected $PostService;
    protected $CategoryService;

    public function __construct() {
        $this->PostService = new PostService();
        $this->CategoryService = new CategoryService();
    }
    
    public function index()
    {
        //
    }
    public function create(){
        $data = [
            'pageTitle' => 'create new post',
            'categories' => $this->CategoryService->getAllCategories()
        ];
        return view('backend/pages/post/create',$data);
    }
}
