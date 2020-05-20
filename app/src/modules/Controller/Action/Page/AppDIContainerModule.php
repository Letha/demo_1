<?php

    namespace App\Controller\Action\Page;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\Model\User\UsersInfoModel;
    use App\Model\User\Entrance\AuthorizationModel;
    use App\Model\Config\LocalizationSettingsModel;
    use App\Model\ViewTemplate\TemplateTextsExtractorModel;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getIndexControllerInstance(): IndexController
        {
            return new IndexController(
                $this->diContainer->getClassInstance(AuthorizationModel::class),
                $this->diContainer->getClassInstance(LocalizationSettingsModel::class),
                $this->diContainer->getClassInstance(TemplateTextsExtractorModel::class),
                $this->diContainer->getClassInstance(UsersInfoModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }