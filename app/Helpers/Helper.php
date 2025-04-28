<?php

namespace App\Helpers;

use App\Models\BusinessSetting;
use App\Models\CompanySetting;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class Helper
{
    public static function dashboard_route()
    {
        $user = User::find(Auth::user()->id);
        $route = $user->role->role.'.dashboard';
        return $route;
    }
    public static function getLogoLight()
    {
        return CompanySetting::first()->logo_light ?? asset('assets/img/logo/logo_light.png');
    }
    public static function getLogoDark()
    {
        return CompanySetting::first()->logo_dark ?? asset('assets/img/logo/logo_light.png');
    }
    public static function getFavicon()
    {
        return CompanySetting::first()->favicon ?? asset('assets/img/favicon/favicon.jpg');
    }
    public static function getCompanyName()
    {
        return CompanySetting::first()->company_name ?? env('APP_NAME');
    }
    public static function getPaymentMode()
    {
        return CompanySetting::first()->payment_mode ?? 'sandbox';
    }
    public static function sitePhone()
    {
        $companySetting = CompanySetting::with('country')->first();
        if ($companySetting && $companySetting->country) {
            $phone = $companySetting->country->phone_code . ' ' . $companySetting->site_phone;
            return $phone;
        }else{
            return '000000000000';
        }
    }
    public static function siteEmail()
    {
        return CompanySetting::first()->site_email ?? 'sales@yourkit.pro';
    }
    public static function facebook()
    {
        return CompanySetting::first()->facebook ?? 'https://www.facebook.com/';
    }
    public static function twitter()
    {
        return CompanySetting::first()->twitter ?? 'https://www.x.com/';
    }
    public static function linkedin()
    {
        return CompanySetting::first()->linkedin ?? 'https://www.linkedin.com/';
    }
    public static function getTimezone()
    {
        $systemSetting = SystemSetting::with('timezone')->first();
        return $systemSetting->timezone->name ?? env('APP_TIMEZONE', 'UTC');
    }
    public static function getDefaultLanguage()
    {
        $systemSetting = SystemSetting::with('language')->first();
        return $systemSetting->language->iso_code ?? 'en';
    }
    public static function getfooterText()
    {
        return SystemSetting::first()->footer_text ?? 'All Copyrights Reserved';
    }
    public static function getMaxUploadSize()
    {
        $sizeInKB = SystemSetting::first()->max_upload_size ?? 2048; // Stored in KB

        return self::humanReadableSize($sizeInKB * 1024); // Convert KB to Bytes
    }
    public static function getCurrencySymbol()
    {
        $currencySymbol = SystemSetting::first()->currency_symbol ?? '$';
        return $currencySymbol;
    }

    // Helper function to format size into KB, MB, GB, etc.
    public static function humanReadableSize($bytes, $decimals = 2)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $sizes[$factor];
    }


    // example use of currency format {{ \App\Helpers\Helper::formatCurrency($price) }}
    public static function formatCurrency($amount)
    {
        $currencySetting = SystemSetting::first();

        if (!$currencySetting) {
            return $amount; // Return the amount as is if settings are not found
        }

        $symbol = $currencySetting->currency_symbol;
        $position = $currencySetting->currency_symbol_position; // 'prefix' or 'postfix'

        if ($position === 'prefix') {
            return $symbol . $amount;
        }

        return $amount . $symbol;
    }

    public static function bundleDefaultImage($subcategories)
    {
        $subcategories = is_array($subcategories) ? $subcategories : json_decode($subcategories, true);
        if (!is_array($subcategories)) {
            return asset('bundleDefaultImages/default.png');
        }

        // Sort for unordered match
        sort($subcategories);

        $key = implode(',', $subcategories);

        $combinationImages = [
            '1,2,3,4' => 'tshirt-hoodie-cap-beanie-bundle.png',
            '1,2,3'   => 'tshirt-hoodie-cap-bundle.png',
            '1,2,4'   => 'tshirt-hoodie-beanie-bundle.png',
            '1,2'     => 'tshirt-hoodie-bundle.png',
            '3,4'     => 'cap-beanie-bundle.png',
            '1'       => 'tshirt-bundle.png',
            '2'       => 'hoodie-bundle.png',
            '3'       => 'cap-bundle.png',
            '4'       => 'beanie-bundle.png',
        ];

        if (isset($combinationImages[$key])) {
            return asset('bundleDefaultImages/' . $combinationImages[$key]);
        }

        return asset('bundleDefaultImages/default.png');
    }

    public static function renderRecaptcha($formId, $action = 'register')
    {
        if (config('captcha.version') === 'v3') {
            return self::renderRecaptchaV3($formId, $action);
        }
    }

    private static function renderRecaptchaV3($formId, $action)
    {
        $siteKey = config('captcha.sitekey');
        return <<<HTML
            <script src="https://www.google.com/recaptcha/enterprise.js?render={$siteKey}"></script>
            <script>
                function handleRecaptcha(formId, action) {
                    document.getElementById(formId).addEventListener('submit', function(e) {
                        e.preventDefault();
                        grecaptcha.enterprise.ready(async () => {
                            try {
                                const token = await grecaptcha.enterprise.execute('{$siteKey}', { action: action });
                                const form = document.getElementById(formId);
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'g-recaptcha-response';
                                input.value = token;
                                form.appendChild(input);
                                form.submit();
                            } catch (error) {
                                console.error('Error executing reCAPTCHA:', error);
                            }
                        });
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    handleRecaptcha('{$formId}', '{$action}');
                });
            </script>
        HTML;
    }
}
