<?php

    namespace App\Controller\Action\API\User;

    use App\Controller\Action\ActionControllerInterface;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;
    use App\Model\User\Files\ImagesDownloadInterface;
    use App\Model\User\Entrance\AuthorizationInterface;

    /**
     * Send image file to HTTP client.
     */
    class ImageDownloadController implements ActionControllerInterface
    {
        /**
         * @var ImagesDownloadInterface $imagesDownloadModel
         * @var AuthorizationInterface $authorizationModel
         * @var ExceptionsManagerInterface $globalExceptionsManager
         */
        private 
            $imagesDownloadModel,
            $authorizationModel,
            $globalExceptionsManager;

        private $allowedImagesCategories = ['profile_photo'];

        function __construct(
            ImagesDownloadInterface $imagesDownloadModel,
            AuthorizationInterface $authorizationModel,
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();

            $this->imagesDownloadModel = $imagesDownloadModel;
            $this->authorizationModel = $authorizationModel;
        }

        /**
         * @see ActionControllerInterface For general description
         * 
         * @param $imageCategory For download
         * @param $imageId For download
         * @param $login Login related with downloading image (can be avoided)
         * 
         * @uses self::isRequestCorrect()
         */
        public function processClientRequest(?string $imageCategory = null, ?string $imageId = null, ?string $login = null): void
        {
            if (!$this->isRequestCorrect($imageCategory, $imageId, $login)) {
                header("HTTP/1.1 400 Bad Request");
                return;
            }

            if ($this->authorizationModel->isAuthorized()) {
                $authorizedLogin = $this->authorizationModel->getAuthorizedLogin();
                $fileData = $this->imagesDownloadModel->getFileData($authorizedLogin, $imageCategory, $imageId);

                header("HTTP/1.1 200 OK");
                header("Content-type: {$fileData['mimeType']}");
                echo $fileData['content'];
                return;

            } else {
                header("HTTP/1.1 400 Bad Request");
                return;
            }
        }

        /**
         * @throws $this->globalExceptionsManager:AppException If unexpected behavior
         */
        private function isRequestCorrect(?string $imageCategory, ?string $imageId, ?string $login): bool
        {
            if (
                $imageCategory === null ||
                !in_array($imageCategory, $this->allowedImagesCategories, true) ||
                // download for other users is not implemented
                $login !== null
            ) {
                return false;
            }

            if ($login !== null) {
                $verifyingRequestStatuses[] = preg_match('~^[\w-]{1,30}$~', $login);
            }
            if ($imageId !== null) {
                $verifyingRequestStatuses[] = preg_match('~^[\w-]{1,100}$~', $imageId);
            }
            $verifyingRequestStatuses[] = preg_match('~^[\w-]{1,30}$~', $imageCategory);
            
            if (in_array(false, $verifyingRequestStatuses, true)) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Unexpected behavior.'
                );
            }
            if (in_array(0, $verifyingRequestStatuses, true)) {
                return false;
            }

            return true;
        }
    }