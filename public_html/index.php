<?php
require __DIR__ . '/../vendor/autoload.php';
spl_autoload_register(function ($classname) {
    // $prefix = 'Crm2Esp';
    // $len = strlen($prefix);
    // if (strncmp($prefix, $class, $len) !== 0) {
    //     return;
    // }
    // $relative_class = substr($class, $len);
    $prefix = 'Crm2Esp';
    $len = strlen($prefix);
    if (strncmp($prefix, $classname, $len) !== 0) {
        return;
    }
    $relative_class = substr($classname, $len);
    $file = __DIR__ . '/../classes' . str_replace('\\', '/', $relative_class) . '.php';
    require $file;
    //require (__DIR__ . '/../classes' . $classname . '.php');
});

session_start();

// Merge user-defined settings into default settings
$settings = require __DIR__ . '/../src/settings.php';
$config =  require __DIR__ . '/../storage/config/settings.php';
$settings['settings'] = array_merge($settings['settings'], $config['settings']);

// Instantiate the app
$app = new \Slim\App($settings);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../templates', [
        // 'cache' => __DIR__ . '/../storage/cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

$container['cm'] = function ($container) { $cm = $container['settings']['cm']; return $cm; };
$container['crm'] = function ($container) { $crm = $container['settings']['crm']; return $crm; };
$container['provider'] = function ($container) { $provider = $container['settings']['provider']; return $provider; };

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/console",
    "realm" => "Protected",
    "secure" => false,
    "users" => $container['settings']['auth']
]));

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
