<?php

namespace App\Models;

use App\Services\Customer\CustomerUidService;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $table = 'user_settings';

    protected $primaryKey = 'id';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'uid',
        'sound',
        'sendEmail',
        'googlePushMessages',
        'importantThings',
        'deliveryServiceTracking',
        'darkMode',
        'classicMode',
        'lifeLine',
        'background'
    ];

    public static function getUserSetting()
    {
        $customerUidService = new CustomerUidService();
        $model = UserSettings::firstOrNew(['uid' => $customerUidService->checkApiUid()]);
        $model->save();
        return $model;
    }

    public function _user()
    {
        return $this->belongsTo('App\Models\User', 'id', 'uid');
    }

}
