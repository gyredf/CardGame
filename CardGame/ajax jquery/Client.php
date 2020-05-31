<label for="donnee">Écrire une données</label>
<br>
<input id="type" type="number" min="1" max="3" onChange="changeByType()">
<select id="joueur">
  <option value="J1">J1</option>
  <option value="J2">J2</option>
</select>
<input id="donnee" type="text" placeholder="Donnee">
<button onclick="getValueForm()">Envoyer</button>

<script>
changeByType();
var tableau = new Array();
var tableauCartes = new Array();

(function() {
  var httpRequest;
  GetAllCards();
  makeRequest()
  setInterval(() => {
    makeRequest()
  }, 5000);

  function makeRequest() {
    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
      alert('Abandon :( Impossible de créer une instance de XMLHTTP');
      return false;
    }

    httpRequest.onreadystatechange = alertContents;
    httpRequest.open('GET', 'LireDonnees.php');
    httpRequest.send();
  }

  function alertContents() {
    if (httpRequest.readyState === XMLHttpRequest.DONE) {
      if (httpRequest.status === 200) {
        // alert(httpRequest.responseText);
        extraireToken(httpRequest.responseText);
        var date = new Date()
        var fullDate = date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
        console.info("========== Nouvelle Lecture | "+fullDate+" ==========");
        // console.log(tableau);
        lire(tableau);

      } else {
        alert('Il y a eu un problème avec la requête.');
      }
    }
  }

})();

function extraireToken(ligne) { /////////////////////////////////////////////////////////////////////////////////////////////////////
  var mot = "";
  tableau = [];
  //console.log(ligne[6]);
  //console.log(ligne);
  //console.log(ligne.length);

  var lire = true;

  for (var i = 0; i < ligne.length; i++) {
    if(lire == true){
        if (ligne[i] == "<") { tableau.push(mot); mot = ""; lire=false} else
        if ((ligne[i] != "<")) { mot = mot + ligne[i]; }
    } else if (lire == false) {
        if (ligne[i] == ">") {lire=true}
    }
  }

} //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function lire(tableau){
  // Faire en fonction des numero de code
  // Code : 
  // 1 = Verifie la connexion des joueurs
  // 2 = Indique le tour du joueur
  // 3 = Indique que un joueur joue

  for (let i = 0; i < tableau.length; i++) {
    var line = tableau[i].split(' ');
    //console.log(line[0]);
    
    switch (line[0]) {
      case "1":
        // console.log(verifConnexion(tableau[i]));
        break;

      case "2":
        // console.log(aQuiLeTour(tableau[i]));
        break;

      case "3":
        // console.log(joueurJoue(tableau[i]));
        break;
    
      default:
        break;
    }

  }

}

function verifConnexion(line){
  if (line == "1 J1 Pret") {
    var etatJ2 = false;
    for (let o = 0; o < tableau.length; o++) {
      if (tableau[o] == "1 J2 Pret") {
        etatJ2 = true;
      }else{
      }
    }
    if (etatJ2 == true) {
      return "Les deux joueur sont pret";
    }
    if (etatJ2 == false) {
      return "Le J2 n\'est pas pret";
    }
  }

  if (line == "1 J2 Pret") {
    var etatJ1 = false;
    for (let o = 0; o < tableau.length; o++) {
      if (tableau[o] == "1 J1 Pret") {
        etatJ1 = true;
      }else{
      }
    }
    if (etatJ1 == true) {
      return "Les deux joueur sont pret";
    }
    if (etatJ1 == false) {
      return "Le J1 n\'est pas pret";
    }
  }

  if (line == "1 J1 Quitte") {
    return "Le J1 a quitté la partie";
  }

  if (line == "1 J2 Quitte") {
    return "Le J2 a quitté la partie";
  }

}

function aQuiLeTour(line){
  if (line == "2 J1") {
    return "C\'est le tour du J1";
  }else if (line == "2 J2") {
    return "C\'est le tour du J2";
  }
}

function joueurJoue(line){
  var ligne = line.split(' ');
  var Joueur = ligne[1];
  var CardId = ligne[2];

  if (Joueur == "J1") {
    return "Le J1 a joué le carte " + CardId;
  }else if (Joueur == "J2") {
    return "Le J2 a joué le carte " + CardId;
  }

}

function changeByType(){
  var type = document.getElementById('type');
  var donnee = document.getElementById('donnee');

  donnee.style.display = "none";
  donnee.value = "";
  donnee.placeholder = "Donnee";

  if (type.value == "1") {
    donnee.value = "Pret";
  }

  if (type.value == "3") {
    donnee.placeholder = "Id carte";
  }

  if (type.value != "2") {
    document.getElementById('donnee').style.display = "inline-block";
  }

}

function getValueForm(){
  var type = document.getElementById('type').value;
  var joueur = document.getElementById('joueur').value;
  var donnee = document.getElementById('donnee').value;
  var fullDonnee = type +" "+ joueur +" "+ donnee;
  // alert(fullDonnee);
  insertDonnee(fullDonnee);
}

function insertDonnee(donnee) {
  httpRequest = new XMLHttpRequest();

  if (!httpRequest) {
    alert('Abandon :( Impossible de créer une instance de XMLHTTP');
    return false;
  }
  httpRequest.open('GET', 'EcritDonnees.php?donnee='+donnee);
  httpRequest.send();
}

function GetAllCards() {
  console.info('========== Lecture de toute les cartes ==========')
  httpRequest = new XMLHttpRequest();

  if (!httpRequest) {
    alert('Abandon :( Impossible de créer une instance de XMLHTTP');
    return false;
  }
  httpRequest.onreadystatechange = AlertAllCards;
  httpRequest.open('GET', 'LireCartes.php');
  httpRequest.send();
}

function AlertAllCards() {
  if (httpRequest.readyState === XMLHttpRequest.DONE) {
    if (httpRequest.status === 200) {
      // alert(httpRequest.responseText);
      extraireTokenCartes(httpRequest.responseText);
      console.log(tableauCartes);
    } else {
      alert('Il y a eu un problème avec la requête.');
    }
  }
}

function extraireTokenCartes(ligne) {
  var mot = "";
  tableauCartes = [];
  //console.log(ligne[6]);
  //console.log(ligne);
  //console.log(ligne.length);

  var lire = true;

  for (var i = 0; i < ligne.length; i++) {
    if(lire == true){
        if (ligne[i] == "<") { tableauCartes.push(mot); mot = ""; lire=false} else
        if ((ligne[i] != "<")) { mot = mot + ligne[i]; }
    } else if (lire == false) {
        if (ligne[i] == ">") {lire=true}
    }
  }

  for (var i = 0; i < tableauCartes.length; i++) {
    fillDeck(tableauCartes[i].split(' '));
    
  }

}

function fillDeck(ligne){
  Deck.push(new Carte(ligne[0],ligne[1],ligne[2],ligne[3],ligne[4]));
}

</script>