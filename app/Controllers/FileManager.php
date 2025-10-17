<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class FileManager extends BaseController
{
    public function upload()
    {
        $file = $this->request->getFile('upload');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'uploaded' => 0,
                'error' => ['message' => 'File upload không hợp lệ.']
            ]);
        }

        // Tạo thư mục nếu chưa có
        $uploadPath = FCPATH . 'uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Đặt tên file tránh trùng
        $newName = time() . '_' . $file->getClientName();
        $file->move($uploadPath, $newName);

        // Trả về JSON cho CKEditor
        return $this->response->setJSON([
            'uploaded' => 1,
            'fileName' => $newName,
            'url' => base_url('uploads/' . $newName)
        ]);
    }

    public function browse()
    {
        $uploadPath = FCPATH . 'uploads/';
        $files = glob($uploadPath . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

        $data = [
            'files' => $files,
            'funcNum' => $this->request->getGet('CKEditorFuncNum'),
            'baseUrl' => base_url('uploads/')
        ];

        return view('backend/filemanager/browse', $data);
    }
    public function browse2()
{
    $basePath = FCPATH . 'uploads/';
    $currentDir = $this->request->getGet('dir') ?? ''; // thư mục hiện tại
    $targetPath = realpath($basePath . $currentDir);

    if ($targetPath === false || strpos($targetPath, realpath($basePath)) !== 0) {
        // tránh truy cập ngoài thư mục uploads
        $targetPath = realpath($basePath);
        $currentDir = '';
    }

    // Lấy danh sách thư mục con
    $dirs = array_filter(glob($basePath . '*'), 'is_dir');

    // Lấy danh sách ảnh trong thư mục hiện tại
    $images = glob($targetPath . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

    $data = [
        'dirs' => $dirs,
        'images' => $images,
        'funcNum' => $this->request->getGet('CKEditorFuncNum'),
        'baseUrl' => base_url('uploads/'),
        'currentDir' => $currentDir
    ];

    return view('backend/filemanager/browse2', $data);
}

}
