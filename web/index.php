<?php
//Nous incluons les diffèrents fichiers PHP nécessaire pour l'application Web
require_once __DIR__.'/../vendor/autoload.php';


$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';

require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';

require __DIR__.'./../src/Controller/ControllerRandonnees.php';
require __DIR__.'/../src/Model/ModelRandonnees.php';

require __DIR__.'/../src/Controller/ControllerComptes.php';
require __DIR__.'/../src/Model/ModelComptes.php';

require __DIR__.'/../lib/security.php';
require __DIR__.'/../lib/Session.php';

require __DIR__.'./../src/Model/Model.php';
session_start();



$app->run();