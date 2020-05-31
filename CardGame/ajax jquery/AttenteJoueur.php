<?php
	$IdPartie = $_GET['idpartie'];
	$IdJ1 = $_GET['idj1'];

	if (isset($_GET['create'])) {
		$IdPartie = $_GET['idpartie'];
		$IdJ1 = $_GET['idj1'];
		$IdJ2 = $_GET['idj2'];
		$bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
		$NewPartie = $bdd->prepare('INSERT INTO `partie`(`IdPartie`, `IdJ1`, `IdJ2`, `PVJ1`, `PVJ2`, `EtatJ1`, `EtatJ2`, `J1CJoue`, `J2CJoue`, `J1Pret`, `J2Pret`) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
		$NewPartie->execute(array($IdPartie, $IdJ1, $IdJ2, 100, 100, 0, 0, 0, 0, 0, 0));

		$SupprPartieEnAttente = $bdd->prepare('DELETE FROM `partieenattente` WHERE `idpartie` = ?');
		$SupprPartieEnAttente->execute(array($IdPartie));

		header("location:Jeu_de_Carte.php?idpartie=".$IdPartie."&idj1=".$IdJ1."&idj2=".$IdJ2."&idclient=1");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Attente de joueur</title>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> 
</head>
<style type="text/css">
	*{
		margin: 0;
		padding: 0;
	}

	.container{
		width: 100%;
		height: 100vh;
		display: flex;
		top: 0;
		left: 0;
		justify-content: center;
		align-items: center;
	}

	h1{
		font-size: 10rem;
		color: #7AC943;
		font-family: 'Pacifico', cursive;
		animation: bounce 1.5s infinite;
		filter: drop-shadow(0 10px 10px black);

	}

	@keyframes bounce{
        0%   { transform: scale(1,1)      translateY(0); }
        10%  { transform: scale(1.1,.9)   translateY(0); }
        30%  { transform: scale(.9,1.1)   translateY(-100px); }
        50%  { transform: scale(1.05,.95) translateY(0); }
        57%  { transform: scale(1,1)      translateY(-7px); }
        64%  { transform: scale(1,1)      translateY(0); }
        100% { transform: scale(1,1)      translateY(0); }
    }
</style>
<body>
	<div class="container">
		<h1>En Attente..</h1>
	</div>



	<input type="hidden" id="IdPartie" value="<?php echo $IdPartie ?>">
	<input type="hidden" id="IdJ1" value="<?php echo $IdJ1 ?>">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">
		IdPartie = $('#IdPartie').val();
		IdJ1 = $('#IdJ1').val();
		setInterval(LireAttente,1000);

		function LireAttente() {
			httpRequest = new XMLHttpRequest();

			if (!httpRequest) {
			alert('Abandon :( Impossible de créer une instance de XMLHTTP');
			return false;
			}
			httpRequest.onreadystatechange = ReturnLireAttente;
			httpRequest.open('GET', 'LireAttente.php?idpartie='+IdPartie);
			httpRequest.send();
		}

		function ReturnLireAttente() {
			if (httpRequest.readyState === XMLHttpRequest.DONE) {
				if (httpRequest.status === 200) {
				  // alert(httpRequest.responseText);
				  var Etats = httpRequest.responseText;
				  Etats = Etats.split(' ');
				  var EtatJ1 = Etats[0];
				  var EtatJ2 = Etats[1];
				  console.info(EtatJ1);
				  console.info(EtatJ2);
				  if (EtatJ2 === "") {
				  	console.log('En attente d\'un joueur 2');
				  }else{
				  	console.log('Joueur trouvé');
				  	document.location.href="?idpartie="+IdPartie+"&idj1="+EtatJ1+"&idj2="+EtatJ2+"&create=true";
				  }
				} else {
				  alert('Il y a eu un problème avec la requête.');
				}
			}
		}
	</script>
</body>
</html>