<?php

    /**
     * Routes to controllers with their actions.
     * 
     * Key means regular expression for $_SERVER['REQUEST_URI'] (trimed by '/').
     * Key can contain regex masks which will make controller's method arguments.
     * Value means controller class name part (any ActionControllerInterface implementation which is in App\Controller\Action namespace).
     */

    return [
        '^$' => 'Page\\IndexController',

        '^api/config/localization/language/([a-z]+)$' => 'API\\Config\\LocalizationController',

        '^api/user/entrance/authorization$' => 'API\\User\\Entrance\\AuthorizationController',
        '^api/user/entrance/registration$' => 'API\\User\\Entrance\\RegistrationController',
        '^api/user/entrance/exit$' => 'API\\User\\Entrance\\ExitController',

        // images/{category}
        '^api/user/files/images/([\w-]+)$' => 'API\\User\\ImageDownloadController',
        // images/{category}/{image_id}
        '^api/user/files/images/([\w-]+)/([\w-]+)$' => 'API\\User\\ImageDownloadController',
    ];