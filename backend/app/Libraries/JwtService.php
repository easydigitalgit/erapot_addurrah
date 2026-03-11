<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private $key;

    public function __construct()
    {
        // Sebaiknya simpan key ini di .env
        $this->key = getenv('JWT_SECRET') ?: 'rahasia_smpit_addurrah_super_secure_key';
    }

    public function generateToken(array $data)
    {
        $issuedAt   = time();
        $expire     = $issuedAt + 3600; // Token berlaku 1 jam

        $payload = [
            'iat'  => $issuedAt,
            'exp'  => $expire,
            'uid'  => $data['id'],
            'role' => $data['role_id'],
            'username' => $data['username']
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    // Fungsi validasi token (dipakai di AuthFilter nanti)
    public function validateToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->key, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
}