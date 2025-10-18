## Router
> trong CodeIgniter 4 (CI4), Service Router nằm trong hệ thống dịch vụ (Service Container), được quản lý bởi class Config\Services. Tuy nhiên, khi ta gọi Services::router(), ta đang lấy đối tượng router hiện tại (class CodeIgniter\Router\Router) — đây là thành phần trung tâm điều hướng request tới controller tương ứng.

### Giới thiệu ngắn gọn về Services::router
Cú pháp
```php
$router = \Config\Services::router();
$router = service('router'); // sử dụng trong controller
```

### Chức năng chính của Router trong CI4
Router chịu trách nhiệm phân tích URL hiện tại để xác định:
- **Controller** nào sẽ được gọi.
- **Method** nào trong controller sẽ thực thi.
- **Các tham số** (parameters) được truyền trong URL.
- **Các ràng buộc** (constraints) cho route.
- **Các filter** (middleware) được áp dụng cho route.
- **Các route alias** (tên định danh) cho phép gọi ngược route.

### Các tính năng cụ thể của Router
- **Tự động ánh xạ route**
    - Nếu bạn không định nghĩa route cụ thể, CI4 có thể tự động ánh xạ URL theo mẫu:
    ```php
    http://domain/controller/method/param1/param2

    // ví dụ
    http://localhost/blog/show/1
    → gọi Blog::show(1)
    ```
- **Định nghĩa route tùy chỉnh**
    - Được khai báo trong ``app/Config/Routes.php``:
    ```php
    $routes->get('blog/(:num)', 'Blog::detail/$1');
    $routes->post('blog/create', 'Blog::create');
    ```
    Router sẽ phân tích theo HTTP method và pattern.
- **Route Groups (nhóm route)**
    - Giúp gom các route cùng prefix hoặc cùng filter:
    ```php
    $routes->group('admin', ['filter' => 'auth'], function($routes) {
        $routes->get('users', 'Admin\User::index');
        $routes->get('posts', 'Admin\Post::index');
    });
    ```
- **Filters (Middleware)**
    - Router liên kết trực tiếp với hệ thống filter.
    ```php
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
    ```
    Filter auth sẽ chạy trước khi controller thực thi.
- **Named Routes (đặt tên cho route)**
    - Giúp bạn gọi URL theo tên route, không phụ thuộc vào đường dẫn thực tế:
    ```php
    $routes->get('user/profile', 'User::profile', ['as' => 'user.profile']);
    
    // Trong view hoặc controller:
    $url = route_to('user.profile');
    ```
- **Route placeholders (thay thế mẫu dữ liệu)**
    - CI4 hỗ trợ các dạng placeholder:
        
    | Placeholder   | Mô tả                        |
    | ------------- | ---------------------------- |
    | `(:segment)`  | 1 đoạn bất kỳ (không có `/`) |
    | `(:any)`      | bất kỳ chuỗi nào             |
    | `(:num)`      | chỉ nhận số                  |
    | `(:alpha)`    | chỉ nhận chữ                 |
    | `(:alphanum)` | chữ + số                     |
    | `(:segment+)` | nhiều đoạn                   |
- **Route priority (ưu tiên)**
    - Router kiểm tra theo thứ tự khai báo trong ``Routes.php``.
    - Route nào khớp đầu tiên sẽ được chọn.
- **Default controller & method**
    ```php
    $routes->setDefaultController('Home');
    $routes->setDefaultMethod('index');
    ```
- **404 Override**

    Khi không tìm thấy route phù hợp, bạn có thể chỉ định xử lý riêng:
    ```php
    $routes->set404Override('Errors::show404');
    ```
### Một số hàm hữu ích của Router

| Hàm                           | Ý nghĩa                     |
| ----------------------------- | --------------------------- |
| `$router->controllerName()`   | Lấy tên controller hiện tại |
| `$router->methodName()`       | Lấy tên method hiện tại     |
| `$router->params()`           | Lấy danh sách tham số       |
| `$router->directory()`        | Lấy thư mục chứa controller |
| `$router->hasRoute($name)`    | Kiểm tra route tồn tại      |
| `$router->setAutoRoute(true)` | Bật auto routing            |

| Mục đích                        | Cách thực hiện                      |
| ------------------------------- | ----------------------------------- |
| Định tuyến request → controller | `$routes->get()`, `$routes->post()` |
| Gom nhóm route                  | `$routes->group()`                  |
| Đặt filter (middleware)         | Thêm `['filter' => 'auth']`         |
| Đặt tên cho route               | `['as' => 'route.name']`            |
| Tạo URL ngược                   | `route_to('route.name')`            |
| Lấy router hiện tại             | `service('router')`                 |
