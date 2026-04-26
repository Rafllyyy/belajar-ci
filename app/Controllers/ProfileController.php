<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index()
    {
        $session = session();
        $data = [
            'title'        => 'Profil Pengguna',
            'username'     => $session->get('username'),
            'email'        => $session->get('email'),
            'role'         => $session->get('role'),
            'login_time'   => $session->get('login_time'),
            'is_logged_in' => $session->has('isLoggedIn'),
        ];
        return view('v_profile', $data);
    }
}
