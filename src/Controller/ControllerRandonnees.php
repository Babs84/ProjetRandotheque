<?php

namespace Randotheque\Controller;

use Randotheque\Model\Model;
use Randotheque\Model\ModelRandonnees;
use Randotheque\lib\Session;

class ControllerRandonnees {

	//Fonction qui redirige vers la view create.twig
	public static function create($app) {
		//S'il n'est pas connecté, on redirige vers la view login.twig
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else {
			return $app['twig']->render('.\randonnees\create.twig');
		}
	}

	//Fonction qui va récupérer les informations saisies dans le formulaire de création puis redirige vers le view created.twig
	public static function created($app) {
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else {
			//Traitement fichier PDF
			$extensions = array('.pdf','.PDF');
			$extension = strrchr($_FILES['ItinerairePdf']['name'], '.');

			if ($_FILES['ItinerairePdf']['size']>0){

				if(!in_array($extension, $extensions)){
					$pagetitle = "ERREUR BABS";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				}
				if (!empty($_FILES['ItinerairePdf']) && is_uploaded_file($_FILES['ItinerairePdf']['tmp_name'])) {
					$name = $_POST['NomRando'].$_POST['DateRandonnee'].$_POST['GroupeRandonnee'].$extension;
					$fp= __DIR__.'/../../views/pdf/'.$name;
					if (!move_uploaded_file($_FILES['ItinerairePdf']['tmp_name'], $fp )) {
						$pagetitle = "La copie à échoué";
						return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
					}
				}
			} else {
				$name='Basique.pdf';
			}

			//Traitement fichier GPX
			$extensions1 = array('.GPX','.TRK','.gpx','.trk');
			$extension1 = strrchr($_FILES['TraceGpx']['name'], '.'); 

			if(!in_array($extension1, $extensions1)){
				$pagetitle = "Erreur fichier GPX/TRK";
				return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));

			} else {
				if (!empty($_FILES['TraceGpx']) && is_uploaded_file($_FILES['TraceGpx']['tmp_name'])) {
					$name1 = $_POST['NomRando'].$_POST['DateRandonnee'].$_POST['GroupeRandonnee'].$extension1;
					$fp= __DIR__.'/../../views/gpx/'.$name1;
					if (!move_uploaded_file($_FILES['TraceGpx']['tmp_name'], $fp )) {
						$pagetitle = "La copie à échoué";
						return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
					}
				}
			}
			//Traitement fichier Images
			$extensions2 = array('.png','.jpeg','.jpg');
			$extension2 = strrchr($_FILES['Image']['name'], '.'); 
			if ($_FILES['Image']['size']>0){
				if(!in_array($extension2, $extensions2)){
					$pagetitle = "Erreur fichier PNG/JPEG/JPG";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				} else {	            
					if (!empty($_FILES['Image']) && is_uploaded_file($_FILES['Image']['tmp_name'])) {
						$name2 = $_POST['NomRando'].$_POST['DateRandonnee'].$_POST['GroupeRandonnee'].$extension2;
						$fp= __DIR__.'/../../views/images/'.$name2;
						if (!move_uploaded_file($_FILES['Image']['tmp_name'], $fp )) {
							$pagetitle = "La copie à échoué";
							return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
						}
					}


				}
			}
			else {
				$name2='ARBRE.png';
			}

			//Récupération des informations du formulaire
			$dataRando = array(
				"NomRando" => $_POST["NomRando"],
				"Localisation" => $_POST["Localisation"],
				"LieuDepart" => $_POST["LieuDepart"],
				"Departement" => $_POST["Departement"],
				"CarteIGN" => $_POST["CarteIGN"],
				"Distance" => $_POST["Distance"],
				"Denivele" => $_POST["Denivele"],
				"DifficulteIBP" => $_POST["DifficulteIBP"],
				"Duree" => $_POST["Duree"],
				"Boucle" => $_POST["Boucle"],
				"TypeChemin" => $_POST["TypeChemin"],
				"DateRandonnee" => $_POST["DateRandonnee"],
				"ItinerairePdf" => $name,
				"Trace" => $name1,
				"Image" => $name2,
				"Commentaire" => $_POST["Commentaire"],
				"DistanceDepuisParking" => $_POST["DistanceDepuisParking"],
				"GroupeRandonnee" => ucfirst($_POST["GroupeRandonnee"]),
				"meteo" => $_POST["meteo"]
			);
			$randonnees = new ModelRandonnees($dataRando);
			$randonnees->save();

			return $app['twig']->render('./randonnees/created.twig');
		}
	}

	//Fonction qui redirige vers la view update.twig
	public static function update($app, $idRandonnee) {
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		//Vérification si l'utilisateur est administrateur
		} else if (!Session::is_admin()) {
			$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
			return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
		} else {
			$Randonnee = ModelRandonnees::getById($idRandonnee);
			return $app['twig']->render('./randonnees/update.twig', array('Randonnee'=>$Randonnee));
		}
	}

	//Fonction qui va récupérer les informations saisies dans le formulaire de mise a jour puis redirige vers le view updated.twig
	public static function updated($app){
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else if (!Session::is_admin()) {
			$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
			return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
		} else {
			$rando=ModelRandonnees::getById($_POST['idRandonnee']);
	        
	        //Traitement fichier PDF
			$extensions = array('.pdf','.PDF');
			$extension = strrchr($_FILES['ItinerairePdf']['name'], '.');

			if ($_FILES['ItinerairePdf']['size']>0){  //Condition si un fichier est déposé

				if(!in_array($extension, $extensions)){
					$pagetitle = "Erreur fichier PDF";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				}
				else{
					if (!empty($_FILES['ItinerairePdf']) && is_uploaded_file($_FILES['ItinerairePdf']['tmp_name'])) {
						$name = $_POST['NomRando'].$_POST['DateRandonnee'].$_POST['GroupeRandonnee'].$extension;
						$fp= __DIR__.'/../../views/pdf/'.$name;
						if (!move_uploaded_file($_FILES['ItinerairePdf']['tmp_name'], $fp )) {
							echo "La copie a échoué";
						}
					}
				}
			} else {
				$name= $rando[0]->get('ItinerairePdf');
			}

	        //Traitement fichier GPX
			$extensions1 = array('.GPX','.TRK','.gpx','.trk');
			$extension1 = strrchr($_FILES['TraceGpx']['name'], '.'); 

			if ($_FILES['TraceGpx']['size']>0){  //Condition si un fichier est déposé

				if(!in_array($extension1, $extensions1)){
					$pagetitle = "Erreur fichier GPX/TRK";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));

				}
				else{
					if (!empty($_FILES['TraceGpx']) && is_uploaded_file($_FILES['TraceGpx']['tmp_name'])) {
						$name1 = $_POST['NomRando'].$_POST['DateRandonnee'].$_POST['GroupeRandonnee'].$extension1;
						$fp= __DIR__.'/../../views/gpx/'.$name1;
						if (!move_uploaded_file($_FILES['TraceGpx']['tmp_name'], $fp )) {
							echo "La copie a échoué";
						}
					}
				}
			} else {
				$name1=$rando[0]->get('Trace');
			}

	        //Traitement fichier Images
			$extensions2 = array('.png','.jpeg','.jpg');
			$extension2 = strrchr($_FILES['Image']['name'], '.');
			if ($_FILES['Image']['size']>0){
				if(!in_array($extension2, $extensions2)){
					$pagetitle = "Erreur fichier PNG/JPEG/JPG";
					return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
				}

				else{
					if (!empty($_FILES['Image']) && is_uploaded_file($_FILES['Image']['tmp_name'])) {
						$name2 = $_POST['NomRando'].$_POST['DateRandonnee'].$_POST['GroupeRandonnee'].$extension2;
						$fp= __DIR__.'/../../views/images/'.$name2;
						if (!move_uploaded_file($_FILES['Image']['tmp_name'], $fp )) {
							echo "La copie a échoué";
						}
					}
				}
			} else {
				$name2=$rando[0]->get('Image');

			}

			//Récupération des informations du formulaire
			$dataRando = array(
				"idRandonnee" => $_POST["idRandonnee"],
				"NomRando" => $_POST["NomRando"],
				"Localisation" => $_POST["Localisation"],
				"LieuDepart" => $_POST["LieuDepart"],
				"Departement" => $_POST["Departement"],
				"CarteIGN" => $_POST["CarteIGN"],
				"Distance" => $_POST["Distance"],
				"Denivele" => $_POST["Denivele"],
				"DifficulteIBP" => $_POST["DifficulteIBP"],
				"Duree" => $_POST["Duree"],
				"Boucle" => $_POST["Boucle"],
				"TypeChemin" => $_POST["TypeChemin"],
				"DateRandonnee" => $_POST["DateRandonnee"],
				"ItinerairePDF" => $name,
				"Trace" => $name1,
				"Image" => $name2,
				"Commentaire" => $_POST["Commentaire"],
				"DistanceDepuisParking" => $_POST["DistanceDepuisParking"],
				"GroupeRandonnee" => ucfirst($_POST["GroupeRandonnee"]),
				"meteo" => $_POST["meteo"]
			);

			ModelRandonnees::update($dataRando);
			return $app['twig']->render('./randonnees/updated.twig', array('Randonnee'=>$rando));
		}
	}

	//Fonction qui redirige vers la view search.twig
	public function search($app){
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else {
			return $app['twig']->render('.\Randonnees\search.twig');
		}
	}

	//Fonction qui redirige vers la view searched.twig avec les différentes randonnees
	public function searched($app){
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else {
			//Récupération des informations saisies dans le formulaire de recherche
			$dataRando = array(
				"Departement"=> $_POST["Departement"],
				"Localisation"=> $_POST["Localisation"],
				"Denivele"=>$_POST["Denivele"], 
				"Distance"=>$_POST["Distance"], 
				"DifficulteIBP"=>$_POST["DifficulteIBP"], 
				"LieuDepart"=>$_POST["LieuDepart"],
				"meteo"=>$_POST["meteo"]
			);
			//Si aucune saisies, on sort toutes les randonnees
			if(empty($_POST["Departement"]) and empty($_POST["Denivele"]) and empty($_POST["Localisation"]) and empty($_POST["Distance"]) and empty($_POST["DifficulteIBP"]) and empty($_POST["LieuDepart"])and empty($_POST["meteo"])){
				$Randonnees = ModelRandonnees::getAll();
				return $app['twig']->render('./randonnees/list.twig', array(
					'Randonnees' => $Randonnees,
					'count' => ModelRandonnees::countRando($Randonnees)
				));
			//Sinon on recherche les randonnees a l'aide des critères
			} else {
				$Randonnees = ModelRandonnees::getBySearch($dataRando);
				$nbRandonnees = ModelRandonnees::countRando($Randonnees);
				return $app['twig']->render('./randonnees/list.twig', array(
					'Randonnees' => $Randonnees,
					'count' => $nbRandonnees
				));
			}
		}		
	}

	//Fonction qui redirige vers la view read.twig
	public static function read($app, $idRandonnee){
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else {
			$Randonnee = ModelRandonnees::getById($idRandonnee);
			return $app['twig']->render('./randonnees/detail.twig', array('Randonnee'=>$Randonnee));
		}
	}

	//Fonction qui redirige vers la view deleted.twig et supprime la randonnée
	public static function delete($app, $idRandonnee){
		if (!isset($_SESSION['id'])) {
			return $app['twig']->render('.\Comptes\login.twig');
		} else if (!Session::is_admin()) {
			$pagetitle = "Vous n'avez pas les droits d'accès nécessaires";
			return $app['twig']->render('erreur.twig', array('pagetitle' => $pagetitle));
		} else {
			ModelRandonnees::delete($idRandonnee);
			$Randonnees = ModelRandonnees::getAll();
			return $app['twig']->render('./randonnees/deleted.twig', array('Randonnee'=>$Randonnees));
		}
	}

	//Fonction qui redirige vers la view afficher.twig
	public static function afficheTrace($app, $idRandonnee){
		$Randonnee = ModelRandonnees::getById($idRandonnee);
		return $app['twig']->render('./randonnees/afficher.twig',array('Randonnee'=>$Randonnee));
	}
}