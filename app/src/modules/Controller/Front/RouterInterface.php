<?php

    namespace App\Controller\Front;

    /** Works with internal routes accordingly to request URI. */
    interface RouterInterface
    {
        /** Whether route for this kind of client requests exists. */
        public function hasRoute(): bool;
        /** Process client request. */
        public function processRoute();
    }