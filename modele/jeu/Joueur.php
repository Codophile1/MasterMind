<?php
class Joueur{
  private $pseudo;

  public function __construct($pseud){
    $this->pseudo=$pseud;
  }

  public function getPseudo(){
    return $this->pseudo;
  }
}
 ?>
