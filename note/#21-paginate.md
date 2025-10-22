## Phân trang trong ci4

- render paginate bằng cách khai báo trong ``app/Config/Pager.php``

```sh
    'custom_pager'   => 'App\Views\backend\pages\paginate',
    # này là giao diện phân trang chỉnh sửa
```

```php
    // gắn trong view
    // lấy gia diện từ custom_page
    // biến get page_posts
    $pager->links('posts', 'custom_pager')
```
