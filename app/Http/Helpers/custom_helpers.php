<?php

use Illuminate\Support\Str;

if (!function_exists('generateUUID')) {
    function generateUUID()
    {
        $uuid = Str::uuid()->toString();
        return rtrim(strtr(base64_encode(hex2bin(str_replace('-', '', $uuid))), '+/', '-_'), '=');
    }
}

if (!function_exists('getInitials')) {
    function getInitials($name) {
        $words = explode(" ", $name);
        $initials = "";
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            if (strlen($initials) == 2) break;
        }
        
        return $initials;
    }
}

if (!function_exists('getColorFromInitials')) {
    function getColorFromInitials($initials) {
        $colors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50'];
        
        $index = (ord($initials[0]) + ord($initials[1])) % count($colors);
        
        return $colors[$index];
    }
}
