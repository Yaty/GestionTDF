<?php
	require "fonc_oracle.php";
	// On récupère les épreuves d'un coureur à une année
	if(isset($_GET["annee"]) && !empty($_GET["annee"]) && isset($_GET["ncoureur"]) && !empty($_GET["ncoureur"])) {
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$reqEpreuves = 'select n_epreuve, ville_d, ville_a, distance, moyenne, jour, cat_code, total_seconde, rang_arrivee from tdf_epreuve join tdf_temps using(n_epreuve) where n_coureur = ' . $_GET["ncoureur"] . ' and tdf_epreuve.annee = ' . $_GET["annee"] ;
		$curEpreuves = PreparerRequete($conn, $reqEpreuves);
		$res = ExecuterRequete($curEpreuves);
		$nbEpreuves = LireDonnees1($curEpreuves,$tabEpreuves);
		FermerConnexion($conn);
		echo json_encode($tabEpreuves);
	} else {
		echo json_encode("Erreur");
	}
?>