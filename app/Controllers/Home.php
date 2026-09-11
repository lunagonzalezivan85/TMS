<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Sistema de Gestión de Mantenimiento de Vehículos',
            'subtitle' => 'Mantén tu flota en perfecto estado'
        ];
        return view('home/dashboard', $data);
    }
    
    public function login(): string
    {
        return view('auth/login');
    }
    
    public function register(): string
    {
        return view('auth/register');
    }
}
