<?php
 /** Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
  */

require_once("modele/DAO/DAO.php");
require_once("vue/Vue.php");
class ControleurAuthentification {
	private $bd;
	private $vue;

	public function __construct() {
		$this->bd = new DAO();
		$this->vue = new Vue();
		session_start();
	}

	/*
	* Pré-condition : reçoit une chaine de caractère correspondant au pseudo et une chaine de caractère correspondant au mot de passe
	* Post-condition : des variables de sessions sont initialisées, un booléen est retourné, il vaut vrai si l'utilisateur est bien connecté et faux sinon
  */
	public function connexion($pseudo, $mdp) {
		try{
			if($this->bd->identifiantsValides($pseudo, $mdp)){
				$_SESSION["pseudo"] = $pseudo;
			}
		}catch(PseudoInexistantException $e){
			$this->vue->connexion($pseudo, false, true);
		}catch(MotDePasseInvalideException $e){
			$this->vue->connexion($pseudo, true, false);
		}
		return $this->estConnecte();
	}
	//Precondition : l'utilisateur est connecté
	//Post-condition :  les variables de session sont détruites
	public function deconnexion() {
		session_destroy();
	}

	//Méthode qui vérifie si l'utilisateur est connecté
	//Post-condition : renvoie un boolean qui vaut vrai si l'utilisateur est connecté et faux s'il n'est pas connecté
	public function estConnecte(){
		return isset($_SESSION["pseudo"]);
	}

	public function formulaireAuthentification($pseudoEntre = "", $motDePasseInvalide = false, $pseudoInexistant = false){
		$this->vue->connexion($pseudoEntre, $motDePasseInvalide, $pseudoInexistant);
	}
}
 ?>
