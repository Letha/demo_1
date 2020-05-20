<?php
    $settingsStates = [
        ini_set('display_errors', '0'),
        ini_set('error_reporting', E_ALL),

        ini_set('session.use_strict_mode', '1'),
        ini_set('session.cookie_httponly', '1'),
        ini_set('session.gc_probability', '0'),
        ini_set('session.gc_maxlifetime', 24 * 60 * 60),

        mb_internal_encoding('UTF-8'),
    ];
    if (in_array(false, $settingsStates, true)) {
        return false;
    } else {
        return true;
    }