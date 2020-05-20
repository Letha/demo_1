<?php

    namespace App\Model\ViewTemplate;

    use App\Model\Config\LocalizationSettingsModel;

    use App\GlobalModule\DIContainer\DIContainerModule;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactory;

    /**
     * @see DIContainerModule For general description
     */
    final class AppDIContainerModule extends DIContainerModule
    {
        public function getTemplateTextsExtractorModelInstance(): TemplateTextsExtractorModel
        {
            return new TemplateTextsExtractorModel(
                $this->diContainer->getClassInstance(LocalizationSettingsModel::class),
                $this->diContainer->getClassInstance(ExceptionsManagersFactory::class)
            );
        }
    }