<?php

namespace App\Validation;

class IsValidURL
{
    public function ValidURL($url)
    {
        if($url != "" && filter_var($url, FILTER_VALIDATE_URL) !== false){
            return true;
        }
        return false;
    }
}
