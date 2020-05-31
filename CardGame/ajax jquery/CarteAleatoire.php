<?php

$IdPartie = $_GET['idpartie'];
$IdClient = $_GET['idclient'];
$IdJ1 = $_GET['idj1'];
$IdJ2 = $_GET['idj2'];

RecuperationDonnee($IdPartie, $IdClient, $IdJ1, $IdJ2);

function RecuperationDonnee($IdPartie, $IdClient, $IdJ1, $IdJ2){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;

    //--- Pr�paration de la requ�te
    $Requete = "SELECT COUNT(*) AS Nbr FROM `carte`";
      
    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    $Resultat = mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

    $ligne = mysqli_fetch_array($Resultat);

    $rand1 = rand(1,$ligne['Nbr']);
    $rand2 = rand(1,$ligne['Nbr']);

    if ($IdClient == $IdJ1) {
        $Insert = "UPDATE `partie` SET `J1CJoue`=$rand1, `J2CJoue`=$rand2 WHERE IdPartie = $IdPartie;";
    }

    $Insert = mysqli_query ( $DataBase, $Insert) or  die(mysqli_error($DataBase) ) ;

	 //--- Lib�rer l'espace m�moire du r�sultat de la requ�te
    mysqli_free_result ( $Resultat ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>