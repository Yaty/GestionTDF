<?php
	require "fonc_oracle.php";
	$coureurSupprime = false;
	if(isset($_GET['ncoureur']) && !empty($_GET['ncoureur'])) {
		// On lance une requete pour lister les coureurs supprimables (qui n'ont pas de participation)
		$conn = OuvrirConnexion('ETU2_64', 'ETU2_64', 'info');
		$reqCoureur = 'select n_coureur from tdf_coureur where tdf_coureur.n_coureur not in (select n_coureur from tdf_parti_coureur)';
		$curCoureur = PreparerRequete($conn, $reqCoureur);
		$res = ExecuterRequete($curCoureur);
		$nbCoureur = LireDonnees1($curCoureur,$tabCoureur);
		for($i=0;$i<$nbCoureur;$i++){
		// S'il est supprimable on le supprime de toute les tables
			if($_GET['ncoureur'] === $tabCoureur[$i]['N_COUREUR']) {
				$reqSupprCoureur1 = 'DELETE FROM tdf_parti_coureur WHERE N_COUREUR=' . $_GET['ncoureur'];
				$curSupprCoureur1 = PreparerRequete($conn, $reqSupprCoureur1);
				$reqSupprCoureur2 = 'DELETE FROM tdf_temps_difference WHERE N_COUREUR=' . $_GET['ncoureur'];
				$curSupprCoureur2 = PreparerRequete($conn, $reqSupprCoureur2);
				$reqSupprCoureur3 = 'DELETE FROM tdf_abandon WHERE N_COUREUR=' . $_GET['ncoureur'];
				$curSupprCoureur3 = PreparerRequete($conn, $reqSupprCoureur3);
				$reqSupprCoureur4 = 'DELETE FROM tdf_temps WHERE N_COUREUR=' . $_GET['ncoureur'];
				$curSupprCoureur4 = PreparerRequete($conn, $reqSupprCoureur4);
				$reqSupprCoureur5 = 'DELETE FROM tdf_coureur WHERE N_COUREUR=' . $_GET['ncoureur'];
				$curSupprCoureur5 = PreparerRequete($conn, $reqSupprCoureur5);
				$reqSupprCoureur6 = 'commit';
				$curSupprCoureur6 = PreparerRequete($conn, $reqSupprCoureur6);
				ExecuterRequete($curSupprCoureur1);
				ExecuterRequete($curSupprCoureur2);
				ExecuterRequete($curSupprCoureur3);
				ExecuterRequete($curSupprCoureur4);
				ExecuterRequete($curSupprCoureur5);
				ExecuterRequete($curSupprCoureur6);
				$coureurSupprime = true;
				break;
			}
		}
	}
	
	if($coureurSupprime) {
		echo "1"; // succès
	} else {
		echo "2"; // echec
	}
?>
