<?php

header('Content-Type: text/html; charset=utf-8');

include_once('../bootstrap/Functions.php');
include_once('../bootstrap/EnvironmentDetector.php');
include_once('../bootstrap/Environment.php');
include_once('../bootstrap/Foundation.php');

// Environment
$env = new Environment;
$env->detectEnvironment(array(

    'production'    => array('clan.web.154'),
    'local'         => array('*'),

));

// Foundation
$foundation = new Foundation($env);
$foundation->run();

exit;