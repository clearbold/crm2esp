<?php

class GenericCrm {

    public function __construct()
    {

    }

    public function getListToImport($listId)
    {
        /**
         * In a CRM-specific version of this class, you'll need to set up a
         * series of cases for each $listId. You can then route those to distinct
         * functions or code the CRM API fetch for each case. The assumption is that
         * each list will require CRM-specific code to fetch and massage data
         * before returning the JSON payload that the ESP expects. That work
         * is custom logic that happens here.
        */
        $result = false;
        switch ($listId) {
            case 'myListId':
                // Do some CRM-specific work
                break;
            default:
                // Catch-all demo for Campaign Monitor
                $result = $this->myListSpecificFunction($listId);
        }
        return $result;
    }

    private function myListSpecificFunction($listId)
    {
        $subscribers = [
            'Subscribers' => [
                [
                    'EmailAddress' => 'testuser+' . (string)time() . '@testdomain.com',
                    'Name' => '',
                    'CustomFields' => [
                        [
                            'Key' => '',
                            'Value' => ''
                        ]
                    ]
                ]/*,
                [
                    'EmailAddress' => '',
                    'Name' => '',
                ],
                [
                    'EmailAddress' => '',
                    'Name' => '',
                ]*/
            ],
            'Resubscribe' => false,
            'QueueSubscriptionBasedAutoResponders' => false,
            'RestartSubscriptionBasedAutoresponders' => false
        ];
        return $subscribers;
    }

}
