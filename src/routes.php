<?php
// Routes

$app->get('/import-list/{listId}', function ($request, $response, $args) {

    $listId = $request->getAttribute('listId');

    $crm = new ReflectionClass($this->provider);
    $crm = $crm->newInstance();

    $subscriberImport = new Subscriber($listId, $this->cm['clientApiKey']);

    $result = $subscriberImport->importSubscribers($crm->getListToImport($listId)['Subscribers'], false);
    $response->withJson($result);

})->setName('importList');

$app->get('/console', function($request, $response, $args) {

    $client = new Client($this->cm['clientId'], $this->cm['clientApiKey']);
    $lists = $client->getLists();

    $clientDetails = $client->getDetails();

    $activeLists = array();
    $inactiveLists = array();

    foreach($lists as $list)
    {
        if ( in_array($list->ListID, $this->cm['subscriberLists']) )
        {
            $subscriberList = new SubscriberList($list->ListID, $this->cm['clientApiKey']);
            $list->customFields = $subscriberList->getCustomFields();
            $list->activeSubscribers = $subscriberList->getActiveSubscribers();
            $list->taskUrl = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . '/import-list/' . $list->ListID;
            $activeLists[] = $list;
        }
        else
            $inactiveLists[] = $list;
    }

    return $this->view->render($response, 'console/index.html', [
        'clientName' => $clientDetails->BasicDetails->CompanyName,
        'clientApiKey' => $clientDetails->ApiKey,
        'clientApiId' => $clientDetails->BasicDetails->ClientID,
        'activeLists' => $activeLists,
        'inactiveLists' => $inactiveLists
    ]);

})->setName('console');

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    // $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
