<?php 
	$IdPartie = $_GET['idpartie'];
	$IdJ1 = $_GET['idj1'];
	$IdJ2 = $_GET['idj2'];

	$bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');

	$J2join = $bdd->prepare('UPDATE `partie` SET `IdJ2`=? WHERE IdPartie = ?');
  	$J2join->execute(array($IdJ2, $IdPartie));
	
	echo "Id Partie = ".$IdPartie;
	echo "<br>";
	echo "IdJ1 = ".$IdJ1;
	echo "<br>";
	echo "IdJ2 = ".$IdJ2;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Game !</title>
	<meta charset="utf-8">
</head>
<body>
	<input type="hidden" id="IdPartie" value="<?php echo $IdPartie ?>">
	<input type="hidden" id="IdJ1" value="<?php echo $IdJ1 ?>">
	<input type="hidden" id="IdJ2" value="<?php echo $IdJ2 ?>">

<p>EtatJ1 : <span id="EtatJ1">0</span></p>
<p>EtatJ2 : <span id="EtatJ2">0</span></p>

<button id="ModifJ1">Etat J1 = 4</button>
<button id="ModifJ2">Etat J2 = 4</button>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
(function() {
  var httpRequest;
  LireEtats();
  setInterval(() => {
    LireEtats();
  }, 1000);

  function LireEtats() {
    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
      return false;
    }

    httpRequest.onreadystatechange = alertLireEtats;
    httpRequest.open('GET', 'LireEtats.php?idpartie=8');
    httpRequest.send();
  }

  function alertLireEtats() {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        var etats = httpRequest.responseText;
        etats = etats.split(' ');
        var EtatJ1 = etats[0];
        var EtatJ2 = etats[1];

        // console.log(EtatJ1);
        $('#EtatJ1').text(EtatJ1);
        $('#EtatJ2').text(EtatJ2);
      } else {
        alert('Il y a eu un problème avec la requête.');
      }
    }
  }

  $('#ModifJ1').click(function(){
  	var IdPartie = $('#IdPartie').val();
  	$.ajax({
        method:'get',
	    url:'UpdateEtats.php',
	    data:
		    {
		        'idpartie': IdPartie,
		        'joueur': 1
		    }
    });
  });

  $('#ModifJ2').click(function(){
  	var IdPartie = $('#IdPartie').val();
  	$.ajax({
        method:'get',
	    url:'UpdateEtats.php',
	    data:
		    {
		        'idpartie': IdPartie,
		        'joueur': 2
		    }
    });
  });

})();

</script>

</body>
</html>