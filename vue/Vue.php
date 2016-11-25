<?php
  /**
   *
   */
  class Vue
  {

    function __construct()
    {

    }
    /*
    * Méthode qui affiche une page de connexion et des erreurs si l'utilisateur a déjà effectué une tentative d'authentification infructueuse
    * @param boolean $motDePasseInvalide Booléen qui vaut vrai si le mot de passe est invalide
    * @param boolean $pseudoInexistant Booléen qui vaut vrai si le pseudo n'existe pas
    */
    function connexion($pseudoEntre = "", $motDePasseInvalide = false, $pseudoInexistant = false){
      ?>
      <!Doctype html>
      <html>
        <head>
          <title>MasterMind</title>
          <meta charset="UTF-8">
          <link rel="stylesheet" type="text/css" href="vue/style/global.css">
          <link rel="stylesheet" type="text/css" href="connexion.css">
        </head>
        <body>
          <form method="POST" action="?tentativeConnexion">
            <input type="text" name="pseudo" <?php if($pseudoInexistant){?>class="invalide"<?php } ?>
             value="<?php echo $pseudoEntre;?>" placeholder="Login"/>
            <input type="password" name="mdp" <?php if($motDePasseInvalide){?>class="invalide"<?php } ?> placeholder="Mot de passe"/>
            <input type="submit" value="Connexion"/>
          </form>
        </body>
      </html>
    <?php
  }
  public function mastermind($phasesDeJeu=array(), $nbcoups=0, $gagne=false, $perdu=false, $combiSecrete=array(0,0,0,0)){
    ?>
      <!doctype html>
      <html>
        <head>
          <title>Mastermind</title>
          <meta charset="UTF-8">
          <link rel="stylesheet" type="text/css" href="vue/style/global.css">
          <link rel="stylesheet" type="text/css" href="vue/style/jeu.css">
        </head>
        <body>
          <?php
            if($gagne){
              echo "<div id='fin'>Gagné !!</div>";
            }elseif ($perdu) {
              echo "<div id='fin'>Perdu !!</div>";
              print_r($combiSecrete);
            }
           ?>
           <div id="menu">
             <ul id="couleurs">
                <li class="bouton" onclick="color('violet');"><div class="violet"></div></li>
                <li class="bouton" onclick="color('fuschia');"><div class="fuschia"></div></li>
                <li class="bouton" onclick="color('bleu');"><div class="bleu"></div></li>
                <li class="bouton" onclick="color('vert');"><div class="vert"></div></li>
                <li class="bouton" onclick="color('jaune');"><div class="jaune"></div></li>
                <li class="bouton" onclick="color('orange');"><div class="orange"></div></li>
                <li class="bouton" onclick="color('rouge');"><div class="rouge"></div></li>
                <li class="bouton" onclick="color('blanc');"><div class="blanc"></div></li>
             </ul>
             <ul id="controles">
               <li><a href="?nouveauJeu" id="nouveauJeu" title="Nouveau jeu"></a></li>
               <li><a href="?statistiques" id="statistiques" title="Afficher les statistiques"></a></li>
                 <li><a href="?deconnexion" id="deconnexion" title="Se déconnecter"></a></li>
             </ul>
           </div>
          <table id="plateau">
              <?php
                // On créé 10 lignes de pions
                $actif = true;
                for($i=0;$i<10;$i++){
                  echo "<tr ";
                  if((!isset($phasesDeJeu[$i])) && $actif==true){
                    // Si on est sur la première ligne à ne pas avoir de phase, on lui donne l'identifiant "actif"
                    echo "id='actif'";
                    $actif=false;
                  }
                  echo ">";
                  // On créé 4 pions pour chaque ligne
                  for($j=0;$j<4;$j++){
                    echo "<td>
                    <div class='pion ";
                    // Si la ligne a été completée
                    if(isset($phasesDeJeu[$i])){
                      // On assigne une couleur au pion
                      echo $phasesDeJeu[$i]->getCombinaison()->tableauCouleur()[$j];
                    }else{
                      // Sinon la case est vide
                      echo "caseVide";
                    }
                    echo "'></div>
                    </td>";
                  }
                  // Si la ligne courante a une phase de jeu
                  if(isset($phasesDeJeu[$i])){
                    echo "<td>
                    <table class='verif'>";
                    //On récupère les nombres de pions noirs et de pions blancs
                    $noir = $phasesDeJeu[$i]->getNbPionsNoirs();
                    $blanc = $phasesDeJeu[$i]->getNbPionsBlancs();
                    //On affiche d'abord les pions noirs puis les pions blancs
                    echo "<tr>";
                    for ($k=0; $k < 4; $k++) {
                        // On assigne une couleur au pion
                        if ($k==2) {
                          echo "</tr>
                          <tr>";
                        }
                        echo "<td>
                        <div class='pionVerif ";
                        if($noir>=1){
                          echo "noir";
                          $noir--;
                        }else if($blanc>=1){
                          echo "blanc";
                          $blanc--;
                        }
                        echo "'></div>
                        </td>
                        ";
                    }
                    echo "</tr>";
                    echo "</table>
                    </td>";
                  }
                  echo "
                  </tr>";
                }
               ?>
            </tr>
          </table>
          <form method="POST" action="?jouer" id="form">
            <input type="hidden" name="pion1" id="pion1">
            <input type="hidden" name="pion2" id="pion2">
            <input type="hidden" name="pion3" id="pion3">
            <input type="hidden" name="pion4" id="pion4" onchange="submit();">
          </form>
          <script type="text/JavaScript">
            var lePionActif = 0;
            var ligneActive = document.getElementById("actif").getElementsByTagName("td");
            var form = document.getElementById("form");
            var champs = form.getElementsByTagName("input");
            function color(color){
              ligneActive.item(lePionActif).getElementsByTagName("div").item(0).className="pion "+color;
              var numCouleur = 0;
              switch (color) {
                case "violet":
                  numCouleur = 1;
                  break;
                case "fuschia":
                  numCouleur=2;
                  break;
                case "bleu":
                  numCouleur=3;
                  break;
                case "vert":
                  numCouleur=4;
                  break;
                case "jaune":
                  numCouleur=5;
                  break;
                case "orange":
                  numCouleur=6;
                  break;
                case "rouge":
                  numCouleur=7;
                  break;
                case "blanc":
                  numCouleur=8;
                  break;
                default:
                  numCouleur=9;
              }

              champs.item(lePionActif).value=numCouleur;
              if (lePionActif==3) {
                form.submit();
              }
              lePionActif++;
            }
          </script>
        </body>
    <?php
  }

  public function statistiques($scores, $meilleursJoueurs){
    ?>
    <!doctype html>
    <html>
      <head>
        <title>Statistiques - Mastermind</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="vue/style/global.css">
        <link rel="stylesheet" type="text/css" href="vue/style/statistiques.css">
      </head>
      <body>
        <div id="menu">
          <ul id="controles">
            <li><a href="?nouveauJeu" id="nouveauJeu" title="Nouveau jeu"></a></li>
            <li><a href="?statistiques" id="statistiques" title="Afficher les statistiques"></a></li>
              <li><a href="?deconnexion" id="deconnexion" title="Se déconnecter"></a></li>
          </ul>
        </div>
        <div id="container">
          <h2>Les 5 meilleures parties</h2>
          <div>
            <table class="score">
              <?php
                $nbCoups=array();
                for ($i=0; $i < count($scores); $i++) {
                  $nbCoups[$i]=$scores[$i][1];
                }
                foreach ($scores as $score) {
                  echo "<tr><td>".$score[0]."</td><td><div class='barre' style='width:".($score[1]/max($nbCoups)*100)."%;'></div><div class='nbCoups'>".$score[1]." coups</div></td></tr>";
                }
               ?>
            </table>
          </div>
          <h2>Les meilleurs joueurs</h2>
          <div>
            <table class="score">
              <?php
                foreach ($meilleursJoueurs as $joueur=>$gagnees) {
                  echo "<tr><td>".$joueur."</td><td><div class='barre' style='width:".(intval($gagnees)/max($meilleursJoueurs)*100)."%;'></div><div class='nbCoups'>".$gagnees." parties gagnées</div></td></tr>";
                }
               ?>
            </table>
          </div>
        </div>
      </body>
    </html>
    <?
  }
}
 ?>
