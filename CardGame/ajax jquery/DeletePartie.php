<?php

$IdClient = $_GET['idclient'];
$IdJ1 = $_GET['idj1'];
$IdPartie = $_GET['idpartie'];

RecuperationDonnee($IdClient, $IdJ1, $IdPartie);

function RecuperationDonnee($IdClient, $IdJ1, $IdPartie){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;

    //--- Pr�paration de la requ�te
   
    if ($IdJ1 == $IdClient) {
        $Requete = "DELETE FROM `partie` WHERE `IdPartie` = ".$IdPartie.";" ;  
    }
    
      
    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    $Resultat = mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

     //--- Lib�rer l'espace m�moire du r�sultat de la requ�te
    mysqli_free_result ( $Resultat ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>