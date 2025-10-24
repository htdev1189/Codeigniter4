<?php

use App\Libraries\CIAuth;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;

/**
 * Category helper functions
 */

// get siderbar categories
if (! function_exists("get_sidebar_categories")) {
    function get_sidebar_categories()
    {
        $categoryModel = new \App\Models\Category();
        $categories = $categoryModel->asObject()
            ->where('deleted_at', null)
            ->where('parent_id', 0)
            ->orderBy('name', 'ASC')
            ->get()->getResult(); // trả về mảng object
        return $categories;
    }
}

if (!function_exists('get_all_child_category_ids')) {
    function get_all_child_category_ids($categoryId)
    {
        $categoryModel = new \App\Models\Category();

        // Lấy danh sách con trực tiếp
        $children = $categoryModel->where('parent_id', $categoryId)->findAll();

        $ids = [$categoryId]; // Bắt đầu với chính category hiện tại

        foreach ($children as $child) {
            // Đệ quy để lấy các con của con
            $ids = array_merge($ids, get_all_child_category_ids($child['id']));
        }

        return $ids;
    }
}

// lấy đệ quy id cah theo id con
if (!function_exists('get_all_parent_category_ids')) {
    function get_all_parent_category_ids($categoryId)
    {
        $categoryModel = new \App\Models\Category();

        $ids = [$categoryId]; // Bắt đầu với chính category hiện tại

        $category = $categoryModel->find($categoryId);
        if ($category && $category['parent_id'] != 0) {
            // Đệ quy để lấy các cha
            $ids = array_merge($ids, get_all_parent_category_ids($category['parent_id']));
        }

        return array_reverse($ids);
    }
}   

if (!function_exists('get_all_parent_categories')) {
    function get_all_parent_categories($categoryId)
    {
        $categoryModel = new \App\Models\Category();

        $categories = [];

        $category = $categoryModel->find($categoryId);
        if ($category) {
            $categories[] = $category; // thêm chính nó vào danh sách

            if ($category['parent_id'] != 0) {
                // đệ quy thêm cha của nó
                $parentCategories = get_all_parent_categories($category['parent_id']);
                $categories = array_merge($categories, $parentCategories);
            }
        }

        // Đảo ngược lại để cha -> con
        return array_reverse($categories);
    }
}



// count post in category
// if (! function_exists("count_posts_by_category")) {
//     function count_posts_by_category($categoryId)
//     {
//         $postModel = new \App\Models\Post();
//         $posts = $postModel->asObject()
//             ->where('deleted_at', null)
//             ->where('visibility', 1)
//             ->where('category_id', $categoryId)
//             ->orderBy('created_at', 'DESC')
//             ->get()->getResult(); // trả về mảng object
//         return count($posts);
//     }
// }

// su dung de quy
if (!function_exists('count_posts_by_category')) {
    function count_posts_by_category($categoryId)
    {
        $postModel = new \App\Models\Post();
        $allCategoryIds = get_all_child_category_ids($categoryId);

        $posts = $postModel
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->whereIn('category_id', $allCategoryIds)
            ->paginate(2);
            // ->findAll();

        return count($posts);
    }
}


/**
 * Post helper functions
 * 
 */

// random post
if (! function_exists("get_random_posts")) {
    function get_random_posts($limit = 2)
    {
        $postModel = new \App\Models\Post();
        $posts = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->orderBy('RAND()')
            ->limit($limit)
            ->get()->getResult(); // trả về mảng object
        return $posts;
    }
}

// lay 6 bao viet theo thu tu moi nhat
if (! function_exists("get_posts")) {
    function get_posts($limit = 6)
    {
        $postModel = new \App\Models\Post();
        $posts = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->orderBy('created_at', 'DESC')
            ->limit($limit,1)
            ->get()->getResult(); // trả về mảng object
        return $posts;
    }
}

// lay danh sach lastest post
if (! function_exists("get_latest_posts")) {
    function get_latest_posts()
    {
        $postModel = new \App\Models\Post();
        $latestPosts = $postModel->asObject()
            ->where('deleted_at', null)
            ->where('visibility', 1)
            ->orderBy('created_at', 'DESC')
            ->first();
        return $latestPosts; // ojbect
    }
}


// gioi han content
if (! function_exists("limit_content")) {
    function limit_content($content, $limit = 100)
    {
        $content = strip_tags($content);
        if (strlen($content) <= $limit) {
            return $content;
        } else {
            $truncated = substr($content, 0, $limit);
            $lastSpace = strrpos($truncated, ' ');
            if ($lastSpace !== false) {
                $truncated = substr($truncated, 0, $lastSpace);
            }
            return $truncated . '...';
        }
    }
}

// tinh toan thoi gian doc
if (! function_exists("get_reading_time")) {
    function get_reading_time($content, $wpm = 200)
    {
        // 200 : words per minute
        $wordCount = str_word_count(strip_tags($content));
        $readingTimeMinutes = ceil($wordCount / $wpm);
        return $readingTimeMinutes <= 1 ? "1 minute read" : $readingTimeMinutes . " minutes read";
    }
}

// date format helper
if (! function_exists('formatDate')) {
    function formatDate($dateString, $format = 'Y-m-d H:i:s')
    {
        $date = Carbon::parse($dateString);
        return $date->format($format);
        // $date = new DateTime($dateString);
        // return $date->format($format);
    }
}

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
