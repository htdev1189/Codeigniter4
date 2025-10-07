<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    // url -> router_to
    // form -> set_value 
    protected $helpers = ['url', 'form'];

    public function loginForm(){
        $data = [
            'pageTitle' => "Login Page",
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }
    public function loginHandle(){
        // kiem tr login theo email hay username
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? "email" : "username";
        echo $fieldType;
    }   
}
