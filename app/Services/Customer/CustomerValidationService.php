<?php

namespace App\Services\Customer;

use App\Models\Customers;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerValidationService
{

    public function check($data, &$errors)
    {
        $result = [];
//        $query = Customers::where('phone', $data['phone']);
//
//        if (!empty($data['customerId'])) {
//            $query->where('customerId', '!=', $data['customerId']);
//        }

//        $customer = $query->first();

//        if (!empty($customer)) {
//            $result['phone'][] = 'This phone number is busy';
//        }

        $query = User::where('email', $data['email']);

        if (!empty($data['customerId'])) {
            $query->where('customerId', '!=', $data['customerId']);
        }

        $customer = $query->first();

        if (!empty($customer)) {
            $result['email'][] = 'The email you are trying to use is already registered in our system. Please contact our customer support to reset your password';
        }

        if (isset($data['avatar']) && !empty($data['avatar'])) {
            $memoryFile = mb_strlen(base64_decode($data['avatar']), '8bit');
            if (($memoryFile / 1024 / 1024) > 2) {
                $result['avatar'][] = 'Image size exceeded 2Mb';
            }

            $imageInfo = getimagesize($data['avatar']);

            if (!empty($imageInfo)) {
                if ($imageInfo[0] > 500 || $imageInfo[1] > 500) {
                    $result['avatar'][] = 'The size of the image is more than 500 x 500';
                }
            } else {
                $svgXML = simplexml_load_file($data['avatar']);
                list($originX, $originY, $relWidth, $relHeight) = explode(' ', $svgXML['viewBox']);

                if ($relWidth > 500 || $relHeight > 500) {
                    $result['avatar'][] = 'The size of the image is more than 500 x 500';
                }
            }
        }

        if (!empty($result)) {
            $errors["errors"] = $result;
        }
    }
}
