<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
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
}
