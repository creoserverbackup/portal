<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|mixed $type
 * @property mixed $value
 * @property mixed|string $path
 * @property int|mixed $time
 * @property mixed|string $disk_name
 * @property mixed|string $file_name
 * @property int|mixed $file_size
 * @property mixed|string $type_file
 */
class File extends Model
{

    protected $table = 'file';
    
    use HasFactory;

    const FILE_TYPE = [
            'ticket' => 10,
            'customer_logo' => 20,
            'customer_background' => 21,
            'rma_create' => 30,
            'chat' => 100,
    ];

    /**
     * Attributes for `mass assigment`
     * @var array
     */
    protected $fillable = [
            'value',
    ];
}
