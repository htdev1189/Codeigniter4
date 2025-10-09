## Data Validation
```bash
# create
php spark make:validation IsPasswordStrong

# Khai báo trong app/config/Validation.php
public array $ruleSets = [
    .....
    IsPasswordStrong::class,
];

# sử dụng

$isValidation = $this->validate([
      'password' => [
           'rules' => 'required|is_password_strong[]',
           'errors' => [
                'required' => 'Password empty',
                'is_password_strong' => 'Password not strong'
            ]
        ]
]);

if ($isValidation) {
    echo "validated";
}

# rules

is_unique[users.email] => su dung trong truong hop them moi, tránh sự trùng lặp

is_unique[users.email,id,{id}] => su dung trong truong hop update, 
