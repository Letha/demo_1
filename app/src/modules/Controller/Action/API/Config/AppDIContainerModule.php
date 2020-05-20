<?php

    namespace App\Controller\Action\API\Config;

    use App\Model\Config\LocalizationSettingsModel;
    use App\GlobalModule\DIContainer\DIContainerModule;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getLocalizationControllerInstance(): LocalizationController
        {
            return new LocalizationController(
                $this->diContainer->getClassInstance(LocalizationSettingsModel::class)
            );
        }
    }