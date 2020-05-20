<?php

    namespace App\Model\User\Files;

    /** Pushing accounts' images' files to server's storage. */
    interface UploadInterface
    {
        /**
         * @param $userLogin Account's login to which a file belongs
         * @param $fileData A file to upload. Has data same as $_FILES[{any_file}]
         */
        public function uploadUserFile(string $userLogin, array $fileData);
    }