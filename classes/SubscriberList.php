<?php

namespace Crm2Esp;

require __DIR__ . '/../vendor/campaignmonitor/createsend-php/csrest_lists.php';

class SubscriberList {
    protected $apiKey;
    protected $client;
    protected $clientId;
    protected $clientLists;
    protected $list;
    protected $lists;

    public function __construct($apiKey, $clientId = NULL, $listId = NULL)
    {
        $this->apiKey = $apiKey;
        $this->clientId = $clientId;

        if ( !is_null($listId) )
            $this->list = new \CS_REST_Lists(
                $listId,
                $apiKey);

        if ( !is_null($clientId) )
        {
            $this->clientLists = new \CS_REST_Lists(
                NULL,
                $apiKey);

            $this->client = new Client($clientId, $apiKey);
            $this->lists = $this->client->getLists();
        }
    }

    public function createList($listName)
    {
        // If list in all lists, return list ID
        foreach ( $this->lists as $list )
        {
            if ( $list->Name == $listName )
                return $list->ListID;
        }

        // Else, create list
        $result = $this->clientLists->create($this->clientId, array(
            'Title' => $listName,
            'UnsubscribeSetting' => CS_REST_LIST_UNSUBSCRIBE_SETTING_ALL_CLIENT_LISTS
        ));

        if ( $result->was_successful() ) {
            return $result->response;
        } else {
            // handle error;
            return false;
        }
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

    public function deleteAllLists()
    {
        foreach ( $this->lists as $list )
        {
            $list = new \CS_REST_Lists(
                $list->ListID,
                $this->apiKey);
            $result = $list->delete();
        }
        return 1;
    }
}
