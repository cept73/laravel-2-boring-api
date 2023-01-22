# Платформа

С использование Laravel написать сервис который будет иметь эндпоинты:

    1. POST /activities - загружать активность из [https://www.boredapi.com](https://www.boredapi.com/) и показывать ее
        key: int - ключ активности из [https://www.boredapi.com](https://www.boredapi.com/), опционален. Если есть, то активность надо пересохранить.

    2. GET /activities - показать все загруженные активности с пагинацией и фильтрацией (все фильтры опциональны):
        2.1. participant: int
        2.2. price: float
        2.3. type: string(recreational, education, social)

    3. GET /activities/{id} - показать загруженную активность с заданным id

    4. DELETE /activities/{id} - удалить загруженную активность с заданным id

При выводе активности показывать дату и время загрузки

При загрузке или удалении новой активности посылать email администратору с кол-вом загруженных активностей(отправку реализовывать не надо, достаточно использовать mailer=”log”)

 * Предложить решения для оптимизации производительности сервиса

# Комментарии

— Открывая начальную страницу, показывается 404 страница с кнопкой перехода на документацию Swagger

![image](https://user-images.githubusercontent.com/16501564/213931367-1f835b59-9437-4629-9a16-548053d7913b.png)

— Документация Swagger генерируется автоматически на основании `storage/api-docs/api.yaml`:

![image](https://user-images.githubusercontent.com/16501564/213931374-9a4c3707-6655-4dad-9669-9574a22caa82.png)
Можно также импортировать готовый пример запросов в PostMan: `BoredAPI.postman_collection.json` в public

— Используется RabbitMQ сервер, настраивается в .env. Отправка писем и уведомлений идет через его очередь, физически их отправляет слушатель (на сервере нужно установить rabbitmq-server, создать Exchange и Queue например `activities.update` с типом direct и соединить их, указать в env такой же: `AMQP_QUEUE=activities.update`)

— Слушатель запускается через CLI коммандой `php artisan queue:listen-activities`, с выводом активности

![image](https://user-images.githubusercontent.com/16501564/213787458-b77ad171-256c-4383-aa1f-70f508334869.png)

— Есть автотесты, запускаются `php artisan test`

— В Get Activities могут использоваться фильтры page, participant, price, type

# По заданию

— По ТЗ не продумана авторизация, токены или секрет, а следовательно возможность перегрузить систему запросами

— В BoredAPI возвращается `participants`, не `participant`

— `type` разнообразен и трех перечисленных мало, поэтому для простоты в миграции используется string вместо enum
