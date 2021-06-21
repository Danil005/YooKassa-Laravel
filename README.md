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

---
#### Публикация конфигураций
`php artisan vendor:publish`

---
#### .env
Обязательные параметры в .env:
```env
YOOKASSA_TOKEN=<API токен из магазина>
YOOKASSA_ID=<ID-магазина>
YOOKASSA_REDIRECT=<Ссылка после оплаты>
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
Возвращает класс [CreatePaymentResponse](https://github.com/yoomoney/yookassa-sdk-php/blob/master/docs/classes/YooKassa-Request-Payments-CreatePaymentResponse.md#methods)