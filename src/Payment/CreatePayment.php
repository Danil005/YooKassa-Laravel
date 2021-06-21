<?php

namespace Fiks\YooKassa\Payment;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use YooKassa\Request\Payments\CreatePaymentResponse;

class CreatePayment
{
    /**
     * Payment Response
     *
     * @var CreatePaymentResponse|null
     */
    private ?CreatePaymentResponse $response;

    /**
     * User ID
     *
     * @var int|null
     */
    private ?int $user_id;

    /**
     * Uniq ID
     *
     * @var string
     */
    private string $uniq_id;

    public function __construct(?CreatePaymentResponse $response, string $uniq_id, int $user_id = null)
    {
        $this->response = $response;
        $this->uniq_id = $uniq_id;

        $this->user_id = $user_id;

        # Save to DataBase
        $this->saveDatabase();
    }

    private function saveDatabase()
    {
        # Create Carbon Now
        $times = Carbon::now();

        # Insert to Database
        DB::table('yookassa')->insert([
            'user_id' => $this->user_id,
            'uniq_id' => $this->uniq_id,
            'payment_id' => $this->response->getId(),
            'status' => $this->response->getStatus(),
            'paid' => $this->response->getPaid(),
            'sum' => $this->response->getAmount()->getIntegerValue() / 100,
            'currency' => $this->response->getAmount()->getCurrency(),
            'payment_link' => $this->response->confirmation->getConfirmationUrl(),
            'created_at' => $times->toDateTimeString(),
            'updated_at' => $times->toDateTimeString()
        ]);
    }

    /**
     * Get Response
     * https://github.com/yoomoney/yookassa-sdk-php/blob/master/docs/classes/YooKassa-Request-Payments-CreatePaymentResponse.md#methods
     * @return CreatePaymentResponse|null
     */
    public function response(): ?CreatePaymentResponse
    {
        return $this->response;
    }
}