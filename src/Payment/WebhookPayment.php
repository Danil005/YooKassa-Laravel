<?php

namespace Fiks\YooKassa\Payment;

use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\NotificationEventType;
use YooKassa\Model\Webhook\Webhook;
use YooKassa\Request\Webhook\WebhookListResponse;

class WebhookPayment
{
    /**
     * YooKassa Client
     *
     * @var Client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthToken('Bearer J86BB2QKHCnq3RaqiG5_F3o1v0x1aFvUrg0CL9FXHY9ty6zMS80oM3vEANdFLTad');
    }

    /**
     * Create Webhook
     *
     * @param string $url
     * @param string $event
     *
     * @return Webhook|null
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
    public function addWebhook(string $url, string $event = NotificationEventType::PAYMENT_SUCCEEDED)
    {
        return $this->client->addWebhook([
            'event' => $event,
            'url' => $url
        ]);
    }

    /**
     * Get List Webhooks
     *
     * @return WebhookListResponse|null
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws AuthorizeException
     */
    public function getWebhooks()
    {
        return $this->client->getWebhooks();
    }

    /**
     * Remove Webhook
     *
     * @param string $webhook_id
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
    public function deleteWebhook(string $webhook_id)
    {
        $response = $this->client->removeWebhook($webhook_id);
    }
}