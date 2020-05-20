<?php

    namespace App\GlobalModule\Exception\Management;
    use App\GlobalModule\Exception\AppException;

    /** 
     * @see ExceptionsManagerInterface For general description
     */
    abstract class ExceptionsManagerAbstraction implements ExceptionsManagerInterface
    {
        /** 
         * @var string $exceptionMessageArg Standard arg for \Exception constructor. Use to create exception
         * @var int $exceptionCodeArg Standard arg for \Exception constructor. Use to create exception
         * @var \Throwable $exceptionPreviousArg Standard arg for \Exception constructor. Use to create exception
         */
        protected 
            $exceptionMessageArg,
            $exceptionCodeArg,
            $exceptionPreviousArg;

        /** @var \Throwable Use to check if exception is an instance of this */
        protected $exceptionToCheck;

        /** 
        * @see ExceptionsManagerInterface For general description
        */
        public function getExceptionInstance(
            string $exceptionLastName, string $message = '', 
            int $code = 0, \Throwable $previous = null
        ): AppException {
            // set exception's args as class' properties to use in child class
            $this->exceptionMessageArg = $message;
            $this->exceptionCodeArg = $code;
            $this->exceptionPreviousArg = $previous;

            // get a name of a method of child class to create exception instance - and create it
            $instantiationMethodName = "get{$exceptionLastName}Instance";
            if (!method_exists($this, $instantiationMethodName)) {
                throw new AppException("Method $instantiationMethodName does not exist.");
            }
            $exceptionInstance = call_user_func([$this, $instantiationMethodName]);

            $this->exceptionMessageArg = null;
            $this->exceptionCodeArg = null;
            $this->exceptionPreviousArg = null;

            return $exceptionInstance;
        }

        /** 
        * @see ExceptionsManagerInterface For general description
        */
        public function isInstanceOfException(string $exceptionLastNameToCompareWith, \Throwable $exceptionToCompare): bool
        {
            // set compared exception as class' property to use in child class
            $this->exceptionToCheck = $exceptionToCompare;

            // get a name of a method of child class to compare exceptions - and compare
            $instantiationMethodName = "isInstanceOf{$exceptionLastNameToCompareWith}";
            if (!method_exists($this, $instantiationMethodName)) {
                throw new AppException("Method $instantiationMethodName does not exist.");
            }
            $isInstanceOfException = call_user_func([$this, $instantiationMethodName], $exceptionToCompare);

            $this->exceptionToCheck = null;

            return $isInstanceOfException;
        }
    }