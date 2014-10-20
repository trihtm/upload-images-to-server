<?php

$router = new \Phalcon\Mvc\Router(false);
$router->removeExtraSlashes(true);

// Set 404 paths
$router->notFound(array(
    "controller" => "guest",
    "action"     => "route404"
));

// Add default route.
$router->add("/", array(
    'controller' => 'guest',
    'action'     => 'default'
));

$router->add("/guest/:action/:params", array(
    'controller' => 'guest',
    'action' => 1,
    'params' => 2
));

return $router;