<?php
	require "fonc_oracle.php";
	// On charge les epreuves par années
	if(isset($_GET['valeurTour']) && !empty($_GET['valeurTour'])){ 
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$reqTour = 'select * from TDF_EPREUVE where annee = ' . $_GET['valeurTour'];
		$curTour = PreparerRequete($conn, $reqTour);
		$res = ExecuterRequete($curTour);
		$nbTour = LireDonnees1($curTour,$tabTour);
		FermerConnexion($conn);
		// On renvoit sous format json les données qu'on va récup via ajax
		echo json_encode($tabTour);
	}
?>