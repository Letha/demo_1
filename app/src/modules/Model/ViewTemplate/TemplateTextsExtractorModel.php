<?php

    namespace App\Model\ViewTemplate;

    use const App\APP_DIR;

    use App\Model\Config\LocalizationSettingsInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see TemplateTextsExtractorInterface For general description
     */
    class TemplateTextsExtractorModel implements TemplateTextsExtractorInterface
    {
        /**
         * @var LocalizationSettingsInterface $localizationSettingsModel
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $localizationSettingsModel,
            $globalExceptionsManager;

        private 
            $pathToTextsDir       = APP_DIR . '/src/config/core/localization/texts',
            $pathToGlobalTextsDir = APP_DIR . '/src/config/core/localization/texts/_global';

        private $textsArrayAllowedSections = [
            'words',
            'phrases',
            'articles',
        ];

        function __construct(
            LocalizationSettingsInterface $localizationSettingsModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
            $this->localizationSettingsModel = $localizationSettingsModel;
        }

        /**
         * @see TemplateTextsExtractorInterface For general description
         * @param $needFrontScriptTexts If need receive texts for JavaScript (but not other texts)
         */
        public function getTexts(string $templatePathPart, bool $needFrontScriptTexts = false): array
        {
            $currentLanguage = $this->localizationSettingsModel->getLocale();
            $includeGlobalTextsPath = "{$this->pathToGlobalTextsDir}/$currentLanguage";
            $includePageTextsPath = "{$this->pathToTextsDir}/$templatePathPart/$currentLanguage";
            if ($needFrontScriptTexts) {
                $includeGlobalTextsPath .= '/dynamic.php';
                $includePageTextsPath .= '/dynamic.php';
            } else {
                $includeGlobalTextsPath .= '/static.php';
                $includePageTextsPath .= '/static.php';
            }

            @ $globalTexts = include $includeGlobalTextsPath;
            @ $pageTexts = include $includePageTextsPath;
            if ($globalTexts === false && $pageTexts === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'No text for that template.'
                );
            }

            $arrayOfExistingTextsArrays = [];
            $this->processTextsArrayIncluding($arrayOfExistingTextsArrays, $globalTexts);
            $this->processTextsArrayIncluding($arrayOfExistingTextsArrays, $pageTexts);

            return $this->mergeTextsArrays(...$arrayOfExistingTextsArrays);
        }

        private function processTextsArrayIncluding(array &$arrayOfExistingTextsArrays, $textsArrayIncluding): void
        {
            if ($textsArrayIncluding !== false) {
                if (!is_array($textsArrayIncluding)) {
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'Including texts file do not return an array.'
                    );
                }
                $arrayOfExistingTextsArrays[] = $textsArrayIncluding;
            }
        }

        private function mergeTextsArrays(array ...$textsArrays): array
        {
            $finalTextsArray = [];
            foreach ($textsArrays as $textsArray) {
                foreach ($this->textsArrayAllowedSections as $textsArraySection) {
                    if (isset($textsArray[$textsArraySection])) {

                        if (!isset($finalTextsArray[$textsArraySection])) {
                            $finalTextsArray[$textsArraySection] = [];
                        }
                        $finalTextsArray[$textsArraySection] = 
                            $this->mergeTextsArraysSection($textsArraySection, $finalTextsArray, $textsArray);
                    }
                }
            }
            return $finalTextsArray;
        }
        private function mergeTextsArraysSection(string $mergingSection, array $mainArray, array $secondArray)
        {
            return array_merge($mainArray[$mergingSection], $secondArray[$mergingSection]);
        }
    }