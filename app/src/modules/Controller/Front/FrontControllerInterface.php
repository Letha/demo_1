<?php

    namespace App\Controller\Front;

    /**
     * Receives client request and sends appropriate answer back. 
     */
    interface FrontControllerInterface
    {
        public function run();
    }