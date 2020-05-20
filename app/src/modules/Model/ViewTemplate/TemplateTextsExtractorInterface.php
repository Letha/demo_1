<?php

    namespace App\Model\ViewTemplate;

    /** Returning texts of tempates (views). */
    interface TemplateTextsExtractorInterface
    {
        /**
         * Returns texts of specified template.
         * 
         * @param $templatePathPart Template to which texts belong.
         *     Part of path inside "/src/config/core/localization/texts/" with no file's extension (like "home/library")
         * @return array Can have several of this keys ['words' => string[], 'phrases' => string[], 'articles' => string[]]. Cannot be empty
         */
        public function getTexts(string $templatePathPart): array;
    }