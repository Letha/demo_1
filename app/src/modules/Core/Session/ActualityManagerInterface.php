<?php

    namespace App\Core\Session;

    /**
     * Configuring PHP session to watch for its actuality and managing session depending of its actuality.
     */
    interface ActualityManagerInterface
    {
        /** 
         * Configures session to watch for its actuality.
         * 
         * Invoke it before working with session.
         * Session will be treated as actual at $actualityPeriod period. After it session id will be regenereted.
         * Not actual session will be available at $timeToDeleteOldSession period after $actualityPeriod period expiration.
         * 
         * @param $actualityPeriod The time of session actuality (seconds). If null, will be used predefined value
         * @param $timeToDeleteOldSession After session actuality is expired, after what time to delete this sessoin. If null, will be used predefined value
         * @param $sessionId Current common session id. If null, standard session id will be used
         * @param $sessionName Current common session name. If null, standard session name will be used
         */
        public function configureSession(
            ?int $actualityPeriod = null, ?int $timeToDeleteOldSession = null, 
            ?string $sessionId = null, ?string $sessionName = null
        );

        /** 
         * Manage session depending of its actuality.
         * 
         * Invoke it before working with session.
         * Invoke it every time when working with session depends of its actuality 
         * (but need not to invoke it at the same time as self::configureSession() is invoked).
         * 
         * @param $sessionId Current common session id. If null, standard session id will be used
         * @param $sessionName Current common session name. If null, standard session name will be used
         */
        public function processSession(?string $sessionId = null, ?string $sessionName = null);
    }