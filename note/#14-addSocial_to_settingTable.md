## triển khai blog_social vào table setting

> Này tôi muốn lưu trữ dạng json trong table

```php

// tạo migration thêm cột vào bảng 
php spark make:migration add_blog_social_to_setting_table

// chỉnh sửa up() và down()
$this->forge->addColumn('settings',[
    'blog_social' => [
    'type' => 'TEXT',
    'null' => true
   ]
]);

// down
$this->forge->dropColumn('settings','blog_social');

// Chạy migrate
php spark migrate

/**
 * Thiết lập cần thiết trong model
 * Khi bạn lấy dữ liệu từ DB, CodeIgniter sẽ tự convert kiểu dữ liệu của cột theo định dạng bạn chỉ định trong $casts
 * 
 * Nếu nó là JSON
 * 
 * $row = $blogModel->find(1);
 * print_r($row['blog_social']);
 * lúc này nó tự động json_decode thành mảng cho bạn, không cẩn json_decode($row['blog_social'], true)
 * 
 * $blogModel->save([
 *     'title' => 'Demo',
 *     'blog_social' => [
 *         'facebook' => 'https://facebook.com/abc',
 *         'instagram' => 'https://instagram.com/abc'
 *     ]
 * ]);
 * khi lưu dữ liệu vào thì chỉ cần truyền qua mảng, nó tự động json_encode đưa vào database
*/
 

protected $casts = [
    'blog_social' => 'json'
];

// thực hiện submit form bằng ajax

```
