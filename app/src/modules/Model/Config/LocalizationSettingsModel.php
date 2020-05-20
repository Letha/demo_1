<?php

    namespace App\Model\Config;

    use const App\APP_DIR;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see LocalizationSettingsInterface For general description
     */
    class LocalizationSettingsModel implements LocalizationSettingsInterface
    {
        /**
         * @var string $defaultLocale
         * @var array $allowedLocales
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $defaultLocale,
            $allowedLocales,
            $globalExceptionsManager;

        function __construct(
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();

            // get default locale and allowed locales from config
            $localizationConfig = include APP_DIR . '/src/config/core/localization/config.php';
            if ($localizationConfig === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Cannot include localization configuration.'
                );
            }
            if (
                !isset($localizationConfig['defaultLocale']) ||
                !isset($localizationConfig['allowedLocales'])
            ) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Localization configuration has unexpected structure.'
                );
            }
            $this->defaultLocale = $localizationConfig['defaultLocale'];
            $this->allowedLocales = $localizationConfig['allowedLocales'];
        }

        /** 
         * @see LocalizationSettingsInterface For general description
         */
        public function getLocale(): string
        {
            /**
             * if cookie for locale is set - return it,
             * else return locale according to $_SERVER['HTTP_ACCEPT_LANGUAGE'],
             * if cannot - return default locale
             */
            if (
                isset($_COOKIE['locale']) && 
                in_array($_COOKIE['locale'], $this->allowedLocales)
            ) {
                return $_COOKIE['locale'];
            } else {
                $priorityLanguage = $this->getPriorityClientLanguage();
                return $priorityLanguage === null ? $this->defaultLocale : $priorityLanguage;
            }
        }

        /** 
         * @see LocalizationSettingsInterface For general description
         */
        public function hasLocale(string $locale): bool
        {
            if (in_array($locale, $this->allowedLocales)) {
                return true;
            } else {
                return false;
            }
        }

        /** 
         * @see LocalizationSettingsInterface For general description
         */
        public function setLocale(string $locale): void
        {
            if ($this->hasLocale($locale)) {
                if (
                    !setcookie(
                        'locale', 
                        $locale, 
                        strtotime('+30 days'),
                        '/'
                    )
                ) {
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'Unexpected behavior.'
                    );
                    
                };

            } else {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'That locale does not exist.'
                );
            }
        }

        /**
         * Returns most important language id from $_SERVER['HTTP_ACCEPT_LANGUAGE'] 
         * which matches one of allowed locales or null
         */
        private function getPriorityClientLanguage(): ?string
        {
            if (
                !isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ||
                $_SERVER['HTTP_ACCEPT_LANGUAGE'] === ''
            ) {
                return null;
            }

            preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]), $matches);
            $languagesPriorities = array_combine($matches[1], $matches[2]);
            foreach ($languagesPriorities as $language => $priority) {
                $languagesPriorities[$language] = $priority ? $priority : 1;
            }
            arsort($languagesPriorities);

            foreach ($languagesPriorities as $language => $languagePriority) {
                if (in_array($language, $this->allowedLocales, true)) {
                    return $language;
                }
            }
            return null;
        }
    }