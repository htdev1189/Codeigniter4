<?php 

namespace App\Libraries;

class CIAuth{
    public static function setCIAuth($result){
        $session = session();
        $array = [
            'logged_id' => true
        ];
        $userData = $result;
        $session->set('userData',$userData);
        $session->set($array);
    }

    // get id
    public static function id(){
        $session = session();
        if ($session->has('logged_id')) {
            if($session->has('userData')){
                return $session->get('userData')['id'];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    // check login
    public static function check(){
        $session = session();
        return $session->has('logged_id');
    }

    // logout
    public static function forget(){
        $session = session();
        $session->remove('logged_id');
        $session->remove('userData');
    }

    // get user
    public static function user(){
        $session = session();
        return $session->get('userData');       
    }
}