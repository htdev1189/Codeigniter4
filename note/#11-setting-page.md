## #11 Setting page
- Thiết lập setting cho toàn bộ website, như thông tin về địa chỉ, số điện thoại vvvv
```php

// thiết lập router
$routes->get('setting', 'AdminController::setting', ['as' => 'setting']);
$routes->post('update-setting', 'AdminController::settingHandler', ['as' => 'admin.update.setting']);

// thiết lập giao diện trong Views/backend/pages/setting.php
$data = ['pageTitle' => 'Setting Page'];
return view('backend/pages/setting', $data);

// tạo table setting bằng migration
php spark make:migration create_setting_table

// tạo model setting
php spark make:model Setting
/**
 * Chú ý đoạn này, thay vì viết trong migration 
 * 'created_at timestamp default current_timestamp',
 * 'updated_at timestamp default current_timestamp on update current_timestamp'
 * 
 * Chỉnh sửa lại
 * 'created_at' => [
 *          'type' => 'DATETIME',
 *          'null' => true,
 *      ],
 * 'updated_at' => [
 *          'type' => 'DATETIME',
 *          'null' => true,
 *      ],
*/
protected $useTimestamps = true; // important
protected $createdField  = 'created_at';
protected $updatedField  = 'updated_at';


// chạy migration
php spark migrate // chạy các file chưa có trạng thái 'đã chạy'
php spark migrate:rollback // quay trở lại trạng thái gần nhất, (chạy hàm down ở các migrator chạy gần nhất)

// thiết lập default setting trong CIFunction_helper

```