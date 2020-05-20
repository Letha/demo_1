<?php

    namespace App\Core\Session;

    use App\GlobalModule\DIContainer\DIContainerModule;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getActualityManagerInstance(): ActualityManager
        {
            return new ActualityManager(
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }