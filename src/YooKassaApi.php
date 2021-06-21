<?php

namespace Fiks\YooKassa;

use Fiks\YooKassa\Payment\CreatePayment;
use Illuminate\Support\Facades\DB;
use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Request\Payments\CreatePaymentResponse;

class YooKassaApi
{
    /**
     * Configuration YooMoney
     *
     * @var array
     */
    private array $config;

    /**
     * YooKassa Client
     *
     * @var Client
     */
    private Client $client;

    /**
     * YooMoneyApi constructor.
     */
    public function __construct(array $config = [])
    {
        $default = config('yookassa');
        # Configuration
        $this->config = array_merge($config, $default);

        # Create Client
        $this->client = new Client();
        # Create Authorization
        $this->client->setAuth($this->config['shop_id'], $this->config['token']);
    }

    /**
     * Create Payment
     *
     * @param float    $sum
     * @param string   $currency
     * @param string   $description
     * @param int|null $user_id
     *
     * @return CreatePayment
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function createPayment(float $sum, string $currency, string $description, int $user_id = null): CreatePayment
    {
        # Uniq ID
        $uniq_id = uniqid('', true);

        # Create Request
        return new CreatePayment($this->client->createPayment([
            'amount' => [
                'value' => $sum,
                'currency' => $currency
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $this->config['redirect_uri'] . '?uniq_id='.$uniq_id
            ],
            'metadata' => [
                'uniq_id' => $uniq_id
            ],
            'capture' => true,
            'description' => $description,
        ], $uniq_id), $uniq_id, $user_id);
    }


}