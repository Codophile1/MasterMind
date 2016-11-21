<?php
  /**Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
   */

class PhaseDeJeu{
  private $noirBlanc;
  private $combinaison;

  public function __construct($comb, $pionsV){
    $this->combinaison = $comb;
    $this->noirBlanc = $pionsV;
  }

  //Méthode qui retourne la combinaison entrée par le joueur lors de la phase de jeu
  public function getCombinaison(){
    return $this->combinaison;
  }
  //Méthode qui retourne le nombre de pions noirs, soit le nombre de pions bien placés par le joueur
  //Post-condition : retourne le nombre de pions noirs
  public function getNbPionsNoirs(){
    return $this->noirBlanc[0];
  }
  //Méthode qui retourne le nombre de pions blancs, soit le nombre de pions mal placés par le joueur
  //Post-condition : retourne le nombre de pions blancs
  public function getNbPionsBlancs(){
    return $this->noirBlanc[1];
  }

}
 ?>
