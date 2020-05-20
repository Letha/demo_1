<?php

    namespace App\GlobalModule\Exception\Management;
    use App\GlobalModule\Exception\ExceptionsManager as GlobalExceptionsManager;

    /**
     * @see ExceptionsManagersFactoryInterface For general description
     */
    class ExceptionsManagersFactory implements ExceptionsManagersFactoryInterface
    {
        /** @var ExceptionsManagerInterface */
        private $globalExceptionsManager;

        function __construct()
        {
            $this->globalExceptionsManager = $this->getExceptionsManager();
        }

        /**
         * @see ExceptionsManagersFactoryInterface For general description
         */
        public function getExceptionsManager(
            ?string $relatedModuleFullName = null
        ): ExceptionsManagerInterface {
            if ($relatedModuleFullName === null) {
                return new GlobalExceptionsManager();
            }

            /**
             * module's exceptions manager is in the same (as a module) namespace - 
             * so get and return this manager instance
             */

            $lastBackslashPosition = strrpos($relatedModuleFullName, '\\');
            if ($lastBackslashPosition === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }

            $relatedObjectNamespace = substr($relatedModuleFullName, 0, $lastBackslashPosition - 1);
            $exceptionsManagerFullClassName = "$relatedObjectNamespace\\ExceptonsManager";
            if (!class_exists($exceptionsManagerFullClassName)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Exceptions manager class not found.'
                );
            }

            return new $exceptionsManagerFullClassName();
        }
    }