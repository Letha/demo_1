<?php

    namespace App\Model\User;
    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;

    /**
     * @see AccountsInfoInterface For general description
     */
    class AccountsInfoModel implements AccountsInfoInterface
    {
        /** @var PDOConnectionsFactoryInterface */
        private $dbConnectionFactory;

        function __construct(
            PDOConnectionsFactoryInterface $dbConnectionFactory
        ) {
            $this->dbConnectionFactory = $dbConnectionFactory;
        }

        /** @see AccountsInfoInterface For general description */
        public function whetherAccountExists(string $login): bool
        {
            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            $sql = 'SELECT login FROM accounts WHERE login = :login';
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([':login' => $login]);
            if ($stmt->fetch()) {
                return true;
            }
            return false;
        }
    }