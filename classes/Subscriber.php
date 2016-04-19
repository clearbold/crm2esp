<?php

namespace Crm2Esp;

require __DIR__ . '/../vendor/campaignmonitor/createsend-php/csrest_subscribers.php';

class Subscriber {
    protected $list;

    public function __construct($listId, $apiKey)
    {
        $this->list = new \CS_REST_Subscribers(
            $listId,
            $apiKey);
    }

    public function importSubscribers($subscribers)
    {
        $result = $this->list->import($subscribers, false);

        $return = $result->was_successful() ? $result->response : false;

        return $return;
    }
}
