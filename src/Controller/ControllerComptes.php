<?php

namespace Randotheque\Controller;

use Randotheque\Model\ModelComptes;
use Randotheque\lib\security;
use Randotheque\lib\Session;

class ControllerComptes {

	public static function login($app) {
		if (isset($_SESSION['id'])) {
			return ControllerComptes::detail($app);
		} else {
			return $app['twig']->render('.\Comptes\login.twig');
		}
	}

	public static function readAllComptes($app){
		if (Session::is_admin()){
	  		$tab_c = ModelComptes::getAllComptes();
	  		return $app['twig']->render('.\Comptes\list.twig', array(
	  			'comptes' => $tab_c
	  		));
	  	} else {
	  		$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
	  		return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
	  	}
	 }



	 public static function detail($app) {
	  	$cl = ModelComptes::getCompte($_SESSION['id']);
	  	if (Session::is_admin()){
	  		$v='Administrateur';
			$ab='<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--grey-300" href="./ListeComptes"> Voir les autres comptes</a>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--grey-300" href="./create">Créer un compte</a>';
	  	}
	  	else{
	  		$v='Animateur';
	  		$ab='';
	  	}
	  	return $app['twig']->render('.\Comptes\details.twig' , array(
	  		'compte' => $cl,
	  		'admin' => $v,
	  		'Adminbutton' => $ab
	  	));
	 }

	public static function detailsAdmin($app) {
	  	if (Session::is_admin()){
	  		$cl = ModelComptes::getCompte($_GET['email']);
	  		$x=$cl[0]->get('admin');
	  		if ($x==0){
	  			$v='Animateur';
	  			$ab='';
	  		} else {
	  			$v='Administrateur';
				/*$ab='<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--grey-300" href="./ListeComptes"> Voir les autres comptes</a>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--grey-300" href="./create">Créer un compte</a>';*/
	  		}
	  		return $app['twig']->render('.\Comptes\detailsAdmin.twig' , array(
	  			'compte' => $cl,
	  			'admin' => $v,
	  			//'Adminbutton' => $ab
	  		));
	  	} else {
	  		$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
	  		return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
	  	}  
	  }

	public static function logged($app) {

	  	$data = array(
	  		"email" => $_POST['email'],
	  		"password" => security::chiffrer($_POST['password']),
	  		"prenom" => "",
	  		"nom" => "",
	  	);

	  	$M = new ModelComptes($data);
	  	if (!$M->checkPassword($data['email'],$data['password'])){
		$snackbartitle = "Email ou Mot de passe incorrect"; 
		return $app['twig']->render('.\Comptes\Erreur\login.twig', array('snackbartitle' => $snackbartitle)); 
	}
	else {
		$_SESSION['id'] = $data['email'];	
		$cl = ModelComptes::getCompte($_SESSION['id']);
		$_SESSION['admin'] = $cl[0]->get('admin');
         // $_SESSION['admin'] = $cl->get('admin');  

		if ($cl==null){
			$snackbartitle = "Compte introuvable";
			return $app['twig']->render('.\Comptes\Erreur\login.twig', array('snackbartitle' => $snackbartitle)); 
		} else {
			return $app['twig']->render('.\Comptes\connected.twig', array('prenom' => $cl[0]->get('prenom')));
		} 
	}

}


public static function create($app) {
	if (isset($_SESSION['id'])) {
		if (Session::is_admin()){
			return $app['twig']->render('.\Comptes\create.twig');
		}
		else{
			$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
			return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
		}
	}
	else{
		return $app['twig']->render('.\Comptes\login.twig');
	}
}

public static function created($app){
	if ($_POST['password']==$_POST['password2']){
		$data = array(
			"email" => $_POST['email'],
			"password" => security::chiffrer($_POST['password']),
			"nom" => ucfirst($_POST['nom']),
			"prenom" => ucfirst($_POST['prenom']),
			"date_inscription" => getdate(),
			"last_co" => getdate(),
			"nonce" => security::generateRandomHex(),
		);

		$ModelComptes = new ModelComptes($data);
		$isAdmin = $_POST['isAdmin'];
		$ModelComptes->save();

		return $app['ControllerComptes']->readAllComptes($app);
	} else {
		$snackbartitle = "Erreur les mots de passe ne correspondent pas";
		return $app['twig']->render('.\Comptes\Erreur\create.twig', array('snackbartitle' => $snackbartitle));
	}
}

	public static function logout($app){
		session_destroy();
		return $app['twig']->render('.\Comptes\loggout.twig', array('pagetitle' => 'Deconnexion',));
	}

	/*public static function update($app){
		
	  $c = ModelComptes::getCompte($_SESSION['id']);
	  
	  $dataC = array(
	   "email" => $c[0]->get('email'),
	   "password" => $c[0]->get('password'),
	   "prenom" =>  $c[0]->get('prenom'),
	   "nom" =>  $c[0]->get('nom'),
	 );
	   if (Session::is_admin()){
		   $dataTypeC = array(
				"typeCompte" => 'Administrateur',
				"valtypeCompte" => 1,
				"NouveauTypeCompte" => 'Animateur',
				"valNouveauTypeCompte" => 0,
			);
		}
		else{
			$dataTypeC = array(
				"typeCompte" => 'Animateur',
				"valtypeCompte" => 0,
				"NouveauTypeCompte" => 'Administrateur',
				"valNouveauTypeCompte" => 1,
			);
		}
	return $app['twig']->render('.\Comptes\update.twig', array('dataTypeC' => $dataTypeC, 'dataC' => $dataC,));
	}

public static function updateByAdmin($app){

  if (Session::is_admin()){

  $c = ModelComptes::getCompte($_GET['email']);

  $dataC = array(
   "email" => $c[0]->get('email'),
   "password" =>$c[0]->get('password'),
   "prenom" =>  $c[0]->get('prenom'),
   "nom" =>  $c[0]->get('nom'),
   "admin" => $c[0]->get('admin'),

    );

    if ($dataC['admin']==1){
       $dataTypeC = array(
        "typeCompte" => 'Administrateur',
        "valtypeCompte" => 1,
        "NouveauTypeCompte" => 'Animateur',
        "valNouveauTypeCompte" => 0,
      );
    }
    else{
      $dataTypeC = array(
        "typeCompte" => 'Animateur',
        "valtypeCompte" => 0,
        "NouveauTypeCompte" => 'Administrateur',
        "valNouveauTypeCompte" => 1,
      );
    }
    return $app['twig']->render('.\Comptes\update.twig', array('dataTypeC' => $dataTypeC, 'dataC' => $dataC,));
  }
  else{
      $pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
      return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
  }
}*/

public static function update($app){

	if (Session::is_admin()){

		if(isset($_GET['email'])){
			$c = ModelComptes::getCompte($_GET['email']);
		}
		else{
			$c = ModelComptes::getCompte($_SESSION['id']);
		}
		
		$dataC = array(
			"email" => $c[0]->get('email'),
			"password" =>$c[0]->get('password'),
			"prenom" =>  $c[0]->get('prenom'),
			"nom" =>  $c[0]->get('nom'),
			"admin" => $c[0]->get('admin'),
		);

		if ($dataC['admin']==1){
			$dataTypeC = array(
				"typeCompte" => 'Administrateur',
				"valtypeCompte" => 1,
				"NouveauTypeCompte" => 'Animateur',
				"valNouveauTypeCompte" => 0,
			);
		}
		else{
			$dataTypeC = array(
				"typeCompte" => 'Animateur',
				"valtypeCompte" => 0,
				"NouveauTypeCompte" => 'Administrateur',
				"valNouveauTypeCompte" => 1,
			);
		}
		
		return $app['twig']->render('.\Comptes\updateAdmin.twig', array('dataTypeC' => $dataTypeC, 'dataC' => $dataC,));


	}else{

		$c = ModelComptes::getCompte($_SESSION['id']);

		$dataC = array(
			"email" => $c[0]->get('email'),
			"password" => $c[0]->get('password'),
			"prenom" =>  $c[0]->get('prenom'),
			"nom" =>  $c[0]->get('nom'),
		);

		return $app['twig']->render('.\Comptes\update.twig', array( 'dataC' => $dataC,));

	}

}



	public static function updated($app){

		if (Session::is_admin()){
			$c = ModelComptes::getCompte($_SESSION['id']);
			if (empty($_POST['passwordnouveau']) && empty($_POST['passwordnouveau1'])){
				
				$dataC1 = array(
					'email'=>$_POST["email"],
					'prenom'=>ucfirst($_POST["prenom"]),
					'passwordnouveau1'=> $c[0]->get('password'),
					'nom'=>ucfirst($_POST["nom"]),
					'admin' => $_POST['User'],
				);

				ModelComptes::update($dataC1);
				return ControllerComptes::detail($app);
			} else {
				if (security::chiffrer($_POST['passwordnouveau'])==security::chiffrer($_POST['passwordnouveau1'])){
					$dataC1 = array(
						'email'=>$_POST["email"],
						'prenom'=>ucfirst($_POST["prenom"]),
						'passwordnouveau1'=>security::chiffrer($_POST["passwordnouveau1"]),
						'nom'=>ucfirst($_POST["nom"]),
						'admin' => $_POST['User'],
					);
					
					ModelComptes::update($dataC1);
					return ControllerComptes::detail($app);
				}		
				else{
					$pagetitle = "Erreur les mots de passe ne correspondent pas";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				}

			}

		} else {

			$c = ModelComptes::getCompte($_POST['email']);
			if (empty($_POST['passwordancien']) && empty($_POST['passwordnouveau']) && empty($_POST['passwordnouveau1'])){
				
				$dataC1 = array(
					'email'=>$_POST["email"],
					'prenom'=>ucfirst($_POST["prenom"]),
					'passwordnouveau1'=> $c[0]->get('password'),
					'nom'=>ucfirst($_POST["nom"]),
					'admin' =>  $c[0]->get('admin'),
				);
				
				ModelComptes::update($dataC1);
				return ControllerComptes::detail($app);

			} else {

				if(security::chiffrer($_POST['passwordancien'])==$c[0]->get('password')){

					if (security::chiffrer($_POST['passwordnouveau'])==security::chiffrer($_POST['passwordnouveau1'])){
						$dataC1 = array(
							'email'=>$_POST["email"],
							'prenom'=>ucfirst($_POST["prenom"]),
							'passwordnouveau1'=>security::chiffrer($_POST["passwordnouveau1"]),
							'nom'=>ucfirst($_POST["nom"]),
							'admin' => $c[0]->get('admin'),
						);
						
						ModelComptes::update($dataC1);
						return ControllerComptes::detail($app);
					}		
					else{
						$pagetitle = "Erreur les mots de passe ne correspondent pas";
						return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
					}

				}
				else{
					$pagetitle = "Votre ancien mot de passe ne correpond pas";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				}
			}
		}
	}


	public static function delete($app) {
		if (isset($_SESSION['id'])) {
			if(isset($_GET['email'])){
				if (Session::is_admin()){
					ModelComptes::delete($_GET['email']);
					$tab_c = ModelComptes::getAllComptes();
	  				return $app['twig']->render('.\Comptes\deleted.twig', array('comptes' => $tab_c));
				} else {
					$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				}
			} else {
				ModelComptes::delete($_SESSION['id']);
				session_destroy();
				$tab_c = ModelComptes::getAllComptes();
	  			return $app['twig']->render('.\Comptes\deleted.twig', array('comptes' => $tab_c));
			}

		} else {
			return $app['twig']->render('.\Comptes\login.twig');
		}
	}
}
?>