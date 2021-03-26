<?php

namespace Dan\Shopify\Integrations\Laravel\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class WebhookEvent.
 */
class WebhookEvent implements ShouldQueue
{
    use Queueable;

    /** @var string $topic */
    protected $topic;

    /** @var array $data */
    protected $data;

    /** @var string|null $shop */
    protected $shop;

    /**
     * WebhookEvent constructor.
     *
     * @param string $topic
     * @param array  $data
     * @param string $shop
     */
    public function __construct($topic, array $data, string $shop)
    {
        $this->topic = $topic;
        $this->data = $data;
        $this->shop = $shop;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @return string|null
     */
    public function getShop()
    {
        return $this->shop;
    }
}
