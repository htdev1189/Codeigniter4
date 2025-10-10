<?php

namespace App\Validation;

use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;

class IsCurrentPasswordCorrect
{
    public function check_current_pass($pass)
    {
        $pass = trim($pass);
        $user = new User();
        $userInfo = $user->asObject()->where('id', CIAuth::id())->first();
        if ($userInfo) {
            if (Hash::check($pass, $userInfo->password)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
