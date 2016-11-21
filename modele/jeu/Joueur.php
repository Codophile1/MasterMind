<?php
  /**Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
   */

class Joueur{
  private $pseudo;
  //associe un pseudo à un joueur
  public function __construct($pseud){
    $this->pseudo=$pseud;
  }
  //retourne le pseudo du joueur
  public function getPseudo(){
    return $this->pseudo;
  }
}
 ?>
