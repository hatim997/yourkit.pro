<?php

namespace App\Utils;

use App\Models\Setting;

class Helper {

    public static function setting($name)
    {
        $items = Setting::all();
        $settings = [];
        foreach ($items as $key => $row) {
            $settings[$row->setting_name] = $row->value;
        }

        return $settings[$name]  ?? "";
    }

    public static function keyValueExists($arrays, $key, $value)
    {
        foreach ($arrays as $array) {
            if (isset($array[$key]) && $array[$key] == $value) {
                return true;
            }


        }
        return false;
    }

    public static function OrderStatus(){
        return [1 => 'Under Review', 2 => 'Design Approved', 3 =>  'Waiting for Garments', 4 => 'Sent to Graphic Designer', 5 =>  'In production'];
    }

    public static function PaymentStatus(){
        return [1 => 'Pending', 2 => 'Success', 3 =>  'Failed', 4 => 'Held for Review'];
    }

    public static function getOperationalContacts(){
        return ["email" => self::setting("site_email"), "phone_number" => self::setting("site_phone")];
    }

    public static function getCssVersion(){
        return "1.1.9" ;
    }

}



