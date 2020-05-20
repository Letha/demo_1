<?php

    namespace App\Model\User\Entrance;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;
    use App\Model\User\AccountsInfoInterface;
    use App\Core\Session\ActualityManagerInterface as SessionActualityManagerInterface;
    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;

    /**
     * @see AuthorizationInterface For general description
     */
    class AuthorizationModel implements AuthorizationInterface
    {
        /**
         * @var SessionActualityManagerInterface $sessionActualityManager
         * @var PDOConnectionsFactoryInterface $dbConnectionFactory
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $sessionActualityManager,
            $dbConnectionFactory,
            $globalExceptionsManager;

        function __construct(
            SessionActualityManagerInterface $sessionActualityManager,
            PDOConnectionsFactoryInterface $dbConnectionFactory,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
            $this->sessionActualityManager = $sessionActualityManager;
            $this->dbConnectionFactory = $dbConnectionFactory;
        }

        /** @see AuthorizationInterface For general description */
        public function authorize(string $login, string $password): void
        {
            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            $this->verifyAuthorizationData($dbConnection, $login, $password);
            $this->configureSessionToAuthorize($login);
        }

        /** @see AuthorizationInterface For general description */
        public function deauthorize(): void
        {
            $this->sessionActualityManager->processSession();
            session_start();
            if (
                !isset($_SESSION['authData']['isAuthorized']) || 
                $_SESSION['authData']['isAuthorized'] === false
            ) {
                session_abort();
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected authorization status.'
                );
            }

            unset($_SESSION['authData']['isAuthorized']);
            session_commit();
        }
        
        /** @see AuthorizationInterface For general description */
        public function isAuthorized(): bool
        {
            session_start(['read_and_close' => true]);
            if (
                isset($_SESSION['authData']['isAuthorized']) && 
                $_SESSION['authData']['isAuthorized'] === true
            ) {
                return true;
            }
            return false;
        }

        /** @see AuthorizationInterface For general description */
        public function getAuthorizedLogin(): string
        {
            session_start(['read_and_close' => true]);
            if (
                isset($_SESSION['authData']['isAuthorized']) && 
                $_SESSION['authData']['isAuthorized'] === true &&
                isset($_SESSION['authData']['login'])
            ) {
                return $_SESSION['authData']['login'];
            }
            throw $this->globalExceptionsManager->getExceptionInstance(
                'AppException',
                'No authorized login.'
            );
        }

        private function verifyAuthorizationData(\PDO $dbConnection, string $login, string $password): void
        {
            $sql = 
                'SELECT password_hash FROM accounts 
                WHERE login = :login';
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([':login' => $login]);

            $passwordHash = $stmt->fetchColumn();
            if ($passwordHash === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'This login does not exist.'
                );
            }

            if (!password_verify($password, $passwordHash)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Wrong password.'
                );
            }
        }

        private function configureSessionToAuthorize(string $login): void
        {
            $this->sessionActualityManager->configureSession();
            session_start();
            $_SESSION['authData']['login'] = $login;
            $_SESSION['authData']['isAuthorized'] = true;
            session_commit();
        }
    }