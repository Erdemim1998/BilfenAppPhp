<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DashboardController
{
    public function index(Request $request): View|Factory|Application
    {
        $userId = $request->query('userId');
        $user = Http::get("http://localhost:8000/api/users/GetUser/{$userId}")->json();
        $role = Http::get("http://localhost:8000/api/roles/GetRole/{$user['RoleId']}")->json();

        return view('dashboard', [
            'userId' => $userId,
            'roleName' => $role['Name'],
            'userFullName' => $user['FirstName'].' '.$user['LastName'],
        ]);
    }
}
