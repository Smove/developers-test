<?php

use Jan\DevelopersTest\Application;

include_once __DIR__ . '/autoload.php';

$application = new Application();
exit($application->run());
