## Change Password

- Sử dụng Jquery Ajax để update password
- Gửi thông báo password đổi thành công qua email

```php
// Router
$routes->post('change-password', 'AdminController::changePassword', ['as' => 'change-pasword']);

// Thiết lập action trong form theo teend được định nghĩa trong router
<form action="<?= route_to('change-pasword') ?>" method="post" id="change_password_form">

// Sử dụng FormData để submit dữ liệu
var form = this;
var formData = new FormData(form);

// quan trọng
// Nếu không thêm contentType: false,
// jQuery sẽ gán header Content-Type: application/x-www-form-urlencoded,
// làm cho PHP không parse được $_POST → dẫn đến validate báo lỗi “required”.
contentType: false,


// thêm validate kiem tra current password
php spark make:validation IsCurrentPasswordCorrect
// nhớ thêm vào file app/config/Validation.php
public array $ruleSets = [
    // more
    IsCurrentPasswordCorrect::class,
];

// Quy  trình xác thực
// Khi người dùng nhập thiếu, hoặc mật khẩu không mạnh, mật khẩu không trùng khớp, thì nó sẽ bắn json ra các lỗi kiểu như 
    if (!$this->validate($rules)) {
        return $this->response->setJSON([
                'status' => 0,
                'errors' => $this->validator->getErrors(),
                'msg' => 'error update password'
            ]);
    }else{
        return $this->response->setJSON([
                'status' => 1,
                'msg' => 'update password success'
        ]);
    }
// Sau đó trên hàm success của AJAX sẽ lấy các lỗi này ra và set Text cho các span hứng sẵn theo class được chỉ định
// Đồng thời tạo mới lại token

$("#change-password-token").val(response.token)
$.each(response.errors, function(key, value){
    $(form).find('span.'+key+'_error').text(value);
});

// Nếu người dùng nhập các thông tin không vi phạm validator

// update password vào database
// send mail

```
