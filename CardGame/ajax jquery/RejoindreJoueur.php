<?php
	$IdPartie = $_GET['idpartie'];
	$IdJ2 = $_GET['idj2'];

	$bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
	$RequeteIdJ1 = $bdd->prepare('SELECT `idj1` FROM `partieenattente` WHERE idpartie = ?;');
	$RequeteIdJ1->execute(array($IdPartie));
	$donnees = $RequeteIdJ1->fetch();
	$IdJ1 = $donnees['idj1'];

	if (isset($IdJ2)) {
		$bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
		
		$SupprPartieEnAttente = $bdd->prepare('UPDATE `partieenattente` SET `idj2`=? WHERE `idpartie` = ?');
		$SupprPartieEnAttente->execute(array($IdJ2,$IdPartie));

		header("location:Jeu_de_Carte.php?idpartie=".$IdPartie."&idj1=".$IdJ1."&idj2=".$IdJ2."&idclient=2");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Attente de joueur</title>
	<meta charset="utf-8">
</head>
<body>

</body>
</html>