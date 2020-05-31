<?php

$IdPartie = $_GET['idpartie'];

RecuperationDonnee($IdPartie);

function RecuperationDonnee($IdPartie){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;

    //--- Pr�paration de la requ�te
    $Requete = "SELECT `idj1`, `idj2` FROM `partieenattente` WHERE `IdPartie` = ".$IdPartie.";" ;
      
    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    $Resultat = mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

    while (  $ligne = mysqli_fetch_array($Resultat)  )
    {
      //--- Afficher une ligne du tableau HTML pour chaque enregistrement de la table 
        echo  $ligne['idj1'].' '.$ligne['idj2']; 
     
    }

     //--- Lib�rer l'espace m�moire du r�sultat de la requ�te
    mysqli_free_result ( $Resultat ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>