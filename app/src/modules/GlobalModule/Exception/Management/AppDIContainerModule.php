<?php

    namespace App\GlobalModule\Exception\Management;
    use App\GlobalModule\DIContainer\DIContainerModule;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getExceptionsManagersFactoryInstance(): ExceptionsManagersFactory
        {
            return new ExceptionsManagersFactory();
        }
    }