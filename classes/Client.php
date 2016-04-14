<?php

require __DIR__ . '/../vendor/campaignmonitor/createsend-php/csrest_clients.php';

class Client {
    protected $client;

    public function __construct($clientId, $apiKey)
    {
        $this->client = new CS_REST_Clients(
            $clientId,
            $apiKey);
    }

    public function getDetails()
    {
        $result = $this->client->get();

        $return = $result->was_successful() ? $result->response : false;

        return $return;
    }

    public function getLists()
    {
        $result = $this->client->get_lists();

        $return = $result->was_successful() ? $result->response : false;

        return $return;
    }
}
