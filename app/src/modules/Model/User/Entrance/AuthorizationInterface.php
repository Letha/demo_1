<?php

    namespace App\Model\User\Entrance;

    /** Working with authorization in application. */
    interface AuthorizationInterface
    {
        public function authorize(string $login, string $password);
        public function deauthorize();
        /** Answers whether current session is authorized (it means account is authorized). */
        public function isAuthorized(): bool;
        /** Returns current autorized login. */
        public function getAuthorizedLogin(): string;
    }