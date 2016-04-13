<?php
// Routes

$app->get('/console', function($request, $response, $args) {
    return $this->view->render($response, 'console/index.html', [
        //'name' => $args['name']
    ]);
})->setName('console');

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    // $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
