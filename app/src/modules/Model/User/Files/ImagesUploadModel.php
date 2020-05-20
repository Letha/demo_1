<?php

    namespace App\Model\User\Files;

    use const App\APP_DIR;

    use App\Core\Database\PDO\ConnectionsFactoryInterface as PDOConnectionsFactoryInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /** 
     * @see UploadInterface For general description
     */
    class ImagesUploadModel implements UploadInterface
    {
        private $dbConnectionFactory;

        function __construct(
            PDOConnectionsFactoryInterface $dbConnectionFactory,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
            $this->dbConnectionFactory = $dbConnectionFactory;
        }

        /**
         * @see UploadInterface For general description
         * @param $imageCategory Images' category to which uploading file belongs
         * @param $relatedDbConnection If provided and is in transaction, this method will work through this transaction
         */
        public function uploadUserFile(
            string $userLogin, array $fileData, string $imageCategory = 'other', ?\PDO $relatedDbConnection = null
        ): void {
            if (!is_uploaded_file($fileData['tmp_name'])) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'That file was not uploaded by POST request.'
                );
            }

            if (
                !$this->isStringCorrectForSingleInsertToFilePath($userLogin) ||
                !$this->isStringCorrectForSingleInsertToFilePath($imageCategory)
            ) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Wrong format of user login or image category.'
                );
            }

            $this->correctFileExtention($fileData);

            if ($relatedDbConnection === null) {
                $dbConnection = $this->dbConnectionFactory->getConnectionFromConfig();
            } else {
                $dbConnection = $relatedDbConnection;
            }
            $isTransactionOnHigherLevel = $dbConnection->inTransaction();

            if (!$isTransactionOnHigherLevel) {
                $dbConnection->beginTransaction();
            }
            try {
                $imageExifType = exif_imagetype($fileData['tmp_name']);
                $filePath = $this->writeFileInfoToDatabase($dbConnection, $userLogin, $imageCategory, $imageExifType);

                $this->setDirectoryForFile($filePath);

                if (!move_uploaded_file($fileData['tmp_name'], $filePath)) {
                    if (!$isTransactionOnHigherLevel) {
                        $dbConnection->rollback();
                    }
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'Unexpected behavior. Cannot upload file.'
                    );
                }
                if (!file_exists($filePath)) {
                    if (!$isTransactionOnHigherLevel) {
                        $dbConnection->rollback();
                    }
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'Unexpected behavior. File was not upload.'
                    );
                }
            } catch (\Throwable $throwable) {
                if (!$isTransactionOnHigherLevel) {
                    $dbConnection->rollback();
                }
                throw $throwable;
            }
        }

        /** Turns jpg to jpeg. */
        private function correctFileExtention(array $fileData): void
        {
            $fileData['tmp_name'] = preg_replace('~\.jpg$~', 'jpeg', $fileData['tmp_name']);
            if ($fileData['tmp_name'] === null) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
        }

        /** Verifying a string to use it as a part of file path. */
        private function isStringCorrectForSingleInsertToFilePath(string $string): bool
        {
            $processResult = preg_match('~^[\w-]{1,100}$~', $string);
            if ($processResult === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if ($processResult === 0) {
                return false;
            }
            return true;
        }

        /** Writes file info (not file's content) to database. */
        private function writeFileInfoToDatabase(
            \PDO $dbConnection, string $userLogin, string $imageCategory, string $imageExifType
        ): string {
            $fileMimeType = image_type_to_mime_type($imageExifType);
            $filePath1stPart = APP_DIR . "/storage/users-files/$userLogin/images/$imageCategory/";
            $filePath2ndPart = image_type_to_extension($imageExifType);

            $sql = 
                "INSERT INTO users_images_files (login, category, MIME_type, path_in_filesystem) 
                VALUES (:login, :category, :MIME_type, :path_in_filesystem)";
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([
                ':login' => $userLogin,
                ':category' => $imageCategory,
                ':MIME_type' => $fileMimeType,
                ':path_in_filesystem' => '',
            ]);

            $sql = 
                "UPDATE users_images_files 
                SET path_in_filesystem = CONCAT(:file_path_1st_part, LAST_INSERT_ID(), :file_path_2nd_part)
                WHERE id = LAST_INSERT_ID()";
            $stmt = $dbConnection->prepare($sql);
            $stmt->execute([
                ':file_path_1st_part' => $filePath1stPart,
                ':file_path_2nd_part' => $filePath2ndPart,
            ]);

            $sql = 
                "SELECT path_in_filesystem FROM users_images_files
                WHERE id = LAST_INSERT_ID()";
            $stmt = $dbConnection->query($sql);
            $filePath = $stmt->fetchColumn();
            if ($filePath === false) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            return $filePath;
        }

        /** Create a directory for a file if not exists. */
        private function setDirectoryForFile(string $filePath): void
        {
            $fileDir = preg_replace('~[\\\\/]{1}[\w-\.]+$~', '', $filePath);
            if ($fileDir === null || $fileDir === $filePath) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }

            if (!is_dir($fileDir)) {
                $mkdirStatus = mkdir($fileDir, 0770, true);
                if ($mkdirStatus === false) {
                    throw $this->globalExceptionsManager->getExceptionInstance(
                        'AppException',
                        'Cannot make directory.'
                    );
                }
            }
        }
    }