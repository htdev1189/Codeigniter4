## Cài đặt
- [https://www.youtube.com/watch?v=o-Yhyz28MPA&list=PLX4adOBVJXavmNeP7CU295sX76jgzziio&index=4](link)
> Thông qua composer
- composer create-project codeigniter4/appstarter project-root
> Download trực tiếp
- [https://github.com/codeigniter4](https://github.com/codeigniter4)
> Chạy
```php
php spark serve
```
> Cấu hình
```env
CI_ENVIRONMENT = development

app.baseURL = 'http://ci4.htdev/'
app_baseURL = 'http://ci4.htdev/'

database.default.hostname = localhost
database.default.database = ci4
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

## Setup Database, Migrations, Models, Seeders
```bash
# tạo 1 class trong Database/Migrations/
# up -> khi thực hiện migrate
# down -> khi thực hiện rollback
php spark make:migation createUsersTable

# Chạy các file migraion chưa từng được thực thi
php spark migrate

# reset và chay lai các gile migration
php spark migrate:refresh
```

```bash
# make model
php spark make:model User
# tạo file User.php trong thư mục app/Model
```

```bash
# make Seeder
php spark make:seeder UserSeeder
# chạy seeder
php spark db:seed UserSeeder
```

```bash
# download deskapp template admin github
```
- [https://github.com/dropways/deskapp](https://github.com/dropways/deskapp)


> Layout master

```php
# Extend Layout
# đường dẫn file layout trong thư mục app/Views
$this->extend('backend/layout/page-layout')

# render được thiết lập trong layout
$this->renderSection('content')

# nội dung render trong component
$this->section('content')
    <h1>Nội dung này sẽ được đưa vào trong layout master</h1>
$this->endSection()
```

> Controller
```bash
# tạo controller
php spark make:controller AuthController
php spark make:controller AdminController
```