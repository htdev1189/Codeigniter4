## Soft Delete
- Khi bạn “xóa” record, nó không bị xóa khỏi database.
- Thay vào đó, CodeIgniter chỉ điền ngày/giờ vào cột deleted_at (hoặc cột tùy chỉnh).
- Dữ liệu đó sẽ không được hiển thị trong truy vấn bình thường.
    - kích hoạt trong Model
    ```php
    // Bật soft delete trong model
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';
    ```
- Xử lý
```bash

# migration
php spark make:migration add_deleted_at_to_categories_table

```