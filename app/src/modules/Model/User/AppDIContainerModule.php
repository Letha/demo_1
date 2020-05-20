<?php

    namespace App\Model\User;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;
    use App\Core\Database\PDO\ConnectionsFactory as PDOConnectionsFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getAccountsInfoModelInstance(): AccountsInfoModel
        {
            return new AccountsInfoModel(
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class)
            );
        }
        public function getUsersInfoModelInstance(): UsersInfoModel
        {
            return new UsersInfoModel(
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }