<?php

    namespace App\GlobalModule\DIContainer;

    /** Intended to create and return class instances. */
    interface ModularDIContainerInterface
    {
        /**
         * @return object
         */
        public function getClassInstance(string $classFullName);
    }