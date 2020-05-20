<?php

    namespace App\Model\User\Files;

    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see ImagesDownloadInterface For general description
     */
    class ImagesDownloadModel implements ImagesDownloadInterface
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

        /**
         * @see ImagesDownloadInterface For general description
         */
        public function getFileData(string $login, string $imageCategory, ?string $imageId = null): array
        {
            $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            if ($imageId === null) {
                $sql = 
                    "SELECT MIME_type, path_in_filesystem FROM users_images_files
                    WHERE login = :login AND category = :category";
                $stmt = $dbConnection->prepare($sql);
                $stmt->execute([
                    ':login' => $login,
                    ':category' => $imageCategory,
                ]);

                $fileInfo = $stmt->fetch();
                if (!$fileInfo) {
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'This file does not exist.'
                    );
                }
                
                $returning['mimeType'] = $fileInfo['MIME_type'];
                $returning['content'] = file_get_contents($fileInfo['path_in_filesystem']);
                if ($returning['content'] === false) {
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'file_get_contents() error.'
                    );
                }

                return $returning;

            } else {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Now cannot process this kind of requests.'
                );
            }
        }
    }