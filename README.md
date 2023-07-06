*************************

### Тестовое задание:

Разработать REST API для записной книжки. Примерная структура методов:

1. GET /api/v1/notebook/
2. POST /api/v1/notebook/
3. GET /api/v1/notebook/\<id>/
4. POST /api/v1/notebook/\<id>/
5. DELETE /api/v1/notebook/\<id>/

Поля для POST записной книжки:

1. ФИО (обязательное)
2. Компания
3. Телефон (обязательное)
4. Email (обязательное)
5. Дата рождения
6. Фото

Нужна возможность выводить информацию в списке по странично

Swagger для отображения методов api (https://swagger.io/)

Так же напишите нам, как вы тестировали результат своей работы. Какие используете инструменты и как вы осуществляете тестирование.

Дополнительным плюсом будет: Финальный билд приложения должен быть запускаться из Docker контейнера (хотя бы с минимальной конфигурацией)
*************************

#### Реализация:

Ниже приведено описание реализации поставленной задачи с соблюдением основных требований.

Разработан REST API для записной книжки.

В проекте используются:<br>

1. Актуальные версии библиотек (Laravel Framework 10.14.1)
2. Все запросы идемпотентны
3. Стандартизированные ответы
4. Перехват и обработка http-ошибок, валидации и исключений
5. Сохранение в базу MySQL 
6. Версионирование изменений базы данных (миграции и фабрики)
7. Логирование в локальную директорию
8. Сохранение в локальную директорию полученных из запросов файлов
9. Авторизация не используется
10. Локализация кастомная и "Laravel-Lang/lang" (установлена локаль "ru")
11. Тестирование PHPUnit
12. Тестирование в Postman
13. Описание и тестирование api-методов в SwaggerUI

#### Требования
- PHP 8.2.7 (Xdebug v3.2.1)
- Composer version 2.5.8
- Laravel Framework 10.14.1
- PHPUnit 10.2.3
- MySQL 8.0.33
- Redis 6.0.16
- Postman 10.15.6
- Docker 24.0.2

#### Для сборки требуется:
- СУБД MySQL,
- созданная база notebook
- пользователь: root, пароль: password

Например, можно использовать следующие команды:

        mysql -u root -p 

        ALTER USER 'root'@'localhost' IDENTIFIED BY 'password';
        ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
        GRANT ALL PRIVILEGES ON * . * TO 'root'@'localhost'  WITH GRANT OPTION;
        flush privileges;

        CREATE SCHEMA notebook COLLATE utf8mb4_general_ci DEFAULT CHARACTER SET utf8mb4;

В корне проекта в консоли выполнить следующие команды:

    composer install
    composer dump-autoload -o
    php artisan config:cache
    php artisan route:cache
    php artisan migrate
    php artisan db:seed --class=NoteSeeder
    php artisan storage:link
    php artisan l5-swagger:generate

В консоли выполнить следующие команды:

    sudo apt install php8.2-sqlite3
    sudo chmod -R 777 /var/www/notebook/storage/framework/cache
    sudo chmod -R 777 /var/www/notebook/storage/logs
    sudo chmod -R 777 /var/www/notebook/public/storage


Запустить тестирование (используется база Sqlite in memory):<br>

    Конфигурация APP_ENV=testing
    php artisan cache:clear
    php artisan config:clear
    vendor/bin/phpunit tests -c phpunit.xml

#### Коллекции для тестирования в Postman
[Notebook.postman_collection.json](Notebook.postman_collection.json)


#### Документация API Auth
[Swagger Api Documentation](http://localhost/api/documentation/)

| Метод  | Описание                                                                                                                            | URL                                                       |
|--------|-------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------|
| GET    | Все записи в записной книжке<br/> запрос с пагинацией                                                                               | {URL}/api/v1/notebook?per_page={per_page}&cursor={cursor} |
| GET    | Все записи в записной книжке<br/> запрос с пагинацией по умолчанию: <br/> per_page=5 записей, начиная с cursor={next_cursor} записи | {URL}/api/v1/notebook/                                    |
| POST   | Создание записи в записной книжке                                                                                                   | {URL}/api/v1/notebook/                                    |
| GET    | Получение записи по id                                                                                                              | {URL}/api/v1/notebook/{id}                                |
| POST   | Обновление записи                                                                                                                   | {URL}/api/v1/notebook/{id}                                |
| DELETE | Удаление записи                                                                                                                     | {URL}/api/v1/notebook/{id}                                |

*************************
#### TODO: ПРИМЕЧАНИЯ 

- В соответствии с Best practices RESTFULL API именование роутов должно было быть следующего вида:

Если "Записная книжка" одна (сущность "Пользователь" не учитывается), и требуется управление только сущностями "Запись в записной книжке"

      GET /api/v1/notes/
      POST /api/v1/notes/
      GET /api/v1/notes/{note_id}
      POST /api/v1/notes/{note_id}
      DELETE /api/v1/notes/{note_id}
    
    Если требуется указывать в адресе название сервиса "notebook"

      GET /api/v1/notebook/notes/
      POST /api/v1/notebook/notes/
      GET /api/v1/notebook/notes/{note_id}
      POST /api/v1/notebook/notes/{note_id}
      DELETE /api/v1/notebook/notes/{note_id}

Либо, если в ТЗ появится уточнение, что требуется функционал для множества "Записных книжек" для множества "Пользователей"

    GET /api/v1/notebooks/{notebook_id}/notes/
    POST /api/v1/notebooks/{notebook_id}/notes/
    GET /api/v1/notebooks/{notebook_id}/notes/{note_id}
    POST /api/v1/notebooks/{notebook_id}/notes/{note_id}
    DELETE /api/v1/notebooks/{notebook_id}/notes/{note_id}

##### Реализация проекта максимально строго следует требованиям ТЗ, поэтому используется именование для адресации ресурсов, описанное в ТЗ.

*************************
