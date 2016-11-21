<?php
 /** Yanis OUAKRIM
   * Simow WELLENREITER
   * Group 2 
   * Programmation web coté serveur (M3104) : Mini-Projet : Master Mind
 */
  class Vue
  {

    function __construct(argument)
    {

    }
    /*
    * Méthode qui affiche une page de connexion et des erreurs si l'utilisateur a déjà effectué une tentative d'authentification infructueuse
    * @param boolean $motDePasseInvalide Booléen qui vaut vrai si le mot de passe est invalide
    * @param boolean $pseudoInexistant Booléen qui vaut vrai si le pseudo n'existe pas
    */
    function connexion($pseudoEntre = "", $motDePasseInvalide = false, $pseudoInexistant = false){
      ?><html>
        <head>
          <title>MasterMind</title>
        </head>
        <body>
          <form method="POST" action="?VerifAuthentification">
            <input type="text" <?php if($pseudoInexistant){?>class="invalide"<?php}?> value="<?php echo $pseudoEntre;?>" placeholder="Login"/>
            <input type="password"  <?php if($motDePasseInvalide){?>class="invalide"<?php}?> placeholder="Mot de passe"/>
            <input type="submit" value="Connexion"/>
          </form>
        </body>
      </html>
      <?php
    }
  }

 ?>
