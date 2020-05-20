<?php

    namespace App\Model\Config;
    
    use App\GlobalModule\DIContainer\DIContainerModule;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getLocalizationSettingsModelInstance(): LocalizationSettingsModel
        {
            return new LocalizationSettingsModel(
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }