<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\EmailSetting;
use App\Models\OtherSetting;
use App\Models\RecaptchaSetting;
use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::create([
            'company_name' => 'Spécialiste en uniformes',
            'contact-map' => 'https://maps.app.goo.gl/DBU87BVVMfKLpATXA',
            'site_email' => 'sales@tonkit.pro',
            'site_phone' => '+1-855-932-6752',
        ]);

        RecaptchaSetting::create([
            'google_recaptcha_type' => 'no_captcha',
        ]);

        SystemSetting::create([
            'max_upload_size' => '2048',
            'currency_symbol' => '$',
            'currency_symbol_position' => 'prefix',
            'footer_text' => 'Copyright © 2025. All Rights Reserved. <br> yourkit.pro powered by WebOrka Inc',
        ]);

        EmailSetting::create([
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => '2525',
            'mail_username' => '725adb089beee5',
            'mail_password' => 'b63984536f3df4',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'admin@example.com',
            'mail_from_name' => 'Admin',
            'is_enabled' => '1',
        ]);
    }
}
