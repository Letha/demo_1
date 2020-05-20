<?php
    namespace App;
    const
        APP_NS = 'App',
        APP_DIR = __DIR__ . '\..',
        APP_MODULES_DIR = APP_DIR . '/src/modules';

    function recursiveGlob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags); 
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, recursiveGlob($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }