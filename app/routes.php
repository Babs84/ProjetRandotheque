<?php
//Fichier routes qui fait le lien entre l'URL et une action de l'application Web.

/*----[ Routes générales ]----*/
//Routes utilisées pour la page d'accueil
$app->get('/', function() use ($app) {
	return $app->redirect('accueil'); 
});

$app->get('/accueil', function() use ($app) {
	return $app['twig']->render('accueil.twig');
})->bind('home');

$app->patch('/css/styles.css')->bind('css');
$app->patch('/images/favicon.png')->bind('favicon');
$app->patch('/images/LogoSaintClement.jpg')->bind('logo');

/*----[ Routes concernant les randonnees ]----*/
//Routes utilisées pour la création de randonnée
$app->get('/create', function() use ($app) {
	return $app['ControllerRandonnees']->create($app);
})->bind('create');

$app->post('/created', function() use ($app) {
	return $app['ControllerRandonnees']->created($app);
})->bind('created');

//Routes utilisées pour la recherche de randonnée
$app->get('/search', function() use ($app) {
	return $app['ControllerRandonnees']->search($app);
})->bind('search');

$app->post('/searched', function() use ($app) {
	return $app['ControllerRandonnees']->searched($app);
})->bind('searched');

//Route utilisée pour l'affichage détaillé d'une randonnée
$app->get('/detail/{idRandonnee}', function ($idRandonnee) use ($app) {
	return $app['ControllerRandonnees']->read($app, $idRandonnee);
})->bind('detail');

//Routes utilisées pour la mise a jour de randonnée
$app->get('/update/{idRandonnee}', function ($idRandonnee) use ($app) {
	return $app['ControllerRandonnees']->update($app, $idRandonnee);
})->bind('update');

$app->post('/updated', function() use ($app) {
	return $app['ControllerRandonnees']->updated($app);
})->bind('updated');

//Route utilisée pour la supression de randonnée
$app->get('/delete/{idRandonnee}', function ($idRandonnee) use ($app) {
	return $app['ControllerRandonnees']->delete($app, $idRandonnee);
})->bind('delete');

//Route utilisée pour l'affichage de la trace GPX
$app->get('/trace/{idRandonnee}', function ($idRandonnee) use ($app) {
	return $app['ControllerRandonnees']->afficheTrace($app, $idRandonnee);
})->bind('trace');



/*----[ Routes concernant les comptes ]----*/
//Routes utilisées pour la création de comptes
$app->get('/Comptes/create', function () use ($app) {
    return $app['ControllerComptes']->create($app);
})->bind('createCompte');

$app->post('/Comptes/created', function () use ($app) {
    return $app['ControllerComptes']->created($app);
})->bind('createdCompte');

//Routes utilisées pour la mise a jour d'un compte
$app->get('/Comptes/update', function () use ($app) {
    return $app['ControllerComptes']->update($app);
})->bind('updateCompte');

$app->get('/Comptes/updateAdmin', function () use ($app) {
    return $app['ControllerComptes']->update($app);
})->bind('updateAdmin');

$app->post('/Comptes/updated', function () use ($app) {
    return $app['ControllerComptes']->updated($app);
})->bind('updatedCompte');

//Routes utilisées pour la connexion
$app->get('/Comptes/login', function () use ($app) {
    return $app['ControllerComptes']->login($app);
})->bind('login');

$app->post('/Comptes/connected', function () use ($app) {
    return $app['ControllerComptes']->logged($app);
})->bind('connected');

//Route utilisé pour la création déconnexion
$app->get('/Comptes/logout', function () use ($app) {
    return $app['ControllerComptes']->logout($app);
})->bind('logout');

//Routes utilisées pour l'affichage de comptes
$app->get('/Comptes/details', function () use ($app) {
    return $app['ControllerComptes']->detail($app);
})->bind('details');

$app->get('/Comptes/detailsAdmin', function () use ($app) {
    return $app['ControllerComptes']->detailsAdmin($app);
})->bind('detailsAdmin');

$app->get('/Comptes/ListeComptes', function () use ($app) {
    return $app['ControllerComptes']->readAllComptes($app);
})->bind('ListeComptes');

//Route utilisé pour la suppression d'un compte
$app->get('/Comptes/supprimer', function () use ($app) {
    return $app['ControllerComptes']->delete($app);
})->bind('supprimer');

/*$app->get('/{controller}', function ($controller) use ($app){
	$var = "add";
	$var2 = "search";
	if (!(strcmp($var, $controller) == 0)) {
		$app->abort(404,"Ce controller n'existe pas.");
	}
	
});*/


