<?php
require_once "joueur.php";
class BD{
  private $connexion;

// Constructeur de la classe
//Initialise la connexion et renvoie une ConnexionException en cas d'echec de la connexion à la base de donnée

  public function __construct(){
   try{
      $chaine="mysql:host=localhost;dbname=E155890W";
      $this->connexion = new PDO($chaine,"E155890W","E155890W");
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
     }
    catch(PDOException $e){
      $exception=new ConnexionException("Problème de connection à la base de donnée");
      throw $exception;
    }
  }


  // méthode qui permet de fermer la connexion
  public function deconnexion(){
     $this->connexion=null;
  }


  //A développer
  // utiliser une requête préparée
  //vérifie qu'un pseudo existe dans la table joueurs
  // post-condition retourne vrai si le pseudo existe sinon faux
  // si un problème est rencontré, une exception de type TableAccesException est levée
  public function estValide($pseudo, $motDePasse){
    try{
    	$statement = $this->connexion->prepare("select id from joueurs where pseudo=?;");
    	$statement->bindParam(1, $pseudoParam);
    	$pseudoParam=$pseudo;
    	$statement->execute();
    	$result=$statement->fetch(PDO::FETCH_ASSOC);

    	if ($result["id"]!=NUll){
    	   if($result["motDePasse"] = crypt($motDePasse)){
           return true;
         }else{
           throw new MotDePasseException("Mot de passe invalide")
         }
    	}
    	else{
    	  throw new PseudonymeException("Pseudo inexistant dans la base");
    	}
    }catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table pseudonyme");
    }
  }
	//Foncion qui enregistre une partie dans la base de données
	public function enregistrerJeu($jeu, $pseudo) {
		$gagnee = 0
		if ($jeu->estGagnee()) {
			$gagnee = 1;
		}
		$statement = $this->connexion->prepare("INSERT INTO parties(pseudo, partieGagnee, nbCoups) VALUES(:pseudo, :gagnee, :nbCoups)");
		$statement->execute(array("pseudo"=>$pseudo,
		"gagnee" => $gagnee,
		"nbCoups" => $jeu->getNbCoups()));

	}
	//Fonction qui retourne les 5 meilleurs scores et les joueurs associés
	//pré conditions : il faut qu'il y ai au moins une partie dans la table partie (si il y en a moins que 5 la fonction affiche que les disponnibles
	//post conditions : retourne les 5 meilleurs scores et les joueurs les ayant joués
	public function getMeilleursScores() {
	try{

	$statement=$this->connexion->query("SELECT pseudo, nombreCoups from parties ORDER BY nombreCoups DESC LIMIT 5 ");
	
	while($ligne=$statement->fetch()){
	$result[]=$ligne['pseudo','nombreCoups'];
	}
	return($result);
	}
	catch(PDOException $e){
	    throw new TableAccesException("problème avec la table partie");
  }
}
	//Fonction qui retourne le nombre partie gagnées par un joueur
	//si le joueur n'a jamais joué retourne une exception
	//pré-condition : le pseudo du joueur doit exister
	//post-condition : retourne son nombre de parties gagnées
	public function getNombrePartieGagnées($pseudo){
		try{	//on cherche le total de parties gagnées par un joueur (représenté par son pseudo) et on le stock dans nbr_parties_g
			$statement = $this->connexion->prepare("SELECT SUM(partieGagnee) AS nbr_parties_g FROM parties WHERE pseudo=?;");
			$statement->bindParam(1, $pseudoParam);
			$pseudoParam=$pseudo;
			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if ($result["nbr_parties_g"]==NUll){//si le joueur n'a pas encore fait de partie on balance une exception
			throw new PasDePartieException("Ce joueur n'a pas encore joué de parties")
			}
			else{//sinon on retourne nbr_parties_g (nbr de partie gagnées
			return $result["nbr_parties_g"];
			}
		}
		catch(PDOException $e){
		    $this->deconnexion();
		    throw new TableAccesException("problème avec la table partie");
		}
		
	}
	
	



?>
