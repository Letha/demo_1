<?php

    namespace App\Model\User\Entrance;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;
    use App\Core\Session\ActualityManager as SessionActualityManager;
    use App\Core\Database\PDO\ConnectionsFactory as PDOConnectionsFactory;
    use App\Model\User\AccountsInfoModel;
    use App\Model\User\Files\ImagesUploadModel;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getAuthorizationModelInstance(): AuthorizationModel
        {
            return new AuthorizationModel(
                $this->diContainer->getClassInstance(SessionActualityManager::class),
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
        public function getRegistrationModelInstance(): RegistrationModel
        {
            return new RegistrationModel(
                $this->diContainer->getClassInstance(AccountsInfoModel::class),
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class),
                $this->diContainer->getClassInstance(ImagesUploadModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
        public function getInvitationVerificationModelInstance(): InvitationVerificationModel
        {
            return new InvitationVerificationModel(
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }