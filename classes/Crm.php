<?php

namespace Crm2Esp;

interface Crm {

    public function __construct($cm, $crm);

    /**
     * Returns an array. See GenericCrm for examples.
     * Required output:
     * Subscribers
     * Resubscribe true/false
     * QueueSubscriptionBasedAutoResponders true/false
     * RestartSubscriptionBasedAutoresponders true/false
     *
     * See https://www.campaignmonitor.com/api/subscribers/#importing_many_subscribers
    */
    public function getListToImport($listId);

}
