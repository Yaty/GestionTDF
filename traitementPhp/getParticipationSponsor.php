<?php
	require "fonc_oracle.php";
	// On récupère les participations d'un sponsor
	if(isset($_GET["nequipe"]) && !empty($_GET["nequipe"]) && isset($_GET["nsponsor"]) && !empty($_GET["nsponsor"])) {
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');// using(n_equipe, n_sponsor, annee)
		$reqCount = 'select tdf_parti_equipe.annee, (select nom from tdf_directeur where n_directeur = n_pre_directeur) as nom_dir, (select prenom from tdf_directeur where n_directeur = n_pre_directeur) as prenom_dir, (select nom from tdf_directeur where n_directeur = n_co_directeur) as nom_co, (select prenom from tdf_directeur where n_directeur = n_co_directeur) as prenom_co, numero_ordre, score from tdf_parti_equipe join tdf_ordrequi on tdf_parti_equipe.n_equipe = tdf_ordrequi.n_equipe and tdf_parti_equipe.n_sponsor = tdf_ordrequi.n_sponsor and tdf_parti_equipe.annee = tdf_ordrequi.annee where tdf_parti_equipe.n_equipe = ' . $_GET["nequipe"] . ' and tdf_parti_equipe.n_sponsor = ' . $_GET["nsponsor"]; 
		$curCount = PreparerRequete($conn, $reqCount);
		$res = ExecuterRequete($curCount);
		$nbCount = LireDonnees1($curCount,$tabCount);
		FermerConnexion($conn);
		echo json_encode($tabCount);
	} else {
		echo json_encode("Erreur");
	}
?>