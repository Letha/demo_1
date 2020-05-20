<?php

    namespace App\GlobalModule\Exception;
    use App\GlobalModule\Exception\Management\ExceptionsManagerAbstraction;

    final class ExceptionsManager extends ExceptionsManagerAbstraction
    {
        protected function getAppExceptionInstance(): AppException
        {
            return new AppException(
                $this->exceptionMessageArg, $this->exceptionCodeArg, $this->exceptionPreviousArg
            );
        }
        protected function isInstanceOfAppException(): bool
        {
            return $this->exceptionToCheck instanceof AppException;
        }
    }