<?php

    namespace App\Controller;

    use const App\APP_DIR;

    use App\Model\User\Entrance\AuthorizationInterface;
    use App\Model\Config\LocalizationSettingsInterface;
    use App\Model\ViewTemplate\TemplateTextsExtractorInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * Abstraction of controller which intended to work with HTML pages.
     */
    abstract class PageController
    {
        /**
         * @var AuthorizationInterface $authorizationModel
         * @var LocalizationSettingsInterface $localizationSettingsModel
         * @var TemplateTextsExtractorInterface $templateTextsExtractorModel
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        protected
            $authorizationModel,
            $localizationSettingsModel,
            $templateTextsExtractorModel,
            $globalExceptionsManager;

        function __construct(
            AuthorizationInterface $authorizationModel,
            LocalizationSettingsInterface $localizationSettingsModel,
            TemplateTextsExtractorInterface $templateTextsExtractorModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
            $this->authorizationModel = $authorizationModel;
            $this->localizationSettingsModel = $localizationSettingsModel;
            $this->templateTextsExtractorModel = $templateTextsExtractorModel;
        }

        /**
         * Insert the code of specified view template.
         * 
         * @param $viewPathPart Path of view template in /src/views/pages/.
         * @param $viewData Data to use in view template.
         * 
         * @throws $this->globalExceptionsManager:AppException If cannot include view file.
         * @uses self::fillViewDataArray()
         */
        final protected function insertView(string $viewPathPart, ?array $viewData = null): void
        {
            $viewData = $this->fillViewDataArray($viewPathPart, $viewData);

            $viewFilePath = APP_DIR . "/src/views/pages/$viewPathPart.php";
            $includeState = include $viewFilePath;
            if ($includeState === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "Cannot include view (view file path: $viewFilePath)."
                );
            }
        }

        /**
         * Push common data for view's template to array (escapes for HTML, do not escape for JS).
         */
        private function fillViewDataArray(string $viewPathPart, ?array $viewData): array
        {
            $viewData['locale'] = $this->localizationSettingsModel->getLocale();
            $viewData['texts'] = $this->templateTextsExtractorModel->getTexts($viewPathPart);
            $viewData['isAuthorized'] = $this->authorizationModel->isAuthorized();

            array_walk_recursive($viewData, function (&$value) {
                $value = htmlentities($value, \ENT_QUOTES | \ENT_HTML5, 'UTF-8');
            });

            $viewData['scriptTexts'] = $this->templateTextsExtractorModel->getTexts($viewPathPart, true);

            return $viewData;
        }
    }