<?php

    namespace App;

    use App\GlobalModule\DIContainer;
    use App\GlobalModule\DIContainer\DIContainerModuleInterface;

    class AppDIContainerModuleTest extends \Codeception\Test\Unit
    {
        private static $appModularDiContainer;

        public static function setUpBeforeClass(): void
        {
            self::$appModularDiContainer = new DIContainer\AppDIContainer();
        }

        public function appDiModulesDataProvider()
        {
            $appDiModulesFilesPaths = [];
            
            $rootAppDiModuleFilesPath = APP_MODULES_DIR . '/AppDIContainerModule.php';
            if (file_exists($rootAppDiModuleFilesPath)) {
                array_push($appDiModulesFilesPaths, $rootAppDiModuleFilesPath);
            }

            $searchPathsInSubdirsResult = recursiveGlob(APP_MODULES_DIR . '/*/AppDIContainerModule.php');
            if ($searchPathsInSubdirsResult) {
                $appDiModulesFilesPaths = array_merge($appDiModulesFilesPaths, $searchPathsInSubdirsResult);
            }

            $appModulesDirPregQuoted = preg_quote(APP_MODULES_DIR);
            $appDiModuleClassesFullNames = array_map(function ($appDiModulePath) use ($appModulesDirPregQuoted) {
                $appDiModuleClassFullName = preg_replace('~^' . $appModulesDirPregQuoted . '~', 'App', $appDiModulePath);
                if ($appDiModuleClassFullName !== null) {
                    $appDiModuleClassFullName = preg_replace('~\.php$~', '', $appDiModuleClassFullName);
                }
                if ($appDiModuleClassFullName !== null) {
                    $appDiModuleClassFullName = str_replace('/', '\\', $appDiModuleClassFullName);
                };
                return $appDiModuleClassFullName;
            }, $appDiModulesFilesPaths);

            $returning = [];
            $diModulesCount = count($appDiModuleClassesFullNames);
            for ($i = 0; $i < $diModulesCount; ++$i) {
                $appDiModuleReflection = new \ReflectionClass($appDiModuleClassesFullNames[$i]);
                $appDiModulePublicMethodsReflections = $appDiModuleReflection->getMethods(\ReflectionMethod::IS_PUBLIC);

                $publicMethodsCount = count($appDiModulePublicMethodsReflections);
                for ($j = 0; $j < $publicMethodsCount; ++$j) {
                    if ($appDiModulePublicMethodsReflections[$j]->isConstructor()) {
                        unset($appDiModulePublicMethodsReflections[$j]);
                    }
                }

                $diModuleNumber = $i + 1;
                foreach ($appDiModulePublicMethodsReflections as $appDiModulePublicMethodReflection) {
                    $returningKey = "module #$diModuleNumber | class: {$appDiModuleClassesFullNames[$i]} | method: " . $appDiModulePublicMethodReflection->getName();
                    $returning[$returningKey] = [$appDiModuleClassesFullNames[$i], $appDiModulePublicMethodReflection];
                }
            }

            return $returning;
        }

        /**
         * @dataProvider appDiModulesDataProvider
         */
        public function testAppDiModule(string $appDiModuleClassFullName, \ReflectionMethod $appDiModulePublicMethodReflection): void
        {
            $appDiModule = new $appDiModuleClassFullName(self::$appModularDiContainer); codecept_debug(var_dump($appDiModule));
            $this->assertInstanceOf(DIContainerModuleInterface::class, $appDiModule);
            $appDiModulePublicMethodReflection->invoke($appDiModule);
        }
    }