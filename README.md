###### Lang: Russia

# YooKassa Laravel Packagist

##### Version: `0.0.2-beta`

##### Author: Danil Sidorenko

## <a name="list"></a>Оглавление

1. [Get Started](#getStarted)
2. [Доступные методы](#methods)
   * [createPayment](#methods-create-payment)

## <a name="getStarted"></a>1. GetStarted | [Top](#list)
Laravel пакет для YooKassa. Предназначен для упрощения работы

---

####  Установка
`composer require fiks/yoo-kassa-laravel`


#### config/app.php

```php
'providers' => [
	...
	Fiks\YooKassa\YooKassaServiceProvider::class,
	...
],
'aliases' => [
	...
	'YooKassa' => Fiks\YooKassa\YooKassaFacade::class,
	...
]
```



---
#### Публикация конфигураций
`php artisan vendor:publish --provider=Fiks\YooKassa\YooKassaServiceProvider`

---
#### Миграция (Обязательно)
Создать миграцию
`php artisan migrate`

Создает таблицу в базе данных: `yookassa`

|Столбец|Тип данных|Описание|
|--|--|--|
|{field_foreign}|unsignedBigInteger \| null | ID-пользователя, если был передан.`foreign {field_foreign} references {field_references} on {field_on} onDelete {field_delete}`
|payment_id|string|ID-платежа из системы YooKassa
|status|enum(['pending', 'waiting_for_capture', 'succeeded', 'canceled'])|[Статус платежа](https://yookassa.ru/developers/payments/payment-process#lifecycle)|
|paid|boolean|Признак оплаты заказа
|sum|float, 2|Сумма заказа с округлением в 2 знака после запятой
|currency|string|Код валюты по [ISO 4217](https://index.minfin.com.ua/reference/currency/code/)
|payment_link|string|Ссылка для оплаты заказа на YooKassa
|description|string|Описание заказа
|paid_at|datetime|Дата оплаты в формате: `2018-07-25T10:52:00.233Z`|
|uniq_id|string|Уникальный ID в Вашей системе
|created_at|datetime|Дата создания заказа
|updated_at|datetime|Дата обновления заказа

> {field_foreign} -- возможно изменить данный столбец в .env: `YOOKASSA_DATABASE_FIELD_FOREIGN`. По умолчанию: user_id
> {field_on} -- возможно изменить данный столбец в .env: `YOOKASSA_DATABASE_FIELD_ON`. По умолчанию: users
> {field_references} -- возможно изменить данный столбец в .env: `YOOKASSA_DATABASE_FIELD_REFERENCES`. По умолчанию: id
> {field_delete} -- возможность изменить данный столбец в .env: `YOOKASSA_DATABASE_FIELD_ON_DELETE`. По умолчанию: cascade

---
#### .env
Параметры в .env:
```env
# Обязательные поля

YOOKASSA_TOKEN=<API токен из магазина>
YOOKASSA_ID=<ID-магазина>
YOOKASSA_REDIRECT=<Ссылка после оплаты>

# Необязательные поля
YOOKASSA_DATABASE_FIELD_FOREIGN=<Поле которое будет зависить>
YOOKASSA_DATABASE_FIELD_ON=<Таблица с которой есть зависимость>
YOOKASSA_DATABASE_FIELD_REFERENCES=<Поле от которого будет зависить>
YOOKASSA_DATABASE_FIELD_ON_DELETE=<Тип удаления строки>
```
Токен можно взять: https://yookassa.ru/my/merchant/integration/api-keys

## <a name="methods"></a> 2. Доступные методы | [Top](#list)
Методы для взаимодействия с API

|Метод|Описание|
|--|--|
|createPayment|Создать ссылку на оплату

---

### <a name="methods-create-payment"></a> createPayment | [Top](#list)

Создать ссылку на оплату

##### Аргументы

|Параметр|Тип данных|Описание|Пример|
|--|--|--|--|
|sum|float|Сумма заказа|100.00|
|currency|string|Код валюты по [ISO 4217](https://index.minfin.com.ua/reference/currency/code/)|RUB
|description|string|Описание заказа|Заказ №1
|user_id|string \| null|ID-пользователя|1

##### Возможные ошибки:
|Тип|Описание|
|--|--|
|ApiException|Неожиданный код ошибки.
|BadApiRequestException|Неправильный запрос. Чаще всего этот статус выдается из-за нарушения правил взаимодействия с API.
|ExtensionNotFoundException|Требуемое PHP расширение не установлено.
|ForbiddenException|Секретный ключ или OAuth-токен верный, но не хватает прав для совершения операции.
|InternalServerError|Технические неполадки на стороне ЮKassa. Результат обработки запроса неизвестен. Повторите запрос позднее с тем же ключом идемпотентности.|
|NotFoundException|Ресурс не найден.|
|ResponseProcessingException|Запрос был принят на обработку, но она не завершена.|
|TooManyRequestsException|Превышен лимит запросов в единицу времени. Попробуйте снизить интенсивность запросов.|
|UnauthorizedException|Неверный идентификатор вашего аккаунта в ЮKassa или секретный ключ (имя пользователя и пароль при аутентификации).|

##### Успешная операция
Возвращает класс `CreatePayment`

|Методы|Описание|Возвращает|
|--|--|--|
|response()|Получить ответ от создания платежа|[CreatePaymentResponse](https://github.com/yoomoney/yookassa-sdk-php/blob/master/docs/classes/YooKassa-Request-Payments-CreatePaymentResponse.md#methods) \| null