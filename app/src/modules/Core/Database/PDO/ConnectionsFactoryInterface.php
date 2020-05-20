<?php

    namespace App\Core\Database\PDO;

    /** Creating PDO connections. */
    interface ConnectionsFactoryInterface
    {
        /** Returns connection described at specified config. */
        public function getConnectionFromConfig(string $dbConnectionConfigId, array $pdoOptions): \PDO;
    }