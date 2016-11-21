<?php
  /**Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
   */

require_once "modele/jeu/Joueur.php";
require_once "modele/jeu/PhaseDeJeu.php";
require_once "modele/jeu/IntelligenceArtificielle.php";
class ExceptionPseudo extends Exception{
  private $chaine;
  public function __construct($chaine){
    $this->chaine=$chaine;
  }

  public function afficher(){
    return $this->chaine;
  }
}
class Jeu{
  private $j1; // Joueur qui choisit la combinaison : IntelligenceArtificielle
  private $j2; // Joueur qui doit trouver la combinaison : Utlisateur
  private $phasesDeJeu; // Tableau qui stocke chacune des phases de jeu : PhaseDeJeu[]
  private $combinaisonTrouvee; // Booléen qui passe à vrai si la combinaison à été trouvée

  public function __construct($pseudo){
    //On initialise le nombre de coups à 0
    $this->nbCoups = 0;
    //On initialise le tableau contenant les phases de jeu
    $this->phasesDeJeu = array();
    //On initialise la variable booleenne indiquant si la combinaison a été trouvée;
    $this->combinaisonTrouvee = false;
    //On initialise le joueur 1, l'intelligence artificielle
    $this->j1 = new IntelligenceArtificielle();
    //On initialise le joueur 2, l'utilisateur
    $this->j2 = new Joueur($pseudo);
  }

  //Récupérer le nombre de coups restants
  //Post-condition : retourne un entier
  public function getNbCoupsRestants(){
    return 10-$this->nbCoups;
  }

  //Récupérer les phases de jeu
  //Post-condition : retourne un tableau de phases de jeu
  public function getPhasesDeJeu(){
    return $this->phasesDeJeu;
  }

  //Fonction qui correspond au jeu d'un joueur qui retourne le nombre de pion noirs (pions bien placés) et le nombre de pions blancs (pions de bonne couleurs mais mal placés)
  //Pré-condition : la partie n'est pas gagnée et le nombre de coups joués <10, une combinaison est passée en paramètre
  //Post-condition : retourne un tableau à deux entrées (la première entrée correspond au nombre de pions noirs et la deuxième au nombre de pions blancs)
  public function jouer($comb){
    //On ajoute 1 coup au nombre de coups
    $this->nbCoups++;
    //On calcule le nombre de pions blancs et de pions noirs;
    $pionsNoirBlanc = $this->j1->getCombinaison()->comparerA($comb);
    //On créer une nouvelle phase de jeu
    $this->phasesDeJeu[count($this->phasesDeJeu)] = new phasesDeJeu($comb, $pionsNoirBlanc);
    //On vérifie si les combinaisons sont identiques
    if($this->j1->getCombinaison()->estIdentiqueA($comb)){
      $this->combinaisonTrouvee = true;
    }
  }

  //Méthode qui renvoie un booléen qui vaut vrai si la combainaison n'a pas été trouvée et le nombre de coups est supérieur ou égal à 10
  public function estPerdu(){
    return ($this->combinaisonTrouvee != true && $this->nbCoups >= 10);
  }
  //Méthode qui renvoie un booléen qui vaut vrai si la combinaison a été trouvée et le nombre de coups est inférieur à 10
  public function estGagne(){
    return ($this->combinaisonTrouvee == true && $this->nbCoups <= 10);
  }
  //Méthode qui renvoie le nombre de coups joués
  public function getNbCoups() {
  	return $this->nbCoups;
  }
  //Méthode qui retourne le premier joueur
  public function getJoueur1(){
    return $this->j1;
  }
  //Méthode qui retourne le second joueur
  public function getJoueur2(){
    return $this->j2;
  }

}

