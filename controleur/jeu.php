<?php
require_once "bd.php";
require_once "joueur.php";
require_once "phaseDeJeu.php";
require_once "IntelligenceArtificielle.php";
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
    //On vérifie si le pseudo correspond à un joueur existant, sinon, on lève une exception
    if(this->bd->exists($pseudo)){
      //Le pseudo correspond à un joueur existant, on peut le créer
      $this->j2->new Joueur($pseudo);
    }else{
      //Le pseudo correspond à un joueur inexistant, on lève une exception
      throw new ExceptionPseudo("Joueur inexistant dans la base de données.");
    }
    //On initialise le joueur 1, l'intelligence artificielle
    $this->j1->new IntelligenceArtificielle();
  }

  //Récupérer le nombre de coups restants
  //Post-condition : retourne un entier
  public function getNbCoupsRestants(){
    return 10-$this->nbCoups;
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
  public function estPerdue(){
    return ($this->combinaisonTrouvee != true && $this->nbCoups >= 10);
  }
  //Méthode qui renvoie un booléen qui vaut vrai si la combinaison a été trouvée et le nombre de coups est inférieur à 10
  public function estGagnee(){
    return ($this->combinaisonTrouvee == true && $this->nbCoups <= 10);
  }
  //Méthode qui renvoie le nombre de coups joués
  public function getNbCoups() {
  	return $this->nbCoups;
  }

}
