<?php
require_once("modele/exceptions/PseudoInexistantException.php");
require_once("modele/exceptions/MotDePasseInvalideException.php");
require_once("modele/exceptions/ConnexionException.php");
require_once("modele/exceptions/TableAccesException.php");
class DAO{
  private $connexion;

  // Constructeur de la classe
  // Initialise la connexion et renvoie une ConnexionException en cas d'echec de la connexion à la base de donnée

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

  // vérifie qu'un pseudo existe dans la table joueurs
  // post-condition retourne vrai si le pseudo existe sinon faux
  // si un problème est rencontré, une exception de type TableAccesException est levée
  public function identifiantsValides($pseudo, $motDePasse){
    try{
      $statement = $this->connexion->prepare("select * from joueurs where pseudo=?;");//requête préparée
      $statement->bindParam(1, $pseudoParam);
      $pseudoParam=$pseudo;
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);
      if (!empty($result["pseudo"])){//si le pseudo n'est pas vide 
        if($result["motDePasse"] == crypt($motDePasse, $result["motDePasse"])){// et si le mot de passe (chiffré) est égale au mot de passe (si le mot de passe est bon)
          return true;//alors les identifiants sont valides 
        }else{
          throw new MotDePasseInvalideException("Mot de passe invalide");//sinon le mot de passe n'est pas valide
        }
      }
      else{
        throw new PseudoInexistantException("Pseudo inexistant dans la base");//sinon le pseudo n'existe pas, le joueur ne c'est pas inscrit
      }
    }catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table pseudonyme");
    }
  }

  //Fonction qui enregistre une partie dans la base de données
  //Pré conditions : une partie vient de finir
  //Post conditions : la partie est enregistrée dans la BD
  public function enregistrerJeu($jeu) {
    $pseudo = $jeu->getJoueur2->getPseudo();
    $gagnee = 0;
    if ($jeu->estGagnee()) {
      $gagnee = 1;
    }
    $statement = $this->connexion->prepare("INSERT INTO parties(pseudo, partieGagnee, nbCoups) VALUES(:pseudo, :gagnee, :nbCoups)");
    $statement->execute(array("pseudo"=>$pseudo,
    "gagnee" => $gagnee,
    "nbCoups" => $jeu->getNbCoups()));

  }
  //Fonction qui retourne les 5 meilleurs scores et les joueurs associés
  //Pré conditions : il faut qu'il y ai au moins une partie dans la table partie (si il y en a moins que 5 la fonction affiche que les disponnibles
  //Post conditions : retourne les 5 meilleurs scores et les joueurs les ayant joués
  public function getMeilleursScores() {
    try{//on recupère les le pseudo de le nombre de coups des 5 meilleurs parties (ou le nombre de coups est le plus bas)
      $statement=$this->connexion->query("SELECT pseudo, nombreCoups from parties ORDER BY nombreCoups DESC LIMIT 5 ");
      $i=0;
      while($partie=$statement->fetch()){
        $result[$i]=array($partie['pseudo'],$partie['nombreCoups']);
      }
      return($result);
    }
    catch(PDOException $e){
      throw new TableAccesException("problème avec la table partie");
    }
  }
  //Fonction qui retourne le nombre partie gagnées par un joueur
  //si le joueur n'a jamais joué retourne une exception
  //Pré-condition : le pseudo du joueur doit exister
  //Post-condition : retourne son nombre de parties gagnées
  public function getNombrePartieGagnées($pseudo){
    try{	//on cherche le total de parties gagnées par un joueur (représenté par son pseudo) et on le stock dans nbr_parties_g
      $statement = $this->connexion->prepare("SELECT SUM(partieGagnee) AS nbr_parties_g FROM parties WHERE pseudo=?;");
      $statement->bindParam(1, $pseudoParam);
      $pseudoParam=$pseudo;
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);

      if ($result["nbr_parties_g"]==NUll){//si le joueur n'a pas encore fait de partie on balance une exception
        throw new PasDePartieException("Ce joueur n'a pas encore joué de parties");
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
}
?>
