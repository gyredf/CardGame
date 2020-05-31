<?php
$IdJ1 = $_GET['idj1'];
$IdJ2 = $_GET['idj2'];
$p = $_GET['idpartie'];
$JPerdu = $_GET['jperdu'];
$degats = 5;

RecuperationDonnee($IdJ1, $IdJ2, $p, $degats, $JPerdu);

function RecuperationDonnee($IdJ1, $IdJ2, $p, $degats, $JPerdu){
	//--- Connection au SGBDR 
    $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

    //--- Ouverture de la base de donn�es
    mysqli_select_db ( $DataBase, "bataille" ) ;


    //--- Pr�paration de la requ�te
    $GetPV = "SELECT `PVJ1`, `PVJ2` FROM `partie` WHERE `IdPartie` = ".$p;
      
    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    $ResultatGetPV = mysqli_query ( $DataBase, $GetPV )  or  die(mysqli_error($DataBase) ) ;

    $lignePV = mysqli_fetch_array($ResultatGetPV);

    $PVJ1 = $lignePV['PVJ1'];
    $PVJ2 = $lignePV['PVJ2'];

    if ($JPerdu == $IdJ1) {
        $PVJ1 = $PVJ1 - $degats;
    } elseif ($JPerdu == $IdJ2){
        $PVJ2 = $PVJ2 - $degats;
    }
    

    //--- Pr�paration de la requ�te
    $Requete = "UPDATE `partie` SET `PVJ1`=".$PVJ1.",`PVJ2`=".$PVJ2." WHERE `IdPartie` = ".$p;
    

    //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
    mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

    //--- D�connection de la base de donn�es
    mysqli_close ( $DataBase ) ; 
}

?>