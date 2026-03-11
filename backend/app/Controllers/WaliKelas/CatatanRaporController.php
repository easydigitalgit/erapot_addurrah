<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class CatatanRapoController extends WaliKelasBaseController
{
    public function index(): string
    {
        $data = [
            'user' => 'Admin',
            'navigations' => $this->getSidebarMenu()
        ];
        return view('WaliKelas\catatan-rapor', $data); 
    }
}