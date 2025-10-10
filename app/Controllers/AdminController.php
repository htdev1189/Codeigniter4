<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\Setting;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

use function PHPUnit\Framework\fileExists;

class AdminController extends BaseController
{

    protected $helpers = ['url', 'form', 'CIMail', 'CIFunction'];

    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard'
        ];
        return view('backend/pages/home', $data);
    }
    public function logoutHandler()
    {
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('error', 'you are logged out !!');
    }
    public function profile()
    {
        $data = [
            'pageTitle' => 'Profile'
        ];
        return view('backend/pages/profile', $data);
    }
    public function updatePersonalDetails()
    {

        if ($this->request->isAJAX()) {

            // validate
            $isValidation = $this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Full name is required',
                    ]
                ],
                'username' => [
                    'rules' => 'required|min_length[4]|is_unique[users.username,id,' . CIAuth::id() . ']',
                    'errors' => [
                        'required' => 'Username is required',
                        'min_length'  => 'Username phai nhieu hon 4 ky tu',
                        'is_unique' => 'Username da ton tai'
                    ]
                ],
            ]);

            if (! $isValidation) {
                $errors = $this->validator->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'errors' => $errors
                ]);
            } else {

                $name = $this->request->getPost('name');
                $username = $this->request->getPost('username');
                $bio = $this->request->getPost('bio');

                $user = new User();
                $update = $user->where('id', CIAuth::id())
                    ->set(
                        [
                            'name' => $name,
                            'username' => $username,
                            'bio' => $bio
                        ]
                    )
                    ->update();
                if ($update) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'userInfo' => $user->find(CIAuth::id()),
                        'msg' => 'Update success'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Something wrong'
                    ]);
                }
            }


            // $name = $this->request->getPost('name');
            // $username = $this->request->getPost('username');
            // $bio = $this->request->getPost('bio');

            // // ví dụ trả JSON String phản hồi
            // return $this->response->setJSON([
            //     'status' => 'success',
            //     'message' => 'Dữ liệu nhận thành công!',
            //     'data' => [
            //         'name' => $name,
            //         'username' => $username,
            //         'bio' => $bio
            //     ]
            // ]);
        }

        return $this->response->setStatusCode(400)->setBody('Yêu cầu không hợp lệ');
    }


    public function updateProfilePicture()
    {
        $user = new User();
        $userInfo = $user->asObject()->where('id', CIAuth::id())->first();
        $path = 'images/users/';
        $file = $this->request->getFile('user_profile_file');
        $old_picture = $userInfo->picture;
        $new_image_name = 'UIMG' . date('Ymd') . uniqid() . '.jpg';

        // method 1
        // if ($file->move($path, $new_image_name)) {
        //     if ($old_picture != null && file_exists($path . $old_picture)) {
        //         unlink($path . $old_picture);
        //     }
        //     $user->where('id', CIAuth::id())->set([
        //         'picture' => $new_image_name
        //     ])->update();
        //     echo json_encode(['status' => 1, 'msg' => 'success', 'name' => $new_image_name]);
        // } else {
        //     echo json_encode(['status' => 0, 'msg' => 'failed']);
        // }

        /**
         * Method 2
         * Using Image Manupulation class
         */

        $image = service('image');
        $uploadFile = $image->withFile($file)
            ->resize(160, 160, true, 'height')
            ->save($path . $new_image_name);

        if ($uploadFile) {
            if ($old_picture != null && file_exists($path . $old_picture)) {
                unlink($path . $old_picture);
            }
            $user->where('id', CIAuth::id())->set([
                'picture' => $new_image_name
            ])->update();
            echo json_encode(['status' => 1, 'msg' => 'success', 'name' => $new_image_name]);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'failed']);
        }

        // test Image Manupulation class
        // $image = service('image');
        // $flag = $image->withFile('images/users/test.jpg')
        //             //   ->fit(100,100,'center')
        //               ->resize(100, 100, true, 'height')
        //               ->convert(IMAGETYPE_WEBP)
        //               ->save('images/users/test-100x100.webp');
        // if ($flag) {
        //     echo json_encode(['status'=>1, 'msg'=>'success']); 
        // } else {
        //     echo json_encode(['status'=>0, 'msg'=>'failed']);
        // }

    }


    // change password
    public function changePassword()
    {
        $user = new User();
        $id = CIAuth::id();
        $userInfo = $user->asObject()->where('id', $id)->first();

        if ($this->request->isAJAX()) {

            // $isValidation = $this->validate([
            //     'current_password' => [
            //         'rules' => 'required|check_current_pass[current_password]',
            //         'errors' => [
            //             'required' => 'Password is required',
            //             'check_current_pass' => 'Password current wrong'
            //         ]
            //     ]
            // ]);
            // if (! $isValidation) {
            //     $errors = $this->validator->getErrors();
            //     return $this->response->setJSON([
            //         'status' => 0,
            //         'errors' => $errors
            //     ]);
            // }

            // validation
            $rules = [
                'current_password' => [
                    'rules' => 'required|check_current_pass[]',
                    'errors' => [
                        'required' => 'Password is required',
                        'check_current_pass' => 'Password current wrong'
                    ]
                ],
                'new_password' => [
                    'rules' => 'required|min_length[6]|max_length[20]|is_password_strong[]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Check min length is 6 characters',
                        'min_length' => 'Check max length is 20 characters',
                        'is_password_strong' => "Password not strong"
                    ]
                ],
                'confirm_new_password' => [
                    'rules' => 'required|matches[new_password]',
                    'errors' => [
                        'required' => 'Password is required',
                        'matches' => 'Confirm Password not matches',
                    ]
                ],
            ];


            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 0,
                    'errors' => $this->validator->getErrors(),
                    'msg' => 'error update password',
                    'token' => csrf_token(),
                ]);
            } else {
                $user->where('id', $id)->set([
                    'password' => Hash::make($this->request->getVar('new_password'))
                ])->update();

                // mail data
                $mail_data = [
                    'user' => $userInfo,
                    'password' => $this->request->getVar('new_password'),
                ];

                // render email body
                $view = service('renderer');
                $email_body = $view->setVar('mailData', $mail_data)->render('backend/email-template/changed-pass-success');

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
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'change password success',
                        'token' => csrf_token(),
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'change password falied',
                        'token' => csrf_token(),
                    ]);
                }
            }
        }
    }

    // general setting page
    public function setting()
    {
        $data = [
            'pageTitle' => 'Setting Page'
        ];
        return view('backend/pages/setting', $data);
    }

    // handler setting
    public function settingHandler()
    {
        if ($this->request->isAJAX()) {
            $rules = [
                'blog_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'blog title not null'
                    ]
                ],
                'blog_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'blog email not null',
                        'valid_email' => 'It does not appear to be valid'
                    ]
                ],
                'blog_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'blog title not null'
                    ]
                ]
            ];

            //check validate
            if ($this->validate($rules)) {

                // update to db
                $setting = new Setting();
                $id = $setting->asObject()->first()->id;
                $flag = $setting->where('id', $id)->set([
                    'blog_title' => $this->request->getPost('blog_title'),
                    'blog_email' => $this->request->getPost('blog_email'),
                    'blog_phone' => $this->request->getPost('blog_phone'),
                    'blog_keywords' => $this->request->getPost('blog_keywords'),
                    'blog_description' => $this->request->getPost('blog_description')
                ])->update();
                if ($flag) {
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'update general setting success',
                        'token' => csrf_hash()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'update general setting failed',
                        'token' => csrf_hash()
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'update general setting failed',
                    'errors' => $this->validator->getErrors(),
                    'token' => csrf_hash()
                ]);
            }
        }
    }

    // public function updateLogo()
    // {
    //     // Check if request is AJAX (optional)
    //     if ($this->request->isAJAX()) {
    //         // same logic applies
    //         $setting = new Setting();
    //         $currentSetting = $setting->asObject()->first();
    //         $id = $currentSetting->id;

    //         $path = FCPATH . 'images/setting/';
    //         $file = $this->request->getFile('blog_logo');
    //         $old_logo = $currentSetting->blog_logo;
    //         $new_logo = 'UIMG' . date('Ymd') . uniqid() . '.' . $file->getExtension();;

    //         $image = service('image');
    //         $uploadFile = $image->withFile($file)
    //             ->resize(257, 55, true, 'height')
    //             ->save($path . $new_logo);

    //         if ($uploadFile) {
    //             $setting->where('id', $id)->set([
    //                 'blog_logo' => $new_logo
    //             ])->update();
    //             echo json_encode([
    //                 'status' => 1,
    //                 'msg' => 'update blog logo success',
    //                 'logo' => base_url($path . $new_logo),
    //                 'token' => csrf_hash()
    //             ]);
    //         } else {
    //             echo json_encode([
    //                 'status' => 0,
    //                 'msg' => 'update blog logo failed',
    //                 'token' => csrf_hash()
    //             ]);
    //         }
    //     }
    // }

    public function updateLogo()
    {
        if ($this->request->isAJAX()) {
            $setting = new Setting();
            $currentSetting = $setting->asObject()->first();
            $id = $currentSetting->id;

            $uploadDir = FCPATH . 'images/setting/';
            $file = $this->request->getFile('blog_logo');

            if ($file->isValid() && !$file->hasMoved()) {
                $new_logo = 'UIMG' . date('Ymd') . uniqid() . '.' . $file->getExtension();

                // di chuyển file
                $file->move($uploadDir, $new_logo);

                // cập nhật DB
                $setting->update($id, ['blog_logo' => $new_logo]);

                // trả về URL công khai
                return $this->response->setJSON([
                    'status' => 1,
                    'msg'    => 'Update blog logo success',
                    'logo'   => base_url('images/setting/' . $new_logo),
                    'token'  => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg'    => 'Upload failed',
                    'token'  => csrf_hash()
                ]);
            }
        }
    }
}
