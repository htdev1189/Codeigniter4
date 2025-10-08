<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\PasswordResetToken;
use Carbon\Carbon;

class AuthController extends BaseController
{
    // url -> router_to
    // form -> set_value 
    protected $helpers = ['url', 'form', 'CIMail'];

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
                    return redirect()->route('admin.home');
                } else {
                    // redirect ve 1 trang, tra lai flash session, tra lai $POST
                    return redirect()->route('admin.login.form')->with('error', 'Wrong infomation')->withInput();
                }
            } else {
                return redirect()->route('admin.login.form')->with('error', 'Wrong infomation')->withInput();
            }
        }
    }

    public function forgotPassword()
    {
        $data = [
            'pageTitle' => "Forgot password",
            'validation' => null
        ];
        return view('backend/pages/auth/forget', $data);
    }

    public function forgotHandle()
    {
        $rules = [
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email cannot be empty',
                    'valid_email' => 'Invalid email format',
                    'is_not_unique' => 'Email does not exist in our system',
                ],
            ],
        ];

        $validation_result =  $this->validate($rules);
        if (! $validation_result) {
            $data = [
                'pageTitle' => "Forgot password",
                'validation' => $this->validator
            ];
            return view('backend/pages/auth/forget', $data);
        } else {
            // get user
            $user = new User();
            // Return as standard objects
            $userInfo = $user->asObject()->where('email', $this->request->getVar('email'))->first();

            // generate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));
            // get reset password token
            $password_reset_token = new PasswordResetToken();
            $oldToken = $password_reset_token->asObject()->where('email', $userInfo->email)->first();

            // neu nhu no da ton tai roi
            if ($oldToken) {
                //update token
                $password_reset_token->where('email', $userInfo->email)->set(['token' => $token, 'created_at' => Carbon::now()])->update();
            } else {
                $password_reset_token->insert([
                    'email' => $userInfo->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            }

            // chay toi link reset password
            $actionLink = route_to('admin.reset-password', $token);


            // mail
            $mail_data = [
                'actionLink' => $actionLink,
                'user' => $userInfo
            ];

            // render email body
            $view = service('renderer');
            $email_body = $view->setVar('mail_data', $mail_data)->render('backend/email-template/forgot-email');

            $mailConfig = [
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_to_email' => $userInfo->email,
                'mail_to_name' => $userInfo->name,
                'mail_subject' => 'reset password',
                'mail_body' => $email_body
            ];

            // send email
            if (sendEmail($mailConfig)) {
                return redirect()->route('admin.forgot.form')->with('success', 'Email send success');
            } else {
                return redirect()->route('admin.forgot.form')->with('error', 'Somting went wrong');
            }
        }
    }

    public function resetPassword($token)
    {
        // check token
        $password_reset_token = new PasswordResetToken();
        $checkToken = $password_reset_token->asObject()->where('token', $token)->first();
        if ($checkToken) {
            // neu ton tai trong db
            // kiem tra expired
            $diffMins = Carbon::createFromFormat('Y-m-d h:i:s', $checkToken->created_at)->diffInMinutes(Carbon::now());
            // neu lon hon 15 phut 
            if ($diffMins > 1500000) {
                // thong bao token het han
                return redirect()->route('admin.forgot.form')->with('error', 'Token expired. Request another reset password link');
            } else {
                // render giao dien thay doi pass
                return view('backend/pages/auth/reset', [
                    'pageTitle' => 'Reset password',
                    'token' => $token,
                    'validation' => null
                ]);
            }
        } else {
            // neu khong ton tai
            return redirect()->route('admin.forgot.form')->with('error', 'Invalid token. Requets another password link');
        }
    }

    public function resetPasswordHandler($token)
    {
        $isValidation = $this->validate([
            'password' => [
                'rules' => 'required|is_password_strong[]',
                'errors' => [
                    'required' => 'Password empty',
                    'is_password_strong' => 'Password not strong'
                ]
            ],
            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Confirm password empty',
                    'matches'  => 'Password not match'
                ]
            ],
        ]);

        if (! $isValidation) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        } else {
            // get token detail
            $password_reset_token = new PasswordResetToken();
            // idUser and token
            $get_token = $password_reset_token->asObject()->where('token', $token)->first();

            // get user by email in token table
            $user = new User();
            $userInfo = $user->asObject()->where('email', $get_token->email)->first();

            // check token
            if (!$get_token) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invaild token');
            } else {
                // update admin pass
                $user->where('email', $userInfo->email)
                    ->set([
                        'password' => Hash::make($this->request->getVar('password'))
                    ])
                    ->update();

                // send notification
                $mailData = [
                    'user' => $userInfo,
                    'password' => $this->request->getVar('password')
                ];

                $render = service('renderer');
                $emailBody = $render->setVar('mailData', $mailData)->render('backend/email-template/changed-pass-success');

                $mailConfig = [
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_to_email' => $userInfo->email,
                    'mail_to_name' => $userInfo->name,
                    'mail_subject' => 'Password changed',
                    'mail_body' => $emailBody
                ];

                // send email
                if (sendEmail($mailConfig)) {

                    // delete token
                    $password_reset_token->where('token', $token)->delete();

                    // redirect to login
                    return redirect()->route('admin.login.form')->with('success', 'Password changed. Please login with new password');
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Invaild token');
                }
            }
        }
    }
}
