<?php
	require "fonc_oracle.php";
	// On récupère les épreuves d'un coureur d'un tour
	if(isset($_GET["nepreuve"]) && !empty($_GET["nepreuve"]) && isset($_GET["annee"]) && !empty($_GET["annee"])) {
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$reqCount = "select nom, prenom, total_seconde, rang_arrivee from tdf_temps join tdf_coureur using (n_coureur) where n_epreuve = " . $_GET["nepreuve"] . " and annee = " . $_GET["annee"];     
		$curCount = PreparerRequete($conn, $reqCount);
		$res = ExecuterRequete($curCount);
		$nbCount = LireDonnees1($curCount,$tabCount);
		FermerConnexion($conn);
		echo json_encode($tabCount);
	} else {
		echo json_encode("Erreur");
	}
?>