<?php
	require "fonc_oracle.php";
	// On récupère le classement d'une année
	if(isset($_GET["annee"]) && !empty($_GET["annee"])) {
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$table = 'TDF_CLASSEMENT_' . $_GET["annee"];
		$reqCount = 'select * from ' . $table ;
		$curCount = PreparerRequete($conn, $reqCount);
		$res = ExecuterRequete($curCount);
		$nbCount = LireDonnees1($curCount,$tabCount);
		FermerConnexion($conn);
		echo json_encode($tabCount);
	} else {
		echo json_encode("Erreur");
	}
?>