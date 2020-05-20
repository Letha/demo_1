<?php

    namespace App;
    use App\GlobalModule\Exception\Management\ExceptionsManagerInterface;

    class ExceptionsManagerTest extends \Codeception\Test\Unit
    {
        public function exceptionsManagersDataProvider()
        {
            $exceptionsManagersFilesPaths = [];
            
            $rootExceptionsManagersFilesPath = APP_MODULES_DIR . '/ExceptionsManager.php';
            if (file_exists($rootExceptionsManagersFilesPath)) {
                array_push($exceptionsManagersFilesPaths, $rootExceptionsManagersFilesPath);
            }

            $searchPathsInSubdirsResult = recursiveGlob(APP_MODULES_DIR . '/*/ExceptionsManager.php');
            if ($searchPathsInSubdirsResult) {
                $exceptionsManagersFilesPaths = array_merge($exceptionsManagersFilesPaths, $searchPathsInSubdirsResult);
            }

            $exceptionsManagersDirPregQuoted = preg_quote(APP_MODULES_DIR);
            $exceptionsManagersClassesFullNames = array_map(function ($exceptionsManagerPath) use ($exceptionsManagersDirPregQuoted) {
                $exceptionsManagerClassFullName = preg_replace('~^' . $exceptionsManagersDirPregQuoted . '~', 'App', $exceptionsManagerPath);
                if ($exceptionsManagerClassFullName !== null) {
                    $exceptionsManagerClassFullName = preg_replace('~\.php$~', '', $exceptionsManagerClassFullName);
                }
                if ($exceptionsManagerClassFullName !== null) {
                    $exceptionsManagerClassFullName = str_replace('/', '\\', $exceptionsManagerClassFullName);
                };
                return $exceptionsManagerClassFullName;
            }, $exceptionsManagersFilesPaths);

            $returning = [];
            $exceptionsManagersCount = count($exceptionsManagersClassesFullNames);
            for ($i = 0; $i < $exceptionsManagersCount; ++$i) {
                $exceptionsManagerReflection = new \ReflectionClass($exceptionsManagersClassesFullNames[$i]);
                $exceptionsManagerPublicMethodsReflections = $exceptionsManagerReflection->getMethods();
                
                $publicMethodsCount = count($exceptionsManagerPublicMethodsReflections);
                for ($j = 0; $j < $publicMethodsCount; ++$j) {
                    $getInstanceMethodNameMatching = preg_match('~^get(.*)Instance$~', $exceptionsManagerPublicMethodsReflections[$j]->getName(), $getInstanceMatchings);
                    if ($getInstanceMethodNameMatching === false) {
                        throw new \Exception('Unexpected behavior.');
                    }

                    $exceptionsManagerNumber = $i + 1;
                    if (
                        $getInstanceMethodNameMatching === 1 &&
                        $exceptionsManagerPublicMethodsReflections[$j]->getName() !== 'getExceptionInstance'
                    ) {
                        $exceptionsManagerPublicMethodsReflections[$j]->setAccessible(true);

                        $returningKey = "manager #$exceptionsManagerNumber | class: {$exceptionsManagersClassesFullNames[$i]} | exception: {$getInstanceMatchings[1]}";
                        $returning[$returningKey] = [$exceptionsManagersClassesFullNames[$i], $getInstanceMatchings[1], $exceptionsManagerPublicMethodsReflections[$j]];
                    }
                }
            }

            return $returning;
        }

        /**
         * @dataProvider exceptionsManagersDataProvider
         */
        public function testExceptionsManagers(string $exceptionsManagerClassFullName, string $exceptionLastName, \ReflectionMethod $exceptionsManagerPublicMethodReflection): void
        {
            $exceptionsManager = new $exceptionsManagerClassFullName();
            $this->assertInstanceOf(ExceptionsManagerInterface::class, $exceptionsManager);

            $exceptionInstance = $exceptionsManagerPublicMethodReflection->invoke($exceptionsManager, $exceptionLastName);

            $this->assertTrue(method_exists($exceptionsManager, "isInstanceOf{$exceptionLastName}"));

            $isInstanceOfMethod = new \ReflectionMethod($exceptionsManager, "isInstanceOfException");
            $isInstanceOfMethod->setAccessible(true);
            $this->assertTrue($isInstanceOfMethod->invoke($exceptionsManager, $exceptionLastName, $exceptionInstance));
        }
    }