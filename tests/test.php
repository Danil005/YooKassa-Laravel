<?php

include __DIR__ .'./../vendor/autoload.php';

$yooMoney = new \Fiks\YooMoney\YooMoneyApi();

$response = $yooMoney->createPayment(100, 'RUB');

