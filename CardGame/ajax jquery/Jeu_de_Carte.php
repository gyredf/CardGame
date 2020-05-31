<?php
	$IdPartie = $_GET['idpartie'];
	$IdJ1 = $_GET['idj1'];
	$IdJ2 = $_GET['idj2'];
	$IdClient = $_GET['idclient'];

	$bdd = new PDO('mysql:host=localhost;dbname=bataille;charset=utf8', 'root', '');
	$NameJ1 = $bdd->prepare('SELECT `Pseudo` FROM `compte` WHERE `Id` = ?;');
	$NameJ1->execute(array($IdJ1));
	$NameJ2 = $bdd->prepare('SELECT `Pseudo` FROM `compte` WHERE `Id` = ?;');
	$NameJ2->execute(array($IdJ2));
	$donneesJ1 = $NameJ1->fetch();
	$donneesJ2 = $NameJ2->fetch();
	$NameJ1 = $donneesJ1 ['Pseudo'];
	$NameJ2 = $donneesJ2 ['Pseudo'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Mon projet canvas</title>
    <meta charset="utf-8" />
    <!-- <script src="ClassDeck.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<style>
    *{
        margin: 0;
        padding: 0;
    }
</style>
</head>

<body bgcolor=white>
<canvas id="mon_canvas"  style="border:1px solid #000000;">
	Message pour les navigateurs ne supportant pas encore canvas. <!-- s'affiche que si canvas pas supporté
</canvas> -->
<script language=JavaScript >
var firstTurn = true;
var win = false;
var loose = false;
var JAPret = null;
var JAPretRand = null;
var EtatPrec = null;
var IdPartie = null;
var IdJ1 = null;
var IdJ2 = null;
var IdClient = null;
var canvas  = null ;
var context = null ;
var etat = 0;
var PVJ1 = null;
var PVJ2 = null;
var carteJ1 = null;
var carteJ2 = null;
var EtatJ1 = null;
var EtatJ2 = null;
var nomJ1 = null;
var nomJ2 = null;
var idJoueur = 1; //On le récupère lors du lancement de la partie
//console.log(carteTest.backDesign);

canvas = document.getElementById('mon_canvas');
context = canvas.getContext('2d');
canvas.addEventListener('click', checkStart, false);

var arrayCarte = []; 
var carteWidth = canvas.width*0.1;
var carteHeight = canvas.height*0.22;

	class Deck{
		constructor(){
			this.width = carteWidth;	
			this.height = carteHeight;
			this.backCard = 'images/backCard.png';
		}
	}

let deckJ1 = new Deck();
let deckJ2 = new Deck();

////////////////////////////////////////////////////////////////////////////////////////////////

window.onload = function() // execute au chargement de la page sans passer par un onload sur le body
{
	chargerCarte(); //push les cartes dans le tableau arrayCarte
	setInterval('background()', 750);//lance la gestion
	setInterval("GererEtat()",1000);

} ////////////////////////////////////////////////////////////////////////////////////////////////

	

//////////////////////////////////FONCTIONS////////////////////////////////////////////////////////
//////////////////////////////////FONCTIONS////////////////////////////////////////////////////////
//////////////////////////////////FONCTIONS////////////////////////////////////////////////////////
//////////////////////////////////FONCTIONS////////////////////////////////////////////////////////
//////////////////////////////////FONCTIONS////////////////////////////////////////////////////////


	var httpRequest;
	  LireEtats();
	  setInterval(() => {
	    LireEtats();
	  }, 1000);

	  function LireEtats() { ////////////////////////////////////////////////////////////////////////
	    httpRequest = new XMLHttpRequest();

	    if (!httpRequest) {
	      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	      return false;
	    }

	    httpRequest.onreadystatechange = alertLireEtats;
	    httpRequest.open('GET', 'LireEtats.php?idpartie=' + IdPartie);
	    httpRequest.send();
	  } ////////////////////////////////////////////////////////////////////////

	  function alertLireEtats() { ////////////////////////////////////////////////////////////////////////
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
	  } ////////////////////////////////////////////////////////////////////////




	  function DeletePartie() { ////////////////////////////////////////////////////////////////////////
	    httpRequest = new XMLHttpRequest();

	    if (!httpRequest) {
	      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	      return false;
	    }
	    httpRequest.open('GET', 'DeletePartie.php?idpartie=' + IdPartie+'&idclient='+IdClient+'&idj1='+IdJ1);
	    httpRequest.send();
	  } ////////////////////////////////////////////////////////////////////////


	function checkStart(e) {////////////////////////////////////////////////////////////////////////
    var p = getMousePos(e);

	    if (p.x >= canvas.width/2 - canvas.width*0.05 && p.x <= canvas.width/2 + canvas.width*0.05  &&
	        p.y >= canvas.height/2 - canvas.height*0.05 && p.y <= canvas.height/2 + canvas.height*0.05) {

	    VerifPret();
	    }
	} ////////////////////////////////////////////////////////////////////////


	function getMousePos(e) { ////////////////////////////////////////////////////////////////////////
    var r = canvas.getBoundingClientRect();
	    return {
	        x: e.clientX - r.left,
	        y: e.clientY - r.top
	    };
	} ////////////////////////////////////////////////////////////////////////



	function GererEtat(){ ////////////////////////////////////////////////////////////////////////////////////////////////
		//document.write("l'Etat est le suivant: ");
	    if (EtatPrec != etat) {	//évite de rappeller un changement d'etat dans le cas où il n'y en a pas eu
		    switch (etat) {
		      case 0: EtatPrec = 0;Iterer_Etat0(); break; //change l'état, et le stock dans une variable
			  case 1: EtatPrec = 1;Iterer_Etat1(); break;
			  case 2: EtatPrec = 2;Iterer_Etat2(); break;
			  case 3: EtatPrec = 3;Iterer_Etat3(); break;
		      case 4: EtatPrec = 4;Iterer_Etat4(); break;
			  case 5: EtatPrec = 5;Iterer_Etat5(); break;
			  default:
				console.log('Erreur, aucun etat détecté');
				break;
			}
	    }
	} ////////////////////////////////////////////////////////////////////////////////////////////////

	//etat 0 répartition deck/main choix joueur
	//ETAT1 DebutTour pioche
	//ETAT2 AttenteACtionTour + timer
	//Etat3 Effet
	//Etat4 Attente
	//Etat5 Win/Lose

	function CarteRand() { ////////////////////////////////////////////////////////////////////////

	  httpRequest = new XMLHttpRequest();

	  if (!httpRequest) {
	    alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	    return false;
	  }
	  httpRequest.open('GET', 'CarteAleatoire.php?idpartie='+IdPartie+'&idclient='+IdClient+'&idj1='+IdJ1+'&idj2='+IdJ2);
	  httpRequest.send();
	} ////////////////////////////////////////////////////////////////////////

	function UpdatePV(jperdu) { ////////////////////////////////////////////////////////////////////////
		console.info('PV update');

	  httpRequest = new XMLHttpRequest();

	  if (!httpRequest) {
	    alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	    return false;
	  }
	  httpRequest.open('GET', 'UpdatePV.php?idpartie='+IdPartie+'&jperdu='+jperdu+'&idj1='+IdJ1+'&idj2='+IdJ2);
	  httpRequest.send();
	} ////////////////////////////////////////////////////////////////////////

	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function UpdatePret(val) { ////////////////////////////////////////////////////////////////////////
	  httpRequest = new XMLHttpRequest();

	  if (!httpRequest) {
	    alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	    return false;
	  }
	  httpRequest.open('GET', 'UpdatePret.php?idpartie='+IdPartie+'&idclient='+IdClient+'&idj1='+IdJ1+'&idj2='+IdJ2+'&val='+val);
	  httpRequest.send();
	} ////////////////////////////////////////////////////////////////////////

	function LirePret() { ////////////////////////////////////////////////////////////////////////
	  httpRequest = new XMLHttpRequest();

	  if (!httpRequest) {
	    alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	    return false;
	  }
	  httpRequest.onreadystatechange = ReturnPret;
	  httpRequest.open('GET', 'LirePret.php?idpartie='+IdPartie+'&idclient='+IdClient+'&idj1='+IdJ1+'&idj2='+IdJ2);
	  httpRequest.send();
	} ////////////////////////////////////////////////////////////////////////


	function ReturnPret() { ////////////////////////////////////////////////////////////////////////
	  if (httpRequest.readyState === XMLHttpRequest.DONE) {
	    if (httpRequest.status === 200) {
	      // alert(httpRequest.responseText);
	      JAPret = httpRequest.responseText;
	    } else {
	      alert('Il y a eu un problème avec la requête.');
	    }
	  }
	} ////////////////////////////////////////////////////////////////////////

	function VerifPret(){ ////////////////////////////////////////////////////////////////////////
		$('#btnjouer').hide();
		UpdatePret(1);
		timerPret = setInterval('VerifPret2()',500);
		  
	} ////////////////////////////////////////////////////////////////////////

	function VerifPret2(){ ////////////////////////////////////////////////////////////////////////
		LirePret();
	  	
		if (JAPret == 1) {
			clearInterval(timerPret);
			UpdatePret(0);
			PasserEn_Etat(3);
		}else{
			console.log('en attente de l\'autre joueur');
		}
	  
	} ////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function CarteJoue() { ////////////////////////////////////////////////////////////////////////
	      httpRequest = new XMLHttpRequest();

	      if (!httpRequest) {
	        alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	        return false;
	      }

	      httpRequest.onreadystatechange = LireCarteJoue;
	      httpRequest.open('GET', 'LireCarteRand.php?idpartie='+IdPartie); //appel un fichier pour récupérer les cartes de chque joueur
	      httpRequest.send();
	    } ////////////////////////////////////////////////////////////////////////


	    function LireCarteJoue() { ////////////////////////////////////////////////////////////////////////
	      if (httpRequest.readyState === XMLHttpRequest.DONE) {
	        if (httpRequest.status === 200) {	      
	          CartesJoue = httpRequest.responseText;
	          CartesJoue = CartesJoue.split(" ");
	          console.log(CartesJoue);
	          carteJ1 = CartesJoue[0]; //stock la carte de J1 récupérée dans la bdd
	          carteJ2 = CartesJoue[1]; //stock la carte de J2 
	        } else {
	          alert('Il y a eu un problème avec la requête.');
	        }
	      }
	    } ////////////////////////////////////////////////////////////////////////

   //////////////////////////////////FIN_FONCTIONS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_FONCTIONS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_FONCTIONS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_FONCTIONS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_FONCTIONS////////////////////////////////////////////////////////



   //////////////////////////////////ETATS////////////////////////////////////////////////////////
   //////////////////////////////////ETATS////////////////////////////////////////////////////////
   //////////////////////////////////ETATS////////////////////////////////////////////////////////
   //////////////////////////////////ETATS////////////////////////////////////////////////////////
   //////////////////////////////////ETATS////////////////////////////////////////////////////////


   function PasserEn_Etat(n){ ////////////////////////////////////////////////////////////////////////////////////////////////
		etat = n; //récupère l'etat envoyé
		IdPartie = $('#IdPartie').val(); //récupère l'id de la partie envoyé lors de la création de la partie
	  	$.ajax({
	        method:'get',
		    url:'UpdateEtats.php', //execution d'une page qui permet d'update les etats dans la bdd
		    data:
			    {
			        'idpartie': IdPartie, //passe en paramètre l'id de la partie et le prochain etat
			        'etat': n
			    }
	    });
	} ////////////////////////////////////////////////////////////////////////////////////////////////



   function Iterer_Etat0(){ ////////////////////////////////////////////////////////////////////////////////////////////////
		
		console.log('Etat 0');
		IdPartie = $('#IdPartie').val(); //permet de faire la transition 
		IdJ1 = $('#IdJ1').val();
		IdJ2 = $('#IdJ2').val();
		IdClient = $('#IdClient').val();
		nomJ1 = $('#nomJ1').val();
		nomJ2 = $('#nomJ2').val();
		CheckPV(); //récupère les pv de chaque joueur dans la bdd pour mettre à jour l'affichage de ceux ci
		PasserEn_Etat(1);//transistion vers l'etat 1
	}////////////////////////////////////////////////////////////////////////////////////////////////


	function Iterer_Etat1(){ //ETAT1 DebutTour////////////////////////////////////////////////////////////////////////////////////////////////
		console.log('Etat 1');
		

    	if(VerifPV()){  //vérifie les pv des joueurs, si l'un des deux à 0 renvoie true, autrement false
    		PasserEn_Etat(5); //passe à l'état qui permet d'afficher l'écran de fin et de conclure la partie
    	} else{
			PasserEn_Etat(2); //passe à l'état suivant, n'interrompant pas le jeu	
			CarteJoue(); //stock du côté clients les cartes jouées par chaque joueur pour leur affichage
	    console.log(carteJ1 + ' CARTES JOUEES' + carteJ2);
    	}
	    	
	}////////////////////////////////////////////////////////////////////////////////////////////////


	function Iterer_Etat2(){ ////////////////////////////////////////////////////////////////////////////////////////////////

		console.log('Etat 2');
		CarteRand(); //appelle la fonction pour déterminer les prochaines cartes qui vont être jouées

		//la transition à l'etat 3 se fera quand les deux joueurs apuyeront sur le bouton jouer au moins une fois

	}////////////////////////////////////////////////////////////////////////////////////////////////


	function Iterer_Etat3(carte){ ////////////////////////////////////////////////////////////////////////////////////////////////
		
		console.log('Etat 3');
		JAPret = 0; //remet la variable indiquant que le joueur adverse est prêt à 0
		firstTurn = false; //permet l'affichage des cartes, tant que true affiche les dos de cartes
		VerifGagnant(); //va vérifier qui des duex joueurs a gagné et met à jour les points de vie en conséquence

		function VerifGagnant(){
		    httpRequest = new XMLHttpRequest();

		    if (!httpRequest) {
		      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
		      return false;
		    }

		    httpRequest.onreadystatechange = AlertVerifGagnant;
		    httpRequest.open('GET', 'VerifGagnant.php?idpartie='+IdPartie+'&idclient='+IdClient+'&idj1='+IdJ1+'&idj2='+IdJ2);
		    httpRequest.send();

			function AlertVerifGagnant() {
			    if (httpRequest.readyState === XMLHttpRequest.DONE) {
			      if (httpRequest.status === 200) {
			        var Perdant = httpRequest.responseText;
			        if (Perdant != "null") {
			        	Perdant = parseInt(Perdant);
			        	if (IdClient == IdJ1) {
			        		UpdatePV(Perdant);
			        	}
			        }
			        
			      } else {
			        alert('Impossible de récupérer les vie de chacun :c.');
			      }
			    }	    
			}
	    }


		PasserEn_Etat(4);		//je retourne à l'état Attente et lui disant que ma tache est faite
	}////////////////////////////////////////////////////////////////////////////////////////////////



	 
 	


	function Iterer_Etat4(){ ////////////////////////////////////////////////////////////////////////////////////////////////
		console.log('Etat 4');
		CheckPV(); //met à jour les variables du client correspondant aux points de vie
							
		if (VerifPV()) { //en fonction des pv restants envoie sur l'écran de fin ou retourne à l'état un
			PasserEn_Etat(5);
		}else{
			PasserEn_Etat(1);
		}
	}////////////////////////////////////////////////////////////////////////////////////////////////


	
	function Iterer_Etat5(){ ////////////////////////////////////////////////////////////////////////////////////////////////
		
		console.log('Etat 5');
		winnerCheck();
        function winnerCheck(){
            const winner = FinalCheckPV(); //détermine qui a gagné

            if(IdClient == winner){  // en fonction du gagnant affiche un écran victoire ou défaite
                console.log("You win");
                win = true;
            }else{
                console.log("You lose");
            	loose = true;
            }
        }

        function FinalCheckPV(){
            if(PVJ1 <= 0){
                return IdJ2;
            }else if(PVJ2 <= 0){
                return IdJ1;
            }
        }

        //au bout de 5 sec supprime la partie, et envoie le client sur la page de création de parties
        setTimeout(function() {DeletePartie();window.location.href = "index.php";}, 5000);

	}////////////////////////////////////////////////////////////////////////////////////////////////


	 
   //////////////////////////////////FIN_ETATS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_ETATS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_ETATS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_ETATS////////////////////////////////////////////////////////
   //////////////////////////////////FIN_ETATS////////////////////////////////////////////////////////

   
   
    function CheckPV(){ ////////////////////////////////////////////////////////////////////////
	    httpRequest = new XMLHttpRequest();

	    if (!httpRequest) {
	      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
	      return false;
	    }

	    httpRequest.onreadystatechange = AlertCheckPV;
	    httpRequest.open('GET', 'LirePV.php?partie='+IdPartie);
	    httpRequest.send();

	  function AlertCheckPV() {
	    if (httpRequest.readyState === XMLHttpRequest.DONE) {
	      if (httpRequest.status === 200) {
	        var PVs = httpRequest.responseText;
	        PVs = PVs.split(' ');
	        PVJ1 = PVs[0];
	        PVJ2 = PVs[1];
	        console.info('PVJ1 : '+PVJ1);
	        console.info('PVJ2 : '+PVJ2);
	      } else {
	        alert('Impossible de récupérer les vie de chacun :c.');
	      }
	    }	    
	  }
    } ////////////////////////////////////////////////////////////////////////
    

	function VerifPV(){////////////////////////////////////////////////////////////////////////////////////////////////
		if ((PVJ1 > 0) && (PVJ2 > 0)){ //vérifie si joueur ont plus de 0 pv
			return false;          //si oui passe à l'état 2
		} else if ((PVJ1 <= 0) && (PVJ2 > 0)) {
			return true;
		} else if ((PVJ1 > 0) && (PVJ2 <= 0)) {
			return true;
		} else if ((PVJ1 <= 0) && (PVJ2 <= 0)) {
			return true;
		} else {
			return false;
		}
	}////////////////////////////////////////////////////////////////////////////////////////////////


    function TracerCercle(couleur, x, y) ////////////////////////////////////////////////////////////////////////////////////////////////
    {
        context.beginPath(); //On démarre un nouveau tracé.
        context.arc(); //On trace la courbe délimitant notre forme
        // centreX, CentreY, rayon ,angleDépart, angleArrivé
        context.fillStyle = arguments[0];
        context.fill(); //On utilise la méthode fill(); si l'on veut une forme pleine
	    context.closePath();
    } ////////////////////////////////////////////////////////////////////////////////////////////////
    
    function TracerTexte() ////////////////////////////////////////////////////////////////////////////////////////////////
    {	// On passe à l'attribut "font" de l'objet context une simple chaîne de caractères 
	    // composé de la taille de la police, puis de son nom.
	    context.font = "18px Helvetica";
	    context.fillText("Hello World", 0, 30); // texte, pX,pY
	    context.strokeText("Hello World", 300, 130); // stroke = contour uniquement
    } ////////////////////////////////////////////////////////////////////////////////////////////////

  

   /////////////////////////////////GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////GRAPHISME////////////////////////////////////////////////////////



   function chargerCarte(){ ////////////////////////////////////////////////////////////////////////
		for (var i = 1; i < 14; i++) {
			AjouterImage(i);
		}
   } ////////////////////////////////////////////////////////////////////////

	function background(){ ////////////////////////////////////////////////////////////////////////
	canvas.width = window.innerWidth;//longueur du canvas = longueur écran
	canvas.height = window.innerHeight;//hauteur du canvas = hauteur écran
	refreshCardSize();
	context.clearRect(0, 0, canvas.width, canvas.height);//efface le contenu du canvas 
	drawGraphics(arrayCarte[carteJ1-1], arrayCarte[carteJ2-1], nomJ1, nomJ2, PVJ1, PVJ2);//dessine le contenu du canvas
	} ////////////////////////////////////////////////////////////////////////

	function drawBackground(){ ////////////////////////////////////////////////////////////////////////
		var img = new Image();
		img.src = "images/wallpaper.png";
		img.width = canvas.width;
		img.height = canvas.height;
		context.drawImage(img, 0,0, canvas.width, canvas.height);
	} ////////////////////////////////////////////////////////////////////////

	function drawImage(source, longueur, largeur, x1,y1,x2,y2){ ////////////////////////////////////////////////
		var img = new Image();
		img.src = source;
		img.width = longueur;
		img.height = largeur;
		context.drawImage(img, x1, y1, x2, y2);
	} ////////////////////////////////////////////////////////////////////////

	function AjouterImage(nom){////////////////////////////////////////////////////////////////////////
            var carte = "carte/Noir/"+nom;
            arrayCarte.push(carte);
    } ////////////////////////////////////////////////////////////////////////

	function drawDeck(){ ////////////////////////////////////////////////////////////////////////
		drawImage(deckJ1.backCard, deckJ1.width, deckJ1.height,canvas.width/2 - deckJ1.width/2, canvas.height*0.95, deckJ1.width, - deckJ1.height )
		drawImage(deckJ2.backCard, deckJ2.width, deckJ2.height,canvas.width/2 - deckJ2.width/2, deckJ2.height*0.05, deckJ2.width, + deckJ2.height)
	} ////////////////////////////////////////////////////////////////////////


	function drawCard(CardJ1, CardJ2){ ////////////////////////////////////////////////////////////////////////
		drawImage(CardJ1, deckJ1.width, deckJ1.height,canvas.width/2 - deckJ1.width, canvas.height*0.5 - deckJ1.height/2, - deckJ1.width, + deckJ1.height)
		drawImage(CardJ2, deckJ2.width, deckJ2.height,canvas.width/2 + deckJ2.width, canvas.height*0.5 - deckJ2.height/2, + deckJ2.width, + deckJ2.height)
	} ////////////////////////////////////////////////////////////////////////


	function refreshCardSize(){ ////////////////////////////////////////////////////////////////////////
		deckJ1.width  = canvas.width*0.1;
		deckJ1.height = canvas.height*0.22;
		deckJ2.width  = canvas.width*0.1;
		deckJ2.height =  canvas.height*0.22;
	} ////////////////////////////////////////////////////////////////////////

	function drawName(nameJ1, nameJ2) //////////////////////////////////////////////////////////////////////// 
    {	
	    context.font = "28px Helvetica";
	    context.fillText(nameJ1, canvas.width*0.2, canvas.height*0.1); // texte, pX,pY
	    context.font = "28px Helvetica";
	    context.fillText(nameJ2, canvas.width*0.6, canvas.height*0.9); // texte, pX,pY
    } ////////////////////////////////////////////////////////////////////////

    function drawPV(PVJ1, PVJ2){ ////////////////////////////////////////////////////////////////////////
    	context.font = "28px Helvetica";
	    context.fillText('PV: ' + PVJ1, canvas.width*0.65, canvas.height*0.1); // texte, pX,pY
	    context.font = "28px Helvetica";
	    context.fillText('PV: ' + PVJ2, canvas.width*0.27, canvas.height*0.9); // texte, pX,pY
    } ////////////////////////////////////////////////////////////////////////

    function drawWin(){ ////////////////////////////////////////////////////////////////////////
    	context.font = "28px Helvetica";
	    context.fillText('VICTOIRE', canvas.width*0.5 - 50, canvas.height*0.4); //
    } ////////////////////////////////////////////////////////////////////////

    function drawLoose(){ ////////////////////////////////////////////////////////////////////////
    	context.font = "28px Helvetica";
	    context.fillText('DEFAITE', canvas.width*0.5 - 50, canvas.height*0.4); //
    } ////////////////////////////////////////////////////////////////////////

    function drawButton(){ ////////////////////////////////////////////////////////////////////////
		drawImage('images/playbtn', canvas.width*0.1, canvas.height*0.1,canvas.width/2 - canvas.width*0.05, canvas.height/2 - canvas.height*0.05, canvas.width*0.1, canvas.height*0.1)
    } ////////////////////////////////////////////////////////////////////////

    function drawGraphics(carteJ1, carteJ2, nameJ1, nameJ2, PVJ1, PVJ2){ ////////////////////////////////
    	if (!win && !loose) {
	    	drawBackground();
	    	drawDeck();
			drawPV(PVJ1, PVJ2);
			drawName(nameJ1, nameJ2);
			drawButton();
			if (firstTurn){drawCard('images/backCard.png','images/backCard.png');} else {drawCard(carteJ1,carteJ2);}
		} else if (win) {drawBackground();drawWin();} else
		if (loose) {drawBackground();drawLoose();} else 
		{ console.log('erreur');}
    } ////////////////////////////////////////////////////////////////////////

   /////////////////////////////////FIN_GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////FIN_GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////FIN_GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////FIN_GRAPHISME////////////////////////////////////////////////////////
   /////////////////////////////////FIN_GRAPHISME////////////////////////////////////////////////////////



</script>

<!-- convertir variables php en var javascript, on récupère les valeurs de chacun des inputs que l'on va récupérer
	et assigner dans dans des variables javascript à l'ETAT 0
 -->

<input type="hidden" id="IdPartie" value="<?php echo $IdPartie ?>"> 
<input type="hidden" id="IdJ1" value="<?php echo $IdJ1 ?>">
<input type="hidden" id="IdJ2" value="<?php echo $IdJ2 ?>">
<input type="hidden" id="IdClient" value="<?php echo $IdClient ?>">
<input type="hidden" id="nomJ1" value="<?php echo $NameJ1 ?>">
<input type="hidden" id="nomJ2" value="<?php echo $NameJ2 ?>">

<!--<p>EtatJ1 : <span id="EtatJ1">0</span></p> 
<p>EtatJ2 : <span id="EtatJ2">0</span></p>
<button id="btnjouer" onclick="VerifPret()">Jouer</button>-->
 
</body>
</html>