## Tích hợp ckeditor 4 vào form trong ci4

- Tải phiên bản ``ckeditor_4.22.1_standard`` vì bản lts giờ đã không còn free
- Tắt kiểm tra version
    ```js
    CKEDITOR.replace('content', {
        versionCheck: false
    });
    ```
> Bình thường sẽ tải ckfinder về tích hợp luôn, nhưng nay sẽ cố gắng xây dựng một cái riêng
- [dev_file_browse_upload](https://ckeditor.com/docs/ckeditor4/latest/guide/dev_file_browse_upload.html)
    ```php
    CKEDITOR.replace( 'editor1', {
        filebrowserBrowseUrl: '/browser/browse.php',
        filebrowserUploadUrl: '/uploader/upload.php'
    });
    ```
- Vì bạn đang dùng CodeIgniter 4 (CI4) nên ta sẽ không đặt các file browse.php và upload.php rời ngoài thư mục public như kiểu PHP thuần nữa.
Thay vào đó, ta sẽ tạo Controller riêng để xử lý 2 chức năng:
    - browse() → duyệt file
    - upload() → upload file