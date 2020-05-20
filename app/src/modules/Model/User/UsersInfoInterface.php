<?php

    namespace App\Model\User;

    /** Returning info about users. */
    interface UsersInfoInterface
    {
        /** Returns info about user which has specified login. */
        public function getUserData(string $login): array;
    }