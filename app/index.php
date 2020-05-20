<?php

    declare(strict_types=1);
    namespace App;

    use App\Controller\Front\FrontController;
    use App\GlobalModule\AutoloadEngine;
    use App\GlobalModule\DIContainer\AppDIContainer;

    const APP_DIR = __DIR__;
    
    try {
        /** System settings. */
        $includingState = include_once APP_DIR . '/src/config/system-setting.php';
        if ($includingState === false) {
            throw new \Exception('Cannot include system settings file.');
        }
        /** Autoload. */
        $includingState = include_once APP_DIR . '/vendor/autoload.php';
        if ($includingState === false) {
            throw new \Exception('Cannot include autoload file.');
        }

        $diContainer = new AppDIContainer();

        $frontController = $diContainer->getClassInstance(FrontController::class);
        $frontController->run();
        
    } catch (\Throwable $throwable) {
        // last chance to report error to client
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Internal server error.';
        // for debug
        throw $throwable;
    }

 