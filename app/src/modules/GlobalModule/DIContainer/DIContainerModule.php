<?php

    namespace App\GlobalModule\DIContainer;

    /**
     * Abstraction of dependency injection module. 
     * 
     * DI module is a part of DI container (DI modules provides DI container methods to construct classes).
     * Every DI module need to be placed in a directory with classes which this module can construct (they have one namespace).
     * 
     * @see ModularDIContainer For description of DI modular container
     */
    abstract class DIContainerModule implements DIContainerModuleInterface
    {
        /** @var ModularDIContainerInterface */
        protected $diContainer;
        
        function __construct(
            ModularDIContainerInterface $diContainer
        ) {
            $this->diContainer = $diContainer;
        }
    }