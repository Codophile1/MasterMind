<?php
 /** Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
  */

//La Classe combinaison permet de ne pas avoir à gérer les combinaison  directement avec des tableau et ainsi de toujours avoir quatres valeurs;
class Combinaison{
  private $combinaison;

  //Constructeur qui prend en paramètre quatres valeurs.
  public function __construct($n1, $n2, $n3, $n4){
    $combinaison[0] = $n1;
    $combinaison[1] = $n2;
    $combinaison[2] = $n3;
    $combinaison[3] = $n4;
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
      switch ($this->combinaison) {
        case 1:
        //Violet
        $couleurs[i] = "violet";
        break;
        case 2:
        //Fuschia
        $couleurs[i] = "fuschia";
        break;
        case 3:
        //Bleu
        $couleurs[i] = "bleu";
        break;
        case 4:
        //Vert
        $couleurs[i] = "vert";
        break;
        case 5:
        //Jaune
        $couleurs[i] = "jaune";
        break;
        case 6:
        //Orange
        $couleurs[i] = "orange";
        break;
        case 7:
        //Rouge
        $couleurs[i] = "rouge";
        break;
        case 8:
        //Blanc
        $couleurs[i] = "blanc";
        break;

        default:
        //Ne sera jamais executé
        $couleurs[i] = "inconnue";
        break;
      }
    }
  }
  public function toColor(){
    $color = array();
    for ($i=0; $i < 4; $i++) {
      switch ($this->combinaison) {
        case 1:
        //Violet
        $color[i] = "rgb(179, 16, 174)";
        break;
        case 2:
        //Fuschia
        $color[i] = "rgb(249, 64, 243)";
        break;
        case 3:
        //Bleu
        $color[i] = "rgb(50, 50, 200)";
        break;
        case 4:
        //Vert
        $color[i] = "rgb(50, 200, 50)";
        break;
        case 5:
        //Jaune
        $color[i] = "rgb(150, 150, 40)";
        break;
        case 6:
        //Orange
        $color[i] = "rgb(100, 240, 40)";
        break;
        case 7:
        //Rouge
        $color[i] = "rgb(255, 0, 0)";
        break;
        case 8:
        //Blanc
        $color[i] = "rgb(255, 255, 255)";
        break;

        default:
        //Ne sera jamais executé
        $color[i] = "rgb(255, 255, 255)";
        break;
      }
    }
  }

  //Méthode qui permet de comparer une combinaison à la combinaison actuelle
  //Pré-condition : une combinaison doit être passée en paramètre
  //Post-condition : retourne un tableau contenant le nombre de pions noirs et de pions blancs
  public function comparerA($combi){
    $comb = $combi->toArray();
    //On initialise le tableau de validation qui sera retourné par la méthode
    $pionsNoirBlanc = array(0,0);
    for ($i=0; $i < 4 ; $i++){
      if($this->combinaison[$i] == $comb[$i]){
        // On ajoute un pion noir
        $pionsNoirBlanc[0]++;
      }else{
        for($j=0; $j < 4 ; $j++){
          if($this->combinaison[$j] == $comb[$i]){
            // On ajoute un pion blanc
            $pionsNoirBlanc[1]++;
          }
        }
      }
    }
  }

  //Méthode qui vérifie si deux combinaisons sont identiques
  //Pré-condition une combinaison doit être donnée en paramètre
  //Post-condition renvoie un booléen qui vaut vrai si les combinaisons sont authentiques et faux si les combinaison sont différentes
  public function estIdentiqueA($comb){
    return $this->comparerA($comb)[0]==4;
  }

}
?>
