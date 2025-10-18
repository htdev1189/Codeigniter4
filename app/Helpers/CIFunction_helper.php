<?php

use App\Libraries\CIAuth;
use App\Models\Setting;
use App\Models\User;

// get current router name
if (!function_exists("getName_current_router_name")) {
    function getName_current_router_name(){
        $router = service("router");
        return $router->getMatchedRouteOptions()['as'];
    }
}

if (! function_exists('shortText')) {
    function shortText($text, $limit = 50)
    {
        if (mb_strlen($text, 'UTF-8') > $limit) {
            return mb_substr($text, 0, $limit, 'UTF-8') . '...';
        }
        return $text;
    }
}

if (! function_exists('normalizeFilename')) {
    function normalizeFilename($str)
    {
        // Loại bỏ dấu tiếng Việt
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);

        // Loại bỏ ký tự đặc biệt, thay khoảng trắng bằng gạch ngang
        $str = preg_replace('/[^A-Za-z0-9_\-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str); // gom dấu '-' trùng
        $str = trim($str, '-');

        return strtolower($str);
    }
}
if (! function_exists('get_user')) {
    function get_user()
    {
        if (CIAuth::check()) {
            $user = new User();
            return $user->asObject()->where('id', CIAuth::id())->first();
        } else {
            return null;
        }
    }
}

// get setting
if (! function_exists('get_setting')) {
    function get_setting()
    {
        $setting = new Setting();
        $currentSetting = $setting->asObject()->first();

        if ($currentSetting) {
            return $currentSetting;
        } else {
            // nghĩa là chưa có gì thì mình sẽ thiết lập các giá trị mặc định
            $data = [
                'blog_title' => "CI4 Blog",
                'blog_email' => "admin@ci4.htdev",
                'blog_phone' => '0123456789',
                'blog_keywords' => null,
                'blog_description' => null,
                'blog_logo' => null,
                'blog_favicon' => null,
                'blog_social' => null,
            ];
            // insert to db
            $setting->save($data);

            // get data
            $new_setting = $setting->asObject()->first();
            return $new_setting;
        }
    }
}
if (! function_exists('slugify')) {

    function slugify(string $text): string
    {
        // 1. Chuẩn hóa dấu tiếng Việt → không dấu
        $text = removeVietnameseAccents($text);

        // 2. Chuyển về chữ thường
        $text = strtolower($text);

        // 3. Bỏ ký tự đặc biệt
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);

        // 4. Thay khoảng trắng thành dấu gạch ngang
        $text = preg_replace('/\s+/', '-', $text);

        // 5. Loại bỏ gạch thừa ở đầu/cuối
        $text = trim($text, '-');

        return $text;
    }
}

if (! function_exists('removeVietnameseAccents')) {
    function removeVietnameseAccents(string $str): string
    {
        $accents = [
            'à' => 'a',
            'á' => 'a',
            'ạ' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ầ' => 'a',
            'ấ' => 'a',
            'ậ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ă' => 'a',
            'ằ' => 'a',
            'ắ' => 'a',
            'ặ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'è' => 'e',
            'é' => 'e',
            'ẹ' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ê' => 'e',
            'ề' => 'e',
            'ế' => 'e',
            'ệ' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'ị' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ò' => 'o',
            'ó' => 'o',
            'ọ' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ô' => 'o',
            'ồ' => 'o',
            'ố' => 'o',
            'ộ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ơ' => 'o',
            'ờ' => 'o',
            'ớ' => 'o',
            'ợ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'ụ' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ư' => 'u',
            'ừ' => 'u',
            'ứ' => 'u',
            'ự' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ỳ' => 'y',
            'ý' => 'y',
            'ỵ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'đ' => 'd',
            'À' => 'A',
            'Á' => 'A',
            'Ạ' => 'A',
            'Ả' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'Ầ' => 'A',
            'Ấ' => 'A',
            'Ậ' => 'A',
            'Ẩ' => 'A',
            'Ẫ' => 'A',
            'Ă' => 'A',
            'Ằ' => 'A',
            'Ắ' => 'A',
            'Ặ' => 'A',
            'Ẳ' => 'A',
            'Ẵ' => 'A',
            'È' => 'E',
            'É' => 'E',
            'Ẹ' => 'E',
            'Ẻ' => 'E',
            'Ẽ' => 'E',
            'Ê' => 'E',
            'Ề' => 'E',
            'Ế' => 'E',
            'Ệ' => 'E',
            'Ể' => 'E',
            'Ễ' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Ị' => 'I',
            'Ỉ' => 'I',
            'Ĩ' => 'I',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ọ' => 'O',
            'Ỏ' => 'O',
            'Õ' => 'O',
            'Ô' => 'O',
            'Ồ' => 'O',
            'Ố' => 'O',
            'Ộ' => 'O',
            'Ổ' => 'O',
            'Ỗ' => 'O',
            'Ơ' => 'O',
            'Ờ' => 'O',
            'Ớ' => 'O',
            'Ợ' => 'O',
            'Ở' => 'O',
            'Ỡ' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Ụ' => 'U',
            'Ủ' => 'U',
            'Ũ' => 'U',
            'Ư' => 'U',
            'Ừ' => 'U',
            'Ứ' => 'U',
            'Ự' => 'U',
            'Ử' => 'U',
            'Ữ' => 'U',
            'Ỳ' => 'Y',
            'Ý' => 'Y',
            'Ỵ' => 'Y',
            'Ỷ' => 'Y',
            'Ỹ' => 'Y',
            'Đ' => 'D'
        ];
        return strtr($str, $accents);
    }
}
