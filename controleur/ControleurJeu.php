<?php
/**
 *
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
    $this->DAO = new DAO();
  }
  public function nouveauJeu($pseudo){
    $this->jeu = new Jeu($pseudo);
  }
  public function enregistrerJeu(){
    $this->DAO->enregistrerJeu($this->jeu);
  }
  public function jouer($couleur1, $couleur2, $couleur3, $couleur4){
    $this->jeu->jouer(new Combinaison($couleur1, $couleur2, $couleur3, $couleur4));
  }
  public function afficherJeu(){
    $this->vue->mastermind($this->jeu->getPhasesDeJeu());
  }
}

 ?>
