<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Récupère la valeur d'un paramètre en base.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
