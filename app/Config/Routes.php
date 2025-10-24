<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// frontend route
$routes->get('/', 'BlogController::index', ['as'=> 'blog.home']);
$routes->get('/404', 'BlogController::page404', ['as'=> 'blog.page404']);
$routes->get('post/(:any)', 'BlogController::readPost/$1', ['as' => 'blog.post.read']);
$routes->get('category/(:any)', 'BlogController::readCat/$1', ['as' => 'blog.category.read']);

// admin route group
$routes->group('admin', static function ($routes) {
    // sub group
    /**
     * ['filter' => 'cifilter:guest'] ==> ['filter' => 'filterName define in Filters.php : arguments check']
     */
    $routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);

        // profile
        $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->post('update-personal-details', 'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
        $routes->post('update-profile-picture', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);
        $routes->post('change-password', 'AdminController::changePassword', ['as' => 'change-pasword']);
        $routes->get('setting', 'AdminController::setting', ['as' => 'admin.setting']);
        $routes->post('update-setting', 'AdminController::settingHandler', ['as' => 'admin.update.setting']);
        $routes->post('update-logo', 'AdminController::updateLogo', ['as' => 'admin.update.logo']);
        $routes->post('update-social', 'AdminController::updateSocial', ['as' => 'admin.update.social']);

        // category
        $routes->get('categories', 'CategoryController::index', ['as' => 'admin.category.list']);
        $routes->get('categories-data', 'CategoryController::getData', ['as' => 'admin.category.data']);
        $routes->get('category/add', 'CategoryController::create', ['as' => 'admin.category.add']);
        $routes->post('category/add', 'CategoryController::store', ['as' => 'admin.category.add']);
        $routes->post('category/delete/(:num)', 'CategoryController::delete/$1', ['as' => 'admin.category.delete']);
        $routes->get('category/edit/(:num)', 'CategoryController::edit/$1', ['as' => 'admin.category.edit']);
        $routes->post('category/update/(:num)', 'CategoryController::update/$1', ['as' => 'admin.category.update']);
        $routes->post('category/restore/(:num)', 'CategoryController::restore/$1', ['as' => 'admin.category.restore']);

        // custome upload file
        $routes->get('filemanager/browse', 'FileManager::browse', ['as' => 'admin.upload.form']);
        $routes->get('filemanager/browse2', 'FileManager::browse2', ['as' => 'admin.upload.browse']);
        $routes->post('filemanager/upload', 'FileManager::upload', ['as' => 'admin.upload.handler']);


        // posts
        $routes->group('post', static function ($routes) {
            $routes->get('list', 'PostController::index', ['as' => 'admin.post.list']);
            $routes->get('new', 'PostController::create', ['as' => 'admin.post.create']);
            $routes->post('store', 'PostController::store', ['as' => 'admin.post.store']);
            $routes->get('edit/(:num)', 'PostController::edit/$1', ['as' => 'admin.post.edit']);
            $routes->post('update/(:num)', 'PostController::update/$1', ['as' => 'admin.post.update']);
            $routes->post('delete/(:num)', 'PostController::delete/$1', ['as' => 'admin.post.delete']);
            $routes->post('restore/(:num)', 'PostController::restore/$1', ['as' => 'admin.post.restore']);
        });
    });
    $routes->group('', ['filter' => 'cifilter:guest'], static function ($routes) {
        $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('login', 'AuthController::loginHandle', ['as' => 'admin.login.handle']);

        // forgot password
        $routes->get('forgot-password', 'AuthController::forgotPassword', ['as' => 'admin.forgot.form']);
        $routes->post('forgot-password', 'AuthController::forgotHandle', ['as' => 'admin.forgot.handle']);
        $routes->get('password/reset/(:any)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset-password']);
        // truyen router_to kèm theo token
        // 
        $routes->post('reset-pasword-handler/(:any)', 'AuthController::resetPasswordhandler/$1', ['as' => 'admin.reset-password-handler']);
    });
});
