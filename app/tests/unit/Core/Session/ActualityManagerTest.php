<?php

    namespace App\Core\Session;
    use App\GlobalModule\DIContainer\AppDIContainer;

    class ActualityManagerTest extends \Codeception\Test\Unit
    {
        private static $diContainer;
        
        public static function setUpBeforeClass(): void
        {
            self::$diContainer = new AppDIContainer();
        }
        public function tearDown(): void
        {
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION = [];
                session_destroy();
            }
        }

        public function testActualityManagementCycle(): void
        {
            $sessionActualityManager = self::$diContainer->getClassInstance(ActualityManager::class); 
            $firstSessionId = $this->initializeSession($sessionActualityManager);

            sleep(1);
            $this->atTimeOfSessionActuality($sessionActualityManager, $firstSessionId);

            sleep(3);
            $this->afterTimeOfSessionActuality($sessionActualityManager, $firstSessionId);

            sleep(3);
            $this->afterTimeOfOldSessionAvailability($sessionActualityManager);
        }

        private function initializeSession(ActualityManager $sessionActualityManager): string
        {
            session_start();
            $firstSessionId = session_id();
            session_commit();

            $sessionActualityManager->configureSession(3, 2);

            session_start();
            $_SESSION['isDataAvailable'] = true;
            session_commit();

            return $firstSessionId;
        }

        private function atTimeOfSessionActuality(ActualityManager $sessionActualityManager, string $firstSessionId): void
        {
            $sessionActualityManager->processSession();
            session_start();
            $this->assertTrue(isset($_SESSION['isDataAvailable']), 'Session data is available at the time of session actuality.');
            $this->assertTrue(session_id() === $firstSessionId, 'Session id is not changed at the time of session actuality.');
            session_commit();
        }
        private function afterTimeOfSessionActuality(ActualityManager $sessionActualityManager, string $firstSessionId): void
        {
            $sessionActualityManager->processSession();
            session_start();
            $this->assertTrue(isset($_SESSION['isDataAvailable']), 'Session data is available in regenerated session.');
            $secondSessionId = session_id();
            $this->assertTrue($secondSessionId !== $firstSessionId, 'Session id is changed after the time of session actuality.');

            unset($_SESSION['isDataAvailable']);
            session_commit();
            session_id($firstSessionId);
            $sessionActualityManager->processSession($firstSessionId);
            session_start();
            $this->assertTrue(isset($_SESSION['isDataAvailable']), 'Old session data is available at the time of availability of old session.');
            session_commit();
        }
        private function afterTimeOfOldSessionAvailability(ActualityManager $sessionActualityManager): void
        {
            $sessionActualityManager->processSession();
            session_start();
            $this->assertFalse(isset($_SESSION['isDataAvailable']), 'Old session data is not available after the time of availability of old session.');
        }
    }