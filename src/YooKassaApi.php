<?php

namespace Fiks\YooKassa;

use Carbon\Carbon;
use Fiks\YooKassa\Payment\CodesPayment;
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
     * Table Yookassa
     *
     * @var string
     */
    private string $table;

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

        # Table
        $this->table = env('YOOKASSA_DATABASE_TABLE_NAME', 'yookassa');
    }

    /**
     * Finish Payment
     *
     * @param string $payment_id
     * @param        $response
     */
    private function finishPayment(string $payment_id, $response)
    {
        $status = $response->getStatus();

        DB::table($this->table)->where('payment_id', $payment_id)->update([
            'paid' => $response->getPaid(),
            'sum' => $response->getAmount()->getIntegerValue() / 100,
            'currency' => $response->getAmount()->getCurrency(),
            'paid_at' => $status == 'succeeded' ? Carbon::now()->toDateTimeString() : null,
            'status' => $status
        ]);
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
            'amount'       => [
                'value'    => $sum,
                'currency' => $currency
            ],
            'confirmation' => [
                'type'       => 'redirect',
                'return_url' => $this->config['redirect_uri'] . '?uniq_id=' . $uniq_id
            ],
            'metadata'     => [
                'uniq_id' => $uniq_id
            ],
            'capture'      => true,
            'description'  => $description,
        ], $uniq_id), $uniq_id, $user_id);
    }

    /**
     * Checking Payments
     *
     * @param string        $uniq_id
     * @param callable      $success
     * @param callable|null $failed
     *
     * @return mixed
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
    public function checkPayment(string $uniq_id, callable $success, callable $failed = null)
    {
        # Get Invoice From Database
        $invoice = DB::table($this->table)->where('uniq_id', $uniq_id)->first();

        $uniq_id = uniqid('', true);
        # If Invoice Not Found
        if(is_null($invoice))
            return [
                'error' => 'Invalid Invoice',
                'code'  => CodesPayment::INVOICE_NOT_FOUND
            ];

        # Get Payment Info
        $payment = $this->client->getPaymentInfo($invoice->payment_id);

        # Validation Payment Life
        if($payment->getStatus() == 'waiting_for_capture') {
            $response = $this->client->capturePayment([
                'amount' => [
                    'value'    => $invoice['sum'],
                    'currency' => $invoice['currency'],
                ],
            ], $invoice->payment_id, $uniq_id);

            if($response->getStatus() == 'succeeded') {
                # Finish Payment
                $this->finishPayment($invoice->payment_id, $response);
                return $success($response, $invoice);
            } else {
                # Finish Payment
                $this->finishPayment($invoice->payment_id, $response);

                if($failed)
                    return $failed($response, $invoice);

                return [
                    'error' => 'Canceled Invoice',
                    'code'  => CodesPayment::CANCELED_INVOICE
                ];
            }
        } elseif($payment->getStatus() == 'succeeded') {
            # Finish Payment
            $this->finishPayment($invoice->payment_id, $payment);

            return $success($payment, $invoice);
        } else {
            # Finish Payment
            $this->finishPayment($invoice->payment_id, $payment);

            if($failed)
                return $failed($payment, $invoice);

            return [
                'error' => 'Canceled Invoice',
                'code'  => CodesPayment::CANCELED_INVOICE
            ];
        }
    }
}