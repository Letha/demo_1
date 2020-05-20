<?php

    namespace App\Core\Session;

    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;
    use App\GlobalModule\Exception\Management\ExceptionsManagersFactoryInterface;

    /**
     * @see ActualityManagerInterface For general description
     */
    class ActualityManager implements ActualityManagerInterface
    {
        /** @var ExceptionsManagerInterface $globalExceptionsManager */
        private $globalExceptionsManager;

        private 
            $defaultSessionActualityPeriod = 15 * 60,
            $defaultTimeToDeleteOldSession = 5 * 60;

        function __construct(
            ExceptionsManagersFactoryInterface $exceptionsManagersFactory
        ) {
            $this->globalExceptionsManager = $exceptionsManagersFactory->getExceptionsManager();
        }

        /**
         * @see ActualityManagerInterface For general description
         * @uses self::verifySessionIsNotStarted()
         * @uses self::startSession()
         */
        public function configureSession(
            ?int $actualityPeriod = null, ?int $timeToDeleteOldSession = null,
            ?string $sessionId = null, ?string $sessionName = null
        ): void {
            $this->verifySessionIsNotStarted();
            $this->startSession($sessionId, $sessionName);

            if ($actualityPeriod === null) {
                $actualityPeriod = $this->defaultSessionActualityPeriod;
            }
            if ($timeToDeleteOldSession === null) {
                $timeToDeleteOldSession = $this->defaultTimeToDeleteOldSession;
            }

            $_SESSION['sessionActuality']['sessionBeginTime'] = time();
            $_SESSION['sessionActuality']['actualityPeriod'] = $actualityPeriod;
            $_SESSION['sessionActuality']['periodToDeleteOldSession'] = $timeToDeleteOldSession;

            session_commit();
        }

        /**
         * @see ActualityManagerInterface For general description
         * 
         * @uses self::verifySessionIsNotStarted()
         * @uses self::startSession()
         * @uses self::deleteSession()
         * @uses self::recreateSession()
         * 
         * @throws $this->globalExceptionsManager:AppException If $_SESSION has not required data or if this data is incorrect
         */
        public function processSession(?string $sessionId = null, ?string $sessionName = null): void
        {
            $this->verifySessionIsNotStarted();
            $this->startSession($sessionId, $sessionName);

            if (
                !isset($_SESSION['sessionActuality']['sessionBeginTime'])         || !is_int($_SESSION['sessionActuality']['sessionBeginTime']) ||
                !isset($_SESSION['sessionActuality']['actualityPeriod'])          || !is_int($_SESSION['sessionActuality']['actualityPeriod']) ||
                !isset($_SESSION['sessionActuality']['periodToDeleteOldSession']) || !is_int($_SESSION['sessionActuality']['periodToDeleteOldSession'])
            ) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Wrong session data.'
                );
            }

            $now = time();
            $isCurrentSessionOld = isset($_SESSION['sessionActuality']['sessionEndTime']);
            if ($isCurrentSessionOld) {
                if ($now >= $_SESSION['sessionActuality']['sessionEndTime']) {
                    $this->deleteSession();
                }

            } else {
                $actualityEndTime = 
                    $_SESSION['sessionActuality']['sessionBeginTime'] + 
                    $_SESSION['sessionActuality']['actualityPeriod'] + 1;
                if ($now >= $actualityEndTime) {
                    $this->recreateSession($now, $sessionId, $sessionName);
                }
            }

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_commit();
            }
        }

        /**
         * @throws $this->globalExceptionsManager:AppException If session is started
         */
        private function verifySessionIsNotStarted(): void
        {
            if (session_status() === PHP_SESSION_ACTIVE) {
                throw $this->globalExceptionsManager->getExceptionInstance(
                    'AppException',
                    'Session is already started.'
                );
            }
        }

        /**
         * @param $sessionId Current common session id. If null, standard session id will be used
         * @param $sessionName Current common session name. If null, standard session name will be used
         */
        private function startSession(?string $sessionId = null, ?string $sessionName = null): void
        {
            if ($sessionId !== null) {
                session_id($sessionId);
            }
            if ($sessionName !== null) {
                session_name($sessionName);
            }
            session_start();
        }

        /**
         * Regenerates session id and uses same as first self::configureSession() invocation for new session.
         * 
         * @param $now Time now (unix timestamp)
         * @param $sessionId Current common session id. If null, standard session id will be used
         * @param $sessionName Current common session name. If null, standard session name will be used
         */
        private function recreateSession(int $now, ?string $sessionId = null, ?string $sessionName = null): void
        {
            // set old session's end time
            $_SESSION['sessionActuality']['sessionEndTime'] = 
                $now + $_SESSION['sessionActuality']['periodToDeleteOldSession'] + 1;

            session_regenerate_id();

            // delete end time key from new session
            unset($_SESSION['sessionActuality']['sessionEndTime']);
            session_commit();

            // configure new session to manage its actuality
            $this->configureSession(
                $_SESSION['sessionActuality']['actualityPeriod'], 
                $_SESSION['sessionActuality']['periodToDeleteOldSession'],
                $sessionId, $sessionName
            );
        }

        /**
         * Deletes current session.
         */
        private function deleteSession(): void
        {
            // clean $_SESSION, delete session's cookie and destroy session
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $sessionCookieParams = session_get_cookie_params();
                setcookie(
                    session_name(), '', strtotime('2000-01-01'),
                    $sessionCookieParams['path'], $sessionCookieParams['domain'],
                    $sessionCookieParams["secure"], $sessionCookieParams["httponly"]
                );
            }
            session_destroy();
        }
    }