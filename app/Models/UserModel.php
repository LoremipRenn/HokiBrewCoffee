<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'tb_user';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama',
        'username',
        'email',
        'password',
        'role_id',
        'login_attempts',
        'locked_until',
        'created_at',
    ];

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}
