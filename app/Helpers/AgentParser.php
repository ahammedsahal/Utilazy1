<?php
namespace App\Helpers;
class AgentParser {
    public static function parse($userAgent) {
        $userAgent = $userAgent ?? 'Unknown';
        $device = 'Desktop';
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            $device = 'Tablet';
        } elseif (preg_match('/(mobi|ipod|iphone|blackberry|opera mini|femobi|windows phone)/i', $userAgent)) {
            $device = 'Mobile';
        }
        $browser = 'Other';
        if (preg_match('/msie/i', $userAgent) && !preg_match('/opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/edge/i', $userAgent)) {
            $browser = 'Edge';
        }
        return ['device' => $device, 'browser' => $browser];
    }
    public static function detectCountry() {
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) return $_SERVER['HTTP_CF_IPCOUNTRY'];
        if (!empty($_SERVER['HTTP_X_COUNTRY_CODE'])) return $_SERVER['HTTP_X_COUNTRY_CODE'];
        if (Env::get('APP_ENV') === 'development') {
            $countries = ['US', 'CA', 'GB', 'DE', 'FR', 'IN', 'AU', 'JP', 'BR', 'ZA'];
            return $countries[array_rand($countries)];
        }
        return 'Unknown';
    }
}