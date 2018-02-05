<?php

namespace Randotheque\Model;
use PDO;
use Randotheque\Model\Model;


class ModelRandonnees {

  private $idRandonnee;
  private $NomRando;
  private $LieuDepart;
  private $Localisation;
  private $Departement;
  private $CarteIGN;
  private $Distance;
  private $Denivele;
  private $DifficulteIBP;
  private $Duree;
  private $Boucle;
  private $TypeChemin;
  private $DateRandonnee;
  private $ItinerairePdf;
  private $Trace;
  private $Image;
  private $Commentaire;
  private $DistanceDepuisParking;
  private $GroupeRandonnee;
  private $meteo;

  
  //Getter Générique
  public function get($nom_attribut) {return $this->$nom_attribut;}
  
  //Setter Générique
  public function set($nom_attribut,$valeur) {return $this->$nom_attribut=$valeur;}

  //Constructeur
  public function __construct($data = NULL) {
    if (!is_null($data)) {
		if (isset($data['idRandonnee'])){
		  $this->idRandonnee = $data['idRandonnee'];
		}
      $this->NomRando = $data['NomRando'];
      $this->LieuDepart = $data['LieuDepart'];
      $this->Localisation = $data['Localisation'];
      $this->Departement = $data['Departement'];
      $this->CarteIGN = $data['CarteIGN'];
      $this->Distance = $data['Distance'];
      $this->Denivele = $data['Denivele'];
      $this->DifficulteIBP = $data['DifficulteIBP'];
      $this->Duree = $data['Duree'];
      $this->Boucle = $data['Boucle'];
      $this->TypeChemin = $data['TypeChemin'];
      $this->DateRandonnee = $data['DateRandonnee'];
      $this->ItinerairePdf = $data['ItinerairePdf'];
      $this->Trace = $data['Trace'];
	  $this->Image = $data['Image'];
      $this->Commentaire = $data['Commentaire'];
      $this->DistanceDepuisParking = $data['DistanceDepuisParking'];
      $this->GroupeRandonnee = $data['GroupeRandonnee'];
      $this->meteo = $data['meteo'];
    }
  }

  //Fonction insérant les différents champs du formulaire de création dans la base de données
  public function save(){

    $sql = "INSERT INTO `Randonnees`(`NomRando`,`Localisation`, `LieuDepart`, `Departement`, `CarteIGN`, `Distance`, `Denivele`, `DifficulteIBP`, `Duree`, `Boucle`, `TypeChemin`, `DateRandonnee`, `ItinerairePdf`, `Trace`,`Image`, `Commentaire`, `DistanceDepuisParking`, `GroupeRandonnee` ,`meteo` ) VALUES (:NomRando,:Localisation,:LieuDepart,:Departement,:CarteIGN,:Distance,:Denivele,:DifficulteIBP,:Duree,:Boucle,:TypeChemin,:DateRandonnee,:ItinerairePdf,:Trace,:Image,:Commentaire,:DistanceDepuisParking,:GroupeRandonnee, :meteo)";
    $req_prep = Model::$pdo->prepare($sql);
    
    $values = array(
      "NomRando" => $this->NomRando,
      "Localisation" => $this->Localisation,
      "LieuDepart" => $this->LieuDepart,
      "Departement" => $this->Departement,
      "CarteIGN" => $this->CarteIGN,
      "Distance" => $this->Distance,
      "Denivele" => $this->Denivele,
      "DifficulteIBP" => $this->DifficulteIBP,
      "Duree" => $this->Duree,
      "Boucle" => $this->Boucle,
      "TypeChemin" => $this->TypeChemin,
      "DateRandonnee" => $this->DateRandonnee,
      "ItinerairePdf" => $this->ItinerairePdf,
      "Trace" => $this->Trace,
      "Image" => $this->Image,
      "Commentaire" => $this->Commentaire,
      "DistanceDepuisParking" => $this->DistanceDepuisParking,
      "GroupeRandonnee" => $this->GroupeRandonnee,
      "meteo" => $this->meteo
    );
    
    try{
      $req_prep->execute($values);
    } catch (PDOException $e){
      echo $e->getMessage();
      die();
    }
  }

  //Fonction insréant les différents champs du formulaire de mise a jour dans la base de données
  public static function update($data){ //EN TRAVAUX

    $sql = "UPDATE `Randonnees` SET `idRandonnee`= :idRandonnee ,`NomRando`= :NomRando,`Localisation`= :Localisation,`LieuDepart`= :LieuDepart,`Departement`= :Departement,`CarteIGN`= :CarteIGN,`Distance`= :Distance,`Denivele`= :Denivele,`DifficulteIBP`= :DifficulteIBP,`Duree`= :Duree,`Boucle`= Boucle,`TypeChemin`= :TypeChemin,`DateRandonnee`= :DateRandonnee,`ItinerairePdf`= :ItinerairePdf,`Trace`= :Trace,`Image`= :Image,`Commentaire`=:Commentaire,`DistanceDepuisParking`=:DistanceDepuisParking,`GroupeRandonnee`= :GroupeRandonnee, `meteo`= :meteo WHERE idRandonnee= :idRandonnee";


    $req_prep = Model::$pdo->prepare($sql);

   $values = array(
		  "idRandonnee" => $data['idRandonnee'],
      "NomRando" => $data['NomRando'],
      "Localisation" => $data['Localisation'],
      "LieuDepart" => $data['LieuDepart'],
      "Departement" => $data['Departement'],
      "CarteIGN" => $data['CarteIGN'],
      "Distance" => $data['Distance'],
      "Denivele" => $data['Denivele'],
      "DifficulteIBP" => $data['DifficulteIBP'],
      "Duree" => $data['Duree'],
      "Boucle" => $data['Boucle'],
      "TypeChemin" => $data['TypeChemin'],
      "DateRandonnee" => $data['DateRandonnee'],
      "ItinerairePdf" => $data['ItinerairePDF'],
      "Trace" => $data['Trace'],
      "Image" => $data['Image'],
      "Commentaire" => $data['Commentaire'],
      "DistanceDepuisParking" => $data['DistanceDepuisParking'],
      "GroupeRandonnee" => $data['GroupeRandonnee'],
      "meteo" => $data['meteo']
    );

    $req_prep->execute($values);

  }

  //Fonction permettant de sélectionner toutes les randonnées
  public static function getAll(){
    $t = Model::$pdo->query('SELECT * FROM Randonnees');
    $tab_t = $t->fetchAll(PDO::FETCH_CLASS, get_class());
    return $tab_t;
  }

  //Fonction permettant de sélectionner les randonnées qui correspondent aux conditions saisies
  public static function getBySearch($dataRando) {
    //Création des conditions pour la requête.
    $where ='';
	  $values = array();
    if(!empty($dataRando['Distance'])){                                                //DISTANCEMax
  	  //$where = ' Distance <='. $dataRando['Distance'];
  	  $where = ' Distance <= :distance';
  	  $values["distance"] = $dataRando['Distance'];
    } else {
      //$where = ' Distance <='. '5000';
  	  $where = ' Distance <= :distance';
  	  $values["distance"] = 5000;
    }
    if(!empty($dataRando['Departement'])){                                            
      //$where = $where . ' AND ' . 'Departement =' . $dataRando['Departement'];        //DEPARTEMENT
  	  $where = $where . ' AND ' . 'Departement = :departement';
  	  $values["departement"] = $dataRando['Departement'];
    }
    if(!empty($dataRando['Localisation'])){
      //$where = $where . ' AND ' . 'Localisation =\'' . $dataRando['Localisation'] .'\'';        //Localisation
  	  $where = $where . ' AND ' . 'Localisation LIKE :localisation';
  	  $values["localisation"] = '%' . $dataRando['Localisation'] . '%';
    }
    if(!empty($dataRando['Denivele'])){
      //$where = $where . ' AND ' . 'Denivele <=' . $dataRando['Denivele'];        //DeniveleMax
  	  $where = $where . ' AND ' . 'Denivele <= :denivele ';
  	  $values["denivele"] = $dataRando['Denivele'];
    }
    if(!empty($dataRando['IndiceIBP'])){
      //$where = $where . ' AND ' . 'IndiceIBP =' . $dataRando['IndiceIBP'];        //IndiceIBP
  	  $where = $where . ' AND ' . 'IndiceIBP = :indice ' ;
  	  $values["indice"] = $dataRando['IndiceIBP'] ;
    }
    if(!empty($dataRando['LieuDepart'])){
      //$where = $where . ' AND ' . 'LieuDepart LIKE \'%' . $dataRando['LieuDepart'] . '%\'';        //Lieu de Depart
  	  $where = $where . ' AND ' . 'LieuDepart LIKE :lieuDepart ';
  	  $values["lieuDepart"] = '%' . $dataRando['LieuDepart'] . '%';
    }
    if(!empty($dataRando['meteo'])){
      //$where = $where . ' AND ' . 'meteo=\''. $dataRando['meteo'].'\'';        //Lieu de Depart
  	  $where = $where . ' AND ' . 'meteo= :meteo';
  	  $values["meteo"] = $dataRando['meteo'];
    }
    //Préparation de la requête.
    $sql= 'SELECT * FROM Randonnees WHERE' . $where .';';
  	$req_prep = Model::$pdo->prepare($sql);
  	$req_prep->execute($values);
  	$tab_t = $req_prep->fetchAll(PDO::FETCH_CLASS, get_class());
    return $tab_t;
  }

  //Fonction permettant d'afficher le nombre de randonnées trouvé par la fonction recherche
  public static function countRando($Randonnees) {
    $count= count($Randonnees);
    if($count==0) {
      return "Aucune randonnée n'a été trouvée";
    } else if($count==1) {
      return ''.$count.' randonnée a été trouvée';
    } else {
      return ''.$count.' randonnées ont été trouvées';
    }
  }

  //Fonction permettant de sélectionner toutes les informations d'une seule randonnée
  public static function getById($idRandonnee){
   $t = Model::$pdo->query("SELECT * FROM Randonnees WHERE idRandonnee='$idRandonnee'");
    $tab_t = $t->fetchAll(PDO::FETCH_CLASS, get_class());
    if (empty($tab_t)){
    return false;
      } 
    else {
     return $tab_t;
    }
  }

  //Fonction qui supprime la randonnée de la BDD ainsi que les différents fichiers
  public static function delete($idRandonnee) {
    //récupération des liens des fichiers
    $sql2 = 'SELECT Image,Trace,ItinerairePdf FROM Randonnees WHERE idRandonnee='.$idRandonnee.';';

    $result = Model::$pdo->query($sql2);
    $res = $result->fetch();
    
    //Suppression des diffèrents fichiers
    if($res[0]!='ARBRE.png'){
      unlink(__DIR__.'/../../views/images/'.$res[0]);
    }

    unlink(__DIR__.'/../../views/gpx/'.$res[1]);

    if($res[2]!='Basique.pdf'){
      unlink(__DIR__.'/../../views/pdf/'.$res[2]);
    }

    //Suppression de la randonnée dans la BDD
    $sql = 'DELETE FROM Randonnees WHERE idRandonnee=:num_idRandonnee';
    $req_prep = Model::$pdo->prepare($sql);
    $values = array(
        "num_idRandonnee" => $idRandonnee,
    );
    $req_prep->execute($values);
  }
}

