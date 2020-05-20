<?php

    namespace App\Controller\Action\API\User\Entrance;

    use App\Controller\Action\ActionControllerInterface;

    use App\Model\User\AccountsInfoInterface;
    use App\Model\User\Entrance\AuthorizationInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * Authorize account in current session.
     */
    class AuthorizationController implements ActionControllerInterface
    {
        /**
         * @var AccountsInfoInterface $accountsInfoModel
         * @var AuthorizationInterface $authorizationModel
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $accountsInfoModel,
            $authorizationModel,
            $globalExceptionsManager;

        function __construct(
            AccountsInfoInterface $accountsInfoModel,
            AuthorizationInterface $authorizationModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
            $this->accountsInfoModel = $accountsInfoModel;
            $this->authorizationModel = $authorizationModel;
        }

        /**
         * @see ActionControllerInterface For general description
         * @uses self::isRquestCorrect()
         */
        public function processClientRequest()
        {
            if (
                !$this->isRquestCorrect() ||
                !$this->accountsInfoModel->whetherAccountExists($_POST['login'])
            ) {
                header('HTTP/1.1 400 Bad Request');
                return;
            }

            $this->authorizationModel->authorize($_POST['login'], $_POST['password']);
            header('HTTP/1.1 200 OK');
        }

        /**
         * @uses self::hasRequiredPostData()
         * @uses self::whetherAccountDataMatchesPatterns()
         */
        private function isRquestCorrect(): bool
        {
            if (
                $this->hasRequiredPostData() &&
                $this->whetherAccountDataMatchesPatterns($_POST['login'], $_POST['password'])
            ) {
                return true;
            }
            return false;
        }

        private function hasRequiredPostData(): bool
        {
            if (
                !isset($_POST['login']) ||
                !isset($_POST['password'])
            ) {
                return false;
            }
            return true;
        }
        
        /**
         * @param $login Data to verify
         * @param $password Data to verify
         * @throws $this->globalExceptionsManager:AppException If unexpected behavior
         */
        private function whetherAccountDataMatchesPatterns(string $login, string $password): bool
        {
            $matchingStatuses[] = preg_match('~^[a-z0-9]{1,30}$~i', $login);
            $matchingStatuses[] = preg_match('~^.{10,30}$~u', $password);
            if (in_array(false, $matchingStatuses, true)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if (in_array(0, $matchingStatuses, true)) {
                return false;
            }
            return true;
        }
    }