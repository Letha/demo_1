<?php

    namespace App\Controller\Action;

    /**
     * Process certain client HTTP request.
     */
    interface ActionControllerInterface
    {
        public function processClientRequest();
    }