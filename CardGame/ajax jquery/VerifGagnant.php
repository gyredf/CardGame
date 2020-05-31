<?php

$IdClient = $_GET['idclient'];
$IdJ1 = $_GET['idj1'];
$IdJ2 = $_GET['idj2'];
$IdPartie = $_GET['idpartie'];

RecuperationDonnee($IdClient, $IdJ1, $IdJ2, $IdPartie);

function RecuperationDonnee($IdClient, $IdJ1, $IdJ2, $IdPartie){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;

    //--- Pr�paration de la requ�te
   
    $Requete = "SELECT `J1CJoue`, `J2CJoue` FROM `partie` WHERE `IdPartie` = ".$IdPartie.";" ;
    
      
    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    $Resultat = mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

    while (  $ligne = mysqli_fetch_array($Resultat)  )
    {
      //--- Afficher une ligne du tableau HTML pour chaque enregistrement de la table 
        $J1CJoue = $ligne['J1CJoue'];
        $J2CJoue = $ligne['J2CJoue'];
        if ($J1CJoue > $J2CJoue) {
            echo  $IdJ2; 
        } elseif ($J1CJoue < $J2CJoue){
            echo  $IdJ1;
        } elseif ($J1CJoue == $J2CJoue) {
            echo  'null';
        } 
     
    }

     //--- Lib�rer l'espace m�moire du r�sultat de la requ�te
    mysqli_free_result ( $Resultat ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>