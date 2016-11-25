<?php
/** Yanis OUAKRIM
* Simow WELLENREITER
* Group 2
* Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
*/
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


  // Méthode qui permet de fermer la connexion
  // Post-condition : la connexion est fermée
  public function deconnexion(){
    $this->connexion=null;
  }

  // Méthode qui vérifie qu'un pseudo existe dans la table joueurs et son son mot de passe est correct
  // Post-condition retourne vrai si les identifiants sonnt valides
  // Si un problème est rencontré, une exception de type TableAccesException est levée
  public function identifiantsValides($pseudo, $motDePasse){
    try{
      $statement = $this->connexion->prepare("select * from joueurs where pseudo=?;");
      $pseudoParam=$pseudo;
      $statement->bindParam(1, $pseudoParam);
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);
      if (!empty($result["pseudo"])){
        // Si le résultat retourné n'est pas vide, on verifie si le mot de passe entré correspond au mot de passe de l'utilisateur
        if($result["motDePasse"] == crypt($motDePasse, $result["motDePasse"])){
          // Si les mots de passe correspondent, on retourne vrai
          return true;
        }else{
          // Sinon, on lance une MotDePasseInvalideException
          throw new MotDePasseInvalideException("Mot de passe invalide");
        }
      }
      else{
        // Si le résultat retourné est vide, on lance une exception de type PseudoInexistantException
        throw new PseudoInexistantException("Pseudo inexistant dans la base");
      }
    }catch(PDOException $e){
      // Si une PDOException est attrapée, on lance une TableAccesException
      $this->deconnexion();
      throw new TableAccesException("Problème avec la table pseudonyme");
    }
  }

  // Méthode enregistre une partie dans la base de données
  // Pré-condition : un jeu est passé en paramètre
  // Post-condition : le jeu est enregistré dans la table "parties" de la base de donnée
  public function enregistrerJeu($jeu) {
    $pseudo = $jeu->getJoueur2()->getPseudo();
    $gagnee = 0;
    if ($jeu->estGagne()) {
      $gagnee = 1;
    }
    $statement = $this->connexion->prepare("INSERT INTO parties(pseudo, partieGagnee, nombreCoups) VALUES(:pseudo, :gagnee, :nbCoups)");
    $statement->execute(array("pseudo"=>$pseudo,
    "gagnee" => $gagnee,
    "nbCoups" => $jeu->getNbCoups()));

  }
  // Méthode qui retourne les 5 meilleurs scores et les joueurs associés
  // Pré-condition : il faut qu'il y ai au moins une partie dans la table partie (si il y en a moins que 5 la fonction affiche que les disponnibles
  // Post-condition : retourne les 5 meilleurs scores et les joueurs les ayant joués
  public function getMeilleursScores() {
    try{
      $statement=$this->connexion->query("SELECT pseudo, nombreCoups from parties ORDER BY nombreCoups LIMIT 5 ");
      $i=0;
      while($partie=$statement->fetch()){
        $result[$i]=array($partie['pseudo'],$partie['nombreCoups']);
        $i++;
      }
      return($result);
    }
    catch(PDOException $e){
      throw new TableAccesException("problème avec la table partie");
    }
  }
  // Méthode qui retourne le nombre partie gagnées par un joueur
  // Si le joueur n'a jamais joué retourne une exception
  // Pré-condition : le pseudo du joueur doit exister
  // Post-condition : retourne son nombre de parties gagnées
  public function getNombrePartieGagnées($pseudo){
    try{
      // On cherche le total de parties gagnées par un joueur (représenté par son pseudo) et on le stock dans nbr_parties_g
      $statement = $this->connexion->prepare("SELECT SUM(partieGagnee) AS nbr_parties_g FROM parties WHERE pseudo=?;");
      $statement->bindParam(1, $pseudoParam);
      $pseudoParam=$pseudo;
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);

      if ($result["nbr_parties_g"]==NUll){
        // Si le joueur n'a pas encore fait de partie on balance une exception
        throw new PasDePartieException("Ce joueur n'a pas encore joué de parties");
      }
      else{
        // Sinon on retourne nbr_parties_g (nbr de partie gagnées)
        return $result["nbr_parties_g"];
      }
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table partie");
    }

  }
  
  public function getNombrePartieJouées($pseudo){
    try{
      // On cherche le total de parties gagnées par un joueur (représenté par son pseudo) et on le stock dans nbr_parties_g
      $statement = $this->connexion->prepare("SELECT COUNT(pseudo) AS nbr_parties_j FROM parties WHERE pseudo=?;");
      $statement->bindParam(1, $pseudoParam);
      $pseudoParam=$pseudo;
      $statement->execute();
      $result=$statement->fetch(PDO::FETCH_ASSOC);

      if ($result["nbr_parties_g"]==NUll){
        // Si le joueur n'a pas encore fait de partie on balance une exception
        throw new PasDePartieException("Ce joueur n'a pas encore joué de parties");
      }
      else{
        // Sinon on retourne nbr_parties_g (nbr de partie gagnées)
        return $result["nbr_parties_g"];
      }
    }
    catch(PDOException $e){
      $this->deconnexion();
      throw new TableAccesException("problème avec la table partie");
    }

  }
  // Méthode qui renvoie les 5 joueurs ayant remporté le plus grand nombre de parties
  // Pré-condition : Il doit y a v avoir au moins un joueur dans la table parties
  // Post-condition : renvoie un tableau de joueurs avec le nombre de parties qu'ils ont gagnées
  public function getMeilleursJoueurs(){
    try{
      $statement=$this->connexion->query("SELECT pseudo , sum(partieGagnee) AS nbr_partie_win FROM parties GROUP BY pseudo ORDER BY nbr_partie_win DESC LIMIT 5  ");
      $i=0;
      while($partie=$statement->fetch()){
        $result[$i]=array($partie['nbr_partie_win']);
        $i++;
      }
      return($result);
    }
    catch(PDOException $e){
      throw new TableAccesException("problème avec la table partie");
    }
  }
}
?>
