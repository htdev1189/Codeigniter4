<?php

namespace App\Validation;

class IsPasswordStrong
{
    // public function custom_rule(): bool
    // {
    //     return true;
    // }
    public function is_password_strong($password){
        // trim
        $password = trim($password);
        /**
         * // -> 
         * ^ -> head
         * $ -> end
         * (?=.*[\W]) -> kiem tra trong chuoi co ton tai ky tu dang biet hay khong
         * (?=.*[a-z]) -> co ky tu thuong
         * (?=.*[A-Z]) -> co ky tu in hoa
         * (?=.*[0-9]) -> co so
         * .{5,50} -> bat ky ky tu na tru cuong dong, do dai ky tu tu 5 toi 50
         * 
         */
        $regex = '/^(?=.*[\W])(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{5,50}$/';
        if ( ! preg_match($regex,$password)) {
            return false;
        }
        return true;
    }
}
