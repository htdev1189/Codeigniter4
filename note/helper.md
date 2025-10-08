## Helper
```bash
# create
create file CIMail_helper.php
# khai báo trong controller
# CIMail lấy từ tên file bỏ _helper
protected $helpers = ['CIMail'];
# Sử dụng các hàm trong helper nay
sendmmail();