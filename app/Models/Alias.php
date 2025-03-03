<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    use HasFactory;

    /**
     * Relations
     */

    public function model()
    {
        return $this->morphTo();
    }
}
