<?php

$IdClient = $_GET['idclient'];
$IdJ1 = $_GET['idj1'];
$IdJ2 = $_GET['idj2'];
$Val = $_GET['val'];
$IdPartie = $_GET['idpartie'];

RecuperationDonnee($IdClient, $IdJ1, $IdJ2, $Val, $IdPartie);

function RecuperationDonnee($IdClient, $IdJ1, $IdJ2, $Val, $IdPartie){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;

    //--- Pr�paration de la requ�te
    if ($IdClient == $IdJ1) {
        $Requete = "UPDATE `partie` SET `J1RP`=".$Val." WHERE `IdPartie` = ".$IdPartie.";" ;
    } elseif ($IdClient == $IdJ2){
        $Requete = "UPDATE `partie` SET `J2RP`=".$Val." WHERE `IdPartie` = ".$IdPartie.";" ;
    }            
              
    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>