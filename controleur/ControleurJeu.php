<?php
/** Yanis OUAKRIM
   * Simow WELLENREITER
   * Groupe 2
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
  */
require_once("vue/Vue.php");
require_once("modele/jeu/Jeu.php");
require_once("modele/DAO/DAO.php");
require_once("modele/jeu/Combinaison.php");
class ControleurJeu
{
  private $jeu;
  private $vue;
  private $DAO;

  function __construct()
  {
    $this->vue = new Vue();
    if(isset($_SESSION["jeu"])){
      $this->jeu = $_SESSION["jeu"];
    }
    $this->DAO = new DAO();
  }
  // Méthode qui permet la création d'un nouveau jeu
  // Précondition : le pseudo du deuxième joueur (l'utilisateur) est passé en oaramètre
  // Post-condition : Un nouveau jeu est créé, le jeu est sauvegardé dans une variable de session
  public function nouveauJeu($pseudo){
    $this->jeu = new Jeu($pseudo);
    $this->enregisterModifs();
  }
  // Méthode qui permet de sauvegarder le jeu dans la base de données
  // Pré-condition : Le controleur a un jeu initialisé
  // Post-condition : le jeu est sauvegardé dans la base
  public function enregistrerJeu(){
    $this->DAO->enregistrerJeu($this->jeu);
  }
  // Méthode permettant de proposer une combinaison de couleurs
  // Pré-condition : un jeu a été initialisé
  // Post-condition : la phase de jeu est sauvegardée dans la variable de session
  public function jouer($couleur1, $couleur2, $couleur3, $couleur4){
    $this->jeu->jouer(new Combinaison($couleur1, $couleur2, $couleur3, $couleur4));
    $this->enregisterModifs();
  }
  // Méthode permettant d'afficher la vue des statistiques
  // Post-condition : les statistiques sont affichées
  public function afficherStatistiques(){
    $ratio=($this->DAO->getNombrePartieJouées)/($this->DAO->getNombrePartieJouées);
    $this->vue->statistiques($this->DAO->getMeilleursScores(), $this->DAO->getMeilleursJoueurs(),$ratio);
  }
  // Méthode permettant d'afficher la vue du jeu
  // Post-condition : le jeu est affiché
  public function afficherJeu(){
    //On vérifie si le jeu est gagné
    if($this->jeu->estGagne()){
      //Si le jeu est gagné, la partie est terminée, on l'enregistre dans la base de donnée
      $this->enregistrerJeu();
      // On affiche la vue du jeu en indiquant que la partie est gagnée
      $this->vue->mastermind($this->jeu->getPhasesDeJeu(), $this->jeu->getNbCoups(), true);
    }else if($this->jeu->estPerdu()){
      //Si le jeu est perdu, la partie est terminée, on l'enregistre dans la base de donnée
      $this->enregistrerJeu();
      // On affiche la vue du jeu en indiquant que la partie est perdue
      $this->vue->mastermind($this->jeu->getPhasesDeJeu(), $this->jeu->getNbCoups(), false, true, $this->jeu->getJoueur1()->getCombinaison()->tableauCouleur());
    }else{
      // Sinon, si le jeu n'est pas terminé
      // On affiche le jeu avec les phases de jeu
      $this->vue->mastermind($this->jeu->getPhasesDeJeu(), $this->jeu->getNbCoups());
    }
  }
  // Méthode qui permet de sauvegarder un jeu dans une variable de session de façon à pouvoir y acceder après rechagergement de la page
  // Pré-condition : Le controleur a un jeu initialisé
  // Post-condition : le jeu est sauvegardé dans une varaible de session
  private function enregisterModifs(){
    $_SESSION["jeu"]=$this->jeu;
  }
}

 ?>
