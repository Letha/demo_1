<?php

    namespace App\Controller\Action\API\Config;

    use App\Model\Config\LocalizationSettingsInterface;
    use App\Controller\Action\ActionControllerInterface;

    /**
     * Sets localization.
     */
    class LocalizationController implements ActionControllerInterface
    {
        /**
         * @var LocalizationSettingsInterface
         */
        private $localizationSettingsModel;

        function __construct(
            LocalizationSettingsInterface $localizationSettingsModel
        ) {
            $this->localizationSettingsModel = $localizationSettingsModel;
        }

        /**
         * @see ActionControllerInterface For general description
         * @param $locale Locale to set
         */
        public function processClientRequest(string $locale = null): void
        {
            if ($locale === null) {
                header('HTTP/1.1 400 Bad Request');
            } else {
                if ($this->localizationSettingsModel->hasLocale($locale)) {
                    $this->localizationSettingsModel->setLocale($locale);
                    header('HTTP/1.1 200 OK');
                } else {
                    header('HTTP/1.1 404 Not Found');
                }
            }
        }
    }