## Filter
```bash
# create
php spark make:filter CIFilter

# add to router
$routes->group('', ['filter' => '<filterName>:<Argumments>'], static function($routes){});
$routes->get('admin', ' AdminController::index', ['filter' => ['admin-auth', \App\Filters\SomeFilter::class]]);

# request lifecycle.
1. system ( tìm nạp file cấu hình )
2. Incoming request routed ( xác định controller, methods thông qua URL )
3. before ( chạy hàm before trong các filter được thiết lập ) 
4. Controller executed ( thực thi controller )
5. Response generated ( trả ve object hoặc view )
6. after ( chạy hàm after trong các filter được nạp )
7. sent to browser ( hiển thị cho người dùng )

# controller -- filter
before() filter → controller → after() filter
```