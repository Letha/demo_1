<?php

    namespace App\GlobalModule\Exception\Management;
    use App\GlobalModule\Exception\AppException;

    /**
     * Layer of abstraction for creating and "instanceof" comparing exceptions instances of single namespace (same namespace means same directory).
     * 
     * File of concrete exception manager must be named "ExceptionsManager.php" and placed at the same directory as managed exceptions.
     */
    interface ExceptionsManagerInterface
    {
        /**
         * @param $exceptionLastName Last part of required exception's full class name (this exception is placed in exception manager's namespace)
         * @param $message Standard arg for \Exception constructor
         * @param $code Standard arg for \Exception constructor
         * @param $previous Standard arg for \Exception constructor
         */
        public function getExceptionInstance(
            string $exceptionLastName, string $message = '', 
            int $code = 0, \Throwable $previous = null
        ): AppException;

        /**
         * Checks if recieved exception is an instance of specified exception of exception manager's namespace
         * @param $exceptionLastNameToCompareWith Last part of exception's full class name (this exception is intended to compare with and placed in exception manager's namespace)
         * @param $exceptionToCompare Exception instance to compare with one specified as first argument
         */
        public function isInstanceOfException(string $exceptionLastNameToCompareWith, \Throwable $exceptionToCompare): bool;
    }