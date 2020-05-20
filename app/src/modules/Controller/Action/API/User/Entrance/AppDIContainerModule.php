<?php

    namespace App\Controller\Action\API\User\Entrance;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\Model\User\AccountsInfoModel;
    use App\Model\User\Entrance\AuthorizationModel;
    use App\Model\User\Entrance\RegistrationModel;
    use App\Model\User\Entrance\InvitationVerificationModel;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getAuthorizationControllerInstance(): AuthorizationController
        {
            return new AuthorizationController(
                $this->diContainer->getClassInstance(AccountsInfoModel::class),
                $this->diContainer->getClassInstance(AuthorizationModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
        public function getExitControllerInstance(): ExitController
        {
            return new ExitController(
                $this->diContainer->getClassInstance(AuthorizationModel::class)
            );
        }
        public function getRegistrationControllerInstance(): RegistrationController
        {
            return new RegistrationController(
                $this->diContainer->getClassInstance(AccountsInfoModel::class),
                $this->diContainer->getClassInstance(RegistrationModel::class),
                $this->diContainer->getClassInstance(AuthorizationModel::class),
                $this->diContainer->getClassInstance(InvitationVerificationModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }