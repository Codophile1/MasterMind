<?php
//La classe combinaison permet de ne pas avoir à gérer les combinaison directement avec des tableau et ainsi de toujours avoir quatres valeurs;
class Combinaison{
  private $combinaison;

  //Constructeur qui prend en paramètre quatres valeurs.
  public function __construct($n1, $n2, $n3, $n4){
    $this->combinaison = array();
    $this->combinaison[0] = $n1;
    $this->combinaison[1] = $n2;
    $this->combinaison[2] = $n3;
    $this->combinaison[3] = $n4;
  }

  //Fonction statique qui retourne une combiaison aléatoire
  //post-condition : combinaison aléatoire comprenant des valeurs comprises entre 0 et 8
  public static function aleatoire(){
    $combi = array();
    for ($i=0; $i < 4; $i++) {
      //Génération d'une pastille de couleur pour chacune des quatres colonnes
      $combi[$i]=rand(1,8);
      /*
      Association de nombres à des couleurs :
      1 -> Violet
      2 -> Fuschia
      3 -> Bleu
      4 -> Vert
      5 -> Jaune
      6 -> Orange
      7 -> Rouge
      8 -> Blanc
      */
    }
    return new Combinaison($combi[0], $combi[1], $combi[2], $combi[3]);
  }

  //Fonction qui retourne la combinaison sous forme de tableau
  //Post-condition retourne un Array contenant les couleurs de chaque pastille sous forme numérique;
  public function toArray(){
    return $this->combinaison;
  }

  //Fonction qui retourne la combinaison sous forme de tableau de couleurs
  //Post-condition retourne un Array contenant les couleurs de chaque pastille;
  public function tableauCouleur(){
    $couleurs = array();
    for ($i=0; $i < 4; $i++) {
      switch ($this->combinaison[$i]) {
        case 1:
        //Violet
        $couleurs[$i] = "violet";
        break;
        case 2:
        //Fuschia
        $couleurs[$i] = "fuschia";
        break;
        case 3:
        //Bleu
        $couleurs[$i] = "bleu";
        break;
        case 4:
        //Vert
        $couleurs[$i] = "vert";
        break;
        case 5:
        //Jaune
        $couleurs[$i] = "jaune";
        break;
        case 6:
        //Orange
        $couleurs[$i] = "orange";
        break;
        case 7:
        //Rouge
        $couleurs[$i] = "rouge";
        break;
        case 8:
        //Blanc
        $couleurs[$i] = "blanc";
        break;

        default:
        //Ne sera jamais executé
        $couleurs[$i] = "inconnue";
        break;
      }
    }
    return $couleurs;
  }

  //Méthode qui permet de comparer une combinaison à la combinaison actuelle
  //Pré-condition : une combinaison doit être passée en paramètre
  //Post-condition : retourne un tableau contenant le nombre de pions noirs et de pions blancs
  public function comparerA($combi){
    $combProposee = $combi->toArray();
    $combSecrete = $this->combinaison;
    $pionsNonTrouves = $combSecrete;
    $pionsPropPasUtilises = $combProposee;
    //On initialise le tableau de validation qui sera retourné par la méthode
    $pionsNoirBlanc = array(0,0);
    for ($i=0; $i < 4 ; $i++){
      //Si le pion d'indice i de la combinaison secrete est égal au pion de même indice de la combinaison proposée
      if($combSecrete[$i] == $combProposee[$i]){
        // On ajoute un pion noir
        $pionsNoirBlanc[0]++;
        unset($pionsNonTrouves[$i]);
        unset($pionsPropPasUtilises[$i]);
      }
    }
    foreach($pionsPropPasUtilises as $pionProposePasUtilise){
        //Si le pion proposé d'indice $i appartient quand même à la combinaison secrète (privée des pion déjà trouvés)
        if(in_array($pionProposePasUtilise, $pionsNonTrouves)){
          // On ajoute un pion blanc
          $pionsNoirBlanc[1]++;
          // On retire le pion qui a été partiellement trouvé de la liste des pions qu'il reste à trouver
          unset($pionsNonTrouves[array_search($pionProposePasUtilise, $pionsNonTrouves)]);
          // On retire le pion qui correspond à un des pions de couleur de la liste des pions poposés non utilisés
          unset($pionsPropPasUtilises[array_search($pionProposePasUtilise, $pionsPropPasUtilises)]);
        }
    }
    return $pionsNoirBlanc;
  }

  //Méthode qui vérifie si deux combinaisons sont identiques
  //Pré-condition une combinaison doit être donnée en paramètre
  //Post-condition renvoie un booléen qui vaut vrai si les combinaisons sont authentiques et faux si les combinaison sont différentes
  public function estIdentiqueA($comb){
    return $this->comparerA($comb)[0]==4;
  }

}
?>
