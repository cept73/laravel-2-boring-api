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

- Можно импортировать готовый пример запросов в PostMan: `BoredAPI.postman_collection.json` в корневой папке проекта 
- В BoredAPI возвращается `participants`, не `participant` 
- `type` разнообразен и трех перечисленных мало, поэтому для простоты в миграции используется string вместо enum
- Вместо отправки по почте, в этой версии добавляется в журнал
- В Get Activities могут использоваться фильтры page, participant, price, type
