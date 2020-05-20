<?php

    namespace App\Model\User\Entrance;

    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;
    use App\Model\User\AccountsInfoInterface;
    use App\Model\User\Files\UploadInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see RegistrationInterface For general description
     */
    class RegistrationModel implements RegistrationInterface
    {
        /**
         * @var AccountsInfoInterface $accountsInfoModel
         * @var PDOConnectionsFactoryInterface $dbConnectionFactory
         * @var UploadInterface $userImagesFilesUploadModel
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private
            $accountsInfoModel,
            $dbConnectionFactory,
            $userImagesFilesUploadModel,
            $globalExceptionsManager;

        function __construct(
            AccountsInfoInterface $accountsInfoModel,
            PDOConnectionsFactoryInterface $dbConnectionFactory,
            UploadInterface $userImagesFilesUploadModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();

            $this->accountsInfoModel = $accountsInfoModel;
            $this->dbConnectionFactory = $dbConnectionFactory;
            $this->userImagesFilesUploadModel = $userImagesFilesUploadModel;
        }

        /** @see RegistrationInterface For general description */
        public function registerUser(string $login, string $password, ?array $userData = null): void
        {
            if ($this->accountsInfoModel->whetherAccountExists($login)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'This account exists.'
                );
            }

            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            $dbConnection->beginTransaction();
            try {
                $passwordHash = $this->hashPassword($password);
                $this->writeUserDataToDatabase($dbConnection, $login, $passwordHash, $userData);

                // upload file in the same transaction
                if (isset($userData['photoOfOneself'])) {
                    $this
                        ->userImagesFilesUploadModel
                        ->uploadUserFile($login, $userData['photoOfOneself'], 'profile_photo', $dbConnection);
                }

                $dbConnection->commit();
            } catch (\Throwable $throwable) {
                $dbConnection->rollback();
                throw $throwable;
            }
        }

        private function hashPassword(string $password): string
        {
            $hashingResult = password_hash($password, \PASSWORD_BCRYPT);
            if (!$hashingResult) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            return $hashingResult;
        }

        private function writeUserDataToDatabase(\PDO $dbConnection, string $login, string $passwordHash, array $userData)
        {
            $sql = 'INSERT INTO accounts (login, password_hash) VALUES (:login, :password_hash)';
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([
                ':login' => $login,
                ':password_hash' => $passwordHash,
            ]);

            $optionalUserData = ['name', 'surname'];
            $existingOptionalUserData = array_intersect($optionalUserData, array_keys($userData));
            $sql1stInsertStr = implode(',', $existingOptionalUserData);
            $sql2ndInsertStr = implode(',', array_map(function ($element) {
                return ":$element";
            }, $existingOptionalUserData));

            $sql = "INSERT INTO users (login, $sql1stInsertStr) VALUES (:login, $sql2ndInsertStr)";
            $stmt = $dbConnection->prepare($sql);

            $stmt->bindValue(':login', $login);
            foreach ($optionalUserData as $dataName) {
                if (in_array($dataName, $existingOptionalUserData, true)) {
                    $stmt->bindValue(":$dataName", $userData[$dataName]);
                } else {
                    $stmt->bindValue(":$dataName", null, \PDO::PARAM_NULL);
                }
            }
            $stmt->execute();
        }
    }