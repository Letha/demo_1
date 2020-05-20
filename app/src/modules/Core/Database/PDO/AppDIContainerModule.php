<?php

    namespace App\Core\Database\PDO;

    use App\GlobalModule\DIContainer\DIContainerModule;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getConnectionsFactoryInstance(): ConnectionsFactory
        {
            return new ConnectionsFactory(
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }