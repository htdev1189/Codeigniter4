<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    // url -> router_to
    // form -> set_value 
    protected $helpers = ['url', 'form'];

    public function loginForm()
    {
        $data = [
            'pageTitle' => "Login Page",
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }
    public function loginHandle()
    {
        // kiem tr login theo email hay username
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? "email" : "username";

        $validation = service('validation');

        $rules = [
            'login_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'You must choose a Username/Email',
                ],
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'You must enter password',
                ]
            ]
        ];

        // lay thong tin validate
        $validation_result = $this->validate($rules);
        if (! $validation_result) {
            $data = [
                'pageTitle' => "Login Page",
                'validation' => $this->validator
            ];
            return view('backend/pages/auth/login', $data);
        } else {
            $user = new User();
            $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();
            if ($userInfo) {
                $check_password = Hash::check($this->request->getVar('password'), $userInfo['password']);
                if ($check_password) {
                    CIAuth::setCIAuth($userInfo); // important
                    return redirect('admin.home');
                } else {
                    // redirect ve 1 trang, tra lai flash session, tra lai $POST
                    return redirect()->route('admin.login.form')->with('error', 'Wrong infomation')->withInput();
                }
            } else {
                return redirect()->route('admin.login.form')->with('error', 'Wrong infomation')->withInput();
            }
        }
    }
}
