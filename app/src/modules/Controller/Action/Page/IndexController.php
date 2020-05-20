<?php

    namespace App\Controller\Action\Page;

    use App\Controller\Action\ActionControllerInterface;
    
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;
    use App\Model\User\UsersInfoInterface;
    use App\Model\User\Entrance\AuthorizationInterface;
    use App\Model\Config\LocalizationSettingsInterface;
    use App\Model\ViewTemplate\TemplateTextsExtractorInterface;
    use App\Controller\PageController;
    
    /**
     * Returns index page (view).
     */
    class IndexController extends PageController implements ActionControllerInterface
    {
        /**
         * @var UsersInfoInterface
         */
        private $usersInfoModel;

        function __construct(
            AuthorizationInterface $authorizationModel,
            LocalizationSettingsInterface $localizationSettingsModel,
            TemplateTextsExtractorInterface $templateTextsExtractorModel,
            UsersInfoInterface $usersInfoModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            parent::__construct(
                $authorizationModel, $localizationSettingsModel,
                $templateTextsExtractorModel, $exceptionsManagersFactory
            );
            $this->usersInfoModel = $usersInfoModel;
        }
        
        /**
         * @see ActionControllerInterface For general description
         */
        public function processClientRequest(): void
        {
            $viewData = null;
            if ($this->authorizationModel->isAuthorized()) {
                $viewData['login'] = $this->authorizationModel->getAuthorizedLogin();
                $viewData['userData'] = $this->usersInfoModel->getUserData($viewData['login']);
            }
            $this->insertView('index', $viewData);
        }
    }