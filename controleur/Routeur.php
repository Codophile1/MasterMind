<?php
 /** Yanis OUAKRIM
   * Simow WELLENREITER
   * Groupe 2
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
 */
  require_once("ControleurAuthentification.php");
  require_once("ControleurJeu.php");
  class Routeur
  {
    private $controleurAuthentification;
    private $controleurJeu;

    function __construct()
    {
      // On initialise les controleurs
      $this->controleurAuthentification=new ControleurAuthentification();
      $this->controleurJeu=new ControleurJeu();

      // si on est connecté
      if($this->controleurAuthentification->estConnecte()){
          //Si l'utilsateur veut se déconnecter
          if(isset($_GET["deconnexion"])) {
            $this->controleurAuthentification->deconnexion();
            header("Location: ?login");
          }
          // Si l'utilisateur soumet une tentative de combinaison
          else if(isset($_GET["jouer"])){
            // On vérifie si l'utilisateur a proposé une couleur pour chacun des pions
            if(isset($_POST['pion1']) && isset($_POST['pion2']) && isset($_POST['pion3']) && isset($_POST['pion4'])){
              // On joue
              $this->controleurJeu->jouer(intval($_POST['pion1']), intval($_POST['pion2']), intval($_POST['pion3']), intval($_POST['pion4']));
              // On affiche le jeu
              header("Location: ?mastermind");
            }
          }else if(isset($_GET["nouveauJeu"])){
            // Si l'utilisateur veut faire un nouveau jeu
            // On créé un nouveau jeu
            $this->controleurJeu->nouveauJeu($_SESSION["pseudo"]);
            // On affiche le jeu créé
            header("Location: ?mastermind");
          }else if(isset($_GET["statistiques"])){
            // Si l'utilisateur souhaite consulter les statistiques
            $this->controleurJeu->afficherStatistiques();
          }
          else if(isset($_SESSION["jeu"])){
            // On affiche la page d'accueil : le jeu
            $this->controleurJeu->afficherJeu();
          }
          else{
            // Si aucun jeu n'est stocké en session, on en créé un nouveau et on l'affiche
            $this->controleurJeu->nouveauJeu($_SESSION["pseudo"]);
            $this->controleurJeu->afficherJeu();
          }
      }
      // Si on tente de se connecter et que l'on vient de remplir le formulaire d'authentification
      else if(isset($_GET['tentativeConnexion'])){
        //On vérifie que les champs on bien été complétés
        if (isset($_POST["pseudo"]) && isset($_POST["mdp"])) {
          if($this->controleurAuthentification->connexion($_POST["pseudo"], $_POST["mdp"])){
            // On affiche la page d'accueil
            header("Location: ?mastermind");
          };
        }else{
          // On affiche la page d'authentification
          $this->controleurAuthentification->formulaireAuthentification();
        }
      }
      // Si on n'est pas connecté
      else {
        // On affiche la page d'authentification
        $this->controleurAuthentification->formulaireAuthentification();
      }

    }
  }

 ?>
