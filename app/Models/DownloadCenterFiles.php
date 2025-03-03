<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DownloadCenterFiles
 *
 * @property int $id
 * @property string $url
 * @property string $name
 * @property string|null $time
 * @property string|null $category
 * @property string|null $type
 * @property int|null $size
 * @property int|null $countFiles
 * @property int|null $unload
 * @property int|null $sort
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles query()
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereCountFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereUnload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DownloadCenterFiles whereUrl($value)
 * @mixin \Eloquent
 */
class DownloadCenterFiles extends Model
{
    /**
     * @var string
     */
    protected $table = 'download_center_files';

    /**
     * Database is prepared for selecting correct timezone
     * @var bool
     */
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @var string[]
     */
    protected $fillable = [
        'url',
        'name',
        'time',
        'category',
        'type',
        'size',
        'countFiles',
        'unload',
        'sort',
        'description',
        ];
}
