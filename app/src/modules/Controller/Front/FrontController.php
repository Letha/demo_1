<?php

    namespace App\Controller\Front;

    use const App\APP_DIR;

    use App\Model\User\Entrance\AuthorizationInterface;
    use App\Model\Config\LocalizationSettingsInterface;
    use App\Model\ViewTemplate\TemplateTextsExtractorInterface;
    use App\Controller\PageController;
    use App\Controller\Front\RouterInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    class FrontController extends PageController implements FrontControllerInterface
    {
        /**
         * @var RouterInterface
         */
        private $router;
        private $httpResponseTitles = [
            '404' => 'Not Found',
            '500' => 'Internal Server Error',
        ];

        function __construct(
            RouterInterface $router,
            AuthorizationInterface $authorizationModel,
            LocalizationSettingsInterface $localizationSettingsModel,
            TemplateTextsExtractorInterface $templateTextsExtractorModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            parent::__construct(
                $authorizationModel, $localizationSettingsModel,
                $templateTextsExtractorModel, $exceptionsManagersFactory
            );
            $this->router = $router;
        }

        /**
         * @see FrontControllerInterface For general description
         * @uses self::applyErrorResponse()
         */
        public function run(): void
        {
            try {
                if (!$this->router->hasRoute()) {
                    $this->applyErrorResponse('404');
                    return;
                }
                $this->router->processRoute();

            } catch (\Throwable $throwable) {
                $this->applyErrorResponse('500');
                throw $throwable;
            }
        }

        /**
         * Send error response header and view.
         * @throws $this->globalExceptionsManager:AppException If there is no response title for required response code
         */
        private function applyErrorResponse(string $responseCode): void
        {
            // check for HTTP response description's existence
            if (!array_key_exists($responseCode, $this->httpResponseTitles)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "Trying to use absent key ($responseCode) of this->httpResponseTitles array in FrontController."
                );
            }
            $responseTitle = $this->httpResponseTitles[$responseCode];

            header("HTTP/1.1 $responseCode $responseTitle");
            $this->insertView("errors/$responseCode");
        }
    }