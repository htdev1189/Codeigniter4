<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// admin route group
$routes->group('admin',static function ($routes){
    // sub group
    /**
     * ['filter' => 'cifilter:guest'] ==> ['filter' => 'filterName define in Filters.php : arguments check']
     */
    $routes->group('', ['filter' => 'cifilter:auth'], static function($routes){
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);

        // profile
        $routes->get('profile', 'AdminController::profile',['as' => 'admin.profile']);
    });
    $routes->group('', ['filter' => 'cifilter:guest'], static function($routes){
        $routes->get('login','AuthController::loginForm',['as' => 'admin.login.form']);
        $routes->post('login','AuthController::loginHandle',['as' => 'admin.login.handle']);

        // forgot password
        $routes->get('forgot-password','AuthController::forgotPassword',['as' => 'admin.forgot.form']);
        $routes->post('forgot-password','AuthController::forgotHandle',['as' => 'admin.forgot.handle']);
        $routes->get('password/reset/(:any)','AuthController::resetPassword/$1',['as' => 'admin.reset-password']);
        // truyen router_to kèm theo token
        // 
        $routes->post('reset-pasword-handler/(:any)','AuthController::resetPasswordhandler/$1',['as' => 'admin.reset-password-handler']);
    });
});
