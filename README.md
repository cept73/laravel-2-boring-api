# Platform

Testing task: using Laravel, implement a service that provides the following endpoints: (P.S. "I wrote this code 3 years ago, in the pre-AI era, while I was still deep into Yii2 projects, so please go easy on me!))")

```
1. POST /activities - fetch an activity from https://www.boredapi.com and return it
    key: int - activity key from https://www.boredapi.com, optional. If provided, the activity should be re-saved.

2. GET /activities - return all loaded activities with pagination and filtering (all filters are optional):
    2.1. participant: int
    2.2. price: float
    2.3. type: string (recreational, education, social)

3. GET /activities/{id} - return a loaded activity by the given id

4. DELETE /activities/{id} - delete a loaded activity by the given id
```

When returning an activity, include the date and time when it was loaded.

When a new activity is created or deleted, send an email to the administrator with the total number of loaded activities (actual sending is not required; it is enough to use mailer="log").

* Propose solutions to optimize the service performance

# Notes

— When opening the root page, a 404 page is displayed with a button linking to the Swagger documentation

![image](https://user-images.githubusercontent.com/16501564/213931367-1f835b59-9437-4629-9a16-548053d7913b.png)

— Swagger documentation is generated automatically based on `storage/api-docs/api.yaml`:

![image](https://user-images.githubusercontent.com/16501564/213931374-9a4c3707-6655-4dad-9669-9574a22caa82.png)
You can also import a ready-made request collection into Postman: `public/BoredAPI.postman_collection.json`

— RabbitMQ server is used and configured in .env. Emails and notifications are sent through its queue; they are physically processed by a listener (you need to install rabbitmq-server on the server, create an Exchange and Queue, for example `activities.update` with type direct, bind them, and specify the same in env: `AMQP_QUEUE=activities.update`)

— The listener is started via CLI using the command `php artisan queue:listen-activities`, with activity output

![image](https://user-images.githubusercontent.com/16501564/213787458-b77ad171-256c-4383-aa1f-70f508334869.png)

— There are automated tests, run with `php artisan test`

— GET /activities may use filters: page, participant, price, type

# Regarding the assignment

— The specification does not cover authorization, tokens, or secrets, which means the system is vulnerable to request flooding

— BoredAPI returns `participants`, not `participant`

— `type` has many possible values, and the three listed are not sufficient, so for simplicity a string is used instead of an enum in the migration
