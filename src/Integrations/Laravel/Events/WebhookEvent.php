<?php

namespace Dan\Shopify\Integrations\Laravel\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class WebhookEvent
 */
class WebhookEvent implements ShouldQueue
{
    use Queueable;

    /** @var string $topic */
    protected $topic;

    /** @var array $data */
    protected $data;

    /**
     * Webhook constructor.
     *
     * @param string $topic
     * @param array $data
     */
    public function __construct($topic, array $data = [])
    {
        $this->topic = $topic;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
