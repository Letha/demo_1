<?php

    namespace App\Controller\Front;

    use const App\APP_DIR;

    use App\Controller\Action\ActionControllerInterface;
    use App\GlobalModule\DIContainer\ModularDIContainerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;
    
    class Router implements RouterInterface
    {
        /**
         * @var array $routes Array of request's URIs as keys and controller's ids as values
         * @var ModularDIContainerInterface $diContainer
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $routes,
            $diContainer,
            $globalExceptionsManager;

        public function __construct(
            ModularDIContainerInterface $diContainer,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->diContainer = $diContainer;
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();

            $routesPath = APP_DIR . '/src/config/controller/routes.php';
            $this->routes = include $routesPath;
            if ($this->routes === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Router cannot include routes file.'
                );
            }
        }

        /**
         * @see RouterInterface For general description
         */
        public function hasRoute(): bool
        {
            if ($this->getRouteKeyIfExists() !== null) {
                return true;
            }
            return false;
        }

        /**
         * @see RouterInterface For general description
         */
        public function processRoute(): void
        {
            // get appropriate URI pattern for current client request
            $uriPattern = $this->getRouteKeyIfExists();
            if ($uriPattern === null) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'No route for this request URI.'
                );
            }
            // get internal route (controller namespace's part) by routes' array key
            $internalRoute = $this->routes[$uriPattern];

            // get args for controller from request URI 
            $requestUri = $this->getRequestUri();
            $matchingStatus = preg_match("~$uriPattern~", $requestUri, $args);
            if ($matchingStatus === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if (count($args) !== 0) {
                array_shift($args);
            }

            // get controller full name, check it for implementation of ActionControllerInterface
            $controllerFullName = 'App\\Controller\\Action\\' . $internalRoute;
            $controller = $this->diContainer->getClassInstance($controllerFullName);
            $actionControllerInterfaceFullName = ActionControllerInterface::class;
            if (!($controller instanceof $actionControllerInterfaceFullName)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "Controller do not implement $actionControllerInterfaceFullName."
                );
            }

            // run controller's function with recieved args
            $processingResult = call_user_func_array(array($controller, 'processClientRequest'), $args);
            if ($processingResult === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Cannot run controller.'
                );
            }
        }

        /**
         * Returns $_SERVER['REQUEST_URI'] trimmed by "/".
         */
        private function getRequestUri(): string
        {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        /**
         * Returns key (if exists) of routes array for current client request.
         */
        private function getRouteKeyIfExists(): ?string
        {
            $requestUri = $this->getRequestUri();
            foreach ($this->routes as $uriPattern => $route) {
                $matchingStatus = preg_match("~$uriPattern~", $requestUri);
                if ($matchingStatus === false) {
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'Unexpected behavior.'
                    );
                }
                if ($matchingStatus === 1) {
                    return $uriPattern;
                }
            }
            return null;
        }
    }