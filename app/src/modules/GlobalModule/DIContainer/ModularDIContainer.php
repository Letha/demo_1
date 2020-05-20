<?php

    namespace App\GlobalModule\DIContainer;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * Abstraction of modular dependency injection container.
     * 
     * It uses DI modules to invoke construction methods of appropriate classes.
     * 
     * @see ModularDIContainerInterface For general description
     * @see DIContainerModule For description of DI modules
     */
    abstract class ModularDIContainer implements ModularDIContainerInterface
    {
        /**
         * @var ExceptionsManagerInterface $globalExceptionsManager
         * @var array $diModulesCache Array of {diModuleFullName} => {diModule}. The way to avoid using "new" to create DI modules which were used
         */
        private
            $globalExceptionsManager,
            $diModulesCache = [];

        function __construct()
        {
            $exceptionsManagersFactory = new ExceptionsManagersFactory();
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
        }

        /** 
         * @see ModularDIContainerInterface For general description
         * @uses self::getDiContainerModule()
         * @uses self::getInstantiationMethodName()
         */
        final public function getClassInstance(string $classFullName)
        {
            // get last class name - it is at the end of a full name
            $classFillNameParts = explode('\\', $classFullName);
            $classLastName = end($classFillNameParts);

            if (!class_exists($classFullName)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "Class does not exist: $classFullName."
                );
            }

            /**
             * get DI module for class, 
             * invoke its method to get this class instance
             */
            $diModule = $this->getDiContainerModule($classLastName, $classFullName);
            $instantiationMethodName = $this->getInstantiationMethodName($diModule, $classLastName);
            return $diModule->$instantiationMethodName();
        }

        /**
         * Returns DI module which can return requested class.
         * 
         * @param $classLastName This is at the end of a full name
         * @return object
         */
        private function getDiContainerModule(string $classLastName, string $classFullName)
        {
            // get DI module full name and verify if
            $diModuleFullName = preg_replace("~\\\\$classLastName$~", '\\', $classFullName) . 'AppDIContainerModule';
            if ($diModuleFullName === null) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if (!class_exists($diModuleFullName)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "DI container module not found: $diModuleFullName."
                );
            }

            // get and return DI module (it will be cached)
            if (array_key_exists($diModuleFullName, $this->diModulesCache)) {
                $diModule = $this->diModulesCache[$diModuleFullName];
            } else {
                $diModule = new $diModuleFullName($this);
                $this->diModulesCache[$diModuleFullName] = $diModule;
            }
            return $diModule;
        }

        /**
         * Returns name of DI module's method which returns an instance of specified class name.
         */
        private function getInstantiationMethodName(
            DIContainerModuleInterface $diModule, string $classLastName
        ): string {
            $instantiationMethodName = "get{$classLastName}Instance";
            if (!method_exists($diModule, $instantiationMethodName)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "Cannot resolve class name into method name (method $instantiationMethodName do not exist)."
                );
            }
            return $instantiationMethodName;
        }
    }