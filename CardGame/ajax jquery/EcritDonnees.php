<?php

if (isset($_GET['donnee'])) {
  $donnee = $_GET['donnee'];
  EcrireDonnees($donnee);
}

function EcrireDonnees($donnee){
	//--- Connection au SGBDR 
  $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

  //--- Ouverture de la base de donn�es
  mysqli_select_db ( $DataBase, "CardGame" ) ;

  //--- Pr�paration de la requ�te
  echo "donnée : ".$donnee;
  $Requete = "INSERT INTO `donnes`(`nom_donnee`) VALUES ('$donnee');";
  

  //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
  $Resultat = mysqli_query ( $DataBase, $Requete );

  //--- D�connection de la base de donn�es
  mysqli_close ( $DataBase ) ; 
}

?>