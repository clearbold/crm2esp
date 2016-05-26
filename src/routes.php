<?php
// Routes

$app->get('/delete-lists/{profile}', function ($request, $response, $args) {
    $profile = $request->getAttribute('profile');

    $subscriberList = new \Crm2Esp\SubscriberList($this->cm['clients']['clientApiKey'], $this->cm['clients']['clientId'], NULL);
    $subscriberList->deleteAllLists();
})->setName('deleteLists');

$app->get('/import-list/{listId}', function ($request, $response, $args) {

    $listId = $request->getAttribute('listId');

    $crm = new ReflectionClass($this->provider);
    $crm = $crm->newInstance($this->cm, $this->crm, $this->db);

    if ( $crm->getListToImport($listId) > 0 )
    {
        // Redirect to run-import/$listId
        return $response->withRedirect("/run-import/$listId");
    }
    else
    {
        $response->withJson(0);
    }
    exit;
    $subscriberImport = new \Crm2Esp\Subscriber($listId, $this->cm['clientApiKey']);
    $result = $subscriberImport->importSubscribers(
                                $listToImport['Subscribers'],
                                $listToImport['Resubscribe'],
                                $listToImport['QueueSubscriptionBasedAutoResponders'],
                                $listToImport['RestartSubscriptionBasedAutoresponders']);
    $response->withJson($result);

})->setName('importList');

$app->get('/run-import/{source}', function ($request, $response, $args) {
    $source = $request->getAttribute('source');
    $crm = new ReflectionClass($this->provider);
    $crm = $crm->newInstance($this->cm, $this->crm, $this->db);
    $crm->runImport($source);
})->setName('runImport');

$app->get('/console', function($request, $response, $args) {

    $activeLists = array();
    $inactiveLists = array();

    $sources = array();

    foreach($this->crm['sources'] as $source)
    {
        $sources[] = array(
            'source' => $source,
            'taskUrl' => $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . '/import-list/' . $source
        );
    }

    foreach($this->cm['clients'] as $clientSettings)
    {
        $client = new \Crm2Esp\Client($clientSettings['clientId'], $clientSettings['clientApiKey']);
        $lists = $client->getLists();

        $clientDetails = $client->getDetails();

        foreach($lists as $list)
        {
            if ( in_array($list->ListID, $clientSettings['subscriberLists']) )
            {
                $subscriberList = new \Crm2Esp\SubscriberList($clientSettings['clientApiKey'], NULL, $list->ListID);
                $list->customFields = $subscriberList->getCustomFields();
                $list->activeSubscribers = $subscriberList->getActiveSubscribers();
                $list->taskUrl = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . '/import-list/' . $list->ListID;
                $activeLists[] = $list;
            }
            else
                $inactiveLists[] = $list;
        }
    }

    return $this->view->render($response, 'console/index.html', [
        'clientName' => $clientDetails->BasicDetails->CompanyName,
        'clientApiKey' => $clientDetails->ApiKey,
        'clientApiId' => $clientDetails->BasicDetails->ClientID,
        'activeLists' => $activeLists,
        'inactiveLists' => $inactiveLists,
        'sources' => $sources
    ]);

})->setName('console');

$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(302)->withHeader('Location', '/console');
});
