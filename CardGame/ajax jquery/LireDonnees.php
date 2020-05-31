<?php

RecuperationDonnee();

function RecuperationDonnee(){
	//--- Connection au SGBDR 
                $DataBase = mysqli_connect ( "localhost" , "root" , "" ) ;

                //--- Ouverture de la base de donn�es
                mysqli_select_db ( $DataBase, "CardGame" ) ;

                //--- Pr�paration de la requ�te
                $Requete = "Select * From donnes ORDER BY id;" ;
                  
                //--- Ex�cution de la requ�te (fin du script possible sur erreur ...)
                $Resultat = mysqli_query ( $DataBase, $Requete )  or  die(mysqli_error($DataBase) ) ;

				while (  $ligne = mysqli_fetch_array($Resultat)  )
                {
                  //--- Afficher une ligne du tableau HTML pour chaque enregistrement de la table 
                
                echo  $ligne['nom_donnee']."<br>";  
                }

				 //--- Lib�rer l'espace m�moire du r�sultat de la requ�te
                mysqli_free_result ( $Resultat ) ;

                //--- D�connection de la base de donn�es
                mysqli_close ( $DataBase ) ; 
}

?>