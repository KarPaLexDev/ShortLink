PHP 8.0

Сервак натравливать на корень проекта

БД - mysql 8

Таблица users имеет столбец name и token (для аутентификации)

Таблица links содержит поля url, hash, created_at, updated_at

Таблица transitions содержит поля link_id и created_at

Дамп в репозитории

API

Параметры отправлять либо в url (в случае GET), либо json-ом в теле запроса

GET /api/links - получение списка всех ссылок. Фильтры: url, hash, 
created_at_from, created_at_to, updated_at_from, updated_at_to, id 

GET /api/links/{id} - получение информации о ссылке по id

POST /api/links - создание ссылки. Поля: url

PUT /api/links/{id} - создание ссылки. Поля: url, hash

GET /api/transitions - получение списка всех переходов по ссылкам. Фильтры: url, hash,
created_at_from, created_at_to, id, link_id 


