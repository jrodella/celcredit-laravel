<?php

namespace Celcredit\Clients;

use Celcredit\Common\CelcreditBaseApi;

use Celcredit\Types\Webhook;
use Celcredit\Rules\Webhook as WebhookRule;

use Illuminate\Support\Facades\Validator;

class BankingOriginatorWebhooksClient extends CelcreditBaseApi
{
    public const REGISTER_ENDPOINT = '/banking/originator/webhooks';

    public const GET_ENDPOINT = '/banking/originator/webhooks';

    public const UPDATE_ENDPOINT = '/banking/originator/webhooks/%s';

    public const REMOVE_ENDPOINT = '/banking/originator/webhooks/%s';

    public function register(Webhook $data)
    {
        $body = Validator::validate($data->toArray(), WebhookRule::rules());

        return $this->post(
            self::REGISTER_ENDPOINT,
            $body
        );
    }

    public function listWebhooks()
    {
        return $this->get(self::GET_ENDPOINT);
    }

    public function edit(Webhook $data, $webhook_id)
    {
        $body = Validator::validate($data->toArray(), WebhookRule::rules());

        return $this->put(
            sprintf(self::UPDATE_ENDPOINT, $webhook_id),
            $body
        );
    }

    public function remove($webhook_id)
    {
        return $this->delete(
            sprintf(self::REMOVE_ENDPOINT, $webhook_id),
        );
    }
}
