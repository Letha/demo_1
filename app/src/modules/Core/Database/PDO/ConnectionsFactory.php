<?php

    namespace App\Core\Database\PDO;

    use const App\APP_DIR;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see ConnectionsFactoryInterface For general description
     */
    class ConnectionsFactory implements ConnectionsFactoryInterface
    {
        /**
         * @var ExceptionsManagerInterface
         */
        private $globalExceptionsManager;
        private $defaultPdoOptions = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        function __construct(
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = 
                $exceptionsManagersFactory->getExceptionsManager();
        }

        /**
         * @see ConnectionsFactoryInterface For general description
         * @param string $dbConnectionConfigId A name of config file placed in /src/config/core/database/connection-configs/
         * @param array $pdoOptions Redefining default connection options
         */
        public function getConnectionFromConfig(
            string $dbConnectionConfigId = 'default', array $pdoOptions = null
        ): \PDO {
            // include and verify config file
            $dbConnectionConfigFilePath = 
                APP_DIR . "/src/config/core/database/connection-configs/$dbConnectionConfigId.php";
            $dbConnectionConfig = include $dbConnectionConfigFilePath;
            if ($dbConnectionConfig === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    "Cannot include database connection config file: $dbConnectionConfigFilePath."
                );
            } elseif (!is_array($dbConnectionConfig)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Database connection config file do not return an array.'
                );
            }

            // check config file for required data
            $requiredConfigFields = ['host', 'database', 'user', 'password', 'charset'];
            if (array_diff($requiredConfigFields, array_keys($dbConnectionConfig))) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Database connection config file do not return required data.'
                );
            }

            // create, tune and return PDO connection
            $pdoDsn = "mysql:host={$dbConnectionConfig['host']};dbname={$dbConnectionConfig['database']};charset={$dbConnectionConfig['charset']}";
            if ($pdoOptions === null) {
                $pdoOptions = $this->defaultPdoOptions;
            }

            return new \PDO($pdoDsn, $dbConnectionConfig['user'], $dbConnectionConfig['password'], $pdoOptions);
        }
    }