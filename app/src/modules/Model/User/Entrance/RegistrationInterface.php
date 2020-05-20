<?php

    namespace App\Model\User\Entrance;

    /** Registration of application accounts (users). */
    interface RegistrationInterface
    {
        /**
         * @param $login Login of account for registration
         * @param $password Password of account for registration
         * @param $userData Any provided user's data to link it to account
         */
        public function registerUser(string $login, string $password, ?array $userData = null);
    }