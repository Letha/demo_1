<?php

    namespace App;

    class LocalizationSettingsCest
    {
        private $defaultLocale = 'en';
        private $existingLocales = ['en', 'ru'];
        private $localizationSettingRouteTemplate = '/api/config/localization/language/';
        
        public function _before(\FunctionalTester $I)
        {
            $I->haveHttpHeader('Accept-Language', '');
            $I->amOnPage('/');
        }

        public function settingLocaleTest(\FunctionalTester $I)
        {
            foreach ($this->existingLocales as $locale) {
                $I->sendAjaxGetRequest("{$this->localizationSettingRouteTemplate}{$locale}");
                $I->seeCookie('locale');
                $I->assertTrue($I->grabCookie('locale') === $locale, 'Locale of cookie equals locale of request.');
            }
        }
        
        public function gettingLocaleByDefaultTest(\FunctionalTester $I)
        {
            $I->seeElement(['css' => "[data-function='locale-setter'][data-locale='{$this->defaultLocale}'][data-state='active']"]);
            $I->dontSeeElement(['css' => "[data-function='locale-setter'][data-state='active']:not([data-locale='{$this->defaultLocale}'])"]);

            $headersAndResults = [
                'ru' => 'ru',
                'fr-CH, fr;q=0.9, pl;q=0.8, en;q=0.7, *;q=0.5' => 'en',
                'fr-CH, fr;q=0.9, ru;q=0.8, de;q=0.7, *;q=0.5' => 'ru',
            ];
            foreach ($headersAndResults as $headerValue => $locale) {
                $I->haveHttpHeader('Accept-Language', $headerValue);
                $I->amOnPage('/');
                $I->seeElement(['css' => "[data-function='locale-setter'][data-locale='$locale'][data-state='active']"]);
                $I->dontSeeElement(['css' => "[data-function='locale-setter'][data-state='active']:not([data-locale='$locale'])"]);
            }
        }
    }