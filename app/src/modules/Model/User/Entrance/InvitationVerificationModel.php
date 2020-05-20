<?php

    namespace App\Model\User\Entrance;

    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see InvitationVerificationInterface For general description
     */
    class InvitationVerificationModel implements InvitationVerificationInterface
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

        /** @see InvitationVerificationInterface For general description */
        public function whetherInvitationExists(string $invitationCode): bool
        {
            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            $sql = 'SELECT code FROM invitations WHERE code = :invitation_code';
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([':invitation_code' => $invitationCode]);
            if ($stmt->fetch()) {
                return true;
            }
            return false;
        }

        /** @see InvitationVerificationInterface For general description */
        public function isInvitationDataCorrect(string $invitationCode): bool
        {
            $matchingStatus = preg_match('~^.{1,30}$~', $invitationCode);
            if ($matchingStatus === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if ($matchingStatus === 1) {
                return true;
            } else {
                return false;
            }
        }
    }