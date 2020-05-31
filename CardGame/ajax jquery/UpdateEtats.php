<?php

$p = $_GET['idpartie'];
$e = $_GET['etat'];

RecuperationDonnee($p,$e);

function RecuperationDonnee($p,$e){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;

    //--- Pr�paration de la requ�te
    $Requete = "UPDATE `partie` SET `EtatJ1`=".$e.",`EtatJ2`=".$e." WHERE IdPartie = ".$p;
    

    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>