<?php
 /** Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
  */
  require_once("Joueur.php");
  require_once("Combinaison.php");
  //Classe de joueur qui génère une combinaison aléatoirement
  class IntelligenceArtificielle extends Joueur{
    private $combinaison;

    //On génère la combinaison aléatoire dans le constructeur
    public function __construct(){
      //On assigne le un pseudo à l'Intelligence Artificielle
      parent::__construct("Intelligence Artificielle");
      $this->combinaison = Combinaison::aleatoire();
    }
    //Méthode qui retourne la combinaison générée
    public function getCombinaison(){
      return $this->combinaison;
    }
  }
 ?>
