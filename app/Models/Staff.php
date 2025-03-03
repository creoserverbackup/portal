<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    public $timestamps = false;

    protected $fillable = [
            'uid',
    ];

    public function roles()
    {
        return $this->hasOne(Role::class,'roleId','roleId');
    }
}
