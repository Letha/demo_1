## Ссылки и развёртывание локально
Сайт проекта : http://s7478147.beget.tech/</br></br>
Для установки проекта локально - загрузить зависимости через Composer.</br></br>
Функциональное тестирование настроено на localhost, если нужно изменить - настройка есть в /app/tests/functional.suite.yml (настройка модуля "PhpBrowser" - url).
## Общие аспекты
Проект ориентирован на архитектуру ООП, SOLID.</br>
Мультиязычность.</br></br>
Функционал приложения : формы регистрации/авторизации с возможностью загрузки изображения (кнопки открытия форм сверху справа), отображение профиля пользователя.</br></br>
Работа с базой данных через подготовленные запросы, "экранирование" вывода на front-end.
## Аккаунты для демонстрации (установлены на сайте проекта)
Без изображения профиля :
- логин : g1
- пароль : 1234567890</br></br>
С изображением профиля :
- логин : g2
- пароль : 1234567890
## На front-end'е
- Адаптивная вёрстка.
- CSS grid.
- CSS по методологии БЭМ.
- Всплывающие окна (открывашки, закрывашки).
- Индикаторы ожидания ответа сервера.</br></br>
JavaScript :
- native JavaScript (без сторонних библиотек, фреймворков);
- MVC (passive view);
- разделение на модули;
- ES6 (классы, стрелки, await...).
## На back-end'е
- Native PHP (без сторонних библиотек, фреймворков).
- MVC, роутинг.
- Dependency injection (DI), общий DI container.
- Управление актуальностью PHP-сессиий на основе временных меток.
- PDO.
- Немного unit- и интеграционного тестирования для демонстрации умений и для критически важных частей программы (PHPUnit код, работающий через Codeception) {/app/src/tests/unit, /app/src/tests/integration}.
- Немного функционального тестирования для демонстрации умений {/app/src/tests/functional}.
## Примечания
Комментарии к коду есть в основном на back-end'e (для экономии времени).</br></br>
Есть дамп базы данных {/db-dumps} (демонстрирует её структуру).</br></br>
Мuli-unit тест (в проекте такие есть) - это единый тест для тестирования нескольких классов, имеющих единый интерфейс.</br></br>
Запуск тестов из консоли (находясь в директории /app) : 
- на Windows : vendor\bin\codecept.bat run
- на Unix : php vendor/bin/codecept run