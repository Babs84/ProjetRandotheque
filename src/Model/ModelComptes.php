<?php

namespace Randotheque\Model;
use PDO;
use Randotheque\Model\Model;


class ModelComptes {

	public $email;
	public $prenom;
	public $nom;
	public $password;
  
	//Getter Générique
  	public function get($nom_attribut) {return $this->$nom_attribut;}

	//Setter Générique
	public function set($nom_attribut, $valeur) {$this->$nom_attribut = $valeur;}

	public function __construct($data = NULL) {
		if (!is_null($data)) {
			$this->email = $data['email'];
			$this->prenom = $data['prenom'];
	    	$this->nom = $data['nom'];	    
	    	$this->password = $data['password'];
	    }
	}
	    
    public static function getAllComptes(){
		$t = Model::$pdo->query("SELECT * FROM Comptes");
   	 	$tab_t = $t->fetchAll(PDO::FETCH_CLASS, get_class());
   		 if (empty($tab_t)){
			return false;
    		} 
  		  else {
    		 return $tab_t;
   		 }
  		} 


    public function checkPassword($login,$mot_de_passe_chiffre){
    	$sql = "SELECT email, password FROM Comptes WHERE email='$login' AND password= '$mot_de_passe_chiffre'";
    	$req_prep = Model::$pdo->prepare($sql);

    	$values = array(
			"nom_email" => $this->email,
	        "nom_password" => $this->password,
	    );

	    $req_prep->execute($values);
	    $res = $req_prep->fetchAll();
	    if (!$res) {
		    return false;
	    } else {
	    	return true;
	    }
    }

    public function save(){
	    $sql = "INSERT INTO Comptes(email, password, nom, prenom, admin) VALUES (:nom_email, :nom_password, :nom, :prenom, :admin);";
	    $req_prep = Model::$pdo->prepare($sql);
	    $values = array(
	        "nom_email" => $this->email,
	        "nom_password" => $this->password,
	        "nom" => $this->nom,
	    	"prenom" => $this->prenom, 
			"admin" => $_POST['isAdmin'],
	    );
	    $req_prep->execute($values);  
	    //print_r($req_prep);
	}

	 public static function getCompte($email){
	$t = Model::$pdo->query("SELECT * FROM Comptes WHERE email='$email'");
    $tab_t = $t->fetchAll(PDO::FETCH_CLASS, get_class());
    if (empty($tab_t)){
		return false;
    	} 
    else {
     return $tab_t;
    }
  }

	public static function update($data){

		$sql = "UPDATE Comptes SET email=:email, prenom=:prenom, nom=:nom, password=:passwordnouveau1, admin=:admin WHERE email=:email";
		$req_prep = Model::$pdo->prepare($sql);

		$values = array(
            "email" => $data['email'],
            "nom" => $data['nom'],
            "prenom" => $data['prenom'],
            "passwordnouveau1" => $data['passwordnouveau1'],
            "admin" => $data['admin'],
        );

		$req_prep->execute($values);


	}

	public static function delete($email) {

		$sql = 'DELETE FROM Comptes WHERE email=:email';
		$req_prep = Model::$pdo->prepare($sql);
		
		$values = array(
			"email" => $email,
		);
		$req_prep->execute($values);
	}

}
?>