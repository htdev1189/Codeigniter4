<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BlogController extends BaseController
{
    protected $helpers = ["url","form","CIMail", "CIFunction", "Frontend"];
    public function index()
    {
        $data = [
            "pageTitle" => "Home Page",
        ];
        return view('frontend/pages/home', $data);
    }

    public function readPost($slug){
        $data = [
            "pageTitle" => "Read Post Page",
            "slug" => $slug,
        ];
        return view('frontend/pages/article', $data);
    }
}
