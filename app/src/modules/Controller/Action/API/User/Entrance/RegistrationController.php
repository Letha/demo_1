<?php

    namespace App\Controller\Action\API\User\Entrance;

    use App\Controller\Action\ActionControllerInterface;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;
    use App\Model\User\AccountsInfoInterface;
    use App\Model\User\Entrance\AuthorizationInterface;
    use App\Model\User\Entrance\RegistrationInterface;
    use App\Model\User\Entrance\InvitationVerificationInterface;

    /**
     * Registration of new user account.
     */
    class RegistrationController implements ActionControllerInterface
    {
        /**
         * @var AccountsInfoInterface $accountsInfoModel
         * @var RegistrationInterface $registrationModel
         * @var AuthorizationInterface $authorizationModel
         * @var InvitationVerificationInterface $invitationVerificationModel
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $accountsInfoModel,
            $registrationModel,
            $authorizationModel,
            $invitationVerificationModel,
            $globalExceptionsManager;

        /**
         * @var array What data is required for registration process.
         */
        private $requiredRequestDataFields = [
            'invitationCode',
            'login',
            'password',
            'passwordRepeat',
            'name',
            'surname',
        ];
        private $allowedUploadImagesTypes = [
            \IMAGETYPE_GIF,
            \IMAGETYPE_JPEG,
            \IMAGETYPE_PNG,
        ];
        private $maxUploadFileSize = 307200;

        function __construct(
            AccountsInfoInterface $accountsInfoModel,
            RegistrationInterface $registrationModel,
            AuthorizationInterface $authorizationModel,
            InvitationVerificationInterface $invitationVerificationModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();

            $this->accountsInfoModel = $accountsInfoModel;
            $this->registrationModel = $registrationModel;
            $this->authorizationModel = $authorizationModel;
            $this->invitationVerificationModel = $invitationVerificationModel;
        }
        
        /**
         * @see ActionControllerInterface For general description
         * @uses self::isRequestCorrect()
         * @uses self::getUserRegistrationData()
         */
        public function processClientRequest()
        {
            if (!$this->isRequestCorrect()) {
                header('HTTP/1.1 400 Bad Request');
                return;
            }

            if (
                !$this->invitationVerificationModel
                    ->whetherInvitationExists($_POST['invitationCode'])
            ) {
                header('HTTP/1.1 403 Forbidden');
                return;
            }

            if (
                $this->accountsInfoModel->whetherAccountExists($_POST['login'])
            ) {
                header('HTTP/1.1 409 Conflict');
                return;
            }
            
            $userRegistrationData = $this->getUserRegistrationData();
            $this->registrationModel->registerUser($_POST['login'], $_POST['password'], $userRegistrationData);
            $this->authorizationModel->authorize($_POST['login'], $_POST['password']);
            header('HTTP/1.1 200 OK');
        }

        /**
         * @uses self::whetherRequestContainsRequiredData()
         * @uses self::isRequestDataCorrect()
         */
        private function isRequestCorrect(): bool
        {
            if (
                $_SERVER['REQUEST_METHOD'] !== 'POST' ||
                !$this->whetherRequestContainsRequiredData() ||
                !$this->isRequestDataCorrect()
            ) {
                return false;
            }
            return true;
        }

        private function whetherRequestContainsRequiredData(): bool
        {
            if (array_diff($this->requiredRequestDataFields, array_keys($_POST))) {
                return false;
            } else {
                foreach ($this->requiredRequestDataFields as $dataField) {
                    if ($_POST[$dataField] === '') {
                        return false;
                    }
                }
                return true;
            }
        }

        /**
         * @uses self::whetherUserDataMatchesPatterns()
         * @uses self::isPasswordRepeatCorrect()
         * @uses self::isUserImagesFilesCorrect()
         */
        private function isRequestDataCorrect(): bool
        {
            if (
                !$this->invitationVerificationModel->isInvitationDataCorrect($_POST['invitationCode']) ||
                !$this->whetherUserDataMatchesPatterns() ||
                !$this->isPasswordRepeatCorrect($_POST['password'], $_POST['passwordRepeat']) ||
                !$this->isUserImagesFilesCorrect()
            ) {
                return false;
            }
            return true;
        }

        private function whetherUserDataMatchesPatterns(): bool
        {
            $matchResults[] = preg_match('~^[a-z0-9]{1,30}$~i', $_POST['login']);
            $matchResults[] = preg_match('~^.{10,30}$~u', $_POST['password']);
            $matchResults[] = preg_match('~^.{1,50}$~u', $_POST['name']);
            $matchResults[] = preg_match('~^.{1,50}$~u', $_POST['surname']);
            if (in_array(false, $matchResults, true)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if (in_array(0, $matchResults, true)) {
                return false;
            }
            return true;
        }

        private function isPasswordRepeatCorrect(string $password, string $passwordRepeat): bool
        {
            if ($password !== $passwordRepeat) {
                return false;
            }
            return true;
        }

        private function isUserImagesFilesCorrect(): bool
        {
            if (
                isset($_FILES['photoOfOneself']) && $_FILES['photoOfOneself']['size'] !== 0 &&
                (   
                    $_FILES['photoOfOneself']['error'] !== \UPLOAD_ERR_OK ||
                    $_FILES['photoOfOneself']['size'] > $this->maxUploadFileSize ||
                    !in_array(
                        exif_imagetype($_FILES['photoOfOneself']['tmp_name']), 
                        $this->allowedUploadImagesTypes, 
                        true
                    )
                )
            ) {
                return false;
            }
            return true;
        }

        /** 
         * Return data for user registration.
         */
        private function getUserRegistrationData(): array
        {
            $requiredUserRegistrationDataFields = [
                'name',
                'surname',
            ];

            /** For required data. */
            $userData = array_map(function($userDataField) {
                return $_POST[$userDataField];
            }, $requiredUserRegistrationDataFields);
            $userData = array_combine($requiredUserRegistrationDataFields, $userData);

            /** For optional data. */
            if (
                isset($_FILES['photoOfOneself']) &&
                $_FILES['photoOfOneself']['size'] !== 0
            ) {
                $userData['photoOfOneself'] = $_FILES['photoOfOneself'];
            }

            return $userData;
        }
    }