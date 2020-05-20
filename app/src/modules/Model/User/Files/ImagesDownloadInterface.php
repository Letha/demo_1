<?php

    namespace App\Model\User\Files;

    /** Downloading accounts' images' files to clent. */
    interface ImagesDownloadInterface
    {
        /** 
         * Returns file's data.
         * 
         * @param $login Look for a file belonging to this login (account)
         * @param $imageCategory Images' category belonging to specified login (account)
         * @param $imageId Image's ID in specified images' category belonging to specified login (account)
         * 
         * @return ['mimeType' => {string}, 'content' => {string}] file's data
         */
        public function getFileData(string $login, string $imageCategory, string $imageId): array;
    }