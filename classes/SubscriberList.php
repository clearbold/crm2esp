<?php

require __DIR__ . '/../vendor/campaignmonitor/createsend-php/csrest_lists.php';

class SubscriberList {
    protected $list;

    public function __construct($listId, $apiKey)
    {
        $this->list = new CS_REST_Lists(
            $listId,
            $apiKey);
    }

    public function getDetails()
    {
        $result = $this->list->get();

        $return = $result->was_successful() ? $result->response : false;

        return $return;
    }

    public function getCustomFields()
    {
        $result = $this->list->get_custom_fields();
        $fields = array();
        foreach ( $result->response as $field )
            $fields[] = substr(substr($field->Key, 1, strlen($field->Key)-1), 0, -1);
        $return = $result->was_successful() ? $fields : false;

        return $return;
    }

    public function getActiveSubscribers()
    {
        $result = $this->list->get_stats();

        $return = $result->was_successful() ? $result->response->TotalActiveSubscribers : false;

        return $return;
    }
}
