<?php

    namespace App\Model\User;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;
    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;

    /**
     * @see UsersInfoInterface For general description
     */
    class UsersInfoModel implements UsersInfoInterface
    {
        /**
         * @var PDOConnectionsFactoryInterface $dbConnectionFactory
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private
            $dbConnectionFactory,
            $globalExceptionsManager;

        function __construct(
            PDOConnectionsFactoryInterface $dbConnectionFactory,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
            $this->dbConnectionFactory = $dbConnectionFactory;
        }

        /** @see UsersInfoInterface For general description */
        public function getUserData(string $login): array
        {
            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            $sql = 
                "SELECT name, surname FROM users
                WHERE login = :login";
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([':login' => $login]);

            $userInfo = $stmt->fetch();
            if (!$userInfo) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'User for this login does not exist.'
                );
            }

            // delete data which has "null" value
            if ($userInfo['name'] === null) {
                unset($userInfo['name']);
            }
            if ($userInfo['surname'] === null) {
                unset($userInfo['surname']);
            }

            $userInfo['hasProfilePhoto'] = $this->hasUserProfilePhoto($dbConnection, $login);
            return $userInfo;
        }

        /** Defines whether specified account (login) has profile photo. */
        private function hasUserProfilePhoto(\PDO $dbConnection, string $login): bool
        {
            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            $sql = 
                "SELECT id FROM users_images_files
                WHERE login = :login AND category = 'profile_photo'";
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([':login' => $login]);

            $userInfo = $stmt->fetchColumn();
            if (!$userInfo) {
                return false;
            }
            return true;
        }
    }