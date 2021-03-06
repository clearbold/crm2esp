<?php

namespace Crm2Esp;

class GenericCrm implements Crm {

    protected $cm;
    protected $crm;
    protected $db;

    public function __construct($cm, $crm, $db)
    {
        $this->cm = $cm;
        $this->crm = $crm;
        $this->db = $db;
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
            case 'specificListId':
                $result = $this->myListSpecificFunction($listId);
                break;
            default:
                $result = [
                    'Subscribers' => [
                        [
                            'EmailAddress' => 'testuser+' . (string)time() . '@testdomain.com',
                        ]
                    ],
                    'Resubscribe' => false,
                    'QueueSubscriptionBasedAutoResponders' => false,
                    'RestartSubscriptionBasedAutoresponders' => false
                ];
                break;
        }
        return $result;
    }

    public function runImport($listId)
    {
        $result = 0;
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
