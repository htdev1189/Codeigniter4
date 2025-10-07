<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function index()
    {
        $session = session();
        var_dump($session->get('userData'));
        echo "admin dashboard";
    }
}
