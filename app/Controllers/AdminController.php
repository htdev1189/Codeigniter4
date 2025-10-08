<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{

    protected $helpers = ['url', 'form', 'CIMail', 'CIFunction'];

    public function index(){
        $data = [
            'pageTitle' => 'Dashboard'
        ];
        return view('backend/pages/home', $data);
    }
    public function logoutHandler(){
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('error','you are logged out !!');
    }
    public function profile(){
        $data = [
            'pageTitle' => 'Profile'
        ];
        return view('backend/pages/profile', $data);
    }
}
