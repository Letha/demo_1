<?php

    namespace App\Model\User\Files;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;
    use App\Core\Database\PDO\ConnectionsFactory as PDOConnectionsFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getImagesUploadModelInstance(): ImagesUploadModel
        {
            return new ImagesUploadModel(
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
        public function getImagesDownloadModelInstance(): ImagesDownloadModel
        {
            return new ImagesDownloadModel(
                $this->diContainer->getClassInstance(PDOConnectionsFactory::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }