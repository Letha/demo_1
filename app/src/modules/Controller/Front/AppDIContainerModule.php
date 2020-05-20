<?php

    namespace App\Controller\Front;

    use App\GlobalModule\DIContainer\DIContainerModule;

    use App\Model\User\Entrance\AuthorizationModel;
    use App\Model\Config\LocalizationSettingsModel;
    use App\Model\ViewTemplate\TemplateTextsExtractorModel;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getFrontControllerInstance(): FrontController
        {
            return new FrontController(
                $this->diContainer->getClassInstance(Router::class),
                $this->diContainer->getClassInstance(AuthorizationModel::class),
                $this->diContainer->getClassInstance(LocalizationSettingsModel::class),
                $this->diContainer->getClassInstance(TemplateTextsExtractorModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
        public function getRouterInstance(): Router
        {
            return new Router(
                $this->diContainer,
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }