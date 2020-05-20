<?php

    namespace App\Model\Config;
    
    /** Setting/getting application localization settings. */
    interface LocalizationSettingsInterface
    {
        /** Returns current locale. */
        public function getLocale(): string;
        /** Answers whether application can use specified locale. */
        public function hasLocale(string $locale): bool;
        /** Sets specified locale. */
        public function setLocale(string $locale);
    }