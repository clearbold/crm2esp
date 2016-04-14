<?php
// Routes

$app->get('/import-list/{listId}', function ($request, $response, $args) {

    $listId = $request->getAttribute('listId');
    // Here's where we import the list...
    // Step 1: Write a class for our generic CRM system
    $crm = new GenericCrm();
    // Step 2: Figure out how to fetch list data from generic CRM as a config'd provider
    // Step 3: Use our CM Subscriber class to import that list
    $subscriberImport = new Subscriber($listId, $this->cm['clientApiKey']);
    // This returns false if NO subscribers imported OR if invalid.
    // If valid AND new subscriber, returns details.
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
