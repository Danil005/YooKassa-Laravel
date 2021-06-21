<?php

include __DIR__ .'./../vendor/autoload.php';

$yooMoney = new \Fiks\YooMoney\YooKassaApi();

$response = $yooMoney->createPayment(100, 'RUB');

