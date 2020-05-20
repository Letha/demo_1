<?php

    namespace App\Model\User;

    /** Returning info about accounts. */
    interface AccountsInfoInterface
    {
        /** Answers whether an account with specified login exists. */
        public function whetherAccountExists(string $login): bool;
    }