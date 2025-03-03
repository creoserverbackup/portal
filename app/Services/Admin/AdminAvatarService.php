<?php

namespace App\Services\Admin;

class AdminAvatarService
{

    const OLEG_UID = 3;

    public function getAvatar($adminUid)
    {
        $avatarPath = storage_path('app/users/users_pics/' .  $adminUid . '.jpg');

        if (file_exists($avatarPath)) {
            return base64_encode(file_get_contents($avatarPath));
        } else {
            $avatarPath = storage_path('app/users/users_pics/' .  self::OLEG_UID . '.jpg');
            return base64_encode(file_get_contents($avatarPath));
        }
    }
}