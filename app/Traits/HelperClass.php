<?php

namespace App\Traits;

use App\Models\User;

trait HelperClass
{
    public function notification($message, $alertType)
    {
        $notification = [
            'message' => $message,
            'alert-type' => $alertType,
        ];

        return $notification;
    }

    public function generateMember()
    {
        $membership_no = '';

        $last_user = User::orderBy('id', 'desc')->first();
        if ($last_user == null) {
            $firstREQ = '1';
            $membership_no = 'HM00I'.date('Y').'00'.$firstREQ;
        } else {
            $last_user_id = $last_user->id + 1;
            $membership_no = 'HM00I'.date('Y').'00'.$last_user_id + 1;
        }

        return $membership_no;
    }

    /**
     * Price formatted
     */
    public static function Price($value, $isWithCurrency)
    {
        $Result = number_format($value, 3);
        if ($isWithCurrency) {
            $Result .= ' '.'ريال';
        }

        return $Result;
    }

    public function splitFullName($fullName)
    {
        // Split the full name by spaces
        $nameParts = explode(' ', $fullName);

        // Assign values to variables
        $frist_name = $nameParts[0] ?? '';  // First name is always the first part
        $second_name = $nameParts[1] ?? ''; // Middle name (if available)
        $third_name = $nameParts[2] ?? '';   // Last name (if available)
        $last_name = $nameParts[3] ?? '';   // Last name (if available)

        return [
            'frist_name' => $frist_name,
            'second_name' => $second_name,
            'third_name' => $third_name,
            'last_name' => $last_name,
        ];
    }
}
