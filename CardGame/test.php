<!DOCTYPE html>
<html>
<head>
	<title></title>

</head>
<body>
	<canvas id="canvasid"  style="border:1px solid #000000;" width="1920" height="1080">
	Message pour les navigateurs ne supportant pas encore canvas. <!-- s'affiche que si canvas pas supporté
</canvas> -->
	<script type="text/javascript">

	var canvas = document.getElementById('canvasid');
	var context = canvas.getContext('2d');

	var arrayCarte = [];
	var carteWidth = canvas.width*0.1;
	var carteHeight = canvas.height*0.22;

	class Deck{
		constructor(){
			this.width = carteWidth;	
			this.height = carteHeight;
			this.backCard = 'backCard.png';
		}
	}


	let deckJ1 = new Deck();
	let deckJ2 = new Deck();

	

	chargerCarte();
	setInterval('background()', 750);
	

	function chargerCarte(){
		for (var i = 1; i < 14; i++) {
			AjouterImage(i);
		}
	}

	function background(){
	canvas.width = window.innerWidth;//timer actualise
	canvas.height = window.innerHeight;
	refreshCardSize();
	context.clearRect(0, 0, canvas.width, canvas.height);
	drawGraphics(arrayCarte[0], arrayCarte[2], 'Momo', 'xXNARUTO SEPHIROSXx', '100', '100');
	}

	function drawBackground(){
		var img = new Image();
		img.src = "wallpaper.png";
		img.width = canvas.width;
		img.height = canvas.height;
		context.drawImage(img, 0,0, canvas.width, canvas.height);
	}

	function drawImage(source, longueur, largeur, x1,y1,x2,y2){
		var img = new Image();
		img.src = source;
		img.width = longueur;
		img.height = largeur;
		context.drawImage(img, x1, y1, x2, y2);
	}

	function AjouterImage(nom){
            var carte = "ajax jquery/carte/Noir/"+nom;
            arrayCarte.push(carte);
    }

	//function drawShape(){
	//	context.rect(canvas.width/2 - carteWidth/2, canvas.height*0.95, carteWidth, - carteHeight);
	//	context.fill();
	//	context.rect(canvas.width/2 - carteWidth/2, canvas.height*0.05, carteWidth, + carteHeight);
	//	context.fill();

	//	context.rect(canvas.width/2 - carteWidth, canvas.height*0.5 - carteHeight/2, - carteWidth, + carteHeight);
	//	context.fill();
	//	context.rect(canvas.width/2 + carteWidth, canvas.height*0.5 - carteHeight/2, + carteWidth, + carteHeight);
	//	context.fill();
	//}    

	function drawDeck(){
		drawImage(deckJ1.backCard, deckJ1.width, deckJ1.height,canvas.width/2 - deckJ1.width/2, canvas.height*0.95, deckJ1.width, - deckJ1.height )
		drawImage(deckJ2.backCard, deckJ2.width, deckJ2.height,canvas.width/2 - deckJ2.width/2, deckJ2.height*0.05, deckJ2.width, + deckJ2.height)
	}


	function drawCard(backCardJ1, backCardJ2){
		drawImage(backCardJ1, deckJ1.width, deckJ1.height,canvas.width/2 - deckJ1.width, canvas.height*0.5 - deckJ1.height/2, - deckJ1.width, + deckJ1.height)
		drawImage(backCardJ2, deckJ2.width, deckJ2.height,canvas.width/2 + deckJ2.width, canvas.height*0.5 - deckJ2.height/2, + deckJ2.width, + deckJ2.height)
	}


	function refreshCardSize(){
		deckJ1.width  = canvas.width*0.1;
		deckJ1.height = canvas.height*0.22;
		deckJ2.width  = canvas.width*0.1;
		deckJ2.height =  canvas.height*0.22;
	}

	function drawName(nameJ1, nameJ2) 
    {	
	    context.font = "28px Helvetica";
	    context.fillText(nameJ1, canvas.width*0.2, canvas.height*0.1); // texte, pX,pY
	    context.font = "28px Helvetica";
	    context.fillText(nameJ2, canvas.width*0.6, canvas.height*0.9); // texte, pX,pY
    } 

    function drawPV(PVJ1, PVJ2){
    	context.font = "28px Helvetica";
	    context.fillText('PV: ' + PVJ1, canvas.width*0.65, canvas.height*0.1); // texte, pX,pY
	    context.font = "28px Helvetica";
	    context.fillText('PV: ' + PVJ2, canvas.width*0.27, canvas.height*0.9); // texte, pX,pY
    }

    function drawButton(){
    	context.rect(canvas.width/2 - canvas.width*0.05, canvas.height/2 - canvas.height*0.05, canvas.width*0.1, canvas.height*0.1);
		context.fill();
		drawImage('playbtn', canvas.width*0.1, canvas.height*0.1,canvas.width/2 - canvas.width*0.05, canvas.height/2 - canvas.height*0.05, canvas.width*0.1, canvas.height*0.1)
    }

    function drawGraphics(carteJ1, carteJ2, nameJ1, nameJ2, PVJ1, PVJ2){
    	drawBackground();
    	drawDeck();
		drawCard(carteJ1,carteJ2);//remplacer par carte j1 et j2 et envoyer array
		drawPV(PVJ1, PVJ2);
		drawName(nameJ1, nameJ2);
		drawButton();
    }


</script>
</body>
</html>

