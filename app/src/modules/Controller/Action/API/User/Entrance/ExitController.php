<?php

    namespace App\Controller\Action\API\User\Entrance;

    use App\Model\User\Entrance\AuthorizationInterface;
    use App\Controller\Action\ActionControllerInterface;

    /**
     * Deauthorizing of current account in current session.
     */
    class ExitController implements ActionControllerInterface
    {
        /**
         * @var AuthorizationInterface
         */
        private $authorizationModel;

        function __construct(
            AuthorizationInterface $authorizationModel
        ) {
            $this->authorizationModel = $authorizationModel;
        }

        /**
         * @see ActionControllerInterface For general description
         */
        public function processClientRequest()
        {
            $this->authorizationModel->deauthorize();
            header('HTTP/1.1 200 OK');
        }
    }