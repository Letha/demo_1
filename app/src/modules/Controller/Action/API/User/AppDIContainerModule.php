<?php

    namespace App\Controller\Action\API\User;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\Model\User\Files\ImagesDownloadModel;
    use App\Model\User\Entrance\AuthorizationModel;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getImageDownloadControllerInstance(): ImageDownloadController
        {
            return new ImageDownloadController(
                $this->diContainer->getClassInstance(ImagesDownloadModel::class),
                $this->diContainer->getClassInstance(AuthorizationModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }