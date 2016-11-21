<?php 
  /**Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
   */

require_once("../modele/bd.php");

class ControleurAuthentification {
	private $bd;
	
	public function __construct() {
		$this->bd = new BD();
		session_start();
	}
	
	//pre-condition : reçoit une chaine de caractère
	//post-condition : des variables de sessions sont initialisées
	public function connexion($pseudo) {
		if ($this->bd->exists($pseudo)) {
			$_SESSION["pseudo"] = $pseudo;
		}else {
			/*Exception*/
		}
		return $this->estConnecte();
	}
	//Precondition : l'utilisateur est connecté
	//Post-condition :  les variables de session sont détruites
	public function deconnexion($pseudo) {
		session_destroy();
	}
	
	public function estConnecte() {
		return isset($_SESSION["pseudo"]);
	}
}
 ?>
