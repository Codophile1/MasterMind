<?php
  /**Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
   */


// Controleur permettant le déroulement d'une partie : son initialisation, son enregistrement, la partie et son résultat
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
    $this->DAO = new DAO();
  }
  //création d'une nouvelle partie
  public function nouveauJeu($pseudo){
    $this->jeu = new Jeu($pseudo);
  }
  //enregistrement de la partie
  public function enregistrerJeu(){
    $this->DAO->enregistrerJeu($this->jeu);
  }
  //déroulement d'un coup (d'un jeu)
  public function jouer($couleur1, $couleur2, $couleur3, $couleur4){
    $this->jeu->jouer(new Combinaison($couleur1, $couleur2, $couleur3, $couleur4));
  }
  //affichage du résultat de la partie
  public function afficherJeu(){
    $this->vue->mastermind($this->jeu->getPhasesDeJeu());
  }
}

 ?>
