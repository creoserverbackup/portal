<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    /**
     * scope
     */

    public function scopeStatusOn(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('status',1);
    }
}
