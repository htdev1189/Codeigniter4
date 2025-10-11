<?php

use App\Libraries\CIAuth;
use App\Models\Setting;
use App\Models\User;

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
