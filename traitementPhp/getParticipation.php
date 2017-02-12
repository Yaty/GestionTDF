<?php
	require "fonc_oracle.php";
	// On récupère les participations d'un coureur
	if(isset($_GET['ncoureur']) && !empty($_GET['ncoureur'])) {
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$reqCount = 'select N_COUREUR, ANNEE, NOM, N_DOSSARD from tdf_parti_coureur join tdf_sponsor using(n_equipe, n_sponsor) where n_coureur =' . $_GET['ncoureur'];
		$curCount = PreparerRequete($conn, $reqCount);
		$res = ExecuterRequete($curCount);
		$nbCount = LireDonnees1($curCount,$tabCount);
		FermerConnexion($conn);
		echo json_encode($tabCount);
	} else {
		echo json_encode("Erreur, ncoureur inexistant");
	}
?>
