<?php

//Permet la gestion des erreurs
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

ErrorHandler::register();
ExceptionHandler::register();

// Définit le repértoire des views 
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));

//Définit $app['ControllerRandonnees'] comme un objet pour pouvoir l'appeler des fonctions du Controlleur ControllerRandonnees
$app['ControllerRandonnees'] = function (){
	return new Randotheque\Controller\ControllerRandonnees();
};

//Idem met fais appelle au Controlleur ControllerComptes
$app['ControllerComptes'] = function (){
	return new Randotheque\Controller\ControllerComptes();
};