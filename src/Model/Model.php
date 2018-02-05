<?php

namespace Randotheque\Model;

use PDO;
use Randotheque\config\prod;
use Randotheque\Model\ModelRandonnees;
use Randotheque\Model\ModelComptes;

class Model
 {
	static $pdo;
	public static function Init(){

		$login = prod::getLogin();
		$password = prod::getPassword();
		$hostname = prod::getHostname();
		$database_name = prod::getDatabase();
		try{
		 	self::$pdo = new PDO("mysql:host=$hostname;dbname=$database_name",$login,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		 	self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
	  		if (prod::getDebug()) {
	    		echo $e->getMessage(); // affiche un message d'erreur
	  		} else {
	    		echo 'Une erreur est survenue <a href=""> retour a la page d\'accueil </a>';
	  	}
	  	die();
		}
	}
}
Model::Init();
?>