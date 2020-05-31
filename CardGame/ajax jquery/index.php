<?php
session_start();
// $_SESSION["idCompte"] = null;
// $_SESSION["Pseudo"] = null;

try{
  $bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
}catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
$PartieEnAttente = $bdd->query('SELECT * FROM `partieenattente` ORDER BY idpartie;');
$PartieEnCours = $bdd->query('SELECT idPartie FROM partie WHERE IdJ1 IS NOT NULL AND IdJ2 IS NOT NULL ORDER BY idPartie ASC;');

function online($id){
  $bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
  $Online = $bdd->prepare('UPDATE `compte` SET `Online`=1,`EtatRecherche`=1 WHERE id = ?');
  $Online->execute(array($id));
}
function offline($id){
  $bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
  $Offline = $bdd->prepare('UPDATE `compte` SET `Online`=0,`EtatRecherche`=0 WHERE id = ?');
  $Offline->execute(array($id));
  header('location:Script server.php');
}


if (isset($_GET['offline'])) {
  // Online = 0
  offline($_SESSION["idCompte"]);
  
  unset($_SESSION["idCompte"]);
  unset($_SESSION["Pseudo"]);
}

if (isset($_POST['pseudo']) && isset($_POST['password'])){
  $Pseudo = $_POST['pseudo'];
  $Password = $_POST['password'];
  $Compte = $bdd->prepare('SELECT `Id`, `Pseudo`, `Password` FROM `compte` WHERE `Pseudo` = ? AND `Password` = ? ');
  $Compte->execute(array($Pseudo,$Password));
  $CompteExist = $Compte->rowCount();
  if ($CompteExist == 1 ) {
    while ($donnees = $Compte->fetch()){
      $_SESSION["idCompte"] = $donnees['Id'];
      $_SESSION["Pseudo"] = $donnees['Pseudo'];
      online($_SESSION["idCompte"]);
    }
  }
  if ($CompteExist == 0 ) {
    echo "Pseudo ou mot de passe incorrect";
  }
}

if (isset($_POST['AddGame'])){
  CreerPartie($_SESSION["idCompte"]);
}

function CreerPartie($idj1){
  $bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
  $NewPartieEnAttente = $bdd->prepare('INSERT INTO `partieenattente`(`idj1`) VALUES (?)');
  $NewPartieEnAttente->execute(array($idj1));
  $GetIdPartie = $bdd->prepare('SELECT `idpartie`FROM `partieenattente` WHERE `idj1` = ?');
  $GetIdPartie->execute(array($idj1));
  $donnees = $GetIdPartie->fetch();
  $idPartie = $donnees['idpartie'];
  header('location: AttenteJoueur.php?idpartie='.$idPartie.'&idj1='.$idj1);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="lobby.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Serveur Browser</title>
</head>
<body>
  <div class="loader"></div>
  <header>
    <?php
      if (isset($_SESSION["Pseudo"]) && isset($_SESSION["Pseudo"])){
    ?>
        <h1>Connecté avec le compte : <?php echo $_SESSION["Pseudo"] ?></h1>

        <form method="POST">
          <input type="hidden" name="AddGame" value="true">
          <input type="submit" class="btn-newGame" value="Créer une nouvelle partie">
        </form>
        <a class="btn-logout" href="?offline=true">Déconnexion</a>
    <?php
      } else {
        ?>
          <form method="POST">
            <input type="text" name="pseudo" placeholder="Pseudo">
            <input type="text" name="password" placeholder="Mot de passe">
            <input type="submit" value="Connexion">
          </form>
        <?php
      }
    ?>
  </header>


    <?php
      if (isset($_SESSION["Pseudo"]) && isset($_SESSION["Pseudo"])){
    ?>
    <div class="lobby-menu">
      <table class="PartieEnAttente">
        <tr>
          <td>Id partie en attente :</td>
          <td>Action :</td>
        </tr>
        <?php
          while ($donnees = $PartieEnAttente->fetch())
          {
            echo "<tr>";
            echo "<td>".$donnees['idpartie']."</td>";
            echo "<td><a class='btn-join' href='RejoindreJoueur.php?idpartie=".$donnees['idpartie']."&idj2=".$_SESSION['idCompte']."'>Rejoindre</a></td>";
            echo "</tr>";
          }
        ?>
      </table>
      <table class="PartieEnCours">
        <tr>
          <td>Id partie en cours :</td>
        </tr>
        <?php
          while ($donnees = $PartieEnCours->fetch())
          {
            echo "<tr>";
            echo "<td>".$donnees['idPartie']."</td>";
            echo "</tr>";
          }
        ?>
      </table>
    </div>
    <?php
    }
    ?>
</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

var tableau = new Array();

function SelectPartie(){
    // Soit le joueur crée un partie
    //    CreateurDePartie();
    // Soit le joueur rejoind une partie
};

function CreateurDePartie(){
    // Requete qui insert dans partie:
    //    IDJ1 = Id du joueur qui créer la partie
    //    IDJ2 = NULL
    //    PVJ1 = 100
    //    PVJ2 = 100
    //    Etat....
};

function AfficheurPartieEnAttente(){
    // Liste par un tableau les parties qui on IDJ2 = NULL
};

function AfficheurPartieEnCours(tableau){
    // Liste par un tableau les parties qui on 2 joueurs
};




// Timer GET PV

// (function() {
//   var httpRequest;
//   makeRequest()
//   setInterval(() => {
//     makeRequest()
//   }, 5000);

//   function makeRequest() {
//     httpRequest = new XMLHttpRequest();

//     if (!httpRequest) {
//       alert('Abandon :( Impossible de créer une instance de XMLHTTP');
//       return false;
//     }

//     httpRequest.onreadystatechange = alertContents;
//     httpRequest.open('GET', 'LirePV.php?partie=1');
//     httpRequest.send();
//   }

//   function alertContents() {
//     if (httpRequest.readyState === XMLHttpRequest.DONE) {
//       if (httpRequest.status === 200) {
//         var test = httpRequest.responseText;
//         test = test.split(' ');
//         var date = new Date()
//         var fullDate = date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
//         console.info("========== Nouvelle Lecture | "+fullDate+" ==========");
//         console.log(test[0]+test[1]);
//       } else {
//         alert('Il y a eu un problème avec la requête.');
//       }
//     }
//   }

// })();


</script>